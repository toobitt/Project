jQuery(function($){
    var stimer = null;
    var shide = false;
    var pagenumber = 0;

    /*$(window).scroll(function(){
        if(!pagenumber) return;
        if(!shide){
            parentObj.hide();
            shide = true;
        }
        if(stimer){
            clearTimeout(stimer);
        }
        var self = $(this);
        stimer = setTimeout(function(){
            var top = self.scrollTop();
            parentObj.top(top);
            parentObj.show();
            shide = false;
        }, 100);
    });*/
    $(window).on('scroll', function(){
        if(!pagenumber) return;
        parentObj.hide();
        return;
        if(!shide){
            parentObj.hide();
            shide = true;
        }
        if(stimer){
            clearTimeout(stimer);
        }
        var self = $(this);
        stimer = setTimeout(function(){
            var top = self.scrollTop();
            parentObj.top(top);
            parentObj.show();
            shide = false;
        }, 100);
    });



    var kptimer = null;
    $(document).on('iscroll', function(){
        $(this).trigger('refresh');
        var top = $(this).scrollTop();
        parentObj.top(top);
        parentObj.show();
        shide = false;
    })/*.on('keyup', function(event){
        var code = event.keyCode;
        if(code == 13 || code == 8){
            $(this).triggerHandler('keypress');
        }
    }).on('cut paste keypress', function(event){
        if(!pagenumber) return;
        if(!kptimer){
            clearTimeout(kptimer);
            kptimer = null;
        }
        var code = event.keyCode;
        if(code >= 37 && code <= 40){
            return;
        }
        var self = $(this);
        kptimer = setTimeout(function(){
            self.trigger("refresh");
        }, 100);
    })*/.on("refresh", function(event, remove){
        var pagesData = [];
        var pages = $(cname);
        $(this).trigger("setbody", [pages.length]);
        var newPageIndex = -1;
        pages.each(function(i){
            if(!pagesData.length){
                pagesData.push({
                    index : 0,
                    top : 0,
                    title : $("body").data("_title") || ""
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
        parentObj.refresh(pagesData);
        setTimeout(function(){
            newPageIndex != -1 && parentObj.select(newPageIndex + 1);
        }, 50);

        parent.window["statistics" + number].pageNumber(pagesData.length);
    }).on("top", function(event, top){
        //$(this).scrollTop(top);
    }).on("settitle", function(event, data){
        var index = data["index"];
        if(index == 0){
            $("body").data("_title", data["title"]);
        }else{
            $(cname).eq(index - 1).attr("_title", data["title"]);
        }
    }).on("setbody", function(event, length){
        if(!length){
            $("body").data("_title", "").removeClass("haspage");
            $(".pagebefore, .pageafter").remove();
        }else{
            if(!$("body").hasClass("haspage")){
                $("body").addClass("haspage");
            }
        }
    }).on("delete", function(event, index){
        if(index == 0){
            return;
        }
        var current = $(cname).eq(index - 1);
        var prev = next = null;
        if((prev = current.prev()) && prev.hasClass("pagebefore")){
            prev.remove();
        }
        if((next = current.next()) && next.hasClass("pageafter")){
            next.remove();
        }
        current.remove();
        $(this).trigger("refresh");
    });


    var brhtml = parent.window["globalEditorConfig"]["page"];
    var autoing = false;
    $(document).on("_auto", function(){
        if(autoing) return;
        $(".pagebg, .pagebefore, .pageafter").remove();
        parentObj.empty();
        $(this).scrollTop(0);
        var children = $("body").children();
        var maxn = 20, initn = 1;
        while(true){
            if(initn >= 20){
                break;
            }
            initn++;
            if(children.length == 1){
                $("body").html(children.eq(0).html());
                children = $("body").children();
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

        var len = all.length, first = false, height = now = autoHeight, index = -1, func;
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
                        $(document).trigger("setbody", [len]);
                        first = true;
                    }
                    now = n + height;
                    children.eq(index).before(brhtml);
                    $(document).trigger("refresh");
                    parentObj.select();
                    setTimeout(function(){
                        func();
                    }, 1000);
                    break;
                }
            }

        }, 800);
    });
});