$(function(){
	(function($){
		$.widget('survey.survey_cite',{
			options : {
				
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'click .cite-survey-box li' : '_getcite',
					'click .survey-search' : '_search',
					'click .cite-list li' : '_citelist'
				});
				this._submit();
				this._initpage();
			},
			
			_initpage : function(){
				this._getlist();
			},
			
			_getlist : function( page ){
				var	url =  'run.php?mid='+ gMid +'&a=cite_search&cite=1';
				var _this = this,
					box = this.element.find('.cite-survey-box'),
					k = $.trim(this.element.find('.search-input').val()),
					nid = this.element.find('.cite-tag').attr('_id');
				var info = {};
				if(page){
					info.page = page
				}else{
					info.page = 1
				}
				info.k = k ;
				info.node_id = nid; 
				$.globalAjax( box, function(){
					return $.getJSON( url, info, function(data){
						_this._getInfo( data[0].info );
						_this._getMenu( data[0].page_info );
					});
				});
			},
			
			_getInfo : function(data){
				var obj = this.element.find('.cite-survey-box');
				if(data[0]){
					var info = {};
					obj.empty();
					info.option = data;
					$('#cite-tpl').tmpl(info).appendTo(obj);
				}else{
					obj.html('<li style="text-align: center;font-size: 18px;color:#5c99d0">没有找到你要的内容</li>');
				}
			},
			
			_getMenu : function(option){
				var page_box = this.element.find('.page_size'),
					_this = this;
				//option.show_all = true;
				if(page_box.data('init')){
					page_box.page('refresh',option);
				}else{
					option['page'] = function( event, page, page_num){
						_this._refresh( page );
					}
					page_box.page( option );
					this.page_num = option.page_num;
					page_box.data('init', true);
				}
			},
			
			_refresh : function( page ){
				this._getlist( page );
			},
			
			_getcite : function(event){
				var self = $(event.currentTarget),
					_this = this,
					obj = this.element.find('.info'),
					data = {
						id : self.attr('_id')
					},
					url = 'run.php?mid='+ gMid +'&a=survey_cite';
				$.globalAjax(obj, function(){
			        return $.getJSON(url,data,function(json){
			        			_this._getinfo(json[0].problems);
			        	   });
			    });
			},
			
			_getinfo : function(data){
				var obj = this.element.find('.info');
				obj.empty();
				if(data){
					$.MC.section.survey_form('initinfo' , data);
				}else{
					obj.html('<div class="no-data">请编辑添加数据</div>');
				}
			},
			
			_search : function(){
				this._getlist();
			},
			
			_citelist : function(event){
				var self = $(event.currentTarget),
					txt = self.text(),
					id = self.attr('_id');
				this.element.find('.cite-tag span').text(txt);
				this.element.find('.cite-tag').attr('_id' , id);
				this._getlist();
				
			},
			
			_submit : function(){
				var sform = this.element,
					_this = this;
				sform.submit(function(){
					var savebtn = sform.find('.m2o-save');
					savebtn.prop('disabled' , true);
					$.MC.section.survey_form('initdata');
			});
			},
		});
	})($);
	$('.m2o-cite-form').survey_cite();
});