$(function(){
    var fileUrl = './run.php?mid=' + gMid + '&a=getBackupInfo&channel_id={{channelid}}&status=1&offset={{offset}}&counts={{counts}}';
    var cunUrl = './run.php?mid=' + gMid + '&a=editStreamBeibo';

    $(window).on('resize', function(){
        $('.live').css({
            //width : $(this).width(),
            height : $(this).height()
        });
    }).trigger('resize');



    $('.item-mask').on({
        'click' :  function(event, only){
            var item = $(this).closest('.play-item');
            if(!item.attr('_url')){
                return;
            }
            var cname = 'yujian-select';
            if(item.hasClass(cname)) return;
            $('.' + cname).removeClass(cname);
            item.addClass(cname);
            $('.play-left').triggerHandler('seturl', [item, only]);
        },
        'mouseenter' : function(){
            var item = $(this).closest('.play-item');
            if(!item.attr('_url')){
                return;
            }
            try{
                player.rollOverHandler('play-item-' + item.attr('_index'));
            }catch(e){}
        },
        'mouseleave' : function(){
            var item = $(this).closest('.play-item');
            if(!item.attr('_url')){
                return;
            }
            try{
                player.rollOutHandler('play-item-' + item.attr('_index'));
            }catch(e){}
        }
    });

    $('.file-all').on('click', '.file-item', function(){
        var cname = 'yujian-select';
        if($(this).hasClass(cname)){
            return;
        }
        $('.' + cname).removeClass(cname);
        $(this).addClass(cname);
        $('.play-left').triggerHandler('seturl', [$(this)]);
    });

    setSwfPlay('flashOut', $('#down_stream_url').val(), '400', '300', 1, 'flashOut');
    setSwfPlay('flashContent', $('.play-item-1').attr('_url'), '400', '300', 100, 'flashContent');
    $('.play-item').each(function(){
        var url = $(this).attr('_url');
        if(url){
            setSwfPlay($(this).find('.item').attr('id'), url, '188', '141', 1, '');
        }
    });

    $('.play-right').hover(function(){
        try{
            player.rollOverHandler('flashYujian');
        }catch(e){}
    }, function(){
        try{
            player.rollOutHandler('flashYujian');
        }catch(e){}
    });

    $('.play-left').on('seturl', function(event, item, only){
        var url, id, type, name;
        if(only && only == 'string'){
            url = item['url'];
            id = item['id'];
            type = item['type'];
            name = item['name'];
        }else{
            url = item.attr('_url');
            id = item.attr('_id');
            type = item.attr('_type');
            name = item.attr('_name');
        }
        $(this).data('info', {
            id : id,
            type : type,
            url : url,
            name : name
        });
        $(this).find('.play-title').html(name + '&nbsp;&nbsp;' + (type == 'stream' ? '信号' : '文件'));

        if(!only || only == 'string'){
            var me = $(this);
            try{
                me.find('object')[0].setUrl(url);
            }catch(e){}
        }


        if(!only){
            var mi = $(this);
            var rotate = ['#5C99D2', '#585858', '#5C99D2', '#585858', '#5C99D2', '#585858', '#5C99D2', 'end'];
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
                        $(this).css('border-color', n);
                        next();
                    }).delay(100, 'mt');
                }
            });
            mi.dequeue('mt');
        }


        allCheck();
    });

    (function(){
        var right = $('.play-right');
        right.data('info', {
            id : right.attr('_id'),
            type : right.attr('_type')
        });
    })();

    $('.item-mask:first').triggerHandler('click', [true]);

    function allCheck(){
        var left = $('.play-left').data('info');
        var right = $('.play-right').data('info');
        var qieboMask = $('#qiebo-mask');
        var qiebo = $('.qiebo');
        if(left['id'] == right['id'] && left['type'] == right['type']){
            var offset = qiebo.offset();
            if(!qieboMask[0]){
                qieboMask = $('<div id="qiebo-mask"></div>').appendTo('body').css({
                    position : 'absolute',
                    left : offset.left + 'px',
                    top : offset.top + 'px',
                    width : qiebo.width() + 'px',
                    height : qiebo.height() + 'px',
                    opacity : 0,
                    'z-index' : 10000
                });
            }
            qieboMask.show();
            qiebo.addClass('qiebo-disable');
        }else{
            qieboMask.hide();
            qiebo.removeClass('qiebo-disable');
        }

        $('.button-item').removeClass('button-item-disable');
        $('.play-bottom .play-item').each(function(){
            if(!$(this).attr('_url')){
                var index = $(this).attr('_index') - 1;
                $('.button-item').eq(index).addClass('button-item-disable');
            }
        });
        if(right['type'] == 'stream'){
            var item = $('.play-bottom .play-item[_id="'+ right['id'] +'"]');
            if(item[0]){
                var index = $('.play-bottom .play-item').index(item[0]);
                $('.button-item').eq(index).addClass('button-item-disable');
            }
        }

    }

    $('.bei-drop-box').on('click', function(){
        $(this).find('.bei-drop').toggleClass('bei-dropdown');
        $(this).closest('.live-bei').find('.bei-content').slideToggle(300);
    });

    $('.stream-item').draggable({
        revert: "invalid",
        helper: "clone",
        cursor: "move"
    });
    function initDrag(){
        $('.stream-item').draggable({ disabled: false });
        $( ".stream-item-current" ).draggable({ disabled: true });
    }
    initDrag();

    $('.item-drop').droppable({
        accept: ".stream-item",
        activeClass: "item-drop-light",
        hoverClass: "item-drop-hover",
        drop: function(event, ui) {
            var drag = ui.draggable.addClass('stream-item-current');

            var id = $(this).attr('_id');
            $('#stream-' + id).removeClass('stream-item-current');
            var newId = drag.attr('_id');
            var newUrl = drag.attr('_url');
            var newName = drag.attr('_name');
            $(this).attr({
                '_id' : newId,
                '_url' : newUrl,
                '_name' : newName
            });
            var object = $(this).find('object');
            if(!object[0]){
                setSwfPlay($(this).find('.item').attr('id'), newUrl, '188', '141', 1, '');
            }else{
                object[0].setUrl(newUrl);
            }
            $(this).find('span:last').text(newName);
            var streamIds = [];
            $('.item-drop').each(function(){
                streamIds.push($(this).attr('_id'));
            });
            $.post(cunUrl,{
                id : $('#channel_id').val(),
                stream_id : streamIds.join(',')
            });
            allCheck();
            initDrag();
        }
    });

    $('.play-right .play-title').on('onoff', function(event, state){
        $(this)[state ? 'show' : 'hide']();
    }).trigger('onoff', [!parseInt($('#chg2_stream_id').val(), 10)]);

    function qiebo(){
        var info = $('.play-left').data('info');
        if(info){
            $('.qiebo-tip').triggerHandler('tip', ['send']);
            hg_emergency_change($('#channel_id').val(), info['id'], info['type']);

            $('.play-right').data('info', {
                id : info['id'],
                type : info['type']
            });

            //$('.play-right .play-title').trigger('onoff', [info['id'] == $('#stream_id').val() ? true : false]);
        }
    }

    $('.qiebo-button').on('move', function(){
        $(this).animate({
            top : 0
        }, 200, function(){
            var me = $(this);
            setTimeout(function(){
                me.animate({
                    top : '54px'
                }, 250);
            }, 100)
        });
    });

    $('.qiebo').on('click', function(){
        $('.qiebo-button').trigger('move');
        qiebo();
    });

    $('.qiebo-button').draggable({
        containment : 'parent',
        axis : 'y',
        stop : function(event, ui){
            var drag = ui.helper;
            if(!parseInt(drag.css('top'), 10)){
                qiebo();
            }
            drag.css({
                'transition' : 'all 0.3s',
                top : '54px'
            });
            setTimeout(function(){
                drag.removeAttr('style');
            }, 300);
        }
    });

    $('.button-item').on({
        'mousedown' : function(){
            $(this).addClass('button-item-current');
        },
        'mouseup' : function(){
            $(this).removeClass('button-item-current');
        },
        'click' : function(){
            if($(this).hasClass('button-item-disable')) return;
            $('.qiebo-button').trigger('move');
            var number = parseInt($(this).text(), 10);
            var item = $('.play-item-' + number);
            $('.qiebo-tip').triggerHandler('tip', ['send']);
            $('.yujian-select').removeClass('yujian-select');
            item.addClass('yujian-select');
            var sid = item.attr('_id');
            var stype = item.attr('_type');
            hg_emergency_change($('#channel_id').val(), sid, stype);

            $('.play-right').data('info', {
                id : sid,
                type : stype
            });

            //$('.play-right .play-title').trigger('onoff', [sid == $('#stream_id').val() ? true : false]);

            allCheck();
        }
    });

    emergency_change_back = function(data){
        data = data[0] || {};

        if(data && data['live_back'] == 0){
            var info = data['prev'];
            var sid = info['stream_id'];
            var stype = info['chg_type'];
            $('.play-left').trigger('seturl', [{
                id : sid,
                url : info['stream_url'],
                name : info['name'],
                type : stype
            }, 'string']);
            $('.yujian-select').removeClass('yujian-select');
            if(stype == 'stream'){
                $('.play-item[_id="'+ sid +'"]').addClass('yujian-select');
            }else if(stype == 'file'){
                $('.file-item[_id="'+ sid +'"]').addClass('yujian-select');
            }
            $('.qiebo-tip').trigger('tip', ['success']);

            $('.play-right .play-title').trigger('onoff', [data['stream_id'] == $('#stream_id').val() ? true : false]);

            allCheck();
        }

        if (data['live_back'] == 0)
        {
        	var channel_id = $('#channel_id').val();
        	var stream_id = $('#stream_id').val();
        	var stream = "'stream'";
        	var live_back = "'live_back'";
        	var html_a = '<a onclick="hg_emergency_change('+channel_id+','+stream_id+','+stream+','+live_back+');" class="live-back-a">' + '返回直播' + '</a>';
	       
	    }
        else
        {
	        var html_a = '<a>正在直播</a>';
        }
        $('#live_back').html(html_a);
        
    }

    $('.qiebo-tip').on('tip', function(event, state){
        var stateConfig = {
            'send' : '切播中...',
            'success' : '切播成功'
        };
        clearTimeout($(this).data('timer'));
        $(this).show().html(stateConfig[state]).stop();
        if(state == 'success'){
            var me = $(this);
            $(this).data('timer', setTimeout(function(){
                me.animate({
                    opacity : 0
                }, 1000, function(){
                    $(this).hide().css('opacity', 1);
                });
            }, 1000));
        }
    });

    (function(){
        var eachPage = parseInt($('.each-page:first').outerWidth(true), 10);
        var indexPage = 1;
        var totalPage = parseInt($('.file-page').find('em:last').text(), 10);
        $('.file-all').on({
            'move.file' : function(){
                $(this).css('left', - (eachPage * (indexPage - 1)) + 'px');
                showPage();
            },
            'left.file' : function(){
                if(indexPage <= 1){
                    return;
                }
                indexPage--;
                $(this).triggerHandler('move');
            },
            'right.file' : function(){

                if(indexPage >= totalPage){
                    return;
                }
                indexPage++;
                ajax();
                $(this).triggerHandler('move');
            },
            'width.file' : function(){
                $(this).width(function(){
                    return (indexPage + 1) * eachPage;
                });
            }
        }).triggerHandler('width');

        $('.page-prev').on('click', function(){
            $('.file-all').triggerHandler('left');
        });

        $('.page-next').on('click', function(){
            $('.file-all').triggerHandler('right');
        });

        function showPage(){
            $('.file-page').find('em:first').text(indexPage);
        }


        var cache = {
            1 : true
        };
        function ajax(){
            if(cache[indexPage]){
                return;
            }
            $('.file-all').triggerHandler('width');
            var page = $($('.each-page:first').clone()).appendTo('.file-all');
            page.find('.file-item').remove();
            page.append('<img class="loading" src="'+ RESOURCE_URL + 'loading2.gif" style="width:50px;position:absolute;left:50%;top:50%;margin:-25px 0 0 -25px;"/>');
            (function(index){
                var replaceTpl = function(tpl, data){
                    return tpl.replace(/{{([a-zA-Z0-9]+)}}/g, function(all, match){
                        return data[match] || '';
                    });
                };
                $.getJSON(replaceTpl(fileUrl, {
                    channelid : $('#channel_id').val(),
                    offset : (index - 1) * 7,
                    counts : 7
                }), function(json){
                    cache[index] = json;
                    var html = [];
                    var tpl = $('#tpl-file-item').val();
                    $.each(json[0]['backupInfo'], function(i, n){
                        html.push(replaceTpl(tpl, {
                            id : n['id'],
                            url : n['file_uri'],
                            title : n['title'],
                            src : n['img']
                        }));
                    });
                    page.html(html.join(''));
                    page.find('.file-item:last').css('margin-right', 0);
                });
            })(indexPage);
        }
    })();
});
