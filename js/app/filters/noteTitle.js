/**
 * removes whitespaces and leading #
 */
app.filter('noteTitle', function () {
	'use strict';
	return function (value) {
		try {
			var jsonData = JSON.parse(value);
			if (jsonData.title) {
				return jsonData.title;
			}
		} catch (e) {
		}

		value = value.split('\n')[0] || 'newNote';
		return value.trim().replace(/^#+/g, '');
	};
});
