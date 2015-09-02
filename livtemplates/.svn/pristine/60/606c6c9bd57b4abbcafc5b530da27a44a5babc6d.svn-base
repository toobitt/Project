(function($){
    $.widget('video.file', {
        options : {
            'cat' : '.file-cat',
            'cat-inner' : '.file-cat-inner',
            'cat-ajax-url' : '',
            'cat-item' : '.file-cat-item',
            'cat-item-li' : '.file-cat-li',
            'cat-item-child' : '.file-cat-child',
            'cat-item-title' : '.file-cat-title',
            'list' : '.file-list',
            'list-ajax-url' : '',
            'list-li' : '.file-list-li',
            'list-more' : '.file-list-more',

            'cat-item-tpl' : '#file-cat-tpl',
            'cat-item-place-tpl' : '#file-cat-place-tpl',
            'list-li-tpl' : '#file-list-li-tpl',
            'list-more-tpl' : '#file-list-more-tpl',

            'default-title' : '全部',
            'default-type' : 'file',

            'img-loading' : '<img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>',

            'need-drag' : false,

            'get-drag' : $.noop,
            'set-drag' : $.noop,
            'drag-helper' : null,

            connectToSortable : '#drop-box',
            plans : '#drop-box',

            'title-box' : '.search',
            'date-box' : '.select-area',
            'page-box' : '.common-page-link'
        },

        _create : function(){
            this.cat = this.element.find(this.options['cat']);
            this.catInner = this.element.find(this.options['cat-inner']);
            this.list = this.element.find(this.options['list']);
            this.plans = $(this.options['plans']);
            this.titleBox = $(this.options['title-box']);
            this.titleVal = '';
            this.whoVal = '';
            this.dateBox = $(this.options['date-box']);
            this.dateVal = 0;
            this.startDateVal = 0;
            this.endDateVal = 0;
            this.pageBox = $(this.options['page-box']);
            this.pageVal = 0;
            this.cacheJSON = {};
        },

        _init : function(){
            var handlers = {};
            handlers['click ' + this.options['cat-item-li']] = '_catOn';
            handlers['click ' + this.options['cat-item-child']] = '_catAjax';
            handlers['click ' + this.options['cat-item-title']] = '_catBack';
            this._on(handlers);
            this._catAjax(null, 0);
            this._initHook();
            this._initTitle();
            this._initWho();
            this.list.disableSelection();
        },

        _initHook : function(){

        },

        _catOn : function(event){
            var target = $(event.currentTarget);
            var catType = target.attr('_fid');
            var _this = this;
            var ajax = function(){
                _this.catType = catType;
                _this.pageVal = 1;
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
            var name = this.options['default-title'];
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
                img : this.options['img-loading']
            }).appendTo(this.catInner);
            this._catAnimate('right');
        },

        _catFilter : function(json){
            var list = [];
            $.each(json, function(i, n){
                list.push({
                    name : n.name,
                    fid : n.id,
                    child : parseInt(n.is_last) ? false : true
                });
            });
            return list;
        },

        _catAjaxCallback : function(fid, title, json){
            var list = this._catFilter(json);
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
            var _this = this;
            if(empty){
                _this._listEmpty();
            }
            _this.pageBox && _this.pageBox.is(':video-page') && _this.pageBox.page('hide');
            var url = _this._replace(_this.options['list-ajax-url'], {
                cat : _this.catType,
                pp : _this.pageVal,
                title : _this.titleVal,
                date : _this.dateVal,
                start_time : _this.startDateVal,
                end_time : _this.endDateVal,
                user_name : _this.whoVal
            });

            _this.list.html('<li class="list-loading">' + _this.options['img-loading'] + '</li>');
            $.getJSON(
                url,
                function(json){
                    if(json[0]['date_search']){
                        _this._initDate(json[0]['date_search']);
                    }
                    if(json[0]['page']){
                        _this._initPage(json[0]['page'][0]);
                    }
                    json = $.type(json[0]['video']) != 'undefined' ? json[0]['video'] : json[0];
                    _this._listAjaxCallback(json);
                }
            );
        },

        _listEmpty : function(){
            var lis = this.list.find(this.options['list-li']);
            if(this.options['need-drag']){
                lis.draggable('destroy');
            }
            lis.remove();
            this.list.find(this.options['list-more']).hide();
        },

        _listFilter : function(json){
            var _this = this;
            var list = [];
            $.each(json, function(i, n){
                list.push({
                    id : n.id,
                    src : n.img,
                    title : n.title,
                    duration : n.duration,
                    mark_count : n['mark_count'],
                    starttime : n['starttime'] ? n['starttime'].replace(/\(|（/, '').replace(/\)|）/, '') : ''
                });
                _this._addCacheJSON(n);
            });
            return list;
        },

        _listAjaxCallback : function(json){
            this.list.empty();
            var list;
            if(json){
                list = this._listFilter(json);
            }
            $(this.options['list-li-tpl']).tmpl({
                list : list
            }).appendTo(this.list);
            this._listOnDrag();
        },

        _listMoreClick : function(){
            this._listAjax();
        },

        _listOnDrag : function(){
            if(!this.options['need-drag']){
                return;
            }

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
                helper : this.options['drag-helper'] && $.type(this.options['drag-helper']) == 'function' ? function(){
                    return _this.options['drag-helper'].apply(this);
                } : 'clone',
                appendTo : 'body',
                connectToSortable : this.options['connectToSortable'],
                revert : 'invalid',
                revertDuration : 100,
                zIndex : 100000,
                start : function(event, ui){
                    var id = $(this).attr('_id');
                    var info = _this._createInfo(id);
                    $(this).data('info', info);
                    var item = _this._getDragHelper(info);
                    $(this).data('item', item);
                    $(ui.helper).attr('_hash', $(item).attr('_hash'));
                },

                toSortable : function(){
                    var inst = $(this).data('ui-draggable');
                    var item = $(this).data('item');
                    var info = $(this).data('info');
                    $.each(inst.sortables, function(){
                        this.instance.currentItem.remove();
                        this.instance.currentItem = $(item).appendTo(this.instance.element).data('ui-sortable-item', true).hide();
                        _this.options['set-drag'].apply(_this, [this.instance.currentItem.attr('_hash'), info]);
                    });
                },

                stop : function(){
                },

                fromSortable : function(){
                }
            });
        },

        _jsonChange : function(json){

        },

        _addCacheJSON : function(json){
            this._jsonChange(json);
            this.cacheJSON[json['id']] = json;
        },

        _getCacheJSON : function(id){
            return this.cacheJSON[id];
        },

        getData : function(id){
            return this.cacheJSON[id];
        },

        _createInfo : function(id){
            return this._getCacheJSON(id);
        },

        _getDragHelper : function(info){
            return this.options['get-drag'].apply(this, [info]);
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
            return tpl.replace(/{{([a-z_]+)}}/ig, function(all, match){
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


        _initDate : function(dates){
            var _this = this;
            if(_this.dateBox.is(':video-mydate')){
                return;
            }
            _this.dateBox.mydate({
                dates : dates,
                date : function(event, val){
                    _this.dateVal = val;
                    _this._listAjax();
                },
                other : function(event, start, end){
                    _this.startDateVal = start;
                    _this.endDateVal = end;
                }
            });
        },

        _initPage : function(page){
            var _this = this;
            var option = {
                total : page.total_num,
                pages : page.total_page,
                cp : page.current_page,
                num : page.page_num
            };
            if(_this.pageBox.is(':video-page')){
                _this.pageBox.page('refresh', option);
                return;
            }
            option['page'] = function(event, val){
                _this.pageVal = val;
                _this._listAjax();
            };
            _this.pageBox.page(option);
        },

        _initTitle : function(){
            var _this = this;
            _this.titleBox.find('.key-word').on({
                focus : function(){},
                blur : function(){
                    _this.titleVal = $.trim($(this).val());
                }
            });
            _this.titleBox.on({
                click : function(){
                    //_this.titleVal = $.trim(_this.titleBox.find('.key-word').val());
                    _this._listAjax();
                }
            }, '.btn');
        },

        _initWho : function(){
            var _this = this;
            _this.titleBox.find('.key-who').on({
                focus : function(){},
                blur : function(){
                    _this.whoVal = $.trim($(this).val());
                }
            }).hg_autocomplete();
        },

        _destroy : function(){

        }
    });

    $.widget('video.page', {
        options : {
            total : 0,
            pages : 0,
            cp : 0,
            num : 20
        },

        _create : function(){
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

    $.widget('video.mydate', {
        options : {
            dates : null
        },

        _create: function(){
            this._createBox();
            this.saOther = this.element.find('.sa-other');
        },

        _init : function(){
            this._on({
                'click li' : '_click',
                'mouseenter .current-box' : '_mouseenter',
                'mouseleave .current-box' : '_mouseleave'
            });

            var _this = this;
            this.element.find('.sa-other input').datepicker().change(function(){
                var inputs = _this.saOther.find('input');
                _this._trigger('other', null, [inputs.eq(0).val(), inputs.eq(1).val()]);
            });
        },

        _createBox : function(){
            if(!this.options['dates']){
                return;
            }
            var html = '';

            html += '<div class="current-box">';
                html += '<div class="current"></div>';
                html += '<ul>';
                var first = '';
                $.each(this.options['dates'], function(i, n){
                    !first && (first = n);
                    html += '<li _id="' + i + '">' + n + '</li>';
                });
                html += '</ul>';
            html += '</div>';

            html += '<div class="sa-other" style="display:none"><input placeholder="开始时间"/><input placeholder="结束时间"/></div>';

            this.element.html(html);
            this._setCurrent(first);
        },

        _mouseenter : function(){
            this.element.addClass('on');
        },

        _mouseleave : function(){
            this.element.removeClass('on');
        },

        _other : function(hide){
            this.saOther[hide ? 'hide' : 'show']();
            if(hide){
                this.saOther.find('input').val('');
                this._trigger('other', null, [0, 0]);
            }
        },

        _setCurrent : function(val){
            this.element.find('.current').html(val);
        },

        _click : function(event){
            var target = $(event.currentTarget);
            this._setCurrent(target.html());
            var id = target.attr('_id');
            if(id == 'other'){
                this._other();
            }else{
                this._other(true);
                this._mouseleave();
                this._trigger('date', null, [id]);
            }
        },

        refresh : function(dates){
            this.options['dates'] = dates;
            this._createBox();
        }
    });
})(jQuery);