(function($){
    $.widget('block.mask', $.magic.mask, {
        options : {

        },

        _create : function(){
            this.element.hide();
        },

        _click : function(event){
            var target = $(event.currentTarget);
            var info = this.getCellInfo(target.attr('hash'));
            if(!(info['cell_mode'] > 0)){
                return;
            }
            var cc = this.options.hoverClass;
            if(!target.hasClass(cc)){
                target.addClass(cc).siblings('.' + cc).removeClass(cc);
                $.MC.sourceBox.source('show', info);
            }else{
                $.MC.sourceBox.source('hide');
                target.removeClass(cc);
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

        refresh : function(infos){
            this._superApply(arguments);
            this.element.show();
        },

        currentMask : function(){
            return this.element.find('.' + this.options.hoverClass);
        },


        _destroy : function(){

        }
    });
})(jQuery);