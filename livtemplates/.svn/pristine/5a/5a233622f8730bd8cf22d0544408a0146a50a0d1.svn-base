(function($){
    $.widget('video.yulan', {
        options: {
            info : null,
            duis : '',
            list : ''
        },

        _create : function(){
            var root = this.element;
            this.tab = root.find('.yulan-tab-inner');
            this.videoBox = root.find('.yulan-video');
            this.video = this.videoBox.find('video');
            this.videoControl = this.videoBox.find('.yulan-control');
            this.videoSlider = this.videoBox.find('.yulan-slider');
            this.videoPlay = this.videoBox.find('.yulan-play');
            this.videoPrev = this.videoBox.find('.yulan-kj-prev');
            this.videoNext = this.videoBox.find('.yulan-kj-next');
            this.videoTime = this.videoBox.find('.yulan-time');
            this.videoTotalTime = this.videoBox.find('.yulan-total-time');

            this.duis = $(this.options.duis);
            this.list = $(this.options.list);

            this.waiting = $('<img src="' + RESOURCE_URL + 'loading2.gif" style="width:50px;"/>').appendTo(this.videoBox).css({
                position : 'absolute',
                'z-index' : 100,
                left : '50%',
                top : '50%',
                margin : '-15px 0 0 -15px'
            }).hide();

            this._refresh();
        },

        _init : function(){

            var me = this;
            var timeupdatePause = false;
            var videoState = true;
            this.video.on({
                'loadstart.video' : function(){

                },
                'canplay.video' : function(){
                    me.waiting.hide();
                    me._canplayCallback();
                    timeupdatePause = false;
                    if(videoState){
                        $(this).triggerHandler('_play');
                    }
                },
                'waiting.video' : function(){
                    me.waiting.show();
                },
                'seeking.video' : function(){
                    me.waiting.show();
                },
                'seeked.video' : function(){
                    me.waiting.hide();
                    //this.play();
                },
                'stop.video' : function(){

                },
                'play.video' : function(){
                    //console.log('play');
                },
                'pause.video' : function(){
                    //console.log('pause');
                },
                'prev.video' : function(){
                    if(this.seeking) return;
                    this.currentTime -= 5;
                },
                'next.video' : function(){
                    if(this.seeking) return;
                    this.currentTime += 5;
                },
                'timeupdate.video' : function(){
                    if(this.seeking || timeupdatePause){
                        return;
                    }
                    me._update();
                },
                'init.video' : function(event, info){
                    timeupdatePause = true;
                    $(this).attr('src', info['yulan_src']);
                },
                'close.video' : function(){
                    $(this).removeAttr('src');
                },
                'space.video' : function(){
                    var paused = this.paused;
                    $(this).triggerHandler(paused ? '_play' : '_pause');

                },
                'enabled-keyboard.video' : function(){
                    $(this).data('keyboard', true);
                },
                'disabled-keyboard.video' : function(){
                    $(this).data('keyboard', false);
                },
                'error.video' : function(){
                    //this.load();
                },

                '_play.video' : function(){
                    videoState = true;
                    this.play();
                    me.videoPlay.triggerHandler('_play');
                },

                '_pause.video' : function(){
                    videoState = false;
                    this.pause();
                    me.videoPlay.triggerHandler('_pause');
                }
            });

            var keyboard = null;
            $(document).on({
                keydown : function(event){
                    if(!me.video.data('keyboard')){
                        return;
                    }
                    var keycode = event.keyCode;
                    if(keycode == 32 || keycode == 37 || keycode == 39){
                        event.preventDefault();
                        switch(keycode){
                            case 32:
                                me.video.triggerHandler('space');
                                break;
                            case 37:
                            case 39:
                                keyboard = true;
                                //键盘快进功能还没有实现，先关闭
                                //me.video.triggerHandler(keycode == 37 ? 'prev' : 'next');
                                break;
                        }
                    }
                },
                keyup : function(event){
                    if(!me.video.data('keyboard')){
                        return;
                    }
                    var keycode = event.keyCode;
                    if(keycode == 37 || keycode == 39){
                        keyboard = false;
                        event.preventDefault();
                    }
                }
            });

            this.videoPlay.on({
                click : function(event){
                    me.video.triggerHandler('space');
                    return false;
                },

                _play : function(){
                    $(this).addClass('yulan-pause');
                },

                _pause : function(){
                    $(this).removeClass('yulan-pause');
                }
            });

            this.videoSlider.slider({
                step : .1,
                range : 'min',
                start : function(event, ui){
                    timeupdatePause = true;
                    $(this).data('video-state', !me.video[0].paused);
                    me.video.triggerHandler('_pause');
                },
                stop : function(event, ui){
                    ui.handle.blur();
                    timeupdatePause = false;
                    if($(this).data('video-state')){
                        me.video.triggerHandler('_play');
                    }
                    me._stop(ui.value);
                },
                slide : function(event, ui){
                    me._slide(ui.value);
                }
            });

            this._on({
                'click .yulan-close' : 'close',
                'click .tab-item' : '_tabClick'
            });

        },

        _setOptions : function(){
            this._superApply(arguments);
            this._refresh();
        },

        _refresh : function(){
            if(!this.options.info){
                return false;
            }
            var me = this;
            var info = this.options.info || [];
            this.tab.empty();
            this.tabInfo = [];
            var totalTime = 0;
            $.each(info, function(i, n){
                var eachTabInfo = {};
                eachTabInfo['title'] = n['title'];
                var isTwo = $.type(n['new_duration']) != 'undefined' ? true : false;
                if(isTwo){
                    n['start'] = parseInt(n['startInfo']['time']);
                    n['end'] = parseInt(n['endInfo']['time']);
                }else{
                    n['start'] = 0;
                    n['end'] = parseInt(n['duration_num']);
                }
                info[i]['beforeTime'] = totalTime;
                eachTabInfo['start'] = totalTime;
                var clone = me.duis.dui('clone', n['hash']);
                clone.addClass('tab-item');
                me.tab.append(clone);
                var eachTime = parseInt(isTwo ? n['new_duration'] : n['duration_num']);
                eachTabInfo['time'] = eachTime;
                totalTime += eachTime;
                info[i]['untilTime'] = totalTime;
                info[i]['yulan_src'] = './vod' + (n['dir_index'] && n['dir_index'] > 0 ? n['dir_index'] : '') + '/' + n['video_path'] + n['video_filename'];
                me.tabInfo.push(eachTabInfo);
            });
            this.totalTime = totalTime;
            this.currentIndex = 0;
            this.currentVideoId = 0;
            this.videoTime.html('00:00:00');
            this.videoTotalTime.html(this._formatTime(totalTime));
            this._step();
            this._tabBiao();
        },

        _formatTime: function( seconds ) {
            seconds /= 1000;
            var h = parseInt(seconds / 3600);
            var m = parseInt((seconds - h * 3600) / 60);
            var s = parseInt(seconds % 60);
            var sp = s >= 10 ? '' : '0';
            var mp = m >= 10 ? '' : '0';
            var hp = h >= 10 ? '' : '0';
            return hp + h + ':' + mp + m + ':' + sp + s;
        },

        _step : function(){
            var currentInfo = this.currentInfo = this.options.info[this.currentIndex];
            //console.log(this.currentIndex, currentInfo);
            if(!currentInfo){
                this.video.triggerHandler('_pause');
                return false;
            }
            var changeVideo = false;
            if(!this.currentVideoId || currentInfo['id'] != this.currentVideoId){
                this.currentVideoId = currentInfo['id'];
                changeVideo = true;
            }
            if(changeVideo){
                this.video.triggerHandler('init', [currentInfo]);
            }else{
                this._canplayCallback();
            }
            this._tabOn();
        },

        _canplayCallback : function(){
            this._setTime(this.currentTime || this.currentInfo['start']);
            this.currentTime = null;
        },

        _tabOn : function(){
            var cname = 'yulan-current';
            this.tab.find('.' + cname).removeClass(cname);
            this.tab.find('.drop-item').eq(this.currentIndex).addClass(cname);
            this._tabInnerMove();
        },

        _tabInnerMove : function(){
            var tab = this.tab;
            var currentPian = tab.find('.yulan-current');
            var hash = currentPian.attr('_hash');
            if(!this.cacheWindowWidth){
                this.cacheWindowWidth = $(window).width() / 2;
            }
            typeof this.cachePianHash == 'undefined' && (this.cachePianHash = {});
            if(!this.cachePianHash[hash]){
                this.cachePianHash[hash] = currentPian.offset().left - tab.offset().left + currentPian.outerWidth() / 2;
            }
            var tabLeft = this.cacheWindowWidth - this.cachePianHash[hash];
            tab.css('left', tabLeft + 'px');
        },

        _tabClick : function(event){
            if($(event.currentTarget).hasClass('yulan-current')) return;
            var index = this.tab.find('.tab-item').index(event.currentTarget);
            this.currentIndex = index;
            this._step();
        },

        _tabBiao : function(){
            var _this = this;
            var totalTime = _this.totalTime;
            if(!_this.videoBiao){
                _this.videoBiao = $('<div class="yulan-biao"></div>').appendTo(_this.videoControl);
            }
            var biao = '';
            $.each(_this.tabInfo, function(i, n){
                var left = (parseInt(n['start'] / totalTime * 100)) + '%';
                biao += '<span style="left:' + left + ';" title="' + n['title'] + '"></span>';
            });
            _this.videoBiao.html(biao);
        },

        _setTime : function(time){
            this.video[0].currentTime = time / 1000;
        },

        _getTime : function(){
            return this.video[0].currentTime * 1000;
        },

        _slide : function(value, isMillSecond){
            !isMillSecond && (value = this.totalTime * (value / 100));
            this.videoTime.html(this._formatTime(value));
        },

        _stop : function(value){
            var me = this;
            var time = this.totalTime * value / 100;
            $.each(this.options.info, function(i, n){
                if(n['beforeTime'] <= time && time <= n['untilTime']){
                    me.currentIndex = i;
                    me.currentTime = time - n['beforeTime'] + n['start'];
                    return false;
                }
            });
            this._step();
        },

        _update : function(){
            var currentTime = this._getTime();
            var currentInfo = this.currentInfo;
            if(currentTime >= (currentInfo['start'] - 1) && currentTime < (currentInfo['end'] - 1)){
                //console.log(currentTime, currentInfo['start'], currentInfo['end'], 1);
                var currentMillSecond = (currentTime - currentInfo['start'] + currentInfo['beforeTime']);
                currentMillSecond < 0 && (currentMillSecond = 0);
                this.videoSlider.slider('value', (currentMillSecond / this.totalTime) * 100);
                this._slide(currentMillSecond, true);
            }else{
                //console.log(currentTime, currentInfo['start'], currentInfo['end'], 2);
                if(currentTime >= currentInfo['end'] - 1){
                    this.currentIndex++;
                    if(!this.options.info[this.currentIndex]){
                        this.video.triggerHandler('_pause');
                        return;
                    }
                    this.currentTime = this.options.info[this.currentIndex]['start'];
                }else if(currentTime <= currentInfo['start'] - 1){
                    this.currentIndex--;
                    if(this.currentIndex < 0){
                        this.video.triggerHandler('_pause');
                        return;
                    }
                    this.currentTime = this.options.info[this.currentIndex]['end'];
                }
                this._step();
            }
        },

        open : function(){
            //this.relativeVideo.video('option', 'canKeyboard', false);
            this.video.trigger('enabled-keyboard');
            this.videoSlider.slider('value', 0);
            var pp = this.element.parent();
            var ppWidth = pp.width();
            var ppHeight = pp.height();
            this.element.css({
                width : ppWidth + 'px',
                height : ppHeight + 'px'
            }).show();
            //$('body').css('overflow', 'hidden');
        },

        close : function(){
            //this.relativeVideo.video('option', 'canKeyboard', true);
            this.video.trigger('disabled-keyboard').trigger('close');
            this.element.hide();
            //$('body').css('overflow', 'auto');
        },

        _destroy : function(){
            this.tab.remove();
        }
    });
})(jQuery);
