(function($){
    $.widget('plan.plans', {
        options : {
            isExpired : false,
            ohms : null,
            'time-zhou' : '#drop-time-zhou'
        },

        datas : {},

        _create : function(){
            this.timeZhou = $(this.options['time-zhou']);
            var one = 60 * 60/* * 1000*/;
            this.times = {
                6 : 6 * one,
                12 : 12 * one,
                18 : 18 * one,
                24 : 24 * one
            };
            this._checkTimeZhou();
        },

        _init : function(){
            var _this = this;

            this.element.on({
                mousedown : function(){
                    var item = $(this).closest('.drop-item');
                    var info = item.data('info');
                    var disOffset = {left : 0, top : 0};
                    if($(this).hasClass('drop-end-time')){
                        disOffset['left'] = -150;
                    }
                    _this.options.ohms.ohms('option', {
                        time : $(this).html(),
                        target : $(this)
                    }).ohms('show', disOffset);
                    return false;
                },
                set : function(event, hms){
                    $(this).html([hms.h, hms.m, hms.s].join(':'));
                    var item = $(this).closest('.drop-item');
                    var type = item.attr('_type');
                    var data = _this.datas[item.attr('_hash')];
                    var isStart = $(this).hasClass('drop-start-time');
                    if(isStart){
                        data['custom-start-time'] = _this._stringToTime(hms, true);
                        item.addClass('custom-guding');
                    }else{
                        var startTime = data['start-time'];
                        var endTime = _this._stringToTime(hms, true);
                        var duration = endTime - startTime;
                        if(type == 'file'){
                            var oldDuration = data['data']['duration'];
                            item.removeClass('drop-duration-gt drop-duration-lt');
                            _this._checkDuration(oldDuration, duration, item);
                        }else{
                            item.find('.drop-duration-use').html(_this._duration(duration));
                        }
                        data['custom-duration'] = duration;
                    }
                    setTimeout(function(){
                        _this._sort();
                    }, 0);
                }
            }, '.drop-time');

            this.element.on({
                click : function(){
                    var item = $(this).closest('.drop-item');
                    delete _this.datas[item.attr('_hash')];
                    item.remove();
                    setTimeout(function(){
                        _this._sort();
                    }, 0);
                }
            }, '.drop-close');

            this.element.on({
                click : function(){
                    var item = $(this).closest('.drop-item');
                    var data = _this.datas[item.attr('_hash')];
                    delete data['custom-start-time'];
                    item.removeClass('custom-guding');
                    _this._sort();
                }
            }, '.drop-suo');

            this.element.sortable({
                items : '.drop-item:not(.drop-item-unable)',
                axis : 'y',
                scroll : true,
                placeholder : 'drop-item-place',
                revert : false,
                zIndex : 10000,

                activate : function(){
                    $(this).addClass('light-state');
                },

                deactivate : function(){
                    $(this).removeClass('light-state');
                },

                update : function(){
                    _this._sort();
                }
            });

            /*this.saveBtn = $('.save').animate({opacity : 0}, 1000, function(){
                $(this).hide().css('opacity', 1);
            });*/
            this.saveBtn = $('.save');
            _this._on(this.saveBtn, {
                click : 'save'
            });

            this.element.disableSelection();
        },

        _checkDuration : function(oldDuration, duration, item){
            var _this = this;
            if(oldDuration != duration){
                if(duration > oldDuration){
                    item.addClass('drop-duration-gt');
                }else{
                    item.addClass('drop-duration-lt');
                }
                item.find('.drop-duration-use').html(_this._duration(duration));
                _this._tip(item.find('.drop-tip'), oldDuration, duration);
            }
        },

        sortEnable : function(){
            this.element.sortable('enable');
        },

        sortDisable : function(){
            this.element.sortable('disable');
        },

        getDragHelper : function(info){
            var type = info['type'];
            var typeString;
            switch(type){
                case 'file':
                    typeString = '文件';
                    break;
                case 'stream':
                    typeString = '频道';
                    break;
                case 'shiyi':
                    typeString = '时移';
                    break;
            }
            var data = info['data'];
            var duration = this._duration(data['duration']);
            var title = data['title'];
            var _id = data['_id'] || 0;
            var _schedule_id = data['_schedule_id'] || 0;
            var _file_id = data['_file_id'] || 0;
            var img = data['img'];
            img = img ? ('<img src="' + img + '"/>') : '';
            var hash = this._makeHash();

            //var startTime = data['starttime'] || '';
            var _startTime = new Date(data['start_time'] * 1000);
            var startTime = '';
            if(_startTime){
                var _m = _startTime.getMonth() + 1;
                _m < 10 && (_m = '0' + _m);
                var _d = _startTime.getDate();
                _d < 10 && (_d = '0' + _d);
                var _h = _startTime.getHours();
                _h < 10 && (_h = '0' + _h);
                var _n = _startTime.getMinutes();
                _n < 10 && (_n = '0' + _n);
                startTime = _m + '-' + _d + ' ' + _h + ':' + _n;
            }

            var helperHtml = '<div class="drop-item drop-item-' + type + '" _hash="' + hash + '" _type="' + type + '" _id="' + _id + '" _schedule_id="' + _schedule_id + '" _file_id="' + _file_id + '">' +
                '<div class="drop-time-box">' +
                    '<div class="drop-start-time drop-time"></div>' +
                    '<div class="drop-end-time drop-time"></div>' +
                '</div>' +
                '<div class="drop-info">' +
                    img +
                    '<div class="drop-content">' +
                        '<div class="drop-types">' + typeString + '</div>' +
                        //(startTime ? '<div class="drop-starttime">' + startTime + '</div>' : '') +
                        '<div class="drop-title" title="' + title + '">'+ (startTime ? '(' + startTime + ')' : '') + title + '</div>' +
                        '<div class="drop-duration">' +
                            '<span class="drop-duration-real">' + duration + '</span><span class="drop-duration-step">/</span><span class="drop-duration-use">' + duration + '</span>' +
                            '<span class="drop-duration-no">--</span>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<span class="drop-close"></span>' +
                '<span class="drop-suo"></span>' +
                '<span class="drop-tip"></span>' +
                '<div style="height:0;clear:both;"></div>' +
                '</div>';
            return helperHtml;
        },

        refresh : function(){
            var _this = this;
            var initInfo = _this.options['schedule-info'];
            $.each(initInfo, function(i, n){
                var info = {
                    'init_start' : n['start_time'],
                    'init_end' : n['end_time'],
                    'start-time' : parseInt(n['start_time_num']),
                    'end-time' : parseInt(n['end_time_num']),
                    'data' : {
                        'title' : n['change2_name'],
                        'img' : n['picture'],
                        _id : n['id'],
                        _schedule_id : n['schedule_id'],
                        _file_id : n['file_id']
                    }
                };
                var data = info['data'];
                switch(parseInt(n['type'])){
                    case 1:
                        info['type'] = 'stream';
                        data['id'] = n['change2_id'];
                        data['duration'] = (parseInt(n['toff']) || 0);
                        break;
                    case 2:
                        info['type'] = 'file';
                        data['id'] = n['change2_id'];
                        data['duration'] = parseInt(n['file_toff'] / 1000) || 0;

                        if(n['file_toff'] != n['toff']){
                            info['custom-duration'] = parseInt(n['toff']);
                        }
                        break;
                    case 3:
                        info['type'] = 'shiyi';
                        data['channel_id'] = n['change2_id'];
                        data['duration'] = (parseInt(n['toff']) || 0);
                        break;
                }
                data['duration_ism'] = true;
                if(parseInt(n['is_locked'])){
                    info['custom-start-time'] = parseInt(n['start_time_num']);
                }
                if($.type(n['start_time_shift']) != 'undefined'){
                    data['start_time'] = parseInt(n['start_time_shift']);
                }

                var item = $(_this.getDragHelper(info)).appendTo(_this.element).data('ui-sortable-item', true).show();
                if(!parseInt(n['is_success'])){
                    item.addClass('drop-error');
                }
                _this.datas[item.attr('_hash')] = info;
                info['custom-start-time'] && item.addClass('custom-guding');
                if(info['type'] == 'file'){
                    _this._checkDuration(info['data']['duration'], info['custom-duration'], item);
                }

            });
            _this._sort(true);
        },

        fastAdd : function(clone, info){
            var _this = this;
            var item = $(this.getDragHelper(info)).appendTo(this.element).data('ui-sortable-item', true).show();
            _this.datas[item.attr('_hash')] = info;
            var itemOffset = item.offset();
            var itemPrev = item.prev();
            var itemPrevEndTime = itemPrev[0] ? _this.datas[itemPrev.attr('_hash')]['end-time'] : 0;
            _this._sortItem(item, itemPrevEndTime);
            _this._check();
            _this._checkGuo();
            _this._checkTimeZhou();

            item.hide();
            clone.animate({
                left : itemOffset.left + 'px',
                top : itemOffset.top + 'px'
            }, 200, function(){
                $(this).remove();
                item.show();
                _this._windowSize();
            });
        },

        _sort : function(isInit){
            var _this = this;
            var currentTime = (isEdit && isInit) ? 0 : globalStartTime;

            var items = $('.drop-item:not(.drop-item-unable)', _this.element);
            if(isInit){
                items.each(function(){
                    _this._sortItemInit(this);
                });
            }else{
                items.each(function(){
                    currentTime = _this._sortItem(this, currentTime);
                });
            }

            if(isInit){
                _this._checkUnable(items);
            }

            _this._check(isInit);

            _this._checkCustom();

            _this._checkGuo(items);

            _this._checkTimeZhou();

            _this._windowSize();

        },

        _check : function(isInit){
            var _this = this;
            if(!_this.options.isExpired && !isInit){
                _this.saveBtn.show();
            }
            $('.drop-wu')[$('.drop-item', _this.element).length ? 'hide' : 'show']();
            if(!isInit){
                _this.element.find('.drop-error').removeClass('drop-error');
                _this._trigger('sort');
            }
        },

        _checkCustom : function(){
            var _this = this;
            $('.drop-kongxi').removeClass('drop-kongxi');
            $('.drop-hu-part').removeClass('drop-hu-part');
            $('.drop-hu').removeClass('drop-hu');

            $('.drop-tip').html('').each(function(){
                var tip = $(this).attr('_tip');
                tip && $(this).html(tip);
            });


            $('.custom-guding', this.element).each(function(){
                var item = $(this);
                var itemData = _this.datas[item.attr('_hash')];
                var itemStartTime = itemData['custom-start-time'];
                var prev = item.prev();
                var itemPrev = true;
                while(true){
                    if(!prev[0] || prev.hasClass('custom-guding')){
                        break;
                    }
                    var prevData = _this.datas[prev.attr('_hash')];
                    var startTime = prevData['start-time'];
                    var endTime = prevData['end-time'];
                    if(endTime < itemStartTime && itemPrev){
                        item.addClass('drop-kongxi');
                        break;
                    }
                    if(endTime > itemStartTime){
                        if(endTime - itemStartTime < 1){
                            break;
                        }
                        if(startTime < itemStartTime){
                            prev.addClass('drop-hu-part');
                            _this._tip(prev.find('.drop-tip'), 'hu-part');
                            break;
                        }else{
                            prev.addClass('drop-hu');
                            _this._tip(prev.find('.drop-tip'), 'hu');
                        }
                    }
                    prev = prev.prev();
                    itemPrev = false;
                }
            });
        },

        _checkGuo : function(items){
            var _this = this;
            items = items || $('.drop-item', _this.element);
            $('.drop-guo-part').removeClass('drop-guo-part');
            $('.drop-guo').removeClass('drop-guo');
            var oneDay = 24 * 60 * 60;
            for(var index = items.length - 1; index >= 0; index--){
                var item = items.eq(index);
                var data = _this.datas[item.attr('_hash')];
                var startTime = data['start-time'];
                var endTime = data['end-time'];
                if(endTime > oneDay){
                    if(startTime < oneDay){
                        item.addClass('drop-guo-part');
                        _this._tip(item.find('.drop-tip'), 'guo-part');
                        break;
                    }else{
                        item.addClass('drop-guo');
                        _this._tip(item.find('.drop-tip'), 'guo');
                    }
                }else{
                    break;
                }
            }
        },

        _sortItemInit : function(item){
            var _this = this;
            (function(){
                var data = _this.datas[$(this).attr('_hash')];
                $('.drop-start-time', this).html(data['init_start']);
                $('.drop-end-time', this).html(data['init_end']);
            }).call(item);
        },

        _sortItem : function(item, currentTime){
            var _this = this;
            (function(){
                var data = _this.datas[$(this).attr('_hash')];
                if(data['custom-start-time']){
                    currentTime = data['custom-start-time'];
                }
                var hms = _this._hms(currentTime);
                $('.drop-start-time', this).html(_this._timeToString(hms, true));
                data['start-time'] = currentTime;
                currentTime += parseInt(typeof data['custom-duration'] != 'undefined' ? data['custom-duration'] : data['data']['duration']);
                $('.drop-end-time', this).html(_this._timeToString(currentTime));
                data['end-time'] = currentTime;
            }).call(item);
            return currentTime;
        },

        _checkTimeZhou : function(items){
            var _this = this;
            _this.timeZhou.height(_this.element.height()).empty();
            items = items || $('.drop-item', _this.element);
            var _times = $.extend({}, _this.times);
            var first = true;
            var times = {};
            items.each(function(){
                var hash = $(this).attr('_hash');
                var data = _this.datas[hash];
                var startTime = data['start-time'];
                var endTime = data['end-time'];
                if(first){
                    if(startTime == 0){
                        times[0] = hash;
                    }
                    first = false;
                }
                $.each(_times, function(i, n){
                    if(i == 0){
                        return;
                    }
                    if(startTime <= n && endTime >= n){
                        times[i] = hash;
                    }
                });
            });
            var html = '';
            var item, position, top;
            $.each(times, function(i, n){
                item = $('.drop-item[_hash="' + n + '"]');
                if(item[0]){
                    position = item.position();
                    top = position.top > 0 || i > 0 ? position.top + 19 : 0;
                    html += '<span class="drop-time-zhou-item" style="top:' + top + 'px;">' + i + '点</span>';
                }
            });
            _this.timeZhou.html(html);
        },

        _checkUnable : function(items){
            if(!globalStartTime) return;
            var _this = this;
            items = items || $('.drop-item', _this.element);
            items.each(function(){
                var hash = $(this).attr('_hash');
                var data = _this.datas[hash];
                var startTime = data['start-time'];
                var endTime = data['end-time'];
                if(startTime < globalStartTime){
                    $(this).addClass('drop-item-unable');
                    if(endTime >= globalStartTime){
                        $(this).nextAll('.drop-item-unable').removeClass('drop-item-unable');

                        globalStartTime = endTime;
                        return false;
                    }
                }
            });
        },

        _windowSize : function(){
            return;
            var darea = $('.drop-area');
            darea.css('height', 'auto');
            setTimeout(function(){
                darea.height(Math.max($('html').height(), $(window).height()));
            }, 1);
        },

        _makeHash : function(){
            return new Date().getTime() + '' + Math.ceil((Math.random() * 1000));
        },

        _hms : function(time){
            //time /= 1000;
            var h = parseInt(time / 3600);
            var m = parseInt((time - h * 3600) / 60);
            var s = parseInt(time % 60);
            return {
                h : h,
                m : m,
                s : s
            };
        },

        _timeToString : function(time, ishms){
            var hms = ishms ? time : this._hms(time);
            var h = hms.h;
            var m = hms.m;
            var s = hms.s;
            var sp = s >= 10 ? '' : '0';
            var mp = m >= 10 ? '' : '0';
            var hp = h >= 10 ? '' : '0';
            return hp + h + ':' + mp + m + ':' + sp + s;
        },
        
        _stringToTime : function(string, ishms){
            var hms = string;
            var h, m, s;
            if(!ishms){
                hms = hms.split(':');
                h = hms[0];
                m = hms[1];
                s = hms[2];
            }else{
                h = hms.h;
                m = hms.m;
                s = hms.s;
            }
            return (parseInt(h) * 60 * 60 + parseInt(m) * 60 + parseInt(s)) /* * 1000*/;
        },

        _duration : function(duration){
            //duration /= 1000;
            var h = parseInt(duration / 3600);
            var m = parseInt((duration - h * 3600) / 60);
            var s = parseInt(duration % 60);
            var hs = h ? h + '小时' : '';
            var ms = m ? m + '分' : '';
            var ss = s ? s + '秒' : '';
            var hms = '';
            if(s && h){
                hms += hs + m + '分' + ss;
            }else{
                hms += hs + ms + ss;
            }
            return hms;
        },

        _duration_bak : function(duration){
            //duration /= 1000;
            var h = parseInt(duration / 3600);
            var m = parseInt((duration - h * 3600) / 60);
            var s = parseInt(duration % 60);
            return h + '\'' + m + '\'' + s + '"';
        },

        _tip : function(tipObj, oldDuration, duration){
            var tip;
            if(typeof duration == 'undefined'){
                tip = oldDuration;
                switch(tip){
                    case 'hu' :
                        tip = '不能播放';
                        break;
                    case 'hu-part' :
                        tip = '不能完全播放';
                        break;
                    case 'guo' :
                        tip = '该节目超过24点';
                        break;
                    case 'guo-part' :
                        tip = '该节目部分超过24点';
                        break;
                }
                tipObj.html(tip);
                return;
            }
            if(oldDuration > duration){
                tip = '不能完全播放';
                tipObj.attr('_tip', tip).html(tip);
            }else if(oldDuration == duration){
                tipObj.removeAttr('_tip').html('');
            }else{
                var total = duration / oldDuration;
                var totalZheng = Math.floor(total);
                tip = '循环播放' + totalZheng + '遍' + (total > totalZheng ? '多' : '');
                tipObj.attr('_tip', tip).html(tip);
            }
        },

        addData : function(hash, data){
            this.datas[hash] = data;
        },

        getData : function(hash){
            return this.datas[hash];
        },

        _checkShiYi : function(){
            var _this = this;
            var currentDate = +new Date(dates + ' 00:00:00');
            var shiyis = this.element.find('.drop-item-shiyi:not(.drop-item-unable)');
            var error = false;
            var currentErrorShiYi = null;
            shiyis.each(function(){
                var hash = $(this).attr('_hash');
                var data = _this.getData(hash);
                var info = data['data'];
                var stime = data['start-time'];
                var etime = data['end-time'];
                if(currentDate + stime * 1000 <= +new Date(info['starttime']) + (etime - stime + 10 * 60) * 1000){
                    error = true;
                    currentErrorShiYi = this;
                    return false;
                }
            });
            if(error){
                $(currentErrorShiYi).myTip({
                    string : '此时该时移节目还未生成',
                    color : 'red',
                    width : 300,
                    delay : 3000
                });
                currentErrorShiYi = null;
            }
            return !error;
        },

        save : function(){
            var _this = this;
            var _saveBtn = _this.saveBtn;
            if(_saveBtn.data('ajaxing')){
                return;
            }
            dates = dates || 0;
            channelId = channelId || 0;

            var checkResult = this._checkShiYi();
            if(!checkResult){
                return false;
            }
            var ids = [];
            var start_time = [];
            var start_time_num = [];
            var end_time = [];
            var end_time_num = [];
            var change2_id = [];
            var change2_name = [];
            var type = [];
            var schedule_id = [];
            var file_id = [];
            var start_time_shift = [];
            var is_locked = [];
            var items = _this.element.find('.drop-item');
            items.each(function(){
                var hash = $(this).attr('_hash');
                var data = _this.getData(hash);
                var info = data['data'];
                ids.push(parseInt($(this).attr('_id')) || 0);
                schedule_id.push(parseInt($(this).attr('_schedule_id')) || 0);
                file_id.push(parseInt($(this).attr('_file_id')) || 0);
                start_time_num.push(data['start-time']);
                start_time.push($(this).find('.drop-start-time').html());
                end_time_num.push(data['end-time']);
                end_time.push($(this).find('.drop-end-time').html());
                is_locked.push($.type(data['custom-start-time']) != 'undefined' ? 1 : 0);
                change2_name.push(info['title']);
                var _t;
                var _cid = 0;
                var _sts = 0;
                switch(data['type']){
                    case 'stream':
                        _t = 1;
                        _cid = info['id'];
                        break;
                    case 'file':
                        _cid = info['id'];
                        _t = 2;
                        break;
                    case 'shiyi':
                        _t = 3;
                        _cid = info['channel_id'];
                        _sts = info['start_time'];
                        break;
                }
                change2_id.push(_cid);
                type.push(_t);
                start_time_shift.push(_sts);
            });
            _saveBtn.data('ajaxing', true);
            _saveBtn.html('保存中...');
            _this._delay(function(){
                _saveBtn.data('ajaxing', false);
            }, 1000);
            $.post(
                _this.options['save-ajax-url'],
                {
                    'dates' : dates,
                    'channel_id' : channelId,
                    'ids[]' : ids,
                    'start_time[]' : start_time,
                    'start_time_num[]' : start_time_num,
                    'end_time[]' : end_time,
                    'end_time_num[]' : end_time_num,
                    'change2_id[]' : change2_id,
                    'change2_name[]' : change2_name,
                    'type[]' : type,
                    'schedule_id[]' : schedule_id,
                    'file_id[]' : file_id,
                    'start_time_shift[]' : start_time_shift,
                    'is_locked[]' : is_locked
                },
                function(json){
                    json = json[0];
                    if(json['id']){
                        var items = _this.element.find('.drop-item');
                        $.each(json['id'], function(i, n){
                            items.eq(i).attr('_id', n);
                        });
                    }
                    _saveBtn.html('保存成功');
                    _this._delay(function(){
                        _saveBtn.data('ajaxing', false);
                        _saveBtn.hide();
                        _saveBtn.html('保存');
                    }, 500);

                    _this._trigger('save');
                },
                'json'
            );
        },

        copy : function(dates, distime, callback){
            var _this = this;
            channelId = channelId || 0;

            var ids = [];
            var start_time = [];
            var start_time_num = [];
            var end_time = [];
            var end_time_num = [];
            var change2_id = [];
            var change2_name = [];
            var type = [];
            var schedule_id = [];
            var file_id = [];
            var start_time_shift = [];
            var is_locked = [];
            var items = _this.element.find('.drop-item');
            items.each(function(){
                var hash = $(this).attr('_hash');
                var data = _this.getData(hash);
                var info = data['data'];
                ids.push(parseInt($(this).attr('_id')) || 0);
                schedule_id.push(parseInt($(this).attr('_schedule_id')) || 0);
                file_id.push(parseInt($(this).attr('_file_id')) || 0);
                start_time_num.push(data['start-time']);
                start_time.push($(this).find('.drop-start-time').html());
                end_time_num.push(data['end-time']);
                end_time.push($(this).find('.drop-end-time').html());
                is_locked.push($.type(data['custom-start-time']) != 'undefined' ? 1 : 0);
                change2_name.push(info['title']);
                var _t;
                var _cid = 0;
                var _sts = 0;
                switch(data['type']){
                    case 'stream':
                        _t = 1;
                        _cid = info['id'];
                        break;
                    case 'file':
                        _cid = info['id'];
                        _t = 2;
                        break;
                    case 'shiyi':
                        _t = 3;
                        _cid = info['channel_id'];
                        _sts = info['start_time'] + distime;
                        break;
                }
                change2_id.push(_cid);
                type.push(_t);
                start_time_shift.push(_sts);
            });
            $.post(
                _this.options['save-ajax-url'],
                {
                    'dates' : dates,
                    'channel_id' : channelId,
                    //'ids[]' : ids,
                    'start_time[]' : start_time,
                    'start_time_num[]' : start_time_num,
                    'end_time[]' : end_time,
                    'end_time_num[]' : end_time_num,
                    'change2_id[]' : change2_id,
                    'change2_name[]' : change2_name,
                    'type[]' : type,
                    'schedule_id[]' : schedule_id,
                    'file_id[]' : file_id,
                    'start_time_shift[]' : start_time_shift,
                    'is_locked[]' : is_locked
                },
                function(json){
                    callback && callback();
                },
                'json'
            );
        },

        _destroy : function(){

        }
    });
})(jQuery);