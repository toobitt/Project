(function($){
    $.widget('plan.channel', {
        options : {

            'cat-url' : 'run.php?mid='+ gMid +'&a=get_channel_info',
            'list-url' : 'run.php?mid='+ gMid +'&a=get_dvr_info&channel_id={{channel_id}}&dates={{dates}}&stime={{stime}}',

            'channel-cat-tpl' : '#channel-cat-tpl',
            'channel-list-tpl' : '#channel-list-tpl',

            date : '',
            connectToSortable : '#plan-box',
            plans : '#plan-box'
        },

        _create : function(){
            var root = this.element;
            this.types = {
                'channelCat' : {
                    url : this.options['cat-url'],
                    name : root,
                    tpl : $(this.options['channel-cat-tpl']).html()
                },
                'channelList' : {
                    url : this.options['list-url'],
                    name : $('#channel-list'),
                    tpl : $(this.options['channel-list-tpl']).html()
                }
            };
            var _this = this;
            $.each(this.types, function(i, n){
                _this[i] = typeof n['name'] == 'string' ? root.find(n['name']) : n['name'];
            });

            if(!this.options.date){
                var date = new Date();
                this.options.date = [date.getFullYear(), date.getMonth() + 1, date.getDate()].join('-');
            }
            this.plans = $(this.options['plans']);

            this._ajax('channelCat');
        },

        _init : function(){
            var _this = this;
            this.channelNext = $('#channel-list-next');
            this.channelList.on({
                _show : function(event, target, stime){
                    $(this).data('target', target);
                    $(this).data('stime', stime);
                    var offset = target.offset();
                    $(this).show().css({
                        left : offset.left + 'px',
                        top : offset.top + 50 + 'px'
                    });
                    $(this).triggerHandler('_createMask').show();
                },

                _hide : function(){
                    $(this).hide();
                    $(this).triggerHandler('_createMask').hide();
                },

                _createMask : function(){
                    var id = 'channel-list-mask';
                    var mask = $('#' + id);
                    if(!mask[0]){
                        mask = $('<div id="' + id + '"></div>').appendTo('body');
                    }
                    return mask;
                }
            })
            .on({
                click : function(){
                    var parent = _this.channelList;
                    var target = parent.data('target');
                    var stime = parent.data('stime');
                    var id = $(this).attr('_id');
                    var info = _this._getChannelListInfo(id);
                    _this.channelNext.triggerHandler('_show');
                    _this.channelNext.triggerHandler('_html', [stime]);
                }
            }, '.channel-list-item-can')
            .on({
                click : function(){
                    var parent = _this.channelList;
                    parent.data('target').remove();
                    parent.removeData('target');
                    parent.removeData('stime');
                    parent.trigger('_hide');
                }
            }, '#channel-list-close');

            this.channelNext.on({
                _show : function(){
                    $(this).addClass('channel-list-next-show');
                },

                _hide : function(){
                    $(this).removeClass('channel-list-next-show');
                },

                _html : function(event, stime){
                    $(this).find('.cln-title input').val('');
                    $(this).find('.cln-start').html(stime);
                    $(this).find('.cln-end').val('');
                }
            });
            this.channelNext.find('.cln-end').mydate({
                timeBox : $.timeBox()
            });
            this.channelNext.on({
                click : function(){
                    var parent = _this.channelList;
                    var next = _this.channelNext;
                    var title = next.find('.cln-title input').val();
                    var start = next.find('.cln-start').html();
                    var end = next.find('.cln-end').val();
                    var info = {
                        start : start,
                        startTime : _this.plans.plans('stringToTime', start),
                        end : end,
                        endTime : _this.plans.plans('stringToTime', end),
                        duration : end - start,
                        type : 'channel',
                        'channel-type' : 'after',
                        title : title
                    };
                    _this.plans.plans('make', info);
                }
            }, '.cln-ok');
            this.channelNext.on({
                click : function(){
                    _this.channelNext.triggerHandler('_hide');
                }
            }, '.cln-cancel');
        },

        _beforeAjax :function(type){
            this['_' + type + 'BeforeAjax']('<img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>');
        },

        _channelCatBeforeAjax : function(img){
            this['channelCat'].html(img);
        },

        _channelListBeforeAjax : function(img){
            this['channelList'].find('#channel-list-inner').html(img);
        },

        _ajax : function(type, urlPrama){
            this._beforeAjax(type);
            var _this = this;
            $.getJSON(
                this['_' + type + 'Url'](urlPrama),
                function(json){
                    _this._afterAjax(type, json);
                }
            );
        },

        _afterAjax : function(type, info){
            this['_' + type + 'AfterAjax'](this['_' + type + 'FilterInfo'](info));
        },

        _channelCatAfterAjax : function(info){
            var html = $.tmpl(this.types['channelCat']['tpl'], info);
            this.element.html(html);
            var _this = this;

            this.element.on({
                mousedown : function(){
                    _this.plans.plans('sortEnable');
                }
            });
            this.element.disableSelection();

            this.element.find('.channel-cat-item').filter(function(){
                return !$(this).is(':ui-draggable');
            }).draggable({
                helper : 'clone',
                connectToSortable : this.options['connectToSortable'],
                revert : 'invalid',
                revertDuration : 100,
                zIndex : 100000,
                disabled : !!this.dragCanDo,
                start : function(){
                    $(_this.options['connectToSortable']).addClass('light-state');
                },
                drag : function(event, ui){
                    ui.helper.data('info', _this._getChannelCatInfo($(this).attr('_id')));
                },
                stop : function(){
                    $(_this.options['connectToSortable']).removeClass('light-state');
                    _this.plans.plans('sortDisable');
                }
            });
        },

        _channelListAfterAjax : function(info){
            var tpl = this.types['channelList']['tpl'];
            var list = this['channelList'].find('#channel-list-inner').html('');
            var listDataInfo = this.listDataInfo = {};
            $.each(info, function(i, n){
                var items = $.extend({}, n['00:00~09:00'].concat(n['09:00~13:00'], n['13:00~19:00'], n['19:00~24:00']));
                $.tmpl(tpl, {
                    date : i,
                    items : items
                }).appendTo(list);

                $.each(items, function(ii, nn){
                    listDataInfo[nn['id']] = nn;
                });
            });
        },

        _channelCatUrl : function(){
            return this.types['channelCat']['url'];
        },

        _channelListUrl : function(urlPrama){
            return this._replace(this.types['channelList']['url'], urlPrama);
        },

        _channelCatFilterInfo : function(info){
            info = info[0];
            return {
                list : info
            };
        },

        _channelListFilterInfo : function(info){
            return info[0];
        },

        _replace : function(tpl, data){
            return tpl.replace(/{{([a-z_]+)}}/g, function(all, match){
                var val = data[match];
                return typeof val != 'undefined' ? val : '';
            });
        },

        _getChannelCatInfo : function(id){
            var info = {};
            info['type'] = 'channel';
            info['id'] = id;
            return info;
        },

        _getChannelListInfo : function(id){
            return this.listDataInfo[id];
        },

        getChannelList : function(target, info, stime){
            this._ajax('channelList', {
                channel_id : info['id'],
                dates : this.options.date,
                stime : stime
            });
            this['channelList'].trigger('_show', [target, stime]);
        },

        _destroy : function(){

        }
    });
})(jQuery);