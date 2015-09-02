(function($){
    $.widget('my.ohms', {
        options : {
            time : '',
            target : null
        },

        _create : function(){
            var root = this.element;

            this._initHMS();
            this._createHtml();
            var _this = this;
            $.each(['h', 'm', 's'], function(i, n){
                _this['i' + n] = root.find('.ohms-input-' + n);
                _this['s' + n] = root.find('.ohms-slider-' + n);
            });

        },

        _init : function(){
            var _this = this;
            $.each({'h' : 23, 'm' : 59, 's' : 59}, function(i, n){
                _this['s' + i].slider({
                    min : 0,
                    max : n,
                    /*orientation: 'vertical',*/
                    value : parseInt(_this['s' + i]),
                    slide : function(event, ui){
                        var which = $(this).attr('which');
                        var value = ui.value;
                        value = (value < 10 ? '0' : '') + value;
                        _this['i' + which].val(value);
                        _this[which] = value;
                    }
                });

                _this['i' + i].on({
                    blur : function(){
                        var val = parseInt($(this).val());
                        var min = parseInt($(this).attr('min'));
                        var max = parseInt($(this).attr('max'));
                        var which = $(this).attr('which');
                        if(!(min <= val && val <= max)){
                            val = 0;
                        }
                        _this['s' + which].slider('value', val);
                        val = (val < 10 ? '0' : '') + val;
                        $(this).val(val);
                        _this[which] = val;
                    },
                    keydown : function(event){
                        var code = event.keyCode;
                        if(code == 8 || code == 37 || code == 39){
                            return;
                        }
                        if(!(48 <= code && code <= 57)){
                            return false;
                        }
                    },
                    keyup : function(event){
                        var target = $(event.target);
                        var val = target.val();
                        if(val.length > 2){
                            val = val.split('');
                            val.length = 2;
                            target.val(val.join(''));
                        }
                    }
                });
            });

            this._on({
                'click .ohms-ok' : '_ok',
                'click .ohms-cancel' : '_cancel',
                'focus .ohms-input' : '_focus'
            });
        },

        _createHtml : function(){
            var html = '<div class="ohms-box">' +
                '<div class="ohms-inputs">' +
                    '<input class="ohms-input-h ohms-input" value="' + this.h + '" which="h" min="0" max="23"/>' +
                    '<span class="ohms-mao">:</span>' +
                    '<input class="ohms-input-m ohms-input" value="' + this.m + '" which="m" min="0" max="59"/>' +
                    '<span class="ohms-mao">:</span>' +
                    '<input class="ohms-input-s ohms-input" value="' + this.s + '" which="s" min="0" max="59"/>' +
                '</div>' +
                '<div class="ohms-sliders clearfix">' +
                    '<div class="ohms-slider-h ohms-slider" which="h"></div>' +
                    '<div class="ohms-slider-m ohms-slider" which="m"></div>' +
                    '<div class="ohms-slider-s ohms-slider" which="s"></div>' +
                '</div>' +
                '<div class="ohms-options common-button-group">' +
                    '<a class="ohms-ok ohms-option blue">确定</a>' +
                    '<a class="ohms-cancel ohms-option gray">取消</a>' +
                '</div>' +
                '</div>';
            this.element.html(html);
        },

        _splitTime : function(time){
            if(!time){
                return ['00', '00', '00'];
            }
            time = time.split(':');
            time = $.map(time, function(n){
                n = parseInt(n);
                if(n < 10){
                    return '0' + n;
                }
                return n;
            });
            return time;
        },

        _initHMS : function(){
            var time = this._splitTime(this.options.time);
            this.h = time[0];
            this.m = time[1];
            this.s = time[2];
        },

        _refresh : function(){
            this._initHMS();
            var _this = this;
            $.each(['h', 'm', 's'], function(i, n){
                _this['i' + n].val(_this[n]);
                _this['s' + n].slider('value', parseInt(_this[n]));
            });
            _this['ih'][0].focus();
        },

        show : function(disOffset){
            var target = this.options.target;
            var offset = target.offset();
            var targetHeight = target.outerHeight(true);
            var disLeft = disOffset && disOffset['left'] || 0;
            var disTop = disOffset && disOffset['top'] || 0;
            this.element.show().css({
                left : offset.left + disLeft + 'px',
                top : offset.top + targetHeight + disTop + 'px'
            });
            this._refresh();
        },

        hide : function(){
            this.element.hide();
            this._empty();
        },

        _focus : function(event){
            var target = $(event.target);
            if(target.hasClass('on')){
                return;
            }
            target.addClass('on').siblings().removeClass('on');
            var which = target.attr('which');
            this.sh.hide();
            this.sm.hide();
            this.ss.hide();
            this['s' + which].show();
        },

        _ok : function(){
            this.options.target.trigger('set', [{
                h : this.h,
                m : this.m,
                s : this.s
            }]);
            this.hide();
        },

        _cancel : function(){
            this.hide();
        },

        _empty : function(){
            this.options.target = null;
        },

        _destroy : function(){
            this._empty();
        }
    });
})(jQuery);