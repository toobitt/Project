(function($){

    $.widget('ueditor.base', {
        options : {
            editor : null,
            config : null,
            className : ''
        },

        _create : function(){
        	/*后台配置的图片最大宽度常量*/
            this.maxpicsize = $.maxpicsize ? parseInt( $.maxpicsize ) : 640;
            this.editor = this.options.editor;
            this.editorBody = this.editor.document.body;
            this.editorOp = this.editor.options;
            this.uid = this.editor.uid;
            this.slide = this.editorOp.slide;
            this.element.attr({
                'ueditor-m2o-plugin' : this.uid,
                'class' : this.options.className
            });
        },

        _init : function(){
        	
        },

        _template : function(tname, info, container, datas){
            tname = tname || this.options.templateName;
            info = info || this.options.pluginInfo;
            container = container || this.element;
            datas = datas || {};
            $.template(tname, info.template);
            var dom = $.tmpl(tname, datas).appendTo(container);
            if(!info.cssInited && info.css){
                info.cssInited = true;
                this.addCss(info.css);
            }
            return dom;
        },

        addCss : function(css){
            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
        },

        position : function(position){
            this._position = position;
            this.element.css({
                left : position.left + 'px',
                top : position.top + 'px'
            });
        },

        getPosition : function(node){
            var editorPosition = $(this.editor.iframe).offset();
            var nodePosition = $(node).offset();
            return {
                left : editorPosition.left + nodePosition.left,
                top : editorPosition.top + nodePosition.top
            };
        },

        range : function(){
            return this.editor.selection.getRange();
        },

        rangeSelect : function(range){
            (range || this.range()).select(true);
        },

        rangeSelectNode : function(node){
            this.rangeSelect(this.range().selectNode(node));
        },
        
        exec : function(cmd, arg){
            this.editor.execCommand(cmd, arg);
        },

        insertHtml : function(html){
            this.exec('insertHtml', html);
        },

        insertImg : function(type, imgInfo){
            var _this = this;
            var imgHtml = '';
            var call;
            switch(type){
                case 'img':
                    var hash = +new Date();
                    imgHtml = '<p style="text-align: center;"><img class="image" imageid="' + imgInfo['id'] + '" src="' + $.globalImgUrl(imgInfo, _this.maxpicsize + 'x') + '" hash="' + hash + '"/></p>';
                    call = function(){
                        var img = $(_this.editor.document).find('.image[hash="' + hash + '"]')[0];
                        if(!img) return;
//                        _this.rangeSelectNode(img);
                        _this.exec('imagefloat', 'center');
                        $(img).removeAttr('hash');
                        _this.range().setCursor(true);
                    };
                    break;
                case '':
                    break;
            }
            this.insertHtml(imgHtml);
            call && call();
        },
        iframeImageGet : function( id ){
            return $(this.editor.document).find('img.image[imageid="'+ id +'"]');
        },
        show : function(){
        	this.hideOthers();
        	this.element.toggleClass('pop-show');
        },
        
        hideAll : function(){
        	var items = $('[ueditor-m2o-plugin="' + this.uid + '"]');
        	items.removeClass('pop-show');
        },
        
        hideOthers : function(){
        	var items = $('[ueditor-m2o-plugin="' + this.uid + '"]').not(this.element);
        	items.removeClass('pop-show');
        },
        
        hide : function(){
            this.element.removeClass('pop-show');
        },

        _checkPlugin : function(plugin){
            return !!$.ueditor[plugin];
        },

        hash : function(){
            return + new Date() + '' + Math.floor(Math.random() * 1000);
        },
        

        _destroy : function(){

        }
    });


    var tooltipInfo = {
    	template : ''+
    				'<div class="tooltip-box"><div class="tooltip-mask"></div><div class="tooltip-msg">${tip}</div></div>' + 
    				'',
    	css : '' + 
    			'.tooltip-box .tooltip-msg{border:1px solid rgb(255, 154, 0);color:rgb(255, 154, 0);background-color:#fff; height:35px; line-height:35px; padding:10px; border-radius:2px;text-align:center;}' +
    			'.tooltip-box .tooltip-mask{position:absolute;top:-26px;width:26px;height:26px;background:yellow;opacity:0.2;}' +
    			'',
    	cssInited : false
    };
    
    var pluginInfo = {
        template : '' +
            //'<div class="ump-box">' +
        		'<div class="ump-wrap">' + 
	                '<div class="ump-inner">' +
	                    '<div class="ump-head">' +
	                        '<div class="ump-title"></div>' +
	                        '<div class="ump-option">' +
	                            '<span class="ump-no">关闭</span>' +
	                        '</div>' +
	                    '</div>' +
	                    '<div class="ump-body"></div>' +
	                '</div>' +
	              '</div>'+
            //'</div>' +
            '',
        css : '' +
            '.ump-box{position:absolute;left:50%;top:-1000px; overflow:hidden; opacity:0; z-index:-1; width:254px; margin-left:-127px;background:#fff;border:10px solid #6ea5e8; 1-webkit-transition:all 0.5s ease; 1-moz-transition:all 0.5s ease; 1transition:all 0.5s ease;  }' +
            '.pop-show{opacity:1; top:97px; z-index:1000;}' +
            '.ump-wrap{width:2000px;position:relative;min-height:262px;left:0;}'+
            '.ump-inner{float:left;width:254px;}'+
            '.ump-head{height:43px; line-height:43px; margin:0 10px; border-bottom:1px solid #e6e6e6;position:relative;cursor:move;}' +
            '.ump-title{padding-left:10px; font-size:14px;}'+
            '.ump-option{position:absolute;right:12px;top:11px;z-index:1;}' +
            '.ump-no{width:13px; height:13px;display:inline-block; font-size:0; cursor:pointer; background:url(./res/ueditor/third-party/m2o/images/close4.png) no-repeat center center #fff;}' +
            '',
        cssInited : false

    };
    
    var slidePluginInfo = {
        	template : '' +
                //'<div class="editor-slide-box">' +
        			'<div class="editor-slide-wrap">'+
	                    '<div class="editor-slide-inner">' +
	                        '<div class="editor-slide-head">' +
	                            '<div class="editor-slide-title">标题</div>' +
	                            '<div class="editor-slide-option">' +
	                                '<span class="editor-slide-no"></span>' +
	                            '</div>' +
	                        '</div>' +
	                        '<div class="editor-slide-body"></div>' +
	                    '</div>' +
	                 '</div>' +
                //'</div>' +
                '',
    		css : '' + 
	    		'.editor-slide-box{position:absolute;z-index:-1;transition:z-index .3s;right:100px;top:90px;width:265px;height:400px;overflow:hidden;}' +
				'.editor-slide-box.pop-show{z-index:1000;}'+
				'.editor-slide-wrap{left:-267px;width: 2000px;height:100%;position:absolute;transition: left .3s;}'+
				'.editor-slide-inner{background: #f9f9f9;float:left;border:1px solid #e9e8e6;width:265px;height:100%;}' +
				'.editor-slide-head{position:relative;border-bottom:1px solid #e7e7e7;height:43px;line-height:43px;text-align:center;margin:0 10px;}' +
				'.editor-slide-no{position:absolute;top:5px;right:0;width:30px;height:30px;line-height:30px;cursor:pointer;background:url(./res/ueditor/third-party/m2o/images/close4.png)no-repeat center;}' +
				'.pop-show .editor-slide-wrap{left:0;}'+
				'',
    		cssInited : false
        };
    
    $.widget('ueditor.baseWidget', $.ueditor.base, {
        options : {
            className : 'ump-box',
            templateName : 'base-plugin-template'
        },

        _create : function(){
            this._super();
            this._initDom();
        },

        _init : function(){
            this._super();
            var flag = this.slide ? 'editor-slide' : 'ump';
            var handlers = {};
            handlers['click ' + '.'+ flag +'-ok'] = 'ok';
            handlers['click ' + '.'+ flag +'-no'] = 'no';
            this._on( handlers );
        },
        _initDom : function(){
        	this._template('', this.slide ? slidePluginInfo : pluginInfo);
        	if( this.slide ){
        		this.element.removeClass( this.options.className ).addClass( 'editor-slide-box' );
        	}
        	var root = this.element,
            	flag = this.slide ? 'editor-slide' : 'ump';
        	this.wrap = root.find('.'+ flag +'-wrap');
            this.inner = root.find('.'+ flag +'-inner');
        	this.body = root.find('.'+ flag +'-body');
        	this.title = root.find('.'+ flag +'-title');
        	this.okBtn = root.find('.'+ flag +'-ok');
        	this.noBtn = root.find('.'+ flag +'-no');
        	this.options.title && this.setTitle(this.options.title);
        	if( !this.slide ){	//弹窗
            	this.element.draggable();
            }else{
            	var relyDom = this.editorOp.relyDom;
            	if( relyDom ){
            		this.element.offset( $( relyDom ).offset() );
            		this.element.height( $( relyDom ).outerHeight() );
            	}
            }
        },
        setTitle : function(title){
            this.title.html(title);
        },

        ok : function(){
            this.element.removeClass('pop-show');
        },

        no : function(){
             this.element.removeClass('pop-show');
        },

        showNext : function( callback ){
        	var flag = this.slide ? 'editor-slide' : 'ump',
        		cname = flag +'-inner',
        		oWid = this.inner.outerWidth(true);
        	var dom = $('<div />').appendTo( this.wrap ).attr('class',cname);
        	this.wrap.css({
				left: '-=' + oWid + 'px'
			});
        	
        	if( callback && $.isFunction( callback ) ){
        		callback( dom );
        	}
        },
        showBack : function( callback ){
        	var flag = this.slide ? 'editor-slide' : 'ump',
            	cname = flag +'-inner',
            	oWid = this.inner.outerWidth(true),
            	_this = this;
        	this.wrap.css({
				left: '+=' + oWid + 'px'
			});
        	setTimeout(function(){
        		_this.wrap.find('.'+cname+':last-child').remove();
        		if( callback && $.isFunction( callback ) ){
            		callback( );
            	}
        	},300);
        },
        
        _tooltip : function( tool, tip ){
        	var tooloffset = $('.' + tool).offset();
        	this.tooltip_dom = this.tooltip_dom || this._template('tooltip-tpl',tooltipInfo, $('body'), { tip : tip });
        	this.tooltip_dom.css({
        		'z-index' : 10000,
        		position : 'absolute',
        		top : tooloffset.top + 26 + 'px',
        		left : tooloffset.left + 'px',
        	});
        },
        
        _tooltipend : function( tip ){
        	var _this = this;
        	this.tooltip_dom.find('.tooltip-msg').text( tip );
        	setTimeout( function(){
        		_this.tooltip_dom.remove();
        		_this.tooltip_dom = null;
        	}, 1000 );
        },
        _destroy : function(){

        }
    });

    $.ueditor.m2oPlugins = function(){
        var utils = baidu.editor.utils;
        var editorui = baidu.editor.ui;

        var m2oPlugins = {
            plugins : {},

            add : function(pluginOption){
                var cmd = pluginOption['cmd'];
                if(this.plugins[cmd]){
                    return;
                }
                this.plugins[cmd] = pluginOption;
                this.init(cmd, pluginOption);
            },

            init : function(cmd, pluginOption){
                editorui[cmd] = function(editor){
                    var ui = new editorui.Button({
                        className : 'edui-for-' + cmd,
                        title : editor.options.labelMap.cmd || pluginOption['title'],
                        theme : editor.options.theme,
                        onclick : function(){
                            pluginOption['click'] ? pluginOption['click'](editor) : editor.execCommand(editor);
                        }
                    });
                    editorui.buttons[cmd] = ui;
                    return ui;
                }
            }

        };
        return m2oPlugins;

    }();

    $.ueditor.pluginDir = './res/ueditor/third-party/m2o/images';
    $.ueditor.gPixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;

    $.editorPlugin = function(){
            return {
                get : function(editor, plugin){
                    !editor.m2oPlugins && (editor.m2oPlugins = {});
                    var baseUrl = './run.php?mid=' + gMid + '&a=',
                    defaultOption = {
				    		uploadUrl: baseUrl + 'upload&admin_id=' + gAdmin.admin_id + '&admin_pass=' + gAdmin.admin_pass,			//附件上传接口
				    		revolveImgUrl: baseUrl + 'revolveImg&admin_id=' + gAdmin.admin_id + '&admin_pass=' + gAdmin.admin_pass,	//图片旋转接口
				    		waterUrl: baseUrl + 'water_config_list',																//水印列表接口
				    		saveWaterUrl : baseUrl + 'create_water_config',															//创建水印接口
				    		referUrl : baseUrl + 'get_sketch_map',																	//点击素材
				    		materialUrl : baseUrl + 'get_material_node',															//素材列表接口
				    		materialInfoUrl : baseUrl + 'get_material_info',														//引用素材信息接口
				    		imgLocalUrl : baseUrl + 'img_local&admin_id=' + gAdmin.admin_id + '&admin_pass=' + gAdmin.admin_pass,	//引用素材信息接口
				    		officeUrl : './word.php'																				//word上传接口
			    		};
                    var _plugin = editor.m2oPlugins[plugin],
                    	config_interface = $.extend( {}, defaultOption, editor.options.config_interface );
                    if(!_plugin){
                        _plugin = editor.m2oPlugins[plugin] = $('<div/>').appendTo('.ueditor-outer-wrap')[plugin]({
                            editor : editor,
                            config : config_interface
                        }).addClass( plugin + '-outer' );
                    }else{
                    }
                    return _plugin;
                },
                check : function(editor, plugin){
                    return editor.m2oPlugins[plugin];
                }
            };
     }();
    

    (function(){
        var cache = {};
        (function loop(){
            $.each(UE.instants, function(key, editor){
                if(!cache[key]){
                    cache[key] = true;
                    editor.callbacks = new $.Callbacks();
                    editor.ready(function(){
                        editor.options.imagePopup = false;
                        this.addListener('selectionchange', function(type, causeByUi, uiReady){
                            this.callbacks.fire(type, causeByUi, uiReady);
                        });
                    });
                }
            });
            setTimeout(loop, 500);
        })();
    })();

    /*
    UE.plugins['xxx'] = function(){
        UE.commands['xxx'] = {
            execCommand:function (cmdName, align) {
                console.log(arguments);
                return true;
            },
            queryCommandValue:function () {
                return '';
            },
            queryCommandState:function () {
                return  state ? -1 : 0;
            }
        };
    };
    */

    $.globalImgUrl = function(info, wh, f5){
        wh = wh ? wh + '/' : '';
        f5 = f5 ? '?' + parseInt(Math.random() * 100000) : '';
        if(info['path']){
            return info['path'] + wh + info['dir'] + info['filename'] + f5;
        }
        return info['host'] + info['dir'] + wh + info['filepath'] + info['filename'] + f5;
    }
})(jQuery);
