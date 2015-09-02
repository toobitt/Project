define(function(require, exports, modules){
	var browser = function( app, dom, label ){
		var photos = [], browser = {};
		dom.find('img').each(function( i ){
			$(this).attr('_index', i);
			photos.push( $(this).attr('src') );
		});	
		if( photos.length ){
			browser[label] = app.photoBrowser({
				photos : photos,
				theme:'dark',
				ofText : '/',
				toolbar : photos.length > 1 ? true : false,
			});
			dom.on('click', 'img', function(){
				var index = $(this).attr('_index');
				browser[label].open( index );
			});
		}
	};
	modules.exports = browser;
});
