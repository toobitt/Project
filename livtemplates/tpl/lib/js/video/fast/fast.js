jQuery(function($){
    var MC = {
        box : $('#file-box'),
        video : $('#video'),
        videoBox : $('#video-box'),
        dui : $('#fast-dui'),

        currentVideoId : 0,
        canplay : false,
        isPlayIng : false
    };


    MC.box.file({
        'need-drag' : true,
        'cat-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_node&fid={{fid}}',
        'list-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_info&page={{pp}}&counts=21&vod_sort_id={{cat}}&title={{title}}&date_search={{date}}&start_time={{start_time}}&end_time={{end_time}}&user_name={{user_name}}',
        'drag-helper' : function(){
            return '<div class="common-vod-area" style="opacity:.4;">' + $(this).clone()[0].outerHTML + '</div>';
        },

        'get-drag' : function(info){
            return MC.dui.dui('getDrag', info);
        },

        'set-drag' : function(hash, data){
            return MC.dui.dui('setDrag', hash, data);
        }
    });



    $.Timer.check = function(){
        return MC.isPlayIng;
    };
    $.Timer.doing = function(){
        MC.video.video('timeupdate');
    };
    $.Timer.start();

    MC.video.video({
        kz : false,
        autoPlay : false,
        autobuffer : true,
        customEvents : {
            '_change.video' : function(event, info){
                $(this).video('option', 'zhen', info['zhen']);
                var fen = info['fen'];
                var canvas = $.createCanvas({
                    width : fen[0],
                    height : fen[1]
                });
                $(this).data('canvas', canvas);
                $(this).attr({
                    src : info['src'],
                    poster : info['img']
                });
                this.load();
            },
            '_pian.video' : function(){

            },

            'canplay.video' : function(){
                MC.canplay = true;
                var canplayCallback = $(this).data('canplayCallback');
                if(canplayCallback && $.type(canplayCallback) == 'function'){
                    canplayCallback.call(this);
                    $(this).data('canplayCallback', null);
                }
            },

            'play.video' : function(){
                MC.isPlayIng = true;
                $.Timer.start();
            },

            'pause.video' : function(){
                MC.isPlayIng = false;
                $.Timer.stop();
            },

            'timeupdate.video' : function(){
            },

            'emptied.video' : function(){
            },

            'seeked.video' : function(){
            },

            'error.video' : function(){
            }
        },

        sliderInit : function(event, self){
            $(self).myslider('values', [0, 100]);
        },

        slide : function(event, index, time, timeString){
            time *= 1000;
            var type = 'set' + (!index ? 'Start' : 'End');
            var imgData = $(this).data('canvas').getImgFromVideo();
            MC.dui.dui(type, time, /*timeString, */imgData);
        }


    });

    MC.videoBox.on({
        _show : function(event, position){
            var win = $(window);
            var winWidth = win.width();
            var winHeight = win.height();
            var left = position.left;
            var top = position.top + position.height + 30;
            if(left + 500 > winWidth){
                left += position.width - 500;
            }
            $(this).css({
                left : left + 'px',
                top : top + 'px'
            }).show();
            $(this).triggerHandler('_mask', ['show']);
        },

        _hide : function(){
            $(this).hide();
            $(this).triggerHandler('_mask', ['hide']);
            MC.dui.dui('clickItemAfter');
        },

        _mask : function(event, state){
            var mask = $('#video-box-mask');
            if(!mask[0]){
                mask = $('<div/>').attr({
                    id : 'video-box-mask'
                }).appendTo('body');
            }
            mask[state]();
        },

        _init : function(){
            $(this).append('<div id="video-box-close"></div>');
        }
    }).triggerHandler('_init');

    MC.videoBox.on({
        click : function(){
            MC.videoBox.triggerHandler('_hide');
        }
    }, '#video-box-close');

    MC.dui.dui({
        info : videos,
        'default-title' : '新快编（' + today + '）',
        'save-item-ajax-url' : 'run.php?mid=' + gMid + '&a=auto_save',
        'delete-item-ajax-url' : 'run.php?mid=' + gMid + '&a=auto_save_delete',
        'sort-ajax-url' : 'run.php?mid=' + gMid + '&a=auto_save_order',
        'save-ajax-url' : 'run.php?mid=' + gMid + '&a=save_as_fast_edit',
        'save-fugai-ajax-url' : 'run.php?mid=' + gMid + '&a=save_fast_edit',
        'reset-ajax-url' : 'run.php?mid=' + gMid + '&a=clear_tmp',
        clickItem : function(event, item, data){

            if(item.hasClass('two-point')){
                var duration = data['duration_num'];
                var startInfo = data['startInfo'];
                var startPoint = startInfo ? startInfo['time'] : 0;
                var endInfo = data['endInfo'];
                var endPoint = endInfo ? endInfo['time'] : duration;
                var startVal = startPoint / duration * 100;
                var endVal = endPoint / duration * 100;
                //MC.video.video('setTime', startPoint / 1000);
                //MC.video.video('setSlider', [startVal, endVal]);

                MC.video.data('canplayCallback', function(){
                    $(this).video('setTime', startPoint / 1000);
                    $(this).video('setSlider', [startVal, endVal]);
                });

            }else{
                //MC.video.video('setTime', 0);
                //MC.video.video('setSlider', [0, 100]);

                MC.video.data('canplayCallback', function(){
                    $(this).video('setTime', 0);
                    $(this).video('setSlider', [0, 100]);
                });

            }
            MC.video.video('changeVideo', {
                zhen : parseInt(data['frame_rate']),
                src : './vod' + (data['dir_index'] && data['dir_index'] > 0 ? data['dir_index'] : '') + '/' + data['video_path'] + data['video_filename'],
                poster : data['img'],
                fen : [data['width'], data['height']]
            });
            var itemPosition = item.offset();
            var itemInfo = {
                left : itemPosition.left,
                top : itemPosition.top,
                width : item.outerWidth(),
                height : item.outerHeight()
            };
            MC.videoBox.triggerHandler('_show', [itemInfo]);
        }
    });

    if(mainId){
        $('.save-fugai').show();
    }
});