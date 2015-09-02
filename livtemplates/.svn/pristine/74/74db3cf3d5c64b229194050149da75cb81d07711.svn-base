(function($){
    $.widget('video.myslider', $.ui.slider, {
        initHandle : function(index){
            var handle = this.handles.eq(index).addClass('ui-state-current');
            /*var offset = handle.offset();
            var event = new $.Event('mousedown', {
                pageX : offset.left,
                pageY : offset.top,
                target : handle[0]
            });
            this._mouseCapture(event);
            this._mouseDrag(event);
            this._mouseStop(event);*/

            this.element.trigger('_time', [0, handle]);
            this.element.trigger('_stop', [0, 'show']);
        },

        createHandlePart : function(){
            this.handles.eq(0).addClass('ui-slider-handle-first');
            this.handles.eq(1).addClass('ui-slider-handle-second');
            return;
            this.handles.each(function(){
                $(this).html('<span class="ui-handle-part"><span class="ui-handle-left-btn">&lt;</span><span class="ui-handle-right-btn">&gt;</span><span class="ui-handle-time"></span></span>');
            });
        }
    });

    $.widget('video.pian', {
        options: {
            mainVideoId : 0,
            video : null,
            videoSlider : null,
            progress : null,
            pians : null,
            distance : 10,
            saveUrl : 'run.php?mid=' + gMid + '&a=fast_auto_save',
            deleteUrl : 'run.php?mid=' + gMid + '&a=fast_auto_save_delete',

            initInfo : null
        },

        _create : function(){
            this.hash = this._hash();
            this.video = $(this.options.video);
            this.videoSlider = $(this.options.videoSlider);
            this.progress = $(this.options.progress);
            this.pians = $(this.options.pians);

            var root = this.element;
            this.startImg = root.find('.pian-img-start');
            this.endImg = root.find('.pian-img-end');
            this.startTime = root.find('.pian-time-start');
            this.endTime = root.find('.pian-time-end');
            this.duration = root.find('.pian-time-duration');
            this.keyboardState = false;
        },

        _init : function(){
            this._slider();

            var initInfo = this.options.initInfo;
            var start, end;
            if(initInfo){
                this.videoId = initInfo['id'];
                this.hash = initInfo['hash'];
                this.element.attr({
                    '_hash' : initInfo['hash'],
                    '_id' : initInfo['id']
                });
                start = initInfo['start'] / initInfo['time'];
                end = initInfo['end'] / initInfo['time'];
                this.slider.myslider('option', 'values', [start, end]).trigger('_hide');
                initInfo['startImg'] && this.startImg.html('<img src="'+ initInfo['startImg'] +'"/>');
                initInfo['endImg'] && this.endImg.html('<img src="'+ initInfo['endImg'] +'"/>');
                this._setDuration(initInfo['start'], initInfo['end']);
            }else{
                this.videoId = this.video.data('info')['id'];
                this.element.attr({
                    '_hash' : this.hash,
                    '_id' : this.videoId
                });
                start = this.videoSlider.slider('value') || 0;
                end = start + this.options.distance;
                this.slider.myslider('option', 'values', [start, end]);
                this.slider.myslider('createHandlePart');
                this.slider.myslider('initHandle', 0);
                this._keyboard(true);
            }

            var me = this;
            this.element.on({
                _click : function(event){
                    $(this).trigger(!$(this).hasClass('pian-current') ? 'selected' : 'unselected');
                    var id = $(this).attr('_id');
                    if(id != me.video.data('info')['id']){
                        me.pians.pians('changeVideo', id);
                    }
                },
                enabled : function(){return;
                    $(this).removeClass('pian-disabled');
                },
                disabled : function(){return;
                    $(this).addClass('pian-disabled');
                },
                selected : function(){
                    $(this).addClass('pian-current');
                    me.slider.trigger('_show');
                    me._mask(true);
                    $('#video-box').addClass('video-box-pian');
                    me.videoSlider.find('.ui-slider-handle').hide();

                    me.pians.pians('openHelp');
                },
                unselected : function(){
                    $(this).removeClass('pian-current');
                    me.slider.trigger('_hide');
                    me._mask(false);
                    $('#video-box').removeClass('video-box-pian');
                    me.videoSlider.find('.ui-slider-handle').show();

                    me.pians.pians('closeHelp');
                    me.pians.pians('totalInfoCheck');
                },

                mouseenter : function(){
                    if($(this).hasClass('pian-current')){
                        return false;
                    }
                    var position = $(this).offset();
                    if(position.left){

                    }
                    position.left -= 2;
                    position.top -= 10;
                    me.pians.pians('openOption', $(this), position);
                }
            });

            //this._save();
        },

        editOption : function(){
            this.element.trigger('_click');
        },

        deleteOption : function(){
            this.element.hasClass('pian-current') && this.element.trigger('unselected');
            this._delete();
            this.destroy();
        },

        _save : function(){
            var me = this;
            var startInfo = this.slider.data('start');
            var startTime = startInfo ? startInfo['time'] : 0;
            var endInfo = this.slider.data('end');
            var endTime = endInfo ? endInfo['time'] : 0;
            var startImg = this.startImgChange ? this.startImg.find('img').attr('src') : '';
            var endImg = this.endImgChange ? this.endImg.find('img').attr('src') : '';
            this.startImgChange = this.endImgChange = false;
            var postData = {
                main_video_id : this.options.mainVideoId,
                vodinfo_id : this.videoId,
                hash_id : this.hash,
                input_point : startTime,
                output_point : endTime,
                start_imgdata : startImg,
                end_imgdata : endImg,
                vcr_type : 4
            };
            $.post(
                this.options.saveUrl,
                postData
            ).success(function(){
                me.pians.pians('saveOrder');
            });
        },

        _delete : function(){
            var me = this;
            $.getJSON(
                this.options.deleteUrl,
                {hash_id : this.hash}
            ).success(function(){
                me.pians.pians('saveOrder');
            });
        },

        _mask : function(state){
            var mask = $('#pian-mask');
            if(!mask[0]){
                mask = $('<div/>', {
                    'id' : 'pian-mask'
                }).appendTo('body').on({
                    _show : function(){
                        $(this).show();
                    },
                    _hide : function(){
                        $(this).hide();
                    },
                    click : function(){
                        $('.pian-duan.pian-current').trigger('_click');
                    }
                });
            }
            mask.trigger(state ? '_show' : '_hide');
        },

        _slider : function(){
            var me = this;
            var offset = this.videoSlider.closest('.ui-video-control').position();

            this.slider = $('<div/>', {
                class : 'pian-slider'
            }).css({
                left : offset.left + 8 + 'px',
                top : offset.top - 5 + 'px'
            }).appendTo('#video-box').myslider({
                range : true,
                step : 0.1,
                animate : false,
                start : function(event, ui){
                    $(this).find('.ui-state-current').removeClass('ui-state-current');
                    var index = $(this).find('.ui-slider-handle').index(ui.handle);
                    $(this).trigger('_start', [index, 'hide']);
                },
                stop : function(event, ui){
                    $(this).find('.ui-state-focus').addClass('ui-state-current');
                    var currentHandle = $(ui.handle) || $(this).find('.ui-state-active');
                    var index = $(this).find('.ui-slider-handle').index(ui.handle);
                    var _this = $(this);
                    setTimeout(function(){
                        _this.trigger('_videoSlide', [ui.values[index]]);
                        _this.trigger('_time', [index, currentHandle]);
                        _this.trigger('_stop', [index, 'show']);
                    }, 0);
                    ui.handle.blur();
                },
                slide : function(event, ui){
                    var currentHandle = $(ui.handle) || $(this).find('.ui-state-active');
                    var index = $(this).find('.ui-slider-handle').index(currentHandle[0]);
                    var _this = $(this);
                    setTimeout(function(){
                        _this.trigger('_videoSlide', [ui.values[index]]);
                        _this.trigger('_time', [index, currentHandle]);
                    }, 0);
                }
            }).on({
                _show : function(){
                    $(this).addClass('pian-slider-current').show();
                    me.keyboardState = true;
                },

                _hide : function(){
                    $(this).removeClass('pian-slider-current').hide();
                    $(this).find('.ui-state-current').removeClass('ui-state-current');
                    me.keyboardState = false;
                },

                _start : function(event, index, state){
                    me._img(index, state);
                },

                _stop : function(event, index, state){
                    me.video.data('prev-or-next', true);
                    me._img(index, state);
                },

                _slide : function(){

                },

                _videoSlide : function(event, value){
                    me.videoSlider.slider('value', value);
                    me.video.video('scrub', value);
                },

                _change : function(event, value){
                    var currentHandle = $(this).find('.ui-slider-handle.ui-state-current');
                    if(!currentHandle[0]){
                        return false;
                    }
                    var index = $(this).find('.ui-slider-handle').index(currentHandle[0]);
                    var data =  me.slider.data(index ? 'start' : 'end');
                    /*if(data['time'] == me.video.video('getTime')){
                        return false;
                    }*/
                    $(this).myslider('values', index, value);
                    $(this).trigger('_time', [index, currentHandle]);
                    me._img(index, 'show');
                    me._refresh();
                },

                _time : function(event, index, handle){
                    var time = me.video.video('getTime');
                    var timeString = me.progress.text();
                    var which = !index ? 'start' : 'end';
                    var whichInfo = $(this).data(which) || {};
                    $(this).data(which, $.extend(whichInfo, {
                        time : time,
                        timeString : timeString
                    }));
                    handle.find('.ui-handle-time').html(timeString);
                    handle.find('.ui-handle-part').show();
                    me._refresh();
                },

                _changeHandle : function(event, handle){
                    if(!handle.hasClass('ui-state-current')){
                        $(this).find('.ui-state-current').removeClass('ui-state-current');
                        handle.addClass('ui-state-current');
                        var values = me.slider.myslider('values');
                        var index = $(this).find('.ui-slider-handle').index(handle[0]);
                        $(this).trigger('_videoSlide', [values[index]]);
                    }
                }
            });

            /*this.slider.on({
                'mousedown' : function(){
                    me.slider.trigger('_changeHandle', [$(this).closest('.ui-slider-handle')]);
                    me.video.video('prev');
                    return false;
                }
            }, '.ui-handle-left-btn');

            this.slider.on({
                'mousedown' : function(){
                    me.slider.trigger('_changeHandle', [$(this).closest('.ui-slider-handle')]);
                    me.video.video('next');
                    return false;
                }
            }, '.ui-handle-right-btn');*/

            /*var handles = this.slider.find('.ui-slider-handle').css('background', '#fff');
            var colors = ['green', 'red'];
            handles.each(function(i, n){
                var color = colors[i];
                var me = $(this);
                $.each(new Array(9), function(i, n){
                    me.animate({backgroundColor : i % 2 ? '#fff' : color}, 100);
                });
                setTimeout(function(){
                    me.css('background-color', color);
                }, 10 * 100);
            });*/

        },

        _keyboard : function(state){
            var me = this;
            $(document)[state ? 'on' : 'off']({
                'keyup' : function(event){
                    if(!me.keyboardState){
                        return;
                    }
                    var keycode = event.keyCode;
                    if(keycode == 38 || keycode == 40){
                        var handles = me.slider.find('.ui-slider-handle');
                        var index = keycode == 38 ? 0 : 1;
                        var handle = handles.eq(index);
                        var cname = 'ui-state-current';
                        if(!handle.hasClass(cname)){
                            handles.removeClass(cname);
                            handle.addClass(cname);
                        }else{
                            me.video.trigger('_pian');
                        }
                        event.preventDefault();
                    }
                }
            });
        },

        slide : function(value){
            value = value || this.videoSlider.slider('value');
            this.slider.trigger('_change', [value]);
        },

        _img : function(index, state){
            var imgBox = index > 0 ? this.endImg : this.startImg;
            if(state == 'hide'){
                $('img', imgBox).remove();
            }else{
                var me = this;
                var _change = function(){
                    me[index > 0 ? 'endImgChange' : 'startImgChange'] = true;
                };
                clearTimeout(imgBox.data('timer'));
                imgBox.data('timer', setTimeout(function(){
                    setTimeout(function(){
                        _change();
                        me._save();
                    }, 1);
                    var canvas = me.video.data('canvas');
                    imgBox.html('<img/>').find('img').attr('src', canvas.getImgFromVideo()).show();
                }, 500));
            }
        },

        _refresh : function(){
            var startInfo = this.slider.data('start');
            if(startInfo){
                this.startTime.html(startInfo['timeString'] || '');
            }
            var endInfo = this.slider.data('end');
            if(endInfo){
                this.endTime.html(endInfo['timeString'] || '');
            }
            if(startInfo && endInfo){
                this._setDuration(startInfo['time'], endInfo['time']);
            }
            this.element.data('info', {
                hash : this.hash,
                id : this.element.attr('_id'),
                start : startInfo && startInfo['time'],
                end : endInfo && endInfo['time']
            });
        },

        _setDuration : function(start, end){
            this.duration.html(this._duration(end - start)).show();
        },

        _duration : function(seconds){
            var h = parseInt(seconds / 3600);
            var m = parseInt((seconds - h * 3600) / 60);
            var s = parseInt(seconds % 60);
            return (h ? h + '\'' : '') + (m ? m + '\'' : '') + (s ? s + '"' : '');
        },

        _hash : function(){
            return (+new Date()) + '' + Math.ceil(Math.random() * 1000);
        },

        _destroy : function(){
            this.element.off().remove();
            this.pians.pians('remove');
            this.pians = null;
            this.video = null;
            this.videoSlider = null;
            this.progress = null;
            this._keyboard(false);
            this.slider.myslider('destroy').off().remove();
        }
    });
})(jQuery);
