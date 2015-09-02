(function($){
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
                        position : 'absolute'
                    }).appendTo('body');
                    $(this.loading).appendTo(this.mask).css({
                        position : 'absolute',
                        left : '50%',
                        top : '50%',
                        margin : '-15px 0 0 -15px'
                    });
                }
                this.mask.css({
                    width : cssInfo['width'] + 'px',
                    height : cssInfo['height'] + 'px',
                    left : cssInfo['left'] + 'px',
                    top : cssInfo['top'] + 'px',
                    'z-index' : cssInfo['z-index'] > 100000 ? cssInfo['z-index'] : 100000,
                    'display' : 'inline-block'
                });
            },
            start : function(){
                if(!$.globalAjaxLoadImg || !this.dom) return;
                this.status = 1;
                var dom = $(this.dom);
                var cssInfo = {};
                $.extend(cssInfo, dom.offset());
                cssInfo['width'] = dom.outerWidth();
                cssInfo['height'] = dom.outerHeight();
                cssInfo['z-index'] = parseInt(dom.css('z-index'));
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
            }
        };
    })();

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