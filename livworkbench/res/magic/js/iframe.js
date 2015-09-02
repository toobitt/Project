(function($){
    $.ajaxPrefilter(function(options, oroptions, jqXHR){
        options.url += (options.url.indexOf('?') != -1 ? '&' : '?') + 'ajax=1';
        var oldSuccess = options.success;
        options.success = function(json){
            if(json && json.login_error){
                $('<a href="/"></a>').appendTo('body')[0].click();
                return false;
            }
            oldSuccess && $.isFunction(oldSuccess) && oldSuccess(json);
        }
    });

    $.globalImgUrl = function(info, wh, f5){
        if(!info) return '';
        wh = wh ? wh + '/' : '';
        f5 = f5 ? '?' + parseInt(Math.random() * 100000) : '';
        if(info['path']){
            return info['path'] + wh + info['dir'] + info['filename'] + f5;
        }
        return info['host'] + info['dir'] + wh + info['filepath'] + info['filename'] + f5;
    };
    
    $.create_rgb_color = function( weight ){
    	weight = 100 - weight;
        return "rgb(" + ([255, weight * 2, weight].join()) + ")";
    };

    $.customMakeArray = function(data){
        var clone = [];
        data && $.each(data, function(i, n){
            clone.push(n);
        });
        return clone;
    };

    $.makeHash = function(){
        if(!$.makeHash.cache){
            $.makeHash.cache = {};
        }
        var hash;
        while(true){
            hash = (+new Date()) + '' + parseInt(Math.random() * 1000000);
            if(!$.makeHash.cache[hash]){
                $.makeHash.cache[hash] = true;
                break;
            }
        }
        return hash;
    };

    $.customDomCache = function(key){
        key = key || 'column-html';
        var cache  = window.localStorage;
        return {
            get : function(){
                //return $.DOMCached.get(key);
                return cache.getItem(key);
            },
            set : function(value){
                //$.DOMCached.set(key, value, false);
                cache.setItem(key, value);
            },
            remove : function(key){
                cache.removeItem(key);
            }
        }
    };

    $.replaceST = function(){
        return {
            T : function(html){
                html = html.replace(/&amp;/g, "&");
                html = html.replace(/&lt;/g, "<");
                html = html.replace(/&gt;/g, ">");
                return html;
            },

            S : function(html){
                html = html.replace(/&/g, "&amp;");
                html = html.replace(/</g, "&lt;");
                html = html.replace(/>/g, "&gt;");
                return html;
            }
        }
    }();










    $.iframe = function(){
        var cellsUnit = {
            cache : {},
            get : function(hash){
                return !hash ? this.cache : this.cache[hash] || {};
            },
            set : function(hash, info, extend){
                if(extend){
                    info = $.extend(this.cache[hash], info);
                }
                this.cache[hash] = info;
            },
            remove : function(hash){
                delete this.cache[hash];
            },
            empty : function(){
                this.cache = {};
            }
        };

        var loadGuidCallBack;
        var cellClass = '.livcms_cell';
        var cellNamePre = 'liv_';
        var randomClass = 'cell-zwf-' + $.makeHash();
        var randomZWF = '<span class="' + randomClass + '" style="position:absolute;width:0;height:0;clear:both;overflow:hidden;opacity:0;margin:0;padding:0;border:none;"></span>';
        var randomSpace = '<div style="display:block;width:100%;height:20px;opacity:0;margin:0;padding:0;border:none;float:none;clear:both;"></div>';
        return {
            it : null,

            widget : function(){
                return this.it;
            },

            bind : function(){
                var _this = this;
                this.it.on({
                    load : function(){
                        if(_this.timeoutTimer){
                            clearTimeout(_this.timeoutTimer);
                            _this.timeoutTimer = null;
                        }
                        if(_this.loaded) return;
                        _this.loaded = true;
                        loadGuidCallBack && loadGuidCallBack();

                        if(mainConfig.which == 'k'){
                            //$.iframe.mainMinHeight();
                        }

                        _this.getCellsPosition();
                    }
                });
            },

            init : function(){
                loadGuidCallBack = $.globalLoad(window);

                !this.it && (this.it = $.MC.it);
                var it = this.it[0];

                this.bind();

                var template = $.MC.info.template;
                template += '<script>(function(){parent.$.iframe.start();})();</script>';
                var itc = it.contentDocument || it.contentWindow.document;
                try{
                    itc.open('text/html');
                    itc.write(template);
                    itc.close();
                }catch(e){}

            },

            resize : function(){
                this.it.height(function(){
                    return $(this).contents().find('html').height();
                });
            },

            HMT : function(){
                var content = this.getContent();
                var body = content.find('body');
                var main = content.find('#m2o-main-box');
                if(main.length != 1){
                    main = main.filter(function(){
                        return $(this).parent()[0] == body[0];
                    });
                }
                var head = main.prev();
                var foot = main.next();
                return [head, main, foot];
            },

            mainMinHeight : function(){
                var ds = this.HMT();
                ds[1][0] && ds[1].css('min-height', $(window).height() - ds[0].outerHeight(true) - ds[2].outerHeight(true) + 'px');
            },

            start : function(){
                setInterval(function(){
                    $.iframe.resize();
                }, 50);
                //this.biaoji();
                this.getCells();

                this._timeout();

                $.MC.mb.mask({
                    infos : cellsUnit.get(),
                    save : mainConfig.save,
                    cancel : mainConfig.cancel,
                    staticSave : mainConfig.staticSave
                });


                this.xiufuIframe();
                /*if($.MC.lb){
                 $.MC.lb.layout('addLayoutStyle').layout('moban');
                 }*/
            },

            xiufuIframe : function(){
                //这个方法可以被覆盖，主要用来进行一些对iframe修复的工作，如：防止页面中的链接跳转
            },

            _timeout : function(){
                var _this = this;
                _this.timeoutTimer = setTimeout(function(){
                    _this.timeoutTimer = null;
                    _this.it.trigger('load');
                }, 10000);
            },

            getContent : function(){
                return this.it.contents();
            },

            getChildren : function(){
                return this.getLayouts();
            },

            getChildrenHash : function(){
                return this.childrenHashs;
            },

            biaoji : function(){
                var _this = this;
                _this.childrenHashs = [];
                this.getChildren().each(function(){
                    var hash = _this.biaojiHash();
                    _this.childrenHashs.push(hash);
                    $(this).attr('hash', hash);
                });
            },

            biaojiHash : function(){
                return 'hash-' + $.makeHash();
            },

            checkCell : function(info){
                return true;
            },

            getCells : function(parent, cellInfos){
                var _this = this;
                var content = this.getContent();
                var parentContent = parent || content;
                parentContent.find(cellClass).filter(function(){
                    return !$(this).prev().is('.' + randomClass);
                }).each(function(){

                        var hash = _this.hash();
                        var name = $(this).text();
                        var info = _this.getCellInfo(name, hash, cellInfos);
                        if(!info) return;
                        var parent = $(this).parent().addClass(hash);
                        var isAlignCenter = parent.css('text-align') == 'center';
                        var prev = $(this).before(randomZWF).prev().addClass('before-' + hash);
                        var next = $(this).after(randomZWF).next().addClass('after-' + hash);
                        isAlignCenter && prev.add(next).css({position : 'static'});
                        if(info && info['cell_mode'] > 0){
                            var $this = $(this);
                            var _tmpHtml = (info['static_html'] || info['rended_html']).replace(/<script\s/ig, '<scriptm2o ');
                            _tmpHtml = _tmpHtml.replace(/<\/script>/ig, '</scriptm2o>');
                            try{
                                $this.replaceWith(_tmpHtml);
                                parent.find('scriptm2o').each(function(){
                                    var _document = _this.it[0].contentWindow.document;
                                    var src = $.trim($(this).attr('src'));
                                    if(src){
                                        var iframe = $('<iframe scrolling="no" frameborder="0"></iframe>').insertAfter(this);
                                        var iframeDocument = iframe[0].contentDocument || iframe[0].contentWindow.document;
                                        iframeDocument.open('text/html');
                                        iframeDocument.write(this.outerHTML.replace(/scriptm2o/g, 'script'));
                                        iframeDocument.close();
                                    }else{
                                        var script = _document.createElement('script');
                                        this.parentNode.insertBefore(script, this);
                                        script.textContent = $(this).html();
                                    }
                                    $(this).remove();
                                });
                            }catch(e){}
                        }
                        _this.addCSS(info['css'], hash, content.find('head'));
                        _this.addJS(info['js'], hash, content.find('body'));
                        if(!_this.checkCell(info)) return;
                        cellsUnit.set(hash, {
                            name : name,
                            id : info['id']
                        });
                    });
            },



            getCellsPosition : function(){
                var _this = this;
                var cells = cellsUnit.get();
                var content = this.getContent();
                $.each(cells, function(hash, n){
                    var cellParent = content.find('.' + hash);
                    /*if(!cellParent[0] || cellParent.css('display') == 'none'){
                        cellsUnit.set(hash, {
                            hidden : true
                        });
                        return;
                    }*/
                    var before = cellParent.find('.before-' + hash);
                    var beforeInfo = before.offset();
                    var last = cellParent.find('.after-' + hash);
                    var lastInfo = last.offset();
                    var siblings = before.nextUntil(last);
                    var left, top, width, height;
                    var gohere = false;
                    if(siblings.length == 1 && siblings.is(cellClass)){
                        if(cellParent.find('.' + randomClass).length == 2){
                            height = cellParent.height();
                            gohere = true;
                        }else if(siblings.siblings(cellClass).length > 0){
                            var cellOffset = siblings.offset();
                            left = cellOffset.left;
                            top = cellOffset.top;
                            width = siblings.width();
                            height = siblings.height();
                        }else{
                            gohere = true;
                        }
                    }else{
                        height = lastInfo['top'] - beforeInfo['top'];
                        gohere = true;
                    }
                    if(gohere){
                        var min = _this._minWH(cellParent, height);
                        width = min[0];
                        height = min[1] || 10;
                        left = beforeInfo.left;
                        top = beforeInfo.top;
                    }
                    cellsUnit.set(hash, {
                        width : width,
                        height : height,
                        left : left,
                        top : top,
                        hidden : false
                    }, true);
                });
                $.MC.mb.mask('refresh', cellsUnit.get());
            },

            _minWH : function(dom, height){
                dom = $(dom);
                var minW = dom.width();
                var minH = height || dom.height();
                var stopH = false;
                dom.parents().each(function(){
                    var w = $(this).width();
                    w && minW > w && (minW = w);
                    if(!stopH && $(this).find('.' + randomClass).length == 2){
                        var h = $(this).height();
                        if(!minH || (h && minH > h)){
                            minH = h;
                        }
                    }else{
                        stopH = true;
                    }
                });
                return [minW, minH];
            },

            getCellInfo : function(name, hash, cellInfos){
                var cellNew = $.MC.info.cellNew;
                var info;
                var setInfo = function(n){
                    if(cellNamePre + n['cell_name'] == name){
                        info = cellNew[hash] = $.extend({}, n);
                        return false;
                    }
                };

                if($.type(cellInfos) == 'undefined'){
                    $.each($.MC.info.cell, function(i, n){
                        return setInfo(n);
                    });
                    if(!info && mainConfig.which == 'k'){
                        $.MC.info.layouts && $.each($.MC.info.layouts, function(i, n){
                            n['cells'] && $.each(n['cells'], function(ii, nn){
                                return setInfo(nn);
                            });
                        });
                    }
                }else{
                    cellInfos && $.each(cellInfos, function(i, n){
                        return setInfo(n);
                    });
                }
                return info;
            },

            refreshCell : function(hash, info, state){
                var html = info['static_html'] ? info['static_html'] : (info['cell_mode'] > 0 ? info['rended_html'] : info['cell_code']);
                var it = this.getContent();
                var before = it.find('.before-' + hash);
                var after = it.find('.after-' + hash);
                var next = before[0].nextSibling;
                while(next){
                    if(next == after[0]){
                        break;
                    }
                    var _next = next;
                    next = next.nextSibling;
                    _next.parentNode.removeChild(_next);
                }
                before.after(html);
                this.removeCSS(hash);
                this.removeJS(hash);
                this.addCSS(info['css'], hash);
                this.addJS(info['js'], hash);
                this.getCellsPosition();
            },

            refreshAll : function(){
                this.getCells();
                this.getCellsPosition();
            },

            hash : function(){
                return 'cell-' + $.makeHash();
            },

            addCSS : function(css, hash, parent){
                $.trim(css) && (parent || this.getContent().find('head')).append('<style type="text/css" ' + (hash ? 'hash="' + hash + '"' : '') + '>' + css + '</style>');
            },

            removeCSS : function(hash, parent){
                (parent || this.getContent().find('head')).find('style[hash="'+ hash +'"]').remove();
            },

            addJS : function(js, hash, parent, isSrc){
                if(!$.trim(js)) return;
                parent = (parent || this.getContent().find('body'));
                var _document = this.it[0].contentWindow.document;
                try{
                    var _script = _document.createElement('script');
                    parent[0].appendChild(_script);
                    if(isSrc){
                        _script.src = js;
                    }else{
                        _script.textContent = js;
                    }
                    _script.setAttribute('hash', hash);
                }catch(e){

                }
            },

            removeJS : function(hash, parent){
                (parent || this.getContent().find('body')).find('script[hash="'+ hash +'"]').remove();
            },

            mainId : '#m2o-main-box',
            addLayoutNew : function(layoutId, layoutInfo, index, hash){
                var mainBox = this.getMainBox();
                var currentLayout = index >= 1 ? $(layoutInfo['content']).insertAfter(this.getLayouts(mainBox).eq(index - 1)) : $(layoutInfo['content']).prependTo(mainBox);
                currentLayout.attr({
                    'data-id' : layoutId,
                    'data-hash' : hash
                });
                this.addCSS(layoutInfo['layout_css'], hash);
                this.getCells(currentLayout, layoutInfo['cells']);
                return currentLayout;
            },

            removeLayoutNew : function(hash){
                var currentLayout = this.getLayout(hash);
                this.removeCSS(hash);
                currentLayout.remove();
            },

            refreshLayoutNew : function(hashs){
                var mainBox = this.getMainBox();
                var children = this.getLayouts(mainBox);
                var _this = this;
                $.each(hashs, function(i, hash){
                    _this.getLayout(hash, children).appendTo(mainBox);
                });
            },

            getLayoutIds : function(){
                var ids = [];
                this.getLayouts().each(function(){
                    var id = $(this).attr('data-id');
                    id && ids.push(id);
                });
                return ids;
            },

            updateLayoutTitle : function(hash, titleHtml){
                var layout = this.getLayout(hash);
                var oldTitle = layout.find('.m2o-layout-title');
                if(oldTitle[0]){
                    oldTitle.replaceWith(titleHtml);
                }else{
                    $(titleHtml).prependTo(layout);
                }
                return layout;
            },

            getLayoutSetTitleMask : function(){
                var mainBox = this.getMainBox();
                var layouts = {};
                mainBox.children().each(function(){
                    var $this = $(this);
                    var hash = $this.data('hash');
                    var info = $this.offset();
                    $.extend(info, {
                        hash : hash,
                        id : $this.data('id'),
                        width : $this.outerWidth(),
                        height : $this.outerHeight()
                    });
                    layouts[hash] = info;
                });
                return layouts;
            },

            getMainBox : function(){
                return this.getContent().find(this.mainId);
            },

            getLayouts : function(mainBox){
                return (mainBox || this.getMainBox()).children();
            },

            getLayout : function(hash, layouts){
                return (layouts || this.getLayouts()).filter(function(){
                    return $(this).data('hash') == hash;
                });
            },



            getCss : function(){
                var head = this.getContent().find('head');
                var csses = [];
                head.find('link, style').each(function(){
                    var $this = $(this);
                    var tagName = $this.prop('tagName').toLowerCase();
                    if(tagName == 'link'){
                        csses.push({
                            type : 'link',
                            href : $this.prop('href')
                        });
                    }else if(tagName == 'style'){
                        csses.push({
                            type : 'style',
                            content : $this.html()
                        });
                    }
                });
                return csses;
            },

            empty : function(){

            }
        }
    }();
})(jQuery);


