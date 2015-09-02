/**
 * @author chenmengjie
 * @date 2014-11-24
 * 拾色器插件，依赖spectrum.js
 * 两种调用方式：
 * 	1.	$(dom).hg_spectrum(); 		最普通的调用方式，无样式
 *  2.	$(dom).flatuiSpectrum();	faltui风格
 * 	参数说明见$.fn.flatuiSpectrum.defaults
 * 
 * */
(function(){
	$.fn.hg_spectrum = function( option ){
		var op = $.extend({}, {
			showInput: true,
			showPalette:true,
			chooseText: "确定",
			cancelText: '取消',
			palette: [
			          ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
			          ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
			          ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
			          ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
			          ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
			          ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
			          ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
			          ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			      ],
		}, option);
		return this.each(function(){
			$(this).spectrum( op );
		});
	};
})();

(function($){
	var css = ''+
				'.flatui-spectrum-box{position:relative;height:40px;width:250px;border:1px solid #d8dfe7;border-radius:3px;padding:9px;}'+
				'.flatui-spectrum-box .color-prev-box{position:relative;float:left;width:20px;height:20px;border-radius:3px;overflow:hidden;}'+
				'.flatui-spectrum-box .color-prev-bg{display:inline-block;width:20px;height:20px;background:URL('+ RESOURCE_URL +'transparentbg.png);}'+
				'.flatui-spectrum-box .color-prev-tile{position:absolute;top:0;left:0;width:100%;height:100%;}'+
				'.flatui-spectrum-box .color-input, .flatui-spectrum-box .alpha-input{border-radius:3px;background:#d9dfe7;border:none;height:20px!important;}'+
				'.flatui-spectrum-box .color-input{width:60px;margin:0 3px 0 5px;}'+
				'.flatui-spectrum-box .alpha-input{width:50px;}'+
				'.flatui-spectrum-box .arrow{position:absolute;right:0;top:0;width:20px;height:100%;cursor:pointer;}'+
				'.flatui-spectrum-box .arrow:after{position:absolute;content:"";width:0;height:0;border:6px solid transparent;border-top-width:8px;border-top-color:#d8dfe7;top:50%;left:0;margin-top:-4px;}'+
				'.flatui-spectrum-box .sp-replacer{width:0px;height:0px;padding:0;border:0;visibility:hidden;}'+
			'';
	$('<style/>').attr('style', 'text/css').appendTo('head').html(css);
	
	var FlatuiSpectrum = function(element, options){
		this.init( element, options );
	};
	FlatuiSpectrum.prototype = {
			constructor : FlatuiSpectrum,
			init : function(element, options){
				var $el = this.$element = $(element);
				this.options = $.extend({}, $.fn.flatuiSpectrum.defaults, options);
				this.$newEl = $( this.options.template )
				$el.before( this.$newEl ).hide();
				this.setState();
				this.initSpectrum();
			},
			setState : function(){
				if( !this.options.showAlpha ){
					this.$newEl.find('.alpha-input').hide();
				}
				this.$newEl.find('.color-prev-tile').css({
					background: this.options.color,
					opacity : this.options.alpha
				});
				this.$newEl.find('.color-input').val( this.options.color );
				this.$newEl.find('.alpha-input').val( this.options.alpha );
			},
			initSpectrum : function(){
				var _this = this;
				this.$newEl.on('click', '.arrow', function(){
					_this.$element.spectrum("toggle");
					return false;
				});
				this.$element.spectrum( this.options );
			},
	};
	
	$.fn.flatuiSpectrum = function( option ){
		return this.each(function(){
			var $this = $(this),
				data = $this.data('spectrum'),
				options = $.extend({}, $.fn.flatuiSpectrum.defaults, typeof option == 'object' && option);
			if( !data ){
				$this.data('spectrum', (data = new FlatuiSpectrum(this, options)));
			}
		});
	};
	
	$.fn.flatuiSpectrum.defaults = {
			showInput: true,		//是否显示当前颜色指示框
			showPalette:true,		//是否显示右侧可调色板
			chooseText: "确定",		//确定按钮显示文字
			cancelText: '取消',		//取消按钮显示文字
			color : '#000',			//初始颜色值
			alpha : 1,				//透明度
			change : $.noop,		//颜色改变时的回调
			palette: [				//左侧色块栏显示颜色
			          ["#000","#444","#666","#999","#ccc","#eee","#f3f3f3","#fff"],
			          ["#f00","#f90","#ff0","#0f0","#0ff","#00f","#90f","#f0f"],
			          ["#f4cccc","#fce5cd","#fff2cc","#d9ead3","#d0e0e3","#cfe2f3","#d9d2e9","#ead1dc"],
			          ["#ea9999","#f9cb9c","#ffe599","#b6d7a8","#a2c4c9","#9fc5e8","#b4a7d6","#d5a6bd"],
			          ["#e06666","#f6b26b","#ffd966","#93c47d","#76a5af","#6fa8dc","#8e7cc3","#c27ba0"],
			          ["#c00","#e69138","#f1c232","#6aa84f","#45818e","#3d85c6","#674ea7","#a64d79"],
			          ["#900","#b45f06","#bf9000","#38761d","#134f5c","#0b5394","#351c75","#741b47"],
			          ["#600","#783f04","#7f6000","#274e13","#0c343d","#073763","#20124d","#4c1130"]
			      ],
			template : '<div class="flatui-spectrum-box">'+
							'<div class="color-prev-box">'+
								'<span class="color-prev-bg"></span>'+
								'<span class="color-prev-tile"></span>'+
							'</div>'+
							'<input class="color-input" type="text" />'+
							'<input class="alpha-input" type="text" />'+
							'<span class="arrow"></span>'+
						'</div>'
	};
})(window.jQuery);