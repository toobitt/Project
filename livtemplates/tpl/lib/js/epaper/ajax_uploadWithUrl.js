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
    $.fn.ajaxUploadWithUrl = function(option,refreshUrl){
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
            	var epaper_id = $('input[name="epaper_id"]').val(),
					period_id = $('input[name="period_id"]').val();
                var i, len = this.files.length, file, reader, formdata, filename;
                var $obj = $('.each-list.active'),
                	$li = $obj.find('li'),
                	nLength = $li.length,
                	stack_i = $obj.attr('_id'),
                	stackFlag = $obj.attr('_flag');
                //批量pdf
                var page_ids = $li.map(function(){
                	return $(this).attr('_id')
                }).get().join(',');
                for(i = 0; i < len; i++){
                    file = this.files[i];
                    filename = file['name'];
                    var over = false;		//pdf是否超过jpg
                    var flagIndex = filename.indexOf(stackFlag),		
	                	substring = filename.substring(flagIndex + 1),
	                	fileIndex = parseInt(substring);
                    
                    var sameFlag = stackFlag + fileIndex;
                    var theSame = $li.filter(function(){
                    	return ( $(this).find('.pageNum').text() == sameFlag );
                    });
                    theSame.addClass('update').siblings().removeClass('update');
                    var $update = $li.filter(function(){
	                    	return $(this).hasClass('update');
	                    	}),
	                    update_len = $update.length,
	                    update_flag = $update.attr('_flag'),
	                    url_more = '',
	                    page_id = $update.attr('_id'),
	                    img_id = $update.find('img').filter('.show').attr('_id');
//	                if( flagIndex == -1 ){
//	                	var words = '请上传' + stackFlag + '叠下的图片';
//	                	jAlert(words,'提示');
//	                	continue;
//	                }
	                if( $.MC.target.attr('_type') == 'pdf' ){
	                	var relateJpg = $li.filter(function(){
	                		return parseInt($(this).attr('_flag')) == fileIndex;
	                	}).length;
	                	if(relateJpg == 0){
	                		over = true;
	                	}
	                }
                    if(type == 'image' && !file.type.match(/image.*/)){
                        continue;
                    }
                    if(!$.browser.chrome){
	                    if($.type(type) == 'function' && !type.call(me, file.type)){
	                        continue;
	                    }
                    }
                    (function(ii, fileTmp){
                    	var filename = fileTmp['name'];
                        var flagIndex = filename.indexOf(stackFlag),		
    	                	substring = filename.substring(flagIndex + 1),
    	                	fileIndex = parseInt(substring);
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
                            var isPdf = $.MC.target.attr('_type') == 'pdf';
                            var paramCache = {};
                            paramCache['stack_id'] = stack_i;
                            if( $.MC.target.hasClass('target-update') ){	//更新
                            	paramCache['page_id'] = $.MC.target.closest('li').attr('_id');
                            	paramCache['img_id'] = 
                            		$.MC.target.closest('li').find( isPdf ? '.pdf_id_hid' : '.jpg_id_hid' ).val();
                            	paramCache['page_num'] = $.MC.target.closest('li').attr('_pagenum');
                            }else{											//新增
                            	paramCache['page_ids'] = page_ids;
                            	paramCache['epaper_id'] = epaper_id;
                            	paramCache['period_id'] = period_id;
//                            	paramCache['page_num'] = ii;
                            	if( isPdf ){
                            		over = true;
                            	}
                            }
                            for( var i in paramCache ){
                            	url_more += '&' + i + '=' + paramCache[i];
                            }
                            $.ajax({
                                url : option['url'] + url_more,
                                type : 'POST',
                                data : formdata,
                                processData : false,
                                contentType : false,
                                dataType : 'json',
                                beforeSend : function(jqXHR, settings){
                                    beforeSend.call(me, {
                                        index : ii,
                                    });
                                },
                                success: function(data){
                                    data = data[0] || {};
                                    $li.removeClass('update');
                                    after.call(me, {
                                        data : data,
                                        index: ii,
                                        fileIndex : fileIndex,
                                        isover : over
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