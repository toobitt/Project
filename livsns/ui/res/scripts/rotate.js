$(document).ready(function (){
	var para = ({
		scrolltop:0
	});
	get_position = function(id)
	{
		off = $("#mid_"+id).offset();
		screentop = off.top - $(document).scrollTop()
		para.scrolltop = $(document).scrollTop() + screentop;	
	}
	set_position = function(id)
	{
		$(document).scrollTop(para.scrolltop)
	}
	scaleImg = function(ids,tid)
	{
		id = parseInt(ids)+ parseInt(tid);
		get_position(ids);
		rot = 0;
		if(rot === 0)
		{
			document.getElementById("prev_"+id).style.display = "none";
			document.getElementById("disp_"+id).style.display = "block";
			img = document.getElementById("load_"+id);
			cv = document.getElementById("canvas_"+id);
			$("#rot_"+id).val(rot);
			cv.style.display = "none";
			img.style.display = "inline-block";
		}
	}
	shlink = function(ids,tid)
	{
		id = parseInt(ids)+ parseInt(tid);
		set_position(ids);
		document.getElementById("disp_"+id).style.display = "none";	
		document.getElementById("prev_"+id).style.display = "block";
	}
	runLeft = function(ids,tid)
	{
		id = parseInt(ids)+ parseInt(tid);
		rot = parseInt($("#rot_"+id).val());
		img = document.getElementById("load_"+id);
		cv = document.getElementById("canvas_"+id);
		rot += 90;		
		if(rot === 360)
		{
			rot = 0;
		}
		$("#rot_"+id).val(rot);
		var w = img.width;
		var h = img.height;
		switch(rot)
		{
			case 0:
				cv.style.display = "none";	
				img.style.display = "inline-block";	
				break;
			case 90:
				if(h>440)
				{
					w = (w/h)*440;
					h = 440;
				}
				rotate(cv, img, rot,w,h);
				break;
			case 180:
				rotate(cv, img, rot,w,h);
				break;
			case 270:
				if(h>440)
				{
					w = (w/h)*440;
					h = 440;
				}
				rotate(cv, img, rot,w,h);
				break;
			case 360:
				cv.style.display = "none";	
				img.style.display = "inline-block";	
				rot = 0;
				break;
			default:
				break;
		}
	};
	runRight = function(ids,tid)
	{
		id = parseInt(ids)+ parseInt(tid);
		rot = parseInt($("#rot_"+id).val());
		img = document.getElementById("load_"+id);
		cv = document.getElementById("canvas_"+id);
		rot -= 90;
		if(rot === -90){
			rot = 270;	
		}
		$("#rot_"+id).val(rot);
		var w = img.width;
		var h = img.height;
		switch(rot)
		{
			case 0:
				cv.style.display = "none";	
				img.style.display = "inline-block";	
				break;
			case 90:
				if(h>440)
				{
					w = (w/h)*440;
					h = 440;
				}
				rotate(cv, img, rot,w,h);
				break;
			case 180:
				rotate(cv, img, rot,w,h);
				break;
			case 270:
				if(h>440)
				{
					w = (w/h)*440;
					h = 440;
				}
				rotate(cv, img, rot,w,h);
				break;
			case -90:
				if(h>440)
				{
					w = (w/h)*440;
					h = 440;
				}
				rotate(cv, img, rot,w,h);
				rot = 270;
				break;
			default:
				break;
		}
	};	

	var rotate = function(canvas,img,rot,w,h){
		canvas.style.display = "inline-block";	
		if(!w)
		{
			w = img.width;
		}
		if(!h)
		{
			h = img.height;
		}
		if(!rot){
			rot = 0;	
		}
		var rotation = Math.PI * rot / 180;
		var c = Math.round(Math.cos(rotation) * 1000) / 1000;
		var s = Math.round(Math.sin(rotation) * 1000) / 1000;
		canvas.height = Math.abs(c*h) + Math.abs(s*w);
		canvas.width = Math.abs(c*w) + Math.abs(s*h);
		var context = canvas.getContext("2d");
		context.save();
		if (rotation <= Math.PI/2) {
			context.translate(s*h,0);
		} else if (rotation <= Math.PI) {
			context.translate(canvas.width,-c*h);
		} else if (rotation <= 1.5*Math.PI) {
			context.translate(-c*w,canvas.height);
		} else {
			context.translate(0,-s*w);
		}
		context.rotate(rotation);
		context.drawImage(img, 0, 0, w, h);
		context.restore();
		img.style.display = "none";	
	}
	
	scaleVideo = function(sid,mid,self){
		id = sid + mid;
		var link = $('#vl_'+id).html();
		var title = $('#vt_'+id).html().length > 15 ? $('#vt_'+id).html().substr(0,15)+"...":$('#vt_'+id).html();
		var url = $('#vu_'+id).html();
		var ids = "vo_" + id;
		var div = '<div class="pad_sp"><a href="javascript:void(0);" onclick="upvideo('+ sid +');">收起</a><a style="padding-left: 5px;" href="'+ url +'" target="_blank">'+ title +'</a><a style="padding-left: 5px;" href="javascript:void(0);" onclick="popvideo('+ sid +','+ mid +','+ self +');">弹出</a></div>';
		$('#v_'+sid).show();
		$('#v_'+sid).html(div+'<img src="./res/img/loading.gif"/>正在加载中...');
		if($.browser.msie) 
		{
			if(self)
			{
				var video = '<embed src="http://video.hcrt.cn/flash-player/Share.swf?mode=TVieVod&width=530&height=350&server=liveapi.hcrt.cn&file='+ link.substr(21) +'&startTimeInMS=0&forceBase64=false&serverVersion=1.5b&skin=d0d0d0video.hcrt.cna_bflash-playera_bCBNVodSkin.swf&amp;astart=true" quality="high" width="500" height="436" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>';
			}
			else
			{	
				var video = '<embed height="356" width="440" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="playMovie=true&amp;auto=1&amp;adss=0"  allowscriptaccess="always" allowfullscreen="true" quality="high" bgcolor="#FFFFFF" name="'+ ids +'" id="'+ ids +'" src="'+ link +'" type="application/x-shockwave-flash">';
			}
		}
		else
		{
			if(self)
			{
				var video = '<embed src="http://video.hcrt.cn/flash-player/Share.swf?mode=TVieVod&width=530&height=350&server=liveapi.hcrt.cn&file='+ link.substr(21) +'&startTimeInMS=0&forceBase64=false&serverVersion=1.5b&skin=d0d0d0video.hcrt.cna_bflash-playera_bCBNVodSkin.swf&amp;astart=true" quality="high" width="500" height="436" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>';
			}
			else
			{
				var video = '<object height="356" width="440" wmode="transparent" type="application/x-shockwave-flash" data="'+ link +'" id="'+ ids +'" style="visibility: visible;"><param name="quality" value="high"><param name="allowScriptAccess" value="always"><param name="wmode" value="transparent"><param name="allowFullscreen" value="true"><param name="flashvars" value="playMovie=true&amp;auto=1"></object>';
			}
			
		}	
		$('#v_'+sid).html(div + video);		
		$('#prev_'+sid).hide();
	}		
	closevideo = function(id){
		$('#pop').hide();
		$('#pop').html('');		
		$('#prev_'+id).show();		
	}
	upvideo = function(id){
		$('#v_'+id).hide();
		$('#v_'+id).html('');
		$('#prev_'+id).show();
	}
	popvideo = function(sid,mid,self){
		upvideo(sid);
		id = sid + mid;
		var link = $('#vl_'+id).html();
		var ids = "vo_" + id;
		
		if($.browser.msie) 
		{
			if(self)
			{
				var video = '<span style="font-size:12px;color:#0082CB;width:auto;cursor:pointer;" onclick="closevideo('+id+')">关闭</span><div><embed src="http://video.hcrt.cn/flash-player/Share.swf?mode=TVieVod&width=530&height=350&server=liveapi.hcrt.cn&file='+ link.substr(21) +'&startTimeInMS=0&forceBase64=false&serverVersion=1.5b&skin=d0d0d0video.hcrt.cna_bflash-playera_bCBNVodSkin.swf&amp;astart=true" quality="high" width="440" height="384" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed></div>';
			}
			else
			{	
				var video = '<span style="font-size:12px;color:#0082CB;width:auto;cursor:pointer;" onclick="closevideo('+id+')">关闭</span><div><embed height="356" width="440" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="playMovie=true&amp;auto=1&amp;adss=0"  allowscriptaccess="always" allowfullscreen="true" quality="high" bgcolor="#FFFFFF" name="'+ ids +'" id="'+ ids +'" src="'+ link +'" type="application/x-shockwave-flash"></div>';
			}
		}
		else
		{
			if(self)
			{
				var video = '<span style="font-size:12px;color:#0082CB;width:auto;cursor:pointer;" onclick="closevideo('+id+')">关闭</span><div><embed src="http://video.hcrt.cn/flash-player/Share.swf?mode=TVieVod&width=530&height=350&server=liveapi.hcrt.cn&file='+ link.substr(21) +'&startTimeInMS=0&forceBase64=false&serverVersion=1.5b&skin=d0d0d0video.hcrt.cna_bflash-playera_bCBNVodSkin.swf&amp;astart=true" quality="high" width="440" height="384" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed></div>';
			}
			else
			{
				var video = '<span style="font-size:12px;color:#0082CB;width:auto;cursor:pointer;" onclick="closevideo('+id+')">关闭</span><div><object height="356" width="440" wmode="transparent" type="application/x-shockwave-flash" data="'+ link +'" id="'+ ids +'" style="visibility: visible;"><param name="quality" value="high"><param name="allowScriptAccess" value="always"><param name="wmode" value="transparent"><param name="allowFullscreen" value="true"><param name="flashvars" value="playMovie=true&amp;auto=1"></object></div>';
			}
		}
		$('#pop').html(video);
		$('#pop').show();
	}
});