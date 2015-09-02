function WatermarkEvent(number, slide) {
	this.number = number;
	this.slide = slide;
	this.editor = window['oEdit' + this.number];
	this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
	this.box = null;
	this.content = null;
	this.init();
}

jQuery.extend(WatermarkEvent.prototype, {
	init: function() {
		var self = this,
			content = '';
		content = '<div id="edit-slide-watermark'  + this.number + '" class="edit-slide-html-each">' + 
			'<div class="edit-slide-title"><span class="edit-slide-close">关闭</span>水印设置</div>' +
			'<div class="edit-slide-content edit-slide-watermark-content"></div>' +
			'</div>';
		this.slide.html( content );
		this.box = $('#edit-slide-watermark' + this.number).parent();
		this.content = this.box.find('.edit-slide-content');
		this.waitingImg = '<img class="waiting-img" src="' + RESOURCE_URL + 'loading2.gif"/>';
		this.box.on( 'click', '.watermark-option li:last', function() {
			return; 
			self.box.animate( {
				left: '-=' + self.slide.width + 'px'
			}, 200, function() {
				self.initWatermarkEditor();
			});
		}).on( 'click', '.watermark-option li:not(:last)', function() {
			var val = $(this).data('value') || '', id, name;
			val = val.split(',');
			id = val[0] || -1;
			name = val[1] || '';
			$('#water_config_id').val(id);
			$('#water_config_name').val(name);
			$(this).find('input:radio').prop('checked', true);
		}).on( 'click', '.watermark-back-title', function(event){
			var back = $(this).find('.edit-slide-back'),
    			close = $(this).find('.edit-slide-close');
    		if (event.target != back[0] && event.target != close[0]) {
    			back.trigger( 'click' );
    		} 
		});
		$(window).trigger('resize.slide');
		this.slide.addInitFunc( this.getCreateOptionViewFunc() );
	},
	getCreateOptionViewFunc: function() {
		var self = this, index = 0, loaded = false;
		return function( data ) {
			if (loaded) return;
			loaded = true;
			var url, 
				me = self,
				level = ++index;

			me.content.empty().append(me.waitingImg);
			url = gUrl.waterList;
			$.get(url, function(data) {
				if( level != index ) {
					return;
				}
				me.createOptionViewWith(data);
			}, 'json');
		}
	},
	createOptionViewWith: function( data ) {
		if ( !(typeof data == 'array' || typeof data == 'object') ) {
			data = [];
		}
		var content = '<ul class="watermark-option">', i, checkedId;
		checkedId = $('#water_config_id').val();
		for( i in data ) {
			content += this.getOneOptionHtml(data[i], checkedId);
		}
		checked = (-1 == checkedId ? 'checked="checked"' : '');
		content += '<li><div><input type="radio" name="tmpwatermarkid" value="-1" ' + checked  + ' />不使用水印</li>';
		checked = (0 == +checkedId ? 'checked="checked"' : '');
		content += '<li><div><input type="radio" name="tmpwatermarkid" value="-1" ' + checked  + ' />继承</li></ul>'
		this.content.html( content );
	},
	getOneOptionHtml: function(config, id) {
		var n = config, desc = '', end, checked = '', html;
		checked = (n.id == id ? 'checked="checked"' : '');
		html = '<li data-value="' + n.id + ',' + n.config_name + '"><div><input type="radio" name="tmpwatermarkid" ' + checked + ' />';
		if (n.type == 1) {
			html += '<p><img src="' + n.img_url + '" /></p><span title="' + n.config_name + '">' + n.config_name + '</span></div></li>';
		} else {
			html += '<a title=' + n.config_name + '>' + n.config_name + '</a></div></li>';
		}
		return html;
	},
	addOne: function(data) {
		data = data[0];
		var html = this.getOneOptionHtml(data);
		this.box.find('.watermark-option li').each(function() {
			if ( !$(this).data('value') ) {
				$(this).before(html);
				$(this).prev().trigger('click');
				return false;
			}
		});
	},
	set: function(json) {
				//return; //暂时
				this.dom.content.trigger('set', [json]);
	},
	initWatermarkEditor: function() {
		var editorView = this.getEditorView();
		this.box.append( editorView );
		if ( !this.WatermarkCtrl ) {
			//没有加载水印编辑则加载
			(function(exports, $) {
				var ctrl, self;
				ctrl = function() {
					this.init.apply(this, arguments);
				};
				$.extend(ctrl.prototype, {
					model: {
						type: 'img',
						position: 9,
						left: 0,
						top: 0,
						opacity: 1,
						img: null,
						text: '',
						size: '',
						color: ''
					},
					elements: {
						'.watermark-type a': 'watermarkType',
						'.watermark-box': 'watermarkBox',
						'.position-box': 'positionBox',
						'.opacity-slider': 'opacitySlider',
						'.watermark-opacity label': 'opacityLabel',
						'.watermark-img': 'imgList',
						'.watermark-text-info': 'textInfo',
						'.watermark-text-info li:first input': 'textInput',
						'.size-slider': 'sizeSlider',
						'.watermark-text-info li:eq(1) label': 'sizeLabel',
						'.watermark-text-color': 'textColor',
						'.watermark-box .can-drag': 'watermark'
					},
					events: {
						'.watermark-type a': 'click toggleType',
						'.position-box > div': 'drop changePosition',
						'.watermark-box .can-drag': 'dragstop changePosition',
						'.opacity-slider': 'slide changeOpacity',
						'.color-box .forecolor': 'click changeColor',
						'.size-slider': 'slide changeSize',
						'.watermark-text-info li:first input': 'keyup,focus changeText',
						'.watermark-img': 'createImgWater changeImg',
						'.save-watermark-btn': 'click save'
					},
					save: function(e) {
						if ( $(this).data('ajax') ) {
							return;
						}
						var data;
						var name = self.view.find('.watermark-name input').val();
						if ( !name.trim() ) {
							alert('请填写水印名！');
							return;
						}
						if ( self.model.type == 'text' ) {
							if ( !self.model.text.trim() ) {
								alert('水印文字不能为空！');
								return;
							}
							data = {
								config_name: name,
								water_type: 0,
								water_text: self.model.text,
								opacity: self.model.opacity,
								water_color: self.model.color,
								get_photo_waterpos: self.model.position
							};
						} else {
							if ( !self.model.img ) {
								alert('水印图片不能为空！');
								return;
							}
							data = {
								config_name: name,
								water_type: 1,
								opacity: self.model.opacity,
								get_photo_waterpos: self.model.position
							};
						}
						
						var el = $(this);
						el.data('ajax', 1);
						data.ajax = 1;
						$.ajax({
							url: gUrl.createWater,
							data: data,
							type: 'post',
							complete: function() {
								el.data('ajax', 0);
							},
							success: function(data) {
								self.view.find('.edit-slide-back').trigger('click');
								self.outer_obj.addOne(data);
							},
							error: function() {
								alert('创建失败');
							},
							dataType: 'json'
						});
					},
					init: function(view) {
						self = this;
						this.view = $(view);
						this.refreshElement();
						this.bindAllEvents();
					},
					refreshElement: function() {
						for (var key in this.elements) {
							this[this.elements[key]] = this.view.find(key);
						}
					},
					bindAllEvents: function() {
						var key, el, handler, eventName;
						for (var key in this.events) {
							el = key;
							eventName = this.events[key].split(' ')[0].split(',');
							handler = this[ this.events[key].split(' ')[1] ];
							for ( var k in eventName ) {
								this.view.on(eventName[k], el, handler);
							}
						}
					},
					toggleType: function(e) {
						if ( $(this).hasClass('current-type') ) {
							return;
						} else {
							var type = self.model.type = (self.model.type == 'img' ? 'text' : 'img');
							self.watermarkType.toggleClass('current-type');
							self.textInfo.add(self.imgList).toggle();
							if (type == 'img') {
								self.imgList.trigger('createImgWater');
							} else {
								self.textInput.focus();
							}
						}
					},
					changePosition: function(e, ui) {
						if ( $(this).is('.position-box > div') ) {
							var offsetMe = $(this).offset(),
								offsetWatermarkBox = ui.draggable.parent().offset(),
								left = offsetMe.left - offsetWatermarkBox.left,
								top = offsetMe.top - offsetWatermarkBox.top;
							self.model.left = left;
							self.model.top = top;
							self.model.position = $(this).attr('class').substr(1, 1);
						}
						self.watermark.css({
							left: self.model.left || 0,
							top: self.model.top || 0
						});
					},
					changeOpacity: function(e, ui) {
						self.opacityLabel.text( ui.value + '%' );
						self.model.opacity = ui.value;
						self.watermarkBox.css( 'opacity', 1 - ui.value / 100 );
					},
					changeImg: function(e) {
						self.watermark.empty();
					},
					changeText: function(e) {
						var text = this.value;
						self.model.text = text;
						self.watermark.text(text);
					},
					changeSize: function(e, ui) {
						self.sizeLabel.text( ui.value );
						self.model.size = ui.value;
						self.watermarkBox.css( 'font-size', ui.value + 'px' );
					},
					changeColor: function(e) {
						if ( $(this).hasClass('current-color') ) {
							return;
						}
						$(this).parent().siblings().removeClass('blue-border').children().removeClass('current-color');
						$(this).addClass('current-color').parent().addClass('blue-border');
						self.watermark.css( 'color', $(this).css('background-color') );
						self.model.color = $(this).css('background-color');
					}
				});
				
				exports.WatermarkCtrl = ctrl;
			})(this, jQuery);
		}
		var ctrl = new this.WatermarkCtrl( editorView[0] );
		ctrl.outer_obj = this;
		$(window).trigger('resize.slide');
	},
	getEditorView: function() {
		var v = '<div class="edit-slide-html-each">' + 
			'<div class="edit-slide-title watermark-edit-title"><div class="watermark-back-title"><a class="edit-slide-back">返回</a><span class="edit-slide-close">关闭</span>水印设置</div></div>' +
			'<div class="edit-slide-watermark-content edit-slide-content">' +
			'<ul class="watermark-edit">' +
			'<li class="watermark-type"><a class="not-current-type current-type">图片水印</a><a class="not-current-type">文字水印</a></li>' +
			'<li class="watermark-position"><div class="position-box"><div class="p1"></div><div class="p2"></div><div class="p3"></div><div class="p4"></div><div class="p5"></div><div class="p6"></div><div class="p7"></div><div class="p8"></div><div class="p9 watermark-box"><div class="can-drag"></div></div></div></li>' +
			'<li class="watermark-name">名称：<input /></li>' + 
			'<li class="watermark-opacity"><span>透明： </span><span class="opacity-slider"></span><label></label></li>' +	
			'<li class="watermark-img"><ul>';
			 
		v += '<li style="text-align:center;padding: 10px;">无预设的水印图片</li>'
		v += '</ul></li><li class="watermark-text-info"><ul><li>内容： <input type="text" /></li><li>大小： <span class="size-slider"></span><label></label></li><li class="watermark-text-color"><span class="color-label">文字颜色:</span></li></ul></li>' +//<div class="text-info-item text-info-last-item"><span class="bg-color-label">背景颜色:</span></div></div>' + 
			'<li><div class="button_6_14 save-watermark-btn">保存水印</div></li>' +
			'</ul>' +
			'</div>' +
			'</div>';
		v = $(v);
		v.find('.position-box > div').droppable();
		v.find('.watermark-box .can-drag').draggable();
		v.find('.opacity-slider').slider({
			create: function() {
				$(this).addClass('myslider-ui')
				.next().text('0%');
			},
			animate: true,
			min: 0,
			max: 100,
			step: 1,
			value: 0
		});
		v.find('.size-slider').slider({
			create: function() {
				$(this).addClass('myslider-ui')
				.next().text( 12 );
			},
			animate: true,
			min: 1,
			max: 36,
			step: 1,
			value: 12
		});
		v.find('.watermark-text-color').append( (function() {
			var i = 0,
				html = '<div class="color-box">',
				colors = [ 
					'#fff', '#fcc', '#cfc', '#cff', '#fcf',
					'#d9d9d9', '#f66', '#6f6', '#9cf', '#c9f',
					'#a4a4a4', '#f33', '#3c0', '#69f', '#93c',
					'#666', '#c00', '#090', '#06c', '#609',
					'#000', '#600', '#130', '#039', '#306'
				];
			for( i = 0; i < 25; i++ ) {
				html += '<span><span class="forecolor" style="background-color:' + colors[i] + '"></span></span>';
			}
			return html += '</div>';
		})() );
		return v;
	}
});
	