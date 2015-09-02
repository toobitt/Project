/**
 * 获取ui列表及ui下的属性
 * */
$(function(){
	$('.modal').on('click', '.close-pop', function(){
		$(this).closest('.modal').removeClass('in').hide();
	});
	$('.show-ui-list-pop').click(function(){
		$('#ui-list-pop').addClass('in').show();
	});
	(function(){
		var UiList = function(options){
			this.op = options;
			this.el = $( options.el );
			this.url = './run.php?mid='+ gMid +'&a=getUIData';
			this.init();
			this.bindEvent();
		};
		UiList.prototype.init = function(){
			$.getJSON( this.url, function( json ){
				$('#ui-list-tpl').tmpl({
					data : json
				}).appendTo('#ui-list-pop .list-group');
			});
		};
		UiList.prototype.bindEvent = function(){
			var _this = this;
			this.el
				.on('click', '.get-attr-btn', $.proxy(_this.ajaxAttrs, _this));
		};
		UiList.prototype.ajaxAttrs = function(e){
			var target = $( e.currentTarget ),
				parent = target.closest('.list-group-item'),
				id = parent.attr('_id');
			if( this.op.callback ){
				this.op.callback( target, id );
			}
		};
		window.myUIList = UiList;
	})();
	
	(function(){
		var UiAttrs = function( options ){
			this.op = options;
			this.el = $( this.op.el );
			this.body = this.el.find('.modal-body');
			this.url = './run.php?mid='+ gMid +'&a=get_attr_by_ui';
			this.bindUrl = './run.php?mid='+ gMid +'&a=bind_relate_attr';
			this.bindEvent();
			this.reset();
		};
		UiAttrs.prototype.bindEvent = function(){
			var _this = this;
			this.el
				.on('click', '.submit-selected-attrs', $.proxy(_this.submitSelectedAttrs, _this))
				.on('click', '.radio', $.proxy(_this.select, _this))
				.on('click', '.checkbox', $.proxy(_this.select, _this))
				.on('click', '.list-group-item', $.proxy(_this.select, _this))
				.on('click', '.pagination li', $.proxy(_this.flip, _this));
				
		};
		UiAttrs.prototype.select = function( e ){
			var target = $(e.currentTarget);
			if( target.hasClass('radio') ){
				this.radioSelect( target );
			}else if(target.hasClass('checkbox')){
				this.checkboxSelect( target );
			}else{
				this.liSelect( target );
			}
		};
		UiAttrs.prototype.triggerSelect = function(dom, boolean){
			if( boolean ){
				dom.addClass('list-group-item-info selected');
			}
			dom.find('.checked').prop('checked', boolean);
			dom.find('.checked').prop('checked', boolean);
			
		},
		UiAttrs.prototype.unSelectAll = function(){
			this.el.find('.list-group-item').removeClass('list-group-item-info selected');
			this.el.find('.radio').prop('checked', false);
		};
		UiAttrs.prototype.radioSelect = function( target ){
			var li = target.closest('.list-group-item');
			this.unSelectAll();
			li[ target.prop('checked') ? 'addClass' : 'removeClass' ]('list-group-item-info selected');
		};
		UiAttrs.prototype.checkboxSelect = function( target ){
			var li = target.closest('.list-group-item');
			li[ target.prop('checked') ? 'addClass' : 'removeClass' ]('list-group-item-info selected');
		};
		UiAttrs.prototype.liSelect = function( target ){
			var isSelect = target.hasClass('selected');
			if( target.find('.radio').length ){
				this.unSelectAll();
			}
			target[ isSelect ? 'removeClass' : 'addClass' ]('list-group-item-info selected ');
			target.find('.checkbox').prop( 'checked', !isSelect );
			target.find('.radio').prop( 'checked', !isSelect );
		};
		UiAttrs.prototype.flip = function( e ){
			var target = $(e.currentTarget);
			this.currentPage = target.attr('_index');
			this.ajaxUiAttrs(target);
		};
		UiAttrs.prototype.reset = function( id ){
			this.attrId = id;
			this.dataTotle = 0;
			this.currentPage = 0;
			this.pageTotle = 0;
		};
		UiAttrs.prototype.ajaxUiAttrs = function( loadPos ){
			var _this = this,
				params = {
					ui_id : _this.attrId,
//					_count : _this.count || 20,
					_count : 100,
					_offset : _this.currentPage * 20
			};
			$.globalAjax( loadPos, function(){
				return $.getJSON( _this.url, params, function( json ){
					_this.dataTotle = json.total.total;
					_this.pageTotle = parseInt( _this.dataTotle / params._count ) + ( _this.dataTotle % 20 ? 1 : 0 );
					var page = [];
					for( var i=0;i<_this.pageTotle; i++ ){
						page.push({
							pageNum : i+1,
							currentPage : _this.currentPage
						});
					}
					var data = {
							data : json.data,
							page : page
					};
					$('#ui-attr-tpl').tmpl( data ).appendTo( $('#ui-attr-pop .list-group').empty() );
					$('#ui-attr-pop').addClass('in').show();
					if( _this.op.afterAjaxData ){
						_this.op.afterAjaxData( _this.body );
					}
				});
			});
		};
		UiAttrs.prototype.getIds = function(){
			var currentItem = this.el.find('.list-group-item.selected'),
				ids = currentItem.map(function(){
					return $(this).attr('_id');
				}).get().join();
			return {
				currentItem : currentItem,
				ids : ids
			};
		};
		UiAttrs.prototype.submitSelectedAttrs = function( e ){
			var target = $(e.currentTarget),
				_this = this;
			var current = _this.getIds();
			if( !current.ids ){
				target.myTip({
					string : '请选择要绑定的属性',
					color : '#a94442',
					width : 150
				});
				return;
			}
			if( this.op.callback ){
				this.op.callback( target, current );
			}
		};
		window.myUIAttrs = UiAttrs;
	})();
});