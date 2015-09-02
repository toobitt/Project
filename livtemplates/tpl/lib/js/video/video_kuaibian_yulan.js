(function($){
    $.widget('video.yulan', {
        options: {
            info : null,
            video : '',
            pians : '',
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

            this.relativeVideo = $(this.options.video);
            this.pians = $(this.options.pians);
            this.list = $(this.options.list);

            this.waiting = $('<img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>').appendTo(this.videoBox).css({
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
            this.video.on({
                'loadstart.video' : function(){

                },
                'canplay.video' : function(){
                    me.waiting.hide();
                    me._canplayCallback();
                    timeupdatePause = false;
                    me.videoPlay.trigger('_play');
                },
                'waiting.video' : function(){
                    me.waiting.show();
                },
                'seeking.video' : function(){
                    //me.waiting.show();
                },
                'seeked.video' : function(){
                    //me.waiting.hide();
                    this.play();
                },
                'stop.video' : function(){
                    this.pause();
                },
                'play.video' : function(){
                    this.play();
                },
                'pause.video' : function(){
                    this.pause();
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
                    $(this).attr('src', info['src']);
                },
                'close.video' : function(){
                    $(this).removeAttr('src');
                },
                'space.video' : function(){
                    var paused = this.paused;
                    $(this).trigger(paused ? 'play' : 'pause');
                    me.videoPlay.trigger(paused ? '_play' : '_pause');
                },
                'enabled-keyboard.video' : function(){
                    $(this).data('keyboard', true);
                },
                'disabled-keyboard.video' : function(){
                    $(this).data('keyboard', false);
                },
                'error.video' : function(){
                    //this.load();
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
                                me.video.trigger('space');
                                break;
                            case 37:
                            case 39:
                                keyboard = true;
                                me.video.trigger(keycode == 37 ? 'prev' : 'next');
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
                    me.video.trigger('space');
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
                step : 1,
                range : 'min',
                start : function(event, ui){
                    timeupdatePause = true;
                    me.video.trigger('pause');
                },
                stop : function(event, ui){
                    me._stop(ui.value);

                    ui.handle.blur();
                },
                slide : function(event, ui){
                    me._slide(ui.value);
                }
            });

            this._on({
                'click .yulan-close' : 'close'
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
            var totalTime = 0;
            $.each(info, function(i, n){
                info[i]['beforeTime'] = totalTime;
                var clone = me.pians.pians('clone', n['hash']);
                clone.find('.pian-time-duration').hide();
                me.tab.append(clone);
                totalTime += n['end'] - n['start'];
                info[i]['untilTime'] = totalTime;
            });
            this.totalTime = totalTime;
            this.currentIndex = 0;
            this.currentVideoId = 0;
            this.videoTime.html('00:00:00');
            this.videoTotalTime.html(this._formatTime(totalTime));
            me._step();
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
            if(!currentInfo){
                this.video.trigger('pause');
                return false;
            }
            var changeVideo = false;
            if(!this.currentVideoId || currentInfo['id'] != this.currentVideoId){
                this.currentVideoId = currentInfo['id'];
                changeVideo = true;
            }
            if(changeVideo){
                this.video.trigger('init', [currentInfo['info']]);
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
            var cname = 'pian-current';
            this.tab.find('.' + cname).removeClass(cname);
            this.tab.find('.pian').eq(this.currentIndex).addClass(cname);
            this._tabInnerMove();
        },

        _tabInnerMove : function(){
            var tab = this.tab;
            var currentPian = tab.find('.pian-current');
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
            var me = this;
            var currentTime = this._getTime();
            var currentInfo = this.currentInfo;
            if(currentTime >= (currentInfo['start'] - 2) && currentTime <= currentInfo['end']){
                var currentMillSecond = (currentTime - currentInfo['start'] + currentInfo['beforeTime']);
                currentMillSecond < 0 && (currentMillSecond = 0);
                this.videoSlider.slider('value', (currentMillSecond / this.totalTime) * 100);
                this._slide(currentMillSecond, true);
            }else{
                if(currentTime >= currentInfo['end']){
                    this.currentIndex++;
                    if(!this.options.info[this.currentIndex]){
                        this.video.trigger('pause');
                        return;
                    }
                    this.currentTime = this.options.info[this.currentIndex]['start'];
                }else if(currentTime <= currentInfo['start']){
                    this.currentIndex--;
                    if(this.currentIndex < 0){
                        this.video.trigger('pause');
                        return;
                    }
                    this.currentTime = this.options.info[this.currentIndex]['end'];
                }
                this._step();
            }
        },

        open : function(){
            this.relativeVideo.video('option', 'canKeyboard', false);
            this.video.trigger('enabled-keyboard');
            this.videoSlider.slider('value', 0);
            this.element.show();
            $('body').css('overflow', 'hidden');
        },

        close : function(){
            this.relativeVideo.video('option', 'canKeyboard', true);
            this.video.trigger('disabled-keyboard').trigger('close');
            this.element.hide();
            $('body').css('overflow', 'auto');
        },

        _destroy : function(){
            this.tab.remove();
        }
    });
})(jQuery);
