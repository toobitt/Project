jQuery(function($){
    var MC = {
        box : $('#file-box'),
        video : $('#video'),
        pian : $('#video-pian'),
        tiao : $('#tiao-box'),
        title : $('#title-box'),
        list : $('.point-list'),
        content : $('.content'),
        currentVideoId : 0,
        currentVideoInfo : null,
        canplay : false,
        isPlayIng : false
    };
    MC.tool = function( duration ){
            var duration = parseInt(duration);
            var h = parseInt(duration / 3600);
            var m = parseInt(duration / 60);
            var s = parseInt(duration % 60);
            return (h > 0 ? h + '时' : '') + ( (h > 0 || m > 0) ? m + '分' : '') + s + '秒';
	        
    };
    MC.content.on({
	    mouseover:function(){
	        $(this).next(".video-title").show();
        },
	    mouseout:function(){
		    $(this).next(".video-title").hide();
		},
        click:function(){
		    var idObj=$(this).closest(".video-point").attr("_id");
		    $(".point-list li").each(function(){
			var _this=$(this);
		    if(_this.attr("_id")==idObj){
			    _this.addClass("current");
			   $(".current").find(".edit").show();
			   $(".current").find(".text").focus();
			 }
	         });
		  }
		//  console.log($(".ui-widget-header").width());
	//var widthL=$(".ui-widget-header").width();
    //$(this).css("left",widthL);
	},'.video-point a');
    MC.box.on({
        _option : function(){
            var state = $(this).data('state');
            $(this).triggerHandler(state ? '_show' : '_hide');
            $(this).data('state', !state);
        },

        _show : function(){
            $(this).show().animate({
                opacity : 1
            }, 200);
            if(MC.isPlayIng){
                $(this).data('clonePlayIng', MC.isPlayIng);
                MC.video.point_video('pause');
            }
         //   MC.tiao.tiao('stopCheck');
        },

        _hide : function(){
            $(this).animate({
                opacity : 0
            }, 200, function(){
                $(this).hide();
                if($(this).data('clonePlayIng')){
                    $(this).data('clonePlayIng', false);
                    MC.video.point_video('play');
                }
            });
        },
      

        _click : function(event, id, data){
        	var _this=this;
            $(this).data('has-set', true);
            MC.box.triggerHandler('_option');
            MC.title.html(data['title']);

            setTimeout(function(){
                MC.video.point_video('changeVideo', {
                    zhen : parseInt(data['frame_rate']),
                    src : './vod' + (data['dir_index'] && data['dir_index'] > 0 ? data['dir_index'] : '') + '/' + data['video_path'] + data['video_filename'],
                    poster : data['img'],
                    fen : [data['width'], data['height']]
                });
                MC.pian.pian('empty');
                MC.pian.pian('option', 'current-video-id', id);
                MC.pian.pian('option', 'current-video-title', data['title'] + '片段(' + today + ')');
            //    MC.tiao('refresh', id);
            }, 1);
			$.getJSON('./run.php?mid=' + gMid + '&a=is_pointed',{videoid:id},function(data){
			
						var data = data[0];
						var arr = [];
						$.each(data,function(key,value){
						   var info = {};
						   info.id = value['id'];
						   info.point =  MC.tool( value['point']);
						   info.brief = value['brief'];
						   info.time = value['point'];
						   info.precent =( parseFloat(value['point'])/parseFloat(video.duration) )*100 + '%';
						   arr.push(info);
						 
						});
                     	$('.point-list').html($('#point-tpl').tmpl(arr));
						$('.video-tips').html($('#point-show').tmpl(arr));
			 });	
           $.getJSON('./run.php?mid=' + gMid + '&a=get_video_points',{videoid:id},function(data){
			            var total = data[0];
                        var info = {};
						info.total = total;
						
                    $('.number').html($('#point-count').tmpl(info));
		    });  
		}
  });
    MC.box.file({
        'cat-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_node&fid={{fid}}',
        'list-ajax-url' : 'run.php?mid=' + gMid + '&a=get_vod_info&page={{pp}}&counts=21&vod_sort_id={{cat}}&title={{title}}&date_search={{date}}'
    });
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
            MC.box.triggerHandler('_click', [id, data]);
        }
    }, '.file-list-li');

    $('.icon-a').on({
        click : function(){
            if(!MC.box.data('has-set')){
                return;
            }
            MC.box.triggerHandler('_option');
        }
    });

    $.Timer.check = function(){
        return MC.isPlayIng;
    };
    $.Timer.doing = function(){
        MC.video.point_video('timeupdate');
    };
    $.Timer.start();

    MC.video.point_video({
        kz : true,
        autoPlay : false,
        autoBuffer : true,
        customEvents : {
            '_change.video' : function(event, info){
                MC.canplay = false;
                $(this).data('canplayDo', null);
                $(this).point_video('option', 'zhen', info['zhen']);
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




    MC.pian.pian({
        'ajax-url' : 'run.php?mid=' + gMid + '&a=addpoint',
        saveAfter : function(event, info, position){
      //      MC.tiao.tiao('newPian', info, position);
        },

        editAfter : function(event, info){
    //        MC.tiao.tiao('editPian', info);
        },

        kzClick : function(event){
            MC.video.point_video('kz');
        },

        setStartAfter : function(event, time, cb){
            MC.video.point_video('sliderStart', time, cb);
        },

        setEndAfter : function(event, time, cb){
            if(MC.canplay){
                MC.video.point_video('sliderEnd', time, cb);
            }else{
                MC.video.data('canplayDo', function(){
                    MC.video.point_video('sliderEnd', time, cb);
                });
            }
        }
    });

   MC.list.dian({
   'delete-url' : 'run.php?mid=' + gMid + '&a=deletepoint',
    'update-url' : 'run.php?mid=' + gMid + '&a=updatepoint',
   });

});