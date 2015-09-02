(function($){
    $.template('my-tip', '<div class="my-tip-box"><div class="my-tip-inner m2o-transition">{{= tip}}</div></div>');

    $.fn.myTip = function(options){
        options = $.extend({
            string : '提示',
            cname : 'on',
            delay : 1000,
            dtop : 0,
            dleft : 0,
            width : 'auto',
            callback : $.noop
        }, options);

        $.fn.myTip.css();

        return this.each(function(){
            var tip = $.tmpl('my-tip', {tip : options['string']}).appendTo('body');
            var inner = tip.find('.my-tip-inner');
            var on = options['cname'];
            var delay = options['delay'];
            var dleft = options['dleft'];
            var dtop = options['dtop'];
            var dwidth = options['width'];
            var callback = options['callback'];
            var $this = $(this);
            var position = $this.offset();
            var width = $this.outerWidth(true);
            var height = $this.outerHeight(true);
            tip.css({
                left : position.left + width / 2 + dleft + 'px',
                top : position.top + height / 2 + dtop + 'px',
            });
            tip.find('.my-tip-inner').css({
            	width : dwidth
            });
            setTimeout(function(){
                inner.addClass(on);
            }, 1);
            setTimeout(function(){
                inner.removeClass(on);
                setTimeout(function(){
                    tip.remove();
                    callback && callback();
                }, 500);
            }, delay || 1300);
        });
    };

    $.fn.myTip.css = function(){
        if(this.cssed){
            return;
        }
        this.cssed = true;
        $('<style/>').html(
            '.my-tip-box{position:absolute;z-index:100000000;width:1px;height:1px;}' +
                '.my-tip-inner{position:absolute;left:50%;top:0px;height:30px;line-height:30px;margin-left:-50px;opacity:0;background:green;color:#fff;text-align:center; padding:0 10px; }' +
                '.my-tip-inner.on{top:-30px;opacity:1;}'
        ).appendTo('head');
    };
})(jQuery);