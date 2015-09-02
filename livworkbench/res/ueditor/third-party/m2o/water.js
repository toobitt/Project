(function($){
    var waterInfo = {
		template : '' + 
				'<div class="water-slide">' +
				'<div class="water-overflow">' + 
					'<div class="water-area">' + 
						'<ul class="editor-content-area editor-water-content-area">' +
						'<li class="item-box" _id="-1"><div class="item-inner-box"><input type="radio" class="type" name="tmpwatermarkid" /><span class="water-title">不使用水印</span></li>' +
						'<li class="item-box" _id="-1"><div class="item-inner-box"><input type="radio" class="type" name="tmpwatermarkid" /><span class="water-title">继承</span></li>' +
						'<!-- <li class="item-box next"><div class="item-inner-box">设置独立水印<em>next</em></div></li> -->' +
						'</ul>' + 
						'<input type="hidden" id="water_config_id" name="water_config_id" value=""/>' +
						'<input type="hidden" id="water_config_name" name="water_config_name" value=""/>' +
					'</div>' + 
					'</div>' + 
					'<div class="edit-slide-html-each">' + 
						'<div class="edit-slide-watermark-content edit-slide-content">' +
							'<div class="watermark-type">' +
								'<a class="not-current-type current-type">图片水印</a>' +
								'<a class="not-current-type">文字水印</a>' +
							'</div>' +
							'<div class="watermark-edit">' +
								'<div class="watermark-position water-item">' +
									'<div class="position-box">' +
										'<div class="p1"></div>' +
										'<div class="p2"></div>' +
										'<div class="p3"></div>' +
										'<div class="p4"></div>' +
										'<div class="p5"></div>' +
										'<div class="p6"></div>' +
										'<div class="p7"></div>' +
										'<div class="p8"></div>' +
										'<div class="p9 watermark-box">' +
											'<div class="can-drag"></div>' +
										'</div>' +
									'</div>' +
								'</div>' +
								'<div class="watermark-name water-item"><label>名称： </label><input type="text" value=""/></div>' +
								'<div class="watermark-opacity water-item">' +
									'<span>透明： </span>' +
									'<span class="opacity-slider"></span>' +
									'<em></em>' +
								'</div>' +	
								'<div class="watermark-img water-item">' +
									'<ul>' +
										'<li style="text-align:center;">无预设的水印图片</li>' +
									'</ul>' + 
								'</div>' +
								'<div class="watermark-text-info">' +
										'<div class="watermark-content water-item"><label>内容： </label><input type="text" value=""/></div>' +
										'<div class="watermark-size water-item"><label>大小： </label><span class="size-slider"></span>' +
											'<em></em>' +
										'</div>' +
										'<div class="watermark-text-color water-item">' +
											'<span class="color-label">文字颜色:</span>' +
										'</div>' +
								'</div>' +
								'<div class="water-btn">' +
									'<span class="button_6 save-watermark-btn">保存水印</span>' + 
									'<span class="button_4 edit-slide-back">返回</span>'+
								'</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
					'',
		item_tpl : '' + 
				'<li class="item-box"  _id="${id}">' + 
					'<div class="item-inner-box">' +
						'<input type="radio" name="tmpwatermarkid" class="type" />' + 
						'{{if url}}<p class="water-img"><img src="${url}" /></p>{{/if}}' + 
						'<span class="water-title" title="${title}">${title}</span>' +
					'</div>' + 
				'</li>' + 
				'',
		css : '' + 
		'.water-slide{position:relative; width:2000px;}' +
		'.water-overflow{overflow-y:auto;overflow-x:hidden;float: left;width:254px;}'+
		'.water-area .item-box{position:relative;border-bottom:1px solid #e7e7e7;overflow:hidden;margin:0 10px; cursor:pointer; }' + 
		'1.water-area .item-box:last-child{padding-left:20px;}' +
		'.water-area .item-box:last-child em{background-image:url('+$.ueditor.pluginDir+'/slide/slide-next.png); width:5px; height:7px; position:absolute; right:10px; top:24px; text-indent:-999px; overflow:hidden}' +
		'.water-area .item-inner-box{display:table-cell;height:55px; padding-left:5px; vertical-align:middle; white-space:nowrap;}' + 
		'.water-area .type{vertical-align:middle;}' + 
		'.water-area .water-img{display:inline-block;width:58px;height:42px;font-size:0;margin-left:10px;line-height:42px;text-align:center;border:1px solid #E0DCDB;}' +
		'.water-area .water-img img{max-width:58px;max-height:42px; vertical-align:middle;}' + 
		'.water-area .water-title{max-width:160px; text-overflow:ellipsis; overflow:hidden; margin-left:10px;vertical-align:middle; display:inline-block;}' +
		
		'.edit-slide-html-each{display:none; float:left;}' +
		'.edit-slide-watermark-content{margin:0 10px;}' +
		'.watermark-type{margin-top:10px; }' +
		'.watermark-type a{display:inline-block;height:32px;width:86px;margin-right:5px;position:relative;bottom:-1px;cursor:pointer; line-height:32px;text-align:center; border:1px solid #cfcfcf; background:-webkit-linear-gradient(#f5f5f7, #e8e8ea); background:-moz-linear-gradient(#f5f5f7, #e8e8ea); background:linear-gradient(#f5f5f7, #e8e8ea); border-top-left-radius:3px; border-top-right-radius:3px;}' +
		'.watermark-type .current-type{background:#fff; border-bottom:1px solid #fff;}' +	
		'.water-item{padding:10px 5px; border-bottom:1px dashed #e1e1e1; margin-right:10px;}' +
		'.water-item em{font-style:normal;}' +
		'.watermark-position .position-box {overflow: hidden; background: url('+$.ueditor.pluginDir+'/kawaii_07.jpg) no-repeat center;width: 180px;line-height: 48px;  text-align: center; }' +
		'.position-box div {width: 58px;height: 48px;float: left; }' +
		'.p1, .p2, .p4, .p5, .p7, .p8 {border-right: 2px dashed rgba(255,255,255,0.6);}' +
		'.p1, .p2, .p4, .p5, .p3, .p6 {border-bottom: 2px dashed rgba(255,255,255,0.6);}' +
		'.p4, .p7{clear:left;}' +
		'.watermark-img-list {padding: 15px 10px;width: 220px; overflow: hidden; }' +
		'.watermark-text-info{display:none;}' +
		'.watermark-text-info input, .watermark-name input{ height: 19px; width: 120px; }' +
		'.color-label, .bg-color-label {cursor: pointer; margin-right:10px;}' +
		'.color-box { width: 120px; display:inline-block; vertical-align:top; }' +
		'.color-box span {width: 20px;height: 13px;float:left; margin: 0 4px 1px 0;}' +
		'.color-box .forecolor {margin: 0px;}' +
		'.color-box .current-color {width: 14px;height: 7px;}' +
		'.color-box .blue-border {width: 14px;height: 7px;border: 1px solid #5a98d1;padding: 2px;}' +
		'.current-color-decoration {width: 20px; height: 10px;border: 1px solid #5a98d1;position: absolute;left: -1px; top: -1px; }' +
		'.water-btn{text-align:center; }' +
		'.save-watermark-btn{margin:20px 10px 20px 0;}' +
		'.watermark-box img, .watermark-box div {cursor: move;}' +
		'.edit-slide-watermark-content .myslider-ui { display: inline-block; margin-right: 10px; width: 100px; height: 0.5em; background:#6d6d6d!important; border-radius:4px!important;}' +
		'.edit-slide-watermark-content .ui-state-default{border-radius:50%;}' +
		'.edit-slide-watermark-content .ui-state-focus, .edit-slide-watermark-content .ui-state-hover{background:#f6f6f6!important; }' +
		'.ump-inner .editor-water-content-area,.ump-inner .watermark-edit{max-height:280px}' +
			'',
		cssInited : false
    };

    $.widget('ueditor.water', $.ueditor.baseWidget, {
        options : {
        	index : true,
        	title : '水印设置',
        	content : '.editor-content-area',
        	item : '.item-box',
        	next : '.next',
        	slide : '.water-slide',
        	edit : '.edit-slide-html-each',
        	current : '.not-current-type',
        	img : '.watermark-img',
        	opacity : '.opacity-slider',
        	text : '.watermark-content input',
        	size : '.size-slider',
        	color : '.color-box .forecolor',
        	back : '.edit-slide-back',
        	nolast : '.item-box:not(:last-child)',
        	drag : '.can-drag',
        	drop : '.position-box > div',
        	save : '.save-watermark-btn',
        	textinfo :'.watermark-text-info',
        	configid : '.water_config_id',
        	configname : '.water_config_name',
        },

        _create : function(){
            this._super();
            this._template('water-template',waterInfo, this.body);
            this.model = {
						type: 'img',
						position: 9,
						left: 0,
						top: 0,
						opacity: 1,
						img: null,
						text: '',
						size: 12,
						color: ''
					};
        },

        _init : function(){
        	var op = this.options,
        		handlers = {};
        	this.content = this.element.find( op['content'] );
        	this.box = this.element.find( op['slide'] );
        	handlers['click ' + op['next'] ] = '_setWater';
        	handlers['click ' + op['current'] ] ='_toggleType';
        	handlers['slide ' + op['opacity'] ] ='changeOpacity';
        	handlers['focus ' + op['text'] ] = 'changeText';
        	handlers['keyup ' + op['text'] ] = 'changeText';
        	handlers['slide ' + op['size'] ] = 'changeSize';
        	handlers['click ' + op['color'] ] = 'changeColor';
        	handlers['click ' + op['back'] ] ='_backWater';
        	handlers['click ' + op['save'] ] ='_saveWater';
        	handlers['click ' + op['nolast'] ] = '_setvalue';
        	handlers['drop' + op['drop'] ] = 'changePosition';
        	handlers['dragstop' + op['drag']] = 'changePosition';
        	handlers['createImgWater' + op['img']] = 'changeImg';
            this._super();
            this._on( handlers );
            this._initWater();
            this._getEditorView();
        },
        
        _initWater : function(){
        	var hei = this.element.height() - this.title.height();
        	this.element.find('.water-area').height( hei );
        	
        	var _this = this,
        		url = this.options.config['waterUrl'];
        	$.getJSON( url , function( data ){
        		if( data.length ){
        			_this._instance( data );
        		}
        	} );
        },
        
        _instance : function( data ){
    		var _this = this,
    			realdata = [];
        	if( $.isArray( data ) ){
        		$.each( data, function( key , value ){
        			_this._handleData(value, realdata);
        		} );
        	}else{
        		_this._handleData(data, realdata);
        	}
        	$.template('item_tpl',waterInfo.item_tpl);
        	$.tmpl('item_tpl', realdata).prependTo(this.content);
        },
        
        _handleData : function( data , arr ){
        	var info = {};
        	info.id = data['id'];
			info.title = data['config_name'];
			info.url = data['img_url'];
			arr.push( info );
        },
        
        _toggleType : function( event ){
        	var op = this.options,
        		self = $(event.currentTarget);
    		if( self.hasClass('current-type') ){
    			return;
    		}else{
    			var type = this.model.type = (this.model.type == 'img' ? 'text' : 'img');
    			$( op['current'] ).toggleClass('current-type');
    			$( op['textinfo'] ).add( op['img'] ).toggle();
    		}
        },
        
        _setWater : function( event ){
        	var op = this.options,
        		self = $(event.currentTarget),
    		content = self.closest( op['content'] );
        	var _this = this;
        	this.showNext(function( dom ){
        		if( $( op['edit'] ).length ){
        			$( op['edit'] ).appendTo( dom ).show();
        		}else{
        			$.tmpl('water-template',{}).find('.edit-slide-html-each').appendTo( dom ).show();
        			_this._getEditorView();
        		}
        	});
        	
//    		this.box.animate({
//    			left: '-=' + (content.width()+10) +'px'
//    		}, 200, function(){
//    			$( op['edit'] ).show();
//    		});
        },

        _backWater : function( event ){
        	var op = this.options;
        	this.showBack(function(){
        		$( op['edit'] ).hide();
        	});
//    		this.box.animate({
//    			left: 0
//    		}, 200, function(){
//    			$( op['edit'] ).hide();
//    		});
        },
        
        _setvalue : function( event ){
        	var op = this.options,
        		self = $(event.currentTarget),
        		id = self.attr("_id") || '',
        		name = self.find('.water-title').text();
        	$('#water_config_id').val(id);
        	$('#water_config_name').val(name);
			self.find('input:radio').prop('checked', true);
//    		$( op['configid'] ).val(id);
//			$( op['configname'] ).val(name);
        },
        
        _saveWater : function( event ){
        	var op = this.options,
        		_this = this,
        		self = $(event.currentTarget);
        	var name = $('.watermark-name').find('input').val(),
        		url = op.config['saveWaterUrl'];
        	if ( $(this).data('ajax') ) {
				return;
			}
        	if ( !name.trim() ) {
				alert('请填写水印名！');
				return;
			}
        	if( this.model.type =='text' ){
        		if( !this.model.text.trim() ){
        			alert('水印文字不能为空！');
        			return;
        		}
        		data = {
        			config_name: name,
					water_type: 0,
					water_text: this.model.text,
					opacity: this.model.opacity,
					water_color: this.model.color,
					get_photo_waterpos: this.model.position
				};
        	}else{
        		if ( !this.model.img ) {
					alert('水印图片不能为空！');
					return;
				}
				data = {
					config_name: name,
					water_type: 1,
					opacity: this.model.opacity,
					get_photo_waterpos: this.model.position
				};
        	}
        	self.data('ajax', 1);
			data.ajax = 1;
        	$.ajax({
				url: url,
				data: data,
				type: 'post',
				complete: function() {
					self.data('ajax', 0);
				},
				success: function(data) {
					_this._saveWaterAfter(data);
				},
				error: function() {
					alert('创建失败');
				},
				dataType: 'json'
			});
        },
        
        _saveWaterAfter : function( data ){
        	var op = this.options;
        		data = data[0],
        		para = [],
				info = {};
			info.id = data.id;
			info.url = data.url;
			info.title = data.config_name;
			para.push(info);
			$.template('item_tpl',waterInfo.item_tpl);
			$.tmpl('item_tpl', para).prependTo(this.content);
			$( op['back'] ).trigger('click');
        },
        
        changeOpacity: function(e, ui) {
			$('.watermark-opacity').find('em').text( ui.value + '%' );
			this.model.opacity = ui.value;
			$('.watermark-box').css( 'opacity', 1 - ui.value / 100 );
		},
        
        changeImg: function(e) {
        	var op = this.options;
			$( op['drag'] ).empty();
		},
        
        changePosition: function(e, ui) {
        	var _this = this,
        		op = this.options;
        		this.left = '',
        		this.top = '';
        	if ( $(this).is( op['drop'] ) ) {
				var offsetMe = $(this).offset(),
					offsetWatermarkBox = ui.draggable.parent().offset(),
					left = offsetMe.left - offsetWatermarkBox.left,
					top = offsetMe.top - offsetWatermarkBox.top;
				this.model.left = left;
				this.model.top = top;
				this.model.position = $(this).attr('class').substr(1, 1);
			}
			$(this).css({
				left: this.model.left || 0,
				top: this.model.top || 0
			});
		},
        
        changeText: function( event ) {
        	var op = this.options,
        		self = $(event.currentTarget);
			var text = self.val();
			$( op['drag'] ).html(text);
			this.model.text = text;
		},
        
        changeSize: function(e, ui) {
			$('.watermark-size').find('em').text( ui.value );
			this.model.size = ui.value;
			$('.watermark-box').css( 'font-size', ui.value + 'px' );
		},
        
        changeColor: function(event) {
        	var op = this.options,
        		self = $(event.currentTarget);
			if ( self.hasClass('current-color') ) {
				return;
			}
			self.parent().siblings().removeClass('blue-border').children().removeClass('current-color');
			self.addClass('current-color').parent().addClass('blue-border');
			$( op['drag'] ).css( 'color', self.css('background-color') );
			this.model.color = self.css('background-color');
		},
        
        _getEditorView : function(){
        	var op =this.options,
        		slide = $( op['edit'] );
        	slide.find( op['drop'] ).droppable();
        	slide.find( op['drag'] ).draggable();
        	slide.find( op['opacity'] ).slider({
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
			slide.find( op['size'] ).slider({
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
			slide.find('.watermark-text-color').append( (function() {
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
        },
   		_destroy : function(){

        }
    });

    $.ueditor.m2oPlugins.add({
        cmd : 'water',
        title : '水印设置',
        click : function(editor){
            $.editorPlugin.get(editor, 'water').water('show');
        }
    });
    


})(jQuery);