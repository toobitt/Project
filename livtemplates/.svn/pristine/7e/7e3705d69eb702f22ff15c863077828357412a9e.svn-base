jQuery(function($){
    var url = './run.php?mid='+ gMid +'&a=form&infrm=1&id=';
    var ajax = '_fetch_node.php?multi=page_manage&fid={{fid}}&ban=1&site_id={{siteid}}';
    var createUrl = './run.php?mid='+ gMid +'&a=create&ajax=1';
    var sortUrl = './run.php?mid='+ gMid +'&a=sort';
    var deleteUrl = './run.php?mid='+ gMid +'&a=delete&ajax=1&id=';

    var cache = {
        cache : {},
        add : function(type, id, html){
            if(!this.cache[type]){
                this.cache[type] = {};
            }
            this.cache[type][id] = html;
        },
        get : function(type, id){
            return this.cache[type] ? this.cache[type][id] : null;
        },
        remove : function(type, id){
            delete this.cache[type][id];
        }
    };

    var constWidth = parseInt($('.column-each:first').outerWidth(), 10);

    $('.column-inner-box').on({
        'move.column' : function(event, number){
            var maxNumber = $('.column-each').length - $(this).data('max');
            if(maxNumber < 0){
                $('.column-left-button, .column-right-button').hide();
                return;
            }
            number = number >= 0 ? number : maxNumber;
            $(this).data('current-number', number);
            var left = number * constWidth;
            $(this).css('left', -left + 'px');

            $('.column-left-button')[number == 0 ? 'hide' : 'show']();
            $('.column-right-button')[number == maxNumber ? 'hide' : 'show']();
        }
    });

    $('.column-left-button').on('click', function(){
        var innerBox = $('.column-inner-box');
        innerBox.triggerHandler('move.column', [innerBox.data('current-number') - 1]);
    });

    $('.column-right-button').on('click', function(){
        var innerBox = $('.column-inner-box');
        innerBox.triggerHandler('move.column', [innerBox.data('current-number') + 1]);
    });

    var resizeTimerId = null;
    $(window).on('resize', function(){
        var max = Math.floor( parseInt($(this).width(), 10) / constWidth );
        $('.column-inner-box').data('max', max);

        resizeTimerId && clearTimeout(resizeTimerId);
        resizeTimerId = setTimeout(function(){
            $('.column-inner-box').triggerHandler('move.column');
        }, 100);
    }).triggerHandler('resize');

    var box = $('.column-box').on({
        'size.column' : function(){
            var eachs = $('.column-each');
            var max = 0;
            $(this)
                .css('width', function(){
                    var width = eachs.length * constWidth;
                    return width - eachs.length;
                })
                .css('height', function(){
                    eachs.each(function(){
                        var eheight = 0;
                        $(this).children().each(function(){
                            eheight += parseInt($(this).outerHeight(true), 10);
                        });
                        if(eheight > max){
                            max = eheight;
                        }
                    });
                    return max - ($('.column-input').length ? 0 : 1);
                });
            eachs.height(max);
            $('.column-inner-box').triggerHandler('move.column');

            //$('body').height(max + 100);
            hg_resize_nodeFrame();
        },
        'shouqi.column' : function(event, id){
            var me = $(this);
            var height = me.height();
            me.attr('_height', height);
            setTimeout(function(){
                me.css('height', 0);
            }, 0);
            var li = $('.column-ul li[_id="'+ id +'"]');
            var each = li.closest('.column-each');
            var lujin = [];
            each.prevAll('.column-each').each(function(){
                lujin.push($(this).find('.column-li-selected .column-name').text());
            });
            var currentName = li.find('.column-name').text();
            lujin.push(currentName);
            var lujinObj = $('.column-lujin');
            lujinObj.find('.column-lujin-text').html(lujin.join('<span class="column-lujin-next"></span>'));
            lujinObj.removeAttr('style');
            $('.column-delete-button').attr('_delete_id', id)[!li.find('.column-next-button')[0] ? 'show' : 'hide']().find('span').text(currentName);
            lujinObj.css('margin-top', '-17px');

            setTimeout(function(){
                $('.column-lujin-cancel').show();
            }, 300);
        },
        'zhankai.column' : function(){

        }
    });
    box.triggerHandler('size.column');

    box.on({
        'mousedown' : function(){
            $(this).css('user-select', 'none');
        },
        'mouseup' : function(){
            $(this).css('user-select', 'auto');
        },
        'click' : function(event){
            if($(event.target).is('.column-edit-button')){
                return false;
            }

            var me = $(this);
            clearTimeout(me.data('timer'));
            me.data('timer', setTimeout(function(){
                var className = 'column-li-selected';
                var hasChild = !!me.find('.column-next-button')[0];
                var nextTpl = $('#column-tpl-child').val();
                me.closest('.column-each').nextAll('.column-each').remove();
                if(me.hasClass(className)){
                    me.removeClass(className);
                }else{
                    me.closest('.column-each').find('.' + className).removeClass(className);
                    me.addClass(className);
                    if(hasChild){
                        var id = me.attr('_id');
                        var next = $(nextTpl).appendTo('.column-inner-box');
                        var oldCache = cache.get('column', id);
                        if(oldCache){
                            next.find('.column-ul').html(oldCache);
                        }else{
                            $.getJSON(replaceTpl(ajax, {
                                siteid : $('#default-site').val(),
                                fid : id
                            })).success(function(json){
                                    if(json){
                                        var tplLi = $('#column-tpl-li').val();
                                        var tplNext = $('#column-tpl-next').val();
                                        var lis = [];
                                        $.each(json, function(i, n){
                                            lis.push(replaceTpl(tplLi, {
                                                name : n['name'],
                                                id : n['id'],
                                                fid : n['fid'],
                                                next : n['is_last'] ? tplNext : ''
                                            }));
                                        });
                                        lis = lis.join('');
                                        cache.add('column', id, lis);
                                        next.find('.column-ul').html(lis);
                                        $('.column-box').triggerHandler('size.column');
                                    }
                                });
                        }
                    }
                }
                $(nextTpl).appendTo('.column-inner-box').find('li').remove();
                $('.column-box').triggerHandler('size.column');
            }, 300));
        },
        'dblclick' : function(){
            clearTimeout($(this).data('timer'));
            $(this).find('.column-edit-button').trigger('click');
        }
    }, '.column-ul li');


    box.on('click', '.column-add-box', function(){
        var each = $(this).closest('.column-each');
        if(each.find('.column-input')[0]){
            return;
        }
        $('.column-input').remove();
        var len = each.prevAll('.column-each').length;
        if(len){
            if(!each.prev().find('.column-li-selected').length){
                errorTip(each.prev(), '请在这一列中选择父级页面...', [5, -5, 3, -3, 1, -1]);
                return;
            }
        }
        $($('#column-tpl-add').val()).appendTo($(this).closest('.column-each')).find('input').focus();
        $('.column-box').triggerHandler('size.column');
        setZIndex(true);

        createMask();

        $('.column-mask').off('click').on('click', function(){
            var input = $('.column-input input');
            var val = $.trim(input.val());
            var me = $(this);
            var cancel = function(){
                $('.column-input').remove();
                $(document).off('keydown');
                me.off('click').hide();
                $('.column-box').triggerHandler('size.column');
                setZIndex(false);
            };
            if(val){
                jConfirm('是否提交？', '提示', function(result){
                    if(result){
                        $('.column-submit').trigger('click');
                    }else{
                        cancel();
                    }
                }).position($('.column-input'))
            }else{
                cancel();
            }
        });

        $(document).on('keydown', function(event){
            var keyCode = event.keyCode;
            if(keyCode == 13){
                $('.column-submit').trigger('click');
            }
        });
    });

    box.on('click', '.column-edit-button', function(){
        var id = $(this).closest('li').attr('_id');
        $('#column-iframe-box').triggerHandler('open.column', [id]);
        $('.column-box').triggerHandler('shouqi.column', [id]);
    });

    box.on('click', '.column-submit', function(){
        var val = $.trim($(this).prev().val());
        if(!val){
            errorTip($(this), '页面名称不能为空！');
            return;
        }

        var me = $(this);
        var fid = 0;
        var each = me.closest('.column-each');
        if(each.prevAll('.column-each').length){
            var parent = each.prev().find('.column-li-selected');
            if(!parent[0]){
                errorTip(each.prev(), '请在这一列中选择父级页面...', [5, -5, 3, -3, 1, -1]);
                return;
            }
            fid = parent.attr('_id');
        }

        var input = me.closest('.column-input').addClass('column-input-transform');
        setTimeout(function(){
            $.post(
                createUrl,
                {name : val, siteid : $('#default-site').val(), fid : fid},
                function(data){
                	if( data['callback'] ){
                		eval( data['callback'] );
                		return;
                	}
                	data = data[0]['id'];
                    if(data && parseInt(data, 10) > 0){
                        me.closest('.column-each').find('.column-ul').append(replaceTpl($('#column-tpl-li').val(), {
                            name : val,
                            id : data,
                            fid : fid,
                            next : ''
                        }));
                        input.remove();
                        $('.column-box').triggerHandler('size.column');
                        var parent = $('.column-ul li[_id="'+ fid +'"]');
                        if(!parent.find('.column-next-button')[0]){
                            parent.find('.column-edit-button').after($('#column-tpl-next').val());
                        }
                        cache.remove('column', fid);
                        var fidfid = parent.attr('_fid');
                        if(fidfid){
                            cache.remove('column', fidfid);
                        }

                        $('.column-mask').off('click').hide();
                        $(document).off('keydown');
                        setZIndex(false);
                    }else{
                        input.removeClass('column-input-transform');
                    }
                },
                'json'
            );
        }, 500);

    });

    box.on('click', '.column-cancel', function(){
        $(this).closest('.column-input').remove();
        $('.column-box').triggerHandler('size.column');
    });

    box.on('click', '.column-sort-button', function(){
        var each = $(this).closest('.column-each');
        var ul = each.find('.column-ul');
        var length = ul.find('li').length;
        if(length < 2){
            var mi = $(this);
            errorTip(mi, !length ? '这一列没有页面' : '只有一个页面无法排序');
            return;
        }


        createMask();

        var oldSort = [];
        ul.find('li').each(function(){
            oldSort.push($(this).attr('_id'));
        });

        $('.column-mask').off('click').one('click', function(){

            var sortClone = $('.column-sort-clone');
            var postData = {}, newSort = [];
            sortClone.find('li').each(function(i, n){
                var id = $(this).attr('_id');
                postData[id] = i + 1;
                newSort.push(id);
            });

            if(newSort.join() == oldSort.join()){

            }else{
                var columnUl = $('.column-ul').eq(parseInt(sortClone.attr('index'), 10));
                $.each(newSort, function(i, n){
                    columnUl.append(columnUl.find('li[_id="'+ n +'"]'));
                });

                $.post(
                    sortUrl,
                    {sort : JSON.stringify(postData)}
                );
            }

            $('.column-mask, .column-sort-clone').hide();
            $('.column-sort-button-clone').remove();
        });


        var index = $('.column-ul').index(ul[0]);
        var offset = ul.offset();
        var clone = $('.column-sort-clone');
        if(!clone[0]){
            clone = $('<div class="column-sort-clone"></div>').appendTo('body');
            clone.sortable({
                placeholder: 'column-sort-light',
                items : 'li'
            });
            clone.disableSelection();
        }
        clone.show().css({
            left : offset.left + 'px',
            top : offset.top - 1 + 'px'
        });
        clone.attr('index', index).html(ul.clone());

        $(this).clone().addClass('column-sort-button-clone').appendTo('body').offset($(this).offset()).on('click', function(){
            $('.column-mask').triggerHandler('click');
        });

    });


    $('.column-lujin-cancel').on('click', function(){
        $($('#column-iframe')[0].contentWindow).off('unload');
        $('.column-box').triggerHandler('size.column');
        $('#column-iframe-box').hide();
        $('#column-iframe').removeAttr('src');
        $('.column-lujin').css({
            'height' : 0,
            'margin-top' : 0
        });
        $(this).hide();

        hg_resize_nodeFrame();
    });

    $('.column-delete-button').on('click', function(){
        var deleteId = $(this).attr('_delete_id');
        jConfirm('确定删除？', '删除提示', function(result){
            if(result){
                $.getJSON(deleteUrl + deleteId, function(data){
                	if( data['callback'] ){
                		eval( data['callback'] );
                		return;
                	}
                	/*if (data[0].error_code) {
                		jAlert(data[0].error_code, '提醒');
                		return;
                	}*/
                    $('.column-lujin-cancel').triggerHandler('click');

                    var li = $('.column-ul li[_id="'+ deleteId +'"]');
                    var fid = parseInt(li.attr('_fid'), 10), parent;
                    if(fid){
                        parent = $('.column-ul li[_id="'+ fid +'"]');
                        cache.remove('column', fid);
                        var fidfid = parseInt(parent.attr('_fid'), 10);
                        if(fidfid){
                            cache.remove('column', fidfid);
                        }
                    }
                    if(!li.siblings().length){
                        li.closest('.column-each').remove();
                        if(parent){
                            parent.find('.column-next-button').remove();
                        }
                    }else{
                        li.remove();
                    }


                    $('.column-box').triggerHandler('size.column');
                });
            }
        }).position(this);

    });

    $('#column-iframe-box').on({
        'open.column' : function(event, id){
            $('#column-iframe').one({
                'load' : function(){
                    if(this.contentWindow.jQuery){
                        var height = this.contentWindow.jQuery('body').outerHeight(true);
                        $('#column-iframe-box, #column-iframe').height(height);
                        $('#column-loading').hide();

                        $('.column-box').triggerHandler('shouqi.column', [id]);
                        hg_resize_nodeFrame();

                        var meWindow = this.contentWindow;
                        meWindow.jQuery('form').on('submit', function(){
                            return;
                            var win = $(meWindow);
                            var width = win.width();
                            var height = win.height();
                            $(this).closest('body').append('<div style="position:fixed;left:0;top:0;z-index:100000;background:#fff;opacity:0.8;width:'+ width +'px;height:'+ height +'px;line-height:'+ height +'px;color:green;font-size:20px;">提交中...</div>');
                        });

                        $(this.contentWindow).one('unload', function(){
                            $('.column-lujin-cancel').triggerHandler('click');
                        });
                    }
                }
            });

            $('#column-iframe-box, #column-loading, #column-iframe').show();
            $('#column-iframe').attr('src', url + id);
        }
    });

    function errorTip(mi, title, rotate){
        var offset = mi.offset();
        if(title){
            $('.column-error').stop(true).remove();
            $('<div class="column-error" style="position:absolute;z-index:100002;color:red;border:1px solid red;padding:2px 5px;background:#fff;">'+ title +'</div>').appendTo('body').css({
                top : offset.top - 30 + 'px',
                left : offset.left + 'px'
            }).delay(1000).animate({
                opacity : 0
            }, 2000, function(){
                $(this).remove();
            });
        }

        rotate = rotate || [20, -20, 10, -10, 5, -5, 'end'];
        if($.inArray('end', rotate) == -1){
            rotate.push('end');
        }
        $.each(rotate, function(i, n){
            if(n == 'end'){
                mi.queue('mt', function(next){
                    $(this).removeAttr('style');
                });
            }else{
                mi.queue('mt', function(next){
                    $(this).css('transform', 'rotate(' + n + 'deg)');
                    next();
                }).delay(50, 'mt');
            }
        });
        mi.dequeue('mt');

    }


    function replaceTpl(tpl, data){
        return tpl.replace(/{{([0-9a-zA-Z]+)}}/g, function(all, match){
            return data[match] || '';
        });
    }


    function createMask(){
        var mask = $('.column-mask');
        if(!mask[0]){
            mask = $('<div class="column-mask"></div>').appendTo('body');
        }
        var doc = $(document);
        mask.show().css({
            width : doc.width() + 'px',
            height : doc.height() + 'px'
        });
    }

    function setZIndex(state){
        if(state){
            $('.column-input').closest('.column-each').prevAll('.column-each').find('.column-li-selected').css('z-index', 10001);
        }else{
            $('.column-li-selected').css('z-index', 1);
        }

    }
});


function tip(str){
    var tip = $('#tip');
    if(!tip[0]){
        tip = $('<div id="tip"></div>').appendTo('body').css({
            position : 'absolute',
            top : 0,
            right : 0,
            border : '1px solid red',
            background : '#fff',
            color : '#000',
            'z-index' : 99999
        });
    }
    tip.html(function(){
        return $(this).html() + '<br/>' + str;
    })

}
function hg_open_fastinput(obj)
{
	 if($(obj).attr('checked'))
	 {
		$("#showsort").show();
	 }
	 else
	 {
		$("#showsort").hide();
	}
}
