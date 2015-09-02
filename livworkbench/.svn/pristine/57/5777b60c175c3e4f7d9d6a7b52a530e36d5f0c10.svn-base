var mozPageEvent = function(iframeWindow, parentObj, name, cname, autoHeight, number){

        var pagenumber = 0;
        var editOffset = $('#idContentoEdit' + number).offset();
        var editTop = parseInt(editOffset['top']);
        $(iframeWindow).scroll(function(){
            if(!pagenumber) return;
            //parentObj.myhide();
        });

        $(iframeWindow.document).on("refresh", function(event, init){
            var pagesData = [];
            var thisBody = $(this.body);
            var pages = thisBody.find(cname);
            $(this).trigger("setbody", [pages.length]);
            var newPageIndex = -1;
            pages.each(function(i){
                if(!pagesData.length){
                    pagesData.push({
                        index : 0,
                        top : 0,
                        title : thisBody.data("_title") || ""
                    });
                }
                pagesData.push({
                    index : i + 1,
                    top : $(this).offset().top,
                    title : $(this).attr("_title") || ""
                });
                if($(this).attr("_title") === undefined){
                    newPageIndex = i;
                    $(this).attr("_title", "");
                }
            });
            pagenumber = pages.length;
            parentObj.refresh(pagesData, $(this).scrollTop(), init);
            window["statistics" + number].pageNumber(pagesData.length);
        }).on("top", function(event, info){
            $('body').stop(true, true).animate({
                scrollTop : (info[0] == 0 ? info[0] : (editTop + info[0])) + 'px'
            });
            /*$(this.body).stop(true, true).animate({
                scrollTop : info[0] + 'px'
            }, 300, function(){
                var top = $(this).scrollTop();
                if(info[1]){
                    info[1](top);
                }
            });*/
        }).on("settitle", function(event, data){
            var index = data["index"],
                thisBody = $(this.body);
            if(index == 0){
                thisBody.data("_title", data["title"]);
            }else{
                thisBody.find(cname).eq(index - 1).attr("_title", data["title"]);
            }
        }).on("setbody", function(event, length){
            var thisBody = $(this.body);
            if(!length){
                thisBody.data("_title", "").removeClass("haspage");
            }else{
                thisBody.addClass("haspage");
            }
        }).on("delete", function(event, index){
            if(index == 0){
                return;
            }
            var current = $(this.body).find(cname).eq(index - 1);
            var me = $(this);
            current.css({
                'margin-left' : 'auto',
                'margin-right' : 'auto'
            }).animate({
                width : 0
            }, 1000, function(){
                $(this).remove();
                me.trigger("refresh");
            });
        })

        .on('check', function(){
            if(!$(this).find('.pagebg').length){
                $(this.body).removeClass("haspage");
            }
            $(this).trigger("refresh");
        });

        $(iframeWindow.document).on('mouseup', '.pagebg', function(event){
            var index = $(iframeWindow.document).find('.pagebg').index(this) + 1;
            parentObj.select(index);
            iframeWindow.getSelection().removeAllRanges();
        });

        var brhtml = window["globalEditorConfig"]["page"];
        var autoing = false;
        $(iframeWindow.document).on("_auto", function(){
            if(autoing) return;
            $(this).find(".pagebg").remove();
            $(this).trigger('refresh');
            $(this).scrollTop(0);
            var thisBody = $(this.body);
            var children = thisBody.children();
            var maxn = 20, initn = 1;
            while(true){
                if(initn >= 20){
                    break;
                }
                initn++;
                if(children.length == 1){
                    thisBody.html(children.eq(0).html());
                    children = thisBody.children();
                }else{
                    break;
                }
            }
            if(initn >= 20){
                return;
            }
            var all = [];
            children.each(function(){
                all.push($(this).offset().top);
            });

            var len = all.length, first = false, height, now, index = -1, func;
            height = now = autoHeight;
            autoing = true;
            setTimeout(func = function(){
                var n;
                while(true){
                    if(!all.length){
                        autoing = false;
                        break;
                    }
                    n = all.shift();
                    index++;
                    if(n < now){
                        continue;
                    }else{
                        if(!first){
                            $(iframeWindow.document).trigger("setbody", [len]);
                            first = true;
                        }
                        now = n + height;
                        var currentBg = children.eq(index).before(brhtml).prev();

                        /*thisBody.stop(true, true).animate({
                            scrollTop : currentBg.offset().top + 'px'
                        }, 300, function(){*/
                        $('body').stop(true, true).animate({
                            scrollTop : editTop + currentBg.offset().top + 'px'
                        }, 300, function(){
                            $(iframeWindow.document).trigger("refresh");
                            setTimeout(function(){
                                //parentObj.myshow(thisBody.scrollTop());
                                setTimeout(function(){
                                    func();
                                }, 600);
                            }, 100);
                        });
                        break;
                    }
                }
            }, 800);
        });
}