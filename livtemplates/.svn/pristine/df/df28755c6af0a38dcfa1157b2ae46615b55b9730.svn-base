(function($){
    /*

    */
	var defaultOption = {
         tpl : '<div class="switch-box"><div class="switch-overflow-box"><div class="switch-inner-box"><span class="switch-left"></span><span class="switch-button-box"><span class="switch-button"></span></span><span class="switch-right"></span></div></div></div>',
         width : 40,
         height : 16,
         bwidth : 16,
         time : 200,
         data : '',
         on : null,
         off : null,
         init : 'off'
	};
	$.fn.switchButton = function(option){
		option = $.extend({}, defaultOption, option);
		return this.each(function(){
			var me = $(this);
			me.append(option['tpl']);
			var box = me.find('.switch-box'),
			    innerbox = box.find('.switch-inner-box'),
			    button = box.find('.switch-button'),
			    left = box.find('.switch-left'),
			    right = box.find('.switch-right'),
			    width = option['width'],
			    height = option['height'],
			    bwidth = option['bwidth'],
			    bperwidth = bwidth / 2,
			    time = option['time'],
			    on = option['on'],
			    off = option['off'],
			    init = option['init'];
			var centerX = width / 2, minX = bperwidth, maxX = width - bperwidth;

		    innerbox.css({
		        width : width * 2 + 'px',
		        'margin-left' : - (init == 'on' ? minX : maxX)  + 'px'
		    });
			box.css({
			    width : width + 'px',
			    height : height + 'px'
			 }).find('.switch-left, .switch-right').width(width);
			button.css({
			    width : bwidth - 2 + 'px',
			    height : height - 2 + 'px',
			    'margin-left' : - bwidth / 2 + 'px'
			});
            box.selector = me;
			box.on('mousedown', function(event){
			    $(this).trigger('drag', [false]);
			    if($(this).data('loading')) return;
                var x = event.pageX, cssLeft =  - (parseInt(innerbox.css('margin-left'), 10) || 0), ncssLeft, mmove, mup;
                $(document).on('mousemove', (mmove = function(event){
                    var nx = event.pageX;
                    ncssLeft =  cssLeft + x - nx;
                    if(ncssLeft < minX){
                        ncssLeft =  minX;
                    }else if(ncssLeft > maxX){
                        ncssLeft = maxX;
                    }
                    innerbox.css('margin-left', - ncssLeft + 'px');
                })).on('mouseup', (mup = function(event){
                    if(ncssLeft != minX || ncssLeft != maxX){
                        innerbox.trigger('move', [ncssLeft < centerX ? 'on' : 'off']);
                    }
                    $(this).off('mousemove', mmove).off('mouseup', mup);
                    $(this).trigger('drag', [true]);
                }));
			}).on('drag', function(event, state){
			    this.onselectstart = this.drag = function(){return state;}
			    state = state ? 'auto' : 'none';
                $(this).attr('style', $(this).attr('style') + ';' + '-moz-user-select:'+ state +';-khtml-user-select:'+ state +';user-select:'+ state +';');
			}).on('load', function(){
			    var all = left.add(right);
			    if(left.data('hasload')){
			        var imgs = all.find('img').show();
			        setTimeout(function(){
			            box.data('loading', false);
			            imgs.hide();
			        }, 10 * 1000)
			    }else{
			        all.html('<img src="' + RESOURCE_URL + 'loading2.gif" style="width:'+(height - 2)+'px;" />');
			        left.data('hasload', true);
			    }
			    $(this).data('loading', true);
			}).on('callback', function(event, state){
			    $(this).data('loading', false);
                left.add(right).find('img').hide();
                if(state == 'on' || state == 'off'){
                    innerbox.trigger('move', [state, true]);
                }
			}).on('tip', function(event, option){
			    var tip = $(this).find('.switch-tip');
			    if(!tip.get(0)){
			        tip = $('<div class="switch-tip" style="dis;play:none;"></div>').appendTo($(this));
			    }
			    option = option || {};
			    var css = option['css'] || {};
			    if(!css['left'] && !css['right']){
			        css['left'] = (width + 10) + 'px';
			    }
			    if(!css['top'] && !css['bottom']){
			        css['top'] = 0;
			    }
			    if(!css['color']){
			        css['color'] = 'red';
			    }
			    tip.css(css);
			    tip.html(option['text'] || '出错啦...').show().delay(option['time'] || 2000).fadeOut(300);
			}).on('data', function(event, data){
			    $(this).data('data', data);
			}).trigger('data', [option['data']]);

			innerbox.data('state', init).on('move', function(event, state, callback){
			    if(box.data('loading')) return;
			    var old = $(this).data('state'), bubian = false;
			    if(old == state){
			        bubian = true;
			    }
			    $(this).data('state', state);
			    $(this).animate({
			        'margin-left' : - (state == 'on' ? minX : maxX) + 'px'
			    }, time, callback ? null : function(){
			        if(bubian) return;
			        if(state == 'off'){
			            if(off && $.type(off) == 'function'){
                            box.trigger('load');
			                off(box);
			            }
			        }else if(state == 'on'){
			            if(on && $.type(on) == 'function'){
			                box.trigger('load');
			                on(box);
			            }
			        }
			    });
			});

			left.on('mousedown', function(event){
			    innerbox.trigger('move', ['off']);
			    event.stopPropagation();
			});

			right.on('mousedown', function(event){
			    innerbox.trigger('move', ['on']);
			    event.stopPropagation();
			});
		});
	}
})(jQuery);


function tip(str){
	var div = $('#div');
	if(!div.get(0)){
		div = $('<div id="div"></div>').appendTo('body').css({
			position : 'absolute',
			top : 0,
			right : 0,
			width : '200px',
			'max-height' : '300px',
			overflow : 'auto',
			border : '1px solid red',
			background : '#000',
			color : '#fff'
		});
	}
	div.html(div.html() + str +'<br />');
}