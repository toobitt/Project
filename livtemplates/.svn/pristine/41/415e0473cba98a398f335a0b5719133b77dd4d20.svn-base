(function($){

    $.widget('point.point_video', $.ui.video,{
    	options : {
    		set_point : '.point-set'
    	},
    	
    	_create : function(){
    		this._super();
    	},
    	
    	_init : function(){
    		this._super();
    		this._set_point();
    	},
    	
    	_set_point : function(){
    		var _this = this;
    		$( this.options['set_point'] ).on( 'click', function(){
    			var data = $(this).data( 'pointInfo' );
    			if( data ){
    				_this._trigger('slide', event, [data.index, data.time, data.timeString]);
    			}else{
    				jAlert( '请设置打点时间' , '打点提醒' );
    			}
    		});
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
	    	_slider.slider({
	    		step : 1,
	    		range : false,
	    		start : function( event, ui ){
					var cname = 'ui-state-current';
					var handle = $(ui.handle);
					if(!handle.hasClass(cname)){
					    handle.siblings('.' + cname).removeClass(cname);
					    handle.addClass(cname);
					}else{
					    slideClick = true;
					}
	    		},
	    		
	    		stop : function( event, ui ){
	    			ui.handle.blur();
                    if(slideMove){
                        $(this).trigger('_slide', [ui, 'stop']);
                        slideMove = false;
                    }else if(slideClick){
                        $(this).trigger('_slideClick', [ui]);
                        slideClick = false;
                    }
	    		},
	    		
	    		slide : function( event, ui ){
	    			slideMove = true;
	    			$(this).trigger('_slide', [ui]);
	    		}
	    	}).on({

                _init : function(){
                    $(this).slider('value', 20);
                    $(this).find('.ui-state-current').removeClass('ui-state-current');
                },

                _slide : function(event, ui, state){
                    var index = $(this).find('.ui-slider-handle').index(ui.handle);
                    var value = ui.value;
                    self._scrubberSlider.slider('value', value);
                    var time = self.scrub(value);
                    var timeString = self._progressDiv.triggerHandler('_html', [time]);
                    $(this).data('slide' + index, time);
                    $( self.options['set_point'] ).data( 'pointInfo', { index: index, time : time, timeString: timeString  } );
                    if('stop' == state){
                        //self._trigger('slide', event, [index, time, timeString]);
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
                    $(this).slider('value', value);
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

    	}
    });
	
})(jQuery);

