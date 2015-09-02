
var adminDemandPlayer = {};

    adminDemandPlayer.startHandler = function()
    {
       
    };

    adminDemandPlayer.endHandler   = function()
    {
        
    };

	adminDemandPlayer.snap = function(vodid,current_time)
	{
		var id = $('#edit_id').val();
		var img_count = 1;
		var url = "./run.php?mid="+gMid+"&a=get_current_img&img_count="+img_count+"&stime="+current_time+"&id="+id;
		hg_ajax_post(url,'','','hg_get_one_vimg');
	};
	var ii=tt=tt_m=tt_s=hg_img_url=0;
	var img_move="#img_move";
	var move_time = 400;
    function hg_get_one_vimg(obj)
    {
    	$(img_move).clearQueue();
		clearTimeout(tt_s,tt_m);
		$(img_move).attr('src',obj[0].new_img);
		$('#source_img_pic').val(obj[0].new_img);
		move_img();
		tt_s = setTimeout(he_get_mov_img_show,move_time+10);
	}
	function he_get_mov_img_show()
	{
		hg_img_url = $('#source_img_pic').val();
		$('#pic_face').attr('src',hg_img_url);
	}
	function move_img()
	{
		$(img_move).addClass('move_img_b');
		$(img_move).animate({top:'0px',left:'-201px',width:'188px'},move_time);
		tt_m = setTimeout(remove_move_img,move_time+20);
	}
	function remove_move_img()
	{
		$(img_move).remove();
		$("#video").before('<img class="move_img_a" src="" id="img_move" style="width:320px;" />');
	}
	function video_close(){
		$("#hoge_edit_play").css("display","none");
	}
		function video_show()
	{
		$("#hoge_edit_play").clearQueue();
		if(ii==0)
		{
			ii=1;
			clearTimeout(tt);
			$("#hoge_edit_play").css("display","block");
			$("#hoge_edit_play").animate({top:"140px"});
			
		}
		else{
			ii=0;
			$("#hoge_edit_play").css({top:"-378px"});
			tt = setTimeout(video_close,600);
		}
	}


	function hg_toSubmit()
	{
		$('#vod_sort_id').val($('#sort_id').val());
		$('#source').val($('#update_source_id').val());
		if($('#vod-title').val() == '请输入标题')
	    {
	    	alert('您未填写标题');
	    	return false;
	    }
	    
	    if($('#comment').val() == '这里输入描述')
	    {
	    	$('#comment').val('');
	    }
		//return hg_ajax_submit('vodform','');
	}

	function hg_overEditVideoInfo(html,id)
	{
		var frame_type = "{$_INPUT['_type']}";
		if(frame_type)
		{
			frame_type = '&_type='+frame_type;
		}
		else
		{
			frame_type = '';
		}
		
		var frame_sort = "{$_INPUT['_id']}";
		if(frame_sort)
		{
			frame_sort = '&_id='+frame_sort;
		}
		else
		{
			frame_sort = '';
		}
		/*因为执行此函数将remove函数所在的iframe，而此函数后还有代码需执行，所以将此函数延迟执行*/
		setTimeout(function () {
			top.$.optionIframeClose(html, id)
		}, 1);
		
	}

	function hg_change_color(obj,flag)
	{
		if(flag)
		{	
			$(obj).css('background','#5F9BD1');
		}
		else
		{
			$(obj).css('background','');
		}
	}
	function hg_content_vod_show()
	{
		if($('#content_vodinfo_more').text()=='更多')
		{
			$('#content_vodinfo_ul_one').attr('class','');
			$('#content_vodinfo_ul').show(0,function(){hg_resize_nodeFrame();});
			$('#hg_vod_text_more').hide();
			$('#content_vodinfo_more').text('收起');
		}
		else
		{
			$('#content_vodinfo_ul_one').attr('class','overflow i');
			$('#content_vodinfo_ul').hide(0,function(){hg_resize_nodeFrame();});
			$('#hg_vod_text_more').show();
			$('#content_vodinfo_more').text('更多');

		}
		
	}
jQuery(function($) {
	function initStyle() {
		$('.publish-box').css({'z-index': ''});
	}
	initStyle();
	
	$('.source-img').toggle(function() {
		$('.source-img-box').slideDown();
	},
	function() {
		$('.source-img-box').slideUp();
	});

	$('.source-img-box li.snap-img').on('click', function() {
		$('#img_src_cpu').val('');
		if ( $(this).hasClass('current') ) {
			$(this).removeClass('current');
			$('#pic_face').css('opacity', 0).trigger('change', [ $('#source_img_pic').val() ]);
			$('#img_src').val('');
		} else {
			$('.source-img-box li.snap-img').removeClass('current');
			$(this).addClass('current');
			var src = $(this).find('img').attr('src');
			$('#pic_face').css('opacity', 0).trigger('change', [src]);
			$('#img_src').val(src);
		}
	});
	var fInpt = $('#file');
	$('#pic_face').on('change', function(event, src) {
		this.onload = function() {
			$(this).removeAttr('width').removeAttr('height').css('opacity', 0);
			/*if ( this.height / this.width > 173 / 232 ) {
				this.height = 173;
			} else {
				this.width = 232;
			}*/
			$(this).animate({opacity: 1}, 500);
		}
		this.src = src + '?' + Math.random();
	}).trigger('change', [$('#pic_face').attr('_src')]);
	fInpt.ajaxUpload({
		url: "run.php?mid=" + gMid + "&a=preview_pic&admin_id=" + gAdmin['admin_id'] + "&admin_pass=" + gAdmin.admin_pass,
		phpkey: 'Filedata',
		after:function(data) {
			var src = data.data.img_path;
			$('#img_src').val('');
			$('#pic_face').trigger('change', [src]);
			$('#img_src_cpu').val(src);
			
			indexPicEdit();
		}
	});
	$('.add-img-button').click(function() {
		fInpt.trigger('click');
	});
	
	/* 编辑索引图 */
	var indexPicEdit = function() {
		var picId = '#pic_face';
		top.$("body").find( picId ).remove();
		top.$('body').append( $( picId ).clone().hide() );
		$( picId ).picEdit({
			positionLeft : true,
			imgSrc: $(this).attr("_src"),
			mouseCheck: function() {
				return $(this).attr("_state") > 0 ? false: true;
			},
			saveAfter: function() {
				top.$("body").find( picId ).remove();
				top.$("body").off("_picsave").on("_picsave", function(event, info) {
					console.log( info );
					try {
						var img = $(this).find("#formwin")[0].contentWindow.$( picId );
						img.attr("src", $.globalImgUrl(info, "", true));
						top.$("body").find( picId ).remove();
					} catch(e) {
						console.log(e);
					}
				}).append( $( picId ).clone().hide() );
			}
		});
	};
	window.indexPicEdit = indexPicEdit;
	indexPicEdit();
	/* 编辑索引图end */
	
	(function(){
        var tcolor = $('#tcolor').val(),
            isbold = $('#isbold').val(),
            isitalic = $('#isitalic').val();
        $('#vod-title').css({
            'font-weight' : isbold > 0 ? 'bold' : 'normal',
            'font-style' : isitalic > 0 ? 'italic' : 'normal',
            'color' : tcolor
        });
        isbold > 0 && $('.form-title-weight').addClass('selected');
        isitalic > 0 && $('.form-title-italic').addClass('selected');
    })();

    var func = function(event){
        var target = $(event.target);
        if(target.hasClass('form-dioption-title') || target.closest('.form-dioption-title').length){
            return;
        }
        $('.form-dioption-title').data('inclick', false);
        $('.form-title-option').hide();
        $('#vod-title').triggerHandler('_blur');
        $(this).off('mousedown', func);
    };

    $('.form-dioption-title').off('click')
        .on('click', function(){
            if($(this).data('inclick')) return;
            $(this).data('inclick', true);
            $('.form-title-option').show();
            $('#vod-title').focus();
        });

    

    $('.form-title-weight, .form-title-italic').off('click').on('click', function(event){
        event.stopPropagation();
        var title = $('#vod-title'),
            bold = $('#isbold'),
            italic = $('#isitalic'),
            weight = $(this).hasClass('form-title-weight') ? true : false;
        if($(this).hasClass('selected')){
            $(this).removeClass('selected');
            if(weight){
                title.css('font-weight', 'normal');
                bold.val(0);
            }else{
                title.css('font-style', 'normal');
                italic.val(0);
            }
        }else{
            $(this).addClass('selected');
            if(weight){
                title.css('font-weight', 'bold');
                bold.val(1);
            }else{
                title.css('font-style', 'italic');
                italic.val(1);
            }
        }
    });
});
	
	
	

        



      








