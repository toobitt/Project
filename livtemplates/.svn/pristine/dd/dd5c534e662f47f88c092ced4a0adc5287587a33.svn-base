(function($){
    $.widget('plan.file', {
        options : {
            cat : '.file-cat',
            'cat-inner' : '.file-cat-inner',
            'cat-ajax-url' : 'run.php?mid=' + gMid + '&a=get_video_node&fid={{fid}}',
            'cat-item' : '.file-cat-item',
            'cat-item-li' : '.file-cat-li',
            'cat-item-child' : '.file-cat-child',
            'cat-item-title' : '.file-cat-title',
            list : '.file-list',
            'list-ajax-url' : 'run.php?mid=' + gMid + '&a=select_videos&start={{start}}&num=15&_type={{cat}}&title={{title}}&date_search={{date}}',
            'list-li' : '.file-list-li',
            'list-more' : '.file-list-more',

            'cat-item-tpl' : '#file-cat-tpl',
            'cat-item-place-tpl' : '#file-cat-place-tpl',
            'list-li-tpl' : '#file-list-li-tpl',
            'list-more-tpl' : '#file-list-more-tpl',

            connectToSortable : '#drop-box',
            plans : '#drop-box'
        },

        _create : function(){
            this.cat = this.element.find(this.options['cat']);
            this.catInner = this.element.find(this.options['cat-inner']);
            this.list = this.element.find(this.options['list']);

            this.plans = $(this.options['plans']);

            this.cacheJSON = {};
        },

        _init : function(){
            var handlers;

            handlers = {};
            handlers['click ' + this.options['cat-item-li']] = '_catOn';
            this._on(handlers);

            handlers = {};
            handlers['click ' + this.options['cat-item-child']] = '_catAjax';
            this._on(handlers);


            handlers = {};
            handlers['click ' + this.options['cat-item-title']] = '_catBack';
            this._on(handlers);

            handlers = {};
            handlers['click ' + this.options['list-more']] = '_listMoreClick';
            this._on(handlers);

            this._catAjax(null, 0);

            var _this = this;
            this.list.disableSelection();

            $(document).on({
                keydown : function(event){
                    if(event.ctrlKey || event.metaKey){
                        _this.ctrlKey = true;
                    }
                },

                keyup : function(event){
                    _this.ctrlKey = false;
                }
            });
        },

        _catOn : function(event){
            var target = $(event.currentTarget);
            var catType = target.attr('_fid');
            var _this = this;
            var ajax = function(){
                _this.catType = catType;
                _this._listAjax(true);
            };
            if(target.hasClass('on')){
                if(this.catType != catType){
                    ajax();
                }
                return false;
            }
            target.addClass('on').siblings().removeClass('on');
            ajax();
        },


        _catAjax : function(event, fid){
            var name = '全部新闻';
            if(typeof fid == 'undefined'){
                var target = $(event.currentTarget).closest(this.options['cat-item-li']);
                fid = target.attr('_fid');
                name = '返回' + target.attr('_name');
            }
            this._catAjaxAnimate(fid, name);
            var url = this._replace(this.options['cat-ajax-url'], {fid : fid});
            var _this = this;
            this.fid = fid;
            $.getJSON(
                url,
                function(json){
                    if(_this.fid != fid){
                        return;
                    }
                    json = json[0];
                    _this._catAjaxCallback(fid, name, json);
                }
            );
            return false;
        },

        _catAnimate : function(direction, callback){
            var catItems = this.catInner.find(this.options['cat-item']);
            var catWidth = catItems.eq(0).outerWidth(true);
            var catItemsLength = catItems.length;
            var left, dis, cb;
            if(direction == 'left'){
                dis = 2;
                cb = function(){
                    catItems.eq(catItemsLength - 1).remove();
                };
            }else{
                dis = 1;
            }
            left = catWidth * (catItemsLength - dis);
            if(left < 0){
                left = 0;
            }
            this.catInner.animate({
                left : - left + 'px'
            }, 100, function(){
                cb && cb();
                callback && callback();
            });
        },

        _catAjaxAnimate : function(fid, title){
            $(this.options['cat-item-place-tpl']).tmpl({
                fid : fid,
                title : title,
                img : '<img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>'
            }).appendTo(this.catInner);
            this._catAnimate('right');
        },

        _catAjaxCallback : function(fid, title, json){
            var list = [];
            $.each(json, function(i, n){
                list.push({
                    name : n.name,
                    fid : n.id,
                    child : parseInt(n.is_last) ? false : true
                });
            });
            $(this.options['cat-item-tpl']).tmpl({
                fid : fid,
                list : list,
                title : title
            }).appendTo(this.catInner).prev().remove();
            this.cat.find(this.options['cat-item'] + ':last').find(this.options['cat-item-li'] + ':first').click();
        },

        _catBack : function(event){
            var fid = parseInt($(event.currentTarget).attr('_fid'));
            if(!fid){
                return false;
            }
            this.cat.find(this.options['cat-item-li'] + '[_fid="'+ fid +'"]').click();
            this._catAnimate('left');
        },

        _listAjax : function(empty){
            var start;
            if(empty){
                start = 0;
                this._listEmpty();
            }else{
                start = this.list.find(this.options['list-li']).length;
            }
            var url = this._replace(this.options['list-ajax-url'], {
                cat : this.catType,
                start : start,
                title : '',
                date : ''
            });
            var _this = this;
            $.getJSON(
                url,
                function(json){
                    json = json[0];
                    _this._listAjaxCallback(json);
                }
            );
        },

        _listEmpty : function(){
            this.list.find(this.options['list-li']).draggable('destroy').remove();
            this.list.find(this.options['list-more']).hide();
        },

        _listAjaxCallback : function(json){
            var list = [];
            if(json){
                var _this = this;
                $.each(json, function(i, n){
                    list.push({
                        id : n.id,
                        src : n.img,
                        title :n.title
                    });
                    _this._addCacheJSON(n);
                });
                $(this.options['list-li-tpl']).tmpl({
                    list : list
                }).appendTo(this.list);
            }
            this._listMore(list.length == 15 ? 'show' : 'hide');
            this._listOnDrag();
        },

        _listMore : function(status){
            var more = this.list.find(this.options['list-more']);
            if(!more[0]){
                if(status == 'show'){
                    $(this.options['list-more-tpl']).tmpl().appendTo(this.list);
                }
            }else{
                status == 'show' ? more.appendTo(this.list).show() : more.hide();
            }
        },

        _listMoreClick : function(){
            this._listAjax();
        },

        _listOnDrag : function(){
            var _this = this;
            var items = this.list.find(this.options['list-li']).filter(function(){
                return !$(this).is(':ui-draggable');
            });

            items.on({
                mousedown : function(){
                    if(_this.ctrlKey){
                        _this._select($(event.currentTarget));
                        $(this).draggable('disable', true);
                    }
                },

                mouseup : function(){
                    $(this).draggable('enable', true);
                }
            });

            items.draggable({
                helper : 'clone',
                appendTo : 'body',
                connectToSortable : this.options['connectToSortable'],
                revert : 'invalid',
                revertDuration : 100,
                zIndex : 100000,
                start : function(){
                    var id = $(this).attr('_id');
                    var info = _this._createInfo(id);
                    $(this).data('info', info);
                    var item = _this._getDragHelper(info);
                    $(this).data('item', item);

                },

                toSortable : function(){
                    var inst = $(this).data('ui-draggable');
                    var item = $(this).data('item');
                    var info = $(this).data('info');
                    var _this = this;
                    $.each(inst.sortables, function(){
                        this.instance.currentItem.remove();
                        this.instance.currentItem = $(item).appendTo(this.instance.element).data('ui-sortable-item', true).hide().data('info', info);
                    });
                },

                fromSortable : function(){

                }
            });



        },

        _addCacheJSON : function(json){
            this.cacheJSON[json['id']] = json;
        },

        getCachecJSON : function(id){
            return this.cacheJSON[id];
        },

        _createInfo : function(id){
            return {
                type : 'file',
                data : this.getCachecJSON(id)
            };
        },

        _getDragHelper : function(info){
            return this.plans.plans('getDragHelper', info);
        },

        dragEnable : function(){
            this.dragCanDo = true;
            this.list.find(this.options['list-li']).draggable('enable');
        },

        dragDisable : function(){
            this.dragCanDo = false;
            this.list.find(this.options['list-li']).draggable('disable');
        },

        _replace : function(tpl, data){
            return tpl.replace(/{{([a-z]+)}}/ig, function(all, match){
                return data[match];
            });
        },

        _select : function(target){
            var info = this._createInfo(target.attr('_id'));
            var targetOffset = target.offset();
            var clone = $(target.clone()).appendTo('body').css({
                position : 'absolute',
                'z-index' : 100000,
                left : targetOffset.left + 'px',
                top : targetOffset.top + 'px'
            });
            this.plans.plans('fastAdd', clone, info);
        },

        _destroy : function(){

        }
    });
})(jQuery);