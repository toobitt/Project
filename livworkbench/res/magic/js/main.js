(function($){

    $.iframe && $.extend($.iframe, {
        xiufuIframe : function(){
            $.MC.dylist.dylist({
                tpl : $('#dylist-tpl').html()
            }).show();

            $.MC.body.formatGSS();
        }
    });

    $(function($){

        //全局的manage center 简称 MC
        $.MC = {
            it : $('#iframe'),
            mb : $('#mask-box'),
            db : $('#mode-box'),
            pb : $('#property-box'),

            columnBox : $('#column-box'),
            picBox : $('#pic-box'),
            bgpicBox : $('#bgpic-box'),
            gjCssBox : $('#gjcss-box'),

            qhcolumnBox : $('#qhcolumn-box'),
            pppBox : $('#pagepp-box'),

            gw : $('#magic-watch'),
            info : null,

            sb : $('#cell-static-box'),

            dylist : $('#dylist-box'),

            body : $('body'),

            loaded : false
        };

        //首先是search_cell  ajax请求
        $.globalAjax(window, function(){
            return $.getJSON(
                mainConfig.search || '',
                function(json){
                    $.MC.loaded = true;

                    json = json[0];
                    if(!json['cell']) return;
                    $.MC.info = json;
                    $.MC.info.cellNew = {};
                    $.iframe.init();
                    $.MC.db.mode({
                        cats : $.MC.info.mode_sort,
                        modes : $.MC.info.cell_mode,
                        'cat-tpl' : $('#mode-cat-tpl').html(),
                        'list-tpl' : $('#mode-item-tpl').html()
                    });
                    $.MC.pb.property({
                        propertys : $.MC.info.data_source,
                        pitpl : $('#property-item-tpl').html(),
                        pstpl : $('#property-source-tpl').html(),
                        jstpl : $('#property-item-tpl').html(),
                        csstpl : $('#property-css-tpl').html(),
                        typetpl : $('#property-type-tpl').html()
                    });

                    /*mainConfig['which'] != 'k' && $.MC.qhcolumnBox.qhcolumn({
                        url : 'plug.php?a=change_column',
                        siteid : json['site_id'],
                        pageid : json['page_id'],
                        dataid : json['page_data_id'],
                        'item-tpl' : $('#qhcolumn-item-tpl').html(),
                        'page-tpl' : $('#qhcolumn-page-tpl').html()
                    });*/

                    //以下全是一些配置功能
                    $.pppConfig = {

                    };


                    $.columnConfig = {
                        url : '../fetch_column_node.php?siteid=' + json['site_id'] + '&ban=1',
                        'name-url' : './plug.php?a=get_column_path',
                        tpl : $('#column-level-tpl').html(),
                        'result-item-tpl' : $('#column-result-item-tpl').html()
                    };
                    if(mainConfig['which'] == 'k'){
                        $.extend($.columnConfig, {
                            'url' : './plug.php?a=get_special_column&special_id=' + json['special_id'],
                            'name-url' : './plug.php?a=get_special_column_path'
                        });

                        $.layoutTitleConfig = {
                            url : './plug.php?a=get_special_column&special_id=' + json['special_id'],
                            tpl : $('#column-title-item-tpl').html()
                        }
                        $.MC.ltb.columnTitle($.layoutTitleConfig);
                    }

                    $.picConfig = {
                        url : './plug.php?a=get_icons',
                        'upload-url' : './plug.php?a=upload_icon',
                        'upload-phpkey' : 'Filedata',
                        'page-num' : 9,
                        tpl : $('#pic-item-tpl').html()
                    };

                    $.bgpicConfig = $.extend({}, $.picConfig, {
                        'page-num' : 11
                    });

                    $.gjcssConfig = {
                        url : './plug.php?a=get_icons'
                    };
                }
            );
        });

        $.MC.sb.cellStatic({
            textarea : 'cell-static-content'
        });


        (function(){
            //查看html css js功能
            var gw = $.MC.gw.on({

                reset : function(){
                    $(this).find('.gw-title.on').removeClass('on');
                    $(this).triggerHandler('open');
                },

                open : function(){
                    $(this).show().find('.gw-title:first').trigger('click');
                },

                close : function(){
                    $(this).hide();
                },

                val : function(event, type, val){
                    $(this).find('.gw-' + type).val(val);
                },

                html : function(event, type, html){
                    var which = $(this).find('.gw-' + type);
                    var editor = which.data('ace');
                    if(!editor){
                        editor = ace.edit(which[0]);
                        editor.setTheme("ace/theme/github");
                        editor.getSession().setMode("ace/mode/" + (type == 'js' ? 'javascript' : type));
                        editor.setReadOnly(true);
                        which.data('ace', editor);
                    }
                    /*if(type == 'html'){
                        html = $.replaceST.S(html);
                    }*/
                    editor.setValue(html);
                }
            });

            gw.on({
                click : function(){
                    $.MC.gw.triggerHandler('close');
                }
            }, '.gw-close');

            gw.on({
                click : function(){
                    var $this = $(this);
                    if($this.hasClass('on')) return;
                    $this.addClass('on').siblings().removeClass('on');
                    var current = $this.parent().next().find('.gw-' + $this.data('type')).show();
                    current.data('ace').focus();
                    current.siblings().hide();
                }
            }, '.gw-title');

        })();

        (function(){
            //窗口获得焦点功能
            (function(){
                var focused = true;
                setInterval(function(){
                    if(!focused){
                        return;
                    }
                    window.focus();
                }, 100);

                window.onfocus = function(){
                    focused = true;
                }
                window.onblur = function(){
                    focused = false;
                }
            })();



            $.gtOption = function(){
                var currentWhich = mainConfig['which'];

                var optionMap = {
                    dy : ['m', 'k', 'p', 'b'],
                    dys : ['m', 'k', 'p', 'b'],
                    bj : ['k'],
                    bjm : ['k'],
                    ys : ['m', 'p', 'b'],
                    yl : ['m', 'k', 'p'],
                    qh : ['m', 'p'],
                    ym : ['k'],
                    fb : ['m', 'k'],
                    css : ['m', 'k'],
                    sde : ['m', 'k'],
                    bde : ['m', 'k']
                };

                var checkOption = function(btn){
                    return $.inArray(currentWhich, optionMap[btn]) != -1;
                };

                var options =  {
                    //单元显示隐藏
                    dy : function(self){
                        if(!checkOption('dy')) return;
                        var $this = $(self || '#xianyin-btn');
                        var state = $.MC.mb.css('display') == 'none';
                        $.MC.mb[state ? 'show' : 'hide']();
                        $this.html(state ? $this.data('close') : $this.data('open'));
                        return false;
                    },

                    //单元刷新
                    dys : function(self){
                        if(!checkOption('dys')) return;

                        var mc = $.MC;
                        if(!mc.loaded) return;
                        if(mc.refreshTimer){
                            clearTimeout(mc.refreshTimer);
                            mc.refreshTimer = null;
                        }
                        var cb = $.globalLoad(self || '#shuaxin-btn');
                        mc.refreshTimer = setTimeout(function(){
                            cb();
                            $.iframe.refreshAll();
                        }, 100);
                        return false;
                    },

                    //布局
                    bj : function(){
                        if(!checkOption('bj')) return;

                        $.MC.lb.layout('btnClick');
                        return false;
                    },

                    //布局标题
                    bjm : function(self){
                        if(!checkOption('bjm')) return;

                        $.MC.lbm.layoutMask('bjm');
                        $(self || '.gt-item[data-type="bjm"]').css('background', function(){
                            var open = !$(this).data('open');
                            var color;
                            if(!open){
                                color = 'transparent';
                            }else{
                                color = '#88d14b';
                            }
                            $(this).data('open', open);
                            return color;
                        });
                        return false;
                    },

                    //样式列表显示隐藏
                    ys : function(){
                        if(!checkOption('ys')) return;

                        if($('#property-box').hasClass('open')){
                            return false;
                        }
                        var mBox = $('#mode-box');
                        mBox[mBox.hasClass('open') ? 'removeClass' : 'addClass']('open');
                        return false;
                    },

                    //样式上一页和下一页功能
                    yspn : function(code){
                        if(!checkOption('yspn')) return;

                        var modeBox = $.MC.db;
                        if(!modeBox.is(':visible')){
                            return false;
                        }
                        modeBox.mode('clickPN', code - 49);
                        return false;
                    },

                    //预览
                    yl : function(self){
                        if(!checkOption('yl')) return;

                        if(!$.MC.loaded) return;
                        var a = $(self || '#yulan-btn').find('a');
                        if(!a.attr('href')){
                            var info = $.MC.info;
                            a.attr('href', './magic.php?a=preview&ispreset='+ info['ispreset'] +'&site_id='+ info['site_id'] +'&page_id='+ info['page_id'] +'&page_data_id='+ info['page_data_id'] +'&content_type='+ info['content_type'] + '&template_id=' + info['template_id'] + '&uniqueid=' + info['uniqueid']);
                            a.click(function(event){
                                event.stopPropagation();
                            });
                        }
                        a[0].click();
                        return false;
                    },

                    //切换栏目功能
                    qh : function(){
                        if(!checkOption('qh')) return;

                        $.MC.qhcolumnBox.qhcolumn('onoff');
                        return false;
                    },

                    //页面属性设置功能
                    ym : function(){
                        if(!checkOption('ym')) return;

                        var box = $.MC.pppBox;
                        if(!box.is(':magic-pagepp')){
                            box.pagepp($.pppConfig);
                        }
                        box.pagepp('SC');
                        return false;
                    },

                    //发布页面功能
                    fb : function(self){
                        if(!checkOption('fb')) return;

                        if(!$.MC.loaded) return;
                        var $this = $(self || '#make-btn');
                        var info = $.MC.info;
                        $.globalAjax(this, function(){
                            return $.getJSON(
                                './mk_cache.php?site_id='+ info['site_id'] +'&page_id='+ info['page_id'] +'&page_data_id='+ info['page_data_id'] +'&content_type='+ info['content_type'] + '&client_type=' + info['client_type'],
                                function(json){
                                    $this.myTip({
                                        string : '发布页面成功！',
                                        delay : 1500,
                                        dtop : 50
                                    });
                                }
                            );
                        });
                        return false;
                    },

                    css : function(){
                        if(!checkOption('css')) return;

                        if(!$.MC.loaded) return;
                        var box = $.MC.reslink = $.MC.reslink || $('#reslink-box');
                        if(!box.is(':magic-reslink')){
                            box.reslink({
                                url : 'plug.php?a=getTemplateFile&site_id=' + $.MC.info.site_id + '&template_sign=' + $.MC.info.template_sign,
                                contentUrl : 'plug.php?a=getTemplateFileInfo',
                                updateContentUrl : 'plug.php?a=updateTemplateFileInfo',
                                templateUrl : 'plug.php?a=getTemplate',
                                checkTemplateUrl : 'plug.php?a=checkTemplate',
                                updateTemplateUrl : 'plug.php?a=updateTemplate',
                                tpl : $('#reslink-item-tpl').html()
                            });
                        }
                        box.reslink('show');
                    },

                    sde : function(){
                        if(!checkOption('sde')) return;
                        location.href = location.href.replace('main.php', 'data.php');
                    },

                    bde : function(){
                        if(!checkOption('bde')) return;
                        location.href = location.href.replace('main.php', 'block.php');
                    }
                };

                var tipBox = $('#global-btns');
                /*tipBox.find('.gt-inner').css('width', function(){
                    var w = 0;
                    $(this).find('.gt-item').each(function(){
                        w += $(this).outerWidth(true);
                    });
                    return w + 'px';
                }); */

                tipBox.on({
                    click : function(event){
                        var type = $(this).data('type');
                        if(!type) return;
                        options[type].call(this, this);
                    }
                }, 'li > div');

                /*tipBox.on({
                    click : function(){
                        tipBox[tipBox.hasClass('close') ? 'removeClass' : 'addClass']('close');
                    }
                }, '.gt-option');*/

                return options;
            }();

            //文档按键功能
            $(document).on({
                keydown : function(event){
                    if(event.ctrlKey || event.metaKey){

                        if(!$.MC.loaded) return;

                        var code = event.keyCode;
                        if(code == 72){
                            //显示和隐藏单元
                            return $.gtOption.dy();
                        }else if(code == 83){
                            //刷新单元定位，为了tab切换
                            return $.gtOption.dys();
                        }else if(code == 68){
                            //布局打开和关闭
                            return $.gtOption.bj();
                        }else if(code == 70){
                            //样式框打开和关闭
                            return $.gtOption.ys();
                        }else if(code == 49 || code == 50){
                            //样式上一页，下一页
                            return $.gtOption.yspn(code);
                        }else if(code == 71){
                            //预览
                            return $.gtOption.yl();
                        }else if(code == 69){
                            //切换页面
                            return $.gtOption.qh();
                        }else if(code == 89){
                            //页面属性
                            return $.gtOption.ym();
                        }

                    }
                }
            });
        })();

    });
})(jQuery);


