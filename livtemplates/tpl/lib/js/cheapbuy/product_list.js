$(function(){
	(function($){
		$.widget('m2o.product_list',{
			options : {
				tpl : '',
				url : ''
			},
			_init : function(){
				this._on({
					'click .product-item' : '_select',
					'click .common-switch, .audit, .check-order' : '_stop',
					'click .order-btn' : '_order'
				});
				this._initSwitch();
				this.cache = {};
			},
			_order : function( event ){
				var btn = $(event.currentTarget);
				var wrap = this.element.find('.product-list');
				var _this = this;
				if( !btn.attr('_needsave') ){
					btn.myTip({
						string : '排序模式已开启，拖动进行排序',
						delay : 2500,
						width : 200,
						dtop : 30,
						dleft : -100
					});
					$.extend(_this.cache,{orderid : []});
					$('.m2o-each').each(function(k, v){
						_this.cache.orderid[k] = $(this).attr('_orderid');
					});
					wrap.sortable({
						start : function(){
							btn.text('保存排序').attr('_needsave',true);
							btn.css('background','orange');
						},
					});
					return;
				}
				$.globalAjax(wrap,function(){
					var url = './run.php?mid='+gMid+'&a=drag_order';
					var content_id = wrap.find('.m2o-each').map(function(){
						return $(this).attr('_id');
					}).get().join();
					var order_id = _this.cache.orderid.join(',');
					return $.getJSON(url,{content_id:content_id,order_id:order_id},function(json){
						btn.myTip({
							string : '保存成功',
							delay : 2500,
							dtop : 30,
						});
						btn.text('开启排序').removeAttr('_needsave');
						btn.css('background','#1bbc9b')
					});
				});
			},
			_initSwitch : function(){
				var switchs = this.element.find('.common-switch'),
					_this = this;
				switchs.each(function(){
					var id = $(this).closest('li').attr('_id');
					var val = $(this).hasClass( 'common-switch-on' ) ? 100 : 0;
					$(this).hg_switch({
						'value' : val,
						'callback' : function( event, value ){
							var is_on = 0;
							( value > 50 ) ? is_on = 1 : is_on = 0;
							_this._onOff(id, is_on);
						}
					});
				});
				$('.m2o-each').geach();
				$('.product-wrap').glist();
			},
			_onOff : function( id, is_on ){
				var _this = this;
				var url = './run.php?mid=' + gMid + '&a=xxx';
				$.getJSON( url, {id : id, is_on : is_on} ,function( data ){
					
				})
			},
			_select : function( event ){
				var self = $(event.currentTarget);
				self.toggleClass('selected');
			},
			_stop : function( event ){
				event.stopPropagation();
			},
		});
	})($);
	$('body').product_list();
});