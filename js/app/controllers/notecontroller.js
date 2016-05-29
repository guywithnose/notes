/**
 * Copyright (c) 2013, Bernhard Posselt <dev@bernhard-posselt.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING file.
 */

app.controller('NoteController', function($routeParams, $scope, NotesModel,
                                          SaveQueue, note) {
    'use strict';

    NotesModel.updateIfExists(note);

    $scope.note = NotesModel.get($routeParams.noteId);

    $scope.isSaving = function () {
        return SaveQueue.isSaving();
    };

    $scope.updateTitle = function () {
        try {
            var jsonData = JSON.parse($scope.note.content);
            if (jsonData.title) {
                $scope.note.title = jsonData.title;
                return;
            }
        } catch (e) {
        }

        $scope.note.title = $scope.note.content.split('\n')[0] ||
            t('notes', 'New note');
    };

    $scope.save = function() {
        var note = $scope.note;
        SaveQueue.add(note);
    };

});
