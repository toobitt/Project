$(function(){
	(function($){
		$.widget('m2o.orderlist',{
			options : {},
			_init : function(){
				this._on({
					'submit .order-search' : '_submit'
				});
				this._initWidget();
				this.element.glist();
			},
			_initWidget : function(){
				$('.m2o-each').geach({
					audit_url : './run.php?mid=' + gMid + '&a=audit_order&ajax=1',
					delete_url : './run.php?mid=' + gMid + '&a=del_order&ajax=1',
					custom_delete : true,
					deleteCallback : function(event, param){
						param.key = 'id';
						jConfirm('确定要删除此订单么？','删除提示',function(result){
							if( result ){
								$.handle.delajax(param);
							}
						});
					}
				});
			},
			_submit : function( event ){
				var _this = this;
				event.preventDefault();
				var form = $(event.currentTarget);
				form.ajaxSubmit({
					dataType : 'json',
					success : function( json ){
						var data = json[0];
						console.log(data);
						_this.refresh(data);
					}
				});
			},
			refresh : function( data ){
				var list = this.element.find('.order-wrap');
				list.empty();
				if( data['info'] ){
					$.MC.tpl.tmpl(data['info']).appendTo(list);
					this._initWidget();
				}else{
					$('<p class="no-content">没有符合条件的订单！</p>').appendTo(list);
				}
				$('.page-link').page('refresh',data['page_info']);
			},
		});
	})($);
	$.MC = {
		list : $('section'),
		tpl : $('#list-item-tpl'),
		page : $('.page-link')
	};
	$.MC.list.orderlist();
});