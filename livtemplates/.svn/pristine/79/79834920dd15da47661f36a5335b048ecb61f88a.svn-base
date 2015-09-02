(function($){
    $.picEditBtn = function(options){
        var btn = $('#pic-edit-btn');
        if($.type(options) == 'string' && options == 'close'){
            return btn;
        }
        options = $.extend({
            state : true,
            offset : null,
            clickEvent : $.noop
        }, options);
        if(!btn[0]){
            btn = $('<div id="pic-edit-btn" class="pic-edit-btn">编辑图片</div>').appendTo('body');
            btn.on({
                '_pos' : function(event, offset){
                    $(this).css({
                        left : offset.left + 'px',
                        top : offset.top + 'px'
                    });
                },
                '_show' : function(){
                    $(this).show();
                },
                '_hide' : function(){
                    $(this).hide();
                },
                'mouseenter' : function(){
                    $(this).addClass('on');
                },
                'mouseleave' : function(){
                    $(this).removeClass('on').trigger('_hide');
                }
            });
        }
        btn.off('click').on('click', options.clickEvent);
        if(options.state){
            btn.trigger('_pos', [options.offset]);
        }
        return btn;
    }
    $.fn.picEdit = function(options){
        options = $.extend({
            type : 'mouse',
            imageId : null,
            imgSrc : null,
            state : 'show',
            mouseCheck :$.noop,
            saveAfter : $.noop,
            disOffset : {left : 0, top : 0},
            positionLeft : false,	//按钮是否居左显示
        }, options);
        return this.each(function(){
            var imageId = options.imageId;
            var imgSrc = options.imgSrc;
            var disOffset = options.disOffset;
            var mouseCheck = options.mouseCheck || $.noop;
            var saveAfter = options.saveAfter;
            var state = options['state'] == 'show' ? true : false;
            var type = options['type'];
            if(type == 'mouse'){
                $(this).on({
                    'mouseenter' : function(){
                        clearTimeout($.editTimer);
                        $.editTimer = null;

                        if(mouseCheck.call(this)){
                            return;
                        }

                        imageId = imageId || $(this).attr('id');
                        imgSrc = imgSrc || $(this).attr('src');
                        var me = $(this);
                        var offset;
                        if(state){
                            offset = $(this).offset();
                            if( !options.positionLeft ){
                            	offset.left += disOffset.left + parseInt($(this).width()) - 65;
                            	offset.top += disOffset.top + 1;
                            }
                        }
                        var btn = $.picEditBtn({
                            state : state,
                            offset : offset,
                            clickEvent : function(){
                                saveAfter();
                                top.featherEditor.launch({
                                    image: imageId,
                                    url: imgSrc
                                });
                            }
                        });

                        state && btn.trigger('_show');
                    },
                    'mouseleave' : function(){
                        var btn = $.picEditBtn('close');
                        if(state){
                            $.editTimer = setTimeout(function(){
                                if(!btn.hasClass('on')){
                                    btn.trigger('_hide');
                                }
                            }, 100);
                        }
                    }
                });
            }else{
                $(this).click(function(){
                    saveAfter();
                    top.featherEditor.launch({
                        image: imageId,
                        url: imgSrc
                    });
                });
            }
        });
    };
})(jQuery);