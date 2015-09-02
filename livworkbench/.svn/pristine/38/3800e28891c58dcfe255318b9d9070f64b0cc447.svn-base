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
					_this.editor.sync();
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