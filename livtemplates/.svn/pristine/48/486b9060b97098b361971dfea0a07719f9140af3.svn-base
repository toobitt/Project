jQuery(function(){

function limit(){
	jAlert( '你没有权限做此操作!','权限提醒' );
}
	
(function($){
	var controll = {
			delajax : function( ids, obj ,method ){
				var url = './run.php?mid=' + gMid + '&a=' + method;
				$.getJSON(url,{id : ids},function(){
					if( obj.length > 1 ){
						var tip = "您确认批量删除选中记录吗？";
					}else{
						var tip = "您确定删除该条内容吗？";
					}
					jConfirm( tip , '删除提醒' , function( result ){
						if( result ){
							object.remove();
						}
					} );
				}).error(function(){
					limit();
				});
			},
			auditajax : function( ids, obj , method ){
				var url = './run.php?mid=' + gMid + '&a=' + method;
				$.getJSON(url,{id : ids},function(){
					if( obj.length > 1 ){
						var tip = "您确认批量审核选中记录吗？";
					}else{
						var tip = "您确定审核该条内容吗？";
					}
					jConfirm( tip , '审核提醒' , function( result ){
						if( result ){
							obj.text('已审核').css({'color':'#17b202'});
						}
					} );
				}).error(function(){
					limit();
				});;
			}
		};
		$('.m2o-each').geach({
			'audit' : function( event, _this ){
				var self = $(event.currentTarget),
					id = self.closest( _this.element ).data('id'),
					method = self.data('method');
				controll.auditajax( id, self , method );
			}
		});
		$('.m2o-list').glist({
			'batchDelete' : function(event,_this){
				var op = _this.options,
					obj = _this.element.find( op['each'] + '.selected' ),
					self = $(event.currentTarget),
					method = self.data('method');
				var ids = obj.map(function(){
					return $(this).data('id');
					}).get().join(',');
				controll.delajax( ids, obj ,method );
			},
			'batchAudit' : function(event,_this){
				var op = _this.options,
					obj = _this.element.find( op['each'] + '.selected' ),
					status = obj.find('.m2o-state'),
					self = $(event.currentTarget),
					method = self.data('method');
				var ids = obj.map(function(){
					return $(this).data('id');
					}).get().join(',');
				controll.auditajax( ids, status , method );
			}
		});

		$(function(){
			$('body').on({
		        'goptiondelete' : function(event, _this){
		            var widget = _this.element,
		            	op = _this.options,
		            	obj = widget.closest( '.m2o-each' ),
		            	id = obj.data('id'),
		            	self = widget.find( op['m2o-delete'] ),
		            	method = self.data('method');
		            controll.delajax( id, obj ,method );
		        }
		    }, '.m2o-option');
		});
})($);
});