(function($){

$.widget("video.video", {
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
            bujin : [['zhen', '逐帧步进'], ['miao', '逐秒步进']],
            canKeyboard : true
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
			self._wrapperElement.height( self.element.outerHeight(true) );

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

			// webkit bug
			if( self.options.autoPlay && ($.browser.webkit || $.browser.chrome) ) {
				self.play();
			}
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


			self._volumeSlider = $('<div/>', 
				{'class': 'ui-video-volume-slider'}
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
					//animate: false,
                    step : 1,
					start: function( e, ui ) {

					},
					stop: function( e, ui ) {
                        self._progressDiv.trigger('_html', [self.element[0].duration * (ui.value/100)]);

                        setTimeout(function(){
                            self.scrub.apply(self,[ui.value]);
                            self.element.trigger('_pian');
                        }, 0);
						ui.handle.blur();
					},
                    slide : function(e, ui){
                        self._progressDiv.trigger('_html', [self.element[0].duration * (ui.value/100)]);

                        setTimeout(function(){
                            self.scrub.apply(self,[ui.value]);
                            self.element.trigger('_pian');
                        }, 0);

                    }
				}
			);

            self._bujinDiv = $('<div/>',
                {
                    'class': 'ui-icon-bujin'
                }
            )
            .appendTo(self._controls);

		},

        _init : function(){
            this._keyboard();
            this._progress();
            this._mask();
            this._bujin();
        },

        _keyboard : function(){
            var me = this;
            var keydown37 = 0, keydown39 = 0;
            $(document).on({
                keydown : function(event){
                    if(!me.options.canKeyboard){
                        return false;
                    }
                    var keycode = event.keyCode;
                    if($.inArray(keycode, [32, 37, 38, 39, 40]) != -1){
                        event.preventDefault();
                    }
                    switch(keycode){
                        case 32:
                            me.space();
                            break;
                        case 37:
                            if($.browser.chrome){
                                me.prev();
                            }else{
                                if(keydown37) return;
                                keydown37 = 1;
                                (function(){
                                    var _this = arguments.callee;
                                    $(document).data('key-timer', setTimeout(function(){
                                        me.prev();
                                        keydown37++;
                                        _this();
                                    }, 100));
                                })();
                            }
                            break;
                        case 39:
                            if($.browser.chrome){
                                me.next();
                            }else{
                                if(keydown39) return;
                                keydown39 = 1;
                                (function(){
                                    var _this = arguments.callee;
                                    $(document).data('key-timer', setTimeout(function(){
                                        me.next();
                                        keydown39++;
                                        _this();
                                    }, 100));
                                })();
                            }
                            break;
                    }
                },
                keyup : function(event){
                    if(!me.options.canKeyboard){
                        return false;
                    }
                    var keycode = event.keyCode;
                    if($.inArray(keycode, [32, 37, 38, 39, 40]) != -1){
                        event.preventDefault();
                    }
                    switch(keycode){
                        case 37:
                        case 39:
                            if($.browser.chrome){
                            }else{
                                if(keycode == 37){
                                    keydown37 == 1 && me.prev();
                                    keydown37 = 0;
                                }else{
                                    keydown39 == 1 && me.next();
                                    keydown39 = 0;
                                }
                                clearTimeout($(this).data('key-timer'));
                                $(this).removeData('key-timer');
                            }
                            break;
                        case 40:
                            break;
                    }
                }
            });
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
                    if(!currentTime){
                        $(this).trigger('_init');
                        return;
                    }
                    $(this).html(html(me._formatTime(currentTime)));
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

        prev : function(){
            this.setTime(this._bujinDiv.data('bujin'), false);
            this.element.data('prev-or-next', true);
        },

        next : function(){
            this.setTime(this._bujinDiv.data('bujin'), true);
            this.element.data('prev-or-next', true);
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
			var m = parseInt((seconds - h*3600) / 60);
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
            self._bujinDiv.trigger('_hide');
		},

		_event_pause: function() {
			var self = this;
			self._playIcon.removeClass('ui-icon-pause').addClass('ui-icon-play');
            self._bujinDiv.trigger('_show');
		},

		_event_timeupdate: function() {
			var self = this;
            var duration = self.element[0].duration;
            var currentTime = self.element[0].currentTime;
            self._scrubberSlider.slider(
                'value',
                [(currentTime/duration)*100]
            );
            self._progressDiv.trigger('_html', [currentTime]);
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



		play: function() {
			var self = this;
			self.element[0].play();
            self.element.trigger('_play');
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
			var pos = Math.max(Math.min(parseInt(pos)/100,1),0);
			self.element[0].currentTime = pos > 1 ? duration : duration * pos;
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
            self._progressDiv.trigger('_html', [time || self.getTime()]);
        },

        changeVideo : function(info){
            this.pause();
            this._progressDiv.trigger('_init');
            this._scrubberSlider.slider('value', 0);
            this.element.trigger('_change', [info]);
        },

        closeVideo : function(){
            this.pause();
            this.element.attr('src', '');
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
		}
	});
})(jQuery);

