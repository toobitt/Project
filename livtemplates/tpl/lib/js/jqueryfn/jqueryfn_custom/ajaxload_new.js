(function($){
    //window.RESOURCE_URL = '../res/magic/image/';

    $.globalAjaxLoadImg = true;
    $.globalAjaxLoad = (function(){
        function AjaxLoad(dom){
            this.mask = null;
            this.dom = dom;
            this.status = 0;
        }

        $.extend(AjaxLoad.prototype, {
            loading : '<img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>',
            create : function(cssInfo){
                if(!this.mask){
                    this.mask = $('<div/>').css({
                        position : 'absolute',
                        background : 'rgba(0, 0, 0, .1)'
                    }).appendTo('body');
                    $(this.loading).appendTo(this.mask).css({
                        position : 'absolute',
                        left : '50%',
                        top : '50%',
                        margin : '-15px 0 0 -15px'
                    });
                }
                var css = {
                    width : cssInfo['width'] + 'px',
                    height : cssInfo['height'] + 'px',
                    left : (cssInfo['left'] || 0) + 'px',
                    top : (cssInfo['top'] || 0) + 'px',
                    'z-index' : cssInfo['z-index'] > 100000 ? cssInfo['z-index'] : 100000,
                    'display' : 'inline-block',
                    position : cssInfo['is-window'] || cssInfo['is-fixed'] ? 'fixed' : 'absolute'
                };
                this.mask.css(css);
            },
            start : function(){
                if(!$.globalAjaxLoadImg || !this.dom) return;
                this.status = 1;
                var isWindow = $.isWindow(this.dom);
                var dom = $(this.dom);
                if(dom.is(':hidden')) return;
                var cssInfo = {};
                $.extend(cssInfo, dom.offset());
                cssInfo['width'] = dom.outerWidth();
                cssInfo['height'] = dom.outerHeight();
                cssInfo['z-index'] = isWindow ? 100000 : parseInt(dom.css('z-index'));
                cssInfo['is-window'] = isWindow;
                cssInfo['is-fixed'] = false;
                dom.parents().each(function(){
                    if($(this).css('position') == 'fixed'){
                        cssInfo['is-fixed'] = true;
                        cssInfo['z-index'] = parseInt($(this).css('z-index')) + 1;
                        return false;
                    }
                });
                if(cssInfo['is-fixed']){
                    var doc = $(document);
                    cssInfo['top'] -= doc.scrollTop();
                    cssInfo['left'] -= doc.scrollLeft();
                }
                this.create(cssInfo);
            },
            stop : function(){
                if(!this.dom) return;
                this.destroy();
            },
            destroy : function(){
                this.dom = null;
                this.mask && this.mask.remove();
                this.mask = null;
            }
        });

        return {
            cache : {},
            guid : 0,
            start : function(){
                $.each(this.cache, function(i, n){
                    n.status == 0 && n.start();
                });
            },
            stop : function(guid){
                this.cache[guid] && this.cache[guid].stop();
            },
            bind : function(dom){
                var guid = ++this.guid;
                this.cache[guid] = new AjaxLoad(dom);
                return guid;
            },
            unbind : function(guid){
                this.cache[guid] && this.cache[guid].destroy();
                delete this.cache[guid];
            },
            loadImg : function(){
                return AjaxLoad.prototype.loading;
            }
        };
    })();

    $.globalLoad = function(dom){
        var gd = $.globalAjaxLoad;
        var guid = gd.bind(dom);
        gd.start();
        return function(){
            gd.stop(guid);
        };
    };

    $.globalAjax = function(dom, ajax, callback){
        var guid = $.globalAjaxLoad.bind(dom);
        var xhr = ajax();
        xhr.guid = guid;
        callback && $.type(callback) == 'function' && $.when(xhr).then(callback);
        return xhr;
    };

    $(function(){
        $(document).on({
            ajaxStart : function(event){
                $.globalAjaxLoad.start();
            },

            ajaxComplete : function(event, xhr, settings){
                xhr.guid && $.globalAjaxLoad.stop(xhr.guid);
            },

            ajaxError : function(event, xhr, settings){
                xhr.guid && $.globalAjaxLoad.stop(xhr.guid);
            }
        });
    });

})(jQuery);