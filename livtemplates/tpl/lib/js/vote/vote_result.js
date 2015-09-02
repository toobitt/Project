$(function(){
	(function($){
		$.widget('vote.vote_result_detail',{
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
					box = this.element.find('.resultsdetail-list'),
				    problem_id = box.attr('_id');
				var info = {};
				var	url =  'run.php?mid='+ gMid +'&id='+ problem_id +'&a=show_detail_result';
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
				var obj = this.element.find('.resultsdetail-list');
				if(data){
					var info = {};
					obj.empty();
					info.option = data;
					$('#detail-tpl').tmpl(info).appendTo(obj);
				}else{
					obj.html('<li style="text-align: center;font-size: 18px;color:#5c99d0">没有找到你要的内容</li>');
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
	$('.result-vote').vote_result_detail();
});