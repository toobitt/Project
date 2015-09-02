(function($){
    $.widget('plan.select', {
        options : {
            tab : '.s-tab',
            'tab-item' : '.s-tab-item',
            content : '.s-content',
            'content-item' : '.s-content-item',

            'file-tpl' : '#file-tpl'
        },

        _create : function(){
            this.tab = this.element.find(this.options['tab']);
            this.content = this.element.find(this.options['content']);
        },

        _init : function(){
            this._on(this.options['tab-item'], {
                click : '_tabChange'
            });

            this._on(this.options['content-item'], {
                content : '_content'
            });

            this.tab.find(this.options['tab-item']).eq(1).click();
        },

        _tabChange : function(event){
            var target = $(event.target);
            if(target.hasClass('on')){
                return;
            }
            target.addClass('on').siblings().removeClass('on');
            var current = this.content.find(this.options['content-item'] + '[ami="'+ target.attr('target') +'"]').show();
            current.trigger('content');
            current.siblings().hide();
        },

        _content : function(event){
            var target = $(event.target);
            if(target.data('init')){
                return;
            }
            this['_' + target.attr('ami')](target);
            target.data('init', true);
        },

        _xinhao : function(self){

        },

        _file : function(self){
            self.html($(this.options['file-tpl']).tmpl()).file();
            this.file = self;
        },

        _shiyi : function(self){

        },

        dragEnable : function(){
            this.file && this.file.file('dragEnable');
        },

        dragDisable : function(){
            this.file && this.file.file('dragDisable');
        },

        _destroy : function(){

        }
    });
})(jQuery);