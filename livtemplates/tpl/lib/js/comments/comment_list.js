$(function(){
	(function(){
		$('.common-list').glist({
			each : '.tile-item'
		});
		$('.tile-item').geach();
		var List = function(el){
			this.el = $( el );
			this.auditUrl = './run.php?mid='+ gMid +'&a=audit';
			this.delUrl = './run.php?mid='+ gMid +'&a=delete&ajax=1';
			this.sortUrl = './run.php?mid='+ gMid +'&a=drag_order&ajax=1';
			this.ids = '';
			this.sortIds = '';
			var _this = this;
			this.el
				.on('click', 'a, .stop', function( event ){
					event.stopPropagation();
				})
				.on('click', '.audit-btn', function(event){
					_this.audit( $(this) );
				})
				.on('click', '.del-btn', function( event ){
					_this.del( $(this) );
				})
				.on('click', '.tile-item', function(){
					$(this).geach('toggleSelect', !$(this).hasClass('selected'));
				})
				.on('click', '.batch-btn', function(){
					_this.batchHandle( $(this) );
				})
				.on('click', '.sort-btn', function(){
					_this.sort($(this));
				});
			this.initSort();
			this.getSortIds();
		};
		
		var ajaxPackage = function(op){
			$.globalAjax(op.load, function(){
				return $.getJSON( op.url, op.param||{}, function(json){
					if( op.callback && $.isFunction(op.callback) ){
						op.callback( json );
					}
				});
			});
		};
		List.prototype.sort = function( target ){
			var now = target.attr('_now');
			var _this = this;
			if( now == 'close' ){
				target.myTip({
					string : '排序模式已开启，拖动进行排序',
					delay : 2000,
					dtop : 30,
					width : 200
				});
				this.el.find('.tile-list').sortable('option', 'disabled', false);
			}else{
				ajaxPackage({
					load : target,
					url : this.sortUrl,
					param : {
						content_id : this.getIds( this.el.find('.tile-item') ),
						order_id : this.sortIds,
						table_name : gData.tableName
					},
					callback : function( json ){
						if( json.referto ){
							target.text('开启排序').attr('_now','close').myTip({
								string : '保存成功！',
								dtop : 30
							});
							_this.el.find('.tile-list').sortable('option', 'disabled', true);
						}else{
							eval( json.callback );
						}
					}
				});
			}
		};
		List.prototype.initSort = function(){
			var list = this.el.find('.tile-list');
			var _this = this;
			list.sortable({
				cursor : 'move',
				disabled : true,
				start : function(){
					_this.el.find('.sort-btn').text('保存排序').attr('_now','open');
				}
			});
		};
		List.prototype.getIds = function( items ){
			return items.map(function(){
				return $(this).attr('_id');
			}).get().join();
		};
		List.prototype.getSortIds = function(){
			 this.sortIds = this.el.find('.tile-item').map(function(){
				return $(this).attr('_orderid');
			}).get().join();
		};	
		List.prototype.audit = function( target ){
			var parent = target.closest('.tile-item');
			this.ajaxAudit({
				load : target,
				item : parent,
				id :  parent.attr('_id'),
				audit : parent.attr('_status') == 1 ? 2 : 1
			});
		};
		List.prototype.ajaxAudit = function( op ){
			ajaxPackage({
				load : op.load,
				url : this.auditUrl,
				param : {
					audit : op.audit,
					id : op.id,
					tablename : gData.tableName,
				},
				callback : function(json){
					op.item.find('.audit-btn')
						.css('color', gConfig['status_color'][ json[0]['status'] ] )
						.text( json[0]['status']==1 ? '已审核' : '已打回' );
					op.item.attr('_status', json[0]['status']);
					console.log( json );
				}
			});
		};
		List.prototype.del = function( target ){
			var parent = target.closest('.tile-item'),
				_this = this;
			jConfirm('确定要删除么？','删除提示',function(result){
				if( result ){
					_this.ajaxDel({
						load : target,
						item : parent,
						id :  parent.attr('_id')
					});
				}
			}).position(target);
		};
		List.prototype.ajaxDel = function( op ){
			ajaxPackage({
				load : op.load,
				url : this.delUrl,
				param : {
					id : op.id,
					tablename : gData.tableName,
				},
				callback : function( json ){
					console.log( json );
					op.item.slideUp(function(){
						op.item.remove();
					});
				}
			});
		};
		List.prototype.getSelecteds = function(){
			return this.el.find('.tile-item.selected');
		};
		List.prototype.batchHandle = function( target ){
			var name = target.text(),
				items = this.getSelecteds(),
				ids = '',
				_this = this;
			if( !items.length ){
				jAlert('请选择要'+name+'的记录', name+'提醒').position( target );
			}else{
				ids = this.getIds( items );
				jConfirm('您确认要批量'+name+'选中的记录么？', name+'提醒',function(result){
					if( !result ){
						return;
					}
					if( target.hasClass('audit') ){
						_this.ajaxAudit({
							load : target,
							item : items,
							id : ids,
							audit : target.attr('_status')
						});
					}else{
						_this.ajaxDel({
							load : target,
							item : items,
							id : ids
						});
					}
				}).position( target );;
			}
		};
		var messageList = new List('.common-list');
	})();
})