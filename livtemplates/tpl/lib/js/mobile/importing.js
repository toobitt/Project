$(function(){
	(function($){
		$.widget('mobile.mobile',{
			_init : function(){
				var _this = this;
				this._on({
					'click .importing' : '_important',
					//'click .makefile' : '_makefile'
				});
				this.file = this.element.find('.importing-file');
				this.file.ajaxUpload({
					url : './run.php?mid='+gMid+'&a=lead_file',
					type : 'file',
					after : function( data ){
						_this._afterAjax( data );
					}
				});
			},
			_important : function( event ){
				var self = $(event.currentTarget),
					items = this.element.find('.importing');
				items.removeClass('current');
				self.addClass('current');
				this.file.click();
			},
			_afterAjax : function( data ){
				var data = data['data'];
				if(data == 'success'){
					this._showTips('导入成功');
				}else{
					alert('导入失败');
				}
			},
			/*_makefile : function( event ){
				var self = $(event.currentTarget),
					sortid = self.attr('_sortid'),
					_this = this,
					url = "./run.php?a=relate_module_show&app_uniq=mobile&mod_uniq=api&mod_a=build_api_file&sort_id=" + sortid + '&ajax=1';
				$.getJSON(url,function( data ){
					if ( data == 'success' ){
						_this._showTips( '生成成功' );
					}else{
						_this._showTips( '生成失败' );
					}
				});
			},
			_showTips : function( words ){
				var tip = $('.tips');
				tip.text(words).css({'opacity':1,'z-index':100001});
				setTimeout(function(){
					tip.css({'opacity':0,'z-index':-10});
				},1600);
			}*/
		});
	})($);
	$('body').mobile();
});