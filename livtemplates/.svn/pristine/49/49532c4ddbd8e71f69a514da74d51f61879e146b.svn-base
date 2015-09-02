function setSwfPlay(flashId, url ,width, height, mute, objectId)
{
	var swfVersionStr = "11.1.0";

	var xiSwfUrlStr = RESOURCE_URL+"swf/playerProductInstall.swf?1111111";
	var flashvars = {objectId: objectId, namespace: "player", url: url, mute: mute};
	var params = {};
	params.quality = "high";
	params.bgcolor = "#000";
	params.allowscriptaccess = "sameDomain";
	params.allowfullscreen = "true";
	params.wmode = "transparent";
	var attributes = {};
	attributes.id = flashId+'_1';
	attributes.name = flashId+'_1';
	attributes.align = "middle";
	swfobject.embedSWF(
	   RESOURCE_URL+"swf/Main.swf?1111111", flashId, 
	    width, height, 
	    swfVersionStr, xiSwfUrlStr, 
	    flashvars, params, attributes);

	swfobject.createCSS("#"+flashId, "display:block;text-align:left;");

}
/*
setUrl 切换流地址
setVolume 100静音 0有声音
clickHandler 定义onclick事件
*/
var Player = function() 
{
	this.rollOverHandler = function(id) {
		var id = id + '_1';
		document.getElementById('flashContent_1').setVolume(0);	
		document.getElementById(id).setVolume(100);
	};
	this.rollOutHandler = function(id) {
		var id = id + '_1';
		document.getElementById(id).setVolume(0);		
		document.getElementById('flashContent_1').setVolume(100);	
	};
};
var player = new Player();

/*切播*/
function hg_change(channel_id, stream_id, change_type, notify, url, name)
{
	if (!url)
	{
		url = '';
	}
	if (!name)
	{
		name = '';
	}
	var url = "./run.php?mid=" + gMid + "&a=change&channel_id=" + channel_id + "&stream_id=" + stream_id + "&change_type=" + change_type + "&notify=" + notify + "&chgurl=" + url + "&chgname=" + name;
	hg_ajax_post(url,'','','change_callback');
	
}

$(function(){
    var fileUrl = './run.php?mid=' + gMid + '&a=get_backup_info&channel_id={{channelid}}&offset={{offset}}&counts={{counts}}';
    var cunUrl = './run.php?mid=' + gMid + '&a=update_beibo';

    /*$(window).on('resize', function(){
        $('.live').css({
            //width : $(this).width(),
            height : $(this).height()
        });
    }).trigger('resize');*/



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

//    $('.file-all').on('click', '.file-item', function(){
//        var cname = 'yujian-select';
//        if($(this).hasClass(cname)){
//            return;
//        }
//        $('.' + cname).removeClass(cname);
//        $(this).addClass(cname);
//        var _this = $(this);
//        var _id = $(this).attr('_id');
//        var _name = $(this).attr('_name');
//        $.post(
//        	'run.php?mid=' + gMid + '&a=set_backup',
//        	{
//	        	vod_id : _id,
//	        	channel_id : $('#channel_id').val(),
//	        	type : 1
//        	},
//        	function(json){
//        		json = json[0];
//        		if(json['file_url']){
//		        	$('.play-left').triggerHandler('seturl', [{
//			        	url : json['file_url'],
//			        	id : _id,
//			        	streamid : json['backup_id'],
//			        	type : 'file',
//			        	name : _name
//		        	}, 'string']);
//	        	}
//        	},
//        	'json'
//        );
//        //$('.play-left').triggerHandler('seturl', [$(this)]);
//    });

    setSwfPlay('flashOut', $('#output_url_rtmp').val(), '400', '300', 1, 'flashOut');
    setSwfPlay('flashContent', $('.play-item-1').attr('_url'), '400', '300', 100, 'flashContent');
    $('.play-bottom .play-item').each(function(){
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
        var url, id, type, name, streamid;
        if(only && only == 'string'){
            url = item['url'];
            id = item['id'];
            streamid = item['streamid'];
            type = item['type'];
            name = item['name'];
        }else{
            url = item.attr('_url');
            id = item.attr('_id');
            streamid = item.attr('_streamid');
            type = item.attr('_type');
            name = item.attr('_name');
        }
        $(this).data('info', {
            id : id,
            streamid : streamid,
            type : type,
            url : url,
            name : name
        });
        $(this).find('.play-title').html(name + '&nbsp;&nbsp;' + (type == 'stream' ? '频道' : '文件'));

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
            streamid : right.attr('_streamid'),
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
        
        
        var cname = 'yujian-select';	
        $('.button-item').removeClass('button-item-disable');
        $('.play-bottom .play-item').removeClass(cname).each(function(){
            if(!$(this).attr('_url')){
                var index = $(this).attr('_index') - 1;
                $('.button-item').eq(index).addClass('button-item-disable');
            }
            
            if(left['id'] == $(this).attr('_id')){
            	
            	if(!$(this).hasClass(cname)){
	            	 $(this).addClass(cname);	
            	}
	           
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
            var streamid = $(this).attr('_streamid');
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
            var changeIds = [];
            $('.item-drop').each(function(){
                changeIds.push($(this).attr('_id'));
            });
			/*
            $.post(
                cunUrl,
                {
                    change_id : newId,
                    stream_id : streamid,
                    channel_id : $('#channel_id').val(),
                    beibo_id : changeIds.join(',')
                },
                function(json){
                    //暂时返回值没有要

                },
                'json'
            );*/
            allCheck();
            initDrag();
        }
    });

    $('.play-right .play-title').on('onoff', function(event, state){
        $(this)[state ? 'show' : 'hide']();
    }).trigger('onoff', [!parseInt($('#change_id').val(), 10)]);

    function qiebo(){
        var info = $('.play-left').data('info');
        if(info){
            $('.qiebo-tip').triggerHandler('tip', ['send']);
            var index = $('.yujian-select').attr('_index');
         /*   var sendVal = index == 1 ? 1 : 0; */
            hg_change($('#channel_id').val(), info['id'], info['type'], 0, info['url'], info['name']);

            $('.play-right').data('info', {
                id : info['id'],
                streamid : info['streamid'],
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
            var streamid = item.attr('_streamid');
            var stype = item.attr('_type');
            var url = item.attr('_url');
            var name = item.attr('_name');
          /*  var sendVal = number == 1 ? 1 : 0;*/
            hg_change($('#channel_id').val(), sid, stype, 0, url, name);

            $('.play-right').data('info', {
                id : sid,
                streamid : streamid,
                type : stype
            });

            //$('.play-right .play-title').trigger('onoff', [sid == $('#stream_id').val() ? true : false]);

            allCheck();
        }
    });

    change_callback = function(data){
        data = data[0] || {};

        if(data){
            var info = data['prev'];
            var sid = info['change_id'];
            var streamid = info['stream_id'];
            var stype = info['change_type'];
            $('.play-left').trigger('seturl', [{
                id : sid,
                streamid : streamid,
                url : info['input_url'],
                name : info['change_name'],
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

        if (data['notify'] == 0)
        {
        	var channel_id = $('#channel_id').val();
        	var stream_id = 0;
        	var change_type = "'stream'";
        	var notify = 1;
        	var html_a = '<a onclick="hg_change('+channel_id+','+stream_id+','+change_type+','+notify+');" class="live-back-a" style="color:#fff;">' + '返回直播' + '</a>';
	       
	    }
        else
        {
	        var html_a = '<a style="color:#fff;">正在直播</a>';
        }
        $('#notify').html(html_a);
        
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
                    $.each(json[0], function(i, n){
                        html.push(replaceTpl(tpl, {
                            id : n['id'],
                            url : n['vodurl'],
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
