jQuery(function($){
    if(!window['configUrl']){
        alert('没有配置一些ajax接口');
        return;
    }

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
        	if(this.cache[type]){
        		 delete this.cache[type][id];
        	}
        },
        empty : function(){
            this.cache = {};
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
            hg_resize_nodeFrame();
        }
    });
    box.triggerHandler('size.column');

    function checkEditing(lujin){
        var edit = $('.column-edit');
        if(edit[0]){
            if(!$('.column-li-visibility')[0]){
                $('#sort-'+ edit.data('data')['id']).addClass('column-li-visibility');
            }
            if(lujin){
                lujin = [];
                $('.column-li-selected .column-name').each(function(){
                    lujin.push($(this).text());
                });
                lujin.push($('.column-edit input').val());
                $('.column-now-parents').html(lujin.join('&nbsp;&nbsp;&gt;&nbsp;&nbsp;')).data('lujin', lujin);
            }
        }
    }

    box.on('click', '.column-ul li', function(event){
        if($(this).hasClass('column-li-visibility')){
            return;
        }
        if($(event.target).is('.column-edit-button') || $(event.target).is('.column-delete-button')){
            return;
        }
        var className = 'column-li-selected';
        var hasChild = !!$(this).find('.column-next-button')[0];
        var nextTpl = $('#column-tpl-child').val();
        $(this).closest('.column-each').nextAll('.column-each').remove();
        if($(this).hasClass(className)){
            $(this).removeClass(className);
        }else{
            $(this).closest('.column-each').find('.' + className).removeClass(className);
            $(this).addClass(className);
            if(hasChild){
                var id = $(this).attr('_id');
                var next = $(nextTpl).appendTo('.column-inner-box');
                var oldCache = cache.get('column', id);
                if(oldCache){
                    next.find('.column-ul').html(oldCache);
                    $('.column-box').triggerHandler('size.column');
                    checkEditing();
                }else{
                    $.getJSON(replaceTpl(window['configUrl']['ajax'], {
                        fid : id
                    })).success(function(json){
                        if(json){
                            var lis = ulHtml(json);
                            cache.add('column', id, lis);
                            next.find('.column-ul').html(lis);
                            $('.column-box').triggerHandler('size.column');
                            checkEditing();
                        }
                    });
                }
            }
        }
        $(nextTpl).appendTo('.column-inner-box').find('li').remove();
        $('.column-box').triggerHandler('size.column');
        checkEditing(true);
    });

    function rootAjax(callback){
        $('.column-inner-box').empty();
        var nextTpl = $('#column-tpl-child').val();
        $(nextTpl + nextTpl).appendTo('.column-inner-box');
        var eachs = $('.column-each');
        eachs.eq(1).find('li').remove();
        $.getJSON(replaceTpl(window['configUrl']['ajax'], {
            fid : 0
        })).success(function(json){
            if(json){
                var lis = ulHtml(json);
                cache.add('column', 0, lis);
                eachs.eq(0).find('.column-ul').html(lis);
                $('.column-box').triggerHandler('size.column');

                callback && callback();
            }
        });
    }

    function ulHtml(json){
        var tplLi = $('#column-tpl-li').val();
        var tplNext = $('#column-tpl-next').val();
        var tplDel = $('#column-tpl-delete').val();
        var lis = [];
        $.each(json, function(i, n){
            lis.push(replaceTpl(tplLi, {
                name : n['name'],
                id : n['id'],
                fid : n['fid'],
                next : !(+n['is_last']) ? tplNext : '',
                del : (+n['is_last']) ? tplDel : ''
            }));
        });
        lis = lis.join('');
        return lis;
    }


    box.on('click', '.column-add-box', function(){
        var each = $(this).closest('.column-each');
        if(each.find('.column-input')[0]){
            return;
        }
        $('.column-input').remove();
        var len = each.prevAll('.column-each').length;
        if(len){
            if(!each.prev().find('.column-li-selected').length){
                errorTip(each.prev(), '请在这一列中选择父级分类...', [5, -5, 3, -3, 1, -1]);
                return;
            }

        }
        $($('#column-tpl-add').val()).appendTo($(this).closest('.column-each')).find('input').focus();
        $('.column-box').triggerHandler('size.column');
        setZIndex(true);

        createMask();

        parent.$('html, body').scrollTop(10000);

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

    box.on('click', '.column-submit', function(){
        var val = $.trim($(this).prev().val());
        if(!val){
            errorTip($(this), '分类名称不能为空！');
            return;
        }

        var me = $(this);
        var fid = 0;
        var each = me.closest('.column-each');
        if(each.prevAll('.column-each').length){
            var parent = each.prev().find('.column-li-selected');
            if(!parent[0]){
                errorTip(each.prev(), '请在这一列中选择父级分类...', [5, -5, 3, -3, 1, -1]);
                return;
            }
            fid = parent.attr('_id');
        }

        var input = me.closest('.column-input').addClass('column-input-transform');
        setTimeout(function(){
            $.post(
                window['configUrl']['create']+'&ajax=1',
                {name : val, fid : fid},
                function(data){
                    if(data){
                    	 if(data['callback']){
                     		eval(data['callback']);
                     	}else{
	                        data = data[0];
	                        me.closest('.column-each').find('.column-ul').append(replaceTpl($('#column-tpl-li').val(), {
	                            name : val,
	                            id : data['id'],
	                            fid : fid,
	                            next : '',
	                            'del' : $('#column-tpl-delete').val()
	                        }));
	                      //  $('.column-box').triggerHandler('size.column');
	                        var parent = $('#sort-'+ fid);
	                        if(!parent.find('.column-next-button')[0]){
	                            parent.find('.column-edit-button').after($('#column-tpl-next').val());
	                            parent.find('.column-delete-button').remove();
	                        }
	                        cache.remove('column', fid);
	                        var fidfid = parent.attr('_fid');
	                      
	                        if(fidfid){
	                            cache.remove('column', fidfid);
	                        }
                     	}
                    	input.remove();
                    	$('.column-mask').off('click').hide();
                    	$('.column-box').triggerHandler('size.column');
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

    box.on('click', '.column-edit-button', function(){
        var li = $(this).closest('li');
        var id = parseInt(li.attr('_id'), 10);
        var fid = parseInt(li.attr('_fid'), 10);
        var name = li.find('.column-name').text();
        var siblings = !!li.siblings().length;
        var html = $('.column-inner-box').html();
        var edit = $($('#column-tpl-edit').val()).appendTo('body').offset(li.offset());
        edit.find('input').val(name).focus();

        edit.data('data', {
            id : id,
            fid : fid,
            name : name,
            html : html,
            siblings : siblings
        });

        var lujin = [];
        var each = li.closest('.column-each')[0];
        $('.column-each').each(function(){
            if(this == each){
                return false;
            }
            lujin.push($(this).find('.column-li-selected .column-name').text());
        });
        lujin.push(li.find('.column-name').text());
        var lujinString = lujin.join('&nbsp;&nbsp;&gt;&nbsp;&nbsp;');
        edit.find('.column-old-parents').html(lujinString);
        edit.find('.column-now-parents').html(lujinString).data('lujin', lujin);

        createMask();

        $('.column-mask').off('click').on('click', function(){
            if($(this).hasClass('column-mask-off')){
                return;
            }
            var input = $('.column-edit input');
            var val = $.trim(input.val());
            var me = $(this);
            var cancel = function(){
                $('.column-edit').remove();
                $(document).off('keydown');
                me.off('click').hide();
                $('.column-box').triggerHandler('size.column');
            };
            if(val){
                jConfirm('是否提交？', '提示', function(result){
                    if(result){
                        $('.column-edit-submit').trigger('click');
                    }else{
                        cancel();
                    }
                }).position($('.column-edit'))
            }else{
                cancel();
            }
        });

        $(document).on('keydown', function(event){
            var keyCode = event.keyCode;
            if(keyCode == 13){
                $('.column-edit-submit').trigger('click');
            }
        });
    });

    $(document).on('keyup', '.column-edit input', function(){
        var now = $('.column-now-parents');
        var newParents = now.data('lujin');
        newParents[newParents.length - 1] = $(this).val();
        now.data('lujin', newParents).html(newParents.join('&nbsp;&nbsp;&gt;&nbsp;&nbsp;'));
    });


    $(document).on('click', '.column-edit-submit', function(){
        var val = $.trim($(this).prev().val());
        if(!val){
            errorTip($(this), '分类名称不能为空！');
            return;
        }
        var me = $(this);
        var edit = me.closest('.column-edit');
        var data = edit.data('data');
        var id = data.id;
        var fid = data.fid;
        var siblings = data.siblings;
        if(edit.data('change-parent')){
            var last = $('.column-li-selected:last');
            if(!last[0]){
                fid = 0;
            }else{
                fid = parseInt(last.attr('_id'), 10);
            }
        }

        var input = edit.addClass('column-edit-transform');
        setTimeout(function(){
            $.post(
                window['configUrl']['update']+'&ajax=1',
                {name : val, id : id, fid : fid},
                function(json){
                    if(json){
                    	if(json['callback']){
                     		eval(json['callback']);
                     	}else{
	                        json = json[0];
	                        if(fid != data.fid){
	                            cache.empty();
	                            if(fid == 0){
	                                rootAjax();
	                                return;
	                            }
	
	                            if(!data.siblings){
	                                var oldParent = $('#sort-'+ data.fid);
	                                if(oldParent[0]){
	                                    oldParent.find('.column-next-button').remove();
	                                    oldParent.find('.column-edit-button').after($('#column-tpl-delete').val());
	                                }
	                            }
	
	                            var parent = $('#sort-'+ fid);
	                            if(!parent.find('.column-next-button')[0]){
	                                parent.find('.column-edit-button').after($('#column-tpl-next').val());
	                                parent.find('.column-delete-button').remove();
	                            }
	
	                            parent.removeClass('column-li-selected').trigger('click');
	
	                            $('#sort-' + id).remove();
	
	                        }else{
	                            var li = $('#sort-'+ id);
	                            li.find('.column-name').text(val);
	                            fid = parseInt(li.attr('_fid'), 10);
	                            fid && cache.remove('column', fid);
	                        }
                     	}
                        edit.remove();
                        $('.column-mask').hide();
                        $('.column-outer-box').removeClass('column-outer-box-visibility');
                        $('.column-li-visibility').removeClass('column-li-visibility');
                    }else{
                        input.removeClass('column-input-transform');
                    }
                },'json'
            );
        }, 500);
    });

    $(document).on('click', '.column-select-button', function(){
        var offset = $('.column-outer-box').offset();

        var edit = $('.column-edit');
        edit.css('transition', 'all 0.5s');
        setTimeout(function(){
            edit.offset(offset);
        }, 10);

        edit.data('change-parent', true);

        var data = edit.data('data');
        var id = data.id;
        $('#sort-'+ id).addClass('column-li-visibility');

        $(this).hide();
        $('.column-select-cancel').show();

        $('.column-select-box').addClass('column-select-box-transition');
        setTimeout(function(){
            $('.column-select-parent').width(function(){
                return parseInt($('.column-outer-box').width(), 10) - 203;
            });
            $('.column-parents').show();
        }, 400);

        $('.column-outer-box').addClass('column-outer-box-visibility');

        $('.column-mask').addClass('column-mask-off');
    });

    $(document).on('click', '.column-select-cancel', function(){
        var edit = $('.column-edit');
        var data = edit.data('data');
        var id = data.id;
        var html = data.html;
        $('.column-inner-box').html(html);
        edit.offset($('#sort-'+ id).offset());

        edit.removeData('change-parent');

        $('.column-li-visibility').removeClass('column-li-visibility');

        $(this).hide();
        $('.column-select-button').show();
        $('.column-select-box').removeClass('column-select-box-transition');
        $('.column-select-parent').width(100);
        $('.column-parents').hide();
        $('.column-outer-box').removeClass('column-outer-box-visibility');

        $('.column-mask').removeClass('column-mask-off');
    });

    box.on('click', '.column-sort-button', function(){
        var each = $(this).closest('.column-each');
        var ul = each.find('.column-ul');
        var length = ul.find('li').length;
        if(length < 2){
            var mi = $(this);
            errorTip(mi, !length ? '这一列没有分类' : '只有一个分类无法排序');
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
                    window['configUrl']['sort'],
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


    $(document).on('click', '.column-delete-button', function(){
        var li = $(this).closest('li');
        var id = parseInt(li.attr('_id'), 10);
        var fid = parseInt(li.attr('_fid'), 10);
        var me = $(this).css('display', 'block');
        jConfirm('确定删除？', '删除提示', function(result){
            if(result){
                $.post(
                    window['configUrl']['delete'],
                    {id : id},
                    function( data ){
                    	var data = $.parseJSON( data );
                    	if( data && data.callback ){
                    		eval( data.callback );
                    		me.removeAttr('style');
                    	}else{
                    		cache.empty();
	                        if(fid && !li.siblings().length){
	                            var parent = $('#sort-'+ fid);
	                            li.closest('.column-each').remove();
	                            parent.find('.column-next-button').remove();
	                            parent.find('.column-edit-button').after($('#column-tpl-delete').val());
	                        }else{
	                            $('#sort-'+ id).remove();
	                        }
	                        $('.column-box').triggerHandler('size.column');
	                    }
                    }
                );
            }else{
                me.removeAttr('style');
            }
        }).position(this);

    });

    function errorTip(mi, title, rotate){
        var offset = mi.offset();
        if(title){
            $('.column-error').stop(true).remove();
            $('<div class="column-error">'+ title +'</div>').appendTo('body').css({
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
            return ( data[match] || data[match] ==0 ) ? data[match] :  '';
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