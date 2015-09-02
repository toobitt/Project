$(function(){
	(function($){
		$.widget('albums.albums_sub',{
			options : {
			},
			
			create : function(){
			},
			
			_init : function(){
				this._on({
					'click .common-list-pub-close' : '_close',
				})
				this._submit();
			},
			
			_close : function(){
				this.element.css({'top':-500 ,'left':100});
			},
			
			_submit : function(){
				var	sform = this.element.find('.move-form'),
					_this = this;
					sform.submit(function(){
						var submit_btn = sform.find('.publish-box-save');
						var val = sform.find('.publish-hidden').val();
						sform.ajaxSubmit({
							beforeSubmit:function(){
								var tip = '';
								if(!val){
									tip = '请先选择分类';
								}
								if(tip){
									submit_btn.myTip({
										string : tip,
										dleft : 150,
										color : '#6EA5E8'
									});
									return false;
								}
							},
							dataType : 'json',
							success:function( data ){
								if(data == 0){
									submit_btn.myTip({
										string : '此相册已在此分类下',
										dleft : 150,
										color : '#6EA5E8'
									});
									return false;
								}else{
									_this._close();
								}
							},
						});
						return false;
					});
			},
		})
		
	})($);
	$('.common-list-ajax-pub').albums_sub();
})