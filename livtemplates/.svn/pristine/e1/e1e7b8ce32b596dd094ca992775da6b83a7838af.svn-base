seajs.use(['$', JS_PATH + 'act/Tag.js'], function ($, Tag) {
	$(function ($) {
		var need = $('input[need-tags=true]');
		if (!need.size()) return;
		need.each(function () {
			var tag = new Tag({input: this, el: $($('#template_tags').html())});
		});
	});
})