!function ($) {
	$.plugin = function(name, object) {
		$.fn[name] = function(options) {
			var args = Array.prototype.slice.call(arguments, 1);
			return this.each(function() {
				var instance = $.data(this, name);
				if (instance) {
					instance[options].apply(instance, args);
				} else {
					instance = $.data(this, name, new object(options, this));
				}
			});
		};
	};
	
	var pluginName = 'role_select';
	
	function SelectRole( options, element ){
		this.options = $.extend({}, $.fn[pluginName].defaults, options);
		this.el = $( element );
		this.el.on('click', 'input', $.proxy(this.addSel, this))
			.on('click', '.select_span', $.proxy(this.removeSel, this));
		this.init();
		this.cssInit();
	}
	
	SelectRole.prototype =  {
		
		constructor : SelectRole,
		
		init : function(){
			var source = this.options.source,
				per = this.options.per;
			if( $.isArray( source ) && source[0] ){
				var i=0, dataline = {};
					lenline = [];
				var render = $.map( source[0], function(vv, kk){
					return {
						id : kk,
						value : vv,
					}
				});
				this.render( render );
			}
		},
		
		render : function( render ){
			var op = this.options;
			var line = Math.ceil(render.length / op.per);
			var data = {
				render : render,
				dline : new Array( line )
			}
			var els = $.tmpl( op.label_tpl, data, {
				name : op.name,
				per : op.per
			});
			els.appendTo( this.el );
			var select = $.isArray( op.select ) && op.select || typeof op.select == 'string' && op.select.split(','); 
			if( select[0] ){
				$( 'input[name="' + this.options.name + '[]"]', this.el ).val( select );
				this.addLabel();
			}
		},
		
		cssInit : function(){
			var op = this.options;
			if( !op.cssInit && op.css ){
				$('<style/>').attr('style', 'text/css').appendTo('head').html( op.css );
				op.cssInit = true
			}
		},
		
		addSel : function( event ){
			var self = $(event.currentTarget);
			if( self.closest('label').hasClass('selected') || !self.prop('checked') ){
				self.prop('checked', true);
				return;
			}
			self.closest('label').addClass('selected');
			var data = {
				id : self.val(),
				value : self.closest('label').attr('title')
			}
			this.el.find('.select_label').append( $.tmpl(this.options.select_tpl, data) );
			this.syncSel();
		},
		
		removeSel : function( event ){
			var self = $(event.currentTarget),
				id = self.attr('_id');
			var label = this.el.find('.select_item label.selected[_id="' + id + '"]');
			label.removeClass('selected').find('input').prop('checked', false);
			self.detach();
			this.syncSel();
		},
		
		syncSel : function(){
			var select_area = this.el.find('.select_label');
			select_area[( select_area.find('span').length > 0 ? 'add' : 'remove') + 'Class']('hasSelect');
		},
		
		addLabel : function(){
			var dSelect = [];
			this.el.find('.select_list input:checked').each(function(){
				$(this).closest('label').addClass('selected');
               	dSelect.push({
               		id : $(this).val(),
               		value : $(this).closest('label').attr('title')
               	});
            });
			this.el.find('.select_label').addClass('hasSelect').append( $.tmpl(this.options.select_tpl, dSelect) );
		},
	};
	
	$.plugin(pluginName, SelectRole);
	
	$.fn[pluginName].defaults = {
		source : '',		//渲染数据
		select : '',	//默认选中的数据
		name : 'admin_role_id',		//name值
		per : 6,				//6个一行
		label_tpl : '' +
			'<p class="select_label"><em>请至少选择一个角色</em></p>' +
			'<ul class="select_list">' +
			'{{each dline}}' +
				'<li class="select_item clear" _line="${$index}">' +
				'{{each(k, v) render}}' +
					'{{if k <= $item.per * ($index + 1) - 1 && k >= $item.per * $index }}' +
					'<label _id="${v.id}" title="${v.value}"><input type="checkbox" name="${$item.name}[]" value="${v.id}">${v.value}</label>' +
					'{{/if}}' +
				'{{/each}}' +
					'' +
				'</li>' +
			'{{/each}}' +
			'</ul>' +
			'',
		select_tpl : '' +
			'<span class="select_span" _id="${id}">${value}</span>' +
			'', 
		css : '' +
			'.select_role{float:left; width:560px; }' +
			'.select_role label{margin-right:10px; width:80px; overflow: hidden; white-space:nowrap; cursor:pointer; }' +
			'.select_role label.selected{opacity:0.2; }' +
			'.select_role input[type="checkbox"]{height:13px!important; margin-right:5px; }' +
			'.select_role li{height:30px; line-height: 30px; padding:0 10px; }' +
			'.select_role li:nth-child(odd){background-color:#f3f4f9; }' +
			'.select_label em{display:inline-block; margin:4px 0 12px; color:#bebebe; font-style:normal; }' +
			'.select_label.hasSelect em{display:none; }' +
			'.select_label span{display:inline-block; margin:0 10px 10px 0; padding:3px 8px; background-color:#d8e8f7; color:#145AA0; border-radius:3px; cursor:pointer; }' +
			'',
		cssInit : false
	};
}( jQuery );
