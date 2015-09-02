(function($){
    $.widget('magic.property', {
        options : {
            propertys : null,
            pitpl : null,
            piname : 'pi-template',
            pstpl : null,
            psname : 'ps-template',
            on : 'open'
        },

        _create : function(){
            this.state = false;
            $.template(this.options.piname, this.options.pitpl);
            $.template(this.options.psname, this.options.pstpl);
            var root = this.element;
            this.tab = root.find('.py-tab');
            this.btcontent = root.find('.py-bt');
            this.pscontent = root.find('.py-source');
            this.content = this.pscontent;
            this.inner = root.find('.py-inner');

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
                'change #source' : '_changeSource',
                'click .py-submit' : '_submit',
                'click .py-tab li' : '_tab',
                'click .ds-watch' : '_dsWatch'
            });
        },

        _tab : function(event){
            var target = $(event.currentTarget);
            var on = 'on';
            if(target.hasClass(on)){
                return;
            }
            target.addClass(on).siblings().removeClass(on);
            this._huandongInnerBefore();
            var type = target.attr('type');
            var form = this.element.find('.py-' + type).show();
            form.siblings().hide();
            this._huandongInner();
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

        _close : function(){
            this.hide();
        },

        _changeSource : function(event){
            this._huandongInnerBefore();
            var target = $(event.currentTarget);
            this.refreshSource(target.val());
            this._huandongInner();
        },

        _submit : function(){
            var _this = this;
            var sourceId = this.element.find('#source').val();
            var valuesTmp = {};
            function filterFunc(values){
                var tmp = {};
                $.each(values, function(i, n){
                    tmp[n['name']] = n['value'];
                });
                return tmp;
            }
            $.each({
                input_param : 'pscontent'
            }, function(i, n){
                valuesTmp[i] = filterFunc(_this[n].serializeArray());
            });
            $.MC.mb.mask('save', {
                source : sourceId,
                headerText : this.btcontent.find('[name="header_text"]').val(),
                moreHref : this.btcontent.find('[name="more_href"]').val()
            }, valuesTmp, $.globalLoad(this.element));
        },

        refreshCurrentData : function(data){
            if($.type(data) == 'undefined'){
                return this.currentData;
            }
            this.currentData = $.extend({}, data);
        },

        refresh : function(data, nodelay){
            var _this = this;
            _this.pscontent.empty();

            _this.refreshCurrentData(data);

            var cb = $.globalLoad(_this.content);
            _this._delay(function(){

                var pid = data['data_source'];
                var inputParam = data['input_param'];
                _this.refreshSource(pid, inputParam);

                _this.refreshBT({
                    header_text : data['header_text'],
                    more_href : data['more_href']
                });

                _this._initTab();

                _this._bindColumn();
                _this._bindPic();
                _this._bindBgpic();
                _this._bindColor();
                _this._bindColumnTitle();

                cb();
            }, nodelay ? 0 : 300);
        },

        _makeArray : function(data){
            return $.customMakeArray(data);
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

        refreshBT : function(bts){
            this.btcontent.find('[name="header_text"]').val(bts['header_text']);
            this.btcontent.find('[name="more_href"]').val(bts['more_href']);
        },

        setColumnTitle : function(info){
            var root = this.element;
            root.find('[name="header_text"]').val(info['name']);
            root.find('[name="more_href"]').val(info['column_url']);
        },

        _bindColumnTitle : function(parent){
            (parent || this.element).find('.bind-btn[data-type="column-title"]').click(function(){
                $.MC.ltb.columnTitle('show', 'dy');
            });
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

        show : function(){
            this.element.addClass(this.options.on);
            $.MC.db.mode('hide');
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
