(function($){
    var defaultConfig = {
        proxy : './magic/proxy.php'
    };

    var printscreen = {};
    $.extend(printscreen, {
        init : function(option){
            option = $.type(option) == 'object' ? option : {};
            option = $.extend(defaultConfig, option);

            if(!$.isFunction(window.html2canvas)){
                console.log('没有引入包含html2canvas的JS文件！');
                return;
            }

            return this.each(function(){
                var node = $(option['node']);
                if(!node.length){
                    console.log('找不到该节点');
                    return;
                }
                var uploadUrl = option['upload-url'];
                var uploadKey = option['upload-key'];
                var uploadCallback = option['upload-callback'];
                var $this = $(this);
                var loadCb = $.globalLoad($this);
                var config = {
                    proxy : option['proxy'],
                    onrendered : function(canvas){
                        var imgData = printscreen.getImgDataFromCanvas.apply(this, [canvas]);
                        var uploadData = {};
                        //imgData.replace('data:image/png;base64,', '');
                        uploadData[uploadKey] = imgData;
                        printscreen.upload.apply(this, [uploadUrl, uploadData, function(json){
                            uploadCallback && uploadCallback(json);
                            loadCb();
                        }]);
                    }
                };
                printscreen.htmlToCanvas.apply(this, [node[0], config]);
            });
        },

        upload : function(url, data, cb){
            $.post(
                url,
                data,
                function(json){
                    cb && cb(json);
                },
                'json'
            );
        },

        htmlToCanvas : function(node, option){
            html2canvas(node, option);
        },

        getImgDataFromCanvas : function(canvas){
            return canvas.toDataURL('image/png');
        }
    });

    $.fn.printscreen = function(argument){
        if(printscreen[argument]){
            return printscreen[argument].apply(this, Array.prototype.slice.call(arguments, 1));
        }else if($.type(argument) == 'object' || !argument){
            return  printscreen['init'].apply(this, arguments);
        }
    };
})(jQuery);

/*
*  调用方式
*         $('xxx').printscreen({
*               node : 'yyy',
                'upload-url' : '',
                'upload-key' : 'abc',
                'upload-callback' : function(json){},
*         });
*
* */