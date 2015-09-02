(function($){
	var defaultOption = {
		time : 1000,
		loadid : 'iframe-loading',
		loadimg : '',
		loadleft : 100,
		loadtop : 100,
		loadwidth : 50,
		loadheight : 50,
		auto : true,
		delegate : true,
        history : false
	};

    var methods = {
        init : function(options){
            options = $.extend({}, defaultOption, options);
            return this.each(function(){

                var load = $('#' + options['loadid']);
                if(!load[0]){
                    load = $('<img id="'+ options['loadid'] +'" src="'+ (options['loadimg'] || (RESOURCE_URL + 'loading2.gif'))  +'"/>').appendTo($(this).parent()).css({
                        position : 'absolute',
                        'z-index' : 1000,
                        left : 0,
                        top : 0
                    }).on({
                            '_show' : function(){
                                $(this).show().triggerHandler('_auto');
                                var me = $(this);
                                $(this).data('auto-timer', setTimeout(function(){
                                    me.triggerHandler('_hide');
                                }, 30000));
                            },
                            '_hide' : function(){
                                $(this).hide().triggerHandler('_auto');
                            },
                            '_auto' : function(){
                                $(this).data('auto-timer') && clearTimeout($(this).data('auto-timer'));
                            }
                        });
                    load.css({
                        position : 'absolute',
                        left : options['loadleft'] + 'px',
                        top : options['loadtop'] + 'px',
                        width : options['loadwidth'] + 'px',
                        height : options['loadheight'] + 'px'
                    });
                }

                var me = $(this);

                me.data('loading', options['loadid']);
                me.on({
                    '_go' : function(event, src, cb){
                        $('#' + $(this).data('loading')).triggerHandler('_show');
                        //此处延迟一秒加载iframe，为了给左边节点动画留点渲染时间，因为iframe的http请求太多了，导致浏览器瞬间CPU高了，页面卡，！！无语！！先这样吧，以后需要优化！！！
                        this.contentWindow.iframeChangeing = true;
                        $(this).data('init', $.proxy(function(){
                            $(this).triggerHandler('_stop');
                            cb && $.isFunction(cb) && cb();
                        }, this));
                        var _this = this;
                        setTimeout(function(){
                            $(_this).attr('src', src);
                            $(_this).triggerHandler('_start');
                        }, 100);
                    },
                    '_complete' : function(){
                        if(!$(this).data('init')){
                            return;
                        }
                        $(this).data('init')();
                        $(this).removeData('init');
                        $('#' + $(this).data('loading')).triggerHandler('_hide');
                    },

                    '_start' : function(){
                        $(this).triggerHandler('_stop');
                        var _this = this;
                        var timer = setInterval(function(){
                            if(!_this.contentWindow.iframeChangeing && _this.contentWindow.document){
                                $(_this).triggerHandler('_complete');
                            }
                        }, 20);
                        $(this).data('timer', timer);
                    },

                    '_stop' : function(){
                        var timer = $(this).data('timer');
                        timer && clearInterval($(this).data('timer'));
                        $(this).data('timer', null);
                    },

                    load : function(){
                        $(this).triggerHandler('_complete');
                    }
                });

                if(options['delegate']){
                    var name = me.attr('name');
                    $('body').on('click', 'a', function(){
                        if($(this).attr('target') == name && $(this).attr('href')){
                            me.triggerHandler('_go', [$(this).attr('href')]);
                            return false;
                        }
                    });
                }
                if(options['auto']){
                    $(this).triggerHandler('_go', [$(this).attr('_src')]);
                }
            });
        },

        go : function(cb){
            this.triggerHandler('_go', [this.attr('_src'), cb]);
        },

        destory : function(){
            return this.each(function(){

            });
        }
    };

	$.fn.iframeAnimate = function(method){
        if(methods[method]){
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }else if(typeof method === 'object' || !method){
            return methods.init.apply(this, arguments);
        }
	}

    $(function(){
        if($('#nodeFrame').get(0)){
            $('#nodeFrame').iframeAnimate();
        }
    });

    /*$.showIframe = function(){
		$('iframe').each(function(){
			if($(this).data('bind-animate')){
                $(this).on('load', function(){
                    $.clearLoad();
                });
				$(this).trigger('_ishow');
			}
		});
	}

	$.clearLoad = function(){
		$('iframe').each(function(){
			if($(this).data('bind-animate')){
				$(this).trigger('_phide');
			}
		});
	}

	$(function(){
		if($('#nodeFrame').get(0)){
			$('#nodeFrame').iframeAnimate();
		}
		if(window.self != window.parent){
			//window.parent.jQuery.showIframe();
		}
	});*/

})(jQuery);