(function($){
    $.widget('data.mask', $.magic.mask, {
        options : {
            hoverClass : 'mask-select'
        },

        _click : function(event){
            var target = $(event.currentTarget);
            var info = this.getCellInfo(target.attr('hash'));
            if(!(info['cell_mode'] > 0)){
                return;
            }
            var cc = this.options.selectClass;
            if(!target.hasClass(cc)){
                target.addClass(cc).siblings('.' + cc).removeClass(cc);
                $.MC.sourceBox.source('show', info || []);
            }else{
                target.removeClass(cc);
                $.MC.sourceBox.source('hide');
            }
        },

        _refreshCell : function(info, isPreview){
            var _this = this;
            this.element.find('.' + this.options.hoverClass).each(function(){
                var hash = $(this).attr('hash');
                info = info || _this.getCellInfo(hash);
                _this._update($(this), hash, info, true, isPreview);
            });
        },

        update : function(info){
            this._refreshCell(info, false);
        },

        preview : function(info){
            this._refreshCell(info, true);
        },

        cancelPreview : function(){
            this._refreshCell(undefined, true);
        },

        removeClick : function(){
            var cc = this.options.hoverClass;
            this.element.find('.' + cc).removeClass(cc);
        },


        _destroy : function(){

        }
    });
})(jQuery);