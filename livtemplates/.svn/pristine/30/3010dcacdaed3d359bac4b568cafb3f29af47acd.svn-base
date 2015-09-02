$(function(){
	(function($){
		$.widget('card.card_list',{
			options : {
				'm2o-card' : '.m2o-card',
				'card-add' : '.card-add',
				'show-add' : 'add-show', 
				'card-tmpl' : '#card-tmpl',
				'card_list' : '.switch_list>ul',
				'card-page' : '.card-page',
				'current' : 'current',
				'common-switch' : '.common-switch',
				'card-delete' : '.card-del',
				'card-status' : '.card-status',
				'sort_control' : '.sort-btn',
				'sort-model' : 'sort-model',
				'no-show' : 'no-show',
				'unusually-show' : 'unusually-show'
			},
			_create : function(){
				this.status = ['','待审核','已审核','已打回'];
				this.status_color = ['','#8ea8c8','#17b202','#f8a6a6'];
			},
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['card-add'] ] = '_toggleCard';
				handlers['click ' + op['card-page'] ] = '_ajaxData';
				handlers['click ' + op['card-delete'] ] = '_delCard';
				handlers['click ' + op['card-status'] ] = '_auditCard';
				handlers['click ' + op['common-switch'] ] = '_stop';
				handlers['click ' + op['sort_control'] ] = '_controlSort';
				this._on(handlers);
				this.initSwitch();
			},
			initSwitch : function( el ){
				var op = this.options,
					widget = this.element,
					_this= this;
				if( el ){
					var switchs = el;
				}else{
					var switchs = widget.find( op['common-switch'] );
				}
				switchs.each(function(){
					var self = $(this),
						id = $(this).closest( op['card-page'] ).attr( '_id' );
					$(this).hasClass( 'common-switch-on' ) ? val = 100 : val = 0;
					$(this).hg_switch({
						'value' : val,
						'callback' : function( event, value ){
							var is_on = 0;
							( value > 50 ) ? is_on = 1 : is_on = 0;
							_this._onOff(id, is_on,self);
						}
					});
				});
			},
			_controlSort : function( event ){
				var self = $(event.currentTarget);
					op = this.options,
					widget = this.element;
				var card_list = widget.find( op['card_list'] ),
					items = card_list.find( op['card-page'] );
				if( self.data('wait') ){
					return;
				}
				if( self.data('sort') ){
					this._savesortable( self );
					card_list.sortable('destroy');
					items.removeClass( op['sort-model'] );
				}else{
					this._novailToend( self, items, card_list);
				}
			},
			_novailToend : function( self, items,card_list){
				var op = this.options;
				this._initSortable();
				self.data( 'sort', true ).text('退出排序');
				items.addClass( op['sort-model'] );
				var novails = items.filter( function(){
					return $(this).hasClass( op['no-show'] ) || $(this).hasClass( op['unusually-show'] );
				});
				if( novails.length ){
					novails.clone( true ).appendTo( op['card_list'] );
					novails.remove();
				}
			},
			_initSortable : function(){
				var op = this.options,
					widget = this.element,
					_this= this;
				var card_list = widget.find( op['card_list'] );
				card_list.each(function(){
					$(this).sortable({
						axis : 'y',
						stop : function(){
							_this.setIndex();
						}
					});
				});
			},
			_savesortable : function( target ){
				var card_list = this.element.find( op['card_list'] ),
					items = card_list.find( op['card-page'] ),
					url = './run.php?mid=' + gMid + '&a=drag_order';
				var content_ids = items.map(function(){
					return $(this).attr('_id');
				}).get().join(',');
				var order_ids = items.map(function(){
					return $(this).attr('order_id');
				}).get();
				order_ids.sort( function( a,b ){
	        		return b-a;
	        	} );
				target.data('wait',true).text( '排序保存中...' );
				$.post( url, {content_ids : content_ids,order_ids: order_ids.join(',')},function(){
					var tip = $('.order-tip');
					tip.css( {opacity: 1} );
					setTimeout( function(){
						tip.css( {opacity: 0} );
					}, 1000 );
					target.data( 'sort', false ).data('wait',false).text('开启排序');
				});
			},
			exitSort : function(){
				var sort_btn = this.element.find('.sort-btn');
				if( sort_btn.data('sort') ){
					sort_btn.trigger( 'click' );
				}
			},
			_onOff : function( id , is_on, target ){
				var _this = this;
				var url = './run.php?mid=' + gMid + '&a=display';
				$.globalAjax( target, function(){
					return $.getJSON( url, {id : id, is_on : is_on} ,function( data ){
					
							}).error(function(){
								_this._limit( target );
							});
				} );
			},
			_limit : function( target ){
				var _jAlert = jAlert( '你没有权限做此操作!','权限提醒' );
				target && _jAlert.position( target );
			},
			_auditCard : function( event ){
				var self = $(event.currentTarget),
					id = self.attr('_id'),
					status = self.attr('_status');
				this._audit( self, id, status );
				event.stopPropagation();
			},
			_audit : function( self, id , status ){
				var _this = this,
					url = './run.php?mid=' + gMid + '&a=audit';
				$.globalAjax( self, function(){
					return $.getJSON( url, {id : id, status : status} ,function( data ){
							var data = data[0];
								status = data['status'],
								status_text = _this.status[status],
								status_color = _this.status_color[status];
							self.text( status_text ).css({'color' : status_color }).attr('_status',status);
						}).error(function(){
							_this._limit( self );
						});
				} );
			},
			_delCard : function( event ){
				var _this = this,
					op = this.options,
					widget = this.element,
					card_add = widget.find( op['card-add'] );
				var self = $(event.currentTarget),
					item = self.closest( op['card-page'] ),
					id = item.attr('_id');
				jConfirm( '确定删除此卡片吗?','删除提醒',function( result ){
					if( result ){
						_this._del( id, item );
						card_add.trigger('click');
					}
				} ).position( self );
				event.stopPropagation();
			},
			_del : function( id , item ){
				var _this = this;
				var url = './run.php?mid=' + gMid + '&a=delete';
				$.getJSON( url, {id : id } ,function(){
					item.remove();
					_this.setIndex();
				}).error(function(){
					_this._limit( item );
				});
			},
			_stop : function( event ){
				event.stopPropagation();
			},
			_toggleCard : function(){
				var op = this.options,
					widget = this.element,
					view_weight = $( op['m2o-card'] );
				if( !$('#card-tmpl').hasClass( op['show-add'] ) ){
					view_weight.view('clearEdit');
					$('#card-tmpl').add_card('clearCardItem');
					$( op['card-tmpl'] ).removeAttr('style').addClass( op['show-add'] );
					this.clearCurrent();
				}
				view_weight.view('reset');
				$('.m2o-card').view('hideTitle');
				//$( op['card-tmpl'] ).add_card('controll');
			},
			clearCurrent : function(){
				var widget = this.element,
					op = this.options;
				widget.find( op['card-page'] ).removeClass( op['current'] );
			},
			addCard : function( card ){
				var op = this.options;
				var card_box = this.element.find( op['card_list'] ),
					item = $(card),
					switch_el = item.find( op['common-switch'] );
				this.initSwitch( switch_el );
				item.prependTo( card_box[0] );
			},
			_ajaxData : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					id = self.attr('_id'),
					url = './run.php?mid=' + gMid + '&a=form&id=' + id; 
				if( self.hasClass( op['current'] ) ){
					return;
				}else{
					self.addClass( op['current'] );
					self.siblings().removeClass( op['current'] );
				}
				$.globalAjax( self, function(){
					return $.get( url,function( html ){
						$( op['m2o-card'] ).view( 'initEdit', html );
					} );
				} );
				$('.m2o-card').view('hideTitle');
			},
			setIndex : function(){
				var index_area = this.element.find('.card-item-set>em');
				index_area.each( function( key,value ){
					$(this).text( key+1 );
				} );
			}
		});
		
		$.widget('card.add_card',{
			options : {
				'card-item' : 'card-item',
				'm2o-card' : '.m2o-card',
				'card-left' : '.card-left',
				'show-add' : 'add-show', 
				'cancel-card' : '.add-cancel',
				'add-card-form' : '#add-card-form',
				'edit-buttons' : '.news-edit-buttons',
				'new-content' : '.new-content',
				'card-name' : '.card-name',
				'hide' : 'hide',
				'order-ids' : '.order-ids'
			},
			_create : function(){
				
			},
			_init : function(){
				var op = this.options,
					handlers = {};
				handlers['click ' + op['cancel-card'] ] = '_closeCard';
				handlers['submit ' + op['add-card-form'] ] = '_saveCard';
				this._on(handlers);
			},
			_closeCard : function(){
				this.controll();
			},
			controll : function(){
				var op =this.options;
				this.element.toggleClass( op['show-add'] );
				//$( op['edit-buttons'] ).toggleClass( op['hide'] );
			},
			_saveCard : function( event ){
				var _this = this,
					op = this.options,
					widget = this.element,
					form = $(event.currentTarget),
					button = form.find('input[type="submit"]');
				var list_widget = $( op['card-left'] ),
					view_widget = $('.m2o-card');
				var title = $.trim( form.find( op['card-name'] ).val() );
				if( !title ){
					jAlert( '请输入卡片标题!','卡片提醒' );
					return false;
				}
				var order_ids = $( op['m2o-card'] ).view('record_order',form);
				form.find( op['order-ids'] ).val( order_ids );
				list_widget.card_list('exitSort');
				var stop = $.globalLoad( button );
				form.ajaxSubmit({
					success : function( html ){
						if( $(html).hasClass( op['card-item'] ) ){
							list_widget.card_list('addCard',html);
							list_widget.card_list('setIndex');
							_this.clearCardItem();
							view_widget.view('reset');
						}else{
							jAlert( '你没有权限做此操作!','权限提醒' );
						}
						stop();
					}
				});
				return false;
			},
			clearCardItem : function(){
				var op = this.options,
					widget = this.element;
				widget.find( 'input[type="checkbox"]' ).attr('checked',false);
				widget.find('input[type="text"],textarea').filter( function(){
					var readonly = $(this).attr('readonly');
					return !readonly;
				} ).val('');
				widget.find( op['new-content'] ).html('');
				$( op['m2o-card'] ).view('clearMask');
			}
		});
		
		$.widget('card.view',{
			options : {
				'card-small' : '.card-small',
				'card-delete' : '.card-delete',
				'current' : 'card-news-cur',
				'card-right-title' : '.card-right-title',
				'card-title' : '.card-title',
				'card-img' : '.card-img',
				'title' : '.title',
				'card-type' : '.card-special',
				'right-title-show' : 'right-title-show',
				'style-type' : '.style-type',
				'select-type' : 'card-select-cur',
				'cardFrame' : '#cardFrame',
				'm2o-each' : '.m2o-each',
				'index-pic' : '.index-pic',
				'edit-content' : '.edit-content',
				'new-content'  : '.new-content',
				'add-box' : '#card-tmpl',
				'add-show' : 'add-show',
				'card-middle': '.card-middle',
				'style-mode' : '.style-mode',
				'source-id' : '.source-id',
				'source-type' : '.source-type',
				'source-from' : '.source-from',
				'card-describe' : '.card-describe',
				'card-show-list' : '.card-show-img',
				'show-state' : 'show-state',
				'editor-card-form' : '#editor-card-form',
				'card-name' : '.card-name',
				'save-editor' : '#save-editor',
				'card-add-head' : '.card-add-head',
				'middle-content' : '.new-content-box',
				'card-content' : '.card-middle-content',
				'add-edit' : '.add-edit',
				'editor-middle' : '.editor-middle',
				'edit-cancel' : '.edit-cancel',
				'card-left' : '.card-left',
				'card-add' : '.card-add',
				'card-right-mask' : '.card-right-mask',
				'html-check' : '#html-check',
				'html-editor' : '.card-html-content',
				'html-show' : 'card-html-show',
				'middle-hide' : 'card-middle-hide',
				'sortable-btn' : '.sortable-btn',
				'order-ids' : '.order-ids',
				'card-edit' : '.card-edit',
				'publish-edit' : '.publish-edit',
				'publish-save' : '.publish-save',
				'card-item' : '.card-item',
				'content-form' : '#content-form'
 			},
			_create : function(){
				
			},
			_init : function(){
				var _this = this,
					op = this.options,
					widget = this.element,
					handlers = {};
				this.showlist = this.element.find( op['card-show-list'] );
				handlers['click ' + op['card-delete'] ] = '_delItem';
				handlers['click ' + op['card-small'] ] = '_selectItem';
				handlers['click ' + op['card-edit'] ] = '_editCardContent';
				handlers['focus .item-info-event'] = '_showEdit';
				handlers['blur .item-info-event' ] = '_hideEdit';
				handlers['click ' + op['publish-save'] ] = '_publishEditSave';
				handlers['click ' + op['style-type'] ] = '_selectStyle';
				handlers['submit ' + op['editor-card-form'] ] = '_editFormSubmit';
				handlers['click ' + op['edit-cancel'] ] = '_close';
				handlers['click ' + op['html-check'] ] = '_isShowHtml';
				handlers['click .form-mode-check' ] = '_isformMode';
				handlers['click .dynamic-setting-check' ] = '_isdynamicMode';
				handlers['blur .publish-column-number' ] = '_validatenumber';
				this._on(handlers);
				this._initStyle();
				this._sortOn( this.showlist );
				this._initClick();
				this._initMenuEvent();
				this._initAdvEvent();
				this._initDom();
			},
			
			_initDom : function(){
				this.normal_style_box = this.element.find('.card-select-normalstyle');
				this.form_style_box = this.element.find('.card-select-formstyle');
				this.iframe_box = this.element.find('.card-iframe-box');
				this.setting_box = this.element.find('.card-setting-box');
			},
			
			_initMenuEvent : function(){
				this._on( {
					'click .module-item' : '_moduleClick',
					'click .module-item-del' : '_delmoduleClick'
				} );
				this.menu = {
					menutpl :  '<div class="module-item" data-id="${id}">' +
							        '<span class="module-item-img"><img src="${index_url}" /></span>' +
							        '<span class="module-item-name">${title}</span>' +
							        '<span class="module-item-descr">${brief}</span>' +
							        '<span class="module-item-del">x</span>' +
							    '</div>' + 
							    ''
				}; 
								
			},
			
			_initAdvEvent : function(){
				var _this = this;
				this._on( {
					'click .set-style-attr' : '_setAdvClick',
				} );
				$('body').on('click','.set-style-box .close', function(event){
					var target = $(event.currentTarget);
					_this._closeAdvpop( target );
				}).on('click','.set-style-box .sure', function(event){
					var target = $(event.currentTarget);
					_this._closeAdvpop( target );
				});
			},
			
			_setAdvClick : function( event ){
				var adv_style_wrap = $('body').find('.set-style-wrap');
				( adv_style_wrap.length ) && adv_style_wrap.remove();
				//console.log(  adv_style_wrap );
				$('#set-style-tmpl').tmpl({}).appendTo( 'body' );
				event.stopPropagation();
			},
			
			_closeAdvpop : function( target ){
				var adv_pop = target.closest('.set-style-wrap');
				adv_pop.remove();
			},
			
			_moduleClick : function( event ){
				var self = $( event.currentTarget ),
					parent = self.closest('.card-menu-type');
				if( parent.hasClass('default') ) return;
				this._toggleModuleClick( self );
				event.stopPropagation();
			},
			
			_delmoduleClick : function( event ){
				var self = $( event.currentTarget ),
					parent = self.closest('.module-item'),
					menu_parent = self.closest('.card-menu-type'),
					siblings = parent.siblings('.module-item');
				if( siblings.length == 1 ){
					menu_parent.remove();
				}
				parent.remove();
				this._restore();
				this._menuHiddenData( menu_parent );
				this._opendefineForm( null );
				event.stopPropagation();
			},
			
			_toggleModuleClick : function( self ){
				var menu_parent = self.closest('.card-menu-type'),
					siblings_menu_modules = menu_parent.siblings('.card-menu-type').find('.module-item');
				if( !menu_parent.hasClass('card-news-cur') ){
					menu_parent.trigger('click');
				}
				self.siblings().removeClass('current');
				siblings_menu_modules.length && siblings_menu_modules.removeClass('current');
				if( self.hasClass('current') ){
					self.removeClass( 'current' );
					this._restore();
					this._opendefineForm( null, 'menu' );
				}else{
					self.addClass( 'current' );
					var id = self.data('id'),
						info = null;
					if( id ){
						this._initData();
						info = this.globalinfo[id];
						info.id = id;
					}
					this._opendefineForm( info, 'menu' );
				}
			},
			
			_menuTip : function( need, name, number ){
				var op = this.options,
					number = number || 8,
					name = name || '菜单',
					body = this.element.find( op['cardFrame'] )[0].contentWindow.$('body'),
					tip = '<div class="menu-tip" style="color:red;position:absolute;">连续录入提交可以创建最多' + number + '个' + name + '模块，选中' + name + '里面的模块可以进行编辑</div>';
				body.find('.menu-tip').remove();
				if( need ){
					$(tip).appendTo( body.find('.publish-form') );
				}
			},
			
			_opendefineForm : function( info, type ){
				var op = this.options,
					body = this.element.find( op['cardFrame'] )[0].contentWindow.$('body');
					isonlydefine = this.styleIsOnlyCustom();
				body.trigger( 'initForm', [info, type, isonlydefine] );
			},
			
			_ismenuCurrent : function(){
				var box = this._decideBox(),
					select_item = box.select_item,
					is_current = false;
				if( select_item.hasClass('card-menu-type') ){
					is_current = true;
				}
				return is_current;
			},
						
			_ismenuMode : function( ismenu, item ){
				if( ismenu ){
					this._opendefineForm( null, 'menu' );
					item.hasClass('card-product-type') ? this._menuTip( true, '商品', 2 ) : this._menuTip( true );
				}else{
					this._menuTip( false );
				}
			},
			
			_isClassnameType : function( style, classname ){
				var $style = $(style);
				return $style.hasClass( classname ) ? true : false;
			},
			
			_isDynamicStyle : function( dom ){
				var form = dom.closest('form'),
					columnid_dom = form.find('input[name="column_id"]');
				return columnid_dom.length ? true : false;
			},
			
			_ismenuStyle : function( info ){
				this._decideAction( info );
				var box = this._decideBox();
					content_box = box.content_box,
					select_item = box.select_item;
					target = null;
				target = select_item.length ? select_item.removeClass('card-news-cur') : content_box.find('.card-menu-type:last');
				target.trigger('click');
			},
			
			_ismovieStyle : function(){
				var box = this._decideBox(),
					select_item = box.select_item;
				select_item.removeClass('card-news-cur');
				this._opendefineForm( null );
				this._menuTip( false );
			},
			
			_immediateaddStyle : function( info ){
				var box = this._decideBox(),
					select_item = box.select_item;
				select_item.length && select_item.removeClass('card-news-cur');
				this._decideAction( info );
			},
			
			_asycStyle : function( id ){
				var style_box = this.element.find('.card-select-style');
				style_box.find('.style-type').removeClass('card-select-cur').filter( function(){
					var self = $(this),
						style = self.data('type');
					return ( style ==  id || style ==  'style' + id );
				} ).addClass('card-select-cur');
			},
			
			_menuCallback : function( id ){
				var box = this._decideBox(),
					data = this.globalinfo[id],
					select_item = box.select_item,
					module_demo = select_item.find('.module-item.demo'),
					module_last = select_item.find('.module-item:last');
				var iseditmenuState = this._editmenuState( select_item );
				if( !iseditmenuState ){
					var islimit = this._menuLimit( select_item );
					if( !islimit ) return;
				}else{
					module_last = iseditmenuState;
				}
				select_item.removeClass('default');
				module_demo.hide();
				data['id'] = id;
				$.template('menu_tpl', this.menu.menutpl);
				$.tmpl( 'menu_tpl', data ).insertAfter( module_last );
				iseditmenuState && iseditmenuState.remove();
				this._menuHiddenData( select_item );
			},
			
			_editmenuState : function( current_menu ){
				var current_module = current_menu.find('.module-item.current');
				return current_module.length ? current_module : false;
			},
			
			_menuHiddenData : function( current_menu ){
				var ids = current_menu.find('.module-item').not('.demo').map( function(){
					return $(this).data('id');
				} ).get().join(',');
				current_menu.find('.source-id').val( ids );
				current_menu.find('.source-from').val( 0 );
			},
			
			_menuLimit : function( current_menu ){
				var items = current_menu.find('.module-item').not('.demo'),
					is_product = current_menu.hasClass('card-product-type'),
					number = is_product ? 2 : 12,
					name = is_product ? '商品' : '菜单';
				if( items.length >= number ){
					jAlert( name + '模块最多' + number + '个','卡片提醒' );
					return false;
				}else{
					return true;
				}
			}, 
			
			_initData : function(){
				var op = this.options,
					_this = this,
					widget = this.element,
					source_window = widget.find( op['cardFrame'] )[0].contentWindow;
				if( window.globaleditinfo ){
					this.editinfo = window.globaleditinfo;
				}
				this.globalinfo =  $.extend( this.globalinfo, this.editinfo, source_window.globalinfo );
			},
			_isShowHtml : function( event ){
				var self = $(event.currentTarget),
					checked = self.prop('checked'),
					form = self.closest('form');
				this.controlMode(checked,form);
			},
			_isformMode : function( event ){
				var self = $(event.currentTarget),
					form = self.closest('form'),
					checked = self.prop('checked');
				this._jConfirm_Mode( checked, form, self, '表单','_isformModeCallback' );
			},
			
			_isformModeCallback : function( checked, form ){
				if( checked ){
					this._exclusiveControl( form, 'html' );
					this._exclusiveControl( form, 'dynamic' );
				}
				this._controlFormMode( checked );
			},
			
			_isdynamicModeCallback : function( checked, form ){
				if( checked ){
					this._exclusiveControl( form, 'html' );
					this._exclusiveControl( form, 'form' );
				}
				this._controlDynamicMode( checked, form );
			},
			
			_jConfirm_Mode : function( checked, form, self, modename, callback ){
				var _this = this,
					box = this._decideBox(),
					iscontent = this._ishasContent( box );
				if( iscontent ){
					this._enter_or_exit_confirm( checked, form , box , self, modename, callback );
				}
				if( !iscontent ){
					form && _this[callback]( checked, form );
				}
			},
			
			_enter_or_exit_confirm : function( checked, form, box, self, modename, callback ){
				var _this = this,
					name = ( checked ? '进入' : '退出' ) + modename;
				jConfirm( name + '模式会清空卡片原先添加的内容，是否继续？',modename + '模式提醒', function( result ){
					if( result ){
						box.content_box.html('');
						$.isFunction( callback ) && callback(true);
						form && _this[callback]( checked, form );
					}else{
						$.isFunction( callback ) && callback( false );
						form && self.prop( 'checked', !checked );
					}
				});
			},
			
			_ishasContent : function( box ){
				var contents = box.content_box.find('.card-small');
				return contents.length ? true : false;
			},
			
			_isdynamicMode : function( event ){
				var self = $(event.currentTarget),
					form = self.closest('form'),
					checked = self.prop('checked');
				this._jConfirm_Mode( checked, form, self, '动态','_isdynamicModeCallback' );
				
			},
			_exclusiveControl : function( form, type ){
				var selector_str = '',
					type_method = null;
				switch( type ){
					case 'html' : 
						selector_str = '#html-check';
						type_method = 'controlMode';
						break;
					case 'form' : 
						selector_str = '.form-mode-check';
						type_method = '_controlFormMode';
						break;
					case 'dynamic' : 
						selector_str = '.dynamic-setting-check';
						type_method = '_controlDynamicMode';
						break;
				}
				var selector_dom = form.find( selector_str );
				selector_dom.prop('checked',false);
				this[type_method]( false, form );
			},
			
			controlMode : function( checked, form ){
				var op = this.options,
					widget = this.element,
					html_el = form.find( op['html-editor'] ),
					con_el = form.find( op['card-content'] ),
					checked = checked;
				if( checked ){
					this._exclusiveControl( form, 'form' );
					this._exclusiveControl( form, 'dynamic' );
				}
				var control =  checked? 'addClass' : 'removeClass',
					sh_hide = checked ? 'addMask' : 'clearMask';
				con_el[control]( op['middle-hide'] );
				html_el[control]( op['html-show'] );
				this[sh_hide]();
			},
			
			_controlFormMode : function( bool ){
				var form_style_box = this.form_style_box,
					normal_style_box = this.normal_style_box,
					iframe_box = this.iframe_box,
					setting_box = this.setting_box;
				if( bool ){
					form_style_box.show();
					setting_box.hide();
					normal_style_box.hide();
					iframe_box.hide();
				}
				!bool && this.reset();
			},
			
			_controlDynamicMode : function( bool, form ){
				var _this = this,
					setting_box = this.setting_box,
					iframe_box = this.iframe_box,
					form_style_box = this.form_style_box,
					normal_style_box = this.normal_style_box,
					column_hidden = this.element.find('.publish-column-id');
				if( bool ){
					this._exclusiveControl( form, 'html' );
					this._exclusiveControl( form, 'form' );
					setting_box.show();
					normal_style_box.show();
					form_style_box.hide();
					iframe_box.hide();
				}
				!bool && this.reset();
				this._toggleNotForDynamicStyle( bool );
				if( !setting_box.data('init') ){
					setting_box.search_pop( {
						type : 'radio',
						columnIdHidden : column_hidden,
						saveCallback : function( event, info ){
							var target = $(event.target).find('.save-button');
							_this._getDynamicData( info,target );
						}
					} );
					setting_box.data('init',true);
				}else{
					setting_box.search_pop('refresh');
				}
			},
			
			_getDynamicData : function( info, target ){
				if( !info.length ){
					jAlert( '请选择要绑定的栏目','栏目绑定提醒' );
					return;
				}
				var _this = this,
					current_style = this.element.find('.card-select-normalstyle').find('.card-select-cur');
					style_id = current_style.data('id'),
					number = this.element.find('.publish-column-number').val(),
					url = './run.php?mid=' + gMid + '&a=get_dynamic';
				$.globalAjax( target, function(){
					return $.post( url, { column_id : info[0], style_id : style_id, number : number}, function( data ){
								if( data ){
									_this._initData();
									var box = _this._decideBox(),
										dynamic_columndom = $('<input type="hidden" name="column_id" />');
									box.content_box.html(data);
									dynamic_columndom.val( info[0] ).appendTo(box.content_box);
								}
							}  );
				} );
			},
			
			_toggleNotForDynamicStyle : function( bool ){
				var notForDynamicStyles = ['card-product-type','card-menu-type','card-weather-type','card-movie-type'],
					style_lists = this.element.find('.card-select-normalstyle').find('li'),
					notForDynamic_dom = style_lists.filter( function(){
						var hasStyle = false;
							style_hidden_value = $(this).find('input[type="hidden"]').val();
						$.each( notForDynamicStyles, function( key, value ){
							if( $( style_hidden_value ).hasClass( value ) ){
								hasStyle = true;
								return false;
							}
						} );
						return hasStyle;
					} );
				notForDynamic_dom[!bool ? 'show' : 'hide']();
			},
			
			_sortOn : function( el ){
				el.each(function(){
					$(this).sortable({
						axis : 'y'
					});
				});
			},
			_initClick : function(){
				var op = this.options,
					_this = this,
					widget = this.element;
				$('html').on('clickAfter',function( event ,id ){
					_this._initData();
					var ismenuCurrent = _this._ismenuCurrent();
					if( ismenuCurrent ){
						_this._menuCallback( id );
						return;
					}
					var select_style = widget.find('.card-select-normalstyle').find( '.' + op['select-type'] );
					var data = _this.globalinfo[id],
						type= select_style.data('type');
					var is_repeat = _this._repeatDecide(  data['source_id'] || id  );
					if( is_repeat ){
						jAlert('该条内容已添加!','卡片提醒');
						return;
					}
					var is_ok = _this._styleDecide(data, type);
					if( !is_ok ){
						jAlert('此样式不适合!','卡片提醒');
					}else{
						var info = {};
						info.id = id;
						info.source_id = data['source_id'] || id;
						info.source_form = data['form'];
						info.type = type;
						info.style = select_style.find( op['style-mode'] ).val();
						info.title = data['title'];
						info.brief = data['brief'];
						info.list_src = data['listpic'];
						info.src = data['index_url'];
						info.username = data['username'] || '';
						info.publish_time = data['publish_time'] || '';
						_this._decideAction(info);	
					}
				});
			},
			_repeatDecide : function( id ){
				var op = this.options,
					box = this._decideBox(),
					repeat = false;
				box.content_box.find( op['source-id'] ).each(function(){
					if( $(this).val() == id ){
						var source_from = $(this).closest( op['card-small'] ).find( op['source-from'] );
						if( +source_from.val() ){
							repeat = true;
						}
						return;
					}
				});
				return repeat;
			},
			record_order : function( form ){
				var op = this.options;
				var order_ids = form.find( op['source-id'] ).map(function(){
					return $(this).val();
				}).get().join(',');
				return order_ids;
			},
			clearMask : function(){
				var op = this.options,
					widget = this.element;
				widget.find( op['card-right-mask'] ).hide();
			},
			addMask : function(){
				var op = this.options,
					widget = this.element;
				widget.find( op['card-right-mask'] ).show();
			},
			initEdit : function( html ){
				var op = this.options,
					html = $(html),
					html_check = html.find( op['html-check'] );
				this.clearEdit();
				if( html_check.attr('checked') != 'checked' ){
					this.clearMask();
				}else{
					this.addMask();
				}
				var showlist = html.find( op['card-show-list'] );
				this._sortOn(showlist);
				html.appendTo( op['card-middle'] );
				$( op['add-box'] ).removeClass( op['add-show'] ).css({'position':'absolute'});
				this._controll(300);
				this._initData();
				this._initMode( html );
			},
			
			_initMode : function( html ){
				var $html = $(html),
				    form = $html.find('form'),
					html_check = $html.find('#html-check').prop('checked'),
					form_check = $html.find('.form-mode-check').prop('checked'),
					dynamic_check = $html.find('.dynamic-setting-check').prop('checked');
				dynamic_check && this._initDynamicColumn( form );
				html_check && this.controlMode( html_check, form );
				dynamic_check && this._controlDynamicMode( dynamic_check,form );
				form_check && this._isformModeCallback( form_check, form );
				if( !html_check && !dynamic_check && !form_check ){
					this.reset();
				}
			},
			
			reset : function(){
				this.clearMask();
				this.normal_style_box.show();
				this.iframe_box.show();
				this._toggleNotForDynamicStyle(false);
				this.form_style_box.hide();
				this.setting_box.hide();
				this._initStyle();
			},
			
			_initDynamicColumn : function( form ){
				var column_id = form.find('input[name="column_id"]').val();
				this.element.find('.publish-column-id').val( column_id );
			},
			
			clearEdit : function(){
				var op = this.options,
					widget = this.element;
				widget.find( op['editor-middle'] ).remove();
			},
			_close : function(){
				this._editAfter();
			},
			_controll : function(times){
				var op = this.options,
					widget = this.element;
				setTimeout(function(){
					widget.find( op['editor-middle'] ).toggleClass( op['add-show'] );
				},times);
			},
			_decideAction : function( info ){
				var box = this._decideBox(),
					select_item = box.select_item,
					content_box = box.content_box;
				if( select_item.length ){
					info.box = select_item;
					this._editItem( info );
				}else{
					info.box = content_box;
					this._addItem( info );
				}	
			},
			_decideBox : function(){
				var op = this.options,
					widget = this.element,
					tmpl_box = widget.find( op['add-box'] ),
					box = {};
				if( tmpl_box.hasClass( op['add-show'] ) ){
					var content_box = tmpl_box.find( op['new-content'] ).removeClass( op['show-state'] );
				}else{
					var content_box = widget.find( op['edit-content'] );
				}
				var select_item = content_box.find( '.' +  op['current'] );
				box.content_box = content_box;
				box.select_item = select_item;
				return box;
			},
			_addItem : function( info ){
				info.add = true;
				var op = this.options,
					item = this._instanceItem(info);
				info.box.append( item );
				var current = info.box.find( op['card-small'] + ':last' );
				//this._triggerCurrent(current);
			},
			_editItem : function( info ){
				var op = this.options,
					item = this._instanceItem(info);
				$(item).addClass( op['current'] ).insertAfter( info.box );
				info.box.remove();
				var current = $( op['card-small'] ).filter( function(){
					return $(this).hasClass( op['current'] );
				} );
				this._triggerCurrent(current);
			},
			_triggerCurrent : function( current ){
				var op = this.options;
				$( op['card-right-title'] ).removeClass( op['right-title-show'] );
				current.removeClass( op['current'] );
				current.trigger( 'click' );
			},
			_instanceItem : function( info ){
				var op = this.options,
					item = $( info.style ),
					title_area = item.find( op['card-title'] ),
					source_type = item.find( op['source-type'] ),
					source_id = item.find( op['source-id'] ),
					source_from = item.find( op['source-from'] ),
					brief_area = item.find( op['card-describe'] ),
					imgs = item.find('img');
				$('<input name="title[]" type="hidden" class="source-title" />').appendTo( item );
				$('<input name="brief[]" type="hidden" class="source-brief" />').appendTo( item );
				var title_hidden = item.find('.source-title');
				var brief_hidden = item.find('.source-brief');
				item.attr('_id',info.id);
				item.attr( '_add', info.add );
				title_hidden.val( info.title );
				title_area.html( info.title );
				source_type.val( info.type );
				source_id.val( info.source_id );
				source_from.val( info.source_form );
				
				this._instanceBaoliao( item, info );
				
				if( imgs.length > 1 ){
					imgs.each(function(key,value){
						$(this).attr('src',info['list_src'][key]);
					});
				}else{
					imgs.attr('src' ,info['src']);
				}
				if( brief_area.length ){
					brief_area.html( info.brief );
					brief_hidden.val( info.brief );
				}
				return item[0];
			},
			
			_instanceBaoliao : function( item, info ){
				if( item.hasClass('card-baoliao-type') ){
					var username_item = item.find('.baoliao-username'),
						time_item = item.find('.baoliao-time');
					username_item.text( info.username );
					time_item.text( info.publish_time );
				}
			},
			
			_initStyle : function(){
				var op = this.options,
					widget = this.element,
					all_style = widget.find( op['style-type'] ),
					first = all_style.eq( 0 );
				all_style.removeClass( op['select-type'] );
				first.addClass( op['select-type'] );
				//this._initrotateStyle( all_style );
			},
			_initrotateStyle : function( all_style ){
				var op = this.options,
					box = this._decideBox(),
					content_box = box.content_box;
				if( content_box.find('.card-rotate-type').length ){
					all_style.removeClass( op['select-type'] );
					all_style.each( function( key, value){
						if( $(this).data('type') == 'style17' ){
							$(this).addClass( 'card-select-cur' );
							return false;
						}
					} );
				}
			},
			_initRotatePreview : function(){
				var op = this.options,
					box = this._decideBox(),
					content_box = box.content_box,
					rotate_items = content_box.find('.card-rotate-type').clone().removeClass();
				//console.log( rotate_items.length );
				$('#rotate-tmpl').tmpl({}).insertBefore( content_box );
				var rotate_wrap_box = this.rotate_wrap_box = this.element.find('.rotate-wrap-box');
				rotate_wrap_box.find('.switch_list').html( rotate_items );
				rotate_wrap_box.hg_switchable({
					visible : 1,
					autoplay : true,
			 		effect : 'scrollLeft'
			 	});
			},
			_clearRotatePreview : function(){
				
			},
			_delItem : function(){
				var op = this.options,
					box = this._decideBox(),
					select_item = box.select_item,
					content_box = box.content_box;
				if( select_item.length ){
					select_item.remove();
					this.hideTitle();
				}else{
					jAlert('请选择要删除的卡片内容!','删除提醒');
				}
			},
			
			_selectItem : function( event ){
				var op = this.options,
					self = $(event.currentTarget);
				var title = self.find( op['card-title'] ).text(),
					brief = self.find('.source-brief').val(),
					type_box = self.find( op['card-type'] ),
					img_box = self.find( op['card-img'] ),
					type_id = self.find('.source-type').val(),
					type = '', 
					src = '',
					id = self.attr( '_id' ),
					add = self.attr( '_add' ),
					content_createtime = id ? this.globalinfo[id]['content_createtime'] : '';
				if( type_box.length ){
					type = type_box.html();
				}
				if( img_box.length ){
					src = img_box.find('img').attr('src');
				}
				self.toggleClass( op['current'] );
				self.siblings().removeClass( op['current'] );
				self.hasClass( op['current'] ) && this._asycStyle( type_id );
				var ismenu = this._isClassnameType( self[0], 'card-menu-type' );
				if( self.hasClass( op['current'] ) ){
					this._restore();
					this._showTitle( title,type,src ,id, add, self,brief,content_createtime);
					this._ismenuMode( ismenu, self );
				}else{
					this.hideTitle();
					ismenu && this._restore();
					ismenu && this._menuTip( false );
				}
			},
			
			_restore :function(){
				var op = this.options,
					widget = this.element;
				var source_window = widget.find( op['cardFrame'] )[0].contentWindow,
					publish_box = source_window.$('body'),
					form = publish_box.find( op['content-form'] ),
					publish_btn = publish_box.find( '#data_source' ).find( 'li:last' ).find('a');
				publish_btn.trigger( 'click' );	
				publish_box.publish( 'clearForm',form  );
				form.find( 'input[name="a"]' ).val( 'diycontent' );
				form.find( 'input[name="id"]' ).remove();
				var type = form.find( 'input[name="type"]' );
				type.length && type.remove();
			},
			_showTitle : function(title,type,src,id, add, self,brief,content_createtime){
				var op = this.options,
					widget = this.element,
					title_box = widget.find( op['card-right-title'] ),
					title_item = title_box.find( op['title'] ),
					brief_item = title_box.find( '.brief' ),
					createtime_item = title_box.find( '.publish-time' ),
					type_item = title_box.find( op['card-type'] ),
					img_item = title_box.find( 'img' ),
					edit_btn = title_box.find( op['card-edit'] );
				var ismenu = this._isClassnameType( self[0],'card-menu-type' );
				var isweather = this._isClassnameType( self[0],'card-weather-type' );
				var isformstyle = this._isClassnameType( self[0],'card-formstyle-type' );
				var isdynamicstyle = this._isDynamicStyle( self );
				if( ismenu || isweather || isformstyle || isdynamicstyle ){
					edit_btn.hide();
					title_item.hide();
					brief_item.hide();
					type_item.hide();
					createtime_item.hide();
					img_item.hide();
					title_box.addClass( op['right-title-show'] );
					return;
				}
				title_box.attr( '_id', id );
				title_box.attr( '_add', add );
				title_item.html( title ).show();
				brief_item.html( brief ).show();
				content_createtime ? createtime_item.html( content_createtime ) : createtime_item.html('') ;
				if( +this.globalinfo[id]['form'] ){
					edit_btn.hide();
					title_item.attr( 'contenteditable', true );
					brief_item.attr( 'contenteditable', true );
				}else{
					this._controlSave(false);
					edit_btn.show();
					title_item.attr( 'contenteditable', false );
					brief_item.attr( 'contenteditable', false );
				}
				if( type ){
					type_item.show().html( type );
				}else{
					type_item.hide();
				}
				if( src ){
					img_item.show().attr({'src':src});
				}else{
					img_item.hide();
				}
				title_box.addClass( op['right-title-show'] );
			},
			hideTitle : function(){
				var op = this.options,
					widget = this.element,
					title_box = widget.find( op['card-right-title'] );
				title_box.removeClass( op['right-title-show'] );
			},
			_editCardContent : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['card-right-title'] );
				var id = item.attr( '_id' ),
					info = this.globalinfo[id];
				info.id = id;
				this._opendefineForm( info );
			},
			
			_publishEditSave : function( event ){
				var _this = this,
					op = this.options,
					self = $( event.currentTarget ),
					box = self.closest( op['card-right-title'] );
				var title = box.find( op['title'] ).text(),	
					brief = box.find('.brief').text(),
					sycn_title = 0,
					id = box.attr( '_id' ),
					content_id = $('.card-item.current').attr( '_id' ),
					url = './run.php?mid=' + gMid + '&a=edit_title',
					info = {};
				if( box.find( '.is-sycn' ).attr('checked') ){
					sycn_title = 1;
				}
				info.title = title;
				brief && ( info.brief = brief );
				info.sycn_title = sycn_title;
				if( box.attr('_add') ){
					info.content_id = id;
				}else{
					info.id = id;
				}
				$.globalAjax( self, function(){
					return $.getJSON( url, info, function(){
							var item = $( '.' + op['current'] );
							box.find( op['publish-edit'] ).hide();
							item.find( op['card-title'] ).text( info.title );
							item.find( '.source-title' ).val( info.title  );
							_this._asycBrief( item, brief );
						} );
				} );
			},
			
			_asycBrief : function( item, brief ){
				var brief_item = item.find('.card-describe'),
					brief_hidden = item.find('.source-brief');
				if( brief && brief_item.length ){
					brief_item.text( brief );
					brief_hidden.val( brief );
				}
			},
			
			_showEdit : function( event ){
				this._controlSave(true);
			},
			_hideEdit : function( event ){
				//this._controlSave(false);
			},
			
			_controlSave : function( bool ){
				var op = this.options,
					box =  this.element.find( op['card-right-title'] ),
					publish_save = box.find( op['publish-edit'] );
				bool ? publish_save.show() :  publish_save.hide();
			},
			_selectStyle : function( event ){
				this._initData();
				this._restore();
				var _this = this,
					op = this.options,
					self = $(event.currentTarget),
					box = this._decideBox(),
					style = self.find( op['style-mode'] ).val(),
					allnormal_style_dom_box = $('.card-select-normalstyle'),
					allnormal_style_dom = allnormal_style_dom_box.find('.style-type');
				var oldselected = allnormal_style_dom_box.find('.card-select-cur');
				allnormal_style_dom.removeClass( op['select-type'] );
				if( self.hasClass( op['select-type'] ) ) return;
				self.addClass( op['select-type'] );
				this._selectStyleCallback( self );
				// var isrotate = this._isClassnameType( style,'card-rotate-type' );
				// if( isrotate ){
					// this._jConfirm_Mode( true, null, self, '轮转图');
					// return;
				// }
				// if( !isrotate ){
					// var content_box = box.content_box;
					// if( content_box.find('.card-rotate-type').length ){
						// this._jConfirm_Mode( false, null, self, '轮转图', function(bool){
							// if( bool ){
								// $.proxy( _this._selectStyleCallback, _this );
							// }else{
								// allnormal_style_dom.removeClass(op['select-type']);
								// oldselected.length && oldselected.addClass( op['select-type'] );
							// }
						// });
					// }else{
						// this._selectStyleCallback( self );
					// }
				// }
				
			},
			_selectStyleCallback : function( self ){
				var box = this._decideBox(),
					op = this.options,
					select_item = box.select_item,
					style = self.find( op['style-mode'] ).val(),
					type = self.data('type'),
					info = {};
				info.type = type;
				info.style = style;
				var ismenu = this._isClassnameType( style, 'card-menu-type' );
				if( ismenu ){
					this._ismenuStyle( info );
					return;
				}
				var ismenucurrent = this._ismenuCurrent();
				if( ismenucurrent ){
					var select_item = this._decideBox().select_item;
					select_item.trigger('click');
				}
				var ismovie = this._isClassnameType( style,'card-movie-type' );
				if( ismovie ){
					this._ismovieStyle();
					return;
				}
				var isweather = this._isClassnameType( style,'card-weather-type' );
				var isformstyle = this._isClassnameType( style,'card-formstyle-type' );
				if( isweather || isformstyle ){
					this._immediateaddStyle( info );
					return;
				}
				var ismovie = this._isClassnameType( style,'card-movie-type' );
				if( ismovie ){
					this._ismovieStyle();
					return;
				}
				if( select_item.length ){
					var id = select_item.attr('_id'),
						content_id = select_item.find( op['source-id'] ).val(),
						data = this.globalinfo[id];
					if( data ){
						info.id = id;
						info.source_id = content_id;
						info.source_form = data['form'];
						info.type = type;
						info.style = style;
						info.title = data['title'];
						info.brief = data['brief'];
						info.list_src = data['listpic'];
						info.src = data['index_url'];
						info.box = select_item;
						var is_ok = this._styleDecide( data , type );
						if( !is_ok ){
							jAlert('此样式不适合!','卡片提醒');
							return;
						}else{
							this._editItem( info );
						}
					}
				}
			},
			_styleDecide : function( data, type ){
				var is_ok = true;
				if( !data['index_url'] && ( type != 'style5' && type !='style16' ) ){
					is_ok = false;
				}
				if( $.isArray( data['listpic'] ) && !data['listpic'].length && type == 'style4' ){
					is_ok = false;
				}
				return is_ok;
			}, 
			_saveEdit : function(){
				var op = this.options,
					widget = this.element;
				widget.find( op['editor-card-form'] ).submit();
			},
			_editFormSubmit : function( event ){
				var _this = this,
					op = this.options,
					form = $(event.currentTarget),
					button = form.find('input[type="submit"]');
				var title = $.trim( form.find( op['card-name'] ).val() );
				if( !title ){
					jAlert('请输入卡片标题!','卡片提醒');
					return false;
				}
				var order_ids = this.record_order(form);
				form.find( op['order-ids'] ).val( order_ids );
				$('.card-left').card_list('exitSort');
				var stop = $.globalLoad( button );
				form.ajaxSubmit({
					dataType : 'json',
					success : function(){
						stop();
						_this._editAfter();
					},
					error : function(){
						stop();
						jAlert( '你没有权限做此操作!','权限提醒' );
					}
				});
				return false;
			},
			_editAfter : function(){
				var op = this.options,
					list = $( op['card-left'] );
				this.clearEdit();
				list.card_list('clearCurrent');
				list.find( op['card-add'] ).trigger('click');
				this.reset();
			},
			styleIsOnlyCustom : function(){
				var isonlycustomArray = ['card-menu-type', 'card-movie-type'];
					current_style_dom = this.element.find('.card-select-normalstyle').find('.card-select-cur'),
					style_hidden = current_style_dom.find('input.style-mode').val(),
					isonly = false;
				$.each( isonlycustomArray, function( key, value ){
					if( $(style_hidden).hasClass( value ) ){
						isonly = true;
						return false;
					}
				} );
				return isonly;
			},
			_validatenumber : function( event ){
				var self = $(event.currentTarget),
					val = +$.trim( self.val() ),
					default_val = self.attr('_default');
				val = ( val && val > 0 ) ? val : default_val ;
				self.val( val );
				
			}
		});	
		
	})($);
	
	$('.card-left').card_list();
	$('#card-tmpl').add_card();
	$('.m2o-card').view();
});
