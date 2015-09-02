$(function(){
	(function($){
		
		$.widget('video.video_play',{
			options : {
			},
			_init : function(){
				this._on({
					'click .play-button' : '_playvideo',
					'click .vedio-back-close' : '_closeBox'
				})
			},
			
			_playvideo : function(event){
				var self = $(event.currentTarget),
					url = self.data('url'),
					offset = self.offset(),
					box = this.element.find('.video-box' );
				box.removeClass( 'video-show' );
				box.html('');
				var info = { video_url : url };
				$( '#vedio-tpl' ).tmpl(info).prependTo( box );
				box.addClass( 'video-show' ).attr({'_type':'m_video'});
			},
			
			_closeBox : function(){
				this._closeVideo();
			},
			
			_closeVideo : function(){
				var op = this.options,
					box = $('.video-box');
				box.removeClass( 'video-show');
				setTimeout(function(){
					box.html('');
				},500)
			}
		});	
	})($);
	$('.feedback-attach').video_play();
});