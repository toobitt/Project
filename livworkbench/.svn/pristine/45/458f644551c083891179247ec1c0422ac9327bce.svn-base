(function($){
    $.layoutConfig = {
        proxy : './proxy.php'
    };

    $.iframe && $.extend($.iframe, {
        checkCell : function(info){
            return info.using_block > 0;
        },

        xiufuIframe : function(){
            this.getContent().find('body').on({
                click : function(event){
                    event.preventDefault();
                }
            }, 'a');
        },

        jt : function(node, cb){
            html2canvas(node, {
                proxy : $.layoutConfig.proxy,
                onrendered : cb
            });
        },

        postJT : function($this, data, cb){
            $.post(
                $.jtConfig.url,
                $.extend(data, $.jtConfig.ext),
                function(){
                    $this.myTip({
                        string : '截图成功！',
                        delay : 1500,
                        dtop : 50
                    });
                    cb && cb();
                }
            );
        }
    });

    $(function($){
        //全局的manage center 简称 MC
        $.MC = {
            it : $('#iframe'),
            mb : $('#mask-box'),

            sourceBox : $('#source-box'),
            startBlockBox : $('#start-block-box'),

            picBox : $('#pic-box'),

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

                    $.MC.sourceBox.source({
                        previewUrl : './magic.php?a=cellPreview&html=1',
                        ajaxUrl : './plug.php?a=getBlockData',
                        saveUrl : './plug.php?a=updataBlockAndData&html=1',
                        itemTpl : $('#source-item-tpl').html(),
                        columnItemTpl : $('#source-column-item-tpl').html(),
                        'upload-url' : './plug.php?a=uploadIndexPic',
                        'upload-phpkey' : 'Filedata'

                    });

                    $.picConfig = {
                        url : './plug.php?a=get_icons',
                        'upload-url' : './plug.php?a=upload_icon',
                        'upload-phpkey' : 'Filedata',
                        'page-num' : 9,
                        tpl : $('#pic-item-tpl').html()
                    };

                    $.jtConfig = {
                        url : './plug.php?a=updateBlockIndexPic',
                        ext : {
                            site_id : json['site_id'],
                            page_data_id : json['page_data_id'],
                            page_id : json['page_id'],
                            content_type : json['content_type'],
                            client_type : json['client_type']
                        }
                    };

                    $('#jt').click(function(){
                        var load = $.globalLoad(this);
                        var $this = $(this);
                        $.iframe.jt($.iframe.getContent().find('body'), function(canvas){
                            $.iframe.postJT($this, {
                                indexpic : canvas.toDataURL('image/png')
                            }, function(){
                                load();
                            });
                        });
                    });
                }
            );
        });
    });


    $.widget('block.source', {
        options : {
            previewUrl : '',
            ajaxUrl : '',
            saveUrl : '',
            itemTname : 'item-tpl',
            itemTpl : '',
            columnItemTname : 'column-item-tpl',
            columnItemTpl : '',
            'upload-url' : '',
            'upload-phpkey' : ''
        },

        _create : function(){
            var widget = this.element;
            this.list = widget.find('.list');
            this.rowInfo = widget.find('.row-info');
            this.columnInfo = widget.find('.column-info');
            $.template(this.options.itemTname, this.options.itemTpl);
            $.template(this.options.columnItemTname, this.options.columnItemTpl);

            this.autoUpdate = widget.find('.auto-update');
            this.autoUpdateTime = widget.find('.auto-update-time');
            this.autoSend = widget.find('.auto-send');
            this.preview = widget.find('.preview');

            this.zksq = widget.find('.zksq');

            this.cacheData = {};
        },

        _init : function(){
            var _this = this;
            this.rowInfo.row({
                source : this.widget()
            });
            this.columnInfo.column({
                source : this.widget(),
                'upload-url' : this.options['upload-url'],
                'upload-phpkey' : this.options['upload-phpkey']
            });

            this.list.sortable({
                items : '.source-item',
                tolerance : 'pointer',
                axis : 'y',
                zIndex : 10
            });

            this._on({
                'click .option' : '_rowClick',
                'click .column' : '_columnClick',
                'click .preview' : '_preview',
                'click .ok' : '_submit',
                'click .add' : '_add',
                'click .add-row' : '_addRow',
                'click .qk-jt' : '_jt',
                'click .sq' : '_sq',
                'click .zk' : '_zk'
            });

            $.pop({
                handlerName : '拖拽选择',
                drag : true,
                connectToSortable : '.source-item',
                widget : 'pubLib',
                className : 'pubLib-pop-box',
                list_url : mainConfig.datasourceUrl,
                site_url : mainConfig.siteUrl,
                module_url : mainConfig.moduleUrl,
                column_url : mainConfig.columnUrl,
                clickCall : function(event, info){
                }
            });
            this.datasource = $('.pubLib-pop-box');
            this.datasource.pubLib('hide');
        },

        _sortColumn : function(){
            var _this = this;
            this.list.find('.source-item').filter(function(){
                return !$(this).is(':ui-sortable');
            }).sortable({
                tolerance : 'pointer',
                placeholder : 'placeholder',
                connectWith : '.source-item',
                items : '.column',
                start : function(){

                },
                sort : function(event, ui){
                    ui.placeholder.css('width', ui.helper.outerWidth() + 'px');
                },
                receive : function(event, ui){
                    if(ui.helper){
                        var info = JSON.parse(decodeURIComponent(ui.helper.attr('info')));
                        _this._addColumn({
                            id : 0,
                            content_id : info['id'],
                            cid : info['cid'],
                            content_fromid : info['content_fromid'],
                            bundle_id : info['bundle_id'],
                            module_id : info['module_id'],
                            title : info['title'],
                            link : info['content_url'],
                            des : info['brief'],
                            img : info['indexpic'] && info['indexpic']['host'] ? $.globalImgUrl(info['indexpic'], '100x') : '',
                            pic : info['pic'],

                            color : '',
                            size : '',
                            bold : 0,
                            bgcolor : '',
                            pre_wz : '',
                            pre_img : '',
                            pre_img_yulan : '',
                            pre_link : '',
                            after_wz : '',
                            after_img : '',
                            after_img_yulan : '',
                            after_link : ''
                        });
                    }
                },
                stop : function(){

                }
            });
        },

        _add : function(){
            var position;
            if(!this._firstAdd){
                this._firstAdd = true;
                position = {
                    position : 'fixed',
                    left : 'auto',
                    right : '400px',
                    top : '10px',
                    'margin' : 0
                }
            }
            this.datasource.pubLib('show', position);
        },

        _getRow : function(hash){
            return this.list.find('.source-item[_hash="'+ hash +'"]');
        },

        _addRow : function(){
            var item = $.tmpl(this.options.itemTname, {}).appendTo(this.list);
            var _rowHash = this._hash();
            item.attr('_hash', _rowHash);
            this.data(_rowHash, {});
            this.list.scrollTop(10000);
            this._sortColumn();
        },

        _changeRow : function(info, hash){
            var _oldRow = this._getRow(hash);
            info['placeholder'] = true;
            var _newRow = $.tmpl(this.options.itemTname, info).attr('hash', hash);
            _newRow.insertAfter(_oldRow);
            _newRow.find('.placeholder').replaceWith(_oldRow.find('.column'));
            _oldRow.remove();
            this._sortColumn();
        },

        removeRow : function(hash){
            this._getRow(hash).remove();
            this._cleanData(hash);
        },

        _getColumn : function(hash){
            return this.list.find('.column[_hash="'+ hash +'"]');
        },

        _addColumn : function(info, replace){
            var item = $.tmpl(this.options.columnItemTname, info || {});
            item.replaceAll(replace || this.list.find('.ui-draggable'));
            var _columnHash = this._hash();
            item.attr('_hash', _columnHash);
            this.data(_columnHash, info || {});
        },

        removeColumn : function(hash){
            this._getColumn(hash).remove();
            this._cleanData(hash);
        },

        _rowClick : function(event){
            var item = $(event.currentTarget).closest('li');
            var index = item.attr('data-index');
            this.rowInfo.row('show', item);
        },

        _columnClick : function(event){
            var item = $(event.currentTarget);
            var id = item.attr('data-id');
            this.columnInfo.column('show', item);
        },

        save : function(hash, info, type){
            info = $.extend({}, this.data(hash), info);
            this.data(hash, info);
            if(type == 'column'){
                this._addColumn(info, this._getColumn(hash));
            }else{
                this._changeRow(info, hash);
            }
        },

        _post : function(dom, url, callback){
            var _this = this;
            var _updateType = this.autoUpdate.prop('checked') ? 1 : 0;
            var data = {
                block : {
                    block_id : this.blockId,
                    update_type : _updateType,
                    update_time : _updateType ? this.autoUpdateTime.val() : 0,
                    is_support_push : this.autoSend.prop('checked') ? 1 : 0
                },
                block_line : [],
                content : []
            };
            this.list.find('li').each(function(i, n){
                var $columns = $(this).find('.column');
                if(!$columns.length) return;
                var _rowInfo = _this.data($(this).attr('_hash'));
                var _row = i + 1;
                var _changeRowInfo = {
                    font_color : _rowInfo['color'],
                    font_size : _rowInfo['size'],
                    font_b : _rowInfo['bold'],
                    font_backcolor : _rowInfo['bgcolor'],
                    before_wz : _rowInfo['pre_wz'],
                    before_img : _rowInfo['pre_imginfo'],
                    before_link : _rowInfo['pre_link'],
                    after_wz : _rowInfo['after_wz'],
                    after_img : _rowInfo['after_imginfo'],
                    after_link : _rowInfo['after_link'],
                    line : _row
                };
                data['block_line'].push(_changeRowInfo);
                var _columns = [];
                $columns.each(function(ii, nn){
                    var _column = _this.data($(this).attr('_hash'));
                    var _changeColumn = {
                        font_color : _column['color'],
                        font_size : _column['size'],
                        font_b : _column['bold'],
                        font_backcolor : _column['bgcolor'],
                        before_wz : _column['pre_wz'],
                        before_img : _column['pre_imginfo'],
                        before_link : _column['pre_link'],
                        after_wz : _column['after_wz'],
                        after_img : _column['after_imginfo'],
                        after_link : _column['after_link'],

                        id : _column['id'],
                        content_id : _column['content_id'],
                        cid : _column['cid'],
                        content_fromid : _column['content_fromid'],
                        bundle_id : _column['bundle_id'],
                        module_id : _column['module_id'],
                        title : _column['title'],
                        brief : _column['des'],
                        outlink : _column['link'],
                        indexpic : _column['indexpic'],

                        line : _row,
                        child_line : ii + 1
                    };
                    _columns.push(_changeColumn);
                });
                data['content'].push(_columns);
            });
            $.globalAjax(dom, function(){
                return $.post(
                    url,
                    {
                        cell_id : _this.currentInfo['id'],
                        data : JSON.stringify(data)
                    },
                    function(json){
                        //console.log(json);
                        callback && callback(json[0]);
                    },
                    'json'
                );
            });
        },

        _submit : function(event){
            var $this = $(event.currentTarget);
            this._post(event.currentTarget, this.options.saveUrl, function(data){
                $.MC.mb.mask('update', data);
                $this.myTip({
                    string : '保存成功！',
                    delay : 1500,
                    dtop : 0
                });
            });
            this._previewDo(false);
        },

        _preview : function(event){
            var $target = event ? $(event.currentTarget) : this.preview;
            if($target.data('previewing')){
                this._previewDo(false);
                $.MC.mb.mask('cancelPreview');
            }else{
                this._previewDo(true);
                this._post(event.currentTarget, this.options.previewUrl, function(data){
                    $.MC.mb.mask('preview', data);
                });
            }
        },

        _previewDo : function(state){
            if(state){
                this.preview.data('previewing', true).html(this.preview.data('ing'));
                this.element.find('.add-row, .bodyer, .add, .ok').addClass('none-event');
            }else{
                this.preview.data('previewing', false).html(this.preview.data('normal'));
                this.element.find('.add-row, .bodyer, .add, .ok').removeClass('none-event');
            }
        },

        refresh : function(json, blockId){
            this._previewDo(false);
            var cahce = this.listCache = {};
            //console.log(json, blockId);
            this.blockId = blockId;
            var blockInfo = this.blockInfo = json['block']['block'][blockId];
            if(blockInfo['update_type'] > 0){
                this.autoUpdate.prop('checked', true);
                this.autoUpdateTime.val(blockInfo['update_time']);
            }
            if(blockInfo['is_support_push'] > 0){
                this.autoSend.prop('checked', true);
            }
            var rows = json['block_line'][blockId];
            var columns = json['content'][blockId];
            var list = [];
            rows && $.each(rows, function(row, val){
                var rowInfo = {
                    color : val['font_color'],
                    size : val['font_size'],
                    bold : val['font_b'],
                    bgcolor : val['font_backcolor'],
                    pre_wz : val['before_wz'],
                    pre_img : val['before_img'] ? val['before_img']['url'] : '',
                    pre_img_yulan : val['before_img'] ? val['before_img']['real_url'] : '',
                    pre_imginfo : val['before_img'],
                    pre_link : val['before_link'],
                    after_wz : val['after_wz'],
                    after_img : val['after_img'] ? val['after_img']['url'] : '',
                    after_img_yulan : val['after_img'] ? val['after_img']['real_url'] : '',
                    after_imginfo : val['after_img'],
                    after_link : val['after_link'],
                    column : []
                };
                columns[row] && $.each(columns[row], function(index, val){
                    var _column = {
                        color : val['font_color'],
                        size : val['font_size'],
                        bold : val['font_b'],
                        bgcolor : val['font_backcolor'],
                        pre_wz : val['before_wz'],
                        pre_img : val['before_img'] ? val['before_img']['url'] : '',
                        pre_img_yulan : val['before_img'] ? val['before_img']['real_url'] : '',
                        pre_imginfo : val['before_img'],
                        pre_link : val['before_link'],
                        after_wz : val['after_wz'],
                        after_img : val['after_img'] ? val['after_img']['url'] : '',
                        after_img_yulan : val['after_img'] ? val['after_img']['real_url'] : '',
                        after_imginfo : val['after_img'],
                        after_link : val['after_link'],
                        id : val['id'],
                        content_id : val['content_id'],
                        cid : val['cid'],
                        content_fromid : val['content_fromid'],
                        bundle_id : val['bundle_id'],
                        module_id : val['module_id'],
                        title : val['title'],
                        des : val['brief'],
                        link : val['outlink'],
                        indexpic : val['indexpic']
                    };
                    rowInfo['column'].push(_column);
                });
                list.push(rowInfo);
            });
            $.tmpl(this.options.itemTname, list).appendTo(this.list);
            var _this = this;
            this.list.find('li').each(function(_row, n){
                var rowInfo = list[_row];
                var _rowHash = _this._hash();
                var _rowData = $.extend({}, rowInfo);
                _rowData['column'] = null;
                _this.data(_rowHash, _rowData);
                $(this).attr('_hash', _rowHash);
                $(this).find('.column').each(function(_column, nn){
                    var columnInfo = rowInfo['column'][_column];
                    var _columnHash = _this._hash();
                    var _columnData = $.extend({}, columnInfo);
                    _this.data(_columnHash, _columnData);
                    $(this).attr('_hash', _columnHash);
                });
            });
            this._sortColumn();

        },



        data : function(hash, data){
            if($.type(data) == 'undefined'){
                return this.cacheData[hash];
            }
            this.cacheData[hash] = data;
        },

        _cleanData : function(hash){
            if(hash){
                delete this.cacheData[hash];
                return;
            }
            this.cacheData = {};
        },

        _ajax : function(blockId){
            var _this = this;
            this._delay(function(){
                $.globalAjax(_this.element, function(){
                    return $.getJSON(
                        _this.options.ajaxUrl,
                        {block_id : blockId},
                        function(json){
                            _this.refresh(json[0], blockId);
                        }
                    );
                });
            }, 300);
        },

        _zk : function(){
            this.zksq.removeClass('click');
            this.element.addClass('open');
        },

        _sq : function(){
            this.zksq.addClass('click');
            this.element.removeClass('open');
        },

        show : function(info){
            this._cleanData();
            this._empty();
            this.currentInfo = info;
            this._ajax(info['block_id']);
            !this.element.hasClass('open') && this.element.addClass('open');
            this.zksq.addClass('on');
        },

        hide : function(){
            if(this.preview.data('previewing')){
                this._preview();
            }
            this.element.removeClass('open');
            this._empty();
            this.rowInfo.row('hide');
            this.columnInfo.column('hide');
            this.zksq.removeClass('on').removeClass('click');

        },

        _hash : function(){
            if(!this.uqid) this.uqid = 0;
            return 'hash' + ++this.uqid;
            return +new Date() + '' + Math.ceil(Math.random() * 10000);
        },

        _empty : function(){
            this.list.add(this.info).empty();
        },

        manageTC : function(type){
            if(type == 'row'){
                this.columnInfo.column('hide');
            }else{
                this.rowInfo.row('hide');
            }
        },

        _jt : function(event){
            var _this = this;
            var mask = $.MC.mb.mask('currentMask');
            var hash = mask.attr('hash');
            var node = $.iframe.getContent().find('.' + hash);
            if(node[0]){
                var load = $.globalLoad(event.currentTarget);
                var $this = $(event.currentTarget);
                $.iframe.jt(node, function(canvas){
                    $.iframe.postJT($this, {
                        block_id : _this.blockId,
                        indexpic : canvas.toDataURL('image/png')
                    }, function(){
                        load();
                    });
                });
            }

        },

        _destroy : function(){

        }
    });


    $.widget('block.infoBase', {
        options : {
            source : null
        },

        _create : function(){
            var wt = this.element;
            this._color = wt.find('.rc-color');
            this._bold = wt.find('.rc-bold');
            this._bgcolor = wt.find('.rc-bgcolor');
            this._size = wt.find('.rc-size');
            this._prewz = wt.find('.rc-pre-wz');
            this._preimg = wt.find('.rc-pre-img');
            this._preimgYuLan = wt.find('.rc-pre-img-select');
            this._prelink = wt.find('.rc-pre-link');
            this._afterwz = wt.find('.rc-after-wz');
            this._afterimg = wt.find('.rc-after-img');
            this._afterimgYuLan = wt.find('.rc-after-img-select');
            this._afterlink = wt.find('.rc-after-link');



            this._colorVal = null;
            this._boldVal = null;
            this._bgcolorVal = null;

            this._sizeVal = null;
            this._prewzVal = null;
            this._preimgVal = null;
            this._preimgYuLanVal = null;
            this._preimgInfo = null;
            this._prelinkVal = null;
            this._afterwzVal = null;
            this._afterimgVal = null;
            this._afterimgYuLanVal = null;
            this._afterimgInfo = null;
            this._afterlinkVal = null;


        },

        _init : function(){
            var _this = this;

            this.element.draggable();

            this._color.ColorPicker({
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
                    this._doColor(color);
                }, this)
            });

            this._bgcolor.ColorPicker({
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
                    this._doBgcolor(color);
                }, this)
            });


            this._on(this._bold, {
                'click' : '_doClickBold'
            });

            this._on({
                'click .rc-save' : '_save',
                'click .rc-cancel' : 'hide',
                'click .rc-del' : '_del'
            });

            this._on({
                'click .rc-pre-img-select' : '_preImgSelect',
                'click .rc-after-img-select' : '_afterImgSelect',
                'click .rc-del-img' : '_delSelectImg'
            });

            this._on({
                'blur input, textarea' : '_inputBlur'
            });
        },

        _inputBlur : function(event){
            var target = $(event.currentTarget);
            this['_' + target.attr('name') + 'Val'] = target.val();
        },

        _preImgSelect : function(event){
            this._imgSelect(event.currentTarget, 'pre');
        },

        _afterImgSelect : function(event){
            this._imgSelect(event.currentTarget, 'after');
        },

        _doSelectImg : function(type, imgSrc, imgInfo){
            var _this = this;

            _this['_' + type + 'img'].val(imgSrc);
            _this['_' + type + 'imgVal'] = imgSrc;
            _this['_' + type + 'imgYuLanVal'] = imgInfo ? imgInfo['real_url'] : '';
            var yulan = _this['_' + type + 'imgYuLan'];
            if(imgInfo && imgInfo['real_url']){
                yulan.addClass('on').find('img').attr('src', imgInfo['real_url']);
            }else{
                yulan.removeClass('on').find('img').removeAttr('src');
            }
            _this['_' + type + 'imgInfo'] = imgInfo ? imgInfo : null;
        },

        _delSelectImg : function(event){
            var type = $(event.currentTarget).attr('type');
            this._doSelectImg(type, '');
            event.stopPropagation();
            return false;
        },

        _imgSelect : function(dom, type){
            var box = $.MC.picBox;
            !box.is(':magic-pic') && box.pic($.picConfig);
            var _this = this;
            var callback = function(imgSrc, imgInfo){
                /*$(this).prev().val(imgSrc);
                _this['_' + type + 'imgVal'] = imgSrc;
                _this['_' + type + 'imgYuLanVal'] = imgInfo['real_url'];
                var yulan = _this['_' + type + 'imgYuLan'];
                if(imgInfo['real_url']){
                    yulan.addClass('on').find('img').attr('src', imgInfo['real_url']);
                }else{
                    yulan.removeClass('on').find('img').removeAttr('src');
                }
                _this['_' + type + 'imgInfo'] = imgInfo;*/
                _this._doSelectImg(type, imgSrc, imgInfo);
            };
            box.pic('option', 'callback', $.proxy(callback, dom));
            box.pic('refresh', $(dom).prev().val());
        },

        _save : function(event){
            var info = this._allData();
            this.options.source.source('save', this.targetHash, info, $(event.currentTarget).data('type'));
            this.hide();
        },

        _allData : function(){
            return {
                color : this._colorVal,
                bold : this._boldVal,
                bgcolor : this._bgcolorVal,
                size : this._size.val(),
                pre_wz : this._prewz.val(),
                pre_img : this._preimg.val(),
                pre_img_yulan : this._preimgYuLanVal,
                pre_imginfo : this._preimgInfo,
                pre_link : this._prelink.val(),
                after_wz : this._afterwz.val(),
                after_img : this._afterimg.val(),
                after_img_yulan : this._afterimgYuLanVal,
                after_imginfo : this._afterimgInfo,
                after_link : this._afterlink.val()
            }
        },

        _del : function(event){
            this.options.source.source($(event.currentTarget).data('type'), this.targetHash);
            this.hide();
        },

        _doClickBold : function(event){
            var target = $(event.currentTarget);
            this._doBold(!this._boldVal);
        },

        refresh : function(data){
            //console.log(data);
            this._doColor(data['color']);
            this._doBold(data['bold']);
            this._doSize(data['size']);
            this._doBgcolor(data['bgcolor']);
            this._doPreAfter(data['pre_wz'], data['pre_link'], data['pre_img'], data['pre_imginfo'], 'pre');
            this._doPreAfter(data['after_wz'], data['after_link'], data['after_img'], data['after_imginfo'], 'after');
        },

        _doColor : function(color){
            this._colorVal = color;
            this._color.css('backgroundColor', color);
        },

        _doBold : function(bold){
            this._boldVal = bold;
            this._bold.css({
                'border-color' :  bold ? '#333' : '#ccc',
                'font-weight' : bold ? 'bold' : 'normal',
                'color' : bold ? '#333' : '#ccc'
            });
        },

        _doSize : function(size){
            this._size.val(size);
        },

        _doBgcolor : function(color){
            this._bgcolorVal = color;
            this._bgcolor.css('backgroundColor', color);
        },

        _doPreAfter : function(wz, link, url, imgInfo, type){
            this['_' + type + 'wz'].val(wz);
            this['_' + type + 'link'].val(link);
            /*this['_' + type + 'img'].val(url);
            var yulan = this['_' + type + 'imgYuLan'];
            if(realUrl){
                yulan.addClass('on').find('img').attr('src', realUrl);
            }else{
                yulan.removeClass('on').find('img').removeAttr('src');
            }
            this['_' + type + 'imgYuLanVal'] = realUrl;*/
            this._doSelectImg(type, url, imgInfo);
        },

        hide : function(){
            this.element.hide();
            this.targetHash = null;
        },

        _destroy : function(){

        }
    });

    $.widget('block.row', $.block.infoBase, {
        options : {
        },

        _create : function(){
            this._super();
        },

        _init : function(){
            this._super();
        },

        show : function(target){
            this.targetHash = target.attr('_hash');
            var targetPP = target.offset();
            var boxPP = $.MC.sourceBox.offset();
            var left = (targetPP.left - boxPP.left) - 250;
            var top = (targetPP.top - boxPP.top) - 50;
            this.element.css({
                left : left + 'px',
                top : top + 'px'
            });
            this.refresh(this.options.source.source('data', target.attr('_hash')));
            this.element.show();
            this.options.source.source('manageTC', 'row');
        },

        _destroy : function(){

        }
    });

    $.widget('block.column', $.block.infoBase, {
        options : {
        },

        _create : function(){
            this._super();
            var wt = this.element;
            this.file = wt.find('.c-upload');

            this._img = wt.find('.c-img');
            this._title = wt.find('.c-title');
            this._link = wt.find('.c-link');
            this._des = wt.find('.c-des');
        },

        _init : function(){
            this._super();
            var _this = this;
            this._on({
                'click .c-img' : '_upload'
            });
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
        _before : function(info){
            var imgBox = this.element.find('.c-img');
            if(imgBox[0]){
                imgBox.html('<img src="'+ info['data']['result'] +'"/>');
                this.uploading = $.globalLoad(imgBox);
            }

        },

        _after : function(info){
            if(this.uploading){
                this.uploading();
                this.uploading = null;
                //console.log(info);
                this.indexpicVal = info['data'];
            }
        },

        _upload : function(){
            this.file.trigger('click');
        },

        refresh : function(data){
            this._superApply(arguments);

            this._title.val(data['title']);
            this._link.val(data['link']);
            this._des.val(data['des']);
            this._doImg(data['indexpic']);

            this.element.find('.edit-title span').html(data['title']);
        },

        _doImg : function(indexpic){
            this.indexpicVal = indexpic;
            if(indexpic && indexpic['host']){
                this._img.html('<img src="'+ $.globalImgUrl(indexpic, '100x') +'"/>');
            }
        },

        _allData : function(){
            var superData = this._super();
            $.extend(superData, {
                title : this._title.val(),
                des : this._des.val(),
                link : this._link.val(),
                indexpic : this.indexpicVal
            });
            return superData;
        },

        show : function(target){
            this.targetHash = target.attr('_hash');
            var targetPP = target.offset();
            var boxPP = $.MC.sourceBox.offset();
            var left = (targetPP.left - boxPP.left) - 640  + target.width() / 2;
            var top = (targetPP.top - boxPP.top) + 40;
            this.element.css({
                left : left + 'px',
                top : top + 'px'
            });
            this.refresh(this.options.source.source('data', target.attr('_hash')));
            this.element.show();
            this.options.source.source('manageTC', 'column');
        },

        _destroy : function(){

        }
    });
})(jQuery);