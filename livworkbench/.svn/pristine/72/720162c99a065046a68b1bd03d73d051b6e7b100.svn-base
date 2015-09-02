(function($){
    $.widget('magic.property', {
        options : {
            propertys : null,
            pitpl : null,
            piname : 'pi-template',
            pstpl : null,
            psname : 'ps-template',
            jstpl : null,
            jsname : 'js-template',
            csstpl : null,
            cssname : 'css-template',
            typetpl : null,
            typename : 'type-template',
            on : 'open'
        },

        _create : function(){
            this.state = false;
            $.template(this.options.piname, this.options.pitpl);
            $.template(this.options.psname, this.options.pstpl);
            $.template(this.options.jsname, this.options.jstpl);
            $.template(this.options.cssname, this.options.csstpl);
            $.template(this.options.typename, this.options.typetpl);
            this.main = this.element.find('.py-main');
            this.body = this.element.find('.py-body');
            this.content = this.element.find('.py-items');
            this.pmcontent = this.content.find('.py-mode');
            this.pscontent = this.content.find('.py-source');
            this.jscontent = this.content.find('.py-js');
            this.csscontent = this.content.find('.py-css');
            this.tab = this.content.find('.py-tab');
            this.btns = this.element.find('.py-btns');
            this.headWhich = this.element.find('.py-which');
            this.tip = this.element.find('.py-tip');
            this.dbox = this.element.find('.py-dbox');
            this.huifu = this.element.find('.py-huifu');
            this.staticTip = this.element.find('.py-static-box');

            this.inner = this.element.find('.py-inner');

            var types = {};
            $.MC.info['cell_type'] && $.each($.MC.info['cell_type'], function(i, n){
                types[i] = n;
            });
            $.tmpl(this.options.typename, types).appendTo(this.element.find('.py-type-box'));
            this.type = this.element.find('.py-type');

            var ps = this.propertys = {};
            var source = this.source = {
                '0' : '无'
            };
            $.each(this.options.propertys, function(i, n){
                ps[n['id']] = i;
                source[n['id']] = n['name'];
            });
        },

        getProperty : function(id){
            return this.options.propertys[this.propertys[id]];
        },

        _init : function(){
            this._on({
                //'click .py-change' : '_close',
                'click .py-which span' : '_close',
                'change #source' : '_changeSource',
                'click .py-submit' : '_submit',
                'click .py-huifu' : '_huifuTip',
                'click .py-tab li' : '_tab',
                'click .py-d-ok' : '_ok',
                'click .py-d-no' : '_no',
                'change #cssid' : '_changeCss',
                'click .py-watch' : '_watch',
                'click .py-gjcss' : 'GJCssClick',
                'click .py-format' : '_format',
                'change .py-type' : '_changeType',
                'click .py-static-edit' : '_staticClick',

                'click .ds-watch' : '_dsWatch'
            });
        },

        _close : function(){
            this.hide();
        },

        _changeSource : function(event){
            this._huandongInnerBefore();
            var target = $(event.currentTarget);
            this.refreshSource(target.val());
            this._huandongInner();
        },

        _changeCss : function(event){
            this._huandongInnerBefore();
            var target = $(event.currentTarget);
            this.refreshCss(target.val());
            this._huandongInner();
        },

        _changeType : function(event){
            var target = $(event.currentTarget);
            var type = target.val();
            this.element.find('.py-static-edit').trigger('click', [type == 3 ? true : false]);
            this.refreshStaticTip(type == 3 ? true : false);
        },

        _staticClick : function(event, state){
            $.type(state) == 'undefined' && (state = true);
            if(state){
                var rhtml = this.currentData['static_html'];
                $.MC.sb.cellStatic('show', rhtml);
            }else if(!state && $.MC.sb.is(':visible')){
                $.MC.sb.cellStatic('hide');
            }
        },

        staticSubmit : function(content){
            $.MC.mb.mask('staticSave', content);
        },

        _submit : function(){
            var _this = this;
            var sourceId = this.element.find('#source').val();
            var cssId = this.element.find('#cssid').val();
            var cellType = this.type.val();
            var valuesTmp = {};
            function filterFunc(values){
                var tmp = {};
                $.each(values, function(i, n){
                    tmp[n['name']] = n['value'];
                });
                return tmp;
            }
            $.each({
                mode_param : 'pmcontent',
                input_param : 'pscontent',
                js_param : 'jscontent',
                css_param : 'csscontent'
            }, function(i, n){
                valuesTmp[i] = filterFunc(_this[n].serializeArray());
            });
            $.MC.mb.mask('save', {
                source : sourceId,
                cssid : cssId,
                cellType : cellType
            }, valuesTmp, $.globalLoad(this.element));
        },

        _huifuTip : function(){
            this._dboxSH(true);
        },

        _huifu : function(){
            $.MC.mb.mask('cancel', $.globalLoad(this.element));
        },

        _watch : function(){
            var gw = $.MC.gw.trigger('open');
            var info = this.refreshCurrentData();
            gw.triggerHandler('html', ['html', info['rended_html'] || '']);
            gw.triggerHandler('html', ['css', info['css'] || '']);
            gw.triggerHandler('html', ['js', info['js'] || '']);
            gw.triggerHandler('reset');
        },

        _format : function(){
            $.MC.db.mode('setFormat', this.headWhich.find('span').html(), this.currentData);
        },

        refreshCurrentData : function(data){
            if($.type(data) == 'undefined'){
                return this.currentData;
            }
            this.currentData = $.extend({}, data);
        },

        _tab : function(event){
            var target = $(event.currentTarget);
            var on = 'on';
            if(target.hasClass(on)){
                return;
            }
            target.addClass(on).siblings().removeClass(on);
            var type = target.attr('type');
            this._huandongInnerBefore();
            var form = this.content.find('.py-' + type).show();
            form.siblings().hide();
            this._huandongInner(form);
        },

        _huandongInnerBefore : function(){
            var height = this.inner.height();
            this.inner.height(height);
        },

        _huandongInner : function(form){
            var toHeight = (form || this.inner.find('form:visible')).height();
            toHeight = toHeight > 400 ? 400 : toHeight;
            var _this = this;
            setTimeout(function(){
                /*_this.inner.stop().animate({
                    height : toHeight + 'px'
                }, 400);*/
                _this.inner.height(toHeight);
            }, 0);
        },

        _initTab : function(){
            this._huandongInnerBefore();
            this.tab.find('li:first').trigger('click');
            this._huandongInner();
        },

        refresh : function(data, nodelay){
            var _this = this;
            _this.pscontent.empty();
            _this.pmcontent.empty();
            _this.jscontent.empty();
            _this.csscontent.empty();

            _this._dboxSH(false);
            _this.refreshTip(false);
            _this.refreshHead();
            _this.refreshBtns(false);
            _this.refreshTab(true);
            _this.refreshHuifu(true);
            _this.refreshStaticTip(false);

            _this.refreshCurrentData(data);

            var cb = $.globalLoad(_this.content);
            _this._delay(function(){

                _this.refreshType(data['cell_type'] || 0);

                _this.refreshBtns(true);

                if(data === false){
                    _this.refreshTip(true);

                    cb();

                    return;
                }

                if(mainConfig['which'] == 'm' && parseInt(data['original_id']) == 0){
                    _this.refreshHuifu(false);
                }

                data['cell_mode'] && _this.refreshHead(data['cell_mode']);

                var modeParam = data['mode_param'];
                _this.refreshMode(modeParam);

                var pid = data['data_source'];
                var inputParam = data['input_param'];
                _this.refreshSource(pid, inputParam);

                var jsParam = data['js_param'];
                _this.refreshJs(jsParam);

                _this.hasList = !!data['css_list'];
                if(_this.hasList){
                    _this.cssId = data['css_id'];
                    /*_this.cssList = $.extend({
                        '-1' : {
                            id : -1,
                            title : '无',
                            para : []
                        }
                    }, data['css_list'] || {});*/
                    _this.cssList = (data['css_list'] || []).concat();
                    _this.cssList.unshift({
                        id : -1,
                        title : '无',
                        para : []
                    });
                    _this.cssParam = data['css_param'];
                }

                _this.refreshCss(_this.cssId);

                _this._initTab();

                _this._bindColumn();
                _this._bindPic();
                _this._bindBgpic();
                _this._bindColor();

                cb();

                data['cell_type'] == 3 && _this.refreshStaticTip(true);
            }, nodelay ? 0 : 300);
        },

        _makeArray : function(data){
            return $.customMakeArray(data);
        },

        refreshType : function(val){
            this.type.val(val);
        },

        refreshHuifu : function(state){
            this.huifu[state ? 'show' : 'hide']();
        },

        refreshTip : function(state){
            this.tip[state ? 'show' : 'hide']().siblings()[state ? 'hide' : 'show']();
        },

        refreshStaticTip : function(state){
            if(state){
                this.staticTip.show();
                this.body.hide();
            }else{
                this.staticTip.hide();
                this.body.show();
            }
        },



        refreshHead : function(modeId){
            if($.type(modeId) == 'undefined'){
                this.headWhich.hide();
            }
            var modeInfo = $.MC.db.mode('getModeInfo', modeId);
            if(modeInfo){
                this.headWhich.find('img').attr('src', modeInfo['indexpic'] ? $.globalImgUrl(modeInfo['indexpic'], '30x25') : '');
                this.headWhich.find('.py-which-title span').html(modeInfo['title']);
                this.headWhich.show();
            }
        },

        refreshTab : function(state){
            return;
            this.tab.find('li:first').siblings().css('visibility', state ? 'visible' : 'hidden');
        },

        refreshBtns : function(state){
            this.btns.add(this.huifu)[state ? 'show' : 'hide']();
        },

        refreshMode : function(data, isMore){
            var _this = this;
            var html = function(data){
                $.tmpl(_this.options.piname, data).appendTo(_this.pmcontent.empty());
                _this._bindDate();
            };
            if(isMore && $.isEmptyObject(data)){
                this.refreshTab(false);
                this.refreshBtns(false);
                html({
                    notong : true
                });
                return;
            }
            data = this._makeArray(data);
            !data.length && (data = {
                empty : true
            });
            html(data);
        },

        refreshSource : function(pid, inputParam){
            var tmplData = {
                pid : pid,
                source : this.source,
                data : []
            };
            if(pid > 0){
                var property = this.getProperty(pid);
                if(property){
                    var pInputParam = property['input_param'];
                    if(inputParam){
                        var fugai = {};
                        $.each(inputParam, function(i, n){
                            fugai[n['sign']] = i;
                        });
                        $.each(pInputParam, function(i, n){
                            if($.type(fugai[n['sign']]) != 'undefined'){
                                pInputParam[i] = inputParam[fugai[n['sign']]];
                            }
                        });
                    }
                    tmplData['data'] = this._makeArray(pInputParam);
                }
            }
            $.tmpl(this.options.psname, tmplData).appendTo(this.pscontent.empty());
            this._bindColumn(this.pscontent);
            this._bindPic(this.pscontent);
            this._bindBgpic(this.pscontent);
            this._bindColor(this.pscontent);
        },

        refreshJs : function(jsParam){
            var _this = this;
            var data;
            if(!jsParam){
                data = {
                    empty : true
                };
            }else{
                data = _this._makeArray(jsParam);
            }
            $.tmpl(_this.options.jsname, data).appendTo(_this.jscontent.empty());
        },

        refreshCss : function(cssId){
            var _this = this;
            var data;
            if(!_this.hasList){
                data = {
                    empty : true
                };
            }else{
                //var currentCss = $.extend({}, (_this.cssList[cssId] || {})['para'] || {});
                var currentCss = {};
                $.each(_this.cssList, function(i, n){
                    if(cssId == n['id']){
                        currentCss = $.extend({}, n['para']);
                        return false;
                    }
                });
                if(cssId == _this.cssId){
                    _this.cssParam && $.each(_this.cssParam, function(i, n){
                        currentCss[i] && (currentCss[i]['value'] = n['value']);
                    });
                }
                data = {
                    cssId : cssId,
                    cssList : _this.cssList,
                    data : _this._makeArray(currentCss)
                };
            }
            $.tmpl(_this.options.cssname, data).appendTo(_this.csscontent.empty());
            this._bindColor(this.csscontent);
            this._bindPic(this.csscontent);
            this._bindBgpic(this.csscontent);
        },

        _bindDate : function(parent){
            (parent || this.content).find('.bind-date[data-type="date"]').datepicker({
                showAnim : 'slideDown',
                dateFormat : 'yy-mm-dd'
            });
        },

        _bindColumn : function(parent){
            (parent || this.content).find('.bind-column[data-type="column"]').filter(function(){
                return !$(this).data('has-bind-column');
            }).data('has-bind-column', true).click(function(){
                    var box = $.MC.columnBox;
                    !box.is(':magic-column') && box.column($.columnConfig);
                    var callback = function(ids){
                        box.column('hide');
                        if(!ids.length) return;
                        $(this).prev().val(ids);
                    };
                    box.column('option', 'callback', $.proxy(callback, this));
                    box.column('refresh', $(this).prev().val());
                });
        },

        _bindPic : function(parent){
            (parent || this.content).find('.bind-pic[data-type="pic"]').filter(function(){
                return !$(this).data('has-bind-pic');
            }).data('has-bind-pic', true).click(function(){
                    var box = $.MC.picBox;
                    !box.is(':magic-pic') && box.pic($.picConfig);
                    var callback = function(imgSrc){
                        $(this).prev().val(imgSrc);
                    };
                    box.pic('option', 'callback', $.proxy(callback, this));
                    box.pic('refresh', $(this).prev().val());
                });
        },

        _bindBgpic : function(parent){
            (parent || this.content).find('.bind-bgpic[data-type="bgpic"]').filter(function(){
                return !$(this).data('has-bind-bgpic');
            }).data('has-bind-bgpic', true).click(function(){
                    var box = $.MC.bgpicBox;
                    !box.is(':magic-bgpic') && box.bgpic($.bgpicConfig);
                    var callback = function(bg){
                        $(this).prev().val(bg);
                    };
                    box.bgpic('option', 'callback', $.proxy(callback, this));
                    box.bgpic('refresh', $(this).prev().val());
                });
        },

        _bindColor : function(parent){
            (parent || this.content).find('.bind-color[data-type="color"]').filter(function(){
                return !$(this).data('has-bind-color');
            }).data('has-bind-color', true).each(function(){
                    $(this).ColorPicker({
                        color : $(this).prev().val(),
                        onShow : function (colpkr) {
                            $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide : function (colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange : $.proxy(function (hsb, hex, rgb) {
                            var color = '#' + hex;
                            $(this).css('backgroundColor', color).prev().val(color);
                        }, this)
                    });
                });
        },

        GJCssClick : function(event){
            var gjcBox = $.MC.gjCssBox;
            !gjcBox.is(':magic-gjcss') && gjcBox.gjcss($.gjcssConfig);
            gjcBox.gjcss('option', 'callback', $.proxy(this.GJCssCallback, this));
            var cdata = this.refreshCurrentData();
            var cssId = this.element.find('#cssid').val();
            var cssInfo = '';
            if(cssId){
                cdata['css_list'] && $.each(cdata['css_list'], function(i, n){
                    if(cssId == n['id']){
                        cssInfo = n;
                        return false;
                    }
                });
            }
            gjcBox.gjcss('refresh', {
                cellId : cdata['id'],
                modeId : cdata['cell_mode'],
                cssId : cssId,
                cssInfo : cssInfo
            });
        },

        GJCssCallback : function(info){
            this.refreshCurrentData(info);
            this.refreshCss(info['cell_id']);
        },

        _ok : function(){
            this._huifu();
        },

        _no : function(){
            this._dboxSH(false);
        },

        _dboxSH : function(state){
            this.dbox[state ? 'show' : 'hide']();
            this.main[state ? 'hide' : 'show']();
        },

        show : function(){
            this.element.addClass(this.options.on);
            $.MC.db.mode('show');
            this.state = true;
        },

        hide : function(){
            this.element.removeClass(this.options.on);
            //$.MC.db.mode('show');
            this.state = false;
        },

        getState : function(){
            return this.state;
        },


        _dsWatch : function(){
            $.MC.dsPreviewBox = $.MC.dsPreviewBox || $('#ds-preview-box');
            if(!$.MC.dsPreviewBox.is(':magic-datasourcePreview')){
                $.MC.dsPreviewBox.datasourcePreview({
                    url : mainConfig.datasourcePreview
                });
            }
            $.MC.dsPreviewBox.datasourcePreview('show', $('#source').val());
        },

        _destroy : function(){

        }
    });
})(jQuery);
