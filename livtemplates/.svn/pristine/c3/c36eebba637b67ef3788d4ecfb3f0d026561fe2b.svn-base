(function($){
    $.widget('plan.mydate', {
        options : {
            date : 0,
            timeBox : null
        },

        _create : function(){
            if(this.options.date){
                this._initTime();
            }
        },

        _init : function(){
            this.element.prop('readonly', true);
            this._on(this.element, {
                focus : '_focus',
                blur : '_blur'
            });
        },

        _initTime : function(){
            if(this.inited){
                return;
            }
            this.inited = true;
            var date = this.options.date || new Date();
            var _this = this;
            $.each({'y' : 'getFullYear', 't' : 'getMonth', 'd' : 'getDay', 'h' : 'getHours', 'm' : 'getMinutes', 's' : 'getSeconds'}, function(i, n){
                _this[i] = date[n]();
            });
        },

        _focus : function(){
            this._initTime();
            var time = new Date(this.y, this.t, this.d, this.h, this.m, this.s).getTime();
            this.options.timeBox.mytime('info', $(this.element), time).mytime('open');
        },

        _blur : function(){

        },

        _click : function(event){
            event.stopImmediatePropagation();
        },

        _refresh : function(){
            this.element.val(this.h + ':' + this.m + ':' + this.s);
        },

        val : function(hms){
            this.h = hms.h;
            this.m = hms.m;
            this.s = hms.s;
            this._refresh();
        },

        _destroy : function(){
            this.options.timeBox = null;
        }
    });
})(jQuery);


(function($){
    $.widget('plan.mytime', {
        options : {
            time : 0,
            box : 'mytime-box',
            each : 'mytime-each',
            'each-on' : 'mytime-each-on',
            item : 'mytime-item',
            'item-disabled' : 'mytime-item-disabled',
            'item-on' : 'mytime-item-on',
            target : null,

            helper : true
        },

        _create : function(){
            this.typesArr = ['h', 'hh', 'm', 'mm', 's', 'ss'];

            var types = this.types = {
                h : 3,
                hh : 10,
                m : 6,
                mm : 10,
                s : 6,
                ss : 10
            };

            var boxClass = this.options.box;
            var eachClass = this.options.each;
            var itemClass = this.options.item;
            var html = '<div class="' + boxClass + '">';

            html += '<div class="mytime-title">' +
                '<span>H</span>' +
                '<span>H</span>' +
                '<span class="mytime-maohao1 mytime-maohao">:</span>' +
                '<span>M</span>' +
                '<span>M</span>' +
                '<span class="mytime-maohao2 mytime-maohao">:</span>' +
                '<span>S</span>' +
                '<span>S</span>' +
                '</div>';

            $.each(types, function(i, n){
                html += '<div class="mytime-' + i + '-box ' + eachClass + '" _val="' + i + '">';

                $.each(new Array(n), function(ii, nn){
                    html += '<div class="' + itemClass + '" _val="' + ii + '">' + ii + '</div>';
                });

                html += '</div>';
            });

            html += '</div>';

            $(html).appendTo(this.element);

            if(this.options.helper){
                this.element.append('<div class="mytime-help">' +
                    '<p>HH:MM:SS代表时:分:秒</p>' +
                    '<p>可以按键盘数字输入</p>' +
                    '<p>按ESC重新开头输入</p>' +
                    '<p>按空格键表示完成输入并关闭</p>' +
                    '</div>');
                this.element.append('<div class="mytime-help-btn" title="点击打开帮助">?</div>');
            }
        },

        _init : function(){
            this._on('.' + this.options.item, {
                click : '_click'
            });

            this._on('.mytime-help-btn', {
                click : '_help'
            });

            var _this = this;
            $(document).on({
                keydown : function(event){
                    if(!_this.keybord) return;
                    var code = event.keyCode;
                    if(code == 27){
                        _this._initKey();
                        event.preventDefault();
                        return false;
                    }
                    if(code == 32){
                        _this.close();
                        event.preventDefault();
                        return false;
                    }
                },

                keyup : function(event){
                    if(!_this.keybord) return;
                    var code = event.keyCode;
                    code -= 48;
                    if(code >= 0 && code <= 9){
                        _this._keySet(code);
                    }
                },

                click : function(event){
                    if(!_this.keybord) return;
                    if($(event.target).closest('.mytime-box').length || $(event.target).is(':plan-mydate') || $(event.target).is('.mytime-help-btn') || $(event.target).is('.mytime-help')){
                        return;
                    }
                    _this.close();
                }
            });

            $(window).on({
                scroll : function(){
                    if(!_this.keybord) return;
                    _this._position();
                }
            });
        },

        _initTime : function(){
            var all = this._getAll(this.options.time);
            this.h = all[0];
            this.hh = all[1];
            this.m = all[2];
            this.mm = all[3];
            this.s = all[4];
            this.ss = all[5];
        },

        _initKey : function(){
            this._setEach('h');
        },

        _keySet : function(val){
            var onIndex = $.inArray(this.keyon, this.typesArr);
            if(onIndex == -1){
                return;
            }
            this._setItem(this.keyon, val);
            this._setEach(this.keyon);
            this.keyon = this.typesArr[++onIndex];
            //this._setEach(this.keyon);
        },

        _help : function(onlyremove){
            this.element.find('.mytime-help')[onlyremove ? 'removeClass' : 'toggleClass']('mytime-help-on');
        },


        _refresh : function(){
            var _this = this;
            $.each(this.types, function(i, n){
                _this._setItem(i, _this[i]);
            });
        },

        info : function(target, time){
            this.option('time', time);
            this.option('target', target);
            this._initTime();
            this._refresh();
        },

        _click : function(event){
            var item = $(event.currentTarget);
            var onClass = this.options['item-on'];
            var disabledClass = this.options['item-disabled'];
            if(item.hasClass(disabledClass) || item.hasClass(this.options['item-on'])){
                return false;
            }
            item.addClass(onClass).siblings().removeClass(onClass);
            var itemVal = item.attr('_val');
            var each = item.closest('.' + this.options.each);
            var eachVal = each.attr('_val');
            this[eachVal] = itemVal;
            this._check(eachVal, itemVal);
            this._flush();
            this._setEach(eachVal);
        },

        _check : function(eachVal, itemVal){
            if(eachVal == 'h'){
                var items = this._getItem(this._getEach('hh'), function(){
                    return $(this).attr('_val') > 3;
                });
                items[itemVal > 1 ? 'addClass' : 'removeClass'](this.options['item-disabled']);
            }else if(eachVal == 'hh'){
                var items = this._getItem(this._getEach('h'), function(){
                    return $(this).attr('_val') > 1;
                });
                items[itemVal > 3 ? 'addClass' : 'removeClass'](this.options['item-disabled']);
            }
        },

        _getEach : function(eachVal){
            return this.element.find('.mytime-' + eachVal + '-box');
        },

        _setEach : function(eachVal){
            var eachClass = this.options['each-on'];
            this.keyon = eachVal;
            this._getEach(eachVal).addClass(eachClass).siblings().removeClass(eachClass);
        },

        _getItem : function(each, condition){
            var items = each.find('.' + this.options['item']);
            condition && (items = items.filter(condition));
            return items;
        },

        _setItem : function(eachVal, itemVal){
            this._getItem(this._getEach(eachVal), function(){
                return $(this).attr('_val') == itemVal;
            }).trigger('click');
        },

        _flush : function(){
            this.options.target.mydate('val', {
                h : '' + this.h + this.hh,
                m : '' + this.m + this.mm,
                s : '' + this.s + this.ss
            });
        },

        _getAll : function(time){
            var date = new Date();
            date.setTime(time);
            var hh = date.getHours();
            hh < 10 && (hh = '0' + hh);


            var mm = date.getMinutes();
            mm < 10 && (mm = '0' + mm);


            var ss = date.getSeconds();
            ss < 10 && (ss = '0' + ss);

            return ('' + hh + mm + ss).split('');
        },

        _position : function(animate){
            var target = this.options.target;
            var offset = target.offset();
            var outerHeight = target.outerHeight();
            var css = {
                left : offset.left + 'px',
                top : offset.top + outerHeight + 'px'
            };
            this.element.show()[animate ? 'animate' : 'css'](css, 100);
        },


        open : function(){
            this._position();
            this._initKey();
            this.keybord = true;
        },

        close : function(){
            this.element.hide();
            this._flush();
            this.options.target.blur();
            this._help(true);
            this.keybord = false;
        },

        _destroy : function(){

        }
    });

    $.timeBox = function(){
        var box = $('#mytime');
        if(!box[0]){
            box = $('<div id="mytime" style="display:none;position:absolute;left:0;top:0;z-index:10000;"></div>').appendTo('body').mytime();
        }
        return box;
    }
})(jQuery);