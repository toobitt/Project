(function($){

    $.Timer = function(){
        window.requestAFrame = (function(){
            return window.requestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                window.oRequestAnimationFrame ||
                function(callback){
                    window.setTimeout(callback, 1000 / 60);
                };
        })();
        window.cancelAFrame = (function(){
            return window.cancelAnimationFrame ||
                window.webkitCancelAnimationFrame ||
                window.mozCancelAnimationFrame ||
                window.oCancelAnimationFrame ||
                function(id){
                    window.clearTimeout(id);
                };
        })();

        var it = {};
        var timer = null;
        var isStart = false;
        it.loop = function(){
            if(this.check()){
                timer = window.requestAFrame(function(){
                    it.doing();
                    it.loop();
                });
            }
        };
        it.start = function(){
            isStart = true;
            this.loop();
        };
        it.stop = function(){
            isStart = false;
            timer && window.cancelAFrame(timer) && (timer = null);
        };
        it.isStart = function(){
            return isStart;
        };

        it.check = function(){
            return true;
        };
        it.doing = function(){

        };
        return it;
    }();


    $.widget('video.myslider', $.ui.slider, {
        _init : function(){
            this._super();
            this.initHandle();
            this.createHandlePart();
        },

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
        }
    });

    $.widget("ui.video", {
		options: {
			volume:.5,
			fadeSpeed: 1000,
			fadeDelay: 2000,
			minHeight: 0,
			minWidth: 0,
			width: null,
			height: null,
			autoPlay: false,
			loop: false,
			autoBuffer: true,
            poster : null,
            zhen : 15,
            bj : true,
            bujin : [['zhen', '逐帧步进'], ['miao', '逐秒步进']],
            kz : false,
            keyevent : true,
            slider : true
		},

		_create: function() {
			var self = this;

			var videoOptions = {
				width: Math.max( self.element.outerWidth() , self.options.minWidth ),
				height: Math.max( self.element.outerHeight() , self.options.minHeight ),
				autoplay: self.options.autoPlay,
				controls: false,
				loop: self.options.loop,
				autobuffer: self.options.autoBuffer,
                poster : self.options.poster
			};

			self.element.wrapAll( $('<div />',{'class': 'ui-video-widget'}) );

			self._wrapperElement = self.element.parent();
			self._wrapperElement.width( self.element.outerWidth(true) );
			self._wrapperElement.height( self.element.outerHeight(true) + 40 );

            self._maskDiv = $('<div/>', {
                class : 'ui-video-mask'
            }).appendTo(self._wrapperElement);

            self._progressDiv = $('<div/>', {
                'class': 'ui-video-progress',
                'html': '<span class="v-hr">00</span>:<span class="v-min">00</span>:<span class="v-sec">00</span>:<span class="v-fr">00</span>'
            }).appendTo(self._wrapperElement);

			self._oldVideoOpts = {};

			$.each( videoOptions , function( key, value) {
					if( value !== null ) {
						// webkit bug
						if( key == 'autoplay' && $.browser.webkit ) {
							value = false;
						}
						self._oldVideoOpts[key] = self.element.attr( key );
						self.element.attr( key, value );
					}
				}
			);

			var videoEvents = [
				"abort",
				"canplay",
				"canplaythrough",
				"canshowcurrentframe",
				"dataunavailable",
				"durationchange",
				"emptied",
				"empty",
				"ended",
				"error",
				"loadedfirstframe",
				"loadedmetadata",
				"loadstart",
				"pause",
				"play",
				"progress",
				"ratechange",
				"seeked",
				"seeking",
				"suspend",
				"timeupdate",
				"volumechange",
				"waiting",
				"resize"
			];

			$.each( videoEvents, function(){
					if( self["_event_" + this] ) {
						self.element.bind( 
							this + ".video", 
							$.proxy(self["_event_" + this],self) 
						);
					}
				}
			);

            if(self.options.customEvents){
                $.each(self.options.customEvents, function(i, n){
                    self.element.on(i + '.video', n);
                });
            }

            self._createControls();

            if(self.options.customControl){
                $.proxy(self.options.customControl, self);
            }


			self._volumeSlider.slider('value', self.options.volume * 100);


		},

		_createControls: function() {
			var self = this;


			self._controls = $('<div/>', 
				{
					'class': 'ui-widget ui-widget-content ui-corner-all ui-video-control'
				}
			)
			.appendTo(self._wrapperElement);


			self._muteIcon = $('<div/>', 
				{
					'class': 'ui-icon ui-icon-volume-on ui-video-mute'
				}
			)
			.appendTo(self._controls)
			.bind('click.video', $.proxy(self._mute,self));


			self._playIcon = $('<div/>', 
				{
					'class': 'ui-icon ui-icon-play ui-video-play'
				}
			)
			.appendTo(self._controls)
			.bind('click.video', $.proxy(self._playPause,self));


            if(self.options.bj){
                self._seekPrevIcon = $('<div/>',
                    {
                        'class': 'ui-icon ui-icon-seek-prev ui-video-seek-prev'
                    }
                )
                .appendTo(self._controls)
                .on('click.video', $.proxy(self.prev, self));


                self._seekNextIcon = $('<div/>',
                    {
                        'class': 'ui-icon ui-icon-seek-next ui-video-seek-next'
                    }
                )
                .appendTo(self._controls)
                .on('click.video', $.proxy(self.next, self));
            }


			self._volumeSlider = $('<div/>', 
				{
					'class': 'ui-video-volume-slider'}
			)
			.appendTo(self._controls)
			.slider({
					range: 'min',
					//animate: true,
					stop: function( e, ui ) {
						ui.handle.blur();
					},
					slide: function( e, ui ) {
						self.volume.apply(self,[ui.value]);
						return true;
					}
				}
			);


            var slideOuter = $('<div/>', {class : 'ui-video-scrubber-slider-outer'}).appendTo(self._controls);
            var slideInner = $('<div/>', {class : 'ui-video-scrubber-slider-inner'}).appendTo(slideOuter);
			self._scrubberSlider = $('<div/>',
				{
					'class': 'ui-video-scrubber-slider'
				}
			)
			.appendTo(slideInner)
			.slider({
					range: 'min',
					animate: false,
                    step : 0.1,
					start: function( e, ui ) {

					},
					stop: function( e, ui ) {
                        self.element.trigger('_pian');
						ui.handle.blur();
					},
                    slide : function(e, ui){
                        self.scrub.apply(self,[ui.value]);
                        //self._progressDiv.triggerHandler('_html', [self.element[0].duration * (ui.value/100)]);
                    }
				}
            )
            .on({
                _init : function(){
                    $(this).slider('value', 0);
                }
            });

            if(self.options.bj){
                self._bujinDiv = $('<div/>',
                    {
                        'class': 'ui-icon-bujin'
                    }
                )
                .appendTo(self._controls);
            }else{
                self._bujinDiv = $({});
            }


            self._scrubberSliderAbsoluteWidth = self._scrubberSlider.width();
            self._bufferStatus = $('<div/>',
                {
                    'class': 'ui-video-buffer-status'
                }
            ).appendTo( self._scrubberSlider );
		},

        _init : function(){
            this._keyevent();
            this._progress();
            this._mask();
            this._bujin();
            this._slider();

            if(this.options['kz']){
                this._kz();
            }

            // webkit bug
            var self = this;
            if( self.options.autoPlay && ($.browser.webkit || $.browser.chrome) ) {
                self.play();
            }
        },

        _keyevent : function(){
            var _this = this;
            if(_this.options['keyevent']){
                var doc = $(document);

                doc.on({
                    keydown : function(event){
                        var code = event.keyCode;
                        if($(this).data('has-input-focus')){
                            return;
                        }
                        if(!_this.element.is(':visible')){
                            return;
                        }
                        switch(code){
                            case 32:
                                _this._playPause();
                                event.preventDefault();
                                break;
                            case 37:
                                _this.prev();
                                break;
                            case 39:
                                _this.next();
                                break;

                            case 38:
                                _this.ru();
                                event.preventDefault();
                                break;
                            case 40:
                                _this.chu();
                                event.preventDefault();
                                break;
                        }
                    }
                });

                doc.on({
                    focus : function(){
                        $(document).data('has-input-focus', true);
                    },

                    blur : function(){
                        $(document).data('has-input-focus', false);
                    }
                }, 'input');
            }
        },

        _progress : function(){
            var me = this;

            function html(timeInfo){
                return '<span class="v-hr">' + timeInfo['hr'] + '</span>' +
                    ':' + '<span class="v-min">' + timeInfo['min'] + '</span>' +
                    ':' + '<span class="v-sec">' + timeInfo['sec'] + '</span>' +
                    ':' + '<span class="v-fr">' + timeInfo['fr'] + '</span>';
            }

            this._progressDiv.on({
                _init : function(){
                    var string = '00';
                    $(this).html(html({
                        hr : string,
                        min : string,
                        sec : string,
                        fr : string
                    }));
                },

                _html : function(event, currentTime){
                    if($.type(currentTime) == 'undefined'){
                        $(this).trigger('_init');
                        return '00:00:00:00';
                    }
                    var timeString = html(me._formatTime(currentTime));
                    $(this).html(timeString);
                    return timeString;
                },

                _getHtml : function(){
                    return $(this).text();
                }
            });
        },

        _mask : function(){
            var me = this;
            this._maskDiv.on({
                click : function(){
                    me._playIcon.trigger('click');
                },
                'load-start' : function(){
                    var img = $(this).find('img');
                    if(!img[0]){
                        img = $('<img src="'+ RESOURCE_URL + 'loading2.gif"/>').css({
                            position : 'absolute',
                            left : '50%',
                            top : '50%',
                            width : '30px',
                            margin : '-15px 0 0 -15px'
                        }).appendTo(this);
                    }
                    img.show();
                },
                'load-end' : function(){
                    $(this).find('img').hide();
                }
            });
        },

        _bujin : function(){
            if(!this.options.bj){
                return;
            }
            var types = {
                type : this.options.bujin.concat(),
                next : function(){
                    var _index = -1;
                    return function(current){
                        if(typeof current != 'undefined'){
                            return this.type[_index];
                        }
                        var len = this.type.length;
                        _index++;
                        _index >= len && (_index = 0);
                        return this.type[_index];
                    }
                }()
            };

            var me = this;
            this._bujinDiv.on({
                click : function(){
                    if($(this).data('ban')) return;
                    var type = types.next();
                    $(this).html(type[1]);
                    $(this).trigger('_bujin', [type[0]]);
                },
                _show : function(){
                    $(this).data('ban', false).css({'opacity' : 1, 'cursor' : 'pointer'});
                    $(this).trigger('_bujin', [types.next(true)[0]]);
                },
                _hide : function(){
                    $(this).data('ban', true).css({'opacity' :.3, 'cursor' : 'default'});
                    $(this).trigger('_bujin', ['miao5']);
                },
                _bujin : function(event, type){
                    var zhen = me.options.zhen;
                    var bujin;
                    switch(type){
                        case 'zhen' :
                            bujin = 1 / zhen;
                            break;
                        case 'zhen5' :
                            bujin = 5 / zhen;
                            break;
                        case 'miao' :
                            bujin = 1;
                            break;
                        case 'miao5' :
                            bujin = 5;
                            break;
                    }
                    $(this).data('bujin', bujin);
                }
            }).trigger('click');
        },

        _kz : function(){
            var _this = this;
            _this._kz = $('<div/>').attr({
                class : 'ui-video-kz'
            }).appendTo(_this._controls);
            _this._kz.on({
                mousedown : function(){
                    $(this).addClass('ui-video-kz-down');
                    var _this = this;
                    setTimeout(function(){
                        $(_this).triggerHandler('mouseup');
                    }, 1000);
                },

                mouseup : function(){
                    $(this).removeClass('ui-video-kz-down');
                },

                click : function(){
                    _this._trigger('clickKZ');
                }
            });
        },

        kz : function(){
            this._kz.triggerHandler('click');
        },

        prev : function(){
            this.setTime(this._bujinDiv.data('bujin'), false);
            this.element[0].paused && this._videoSlider.triggerHandler('_key');
        },

        next : function(){
            this.setTime(this._bujinDiv.data('bujin'), true);
            this.element[0].paused && this._videoSlider.triggerHandler('_key');
        },

        ru : function(){
            this.element[0].paused && this._videoSlider.triggerHandler('_keyRC', [0]);
        },

        chu : function(){
            this.element[0].paused && this._videoSlider.triggerHandler('_keyRC', [1]);
        },

		_playPause: function() {
			var self = this;
			if( self.element[0].paused ) {
				self.play();
			} else {
				self.pause();
			}
		},

		_mute: function() {
			var self = this;
			var muted = self.element[0].muted = !self.element[0].muted;
			self._muteIcon.toggleClass('ui-icon-volume-on', !muted).toggleClass('ui-icon-volume-off', muted);
		},

        _showSpinner: function(){

        },

        _hideSpinner: function(){

        },

		_formatTime: function( seconds ) {
			var h = parseInt(seconds / 3600);
			var m = parseInt(seconds / 60);
			var s = parseInt(seconds % 60);
			var sp = s >= 10 ? '' : '0';
			var mp = m >= 10 ? '' : '0';
			var hp = h >= 10 ? '' : '0';
            var t = seconds;
            t -= Math.floor(t);
            t *= Math.ceil(this.options.zhen);
            t = Math.ceil(t);
            var tp = t >= 10 ? '' : '0';
            return {
                hr : hp + h,
                min : mp + m,
                sec : sp + s,
                fr : tp + t
            };
		},

		_event_canplay: function() {
			var self = this;
			self._hideSpinner();
            self.element[0].volume = self.options.volume;
		},

		_event_loadstart: function() {
			var self = this;
			self._showSpinner();
		},

		_event_durationchange: function() {
			var self = this;
			self._showSpinner();
		},

		_event_seeking: function() {
			var self = this;
			self._showSpinner();
		},

		_event_waiting: function() {
			var self = this;
			self._showSpinner();
		},

		_event_loadedmetadata: function() {
		},

		_event_play: function() {
			var self = this;
			self._playIcon.addClass('ui-icon-pause').removeClass('ui-icon-play');
            self._bujinDiv.triggerHandler('_hide');
		},

		_event_pause: function() {
			var self = this;
			self._playIcon.removeClass('ui-icon-pause').addClass('ui-icon-play');
            self._bujinDiv.triggerHandler('_show');
		},

		_event_timeupdate: function() {
			var self = this;
            var duration = self.element[0].duration;
            var currentTime = self.element[0].currentTime;
            self._scrubberSlider.slider(
                'value',
                [(currentTime/duration)*100]
            );
            self._progressDiv.triggerHandler('_html', [currentTime]);
		},

		_event_resize: function() {return;
			var self = this;
			self._controls.position({
					'my': 'bottom',
					'at': 'bottom',
					'of': self.element,
					'offset': '0 -10',
					'collision': 'none'
				}
			);
			self._wrapperElement.width( self.element.outerWidth(true) );
			self._wrapperElement.height( self.element.outerHeight(true) );
		},

        _progressCache : {},
        _event_progress: function(e) {
            var _this = this;
            /*var lengthComputable = e.originalEvent.lengthComputable,
                loaded = e.originalEvent.loaded,
                total = e.originalEvent.total;
            if( lengthComputable ) {
                var fraction = Math.max(Math.min(loaded / total,1),0);
                this._bufferStatus.width(Math.max(fraction * self._scrubberSliderAbsoluteWidth));
            }*/
            if(_this.element[0].paused){
                return;
            }
            try{
                var buffered = _this.element[0].buffered;
                var lastBufferedEnd = _this._progressCache['end'];
                if(buffered && buffered.length > 0){
                    var bufferedEnd = buffered.end(0);
                    if(lastBufferedEnd == bufferedEnd){
                        return;
                    }
                    _this._progressCache['end'] = bufferedEnd;
                    var duration = _this.element[0].duration;
                    _this._bufferStatus.css('width', bufferedEnd * 100 / duration + '%');
                }
            }catch(e){

            }
        },



		play: function() {
			var self = this;
			self.element[0].play();
            self.element.trigger('_play');
            this._videoSlider.triggerHandler('_reset');
		},
		pause: function() {
			var self = this;
			self.element[0].pause();
            self.element.trigger('_pause');
		},
        space : function(){
            this[this.element[0].paused ? 'play' : 'pause']();
        },
		mute: function() {
			var self = this;
			self.element[0].muted = true;
		},
		unmute: function() {
			var self = this;
			self.element[0].muted = false;
		},
		volume: function(vol) {
			var self = this;
			self.element[0].volume = Math.max(Math.min(parseInt(vol)/100,1),0);
		},
		scrub: function(pos){
			var self = this;
			var duration = self.element[0].duration;
			//var pos = Math.max(Math.min(parseInt(pos)/100,1),0);
            pos /= 100;
			self.element[0].currentTime = pos > 1 ? duration : duration * pos;
            return self.element[0].currentTime;
		},

        setTime : function(t, type){
            var self = this;
            if(typeof type != 'undefined'){
                if(type){
                    self.element[0].currentTime += t;
                }else{
                    self.element[0].currentTime -= t;
                }
            }else{
                this._maskDiv.trigger('load-start');
                self.element[0].currentTime = t;
                setTimeout(function(){
                    self._maskDiv.trigger('load-end');
                }, 10);
            }
        },

        getTime : function(){
            return this.element[0].currentTime;
        },

        timeupdate : function(time){
            var self = this;
            self._progressDiv.trigger('_html', [time || self.getTime()]);
        },

        changeVideo : function(info){
            this.pause();
            this._progressDiv.triggerHandler('_init');
            this._videoSlider.triggerHandler('_init');
            this._scrubberSlider.triggerHandler('_init');
            this.element.trigger('_change', [info]);
        },

		destroy: function() {
			var self = this;
			$.each( self._oldVideoOpts , function( key, value) {
					self.element.attr( key, value );
				}
			);

			self._controls.remove();
			self.element.unwrap();
			self.element.unbind( ".video" );
			$.Widget.prototype.destroy.apply(self, arguments);
		},


        _slider : function(){
            if(!this.options.slider){
                this._videoSlider = $({});
                return;
            }
            var self = this;
            var _slider = this._videoSlider = $('<div/>', {
                class : 'ui-video-slider'
            }).css({
                left : '0px',
                top : - 5 + 'px'
            }).appendTo(self._controls);


            var slideMove = false;
            var slideClick = false;
            _slider.myslider({
                range : true,
                step : 1,
                animate : false,
                start : function(event, ui){
                    var cname = 'ui-state-current';
                    var handle = $(ui.handle);
                    if(!handle.hasClass(cname)){
                        handle.siblings('.' + cname).removeClass(cname);
                        handle.addClass(cname);
                    }else{
                        slideClick = true;
                    }
                },

                stop : function(event, ui){
                    ui.handle.blur();
                    if(slideMove){
                        $(this).trigger('_slide', [ui, 'stop']);
                        slideMove = false;
                    }else if(slideClick){
                        $(this).trigger('_slideClick', [ui]);
                        slideClick = false;
                    }
                },

                slide : function(event, ui){
                    slideMove = true;
                    $(this).trigger('_slide', [ui]);
                }

            }).on({

                _init : function(){
                    $(this).myslider('values', [0, 20]);
                    $(this).find('.ui-state-current').removeClass('ui-state-current');
                    self._trigger('sliderInit', null, [this]);
                },

                _slide : function(event, ui, state){
                    var index = $(this).find('.ui-slider-handle').index(ui.handle);
                    var value = ui.values[index];
                    self._scrubberSlider.slider('value', value);
                    var time = self.scrub(value);
                    var timeString = self._progressDiv.triggerHandler('_html', [time]);
                    $(this).data('slide' + index, time);
                    if('stop' == state){
                        self._trigger('slide', event, [index, time, timeString]);
                    }
                },

                _slideClick : function(event, ui){
                    var index = $(this).find('.ui-slider-handle').index(ui.handle);
                    var time = $(this).data('slide' + index);
                    self.element[0].currentTime = time;
                },

                _reset : function(){
                    $(this).find('.ui-state-current').removeClass('ui-state-current');
                },

                _key : function(){
                    var currentHandle = $(this).find('.ui-state-current');
                    if(!currentHandle[0]){
                        return;
                    }
                    var index = $(this).find('.ui-slider-handle').index(currentHandle[0]);
                    $(this).triggerHandler('_keyPN', [index]);
                    if(self.keyTimer){
                        clearTimeout(self.keyTimer);
                    }
                    var _this = this;
                    self.keyTimer = setTimeout(function(){
                        $(_this).triggerHandler('_keyPNAfter', [index]);
                    }, 1000);
                },

                _keyPN : function(event, index){
                    var value = self._scrubberSlider.slider('value');
                    $(this).myslider('values', index, value);
                },

                _keyPNAfter : function(event, index){
                    var time = self.getTime();
                    var timeString = self._progressDiv.triggerHandler('_getHtml');
                    self._trigger('slide', null, [index, time, timeString]);
                },

                _keyRC : function(event, index){
                    $(this).find('.ui-slider-handle').eq(index).addClass('ui-state-current').siblings().removeClass('ui-state-current');
                    $(this).triggerHandler('_keyPN', [index]);
                    $(this).triggerHandler('_keyPNAfter', [index]);
                }
            });

            _slider.triggerHandler('_init');
        },

        setSlider : function(index, value){
            if($.type(value) == 'undefined'){
                this._videoSlider.myslider('values', index);
                return;
            }
            this._videoSlider.myslider('values', index, value);
        },

        sliderStart : function(time, cb){
            this._sliderSE(time, 0, cb);
        },

        sliderEnd : function(time, cb){
            this._sliderSE(time, 1, cb);
        },

        _sliderSE : function(time, index, cb){
            this.setTime(time);
            this._event_timeupdate();
            this._videoSlider.triggerHandler('_keyPN', [index]);
            if(!cb){
                this._videoSlider.triggerHandler('_keyPNAfter', [index]);
            }else{
                var _this = this;
                setTimeout(function(){
                    _this._videoSlider.triggerHandler('_keyPNAfter', [index]);
                    cb && $.isFunction(cb) && cb();
                }, 1000);
            }

        },

        setSliderPN : function(index){
            var $mySlider = this._videoSlider;
            $mySlider.triggerHandler('_keyPN', [index]);
            $mySlider.triggerHandler('_keyPNAfter', [index]);
        }
	});
})(jQuery);

