/*attach.js*/
(function($){
    var attachInfo = {
		template : '' + 
				'<div class="editor-upload-area">' + 
					'<span class="upload-btn">添加附件</span>' + 
					'<p>pdf,txt,zip,docx,doc</p>'+
					'<input type="file" multiple class="upload-file" />' + 
				'</div>' + 
				'<div class="attach-area">' + 
					'<ul class="editor-content-area editor-attach-content-area">' +
					'</ul>' + 
				'</div>' + 
				'',
		item_tpl : '' + 
				'<li class="item-box m2o-flex m2o-flex-center" _id="${id}">' + 
					'<div class="attach-logo attach-${type}"></div>' +
					'<div class="attach-info m2o-flex-one"><span class="name">${name}</span><span class="size">(${filesize})</span></div>' +
					'<div class="attach-option">' +
						'<div class="attach-option-del"></div>' + 
					'</div>' + 
					'<input type="hidden" name="material_id[]" value="${id}" />' + 
				'</li>' + 
				'',
		css : '' + 
			'.attach-area{overflow-y:auto;overflow-x:hidden;}' +
			'.ump-box .editor-upload-area{position:absolute;bottom:0;left:10px;cursor:pointer;text-decoration: underline;}'+
			'.editor-upload-area p{text-align:center;margin-top:5px;color:#333;font-size:12px;}'+
			'.editor-upload-area{margin:10px 2px; font-size:14px; color:#5b98d1;}' + 
			'.editor-attach-content-area{margin: 0 10px;}' + 
			'.editor-inner-box{position:relative; padding:30px 10px; border-bottom:1px solid #e7e7e7;}' +
			'.attach-area .item-box{position:relative; padding:15px 0;border-bottom:1px solid #e7e7e7;overflow:hidden;cursor: pointer;}' + 
			'.attach-area .item-box:hover{background:#f0eff5;}' + 
			'.upload-file{display:none;}' + 
			'.editor-slide-box .upload-btn{cursor:pointer;display:block;border:1px dashed #d2d3d5;color:#9c9c9c;font-size:14px;width:95px;height:31px;line-height:31px;margin:0 auto;padding-left:64px;background:url('+$.ueditor.pluginDir+'/slide-button.png) no-repeat 40px 8px #f5f6f8;}'+
			'.attach-area .slidebtn{display: block;border: 1px dashed #d2d3d5;color: #9c9c9c;font-size: 14px;width: 95px;height: 31px;line-height: 31px;margin: 0 auto;padding-left: 64px;background: url('+$.ueditor.pluginDir+'/slide-button.png) no-repeat 40px 8px #f5f6f8;}'+
			'.attach-area .slidebtn:hover{color: #9fb7d8;border-color: #bbd2e3;background-color: #f2f9ff;background-image: url('+$.ueditor.pluginDir+'/slide-button-hover.png);}'+
			'.attach-area .attach-logo{width:24px;height:24px;margin:0 8px;background:url() no-repeat center;background-size:24px 24px;}' +
			'.attach-area .attach-doc,.attach-area .attach-docx{background-image:url('+$.ueditor.pluginDir+'/attach/doc-2x.png);}' +
			'.attach-area .attach-zip{background-image:url(./res/ueditor/third-party/m2o/images/attach/zip-2x.png)}' + 
			'.attach-area .attach-txt{background-image:url(./res/ueditor/third-party/m2o/images/attach/txt-2x.png);}' +
			'.attach-area .attach-pdf{background-image:url(./res/ueditor/third-party/m2o/images/attach/pdf-2x.png);}' +
			'.attach-area .attach-info{overflow:hidden;}' + 
			'.attach-area .size{color:#ababab;}' + 
			'.attach-area .attach-option{display:none;width:24px;height:24px;position:absolute;right:0;bottom:0;}' +
			'.attach-area .item-box:hover .attach-option{display:block;}' + 
			'.attach-area .attach-option-del{cursor:pointer;height:100%;background:url('+$.ueditor.pluginDir+'/del.png) #ccc no-repeat center;}' + 
			'.attach-area .attach-option-del:hover{background-image:url('+$.ueditor.pluginDir+'/del_hover.png);}' + 
			'.attach-area .del-transition{height:0;margin:0 auto;padding:0;border:0;}' + 
			'.ump-inner .editor-attach-content-area{min-height:176px}' +
			'',
		cssInited : false
    };

    $.widget('ueditor.attach', $.ueditor.baseWidget, {
        options : {
        	index : true,
        	title : '附件管理',
        },

        _create : function(){
            this._super();
            this._template('attach-template', attachInfo,  this.body);
            $.template('item_tpl',attachInfo.item_tpl);
            this.slide && this.element.find('.upload-btn').addClass('slidebtn');
        },

        _init : function(){
        	var op = this.options,
        		handlers = {};
        	this.content = this.element.find( '.editor-content-area' );
            this._super();
            this._on({
            	'click .upload-btn' : '_upload',
            	'click .attach-option-del' : '_delete',
            	'click .item-box' : '_insertAttach'
            });
            this._initInputFile();
            var hei = this.element.height()- this.title.height()- this.element.find('.editor-upload-area').outerHeight( true );
            this.element.find('.attach-area').height( hei );
            var _this = this;
            if( typeof attachList == 'undefined' ){
            	return;
            }
            if( attachList.length ){
            	$.each( attachList, function(k, v){
            		var obj = {
            				data : v,
            				index : k+1
            		};
            		_this._uploadAfter(obj);
            	} );
        	}
        },
        
        _initInputFile : function(){
        	var _this = this,
        		op = this.options,
        		url = op.config['uploadUrl'],
        		input_file = this.element.find( '.upload-file' );
	        var acceptType = [	
	                          'text/plain',
        	                  'application/pdf',
        	                  'application/zip',
        	                  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        	                  'application/msword' ];
	        input_file.attr('accept', acceptType.join() );
        	input_file.ajaxUpload({
        		url : url,
        		phpkey : 'Filedata',
        		type : 'attach',
        		after : function( json ){
        			_this._uploadAfter( json );
        		}
        	});
        },
        
        _upload : function(){
        	var op = this.options,
	    		root = this.element;
	    	var input_file = root.find( '.upload-file' );
	    	input_file.click();
        },
        
        _uploadAfter : function( json ){
        	var data = json['data'],
        		op = this.options;
        	if(data['error']){
        		this.element.find('.upload-btn').myTip({
					string : data['error'],
					delay : 1000,
					color : '#6ba4eb'
				});
        	}else{
            	this.content.find( '.editor-inner-box' ).remove();
            	var dom = $.tmpl('item_tpl', data).appendTo(this.content);
            	if( this.editorOp.needCount ){
            		$('.editor-statistics-item[_type="attach"]').find('span').text( this.element.find('.item-box').length );
            	}
            	this._recordattachInfo(data);
        	}
        },
        _insertAttach : function( event ){
        	var self = $(event.currentTarget),
        		id = self.attr('_id'),
        		code = this.attachCollection[id]['code'];
        	this.insertHtml( code );
        },
        _recordattachInfo : function( data ){
        	this.attachCollection = this.attachCollection || {};
        	var id = data['id'];
        	this.attachCollection[id] = data;
        },
        
        
        _delete : function( event ){
        	event.stopPropagation();
        	var self = $(event.currentTarget),
        		item = self.closest( '.item-box' ),
        		id = item.attr('_id'),
        		_this = this;
        	item.slideUp(function(){
        		item.remove();
        		if( _this.editorOp.needCount ){
        			$('.editor-statistics-item[_type="attach"]').find('span').text( _this.element.find('.item-box').length );
        		}
        	});
        	this._asyncDelmaterialId( id );
        },
        
        /*同步删除页面隐藏域中的素材id*/
       _asyncDelmaterialId : function( id ){
       		$('input[_id="' +  id +'"]').remove();
       	},
       	
        _destroy : function(){

        }
    });

    $.ueditor.m2oPlugins.add({
        cmd : 'attach',
        title : '附件管理',
        click : function(editor){
            $.editorPlugin.get(editor, 'attach').attach('show');
        }
    });
    


})(jQuery);

/*editorCount.js*/
(function($){
	var editorCountInfo = {
			template : ''+
//				'<div id="editor-count">'+
					'<ul class="editor-statistics clear">'+
						'<li class="editor-statistics-item"_type="word"><span>{{= wordCount}}</span>字数</li>'+
						'<li class="editor-statistics-item"_type="image"><span>{{= imgCount}}</span>图片</li>'+
						'<li class="editor-statistics-item"_type="attach"><span>{{= attachCount}}</span>附件</li>'+
						'<li class="editor-statistics-item"_type="pageslide"><span>{{= pageCount}}</span>页数</li>'+
						'<li class="editor-statistics-item"_type="pizhu"><span>{{= pizhuCount}}</span>批注</li>'+
					'</ul>'+
//				'</div>'+
				'',
			css : ''+
				'.editor-statistics{position:relative;min-width:220px;color:#a3a3a3;}'+
				'.editor-statistics li{float:left;border-left:1px solid #d3d3d3;padding:0 10px 0 5px;}'+
				'.editor-statistics li:first-child{border:none;}'+
				'.editor-statistics span{display:block;color:#333;font-weight:bold;margin-bottom:8px;}'+
				'.editor-statistics li:hover span{color:#1459a4;cursor:pointer;}'+
				'',
			cssInited : false
	};
	$.widget('ueditor.editorCount',$.ueditor.base, {
        options : {

        },
        _create : function(){
        	this._super();
        	if( this.editorOp.countDom ){
        		this.dom = $( this.editorOp.countDom );
        	}else{
        		this.dom = $('<div id="editor-count"></div>').prependTo('body');
        	}
        	this._template('editor_count_tpl', editorCountInfo, this.dom);
        },
        _init : function(){
        	this._super();
        	var _this = this;
        	this.dom.on('click','.editor-statistics-item',function(event){
        		var self = $(event.currentTarget),
        			type = self.attr('_type');
        		_this._tabEditorWidget( type );
        	});
        },
        _tabEditorWidget : function(type){
        	if( type == 'word' ){
        		return;
        	}
        	switch ( type ){
        		case 'image' : 
        			$.editorPlugin.get(this.editor, 'imgmanage').imgmanage('show');
        			break;
        		case 'attach' : 
        			$.editorPlugin.get(this.editor, 'attach').attach('show');
        			break;
        		case 'pizhu' : 
        			$.editorPlugin.get(this.editor, 'pizhu').pizhu('showAll');
        			break;
        		case 'pageslide' : 
        			$.editorPlugin.get(this.editor, 'page').page('show');
        			break;
        	}
        },
        _widgetCount : function(){
        	var data = {
        			wordCount : this.editor.getContentTxt().length,
        			imgCount : imgList.length,
        			attachCount : attachList.length,
        			pageCount : $(this.editorBody).find('.pagebg').length + $(this.editorBody).find('.pagebg-first').length,
        			pizhuCount : $(this.editorBody).find('.m2o-pizhu-before').length
        	}
        	$.tmpl( 'editor_count_tpl', data ).appendTo( this.dom.empty() );
        },
        //单个刷新，新增或删除时触发
        singleRefresh : function( type ){
        	var theItem = this.dom.find('.editor-statistics-item').filter(function(){
        		return $(this).attr('_type') == type;
        	}).find('span');
        	switch( type ){
        		case 'word' : 
        			theItem.text( this.editor.getContentTxt().length );
        			break;
        		case 'image' :
        			theItem.text( $.editorPlugin.get(this.editor, 'imgmanage').imgmanage('count') );
        			break;
        		case 'attach' :
        			theItem.text( $.editorPlugin.get(this.editor, 'attach').attach('count') );
        			break;
        		case 'pageslide' :
        			var page = $(this.editorBody).find('.pagebg'),
        				pageFirst = $(this.editorBody).find('.pagebg-first');
        			theItem.text( page.length + pageFirst.length);
        			break;
        		case 'pizhu' : 
        			theItem.text( $(this.editorBody).find('.m2o-pizhu-before').length );
        			break;
        	}
        },
        refresh : function( ){
        	this._widgetCount();
        },
    });
	
	
	(function(){
        var init = {};
        (function loop(){
            $.each(UE.instants, function(key, editor){
            	if(!init[key]){
                    init[key] = true;
                	editor.ready(function(){
                		$.editorPlugin.get(editor, 'editorCount').editorCount('refresh');
                		editor.body.addEventListener('keyup',function(){
                			$.editorPlugin.get(editor, 'editorCount').editorCount('singleRefresh','word');
                		});
                	});
            	}
            });
            setTimeout(loop, 500);
        })();
    })();
})(jQuery);

/*imginfo.js*/
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
        		imageId = self.closest('.img-info-item').find('img').attr('imageid');
        	var item = imgmanageWidget.find('.image[imageid="'+ imageId +'"]');
        	item.trigger('mouseenter');
        	$('#pic-edit-btn').trigger('click');
        	item.trigger('mouseleave');
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

/*imglocal.js*/
(function($){
    var imglocalInfo = {
		template : '' +  
				'',
		tip_tpl : '' +
				'<div class="local-message">' +
				'</div>' +
				'',
		img_tpl : '' + 
				'<img src="${src}" hash="${hash}" style="width:30px; height:30px; "/>' +
				'',
		small_animate_tpl : ''+
				'<div class="small-animate">'+
					'<img />'+
				'</div>'+
				'',
		tip_css : '' + 
				'.small-animate{border:1px dashed #ccc;opacity:0;width:32px;height:32px;float:left;margin:0 2px;}'+
				'.local-message{border:1px solid rgb(255, 154, 0);color:rgb(255, 154, 0);background-color:#fff; height:35px; line-height:35px; padding:10px; border-radius:2px;text-align:center;}' +
			'',
		cssInited : false
    };

    $.widget('ueditor.imglocal', $.ueditor.base, {
        options : {
        	title : '图片本地化'
        },

        _create : function(){
            this._super();
            this.editor_head = $(this.editor.document).find('head');
            this.editor_body = $(this.editor.document).find('body');
            $.template('item_tpl',imglocalInfo.item_tpl);
        },

        _init : function(){
        	this._super();
        	this._initFlagCss();
//        	this.initLocal();
        	if(!this.localIndex){
	            this.localIndex = +new Date();
	        }
        	this.srcs = [];
        },
		initLocal : function(){
			var _this = this;
			this._show();
			this._html('正在收集需要本地化的图片...');
			setTimeout(function(){
				_this.localImg(function( result ){
					if( !result ){
					 	_this._html('没有发现需要本地化的图片', true);
					 	setTimeout(function(){
					 		_this._hide();
					 	}, 800);
					}
				}); 
			}, 1000);
		},
		/** 上传word后搜集word中的图片进行本地化 
		 * 参数：word的内容*/
		queryPicsFromWord : function( content ){
			var imgs = content.find('img');
//			.....
		},
		/** 收集需要本地化的图片 */
		queryEditorPics : function(){
			var imgs = $(this.editorBody).find('img')
						.not('.m2o-pizhu, .image-refer, .image');
			var _this = this;
			this._showLocalPicsBox( '正在收集需要本地化的图片...' );
			setTimeout(function(){
				_this._handlePics( imgs );
			},1000);
		},
		
		/** 处理图片
		 * 如果是相同src的图片，只本地化一次 */
		_handlePics : function( imgs ){
			var imgArr = [],
				_this = this;
			$( imgs ).each(function(k, v){
				var self = $(v),
					src = $.trim( self.attr('src') );
				if( src && (src.indexOf('http') == 0) && ($.inArray(src, _this.srcs) == -1) ){
					_this.srcs.push( src );
					imgArr.push( self );
				}
			});
			var param = imgArr.length ? imgArr : '没有发现需要本地化的图片';
			this._showLocalPicsBox( param );
		},
		/** 显示本地化信息 */
		_showLocalPicsBox : function( param ){
			var _this = this;
			if( typeof param == 'string' ){
				$('.local-message').text( param ).show();
			}else if( param instanceof Array ) {
				$('.local-message').empty();
				var editorOffset = $(this.editor.iframe).offset();
				$( param ).each(function(){
					var imgOffset = $(this).offset(),
						cloneDom = $(this).clone().appendTo('body');
//					var hei = 
					cloneDom.css({
						'position' : 'absolute',
						'top' : imgOffset.left + editorOffset.left + 'px',
						'left' : imgOffset.top - _this.editorBody.scrollTop() + editorOffset.top + 'px',
						'border' : '10px solid'
					});
					
//					cloneDom.css({
//						'border' : '10px solid'
//					});
				});
			}
		},
		localImg : function( callback ){
			var _this = this;
			var localInfo = this._localImg( callback );
			this.data = [];
			if( localInfo ){
				var processImgs = localInfo.processImgs,
					len = processImgs.length;
				var index = oknum = 0;
				_this._html('');
				while( index < len ){
					var img = processImgs.shift();
					if(!img){
                        return false;
                    }
					var imgInfo = localInfo.outerImgs[index];
	                _this._insert(img, imgInfo, function(){
	                	 _this.tip_dom.css({
	                        width : len * 20 + 10 + 'px'
	                    });
	                });
	                index++;
				}
				this.doLocalUpload( localInfo, function( data ){
					oknum++;
					_this.data.push( data );
					if( oknum == localInfo.len ){
						_this._moreUpload( localInfo );
					}
				});
       			_this.editor.sync();
			}
		},
		
		doLocalUpload : function( localInfo, cb ){
			var _this = this;
			var outerImgs = localInfo.outerImgs,
				len = outerImgs.length;
			var index = 0;
			while( index < len ){
				var val = outerImgs.shift();
				if(!val){
                    return false;
                }
                _this._ajax( val, localInfo, cb );
                index++;
			}
		},
		
		_moreUpload : function( localInfo ){
			var _this = this;
			var json = this.data,
				len = json.length;
			var index = 0;
			while( index < len ){
				var data = json[index];
				if(!data){
	                return false;
	            }
	            if(data['error']){
	            	_this._error( data['hash'] );
	            }else{
	                _this._ajaxBack(data, localInfo, index, function(which){
	                	_this._remove(data, data['hash'], which);
	                	if(oknum == localInfo.len){
	                		_this._close();
	                	}
	                });
	            }
                index++;
			}
		},
		
		_ajaxBack : function(data, localInfo, index, cb){
			//$.editorPlugin.get(this.editor, 'imgmanage').imgmanage('show', data);
			this._after( data );
			cb && cb( $('.info-list') );
		},
		
		_localImg : function( callback ){
			var self = this;
			var outerSrcs = [], outerImgs = [], processImgs = [];
			$(this.editorBody).find('img').each(function(){
				var $this = $(this), 
					src;
                if( $this.attr('imageid') || $this.hasClass('pagebg') || $this.hasClass('m2o-pizhu') || $this.hasClass('image-refer') ){

                }else{
                    src = $.trim($this.attr('src'));
                    if(src && (src.indexOf('http') == 0) && ($.inArray(src, outerSrcs) == -1)){
                        outerSrcs.push(src);
                        outerImgs.push({
                            src : src,
                            _src : src,
                            hash : ++self.localIndex,
                            dom : $this
                        });
                        processImgs.push( $this );
                    }
                }
			});
			if( !outerImgs.length && callback){
				callback(false);
				return;
			}
			var result = {
					outerSrcs : outerSrcs,
					outerImgs : outerImgs,
					processImgs : processImgs,
				};
			return result; 
		},
		
		_ajax : function(val, localInfo, cb){
			var _this = this;
			var url = this.options.config['imgLocalUrl'];
    		$.getJSON(url, {url: val.src}, function(json){
    			var json = json[0];
    			var data = {};
    			for( var k in json ){
    				for( var i in json[k] ){
    					data[i] = json[k][i]
    				}
    			}
    			data.hash = val.hash;
    			data.oldurl = val.src;
                cb && cb( data );
                val.dom.attr({
                	'class' : 'image',
                	'imageid' : data.id,
                	'src' : $.globalImgUrl( data ),
                	'_src' : $.globalImgUrl( data ),
                	'hash' : data.hash
                });
                $.editorPlugin.get(_this.editor, 'imgmanage').imgmanage('acceptData',[data]);
                var dom = $('.local-message').find('img').filter(function(){
                	return $(this).attr('hash') == data.hash;
                }).closest('div');
                _this._animateToRight( dom );
                _this.editor.sync();
    		});
		},
		_animateToRight : function( dom ){
			var selfPos = dom.offset(),
				relyDomOff = $('.imgmanage-outer').offset();
			if( this.slide ){
				dom.css({
					'transition' : 'all .3s',
					'margin' : '40px 0 0 200px',
					'position' : 'absolute',
					'-webkit-transform' : 'scale(2)',
					'opacity' : '1',
				});
			}else{
				
			}
			var tipDom = this.tip_dom;
			var k = setTimeout(function(){
				dom.remove();
				var surplus = tipDom.find('img');
				var wid = surplus.length ? surplus.length * 20 + 10 + 'px' : '0';
				tipDom.css({
					width : wid
				});
				if( !surplus.length ){
					tipDom.hide();
				}
			},300);
		},
		_error : function( hash ){
			var thumImg = $(this).find('img[hash="'+ hash +'"]');
            thumImg.animate({
                top : '50px'
            }, 100).delay(100).animate({
                opacity : 0
            }, 300, function(){
                $(this).remove();
            });
		},
		
		_after : function( data ){
			 var bigsrc = $.globalImgUrl( data, '640x' );
			var img = this.editor_body.find('img[src="'+ data['oldurl'] +'"]');
            img.each(function(){
                var width = $(this).width();
                $(this).attr({
                    'class' : 'image',
                    src : bigsrc,
                    oldWidth : '640px',
                    hash : data['hash'],
                    imageid : data['id']
                }).width(width);
               $(this).removeAttr('_src');
            });
		},
		
		_insert : function(img, imgInfo, callback){
			var thumImg = $('<div/>').css({
				border : '1px dashed #ccc',
					opacity : 0,
	                width : '32px',
	                height : '32px',
	                'float' : 'left',
	                'margin-right' : '-20px'
            }).appendTo( this.tip_dom ).append($('<img/>').attr({
                hash : imgInfo['hash'],
                src : imgInfo['src']
            }).css({
                width : '32px',
                height : '32px'
            }));
            this._animate(img, imgInfo, thumImg);
            callback && callback.call(thumImg);
		},
		
		_animate :function(img, imgInfo, thumImg){
			var body = this.editor_body;
			var editorOffset = $(this.editor.iframe).offset();
            var thumImgOffset = thumImg.offset();
            var offset = img.offset();
	        // body.animate({
	            // scrollTop : offset.top - 20 + 'px'
	        // }, 100, function(){
	            var clone = $('<img class="imgInfo"/>').attr('src', imgInfo['src']).css({
	                position : 'absolute',
	                left : offset.left + editorOffset.left + 'px',
	                top : offset.top - body.scrollTop() + editorOffset.top + 'px',
	                'z-index' : 999,
	                width : img.width()
	            }).appendTo('body').animate({
	                width : '30px',
	                left : thumImgOffset.left + 'px',
	                top : thumImgOffset.top + 'px'
	            },300, function(){
	                $(this).remove();
	                thumImg.css('opacity', 1);
	            });
	        // });
		},
		
		_remove : function( data, hash, directImg ){
			var thumImg = this.tip_dom.find('img[hash="'+ hash +'"]');
			var thumImgParent = thumImg.parent(),
				thumOffset = thumImg.offset();
			var directImgOffset = directImg.offset();
			thumImg.appendTo('body').css({
                position : 'absolute',
                left : thumOffset.left + 'px',
                top : thumOffset.top + 'px',
                'z-index' : 1000000,
                width : '30px',
                height : '30px'
            }).animate({
				left : directImgOffset.left + 'px',
                top : directImgOffset.top + 'px',
                width : '160px',
			}, 400, function(){
				$(this).remove();
			});
			thumImgParent.html('OK');			
		},
		
		_close : function(){
			var _this = this;
			var tip_dom = this.tip_dom;
            if(tip_dom.find('img')[0]) return;
            this._html('本地化完成');
            setTimeout(function(){
            	_this._hide();
            }, 1000);
		},
		
		_html : function( html ){
			this.tip_dom.html( html ).css('width','auto');
		},

		_show : function(){
			this.tip_dom.show();
		},
		
		_hide : function(){
			this.tip_dom.hide( 300 );
		},

		_initFlagCss : function(){
        	$('<style/>').attr('style', 'text/css').appendTo(this.element).html(imglocalInfo.tip_css);
        	var offset = $('.edui-for-imglocal').offset();
        	offset.top +=26;
        	$.template( 'tip_tpl', imglocalInfo.tip_tpl );
        	this.tip_dom = $.tmpl('tip_tpl',{}).css({
        		'z-index' : 10000,
        		position : 'absolute',
        		top : offset.top + 'px',
        		left : offset.left + 'px',
        		display : 'none'
        	}).appendTo(this.element);
        },
        _destroy : function(){

        },
        /** 点击编辑器中tip上的本地化按钮 */
        localSinglePic : function( param ){
        	var target = param.target,
        		img = param.img;
        	var url = this.options.config['imgLocalUrl'],
        		src = img.src,
        		_this = this;
        	$.globalAjax( target, function(){
        		return $.getJSON(url, {url : src}, function(json){
        			var json = json[0];
        			var data = {};
        			for( var k in json ){
        				for( var i in json[k] ){
        					data[i] = json[k][i]
        				}
        			}
        			data.hash = +new Date();
        			data.oldurl = src;
        			$.editorPlugin.get(_this.editor, 'imgmanage').imgmanage('acceptData',[data], img);
        			$(img).attr({
                    	'class' : 'image',
                    	'imageid' : data.id,
                    	'src' : $.globalImgUrl( data ),
                    	'_src' : $.globalImgUrl( data ),
                    	'hash' : data.hash
                    });
        			param.callback();
        			_this.editor.sync();
        		});
        	} );
        },
    });

    $.ueditor.m2oPlugins.add({
        cmd : 'imglocal',
        title : '图片本地化',
        click : function(editor){
//            $.editorPlugin.get(editor, 'imglocal').imglocal('queryEditorPics');
        	$.editorPlugin.get(editor, 'imglocal').imglocal('initLocal');
        }
    });
})(jQuery);

/*imgmanage.js*/
/**
 * tip:有两个地方调用该组件
 * 一个是编辑器内，自己的图片管理按钮
 * 一个是form页中的上传索引图
 * 上传索引图file只可选一张，图片管理可选多张
 * ajaxUploadAfter的具体操作，根据this.externalCall变量来判断(是否是外部触发)，值为true/false,
 * 上传索引图，具体操作在实例化编辑器时绑定"_uploadSuoyinCallback"
 * */
(function($){
    var imgInfo = {
		template : '' + 
				'<div class="imgmanage-area">' + 
					'<div class="editor-current-img">' +
						'<!-- <a class="suoyin set-suoyin"></a> -->'+
						'<div class="item-inner-box">' + 
							'<img src="" class="img-item image" _id="" imageid="${id}"/>' + 
						'</div>' + 
						'<div class="img-indexpic"></div>' + 
						'<div class="img-option">' + 
							'<div class="img-option-box">' + 
								'<span class="image-option-left image-option-item" _deg="-90"></span>' +	
								'<span class="image-option-right image-option-item" _deg="90"></span>' +
							'</div>' + 
						'</div>' + 
					'</div>' + 
					'<ul class="editor-content-area editor-img-content-area"></ul>' + 
					'<div class="editor-upload-area"><span class="upload-btn">添加图片</span><input class="material-history" type="hidden" name="material_history" /><input type="file" multiple class="upload-file" accept="image/*"/></div>' + 
				'</div>' + 
				'',
		item_tpl : '' + 
				'<li class="item-box"  _id="${id}">' + 
					'<img class="image img-each" imageid="${id}" src="${_sSrc}" _src="${_mSrc}" bigsrc="${_bigsrc}" _id="${id}"/>' + 
					'<input type="hidden" value="${id}" name="material_id[]" />' + 
					'<span class="image-option-del image-option-item"></span>' +
				'</li>' + 
				'',
		css : '' + 
			'.suoyin{position:absolute;left:-2px;top:-2px;width:16px;height:46px;background:url('+$.ueditor.pluginDir+'/suoyintu-2x.png) no-repeat;cursor:pointer;background-size:16px 46px;}'+
			'.suoyin:hover, .suoyin-current{background-image:url('+$.ueditor.pluginDir+'/suoyintu_current-2x.png);}'+
			'.edit-slide-sort{position:absolute;right:50px;cursor:pointer;color:red;font-weight:bold;}'+
			'.imgmanage-area{margin:15px 10px 0 20px;}' +
			'.editor-upload-area{margin:10px 2px; font-size:14px; color:#5b98d1;}' + 
			'.editor-current-img{position:relative;width:160px;height:160px; display:inline-block; vertical-align:top; border:1px solid #e0dcdd;background:url('+$.ueditor.pluginDir+'/suoyin-default.png) no-repeat center;}' + 
			'.editor-current-img.has-pic{background-image:none;}'+
			'.editor-current-img:hover .img-option{display:block}' +
			'.editor-img-content-area{display:inline-block; max-height:155px; overflow-y:auto; overflow-x:hidden; width:55px;}' + 
			'.imgmanage-area .upload-file{display:none;}' + 
			'.imgmanage-area .upload-btn{display:inline-block; text-decoration:underline; cursor:pointer}' + 
			'.imgmanage-area .item-box{position:relative; width:30px;height:30px;margin:0 5px 5px; 1-webkit-transition:all .8s; background-color:rgba(255,255,255,0.8);}' + 
			'.imgmanage-area .item-inner-box{1-webkit-backface-visibility:hidden;font-size: 0;display:table-cell;width:160px;height:160px;vertical-align:middle;text-align:center;}' + 
			'.imgmanage-area .item-inner-box img{max-height:160px;max-width:160px;margin:0;padding:0;}' +
			'.imgmanage-area .img-indexpic{cursor:pointer;position:absolute;top:-2px;left:-2px;width:16px;height:46px;background:url('+$.ueditor.pluginDir+'/suoyintu.png) no-repeat;}' + 
			'.imgmanage-area .item-box:hover .img-indexpic{display:block;}' + 
			'.imgmanage-area .img-indexpic.current{background-image:url('+$.ueditor.pluginDir+'/suoyintu_current.png);}' + 
			'.imgmanage-area .img-option{display:none;position:absolute;left:0;bottom:0;width:100%;height:30px;}' +
			'.imgmanage-area .item-box:hover .image-option-del{display:block;}' + 
			'.imgmanage-area .img-option-box{height:15px;padding:7px 0 8px 0;background:rgba(120,120,120,0.8);}' + 
			'.image-option-item{display:inline-block; font-size:0;cursor:pointer;width:19px;height:15px;line-height:24px; margin:0 10px; background:url('+$.ueditor.pluginDir+'/left_rotate.png) left center no-repeat;}' + 
			'.image-option-right{background-image:url('+$.ueditor.pluginDir+'/right_rotate.png);}' + 
			'.image-option-left:hover{background-image:url('+$.ueditor.pluginDir+'/left_rotate_hover.png);}' + 
			'.image-option-right:hover{background-image:url('+$.ueditor.pluginDir+'/right_rotate_hover.png);}' + 
			'.imgmanage-area .del-transition{width:0;height:0;margin:0 5px 5px;border:0;}' + 
			'.image-option-del{width:16px; height:16px; position:absolute; z-index:999; font-size:0; top:-5px; right:-5px; margin:0; cursor:pointer; display:none; border-radius:50%; background: url('+$.ueditor.pluginDir+'/icon_close.png) no-repeat center #cdcdcd; background-size:10px 10px;}' + 
			'.item-box.selected{background-color:transparent;}' +
			'.img-each{width:100%; height:100%; position:absolute; z-index:-1;}' +
			'',
		cssInited : false
    };
    var slideImgInfo = {
    		template : ''+
				'<div class="edit-slide-button">'+
					'<span class="upload-btn">添加图片</span>'+
					'<input class="material-history" type="hidden" name="material_history" />'+
					'<input type="file" style="display:none" multiple="" class="upload-file" accept="image/*">'+
				'</div>'+
				'<div class="item-list-wrap"></div>'+
				'',
			item_tpl : '' +
				'<div class="item-box" _id="${id}">' +
					'<span class="del"></span>'+
					'<div class="item-inner-box">' +
						'<a class="suoyin set-suoyin"></a>' +
						'<img class="image" imageid="${id}" bigsrc="${_bigsrc}" path="${path}" dir="${dir}" filename="${filename}" _src="${_mSrc}">' +
					'</div>' +
					'<div class="nooption-mask"></div>' +
					'<div class="image-option-box">' +
						'<span class="image-option-left image-option-item" _deg="-90"></span>' +
						'<span class="image-option-right image-option-item" _deg="90"></span>' +
						'<span class="image-option-del image-option-item"></span>' +
					'</div>' +
					'<input type="hidden" value="${id}" name="material_id[]" />' + 
					'<img class="loading-img" src="'+ $.ueditor.pluginDir +'/loading.gif" />'+
				'</div>' +
				'',
			css : ''+
				'.edit-slide-sort{position:absolute;left:5px;cursor:pointer;color:red;font-weight:bold;}'+
				'.edit-slide-button{margin:15px 0 5px 0;}'+
				'.imgmanage-outer .upload-btn{cursor:pointer;display:block;border:1px dashed #d2d3d5;color:#9c9c9c;font-size:14px;width:95px;height:31px;line-height:31px;margin:0 auto;padding-left:64px;background:url('+$.ueditor.pluginDir+'/slide-button.png) no-repeat 40px 8px #f5f6f8;}'+
				'.item-list-wrap{overflow-y:auto}'+
				'.imgmanage-outer .item-box{width:160px;height:160px;border:1px solid #e0dcdd;background:#fff;position:relative;margin:10px auto 10px;text-align:center;line-height:160px;}'+
				'.imgmanage-outer .item-inner-box{font-size:0}'+
				'.item-inner-box .suoyin{display:none;position:absolute;left:-2px;top:-2px;width:16px;height:46px;background:url('+$.ueditor.pluginDir+'/suoyintu-2x.png) no-repeat;cursor:pointer;background-size:16px 46px;}'+
				'.item-inner-box .suoyin:hover, .item-inner-box .suoyin-current{display:block;background-image:url('+$.ueditor.pluginDir+'/suoyintu_current-2x.png);}'+
				'.item-inner-box img{vertical-align:middle;max-width:160px;max-height:160px;}'+
				'.image-option-box{display:none;position:absolute;bottom:0;height:22px;width:100%;background:rgba(0,0,0,.5);}'+
				'.item-box:hover .suoyin{display:block;}'+
				'.item-box:hover .image-option-box{display:block;}'+
				'.image-option-item{float:left;width:30px;height:22px;cursor:pointer;background:url('+$.ueditor.pluginDir+'/left_rotate.png) center no-repeat;}'+
				'.image-option-right{background-image:url('+$.ueditor.pluginDir+'/right_rotate.png);}'+
				'.image-option-del{float:right;background-image:url('+$.ueditor.pluginDir+'/delate.png);}'+
				'.image-option-left:hover{background-image:url('+$.ueditor.pluginDir+'/left_rotate_hover.png);}'+
				'.image-option-right:hover{background-image:url('+$.ueditor.pluginDir+'/right_rotate_hover.png);}'+
				'.image-option-del:hover{background-image:url('+$.ueditor.pluginDir+'/delate_hover.png);}'+
				'.image-option-del:hover{}'+
				'.loading-img{position:absolute;width:30px;top:50%;left:50%;margin:-15px 0 0 -15px;}'+
				'',
			cssInited : false
    };
    var material_tpl = ''+
    	'<div id="material_{{= id}}">'+
    		'<input type="hidden" name="material_id[]" value="{{= id}}" />'+
    		'<input type="hidden" name="material_name[]" value="{{= filename}}"/>'+
    	'</div>';
    
    $.widget('ueditor.imgmanage', $.ueditor.baseWidget, {
        options : {
        	title : '图片管理'
        },
        _create : function(){
            this._super();
            /*后台配置的图片最大宽度常量*/
           this.maxpicsize = $.maxpicsize ? parseInt( $.maxpicsize ) : 640;
            this._template('img-template',this.slide ? slideImgInfo : imgInfo, this.body);
            $('<span class="edit-slide-sort" shang="排序↑" xia="排序↓" state="shang">排序↑</span>').insertBefore(this.title);
            $.template('item_tpl',imgInfo.item_tpl);
            $.template('slide_item_tpl',slideImgInfo.item_tpl);
            $.template('material_tpl',material_tpl);
        },
        _init : function(){
            this._on({
            	'click .img-indexpic' : '_setIndex',
            	'click .upload-btn' : '_upload',
            	'click .set-suoyin' : '_setSuoyin',
            	'click .image-option-item' : '_baseHandle',
            	'click .edit-slide-sort' : '_sort',
            	'click .image' : '_insertImg',
            	'click .item-box' : '_setCurrent'
            });
            this._super();
            this._default();
            this.heiInit = false;	//初始化时计算一次高度
        },
        _default : function(){
        	this.gLoad = [];	//图片loading
        	this.content = this.element.find( '.editor-content-area' );
        	this.list = this.element.find('.item-list-wrap');
        	this._initInputFile();
        	this.resetManageView( typeof imgList == 'undefined' ? [] : imgList );
        },
        //重置视图
        resetManageView : function( data ){
        	var el = this.element;
        	if( !this.slide ){		//弹窗风格
        		el.find('.editor-content-area').empty();	//清空图片列表
        		el.find('.editor-current-img').removeClass('has-pic').attr('_id', '');	//重置当前图片预览
        		el.find('.item-inner-box img').attr({
        			src : '',
        			_id : '',
        			imageid : ''
        		});
        		el.find('.material-history').val('');			//清空历史纪录
        	}
        	if( data && data.length ){
        		this._ajaxUploadAfter( data );
        	}
        },
        /** file change */
        _initInputFile : function(){
        	var _this = this;
        	this.element.find('.upload-file').ajaxUpload({
        		url : _this.options.config['uploadUrl'],
        		phpkey : 'Filedata',
        		filter : function(data){
                	var water_id = $('#water_config_id').val();
                    data.append('water_config_id' ,water_id );
                },
        		after : function( json ){
        			var obj = [];
        			if( json['data'] instanceof Array ){
        				obj = json['data'];
        			}else{
        				obj = [json['data']];
        			}
        			_this._ajaxUploadAfter(obj);
        		}
        	});
        },
        _upload : function(){
        	this.externalCall = false;
	    	this.element.find('.upload-file').attr('multiple','multiple').click();
        },
        //点击上传索引图时触发
        showInputFile : function(){
        	this.externalCall = true;
        	this.element.find('.upload-file').removeAttr('multiple').click();
        },
        _ajaxUploadAfter : function(json){
        	var data = [],
        		ids_arr = [],
        		_this = this,
        		img = $( '.img-item' );		//弹窗风格中间那个dom
        	$.each( json, function(k,v){
        		var obj = $.extend({}, v, {
        			id : v['material_id'],
        			_sSrc : $.globalImgUrl( v, '30x30' ),
        			_mSrc : $.globalImgUrl( v, '160x' ),
        			_bigsrc : $.globalImgUrl( v )
        		});
        		ids_arr.push( obj['id'] );
        		data.push( obj );
        		_this._recordImginfo(obj);
        		_this._insertDom( obj );
        	});
        	this._setImgHistory( ids_arr );
        	if( this.editorOp.needCount ){
        		$('.editor-statistics-item[_type="image"]').find('span').text( this.element.find('.item-box').length );
        	}
        	if( this.externalCall ){	//具体操作实例化时自己写
        		this.editor.fireEvent('_uploadSuoyinCallback', data);
        		_this._changeCurrent( json );
        	}
        },
        
        _changeCurrent : function( json ){
        	var id = $.isArray( json ) ? json[0]['id'] : 0 ;
        	id && this.element.find('.item-box[_id=' + id + ']').find('.set-suoyin').trigger('click', ['externalCall']);
        },
        
        _insertDom : function( data ){
        	if( !this.slide ){		//弹窗风格
        		$.tmpl('item_tpl', data).appendTo(this.content);
        		var prevImg = this.element.find('.editor-current-img');
            	if( !prevImg.attr('src')){
            		var first = $( '.item-box:first-child');
            		first.click();
            	}
        	}else{			//侧滑风格
        		var dom = $.tmpl('slide_item_tpl', data).prependTo(this.list);
            	this._loadImg( dom );
            	this._editPic( dom );
            	if( this.editorOp && this.editorOp.suoyinId == data['material_id'] ){
            		dom.find('.suoyin').addClass('suoyin-current');
            	}
        	}
        },
        _editPic : function( dom ){
        	var me = dom.find('.image'),
        		imageId = 'slide-image',
            	imgSrc = me.attr('path') + me.attr('dir') + me.attr('filename'),
            	_this = this;
            me.picEdit({
                imageId : imageId,
                imgSrc : imgSrc,
                saveAfter : function(){
                    top.$('body').find('img.tmp-edit-top-img').remove();
                    top.$('body').off('_picsave').on('_picsave', function(event, info){
                        try{
                            var topImg = $(this).find('#slide-image');
                            var imageid = topImg.attr('imageid');
                            var attrs = {
                                path : info['host'] + info['dir'],
                                dir : info['filepath'],
                                filename : info['filename'],
                                src : $.globalImgUrl(info, '160x', true),
                                bigsrc : $.globalImgUrl(info, _this.maxpicsize + 'x', true)
                            };
                            me.attr(attrs);
                            $(_this.editorBody).find('.image[imageid="'+ imageid +'"]').attr('src',attrs.bigsrc).attr('_src',attrs.bigsrc);
                            $.editorPlugin.get(_this.editor, 'imginfo').find('.img-box img').attr('src',attrs.bigsrc);
                            $(this).find('img.tmp-edit-top-img').remove();
                            if( dom.find('.suoyin').hasClass('suoyin-current') ){
                            	_this.editor.fireEvent('_refreshIndexPic', attrs);
                            }
                    		_this.editor.sync();
                        }catch(e){}
                    }).append(me.clone().hide().attr('id', imageId).addClass('tmp-edit-top-img')).data('current-edit-image', imageId);
                }
            });
        },
        _setImgHistory : function( ids_arr ){
        	var imgHistory_hidden = this.element.find('.material-history'),
	    		has_ids = imgHistory_hidden.val(),
	    		ids_new = ids_arr;
	    	if( has_ids ){
	    		has_ids = has_ids.split(',');
	    		ids_new = ids_arr.concat( has_ids );
	    	}
	    	imgHistory_hidden.val( ids_new.join() );
        },
        _loadImg : function( dom ){
        	var img = new Image();
        	img.src = dom.find('.image').attr('_src');
        	img.onload = function(){
        		dom.find('.loading-img').hide();
        		dom.find('.image').attr('src',img.src);
        	};
        },
        _baseHandle : function( event ){
        	var self = $(event.currentTarget);
        	self.hasClass('image-option-del') ? this._del( event ) : this._rotateImg( event );
        },
        /** 旋转 */
        _rotateImg : function(event){
        	var self = $(event.currentTarget),
        		url = this.options.config['revolveImgUrl'],
        		parent = this.slide ? self.closest('.item-box') : this.element.find('.editor-current-img'),
        		img = parent.find('.image'),
        		imgId = img.attr('imageid'),
        		deg = parseInt( self.attr('_deg') ),
        		_this = this;
        		param = {
	        			material_id : imgId,
	        			direction : deg > 0 ? 2 : 1
        			};
        	parent.find('.loading-img').show();
        	$.getJSON(url, param, function(json){
        		var data = json[0];
    			_this._recordImginfo(data);
    			parent.find('.loading-img').hide();
    			var bigsrc = $.globalImgUrl( data, '', true );
    			img.attr('src', $.globalImgUrl( data, '160x', true ) );
    			_this._preloadImg(bigsrc, function(){
    	            _this._iframeImageRefresh(imgId, bigsrc);
    	        });
    	        if( parent.find('.suoyin').hasClass('suoyin-current') ){
    	        	_this.editor.fireEvent('_refreshIndexPic', data);
    	        }
    			if( !_this.slide ){	
    				var sSrc = $.globalImgUrl( data, '30x30' );
    				var theSItme = _this.element.find('.item-box').filter(function(){
    					return $(this).attr('_id') == imgId;
    				});
    				theSItme.find('img').attr('src',sSrc );
    			}
        	});
        },
        
        _preloadImg : function( src, callback ){
        	if($.type(src) == 'array'){
                $.each(src, function(i, n){
                    var img = new Image();
                    img.src = n;
                });
            }else{
                var img = new Image();
                img.onload = function(){
                    callback && callback();
                };
                img.src = src;
            }
        },
        _iframeImageRefresh : function( id, src ){
        	var images = this.iframeImageGet( id );
            if(images.length) images.attr('src', src);
        },
        
        /** 删除 */
        _del : function(event){
        	event.stopPropagation();
        	var self = $(event.currentTarget),
        		imgId = self.closest('.item-box').find('.image').attr('imageid'),
        		_this = this;
        	var relImgs = this.iframeImageGet( imgId );		//编辑器中对应的图片
        	if( !relImgs.length ){
        		_this._delCallback( self );
				_this._isdelIndexPic( self );
        	}else{
        		jConfirm('编辑器中已经插入此图片了，如果删除将连同一起删除，是否确定删除？', '删除提示', function(result){
        			if( result ){
        				_this._delCallback( self );
        				_this._isdelIndexPic( self );
        				relImgs.remove();
        				$('#material_'+ imgId ).remove();
        				_this.editor.focus();
        			}
        		});
        	}
        },
        
        _delCallback : function( self ){
    		var	parent = self.closest('.item-box'),
        		imgId = parent.find('.image').attr('imageid'),
        		_this = this;
        	if( !this.slide && parent.hasClass('selected') ){
				this.element.find('.editor-current-img').attr('_id', '').removeClass('has-pic');
				this.element.find('.item-inner-box img').attr('src', '');
			}
        	parent.slideUp(function(){
				parent.remove();
				if( _this.editorOp.needCount ){
					$('.editor-statistics-item[_type="image"]').find('span').text( _this.element.find('.item-box').length );
				}
			});
			this._asyncDelmaterialId( imgId );
        },
        
        /*删除索引图回调*/
        
        _isdelIndexPic : function( self ){
        	if( this.slide ){
    			var isindex = self.closest('.item-box').find('.set-suoyin').hasClass('suoyin-current');
        		isindex && this.editor.fireEvent('_setIndex', null);
        	}
        },
        
        /*同步删除页面隐藏域中的素材id*/
        _asyncDelmaterialId : function( id ){
        	$('input[_id="' +  id +'"]').remove();
        },
        
        /** 插入图片至编辑器 */
        _insertImg : function( event ){
        	var self = $(event.currentTarget),
        		id = self.attr('imageid');
        	var data = this.imgCollection[id];
        	this.insertImg('img', data);
        	if( this.editorOp.needCount ){
        		$.editorPlugin.get(this.editor, 'editorCount').editorCount('refresh');
        	}
        },
        _sort : function( event ){
        	var self = $(event.currentTarget),
        		state = self.attr('state'),
        		state = state == 'shang' ? 'xia' : 'shang';
        	self.html(self.attr(state));
        	self.attr('state', state);
        	var list = this.slide ? this.list : this.element.find('.editor-img-content-area');
        	var imgs = list.find('.item-box');
            if(!imgs.length) return;
            var lastImg = null;
            imgs.each(function(){
            	lastImg ? $(this).insertBefore(lastImg) : $(this).appendTo(list);
                lastImg = this;
            });
            lastImg = null;
        },
        _recordImginfo : function( data ){
        	this.imgCollection = this.imgCollection || {};
        	var id = data['id'];
        	this.imgCollection[id] = data;
        },
        _click : function( event ){
        	var self = $(event.currentTarget),
        		id = self.attr('_id');
        	var data = this.imgCollection[id];
        	this.insertImg('img', data);
        },
        
        _setCurrent : function( event ){
        	var self = $(event.currentTarget);
        	if( this.slide || self.hasClass('selected') ){
        		return;
        	}
        	var	id = self.attr('_id'),
        		src = self.find('img').attr('_src'),
        		load = $.globalLoad( self ),
        		img = new Image();
    		self.addClass( 'selected' ).siblings().removeClass( 'selected' );
    		img.src = src;
    		img.onload = function(){
    			load();
    		};
    		var currentWrap = this.element.find('.editor-current-img');
    		currentWrap.attr('_id',id).addClass('has-pic');
    		currentWrap.find('.item-inner-box img').attr({'src':src,'_id':id,'imageid': id});
	    	$( '.index-pic' ).val(id);
        },
        
        //弹窗设索引图
        _setIndex : function( event, type ){
        	var op = this.options,
        		self = $(event.currentTarget),
        		item = self.closest( '.editor-current-img' ),
        		id = item.attr('_id');
        	var data = this.imgCollection[id];
        	if( self.hasClass( 'current') ){
        		data = null;
        	}else{
        		item.siblings().find( '.img-indexpic' ).removeClass( 'current' );
        	}
        	self.toggleClass( 'current' );
	        !type && this.editor.fireEvent('_setIndex', data );
        },
        //侧滑设索引图
        _setSuoyin : function( event ){
        	var self = $(event.currentTarget),
        		id = self.siblings('.image').attr('imageid');
        	var data = self.hasClass( 'suoyin-current' ) ? null : this.imgCollection[id];
        	this.element.find('.set-suoyin').not(self).removeClass('suoyin-current');
        	self.toggleClass( 'suoyin-current' );
        	this.editor.fireEvent('_setIndex', data );
        },
        //刷新图片
        refreshPic : function(img, data){
        	var imgid = $( img ).attr('imageid'),
        		src = $.globalImgUrl(data, '', true);
        	this.element.find('.image[imageid="'+ imgid +'"]').attr('src',src);
        },
        /**
         * 刷新图片2	
         * 点索引图的编辑、编辑器内图片的旋转后触发
         * param( id )
         * */
        refreshPicSrc : function( id ){
        	var item = this.element.find('.image[imageid="'+ id +'"]'),
        		src = item.attr('src');
        	item.attr('src', src+'?'+new Date().getTime());
        },
        
        acceptData : function( data, img ){
        	if( !this.element.hasClass('pop-show') ){
        		this.show();
        	}
        	this._ajaxUploadAfter(data);
        },
        show : function(){
        	this._super();
        	if( !this.heiInit ){
        		this.heiInit = true;
        		var hei = this.element.height() - this.title.height() - this.element.find('.edit-slide-button').outerHeight(true) - 15;
        		this.element.find('.item-list-wrap').height(hei);
        	}
        },
        count : function(){
        	return;
        	var num = this.element.find('.item-box').length;
        	return num;
        },
        _destroy : function(){

        },
    });

    $.ueditor.m2oPlugins.add({
        cmd : 'imgmanage',
        title : '图片管理',
        click : function(editor){
            $.editorPlugin.get(editor, 'imgmanage').imgmanage('show');
        }
    });
})(jQuery);

/*office.js*/
(function($){
    var officeInfo = {
		template : '' + 
				'<input type="file" class="office-file" name="file" accept="${msType}" style="display:none;"/>'  +
				'',
		flag_tpl : '' +
				'<div>' +
					'<p class="word-uploading">文档名：<strong>${name}</strong>。正在上传中，请稍等...</p>' +
				'</div>' +
				'',
		tip_tpl : '' +
				'<div class="office-message">' +
					'<span>${message}</span>' +
				'</div>' +
				'',
		css : '' +
				'.office-message{border:1px solid rgb(255, 154, 0);color:rgb(255, 154, 0);background:#fff;border-radius:2px;text-align:center;}' +
				'',
		tip_css : '' + 
				'.word-uploading{font-size:12px;font-weight:normal;color:#dedede;background:url(' + $.ueditor.pluginDir +'/office.png) no-repeat;border:1px solid #dedede;height:53px;line-height:53px;padding-left:45px;margin:0 15px;}' +
				'.word-uploading strong{color:#000;}' +
			'',
		cssInited : false
    };

    $.widget('ueditor.office', $.ueditor.base, {
        options : {
	        msType : [
				        'application/x-zip-compressed',
				        'application/msword',
				        'application/vnd.ms-powerpoint',
				        'application/vnd.ms-excel',
				        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
				        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
				    ]
        },

        _create : function(){
            this._super();
            this._template( 'input_file_tpl', officeInfo, this.element, {msType : this.options.msType.join() } );
            this.editor_head = $(this.editor.document).find('head');
            this.input_file = this.element.find('.office-file');
        },

        _init : function(){
        	this._super();
        	this._initUpload();
        	this._initFlagCss();
        },
        
        
        _initUpload : function( option ){
        	var _this = this;
        	this.input_file.ajaxUpload({
        		url : _this.options.config['officeUrl'],
        		type : 'doc',
        		phpkey : 'file',
        		beforeSend : function( data ){
        			_this._uploadBefore( data );
        		},
        		after : function( json ){
        			_this._uploadAfter( json['data'] );
        		},
        		error : function( json ){
        			_this._serviceError( json );
        		}
        	});
        },
        
        _serviceError : function( data ){
        	this.tip_dom.remove();
        	if( data['error'] ){
	        	this._tip( data['error'] );
	        	$(this.editorBody).find('.word-uploading').remove();
	        	var _this = this;
	        	setTimeout(function(){
	        		_this.tip_dom.fadeOut();
	        	},2000);
        	}
        },
        
        _uploadBefore : function( data ){
        	var fileinfo = {},
        		flag_html = null;
        	fileinfo.name = data.file.name;
        	this._tip('文档上传中...');
        	$.template( 'flag_tpl', officeInfo.flag_tpl );
        	flag_html = $.tmpl( 'flag_tpl', fileinfo );
        	this.insertHtml( flag_html.html() );
        },
        
        _uploadAfter : function( data ){
        	this.tip_dom.remove();
        	if( data['error'] == true ){
        		this._uploadError();
        	}else{
        		this._tip( '文档上传成功！正在解析中...' );
        		this._parseWord( data );
        	}
        },
        _uploadError : function(){
        	this._tip( '文档解析出错！' );
        	$(this.editorBody).find('.word-uploading').remove();
        	var _this = this;
        	setTimeout(function(){
        		_this.tip_dom.fadeOut();
        	},2000);
        },
        _parseWord : function( data ){
        	var _this = this,
        		href = location.href;
            var url = href.substr(0, href.indexOf(location.pathname));
            url += (location.pathname.indexOf("livworkbench") != -1 ? "/livworkbench" : "") + "/";
            var doc_iframe = $("<iframe/>").attr("src", url + data["url"]).on({
                load : function(){
                    var body = $(this.contentWindow.document).find("body");
                    bodyImgNum = body.find("img").each(function(){
                        $(this).attr("src", url + data.path + $(this).attr("src"));
                    }).length;
                    _this.tip_dom.remove();
                    doc_iframe.off('load').remove();
                    _this._insertWord( body );
                    _this.tip_dom.remove();
                }
            }).css("display", "none").appendTo("body");
        },
        
        _insertWord : function( body ){
        	body.find('table[border]').css({
        		'border' : '1px solid #000'
        	});
        	var content = body.html(),
        		flag_dom = $(this.editor.document).find('.word-uploading');
        	flag_dom.replaceWith( content );
        	$.editorPlugin.get(this.editor, 'imglocal').imglocal('initLocal');
        },
        
        _tip : function( message ){
        	var offset = $('.edui-for-office').offset();
        	offset.top +=26;
        	$.template( 'tip_tpl', officeInfo.tip_tpl );
        	this.tip_dom = $.tmpl('tip_tpl',{ message : message }).css({
        		'z-index' : 10000,
        		position : 'absolute',
        		top : offset.top + 'px',
        		left : offset.left + 'px'
        	}).appendTo(this.element);
        },
        
        _initFlagCss : function(){
        	$('<style/>').attr('style', 'text/css').appendTo(this.editor_head).html(officeInfo.tip_css);
        },
        
        click : function(){
        	this.input_file.trigger('click');
        },

        _destroy : function(){

        }
    });

    $.ueditor.m2oPlugins.add({
        cmd : 'office',
        title : '上传word文档',
        click : function(editor){
            $.editorPlugin.get(editor, 'office').office('click');
        }
    });
    


})(jQuery);


/*page.js*/
UE.plugins['m2o_pagebreak'] = function () {
    var me = this,
        notBreakTags = ['td'];
    me.setOpt('m2o_pageBreakTag','_m2o_ueditor_page_break_tag_');
    var domUtils = UE.dom.domUtils;
    var utils = UE.utils;
    var src = $.ueditor.pluginDir + '/page/bg' + ($.ueditor.gPixelRatio > 1 ? '-2x' : '') + '.png';
    UE.m2oPageBgSrc = src;

    var pageHtml = '<img class="pagebg" src="' + src + '" style="-webkit-user-select: none;">';
    UE.m2oPageBgHtml = pageHtml;

    function fillNode(node){
        if(domUtils.isEmptyBlock(node)){
            var firstChild = node.firstChild,tmpNode;

            while(firstChild && firstChild.nodeType == 1 && domUtils.isEmptyBlock(firstChild)){
                tmpNode = firstChild;
                firstChild = firstChild.firstChild;
            }
            !tmpNode && (tmpNode = node);
            domUtils.fillNode(me.document,tmpNode);
        }
    }
    //分页符样式添加

    me.ready(function(){
        var css = '' +
            '.pagebg{position:relative;display:block;clear:both !important;cursor:default !important;width: 100%;margin:50px -8px;height:8px;}' +
            '.pagebg:before{content:"";position:absolute;top:0;left:-20px;width:20px;height:100%;background:url(' + src + ') no-repeat 0 0;}' +
            '.pagebg:after{content:"";position:absolute;top:0;right:-20px;width:20px;height:100%;background:url(' + src + ') no-repeat 0 0;}' +
            '';
        utils.cssRule('pagebg', css, me.document);
    });
    function isPage(node){
        return node && node.nodeType == 1 && node.tagName == 'IMG' && node.className == 'pagebg';
    }
    me.addInputRule(function(root){
        root.traversal(function(node){
            if(node.type == 'text' && node.data == me.options.m2o_pageBreakTag){
                var img = UE.uNode.createElement(pageHtml);
                node.parentNode.insertBefore(img,node);
                node.parentNode.removeChild(node)
            }
        })
    });
//    me.addOutputRule(function(node){
//        utils.each(node.getNodesByTagName('img'),function(n){
//            if(n.getAttr('class') == 'pagebg'){
//                var txt = UE.uNode.createText(me.options.m2o_pageBreakTag);
//                n.parentNode.insertBefore(txt,n);
//                n.parentNode.removeChild(n);
//            }
//        })
//    });
    me.commands['m2o_pagebreak'] = {
        execCommand:function () {
            var range = me.selection.getRange();
            /*var page = me.document.createElement('img');
            domUtils.setAttributes(page,{
                'class' : 'pagebg',
                src : src
            });
            domUtils.unSelectable(page);*/
            var page = $(pageHtml)[0];
            //table单独处理
            var node = domUtils.findParentByTagName(range.startContainer, notBreakTags, true),
                parents = [], pN;
            if (node) {
                switch (node.tagName) {
                    case 'TD':
                        pN = node.parentNode;
                        if (!pN.previousSibling) {
                            var table = domUtils.findParentByTagName(pN, 'table');
                            /*var tableWrapDiv = table.parentNode;
                            if(tableWrapDiv && tableWrapDiv.nodeType == 1
                                && tableWrapDiv.tagName == 'DIV'
                                && tableWrapDiv.getAttribute('dropdrag')
                                ){
                                domUtils.remove(tableWrapDiv,true);
                            }*/
                            table.parentNode.insertBefore(page, table);
                            parents = domUtils.findParents(page, true);

                        } else {
                            pN.parentNode.insertBefore(page, pN);
                            parents = domUtils.findParents(page);

                        }
                        pN = parents[1];
                        if (page !== pN) {
                            domUtils.breakParent(page, pN);

                        }
                        //table要重写绑定一下拖拽
                        me.fireEvent('afteradjusttable',me.document);
                }

            } else {
                if (!range.collapsed) {
                    range.deleteContents();
                    var start = range.startContainer;
                    while ( !domUtils.isBody(start) && domUtils.isBlockElm(start) && domUtils.isEmptyNode(start)) {
                        range.setStartBefore(start).collapse(true);
                        domUtils.remove(start);
                        start = range.startContainer;
                    }

                }
                range.insertNode(page);
                var pN = page.parentNode, nextNode;
                while (!domUtils.isBody(pN)) {
                    domUtils.breakParent(page, pN);
                    nextNode = page.nextSibling;
                    if (nextNode && domUtils.isEmptyBlock(nextNode)) {
                        domUtils.remove(nextNode);
                    }
                    pN = page.parentNode;
                }
                nextNode = page.nextSibling;
                var pre = page.previousSibling;
                if(isPage(pre)){
                    domUtils.remove(pre);
                }else{
                    pre && fillNode(pre);
                }

                if(!nextNode){
                    var p = me.document.createElement('p');

                    page.parentNode.appendChild(p);
                    domUtils.fillNode(me.document,p);
                    range.setStart(p,0).collapse(true);
                }else{
                    if(isPage(nextNode)){
                        domUtils.remove(nextNode);
                    }else{
                        fillNode(nextNode);
                    }
                    range.setEndAfter(page).collapse(false);
                }

                range.select(true);

            }

        }
    };
};


(function($){

    var pluginInfo = {
        dialogBox : {
            template : '',
            css : '',
            cssInited : false
        },
        pageBox : {
            template : '<div class="up-page"></div>',
            css : '' +
                '.edui-default .edui-editor-toolbarbox{z-index:1000;}' +
                '.up-page{position:absolute;left:0;top:0;width:0;height:0;z-index:999;}' +
                '.up-page .up-page-item{position:absolute;left:20px!important;top:0;1background:rgba(255, 255, 255, .8);}' +
                '.up-page .up-page-item-inner{position:absolute;left:0;bottom:10px;width:100%;}' +
                '.up-page input{position:absolute;top:-4px;width:95%;border:none;border-bottom:2px solid #e8e8e8;background:transparent;}' +
                '.up-page input:focus{box-shadow:none;}' + 
                '.up-page .up-page-item.focus input{border-bottom-color:#5a98d1;}' + 
                '.up-page .up-page-index{display:inline-block;height:25px;line-height:25px;width:27px;background:url('+$.ueditor.pluginDir+'/page/page-normal-2x.png) no-repeat;color:#999;background-size:27px 25px;text-indent:-4px;text-align:center;font-size:12px;}' +
                '.up-page .up-page-item.focus .up-page-index{background-image:url('+$.ueditor.pluginDir+'/page/page-current-2x.png);color:#fff;}' + 
                '.up-page .up-page-del{position:absolute;right:5px;top:-4px;height:25px;width:25px;background:url('+$.ueditor.pluginDir+'/page/del-2x.png) center no-repeat;cursor:pointer;background-size:8px 8px;}' +
                '',
            cssInited : false
        },
        pageItem : {
            template : '' +
                '<div class="up-page-item" style="{{= style}}" data-index="{{= index}}">' +
                    '<div class="up-page-item-inner">' +
                        '<span class="up-page-index">{{= index}}</span>' +
                        '<input value="{{= title}}"/>' +
                        '{{if index > 1}}<span class="up-page-del"></span>{{/if}}' +
                    '</div>' +
                '</div>' +
                ''
        },
        pageInfoItem : {
        	template : '' +
	        		'<div class="page-info-item" data-index="{{= index}}">'+
		        		'<span class="page-info-flag">{{= index}}</span>'+
		        		'<textarea class="page-info-content">{{= title}}</textarea>'+
	        		'</div>'+
	            '',
            css : ''+
            	'.page-info-item{margin:0 10px;border-bottom:1px solid #e7e7e7;padding:10px 0;}'+
            	'.page-info-flag{display:inline-block;width:27px;height:25px;text-indent:-4px;text-align:center;line-height:25px;color:#939393;cursor:pointer;background:url('+$.ueditor.pluginDir+'/page/page-normal-2x.png);background-size:27px 25px;}'+
            	'.page-info-item.current .page-info-flag{background-image:url('+$.ueditor.pluginDir+'/page/page-current-2x.png);color:#fff;}'+
            	'.page-info-content{vertical-align:middle;height:22px;line-height:22px;width:150px;resize:none;margin-left:10px;border-color:transparent;background:transparent;}'+
            	'.page-info-content:hover{border-color:transparent;box-shadow:none;}'+
            	'.page-info-content:focus{border: 1px solid #77b7f9;-webkit-box-shadow: 0 0 3px #ccc;}'+
            	'',
            cssInited : false
        }
    };

    $.widget('ueditor.page', $.ueditor.baseWidget, {
        options : {
        	title : '分页设置',
            pluginInfo : pluginInfo,
            animateDuration : 800
        },

        _create : function(){
            this.hide();
            this._super();
            this.pageBox = this._template('page-template', this.options.pluginInfo.pageBox, $(this.editor.iframe).parent());
        },

        _init : function(){
            this._super();
            this._on(this.pageBox, {
                'click .up-page-item' : '_itemClick',
                'click .up-page-del' : '_itemDel',
                'keyup .up-page-item input' : '_itemKeyup',
                'focus .up-page-item input' : '_itemFocus',
                'blur .up-page-item input' : '_itemBlur'
            });
            this._on({
            	'keyup .page-info-content' : '_pageInfoKeyUp',
            	'focus .page-info-content' : '_pageInfoFocus',
            	'blur .page-info-content' : '_pageInfoBlur',
            });
            this.body.height( this.element.height() - this.title.height() ).css('overflow-y','auto');
        },
        ok : function(){
            this._super();
        },

        _itemClick : function(event){
            $(event.currentTarget).find('input').focus();
        },

        _itemDel : function(event){
            var index = $(event.currentTarget).closest('.up-page-item').data('index');
            this._getEditorPageByIndex(index - 2).remove();
            this._getPageInfoByIndex(index).slideUp();
            if( $(this.editorBody).find('.pagebg').length == 0 ){
            	this._getEditorPageByIndex(-1).remove();
                this._getPageInfoByIndex(1).slideUp();
            }
            this._savePage();
            this.refresh();
            return false;
        },

        _itemKeyup : function(event){
            var target = $(event.currentTarget);
            var val = target.val();
            var index = target.closest('.up-page-item').data('index');
            this._getEditorPageByIndex(index - 2).attr('_title', val);
        	this._getPageInfoByIndex(index).find('textarea').val(val);
            this._savePage();
        },
        
        
        _itemFocus : function( event ){
        	var target = $(event.currentTarget);
        	this._toggleFocusClass( target, true );
        },
        
        _itemBlur : function(){
        	var target = $(event.currentTarget);
        	this._toggleFocusClass( target, false );
        },
        
        _toggleFocusClass : function( target, bool ){
        	var item = target.closest('.up-page-item');
        	$('.up-page-item').removeClass( 'focus' );
        	item[( bool ? 'add' : 'remove' ) + 'Class']('focus');
        },
		//编辑器内用于存分页信息的img标签
        _getEditorPageByIndex : function(index){
            var $doc = $(this.editor.document),
            	pagebgFirst = $(this.editorBody).find('.pagebg-first');
            return index == -1 ? pagebgFirst : $doc.find('.pagebg').eq(index);
        },
		//分页展示列表中的item
        _getPageInfoByIndex : function(index){
        	var item = this.element.find('.page-info-item[data-index="'+ index +'"]');
        	return item;
        },
        //分页样式
        _getPageInputByIndex : function(index){
        	var item = this.pageBox.find('.up-page-item[data-index="'+ index +'"]')
        	return item;
        },
        
        _createPage : function(page,type){
            this._template('page-item-template', this.options.pluginInfo.pageItem, this.pageBox, page);
            this._template('page_item_tpl', this.options.pluginInfo.pageInfoItem, $(this.body).empty() , page);
            if( type=='single' ){
            	var items = this.body.find('.page-info-item');
            	items.hide()
            	var len = items.length,
	            	index = 0;
	            (function loop(){
	                if(index >= len){
	                    return;
	                }
	                items.eq(index).slideDown(300);
	                index++;
	                setTimeout(loop, 1300);
	            })();
            }
        },

        _emptyPage : function(){
            this.pageBox.empty();
        },

        cleanPage : function(){
            this._emptyPage();
            $(this.editor.document).find('.pagebg-first').remove();
            $(this.editor.document).find('.pagebg').remove();
            this.body.empty();
        },
        showWidget : function(){
        	if( !this.element.hasClass('pop-show') ){
        		this.show();
        	}
        },
        
        refresh : function( type ){
            this._emptyPage();
            var _this = this,
            	editor = this.editor,
            	editorIframeContainer = $(editor.iframe).parent(),
            	editorDoc = $(editor.document),
            	pages = [],
            	init = false,
            	height, width, outerHeight,
            	containerOffset = editorIframeContainer.offset(),
            	_body = $(this.editorBody);
            function add(index, pp, title){
                pages.push({
                    index : index,
                    title : title,
                    style : 'left:' + (pp.left - containerOffset.left + 8) + 'px;top:' + ((pp.top - (outerHeight - height) / 2) - containerOffset.top) + 'px;height:' + outerHeight + 'px;width:' + width + 'px;'
                });
            }
            _body.css('padding-top', 0);
            _body.find('img.pagebg').each(function(index){
                var $this = $(this);
                if(!init){
                    height = $this.height();
                    width = $this.width() - 16;
                    outerHeight = $this.outerHeight(true);
                    var _body = $(_this.editorBody);
                    _body.css('padding-top', (outerHeight - height) / 2 + 'px');
                    
                    var pagebgFirst = $(_this.editorBody).find('.pagebg-first');
                    if( !pagebgFirst.length ){
                    	pagebgFirst = $('<img class="pagebg-first" style="display:none;">').prependTo( _this.editorBody );
                    }
                    add(1, _this.getPosition(pagebgFirst), pagebgFirst.attr('_title'));
                    init = true;
                }
                add(index + 2, _this.getPosition(this), $(this).attr('_title'));
            });
            this.pages = pages;
            pages.length && this._createPage(pages,type);
            this._scroll();
            $.editorPlugin.get(editor, 'editorCount').editorCount('singleRefresh','pageslide');
        },

        _scroll : function(){
            if(this.bindScroll) return;
            this.bindScroll = true;
            var _this = this;
            $(this.editor.document).off('.m2o-page-scroll').on('scroll.m2o-page-scroll', function(){
                _this.pageBox.css('top', - $(this).scrollTop() + 'px');
            });
        },

        checkPage : function(){
            return this.pages ? this.pages.length : 0;
        },

        scrollToPage : function(index){
            var _this = this;
            var autoHeight = this.editor.options['autoHeightEnabled'];
            var $doc = $(this.editor.document);
            var pagebgFirst = $(_this.editorBody).find('.pagebg-first');
            var bg = index == -1 ? pagebgFirst : $doc.find('.pagebg').eq(index);
            var duration = this.options.animateDuration;
            var pageBox = this.pageBox;
            var page = pageBox.children().eq(index + 1);
//            var needOpacity = index == -1 ? page : page.add(bg);
//            needOpacity.css('opacity', 0);
//            function afterAnimate(){
//                needOpacity.animate({
//                    opacity : 1
//                }, 100);
//            }
            if(autoHeight){
                var dis = $(this.editor.iframe).offset().top - $(this.editor.container).offset().top;
                $(document.body).animate({
                    scrollTop : this.getPosition(bg[0]).top - dis + 'px'
                }, duration, afterAnimate);
            }else{
            	var scrollTopVal = bg.offset().top + $(this.editor.iframe).parent().offset().top - $(this.editor.iframe).parent().prev().height() + 8;
                var bgTop = index == -1 ? 0 : scrollTopVal;
                $('body').animate({
                	scrollTop : bgTop + 'px'
                }, {
                    duration : duration,
//                    complete : afterAnimate
                });

            }
        },

        scrollAnimate : function(){
            var _this = this,
            	len = this.pages.length,
            	duration = this.options.animateDuration + 500,
            	index = 0;
            (function loop(){
                if(index >= len){
                    return;
                }
                _this.scrollToPage(index - 1);
                index++;
                setTimeout(loop, duration);
            })();

        },

        _destroy : function(){

        },
        _savePage : function(){
        	var hidden = $('textarea[name="'+ this.editorOp.editorContentName +'"]'),
        		content = this.editor.getContent();
        	hidden.val( content )
        },
        _pageInfoKeyUp : function( event ){
        	var target = $(event.currentTarget),
             	val = target.val(),
        		index = target.closest('.page-info-item').data('index');
             this._getEditorPageByIndex(index - 2).attr('_title', val);
             this._getPageInputByIndex(index).find('input').val(val);
             this._savePage();	
        },
        _pageInfoFocus : function( event ){
        	var target = $(event.currentTarget),
        		parent = target.closest('.page-info-item'),
        		index = parent.data('index');
        	parent.addClass('current');
        	this.scrollToPage(index-2);
        },
        _pageInfoBlur : function( event ){
        	var target = $(event.currentTarget),
    			parent = target.closest('.page-info-item');
        	parent.removeClass('current');
        	this._savePage();
        },
    });
    $.widget('ueditor.autopage', $.ueditor.base, {
        options : {
            pluginInfo : pluginInfo,
            stepHeight : 500
        },

        _create : function(){
            this._super();

        },

        refresh : function(){
            var _this = this,
            	editor = this.editor,
            	pageWidget = $.editorPlugin.get(editor, 'page'),
            	top = 0,
            	step = this.options.stepHeight;
            editor.focus();
            if(pageWidget.page('checkPage') && !confirm('编辑器中已经有分页，确定要重新分页？')){
                return;
            }
            pageWidget.page('cleanPage');
            pageWidget.hasClass('pop-show') ? $.noop() : pageWidget.page('show');
            $(this.editorBody).children().each(function(){
                var height = $(this).outerHeight(true);
                top += height;
                if(top >= step){
                    top = 0;
                    $(UE.m2oPageBgHtml).insertAfter(this);
                }
            });
            pageWidget.page('refresh','single');
            pageWidget.page('scrollAnimate');
        },

        _destroy : function(){

        }
    });

    $.ueditor.m2oPlugins.add({
        cmd : 'm2o_pagebreak',
        title : '分页',
        click : function(editor){ 
            editor.execCommand('m2o_pagebreak');
            $.editorPlugin.get(editor, 'page').page('showWidget');
        }
    });
    
    $.ueditor.m2oPlugins.add({
        cmd : 'm2o_auto_pagebreak',
        title : '自动分页',
        click : function(editor){
            $.editorPlugin.get(editor, 'autopage').autopage('refresh');
        }
    });

    (function(){
        var init = {};
        (function loop(){
            $.each(UE.instants, function(key, editor){
                if(!init[key]){
                    init[key] = true;
                    editor.ready(function(){
                    	var editorBodyHei = $(editor.body).outerHeight(true);
                    	if( editorBodyHei > editor.options.initialFrameHeight ){
                    		$(editor.iframe).parent().height( editorBodyHei );
                    	}
                    	$.editorPlugin.get(editor, 'page').page('refresh','all');
                    	var newCss = '.pagebg{width:'+ editor.options.initialFrameWidth +'px;}';
                    	UE.utils.cssRule('_pagebg', newCss, editor.document);
                	});
                    editor.callbacks.add(function(){
                        $.editorPlugin.get(editor, 'page').page('refresh','all');
                    });
                }
            });
            setTimeout(loop, 500);
        })();
    })();

})(jQuery);

/*pizhu.js*/
(function($){
    var pluginInfo = {
        templateName : 'plugin-imginfo-template',
        template : '' +
            '<div class="pz-item" data-hash="{{= hash}}">' +
                '<div class="pz-option"><span class="pz-icon pz-oc pz-more">↓</span><span class="pz-icon pz-del">↑</span></div>' +
                '<div class="pz-content">' +
                	'<label>{{= index}}</label>：<span class="pizhu-content" title="{{= pizhuname}}">{{= pizhuname}}</span>' +
                	'<span class="reply-num">({{= num}})</span>' +
                '</div>' +
                '<div class="pz-reply">' +
                    '{{each reply}}' +
                    '<div class="pz-reply-item"><label class="name">{{= bname}}</label>：<span class="reply-content">{{= content}}</span></div>' +
                    '{{/each}}' +
                    '<div class="pz-reply-item"><label class="name">我</label>：<input class="pz-input"/></div>' +
                '</div>' +
            '</div>'+
            '',
	    reply_item_tpl : ''+
	    		'<div class="pz-reply-item"><label class="name">{{= bname}}</label>：<span class="reply-content">{{= content}}</span></div>' +
	    	'',
        css : '' +
        	'.pizhu-list{overflow-y:auto;}'+
            '.pz-item{padding:10px;margin:0 10px;position:relative;border-bottom:1px solid #e7e7e7;}' +
            '.pizhu-content{white-space:nowrap;display:inline-block;max-width:80px;overflow:hidden;text-overflow:ellipsis;vertical-align: middle;}'+
            '.pz-item.open{background: #f0eff5;}'+
            '.pz-item.open .pz-content{color:red;}'+
            '.pz-item .reply-num{color:#a0a0a0;margin-left:5px;}'+
            '.pz-item .pz-option{position:absolute;right:10px;top:10px;}' +
            '.pz-icon{color:transparent;cursor:pointer;display:inline-block;width:20px;height:20px;}'+
            '.pz-more{background:url('+$.ueditor.pluginDir+'/arrow_down.png) no-repeat center;}'+
            '.pz-item.open .pz-more{background-image:url('+$.ueditor.pluginDir+'/arrow_up.png);}'+
            '.pz-item .pz-del{background:url('+$.ueditor.pluginDir+'/del_grey.png) no-repeat center;}' +
            '.pz-item .pz-del:hover{background-image:url('+$.ueditor.pluginDir+'/del_hover.png);}' +
            '.pz-item .pz-reply{display:none;}' +
            '.pz-item.open .pz-reply{display:block;}'+
            '.pz-reply-item{margin:5px;}'+
            '.pz-reply-item .name{color:#a0a0a0;}'+
            '',
        cssInited : false
    };

    var pizhuInfo = function(){
        var dir = $.ueditor.pluginDir + '/slide/';
        var style = 'cursor:pointer;position:absolute;margin-top:-10px;width:10px;';
        return {
            before : '<img class="m2o-pizhu m2o-pizhu-before" src="' + dir + 'before-pizhu' + ($.ueditor.gPixelRatio > 1 ? '-2x' : '') + '.png" style="' + style + 'margin-left:-10px;"/>',
            after : '<img class="m2o-pizhu m2o-pizhu-after" src="' + dir + 'after-pizhu' + ($.ueditor.gPixelRatio > 1 ? '-2x' : '') + '.png" style="' + style + 'margin-right:-10px;"/>'
        }
    }();
    var gAdminInfo = {
        id : gAdmin['admin_id'],
        name : gAdmin['admin_user']
    };
    $.widget('ueditor.pizhu', $.ueditor.baseWidget, {
        options : {
            //selfPluginInfo : pluginInfo,
            //selfTemplateName : 'plugin-imginfo-template',
            initData : null,
            uid : gAdminInfo['id'],
            uname : gAdminInfo['name'],
            pizhuInfo : pizhuInfo
        },
        _create : function(){
            this._super();
            this.initData = this.options.initData || [];
            this._selfTemplate(this.initData);
            this.setTitle('批注设置');
            this.gPizhu = {};		//全局变量，存放pizhu data，键名为pizhu的hash值
            this.gPizhuCount = 0;	//批注的个数，用于批注列表的index
            this.pizhuInit = false;	
        },
        _init : function(){
            this._super();
            this._on({
                'click .pz-del' : '_del',
                'click .pz-more' : '_open',
                'blur .pz-input' : '_reply'
            });
            $.template('list_item_tpl', pluginInfo.template);
            $.template('reply_item_tpl', pluginInfo.reply_item_tpl);
            this.list = $('<div class="pizhu-list"></div>').appendTo(this.body);
            this.list.height( this.element.height() - this.title.height() - 10 );
        },
        /** 新增批注 */
        _createEditorPizhu : function( hash ){
        	var selection = this.editor.selection,
            	range = selection.getRange().select(),
            	content = selection.getText(),
            	cloneRange = range.cloneRange();
            range.setCursor(true);
            this.gPizhu[hash].pizhuname = content;
            var pizhuParam = this.gPizhu[hash];
            range.insertNode($(this.options.pizhuInfo.after).attr( pizhuParam )[0]);
            cloneRange.select().insertNode($(this.options.pizhuInfo.before).attr( pizhuParam )[0]);
            if( this.editorOp.needCount ){
        		$('.editor-statistics-item[_type="pizhu"]').find('span').text( $(this.editorBody).find('.m2o-pizhu-before').length );
        	}
        },
        _createListPizhu : function( hash ){
        	this.gPizhu[hash].num = 0;
        	this.gPizhu[hash].index = this.gPizhuCount;
        	var data = this.gPizhu[hash];
        	$.tmpl('list_item_tpl', data).appendTo(this.list);
        },
        /** 新增回复 */
        _reply : function(event){
        	var self = $(event.currentTarget),
	        	parent = self.closest('.pz-item'),
	        	hash = parent.data('hash'),
	        	reply = $.trim(self.val());
	        if(reply){
	        	var itemInfo = {
	        			name : this.options.uname,
	        			bname : '我',
	        			content : reply,
	        	};
	        	$.tmpl('reply_item_tpl', itemInfo).insertBefore(self.parent());
	        	self.val('');
	        	this._refreshListData( hash, parent );
	        	this._refreshEditorPizhu( parent );
	        	var len = this.gPizhu[hash].reply.length;
	        	this.gPizhu[hash].num = len;
	        	parent.find('.reply-num').text('('+ len +')');
	        	this.editor.focus();
	        }
        },
        _refreshListData : function( hash, parent ){
        	var arr = [],
        		_this = this,
        		replyItems = parent.find('.pz-reply-item').not(':last-child');
        	$( replyItems ).each(function(){
        		var self = $(this),
        			obj = {};
    			obj.bname = self.find('.name').text(),
    			obj.content = self.find('.reply-content').text();
    			arr.push( obj )
        	});
        	this.gPizhu[hash].reply = arr;
        },
        _refreshEditorPizhu : function( parent ){
        	var replyItems = parent.find('.pz-reply-item').not(':last-child'),
	        	hash = parent.data('hash');
	        var dataStr = this._replyDataToStr( replyItems ),
	        	pizhuFlag = this._getEditorBeforePZ( hash );
	        pizhuFlag.attr('_pzdata', dataStr);
        },
        _replyDataToStr : function( domList ){
        	var arr = [];
        	$.each( domList, function(k,v){
        		var v = $(this),
        			name = v.find('.name').text(),
        			content = v.find('.reply-content').text();
//        		arr.push({
//        			name : name,
//        			content : content
//        		});
        		var item = '_bname_:_'+ name + '_,_content_:_'+ content +'_';
        		arr.push(item);
        	});
//        	var str = encodeURIComponent(JSON.stringify(arr));
        	var str = arr.join('|');
        	return str;
        },
        /** 展开回复 */
        _open : function(event){
            var self = $(event.currentTarget),
            	parent = self.closest('.pz-item');
            parent.toggleClass('open').siblings().removeClass('open');
        },
        /** 删除批注 */
        _del : function(event){
            var item = $(event.currentTarget).closest('.pz-item'),
            	hash = item.data('hash');
            this._delEditorPZ(hash);
            this.gPizhuCount--;
            var _this = this;
            item.slideUp(function(){
            	item.remove();
            	_this._refreshItemIndex();
            	_this.editor.focus();		//让编辑器获得焦点，以同步内容，否则删除无效
            	if( _this.editorOp.needCount ){
            		$('.editor-statistics-item[_type="pizhu"]').find('span').text( $(_this.editorBody).find('.m2o-pizhu-before').length );
            	}
            });
        },
        _refreshItemIndex : function(){
            this.body.find('.pz-item').each(function(index, val){
                $(this).find('.pz-content label').html(1 + index);
            });
        },
        _getEditorBeforePZ : function(hash){
            return $(this.editor.document).find('img.m2o-pizhu-before[hash="' + hash + '"]');
        },
        _getEditorPZ : function(hash){
            return $(this.editor.document).find('img.m2o-pizhu[hash="' + hash + '"]');
        },
        _delEditorPZ : function(hash){
            this._getEditorPZ(hash).remove();
        },
        _selfTemplate : function(data){
            this._template(pluginInfo.templateName, pluginInfo, this.body, data);
        },
        /** 遍历已保存的批注 */
        _getHistoryData : function(){
        	var html = $(this.editor.document.body),
        		bPi = html.find('.m2o-pizhu-before'),
        		_this = this;
        	$.each( bPi, function(k, v){
        		var self = $(this),
        			hash = self.attr('hash'),
        			dataStr = self.attr('_pzdata') || '',
        			dataObj = dataStr.length ? _this._strToArr( dataStr ) : [];
        		_this.gPizhu[hash] = {
        				hash : hash,
        				index : k+1,
        				pizhuname : self.attr('pizhuname'),
        				reply : dataObj,
        				num : dataObj.length
        		}
        		_this.gPizhuCount++;
        		var dom = $.tmpl('list_item_tpl', _this.gPizhu[hash]).appendTo( _this.list );
        	});
        },
        refresh : function(){
            this.showAll();
            var hash = this.hash();
            this.gPizhuCount++;
            this.gPizhu[ hash ] = {};
            this.gPizhu[ hash ].hash = hash;
            this._createEditorPizhu( hash );
            this._createListPizhu( hash );
        },
        ok : function(){
            this._super();
        },
        no : function(){
            this._super();
            this.hideAll();
        },
        showAll : function(){
            this.show();
            var flag = this.element.hasClass('pop-show');
            $(this.editorBody).find('img.m2o-pizhu').css('display', flag ? 'inline' : 'none');
            if( !this.pizhuInit ){
            	this._getHistoryData();
            	this.pizhuInit = true;
            }
        },
        _regFunc : function( str, model ){
			for( var k in model ){
				var re = new RegExp( k, 'g' );
				str = str.replace( re, model[k] );
			}
			return str;
		},
        _strToArr : function(str){
        	var arr = str.split('|'),
        		newArr = [];
        	for( var i=0,len = arr.length;i<len;i++ ){
        		var s = '{' + this._regFunc( arr[i], {
        			'_' : '"'
        		}) + '}';
        		newArr[i] = JSON.parse(s);
        	}
//        	var newArr = $.parseJSON(decodeURIComponent(str));
        	return newArr;
        },
        hideAll : function(){
            $(this.editor.document).find('img.m2o-pizhu').hide();
            this.hide();
        },

        focusHide : function(){
            this.hideAll();
        },

        _destroy : function(){

        },
    });
    
    $.ueditor.m2oPlugins.add({
        cmd : 'pizhu',
        title : '批注',
        click : function(editor){
            $.editorPlugin.get(editor, 'pizhu').pizhu('showAll');
        }
    });
    
    (function(){
        var init = {};
        var c = setInterval(function(){
        	$.each(UE.instants, function(key, editor){
            	if(!init[key]){
            		init[key] = true;
                	editor.ready(function(){
                		$(editor.body).find('.m2o-pizhu').hide();
                	});
            	}else{
            		clearInterval(c);
            	}
            });
        },50);
    })();
})(jQuery);

/*refer.js*/
(function($){
	var referInfo = {
		template : '' +
				'<div class="refer-slide">' +
					'<div id="edit-slide-each1" class="edit-slide-each">' +
						'<div class="edit-slide-title">引用素材</div>' +
						'<span class="editor-slide-no"></span>'+
						'<div class="edit-slide-refer-content edit-slide-content" data-sortlevel="0">' +
							'<div class="refer-item refer-my_publisth edit-slide-next refer-with-icon">' +
								'<span>我发布的</span>' +
								'<a class="refer-item-button">&gt;</a>' + 
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
				'',
		
		item_tpl : '' + 
				'<div class="refer-item refer-${bundle} edit-slide-next {{if !level}}refer-with-icon{{/if}}" data-islast="${islast}" data-host="${host}" data-dir="${dir}" data-filename="${filename}" data-fid="${fid}" data-sort_id="${sort_id}" >' + 
					'<span>${name}</span>' +
					'<a class="refer-item-button">&gt;</a>' +
				'</div>' +
				'',
		item_content : '' +
				'<div class="edit-slide-each">' +
					'<div class="edit-slide-title">${tit}</div>' +
					'<span class="slide-back">返回</span>' +
					'<span class="editor-slide-no"></span>'+
					'<div class="edit-slide-refer-content edit-slide-content" data-sortlevel="${sortlevel}">' +
						'{{tmpl($data["columnlist"]) "item_tpl"}}' +
					'</div>' +
				'</div>' +
				'' ,
		item_data : '' +
			'<div class="refer-item refer-material-item">' +
				'<div class="wrap-img" data-host="${host}" data-dir="${dir}" data-filename="${filename}" data-id="${id}">' +
					'<img src="${src}" alt="${alt}" title="${title}" />' +
				'</div>' + 
				'<div class="refer-label">' +
					'${cont}' +
					'<p>${update_time}</p>' +
				'</div>' + 
			'</div>' + 
			'',
		item_nodata : '' +
			'<h3 class="nodata">没有此类素材！</h3>' +
			'',
		css : '' + 
			'.refer-slide{position:relative; width:11000px; top:-44px;}' +
			'.edit-slide-each{width:245px; margin:0 10px; float:left; position:relative;}' +
			'.ump-box .edit-slide-each{width:235px}'+
			'.edit-slide-title{height:43px; line-height:43px;text-align:center;}' +
			'.slide-back{position:absolute;left:0; top:10px; z-index:99; cursor:pointer; width:22px; height:22px;color:transparent;background: url(./res/ueditor/third-party/m2o/images/slide/slide-back.png) no-repeat center; }' +
			'.edit-slide-refer-content{overflow-y:auto;}' +
			'.refer-item{padding:10px; border-bottom:1px solid #e7e7e7; position:relative; cursor:pointer; overflow:hidden;}' +
			'.refer-item-button{width:8px; height:10px; position:absolute;background-repeat:no-repeat;right:10px; top:16px; text-indent:-999px; background-image:url(./res/ueditor/third-party/m2o/images/slide/slide-next.png); overflow:hidden}' +
			'.refer-with-icon span{width:140px; height:22px; display:block; line-height:22px; padding-left:30px;}' +
			'.refer-my_publisth span{background: url(./res/ueditor/third-party/m2o/images/slide/nav-user-h.png) no-repeat left center;}' +
			'.refer-my_publisth:hover span{background-image: url(./res/ueditor/third-party/m2o/images/slide/nav-user.png);}' +
			'.refer-with-icon.refer-tuji span{background: url(./res/ueditor/third-party/m2o/images/slide/tw-tjk-h.png) no-repeat left center;}' +
			'.refer-with-icon.refer-tuji:hover span{background-image: url(./res/ueditor/third-party/m2o/images/slide/tw-tjk.png);}' +
			'.refer-with-icon.refer-vote span{background: url(./res/ueditor/third-party/m2o/images/slide/hd-tp-h.png) no-repeat left center;}' +
			'.refer-with-icon.refer-vote:hover span{background-image: url(./res/ueditor/third-party/m2o/images/slide/hd-tp.png);}' +
			'.refer-with-icon.refer-livmedia span{background: url(./res/ueditor/third-party/m2o/images/slide/mt-zbt-h.png) no-repeat left center;}' +
			'.refer-with-icon.refer-livmedia:hover span{background-image: url(./res/ueditor/third-party/m2o/images/slide/mt-zbt.png);}' +
			'.wrap-img{width:72px; height:54px; line-height:54px; float:left; border:1px solid #E7E7E7; text-align:center; margin-right:10px; cursor:pointer;}' + 
			'.wrap-img img{max-height:54px; max-width:72px; vertical-align:middle; }' +
			'.refer-label{ display: table-cell; height: 54px; vertical-align: middle; max-width: 115px; word-wrap: break-word;}' +
			'.refer-label p{color:#9f9f9f; font-size:0.7em; }' +
			'.nodata{color:red; font-size:16px; padding:10px; text-align:center;}' +
			'.refer-material-search{border-bottom: 1px solid #e7e7e7; line-height: 40px;padding-left: 10px;}' +
			'.refer-event-submit{margin-left:10px; cursor:pointer; }' +
			'.page-control{float:right; margin:15px 20px;}' +
			'.page-control a{margin:10px; cursor:pointer; }' +
			'.ump-inner .edit-slide-title{background:#fff}' +
			'.editor-slide-inner .edit-slide-title{background:#f9f9f9}' +
			'',
		cssInited : false
	};
	$.widget('ueditor.refer', $.ueditor.baseWidget, {
		options : {
			index : true,
			title : '引用素材',
			slide : '.refer-slide',
			eachfirst : '#edit-slide-each1',
			each : '.edit-slide-each',
			content : '.edit-slide-content',
			mypublish : 'refer-my_publisth',
			eventsubmit : 'refer-event-submit',
			icon : 'refer-with-icon',
			next : '.edit-slide-next',
			tit : '.edit-slide-title',
			back : '.slide-back',
			mulpage : '.page-control a',
			wrap : '.refer-material-item',
		},
		_create : function(){
			this.editor = this.options.editor;
			this._super();
            this._template('attach-template',referInfo, this.body);
            this.sortlevel = 0;
			this.content = '';
			this.tit = '';
            this.nextInfo = {
				host: '',
				dir: '',
				filename: '',
				fid: 0,
				sort_id: 0,
				isLast: 0
			};
			this.typeList = {
				VOD: 'vod',
				TUJI: 'tuji',
				VOTE_QUESTION: 'vote_question',
				VOTE: 'vote'
			};
			this.page = {
				total: 0,
				needNext: true,
				nowCount: 0,
				step: 7,
				cache: null,
				hasNext: function() {
					return this.needNext;
				}
			};
			this.searchHtml = '<div class="refer-material-search"><label>搜索：<input /></label><a class="refer-event-submit edit-slide-next">确定</a></div>';
			this.waitingImg = '<img class="waiting-img" src="' + RESOURCE_URL + 'loading2.gif"/>';
		},
		_init : function(){
			var op = this.options,
				handlers = {};
			this.box = this.element.find( op['slide'] );
			handlers['click ' + op['next'] ] = '_showNext';	
			handlers['click ' + op['back'] ] ='_back';
			handlers['click ' + op['mulpage'] ] ='_switchPage';
			handlers['click ' + op['wrap'] ] ='insertRefer';
			this._on(handlers);
			this._initWater();
			this._super();
		},
		
		/*增加内容开始*/
		_showNext : function( event ){
			var op = this.options,
				self = $(event.currentTarget);
			//新增一个全局变量，记录该target所属的第一级菜单名
			if( self.closest('.edit-slide-content').data('sortlevel') == 0 ){
				this.belongTopSortName = self.find('span').text();
			}
			this.addAjaxB( self );
		},
		
		/*提交ajax之前*/
		addAjaxB : function( dataDom ){
			var next = null;
			var op = this.options,
				_this = this;
			this.page.total = this.page.nowCount = 0;
			this.page.cache =null;
			if ( dataDom.hasClass( op['mypublish'] ) ) {
				this.content = '我发布的';
				_this.requestAjax( dataDom );
				this.nextInfo.islast = 0;
				this.nextInfo.fid = 0;
				this.search = false;
			}else if(dataDom.hasClass( op['eventsubmit'] )){
				if( !dataDom.parent().find('input').val().trim() ){
					dataDom.myTip({
						string : '请输入要搜索的内容'
					});
					return;
				}
				this.content = '搜索结果';
				this.nextInfo.isLast = 1;
				this.nextInfo.key = dataDom.parent().find('input').val().trim();
				this.nextInfo.search_type = dataDom.data('search_type');
				_this.requestAjaxForSearch( dataDom );
				this.search = true;
			}else{
				this.nextInfo.isLast = dataDom.data( 'islast' );
				this.nextInfo.host = dataDom.data( 'host' );
				this.nextInfo.dir = dataDom.data( 'dir' );
				this.nextInfo.filename = dataDom.data( 'filename' );
				this.nextInfo.fid = dataDom.data( 'fid' );
				this.nextInfo.sort_id = dataDom.data( 'sort_id' );
				this.content = ( this.content == '我发布的' && this.sortlevel != 1  ? this.content : dataDom.find( 'span' ).text() );
				_this.requestAjax( dataDom );
				this.search = false;
			}
		},
		
		requestAjaxForSearch : function( dataDom ){
			this.sortlevel++;
			var _this = this,
				sortlevel = this.sortlevel,
				text = this.nextInfo.key,
				host = this.nextInfo.host,
				filename = this.nextInfo.filename,
				dir = this.nextInfo.dir;
			var url = this.options.config['materialUrl'],
				param = {
						host: host,
						dir: dir,
						filename: filename,
						key: text
					};
			$.globalAjax(dataDom, function(){
				return $.getJSON( url, param, function(data) {
					data = data || [];
					if ( sortlevel == _this.sortlevel ) { //如果ajax后,当前内容级别切换了,则什么都不做
						data = data.filter(function(d) { return d != null; });
						data.push('mix');
						data.push('isLast');
						_this.addAjaxA( data );
						_this.setreferSlide( dataDom );
					}
				});
			});
		},
		
		requestAjax : function( dataDom ){
			this.sortlevel++;
			var url = this.options.config['materialUrl'] + '&host=' + this.nextInfo.host + 
				'&dir=' + this.nextInfo.dir + '&filename=' + this.nextInfo.filename + '&fid=' + this.nextInfo.fid,
				isLast = false,
				materialType,
				op = this.options,
				_this = this,
				self = dataDom,
				sortlevel = this.sortlevel;
			var goal = self.closest( op['content'] ).data('sortlevel');
			if( this.content == '我发布的' ) {
				if(goal == 0){
					this.nextInfo.isLast = 0;
				}
				if ( this.nextInfo.isLast ) {
					url += '&my_publisth=1';
				} else {
					if (this.sortlevel == '1') {
						url = this.options.config['materialUrl'];
					}
				}
			}
			if ( this.nextInfo.isLast == 0 ) {
				this.isLast = false;
				isLast = false;
			} else {
				this.isLast = true;
				url += '&sort_id=' + this.nextInfo.sort_id + '&offset=' + this.page.nowCount + '&counts=' + (this.page.step + 1); 
				isLast = true;
				materialType = this.nextInfo.filename; 
			}
			if( this.belongTopSortName == '我发布的' ){
				url += '&my_publisth=1'
			}
			$.globalAjax( dataDom, function(){
				return $.getJSON(url, function( data ){
					var data = data || [];
					if(isLast){
						data.push( materialType );
						data.push( 'isLast' );
					}else{
						data.push( 'notLast' );
					}
					if ( sortlevel == _this.sortlevel ) { //如果ajax后,当前内容级别切换了,则什么都不做
						_this.addAjaxA( data );
						_this.setreferSlide( dataDom );
					}
				});
			});
			
//			$.ajax({
//				url: url,
//				type: 'post',
//				processData: false,
//				contentType: false,
//				dataType: 'json',
//				success: function( data ){
//					var data = data || [];
//					if(isLast){
//						data.push( materialType );
//						data.push( 'isLast' );
//					}else{
//						data.push( 'notLast' );
//					}
//					if ( sortlevel == _this.sortlevel ) { //如果ajax后,当前内容级别切换了,则什么都不做
//						_this.addAjaxA( data );
//						_this.setreferSlide( dataDom );
//					}
//				}
//			});	
		},
		/*提交ajax之后*/
		addAjaxA : function( json ){
			var len = json.length,
				sort = json.pop();
			if(len > 2){
				if( sort === 'isLast' ) {
					this.addMaterialList( json );
				} else {
					this.addSortList( json );
				}
			}else{
				this.showEmpty();
			}
		},
		
		addMaterialList : function( json ){
			var data = json,
				_this = this,
				op = this.options,
				sortlevel = this.sortlevel,
				num = 0, 
				total,
				type = data.pop();
			var realdata = [],
				info = {};
			total = data.length;
			if (total < _this.page.step + 1) {
				_this.page.needNext = false;
				_this.page.cache = null;
			} else {
				_this.page.needNext = true;
				_this.page.cache = type;
			}
			_this.page.nowCount += total;
			if( $.isArray( data ) ){
        		$.each( data, function( key, value ){
        			_this.preloadImg(value, realdata);
        			if ( ++num >= total ) {
						showmulPage = _this.showPage();
					}
        		} );
        	}else{
        		_this.preloadImg(value, realdata);
        	}
        	info.columnlist = realdata;
			info.sortlevel = this.sortlevel;
			info.tit = this.content;
			$.template('item_tpl',referInfo.item_data);
			$.template('item_content',referInfo.item_content);
        	var dom = $.tmpl('item_content', info).appendTo( op['slide'] );
        	$( '.edit-slide-content:last' ).after( showmulPage );
        	$(showmulPage).find('a').each(function(){
	    	   if($(this).text() == '上一页'){
	    			$( '.edit-slide-each:last' ).find( op['back'] ).hide();
	    		}
        	});
        	$(window).trigger('resize.slide');

        	var height = this.element.height() - dom.find('.edit-slide-title').height();
        	if( dom.find('.page-control').length ){
        		height -= dom.find('.page-control').outerHeight(true);
        	}
        	dom.find('.edit-slide-refer-content').css({
        		'max-height' : height + 'px'
        	});
		},
		
		showPage : function(){
			var html = '<div class="page-control">';
			if( this.page.nowCount > (this.page.step+1) ) { //需要上一页
				html += '<a class="prev">上一页</a>';	
			}
			if (this.page.hasNext() == true) {
				html += '<a>下一页</a>';
			} 
			if( html != '<div class="page-control">' ) {
				html += '</div>';
			}
			return html;
		},
		
		preloadImg : function(value, arr){
			var info = {},
				title = '';
			src =  $.globalImgUrl(value.img);
			img = value.img;
			bundle = value.app_bundle;
			info.id = value.id;
			info.host = value.host;
			info.dir = value.dir;
			info.filename = value.filename;
			info.update_time = value.update_time;
			info.cont = value.title;
			title = this.Settitle( bundle);
			if(!img.filename){
				info.alt = '无索引图';
				info.src = './res/ueditor/third-party/m2o/images/big_default.png';
				info.title = title;
			}else{
				info.alt = "一张素材示意图";
				info.src = src;
				info.title = title;
			}
			arr.push(info);
		},
		
		Settitle : function( str ){
			switch(str){
				case this.typeList.VOD:{
					return '点击插入此视频';
				}
				case this.typeList.TUJI:{
					return '点击插入此图集';
				}
				case this.typeList.VOTE_QUESTION:{
					return '点击插入此投票';
				}
				case this.typeList.VOTE:{
					return '点击插入此问卷';
				}
			}
		},
		
		addSortList : function( json ){
			var _this = this;
			var op = this.options;
			var realdata = [],
				info = {};
			if( $.isArray( json )){
				$.each(json,function(key, value){
					_this._handleData(value, realdata);
				});
			}else{
				_this._handleData(value, realdata);
			}
			info.columnlist = realdata;
			info.sortlevel = this.sortlevel;
			info.tit = this.content;
			$.template('item_tpl',referInfo.item_tpl);
			$.template('item_content',referInfo.item_content);
        	var dom = $.tmpl('item_content', info).appendTo( op['slide'] );
        	var height = this.element.height() - dom.find('.edit-slide-title').height()-10;
        	dom.find('.edit-slide-refer-content').height( height );
//        	if(this.content == '我发布的'){				//因为点击搜索之后，this.content会改变，这里的判断就无法执行了
        	if(this.belongTopSortName == '我发布的'){		
        		if(this.sortlevel == '2'){
        		 	$( '.edit-slide-content:last' ).prepend( _this.searchHtml );
        		}
        	 }else {
        	 	if(this.sortlevel == '1'){
        	 		 $( '.edit-slide-content:last' ).prepend( _this.searchHtml );
        	 	}
        	 }
        	$(window).trigger('resize.slide');
		},
		
		setreferSlide : function( dataDom ){
			var op = this.options,
				self = dataDom,
				item = self.closest( op['each'] );
			this.box.animate({
				left: '-=' + (item.width()+20) + 'px'
			}, 200);
		},
		/*增加内容结束*/
		
		_switchPage : function( event ){
			var self = $(event.currentTarget);
			var nORp = self.text();
    		if( nORp == '上一页' ) {
    			this.showprevtPage( event );
    		} else {
    			this.showNextPage( self );
    		}
		},
		
		showprevtPage : function( event ){
			var op = this.options,
				self = $(event.currentTarget);
			var total= self.closest( op['each'] ).find( '.refer-item' ).length;
			this.page.nowCount -= total;
			this._back(event);
		},
		
		showNextPage : function( self ){
			var op = this.option;
			this.requestAjax( self );
			self.closest( op['each'] ).find( op['back'] ).hide();
		},
		
		_back : function( event ){
			var op = this.options,
				self = $(event.currentTarget);
			this.sortlevel--;
			var item = self.closest( op['each'] );
			this.box.animate({
				left : '+=' + (item.width()+20) + 'px'
			},200,function(){
				item.remove();
			});
		},
		
		_click : function( event ){
        	var self = $(event.currentTarget),
        		id = self.attr('_id');
        	var data = this.imgCollection[id];
        	this.insertImg('img', data);
        },
		
		insertRefer : function( event ){
			var op = this.options,
				_this = this,
				item = $(event.currentTarget),
				self = item.find('.wrap-img');
			var url = this.options.config['referUrl'],
				param = {
					host : self.data('host'),
					dir : self.data('dir'),
					filename : self.data('filename'),
					id : self.data('id')
			};
			$.globalAjax(self, function(){
				return $.getJSON( url, param, function(data) {
					if ( $.type(data) != 'array' || !data[0] ) {
						self.myTip({
							string : '插入失败'
						});
						return;
					}
					var src = data[0];
					var imgHtml = '<p style="text-align:center;"><img class="image-refer" src= "' + src + '" /></p>'; 
					_this.editor.execCommand('insertHtml', imgHtml);
				});
			});
		},
		
		showEmpty: function() {
			var info = {},
				op = this.options;
			info.tit = this.content;
			$.template('item_tpl',referInfo.item_nodata);
			$.template('item_content',referInfo.item_content);
        	$.tmpl('item_content', info).appendTo( op['slide'] );
			$(window).trigger('resize.slide');
		},
		
		/*初始化最初分类开始*/
		_initWater : function(){
        	var _this = this,
        		url = this.options.config['materialUrl'];
        	setTimeout(function(){
        		$.globalAjax(_this.element, function(){
        			return $.getJSON( url , function( data ){
                		if( data.length ){
                			_this._instance( data );
                		}
                	});
        		});
        	},300);
        },
        _instance : function( data ){
        	var _this = this,
        		op = this.options,
        		prebox = $( op['eachfirst'] ).find( op['content'] ),
    			realdata = [];
        	if( $.isArray( data ) ){
        		$.each( data, function( key , value ){
        			_this._handleData(value, realdata);
        		} );
        	}else{
        		_this._handleData(data, realdata);
        	}
        	$.template('item_tpl',referInfo.item_tpl);
        	$.tmpl('item_tpl', realdata).appendTo(prebox);
        },
         _handleData : function( data , arr ){
        	var info = {};
        	info.bundle = data.bundle ? data.bundle : data.filename;
			info.name = data.name;
			info.level = this.sortlevel;
			info.islast = data.is_last;
			info.host = data.host;
			info.filename = data.filename;
			info.fid = data.fid;
			info.dir = data.dir;
			info.sort_id = data.sort_id;
			arr.push( info );
        },
        /*初始化最初分类结束*/
	});
	
	$.ueditor.m2oPlugins.add({
        cmd : 'refer',
        title : '引用素材',
        click : function(editor){
            $.editorPlugin.get(editor, 'refer').refer('show');
        }
    });
})(jQuery);


/*referinfo.js*/
;(function($){
	var referInfo = {
		template : ''+
			'<ul>'+
				'<li class="refer-info-item refer-info-prev">'+
					'<div class="refer-info-img-box {{= cname}}">'+
						'<div class="middle-wrap refer-info-img-box">'+
							'<img src="{{= imgUrl}}"/>{{if cname}}<p class="prev-btn">点击预览</p>{{/if}}'+
						'</div>'+
					'</div>'+
				'</li>'+
				'<li class="refer-info-item-last">'+
					'<a class="button_6 open-refer-event">{{= buttonLabel}}</a>'+
				'</li>'+
			'</ul>'+
			'',
		css : ''+
			'.refer-info-item{border-bottom:1px solid #E7E7E7;padding:10px 7px;margin:0 10px;}'+
			'.refer-info-item .name{color:#9f9f9f;display:inline-block;width:60px;text-align:right;margin-right:5px;}'+
			'.refer-info-prev{text-align:center;}'+
			'.refer-info-item-last{text-align: center;padding:10px 0;}'+
			'.can-preview{cursor:pointer;}'+
			'',
		cssInited : false
	};
	$.widget('ueditor.referinfo', $.ueditor.baseWidget, {
		options : {
			title : '引用素材属性'
		},
		_create : function(){
			this._super();
		},
		_init : function(){
			this._super();
			this._on({
				'click .can-preview' : '_showSwf',
				'click .open-refer-event' : '_referChange'
			});
		},
		_referChange : function(){
			$.editorPlugin.get(this.editor, 'refer').refer('show');
		},
		_showSwf : function(){
			var swf = this._createSwf();
			$("<div>" + swf + "</div>").dialog({
				modal: true,
				dialogClass: 'swf-dialog',
				resizable: false,
				width: 430,
				height: 400,
				close: function () {
					$(this).dialog('destroy');
				}
			});
		},
		_createSwf : function(){
			var flashvars = "startTime=0&duration=227467&videoUrl=http://vfile1.dev.hogesoft.com/500x48/2013/02/1359683684581621.ssm/manifest.f4m&videoId=1821&snap=false&aspect=4:3&autoPlay=true&snapUrl=http://vapi1.dev.hogesoft.com:233/snap.php";
			
			flashvars = $.map(this.info, function (v, k) { return k + '=' + v; }).join('&') + '&autoPlay=true';
			return '<object type="application/x-shockwave-flash" data="' + RESOURCE_URL + 'swf/vodPlayer.swf?11122713" width="400" height="330">' +
			'<param name="movie" value="' + RESOURCE_URL + 'swf/vodPlayer.swf?11122713">' +
			'<param name="allowscriptaccess" value="always">' +
			'<param name="allowFullScreen" value="true">' +
			'<param name="wmode" value="transparent">' +
			'<param name="flashvars" value="' + flashvars + '">'+
	  		'</object>';
		},
		_getReferInfo : function(){
			var url = this.options.config['materialInfoUrl'],
				src = $(this.img).attr('src'),
				_this = this;
			$.getJSON( url, {'url':src}, function(data){
				if( !data ){
					data = [{
						type: 'error'
					}];
				}
				_this.info = data[0].flashvars;
				var type = data[0].type;
				var title = _this._getTitle( type );
				_this.title.text( title );
				_this._getContent(type, data[0]);
			});
		},
		_getTitle : function( type ){
			var titleLabel = '';
			switch(type) {
				case 'vod':
					titleLabel = '引用视频属性';
					break;
				case 'tuji':
					titleLabel = '引用图集属性';
					break;
				case 'vote':
					titleLabel = '引用投票属性';
					break;
				default:
					titleLabel = '出错了';
					break;
			}
			return titleLabel;
		},
		_getContent : function( type, json ){
			var data = {};
			switch(type) {
				case 'vod':
					data = {
						buttonLabel : '替换这个视频',
						cname : 'can-preview',
						attrs : {
							title: '标　题',
							time: '时　间',
							keywords: '关键字',
							sort_name: '分　类',
							size: '大　小',
							duration: '时　长'
						}
					};
					break;
				case 'tuji':
					data = {
						buttonLabel : '替换这个图集',
						attrs : {
							title: '标　题',
							time: '时　间',
							keywords: '关键字',
							sort_name: '分　类'
						}
					};
					break;
				case 'vote':
					data = {
						buttonLabel : '替换这个投票',
						attrs : {
							title: '标　题',
							create_time: '创建时间',
							start_time: '开始时间',
							end_time: '结束时间'
						}
					};
					break;
				default:
//					return '<p style="font-size:14px;text-align:center;color:red;">无法获取素材的信息！</p>';
			}
			data.imgUrl = json.img ? $.globalImgUrl(json.img,'160x120') : '';
			this._template( 'refer_info_tpl', referInfo, this.body.empty(), data );
			var content= '';
			for (var k in data.attrs) {
				content += '<li class="refer-info-item"><span class="name">' + data.attrs[k] + '：</span><span class="info">' + json[k] + '</span></li>';
			}
			$(content).insertBefore( this.element.find('.refer-info-item-last') );
		},
		
		_empty : function(){
            this.body.empty();
        },
        
		refresh : function( img ){
			this._empty();
			this.img = img;
			this._getReferInfo();
			if( !this.element.hasClass('pop-show') ){
            	this.show();
            }
		},
	});
})(jQuery);

/*removetag.js*/
(function($){
	$.widget('ueditor.removetag', $.ueditor.baseWidget, {
        options : {
        	title : '清除格式'
        },
        _create : function(){
            this._super();
        },
        _init : function(){
            this._super();
        },
        
        refresh : function( editor ){
        	var _this = this;
        	this._tooltip( 'edui-for-removetag','正在执行格式化请稍候...' );
        	setTimeout( function(){
        		_this._removeFormat( editor );
        		_this._centerImg( editor );
        	},50 );
        },
        
        /*清楚p标签嵌套*/
        _clearPnested : function(body){
        	var root_p = body.find('p').filter( function(){
        		var _parent_tagname = $(this).parent()[0].tagName.toLowerCase();
        		return _parent_tagname == 'body';
        	} );
        	if( root_p.find('p').length ){
        		var childs = root_p.children();
        		childs.unwrap( root_p );
        	}
        },
        
        _removeFormat : function( editor ){
            var body = $(editor.document.body);
            this._clearPnested( body );
            body.find("img.before-biaozhu-ok, img.after-biaozhu-ok").remove();
            body.find("img").each(function(){
            	$(this).removeAttr('style');
                var clone = $(this).clone();
                var div = $("<div></div>");
                var imgHtml = div.html(clone).html();
                $(this).replaceWith("{{{"+  encodeURIComponent(imgHtml) +"}}}");
                div.remove();
            });
            body.find("br").each(function(){
                $(this).replaceWith("{{{br}}}");
            });
            body.find("span[style]").filter(function(){
                return $(this).css("font-weight") == "bold";
            }).add(body.find("b, strong")).each(function(){
                $(this).replaceWith("{{{strong}}}" + $.trim($(this).text()) + "{{{/strong}}}");
            });
            body.find("p").each(function(){
                $(this).replaceWith("{{{p}}}" + $.trim($(this).text()) + "{{{/p}}}");
            });
            var string = body.text();
            string = string.replace(/({{{p}}}){1,}/g, "<p>");
            string = string.replace(/({{{\/p}}}){1,}/g, "</p>");
            string = string.replace(/({{{br}}}){1,}/g, "<br/>");
            string = string.replace(/({{{strong}}}){1,}/g, "<strong>");
            string = string.replace(/({{{\/strong}}}){1,}/g, "</strong>");
            string = string.replace(/{{{([^}]*)}}}/g, function(all, match){
                return decodeURIComponent(match);
            });
            body.html(string);
            body.find("img.pagebg").unwrap();
            body.contents().filter(function(){
                return this.nodeType == 3;
            }).wrap("<p></p>");
            body.find('p').filter(function(){
                var text = $.trim($(this).text()),
                    img = $(this).has('img');
                return  text== "" && !img.length;
            }).remove();

            body.find("br").filter(function(){
                var self = $(this),
                    parent = self.parent(),
                    prev = self.prev(),
                    next = self.next(),
                    parent_prev = parent.prev(),
                    parent_next = parent.next();
                return ( ( prev.is("p") && next.is("p") ) || ( parent_prev.is("p") && parent_next.is("p") ) );
            }).remove();
            
            editor.sync();
            
        },
        
        _centerImg : function( editor ){
			var _this = this,
				editor_document = $(editor.document);
			var	imgs = editor_document.find('img');
			var needformat_imgs = imgs.filter( function(){
				var is_need = true,
					src = $(this).attr('src'),
					parent = $(this).parent(),
					text = $.trim( parent.text() ),
					is_body = parent.is('body'),
					is_p = parent.is('p');
				if( !src ){
					$(this).remove();
					return;
				}
				if( !is_body && is_p && !text ){
					is_need = false;
					parent.css('text-align','center');
				}
				return is_need;
			} );
			needformat_imgs.each( function(){
				$(this).wrap('<p style="text-align:center"></p>');
			} );
			this._tooltipend('格式化完成');
			
			this._fixformat( editor);
			this._clear(editor_document);
			editor.sync();
        },
        
        _clear : function( editor_document ){
        	var _this = this;
        	editor_document.find('p').each( function(){
        		var self = $(this),
        			strong = self.find('strong'),
        			b = self.find('b');
        		$.each( [self,strong,b], function( key, value ){
        			_this._clearByelement( value );
        		} );
        	} );
        },
        
        _clearByelement : function( $el ){
        	var text = $.trim( $el.text() ),
        		img = $el.find('img');
        	if( !img.length && !text ){
    			$el.remove();
    		}
        },
        
        
        _fixformat : function(editor){
        	var range = this.range();
			range.selectNode($(editor.document).find('body')[0]);
			range.select();
			editor.execCommand( 'source');		//调用切换源码模式来修复标签嵌套与闭合问题
			editor.execCommand( 'source');
        }
        
  });

   $.ueditor.m2oPlugins.add({
        cmd : 'removetag',
        title : '清除格式',
        click : function(editor){
            $.editorPlugin.get(editor, 'removetag').removetag('refresh',editor);
        }
    });
})(jQuery);

/*tip.js*/
(function($){
    var pluginInfo = {
        templateName : 'plugin-tip-template',
        template : '' +
        	'<div class="ueditor-tip-content">' + 
	            '<ul>' +
	            	'{{each btns}}'+
	                '<li class="ut-{{= $index}}">{{= $value}}</li>' +
	            	'{{/each}}'+
//	                '<li class="ut-title">设标题</li>' +
//	                '<li class="ut-keyword">设关键字</li>' +
//	                '<li class="ut-desc">设描述</li>' +
//	                '<li class="ut-link">设链接</li>' +
//	                '<li class="ut-pizhu">设批注</li>' +
	            '</ul>' +
	        '</div>' + 
            '',
        css : '' +
            '.ueditor-tip-box{opacity:1;padding-top:5px;position:absolute;left:0;top:0;z-index:100000;background:url('+$.ueditor.pluginDir+'/tip/tip_top_bg.png) no-repeat;margin-top:15px;width:65px;}' +
            '.ueditor-tip-box{}' +
            '.ueditor-tip-content{background:url('+$.ueditor.pluginDir+'/tip/tip_bottom_bg.png) bottom no-repeat;padding-bottom:5px;}' +
            '.ueditor-tip-content ul{background:url('+$.ueditor.pluginDir+'/tip/tip_middle_bg.png) repeat-y;}' +
            '.ueditor-tip-box.hide{opacity:0;-webkit-transition:all .5s;-moz-transition:all .5s;transtion:all .5s;}' +
            '.ueditor-tip-box li{height:22px;padding:2px 5px;cursor:pointer;text-align:center;line-height:22px;color:#fff;background:url('+$.ueditor.pluginDir+'/tip/tip_fen_bg.png) no-repeat center bottom;}' +
            '.ueditor-tip-box li:hover{color: #8bc8ff;}'+
            '.ueditor-tip-box li:last-child{background:none;}'+
            '',
        cssInited : false,
        space : '<span class="m2o-tip-space" style="position:absolute;margin:0;padding:0;width:1px;height:1px;visibility:hidden;"></span>'
    };

    $.widget('ueditor.tip', $.ueditor.base, {
        options : {
            className : 'ueditor-tip-box'
            //pluginInfo : pluginInfo,
            //templateName : 'plugin-tip-template'
        },
        _create : function(){
            this._super();
            var btnRelation = {			//将按钮改为配置项，值为数组，eg:{tipBtns : ['title','link']},不传默认全部
            		'title' : '设标题',
            		'keyword' : '设关键字',
            		'desc' : '设描述',
            		'link' : '设链接',
            		'pizhu' : '设批注'
            	};
            var btnArr = this.editorOp.tipBtns || ['title', 'keyword', 'desc', 'link', 'pizhu'],
            	btnObj = {};
            for( var i=0,len=btnArr.length; i<len; i++ ){
            	btnObj[ btnArr[i] ] = btnRelation[ btnArr[i] ];
            }
            this._template(pluginInfo.templateName, pluginInfo, null, { btns : btnObj });
            this.element.hide();
        },
        _init : function(){
            this._super();
            var _this = this;
            this._on({
                mouseenter : function(){
                    _this._transition(true);
                },
                mouseleave : function(){
                    _this._transition();
                }
            });

            this._on({
                'click .ut-title' : '_title',
                'click .ut-keyword' : '_keyword',
                'click .ut-desc' : '_desc',
                'click .ut-link' : '_link',
                'click .ut-pizhu' : '_pizhu'
            });
        },

        refresh : function(type, causeByUi, uiReady){
            if(!causeByUi) return;
            var _this = this;
            $.each(['link', 'pizhu'], function(key, val){
                $.editorPlugin.check(_this.editor, val) && $.editorPlugin.get(_this.editor, val)[val]('focusHide');
            });
            var selection = this.editor.selection;
            this.selectionText = selection.getText();
            if( !this.selectionText ){
                $.editorPlugin.get(this.editor, 'imgtip').imgtip('hide');	//选区为空时,imgtip也不显示
                $.editorPlugin.get(this.editor, 'link').link('hide');
            	var isImg = this._checkImg(selection),
            		isLink = this._checkLink(selection);
            	if( isImg ){
            		return;
            	}
            	this.hide(true);
                this.hideAll();
            }else{
            	var range = selection.getRange();
                var space = $(pluginInfo.space);
                range.insertNode(space[0]);
                try{
                    range.select(true);
                }catch(e){}
                this.position(this.getPosition(space));
                space.remove();
                this.show();
            }
        },

        show : function(){
            this.element.show().removeClass('hide');
            this._transition();
        },

        hide : function(fast){
            var root = this.element.addClass('hide');
            setTimeout(function(){
                root.hasClass('hide') && root.hide();
            }, fast ? 0 : 500);
        },

        _transition : function(clear){
            clearTimeout(this.delayTimer);
            if(clear){
                return;
            }
            var _this = this;
            this.delayTimer = setTimeout(function(){
                _this.hide();
            }, 2000);
        },

        _title : function(){
            this.editor.fireEvent('_title', this.selectionText, this);
            this.hide(true);
        },

        _keyword : function(){
            this.editor.fireEvent('_keyword', this.selectionText, this);
            this.hide(true);
        },

        _desc : function(){
            this.editor.fireEvent('_desc', this.selectionText, this);
            this.hide(true);
        },

        _checkLink : function(selection){
            var link = this.editor.queryCommandValue('link');
            if(link){
                selection.getRange().selectNode(link);
                this._position = this.getPosition(link);
                this.element.find('.ut-link').trigger('click');
                return true;
            }
            return false;
        },

        _link : function(event){
            var link = this.editor.queryCommandValue('link'),
            	url = link ? (link.getAttribute('_href') || link.getAttribute('href', 2)) : '',
            	range = this.editor.selection.getRange();
            this.hide(true);
            this.rangeSelect( range );
            this.linkTip = this.linkTip || $.editorPlugin.get(this.editor, 'link');
            this.linkTip.link('setCurrentRange', range);
            this.linkTip.link('show', {
            	url : url,
            	pp : this._position,
            });
            event.stopPropagation();
        },

        _checkImg : function(selection){
            var img = selection.getRange().getClosedNode();
            var imgtipPlugin = this.editor.m2oPlugins['imgtip'];
            if(img && img.tagName == 'IMG'){
                if($(img).is('.pagebg')){
                    return;
                }
                $.editorPlugin.get(this.editor, 'imgtip').imgtip('refresh', selection, img);
                return true;
            }
            imgtipPlugin && imgtipPlugin.imgtip('hide');
            return;
        },

        _pizhu : function(){
//            if(!this._checkPlugin('pizhu')) return;
            $.editorPlugin.get(this.editor, 'pizhu').pizhu('refresh');
            this.hide(true);
        },

        _destroy : function(){

        }
    });


    (function(){

        var pluginInfo = {
            templateName : 'plugin-tip-link-template',
            template : '' +
                '<p>链接地址：</p>' +
                '<input type="text" placeholder="http://"/>' +
                '<span class="ueditor-tip-sc">去除链接</span>' +
                '<span class="ueditor-tip-close">X</span>' +
                '',
            css : '' +
                '.ueditor-tip-link{display:none;z-index:10000;position:absolute;left:0;top:0;margin-top:25px;width:300px;padding:3px 10px;height:60px;background:#eee;border:1px solid #ccc;box-shadow:0 0 1px 1px #ccc;}' +
                '.ueditor-tip-link.pop-show{display:block;}'+
                '.ueditor-tip-link input{width:235px;padding-right: 60px;}' +
                '.ueditor-tip-link .ueditor-tip-sc{position:absolute;z-index:100;right:15px;top:27px;color:blue;cursor:pointer;}' +
                '.ueditor-tip-link .ueditor-tip-close{width:8px;height:8px;position:absolute;right:13px;top:2px;color:#999;text-align:center;cursor:pointer;}' +
                '',
            cssInited : false
        };

        $.widget('ueditor.link', $.ueditor.base, {
            options : {
                className : 'ueditor-tip-link'
                //pluginInfo : pluginInfo,
                //templateName : 'plugin-tip-link-template'
            },

            _create : function(){
                this._super();
                this._template(pluginInfo.templateName, pluginInfo);
                this.input = this.element.find('input');
            },

            _init : function(){
                this._super();
                var _this = this;
                this._on(document, {
                    click : function(event){
                        _this._show && _this._check(event);
                    }
                });
                $(this.editor.document).on('mousedown', function(){
                    _this._show && _this.setLink();
                });
                this._on({
                	'focus input' : '_focusInput'
                });

                this._on({
                    'click .ueditor-tip-sc' : 'delLink',
                    'click .ueditor-tip-close' : 'back'
                })
            },
            setCurrentRange : function( range ){
            	this.currentRange = range;
            },
            _getCurrentRange : function(){
            	return this.currentRange;
            },
            _focusInput : function( event ){
            	var self = $(event.currentTarget);
            	var _this = this;
            	var range = this._getCurrentRange();
            },
            _check : function(event){
                var target = $(event.target);
                if(!target.closest('.' + this.options.className).length && target[0] != this.element[0]){
                    this.setLink();
                }
            },

            setLink : function(){
            	if( !this.input.val() ){
            		return;
            	}
                this.exec('link', {
                    href : this.input.val()
                });
                this.hide();
            },

            delLink : function(){
                this.exec('unlink');
                this.hide();
            },

            url : function(url){
                this.input.val(url);
            },

            back : function(){
                this.hide();
//                this.editor.m2oPlugins['tip'].tip('show');
            },

            show : function(info){
            	this._super();
            	this.element.css('display','block');
            	var url = $.trim( info.url );
                this.url( url );
                this.position(info.pp);
                this.element.find('.ueditor-tip-sc')[ url.length ? 'show' : 'hide' ]();
                this._show = true;
                this.element.find('input').focus();
            },

            hide : function(){
                this._super();
                this.element.css('display','none');
                this._show = false;
            },

            focusHide : function(){
                this._show && this.setLink();
            },

            _destroy : function(){

            }
        });

    })();

    (function(){

        var pluginInfo = {
            templateName : 'plugin-imgtip-template',
            template : '' +
                '<ul>' +
                    '<li class="uit-left">居左</li>' +
                    '<li class="uit-center">居中</li>' +
                    '<li class="uit-right">居右</li>' +
                    '<li class="uit-rotatel">向左转</li>' +
                    '<li class="uit-rotater">向右转</li>' +
                    '<li class="uit-local">本地化</li>' +
                    '<li class="uit-change">修改</li>' +
                '</ul>' +
                '',
            css : '' +
            	'.uit-change{display:none;}'+
                '.ueditor-imgtip-box{opacity:0.7;position:absolute;left:10px; top:10px; z-index:100000; background:#000; padding:3px 6px;}' +
                '.ueditor-imgtip-box{}' +
                '.ueditor-imgtip-box li{cursor:pointer;float:left;margin-right:5px;height:22px;line-height:22px; color:#fff; font-weight:bold; }' +
                '',
            cssInited : false
        };

        $.widget('ueditor.imgtip', $.ueditor.base, {
            options : {
                className : 'ueditor-imgtip-box'
                //pluginInfo : pluginInfo,
                //templateName : 'plugin-imgtip-template'
            },

            _create : function(){
                this._super();
                this._template(pluginInfo.templateName, pluginInfo);
                var widget = this.element;
                this.leftBtn = widget.find('.uit-left');
                this.centerBtn = widget.find('.uit-center');
                this.rightBtn = widget.find('.uit-right');
                this.rotateLeftBtn = widget.find('.uit-rotatel');
                this.rotateRightBtn = widget.find('.uit-rotater');
                this.localBtn = widget.find('.uit-local');
                this.changeBtn = widget.find('.uit-change');
            },

            _init : function(){
                this._super();
                this._on({
                    'click .uit-left' : '_left',
                    'click .uit-center' : '_center',
                    'click .uit-right' : '_right',
                    'click .uit-rotatel' : '_rotatel',
                    'click .uit-rotater' : '_rotater',
                    'click .uit-local' : '_local',
                    'click .uit-change' : '_change'
                });
            },

            pp : function(type){
                this._pp(type);
            },

            _pp : function(type){
            	var range = this.range();
				range.selectNode(this.img);
				range.select();
				this.exec('imagefloat', type);

            	// if( type == 'center' ){
            		// $(this.img).css('float','none');
            		// $(this.img).parent().css('text-align','center');
            	// }else{
            		// $(this.img).css('float', type );
            	// }
                // if(type == 'center'){
                    // this.img = this.range().getClosedNode();
                // }
                this.refreshPosition();
            },

            _left : function(){
                this._pp('left');
            },

            _center : function(){
                this._pp('center');
            },

            _right : function(){
                this._pp('right');
            },

            _rotate : function(event, type){
            	var self = $(event.currentTarget);
            	var url = this.options.config['revolveImgUrl'],
            		direction = type =='left' ?  1 : 2,
            		imgid = $(this.img).attr('imageid'),
            		_this = this;
            	var param = {
            			material_id : imgid,
	        			direction : direction
            	};
            	var load = $.globalLoad(self);
            	$.getJSON( url, param, function(json){
            		load();
            		var data = json[0];
            		var src = $.globalImgUrl(data, '640x', true);
            		$(_this.editorBody).find('.image[imageid="'+ imgid +'"]').attr({
            			'src' : src,
            			'_src' : src
            		});
            		$.editorPlugin.get(_this.editor, 'imgmanage').imgmanage('refreshPicSrc', imgid);
        			$.editorPlugin.get(_this.editor, 'imginfo').imginfo('refresh', _this.img);
        			_this.editor.fireEvent('_refreshIndexPic');
            	});
            },

            _rotatel : function( event ){
            	this._rotate(event, 'left');
            },

            _rotater : function( event ){
            	this._rotate(event, 'right');
            },

            _local : function( event ){
            	var self = $(event.currentTarget),
            		_this = this;
            	$.editorPlugin.get(this.editor, 'imglocal').imglocal('localSinglePic',{
            		target : self,
            		img : _this.img,
            		callback : function(){
            			_this.localBtn.hide();
            			_this.rotateLeftBtn.show();
            			_this.rotateRightBtn.show();
            			_this.refreshPosition();
            		}
            	});
            },
            _change : function(){
            	if( $(this.img).hasClass('image-refer') ){		//引用素材
            		$.editorPlugin.get(this.editor, 'referinfo').referinfo('refresh', this.img);
            	}else if( $(this.img).hasClass('extranet-prev-pic') ){	//外部视频
            		$.editorPlugin.get(this.editor, 'extranetinfo').extranetinfo('refresh', this.img);
            	}else{
            		$.editorPlugin.get(this.editor, 'imginfo').imginfo('refresh', this.img);
            	}
            },

            refreshPosition : function(){
                var pp = this.getPosition(this.img);
                var width = $(this.img).outerWidth();
                var widgetWidth = this.element.outerWidth();
                pp.left += (width - widgetWidth);
                this.position(pp);
            },

            _checkLocalImg : function(){
                return !!$(this.img).attr('imageid');
            },

            refresh : function(selection, img){
                this.img = img;
                if( $(this.img).hasClass('m2o-pizhu') ){
                	this.element.hide();
                	return;
                }
                var extraClass = ['image-refer','extranet-prev-pic'],
                	hasExtraClass = false;
                for( var i=0,len=extraClass.length; i<len; i++){
                	if( $(this.img).hasClass( extraClass[i] ) ){
                		hasExtraClass = true;
                	}
                }
                this.element[ hasExtraClass ? 'hide' : 'show' ]();	//素材属性不出现弹窗
                if( this._checkLocalImg() ){
                	this.localBtn.hide();
                	this.rotateLeftBtn.show();
                    this.rotateRightBtn.show();
                }else{
                	this.localBtn.show();
                	this.rotateLeftBtn.hide();
                    this.rotateRightBtn.hide();
                }
                this.changeBtn.click();
                this.refreshPosition();
            },
            show : function(){
            	this.element.show();
            },
            hide : function(){
//                this._super();
            	this.element.hide();
                this.img = null;
            },


            _destroy : function(){

            }
        });

    })();

    (function(){
        var init = {};
        (function loop(){
            $.each(UE.instants, function(key, editor){
                if(!init[key]){
                    init[key] = true;
                    editor.callbacks.add(function(type, causeByUi, uiReady){
                        $.editorPlugin.get(editor, 'tip').tip('refresh', type, causeByUi, uiReady);
                    });
                }
            });
            setTimeout(loop, 500);
        })();
    })();

})(jQuery);

/*water.js*/
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
