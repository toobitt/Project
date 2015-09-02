(function($){
    if(top != self){
        return;
    }
	var defaultOption = {
		src : ''
	};
    var defaultZIndex = 1;
    var defaultId = 'formwin';

    var uint = function(){
        var menuClick  = false;
        return {
            menuAddClick : function(){
                if(menuClick) return;
                menuClick = true;
                $(document).on('mousedown', '#hg_menu a[href]', function(){
                    $('#formwin').parent().trigger('iclose');
                });
            }
        }
    }();

    $(window).on({
        'resize.option-iframe' : function(){
            var iframe = $('#'+ defaultId);
            if(!iframe || !iframe[0]){
                return;
            }
            var iframeParent = iframe.parent();
            var win = $(window),
                width = win.width() - iframeParent.offset().left,
                height = win.height();
            iframe.css({
                height : height + 'px',
                width : width + 'px'
            });
        },
        'destory.option-iframe' : function(event){

        }
    });

	var methods = {
	    init : function(){
	        return this.each(function(){
                var me = $(this);
                me.on({
                    'iopen.option-iframe' : function(event, options){
                        options = $.extend({}, defaultOption, options);
                        var src = options['src'];
                        var gMid = options['gMid'];
                        var height = $(window).height();
                        var iframe = $('#'+ defaultId);
                        if(!iframe[0]){
                            iframe = $('<iframe id="'+ defaultId +'" />').css({
                                position : 'absolute',
                                left : 0,
                                top : 0,
                                width : '100%',
                                display : 'none'
                            }).appendTo(this).on({
                                _position : function(event, info){
                                    $(this)
                                        .attr('_index', info['index'])
                                        .css({
                                            'z-index' : info['index'],
                                            height : info['height'] + 'px'
                                        });
                                },

                                _src : function(event, src){
                                    $(this).attr('_src', src);
                                },

                                _show : function(event){
                                    $(this)

                                        .show()
                                        .css({
                                            'z-index' : $(this).attr('_index'),
                                            opacity : 1
                                        })/*
                                        .animate({
                                            opacity : 1
                                        }, 300)*/;
                                },

                                _hide : function(){
                                    $(this)
                                        .stop()
                                        .animate({
                                            opacity : 0
                                        }, 300, function(){
                                            $(this).css({
                                                'z-index' : -1
                                            });
                                            $(this).attr('src','');
                                        });
                                }
                            });
                            iframe.iframeAnimate({
                                delegate : false,
                                history : true,
                                auto : false
                            });
                        }
                        $(this).data('gMid', gMid);
                        iframe.triggerHandler('_src', [src]);
                        iframe.triggerHandler('_position', [{
                            index : defaultZIndex++,
                            height : height
                        }]);
                        iframe.iframeAnimate('go', $.proxy(function(){
                            $(this).triggerHandler('_show');
                        }, iframe));
                        uint.menuAddClick();
                    },
                    'iclose.option-iframe' : function(event, refresh, html, id){
                        var iframe = $('#' + defaultId),
                            mainWin = $('#mainwin'),
                            nodeWin;
                        if (iframe && iframe[0]) {
                            iframe.triggerHandler('_hide');
                            if (refresh) {
                                nodeWin = mainWin[0].contentWindow.$('#nodeFrame')[0].contentWindow;
                                if ( id ) {
                                    /*nodeWin中的页面内容，对应于编辑页才会更新nodeWin*/
                                    if ( nodeWin.replaceLi ) {
                                        nodeWin.replaceLi( id, html );
                                    }
                                } else {
                                    nodeWin.location.href = nodeWin.location.href.replace(/&pp=\d*/, '') + '&pp=0';
                                }
                            }
                        }
                    },
                    'destory.option-iframe' : function(){
                        $(this).off('.option-iframe');
                    }
                });
	        });
	    },
	    destory : function(){
            return this.each(function(){

            });
	    }
	};
	/*编辑iframe调用此方法关闭自己，并且传入用于更新列表iframe的信息*/
    $.optionIframeClose = function(html, id) {
        var iframe = $('#' + defaultId);
        if(!iframe || !iframe[0]){
            return;
        }
        var iframeParent = iframe.parent();
        if ( html || !id ) {
            iframeParent.trigger('iclose', [true, html, id]);
        } else {
            $.get(
                'run.php?a=fetch_one_li',
                {id: id, mid: top.$('#livwinarea').data('gMid')},
                function (html) {
                    iframeParent.trigger('iclose', [true, html, id]);
                }
            );
        }
    }

    $.optionIframeBlankClose = function(){
        var iframe = $('#' + defaultId);
        if(!iframe || !iframe[0]){
            return;
        }
        iframe.parent().trigger('iclose', [false]);
    }

	$.fn.optionIframe = function(method){
        if(methods[method]){
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }else if(typeof method === 'object' || !method){
            return methods.init.apply(this, arguments);
        }
	}
})(jQuery);