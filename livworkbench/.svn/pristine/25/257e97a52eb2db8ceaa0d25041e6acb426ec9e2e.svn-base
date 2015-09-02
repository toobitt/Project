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