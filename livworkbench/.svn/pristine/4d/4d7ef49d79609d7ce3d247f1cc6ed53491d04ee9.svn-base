(function($){
    $.widget('m2o.hotarea', $.ui.mouse, {
        option : {

        },

        _create : function(){
            this._initCss();
            this._initMaskBox();
            this.dragged = false;
            this._mouseInit();
        },

        _initCss : function(){
            var widget = this.element.addClass('m2o-hotarea');
            if($.inArray(widget.css('position'), ['absolute', 'relative', 'fixed']) == -1){
                widget.css('position', 'relative');
            }
            this._addCss();
        },

        _addCss : function(){
            if(!$('style#m2o-hotarea-css').length){
                var css = '' +
                    '.m2o-hotarea .hot-item{position:absolute;border:1px solid #fff;background:#fff;opacity:.3;}' +
                    '.m2o-hotarea .hot-item.on{border-color:blue;}' +
                    '.m2o-hotarea .m2o-border-box{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}' +
                    '';
                $('<style id="m2o-hotarea-css" type="text/css">' + css + '</style>').appendTo('head');
            }
        },

        _initMaskBox : function(){
            this.hotBox = $('<div/>').attr({
                'class' : 'hot-box',
                'style' : 'position:absolute;left:0;top:0;z-index:1;'
            }).appendTo(this.element);
        },

        _init : function(){
            var _this = this;
            this._on({
                'click .hot-item' : '_itemClick',
                'dblclick .hot-item' : '_itemDelete'
            });
            $(document).on({
                keydown : function(event){
                    var code = event.keyCode;
                    if(code == 27){
                        _this._itemDelete();
                        return false;
                    }
                }
            });
        },

        _itemClick : function(event){
            var $t = $(event.currentTarget);
            $t[!$t.hasClass('on') ? 'addClass' : 'removeClass']('on');
        },

        _itemDelete : function(event){
            var $t = !event ? this._getOnItem().eq(0) : $(event.currentTarget);
            var hash = $t.attr('hash');
            this._trigger('delete', [hash]);
            $t.remove();
        },

        _getOnItem : function(){
            return this.element.find('.hot-item.on');
        },

        _mouseStart : function(event){
            this.rootPS = this.element.offset();
            this.startPS = [event.pageX, event.pageY];
            this.startLT = [event.pageX - this.rootPS.left, event.pageY - this.rootPS.top];
            this.currentHot = this._createHot({
                left : this.startLT[0],
                top : this.startLT[1],
                width : 0,
                height : 0
            }).addClass('on');
            this._trigger('start', [this.currentHot]);
        },

        _mouseDrag : function(event){
            this.dragged = true;
            var x1 = this.startPS[0];
            var y1 = this.startPS[1];
            var x2 = event.pageX;
            var y2 = event.pageY;
            var tmp;
            var left = this.startLT[0];
            var top = this.startLT[1];
            if(x1 > x2){left = x2 - this.rootPS.left; tmp = x1; x1 = x2; x2 = tmp;}
            if(y1 > y2){top = y2 - this.rootPS.top; tmp = y1; y1 = y2; y2 = tmp;}
            this._refreshHot(this.currentHot, {
                left : left + 'px',
                top : top + 'px',
                width : x2 - x1 + 'px',
                height : y2 - y1 + 'px'
            });
            this._trigger('drag', [this.currentHot]);
            return false;
        },

        _mouseStop : function(event){
            this.dragged = false;
            this._trigger('stop', [this.currentHot]);
            return false;
        },

        _createHot : function(info){
            this.lastHash = this._hash();
            var hot = $('<div/>').appendTo(this.hotBox).attr({
                'class' : 'hot-item m2o-border-box',
                'hash' : this.lastHash
            });
            hot.draggable({
                containment : this.element
            });
            hot.resizable({
                autoHide : true
            });
            this._refreshHot(hot, {
                left : info['left'] + 'px',
                top : info['top'] + 'px',
                width : info['width'] + 'px',
                height : info['height'] + 'px'
            });
            return hot;
        },

        _refreshHot : function(hot, info){
            hot.css(info);
        },

        _hash : function(){
            return +new Date() + '' + Math.ceil(Math.random() * 1000000);
        },

        _destroy : function(){

        }
    });
})(jQuery);