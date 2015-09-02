(function($){
    $.widget('plan.stream', $.plan.catlist, {
        options : {
            cat : '.stream-cat',
            'cat-inner' : '.stream-cat-inner',
            'cat-ajax-url' : '',
            'cat-item' : '.stream-cat-item',
            'cat-item-li' : '.stream-cat-li',
            'cat-item-child' : '.stream-cat-child',
            'cat-item-title' : '.stream-cat-title',
            list : '.stream-list',
            'list-ajax-url' : '',
            'list-li' : '.stream-list-li',
            'list-more' : '.stream-list-more',

            'shiyi-ajax-url' : '',

            'cat-item-tpl' : '#stream-cat-tpl',
            'cat-item-place-tpl' : '#stream-cat-place-tpl',
            'list-li-tpl' : '#stream-list-li-tpl',
            'list-more-tpl' : '#stream-list-more-tpl',
            'shiyi-cat-tpl' : '#shiyi-cat-tpl',
            'shiyi-list-tpl' : '#shiyi-list-tpl',

            'default-title' : '全部频道',
            'default-type' : 'stream'
        },

        _listFilter : function(json){
            var _this = this;
            var list = [];
            $.each(json, function(i, n){
                var imgInfo = n['logo_rectangle'];
                var img = imgInfo ? [imgInfo['host'], imgInfo['dir'], imgInfo['filepath'], imgInfo['filename']].join('') : '';
                list.push({
                    id : n.id,
                    src : img,
                    title :n.name,
                    name :n.name
                });
                _this._addCacheJSON(n);
            });
            return list;
        },

        _jsonChange : function(json){
            json['duration'] = 60 * 60;
            json['title'] = json['name'];
            json['img'] = json['logo_rectangle_url'];
        },

        _initHook : function(){
            var _this = this;

            _this.selectPindao = {};
            this.element.on({
                click : function(){
                    if(_this.ctrlKey){
                        return;
                    }
                    var id = $(this).attr('_id');
                    _this._shiyiAjax(id);
                    _this.element.addClass('hover');
                    _this.selectPindao.id = id;
                    _this.selectPindao.name = $(this).attr('_name');
                    _this._shiyiCurrent();
                }
            }, '.stream-list-li');

            this.element.on({
                click : function(){
                    _this.element.removeClass('hover');
                }
            }, '.shiyi-back-all');

            this.shiyiCat = this.element.find('.shiyi-cat');
            this.shiyiList = this.element.find('.shiyi-content');

            this.shiyiCat.on({
                click : function(){
                    if($(this).hasClass('on')) return;
                    $(this).addClass('on').siblings().removeClass('on');
                    _this.shiyiList.find('.shiyi-list-item').eq(_this.shiyiCat.find('li').index(this)).addClass('on').siblings('.on').removeClass('on');
                }
            }, 'li');

            this.shiyiPindaoCache = {};
            this.shiyiCache = {};
        },

        _shiyiAjax : function(id){
            var _this = this;
            _this.shiyiCat.hide().empty();
            _this.shiyiList.find('.shiyi-list-li').draggable('destroy');
            _this.shiyiList.html(_this.options['img-loading']);
            var shiyiPindao = _this._getShiyiPindaoCache(id);
            if(shiyiPindao){
                _this._shiyiCallback(shiyiPindao);
                return;
            }
            $.getJSON(
                _this.options['shiyi-ajax-url'],
                {channel_id : id},
                function(json){
                    _this._addShiyiPindaoCache(id, json[0]);
                    _this._shiyiCallback(json[0]);
                }
            );
        },

        _shiyiCallback : function(data){
            var _this = this;
            if(data){
                var dates = [];
                $.each(data, function(i, n){
                    dates.push({
                        title : i
                    });
                });
                _this._shiyiCat(dates);
                _this._shiyiList(data);
                _this._shiyiDrag();

                _this.shiyiCat.find('li:first').trigger('click');
            }
        },

        _shiyiCurrent : function(){
            this.element.find('.shiyi-current').html(this.selectPindao.name);
        },

        _shiyiCat : function(dates){
            $(this.options['shiyi-cat-tpl']).tmpl({
                list : dates
            }).appendTo(this.shiyiCat.show());
        },

        _shiyiList : function(data){
            $(this.options['shiyi-list-tpl']).tmpl({
                items : data
            }).appendTo(this.shiyiList.empty());
        },

        _shiyiDrag : function(){
            var _this = this;
            _this.shiyiList.find('.shiyi-list-li').draggable({
                helper : function(){
                    return '<div class="shiyi-helper">' + $(this).html() + '</div>';
                },
                appendTo : 'body',
                connectToSortable : this.options['connectToSortable'],
                revert : 'invalid',
                revertDuration : 100,
                zIndex : 100000,
                start : function(event, ui){
                    var id = $(this).attr('_id');
                    var info = _this._createShiyiInfo(id);
                    $(this).data('info', info);
                    var item = _this._getDragHelper(info);
                    $(this).data('item', item);
                },

                toSortable : function(event, ui){
                    var inst = $(this).data('ui-draggable');
                    var item = $(this).data('item');
                    var info = $(this).data('info');
                    var _this = this;
                    $.each(inst.sortables, function(){
                        this.instance.currentItem.remove();
                        this.instance.currentItem = $(item).appendTo(this.instance.element).data('ui-sortable-item', true).hide();
                        this.instance.bindings.plans('addData', this.instance.currentItem.attr('_hash'), info);
                    });
                    ui.helper.css({
                        width : $(this).width() + 'px',
                        height : $(this).height() + 'px'
                    });
                },

                stop : function(){
                },

                fromSortable : function(){

                }
            });
        },

        _addShiyiPindaoCache : function(id, data){
            var _this = this;
            _this.shiyiPindaoCache[id] = data;
            if(data){
                $.each(data, function(i, n){
                    $.each(n, function(ii, nn){
                        $.each(nn, function(iii, nnn){
                            _this._addShiyiCache(nnn);
                        });
                    });
                });
            }
        },

        _getShiyiPindaoCache : function(id){
            return this.shiyiPindaoCache[id];
        },

        _addShiyiCache : function(data){
            data['title'] = data['theme'];
            data['duration'] = parseInt(data['toff']);
            this.shiyiCache[data['id']] = data;
        },

        _getShiyiCache : function(id){
            return this.shiyiCache[id];
        },

        _createShiyiInfo : function(id){
            return {
                type : 'shiyi',
                data :$.extend({}, this._getShiyiCache(id))
            };
        }
    });
})(jQuery);