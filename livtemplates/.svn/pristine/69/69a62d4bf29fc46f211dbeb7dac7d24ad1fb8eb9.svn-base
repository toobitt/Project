(function($){
    $.widget('video.page', {
        options : {
            total : 0,
            pages : 0,
            cp : 0,
            num : 20
        },

        _create : function(){
            this._createBox();
        },

        _init : function(){
            this._on({
                'click span[_page]' : '_click'
            });
        },

        _createBox : function(){
            var op = this.options;
            var cp = parseInt(op.cp);
            var tp = op.pages;
            if(tp < 2){
                this.element.hide();
                return;
            }
            var html = '';
            html+='<span class="prev-btn" _page=' + (cp-1) + '><em></em></span>'
            html+='<span class="new-current">第' + cp + '页/</span>'; 
            html += '<span class="new-page-all">共' + tp + '页</span>';
            html += '<span class="next-btn" _page=' + (cp+1) + '><em></em></span>';
            this.element.html(html);
        },

        _click : function(event){
        	var self=$(event.currentTarget)
            var page = self.attr('_page');
            var pages=this.options['pages'];
            if(page==0 || page >pages){
            	return;
            }
            this._trigger('page', null, [page]);
        },

        show : function(){
            this.element.show();
        },

        hide : function(){
            this.element.hide();
        },

        refresh : function(option){
            this.show();
            $.extend(this.options, option);
            this._createBox();
        }
    });
})(jQuery);
