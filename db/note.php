<?php
/**
 * Nextcloud - Notes
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Bernhard Posselt <dev@bernhard-posselt.com>
 * @copyright Bernhard Posselt 2012, 2014
 */

namespace OCA\Notes\Db;

use OCP\Files\File;
use OCP\Files\Folder;
use OCP\AppFramework\Db\Entity;

/**
 * Class Note
 * @method integer getId()
 * @method void setId(integer $value)
 * @method string getEtag()
 * @method void setEtag(string $value)
 * @method integer getModified()
 * @method void setModified(integer $value)
 * @method string getTitle()
 * @method void setTitle(string $value)
 * @method string getCategory()
 * @method void setCategory(string $value)
 * @method string getContent()
 * @method void setContent(string $value)
 * @method boolean getFavorite()
 * @method void setFavorite(boolean $value)
 * @package OCA\Notes\Db
 */
class Note extends Entity {

    public $etag;
    public $modified;
    public $title;
    public $category;
    public $content;
    public $favorite = false;

    public function __construct() {
        $this->addType('modified', 'integer');
        $this->addType('favorite', 'boolean');
    }

    /**
     * @param File $file
     * @return static
     */
    public static function fromFile(File $file, Folder $notesFolder, $tags=[]){
        $note = new static();
        $note->setId($file->getId());
        $note->setContent($file->getContent());
        $note->setModified($file->getMTime());
        $note->setTitle(pathinfo($file->getName(),PATHINFO_FILENAME)); // remove extension
        $subdir = substr(dirname($file->getPath()), strlen($notesFolder->getPath())+1);
        $note->setCategory($subdir ? $subdir : null);
        if(is_array($tags) && in_array(\OC\Tags::TAG_FAVORITE, $tags)) {
            $note->setFavorite(true);
            //unset($tags[array_search(\OC\Tags::TAG_FAVORITE, $tags)]);
        }
        $note->updateETag();
        $note->resetUpdatedFields();
        return $note;
    }

    private function updateETag() {
        $this->setEtag(md5($this->title.$this->content.$this->modified.$this->favorite.$this->category));
    }
}
