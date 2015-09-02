(function($){
    var defaultOptions = {
        url : "./run.php?mid=" + gMid + "&a=upload_tuji_imgs&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
        phpkey : 'file',
        type : 'image',
        filter : $.noop,
        before : $.noop,
        beforeSend : $.noop,
        after : $.noop
    };

    var msType = [
        'application/x-zip-compressed',
        'application/msword',
        'application/vnd.ms-powerpoint',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];
    function upload(option){
        option = $.extend({}, defaultOptions, option);
        //return this.each(function(){
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
            for(i = 0; i < len; i++){
                file = this.files[i];//alert(file.type);return false;
                /*if($.type(type) == 'function' && type.call(me, file.type) === false){
                    continue;
                }*/
                (function(ii, fileTmp){
                    var fileInfo = {
                        name : fileTmp.name
                    };
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
                        reader.readAsText(file);
                    }

                    if(window.FormData){
                        formdata = new FormData();
                        formdata.append(phpkey, file);
                        if(filter){
                            filter.call(me, formdata);
                        }
                        $.ajax({
                            url : url,
                            type : 'POST',
                            data : formdata,
                            processData : false,
                            contentType : false,
                            dataType : 'json',
                            beforeSend : function(jqXHR, settings){
                                beforeSend.call(me, {
                                    index : ii,
                                    fileInfo : fileInfo
                                });
                            },
                            success: function(data){
                                after.call(me, data);
                            },
                            statusCode : {413 : function(){
                                $.clearWordUpload("服务器报413啦，意味着你的文档太大了。");
                            }},
                            error : function(xhr, status){

                            }
                        });
                    }
                })(index++, file);
            }
        });
        //});
    }

    function ajaxWordUpload(options){
        options = options || {};
        options['type'] = function(type){
            if($.inArray(type, msType) == -1){
                return false;
            }
        };
        var fileElement = $('<input type="file" name="file" accept="'+ msType.join(',') +'" style="display:none;"/>').appendTo('body');
        upload.call(fileElement, options);
        fileElement.trigger('click');
    }


    $.doWordUpload = function(num){
        $.doWordUpload.num = num;
        var ajaxBeforeSend = function(info){
            var html = '<div id="word-uploading">文档名：<span>'+ info.fileInfo.name +'</span>。正在上传中，请稍等...</div>';
            window['oEdit' + num].insertHTML(html);
            var wordOffset = $('#CWordoEdit' + num).offset();
            wordOffset['top'] += 30;
            $.officeBox(num).trigger('offset', [wordOffset]).trigger('html', ['文档上传中...']);
        };

        var ajaxAfter = function(json){
            if(json.error){
                $.clearWordUpload("文档解析出错！");
                return false;
            }
            $.officeBox(num).trigger('html', ['文档上传成功！正在解析中...']);
            var editorWindow = $("#idContentoEdit" + num)[0].contentWindow;
            var bodyHtml = "";
            var bodyImgNum = 0;
            var href = location.href;
            var url = href.substr(0, href.indexOf(location.pathname));
            url += (location.pathname.indexOf("livworkbench") != -1 ? "/livworkbench" : "") + "/";
            $("<iframe/>").attr("src", url + json["url"]).attr("id", "word-iframe").on({
                load : function(){
                    var body = $(this.contentWindow.document).find("body");
                    bodyImgNum = body.find("img").each(function(){
                        $(this).attr("src", url + json.path + $(this).attr("src"));
                    }).length;
                    bodyHtml = body.html();
                    after();
                    $("#word-iframe").off("load").remove();
                }
            }).css("display", "none").appendTo("body");
            /*function after(){
                var index = 3;
                var string = "word文档已经上传成功！<span style=\"color:green;margin:0 5px;\"></span>后将执行处理并插入页面...";
                var editBody = $(editorWindow.document).find("body");
                var wordBox = editBody.find("#word-uploading");
                var wuTop = wordBox.offset().top - 20;
                wuTop < 0 && (wuTop = 0);
                editBody.animate({
                    scrollTop : wuTop + "px"
                }, 200);
                var colors = ["red", "yellow"];
                var colorIndex = 0;
                var colorTimer = setInterval(function(){
                    wordBox.css("border-color", colors[colorIndex % colors.length]);
                    colorIndex++;
                }, 50);
                var timer = setInterval(function(){
                    if(index < 0){
                        clearInterval(timer);
                        timer = null;
                        clearInterval(colorTimer);
                        colorTimer = null;

                        /*var w = $(window), wd = w.width(), wh = w.height();
                         $("<div/>").attr("id", "word-uploading-mask").css({
                         position : "absolute",
                         "z-index" : "10000000",
                         left : 0,
                         top : 0,
                         background : "#000",
                         opacity : .3,
                         width : wd + "px",
                         height : wh + "px"
                         }).appendTo("body");
                         $("<div/>").attr("id", "word-uploading-mask-tip").css({
                         position : "absolute",
                         "z-index" : "10000001",
                         left : (wd - 200) / 2 + "px",
                         top : wh / 2 + "px",
                         color : "red",
                         "font-size" : "18px",
                         "font-weight" : "bold"
                         }).html("处理中...请稍等...").appendTo("body");
                         setTimeout(function(){
                         $("#word-uploading-mask, #word-uploading-mask-tip").remove();
                         }, 2000);
                        wordBox.replaceWith(bodyHtml);
                        window['EditorLocalImage' + num]();
                        $("#word-iframe").off("load").remove();
                        return false;
                    }
                    wordBox.html(string).find("span").html(index);
                    index--;
                }, 1000);
            }*/

            function after(){
                var editBody = $(editorWindow.document).find("body");
                var wordBox = editBody.find("#word-uploading");
                var wuTop = wordBox.offset().top - 20;
                wuTop < 0 && (wuTop = 0);
                editBody.animate({
                    scrollTop : wuTop + "px"
                }, 200);

                wordBox.replaceWith(bodyHtml);
                if(bodyImgNum){
                    $.officeBox(num).trigger('html', ['发现' + bodyImgNum + '张需要本地化的图片，准备收集...']);
                    setTimeout(function(){
                        window['EditorImage' + num].event.local(function(result){
                            if(!result){
                                officeBox.trigger('html', ['没有发现需要本地化的图片']).trigger('hide');
                            }
                        });
                    }, 1000);
                }
            }
        };

        ajaxWordUpload({
            url : "./word.php",
            beforeSend : ajaxBeforeSend,
            after : ajaxAfter
        });
    }

    $.clearWordUpload = function(string){
        var num = $.doWordUpload.num;
        $('#idContentoEdit' + num).contents().find('#word-uploading').hide(300, function(){
            $(this).remove();
        });
        var officeBox = $.officeBox(num).trigger('html', [string]);
        setTimeout(function(){
            officeBox.trigger('hide');
        }, 2000);
    }


    $.officeBox = function(_num){
        var box = $('#local-box-process' + _num);
        if(!box[0]){
            var offset = $('#CLocaloEdit' + _num).offset();
            box = $('<div/>').attr('id', 'local-box-process' + _num).css({
                position : 'absolute',
                left : 0,
                top : 0,
                height : '34px',
                'line-height' : '34px',
                border : '1px solid #ff9a00',
                padding : '10px',
                background : '#fff',
                'text-align' : 'center',
                'z-index' : 10000000,
                'border-radius' : '2px',
                'color' : '#ff9a00'
            }).appendTo('body').on({
                insert : function(event, img, imgInfo, callback){
                    var thumImg = $('<div/>').css({
                        position : 'absolute',
                        top : '10px',
                        border : '1px dashed #ccc',
                        opacity : 0,
                        width : '30px',
                        height : '30px',
                        'line-height' : '30px',
                        'text-align' : 'center',
                        background : '#fff'
                    }).appendTo(this).append($('<img/>').attr({
                        hash : imgInfo['hash'],
                        src : imgInfo['src']
                    }).css({
                        width : '30px',
                        height : '30px'
                    }));
                    var editor = $('#idContentoEdit' + _num);
                    var editorOffset = editor.offset();
                    var thumImgOffset = thumImg.offset();
                    var offset = img.offset();
                    var body = $(editor[0].contentWindow.document).find('body');
                    body.animate({
                        scrollTop : offset.top - 20 + 'px'
                    }, 200, function(){
                        var clone = $('<img/>').attr('src', imgInfo['src']).css({
                            position : 'absolute',
                            left : offset.left + editorOffset.left + 'px',
                            top : offset.top - body.scrollTop() + editorOffset.top + 'px',
                            width : img.width()
                        }).appendTo('body').animate({
                            width : '30px',
                            left : thumImgOffset.left + 'px',
                            top : thumImgOffset.top + 'px'
                        }, 300, function(){
                            $(this).remove();
                            thumImg.css('opacity', 1);
                            callback && callback.call(thumImg);
                        });
                    });
                },
                remove : function(event, hash, directImg){
                    var thumImg = $(this).find('img[hash="'+ hash +'"]');
                    var thumImgParent = thumImg.parent();
                    var thumOffset = thumImg.offset();
                    var directImgOffset = directImg.offset();
                    thumImg.appendTo('body').css({
                        position : 'absolute',
                        left : thumOffset.left + 'px',
                        top : thumOffset.top + 'px',
                        'z-index' : 1000000,
                        width : '30px',
                        height : '30px'
                    }).animate({
                        left : directImgOffset.left + 'px',
                        top : directImgOffset.top + 'px',
                        width : '160px'
                    }, 400, function(){
                        $(this).remove();
                    });
                    thumImgParent.html('OK');
                },
                error : function(event, hash){
                    var thumImg = $(this).find('img[hash="'+ hash +'"]');
                    thumImg.animate({
                        top : '50px'
                    }, 100).delay(100).animate({
                        opacity : 0
                    }, 300, function(){
                        $(this).remove();
                    });
                },
                close : function(event, cb){
                    var me = $(this);
                    if(me.find('img')[0]) return;
                    me.trigger('html', ['本地化完成']);
                    /*var colors = ['green', 'red'];
                    var num = 21;
                    var numArr = new Array(num);
                    me.animate({
                        opacity : 1
                    }, 100);

                    $.each(numArr, function(i, n){
                        me.queue(function(next){
                            $(this).css('border-color', colors[i%2]);
                            next();
                        }).delay(100);
                        if(i + 1 == num){
                            me.delay(1000).hide(300);
                        }
                    });
                    me.dequeue();*/
                    setTimeout(function(){
                        me.trigger('hide');
                    }, 1000);
                    cb();
                },
                offset : function(event, offset){
                    $(this).css({
                        left : offset.left + 'px',
                        top : offset.top + 'px'
                    });
                },
                html : function(event, html){
                    $(this).html(html).css('width', 'auto');
                },
                show : function(){
                    $(this).show();
                },
                hide : function(){
                    $(this).hide(300);
                }
            });
        }
        return box.trigger('show');
    }

    $.officeBoxMask = function(num){
        var mask = $('#local-box-mask' + num);
        if(!mask[0]){
            mask = $('<div/>').attr('id', 'local-box-mask' + num).css({
                position : 'absolute',
                //'background' : '#000',
                'z-index' : 100
            }).appendTo('body').on({
                show : function(){
                    var editor = $('#idContentoEdit' + num);
                    var offset = editor.offset();
                    var ew = editor.width();
                    var eh = editor.height();
                    $(this).css({
                        left : offset.left + 'px',
                        top : offset.top + 'px',
                        width : ew + 'px',
                        height : eh + 'px'
                    }).show();
                },
                hide : function(){
                    $(this).hide();
                }
            });
        }
        return mask;
    }
})(jQuery);