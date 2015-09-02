/*(function($){
    if($.fn.textareaAuto){
        return;
    }
    var height = function(){
        var hidden = $('#hidden-oninput-page'),
            val = $(this).val();
        var ptop = parseInt($(this).css('padding-top'), 10);
        var pbottom = parseInt($(this).css('padding-bottom'), 10);
        var pleft = parseInt($(this).css('padding-left'), 10);
        var pright = parseInt($(this).css('padding-right'), 10);
        if(!hidden[0]){
            hidden = $('<textarea id="hidden-oninput-page"></textarea>').appendTo('body');
        }
        hidden.css({
            position : 'absolute',
            left : '-1000px',
            left : '0',
            top : 0,
            'padding-top' : ptop + 'px',
            'padding-left' : pleft + 'px',
            'padding-right' : pright + 'px',
            'padding-bottom' : pbottom + 'px',
            width : $(this).width() + 'px',
            height : $(this).height() + 'px',
            'font-size' : $(this).css('font-size'),
            'line-height' : $(this).css('line-height')
        });
        hidden[0].scrollWidth = $(this).width();
        hidden.val(val);
        var newHeight = $.browser.mozilla ? hidden[0].scrollHeight : (hidden[0].scrollHeight - ptop - pbottom);
        $(this).css({
            height : newHeight + 'px'
        });
    };
    $.fn.textareaAuto = function(){
        return this.each(function(){
            $(this).on('focus propertychange blur', height);
            this.addEventListener('input', height, false);
        });
    };
})(jQuery);*/

/*
 * jQuery autoResize (textarea auto-resizer)
 * @copyright James Padolsey http://james.padolsey.com
 * @version 1.04
 */

(function($){

    $.fn.autoResize = function(options) {

        // Just some abstracted details,
        // to make plugin users happy:
        var settings = $.extend({
            onResize : function(){},
            animate : true,
            animateDuration : 150,
            animateCallback : function(){},
            extraSpace : 20,
            limit: 1000
        }, options);

        // Only textarea's auto-resize:
        this.filter('textarea').each(function(){

            // Get rid of scrollbars and disable WebKit resizing:
            var textarea = $(this).css({resize:'none','overflow-y':'hidden'}),

            // Cache original height, for use later:
                origHeight = textarea.height(),

            // Need clone of textarea, hidden off screen:
                clone = (function(){

                    // Properties which may effect space taken up by chracters:
                    var props = ['height','width','lineHeight','textDecoration','letterSpacing'],
                        propOb = {};

                    // Create object of styles to apply:
                    $.each(props, function(i, prop){
                        propOb[prop] = textarea.css(prop);
                    });

                    // Clone the actual textarea removing unique properties
                    // and insert before original textarea:
                    return textarea.clone().removeAttr('id').removeAttr('name').css({
                        position: 'absolute',
                        top: 0,
                        left: -9999
                    }).css(propOb).attr('tabIndex','-1').insertBefore(textarea);

                })(),
                lastScrollTop = null,

                updateSize = function() {

                    // Prepare the clone:
                    clone.height(0).val($(this).val()).scrollTop(10000);

                    neededHeight = clone.scrollTop();
                    resizeHeight = neededHeight + settings.extraSpace;
                    // if wanting to resize to a smaller height than normal, do nothing
                    // (prevents adding extra space even when not )
                    if (resizeHeight < origHeight) {
                        if (lastScrollTop > origHeight) {
                            // but when resizing from bigger to smaller, do it
                        } else {
                            return;
                        }
                    }
                    // Find the height of text:
                    var scrollTop = Math.max(neededHeight, origHeight) + settings.extraSpace,
                        toChange = $(this).add(clone);

                    // Don't do anything if scrollTop hasen't changed:
                    if (lastScrollTop === scrollTop) { return; }
                    lastScrollTop = scrollTop;

                    // Check for limit:
                    if ( scrollTop >= settings.limit ) {
                        // Show scrollbar
                        $(this).css('overflow-y','');
                        return;
                    }
                    // Fire off callback:
                    settings.onResize.call(this);

                    // Either animate or directly apply height:
                    settings.animate && textarea.css('display') === 'block' ?
                        toChange.stop().animate({height:scrollTop}, settings.animateDuration, settings.animateCallback)
                        : toChange.height(scrollTop);
                };

            // Bind namespaced handlers to appropriate events:
            textarea
                .unbind('.dynSiz')
                .bind('keyup.dynSiz', updateSize)
                .bind('keydown.dynSiz', updateSize)
                .bind('change.dynSiz', updateSize);

        });

        // Chain:
        return this;

    };



})(jQuery);