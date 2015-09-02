(function($){
    var defaultOptions = {
        width : 160,
        height : 160,
        src : '',
        loading : false,
        yizhi : false,
        callback : $.noop()
    };
    $.fn.preLoadImg = function(option){
        option = $.extend({}, defaultOptions, option);
        return this.each(function(){
            var src = option['src'];
            var width = option['width'];
            var height = option['height'];
            var callback = option['callback'];
            var loading = option['loading'];
            var yizhi = option['yizhi'];
            if(loading){
                var loadingObj = $('<img class="loading" src="'+ RESOURCE_URL + 'loading2.gif" style="width:30px;position:absolute;left:50%;top:50%;margin:-15px 0 0 -15px;"/>').appendTo($(this).parent());
            }
            var me = $(this);
            var img = new Image();
            img.onload = function(){
                var pw = this.width / width;
                var ph = this.height / height;
                var has = false;
                me.removeAttr('width height');
                if(pw >= 1 && pw >= ph){
                    me.attr('width', width);
                    has = true;
                }
                if(!has){
                    if(ph >= 1 && ph >= pw){
                        me.attr('height', height);
                    }
                }
                me.attr('src', src);
                loading && !yizhi && loadingObj.remove();
                callback.apply(me);
            };
            img.src = src;
        });
    };

    $.preLoadImg = function(option){
        option = $.extend({}, defaultOptions, option);
        var src = option['src'];
        var width = option['width'];
        var height = option['height'];
        var callback = option['callback'];
        var img = new Image();
        img.onload = function(){
            var pw = this.width / width;
            var ph = this.height / height;
            var type = '', val = 0;
            var has = false;
            if(pw >= 1 && pw >= ph){
                type = 'width';
                val = width;
                has = true;
            }
            if(!has){
                if(ph >= 1 && ph >= pw){
                    type = 'height';
                    val = height;
                }
            }
            callback && callback({type : type, val : val});
        };
        img.src = src;
    };
})(jQuery);