function SlideClass(number, extname){
    this.number = number;
    this.extname = extname;
    this.slide = null;
    this.slideInner = null;
    this.slideHtml = null;
    this.width = null;
    this.initFunc = [];
    this.closeFunc = [];
    this.data = null;
    this.init();
}
jQuery.extend(SlideClass.prototype,{
    init : function(){
        this.slide = $('<div id="edit-slide-'+ this.extname + this.number +'-box" class="edit-slide"><div class="edit-slide-inner"><div class="edit-slide-html"></div></div></div>').appendTo('body');
        this.slideInner = this.slide.children().eq(0);
        this.slideHtml = this.slideInner.children().eq(0);
        this.css();
        this.width = this.slideInner.width();

        var self = this;
        this.slide.on('click', '.edit-slide-close', function(){
            self.close();
        }).on('click', '.edit-slide-next', function(){
            self.slideHtml.trigger('move', ['next', $(this).data('bcallback'), $(this).data('acallback')]);
        }).on('click', '.edit-slide-back', function(){
            self.slideHtml.trigger('move', ['back']);
        });

        this.slideHtml.on('move', function(event, direct, bcallback, acallback){
            var width = self.width;
            if(direct == 'next'){
                var html = bcallback ? $.proxy(bcallback, this)() : '';
                $(this).append('<div class="edit-slide-html-each">'+ html +'</div>').animate({
                    left : '-=' + width + 'px'
                }, 200);
                acallback && $.proxy(acallback, this)();
            }else{
                $(this).animate({
                    left : '+=' + width + 'px'
                }, 200, function(){
                    $(this).find('.edit-slide-html-each').last().remove();
                });
                self.resizeHeight();
            }
        }).on('open', function(){
            $(this).css('left', 0).find('.edit-slide-html-each').not(':first').remove();
        });

        $(window).on('resize.slide', function(){
            self.resizeHeight();
        });
    },
    css : function(){
        var edit = $('#idContentoEdit' + this.number);
        var offset = edit.offset();
        var left = offset.left;
        var top = offset.top;
        var width = edit.width();
        var height = edit.height();
        var middle = $('#form-middle');
        middle = middle[0] ? middle.offset().top : 0;

        var _this = this;
        var setNumber = 0;
        var setHeight = function(){
            setNumber++;
            if(setNumber > 10){
                return;
            }
            var winHeight = $(window).height();
            if(winHeight < 200){
                setTimeout(setHeight, 500);
            }else{
                height = winHeight - middle - 10;
                _this.slide.css({
                    left : '50%',
                    top : 90 + 'px',
                    'margin-left' : '340px',
                    height : height + 'px'
                });
                _this.slideInner.css({
                    height : height - 2 + 'px'
                });
                _this.resizeHeight(height - 2);
            }
        }
        setHeight();
    },
    resizeHeight : function(height){
        var content = this.slideHtml.find('.edit-slide-content:last');
        height = height || this.slideInner.height();
        var disHeight = 0;
        content.parent().children().each(function(){
            if(this == content[0]){
                return false;
            }
            disHeight += $(this).outerHeight(true);
        });
        content.height(height - disHeight);
        return;
        var content = this.slideHtml.find('.edit-slide-content:last'),
            ctop,
            wheight = $(window).height();
        if(content[0]){
            ctop = content.offset().top;
            content.height(wheight - ctop);
        }
    },
    open : function(state){
        var self = this;
        var doinit = function(){
            $.each(self.initFunc, function(i, n){
                n && $.type(n) == 'function' && n(self.data);
            });
        };
        if(this.opened){
            doinit();
            return;
        }
        this.opened = true;
        this.slide.show().css('z-index', 1000);
        this.slideInner.animate({
            left : '0px'
        }, 200);
        if(!state){
            this.slideHtml.trigger('open');
        }
        doinit();
        $(window).triggerHandler('resize.slide');
    },
    close : function(){
        var self = this;
        var doclose = function(){
            $.each(self.closeFunc, function(i, n){
                n && $.type(n) == 'function' && n(self.data);
            });
        };
        if(!this.opened){
            doclose();
            return;
        }
        var self = this;
        self.slide.css('z-index', 1);
        this.slideInner.animate({
            left : - this.slide.width() + 'px'
        }, 200, function(){
            self.slide.hide();
            self.opened = false;
        });
        doclose();
    },
    state : function(){
        return this.opened;
    },
    html : function(html){
        this.slideHtml.css('left', 0).html(html);
    },
    addInitFunc : function(func){
        this.initFunc.push(func);
    },
    addCloseFunc : function(func){
        this.closeFunc.push(func);
    }
});


function SlideManage(){
    this.box = {};
}
jQuery.extend(SlideManage.prototype, {
    add : function(name, obj){
        if(!obj instanceof SlideClass) return;
        this.box[name] = obj;
    },
    close : function(name){
        $.each(this.box, function(i, n){
            if(name && name == i){
                return;
            }
            n.close();
        });
    },
    openOne : function(name, data){
        $.each(this.box, function(i, n){
            if(name && name == i){
                data && (n.data = data);
                n.open();
            }else{
                n.close();
            }
        });
    },
    closeOne : function(name){
        $.each(this.box, function(i, n){
            if(name && name == i){
                n.close();
                return false;
            }
        });
    },
    closeMore : function(names){
        $.each(this.box, function(i, n){
            if($.inArray(i, names) != -1){
                n.close();
            }
        });
    },
    state : function(name, state, openorclose){
        $.each(this.box, function(i, n){
            if(name && name == i){
                if(n.state() === state){
                    n[openorclose]();
                }
            }
        });
    },
    has : function(name){
        return !!this.box[name];
    }
});

function mysaveForUndo(){
    oUtil.obj.saveForUndo();
}

function initEditorBind(myoEditorWindow, number){

    function myPageCheckAndRefresh(){
        if(arguments.callee.timeid){
            clearTimeout(arguments.callee.timeid);
        }
        arguments.callee.timeid = setTimeout(function(){
            window['contentWindow' + number]('check');
        }, 100);
    }

    //撤销按钮的事件绑定
    var reundoTimer = null;
    $('#btnUndooEdit' + number + ',#btnRedooEdit' + number).on({
        'click' : function(){
            reundoTimer && clearTimeout(reundoTimer);
            reundoTimer = setTimeout(function(){
                var focusHack = $('<input id="focus-hack" type="text" style="position:absolute;left:0;top:0;opacity:0;" size="1"/>').appendTo('body');
                focusHack.focus();
                $(myoEditorWindow.document).trigger('refresh', [true]);
                focusHack.remove();
                try{
                    var body = myoEditorWindow.document.body;
                    body.contentEditable = true;
                    var top = $(body).scrollTop();
                    body.focus();
                    $(body).scrollTop(top);
                    /*
                    var oSel = myoEditorWindow.getSelection();
                    var range = myoEditorWindow.document.createRange();
                    var hackSpan = range.createContextualFragment('<span id="edit-focus-hack"></span>');
                    range.insertNode(hackSpan.firstChild);
                    oSel.removeAllRanges();
                    oSel.addRange(range);
                    var hack = $(body).find('#edit-focus-hack');
                    var offset = hack.offset();
                    $(body).scrollTop(offset.top); */
                }catch(e){}
            }, 30);
        }
    });

    //禁止在firefox下面对于图片进行缩放处理
    if($.browser.mozilla){
        myoEditorWindow.document.execCommand("enableObjectResizing", false, false);
    }

    //跟上面的目的一样禁止在IE中缩放图片
    //myoEditorWindow.document.images.attachEvent("onresizestart", function(e) { e.returnValue = false; }, false);

    var biaozhuSlide = function(){
        var _biaozhuSlide = new SlideClass(number, 'biaozhu');
        window['slideManage' + number].add('biaozhu', _biaozhuSlide);
        var biaozhu = new BiaozhuEvent(number, _biaozhuSlide);
        biaozhu.set();
        $(myoEditorWindow.document).find('.before-biaozhu-ok, .after-biaozhu-ok').hide();
        return function(close){
            !close && biaozhu.set();
            window['slideManage' + number][close ? 'closeOne' : 'openOne']('biaozhu');
        }
    }();

    var cleanBiaozhu = function(){
        $(myoEditorWindow.document).find('.before-biaozhu, .after-biaozhu').remove();
        $("#iframe-tip" + number).hide();
    };

    /*var checkHasImg = function(cloneContent){
        var checkBox = $('#check-box-linshi');
        if(!checkBox[0]){
            checkBox = $('<div id="check-box-linshi" style="display:none;"></div>').appendTo('body');
        }
        checkBox.empty().append(cloneContent);
        return !!checkBox.find('img').length;
    };*/
    var checkHasImg = function(cloneContent){
        var checkBox = $('<div style="display:none;"></div>').append(cloneContent);
        var length = !!checkBox.find('img').length;
        checkBox.remove();
        return length;
    };

    var parentsHasA = function(target){
        return !!$(target).closest('a').length;
    };

    //对编辑中的批注前后两个标签的处理
    $(myoEditorWindow.document).on('click', '.before-biaozhu-ok, .after-biaozhu-ok', function(event, only){
        var me = $(this),
            isBefore = me.hasClass('before-biaozhu-ok'),
            rand = me.attr('rand'),
            body = $(myoEditorWindow.document.body),
            other = body.find('.'+ (isBefore ? 'after' : 'before') +'-biaozhu-ok[rand="'+ rand +'"]');
        if(event.type == 'click'){
            window['oEdit' + number].setFocus();
            var oSel = myoEditorWindow.getSelection();
            var range = myoEditorWindow.document.createRange();
            if(isBefore){
                range.setStartAfter(this);
                range.setEndBefore(other[0]);
            }else{
                range.setStartAfter(other[0]);
                range.setEndBefore(this);
            }
            oSel.removeAllRanges();
            oSel.addRange(range);
            if(!only){
                biaozhuSlide();
            }
        }else{
            other[0] && other.remove();
            me.remove();
        }
    });

    //对编辑器中的鼠标按下事件处理  包括设描述，关键字，批注，超链接等等的处理
    $(myoEditorWindow.document).bind("mousedown", function(event){
        $(this).data('start', [event.pageX, event.pageY]);
        if(event.which > 1){
            return;
        }
        if($('.iframe-tip-href-content').is(':visible')){
            $('.iframe-tip-href-content').triggerHandler('addlink.tip');
            return;
        }

        $(myoEditorWindow.document).find('a').filter(function(){
            return $(this).html() == '';
        }).remove();
        //setTimeout(function(){
            cleanBiaozhu();
            biaozhuSlide(true);
            var close = [];
            if(event.target && $(event.target).is('img')){
            }else{
                //myoEditorWindow.getSelection().removeAllRanges();
                close.push('pageslide');
                close.push('refer-info');
            }
            $("#iframe-tip" + number).hide();
            window["slideManage" + number].closeMore(close);
        //}, 10);
    }).bind("mouseup", function(event){
        $(this).data('end', [event.pageX, event.pageY]);
        if(event.which > 1){
            return;
        }

        if(event.target && $(event.target).is('img')) return;
        var doc = myoEditorWindow.document;
        var body = $(doc.body);
        var oSel = myoEditorWindow.getSelection();
        var range = oSel.getRangeAt(0);
        var string = $.trim(range.toString()), len = string.length;
        var hasA = parentsHasA(range.startContainer);
        if(!hasA){
            if(!len || $(this).triggerHandler('_checkIsClick')){
                return;
            }
        }
        var cloneRange = range.cloneRange();
        var ec = range.endContainer,
            et = range.endOffset;
        range.setStart(ec, et);
        range.setEnd(ec, et);
        var aspan = range.createContextualFragment('<span class="after-biaozhu"><span class="biaozhu"></span></span>');
        var aspand = aspan.firstChild;
        range.insertNode(aspan);
        oSel.addRange(range);

        oSel.removeAllRanges();
        oSel.addRange(cloneRange);
        cloneRange = oSel.getRangeAt(0);
        var sc = cloneRange.startContainer,
            st = cloneRange.startOffset;
        cloneRange.setStart(sc, st);
        cloneRange.setEnd(sc, st);
        var bspan = cloneRange.createContextualFragment('<span class="before-biaozhu"><span class="biaozhu"></span></span>');
        var bspand = bspan.firstChild;
        cloneRange.insertNode(bspan);
        oSel.addRange(cloneRange);

        range = doc.createRange();
        range.setStartAfter(bspand);
        range.setEndBefore(aspand);
        oSel.removeAllRanges();
        oSel.addRange(range);

        //var px = event.pageX, py = event.pageY;
        bspan = body.find('.before-biaozhu');
        aspan = body.find('.after-biaozhu');

        var cloneContent = oSel.getRangeAt(0).cloneContents();
        if(checkHasImg(cloneContent)){
            bspan.add(aspan).remove();
            return;
        }

        bspan.add(aspan).show();
        bspanOffset = bspan.offset();
        var px = bspanOffset.left, py = bspanOffset.top;
        var offset = $("#idContentoEdit" + number).offset();
        var ix = offset.left, iy = offset.top;
        var st = $(this).scrollTop();
        var nleft = ix + px, ntop = iy + py - st;

        var tip = $("#iframe-tip" + number);
        if(!tip[0]){
            tip = $('<div class="iframe-tip" id="iframe-tip'+ number +'">'+
                '<div class="iframe-tip-left">' +
                '<div class="iframe-tip-right">'+
                    '<div class="iframe-tip-content">'+
                    '</div>'+
                '</div>'+
                '</div>'+
                '<div class="iframe-tip-href-box" style="display:none;">' +
                    '<div class="iframe-tip-href-left">'+
                        '<div class="iframe-tip-href-right">' +
                            '<div class="iframe-tip-href-content">' +
                                '<span class="iframe-tip-href-close"></span>' +
                                '<div class="iframe-tip-href-title">链接地址：</div><input type="text" class="text" style="width:250px;"/>'+
                                '<a class="iframe-tip-href-delete" href="javascript:;">去除链接</a>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '</div>').appendTo("body");
            tip.on("click", function(event){
                var target = event.target;
                if(target){
                    target = $(target);
                    var oSel = myoEditorWindow.getSelection();
                    var range = oSel.getRangeAt(0);
                    var cloneRange = range.cloneRange();
                    var tmp = $.trim(range.toString());
                    if(target.hasClass('iframe-tip-title')){
                        $("#title")[0] && $("#title").val(tmp).attr('_value', tmp).attr('title', tmp);
                        //range.deleteContents();
                        cleanBiaozhu();
                    }else if(target.hasClass('iframe-tip-des')){
                        $("#brief")[0] && $("#brief").val(tmp).attr('_value', tmp);
                        $("#brief-clone")[0] && $("#brief-clone").html(tmp);
                        cleanBiaozhu();
                    }else if(target.hasClass('iframe-tip-keyword')){
                        $('#keywords-box')[0] && $('#keywords-box').trigger('append', [tmp]);
                        cleanBiaozhu();
                    }else if(target.hasClass('iframe-tip-href')){
                        var bspan = body.find('.before-biaozhu');
                        var aspan = body.find('.after-biaozhu');
                        var bparent = bspan.closest('a');
                        if(bparent[0]){
                            bspan.insertBefore(bparent);
                            cloneRange.setStartAfter(bspan[0]);
                        }
                        var aparent = aspan.closest('a');
                        if(aparent[0]){
                            if(bparent[0] === aparent[0]){
                                aspan.insertAfter(aparent);
                            }else{
                                aspan.insertBefore(aparent);
                            }
                            cloneRange.setEndBefore(aspan[0]);
                        }
                        oSel.removeAllRanges();
                        oSel.addRange(cloneRange);

                        var cloneContent = cloneRange.cloneContents();
                        if(checkHasImg(cloneContent)){
                            bspan.add(aspan).remove();
                            oSel.removeAllRanges();
                            tip.hide();
                            return;
                        }
                        var content = target.closest('.iframe-tip-left').hide().next().show();
                        var href = bparent.attr('href') || '';
                        if(!href/* || href.indexOf('http://') == -1 || href.indexOf('https://') == -1*/){
                            href = 'http://' + href;
                        }
                        var input = content.find('input').val(href);
                        if(href){
                            content.find('.iframe-tip-href-delete').show();
                        }
                        input[0].focus();
                    }else if(target.hasClass('iframe-tip-biaozhu')){
                        var rand = +new Date();
                        var id = gAdmin['admin_id'], name = gAdmin['admin_user'] || '我';
                        var before = window['globalEditorConfig']['before'];
                        var after = window['globalEditorConfig']['after'];
                        var data = {
                            'id' : id,
                            'rand' : rand,
                            'name' : name
                        };
                        var myreplace = function(which){
                            return which.replace(/{([a-zA-Z0-9]+)}/g, function(all, match){
                                return data[match] || '';
                            });
                        };
                        before = myreplace(before);
                        after = myreplace(after);
                        body.find('.before-biaozhu').replaceWith(before);
                        body.find('.after-biaozhu').replaceWith(after);
                        $(this).hide();
                        biaozhuSlide();
                    }else if(target.hasClass('iframe-tip-href-close')){
                        target.closest('.iframe-tip-href-box').hide().prev().show();
                        target.closest('.iframe-tip').trigger('_hide');
                    }else if(target.hasClass('iframe-tip-href-delete')){
                        target.closest('.iframe-tip').find('.iframe-tip-href-content').triggerHandler('removelink.tip');
                    }
                }
            });

            var queueName = 'iframe-tip';
            tip.on({
                '_start' : function(){
                    var me = $(this);
                    $(this).data('timer', setTimeout(function(){
                        me.triggerHandler('_hide');
                    }, 2500));
                },
                '_stop' : function(){
                    clearTimeout($(this).data('timer'));
                },
                '_show' : function(){
                    $(this).stop(queueName, true).css('opacity', 1).show();
                    $(this).triggerHandler('_stop');
                    $(this).triggerHandler('_start');
                },
                '_hide' : function(){
                    $(this).stop(queueName, true);
                    if($(this).find('.iframe-tip-href-box').is(':visible')) return;
                    $(this).animate({
                        opacity : 0
                    },{
                        queue : queueName,
                        duration : 500
                    }).queue(queueName, function(next){
                        $(this).hide();
                        $(myoEditorWindow.document).find('.before-biaozhu, .after-biaozhu').remove();
                        next();
                    }).dequeue(queueName);
                },
                'mouseenter' : function(){
                    $(this).triggerHandler('_stop');
                    $(this).stop(queueName, true).css('opacity', 1).show();
                },
                'mouseleave' : function(){
                    $(this).triggerHandler('_start');
                }
            });
        }
        tip.find('.iframe-tip-href-content').off('.tip').on({
            'addlink.tip' : function(){
                mysaveForUndo();  //保存撤销
                var url = $.trim($(this).find('input').val());
                if(url && url != 'http://'){
                    /*if(url.indexOf('http://') == -1 && url.indexOf('https://') == -1){
                        url = 'http://' + url;
                    }*/
                    myoEditorWindow.focus();
                    myoEditorWindow.document.execCommand("CreateLink", false, url);

                    /*var oSel = myoEditorWindow.getSelection();
                    var range = oSel.getRangeAt(0);
                    var text = range.toString();
                    var body = $(myoEditorWindow.document.body);
                    var bspan = body.find('.before-biaozhu');
                    var aspan = body.find('.after-biaozhu');*/

                }
                $(this).triggerHandler('clean.tip');
            },
            'removelink.tip' : function(){
                mysaveForUndo();  //保存撤销
                //myoEditorWindow.document.execCommand("Unlink");
                var body = $(myoEditorWindow.document.body);
                var bspan = body.find('.before-biaozhu');
                var aspan = body.find('.after-biaozhu');
                var next = bspan.next();
                var prev = aspan.prev();
                if(next[0] == prev[0]){
                    next.replaceWith(next.html());
                }
                $(this).triggerHandler('clean.tip');
            },
            'clean.tip' : function(){
                var bspan = body.find('.before-biaozhu');
                bspan.next().attr('target', '_blank');
                $(this).closest('.iframe-tip').hide();
                body.find('.before-biaozhu, .after-biaozhu').remove();
            }
        });
        tip.css({
            left : (nleft + 10) + "px",
            top : (ntop + 20) + "px"
        });
        var items = {
            title : '<span class="iframe-tip-title iframe-tip-item">设标题</span>',
            des : '<span class="iframe-tip-des iframe-tip-item">设描述</span>',
            href : '<span class="iframe-tip-item iframe-tip-href">设链接</span>',
            keyword : '<span class="iframe-tip-keyword iframe-tip-item">设关键字</span>',
            biaozhu : '<span class="iframe-tip-biaozhu iframe-tip-item">设批注</span>',
            fen : '<span class="iframe-tip-fen"></span>'
        };
        var content = tip.find('.iframe-tip-content');
        var html = '';
        if(len <= 1){
            html = items['href'] + items['fen'] + items['biaozhu'];
        }/*else if(len >= 2 && len <= 5){
            html = items['keyword'] + items['fen'] + items['href'] + items['fen'] + items['biaozhu'];
        }else if(len >= 6){
        }*/else if(len >= 2){
            if(len <= 10){
                html = items['keyword'] + items['fen'] + items['title'] + items['fen'] + items['des']  + items['fen'] + items['href'] + items['fen'] + items['biaozhu'];
            }else if(len <= 30){
                html = items['title'] + items['fen'] + items['des']  + items['fen'] + items['href'] + items['fen'] + items['biaozhu'];
            }else if(len <= 200){
                html = items['des'] + items['fen'] + items['href'] + items['fen'] + items['biaozhu'];
            }else{
                html = items['biaozhu'];
            }
        }
        content.html(html);
        tip.find('.iframe-tip-left').show().next().hide();
        tip.find('.iframe-tip-href-delete').hide();

        tip.trigger('_show');
        if(len == 0){
            tip.find('.iframe-tip-href').trigger('click');
        }
    }).on('_checkIsClick', function(){
        var start = $(this).data('start');
        var end = $(this).data('end');
        if(start[0] != end[0] || start[1] != end[1]){
            return false;
        }
        return true;
    }).on('keydown', function(event){
        var keyCode = event.keyCode;
        if(keyCode == 8){
            $(this).find('.before-biaozhu, .after-biaozhu').remove();
        }
    });

    //编辑器中对分页标签的点击处理
    /*$(myoEditorWindow.document).on('mouseup', '.pagebg', function(event){
        var index = $(myoEditorWindow.document).find('.pagebg').index(this) + 1;
        window["cpage" + number].which(index);
        myoEditorWindow.getSelection().removeAllRanges();
    });*/

    //编辑器的剪切粘贴键盘弹起处理
    $(myoEditorWindow.document).bind("cut paste keyup", function(event){
        cleanBiaozhu();
        //event.keyCode == 13 && $(myoEditorWindow).scrollTop($(myoEditorWindow).scrollTop() + 23);
        event.keyCode == 13 && $.proxy(function(){
            var top = $(this).scrollTop();
            $(this).scrollTop(top + 14 * 1.5);
        }, $('body'))();
        window["slideManage" + number].close();
        window['statistics' + number].fontNumber();

        myPageCheckAndRefresh();
    });

    //模拟滚动条事件处理
    /*$(window).mousewheel(function(){
        var curT = $(myoEditorWindow).scrollTop(),
            wh = parseInt($(myoEditorWindow.document).height() / 120, 10);
        if(this.D > 0){
            curT -= wh;
        }else{
            curT += wh;
        }
        $(myoEditorWindow).scrollTop(curT);
    });*/


    //视频和图集等缩印图统一插入处理
    window['globalSlideInsertHtml' + number] = function(type, data){
        var editor = window['oEdit' + number],
            editorDom = $('#idContent' + editor.oName),
            html;

        if(type == 'attach'){
            mysaveForUndo();  //保存撤销
            editor.insertHTML(data);
            myPageCheckAndRefresh();
            return;
        }

        var imgRand = + new Date() + '' + parseInt(Math.random() * 1000);
        switch(type){
            case 'image' :
                html = '<img class="image" rand="' + imgRand + '" src="'+ data['src'] +'" oldwidth="'+ data['oldwidth'] +'" imageid="'+ data['id'] +'"/>';
                break;
            case 'refer' :
                html = '<img class="image-refer" rand="' + imgRand + '" src="'+ data +'"/>';
                break;
        }
        editor.applyJustifyCenter();
        mysaveForUndo();  //保存撤销
        editor.insertHTML(html);
        myPageCheckAndRefresh();
        var $ele = editorDom.contents().find('img[rand="' + imgRand + '"]').removeAttr('rand');
        $ele.parent().css('text-indent', '0');
    }

    //视频和图集等缩印图统一删除处理
    window['globalSlideDeleteHtml' + number] = function(type, data){
        var types = {
            image : 'image',
            refer : 'image-refer'
        };
        mysaveForUndo();  //保存撤销
        $(myoEditorWindow.document).find('img.'+ types[type] + '[src="'+ data +'"]').remove();
        myPageCheckAndRefresh();
    }

    //编辑器里面的视频和图集等缩印图统一点击处理
    $(myoEditorWindow.document).on('click', '.image-refer', function(){
        var slideManage = window['slideManage' + number];
        var name = 'refer-info';
        if(!slideManage.has(name)){
            var slide = new SlideClass(number, name);
            slideManage.add(name, slide);
            new ReferInfoEvent(number, slide);
        }
        slideManage.openOne(name, $(this).attr('src'));
    });


    (function(){
        //编辑器里面的图片点击处理
        var slide = new SlideClass(number, "image-info");
        slide.slide.hide();
        window["slideManage" + number].add("image-info", slide);
        var self = new ImageInfoEvent(number, slide, window['globalEditorConfig']['path'] + 'icons/slide/');
        window['EditorImage' + number].imageInfoEvent = self;

        var body = $(myoEditorWindow.document.body);
        if(!body.data('init-image-info')){

            var imageTipTimer = null;
            $(myoEditorWindow).on('scroll.image-tip', function(){
                //$('#image-tip-box' + number).trigger('close');
                imageTipTimer && clearTimeout(imageTipTimer);
                var imageTip = $('#image-tip-box' + number).hide();
                if(imageTip[0] && imageTip.data('current-image')){
                    imageTipTimer = setTimeout(function(){
                        positionTip();
                    }, 100);
                }
            });

            function clickImageIn(target){
                var info = {
                    id : target.attr('imageid'),
                    alt : target.attr('alt'),
                    title : target.attr('title'),
                    width : parseInt(target.css('width'), 10),
                    height : parseInt(target.css('height'), 10),
                    oldwidth : target.attr('oldwidth')
                };
                if(target.parent().is('a')) {
                    info['href'] = target.parent().attr('href');
                }
                if(target.css('float') == 'left' || target.css('float') == 'right'){
                    info['position'] = target.css('float');
                }else{
                    if(target.css('display') == 'block'){
                        info['position'] = 'middle';
                    }
                }
                info['_style'] = target.attr('_style') || 'none';
                info['margin'] = {
                    top : parseInt(target.css('margin-top'), 10),
                    right : parseInt(target.css('margin-right'), 10),
                    bottom : parseInt(target.css('margin-bottom'), 10),
                    left : parseInt(target.css('margin-left'), 10)
                };
                var classImg = $('#edit-slide-image' + number).find('img.image[imageid="'+ info['id'] +'"]');
                if(classImg[0]){
                    info['path'] = classImg.attr('path');
                    info['dir'] = classImg.attr('dir');
                    info['filename'] = classImg.attr('filename');
                }else{
                    info['src'] = target.attr('src');
                }

                return info;
            }

            function clickImageOut(target){
                var info = {
                    title : target.attr('title'),
                    width : parseInt(target.css('width'), 0),
                    height : parseInt(target.css('height'), 0),
                    src : target.attr('src')
                };
                info['oldwidth'] = info['width'];
                if(target.parent().is('a')) {
                    info['href'] = target.parent().attr('href');
                }
                if(target.css('float') == 'left' || target.css('float') == 'right'){
                    info['position'] = target.css('float');
                }else{
                    if(target.css('display') == 'block'){
                        info['position'] = 'middle';
                    }
                }
                info['_style'] = target.attr('_style') || 'none';
                info['margin'] = {
                    top : parseInt(target.css('margin-top'), 10),
                    right : parseInt(target.css('margin-right'), 10),
                    bottom : parseInt(target.css('margin-bottom'), 10),
                    left : parseInt(target.css('margin-left'), 10)
                };
                return info;
            }

            body.on('click', function(event){
                var target = event.target;
                if(target){
                    target = $(target);
                    if(target.is('img') && !target.hasClass('pagebg') && !target.hasClass('before-biaozhu-ok') && !target.hasClass('after-biaozhu-ok') && !target.hasClass('image-refer')){
                        if(target.hasClass('image')){
                            self.set(clickImageIn(target));
                        }else{
                            self.set(clickImageOut(target));
                        }
                        $('#image-tip-box'+ number).data('current-image', target).show();
                        return;
                    }
                }
                $('#image-tip-box'+ number).data('current-image', null).hide();
                window['slideManage' + number].closeOne('image-info');
            });

            body.on('click', 'img', function(event, only){
                var imageTip = $('#image-tip-box' + number);
                if(!only && imageTip.data('current-image') == $(this)){
                    return;
                }
                var me = $(this);
                if(!imageTip[0]){
                    imageTip = createTipBox();
                }

                imageTip.trigger('init', [(me.attr('imageid') ? true : false), (me.attr('src').indexOf('http://') == 0 ? true : false)]);
                positionTip(me, imageTip);
            });

            function positionTip(me, imageTip){
                myPageCheckAndRefresh();

                !imageTip && (imageTip = $('#image-tip-box' + number));
                !me && (me = imageTip.data('current-image'));
                var moffset = me.offset();
                var mleft = moffset.left;
                var mtop = moffset.top;
                var mwidth = parseInt(me.width());
                var mheight = parseInt(me.height());
                var offset = $("#idContentoEdit" + number).offset();
                var ileft = offset.left;
                var itop = offset.top;
                var stop = body.scrollTop();
                var winHeight = $(myoEditorWindow).height();

                if(mtop + mheight < stop || mtop > stop + winHeight){
                    imageTip.hide();
                    return;
                }

                var disTop = mtop - stop;
                var tipTop = itop + disTop;

                imageTip.show().css({
                    top : tipTop - 1 + 'px',
                    left : ileft + mleft - 1 + (mwidth - 150) + 'px',
                    width : 150 + 'px',
                    height : 0 + 'px',
                    border : 'none'
                }).find('.image-tip-option').css({
                    top : ((disTop < 0) ? -disTop : 10) + 'px'
                });
            }

            function createTipBox(){
                var box = $(
                    '<div class="image-tip-box" id="image-tip-box'+ number +'">'+
                        '<div class="image-tip-option" id="image-tip-option'+ number +'">'+
                        '<span class="image-tip-left"></span>'+
                        '<span class="image-tip-right"></span>'+
                        '<span class="image-tip-local" style="color:red;">本地化</span>'+
                        '<span class="image-tip-change">换图</span>'+
                        '<span class="image-tip-delete">去除</span>'+
                        '</div>' +
                        '</div>'
                ).appendTo('body');

                box.on('click', '.image-tip-left, .image-tip-right', function(){
                    var me = $(this);
                    var parent = me.closest('.image-tip-box');
                    if(parent.data('loading')){
                        return;
                    }
                    var target = parent.data('current-image');
                    if(target && target[0]){
                        var direction = me.hasClass('image-tip-left') ? -90 : 90;
                        var imageid = target.attr('imageid');
                        me.html('<img src="' + RESOURCE_URL + 'loading2.gif" width="14"/>').addClass('image-tip-loading');
                        parent.data('loading', true);
                        window['EditorImage' + number].event.rotate(imageid, direction, function(){
                            me.html('').removeClass('image-tip-loading').trigger('resize');
                            parent.data('loading', null);
                        });
                        return;
                    }
                });

                box.on('click', '.image-tip-local', function(){
                    var me = $(this);
                    var parent = me.closest('.image-tip-box');
                    if(parent.data('loading')){
                        return;
                    }
                    var target = parent.data('current-image');
                    if(target && target[0]){
                        var src = target.attr('src');
                        me.append('<img src="' + RESOURCE_URL + 'loading2.gif" width="14"/>').addClass('image-tip-loading');
                        parent.data('loading', true);
                        window['EditorLocalImage' + number](src, function(){
                            me.find('img').remove();
                            me.removeClass('image-tip-loading').trigger('resize');
                            parent.data('loading', null);
                            target.trigger('click');
                            self.slide.state() && (self.set(clickImageIn(target)));
                        });
                    }
                });

                box.on('click', '.image-tip-change', function(){
                    if($(this).closest('.image-tip-box').data('loading')){
                        return;
                    }
                    window['slideManage' + number].openOne('image');
                });

                box.on('click', '.image-tip-delete', function(){
                    var parent = $(this).closest('.image-tip-box');
                    if(parent.data('loading')){
                        return;
                    }
                    var target = parent.data('current-image');
                    mysaveForUndo();  //保存撤销
                    target && target[0] && target.remove();
                    parent.hide();
                    window['slideManage' + number].closeOne('image-info');
                    myPageCheckAndRefresh();
                });

                box.on('close', function(){
                    if($(this).is(':visible')){
                        $(this).hide().data('current-image', null);
                        window['slideManage' + number].closeOne('image-info');
                    }
                });

                box.on('init', function(event, state, http){
                    $(this).find('.image-tip-left, .image-tip-right')[state ? 'show' : 'hide']();
                    $(this).find('.image-tip-local')[(!state && http) ? 'show' : 'hide']();
                });

                box.on('resize.image-tip', function(event){
                    var target = $(this).data('current-image');
                    if(target){
                        positionTip(target, $(this));
                    }
                });

                box.on('click', function(event){
                    if(event.target != this){
                        return false;
                    }
                    var target = $(this).data('current-image');
                    target && target.trigger('click');
                });

                return box;
            }

            body.data('init-image-info', true);
        }
    })();
}

(function($){
    $.fn.textareaAuto = function(){
        return this.autoResize({
            animate : false,
            extraSpace : 0
        });
    }
    return;
    if($.fn.textareaAuto){
        return;
    }
    var height = function(){
        var hidden = $('#hidden-oninput-page'),
            val = $(this).val();
        var ptop = parseInt($(this).css('padding-top'), 10);
        var pbottom = parseInt($(this).css('padding-bottom'), 10);
        var pleft = parseInt($(this).css('padding-left'), 10);
        var pright = parseInt($(this).css('padding-right'), 10);
        if(!hidden[0]){
            hidden = $('<textarea id="hidden-oninput-page"></textarea>').appendTo('body');

            var constWidth = 100;
            hidden.width(constWidth).val('测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试');
            hidden.css({
                position : 'absolute',
                left : '-1000px',
                left : '0',
                top : 0,
                'padding-top' : ptop + 'px',
                'padding-left' : pleft + 'px',
                'padding-right' : pright + 'px',
                'padding-bottom' : pbottom + 'px',
                height : $(this).height() + 'px',
                'font-size' : $(this).css('font-size'),
                'line-height' : $(this).css('line-height'),
                overflow : 'hidden'
            });
            var scrollWidth = hidden.attr('scrollWidth');
            var scrollBarWidth = constWidth - scrollWidth;alert(scrollWidth);
            var width = $(this).width() + scrollBarWidth;
            hidden.width(width);
        }
        hidden.val(val);
        var newHeight = $.browser.mozilla ? hidden[0].scrollHeight : (hidden[0].scrollHeight - ptop - pbottom);alert(newHeight);
        $(this).css({
            height : newHeight + 'px'
        });
    };
    $.fn.textareaAuto = function(){
        return this.each(function(){
            $(this).on('focus propertychange blur', height);
            this.addEventListener('input', height, false);
        });
    };
})(jQuery);

(function($){
    function isInSlide(target){
        var slide = $('.edit-slide:visible')[0];
        return $(target).hasClass('.edit-slide') || (slide && $.contains(slide, target));
    }

    $.fn.extend({
        mousewheel:function(Func){
            return this.each(function(){
                var _self = this;
                _self.D = 0;//滚动方向
                if($.browser.msie || $.browser.safari){
                    _self.onmousewheel=function(e){
                        if(isInSlide(event.srcElement || e.target)) return;
                        _self.D = event.wheelDelta;
                        event.returnValue = false;
                        Func && Func.call(_self);
                    }
                }else{
                    _self.addEventListener("DOMMouseScroll", function(e){
                        if(isInSlide(e.target)) return;
                        _self.D = e.detail > 0 ? -1 : 1;
                        e.preventDefault();
                        Func && Func.call(_self);
                    },false);
                }
            });
        }
    });
})(jQuery);