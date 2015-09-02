(function($){
    $.widget('my.rhms', {
        options : {
            h : 0,
            m : 0,
            s : 0,
            width : 51,
            am : false
        },

        _create : function(){
            var root = this.element.addClass('date-hms-box');
            this._createHMS();
            this.h = root.find('.date-h');
            this.m = root.find('.date-m');
            this.s = root.find('.date-s');
            this._getDeg();
            this._refresh();
        },

        _init : function(){
        },

        _createHMS : function(){
            var hms = '';
            $.each(['h', 'm', 's'], function(i, n){
                hms += '<div class="date-hms date-'+ n +'"></div>';
            });
            hms += '<div class="date-center"></div>';
            this.element.html(hms);
        },

        _getDeg : function(){
            this._getSDeg();
            this._getMDeg();
            this._getHDeg();
        },

        _getSDeg : function(){
            this.sDeg = 360 * this.options.s / 60;
        },

        _getMDeg : function(){
            this.mDeg = 360 * this.options.m / 60;
        },

        _getHDeg : function(){
            this.hDeg = (this.options.h + this.options.m / 60) * (360 / 12);
        },

        _refresh : function(){
            this._getDeg();
            var _this = this;
            $.each(['h', 'm', 's'], function(i, n){
                _this._css(n, _this[n + 'Deg']);
            });
        },

        refresh : function(options){
            $.extend(this.options, options);
            this._refresh();
        },

        _css : function(which, deg){
            this[which].css('transform', 'rotate('+ deg +'deg)');
        },

        _destroy : function(){

        }
    });
})(jQuery);