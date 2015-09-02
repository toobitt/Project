$(function(){
	(function($){
		$.widget('interact.tv_interact',{
			options : {
				
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._initpage();
			},
			
			_initpage : function(){
				this._getlist();
			},
			
			_getlist : function( page ,  page_num){
				var _this = this,
					box = this.element.find('.info-list'),
				    info_id = box.attr('_id');
				var info = {};
				var	url =  'run.php?mid='+ gMid +'&a=show_win_info_more' + '&id='+ info_id;
				if(page){
					info.page = page
				}else{
					info.page = 1
				}
				page_num ? info.page_num = page_num : 20;
				$.globalAjax( box, function(){
					return $.getJSON( url, info, function(data){
						_this._getInfo( data[0].info );
						_this._getMenu( data[0].page_info );
					});
				});
			},
			
			_getInfo : function(data){
				var obj = this.element.find('.info-list');
				if(data){
					var info = {};
					obj.empty();
					info.option = data;
					$('#info-tpl').tmpl(info).appendTo(obj);
				}else{
					this.element.find('.info').html('<h1>没有相关获奖信息！</h1>');
				}
			},
			
			_getMenu : function(option){
				var page_box = this.element.find('.page_size'),
					_this = this;
				option.show_all = true;
				if(page_box.data('init')){
					page_box.page('refresh',option);
				}else{
					option['page'] = function( event, page, page_num){
						_this._refresh( page ,  page_num);
					}
					page_box.page( option );
					this.page_num = option.page_num;
					page_box.data('init', true);
				}
			},
			
			_refresh : function( page ,  page_num ){
				this._getlist( page  ,  page_num);
			},
		});
	})($);
	$('.result-info-box').tv_interact();
});