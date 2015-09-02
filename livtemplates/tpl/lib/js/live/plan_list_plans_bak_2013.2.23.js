(function($){
    $.widget('plan.plans', {
        options : {
            accept : '.ui-draggable',
            'plan-tpl' : '#plan-item-tpl',
            'plan-item' : '.plan-item',
            'plan-delete' : '.plan-delete',

            btn : '#plan-option-btn',

            channel : '#channel-box'
        },

        _create : function(){
            this.currentTime = 0;
            this.btn = $(this.options['btn']);
            this.infos = {};
            this.caches = {};

            this.channel = $(this.options.channel);
        },

        _init : function(){
            var handlers;

            handlers = {};
            handlers['mousedown ' + this.options['plan-delete']] = '_delete';
            this._on(handlers);

            var _this = this;

            this.element.sortable({
                items : this.options['plan-item'],
                axis : 'y',
                scroll : true,
                placeholder : 'plan-item-placeholder',
                disabled : true,
                revert : '100',
                cancel : this.options['plan-delete'],

                beforeStop : function(event, ui){
                    $(this).data('currentInfo', ui.helper.data('info'));
                },

                receive : function(event, ui){
                    var info = $(this).data('currentInfo');
                    var draggable = $(this).find('.ui-draggable');
                    var replaceDrag = _this._make(info);
                    draggable.replaceWith(replaceDrag);
                    switch(info['type']){
                        case 'file':
                            replaceDrag.css('opacity', 0).animate({
                                opacity : 1
                            }, 300);
                            break;

                        case 'channel':
                            var prev = replaceDrag.prev();
                            var lastTimeString;
                            if(!prev[0]){
                                lastTimeString = '00:00:00';
                            }else{
                                var prevInfo = _this.infos[prev.attr('_hash')];
                                lastTimeString = prevInfo['end'];
                            }
                            _this._getChannelList(replaceDrag, info, lastTimeString);
                            break;

                        case 'stream':

                            break;
                    }



                },

                stop : function(event, ui){
                    var info = $(this).data('currentInfo');
                    switch(info['type']){
                        case 'file':
                            _this._sort();
                            break;
                        case 'channel':
                            break;
                    }
                    $(this).removeData('currentInfo');
                }
            });

        },

        _getChannelList : function(replaceDrag, info, lastTimeString){
            this.channel.channel('getChannelList', replaceDrag, info, lastTimeString);
        },

        _delete : function(event){
            var _this = this;
            $(event.currentTarget).closest(this.options['plan-item']).animate({
                height : 0
            }, 100 ,function(){
                var hash = $(this).attr('_hash');
                $(this).remove();
                if(!_this.element.find(_this.options['plan-item']).length){
                    _this.btn.trigger('click').triggerHandler('_hide');
                }else{
                    _this._sort();
                }
            });
        },

        _sort : function(callback){
            var _this = this;
            var tpl;
            var currentInfos = this.infos;
            var newInfos = {};
            var hashs = [];
            this.currentTime = 0;

            this.element.find(this.options['plan-item']).each(function(){
                !tpl && (tpl = $(_this.options['plan-tpl']).html());
                var hash = $(this).attr('_hash');
                var info = currentInfos[hash];
                switch(info['type']){
                    case 'file':
                        info = _this._getFileInfo(info);
                        break;
                    case 'channel':
                        info = _this._getChannelInfo(info);
                        break;
                }
                info['hash'] = hash;
                hashs.push(hash);
                newInfos[hash] = info;
                $(this).html($($.tmpl(tpl, info)).html());
            });
            currentInfos = null;
            this.infos = newInfos;
            if(callback){
                callback(newInfos);
            }
        },

        sortEnable : function(){
            this.element.sortable('enable');
        },

        sortDisable : function(){
            this.element.sortable('disable');
        },

        inEditing : function(){
            this.element.addClass('plan-inediting');
            this.sortEnable();
        },

        outEditing : function(){
            this.element.removeClass('plan-inediting');
            this.sortDisable();
        },

        change : function(hash, nowDuration, resize){
            var info = this.infos[hash];
            if(resize){
                info['duration'] = parseInt(nowDuration);
                return this._changeTime(info['startTime'] + info['duration']);
            }
            this._sort();
        },

        resize : function(hash){

        },

        make : function(info){
            this._make(info);
        },

        _createInfo : function(info){
            var needInfo = this[info['type'] + 'FilterInfo'](info);
            needInfo['hash'] = this._makeHash();
        },

        _fileFilterInfo : function(info){
            return {
                type : 'file',
                id : info['id'],
                duration : info['duration']
            };
        },

        _streamFilterInfo : function(info){
            return {
                type : 'stream',
                id : info['id'],
                duration : info['duration']
            };
        },

        _addCache : function(info){
            var type = info['type'];
            var cache = this.caches[type];
            if(!cache){
                cache = {};
            }
            this.caches[type][info['id']] = info;
        },

        _getCache : function(type, id){
            return this.caches[type][id];
        },

        _make : function(info){
            switch(info['type']){
                case 'file':
                    info = this._getFileInfo(info['data']);
                    break;
                case 'channel':
                    if(info['channel-type'] == 'before'){
                        info = this._getChannelInfoBefore(info);
                    }else{
                        info = this._getChannelInfo(info);
                    }
                    break;
                case 'stream':

                    break;
            }
            var hash = info['hash'];
            if(!hash){
                hash = this._makeHash();
                info['hash'] = hash;
            }
            this.infos[hash] = info;
            var replace = $(this.options['plan-tpl']).tmpl(info).attr('_hash', hash);
            return replace;
        },

        _getFileInfo : function(info){
            var duration = parseInt(info['duration']);
            var title = info['title'];
            var startTime = this.currentTime;
            var start = this._changeTime(startTime);
            this.currentTime += duration;
            var endTime = this.currentTime;
            var end = this._changeTime(endTime);
            return {
                id : info['id'],
                startTime : startTime,
                start : start,
                duration : duration,
                endTime : endTime,
                end : end,
                title : title,
                type : 'file'
            };
        },

        _getChannelInfoBefore : function(info){
            return {
                type : 'channel',
                'channel-type' : 'before'
            };
        },

        _getChannelInfo : function(info){
            return {
                type : 'channel'
            };
        },


        _makeHash : function(){
            return new Date().getTime() + '' + Math.ceil((Math.random() * 1000));
        },

        _changeTime : function(time){
            time /= 1000;
            var h = parseInt(time / 3600);
            var m = parseInt((time - h * 3600) / 60);
            var s = parseInt(time % 60);
            var sp = s >= 10 ? '' : '0';
            var mp = m >= 10 ? '' : '0';
            var hp = h >= 10 ? '' : '0';
            return hp + h + ':' + mp + m + ':' + sp + s;
        },

        timeToString : function(time){
            return this._changeTime(time * 1000);
        },

        stringToTime : function(timeString){
            timeString = timeString.split(':');
            return timeString[0] * 3600 + timeString[1] * 60 + timeString[2] * 1;
        },

        _destroy : function(){

        }
    });
})(jQuery);