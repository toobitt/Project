$(function(){
	(function($){
		$.widget('gather.user', {
			options : {
				userdatatpl : '',
				userdatatname : 'userdata-tmpl',
				nodatatpl : '',
				nodatatname : 'nodata-tpl',
				datedatatpl : '',
				datedatatname : 'datedata-tmpl', 
				userheadtpl : '',
				userheadtname : 'userhead-tmpl',
				dateheadtpl : '',
				dateheadtname : 'datehead-tmpl',
				getMenuUrl : ''
			},
			
			_create : function(){
				var op = this.options;
				$.template(op.userdatatname, op.userdatatpl);
				$.template(op.userheadtname, op.userheadtpl);
				$.template(op.datedatatname, op.datedatatpl);
				$.template(op.dateheadtname, op.dateheadtpl);
				$.template(op.nodatatname, op.nodatatpl);
			},
			
			_init : function(){
				this._on({
					'click .m2o-each' : '_getArticle',
					'click .m2o-all' : '_getAll'
				});
				this._initleft();
			},
			
			_initleft : function(){
				this._initAjax();
				this._initStruct();
			},
			
			/*采集左侧数据*/
			_initStruct : function(){
				var AccountId = this.element.data('accountid');
				var area = this.element.find('.m2o-list');
				if(AccountId){
					$.tmpl(this.options.userheadtname).prependTo( area );
					$('.list-box').article( 'sortTip', 0);
				}else{
					this.element.addClass('date-box');
					$.tmpl(this.options.dateheadtname).prependTo( area );
					$('.list-box').article( 'sortTip', 1);
				}
			},
			
			ajaxBefore : function( obj ){
	            obj.html('<div style="text-align:center; padding:20px 0;"><img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/></div>');
	        },
			
			_initAjax : function( page ){
				var _this = this;
				var info = {};
				page ? info.page = page : '';
				this.page_num ? info.page_num = this.page_num : '';
				var box = this.element.find('.m2o-each-list');
				this.ajaxBefore( box );
				$.getJSON(this.options.getMenuUrl, info, function( data ){
					if(data['callback']){
						eval( data['callback'] );
						return;
					}else{
						var data = data || [];
						_this._getMenu( data[0] );
					}
				});
			},
			
			_getMenu : function( data ){
				this._getMenudata( data.info );
				this._getMenupage( data.page_info );
			},
			
			_getMenudata : function( data ){
				var AccountId = this.element.data('accountid');
				var box = this.element.find('.m2o-each-list');
				var title = this.element.find('.m2o-all');
				if(data.length){
					if(AccountId){
						this._initAdministrator( data );
					}else{
						this._initUsername( data );
					}
				}else{
					box.empty();
					$.tmpl(this.options.nodatatname).appendTo( box );
					$('.list-box').article( 'nodataTpl');
				}
				this._initFirst( title );
			},
			
			_initAdministrator : function( data ){
				var default_avatar = RESOURCE_URL + 'avatar.jpg';
				$.each(data, function( key, value ){
					if(value.avatar){
						value.avatarImg = $.globalImgUrl(value.avatar, '24x24');
					}else{
						value.avatarImg = default_avatar;
					}
					value.time = value.format_date;
				});
				var box = this.element.find('.m2o-each-list');
				box.empty();
				$.tmpl(this.options.userdatatname, data).appendTo( box );
			},
		
			_initUsername : function( data ){
				$.each(data, function( key, value ){
					value.time = value.format_date;
				});
				var box = this.element.find('.m2o-each-list');
				box.empty();
				$.tmpl(this.options.datedatatname, data).appendTo( box );
			},
		
			_getMenupage : function( option ){
				var page_box = this.element.find('.page_size'),
					_this = this;
				option.show_all = false;
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
				var obj = $('.aside-box').find('.m2o-each.current');
				this._initAjax( page );
			},
			
			_getAll : function( event ){
				var self = $(event.currentTarget);
				this.element.find('.m2o-each').each(function(){
					$(this).removeClass('current');
				});
				this._initFirst( self );
			},
			
			_initFirst : function( obj ){
				$('.list-box').article( 'getData', obj);
				
			},
			
			_getArticle : function( event ){
				var self = $(event.currentTarget);
				if(self.hasClass('current')){
					return;
				}else{
					self.addClass('current')
					.siblings().removeClass('current');
				}
				$('.list-box').article( 'getData', self);
			},
			
		});
		
		$.widget('gather.article', {
			options : {
				getArticleUrl : '',
				
				articledatatpl : '',
				articledatatname : 'articledata-tmpl',
				nodatatpl : '',
				nodatatname : 'nodata-tpl',
			},
			
			_create : function(){
				var op = this.options;
				$.template(op.articledatatname, op.articledatatpl);
				$.template(op.nodatatname, op.nodatatpl);
				this.status_color = ['#8ea8c8','#17b202','#f8a6a6'];
			},
			
			_init : function(){
				this._on({
					'click .gather-tip li' : '_chooseSort'
				})
			},
			
			_chooseSort : function( event ){
				var self = $( event.currentTarget ),
					sortid = self.data('sortid');
				var obj = $('.aside-box').find('.m2o-each.current');
				var obj = obj.length ? obj : $('.aside-box').find('.m2o-all');
				obj.data('sortid', sortid);
				this.getData( obj );
			},
			
			getData : function( obj, page, page_num ){
				var op = this.options,
					_this = this;
				var info = {};
				obj.data('date') && (info.format_date = obj.data('date'));
				obj.data('id') && (info.user_id = obj.data('id'));
				obj.data('sortid') && (info.sort_id  = obj.data('sortid'));
				obj.data('date') && (info.user_id = $('.aside-box').data('userid'));
				page ? info.page = page : '';
				page_num ? info.page_num = page_num : '';
				this._gethidden( info );
				var box = this.element.find('.m2o-each-list');
				$('.aside-box').user('ajaxBefore', box);
				$.globalAjax( obj, function(){
					return $.getJSON( op.getArticleUrl, info, function( data ){
						if(data['callback']){
							eval( data['callback'] );
							return;
						}else{
							var data = data || [];
							_this.getarticle( data[0] );
						}
					});
				});
			},
			
			_gethidden : function( info ){
				 var form = $('#searchform');
				 form.find('input[name="user_id"]').val(info.user_id);
				 form.find('input[name="format_date"]').val(info.format_date);
			},
			
			getarticle : function( data ){
				this._handlearticle( data.info );
				this._option( data.info );
				this._ajaxPage( data.page_info );
				this._addevent();
			},
			
			_handlearticle : function( data ){
				var box = this.element.find('.m2o-each-list');
				var _this = this;
				var articledata = [];
				this.clearData( box );
				if(data.length){
					$.each(data, function(key, value){
						var status_color = _this.status_color[value.status];
						var pubSet = value.set_id ? _this._setSort( value ) : '';
						articledata.push({
							title : value.title,
							id : value.id,
							order_id : value.order_id,
							indexpic : value.indexpic_url,
							sort_name : value.sort_name,
							sourcelist : pubSet.sourcelist,
							source_class : pubSet.setClass,
							status_name : value.status_name,
							status : value.status,
							status_color : status_color,
							user_name : value.user_name,
							create_time : value.create_time,
						});
					});
					$.tmpl(this.options.articledatatname, articledata).appendTo( box );
				}else{
					this.nodataTpl();
				}
			},
			
			nodataTpl : function(){
				var box = this.element.find('.m2o-each-list');
				$.tmpl(this.options.nodatatname).appendTo( box );
			},
			
			sortTip : function( type ){
				var handle = type ? 'show' : 'hide';
				this.element.find('.gather-tip')[handle]();
			},
			
			_setSort : function( value ){
				var setId = value.set_id;
				var pubSet = {}, pubdata = [];
				$.each(setId, function(i, n){
					if(value.set_url){
						var url_keys = [];
						$.each(value.set_url, function(j, m){
							url_keys.push(j);
						});
						if($.inArray(i, url_keys) == -1){
							pubdata.push({
								source : n
							});
							pubSet.setClass = '';
						}else{
							pubdata.push({
								source : n
							});
							pubSet.setClass = 'm2o-pre-gather'
						}
					}else{
						pubdata.push({
							source : n
						});
						pubSet.setClass = '';
					}
				});
				pubSet.sourcelist = pubdata;
				return pubSet;
			},
			
			_option : function( data ){
				$.extend($.geach || ($.geach = {}), {
			        data : function(id){
			            var info;
			            $.each(data, function(i, n){
			               if(n['id'] == id){
			                   info = {
			                       id : n['id']
			                   }
			                   return false;
			               }
			            });
			            return info;
			        }
			    });
			},
			
			_ajaxPage : function( option ){
				var page_box = this.element.find('.page_size'),
					_this = this;
				if(page_box.data('init')){
					page_box.page('refresh',option);
				}else{
					option['page'] = function( event, page, page_num ){
						_this.refresh(page, page_num);
					}
					page_box.page( option );
					page_box.data('init', true);
				}
			},
			
			refresh : function( page, page_num ){
				var obj = $('.aside-box').find('.m2o-each.current');
				this.getData( obj, page, page_num );
			},
			
			_addevent : function(){
				this.element.find('.m2o-each').geach();
				this.element.find('.m2o-list').glist();
			},
			
			clearData : function( obj ){
				obj.empty();
			},
		});
	})($);
	$('.list-box').article({
		articledatatpl : $('#articledata-tmpl').html(),
		getArticleUrl : './run.php?mid='+ gMid + '&a=get_content&ajax=1'
	});
	$('.aside-box').user({
		userdatatpl : $('#userdata-tmpl').html(),
		userheadtpl : $('#userhead-tmpl').html(),
		datedatatpl : $('#datedata-tmpl').html(),
		dateheadtpl : $('#datehead-tmpl').html(),
		nodatatpl : $('#nodata-tpl').html(),
		getMenuUrl : './run.php?mid='+ gMid + '&a=get_menu&ajax=1'
	});
	var searchForm = $('#searchform');
	searchForm.submit(function(){
		$(this).ajaxSubmit({
			beforeSubmit : function(){
				
			},
			dataType : 'json',
			success : function( data ){
				$('.list-box').article( 'getarticle', data[0]);
			}
		});
		return false;
	});
});








