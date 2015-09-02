function TextEvent(number, slide){
    this.number = number;
    this.slide = slide;
    this.editor = window['oEdit' + this.number];
    this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
    this.box = null;
    this.content = null;
    this.option = null;
    this.slideManage = window['slideManage' + number];
    this.init();
}

jQuery.extend(TextEvent.prototype, {
    init : function(){
    	var slideContent = '<div id="edit-slide-text' + this.number +'" class="edit-slide-html-each">'+
        '<div class="edit-slide-title"><span class="edit-slide-close">关闭</span>文本属性</div>'+
        '<div class="edit-slide-text-content edit-slide-content"></div>'+
        '</div>';
        this.slide.html( slideContent );
        this.box = $('#edit-slide-text' + this.number);
        this.content = this.box.find('.edit-slide-content');
        this.slide.addInitFunc( this.getUpdateBoxFunc() );
    },
    getUpdateBoxFunc: function() {
		var self = this;
		return function( data ) {
			self.updateBoxWith( data );
		}
	},
    updateBoxWith: function( data ) {
    	this.content.html( '<iframe src="'+ window['globalEditorConfig']['path'] + 
    		'common/webtext.htm" frameborder="0" scrolling="no" height="100%" width="220"></iframe>' );
    }
});
