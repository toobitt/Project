(function($){
    function canvas(options){
        this.width = options.width || 500;
        this.height = options.height || 375;
        this.oldWidth = options.oldWidth || options.width;
        this.oldHeight = options.oldHeight || options.height;
        this.video = options['video'] || $('#video');
        this.init();
    }
    $.extend(canvas.prototype, {
        init : function(){
            this.element = $('<canvas width='+ this.width +' height="'+ this.height +'"></canvas>')[0];
        },
        getImgFromVideo : function(){
            this.element.getContext('2d').drawImage(this.video[0], (this.width - this.oldWidth) / 2, (this.height - this.oldHeight) / 2, this.width, this.height);
            return this.element.toDataURL('image/png');
        },
        destroy : function(){
            this.element = null;
            this.video = null;
        }
    });

    $.createCanvas = function(options){
        var cacheKey = options['width'] + 'x' + options['height'];
        if($.createCanvas.caches[cacheKey]){
            return $.createCanvas.caches[cacheKey];
        }
        return ($.createCanvas.caches[cacheKey] = new canvas(options));
    }
    $.createCanvas.caches = {};
})(jQuery);