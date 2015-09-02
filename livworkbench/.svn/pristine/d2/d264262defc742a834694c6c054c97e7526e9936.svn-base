(function($){

    $.iframe && $.extend($.iframe, {
        checkCell : function(info){
            return !!info.can_edit;
        },

        xiufuIframe : function(){
            $.MC.dylist.dylist({
                tpl : $('#dylist-tpl').html()
            }).show();
        }
    });

    $(function($){
        //全局的manage center 简称 MC
        $.MC = {
            it : $('#iframe'),
            mb : $('#mask-box'),

            sourceBox : $('#source-box'),

            dylist : $('#dylist-box'),

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
                        saveUrl : './magic.php?a=cellDataUpdate&html=1',
                        itemTpl : $('#source-item-tpl').html(),
                        infoTpl : $('#source-info-tpl').html(),
                        'upload-url' : './plug.php?a=uploadIndexPic',
                        'upload-phpkey' : 'Filedata'
                    });
                }
            );
        });

        $('.dylist-inner').addClass('dylist-inner-k');
    });


    $.widget('data.source', {
        options : {
            itemTname : 'item-tpl',
            itemTpl : '',
            infoTname : 'info-tpl',
            infoTpl : '',
            'upload-url' : '',
            'upload-phpkey' : ''
        },

        _create : function(){
            var widget = this.element;
            this.inner = widget.find('.inner');
            this.list = widget.find('.list');
            this.info = widget.find('.info');
            this.file = widget.find('.fileupload');
            $.template(this.options.itemTname, this.options.itemTpl);
            $.template(this.options.infoTname, this.options.infoTpl);

            var _this = this;
            $.pop({
                handlerName : '点击选择',
                widget : 'pubLib',
                className : 'pubLib-pop-box',
                list_url : mainConfig.datasourceUrl,
                site_url : mainConfig.siteUrl,
                module_url : mainConfig.moduleUrl,
                column_url : mainConfig.columnUrl,
                clickCall : function(event, info){
                    _this._doTihuan(info[0] || {});
                }
            });
            this.datasource = $('.pubLib-pop-box');
            this.datasource.pubLib('hide');
        },

        _init : function(){
            this._on({
                'click li' : '_click',
                'click .back' : '_back',
                'click .tihuan' : '_tihuan',
                'click .preview' : '_preview',
                'click .save' : '_save',
                'click .cancel' : '_cancel',
                'click .img' : '_upload'
            });


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

        _before : function(info){
            var imgBox = this.element.find('.img');
            if(imgBox[0]){
                imgBox.html('<img src="'+ info['data']['result'] +'"/>');
                this.uploading = $.globalLoad(imgBox);
            }

        },

        _after : function(info){
            if(this.uploading){
                this.indexpic = info['data'];
                this.uploading();
                this.uploading = null;
            }
        },

        _upload : function(){
            this.file.trigger('click');
        },

        _click : function(event){
            var target = $(event.currentTarget);
            var id = target.data('id');
            this.currentId = id;
            var _cache = this.listCache[id] || {};
            this.indexpic = _cache['indexpic'];
            _cache['img'] = _cache['indexpic'] && _cache['indexpic']['host'] ? $.globalImgUrl(_cache['indexpic'], '100x') : '';
            $.tmpl(this.options.infoTname, _cache).appendTo(this.info.empty());
            this._move('left');
            this.moveNum = (this.moveNum || 0) + 1;
            this.preview = this.element.find('.preview');
            this._previewDo(false);
        },

        _back : function(event){
            this._move('right');
            var _this = this;
            var num = this.moveNum;
            this._delay(function(){
                if(num != _this.moveNum) return;
                _this.info.empty();
            }, 300);
            this.datasource.pubLib('hide');
        },

        _tihuan : function(){
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

        _doTihuan : function(info){
            var data = {
                id : this.currentId,
                title : info['title'],
                url : info['url'],
                des : info['brief'],
                pic : info['pic'],
                indexpic : $.extend({}, info['indexpic']),
                img : info['indexpic'] && info['indexpic']['host'] ? $.globalImgUrl(info['indexpic'], '100x') : ''
            };
            this.listCache[this.currentId] = data;
            $.tmpl(this.options.infoTname, data).appendTo(this.info.empty());
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
            }else{
                this.preview.data('previewing', false).html(this.preview.data('normal'));
            }
        },

        _post : function(dom, url, callback){
            var _this = this;
            var _titleVal = $('#input-title').val();
            var _linkVal = $('#input-link').val();
            var _desVal = $('#input-des').val();
            var data = JSON.stringify({
                content_id : _this.currentId,
                title : _titleVal,
                brief : _desVal,
                content_url : _linkVal,
                indexpic : _this.indexpic
            });
            $.globalAjax(dom, function(){
                return $.post(
                    url,
                    {
                        cell_id : _this.currentCellInfo['id'],
                        data : data
                    },
                    function(json){
                        callback && callback(json[0]);
                    },
                    'json'
                );
            });
        },

        _save : function(event){
            this._previewDo(false);
            var _this = this;
            this._post(event.currentTarget, this.options.saveUrl, function(data){
                $.MC.mb.mask('update', data);
                _this.list.empty();
                _this._refresh(data);
            });
        },

        _cancel : function(){
            this.element.find('.back').trigger('click');
        },

        _move : function(direct){
            this.inner[direct == 'left' ? 'addClass' : 'removeClass']('move');
        },

        refresh : function(info){
            this._refresh(info);
        },

        _refresh : function(info){
            var cache = this.listCache = {};
            this.currentCellInfo = info;
            var list = info['data'] || [];
            $.each(list, function(ii, nn){
                cache[nn['id']] = {
                    id : nn['id'],
                    title : nn['title'],
                    indexpic : $.extend({}, nn['indexpic']),
                    img : nn['indexpic'] && nn['indexpic']['host'] ? $.globalImgUrl(nn['indexpic'], '100x') : '',
                    link : nn['content_url'],
                    des : nn['brief']
                };
            });
            $.tmpl(this.options.itemTname, list).appendTo(this.list);
        },

        show : function(list){
            this._empty();
            this.refresh(list);
            this._move('right');
            !this.element.hasClass('open') && this.element.addClass('open');
        },

        hide : function(){
            if(this.preview && this.preview.data('previewing')){
                this._preview();
            }
            this.element.removeClass('open');
            this._empty();
            this.datasource.pubLib('hide');
        },

        _empty : function(){
            this.list.add(this.info).empty();
        },

        _destroy : function(){

        }
    });
})(jQuery);