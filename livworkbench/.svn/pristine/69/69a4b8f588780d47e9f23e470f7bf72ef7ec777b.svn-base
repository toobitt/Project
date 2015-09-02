(function($){

    var pluginInfo = {
        template : '' +
        	'<div class="imginfo-area">' +
	            '<div class="ueditor-imginfo-box">' +
	            	'<div class="img-info-item item-with-pic">' +
		            	'<div class="img-box"><img src="{{= src}}" imageid="{{= id}}"/></div>' +
		            	'{{if (!id)}}<p class="image-info-outer">外部图片</p>{{else}}<a class="image-edit-btn">编辑图片</a>{{/if}}'+
		            '</div>' +
		           '<div class="img-info-item">' +
			            '<div class="img-item"><label>图片：</label><input type="text" name="image-info-src" class="info-input {{if !id}}image-info-outer{{/if}}" value="{{= src}}" /></div>' +
			            '<div class="img-item"><label>链接：</label><input type="text" name="image-info-href" class="info-input" value="{{= href}}" /></div>' +
			            '<div class="img-item"><label>标题：</label><input type="text" name="image-info-title" class="info-input" value="{{= title}}" /></div>' +
		            '</div>' +
		            '<div class="img-info-item">' +
			            '<div class="img-item"><label>缩放：</label><span class="width-slider"></span>' +
			            	// '<span class="image-info-width">{{= oldwidth}}</span>x<span class="image-info-height">{{= oldheight}}</span>' +
			            	'<span class="image-info-width">最大宽度{{= maxwidth}}</span>' +
			            '</div>' +
		            '</div>' +
		            '<div class="img-info-item">' +
			            '<div class="img-item uii-pp"><label>版式：</label>' +
			           		'<span type="left"><img src="./res/ueditor/third-party/m2o/images/slide/position-left.png"/></span>' +
			           		'<span type="center"><img src="./res/ueditor/third-party/m2o/images/slide/position-middle.png"/></span>' +
			            	'<span type="right"><img src="./res/ueditor/third-party/m2o/images/slide/position-right.png"/></span>' +
			            '</div>' +
		            '</div>' +
		            '<div class="img-info-item">' +
			            '<div class="img-item"><label>间距：</label><span class="margin-slider"></span>' +
			            	'<em></em>' +
			            '</div>' +
		            '</div>' +
		             '<div class="img-info-item">' +
			            '<div class="img-item img-border-style"><label>边框：</label>' +
			           		'<span><img style="padding:5px; background-color:#fff; border:1px solid #ddd;" _style="style1" src="{{= src}}" /></span>' +
			           		'<span><img style="-webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" _style="style2" src="{{= src}}" /></span>' +
			            	'<span><img style="border:5px solid #fff; -webkit-box-shadow: 0 10px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);-moz-box-shadow: 0 10px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);box-shadow: 0 10px 10px -10px rgba(0, 0, 0, 0.5), 0 1px 4px rgba(0, 0, 0, 0.3);" _style="style3" src="{{= src}}" /></span>' +
			            '</div>' +
		            '</div>' +
	            '</div>' +
            '</div>' +
            '',
        css : '' +
        	'.image-edit-btn{background:rgba(0,0,0,.5);color:#fff;width:100px;text-align:center;padding:5px 0;cursor:pointer;margin:10px auto;display:inline-block;}'+
        	'.image-info-outer{color:red;text-align:center;}'+
        	'.imginfo-area{overflow-y:auto;}' +
            '.img-box img{max-width:160px;min-height:40px;}' +
            '.img-info-item{margin:0 10px; border-bottom:1px solid #e6e6e6;}' +
            '.item-with-pic{padding:10px 0;text-align:center;}'+
            '.img-item{margin:5% 0;padding:0 10px;}' +
            '.img-item .image-info-width{padding-left:4px;}' +
            '.img-item input{height:22px;line-height:22px;width:75%;}' +
            '.img-item em{font-style:normal; }' +
            '.uii-pp{line-height:2.2em;}' +
            '.uii-pp span{width:15%; height:100%; padding:5px; line-height:0; position:relative; display:inline-block; margin:2%; vertical-align:middle; }' +
            '.uii-pp span.current{background: url(./res/ueditor/third-party/m2o/images/ui-current.png) no-repeat 0;}' +
            '.img-border-style{line-height:2.5em;}' +
            '.img-border-style label{display:none;}' +
            '.img-border-style span{display:inline-block; padding:5% 3%;} ' +
            '.img-border-style span.current{background:yellow; }' +
            '.img-border-style img{line-height:0; vertical-align:middle; width:45px;}' +
            '.img-item .ui-slider{display: inline-block; margin-right: 6px; width: 84px; height: 6px; border:0;background:#6d6d6d;}' +
            '.img-item .ui-slider-handle{ border:0!important;top:-3px!important;width:12px!important;height:12px!important;background:-webkit-linear-gradient(#d0cfcf,#9d9d9d)!important;background:-moz-linear-gradient(#d0cfcf,#9d9d9d)!important;border-radius:50%!important;}' +
            '',
        cssInited : false
    };

    $.widget('ueditor.imginfo', $.ueditor.baseWidget, {
        options : {
        	index : true,
    		title : '图片属性',
            selfPluginInfo : pluginInfo,
            selfTemplateName : 'plugin-imginfo-template',
        },

        _create : function(){
            this._super();
            /*后台配置的图片最大宽度常量*/
            this.maxpicsize = $.maxpicsize ? parseInt( $.maxpicsize ) : 640;
        },

        _init : function(){
        	this._super();
            this._on({
                'click .uii-pp span' : '_imgFloat',
                'click .img-border-style img' : '_borderStyle',
                'blur .info-input' : '_addInfo',
                'click .image-edit-btn' : '_editPic'
            });
            this._initEdit();
        },
        _editPic : function( event ){
        	var imgmanageWidget = $.editorPlugin.get(this.editor, 'imgmanage');
        	var self = $(event.currentTarget),
        		img = self.closest('.img-info-item').find('img'),
        		imageId = img.attr('imageid');
        	if( this.slide ){
        		var item = imgmanageWidget.find('.image[imageid="'+ imageId +'"]');
        		item.trigger('mouseenter');
        		$('#pic-edit-btn').trigger('click');
        		item.trigger('mouseleave');
        	}else{
        		imgmanageWidget.imgmanage('bindPicEditor', {
        			target : img,
            		src : img.attr('src').replace('/640x',''),
            		imgId : imageId
        		});
        		self.siblings('.img-box').find('img').trigger('mouseenter');
        		$('#pic-edit-btn').css('visibility','hidden').trigger('click');
        		setTimeout(function(){
        			$('#pic-edit-btn').css('visibility','visible');
        			self.siblings('.img-box').find('img').trigger('mouseleave');
        		},500);
        	}
        },
        _imgFloat : function(event){
            var $target = $(event.currentTarget);
            var $img = $(this.img);
            var type = $target.attr('type'),
            	parent = $target.parent();
            if($target.hasClass('current')){
             	$target.removeClass('current');
             }else{
             	$target.addClass('current').siblings().removeClass('current');
             }
             parent.data('type', type);
             this.changeFloat($img, type);
        },

		_borderStyle : function(event){
			 var $img = $(this.img);
			 var $target = $(event.currentTarget),
			 	 $obj = $target.closest('span');
             var type = $target.attr('_style'),
             	 style = $target.attr('style');
             if($obj.hasClass('current')){
             	$obj.removeClass('current');
             }else{
             	$obj.siblings().removeClass('current');
             	$obj.addClass('current');
             }
              this.changeStyle($img, type, style);
		},

		_addInfo : function( event ){
			img = this.img;
            var $img = $(img);
			var $target = $(event.currentTarget);
			var val = $target.val();
			if($target.data('oldval') == val){
				return;
			}
			$target.data('oldval',val);
			var name = $target.attr('name').replace('image-info-', '');
			if(name == 'href'){
				if($img.parent().is('a')){
					$img.parent().attr('href', val);
				}else{
					$img.wrap('<a href="'+ val+ '" target = "_blank"></a>');
				}
			}else{
				$img.attr(name, val);
			}
		},

        _empty : function(){
            this.body.empty();
        },

        refresh : function(img){
            this.img = img;
            var _this = this,
            	$img = $(this.img);
            var $imgA = $img.closest('a');
            var style = $img.attr('style'),
            	direction = '';
        	if(style){
	            $.each(style.split(';'), function(key, value){
	            	var value = value.split(':');
	            	if($.trim(value[0]) == 'float'){
	            		direction = $.trim(value[1]);
	            		if(direction == 'none'){
		        			direction = 'center';
		        		}
	            	}
	            });
            }
            this.datas = {
                src : $img.attr('src'),
                href : $imgA[0] ? $imgA.attr('href') : '',
                oldwidth : $img.width(),
                // oldheight : $img.height(),
                maxwidth : _this.maxpicsize,
                title : $img.attr('title'),
                style : $img.attr('_style'),
                direction : direction,
                id : $img.attr('imageid')
            };
            this._empty();
            this._template(this.options.selfTemplateName, this.options.selfPluginInfo, this.body, this.datas);
            this.element.find('.imginfo-area').height(this.element.height()-this.title.height()-10);
            this._getEditorView();
            if( !this.element.hasClass('pop-show') ){
            	this.show();
            }
        },

		changeStyle : function(current, type, style){
			var type = type || 'none';
			current.attr('_style', type);
			current.removeAttr('style').attr('style',style);
			this.changeFloat( current );
			this.changeMargin( current );
		},

		changeFloat : function( current, type ){
			if(!type){
				type = this.element.find('.uii-pp').data('type') || 'none';
			}
			var range = this.range();
			range.selectNode(current[0]);
			range.select();
			this.exec( 'imagefloat', type );
			// switch(type){
				// case 'left' : 
					// current.css('float', 'left');
					// break;
				// case 'right' : 
					// current.css('float', 'right');
					// break;
				// case 'center':
					// var parent = current.parent();
					// if(!parent[0]){
						// current.wrap('<div style="text-align:center"></div>');
					// }else{
						// parent.css('text-align','center');
					// }
					// current.css('float','none');
					// break;
				// default :
					// current.css('float', 'none');
			// }
		},

		changeWidth : function( current, width ){
			var style = current.attr('style');
			var newstyle = '';
			if(style){
				$.each(style.split(';'), function(key, value){
					var value = value.split(':');
					var iden = $.trim(value[0]),
						name = $.trim(value[1]);
					if(iden && name && iden != 'width' && iden != 'height'){
						newstyle += iden + ':' + name + ';';
					}
				});
			}
			if(newstyle){
				current.attr('style', newstyle);
			}else{
				current.removeAttr('style');
			}
			current.removeAttr('width height').attr('width', width);
		},

		changeMargin : function( current, margin){
			var type = this.element.find('.uii-pp').data('type') || 'none';
			if(margin == undefined){
				margin = this.element.find('.margin-slider').slider('value');
                if(!margin) return;
			}
			switch(type){
				case 'left': 
					current.css({
						'margin-right' : margin + 'px',
						'margin-bottom' : margin + 'px'
					});
					break;
				case 'right':
					current.css({
						'margin-left' : margin + 'px',
						'margin-bottom' : margin + 'px'
					});
					break;
				case 'center':
					current.css({
						'margin-top' : margin + 'px',
						'margin-bottom' : margin + 'px'
					});
					break;
				default:
					current.css({
						'margin-left' : margin + 'px',
						'margin-right' : margin + 'px'
					});
			}
		},

		_getEditorView : function(){
			var _this = this,
				datas = this.datas,
				img = this.img;
			var $img = $(img);
			var widthSlider = this.element.find( '.width-slider' ).slider({
				animate: true,
				min: 10,
				// max: datas.oldwidth,
				max : _this.maxpicsize,
				step: 10,
				value: datas.oldwidth,
				slide : function(event, ui){
					var str = '当前宽度',
						width = ui.value,
						max = widthSlider.data('max');
					if( width ==  _this.maxpicsize){
						str = '最大宽度';
					}
					// var height = parseInt(max.Maxheight * (width / max.Maxwidth),10);
					var parent = $(this).parent();
					parent.find('.image-info-width').text(str +width);
					// parent.find('.image-info-height').text(height);
					_this.changeWidth($img, width);
				}
			}).data('max',{
				Maxwidth : datas.oldwidth,
				Maxheight : datas.oldheight
			});
			var marginSlider = this.element.find( '.margin-slider' ).slider({
				create: function() {
					$(this).addClass('myslider-ui')
					.next().text( 0 );
				},
				animate: true,
				min: 0,
				max: 50,
				step: 1,
				value: 0,
				slide : function(event, ui){
					var margin = ui.value;
					$(this).parent().find('em').text(margin);
					_this.changeMargin($img, margin);
				}
			});
			this.element.find('.info-input').each(function(){
				$(this).data('oldval', $(this).val());
			});
			var imgBorderstyle = this.element.find('.img-border-style img').filter(function(){
				return ($(this).attr('_style') == datas.style);
			});
			var imgFloatdirection = this.element.find('.uii-pp span').filter(function(){
				return ($(this).attr('type') == datas.direction);
			});
			this.element.find('.uii-pp').data('type',datas.direction);
			imgBorderstyle.parent().addClass('current');
			imgFloatdirection.addClass('current');
		},

        hide : function(){
            this._super();
            this.img = null;
        },

        ok : function(){

        },

		_initEdit : function(){
			if( !this.slide ){
				this.element.resizable();
			}
		},

        _destroy : function(){

        }
    });
})(jQuery);