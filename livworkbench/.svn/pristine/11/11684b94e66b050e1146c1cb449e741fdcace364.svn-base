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
        			var water_id = $('#water_config_id');
                	if( water_id.length && water_id.val().trim() ){
                		data.append('water_config_id' ,water_id.val().trim() );
                	}
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
        	if( data.error ){
        		alert( data.error );
        		return;
        	}
        	if( !this.slide ){		//弹窗风格
        		$.template('imgmanage_pop_style_item',imgInfo.item_tpl);
        		$.tmpl('imgmanage_pop_style_item', data).appendTo(this.content);	//用tmpl先命名和水印的小模板命名冲突了
        		var prevImg = this.element.find('.editor-current-img');
            	if( !prevImg.attr('src')){
            		var first = $( '.item-box:first-child');
            		first.click();
            	}
        	}else{			//侧滑风格
        		var dom = $.tmpl('slide_item_tpl', data).prependTo(this.list);
        		var img = dom.find('.image');
            	this._loadImg( dom );
            	this.bindPicEditor({
            		target : img,
            		src : img.attr('path') + img.attr('dir') + img.attr('filename'),
            		imgId : img.attr('imageid')
            	});
            	if( this.editorOp && this.editorOp.suoyinId == data['material_id'] ){
            		dom.find('.suoyin').addClass('suoyin-current');
            	}
        	}
        },
        bindPicEditor : function( op ){
        	var me = op.target,
            	_this = this;
            me.picEdit({
                imageId : 'slide-image',
                imgSrc : op.src,
                saveAfter : function(){
                    top.$('body').find('img.tmp-edit-top-img').remove();
                    top.$('body').off('_picsave').on('_picsave', function(event, info){
                    	try{
//                    		var topImg = $(this).find('#slide-image');
	                        var attrs = {
	                            path : info['host'] + info['dir'],
	                            dir : info['filepath'],
	                            filename : info['filename'],
	                            src : $.globalImgUrl(info, '160x', true),
	                            bigsrc : $.globalImgUrl(info, _this.maxpicsize + 'x', true)
	                        };
	                        me.attr(attrs);
	                        $(_this.editorBody).find('.image[imageid="'+ op.imgId +'"]').attr('src',attrs.bigsrc).attr('_src',attrs.bigsrc);
	                        $.editorPlugin.get(_this.editor, 'imginfo').find('.img-box img').attr('src',attrs.bigsrc);
	                        $(this).find('img.tmp-edit-top-img').remove();
	                        var syFlag = _this.slide ? op.target.siblings('.suoyin') : _this.element.find('.img-indexpic');
	                    	if( syFlag.hasClass('suoyin-current') || syFlag.hasClass('current') ){
	                    		_this.editor.fireEvent('_refreshIndexPic', attrs);
	                    	}
                    		_this.editor.sync();
                    	}catch(e){}
                    }).append(me.clone().hide().attr('id', 'slide-image').addClass('tmp-edit-top-img')).data('current-edit-image', 'slide-image');
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
        //切换当前的图片（只有弹窗风格有）
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
	    	var prevImg = currentWrap.find('.image');
	    	this.bindPicEditor({
        		target : prevImg,
        		src : self.attr('bigsrc'),
        		imgId : id,
        	});
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