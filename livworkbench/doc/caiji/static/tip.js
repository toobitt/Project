(function($){
    $.template('my-tip', '<div class="my-tip-box"><div class="my-tip-inner m2o-transition">{{= tip}}</div></div>');
    $.fn.myTip = function(options){
        options = $.extend({
            string : '提示',
            cname : 'on',
            delay : 1000,
            dtop : 0,
            dleft : 0,
            color : ''
        }, options);

        return this.each(function(){
            var tip = $.tmpl('my-tip', {tip : options['string']}).appendTo('body');
            var inner = tip.find('.my-tip-inner');
            var on = options['cname'];
            var delay = options['delay'];
            var dleft = options['dleft'];
            var dtop = options['dtop'];
            var color = options['color'];
            var $this = $(this);
            if(color){
                inner.css('background-color', color);
            }
            var position = $this.offset();
            var width = $this.outerWidth(true);
            var height = $this.outerHeight(true);
            tip.css({
                left : position.left + width / 2 + dleft + 'px',
                top : position.top + dtop + 'px'
            });
            setTimeout(function(){
                inner.addClass(on);
            }, 1);
            setTimeout(function(){
                inner.removeClass(on);
                setTimeout(function(){
                    tip.remove();
                }, 500);
            }, delay || 1300);
        });
    }
})(jQuery);