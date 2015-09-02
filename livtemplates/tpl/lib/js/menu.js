
jQuery(function($){

    var menuType = $('.menu-all').hasClass('menu-all-other') ? 0 : 3;
    //menuType = 0;
    (function(){
        if(menuType != 0) return;
        $('.menu-li').on('click', function(event){
            if($(event.target).closest('.menu-item').length){
                return;
            }
            var mi = $(this).find('.menu-icon');
            $.each([30, -30, 20, -20, 10, -10, 'end'], function(i, n){
                if(n == 'end'){
                    mi.queue('mt', function(next){
                        $(this).removeAttr('style');
                    });
                }else{
                    mi.queue('mt', function(next){
                        $(this).css('transform', 'rotate(' + n + 'deg)');
                        next();
                    }).delay(50, 'mt');
                }
            });
            mi.dequeue('mt');

        });
    })();

    (function(){
        if(menuType != 1) return;
        $('.menu-items').css({
            transform : 'rotateY(90deg) translateZ(75px)'
        }).show();
        $('.menu-all').on('rotate', function(event, redirect){
            var items = $('.menu-items'), itemsType = '', cats = $('.menu-cats'), catsType = '', zIndex = 1;
            if(redirect == 'next'){
                catsType = 'rotateY(-90deg) translateZ(75px)';
                itemsType = 'rotateY(0deg) translateZ(0px)';
                zIndex = 3;
            }else{
                catsType = 'rotateY(0deg) translateZ(0px)';
                itemsType  = 'rotateY(90deg) translateZ(75px)';
                zIndex = 1;
            }

            items.css('z-index', zIndex).css({
                transform : itemsType,
                transition : 'all 0.3s'
            });
            cats.css({
                transform : catsType,
                transition : 'all 0.3s'
            });
        });
        $('.menu-cats').on('click', '.menu-li', function(){
            var childs = $(this).attr('childs');
            $('#childs_' + childs).show().siblings('.menu-item').hide();
            $('.menu-all').trigger('rotate', ['next']);
        });
        $('.menu-back').on('click', function(){
            $('.menu-all').trigger('rotate', ['back']);
        });

    })();

    (function(){
        if(menuType != 2) return;
        var all = $('.menu-all');
        var allWidth = all.width();
        $('.menu-items').removeClass('menu-items-transform').css({
            left : allWidth + 'px'
        });
        all.on('move', function(event, redirect){
            $('.menu-items').show();
            var leftVal = redirect == 'next' ? -allWidth : 0;
            $(this).animate({
                left : leftVal + 'px'
            }, 150);
        });
        $('.menu-cats').on('click', '.menu-li', function(){
            var childs = $(this).attr('childs');
            $('#childs_' + childs).show().siblings('.menu-item').hide();
            $('.menu-all').trigger('move', ['next']);
        });
        $('.menu-back').on('click', function(){
            $('.menu-all').trigger('move', ['back']);
        });

    })();

    (function(){
        if(menuType != 3) return;
        var all = $('.menu-all');
        var allWidth = all.width();
        var items = $('.menu-items').css('opacity', 0);
        $('.menu-cats').on('click', '.menu-li', function(){
            var childs = parseInt($(this).attr('childs'), 10);
            var childItem = $('#childs_' + childs);
            if(childItem[0]){
                childItem.show().siblings('.menu-item').hide();
            }else{
                $('.menu-item').hide();
            }

            var position = $(this).position();
            var mask = $('<ul class="menu-mask menu-cats" style="width:'+ allWidth +'px;top:'+ position.top +'px;" _top="'+ position.top +'"></ul>').appendTo(all);
            var maskLi = mask.append($(this).clone()).find('.menu-li').addClass('gaoliang').append('<div class="menu-item-back"></div>').css('background-color', '#373737');
            items.show();
            setTimeout(function(){
                mask.css({
                    'transition-property' : 'opacity, top',
                    'transition-duration' : '0.2s, 0.4s',
                    'transition-delay' : '0s, 0.2s',
                    'transition-timing-function' : 'ease',
                    opacity : 1,
                    'top' : '-44px'
                });
                items.css({
                    transition : 'all 0.3s linear 0.4s',
                    opacity : 1
                });
            }, 10);


            maskLi.one('click', function(){


                items.css({
                    transition : 'all 0.8s',
                    opacity : 0
                });
                items.delay(800, 'menu-items').queue('menu-items', function(next){
                    $(this).hide();
                    next();
                }).dequeue('menu-items');
                var parent = $(this).parent();
                parent.css({
                    'transition-property' : 'top, opacity',
                    'transition-duration' : '0.4s, 0.2s',
                    'transition-delay' : '0.5s, 0.9s',
                    'transition-timing-function' : 'ease',
                    'top' : parent.attr('_top') + 'px',
                    opacity : 0
                });
                parent.delay(1200, 'menu-mask').queue('menu-mask', function(next){
                    $(this).remove();
                    next();
                }).dequeue('menu-mask');
            });
            /*$('.menu-items').animate({
                    opacity : 0
              },300, function(){
                 $(this).hide();
                    parent.animate({
                    'padding-top' : parent.attr('_top'),
                    opacity : 0
                 }, 400, function(){
                    $(this).remove();
                 });
             });*/
            /*mask.animate({
                'padding-top' : 0,
                opacity : 1
            }, 400, function(){
                $('.menu-items').show().css({
                    'z-index' : 101,
                    left : 8 + 'px',
                    top : '30px',
                    opacity : 0
                }).animate({
                    opacity : 1
                }, 300);
            });*/


        });
    })();
});

jQuery(function($){
    $('.menu-all').on('click', '.menu-child', function(){
        var cname = 'menu-child-current';
        if(!$(this).hasClass(cname)){
            $('.' + cname).removeClass(cname);
            $(this).addClass(cname);
        }
    });
});


jQuery(function($){
	var menu = $('#hg_menu');
	if(!menu.get(0)) return;

    /*menu.css({
        overflow : 'hidden',
        'z-index' : 1000,
        height : '43px',
        width : '55px'
    });
    var zanshiTime = 100;
    menu.hover(function(){
        $(this).stop().animate({
            width : '150px'
        }, zanshiTime / 2).animate({
            height : '100%'
        }, zanshiTime);
    }, function(){
        $(this).stop().animate({
            height : '43px'
        }, zanshiTime).animate({
            width : '55px'
        }, zanshiTime / 2);
    });
    menu.find('.menu-all').width(150);*/

	var cachestate = {
        getcache : function(){
            return $.DOMCached.get("key", "cachestate");
        },
        setcache : function(value){
            $.DOMCached.set("key", value, false, "cachestate");
        }
    };
    var cachestateval = cachestate.getcache() || 1;
		
	var suo = '55';
	var timer = null;
	var time = {
		//zhan : 250,
		//suo : 250,
        zhan : 300,
		suo : 500,
		delay : 800,
		slide : 250,
		hover : 500,
		auto : 1000
	};
	var hoverin = false;
	menu.bind('mouseenter', function(){
		hoverin = true;
		if(!mstate.data('has-state')){
		    mainwin.trigger('open');
		}
	}).bind('mouseleave', function(){
		hoverin = false;
		if(!mstate.data('has-state')){
		    mainwin.trigger('suo');
		}
		setTimeout(function(){
			mainul.trigger('auto-move');
		}, time.slide);
	}).bind('clear', function(){
		if(timer){
			clearTimeout(timer);
			timer = null;
		}
	}).bind('dclear', function(){
		if(dtimer){
			clearTimeout(dtimer);
			dtimer = null;
		}
	}).bind('current', function(event, now){
		var current = menu.data('current');
		var self = current == now ? true : false;
		if(!self){
			menu.data('current', now);
			now = $(now);
			now.find('h2').addClass('cur');
			var nlist = now.find('.list_menu');
			if(nlist.get(0)){
				menu.data('move', true);
				nlist.slideDown(time.slide, function(){
					menu.data('move', false);
					mainul.trigger('change-auto-move-state', [true]);
				});
			}
		}
		if(current){
			current = $(current);
			var clist = current.find('.list_menu'),
				ch2 = function(){!self && current.find('h2').removeClass('cur');};
			clist.get(0) ? clist.slideUp(time.slide, function(){ch2();mainul.trigger('change-auto-move-state', [true]);}) : ch2();
		}
	}).bind('init', function(){
		$(this).data('width', $(this).width());
	}).trigger('init');

	(function(){
		var timeout = null;
		var state = false;
		var y = disy = stop = nowy = min = max = 0;
		menu.bind('mousedown', function(event){return;
			if(state){
				$(this).trigger('mouse-move', [true]);
				return false;
			}
			timeout = setTimeout(function(){
				menu.trigger('mouse-move');
				y = event.pageY;
				stop = parseInt(mainul.css('top')) || 0;
				menu.data('user-move', true);
				max = 0;
				var mheight = mainul.outerHeight(),
					wheight = $(window).height();
				min = mheight < wheight ? 0 :  wheight - mheight;
			}, 1000);
		}).bind('mouseup', function(){
			if(timeout){
				clearTimeout(timeout);
				timeout = null;
			}
		}).bind('mousemove', function(event){
			if(!state) return;
			disy = event.pageY - y;
			nowy = stop + disy;
			if(nowy > max){
				nowy = max;
			}else if(nowy < min){
				nowy = min;
			}
			mainul.css('top', nowy + 'px');
		}).bind('mouse-move', function(event, clear){
			if(clear){
				$('#mouse-mask').trigger('_hide', [true]);
				state = false;
				menu.data('state', state).trigger('user-select', [true]);
				return;
			}
			$('#mouse-mask').trigger('_show', [true]);
			state = true;
			menu.data('state', state).trigger('user-select');
			mainul.trigger('change-auto-move-state', [true]);
		}).bind('user-select', function(event, clear){
			if(clear){
				$(this).removeClass('user-select');
				this.onselectstart = this.ondrag = function(){return true;}
				return;
			}
			$(this).addClass('user-select');
			this.onselectstart = this.ondrag = function(){return false;}
		});
	})();

	var mainul = $('#main-ul').bind('move-start', function(event, direction, only){
		var self = $(this);
		var mheight = self.outerHeight();
		var wheight = $(window).height();
		if(only){
			self.css('top', (direction == 'up' ? 0 : (wheight - mheight)) + 'px');
			return false;
		}
		if(mheight < wheight) return;
		var top = parseInt(self.css('top')) || 0;
		$(this).data('move-timer', setInterval(function(){
			top += direction == 'up' ? 3 : -3;
			if(top > 0){
				top = 0;
				self.trigger('move-stop');
			}else if(top + mheight <= wheight){
				top = wheight - mheight;
				self.trigger('move-stop');
			}
			self.css('top', top + 'px');
		}, 10));
	}).bind('move-stop', function(){
		$(this).data('move-timer') && clearInterval($(this).data('move-timer'));
	});
		
	mainul.bind('auto-move', function(){
		if(!$(this).data('need-auto-state')) return;
		if($(this).data('auto-move')) return;
		var mheight = $(this).outerHeight();
		var wheight = $(window).height() - $('#menu-logo').outerHeight();
		var ntop = 0;
		if(mheight == wheight) return;
		if(mheight > wheight){
			var current = menu.data('current');
			if(!current) return;
			current = $(current);
			if(!current) return;
			var cheight = current.outerHeight();
			var cposition = current.position();
			if(cheight > wheight){
				ntop = -cposition.top;
			}else{
				ntop = cposition.top + cheight;
				ntop = ntop < wheight ? 0 : wheight - ntop;
			}
		}
		$(this).data('auto-move', true).animate({
			top : ntop + 'px'
		}, time.auto, function(){
			$(this).data('auto-move', false).trigger('change-auto-move-state', [false]);
		});
	}).bind('change-auto-move-state', function(event, state){
		$(this).data('need-auto-state', state);
	});

	//下面打注释的地方是去掉li.nav的mouseenter
	var dtimer = null;
	menu.delegate('h2', 'click', function(event){
		var current = menu.data('current');
		if(current && current != $(this).parent().get(0)){
			current = $(current);
			current.find('h2').removeClass('cur');
			var oldlist = current.find('.list_menu');
			!oldlist.is(':hidden') && oldlist.slideUp(time.slide);
		}
		menu.data('current', $(this).parent().get(0));
		if(!$(this).hasClass('cur')){
			$(this).addClass('cur');
		}
		if(menu.data('state')) return;
		var list = $(this).parent().find('.list_menu');
		if(list.is(':animated')) return;
		mainul.trigger('change-auto-move-state', [true]);
		list[list.is(':hidden') ? 'slideDown' : 'slideUp'](time.slide);
	}).delegate('.list_menu a', 'click', function(){
		var last = menu.data('ca');
		if(this === last) return;
		menu.data('ca', this);
		last && $(last).removeClass('cur');
		$(this).addClass('cur');
	});

	(function(){
        return;
		var tpl = '<div id="mouse-{id}" class="mouse-move"></div>';
		$.each(['up', 'down'], function(i, n){
			$(tpl.replace('{id}', n)).appendTo(menu).hover(function(){
				mainul.trigger('move-start', [n]);
				$(this).addClass('mouse-move-current');
				mask.trigger('_show');
				menu.trigger('dclear');
			}, function(){
				mainul.trigger('move-stop');
				$(this).removeClass('mouse-move-current');
				mask.trigger('_hide');
			});
		});

		tpl = '<div id="mouse-mask"></div>';
		var mask = $(tpl).appendTo(menu).bind('_show', function(event, addclass){
			$(this).css({
				height : menu.height() + 'px',
				display : 'block'
			});
			if(addclass){
				$(this).addClass('mouse-mask-show');
				$('.mouse-move').hide();
			}
		}).bind('_hide', function(event, removeClass){
			$(this).hide();
			if(removeClass){
				$(this).removeClass('mouse-mask-show');
				$('.mouse-move').show();
			}
		});
	})();

	(function(){
        return;
		var dewidth = 94, scale = 1.1, time = 300;
		if($.browser.msie){
			$('#menu-logo').delegate('img', 'mouseenter', function(){
				$(this).animate({
					width : dewidth * 1.1 + 'px'
				}, time);
			}).delegate('img', 'mouseleave', function(){
				$(this).animate({
					width : dewidth + 'px'
				}, time);
			});
		}	
	})();


	
	
	var widthchangetime = 100;
	var mainwin = $('#mainwin_container').on('init', function(){
		if(init){
			clearTimeout(init);
		}
		var	mw = $('#hg_menu').outerWidth();
		$(this).data('open-width', mw).data('suo-width', suo);
		/*$(this).trigger('layout').css({
			left : mw + 'px'
		});*/
	}).on('layout', function(){
		/*var w = $(window),
			ww = w.width(),
			wh = w.height();
		$(this).css({
			width : ww - (cachestateval == 2 ? $(this).data('open-width') : $(this).data('suo-width')) + 'px',
			height : wh + 'px'
		});*/
	}).on('open', function(event, open){
        $('#menu-logo').trigger('zhan');
        $(this).stop().animate({"margin-left": $(this).data('open-width') + 'px'}, time.suo);
        /*
		$(this).stop().animate({
			left : $(this).data('open-width') + 'px'
		}, time.suo, (open ? function(){
		    $(this).animate({
		        width : $(window).width() - $(this).data('open-width') + 'px'
		    }, widthchangetime, function(){
                resetWidth();
            });
		} : ''));*/
	}).on('suo', function(event, custom, suo){
	    if(custom){
            $('#menu-logo').trigger('suo');
	        /*$(this).css('left', $(this).data('suo-width') + 'px');*/
            $(this).css({"margin-left": $(this).data('suo-width') + 'px'});
	        return;
	    }
        $('#menu-logo').trigger('suo');
        $(this).stop().animate({"margin-left": $(this).data('suo-width') + 'px'}, time.suo);
		/*$(this).stop().animate({
			left : $(this).data('suo-width') + 'px'
		}, time.suo, function(){
            if(suo){
                $(this).animate({
                    width : $(window).width() - $(this).data('suo-width') + 'px'
                }, widthchangetime, function(){
                    resetWidth();
                });
            }
		});*/
	}).trigger('init');

    function resetWidth(){
        var content = $('#mainwin').contents();
        var wrap = content.find('.wrap');
        var node = content.find('#livnodewin');
        var menu = content.find('.leftmenu');
        /*node.width(wrap.width() - menu.width());*/
    }

    ;(function(){
        return;
        var small = 'menu-small-logo';
        var normal = 'menu-logo';
        var timer = null;
        var menuLogo = $('#' + normal).on({
            zhan : function(){
                //if(!$(this).hasClass(small)) return;
                timer && clearTimeout(timer);
                var me = $(this);
                var logoImg = $(this).find('img').css('opacity', 0);
                timer = setTimeout(function(){
                    me.removeClass(small);
                    logoImg.attr('src', logoImg.attr('src').replace(small, normal)).css('opacity', 1);
                }, 300);
            },

            suo : function(){
                //if($(this).hasClass(small)) return;
                timer && clearTimeout(timer);
                var logoImg = $(this).addClass(small).find('img').css('opacity', 0);
                timer = setTimeout(function(){
                    logoImg.attr('src', logoImg.attr('src').replace(normal, small)).css('opacity', 1);
                }, 300);
            }
        });
        var logoImg = menuLogo.find('img');
        gPixelRatio > 1 && logoImg.attr('src', logoImg.attr('src').replace(normal, normal + '-2x'));
    })();


	
	var init;
	$(window).one('load', function(){
	    if(cachestateval == 1){
            init = setTimeout(function(){
                if(!hoverin){
                    mainwin.trigger('suo');
                }
                init = null;
            }, 2000);
		}
	});
	
	
	/*
	(function(){
		var timer;
		$(window).on('resize', function(){
			if(timer){
				clearTimeout(timer);
				timer = null;
			}
			timer = setTimeout(function(){
				mainwin.trigger('layout');
                //$(window).triggerHandler('resize');
			}, 30);
		})
	})(); 
	*/

	var mstate = $('#menu-state').on('click', function(event, ctypeVal){
        var cname = $(this).data('class-type');
        var ctype;
        if(ctypeVal){
            ctype = ctypeVal;
        }else{
	        ctype = ($(this).data('current-type') || 1);
            if(++ctype > 3) ctype = 1;
        }
        $(this).data('current-type', ctype).data('has-state', ctype > 1 ? true : false);
	    switch(ctype){
	        case 1:
	            break;
	        case 2:
	            mainwin.trigger('open', [true]);
	            break;
	        case 3:
	            mainwin.trigger('suo', [false, true]);
	            break;
	    }
	    cachestate.setcache(ctype);
        $(this).removeClass().addClass(cname[ctype]);
	}).data('class-type', {
	    1 : 'state-normal',
	    2 : 'state-open',
	    3 : 'state-suo'
	});

    /*mstate.trigger('click', [1]).hide();
    mainwin.trigger('suo', [true]);*/
	(function(){
        //return;
	    mstate.data('current-type', cachestateval).data('has-state', cachestateval > 1 ? true : false).removeClass().addClass(mstate.data('class-type')[cachestateval]);
	    if(cachestateval == 3){
            mainwin.trigger('suo', [true]);
        }
	})();

	
});