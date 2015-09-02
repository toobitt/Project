function pageCreateScript(mywindow, src, content){
    var script = mywindow.document.createElement("SCRIPT");
    script.type = "text/javascript";
    if(src){
        script.src = src;
    }else{
        script.textContent = content;
    }
    mywindow.document.documentElement.childNodes[0].appendChild(script);
}

function PageClass(number){
    this.number = number;
    this.leftBox = null;
    this.bottomBox = null;
    this.pageslide = null;
    this.slideManage = window["slideManage" + this.number];
    this.curl = null;
    this.firstInit = false;
    this.pages = null;
    this.ptop = 0;
    this.init();
}
PageClass.prototype = {
    init : function(){
        this.curl = window["contentWindow" + this.number];
        this.leftBox = $("#page-left-box" + this.number);

        this.pageslide = new SlideClass(this.number, 'pageslide');
        this.slideManage.add("pageslide", this.pageslide);
        new PageslideEvent(this.number, this.pageslide);

        this.bottomBox = $("#page-bottom-box" + this.number);

        this.initLeftBox();
        this.initBottomBox();

        //this.initMove();
    },
    initLeftBox : function(){
        var box = this.leftBox;
        var self = this;
        box.on('refresh', function(event, init){
            var pages = self.pages;
            var html = "";
            $.each(pages, function(i, page){
                html += '<div class="page-left-item '+ (i == 0 ? 'page-left-item-first' : '') +'" style="top:'+ page['top'] +'px;">'+
                    '<span class="page-left-number">'+ (i+1) +'</span>'+
                    '<input type="text" class="page-left-title-input" value="'+ page['title'] +'"/>'+
                    '<span class="page-left-del" '+ (i == 0 ? 'style="display:none;"' : '') +'></span>'+
                    '</div>';
            });
            $(this).html(html);
            if(!this.firstInit){
                this.firstInit = true;
                //$(this).hide();
            }else{
                if(!init){
                    $(this).trigger('myshow');
                }
            }
        });

        box.on('myshow', function(){return;
            var pages = self.pages,
                ptop = self.ptop,
                pheight = $("#idContentoEdit" + self.number).height(),
                items = $(this).find(".page-left-item");
            var mint = ptop - 50, maxt = ptop + pheight - 90;
            $.each(pages, function(i, n){
                if(n.top >= (i == 0 ? ptop : mint) && n.top <= maxt){
                    items.eq(i).show();
                }else{
                    items.eq(i).hide();
                }
            });
            $(this).show().parent().css('top', - ptop + 'px');
        });

        box.on('click', '.page-left-del', function(){
            var index = box.find('.page-left-del').index(this);
            if(!index) return;
            self.curl("delete", index);
            $(this).closest('.page-left-item').remove();
        });

        box.on('click', '.page-left-item', function(){
            $(this).find('.page-left-title-input').focus();
        });
        
        box.on('settitle', function(event, index, title){
            $(this).find('.page-left-title-input').eq(index).val(title);
        });

        box.on({
            focus : function(){
                $(this).closest('.page-left-item').addClass('focus');
            },
            blur : function(){
                $(this).closest('.page-left-item').removeClass('focus');
            },
            keyup : function(){
                var index = $(this).closest('.page-left-item').parent().find('.page-left-title-input').index(this),
                    title = $(this).val();
                self.bottomBox.trigger('settitle', [index, title]);
                self.curl('settitle', {
                    index : index,
                    title : title
                });
            }
        }, '.page-left-title-input');

        box.on('select', function(event, index){
            var me = $(this);
            setTimeout(function(){
                me.find('.page-left-item').eq(index).trigger('click');
            }, 0);

        });
    },
    initBottomBox : function(){
        var box = this.bottomBox;
        var self = this;
        box.on("refresh", function(event, init){
            var pages = self.pages;
            if(!pages.length){
                $(this).html("").hide();
                return;
            }
            var html = "<ul>";
            $.each(pages, function(i, page){
                html += '<li class="page-bottom-item">'+
                    '<span class="page-bottom-tab-item">'+ (i+1) + '</span>'+
                    '<textarea class="page-bottom-textarea input-hide" style="height:22px;line-height:22px;width:150px;">'+ page["title"] +'</textarea>'+
                    '</li>';
            });
            html += "</ul>";

            $(this).html(html).show();

            var area = $(this).find('.page-bottom-textarea');
            area.inputHideOther();
            area.textareaAuto();
            area.trigger('propertychange');
            area.on('keyup', function(){
                var index = $(this).closest('.page-bottom-item').parent().find('.page-bottom-textarea:not([tabindex="-1"])').index(this),
                    title = $(this).val();
                self.leftBox.trigger('settitle', [index, title]);
                self.curl('settitle', {
                    index : index,
                    title : title
                });
            });

            if(init){
                //$(this).find('.page-bottom-item:last').trigger('click');
            }
        });

        box.on('settitle', function(event, index, title){
            $(this).find('.page-bottom-textarea:not([tabindex="-1"])').eq(index).val(title).trigger('propertychange');
        });

        box.on('click', '.page-bottom-item', function(event, onlyselect){
            if($(this).hasClass("current")) return;

            var pages = self.pages;
            box.find('.page-bottom-item.current').removeClass('current');
            var all = box.find(".page-bottom-item");
            var index = all.index(this);
            $(this).addClass("current");

            if(!onlyselect){
                var page = pages[index];
                self.curl("top", [page.top, function(top){
                    setTimeout(function(){
                        self.myshow(top);
                    }, 100);
                }]);
            }
            $(this).find('.page-bottom-textarea:not([tabindex="-1"])').trigger('focus');
        });

        box.on('select', function(event, index){
            $(this).find('.page-bottom-item').eq(index).trigger('click', [true]);
        });
    },
    initMove : function(){
        return;
        var self = this;
        var move = false, tleft = ttop = x = y = 0, obj = null, del = false, index = -1;
        $(document).mousedown(function(event){
            var target = event.target;
            if(target){
                target = $(target);
                if(target.hasClass("page-left-number")){
                    index = self.leftBox.find(".page-left-number").index(target.get(0));
                    if(index == 0){
                        return;
                    }
                    $(this).trigger('drag', [false]);
                    obj = target;
                    move = true;
                    tleft = parseInt(target.css("left"), 10) || 0;
                    ttop = parseInt(target.css("top"), 10) || 0;
                    x = event.pageX;
                    y = event.pageY;
                }
            }
        }).mousemove(function(event){
            if(move && obj){
                obj.css({
                    left : tleft + event.pageX - x + "px",
                    top : ttop + event.pageY - y + "px"
                });
            }
        }).mouseup(function(event){
            if(move && obj){
                if(index > 0){

                    var editor = $("#idContentoEdit" + self.number);
                    var editorOffset = editor.offset();
                    var objOffset = obj.offset();
                    var del = false;
                    if(objOffset.top < editorOffset.top || objOffset.top > editorOffset.top + editor.height()){
                        del = true;
                    }else if(objOffset.left < editorOffset.left || objOffset.left > editorOffset.left + editor.width()){
                        del = true;
                    }
                    if(del){
                        obj.parent().fadeOut(1000, function(){
                            self.curl("delete", index);
                        });
                    }else{
                        obj.animate({
                            left : tleft + "px",
                            top : ttop + "px"
                        }, 200);
                    }
                }
                move = false;
                tleft = ttop = x = y = 0;
                obj = null;
                del = false
                $(this).trigger('drag', [true]);
            }
        });
        $(document).on('drag', function(event, state){
            this.onselectstart = this.drag = function(){return state;}
            state = state ? 'auto' : 'none';
            $(this).attr('style', $(this).attr('style') + ';' + '-moz-user-select:'+ state +';-khtml-user-select:'+ state +';user-select:'+ state +';');
        });
    },
    refresh : function(pages, top, init){
        this.pages = pages;
        this.ptop = top;
        this.leftBox.trigger("refresh", [init]);
        this.bottomBox.trigger("refresh", [init]);
    },
    myshow : function(top){
        this.ptop = top;
        this.leftBox.trigger("myshow");
    },
    myhide : function(){
        //this.leftBox.hide();
    },
    select : function(index){
        this.slideManage.openOne('pageslide');
        this.bottomBox.trigger('select', [index]);
        this.leftBox.trigger('select', [index]);
    }
}


function PageslideEvent(number, slide){
    this.number = number;
    this.slide = slide;
    this.editor = window['oEdit' + this.number];
    this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
    this.box = null;
    this.init();
}

jQuery.extend(PageslideEvent.prototype, {
    init : function(){
        var self = this;
        this.slide.html(this.content());
        this.slide.addInitFunc(function(){
            window['contentWindow' + self.number]('refresh');
        });
        this.slide.addCloseFunc(function(){
            window['cpage' + self.number].myhide();
        });
        this.box = $('#edit-slide-pageslide'+ this.number);
    },
    content : function(){
        return '<div id="edit-slide-pageslide' + this.number +'" class="edit-slide-html-each">'+
            '<div class="edit-slide-title"><span class="edit-slide-close">关闭</span>分页设置</div>'+
            '<div class="edit-slide-pageslide-content edit-slide-content" id="page-bottom-box' + this.number + '"></div>'+
            '</div>';
    }
});


(function($){
    var inputHide = 'input-hide';
    $.fn.inputHideOther = function(){
        return this.each(function(){
            $(this).on('focus', function(){
                var me = $(this),
                    val = me.attr('_value'),
                    _default = me.attr('_default');
                if(val == _default){
                    setTimeout(function(){
                        me.select();
                    }, 0);
                }else{
                    $(this).val(val);
                }
                me.removeClass(inputHide);
            }).on('blur', function(){
                    var me = $(this),
                        val = me.val(),
                        _default = me.attr('_default'),
                        id = me.attr('id');
                    if(!val || val == _default){
                        me.val(_default).data('hasval', false);
                    }else{
                        me.attr('_value', val).val(val).data('hasval', true);
                    }
                    me.addClass(inputHide)
                });
        });
    }
})(jQuery);