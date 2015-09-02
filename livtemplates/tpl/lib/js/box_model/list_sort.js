$(function(){
	(function($){
		$.widget('model.list_sort', {
			options : {
				sort : '.common-list-paixu',
				quit : '.quit_model',
				save : '.button_4'
			},
			
			_create : function(){
				this.gDragMode = false;		//true 排序状态
				this.list = this.element.find('.m2o-each-list');
				this.saveBtn = $('<input class="button_4" style="margin-left:10px;" type="button" value="保存排序" />');
				this.Dom = $('<div id="infotip" class="ordertip">排序模式已关闭</div>');
			},
			
			_init : function(){
				var op = this.options,
					handlers = {}; 
				handlers['click ' + op['sort'] ] = '_listSort';
				handlers['click ' + op['quit'] ] = '_quit';
				handlers['click ' + op['save']] = '_save';
				this._on(handlers);
				this._sortable();
				this._initDom();
			},
			
			_initDom : function(){
				!this.element.find('#infotip').length && this.Dom.prependTo( '.m2o-list' );
				this.tip = this.element.find('#infotip');
			},
			
			_sortable : function(){
				var _this = this;
				this.list.sortable({
					revert: true,
			        cursor: "move",
			        axis: "y",
			        scrollSpeed: 100,
			        tolerance: 'intersect',
			        stop: function(e, ui) {
			            /*每次拖动停止时检查是否需要保存*/
			            if (_this.needSave()) {
			                _this.tip.append(_this.saveBtn);
			            } else {
			                _this.saveBtn.remove();
			            }
			        },
			        disabled: true
				});
			},
			
			_listSort : function(){
				 this.openDragView();
				 this.gDragMode = true;
				 this.old_order_ids = this.getOrderId();
        		 this.old_ids = this.getIds();
			},
			
			_quit : function(){
				if(this.needSave()){		//需要保存
					if (confirm('排序已改变，您确定要放弃此次排序吗？')) {
                        this.gDragMode = false;
                        this.closeDragView("排序模式已关闭");
                        window.location.reload();
                    }
				}else{
	                 this.gDragMode = false;
	                 this.closeDragView("排序模式已关闭");
	             }
			},
			
			_save : function(){
				var ids = this.getIds().join(','),
					old_order_ids = this.old_order_ids;
				var url = "./run.php?mid=" + gMid + "&a=drag_order&ajax=1";
				$.post(url, {
		            content_id: ids,
		            order_id: old_order_ids.join(','),
				});
				this.setOrderId(old_order_ids);
				this.gDragMode = false;
				this.closeDragView("排序保存成功");
			},
			
			closeDragView : function( msg ){
				this.list.find('.m2o-paixu').removeClass('sort-pic')
						.children('input').css('visibility', 'visible');
				this.list.removeClass('gDragMode').sortable("option", "disabled", true);
				this.tip.html(msg).fadeOut(2000);
			},
			
			openDragView : function(){
				this.tip.stop().css({
					"opacity" : 1,
					"height" : 40
				}).show().html('排序模式已开启<span class="quit_model" style="color:#666;margin:0 0 0 5px;font-size:12px;cursor: pointer;">退出</span>');
				this.list.find('.m2o-paixu').addClass('sort-pic')
						.children('input').css('visibility', 'hidden');
				this.list.removeClass('gDragMode').sortable("option", "disabled", false);
			},
			
			getIds : function(){
				return this.element.find('.m2o-each').map(function(){
					return $(this).data('id') || $(this).attr('_id');
				}).get();
			},
			
			getOrderId : function(){
				return this.element.find('.m2o-each').map(function(){
					return $(this).attr('orderid');
				}).get();
			},
			
			setOrderId : function( old_order_ids ){
				this.list.find('.m2o-each').each(function(i) {
		            $(this).attr('orderid', old_order_ids[i])
		        });
			},
			
			needSave : function(){
				return this.old_ids && this.old_ids.join(',') != this.getIds().join(',');
			},
		});
	})($);
	$('.m2o-list').list_sort();
});
