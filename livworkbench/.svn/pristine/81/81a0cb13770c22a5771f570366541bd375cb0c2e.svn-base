(function($){
    if(mainConfig['which'] != 'k'){
        return;
    }

    $.layoutConfig = {
        proxy : './proxy.php'
    };

    $.widget('magic.layout', {
        options : {
            url : '',
            'edit-url' : '',
            'get-layout-info-url' : '',
            'update-layout-url' : '',
            'update-layout-title-url' : '',
            layouts : null,
            tpl : '',
            tname : 'layout-template',
            bttpl : '',
            btname : 'layout-bt-template',
            biaoji : 'm2o-layout-item',
            'drag-item' : 'layout-item',
            'sort-item' : 'canvas-item'
        },

        _create : function(){
            $.template(this.options.tname, this.options.tpl);
            $.template(this.options.btname, this.options.bttpl);
            var root = this.element;
            this.main = root.find('.layout-main');
            this._fetchList();
        },

        _fetchList : function(){
            var _this = this;
            $.getJSON(
                this.options.url,
                function(json){
                    _this._createList(json[0]);
                }
            );
        },

        _createList : function(layouts){
            var temData = [];
            var _layouts = {};
            $.each(layouts || {}, function(i, n){
                n && $.map(n, function(v){
                    v['img'] = v && v.indexpic && $.type(v.indexpic) != 'array' ? $.globalImgUrl(v.indexpic) : '';
                });
                temData.push({
                    title : i,
                    list : n
                });
                $.each(n, function(ii, nn){
                    _layouts[nn['id']] = nn;
                });
            });
            this.options.layouts = _layouts;
            $.tmpl(this.options.tname, temData).appendTo(this.main);
            this.element.find('.' + this.options['drag-item']).each(function(){
                $(this).draggable({
                    connectToSortable : '.canvas-m2o-main',
                    helper : 'clone',
                    appendTo : 'body',
                    revert : 'invalid',
                    scrollSpeed : 100,
                    scrollSensitivity : 40
                });
            }).disableSelection();
            this.main.find('.layout-cat-name:first').trigger('click');
        },

        _init : function(){
            var handlers = {};
            handlers[''] = '_btnClick';
            this._on({
                'click .layout-cat-name' : '_catClick',
                'click .layout-close' : 'btnClick'
            });

            var handlers = {};
            handlers['click .canvas-del'] = '_deleteClick';
            handlers['click .canvas-ss'] = '_ssClick';
            handlers['click .canvas-edit'] = '_editClick';
            handlers['click .lt-bt-ok'] = '_editOkClick';
            handlers['click .lt-bt-no'] = '_editNoClick';
            this._on('body', handlers);
        },

        getDefaultLayout : function(id){
            return this.options.layouts[id];
        },

        btnClick : function(event){
            var state = this.state = !this.state;
            this.element[state ? 'addClass' : 'removeClass']('on');
            this._mask(state);
            this._canvasBox(state);
            this._canvas(state);
            if(!state){
                //this.postLayoutIds();
                this._refreshAll();
            }
        },

        _catClick : function(event){
            var _this = this;
            this.calWidth = this.calWidth || (function(){
                var mainWidth = _this.main.width();
                var eachs = _this.main.find('.layout-cat-each');
                var len = eachs.length;
                var eachWidth = eachs.not('.on').eq(0).width();
                return mainWidth - (eachWidth + 1) * (len - 1);
            })();
            var targetParent = $(event.currentTarget).parent();
            var flexOne = 'on';
            var state = targetParent.hasClass(flexOne);
            if(state){
                return;
            }
            targetParent.addClass(flexOne).css('width', this.calWidth + 'px').siblings('.' + flexOne).removeClass(flexOne).css('width', '50px');
        },

        _deleteClick : function(event){
            var item = $(event.currentTarget).closest('.' + this.options['sort-item']);
            this._removeLayoutNew(item.data('hash'));
            item.css('overflow', 'hidden').animate({
                height : 0
            }, 300, function(){
                $(this).remove();
            });
        },

        _ssClick : function(event){
            var target = $(event.currentTarget);
            var state = !target.data('state');
            target.data('state', state);
            target.html(state ? '展开' : '收缩');
            target.closest('.' + this.options['sort-item']).css({
                'max-height' : (state ? '50px' : 'none'),
                'overflow' : (state ? 'hidden' : 'visible')
            });
        },

        _editClick : function(event){
            var target = $(event.currentTarget);
            var item = target.closest('.' + this.options['sort-item']);
            var hash = item.data('hash');
            var layoutId = item.data('id');
            var info = this._getLayoutFromList(layoutId);
            var btBox = $('.layout-bt-box[data-hash="' + hash + '"]');
            if(btBox[0] && btBox.is(':visible')){
                btBox.hide();
                item.removeClass('on');
                return;
            }
            if(!btBox[0]){
                btBox = $.tmpl(this.options.btname, {
                    headerText : info['header_text'],
                    moreHref : info['more_href']
                }).attr({
                    'data-hash' : hash,
                    'data-id' : layoutId
                }).appendTo('body');
            }
            var position = target.offset();
            btBox.css({
                left : position.left - 100 + 'px',
                top : position.top - 100 + 'px'
            }).show();
            item.addClass('on');
        },

        _editOkClick : function(event){
            var _this = this;
            var box = $(event.currentTarget).closest('.layout-bt-box');
            var headerText = box.find('[name="header_text"]').val();
            var moreHref = box.find('[name="more_href"]').val();
            var layoutId = box.data('id');
            var hash = box.data('hash');
            this.ajaxUpdateLayoutTitle(layoutId, hash, headerText, moreHref, box, function(json){
                var layout = _this._updateLayoutTitle(hash, json[0]);
                var canvasItem = _this._getCanvasItem(box.data('hash'));
                _this._customHC(layout[0], function(canvas){
                    canvasItem.find('img').remove();
                    _this._appendToCanvasItem(canvasItem, canvas);
                    box.hide();
                    canvasItem.removeClass('on');
                });
            });
            return;
            var titleInfo = {
                header_text : headerText,
                is_header : $.trim(headerText) ? 1 : 0,
                more_href : moreHref,
                is_more : $.trim(moreHref) ? 1 : 0
            };
            this._updateLayoutToList(layoutId, titleInfo);
            var _this = this;
            $.globalAjax(box, function(){
                return $.post(
                    _this.options['update-layout-title-url'],
                    $.extend({layout_id : layoutId}, titleInfo),
                    function(json){
                        var layout = _this._updateLayoutTitle(hash, json[0]);
                        var canvasItem = _this._getCanvasItem(box.data('hash'));
                        _this._customHC(layout[0], function(canvas){
                            canvasItem.find('img').remove();
                            _this._appendToCanvasItem(canvasItem, canvas);
                            box.hide();
                            canvasItem.removeClass('on');
                        });
                    },
                    'json'
                );
            });

        },

        _editNoClick : function(event){
            var box = $(event.currentTarget).closest('.layout-bt-box').hide();
            this._getCanvasItem(box.data('hash')).removeClass('on');
        },

        ajaxUpdateLayoutTitle : function(layoutId, hash, headerText, moreHref, box, cb){
            var _this = this;
            var titleInfo = {
                header_text : headerText,
                is_header : $.trim(headerText) ? 1 : 0,
                more_href : moreHref,
                is_more : $.trim(moreHref) ? 1 : 0
            };
            _this._updateLayoutToList(layoutId, titleInfo);
            var ajax = function(){
                return $.post(
                    _this.options['update-layout-title-url'],
                    $.extend({layout_id : layoutId}, titleInfo),
                    function(json){
                        cb && cb(json);
                    },
                    'json'
                );
            };
            if(box){
                $.globalAjax(box, ajax);
            }else{
                ajax();
            }
        },

        _updateLayoutTitle : function(hash, titleHtml){
            return $.iframe.updateLayoutTitle(hash, titleHtml);
        },

        _mask : function(state){
            this.mask = this.mask || $('<div/>').attr({
                'class' : 'layout-mask'
            }).appendTo('body');
            this.mask[state ? 'show' : 'hide']();
        },

        _canvasBox : function(state){
            var box = this.canvasBox = this.canvasBox || $('<div/>').attr({
                id : 'canvas-box',
                'class' : 'canvas-box'
            }).appendTo('body');
            state ? box.empty().show() : box.hide();
        },

        _canvasLoad : function(state){
            if(state){
                this.canvasBox.loading = $.globalLoad(this.canvasBox);
            }else{
                this.canvasBox.loading && this.canvasBox.loading();
                this.canvasBox.loading = null;
            }
        },

        _initSortable : function(){
            var _this = this;
            this.canvasMainBox.sortable({
                items : '.' + this.options['sort-item'],
                axis : 'y',
                zIndex : 100099,
                receive : function(event, ui){
                    $(this).data('receive', true);
                    _this._addCanvasItem(ui);
                },

                update : function(){
                    if($(this).data('receive')){
                        $(this).data('receive', false);
                        return;
                    }
                    _this._refreshLayoutNew();
                }
            });
        },

        _getLayoutFromList : function(id){
            var info;
            $.MC.info.layouts && $.each($.MC.info.layouts, function(i, n){
                if(n.id == id){
                    info = n;
                    return false;
                }
            });
            return info;
        },

        _addLayoutToList : function(info){
            if(!$.MC.info.layouts){
                $.MC.info.layouts = [];
            }
            $.MC.info.layouts.push(info);
        },

        _updateLayoutToList : function(id, info){
            var layoutInfo = this._getLayoutFromList(id);
            $.extend(layoutInfo, info);
        },

        _addCanvasItem : function(ui){
            var layoutId = ui.helper.data('id');
            var defaultLayoutInfo = this.getDefaultLayout(layoutId);
            var defaultLayoutTitle = defaultLayoutInfo['title'];
            var index = this.canvasBox.find('.ui-draggable').prevAll().length;
            var _this = this;
            var scrollTop = $('body').scrollTop();
            $.globalAjax(this.canvasBox.find('.ui-draggable').eq(0), function(){
                return $.getJSON(
                _this.options['get-layout-info-url'],
                {layout_id : layoutId},
                function(json){
                    var layoutInfo = json[0];
                    if(!layoutInfo) return;
                    _this._addLayoutToList(layoutInfo);
                    layoutId = layoutInfo['id'] || layoutInfo['layout_id'];
                    var hash = _this._hash();
                    var newLayout = _this._addLayoutNew(layoutId, layoutInfo, index, hash);
                    var imgs = newLayout.find('img');
                    var imgsLen = imgs.length;
                    if(imgsLen){
                        var imgsStart = 0;
                        imgs.on('load error', function(){
                            imgsStart++;
                            if(imgsStart == imgsLen){
                                doCanvas(newLayout[0]);
                            }
                        });
                    }else{
                        doCanvas(newLayout[0]);
                    }
                    var doCanvased = false;
                    function doCanvas(dom){
                        if(doCanvased) return;
                        doCanvased = true;
                        _this._customHC(dom, function(canvas){
                            var item = _this._canvasItem(hash, layoutId, defaultLayoutTitle);
                            _this.canvasBox.find('.' + _this.options['drag-item']).replaceWith(item);
                            _this._appendToCanvasItem(item, canvas);
                            $('body').scrollTop(scrollTop);
                        });
                    }
                });
            })
        },

        _addLayoutNew : function(layoutId, layoutInfo, index, hash){
            var layout = $.iframe.addLayoutNew(layoutId, layoutInfo, index, hash);
            this.postLayoutIds();
            return layout;
        },

        _removeLayoutNew : function(hash){
            $.iframe.removeLayoutNew(hash);
            this.postLayoutIds();
        },

        _refreshLayoutNew : function(){
            var hashs = [];
            this.canvasMainBox.find('.' + this.options['sort-item']).each(function(){
                var _hash = $(this).data('hash');
                _hash && hashs.push(_hash);
            });
            $.iframe.refreshLayoutNew(hashs);
            this.postLayoutIds();
        },

        _refreshAll : function(){
            $.iframe.refreshAll();
        },

        _canvas : function(state){
            if(!state) return;

            return this._canvasAnother();
        },

        _canvasAnother : function(){
            var _this = this;
            _this._canvasLoad(true);

            var defaultBoxs = $.iframe.HMT();
            var contentBody = defaultBoxs[1];
            //this._createDefault('head', defaultBoxs[0]);
            this._createDefault('main', contentBody);
            //this._createDefault('foot', defaultBoxs[2]);

            var children = contentBody.children();
            var childrenSort = [];
            var checkHashs = {};
            /*var filterTagName = ['style', 'script', 'link'];
            var filterPosition = ['absolute', 'fixed'];*/
            this.initIframeLayoutHash(children);
            var childIndex = 0;
            children.each(function(){
                /*if($.inArray(this.tagName.toLocaleLowerCase(), filterTagName) != -1){
                    return;
                }
                if($.inArray($(this).css('position'), filterPosition) != -1){
                    return;
                }*/

                var hash = $(this).attr('data-hash');
                if(!hash){
                    hash = _this._hash();
                    $(this).attr('data-hash', hash);
                }
                var layoutId = $(this).attr('data-id');
                if(!layoutId){
                    layoutId = _this._getInitLayoutId(childIndex);
                    $(this).attr('data-id', layoutId);
                }
                var defaultid = $(this).attr('data-defaultid');
                if(!defaultid){
                    defaultid = _this._getLayoutFromList(layoutId);
                    $(this).attr('data-defaultid', defaultid);
                }
                _this._canvasItem(hash, layoutId, _this.getDefaultLayout(defaultid)['title']);
                checkHashs[hash] = false;
                childrenSort.push([this, hash]);

                childIndex++;
            });
            function doCheckHashs(){
                var OK = true;
                $.each(checkHashs, function(i, n){
                    if(!n){
                        OK = false;
                        return false;
                    }
                });
                if(OK){
                    _this._canvasLoad(false);
                }
            }
            if(!childrenSort.length){
                doCheckHashs();
            }else{
                function doHtml2canvas(){
                    var nodeEach = childrenSort.shift();
                    if(!nodeEach) return;
                    var node = nodeEach[0];
                    var hash = nodeEach[1];
                    if(!node)  return;
                    try{
                        var $this = $(node);
                        var isHidden = !$this.outerHeight();
                        if(isHidden){
                            var paddingTop = $this.css('padding-top');
                            isHidden && $this.css('padding-top', '1px');
                        }
                        _this._customHC(node, function(canvas){
                            _this._appendToCanvasItem(hash, canvas);
                            checkHashs[hash] = true;
                            isHidden && $this.css('padding-top', paddingTop);
                            doCheckHashs();
                            doHtml2canvas();
                        });
                    }catch(e){}
                }
                doHtml2canvas();
            }
        },

        initIframeLayoutHash : function(children){
            if(this.initIframeed) return;
            this.initIframeed = true;
            var _this = this;
            var childIndex = 0;
            (children || $.iframe.getLayouts()).each(function(){
                var id = _this._getInitLayoutId(childIndex);
                var defaultId = _this._getLayoutFromList(id)['original_id'];
                $(this).attr({
                    'data-hash' : _this._hash(),
                    'data-id' : id,
                    'data-defaultid' : defaultId
                });
                childIndex++;
            });
        },

        _customHC : function(node, cb){
            html2canvas(node, {
                //logging : true,
                //proxy : $.layoutConfig.proxy,
                //timeout : 1000,
                onrendered : cb
            });
        },

        _createDefault : function(type, node){
            var _this = this;
            switch(type){
                case 'main':
                    var main = this.canvasMainBox = $('<div class="canvas-m2o-main"></div>').appendTo(this.canvasBox);
                    this._initSortable();
                    /*main.css('min-height', (function(){
                        return node.height() / ($(window).width() / 800) + 'px'
                    })());*/
                    break;
                case 'head':
                case 'foot':
                    var defaultBox = $('<div class="canvas-m2o-' + type + '"></div>').appendTo(this.canvasBox);
                    this._customHC(node, function(canvas){
                        _this._appendToCanvasItem(defaultBox, canvas);
                    });
                    break;
            }
        },

        _createCanvasItem : function(layoutName){
            return $('<div/>').attr({
                'class' : this.options['sort-item']
            }).append('<div class="canvas-do"><span class="canvas-lbtitle">'+ layoutName +'</span><span class="canvas-edit">编辑</span><span class="canvas-ss">收缩</span><span class="canvas-del">移除</span></div>');
        },

        _canvasItem : function(hash, id, layoutName){
            var item = this._createCanvasItem(layoutName).attr({
                'data-hash' : hash,
                'data-id' : id
            });
            item.appendTo(this.canvasMainBox);
            return item;
        },

        _getCanvasItem : function(hash){
            return this.canvasBox.find('[data-hash="' + hash + '"]');
        },

        _appendToCanvasItem : function(hash, canvas){
            this.getImage(canvas).appendTo($.type(hash) != 'string' ? hash : this._getCanvasItem(hash));
        },

        getImage : function(canvas){
            return $('<img/>').attr('src', canvas.toDataURL('image/png'));
        },

        _hash : function(){
            return $.iframe.biaojiHash();
        },

        _getInitLayoutId : function(index){
            if(!this.layoutIds){
                this.layoutIds = $.MC.info.layout_ids.split(',');
            }
            return this.layoutIds[index];
        },

        postLayoutIds : function(){
            var layoutIds = $.iframe.getLayoutIds();
            $.post(
                this.options['update-layout-url'],
                {layout_ids : layoutIds.join(',')},
                function(json){

                },
                'json'
            );
        },


        _destroy : function(){

        }
    });


    $.widget('magic.layoutMask', {
        options : {
            'update-layout-title-url' : '',
            tpl : '',
            tname : 'layout-mask-item'
        },

        _create : function(){
            $.template(this.options.tname, this.options.tpl);
        },

        _init : function(){
            this._on({
                'keyup input[name]' : '_doKeyup',
                'blur input[name]' : '_doBlur',
                'click .layout-mask-fast' : '_fastClick'
            });
            this.hide();
        },

        _createLayoutTitle : function(title, more){
            var html = '{{if title}}<div class="m2o-layout-title"><h2>{{= title}}</h2>{{if more}}<a href="{{= more}}">更多>></a>{{/if}}</div>{{/if}}';
            $.template('layout-title-tname', html);
            return $.tmpl('layout-title-tname', {
                title : $.trim(title) ? title : '',
                more : $.trim(more) ? more : ''
            });
        },

        _doKeyup : function(event){
            var target = $(event.currentTarget);
            var item = target.closest('.layout-mask-item');
            var layoutId = item.data('id');
            var hash = item.data('hash');
            var title = this._getHeadInput(item).val();
            var more = this._getMoreInput(item).val();
            var layout = $.iframe.getLayout(hash);
            var $title = layout.find('.m2o-layout-title');
            var $newTitle = this._createLayoutTitle(title, more);
            if(!$newTitle[0]){
                $title.remove();
                return;
            }
            if(!$title[0]){
                $newTitle.prependTo(layout);
            }else{
                $title.replaceWith($newTitle);
            }
        },

        _doBlur : function(event){
            var target = $(event.currentTarget);
            var item = target.closest('.layout-mask-item');
            var layoutId = item.data('id');
            var hash = item.data('hash');
            var title = this._getHeadInput(item).val();
            var more = this._getMoreInput(item).val();
            $.MC.lb.layout('ajaxUpdateLayoutTitle', layoutId, hash, title, more);
        },

        _fastClick : function(event){
            var target = $(event.currentTarget);
            var item = target.closest('.layout-mask-item');
            var hash = item.data('hash');
            $.MC.ltb.columnTitle('show', hash);
        },

        _getHeadInput : function(item){
            return item.find('[name="header_text"]');
        },

        _getMoreInput : function(item){
            return item.find('[name="more_href"]');
        },

        refresh : function(){
            var _this = this;
            $.MC.lb.layout('initIframeLayoutHash');
            var infos = $.iframe.getLayoutSetTitleMask();
            $.each(infos, function(i, n){
                var mask = _this._findItem(i);
                var info = _this._getLayoutInfo(n['id']);
                if(!mask[0]){
                    mask = _this._createItem(n['id'], n['hash'], info);
                }else{
                    _this._valItem(mask, info);
                }
                _this._cssItem(mask, n);
                mask.attr('checked', 1);
            });
            _this.element.find('.layout-mask-item').filter(function(){
                return !$(this).attr('checked');
            }).remove();
        },

        refreshItemTitle : function(hash, valInfo){
            valInfo['header_text'] = valInfo['name'];
            valInfo['more_href'] = valInfo['column_url'];
            var item = this._findItem(hash);
            this._valItem(item, valInfo);
            this._getHeadInput(item).trigger('keyup').trigger('blur');
        },

        _findItem : function(hash){
            return this.element.find('.layout-mask-item[data-hash="' + hash + '"]');
        },

        _createItem : function(id, hash, info){
            var mask = $.tmpl(this.options.tname, {
                id : id,
                hash : hash,
                headerText : info['header_text'] || '',
                moreHref : info['more_href'] || ''
            }).appendTo(this.element);
            return mask;
        },

        _cssItem : function(mask, screenInfo){
            mask.css({
                left : screenInfo['left'] + 'px',
                top : screenInfo['top'] + 'px',
                width : screenInfo['width'] - 4 + 'px',
                height : screenInfo['height'] - 4 + 'px'
            });
        },

        _valItem : function(mask, valInfo){
            this._getHeadInput(mask).val(valInfo['header_text']);
            this._getMoreInput(mask).val(valInfo['more_href']);
        },

        _getLayoutInfo : function(id){
            var info;
            $.MC.info.layouts && $.each($.MC.info.layouts, function(i, n){
                if(n.id == id || n.layout_id == id){
                    info = n;
                    return false;
                }
            });
            return info;
        },

        show : function(){
            this.refresh();
            this.element.show();
        },

        hide : function(){
            this.element.hide();
        },

        bjm : function(){
            this[this.element.css('display') != 'none' ? 'hide' : 'show']();
        },

        _destroy : function(){

        }
    });

    $.widget('magic.columnTitle', $.magic.plugin, {
        options : {
            url : '',
            tpl : '',
            tname : 'column-title-template'
        },

        _create : function(){
            this._super();
            $.template(this.options.tname, this.options.tpl);
            this.inited = false;
            this.info = null;
        },

        _init : function(){
            this._super();
            this._on({
                'click li' : '_liClick'
            });
        },

        _liClick : function(event){
            var target = $(event.currentTarget);
            var id = target.data('id');
            var info = this._getInfo(id);
            if(this.target == 'dy'){
                $.MC.pb.property('setColumnTitle', info);
            }else{
                $.MC.lbm.layoutMask('refreshItemTitle', this.target, info);
            }
            this.hide();
        },

        _getInfo : function(id){
            return this.info[id];
            var info = {};
            $.each(this.info, function(i, n){
                if(n.id == id){
                    info = n;
                    return false;
                }
            });
            return info;
        },

        _refresh : function(){
            if(this.inited){
                return;
            }
            this.inited = true;
            var _this = this;
            $.globalAjax(this.element, function(){
                return $.getJSON(
                    _this.options.url,
                    function(json){
                        _this.info = json;
                        _this._list();
                    }
                );
            });
        },

        _list : function(){
            var data = this.info ? $.customMakeArray(this.info) : [];
            $.tmpl(this.options.tname, data).appendTo(this.element.find('.plugin-body ul'));
        },

        show : function(target){
            this._super();
            this._refresh();
            this.target = target;
        },

        hide : function(){
            this._super();
            this.target = null;
        },

        _destroy : function(){

        }
    });

    $(function($){
        $.extend($.MC, {
            lb : $('#layout-box'),
            lbm : $('#layout-mask-box'),
            ltb : $('#column-title-box')
        });

        $.MC.lb.layout({
            url : mainConfig.layout,
            'edit-url' : mainConfig.edit,
            'get-layout-info-url' : mainConfig.getLayoutInfo,
            'update-layout-url' : mainConfig.updateLayout,
            'update-layout-title-url' : mainConfig.updateLayoutTitle,
            tpl : $('#layout-tpl').html(),
            bttpl : $('#layout-bt-tpl').html()
        });

        $.MC.lbm.layoutMask({
            'update-layout-title-url' : mainConfig.updateLayoutTitle,
            tpl : $('#layout-mask-item-tpl').html()
        });

        $('.dylist-inner').addClass('dylist-inner-k');
    });
})(jQuery);