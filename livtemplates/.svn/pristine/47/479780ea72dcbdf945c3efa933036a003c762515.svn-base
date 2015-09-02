jQuery(function($){
    var MC = {
    	tab : $('.split-tab'),
        box : $('#split-video-local'),										/*视频*/
        live : $('#split-video-live'),										/*频道直播*/
        video : $('#video'),
        pian : $('#video-pian'),
        tiao : $('#tiao-box'),
        title : $('#title-box'),

        currentVideoId : 0,
        currentVideoInfo : null,
        canplay : false,
        isPlayIng : false,
        isLive : $.liveSet,													/*判断直播是否开启字段*/
        flag : true
    };
    
    var options ={
            _option : function(){
                var state = $(this).data('state');
                $(this).triggerHandler(state ? '_show' : '_hide');
                $(this).data('state', !state);
            },

            _show : function(){
            	var item = $(this).closest('#file-box');
            	item.show().animate({
                    opacity : 1
                }, 200);
                if(MC.isPlayIng){
                	item.data('clonePlayIng', MC.isPlayIng);
                    MC.video.video('pause');
                }
                MC.tiao.tiao('stopCheck');
            },

            _hide : function(){
            	var item = $(this).closest('#file-box');
            	item.animate({
                    opacity : 0
                }, 200, function(){
                	item.closest('#file-box').hide();
                    if(item.data('clonePlayIng')){
                    	item.data('clonePlayIng', false);
                        MC.video.video('play');
                    }
                });
            },
            
            _click : function( event, id, data , callback ){
                $(this).data('has-set', true);
                if( $.isFunction( callback ) ){
    				callback( data );
    			}
                MC.title.html(data['title']);
                setTimeout(function(){
                    MC.video.video('changeVideo', {
                        zhen : parseInt(data['frame_rate']),
                        src : './vod' + (data['dir_index'] && data['dir_index'] > 0 ? data['dir_index'] : '') + '/' + data['video_path'] + data['video_filename'],
                        poster : data['img'],
                        fen : [data['width'], data['height']]
                    });
                    MC.pian.pian('empty');
                    MC.pian.pian('option', 'current-video-id', id);
                    MC.pian.pian('option', 'current-video-title', data['title'] + '片段(' + today + ')');
                    MC.tiao.tiao('refresh', id);
                }, 1);
            }
    };
    
    MC.box.file({
        'cat-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_node&fid={{fid}}',
        'list-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_info&page={{pp}}&counts=21&vod_sort_id={{cat}}&title={{title}}&date_search={{date}}&start_time={{start_time}}&end_time={{end_time}}&user_name={{user_name}}',
        'type' : 'video'
    });
    
    if( MC.isLive ){
    	MC.live.file({
    		'cat-ajax-url' : 'run.php?mid=' + gMid + '&a=get_live_node&fid={{fid}}',
    	    'list-ajax-url' : 'run.php?mid=' + gMid + '&a=get_live_info&page={{pp}}&counts=21&live_sort_id={{cat}}',
    	    'type' : 'channel'
    	});
    	 
    	MC.tab.on('click' , 'span' , function( event ){
        	var self = $( event.currentTarget ),
        		index = self.index();
        	self.addClass('current').siblings().removeClass('current');
        	$('.split-video-box:eq('+ index +')').show().siblings('.split-video-box').hide();
        	$('#file-box').css({'opacity' : 1 , 'display' : 'block'});
        	$('.modal-live-box').remove();
        });
    	
    	MC.live.on( options  );
    	MC.live.on({
            click : function(){
                var id = $(this).attr('_id'),
                	vod = $(this).data('url'),
                	title = $(this).find('.name').text();
                var info = {};
                info.id = id;
                info.vod = vod;
                info.title = title;
                $('#live-video-tpl').tmpl( info ).appendTo( '.main-box' );
                var ohms = $("#ohms-instance").ohms();
                $('.modal-live-box').live({
                	ohms : ohms,
                	callback : function( item , param , channel ){
                        var data = MC.live.file('getData', id);
                        var data = $.extend( data , param );
                        MC.currentVideoInfo = data;
                        MC.live.triggerHandler('_click', [id, data , function(){
                        	if( MC.flag ){
                        		MC.live.triggerHandler('_option');
                        		MC.flag = false;
                        	}
                        }]);
                        if(curVideo && !$.isArray(curVideo) && curVideo['id']){
	                        MC.tiao.tiao('destroy');
	                        MC.pian.pian('destroy');
                        }
                        tiao( 'get_split_live_videos&live_data_id=' + channel.live_data_id + '&channel_id=' + channel.id , 'get_live_video_status&live_data_id=' + channel.live_data_id , 1 );
                        pian( 'add_to_live_mark&live_data_id=' + channel.live_data_id , 1 );
                	},
                	status : function(){
                		MC.live.triggerHandler('_show');
                	},
                	
                	checkSpilt : function(){
                		/*查看全部拆条*/
                	},
                });
            }
        }, '.file-list-li');
    };
   
    MC.box.on( options  );
    MC.box.on({
        click : function(){
            var id = $(this).attr('_id');
            if(MC.currentVideoId && MC.currentVideoId == id){
                MC.box.triggerHandler('_option');
                return;
            }else{
                MC.currentVideoId = id;
            }
            var data = MC.box.file('getData', id);
            MC.currentVideoInfo = data;
            MC.box.triggerHandler('_click', [id, data , function(){
            	MC.box.triggerHandler('_option');
            }]);
            if(curVideo && !$.isArray(curVideo) && curVideo['id']){
            	MC.tiao.tiao('destroy');
            	MC.pian.pian('destroy');
            }
            tiao('get_split_videos' , 'get_video_status' , 0 );
            pian('add_to_vod_mark' , 0 );
        }
    }, '.file-list-li');
    
    
    
    $('.icon-a').on({
        click : function(){
            if(!MC.box.data('has-set') ){
                return;
            }
            MC.box.triggerHandler('_option');
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
        kz : true,
        autoPlay : false,
        autoBuffer : true,
        customEvents : {
            '_change.video' : function(event, info){
                MC.canplay = false;
                $(this).data('canplayDo', null);
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
                var canplayDo = $(this).data('canplayDo');
                if(canplayDo && $.isFunction(canplayDo)){
                    canplayDo();
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

        slide : function(event, index, time, timeString){
            var type = 'set' + (!index ? 'Start' : 'End');
            var $this = $(this);
            var imgData = MC.currentVideoInfo && MC.currentVideoInfo['is_audio'] > 0 ? '' : $this.data('canvas').getImgFromVideo();
            MC.pian.pian(type, time, timeString, imgData);
         

        },

        clickKZ : function(){
            if(MC.currentVideoInfo && MC.currentVideoInfo['is_audio'] > 0){
                return;
            }
            var imgData = $(this).data('canvas').getImgFromVideo();
            MC.pian.pian('setKZ', imgData);
        }
    });



    function pian( a , type ){
    	MC.pian.pian({
            'ajax-url' : 'run.php?mid=' + gMid + '&a=' + a,
            'publish-url' : './get_selected_nodes.php?column_id=',
            'type' : type,
            saveAfter : function(event, info, position){
                MC.tiao.tiao('newPian', info, position);
            },

            editAfter : function(event, info, type){
                MC.tiao.tiao('editPian', info, type);
            },

            kzClick : function(event){
                MC.video.video('kz');
            },

            setVideoSlider : function(event, index){
                MC.video.video('setSliderPN', index);
            },

            setStartAfter : function(event, time, cb){
                MC.video.video('sliderStart', time, cb);
            },

            setEndAfter : function(event, time, cb){
                if(MC.canplay){
                    MC.video.video('sliderEnd', time, cb);
                }else{
                    MC.video.data('canplayDo', function(){
                        MC.video.video('sliderEnd', time, cb);
                    });
                }
            }
        });
    };
    
    function tiao( a1 , a2 , type ){
    	MC.tiao.tiao({
            'ajax-url' : 'run.php?mid=' + gMid + '&a='+ a1 +'&id={{id}}',
            'check-status-url' : 'run.php?mid=' + gMid + '&a=' + a2,
            'tpl' : '#split-tpl',
            'number' : '.vod-split .number',
            'type' : type,									/*判断是视频还是直播拆条*/
            'editAfter' : function(event, data, type){
                MC.pian.pian('edit', data, type);
            }
        });
    };

    


    if(curVideo && !$.isArray(curVideo) && curVideo['id']){
        MC.currentVideoInfo = curVideo;
        MC.box.triggerHandler('_click', [curVideo['id'], curVideo , function(){
        	tiao('get_split_videos' , 'get_video_status' , 0 );
            pian('add_to_vod_mark' , 0);
            MC.box.triggerHandler('_option');
        }]);
    }
});