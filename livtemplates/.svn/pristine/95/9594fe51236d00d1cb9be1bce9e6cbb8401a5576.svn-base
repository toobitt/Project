(function($){
    var defaultOptions = {
        url : '',
        phpkey : 'videofile',
        type : 'image',
        filter : $.noop,
        before : $.noop,
        beforeSend : $.noop,
        after : $.noop
    };
    $.fn.ajaxUpload = function(option,refreshUrl){
        option = $.extend({}, defaultOptions, option);
        return this.each(function(){
        	if( refreshUrl ){
        	}
            if($(this).data('ajaxUpload')){
                return;
            }
            $(this).data('ajaxUpload', true);
            var index = 1;
            var url = option['url'];
            var before = option['before'];
            var beforeSend = option['beforeSend'];
            var after = option['after'];
            var filter = option['filter'];
            var phpkey = option['phpkey'];
            var type = option['type'];
            var me = $(this);
            $(this).on('change', function(event){
                var i, len = this.files.length, file, reader, formdata;
                var $obj = $('.each-list.active'),
                	nLength = $obj.find('li').length,
                	stack_i = $obj.index() + 1;
                for(i = 0; i < len; i++){
                    file = this.files[i];
                    if(type == 'image' && !file.type.match(/image.*/)){
                        continue;
                    }
                    if(!$.browser.chrome){
	                    if($.type(type) == 'function' && !type.call(me, file.type)){
	                        continue;
	                    }
                    }
                    (function(ii, fileTmp){
                        if(window.FileReader){
                            reader = new FileReader();
                            reader.onloadend = function(event){
                                var target = event.target;
                                before.call(me, {
                                    data : target,
                                    file : fileTmp,
                                    index : ii
                                });
                            };
                            reader.readAsDataURL(file);
                        }
                        if(window.FormData){
                            formdata = new FormData();
                            formdata.append(phpkey, file);
                            if(filter){
                                filter.call(me, formdata);
                            }
                            $.ajax({
                                url : option['url'] + "&stack_id=" + stack_i + "&page_num=" + (nLength + i+1),
                                type : 'POST',
                                data : formdata,
                                processData : false,
                                contentType : false,
                                dataType : 'json',
                                beforeSend : function(jqXHR, settings){
                                    beforeSend.call(me, {
                                        index : ii
                                    });
                                },
                                success: function(data){
                                    data = data[0] || {};
                                    after.call(me, {
                                        data : data,
                                        index: ii
                                    });
                                    me.val('');
                                }
                            });
                        }
                    })(index++, file);
                }
            });
        });
    }
})(jQuery);