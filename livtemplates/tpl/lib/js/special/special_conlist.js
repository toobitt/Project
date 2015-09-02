jQuery( function(){
	$.MC = {
		pop_module : $('.special-modules-body'),
		conList : $('.special-con-area'),
		conForm : $('.special-content-form'),
		nav : $('.m2o-nav-box')
	};
} );
;(function($){
	var nav_info = {
		item_tpl : '' + 
				 '<li class="{{if is_last > 0}}no-child{{else}}{{/if}}" data-id="${id}" data-name="${name}" data-fid="${id}">' + 
				 '{{if !(is_last > 0)}}' + 
				    '<span class="hook"></span>' +
				 '{{/if}}' +
				    '<span class="title">${name}</span>' +
				'</li>' +
				'',
		css : '' +
			'.m2o-nav-list{padding:10px;}' +
			'.m2o-nav-list ul{padding-left:15px; margin-top:1px; }' +
			'.m2o-nav-list li{line-height: 30px; background:url(' + RESOURCE_URL+ 'publishsys/left-list.png) no-repeat left 7px; margin-bottom:1px; position:relative;}' +
			'.m2o-nav-list li.no-child{background-image:none;}' + 
			'.m2o-nav-list li > .title{cursor:pointer;display:block; height:28px;overflow:hidden;font-size:14px; margin-left:14px; text-indent:5px;}' +
			'.m2o-nav-list li.hasset > .title{color:#96c7ef;}' +
			'.m2o-nav-list li >.title:hover, .temp-nav li > .title.on{background-color:#545454; color:#fff;}' +
			'.m2o-nav-list li > .title.selected {background-color:#5c99cf; color:#fff;}' +
			'.m2o-nav-list li > .title:hover a, .temp-nav li > .title.on a{opacity:1;cursor:pointer; -webkit-transition: opacity .3s ease-in; -moz-transition: opacity .3s ease-in; transition: opacity .3s ease-in;}' +
			'.m2o-nav-list li.stretch-list{background:url(' + RESOURCE_URL+ 'publishsys/drop-list.png) no-repeat left 7px;}' +
			'.m2o-nav-list .hook{position:absolute;left:0px;top:0px;height:30px;width:14px;} ' +
			'',
		cssInited : false
 	};
	$.widget('m2o.nav', {
        options : {
        	url : './fetch_column.php'
        },
        
        _create :function(){
        	this.column_list = this.element.find('.column-list').find('ul');
        },
        
        _template : function( tname, tpl, dataInfo, container, data ){
        	$.template( tname, tpl );
        	$.tmpl( tname, data ).appendTo( container );
        	if( !dataInfo.cssInited && dataInfo.css ){
        		dataInfo.cssInited = true;
        		this._addCss( dataInfo.css );
        	}
        },
        
        _addCss : function(css){
            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
        },
        
        _init : function(){
        	$.template(this.options.tname, this.options.tpl);
            this._on({
                'click .hook' : '_stretch',
                'click .title' : '_selected'	//刷新右侧列表
            });
            this._root();
        },
        _root : function(){
        	var site_id = this._getSiteid();
            this._ajax( site_id, 0, this.column_list );
    		this._initSelect();
        },
        
        _getSiteid : function(){
        	var site_id =  $.MC.pop_module.special_con_form( 'getSiteid' );
        	return site_id;
        },
        
        _ajax : function( site_id, fid, parent ){
            var _this = this;
            var url = this.options.url;
            $.globalAjax( parent, function(){
               return $.getJSON( url, { siteid : site_id, fid : fid }, function( json ){
            		var data = json;
            		_this._template('child-tpl',nav_info['item_tpl'],nav_info, parent.empty(), data );
                } );
            } );
        },
        
        _initSelect : function(){
        	this.element.find('.title').eq( 0 ).addClass('selected');
        },
        
        _stretch : function(event){
            var item = $(event.currentTarget).closest('li');
            var cname = 'stretch-list';
            if(item.hasClass(cname)){
                item.removeClass(cname);
                item.find('ul').hide();
            }else{
                item.addClass(cname);
                if( item.data('ajax') ){
                    item.find('ul').show();
                }else{
                	var fid = item.data('id'),
                		site_id = this._getSiteid();
                    item.data('ajax', true);
                    this._appendBox(item);
                    this._ajax( site_id, fid, item.find('ul') );
                }
            }
        },

        _appendBox : function(parent){
            $('<ul><li class="no-child"><img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/></li></ul>').appendTo(parent);
        },
        _selected : function(event){
            var self = $(event.currentTarget);
            var	item = self.closest('li'),
            	all = this.element.find('.title'),
            	id = item.data('id'),
            	cname = 'selected';
            all.removeClass( cname );
            self.addClass( cname );
            $.MC.pop_module.special_con_form( 'refresh', id ); 
        },

        refresh : function( site_id, fid ){
        	var fid = fid || 0,
        		site_id = this._getSiteid();
        	this._ajax( site_id, fid, this.column_list.empty() );
    		this._initSelect();
        }
    });
	
})(jQuery);


jQuery(function($){
	var gFlag=false;
	(function($){
		var info = {
			template : '' +
						'<select>' +
							'{{each pages}}' +
							'<option value="${$value}" {{if $item.page_num == $value}}selected{{/if}}>每页${$value}条</option>' +
							'{{/each}}' +
						'</select>' + 
						''
		};
		$.widget('special.page',{
		options:{
			total_page:0,
			total_num:0,
			page_num:0,
			current_page:0,
			page_num_list : [20,40,60]
		},
		_create:function(){
			this.page_tpl_name = 'page_tpl_name';
			$.template( this.page_tpl_name, info.template );
		},
		_init:function(){
			this._on({
                'click span[_page]' : '_click',
                'change select' : '_change'
            });
			this._createPage();
		},
		_createPage:function(){
			var options=this.options;
			total_page=options.total_page;
			total_num=options.total_num;
			page_num=options.page_num;
			current_page=options.current_page;
			if(total_page < 2){
                this.element.hide();
                return;
            }
            var html = '';
            html += '<span class="page_all">共' + options['total_page'] + '页/计' + options['total_num'] + '条</span>';
            html += '<span class="numbers-box"></span>';
            if(current_page > 1){
                html += '<span class="page-prev" _page="1"><a>|<</a></span>';
                html += '<span class="page-prev" _page="' + (current_page - 1) + '"><a><<</a></span>';
            }
            $.each([-2, -1, 0, 1, 2], function(i, n){
                var check = false;
                var val = current_page + n;
                if(n < 0){
                    if(val > 0){
                        check = true;
                    }
                }else if(n > 0){
                    if(val <= total_page){
                        check = true;
                    }
                }
                if(check){
                    html += '<span class="page-code" _page="' + val + '"><a>' + val + '</a></span>';
                }
                if(n == 0){
                    html += '<span class="page_cur">' + current_page + '</span>';
                }
            });
            if(current_page < total_page){
                html += '<span class="page-next" _page="' + (current_page + 1) + '"><a>>></a></span>';
                html += '<span class="page-next" _page="' + total_page + '"><a>>|</a></span>';
            }
            this.element.html(html);
            this._createNumbers();
		},
		_createNumbers : function(){
        	var op = this.options,
        		page_numbers = {},
        		page_numbers_box = this.element.find( '.numbers-box' );
            page_numbers['pages'] = op.page_num_list;
            $.tmpl( this.page_tpl_name, page_numbers, {
            	page_num : op.page_num
            } ).appendTo( page_numbers_box );
		},
		_change : function( event ){
			var self = $( event.currentTarget ),
				value = self.val(),
				current_page = this.element.find( '.page_cur' ).text();
			this._trigger('page', null, [current_page,value]);
		},
		
		_click : function(event){
            var page = $(event.currentTarget).attr('_page'),
            	page_num = this.element.find('select').val();
            this._trigger('page', null, [page,page_num]);
        },
        show : function(){
            this.element.show();
        },
        hide : function(){
            this.element.hide();
        },
        refresh : function(option){
            this.show();
            $.extend(this.options, option);
            this._createPage();
        }
	});
		$.widget('special.special_con_form',{
			options:{
				hg_search:'.choice-area',
				title : '#con-title',
				con_form : '#content-form',
				result_tip : '.result-tip',
				search :'#modules-search',
				speciallist : '#select-conlist',
				specialcon_tpl : '#selectcon_list',
				special_modules_list:'.special-content-list',
				special_modules_form:'.special-content-form',
				define_add : '.define-add',
				add_btn:'.addBtn',
				batch_add:'.batch-add',
				item:'.item',
				modules_searchform:'#modules_searchform',
				modules_searlist:'.modules-searlist',
				page_area:'.page_area',
				closeButton : '.special-close',
				loading: '.modules-loading'
			},
			_create:function(){
				this.root=this.element;
				this.pageBox=this.element.find(this.options['page_area']);
				this.speid=this.element.data('id');
				this.loading=this.element.find(this.options['loading']);
				$.MC.nav.nav();
			},
			_init:function(){
				var _this=this;
				$('#searchform').submit(function(){
					var id=$('#column_id').val();
					if($('#column_id').data('all')){
						gFlag=true;
						$('.column-all').trigger('click');
					}else{
						$('#'+id).trigger('click');

					}
					return false;
				});
				this._ajaxForm();
				var handlers={};
				handlers['click '+this.options['search']] ='_searchList';
				handlers['click '+this.options['add_btn']] ='_addList';
				handlers['click '+this.options['batch_add']] ='_bathaddList';
				handlers['click '+this.options['item']] ='_showForm';
				handlers['submit '+this.options['modules_searchform']] ='_queryForm';
				this._on(handlers);
			},
			_initPage:function(option){
				var _this=this;
				var pageBox=this.pageBox;
				if(pageBox.data('type')){
					pageBox.page('refresh', option);
	                return;
				}
				option['page']=function(event,val,count){
	                _this.pageVal = val;
	                _this.count = count;
	                _this._setCheck();
	                _this._searchAjaxList();
				}
			    pageBox.page(option);
			    pageBox.data('type',true);
				_this.root.find('.common-list-bottom').append(this.pageBox[0]);
			},
			_setCheck:function(){
				var check = this.element.find('input[name="checkall"]').attr('checked',false);
			},
			_showForm:function(event){
				var self=$(event.currentTarget),
				    id=self.attr('_id'),
				    _value=self.text();
				var parent=self.closest(this.options['modules_searlist']),
				    special_area=$(this.options['special_modules_list']);
				parent.find('.current').text(_value);
				if(id==1){
					parent.siblings().hide();
					special_area.hide();
					$(this.options['special_modules_form']).show();
				}else if(id=2){
					parent.siblings().show();
					$(this.options['special_modules_form']).hide();
					special_area.show();
					special_area.find('.common-list').hide();
					this._resetForm();
					$(this.options['search']).trigger('click');
				}

			},
			_resetForm:function(){
				var form=$(this.options['modules_searchform']),
					site_name = form.find( '#site' ).find( 'li' ).eq(0).text();
				form.find('.search-k').val('');
				form.find('input[name="user_name"]').val('');
				form.find('input[name="special_modules"]').val('0');
				form.find('input[name="site_id"]').val('1');
				form.find('input[name="special_date_search"]').val('1');
				form.find('input[name="start_time"]').val('');
				form.find('input[name="end_time"]').val('');
				form.find('.input').hide();
				form.find('input[name="start_weight"]').val('0');
			    form.find('input[name="end_weight"]').val('100');
			    form.find('#display_modules').text('全部');
			    form.find('#display_special_data_time').text('所有时间段');
			    //form.find('#display_site').text( site_name );
			    form.find('.weightPicker #display_colonm_show').text('所有权重');
			},
			_ajaxForm:function(){
				var _this=this,
				    form=$(_this.options['con_form']);
				form.submit(function(){
					var value=$.trim($(_this.options['title']).val());
					if(!value){
						alert('标题不能为空');
						return false;
					}
					$(this).ajaxSubmit({
						dataType : 'json',
						beforeSubmit:function(){
							$('.modules-loading').show();
						},
						success:function(data){
							var data=data[0] || {},
							    obj=$(_this.options['result_tip']);
							$('.modules-loading').hide();
							if(data.success){
								var tip="专题内容保存成功";
								_this._ajaxTip(obj, tip);
								var id=$('#column_id').val();
								_this.clearConditions();   //清空搜索条件
								$('#'+id).trigger('click');
								setTimeout(function(){
									$(_this.options['closeButton']).trigger('click');
								},1000);
								
							}else{
								var tip=data.error;
								_this._ajaxTip(obj, tip);
							}
						}
					});
					return false;
				});
			},
			clearConditions:function(){
			     var form=$('.special-con-area').find('form[name="searchform"]');
			     form.find('#search_list_key').val('');
			     form.find('input[name="user_name"]').val('');
			     form.find('input[name="state"]').val('1');
			     form.find('input[name="date_search"]').val('1');
			     form.find('input[name="start_time"]').val('');
				 form.find('input[name="end_time"]').val('');
				 form.find('.input,#go_date').hide();
			     form.find('input[name="start_weight"]').val('0');
			     form.find('input[name="end_weight"]').val('100');
			     form.find('#display_status_show').text('全部状态');
			     form.find('#display_colonm_show').text('所有时间段');
			     form.find('.weightPicker #display_colonm_show').text('所有权重');
			},
			_ajaxTip:function(obj,tip){
				obj.html(tip).css({'opacity':1,'z-index':1000});
				setTimeout(function(){
					obj.css({'opacity':0,'z-index':-1});
				},1000);
			},
			getSiteid : function(){
				var site_id = this.element.find( this.options['modules_searchform'] ).find( 'input[name="site_id"]' ).val();
				return site_id;
			},
			refresh : function( fid ){
				this._searchAjaxList( fid );
			},
			_searchList:function( event ){
				this._searchAjaxList();
			},
			_searchAjaxList : function( fid ){
				this.column_id=$('#column_id').val();
				var _this=this,
				    url = './run.php?mid=' + gMid + '&a=query',
				    fid = fid || 0,
				    info = $(this.options['modules_searchform']).serializeArray();
				if( !fid ){
					fid =  $.MC.nav.find('.title.selected').closest('li').data('fid');
				}
				$.ajax({
					url:url,
					type:'POST',
					data:{
						column_id:_this.column_id,
						speid:_this.speid,
						info:info,
						page:_this.pageVal,
						offset : _this.count,
						column_id : fid
					},
					dataType:'json',
					beforeSend:function(){
						$(_this.options['loading']).show();
					},
					success:function(data){
						_this.pageVal = 1;
						var data=data[0] || {},
						    info=data['info'],
						    pageInfo=data['page_info'];
						    /*items=data[id];*/
						$(_this.options['loading']).hide();
						$(_this.options['special_modules_list']).show();
						$(_this.options['special_modules_form']).hide();
						_this.element.find('.none_tip').remove();
						if(!info){
							$(_this.options['special_modules_list']).find('.common-list').hide();
							$(_this.options['speciallist']).html('');
							$('<span class="none_tip">暂无内容</span>').appendTo( _this.element.find('.m2o-list-box') );
						}else{
							$(_this.options['special_modules_list']).find('.common-list').show();
							$(_this.options['speciallist']).html('');
							$.each(info,function(key,value){
								var id=value['id'],
								    info=$.parseJSON(value['pic']);
								if(info){
									value['url']=info['host']+info['dir']+'40x30/'+info['filepath']+info['filename'];
									value['host']=info['host'];
								}
								$(_this.options['specialcon_tpl']).tmpl(value).appendTo(_this.options['speciallist']);
								$('#info'+id).data('value',value);
								/*var quanzhong=$('.common-list-data[name="'+id+'"]').find('.common-quanzhong'),
							    _weight=quanzhong.attr('_weight'),
							    _value=create_rgb_color(_weight);
							    quanzhong.css({'background':_value});*/
							});
							_this._initPage(pageInfo);
						}
					}
				});
			},
			_queryForm:function(event){
				var site_id = this.getSiteid();
				this.site_id = this.site_id || '';
				if( this.site_id != site_id ){
					$.MC.nav.nav( 'refresh', site_id );
					this.site_id = site_id;
				}
				$(this.options['search']).trigger('click');
				return false;
				   
			},
			_selectAjax:function(data){
				var _this=this,
					title = data['title'];
			    url = './run.php?mid=' + gMid + '&a=select';
				$.ajax({
					url:url,
					type:'POST',
					data:data,
					dataType:'json',
					beforeSend:function(){
						$(_this.options['loading']).show();
					},
					success:function(data){
						$(_this.options['loading']).hide();
						var data=data[0] || {},
				            obj=$(_this.options['result_tip']);
						var tip='';
						if(data.success){
							tip+=data.success+'<br/>';
							_this.clearConditions();   //清空搜索条件
							$('#'+_this.column_id).trigger('click');
						}
						if(data.con_error){
							tip+='<span class="error-box">'+data.con_error+'</span><span class="error-box">已添加过</span>';
						}
						if(data.error){
							tip+='<span class="error-box">'+data.error+'</span>';
						}
						_this._ajaxTip(obj, tip);
					}
				});
			},
			_addList:function(event){
				this.column_id=$('#column_id').val();
				var self=$(event.currentTarget),
				    parent=self.closest('.common-list-data'),
				    id=parent.attr('name'),
				    title = parent.find( '.common-list-biaoti' ).text(),
				    info=parent.find('.select_info').data('value'),
				    data={},
				    infoObj={},
				    infoArr=[];
				data['special_column_id']=this.column_id;
				data['speid']=this.speid;
				infoObj['id']=id;
				infoObj['info']=JSON.stringify(info);
				infoArr.push(infoObj);
				data['info']=infoArr;
				data['title']=title;
				this._selectAjax(data);
				event.stopPropagation();
			},
			_bathaddList:function(event){
				var self=$(event.currentTarget),
				    data={},
			        infoArr=[];
				var ids = self.closest('form')
				.find('input:checked:not([name="checkall"])')
				.map(function() {
					var infoObj={},
					   eachinfo=$(this).closest('.common-list-data').find('.select_info').data('value');
					infoObj.id=this.value;
					infoObj.info=JSON.stringify(eachinfo);
					infoArr.push(infoObj);
				});
				this.column_id=$('#column_id').val();
				data['special_column_id']=this.column_id;
				data['speid']=this.speid;
				data['info']=infoArr;
				if(!self.closest('form').find('input:checked:not([name="checkall"])').length){
					alert('请至少选择一条数据');
				}else{
					this._selectAjax(data);
				}
			}
		});
		
		$.MC.pop_module.special_con_form();
		
		$.widget('special.special_con',{
			options:{
				columnList : '.comuln-list',
				column : '.comuln-list>li',
				lastColumn : '.comuln-list>li:last',
				active : 'on',
				column_all:'.column-all',
				item : '.column_item',
				sort_column : '.column-sort-btn',
				addColumn : '.add-column',
				delColumn : '.del-column',
				column_input : '.column_input',
				search_btn:'#search-btn',
				key_search:'.key-search',
				column_id : '#column_id',
				column_tpl : '#column_list',
				add_button:'.add-button',
				speciallist : '#speciallist',
				special_list_bottom:'#special-list-bottom',
				specialcon_tpl : '#specialcon_list',
				addButton : '.add-button',
				closeButton : '.special-close',
				special_dialog : '.special-modules',
				special_modules : '.special-modules-body',
				editor_href:'.editor_href',
				edit_btn:'.edit_btn',
				page_area:'.page_area',
				mask : '.mask',
				loading: '#top-loading',
				column_editor : '.column-editor',
				column_edit_tpl : '#column_edit_tpl',
				edit_column_pop : '#edit-column-pop',
				edit_column_con: '.column-pop-content',
				edit_column_close : '.close-area',
				edit_column_form : '#edit-column-pop form',
				edit_state : 'edit-state'
			},
			_create:function(){
				this.root=this.element;
				this.speid=this.element.data('id');
				this.pageBox=this.element.find(this.options['page_area']);
				this.triggerback=false;
			},
			_init:function(){
				var _this=this;
				$(this.options['closeButton']).on('click',function(){
					$(_this.options['special_dialog']).removeClass('show').css({'top':'-620px'});
					$(_this.options['mask']).hide();
				});
				$(this.options['search_btn']).on('click',function(){
					$(_this.options['key_search']).toggleClass('key-search-open');
				});
				$('#record-edit').on('click','.edit_btn',function(event){
					var self=$(event.currentTarget),
				        id=self.data('id');
				        $('#'+id).trigger('click');
				        $('.record-edit-close').trigger('click');
				});
				var handlers={};
				handlers['click '+ this.options['sort_column']]='_toggleSort';
				handlers['click '+this.options['column_all']]='_getAll';
				handlers['click '+this.options['column']]='_getOne';
				handlers['blur '+this.options['item']]='_updateColumn';
				handlers['focus '+this.options['item']]='_saveValue';
				//handlers['click '+this.options['item']]='_stopPro';
				handlers['click '+this.options['addColumn']]='_addColumn';
				handlers['click '+this.options['delColumn']]='_delColumn';
				handlers['blur '+this.options['column_input']]='_saveColumn';
				handlers['click '+this.options['addButton']]='_openDialog';
				handlers['click '+this.options['editor_href']]='editorItem';
				handlers['click '+this.options['column_editor']]='_editorColumn';
				handlers['click '+this.options['edit_column_close']]='_closeColumnPop';
				handlers['submit '+this.options['edit_column_form']]='_submitColumn';
				this._on(handlers);
				this._initColumnSort();
				$(this.options['column_all']).trigger('click');
			},
			_toggleSort : function( event ){
				var widget = this.element,
					op = this.options,
					self = $(event.currentTarget),
					drag = self.data('drag') || false,
					el = widget.find( op['columnList'] );
				if( self.data('wait') ){
					return;
				}
				this._setSort(self, el, drag);
				drag ? self.data('drag',false) : self.data('drag',true);
				event.stopPropagation();
			},
			_initColumnSort : function(){
				var el = this.element.find( this.options['columnList'] ),
					_this = this;
				el.sortable({
			        axis: "y",
			        disabled: true
			    });
			},
			_setSort : function( self, el, bool ){
				el.sortable( 'option', 'disabled', bool );
				if( bool ){
					this._saveOrder( self );
				}else{
					self.text('退出排序');
				}
			},
			_saveOrder : function( target ){
				var url = './run.php?mid=' + gMid + '&a=drag_col_order',
					el = this.element.find( this.options['columnList'] );
				var column_id = el.find( 'li' ).map( function(){
	        		return $(this).attr( 'id' );
	        	} ).get().join(',');
				var order_id = el.find( 'li' ).map( function(){
	        		return $(this).attr( 'order_id' );
	        	} ).get();
				order_id.sort( function( a,b ){
	        		return b-a;
	        	} );
				target.data('wait', true).text('排序保存中...');
				$.post( url, {
	        		id : column_id,
	        		order_id : order_id.join(',')
	        	}, function( data ){
	        		var data = data[0];
	        		if( data.error ){
	        			jAlert( data.error,'权限提醒');
	        		}else{
	        			target.text('排序保存成功');
	        			setTimeout( function(){
		        			target.data('wait', false).text('开启排序');
	        			}, 1000 );
	        		}
	        	} );
			},
			editorItem:function(event){
				var self=$(event.currentTarget),
				    flag=self.data('edit'),
				    column_id=self.data('column'),
				    _this=this;
				if(flag== 1){
					this.triggerback=true;
					$(this.options['column_id']).val(column_id);
					var id=self.attr('_id'),
					    url='./run.php?mid=' + gMid + '&a=edit';
					$.ajax({
						url:url,
						type:'POST',
						data:{
							id:id
						},
						dataType:'json',
						beforeSend:function(){
							$(_this.options['loading']).show();
						},
						success:function(data){
							$(_this.options['loading']).hide();
							var data=data[0][0] ||{};
							var obj=$('#publish-area').find('span:first');
							var form=$('#content-form');
							var info=$.parseJSON(data['pic']),
							    url=info['host']+info['dir']+'175x140/'+info['filepath']+info['filename'];
							$(_this.options['addButton']).trigger('click');
							obj.trigger('click');
							form.find('#con-title').val(data['title']);
							form.find('.descr-area').val(data['brief']);
							form.find('.link-input').val(data['outlink']);
							form.find('#pic_info').val(data['indexpic']);
							$('#a').val('update');
							$('input[name="id"]').remove();
							$('<input type="hidden" name="id" id="info_id"/>').val(id).appendTo(form[0]);
							if(data['pic']!='false'){
								var box=form.find('.special-suolue'),
						        img=box.find('img');
					            !img[0] && (img = $('<img/>').appendTo(box));
					            img.attr('src', url);
							}
							_this.triggerback=false;
						}
					});
				}
			},
			_addColumn:function(event){
				var self=$(event.currentTarget);
	            self.hide();
	            $(this.options['column_input']).show().focus();
			},
			_delColumn:function(event){
				var parent=$(event.currentTarget).parent(),
				    id=parent.attr('id'),
				    url='./run.php?mid=' + gMid + '&a=del_column';
				if($(this.options['column']).length==1){
					alert('栏目至少保留一个');
					event.stopPropagation();
					return;
				}
				$.post(url,{column_id:id},function(data){
					var data=data[0] || {};
					if(data.success){
						parent.remove();
					}else{
						var error=data.error;
						jAlert( data.error,'权限提醒'  );
					}
				}, 'json');
				this._close();
                event.stopPropagation();
			},
			_editorColumn : function( event ){
				var _this = this,
					op = this.options,
					self = $( event.currentTarget ),
					item = self.closest( op['column'] ),
					url = self.attr( 'href' ),
					top = item.position().top;
				this.item = item;
				item.addClass( op['edit_state'] ).siblings().removeClass( op['edit_state'] );
				$.getJSON( url, function( data ){
					_this._showColumnPop( data, top );
				} );
				event.preventDefault();
				event.stopPropagation();
			},
			_submitColumn : function( event ){
				var _this = this,
					op = this.options,
					form = $( event.currentTarget ),
					name = form.find( 'input[name="column_name"]' ).val();
				form.ajaxSubmit( {
					success : function( data ){
						var data = data[0];
						_this._close();
						if( data.error ){
							jAlert( data.error, '权限提醒' );
						}else{
							_this.item.find( op['item'] ).text( name );
						}
					},
					dataType : 'json'
				} );
				return false;
			},
			_showColumnPop : function( data, top ){
				var op = this.options,
					pop = $( op['edit_column_pop'] ),
					content_box = pop.find( op['edit_column_con'] );
				var data = data[0];
				content_box.html( $( op['column_edit_tpl'] ).tmpl( data ) );
				this._initSelect(data);
				pop.css( {'opacity' : 1, 'top': top + 'px'} );
			},
			_initSelect : function( data ){
				var _this = this,
					select_value = [ data['maketype'], data['column_file'] ];
				$('.custom-select').find('ul').each( function( key ){
					_this._triggerShow($(this), select_value[key] );
				} );
			},
			_triggerShow : function( box, value ){
				if( !value || value == '0' ){
					box.find( 'a:first' ).trigger( 'click' );
				}else{
					box.find( 'a' ).each( function(){
						if( $(this).attr('attrid') == value ){
							$(this).trigger( 'click' );
							return;
						}
					} );
				}
			},
			_closeColumnPop : function( event ){
				this._close();
			},
			_close : function(){
				var op =this.options;
				$( op['edit_column_pop'] ).removeAttr('style');
			},
			_checkRepeat:function(self_input){
				  var items=$('.comuln-list>li .item'),
		    	      isRepeat=false;
				  for(var i=0;i<items.length;i++){
					  if(self_input[0]==items.eq(i)[0]) continue;
		    		  if($.trim(items.eq(i).text()) == $.trim(self_input.text())){
		    			  isRepeat=true;
		    		  }
				  }
		    	  return isRepeat;
		     },
		      _saveColumn:function(event){
					var self=$(event.currentTarget),
					    value=$.trim(self.val());
					if(!value){
						self.hide();
						$(this.options['addColumn']).css({'display':'block'});
					}else{
						var items=$('.comuln-list>li .item');
						var info={speid:this.speid,name:value};
						for(var i=0;i <items.length;i++){
							var _value=items.eq(i).text();
							if(value == _value){
								alert('栏目已存在');
								self.focus();
								return;
							}
						}
						this._ajaxColumn(info);
						self.val(' ').hide();
						$(this.options['addColumn']).css({'display':'block'});
					}
			},
			_saveValue:function(event){
				var self=$(event.currentTarget);
				this.columnValue=self.text();
			},
			_updateColumn:function(event){
				var self=$(event.currentTarget),
				    id=self.data('id'),
				    name=self.text(),
				    isrepeat=false;
				isrepeat=this._checkRepeat(self);
				if(isrepeat){
					alert('栏目已存在');
					self.focus();
				}else{
					if(this.columnValue!=name){
						var url = './run.php?mid=' + gMid + '&a=update_column',
					    info={speid:this.speid,name:name,column_id:id};
						$.post(url,info,function(data){
							var data = data[0];
							if( data.error ){
								jAlert( data.error, '权限提醒' );
							}
						},'json');
					}
				}
			},
			_stopPro:function(event){
				var self=$(event.currentTarget);
				event.stopPropagation();
			},
			_ajaxColumn:function(info){
				var url = './run.php?mid=' + gMid + '&a=create_column',
				    _this=this;
				$.ajax({
					url:url,
					type:'POST',
					data:info,
					dataType:'json',
					success:function(data){
						var data=data[0] || {};
						if(data.success){
							$(_this.options['column_tpl']).tmpl(data).prependTo(_this.options['columnList']);
						}
						if( data.error ){
							jAlert( data.error,'权限提醒' );
						}
					}
				});
			},
			_getAll:function(event){
				if(!gFlag){             //清空搜索条件
					var special_modules=$(this.options['special_modules']);
					special_modules.special_con_form('clearConditions');
				}
				gFlag=false;
				var self=$(event.currentTarget),
				    form_info=$('#searchform').serializeArray();
				var column_id=$(this.options['lastColumn']).attr('id');
				self.addClass(this.options['active']);
				$(this.options['column']).removeClass(this.options['active']);
				$(this.options['column_id']).val(column_id).data('all',true);
				this._setCheck();
				//this._hideButton();
				this._ajaxList(form_info);
			},
			_getOne:function(event){
				var self=$(event.currentTarget),
			        column_id=self.attr('id'),
				    form_info=$('#searchform').serializeArray();
				$(this.options['column_id']).val(column_id);
				$(this.options['column_id']).data('all',false);
				self.addClass(this.options['active']).siblings().removeClass(this.options['active']);
				$(this.options['column_all']).removeClass(this.options['active']);
				this._setCheck();
				//this._showButton();
				this._ajaxList(form_info,column_id);
			},
			_setCheck:function(){
				var check = this.element.find('input[name="checkall"]').attr('checked',false);
			},
			_showButton:function(){
				$(this.options['add_button']).show();
			},
			_hideButton:function(){
				$(this.options['add_button']).hide();			
			},
			_initPage:function(option){
				var _this=this;
				var pageBox=this.pageBox;
				if(pageBox.data('type')){
					pageBox.page('refresh', option);
	                return;
				}
				option['page']=function(event,val,count){
	                _this.pageVal = val;
	                _this.count = count;
	                var form_info=$('#searchform').serializeArray(),
					    column_id=$(_this.options['column_id']).val(),
					    column_all = _this.element.find( '.column-all' );
	                if( column_all.hasClass( 'on' ) ){
	                	_this._ajaxList(form_info);
	                }else{
		                if(!(+column_id)){
			                _this._ajaxList(form_info);
		                }else{
		                	_this._ajaxList(form_info,column_id);
		                }
	                }
				}
			    pageBox.page(option);
			    pageBox.data('type',true);
				_this.root.find('.common-list-bottom').append(this.pageBox[0]);
			},
			_ajaxList:function(form_info,column_id){
				var _this=this,
				    url='./run.php?mid=' + gMid + '&a=get_speconlist';
				$.ajax({
					url:url,
					type:'POST',
					data:{
						offset : _this.count,
						column_id:column_id,
						speid:_this.speid,
						info:form_info,
						page:_this.pageVal
					},
					dataType:'json',
					beforeSend:function(){
						$(_this.options['loading']).show();
					},
					success:function(data){
						_this.pageVal=1;
						var data=data[0] || {},
						    items=data['info'],
						    pageInfo=data['page_info'];
						$.globalData = $.globalData || {};
						$(_this.options['loading']).hide();
						$(_this.options['speciallist']).html('');
						if(items){
							_this.root.find('#special-list-head').show();
							_this.root.find('#special-list-bottom').show();
							var items_array = $.map(items,function(value,key){
								var id=value['id'],
								    info=$.parseJSON(value['pic']);
								if(info){
									value['url']=info['host']+info['dir']+'40x30/'+info['filepath']+info['filename'];
									value['host']=info['host'];									
								}
								if($('.none_tip').length){
									$('.none_tip').remove();
								}
								$.globalData[id] = value;
								$(_this.options['specialcon_tpl']).tmpl(value,{
									getUrl: function(){
										var url = encodeURIComponent( parent.$('#formwin').attr( 'src' ) );
										return url;
									}
								}).appendTo(_this.options['speciallist']);
								
								/*$(_this.options['editor_href']).filter(function(){
									return $(this).data('edit') ? true: false ;
								}).css({'color':'blue'})*/
								/*var quanzhong=$('.common-list-data[name="'+id+'"]').find('.common-quanzhong'),
								    _weight=quanzhong.attr('_weight'),
								    _value=create_rgb_color(_weight);
								quanzhong.css({'background':_value});*/
							return value;
								
							});
							recordCollection.reset();
							recordCollection.add(items_array);
							_this._initPage(pageInfo);
						}else{
							_this.root.find('#special-list-head').hide();
							_this.root.find('#special-list-bottom').hide();
							$('<span class="none_tip">暂无专题内容</span>').appendTo(_this.options['speciallist']);
						}
					}
				});
			},
			_openDialog:function(event){
				var top=$(window).scrollTop(),
				    height=$('body,html').height();
				/*if(!$('.comuln-list li').length){
					alert('请先添加栏目');
					return;
				}
				if(!this.triggerback){
					if(!$('.comuln-list li.on').length){
						alert('请选择一个栏目');
						return;
					}
				}*/
				if(top){
					$(this.options['special_dialog']).addClass('show').css({'top':top+'px'});
				}else{
					$(this.options['special_dialog']).addClass('show').css({'top':'95px'});
				}
				$(this.options['mask']).show().css({'top':top+'px',height:height+'px'});
				$('#con-title,.descr-area,.link-input').val('');
				$('.special-suolue').find('img').remove();
				$('#a').val('create');
				if($('#info_id').length){
					$('#info_id').remove();
				}
				if(!this.triggerback){
					$('#publish-area .item[_id="2"]').trigger('click');
					$('.special-modules-title .biaoti').text('添加内容');
					$('.modules-searlist li').hasClass('hidden') && $('.modules-searlist li').removeClass('hidden');
				}else{
					$('.special-modules-title .biaoti').text('编辑内容');
					!$('.modules-searlist li').hasClass('hidden') && $('.modules-searlist li').addClass('hidden');
				}
			}
			
		});
		
		
		$.widget('special.picupload', {
			options : {
				'avatar-url':'',
			     upload:'.user-head',
			     uploadFile:'#user-head-upload',
			     pic_id :'#pic_id',
			     pic_info : '#pic_info'
	        },
	        _create : function(){
	        	var root = this.element;
	        	this.upload=root.find(this.options['upload']);
	        	this.uploadFile=root.find(this.options['uploadFile']);
	        },
	        _init:function(){
	        	var _this=this,
	        	    handlers={};
				handlers['click '+this.options['upload']] ='_upload';
				this._on(handlers);
				this.uploadFile.ajaxUpload({
	                url : _this.options['avatar-url'],
	                phpkey : 'Filedata',
	                before : function(info){
	                    _this._uploadBefore(info['data']['result']);
	                },
	                after : function(json){
	                    _this._uploadAfter(json);
	                }
	            });
	        },
	        _upload : function(){
	        	this.uploadFile.click();
	        },

	        _uploadBefore : function(src){
	            this._avatar(src);
	        },

	        _uploadAfter : function(json){
	            var data = json.data,
	                pic_id=data.id,
	                pic_info=data['pic'];
	            $(this.options['pic_id']).val(pic_id);
	            $(this.options['pic_info']).val(pic_info);
	        },
	        _avatar : function(src){
	            var box = this.upload;
	            if(!src){
	                return;
	            }
	            var img = box.find('img');
	            !img[0] && (img = $('<img/>').appendTo(box));
	            img.attr('src', src);
	        }
		});
	})(jQuery);
	
	$.MC.conList.special_con();
	$.MC.conForm.picupload({
		'avatar-url' : "./run.php?mid="+gMid+"&a=upload&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass
	});
	
	function create_rgb_color(weight){
		var _weight = 100-weight,
		    rgb = new Array(255, _weight * 2, _weight);
		var _string=rgb.join(',');
		return 'rgb('+_string+')';
	}
	(function($){
		$('.property-tab').on('click',function(){
			if($(this).attr('href')){
				$('#top-loading').show();
			}
		});
	})($);
	
	
	/**
	 * 选中、全选
	 */
	jQuery(function($) {
		var checkedClass = 'selected';
		var ul = $(".public-list");
		
		ul.on('click', '.common-list-data input:checkbox', function(event) {
			$(this).closest('.common-list-data').toggleClass(checkedClass);
			event.stopPropagation();
		});
		ul.on('click','.common-list-bottom input:checkbox',function(event) {
			var self=$(event.currentTarget);
			var isChecked = self.prop('checked');
			self.closest('form')
				.find(".common-list-data input:checkbox").prop('checked', isChecked)
				.closest('.common-list-data')[ (isChecked ? 'add' : 'remove') + 'Class' ](checkedClass);
		});
		$('.special-modules-body').on('click','.common-list-data',function(e){
			var input=$(this).find('input:checkbox');
			input.trigger('click');
		});
	});
	
	/**
	 * 选中、全选
	 */
	jQuery(function($) {
		$('.weightPicker').on('click','.weight-list li',function(){
			var self=$(this),
			    weight=self.data('weight'),
			    weight_arr=weight.split(',');
			
			var text="权重("+weight_arr[0]+"-"+weight_arr[1]+")";
			$(this).closest('.weightPicker').find('.input_middle label').text(text);
		});
		$('.weightPicker').on('click','.btn[type="submit"]',function(){
			var parent=$(this).closest('.weightPicker'),
			    weight1=parent.find('input[name="start_weight"]').val(),
			    weight2=parent.find('input[name="end_weight"]').val();
			var text="权重("+weight1+"-"+weight2+")";
			$(this).closest('.weightPicker').find('.input_middle label').text(text);
		});
	});

});
function  special_hg_select_value(obj,flag,show,value_name,is_sub)
{
	if($(obj).attr('attrid') != $('#' + value_name).val())
	{
		$('#display_'+ show).text($(obj).text());
		$('.display_'+show).val($(obj).attr('attrid'));
		$('#' + value_name).val($(obj).attr('attrid'));
		$('#' + show).css('display','none');
		if(flag == 1)
		{
			if($(obj).attr('attrid') == 'other')
			{
			   $('#special_start_time_box').css('display','block');
			   $('#special_end_time_box').css('display','block');
				$('#special_go_date').css('display','block');
			}
			else
			{
				$('#special_start_time').val('');
				$('#special_end_time').val('');
				$('#special_start_time_box').css('display','none');
				$('#special_end_time_box').css('display','none');
				$('#special_go_date').css('display','none');
			}
		}
		if(is_sub == 1)
		{
			$("#modules_searchform").submit();
		}
		return true;
	}
	else
	{
		$('#' + show).css('display','none');
		return false;
	}
}

$(function(){
	var el = $('#move-column-pop'),
		speid = el.data('speid');
	var change = function(){
		if( $('.column-all').hasClass( 'on' ) ){
			$('.column-all').trigger('click');
		}else{
			$('.comuln-list li.on').trigger( 'click' );
		}
		$('.record-edit-close').trigger( 'click' );
	};
	;( function(){
		var tool = { 
				getParam : function( target ){
					var list = {},
						ids = [],
						column_ids = [],
						bundle_ids = [],
						content_fromids = [],
						cids = [],
						module_ids = [];
					target.each( function(){
						var id = $(this).attr('_id'),
							column_id = $(this).attr('_columnid'),
							bundle_id = $(this).attr('_bundleid'),
							module_id = $(this).attr('_moduleid'),
							content_fromid = $(this).attr('_fromid');
							cid = $(this).attr('_cid');
						ids.push(id);
						cids.push(cid);
						column_ids.push(column_id);
						bundle_ids.push(bundle_id);
						module_ids.push(module_id);
						content_fromids.push(content_fromid);
					} );
					list.ids = ids;
					list.cids = cids;
					list.column_ids = column_ids;
					list.bundle_ids = bundle_ids;
					list.content_fromids = content_fromids;
					list.module_ids = module_ids;
					return list;
				} 
		};
		$.tool = tool;
	} )($);
	$('#record-edit').on('click','.move',function( event ){
		var self = $( event.currentTarget ),
			top = self.offset().top;
		var param = $.tool.getParam( self.closest('.record-edit') );
		param['speid'] = speid;
		param['top'] = top;
		param['change'] = change;
		el.hg_moveColumn( param );
	});
	
	$('.batch-move').on('click',function(){
		var top = $(this).offset().top;
			items = $('.common-list-data.selected');
		if( items.length ){
			var param = $.tool.getParam( $('.common-list-data.selected') );
			param['speid'] = speid;
			param['change'] = change;
			param['top'] = top;
			el.hg_moveColumn( param );
			
		}else{
			jAlert( '请选择要移动的记录','移动提醒' );
		}
	})
	
});

$( function(){
	var autoitem = $( '.autocomplete' );
	if( autoitem.length ){
		autoitem.autocompleteResult();
	}
} )

