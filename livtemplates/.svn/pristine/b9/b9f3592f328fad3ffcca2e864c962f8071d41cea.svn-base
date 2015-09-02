jQuery(function($){
    var appCacheState = (function(){
        var key = 'app-state';
        return {
            getCache : function(){
                return $.DOMCached.get(key);
            },
            setCache : function(value){
                $.DOMCached.set(key, value, false);
            }
        };
    })();


    var manager = (function(){
        var menuBox = $('.menu-box');
        var home = $('.menu-home');
        var homeTitle = $('.home-title');
        var app = $('.menu-app');
        var appTitle = $('.app-title');
        var appBox = $('.app-box');
        var indexBox = $('.index-box');
        var topBox = $('.top-box');
        var brigeBox = $('.brige-box');
        var appOptions = $('.app-options');
        return{
            menuBox : menuBox,
            home : home,
            homeTitle : homeTitle,
            app : app,
            appTitle : appTitle,
            appBox : appBox,
            indexBox : indexBox,
            topBox : topBox,
            brigeBox : brigeBox,
            appOptions : appOptions,


            isHome : true,
            isSet : false,

            amTime : 200
        };
    })();

    manager.home.on({
        click : function(){
            if(manager.isHome){
                if($('#mainwin').attr('src')){
                    $(this).triggerHandler('_close', [true]);
                }
                return false;
            }
            $(this).triggerHandler('_open');
        },

        _open : function(){
            $('body').triggerHandler('_loadimg', [false]);

            $(this).addClass('on');
            manager.isHome = true;
            manager.homeTitle.show();
            manager.appTitle.hide();
            manager.brigeBox.triggerHandler('_option', ['_open']);

            manager.topBox.animate({
                opacity : 0
            }, manager.amTime, function(){
                $('#mainwin').triggerHandler('_close');
                setTimeout(function(){
                    manager.topBox.hide();
                    var indexOptions = manager.appOptions.add(manager.indexBox).show();
                    setTimeout(function(){
                        indexOptions.animate({
                            opacity : 1
                        }, manager.amTime);
                    }, 10);

                }, 10);
            });

            $('.app-tag-' + ($('.app-cat').hasClass('fenye') ? 'fenye' : 'fenzu') + ' li:first').trigger('click', [true]);
            $(window).scrollTop(0);
        },

        _close : function(event, back){
            $(this).removeClass('on');
            manager.isHome = false;
            manager.homeTitle.hide();
            manager.appTitle.show();
            var opacitys = manager.appOptions.add(manager.indexBox);
            if(back){
                opacitys.animate({
                    opacity : 0
                }, manager.amTime, function(){
                    manager.brigeBox.triggerHandler('_option', ['_close']);
                    $(window).scrollTop(0);
                    setTimeout(function(){
                        opacitys.hide();
                        manager.topBox.show();
                        setTimeout(function(){
                            manager.topBox.animate({
                                opacity : 1
                            }, manager.amTime);
                        }, 10);
                    }, 10);
                });
            }else{
                opacitys.hide().css('opacity', 0);
                manager.topBox.css('opacity', 1).show();
                $('body').scrollTop(0);
                setTimeout(function(){
                    manager.brigeBox.triggerHandler('_option', ['_close']);
                }, 0);
            }
        }
    });

    manager.brigeBox.on({
        _option : function(event, option){
            $(this).triggerHandler(option);
        },

        _open : function(){
            manager.menuBox.addClass('fixed');
            $(this).show();
        },

        _close : function(){
            manager.menuBox.removeClass('fixed');
            $(this).hide();
        },

        _oapp : function(){
            $(this).addClass('brige-app');
        },

        _capp : function(){
            $(this).removeClass('brige-app');
        }
    });

    manager.app.on({
        click : function(){
            if(manager.isSet) return;
            $(this).triggerHandler($(this).hasClass('on') ? '_close' : '_open');
            return false;
        },

        _open : function(){
            $(this).addClass('on');
            $('.app-list').triggerHandler('show');
            $(document).triggerHandler('custom');
        },

        _close : function(event){
            $(this).removeClass('on');
            $('.app-list').triggerHandler('hide');
            $(document).triggerHandler('custom');
        }
    });


    (function(){
        var eachs = {};
        var clicking = false;

        var cat = $('.app-cat').on('click', 'span', function(){
            var type = $(this).attr('_type');
            $(this).parent().removeClass('fenye fenzu').addClass(type);
            appsParent.triggerHandler('_open', [type]);
            var ul = tag.find('ul').hide().filter(function(){
                return $(this).attr('_type') == type;
            }).show();
            ul.find('li:first').trigger('click', [true]);
            eachs['type'] = type;
            $('body').scrollTop(0);
        });

        var tag = $('.app-tag').on({
            click : function(event, noanimate){
                if($(this).hasClass('on')) return;
                var parent = $(this).parent();
                parent.find('li.on').removeClass('on');
                $(this).addClass('on');
                appsParent.triggerHandler('_move', [parent.attr('_type'), $(this).attr('_index'), noanimate]);
            }
        }, 'li');

        var appsParent = $('.index-inner').on({

            _open : function(event, which){
                $(this).find('.index-apps').hide().filter(function(){
                    return $(this).hasClass('index-apps-' + which);
                }).show();
            },

            _move : function(event, type, value, noanimate){

                var each = $(this).find('.app-each[_' + type + '="' + value + '"]');
                each.addClass('on').siblings('.on').removeClass('on');
                if(!noanimate){
                    clicking = true;
                    $('html, body').animate({
                        scrollTop : each.offset().top - $('.brige-box').height() - 50 + 'px'
                    }, manager.amTime, function(){
                        clicking = false;
                    });
                }
            }
        });

        cat.find('.app-cat-fenzu').click();


        $(window).scroll(function(){
            if(!manager.isHome || clicking) return;
            var type = eachs['type'];
            !eachs[type] && (eachs[type] = $('.index-apps-' + type).find('.app-each'));
            var scrollTop = $(this).scrollTop();
            var windowHeight = $(this).height();
            var height = manager.brigeBox.height();
            var min = scrollTop + height;
            var max = scrollTop + windowHeight / 2;
            var index = 0;
            if(scrollTop + windowHeight == $(document).height()){
                index = eachs[type].last().attr('_' + type);
            }else{
                eachs[type].each(function(){
                    var offset = $(this).offset();
                    if(min < offset.top && offset.top < max){
                        index = $(this).attr('_' + type);
                        return false;
                    }
                });
            }
            index && $('.app-tag-' + type + ' li[_index="' + index + '"]').trigger('click', [true]);
            return false;
        });

    })();

    (function(){
        var appList = $('.app-list').sortable({
            items : '.app-item:not(.app-kong)',
            disabled : true,

            start : function(){
                $(this).triggerHandler('disable-drop', [true]);
            },
            update : function(){
                $(this).triggerHandler('save');
            },
            stop : function(){
                $(this).triggerHandler('enable-drop', [true]);
            }
        });

        appList.on({
            'enable' : function(){
                $(this).addClass('app-list-edit').removeClass('app-list-normal');
                $(this).triggerHandler('enable-sort');
                $(this).triggerHandler('enable-drop');
            },

            'disable' : function(){
                $(this).removeClass('app-list-edit').addClass('app-list-normal');
                $(this).triggerHandler('disable-sort');
                $(this).triggerHandler('disable-drop');
            },

            'enable-sort' : function(){
                $(this).sortable('enable');
            },

            'disable-sort' : function(){
                $(this).sortable('disable');
            },

            'bind-drop' : function(){
                $(this).find('.app-item').filter(function(){
                    return !$(this).is(':ui-droppable');
                }).droppable({
                    accept : '.app-item',
                    activeClass : ' ',
                    drop : function(event, ui){
                        var helper = ui.helper;
                        if(helper.attr('id') != 'app-item-helper'){
                            return;
                        }

                        var color = $(this).attr('_color');
                        if(color){
                            $(this).removeClass('app-item-' + color);
                        }
                        color = helper.attr('_color');
                        var app = $(this).attr('_app');
                        if(app){
                            $(this).removeClass('app-' + app);
                        }
                        app = helper.attr('_app');
                        $(this).removeClass('app-kong')
                        .addClass('app-item-' + color)
                        .addClass('app-' + app)
                        .attr({
                            target : helper.attr('target'),
                            href : helper.attr('href'),
                            _color : color,
                            _app : app
                        }).html(helper.html());

                        appList.triggerHandler('save');
                    }
                });
            },

            'enable-drop' : function(event, kong){
                var itemClass = kong ? '.app-kong' : '.app-item';
                $(this).find(itemClass).droppable('enable');
            },

            'disable-drop' : function(event, kong){
                var itemClass = kong ? '.app-kong' : '.app-item';
                $(this).find(itemClass).droppable('disable');
            },

            'class-drop' : function(event, type, cname){
                $(this).find('.app-item')[type + 'Class'](cname);
            },

            'kong' : function(){
                $(this).append('<a href="javascript:;" class="app-item app-kong"></a>').triggerHandler('bind-drop');
            },

            'show' : function(){
                manager.appBox.removeClass('hidden');
                manager.brigeBox.triggerHandler('_option', '_oapp');
                !manager.isSet && appCacheState.setCache(1);
            },

            'hide' : function(event){
                manager.appBox.addClass('hidden');
                manager.brigeBox.triggerHandler('_option', '_capp');
                !manager.isSet && appCacheState.setCache(0);
            },

            save : function(){
                var appMenus = [];
                $(this).find('.app-item:not(.app-kong)').each(function(){
                    appMenus.push($(this).attr('_app'));
                });
                appMenus = appMenus.join(',');
                $.post(
                    'index.php?a=save_app_menus',
                    {app_menus : appMenus},
                    function(json){
                    }
                );
            }
        });

        appList.triggerHandler('bind-drop');
        appList.triggerHandler('disable-drop');

        appList.on({
            click : function(){
                var item = $(this).closest('.app-item');
                $(this).remove();
                item.animate({
                    width : 0
                }, manager.amTime / 2, function(){
                    $(this).droppable('destroy').remove();
                    appList.triggerHandler('kong');
                    appList.triggerHandler('save');
                });
                return false;
            }
        }, '.app-item-del');

        var dragItems = $('.index-apps .app-item').draggable({
            helper : function(){
                var color = $(this).attr('_color');
                var app = $(this).attr('_app');
                var target = $(this).attr('target');
                var href = $(this).attr('href');
                var html = $(this).html();
                return '<div id="app-item-helper" class="app-item app-item-' + color + ' app-' + app + '" _color="' + color + '" _app="' + app + '" target="' + target + '" href="' + href + '">' + html + '<span class="app-item-del">x</span></div>';

            },
            appendTo : 'body',
            zIndex : 100000,
            disabled : true,
            revert : 'invalid',
            revertDuration : 100,
            start : function(event, ui){
                appList.triggerHandler('disable-sort');
                appList.triggerHandler('class-drop', ['add', 'accept-shadow']);
            },

            stop : function(){
                appList.triggerHandler('enable-sort');
                appList.triggerHandler('class-drop', ['remove', 'accept-shadow']);
            }
        });

        dragItems.on({
            'enable' : function(){
                $(this).draggable('enable');
            },

            'disable' : function(){
                $(this).draggable('disable');
            }
        });

        $('.app-set').on({
            click : function(){
                $(this).triggerHandler($(this).hasClass('on') ? '_close' : '_open');
            },

            _open : function(){
                $(this).data('set-before', !!manager.app.hasClass('on'));
                manager.isSet = true;
                $(this).addClass('on');
                dragItems.trigger('enable');
                appList.triggerHandler('enable');
                //appList.triggerHandler('show');
                manager.app.triggerHandler('_open');

            },

            _close : function(){

                $(this).removeClass('on');
                dragItems.trigger('disable');
                appList.triggerHandler('disable');
                //appList.triggerHandler('hide');
                if(!$(this).data('set-before')){
                    manager.app.triggerHandler('_close');
                }
                manager.isSet = false;
                appList.triggerHandler('save');
            }
        });

    })();

    (function(){
        var state = appCacheState.getCache() || 0;
        if(state){
            manager.app.addClass('on');
            manager.appBox.removeClass('hidden');
            manager.brigeBox.triggerHandler('_option', '_oapp');
        }
    })();


    (function(){
        $('body').on({
            _loadimg : function(event, state){
                var topLoad = $('#top-loading');
                if(state){
                    topLoad.css({
                        top : 100 + $('.menu-box').outerHeight(true) + $(window).scrollTop() + 'px'
                    }).show();
                }else{
                    topLoad.hide();
                }
            }
        });

        $.configeHtml = {
            'back' : '<a class="back" href="{{href}}" target="{{target}}">返回</a>',
            'peizhi' : '<a class="gray mr10 set-button" href="run.php?mid={{mid}}&a=configuare&infrm=1" target="mainwin">配置</a>',
            'yuan' : '<a class="add-yuan-btn add-button news mr10" onclick="return false;" gmid="{{mid}}">添加源</a>',
            replace : function(tpl, data){
                return tpl.replace(/{{([a-z]*)}}/ig, function(all, match){
                    return $.type(data[match]) != 'undefined' ? data[match] : '';
                });
            },
            backHtml : function(href, target){
                return this.replace(this.back, {
                    href : href,
                    target : target || 'mainwin'
                });
            },
            peizhiHtml : function(mid){
                return this.replace(this.peizhi, {
                    mid : mid
                });
            },
            yuanHtml : function(mid){
                return this.replace(this.yuan, {
                    mid : mid
                });
            }
        };

        $.urlManager = {
            cache : [],
            add : function(urlObj){
                switch(urlObj['type']){
                    case 'nodeFrame' :
                        var last = this.fetch();
                        if(last && last['type'] == 'nodeFrame'){
                            this.pop();
                        }
                        break;
                    case 'mainwin' :
                        this.reset();
                        break;
                }
                //console.log( this.cache );
                if(!urlObj['needBack'] && !this.check(urlObj)) return;
                this.cache.push(urlObj);
                //console.log(  this.cache);
            },
            pop : function(){
                this.cache.pop();
            },
            fetch : function(){
                return this.cache.length ? this.cache[this.cache.length - 1] : null;
            },
            reset : function(){
                this.cache = [];
            },
            check : function(urlObj){
                var nowSrc = urlObj.nowSrc;
                var state = false;
                switch(urlObj['type']){
                    case 'nodeFrame' :
                        state = !!(/a=(form|get_app)/.test(nowSrc));
                        break;
                    case 'mainwin' :
                        state = !!(/a=(configuare|relate_module_show|form)|needback=true/.test(nowSrc));
                        break;
                }
                return state;
            },
            len : function(){
                return this.cache.length;
            }
        };

        var mainwin = $('#mainwin');
        mainwin.on({
            _load : function(){
                var _this = $(this);
                var content = _this.contents();
                $('html').scrollTop(0);
                _this.triggerHandler('_height', [content]);
                //var body = content.find('body').on({
                this.contentWindow.$('body').on({
                    click : function(){
                        if($(this).hasClass('back')){
                            $.urlManager.pop();
                        }
                        var targetName = $(this).attr('target');
                        var target = $('#' + targetName);
                        //!target[0] && (target = content.find('#' + targetName));
                        var $$ = this.ownerDocument.defaultView.jQuery;
                        !target[0] && (target = $$('#' + targetName));
                        var href = $(this).attr('href');
                        if((href || '').indexOf('http:') == 0){
                            $.outerIframe.start(href);
                            return false;
                        }
                        var needBack = $.type($(this).attr('need-back')) != 'undefined';
                        if(!href || href.indexOf('javascript') == 0){
                            return;
                        }
                        target.triggerHandler('_go', [href,0,needBack]);
                        return false;
                    }
                }, 'a[target="mainwin"], a[target="formwin"], a[target="nodeFrame"]');

                _this.triggerHandler('_otherOption', [content]);
                _this.triggerHandler('_checkBack', [content]);
                _this.triggerHandler('_checkNoNodeFrame', [content]);
                _this.triggerHandler('_show');

                _this.triggerHandler('_someEvent', [content]);

                //var nodeWin = content.find('#nodeFrame');
                var nodeWin = this.contentWindow.$('#nodeFrame');
                if(nodeWin[0]){
                    nodeWin.on({
                        _load : function(){
                            var $ = this.ownerDocument.defaultView.jQuery;
                            var _this = $(this);
                            var content = _this.contents();
                            _this.triggerHandler('_loadimg', [false]);

                            //content.find('body').on({
                            this.contentWindow.$('body').on({
                                click : function(event){
                                    var selfWindow = this.ownerDocument.defaultView;
                                    var $ = top.$;
                                    if($.type($(this).attr('go-blank')) != 'undefined'){
                                        event.stopImmediatePropagation();
                                        return;
                                    }
                                    var href = $(this).attr('href');
                                    if(href){
                                        if(href.indexOf('http://') != -1 && !$(this).data('notouteriframe')){
                                            $.outerIframe.start(href);
                                            return false;
                                        }
                                        if(href.indexOf('magic/main.php') != -1){
                                            event.stopImmediatePropagation();
                                            return;
                                        }
                                    }
                                    if(/(\.html?)$/.test($(this).attr('id') || '')
                                        || ($(this).hasClass('shareslt') && $(this).text() == '浏览')
                                        || ($(this).attr('href') == 'magic_view.php')
                                        || /redirect\.php\?/.test($(this).attr('href'))
                                        || ($(this).closest('#record-edit').length && new RegExp(['签发','分享','专题','区块','移动','审核','打回','下载','技审','删除'].join('|')).test($.trim($(this).text()) || ''))
                                    ){
                                        //这边先这样处理
                                        return;
                                    }
                                    if($.trim($(this).attr('onclick'))){
                                        return;
                                    }
                                    var targetName = $(this).attr('target');
                                    if(!targetName || targetName == '_blank'){
                                        targetName = 'nodeFrame';
                                    }
                                    if($.inArray(targetName, ['mainwin', 'formwin', 'nodeFrame']) == -1){
                                        targetName = 'nodeFrame';
                                    }
                                    var target = $('#' + targetName);
                                    //!target[0] && (target = $('#mainwin').contents().find('#' + targetName));
                                    !target[0] && (target = selfWindow.parent.$('#' + targetName));
                                    var href = $(this).attr('href');
                                    if(!href || href.indexOf('javascript') == 0 || href.substr(0, 1) == '#'){
                                        return;
                                    }
                                    var needBack = $.type($(this).attr('need-back')) != 'undefined';
                                    target.triggerHandler('_go', [href, targetName == 'nodeFrame' ? 500 : 0, needBack]);
                                    return false;
                                }
                            }, 'a'/*'a[target="mainwin"], a[target="formwin"], a[target="nodeFrame"]'*/);

                            _this.triggerHandler('_checkBack', [content]);
                        },

                        _go : function(event, src, delay, needBack){
                            var $ = this.ownerDocument.defaultView.jQuery;
                            var _this = $(this);
                            _this.triggerHandler('_loadimg', [true]);
                            src.substr(0, 1) == '?' && (src = './run.php' + src);

                            top.$.urlManager.add({
                                lastSrc : _this.attr('src'),
                                nowSrc : src,
                                type : 'nodeFrame',
                                needBack : needBack
                            });
                            setTimeout(function(){
                                _this.attr('src', src);
                            }, delay || 0);
                        },

                        _loadimg : function(event, state){
                            top.$('body').triggerHandler('_loadimg', [state]);
                        },

                        _checkBack : function(event, content){

                            var hgMenu = content.find('#hg_page_menu');
                            var pMenu = top.$('#mainwin').contents().find('#hg_parent_page_menu');
                            pMenu.children().not('.gray').remove();
                            pMenu.prepend(hgMenu[0] ? hgMenu.find('.gray').remove() && hgMenu.html().replace(/onclick=\"/ig, 'onclick="nodeFrame.') : '');
                            pMenu.find('a:not(.gray)').attr('target', function(){
                                var target = $(this).attr('target');
                                if(!target || target == '_blank'){
                                    target = 'nodeFrame';
                                }
                                return target;
                            });
                            //pMenu.prepend($.configeHtml.yuanHtml(pMenu[0].ownerDocument.defaultView.gMid));
                            var urlObj = top.$.urlManager.fetch();
                            if(urlObj){
                                if(urlObj && urlObj['type'] == 'nodeFrame'){
                                    pMenu.find('.gray').remove();
                                }
                                $(top.$.configeHtml.backHtml(urlObj['lastSrc'], urlObj['type'])).appendTo(pMenu);
                            }
                            if(!top.$.urlManager.len()){
                                top.$('#mainwin').triggerHandler('_otherOption');
                            }
                        },

                        _height : function(event, content){
                            var $ = this.ownerDocument.defaultView.jQuery;
                            var _this = $(this);
                            content = content || _this.contents();
                            if(!content.length) return;
                            var height = Math.max(content.find('html').height(), content.find('body').height());
                            _this.parent().add(_this).height(height);
                        }
                    });

                    nodeWin.triggerHandler('_go', [nodeWin.attr('_src')]);
                }
            },

            _go : function(event, src, delay, needBack){
                $(window).off('scroll.plan');
                var _this = $(this);
                _this.triggerHandler('_hide');
                src.substr(0, 1) == '?' && (src = './run.php' + src);
                $.urlManager.add({
                    lastSrc : _this.attr('src'),
                    nowSrc : src,
                    type : 'mainwin',
                    needBack : needBack
                });
                setTimeout(function(){
                    _this.attr('src', src);
                }, delay || 0);
            },

            _loadimg : function(event, state){
                top.$('body').triggerHandler('_loadimg', [state]);
            },

            _show : function(){
                $(this).show().triggerHandler('_loadimg', [false]);
            },

            _hide : function(){
                $(this).hide().triggerHandler('_loadimg', [true]);
            },

            _close : function(){
                $(this).attr('src', '');
            },

            _parent : function(event, state){
                $(this).closest('.top-box')[state]();
            },

            _height : function(event, content){
                var _this = $(this);
                content = content || _this.contents();
                if(!content.length) return;
                var height = Math.max(content.find('html').height(), content.find('body').height());
                _this.parent().add(_this).height(height);
            },

            _otherOption : function(event, content){
                var _this = $(this);
                content = content || _this.contents();
                var pMenu = content.find('#hg_parent_page_menu');
                var _gMid = content[0].defaultView.gMid;
                if(_gMid > 0 && !pMenu.find('.gray')[0]){
                	var app_unique_id = top.$.globalData.get('mid_to_app_uniqueid')[_gMid];
                	if (top.$.globalData.get('setting_prms')[app_unique_id]
                		|| gAdmin.group_type <= top.$.globalData.get('MAX_ADMIN_TYPE')) {
                    	pMenu.append($.configeHtml.peizhiHtml(_gMid));
                    }
                }
            },

            _checkBack : function(event, content){
                var urlObj = $.urlManager.fetch();
                if(!urlObj) return;
                (content || $(this).contents()).find('#hg_parent_page_menu').html($.configeHtml.backHtml(urlObj['lastSrc']));
            },

            _checkNoNodeFrame : function(event, content){
                content = content || $(this).contents();
                if(!content.find('iframe#nodeFrame').length){
                    var searchForm = content.find('#info_list_search'),
                    	box = content.find('#hg_info_list_search'),
                    	controllArea = content.find('#hg_parent_page_menu');
                    searchForm.length && box.html(searchForm.html());
                    if( box ){
                    	var search_area = box.find( '.text-search' ),
                    		search_key = search_area.find( 'input[type="text"]' ),
                    		serach_btn = box.find( 'input[name="hg_search"]' ),
                    		button = box.find('.serach-btn');
                    	/*此处用二级里面的$去选取二级页面里面的元素，避免浏览器升级后直接用一级里面的$报错*/
                    	var $$ = this.contentWindow.$;
                        var	autoitem = $$('#hg_info_list_search').find( '.autocomplete' );
                    	var wrap = $('<div class="key-search"></div>');
                    	autoitem.length && autoitem.autocompleteResult(); //初始化autocomplete
                    	wrap.append(search_key).append(serach_btn).prependTo(box.find('form'));
                    	button.click(function () {
                			var open = $(this).data('open');
                			$(this).data('open', !open);
                			wrap[open ? 'removeClass' : 'addClass']('key-search-open');
                		});
                    	if( $.trim( search_key.val() ) ){
                    		button.trigger( 'click' );
                    	}
                    	box.find('.colonm.down_list').deferHover();
                    }
                    if( controllArea.length ){
                    	controllArea.prepend( content.find('#hg_page_menu').html() );
                    }
                }
            },

            _someEvent : function(event, content){
                var $ = this.contentWindow.$;
                content.on({
                    click : function(){
                        var $this = $(this);
                        var gMid = $this.attr('gmid');
                        var getScript = $this.data('getScript');
                        if(getScript == 'ing'){
                            return false;
                        }
                        if(getScript == 'end'){
                            //此处重新实力话
                        	$('.cloud-pop-box').pubLib('show');
                            return false;
                        }
                        $this.data('getScript', 'ing');
                        $.getScript(SCRIPT_URL + '2013/cloud_pop.js', function(){
                        	$.cloud_pop({
                        		className : 'cloud-pop-box',
                        		widget : 'pubLib',
                        		css : {
                        			top : '66px',
                        			'margin-top' : 0
                        		}
                        	});
                            $this.data('getScript', 'end');
                        });
                        return false;
                    }
                }, '.add-yuan-btn');
            }
        });

        $.myScrollBar = (function(){
            var div = $('<div/>').css({
                position : 'absolute',
                left : 0,
                top : '-1000px',
                width : '100px',
                height : '40px',
                overflow : 'scroll'
            }).appendTo('body').html(new Array(200).join('张飞虎'));
            var barWidth = 100 - div[0].clientWidth;
            div.css('overflow-y', 'hidden');
            var barHeight = 40 - div[0].clientHeight;
            div.remove();
            return {
                width : barWidth,
                height : barHeight
            };
        })();
        var formwin = $('#formwin');
        formwin.on({
            _load : function(){
                var _this = $(this);

                if(_this.data('direct')){
                    _this.data('direct', false);
                    return;
                }
                if(!_this.attr('src')) return;

                var formGo = _this.data('from-go');
                _this.triggerHandler('_scrollOpacity', [0, function(){
                    _this.triggerHandler('_overflow', [true]);
                    var win = $(window);
                    var width = win.width();
                    var height = win.height();
                    var css = {
                        'opacity' : formGo ? 0 : 1,
                        'height' : height + 'px',
                        'width' : width + 'px'
                    };
                    _this.css(css).show();
                    formGo && setTimeout(function(){
                        _this.animate({
                            opacity : 1
                        }, manager.amTime);
                    }, 1);
                }]);
                if(formGo){
                    _this.triggerHandler('_loadimg', [false]);
                    _this.data('from-go', false);
                }
                //$(this).contents().find('.option-iframe-back').click(function(){
                this.contentWindow.$('.option-iframe-back').click(function(){
                    $(parent.document).find('#formwin').triggerHandler('_close');
                });
            },

            _open : function(){

            },

            _close : function(){
                var _this = $(this);
                _this.triggerHandler('_overflow', [false]);
                _this.triggerHandler('_scrollOpacity', [1]);
                _this.animate({
                    opacity : 0
                }, manager.amTime, function(){
                    _this.hide().attr('src', '').data('direct', false);
                });
            },

            _go : function(event, src, delay){
                var _this = $(this);
                _this.triggerHandler('_open');
                _this.triggerHandler('_loadimg', [true]);
                src.substr(0, 1) == '?' && (src = './run.php' + src);
                setTimeout(function(){
                    _this.attr('src', src);
                }, delay || 0);
                _this.data('from-go', true);
            },

            _loadimg : function(event, state){
                top.$('body').triggerHandler('_loadimg', [state]);
            },

            _scrollOpacity : function(event, state, callback){
                $('#scroll-box').animate({
                    opacity : state ? 1 : 0
                }, manager.amTime / 2, function(){
                    callback && callback();
                });
            },

            _overflow : function(event, state){
                var css = state ? {
                    overflow : 'hidden',
                    height : $(window).height() + 'px'
                } : {
                    'overflow-y' : 'scroll',
                    'overflow-x' : 'hidden',
                    height : 'auto'
                };
                $('html').css(css);
            },

            _height : function(event, content){
                var _this = $(this);
                content = content || _this.contents();
                var height = content.find('body').outerHeight(true);
                _this.height(height);
            }
        });

        setInterval(function(){
            if(mainwin.attr('src')){
                mainwin.triggerHandler('_height');
                if(mainwin[0].contentWindow && mainwin[0].contentWindow.$){
                    var nodeFrame = mainwin[0].contentWindow.$('#nodeFrame');
                    if(nodeFrame[0]){
                        if((nodeFrame.attr('href') || '').indexOf('http:') == 0){
                            return;
                        }
                        nodeFrame.triggerHandler('_height');
                    }
                }
            }
        }, 50);
    })();

    $.outerIframe = function(src){
        $('body').on({
            click : function(event){
                $.outerIframe.end();
            }
        }, '#outer-iframe-closebtn');

        return {
            iframe : null,
            btn : null,
            init : function(){
                if(!this.iframe){
                    var ind = 10000001;
                    this.iframe = $('<iframe id="outer-iframe" frameborder="0"></iframe>').appendTo('body').css({
                        position : 'fixed',
                        left : 0,
                        right : 0,
                        top : 0,
                        bottom : 0,
                        'z-index' : ind,
                        background : '#fff',
                        width : '100%',
                        height : '100%'
                    }).on({
                        load : function(){
                            $('body').triggerHandler('_loadimg', [false]);
                        }
                    });

                    this.btn = $('<span id="outer-iframe-closebtn">x</span>').appendTo('body').css({
                        position : 'fixed',
                        'z-index' : ind + 1,
                        right : '20px',
                        top : '20px',
                        width : '34px',
                        height : '34px',
                        'line-height' : '30px',
                        cursor : 'pointer',
                        'border-radius' : '2px',
                        background : '#ccc',
                        color : '#fff',
                        'font-size' : '30px',
                        'font-weight' : 'bold',
                        'text-align' : 'center'
                    });
                }
            },

            csrc : function(src){
                return src + (src.indexOf('?') == -1 ? '?' : '&') + 'access_token=' + ACCESS_TOKEN;
            },

            start : function(src){
                this.init();
                $('body').triggerHandler('_loadimg', [true]);
                $('#formwin').triggerHandler('_overflow', [true]);
                this.iframe.attr('src', this.csrc(src)).fadeIn();
                this.btn.show();
            },

            end : function(){
                $('#formwin').triggerHandler('_overflow', [false]);
                this.iframe.attr('src', '').fadeOut();
                this.btn.hide();
            }
        };
    }();

    $('body').on({
        click : function(){
            if(manager.isSet){
                return false;
            }
            var href = $(this).attr('href');
            if(href == 'javascript:;'){
                return;
            }
            if(href.indexOf('http') == 0) {
                //if (href.indexOf(location.host) == -1) {
                    $.outerIframe.start(href);
                    return false;
                //}
            }
            if($(this).parent()[0] != manager.appTitle[0]){
                var name, href, target;
                name = $(this).attr('_selfname');
                if(name){
                    href = $(this).attr('href');
                    target = $(this).attr('target');
                }else{
                    var nameSpan = $(this).find('.app-item-name');
                    name = nameSpan.html();
                    var parent = nameSpan.parent();
                    href = parent.attr('href');
                    target = parent.attr('target');
                }
                manager.appTitle.html('<a href="' + href + '" target="' + target + '">' + name + '</a>');
            }
            manager.home.triggerHandler('_close');
            $('#mainwin').triggerHandler('_go', [href, 0/*manager.amTime*/]);
            return false;
        }
    }, 'a[target="mainwin"]');

    $('body').on({
        click : function(){
            if(manager.isSet){
                return false;
            }
            var href = $(this).attr('href');
            $('#formwin').triggerHandler('_go', [href, manager.amTime]);
        }
    }, 'a[target="formwin"]');


    $.closeFormWin = function(){
        try{
            var mainwin = $('#mainwin'),
            	node = mainwin[0].contentWindow.$('#nodeFrame');
            if(node[0]){
                //node.triggerHandler('_go', [node.prop('src')]);
                node[0].contentWindow.location.reload();
            }else{
            	mainwin.length && mainwin[0].contentWindow.location.reload();
            }
            $.myDelay(function(){
                $('#formwin').data('direct', true).triggerHandler('_close');
            }, 1000);
        }catch(e){}
    }


    $.myDelay = function(func, time){
        var arg = [].slice.call(arguments, 2);
        if(time <= 0) return func(arg);
        setTimeout(function(){
           func(arg);
        }, parseFloat(time) || 50);
    }

    $('.menu-logo').on({
        click : function(){
            if(manager.isHome){
                return;
            }
            manager.home.triggerHandler('_open');
            return false;
        }
    });

    $('.app-publishsys').attr('href', function(){
        return $(this).attr('href').replace('a=frame', 'a=show');
    });
});

(function($){
    return;

    $.event.special.myevent = {
        setup : function(data, namespace){
            $(this).css('background', 'red');
            console.log('setup', arguments);
        },

        teardown : function(data, namespace){
            $(this).css('background', 'transparent');
            console.log('teardown', arguments);
        },

        add : function(handleObj){
            console.log('add', handleObj);
        },

        remove : function(handleObj){
            console.log('remove', handleObj);
        },

        _default : function(event){
            console.log('_default', event);
        }
    }

    $(function($){
        $('.menu-logo').delay(5000).animate({
            opacity : 0
        }, 100);
        $('.tag-span:eq(0)').on('myevent', function(){}).trigger('myevent')//.off('myevent');
    });
})(jQuery);

$(function($){
	$('body').toTop();
});