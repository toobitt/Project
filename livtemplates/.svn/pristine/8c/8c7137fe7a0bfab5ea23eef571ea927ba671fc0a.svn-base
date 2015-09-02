(function($){
    $.widget('ui.hms', $.ui.mouse, {
        options : {
            h : 0,
            m : 0,
            s : 0,
            am : false
        },

        _create : function(){
            var root = this.element;
            this.h = root.find('.date-h');
            this.m = root.find('.date-m');
            this.s = root.find('.date-s');
            this._getDeg();
            this._refresh();
            this._mouseInit();
        },

        _init : function(){
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

        _css : function(which, deg){
            this[which].css('transform', 'rotate('+ deg +'deg)');
        },

        _mouseCapture : function( event ) {
            var target = $(event.target);
            if(!target.is('.date-hms')){
                return false;
            }
            this.which = target.attr('which');
            var offset = this.element.offset();
            var size = {
                width: this.element.outerWidth(),
                height: this.element.outerHeight()
            };
            this.element.centerPointer = {
                x : offset.left + size.width / 2,
                y : offset.top + size.height / 2
            };
            return true;
        },

        _mouseStart : function() {
            return true;
        },

        _mouseDrag : function( event ) {
            var position = {
                x: event.pageX,
                y: event.pageY
            };
            var deg = this._calDeg(position, this.element.centerPointer);
            switch(this.which){
                case 'h':
                    this.options.h = deg / 360 * 12;
                    this.options.m = (this.options.h - Math.floor(this.options.h)) * 60;
                    break;
                case 'm':
                    this.options.m = deg / 360 * 60;
                    if(this.options.m == 0 && this['lastm'] != undefined){
                        console.log(this.options.m, this['lastm']);
                        if(this['lastm'] < 90){
                            this.options.h--;
                            if(this.options.h < 0){
                                this.options.h = 11;
                            }
                        }else if(this['lastm'] > 270){
                            this.options.h++;
                            if(this.options.h >= 12){
                                this.options.h = 0;
                            }
                        }
                    }
                    break;
                case 's':
                    this.options.s = deg / 360 * 60;
                    break;
            }
            this['last' + this.which] = deg;
            this._refresh();
            return false;
        },

        _mouseStop : function( event ) {

            return false;
        },

        _calDeg : function(positionPointer, centerPointer){
            var disX = positionPointer.x - centerPointer.x;
            var disY = centerPointer.y - positionPointer.y;
            var deg = Math.atan2(disY, disX) * 180 / Math.PI;

            if(deg >= 0 && deg <= 90){
                deg = 90 - deg;
            }else if(deg > 90 && deg <= 180){
                deg = 90 - deg + 360;
            }else if(deg < 0 && deg > -180){
                deg = -deg + 90;
            }

            return deg;
        },

        _destroy : function(){

        }
    });
})(jQuery);