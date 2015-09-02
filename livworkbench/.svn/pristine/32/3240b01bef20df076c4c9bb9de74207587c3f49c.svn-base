function editorStatistics(number){
    this.number = number;
    this.editor = window['oEdit' + this.number];
    this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
    this.box = null;
    this.init();
}

$.extend(editorStatistics.prototype, {
    init : function(){
        this.view();
        this.control();
    },
    view : function(){
        var html = '<ul class="editor-statistics">'+
            '<li class="first"><span></span>字数</li>'+
            '<li class="editor-statistics-item" _type="image"><span></span>图片</li>'+
            '<li class="editor-statistics-item" _type="attach"><span></span>附件</li>'+
            '<li class="editor-statistics-item" _type="pageslide"><span></span>页数</li>'+
            '<li class="editor-statistics-item" _type="biaozhu"><span></span>批注</li>'+
            '</ul>';
        //this.box = $(html).insertBefore($('#oEdit'+ this.number +'grp'));
        this.box = $(html).appendTo('.editor-detail');
        var items = this.box.find('.editor-statistics-item').hover(function(){
            $(this).toggleClass('hover');
        });
        var number = this.number;
        items.click(function(){
            window['slideManage' + number].openOne($(this).attr('_type'));
        });
    },
    control : function(){
        var boxs = this.box.find('span');
        this.box.on('set', function(event, index, num){
            boxs.eq(index).text(num);
        });
        this.fontNumber();
        this.imgNumber();
        this.attachNumber();
        this.pageNumber();
        this.biaozhuNumber();
    },
    fontNumber : function(num){
        if(typeof num == 'undefined'){
            num = $.trim($(this.editorWindow.document.body).text()).length || 0;
        }
        this.box.trigger('set', [0, num]);
    },
    imgNumber : function(num){
        if(typeof num == 'undefined'){
            num = $('#edit-slide-image' + this.number).find('.image').length;
        }
        this.box.trigger('set', [1, num]);
    },
    attachNumber : function(num){
        if(typeof num == 'undefined'){
            num = $('#edit-slide-attach' + this.number).find('.attach-item').length;
        }
        this.box.trigger('set', [2, num]);
    },
    pageNumber : function(num){
        if(typeof num == 'undefined'){
            num = $(this.editorWindow.document.body).find('.pagebg').length;
            num > 0 && num++;
        }
        this.box.trigger('set', [3, num]);
    },
    biaozhuNumber : function(num){
        if(typeof num == 'undefined'){
            num = $(this.editorWindow.document.body).find('.before-biaozhu-ok').length;
        }
        this.box.trigger('set', [4, num]);
    }
});
