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