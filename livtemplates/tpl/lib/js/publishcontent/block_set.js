//调试用的log
function log() {
	console.log.apply(console, arguments);
}
$(function($) {
	var that = {};//, Model, Anchor, AnchorEdit, Block, BlockCtrl, Line, LineEdit, App;
	(function (exports) {
		var createNewPubSub = function () {
			var o = $({});
			return {
				on: function () {
					o.on.apply(o, arguments);
					return this;
				},
				off: function () {
					o.off.apply(o, arguments);
					return this;
				},
				trigger: function () {
					o.trigger.apply(o, arguments);
					return this;
				}
			};
		};
		
		exports.createNewPubSub = createNewPubSub;
	})(that);
	that.Controller = {
		prototype: {
			init: function () {},
			proxyMethod: function () {
				for ( var method in (this.proxied || []) ) {
					method = this.proxied[method];
					this[method] = this.proxy(this[method]);
				}
				return this;
			},
			refreshElements: function () {
				for (var selector in (this.elements || []) ) {
					this[this.elements[selector]] = this.el.find(selector);
				}
				return this;
			},
			subAllEvents: function () {
				var method, match, eventName, selector;
				for (var key in (this.events || []) ) {
					method = this[this.events[key]];
					match = key.match(this.eventSplitter);
					eventName = match[1];
					selector = match[2];
					if (selector) {
						this.el.on( eventName, selector, method);
					} else {
						this.el.on( eventName, method );
					}
				}
				return this;
			},
			eventSplitter: /^(\w+)\s*(.*)$/,
			proxy: function (func) { 
				return $.proxy(func, this); 
			}
		},
		create: function(obj) {
			var o = Object.create(this);
			o.parent = this;
			o.prototype = o.fn = Object.create(this.prototype);
			o.extend(that.createNewPubSub());
			if (obj) o.include(obj);
			return o;
		},
		init: function (obj) {
			var instance = Object.create(this.prototype);
			instance.parent = this;
			instance.el = (obj && obj.el) || $('<div></div>');
			instance
				.proxyMethod()
				.refreshElements()
				.subAllEvents()
				.init();
			return instance;
		},
		extend: function(obj) { 
			$.extend(this, obj); 
		},
		include: function(obj) { 
			$.extend(this.fn, obj); 
		}
	};	
	(function() {
		var createNewPubSub = this.createNewPubSub;
		var Model = {
			prototype: {
				init: function(attrs) {
					if (attrs) {
						for (var name in attrs) {
							this[name] = attrs[name];
						}
					}
					$.extend(this, createNewPubSub());
				}
			},
			setup: function (name, attributes) {
				this.models = this.models || [];
				var o = {};
				o[name] = attributes;
			},
			create: function() {
				var o = Object.create(this);
				o.records = {};
				o.attributes = [];
				o.extend( createNewPubSub() );
				o.parent = this;
				o.prototype = o.fn = Object.create(this.prototype);
				return o;
			},
			init: function() {
				var instance = Object.create(this.prototype);
				instance.parent = this;
				instance.init.apply(instance, arguments);
				return instance;
			},
			extend: function(o) {
				$.extend(this, o);
				return this;
			},
			include: function(o) {
				$.extend(this.prototype, o);
				return this;
			}
		};
		Model
			.extend({
				find: function(id) {
					return this.records[id] || null;
				}
			})
			.include({
				newRecord: true,
				create: function() {
					this.newRecord = false;
					this.parent.records[this.id] = this;
					this.trigger('change', [this]);
					return this;
				},
				destroy: function() {
					delete this.parent.records[this.id];
					this.trigger('remove', [this]);
					return this;
				},
				update: function(data) {
					$.extend(this, data);
					this.trigger('change', [this]);
					return this;
				},
				save: function() {
					this.newRecord ? this.create() : this.update();
					return this;
				},
				attributes: function() {
					var result = {}, i, attr;
					for (i in this.parent.attributes) {
						attr = this.parent.attributes[i];
						result[attr] = this[attr];
					}
					return result;
				},
				attr: function(name) {
					return this[name];
				}
			});
		
		this.Model = Model;
	}).apply(that);

	/*module:颜色选择器*/
	(function(exports) {
		var change, view, colors, html;
		view = $('#color-picker');
		view = view.length != 0 ? view : $( '<div id="color-picker"></div>' ).appendTo( $('body') );
		colors = [
	        '#fff', '#000', '#eeece0', '#1c477c', '#4e80bf', '#c24f4a', '#99bd53', '#8162a5', '#46abc7', '#f99639',
	        '#f2f2f2', '#7f7f7f', '#dcdac3', '#c4d8f1', '#dae6f2', '#f2ddda', '#ecf2dd', '#e5dfeb', '#dbeef5', '#fdead9',
	        '#d8d8d8', '#595959', '#c5bd96', '#8cb2e3', '#b7cbe6', '#e6b9b6', '#d8e5ba', '#cdc0da', '#b7ddea', '#fdd7b3',
	        '#bfbfbf', '#3f3f3f', '#948a4f', '#518bd5', '#95b3d9', '#da9492', '#c3d798', '#b2a2c9', '#8fcddc', '#fcc08c',
	        '#a5a5a5', '#262626', '#494427', '#15355e', '#345f94', '#973630', '#759235', '#60487c', '#2d859d', '#e56c01',
	        '#7f7f7f', '#0c0c0c', '#1d1b0f', '#0d223f', '#223f61', '#642422', '#4f6125', '#3e3051', '#1d5868', '#984800',
	        '#c20000', '#fe0000', '#ffc100', '#ff0', '#8fd245', '#00b24c', '#00aef3', '#006ec3', '#011e62', '#712aa2'
	    ];
	    html = '<div class="color-box"><div>';
	    $.each( colors, function(i, color) {
	        html += '<span style="background:' + color + '"></span>'
	        if (i == 9) {
	            html += '</div><div>';
	        }
	        if (i == 59) {
	            html += '</div><div>';
	        }
	        if (i == 69) {
	            html += '</div></div>'
	        }
	    });
		view
			.html(html)
			.on( 'click.pickerAcolor', '.color-box span', function() {
				var color = $(this).css('background-color');
				change && change(color);
			})
			.on( 'open.color', function(e, option) {
				view.css({
					left: (option.left || 0) + 'px',
					top: (option.top || 0) + 'px',
					'z-index': option['z-index'] || 'auto'
				});
				view.show();
				change = option.change;
			})
			.on( 'close.color', function() {
				view.hide();
			})
			.css({
				'position': 'absolute',
				'display': 'none'
			});
		
		exports.colorPicker = view;
	})(that);
	//end
	
	(function(requires, exports) {
		var colorPicker = requires.colorPicker, oldme, me = $({}), func;
		function showColorPicker(fn) {
			var offset, left, top;
			func = fn;
			oldme = me;
			me = this;
			if ( me.hasClass('selected') ) {
				colorPicker.trigger('close.color');	
				me.removeClass('selected');
			} else {
				oldme.removeClass('selected');
				me = this;
				offset = me.offset();
				left = offset.left + 20;
				top = offset.top + 25;
				colorPicker.trigger('open.color', {
					left: left,
					top: top,
					change: colorChange
				});
				me.addClass('selected');
			}
			return this;
		}
		function colorChange() {
			colorPicker.trigger('close.color');
			me.removeClass('selected');
			func.apply(this, arguments);
		}
		
		exports.showColorPicker = showColorPicker;
	})(that, $.fn);
	

	Model = that.Model;
	Block = Model.create();
	$.each(gData || [], function(k, v) {
		Block.init(v).save();
	});
	Line = Block.create();
	Anchor = Line.create();
	Line.attributes = ['line', 'block_id', 'font_size', 'font_color', 'font_border', 'font_backcolor', 
		'font_b', 'before_prefix', 'back_prefix'];
	Anchor.attributes = ['id', 'block_id', 'title', 'brief', 'indexpic', 'outlink', 'line', 'font_size', 'font_color', 
		'font_border', 'font_backcolor', 'font_b'];
	LineEdit = that.Controller.create({
		proxied: ['render', 'pub_before_prefix', 'pub_font_color', 'pub_font_backcolor', 'pub_font_b', 'pub_font_size'],
		elements: {
			'[name=font_color]': 'font_color',
			'[name=font_size]': 'font_size',
			'[name=font_b]': 'font_b',
			'.font-attr span:eq(1)': 'boldView',
			'ul li:last span': 'bgcolor',
			'ul li:nth-child(2) span': 'before_prefix'
		},
		events: {
			'click ul li:nth-child(2) span': 'pub_before_prefix',
			'click ul li:nth-child(1) span:first': 'pub_font_color',
			'click ul li:last span': 'pub_font_backcolor',
			'click ul li:nth-child(1) span:eq(1)': 'pub_font_b',
			'change select[name=font_size]': 'pub_font_size'
		},
		init: function () {
			App
				.on( 'edit.line', this.render )
				.on( 'finishEdit.line', this.proxy(function () {
					this.el.css( 'top', '' );
				}))
				.on( 'open.sort', this.proxy(function () {
					this.el.fadeOut();
				}))
				.on ('close.sort', this.proxy(function () {
					this.el.fadeIn();
				}));
		},
		render: function (e, data) {
			var font_color, font_b, font_size, cname;
			font_color = data.font_color || '';
			font_b = data.font_b || '';
			font_size = data.font_size || '';
			cname = data.before_prefix;
			
			this.font_color.val(font_color);
			this.font_size.val(font_size);
			this.font_b.val(font_b);
			this.boldView[ (font_b == 'bold' ? 'add' : 'remove') + 'Class' ]('selected');
			this.before_prefix.each(function() {
				$(this).data('cname') == cname ? 
					$(this).addClass('selected') : $(this).removeClass('selected');
			});
			this.el.css({
				'top': data.top + 'px'
			});	
		},
		pub_before_prefix: function (e) {
			var me = $(e.target), cname;
			me.hasClass('selected') ? (
				cname = '', me.removeClass('selected')
			) : (
				cname = me.addClass('selected').data('cname'),
				me.siblings().removeClass('selected')
			);
			App.trigger('before_prefix.line', [{'before_prefix': cname}]);
		},
		pub_font_color: function (e) {
			var me = $(e.target);
			me.showColorPicker(function(color) {
				App.trigger('font_color.line', [{'font_color': color}]);
			});
		},
		pub_font_backcolor: function (e) {
			var me = $(e.target);
			me.showColorPicker(function(color) {
				$(this).css('background-color', color);
				App.trigger('font_backcolor.line', [{'font_backcolor': color}]);
			});
		},
		pub_font_b: function (e) {
			var me = $(e.target), bold;
			if ( me.hasClass('selected') ) {
				me.removeClass('selected');
				bold = 'normal';
			} else {
				me.addClass('selected');
				bold = 'bold';
			}
			App.trigger('font_b.line', [{'font_b': bold}]);
		},
		pub_font_size: function (e) {
			App.trigger('font_size.line', [{ 'font_size': $(e.target).val() }]);
		}
	});
	
	//end
	AnchorEdit = that.Controller.create({
		proxied: ['pubSave', 'pubRemove', 'closeView', 'changeBold', 'pickColor', 'render', 'tabType', 'showSource', 'changeAnchor'],
		elements: {
			'.anchor-fill-editor': 'fill',
			'.anchor-select-editor': 'source',
			'.anchor-select-editor .source-header': 'sourceHeader', 
			'.anchor-select-editor h1 label': 'sourceTitleLabel',
			'.anchor-select-editor h1 span': 'sourceTitleDesc',
			'.anchor-select-editor form': 'searchForm',
			'.anchor-select-editor ul': 'sourceList',
			'.anchor-fill-editor #headLabel': 'headLabel',
			'.anchor-fill-editor #titleLabel': 'titleLabel',
			'.anchor-fill-editor > p span:eq(1)': 'deleteButton',
			'.anchor-fill-editor input[name=title]': 'title',
			'.anchor-fill-editor [name=brief]': 'brief',
			'.anchor-fill-editor #indexpic': 'indexImg',
			'.anchor-fill-editor [name=indexpic]': 'indexpic',
			'.anchor-fill-editor [name=outlink]': 'outlink',
			'.anchor-fill-editor [name=font_color]': 'font_color',
			'.anchor-fill-editor [name=font_size]': 'font_size',
			'.anchor-fill-editor [name=font_b]': 'font_b',
			'.anchor-fill-editor .font-attr span:eq(1)': 'boldView'
		},
		events: {
			'click .anchor-fill-editor > p span:first': 'pubSave',
			'click .anchor-fill-editor > p span:eq(1)': 'pubRemove',
			'click .anchor-fill-editor > p span:last': 'closeView',
			'click .anchor-fill-editor form ul .font-attr span:eq(1)': 'changeBold',
			'click .anchor-fill-editor form ul .font-attr span:first': 'pickColor',
			'click .anchor-editor-type span': 'tabType',
			'click .anchor-fill-editor .enter-data-source': 'showSource',
			'click .anchor-select-editor ul li div': 'changeAnchor'
		},
		init: function () {
			this.searchForm.submit( function (e) {
				
				return false;
			});
			App.on('edit.anchor', this.render);
		},
		tabType: function (e) {
			$(e.target).addClass('current').siblings().removeClass('current');
			if ( $(e.target).is(':first-child') ) {
				this.fill.show();
				this.source.hide();
			} else {
				this.fill.hide();
				this.source.show();
			}
		},
		changeAnchor: function (e) {
			var me = $(e.target).closest('li');
			this.pubSave({}, {
				title: me.data('title'),
				brief: me.data('brief'),
				outlink: me.data('outlink')
			});
		},
		showSource: function () {
			this.fill.hide();
			this.source.show();
			this.renderList({}, 1);
		},
		SourceData: {},
		renderList: function (e, pageNum) {
			var data;
			pageNum = pageNum || $(e.target).data('page');
			data = this.SourceData[this.block_id][pageNum];
			data ? this.sourceList.html( this.templateList(data, pageNum) ) : ( 
				this.sourceList.html( this.waitImg ),
				$.get(this.getSourceUrl, $.proxy(function (data) {
					data = data[0];
					data = data.content_data;
					log(typeof data);
					if (typeof data == 'string' || !data) {
						this.sourceList.html( this.templateList() )
						return;
					}
					this.sourceList.html( this.templateList(data, pageNum) )
					this.SourceData[this.block_id][pageNum] = data;
					
				}, this), 'json')
			);
		},
		templateList: function (data) {
			var html = '', buttonLabel = this.editModel;
			if (!data) {
				html = '<h2 style="color:red;text-align:center;">无内容！</h2>'
			} else {
				$.each(data, function (i, n) {
					html += '<li data-title="' + n.title +'" data-brief="' + n.brief +'" data-outlink="' + 
								n.outlink +'"><p class="overflow">' + n.title + 
									'</p><div class="button_4">' + buttonLabel + '</div></li>';
				});
			}
			return html;
		},
		
		pubSave: function (e, data) {
			data = data || {
				title: this.title.val() == '标题' ? '' : this.title.val(),
				brief: this.brief.val() == '提要' ? '' : this.brief.val(),
				indexpic: this.indexpic.val(),
				outlink: this.outlink.val() == '链接' ? '' : this.outlink.val(),
				font_color: this.font_color.val(),
				font_size: this.font_size.val(),
				font_b: this.font_b.val()
			};
			this.closeView();
			App.trigger('save.anchor', [data]);
		},
		pubRemove: function () {
			jConfirm('您确认删除此条记录吗？', '删除提醒', this.proxy(function(yes) {
				if (yes) {
					this.closeView();
					App.trigger('remove.anchor');
				}	
			}, this));
		},
		closeView: function () {
			this.source.hide();
			this.fill.show();
			this.full.hide();
			this.el.fadeOut(500);
		},
		pickColor: function (e) {
			var me = this;
			$(e.target).showColorPicker(function(color) {
				me.font_color.val(color);
			});
		},
		changeBold: function (e) {
			var me = this.boldView;
			if ( me.hasClass('selected') ) {
				me.removeClass('selected');
				this.font_b.val('normal');
			} else {
				me.addClass('selected');
				this.font_b.val('bold');
			}
		},
		render: function(e, data) {
			var deftPic, headLabel, title, titleLabel, brief, src, indexpic, outlink, font_color, font_b, font_size;
			sTop = $(document).scrollTop();
			log(sTop);
			deftPic = this.indexImg.attr('_src');
			headLabel = data.headLabel;
			titleLabel = data.title || '';
			title = data.title || '标题';
			brief = data.brief || '提要';
			indexpic = data.indexpic || '';
			outlink = data.outlink || '链接';
			font_color = data.font_color || '';
			font_b = data.font_b || '';
			font_size = data.font_size || '';
			src = indexpic || deftPic;
			
			 this.editModel = (
			 	title == '标题' ? '添加' : '更换'
			 );
			title == '标题' ? this.deleteButton.hide() : this.deleteButton.show();
			this.headLabel.text(headLabel);
			this.titleLabel.text(titleLabel);
			this.title.val(title);
			this.brief.val(brief);
			this.indexImg.attr('src', src);
			this.indexpic.val(indexpic);
			this.outlink.val(outlink);
			this.font_color.val(font_color);
			this.font_size.val(font_size);
			this.font_b.val(font_b);
			this.boldView[ (font_b == 'bold' ? 'add' : 'remove') + 'Class' ]('selected');
			this.title.focus().select();
			
			this.sourceHeader.html(data.sourceHead);//用于数据源的头部，表示区块的信息
			title == '标题' ? this.sourceTitleLabel.text(headLabel) : this.sourceTitleLabel.text('更换：');
			this.sourceTitleDesc.text(titleLabel);
			this.getSourceUrl = './run.php?mid=' + gMid + '&a=get_datasource&id=' + data.block_id;
			this.block_id = data.block_id;
			this.SourceData[this.block_id] = this.SourceData[this.block_id] || [];
			this.renderList({}, 1);
			
			this.el.css({
				left: data.left + 'px',
				top: data.top + 'px'
			}).fadeIn(500);
		
			this.full.css( 'height', $(document).height() ).show();
			hg_resize_nodeFrame();
	
			return false;
		},
		getSourceUrl: '',
		waitImg : '<div>' +
		'<img width="40" src="' + RESOURCE_URL + 'loading2.gif"/>' + 
		'</div>',
		full: $('#full-screen-masking')
	});
	
	BlockCtrl = that.Controller.create({
		proxied: ['lookSortOpen', 'lookSortClose', 'openDragModel', 'closeDragModel', 'judgeOrder', 'saveSort', 'dragStop', 'lookAnchor', 'lookLine', 'callLineEdit', 'callLineEditForAll', 'updateLine', 'renderLine', 'callAnchorEdit', 'saveAnchor', 'addAnchor', 'deleteAnchor', 'renderAnchor', 'removeAnchor'],
		elements: {
			'.meta-info': 'metaInfo',
			'ul': 'ul',
			'li': 'lis',
			'.anchor-box': 'aBox',
			'.ordertip': 'tip',  
			'.anchor-box a': 'as',
			'.anchor-box .anchor': 'anchors',
			'.line-wrap .option-setting': 'setting',
			'.operate-bat-setting': 'opBatSetting',
			'.operate-sort': 'opSort'
		},
		events: {
			'click .has-event .anchor-box a': 'callAnchorEdit',
			'click .has-event .line-wrap .option-setting': 'callLineEdit',
			'click .operate-bat-setting': 'callLineEditForAll',
			'click .operate-sort': 'openDragModel',
			'click .current.operate-sort': 'closeDragModel',
			'click .ordertip span': 'closeDragModel',
			'click .ordertip input': 'saveSort'
		},
		init: function () {
			var me = this;
			this.reInit(true);
			this.ul.sortable({
				placeholder: 'line-place-holder',
				revert: true,
		        cursor: 'move',
		        containment: 'document',
		        scrollSpeed: 100,
		        tolerance: 'intersect',
		        start: this.dragStart,
		        stop: this.dragStop,
		        disabled: true
			});
			$.each(Block.find(this.el.data('id')).children || [], function(kk, vv) {
				$.each(
					Line.init(vv).on('change', me.renderLine).save().children || [], 
					function(kkk, vvv) {
						Anchor.init(vvv).on('change', me.renderAnchor).on('remove', me.removeAnchor).save();
					}
				);
			});
			App.on('save.anchor', this.saveAnchor)
			   .on('remove.anchor', this.deleteAnchor)
			   .on('edit.anchor', this.lookAnchor)
			   .on('edit.line', this.lookLine)
			   .on('before_prefix.line', this.updateLine)
			   .on('font_color.line', this.updateLine)
			   .on('font_b.line', this.updateLine)
			   .on('font_size.line', this.updateLine)
			   .on('font_backcolor.line', this.updateLine)
			   .on('open.sort', this.lookSortOpen)
			   .on('close.sort', this.lookSortClose);
		},
		reInit: function (noElements) {
			if (!noElements) this.refreshElements();
			
			this.aBox.sortable({
				items: 'a:not(.option-add)',
				revert: true,
		        cursor: 'move',
		        containment: 'document',
		        scrollSpeed: 100,
		        tolerance: 'intersect',
		        stop: this.judgeOrder,
		        disabled: true
			});
		},
		lookSortOpen: function (e, caller) {
			if (this != caller) this.el.slideUp();
		},
		lookSortClose: function (e, caller) {
			this.el.slideDown();
		},
		dragStart: function(e, ui) {
        	$(e.target).find('.line-place-holder').append('<div class="line-wrap clear"><a class="option-setting">设置</a></div>');
        	ui.helper.css({
        		'border': '1px dotted #73B6FC',
        		'background': 'transparent'
        	}).find('.option-setting').hide();
        },
		dragStop: function(e, ui) {
        	ui.item.find('.option-setting').show();
        	Line.find(ui.item.data('id')).update({});
        	this.judgeOrder();
        },
        judgeOrder: function (e, ui) {
        	var ids;
        	ids = this.serializeId(this.ul);
        	if (ids == this.startIds) {
        		this.sortIsChange = false;
        		this.tip.find('input').hide();
        	} else {
        		this.sortIsChange = true;
        		this.tip.find('input').show();
    		}
        },
		closeDragModel: function (e) {
			if (this.sortIsChange) { 
				jConfirm('排序已改变，您确定要放弃此次排序吗？', '提醒', this.proxy(function(yes) {
					if (yes) {
						this.ul.html(this.startHtml);
						this.reInit();
						this._closeDragModel();
					}	
				}, this));
			} else {
				this._closeDragModel();
			}
		},
		openDragModel: function (e) {
			this.opBatSetting.filter('.current').trigger('click');
			this.lis.filter('.current').find('.option-setting').trigger('click');
			this.lis.filter('.current').trigger('click');
			this.ul.removeClass('has-event');
			this.opBatSetting.removeClass('has-event');
			this.opSort.addClass('current');
			this.tip.fadeIn().find('input').hide();	
			this.ul.sortable('option', 'disabled', false);
			this.aBox.sortable('option', 'disabled', false);
			this.sortIsChange = false;
        	this.startHtml = this.ul.html();
        	this.startIds = this.serializeId();
        	App.trigger('open.sort', [this]);
		},
		_closeDragModel: function() {
			log('1')
			this.opSort.removeClass('current');
			this.tip.fadeOut();
			this.ul.sortable('option', 'disabled', true);
			this.aBox.sortable('option', 'disabled', true);
			
			this.ul.addClass('has-event');
			this.opBatSetting.addClass('has-event');
			App.trigger('close.sort', [this]);
		},
		saveSort: function() {
			var order = this.serializeId(this.ul),
				walkUl = this.walkUl;
			$.post(this.orderAjax, {data: order}, function () {
				walkUl(function (parentIndex, index) {
					var id = $(this).data('id');
					$(this).is('li') ? 
						Line.find(id).update({line: index + 1}) :
						Anchor.find(id).update({line: parentIndex + 1, child_line: index + 1});
				});
			});
			this._closeDragModel();
		},
		serializeId: function (){
			var order;
			order = {
				line: {}, 
				content: {}
			};
			this.ul.find('li').each(function(index) {
				var me = $(this);
				order.line[me.data('id')] = index + 1;
				me.find('.anchor').each(function(i) {
					order.content[$(this).data('id')] = {
						line: index + 1,
						child_line: i + 1
					};
				});
			});
			return JSON.stringify(order);
		},
		walkUl: function (func) {
			this.ul.find('li').each(function() {
				var index = arguments[0];
				func.call(this, 0, arguments[0]);
				$(this).find('.anchor').each(function () {
					func.call(this, index, arguments[0]);
				});
				
			})
		},
		lookLine: function (e, info, caller) {
			if ( this !== caller ) {
				this.currentLine = null;
				this.lis.removeClass('current');
				this.opBatSetting.removeClass('current');
				this.el.find('ul').addClass('has-event');
			}
		},
		callLineEdit: function (e) {
			var me = $(e.target).closest('li'), info, eventName;
			if ( me.hasClass('current') ) {
				log('here');
				this.currentLine = null;
				me.removeClass('current');
				eventName = 'finishEdit.line';
			} else {
				this.currentLine = me;
				me.addClass('current').siblings().removeClass('current');
				info = Line.find(me.data('id')) ? Line.find(me.data('id')).attributes() : {};
				$.extend(info, {
					top: me.offset().top - 40
				});
				eventName = 'edit.line';
			}
			App.trigger(eventName, [info, this]);
		},
		callLineEditForAll: function (e) {
			var me = $(e.target), eventName;
			if (!me.hasClass('has-event')) return;
			me.hasClass('current') ? (
				this.currentLine = null,
				me.removeClass('current'),
				this.lis.removeClass('current'),
				this.el.find('ul').addClass('has-event'),
				eventName = 'finishEdit.line'
			) : ( 
				this.currentLine = this.lis,
				me.addClass('current'),
				this.lis.addClass('current'),
				this.el.find('ul').removeClass('has-event'),
				eventName = 'edit.line'
			);
			App.trigger(eventName, [{top: this.el.offset().top + 50}, this])
		},
		updateLine: function (e, info) {
			if (!this.currentLine) return;
			var me = this.currentLine;
			$.post(
				this.lineAjax, 
				$.extend( 
					me.length > 1 ? function () {
						var ret = [], block;
						block = Block.find(this.el.data('id'));
						console.log(block.attr('line_num'));
						for( var i = 1; i <= block.attr('line_num'); i++)
							ret.push(i);
						return {
							block_id: block.attr('id'),
							line: ret.join(',')
						};
					}.call(this) : Line.find(me.data('id')).attributes(),
					info
				),
				function () {
					me.each(function () {
						Line.find($(this).data('id'))
							.update(info);
					});
				}
			);
		},
		renderLine: function (e, line) {
			var info = line.attributes();
			this.el.find('li[data-id=' + line.attr('id') + ']')
				.removeClass('with-prefix-1 with-prefix-2 with-prefix-3')
				.css({
					'color': info.font_color || '',
					'font-size': info.font_size || '',
					'font-weight': info.font_b || '',
					'background-color': info.font_backcolor || '',
					'border': info.font_border || ''
				})
				.addClass(info.before_prefix);
		},
		lookAnchor: function (e, info, caller) {
			if ( this !== caller ) {
				this.currentAnchor = null;
			}
		},
		callAnchorEdit: function (e) {
			var left = e.pageX, top = e.pageY, me = $(e.target), headLabel, info;
			this.currentAnchor = me;
			headLabel = '编辑：';
			if (me.hasClass('option-add')) {
				headLabel = '添加一条新新闻';
			}
			info = Anchor.find(me.data('id')) ? Anchor.find(me.data('id')).attributes() : {};
			$.extend(info, {
				left: left,
				top: top,
				headLabel: headLabel,
				sourceHead: this.metaInfo.clone(),
				block_id: this.el.data('id')
			});
			App.trigger('edit.anchor', [info, this]);
		},
		saveAnchor: function (e, data) {
			if (!this.currentAnchor) return;
			var me = this.currentAnchor, callback;
			if (me.hasClass('option-add')) {
				if ( !data.title || data.title == '标题' ) {
					return;
				}
				data['block_id'] = me.closest('.block-content').data('id');
				data['line'] = Line.find( me.closest('li').data('id') ).attr('line');
				callback = this.addAnchor;
			} else {
				var anchor = Anchor.find(me.data('id'));
				data = $.extend(
					anchor.attributes(), data
				);
				callback = function(result) {
					anchor.update(data);
				}
			}
			$.post(this.anchorAjax, data, callback, 'json');
		},
		addAnchor: function (result) {
			var re = result[0];
			$('<a class="anchor"></a>')
				.data('id', re.id)
				.attr('data-id', re.id)
				.insertBefore(this.currentAnchor);
			Anchor.init(re).on('change', this.renderAnchor).on('remove', this.removeAnchor).save();
		},
		deleteAnchor: function() {
			if (!this.currentAnchor) return;
			var me = this.currentAnchor,
				anchor = Anchor.find(me.data('id'));
			$.post(this.deleteAjax, {content_id: anchor.attr('id')}, function(result) {
				anchor.destroy();
			});
		},
		renderAnchor: function (e, anchor) {
			var info = anchor.attributes();
			this.el.find('a[data-id="' + anchor.attr('id') + '"]')
				.css({
					'color': info.font_color || '',
					'font-size': info.font_size || '',
					'font-weight': info.font_b || ''
				})
				.text(info.title);
		},
		removeAnchor: function (e, anchor) {
			this.el.find('a[data-id="' + anchor.attr('id') + '"]').remove();
		},
		anchorAjax: './run.php?ajax=1&mid=' + gMid + '&a=content_create',
		deleteAjax: './run.php?ajax=1&mid=' + gMid + '&&a=delete_content',
		lineAjax: './run.php?ajax=1&mid=' + gMid + '&a=block_line_set',
		orderAjax: './run.php?mid=' + gMid + '&a=block_set_sort'
	});
	
	App = that.Controller.create({
		elements: {
			'[id^=block]': 'allBlockEl',
			'#anchor-prop-editor': 'anchorEditEl',
			'#line-prop-editor': 'lineEditEl'
		},
		init: function () {
			this.allBlockEl.each(function (index) {
				BlockCtrl.init({
					el: $(this)
				});
			});
			this.anchorEdit = AnchorEdit.init({el: this.anchorEditEl});
			this.lineEdit = LineEdit.init({el: this.lineEditEl});
		}
	});
	App.init({el: $('body')});
});
	