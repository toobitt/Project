$(function ($) {
	var _ = window._,
		View = window.Backbone.View,
		Model = window.Backbone.Model,
		Collection = window.Backbone.Collection;
	
	var List = Collection.extend({
		//向上增长，获取新的
		rise: function () {
			if ( this.rising ) return;
			
		},
		//向下增长，获取老的
		increment: function () {
			
		}
	});
	
	
	
});