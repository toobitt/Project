(function($){
    $.widget('magic.page', {
        options : {
            total : 0,
            cp : 0,
            num : 20
        },

        _create : function(){
            this.options.pages = Math.ceil(this.options.total / this.options.num);
            this._createBox();
        },

        _init : function(){
            this._on({
                'click span[_page]' : '_click'
            });
        },

        _createBox : function(){
            var op = this.options;
            var cp = parseInt(op.cp);
            var tp = op.pages;
            if(tp < 2){
                this.element.hide();
                return;
            }
            var html = '';
            html += '<span class="page-all">共' + op['pages'] + '页/计' + op['total'] + '条</span>';
            if(cp > 1){
                html += '<span class="page-prev" _page="1"><a>|<</a></span>';
                html += '<span class="page-prev" _page="' + (cp - 1) + '"><a><<</a></span>';
            }
            $.each([-2, -1, 0, 1, 2], function(i, n){
                var check = false;
                var val = cp + n;
                if(n < 0){
                    if(val > 0){
                        check = true;
                    }
                }else if(n > 0){
                    if(val <= tp){
                        check = true;
                    }
                }
                if(check){
                    html += '<span class="page-code" _page="' + val + '"><a>' + val + '</a></span>';
                }
                if(n == 0){
                    html += '<span class="current">' + cp + '</span>';
                }
            });
            if(cp < tp){
                html += '<span class="page-next" _page="' + (cp + 1) + '"><a>>></a></span>';
                html += '<span class="page-next" _page="' + tp + '"><a>>|</a></span>';
            }
            this.element.html(html);
        },

        _click : function(event){
            var page = $(event.currentTarget).attr('_page');
            this._trigger('page', null, [page]);
        },

        show : function(){
            this.element.show();
        },

        hide : function(){
            this.element.hide();
        },

        refresh : function(option){
            this.show();
            $.extend(this.options, option);
            this._createBox();
        }
    });

    $.widget('magic.plugin', {

        _init : function(){
            this._on({
                'click .plugin-save' : '_save',
                'click .plugin-cancel' : '_cancel'
            });
        },

        addCache : function(hash, data, type){
            type = type || 'default';
            this[type + 'cache'] = this[type + 'cache'] || {};
            this[type + 'cache'][hash] = data;
        },

        getCache : function(hash, type){
            type = type || 'default';
            return this[type + 'cache'] ? this[type + 'cache'][hash] : '';
        },

        show : function(zIndex){
            zIndex && this.element.css('z-index', zIndex);
            this.element.show();
        },

        hide : function(){
            this.element.hide();
        },

        _save : function(){
        },

        _cancel : function(){
            if(this._selfCancel){
                if(this._selfCancel() === false){
                    return false;
                }
            }
            this.hide();
        }
    });

    $.widget('magic.column', $.magic.plugin, {
        options : {
            'url' : '',
            'name-url' : '',
            tpl : '',
            tname : 'level-template',
            'result-item-tpl' : '',
            'result-item-name' : 'result-item-template',
            'callback' : $.noop
        },

        _create : function(){
            $.template(this.options.tname, this.options.tpl);
            $.template(this.options['result-item-name'], this.options['result-item-tpl']);
            var root = this.element;
            this.result = root.find('.cl-result');
            this.list = root.find('.cl-list');
            this.listInner = root.find('.cl-list-inner');
        },

        _init : function(){
            this._super();

            this._on({
                'click .cl-list li' : '_click',
                'click .cl-list input[type="checkbox"]' : '_listCheckbox',
                'click .cl-result input[type="checkbox"]' : '_resultCheckbox'
            });

            this._child();
        },

        _click : function(event){
            var target = $(event.currentTarget);
            if(target.is('input')) return;
            if(target.hasClass('on')){
                target.removeClass('on').closest('ul').nextAll().remove();
            }else{
                target.addClass('on').siblings().removeClass('on');
                if(!(target.data('child') > 0)){
                    this._child(target);
                }
            }
            this._after();
        },

        _listCheckbox : function(event){
            var target = $(event.currentTarget);
            var id = target.val();
            var checked = target.prop('checked');
            if(checked){
                var name = [];
                target.closest('ul').prevAll().find('.on').each(function(){
                    name.push($(this).data('name'));
                });
                name = name.reverse();
                name.push(target.closest('li').data('name'));

                this._addResult(id, name);
            }else{
                this._delResult(id);
            }
        },

        _resultCheckbox : function(event){
            var item = $(event.currentTarget).closest('li');
            var id = item.data('id');
            this._checkList(id, false);
            item.remove();
        },

        _addResult : function(id, name){
            if(this.result.find('li[data-id="' + id + '"]').length){
                return;
            }
            this.result.append($.tmpl(this.options['result-item-name'], {
                id : id,
                name : name.join('&gt;')
            }));
        },

        _delResult : function(id){
            this.result.find('li[data-id="' + id + '"]').remove();
        },

        _checkAllList : function(parent){
            var _this = this;
            this.result.find('li').each(function(){
                _this._checkList($(this).data('id'), true, parent);
            });
        },

        _checkList : function(id, state, parent){
            (parent || this.list).find('li[data-id="' + id + '"]').find('input[type="checkbox"]').prop('checked', state);
        },

        _refreshResult : function(data){
            data.length && $.tmpl(this.options['result-item-name'], data).appendTo(this.result);
        },

        _child : function(target){
            var _this = this;
            var id = 0;
            if(target){
                id = target.data('id');
                _this._before(target);
            }
            var cb = function(data){
                _this._appendChild(data);
                _this._after();
            };
            var data = _this.getCache(id);
            if(data){
                cb(data);
            }else{
                $.globalAjax(_this.list, function(){
                    return $.getJSON(
                        _this.options.url,
                        {fid : id},
                        function(json){
                            var tmp = {};
                            $.each(json, function(i, n){
                                tmp[i] = n;
                            });
                            _this.addCache(id, tmp);
                            cb(tmp);
                        }
                    );
                });
            }
            return false;
        },

        _before : function(target){
            target.closest('ul').nextAll().remove();
            target.closest('li').addClass('on').siblings().removeClass('on');
        },

        _appendChild : function(data){
            var newUL = $.tmpl(this.options.tname, data).appendTo(this.listInner);
            this._checkAllList(newUL);
        },

        _after : function(){
            var uls = this.list.find('ul');
            var len = uls.length;
            this.ulWidth = this.ulWidth || uls.eq(0).outerWidth(true);
            this.listInner.css('left', (len > 3 ? (- (len - 3) * this.ulWidth) : 0) + 'px');
        },

        _save : function(){
            var _this = this;
            var ids = [];
            var extJSON = {};
            this.result.find('li').each(function(){
                var id = $(this).data('id');
                ids.push(id);
                extJSON[id] = $(this).data('name');
            });
            $.each(ids, function(i, n){
                _this.addCache(i, n, 'name');
            });
            ids = ids.join(',');
            this.options.callback(ids, extJSON);

            this._super();
        },

        _ajaxName : function(ids, cb){
            var _this = this;
            return $.getJSON(
                _this.options['name-url'],
                {column_id : ids},
                function(json){
                    var tmp = {};
                    json['selected_items'] && $.each(json['selected_items'], function(i, item){
                        _this.addCache(item['id'], item['showName'], 'name');
                        tmp[item['id']] = {
                            id : item['id'],
                            name : item['showName']
                        };
                    });
                    cb(tmp);
                }
            );
        },

        refresh : function(ids){
            var _this = this;
            this.result.empty();
            this.list.find('ul:not(:first)').remove();
            ids = (ids || '').split(',');
            var data = {};
            var noNameIds = [];
            $.each(ids, function(_, id){
                var name = _this.getCache(id, 'name');
                if(!name){
                    noNameIds.push(id);
                }else{
                    data[id] = {
                        id : id,
                        name : name
                    };
                }
            });
            _this.list.find('input[type="checkbox"]').prop('checked', false);
            _this.show();
            var cb = function(json){
                var tmp = $.customMakeArray($.extend({}, data, json || {}));
                _this._refreshResult(tmp);
            };
            if(noNameIds.length){
                $.globalAjax(_this.element, function(){
                    return _this._ajaxName(noNameIds.join(','), function(json){
                        cb(json);
                    });
                });
            }else{
                cb();
            }
        },

        _destroy : function(){

        }
    });

    $.widget('magic.pic', $.magic.plugin, {
        options : {
            'url' : '',
            'upload-url' : '',
            'upload-phpkey' : '',
            'page-num' : 9,
            tpl : '',
            tname : 'pic-item-template',
            'callback' : $.noop
        },

        _create : function(){
            $.template(this.options.tname, this.options.tpl);
            var root = this.element;
            this.list = root.find('.pc-body');
            this.add = root.find('.pc-add');
            this.file = root.find('.pc-file');
            this.pages = root.find('.pc-pages');

            this.pageTotal = 0;
            this.pageNum = this.options['page-num'];
            this.pageCurrent = 1;
        },

        _init : function(){
            this._super();

            this._on({
                'click .pc-item' : '_click',
                'click .pc-add' : '_add'
            });
            if(!this.inited){
                this._ajax();
            }

            var _this = this;
            this.file.ajaxUpload({
                url : this.options['upload-url'],
                phpkey : this.options['upload-phpkey'],
                before : function(info){
                    _this._before(info);
                },
                after : function(info){
                    _this._delay(function(){
                        _this._after(info);
                    }, 250);
                }
            });

        },

        _pages : function(){
            var _this = this;
            if(!this.pages) return;
            var options = {
                total : this.pageTotal,
                cp : this.pageCurrent,
                num : this.pageNum
            };
            if(!this.pages.is(':magic-page')){
                this.pages.page($.extend(options, {
                    page : function(event, cp){
                        _this._pageClick(cp);
                    }
                }));
            }else{
                this.pages.page('refresh', options);
            }
            this.pages[this.pageTotal > this.pageNum ? 'show' : 'hide']();
        },

        _pageClick : function(cp){
            this.pageCurrent = cp;
            this.list.find('li:not(:first)').remove();
            this._ajax();
        },

        _click : function(event){
            var target = $(event.currentTarget);
            var on = 'on';
            if(target.hasClass(on)) return;
            target.addClass(on).siblings('.' + on).removeClass(on);
            return false;
        },

        _add : function(event){
            var target = $(event.currentTarget);
            this.currentHashPre = this._hash();
            this.imgs = {};
            this.file.trigger('click');
            this._check();
            this.addPosition = this.add.offset();
        },

        _hash : function(){
            return $.makeHash();
        },

        _hashPre : function(index){
            return this.currentHashPre + '_' + index;
        },


        _checkMax : function(){
            var lis = this.list.find('li');
            if(lis.length > this.pageNum + 1){
                lis.last().remove();
            }
        },

        _before : function(info){
            if(!info) return;
            this._start();
            var index = info['index'];
            var hash = this._hashPre(index);
            this.imgs[index] = info;
            var item = $.tmpl(this.options.tname, {
                name : info['file']['name'],
                img : info['data']['result']
            }).insertAfter(this.add).attr('hash', hash);
            this._checkMax();
            var position = item.offset();
            item.css({
                position : 'relative',
                left : this.addPosition.left - position.left + 'px',
                top :  this.addPosition.top - position.top + 'px',
                opacity : 0
            });
            item.data('uploading', true);

            this._delay(function(){
                item.css({
                    left : 0,
                    top : 0,
                    opacity : 1
                });
            }, 500);
        },

        _after : function(info){
            var _this = this;
            var index = info['index'];
            var item = _this.list.find('li[hash="'+ _this._hashPre(index) +'"]').attr('data-id', info['data']['id']);
            var load = $.globalLoad(item);
            var src = info['data']['real_url'];
            _this._data(info['data']['id'], info['data']);
            var img = new Image();
            img.onload = img.onerror = function(){
                item.data('uploading', false);
                _this.imgs[index] = true;
                item.find('img').attr('src', src);
                item.attr('data-url', info['data']['url']);
                _this._checkImgs();
                load();
            }
            img.src = src;
        },

        _checkImgs : function(){
            var ok = true;
            $.each(this.imgs, function(i, n){
                if(n !== true){
                    ok = false;
                    return false;
                }
            });
            if(ok){
                this._stop();
            }
        },

        _start : function(){
            if(this.started){
                return;
            }
            this.started = true;
            this.loadEvent = $.globalLoad(this.add);
        },

        _stop : function(){
            $.type(this.loadEvent) == 'function' && this.loadEvent();
        },

        _ajax : function(){
            var _this = this;
            var offset = (this.pageCurrent - 1) * this.pageNum;
            var count = this.pageNum;
            $.globalAjax(this.list, function(){
                return $.getJSON(
                    _this.options.url,
                    {offset : offset, count : count},
                    function(json){
                        //_this.inited = true;
                        _this.pageTotal = json['total'];
                        _this._render(json['data']);
                        _this._check();
                        _this._pages();
                    }
                );
            });
        },

        _render : function(list){
            var tmp = [];
            var _this = this;
            $.each(list, function(i, n){
                tmp.push({
                    id : n['id'],
                    url : n['url'],
                    img : n['real_url']
                });
                _this._data(n['id'], n);
            });
            $.tmpl(this.options.tname, tmp).appendTo(this.list);
        },

        _data : function(id, data){
            this._dataCache = this._dataCache || {};
            if($.type(data) == 'undefined'){
                return this._dataCache[id];
            }
            this._dataCache[id] = data;
        },

        _cleanData : function(){
            this._dataCache = {};
        },

        _save : function(event){
            this._super(event);
            var _this = this;
            var select = _this.list.find('.on');
            if(select.length){
                if(select.data('uploading')){
                    return;
                }
                var id = select.data('id');
                var imgUrl = select.data('url');
                var imgInfo = _this._data(id);
                var beforeSaveVal;
                this._beforeSave && (beforeSaveVal = this._beforeSave(imgUrl));
                _this.options.callback($.type(beforeSaveVal) == 'undefined' ? imgUrl : beforeSaveVal, imgInfo);
            }else{
                $(event.currentTarget).myTip({
                    string : '没有选择图片！',
                    delay : 1500
                });
                return false;
            }
            _this.hide();
        },

        _check : function(){
            /*if(!this.inited || this.checked){
                return;
            }
            this.checked = true;*/
            this.list.find('li[data-url="' + this.hash + '"]').addClass('on');
        },

        refresh : function(hash){
            var on = 'on';
            this.list.find('.' + on).removeClass(on);
            this.hash = hash;
            this.checked = false;
            this._check();
            this.show();
        },

        _destroy : function(){

        }
    });

    $.widget('magic.bgpic', $.magic.pic, {
        options : {
            'url' : '',
            'upload-url' : '',
            'upload-phpkey' : '',
            'page-num' : 11,
            tpl : '',
            tname : 'bgpic-item-template',
            'callback' : $.noop
        },

        _create : function(){
            this._super();

            this.defaultRepeat = 'no-repeat';
            this.defaultPositionX = 'left';
            this.defaultPositionY = 'top';
            this.defaultColor = 'transparent';

            var root = this.element;
            var makeAutoList = function(list){
                var _list = [];
                $.each(list, function(i, n){
                    _list.push({
                        id : n,
                        name : n
                    });
                });
                return _list;
            }
            var bxBox = root.find('.bgpic-x').myAuto({
                list : makeAutoList(['left', 'center', 'right'])
            });
            var byBox = root.find('.bgpic-y').myAuto({
                list : makeAutoList(['top', 'center', 'bottom'])
            });

            this.bgcolorBox = root.find('.bgpic-color-box').ColorPicker({
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
                    this.bgcolorBox.css('backgroundColor', color).prev().val(color);
                }, this)
            });

            this.repeat = root.find('input[name="repeat"]');
            this.positionX = bxBox.find('div');
            this.positionY = byBox.find('div');
            this.bgcolor = root.find('.bgpic-color');
        },

        _init : function(){
            this._super();

            this._on({
                'click .bgpic-save-bg' : '_onlyBGColor'
            })
        },

        _beforeSave : function(imgUrl){
            var bgCss = [];
            if(imgUrl){
                bgCss.push('url('+ imgUrl +')');
                var repeatVal = this.repeat.filter(function(){return $(this).prop('checked');}).val() || this.defaultRepeat;
                bgCss.push(repeatVal);
                var positionXVal = $.trim(this.positionX.text()) || this.defaultPositionX;
                bgCss.push(positionXVal);
                var positionYVal = $.trim(this.positionY.text()) || this.defaultPositionY;
                bgCss.push(positionYVal);
                var colorVal = $.trim(this.bgcolor.val()) || this.defaultColor;
                bgCss.push(colorVal);
            }
            return bgCss.join(' ');
        },

        refresh : function(hash){
            var hashTmp;
            var imgUrl;
            if(/^#(.{3}|.{6})$/.test(hash)){
                hashTmp = ['', '', '', hash];
            }else{
                var noUrlPart = hash.replace(/url\(.*\)/, '');
                hashTmp = noUrlPart.replace(/\s+/g, ' ');
                hashTmp = $.trim(hashTmp);
                hashTmp = hashTmp.split(' ');
                var match = hash.match(/url\(['"]?([^'"\(\)]*)['"]?\)/);
                imgUrl = match && match[1] ? match[1] : '';
            }
            var repeat = hashTmp[0] || this.defaultRepeat;
            var positionX = hashTmp[1] || this.defaultPositionX;
            var positionY = hashTmp[2] || this.defaultPositionY;
            var bgcolor = hashTmp[3] || this.defaultColor;
            this._super(imgUrl);
            this.repeat.val([repeat]);
            this.positionX.html(positionX).attr('data-id', positionX);
            this.positionY.html(positionY).attr('data-id', positionY);
            this.bgcolor.val(bgcolor);
            this.bgcolorBox.ColorPickerSetColor(bgcolor).css('background-color', bgcolor);
        },

        _onlyBGColor : function(){
            this.options.callback($.trim(this.bgcolor.val()) || this.defaultColor);
            this.hide();
        },

        _destroy : function(){

        }
    });

    $.widget('magic.gjcss', $.magic.plugin, {
        options : {
            'url' : '',
            'callback' : $.noop
        },

        _create : function(){
            var root = this.element;
            this.cellId = 0;
            this.modeId = 0;
            this.cssId = 0;
            this.cssCode = '';
            this.editDiv = root.find('.gjc-edit');
        },

        _init : function(){
            this._super();

            this._on({
                'click .cl-list li' : '_click'
            });


        },

        _save : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var clone = this.editDiv.clone();
            clone.find('br').replaceWith('\n');
            this.cssCode = clone.text();
            if(!this.cssCode){
                target.myTip({
                    string : 'css不能为空！'
                });
                return false;
            }
            var data = {
                cell_id : _this.cellId,
                mode_id : _this.modeId,
                css_id : _this.cssId,
                code : _this.cssCode,
                css_info : _this.cssInfo
            };
            $.globalAjax(target, function(){
                return $.post(
                    _this.options.url,
                    {code : JSON.stringify(data)},
                    function(json){
                        cb(json);
                    },
                    'json'
                );
            });

            function cb(json){
                _this.options.callback({

                });
                _this.hide();
            }

        },

        refresh : function(info){
            var _this = this;

            this.cellId = info['cellId'];
            this.modeId = info['modeId'];
            this.cssId = info['cssId'];
            this.cssInfo = info['cssInfo'];
            this.cssCode = this.cssInfo ? this.cssInfo['code'] : '';
            this.show();
            var editor = this.editDiv.data('ace');
            if(!editor){
                editor = ace.edit(this.editDiv[0]);
                editor.setTheme("ace/theme/github");
                editor.getSession().setMode("ace/mode/css");
                this.editDiv.data('ace', editor);
            }
            this.cssCode = $.replaceST.T(this.cssCode);
            editor.setValue(this.cssCode);

        },

        _destroy : function(){

        }
    });

    $.widget('magic.margin', {
        options : {
            s : 0,
            x : 0,
            z : 0,
            y : 0
        },

        _create : function(){
            var root = this.element;

        },

        _init : function(){

        },

        refresh : function(){

        },

        _destroy : function(){

        }
    });

    $.widget('magic.qhcolumn', {
        options : {
            url : '',
            siteid : 0,
            pageid : 0,
            dataid : 0,
            'item-tpl' : '',
            'item-tname' : 'qhcolumn-item',
            'page-tpl' : '',
            'page-tname' : 'qhcolumn-page'
        },

        _cache : function(key, value){
            if($.type(value) == 'undefined'){
                return this.cache ? this.cache[key] : '';
            }
            this.cache = this.cache || {};
            this.cache[key] = value;
            this.qhcolumnDataCache.set(JSON.stringify(this.cache));
        },

        _create : function(){
            $.template(this.options['item-tname'], this.options['item-tpl']);
            $.template(this.options['page-tname'], this.options['page-tpl']);
            var _this = this;
            var root = this.element;
            this.qhcolumnOnOffCache = $.customDomCache('qhcolumnOnOff');
            if(this.qhcolumnOnOffCache.get() > 0){
                this.show();
            }
            this.qhcolumnDataCache = $.customDomCache('qhcolumnData');
            var data = this.qhcolumnDataCache.get();
            if(data){
                this.cache = JSON.parse(data);
            }
            this.qhcolumnHtmlCache = $.customDomCache('qhcolumnHtml');
            this.cacheHTMLInfo = this.qhcolumnHtmlCache.get();
            var html = '';
            try{
                this.cacheHTMLInfo && (this.cacheHTMLInfo = JSON.parse(this.cacheHTMLInfo));
                var time = this.cacheHTMLInfo['time'];

                if(time && time + 3600 * 24 < +new Date){

                }else{
                    html = this.cacheHTMLInfo['content'];
                }
                if(html){
                    root.html(html);
                }
            }catch(e){

            }finally{

            }
            this.leftBox = root.find('.qhc-left');
            this.inner = root.find('.qhc-inner');
            this.page = root.find('.qhc-right ul');
            var items = root.find('.qhc-item');
            if(!items.length){
                this.ajax();
            }else{
                this._afterAjax();
            }
        },

        _init : function(){
            this._on({
                'click .qhc-child' : '_child',
                'click .qhc-item li' : '_eachClick',
                'click .qhc-title' : '_back',
                'click .qhc-title a' : '_f5'
            });
            var _this = this;
            $(window).on({
                beforeunload : function(){
                    _this.qhcolumnHtmlCache.set(JSON.stringify({
                        content : $('#qhcolumn-box').html(),
                        time : +new Date
                    }));
                }
            });
        },

        ajax : function(fid, pageid, cb){
            var _this = this;
            $.globalAjax(this.leftBox, function(){
                return $.getJSON(
                    _this.options['url'],
                    {site_id : _this.options['siteid'], page_id : pageid, page_data_id : fid},
                    function(json){
                        if(json){
                            $.each(json, function(i, n){
                                _this._cache(n['id'], n);
                            });
                            _this._createItem(json);
                        }
                    }
                );
            }, cb);
        },

        _child : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var parent = target.closest('li');
            var fid = parent.data('pagedataid');
            var pageid = parent.data('pageid');
            !parent.hasClass('on') && parent.addClass('on').siblings().removeClass('on');
            this._beforeAjax();
            this.ajax(fid, pageid, function(){
                _this._afterAjax();
            });

        },

        _beforeAjax : function(){
            this.page.empty();
        },

        _afterAjax : function(back){
            var items = this.inner.children();
            !this.itemWidth && (this.itemWidth = items.outerWidth(true));
            this.inner.css('left', - (items.length - (back ? 2 : 1)) * this.itemWidth + 'px')
        },

        _createItem : function(data){
            var title = '全部';
            var fid = 0;
            var canBack = false;
            var lastItem = this.inner.find('.qhc-item:last');
            if(lastItem[0]){
                var onItem = lastItem.find('.on');
                title = onItem.data('name');
                fid = onItem.data('id');
                canBack = true;
            }
            var info = {
                fid : fid,
                title : title,
                canBack : canBack,
                list : data
            };
            var item =$.tmpl(this.options['item-tname'], info).appendTo(this.inner);
            item.find('li:first').trigger('click');
        },

        _eachClick : function(event){
            var target = $(event.currentTarget);
            target.addClass('on').siblings().removeClass('on');
            var cache = this._cache(target.data('id'));
            var tmp = [];
            var _this = this;
            $.each(cache['content_types'], function(i, n){
                tmp.push({
                    href : '?gmid=' + mainConfig['gmid'] + '&ext=' + encodeURIComponent('site_id=' + _this.options['siteid'] + '&page_id=' + cache['page_id'] + '&page_data_id=' + cache['page_data_id'] + '&content_type=' + i),//site_id%3D1%26page_id%3D%26page_data_id%3D%26content_type%3D-1',
                    name : n
                });
            });
            this._createPage(tmp);
        },

        _createPage : function(pages){
            this.page.empty();
            $.tmpl(this.options['page-tname'], pages).appendTo(this.page);
        },

        _back : function(event){
            var target = $(event.currentTarget);
            if(!target.find('span').length) return;
            this._afterAjax(true);
            this._delay(function(){
                var item = target.closest('.qhc-item');
                item.prev().find('li.on').trigger('click');
                item.remove();
            }, 500);

        },

        _f5 : function(){
            this.qhcolumnDataCache.remove();
            this.qhcolumnHtmlCache.remove();
            this.inner.empty();
            this.page.empty();
            this.cache = {};
            this.ajax(0, 0);
        },

        refresh : function(){

        },

        onoff : function(){
            this[this._onoff ? 'hide' : 'show']();
        },

        show : function(){
            this._onoff = true;
            this.element.addClass('on');
            this.qhcolumnOnOffCache.set(1);
        },

        hide : function(){
            this._onoff = false;
            this.element.removeClass('on');
            this.qhcolumnOnOffCache.set(0);
        },

        _destroy : function(){

        }
    });

    $.widget('magic.pagepp', $.magic.plugin, {
        options : {

        },

        _create : function(){
            this._super();
            var root = this.element;

        },

        _init : function(){
            this._super();
            this._on({
                'click .bind-btn' : '_body'
            });
        },

        _body : function(event){
            var box = $.MC.bgpicBox;
            if(!box.is(':magic-bgpic')){
                box.bgpic($.bgpicConfig);
            }
            var target = event.currentTarget;
            box.bgpic('option', 'callback', $.proxy(function(bg){
                $(this).prev().val(bg);
            }, target));
            box.bgpic('refresh', $(target).val());
            box.bgpic('show', 1000001);
        },

        _save : function(){
            var data = this.element.find('form').serializeArray();
            this.element.myTip({
                string : JSON.stringify(data)
            });

        },

        SC : function(){
            this._SC = !this._SC;
            this[this._SC ? 'show' : 'hide']();
        },

        show : function(){
            this._super();
            this._SC = 1;
        },

        hide : function(){
            this._super();
            this._SC = 0;
        },

        refresh : function(){

        },

        _destroy : function(){

        }
    });


    $.widget('magic.cellStatic', $.magic.plugin, {
        options : {
            textarea : 'cell-static-content'
        },

        _create : function(){
            this._super();
            this.editor = UE.getEditor(this.options['textarea'], {
                autoClearEmptyNode : false,
                enterTag : 'div'
            });
        },

        _init : function(){
            this._super();
            var _this = this;
            this.editor.addListener('ready', function(){
            });
            this.editor.addListener('filterInputRules', function(){
                this.inputRules = [];
            });
        },

        show : function(content){
            var csses = $.iframe.getCss();
            this.addCss(csses);
            this.setContent(content);
            this._super();
        },

        addCss : function(csses){
            if(csses.length){
                var $document = $(this.editor.document);
                var head = $document.find('head');
                $.each(csses, function(i, n){
                    if(n.type == 'link'){
                        head.append('<link rel="stylesheet" type="text/css" href="'+ n['href'] +'" class="css-static"/>');
                    }else{
                        head.append('<style type="text/css" class="css-static">'+ n['content'] +'</style>');
                    }
                });
                $document.find('body').css('background', 'none');
            }
        },

        removeCss : function(){

        },

        setContent : function(content){
            this.editor.setContent(content);
        },

        getContent : function(){
            return this.editor.getContent();
        },

        _save : function(){
            var content = this.getContent();
            $.MC.pb.property('staticSubmit', content);
            this.hide();
        },

        _destroy : function(){

        }

    });

    $.widget('magic.datasourcePreview', $.magic.plugin, {
        options : {
            url : '',
            tpl : '',
            tname : 'ds-item-tpl'
        },

        _create : function(){
            this._super();
            this.list = this.element.find('.ds-preview-list');
            this.code = this.element.find('.ds-preview-code');
            $.template(this.options.tname, $('#ds-preview-item-tpl').html());
        },

        _init : function(){
            this._super();
            var _this = this;
            this._on({
                'change select' : '_preview'
            });
        },

        show : function(dsId){
            this._initSelect();
            this.element.find('.plugin-head select').val(dsId);
            this._super();
            this._preview(dsId);
        },

        _initSelect : function(){
            if(this.initSelected) return;
            this.initSelected = true;
            var selectInfo = $.MC.info.data_source;
            this.element.find('.plugin-head').html(function(){
                var options = '';
                $.each(selectInfo, function(i, n){
                    options += '<option value="' + n.id + '">' + n.name + '</option>';
                });
                return '<select>' + options + '</select>';
            });
            this._on({
                'change .plugin-head select' : '_preview'
            });
        },

        _preview : function(dsId){
            dsId = parseInt(dsId) || this.element.find('.plugin-head select').val();
            var _this = this;
            $.globalAjax(this.element, function(){
                return $.getJSON(
                    _this.options.url,
                    {id : dsId},
                    function(json){
                        json = json[0] || json;
                        var data = '', error = false;
                        if(json['data']['error']){
                            data = json['data']['error'];
                            error = true;
                        }else{
                            data = json;
                        }
                        _this._list(error ? data : data['data'], error);
                        _this._code(error ? data : data['str_data'], error);
                    }
                );
            });
        },

        _list : function(data, error){
            if(error){
                this.list.html('<span style="display:block;text-align:center;">' + data + '</span>');
            }else{
                $.tmpl(this.options.tname, data).appendTo(this.list.empty());
            }
        },

        _code : function(data, error){
            if(error){
                this.code.html('<div style="text-align:center;">' + data + '</div>');
            }else{
                this.code.html('<pre>' + data + '</pre>');
            }
        },

        _destroy : function(){

        }

    });

    $.widget('magic.reslink', $.magic.plugin, {
        options : {
            url : '',
            contentUrl : '',
            updateContentUrl : '',
            uploadUrl : '',
            templateUrl : '',
            checkTemplateUrl : '',
            updateTemplateUrl : '',
            tpl : '',
            tname : 'reslink-tname'
        },

        _create : function(){
            this._super();
            $.template(this.options.tname, this.options.tpl);
            this.list = this.element.find('.reslink-list');
            this.code = this.element.find('.reslink-code');
            this.tip = this.element.find('.code-tip');
            this.editor = ace.edit(this.element.find('.code-content')[0]);
            this.editor.setTheme("ace/theme/github");
            this.updateBtn = this.element.find('.code-update');
            this.imgBox = this.element.find('.code-img');
            this.fileUpload = this.element.find('#reslink-file');

            this.checkBox = this.element.find('.ds-html-check');
        },

        _init : function(){
            this._super();
            var _this = this;
            this._on({
                'click .reslink-list li' : '_click',
                'click .code-update' : '_update',
                'click .ds-html-ok' : '_checkOK',
                'click .ds-html-no' : '_checkNO'
            });

            this.fileUpload.ajaxUpload({
                url : this.options['uploadUrl'],
                phpkey : '',
                before : function(info){
                    _this._before(info);
                },
                after : function(info){
                    _this._delay(function(){
                        _this._after(info);
                    }, 250);
                }
            });
        },

        show : function(){
            this._super();
            this._resList();
        },

        _resList : function(){
            if(this.resListed) return;
            this.resListed = true;
            var _this = this;
            $.globalAjax(this.element, function(){
                return $.getJSON(
                    _this.options.url,
                    function(json){
                        _this._list(json);
                    }
                );
            });
        },

        _list : function(list){
            list = list[0] || list;
            var _this = this;
            var cssList = [];
            var jsList = [];
            var picList = [];
            this.templateId = 0;
            $.each(list, function(tid, val){
                _this.templateId = tid;
                list = val;
                return false;
            });

            if(list['css'] || list['js'] || list['pic']){

            $.each(list, function(key, each){
                if(key == 'css'){
                    $.each(each, function(i, n){
                        var match = n.match(/([^\/]+)\.css$/i);
                        if(match && match[1]){
                            cssList.push({
                                name : match[1] + '.css',
                                path : n,
                                type : 'css'
                            });
                        }
                    });
                }
                if(key == 'js'){
                    $.each(each, function(i, n){
                        var match = n.match(/([^\/]+)\.js$/i);
                        if(match && match[1]){
                            jsList.push({
                                name : match[1] + '.js',
                                path : n,
                                type : 'js'
                            });
                        }
                    });
                }
                if(key == 'pic'){
                    $.each(each, function(i, n){
                        var match = n['dir'].match(/([^\/]+)\.([^\.]+)$/i);
                        if(match && match[1]){
                            picList.push({
                                name : match[1] + '.' + match[2],
                                path : n['dir'],
                                url : n['url'],
                                type : 'pic'
                            });
                        }
                    });
                }
            });

            }
            $.tmpl(_this.options.tname, {
                templateId : _this.templateId,
                cssList : cssList.length ? cssList : null,
                jsList : jsList.length ? jsList : null,
                picList : picList.length ? picList : null
            }).appendTo(this.list);
        },

        _click : function(event){
            var _this = this;
            var $target = $(event.currentTarget);
            if($target.hasClass('on')){
                $target.removeClass('on');
                this._kong();
                return;
            }
            this.list.find('.on').removeClass('on');
            $target.addClass('on');
            var type = this._currentType = $target.attr('type');
            if(type == 'pic'){
                this._fillImg($target.attr('url'));
                return;
            }

            var path = this._currentPath = $target.attr('path');
            var url = this.options.contentUrl;
            var data = {dir : path, template_id : _this.templateId};
            if(type == 'html'){
                path = '_html_';
                url = this.options.templateUrl;
                data = {template_id : _this.templateId};
            }
            var _cache = this._cache(path);
            if(_cache){
                _this._refreshContent(type, _cache);
            }else{
                _this._ajax(event.currentTarget, url, data, path, type);
            }
        },


        _ajax : function(target, postUrl, postData, path, type){
            var _this = this;
            $.globalAjax(target, function(){
                return $.getJSON(
                    postUrl,
                    postData,
                    function(json){
                        json = json[0] || json;
                        if(json['error']){
                            $(_this.code).myTip({
                                string : json['error'],
                                delay : 2000,
                                dtop : 250,
                                dleft : 0,
                                color : '#cc2a1e'
                            });
                            _this._kong();
                            return;
                        }
                        var content = json['file_info'] || json['content'];
                        path != '_html_' && _this._cache(path, content);
                        _this._refreshContent(type, content);
                    }
                );
            });
        },

        _cache : function(path, data){
            if($.type(data) == 'undefined'){
                return this.cache && this.cache[path];
            }
            (this.cache = this.cache || {})[path] = data;
        },

        _kong : function(){
            this.tip.show();
            this.updateBtn.hide();
            this.imgBox.hide();
            $(this.editor.container).hide();
        },

        _fillKong : function(type){
            this.tip.hide();
            this.imgBox.hide();
            this.updateBtn.show();
            $(this.editor.container).show();
        },

        _fillImg : function(img){
            this.tip.hide();
            this.imgBox.show().find('img').attr('src', img);
            this.updateBtn.hide();
            $(this.editor.container).hide();
        },

        _refreshContent : function(type, content){
            this.editor.getSession().setMode("ace/mode/" + type);
            this.editor.setValue(content);
            this._fillKong(type);
        },

        _update : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var content = this.editor.getValue();
            if(this._currentType == 'html'){
                this._checkHtml(target, content);
                return;
            }
            this._cache(_this._currentPath, content);
            $.globalAjax(target, function(){
                return $.post(
                    _this.options.updateContentUrl,
                    {template_id : _this.templateId, dir : _this._currentPath, file_info : content},
                    function(json){
                        target.myTip({
                            string : '保存成功！'
                        });
                    }
                );
            });
        },

        _checkHtml : function(target, content){
            var _this = this;
            $.globalAjax(target, function(){
                return $.post(
                    _this.options.checkTemplateUrl,
                    {template_id : _this.templateId, content : content, html : 1},
                    function(json){
                        _this._checkShow(json[0]);
                    },
                    'json'
                );
            });
        },

        _checkShow : function(checkInfo){
            var table = checkInfo['table'];
            var iframe = this.checkBox.find('iframe');
            var iframeDoc = iframe[0].contentDocument || iframe[0].contentWindow.document;
            var script = '<script src="../res/magic/js/jquery.min.js"></script>';
            var style = '<style>.compare{width:330px !important;height:480px !important;border:1px solid #494949;font-size:12px !important;}.compare table{font-size:12px !important;}.line{background:#494949;color:#77B7F7;padding:0 3px;}.comp-head{height:30px;line-height:30px;background:#494949;color:#fff;font-size:12px !important;}.comp-thead{float:left;width:330px;}</style>';
            iframeDoc.open();
            iframeDoc.write(script + style + table[0] + table[1]);
            iframeDoc.close();

            var cellBox = this.checkBox.find('.ds-html-cell');
            if(!table['celladding'] && !table['celldeling']){
                cellBox.hide();
            }else{
                cellBox.show();
                var addCellBox = this.checkBox.find('.ds-cell-add').hide();
                var delCellBox = this.checkBox.find('.ds-cell-del').hide();
                if(table['celladding']){
                    addCellBox.show().find('dd').remove();
                    addCellBox.append('<dd>' + table['celladding'] + '</dd>');
                }
                if(table['celldeling']){
                    delCellBox.show().find('dd').remove();
                    delCellBox.append('<dd>' + table['celldeling'] + '</dd>');
                }
            }

            this.checkBox.addClass('show');
        },

        _checkHide : function(){
            this.checkBox.removeClass('show');
        },

        _checkOK : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var content = this.editor.getValue();
            _this._cache(_this._currentPath, content);
            $.globalAjax(target, function(){
                return $.post(
                    _this.options.updateTemplateUrl,
                    {template_id : _this.templateId, content : content, html : 1},
                    function(json){
                        target.myTip({
                            string : '保存成功！'
                        });
                        _this._checkHide();
                    },
                    'json'
                );
            });
        },

        _checkNO : function(){
            this._checkHide();
        },

        _destroy : function(){

        }

    });



    $.widget('magic.dylist', {
        options : {
            tpl : '',
            tname : 'dylist-tpl'
        },

        _create : function(){
            $.template(this.options.tname, this.options.tpl);
            this.inner = this.element.find('.dylist-inner');
            this.btn = this.element.find('.dylist-btn');

            mainConfig['which'] != 'k' && this.inner.addClass('normal-state');

            this._initList();
        },

        _init : function(){
            this._on({
                'click .dylist-btn' : '_btn',
                'click .dylist-item' : '_click',
                'mouseenter .dylist-item' : '_mouseenter',
                'mouseleave .dylist-item' : '_mouseleave',
                'click .gss-btn' : '_gssSelect',
                'click .gss-mask' : '_gssMask'
            });
        },

        _initList : function(){
            var cells = $.MC.info.cellNew;
            $.tmpl(this.options.tname, cells).appendTo(this.inner);
        },

        show : function(){
            this.element.addClass('open');
            this.open = true;
            this.btn.html(this.btn.data('close'));
        },

        hide : function(){
            this.element.removeClass('open');
            this.open = false;
            this.btn.html(this.btn.data('open'));
        },

        _btn : function(event){
            if(!this.open){
                this.show();
            }else{
                this.hide();
            }
        },

        _click : function(event){
            var $target = $(event.currentTarget);
            var hash = $target.attr('hash');
            var _this = this;
            $.MC.mb.mask('moniClick', hash);
        },

        refreshClick : function(hashs){
            var cc = 'on';
            var widget = this.element;
            widget.find('.' + cc).removeClass(cc);
            hashs.length && $.each(hashs, function(i, n){
                widget.find('.dylist-item[hash="'+ n +'"]').addClass(cc);
            });
        },

        _mouseenter : function(event){
            var $target = $(event.currentTarget);
            var hash = $target.attr('hash');
            $.MC.mb.mask('moniMouseenter', hash);
        },

        _mouseleave : function(){
            var $target = $(event.currentTarget);
            var hash = $target.attr('hash');
            $.MC.mb.mask('moniMouseleave', hash);
        },

        _gssSelect : function(event){
            var $target = $(event.currentTarget).closest('.dylist-item');
            if(!$target.hasClass('gss-select')){
                $target.addClass('gss-select');
                this.innerState('gss');
                var hash = $target.attr('hash');
                var info = $.MC.mb.mask('getCellInfo', hash);
                var cloneInfo = $.extend({}, info);
                cloneInfo['data_source'] = 0;
                $.MC.body.formatGSS('start', cloneInfo);
            }
            return false;
        },

        _gssMask : function(event){
            var gssMask = $(event.currentTarget);
            var $target = gssMask.closest('.dylist-item');
            if($target.hasClass('gss-select')) return false;
            gssMask.addClass('gss-hover');
            jConfirm('确定要格式刷该单元？', '提醒', function(result){
                if(result){
                    var info = $.MC.body.formatGSS('gssInfo');
                    var hash = $target.attr('hash');
                    $.MC.mb.mask('gssSave', hash, info);
                    $target.addClass('gss-hasset');
                }
                gssMask.removeClass('gss-hover');
            }).position($target, {left : 155, top : -($('body').scrollTop() - 80 + 30), position : 'fixed'});
            return false;
        },

        gssRemove : function(){
            this.element.find('.gss-select').removeClass('gss-select');
            this.element.find('.gss-hasset').removeClass('gss-hasset');
            this.innerState('normal');
        },

        innerState : function(state){
            if(state == 'normal'){
                this.inner.removeClass('gss-state').addClass('normal-state');
            }else{
                this.inner.removeClass('normal-state').addClass('gss-state');
            }
        },

        refreshState : function(){
            var widget = this.element;
            $.each($.MC.info.cellNew, function(i, n){
                widget.find('.dylist-item[hash="'+ i +'"] em').removeClass('yes no').addClass(n['cell_mode'] > 0 ? 'yes' : 'no');
            });
        },

        _destroy : function(){

        }
    });

    $.widget('magic.formatGSS', {
        options : {
        },

        _create : function(){

        },

        _init : function(){
            var _this = this;
            $(document).on({
                keydown : function(event, cdEvent){
                    if(_this.doing){
                        var keyCode = cdEvent ? cdEvent.keyCode : event.keyCode;
                        if(keyCode == 27){
                            _this.end();
                        }
                    }
                }
            });

            $.iframe.getContent().on({
                keydown : function(event){
                    if(_this.doing){
                        $(this.defaultView.parent.document).trigger('keydown', [event]);
                    }
                }
            });
        },

        start : function(info){
            this.element.addClass('gss');
            this._gssInfo = info;
            this.doing = true;
            this._startTip();
        },

        end : function(){
            this.element.removeClass('gss');
            $.MC.dylist.dylist('gssRemove');
            this._gssInfo = null;
            this.doing = false;
            this._endTip();
        },

        _startTip : function(){
            if(!this._tip){
                this._tip = $('<div/>').appendTo('body').css({
                    position : 'fixed',
                    left : '0px',
                    top : '30px',
                    'z-index' : 100000,
                    width : '150px',
                    'text-align' : 'center',
                    background : 'yellow',
                    padding : '10px 0'
                });
            }
            this._tip.stop().fadeIn().html('格式刷开始，按ESC取消');
        },

        _endTip : function(){
            this._tip && this._tip.html('格式刷结束').fadeOut(3000);
        },

        gssInfo : function(){
            return $.extend({}, this._gssInfo);
        },

        _destroy : function(){

        }
    });
})(jQuery);


(function($){
    var tpl = '<div contenteditable="true" data-id="{{= current.id}}">{{= current.name}}</div><ul>{{each list}}<li data-id="{{= $value.id}}" data-name="{{= $value.name}}">{{= $value.name}}</li>{{/each}}</ul>';
    $.template('my-auto', tpl);
    $.fn.myAuto = function(options){
        options = $.extend({
            current : '',
            list : '',
            cname : 'on'
        }, options);

        return this.each(function(){
            $.tmpl('my-auto', options).appendTo(this);
            var $this = $(this);
            var current = $this.find('div');
            var list = $this.find('ul');
            var cname = options['cname'];
            $this.on({
                mouseenter : function(){
                    list.show();
                },

                mouseleave : function(){
                    list.hide();
                }
            });

            $this.on({
                click : function(){
                    var _$this = $(this);
                    current.attr('data-id', _$this.data('id'));
                    current.html(_$this.data('name'));
                    list.hide();
                }
            }, 'li');

            $this.on({
                focus : function(){
                    $(this).addClass(cname);
                },

                blur : function(){
                    $(this).removeClass(cname);
                }
            }, 'div');
        });
    }
})(jQuery);

(function($){
    $.template('my-tip', '<div class="my-tip-box"><div class="my-tip-inner m2o-transition">{{= tip}}</div></div>');
    $.fn.myTip = function(options){
        options = $.extend({
            string : '提示',
            cname : 'on',
            delay : 1000,
            dtop : 0,
            dleft : 0,
            color : ''
        }, options);

        return this.each(function(){
            var tip = $.tmpl('my-tip', {tip : options['string']}).appendTo('body');
            var inner = tip.find('.my-tip-inner');
            var on = options['cname'];
            var delay = options['delay'];
            var dleft = options['dleft'];
            var dtop = options['dtop'];
            var color = options['color'];
            var $this = $(this);
            if(color){
                inner.css('background-color', color);
            }
            var position = $this.offset();
            var width = $this.outerWidth(true);
            var height = $this.outerHeight(true);
            tip.css({
                left : position.left + width / 2 + dleft + 'px',
                top : position.top + dtop + 'px'
            });
            setTimeout(function(){
                inner.addClass(on);
            }, 1);
            setTimeout(function(){
                inner.removeClass(on);
                setTimeout(function(){
                    tip.remove();
                }, 500);
            }, delay || 1300);
        });
    }
})(jQuery);