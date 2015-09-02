/**
*
* 设置cookie
*/
function hg_set_cookie(name, value, expires)
{
	var today = new Date();
	today.setTime(today.getTime());
	if (expires) expires = expires * 86400000;
	var expires_date = new Date(today.getTime() + expires); 
	document.cookie = ((cookie_id) ? cookie_id : '') +
		name + '=' + hg_urlencode(value) +
		((expires) ? ';expires=' + expires_date.toGMTString() : '') +
		((cookie_path) ? ';path=' + cookie_path : '') +
		((cookie_domain) ? ';domain=' + cookie_domain : '');
}

/**
*
* 获取cookie值
*/
function hg_get_cookie(name)
{
	name = cookie_id + name;
	var start = document.cookie.indexOf(name + '=');
	var len = name.length;
	if (start == -1 || (!start && name != document.cookie.substring(0, len))) return null;
	len += start + 1;
	end = document.cookie.indexOf(';', len);
	if (end == -1) end = document.cookie.length;
	return unescape(document.cookie.substring(len, end));
} 

function hg_urlencode(text)
{
	text = escape(text.toString()).replace(/\+/g, '%2B');
	var matches = text.match(/(%([0-9A-F]{2}))/gi);
	if (matches)
	{
		for (var matchid = 0; matchid < matches.length; matchid++)
		{
			var code = matches[matchid].substring(1,3);
			if (parseInt(code, 16) >= 128) text = text.replace(matches[matchid], '%u00' + code);
		}
	}
	text = text.replace('%25', '%u0025');
	return text;  
}

hg_init_client_info = function()
{
	var clientW = document.documentElement.clientWidth;
	var offset = 0;
	var clientH = document.documentElement.clientHeight - offset;
	hg_set_cookie("client_info[w]", clientW);
	hg_set_cookie("client_info[h]", clientH);
	var wOffset;
	if (gMenuMode != 2)
	{
		wOffset = 65;
	}
	else
	{
		wOffset = 160;
	}
	/*if ($('#livwinarea').length > 0)
	{
		$('#livwinarea').height(clientH);
		$('#livwinarea').width(clientW - wOffset);
	}  */ 
	var childFrame = document.getElementById('mainwin').contentWindow;
    childFrame = childFrame.document.getElementById('nodeFrame');
    if (childFrame)
    {
		if ( childFrame.contentWindow.hg_resize_nodeFrame ) {
			childFrame.contentWindow.hg_resize_nodeFrame();
		}
    }
    
    /*设置上传的flash按钮的位置*/
    if(top.livUpload.currentFlagId)
    {
    	top.livUpload.OpenPosition();
    }
 
};

hg_resize_nodeFrame = function (firstload)
{
	if (parent.$('#livnodewin').length > 0)
	{
		var clientW = parent.document.documentElement.clientWidth;
		if (firstload && firstload == 1)
		{
			clientW = clientW - 17;
		}
		var height = document.documentElement.scrollHeight;
		if (height < 550)
		{
			height = 550;
		}
		parent.$('#livnodewin').height(height);
        var dis = 152;
        if(parent.$('#livnodewin').attr('_isnotnode')){
            dis -= 3;
        }
		parent.$('#livnodewin').width(clientW - dis);
	}
};

hg_repos_top_menu = function()
{
	if (parent.$('#hg_parent_page_menu').length > 0)
	{
		if ($('#hg_page_menu').length > 0)
		{
			var html = $('#hg_page_menu').html();
			html = html.replace(/onclick=\"/ig, 'onclick="nodeFrame.');
			html = html.replace(/\<a /ig, '<a target="nodeFrame" ');
		}
		else
		{
			html = '';
		}
		parent.$('#hg_parent_page_menu').html(html);
	}
	if (parent.$('#_nav_menu').length > 0)
	{
		if ($('#_nav_menu').length > 0)
		{
			var html = $('#_nav_menu').html();
			var pnav = parent.$('#_nav_menu').html(html);
			pnav.find('a').each(function(){
				if(!$(this).attr('target')){
					$(this).attr('target', 'nodeFrame');
				}
				var click = '';
				if(click = $(this).attr('onclick')){
					$(this).attr('onclick', 'nodeFrame.' + click);
				}
			});
			return;
		}
	}
}
/**
* type = 0 更新最后级
* type = 1 追加最后一级
*/
hg_rebuild_nav = function(obj, type)
{
	if (!type)
	{
		type = 0;
	}
	if ($(obj).html())
	{
		var html = $(obj).html();
	}
	else
	{
		var html = '<a href="run.php?mid=' + obj[0] + '" target="nodeFrame">' + obj[1] + '</a>';
	}
	if (type == 0)
	{
		$('#hg_cur_nav_last').html(html);
	}
}

var gTasks = {}, gTasksId = 0;
hg_window_destruct = function ()
{
	var str = '';
	var hasTask = false;
	for (var tid in gTasks)
	{
		str = str + tid + ' : '  + gTasks[tid].name + '\n';
		hasTask = true;
	}
	
	if (hasTask)
	{
		return "当前系统有以下任务正在执行\n\n" + str;
	}
	
	/*刷新页面或框架时初始化flash位置*/
    if(top.livUpload.SWF)
    {
    	top.livUpload.initPosition();
    }
}

hg_add2Task = function (task)
{
	var tid = gTasksId;
	var item = {Tid : tid, name : task.name };
	gTasks[tid] = item;
	gTasksId++;
	return tid;
}

hg_taskCompleted = function (Tid)
{
	delete gTasks[Tid];
	return true;
}
function hg_getvideoinfo(maxid,trans_ids,vod_leixing,vod_sort_id)
{
	var frame = document.getElementById("mainwin");
	var mpara = '';
	var transpara = '';
	if (maxid && maxid != -1)
	{
		mpara = '&since_id=' + maxid;
		if(vod_leixing != -1)
		{
			mpara += '&vod_leixing=' + vod_leixing;
		}
		
		if(vod_sort_id != -1)
		{
			mpara += '&vod_sort_id=' + vod_sort_id;
		}
	}
	
	transpara = '&transids=' + trans_ids;
	
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		if (frame.$('#vodlist').attr('id'))
		{
			var mid = frame.gMid;
			var html = '<scr' + 'ipt id="request_videoinfo" type="text/javascript" src="run.php?mid='+mid+'&a=getinfo&ajax=1' + mpara + transpara + '"></scr' + 'ipt>';
			frame.$('head').append(html);
		}
	}
	else
	{
		var html = '<scr' + 'ipt id="request_videoinfo" type="text/javascript" src="run.php?mid='+gMid+'&a=getinfo&ajax=1' + mpara + transpara + '"></scr' + 'ipt>';
		$('head').append(html);
	}
}

function hg_add_list(html)
{
	var frame = document.getElementById("mainwin");
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		if (frame.$('#vodlist').attr('id'))
		{
			frame.$('#vodlist').prepend(html);
			frame.correctPosition();
			hg_resize_nodeFrame();
		}
	
	}
	else
	{
		$('#vodlist').prepend(html);
		correctPosition();
		hg_resize_nodeFrame();
	}
}

function hg_create_collect(mid,id)
{
	 var url = "./run.php?mid="+mid+"&a=create_collect&id="+id;
	 hg_ajax_post(url);
}

function hg_batchremove(obj, op, name, need_confirm, primary_key, para, request_type,collect_id)
{
	var tmp = obj;
	obj = hg_find_nodeparent(obj, 'FORM');
	var ids = hg_get_checked_id(obj);

	if(typeof jAlert != 'undefined'){
		if(!ids){
			jAlert('请选择要' + name + '的记录', name + '提醒').position(tmp);
			return false;
		}

		var wrapcallback = function(){
			primary_key = primary_key || 'id';
			url = "./run.php?mid="+gMid+"&a="+op+"&collect_id="+collect_id;
			para && (url += para);
			var data = {};
			data[primary_key] = ids;
			if(request_type == 'ajax'){
				hg_request_to(url, data);
			}else{
				location.href = url + '&id=' +data.id;
			}
		};

		if(need_confirm){
			jConfirm('您确认批量' + name + '选中记录吗？', name + '提醒', function(result){
				if(!result) return false;
				wrapcallback();
			}).position(tmp);
		}else{
			wrapcallback();
		}
	}else{
		if(!ids)
		{
			alert('请选择要' + name + '的记录');
			return false;
		}
		if (need_confirm && !window.confirm('您确认批量' + name + '选中记录吗？'))
		{
			return false;
		}
		if (!primary_key)
		{
			primary_key = 'id';
		}
		
		url = "./run.php?mid="+gMid+"&a="+op+"&collect_id="+collect_id;

		if (para)
		{
			url = url + para;
		}

		var data = {};
		data[primary_key] = ids;
		if (request_type == 'ajax')
		{
			hg_request_to(url, data);
		}
		else
		{
			document.location.href = url + '&id=' +data.id;
			
		}
	}
	return false;
}


function  hg_get_size()
{
	var left = parseInt($(window).width())/2 - 331;
	var top =  parseInt($(window).height())/2 - 275 + $(window).scrollTop();
	
	$("#player_container_o").css({"left":left+"px","top":top+"px"});
}

function hg_close_video()
{
	 $("#player").remove();
	 $("#player_container_o").removeClass("player_style_o");
	 $("#player_container_c").removeClass("player_style_c");
	 $("#close_player").css("display","none");
	 $("#player_container_c").html("<div id='player'></div>");
}

function hg_fold(obj)
{
    var status = $(obj).attr("status");
    var id = $(obj).attr("id");
    if(status == "0")
    {
  	  $("#m_"+id).show();
  	  $(obj).attr("status","1");
    }
    else
    {
  	  $("#m_"+id).hide();
  	  $(obj).attr("status","0");
    }
	hg_resize_nodeFrame();
 }

function hg_chang_pic(obj,pic)
{
	$(obj).attr("src",RESOURCE_URL+pic);
}

function  hg_back_pic(obj,pic)
{
	$(obj).attr("src",RESOURCE_URL+pic);
}


function hg_single_video(obj,type)/*type是发布的类型(手机还是网站)*/
{
	var frame = document.getElementById("mainwin");
	var para = '';
	if(type)
	{
		para = '&pubinfo='+type;
	}
	
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		if (frame.$('#vodlist').attr('id'))
		{
			var mid = frame.gMid;
			var url = "./run.php?mid="+mid+"&a=form_addlist&vodid="+obj.vodid+"&row_id="+obj.id+para;
			hg_ajax_post(url);
		}
	}
	else
	{
		var url = "./run.php?mid="+gMid+"&a=form_addlist&vodid="+obj.vodid+"&row_id="+obj.id+para;
		hg_ajax_post(url);
	}
	
}


/*寻找页面元素,按id寻找
 * ①如果当前层级有该元素,就返回当前层级的元素
 * ②如果当前层级没有该元素,就寻找最里层的子级元素
 * 返回jquery对象
 * 
 * */
function  hg_findFrameElements(id)
{
	if($('#'+id).attr('id'))
	{
		return $('#'+id);
	}
	
	var frame = document.getElementById("mainwin");
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		if (frame.$('#'+id).attr('id'))
		{
			return frame.$('#'+id);
		}
		else
		{
			return false;
		}
	}
}

/*找到nodeframe*/
function hg_findNodeFrame()
{
	var frame = document.getElementById("mainwin");
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
		return frame;
	}
	else
	{
		return false;
	}
}

/*添加一行图集列表的回调函数*/
function hg_put_new_tujilist(html)
{
	var frame = hg_findNodeFrame();
	if(frame)
	{
		frame.$('#tujilist').prepend(html);
	}
	else
	{
		$('#tujilist').prepend(html);
	}
}

$(document).ready(function()
{
	var gCurPicIndex = 0;
	var gDialog;
	hg_showpage = function(link)
	{
		hg_showwindialog();
		return false;
	};

	hg_showwindialog = function()
	{
		if($("#livwindialog").html() != null)	
		{
			gDialog = new AlertBox("livwindialog"),locks = false;
			function lockup(e){ e.preventDefault(); };
			function lockout(e){ e.stopPropagation(); };
			$("#livwindialogClose").click(function(){ 
				gDialog.close(); 
				$("#livwindialogbody").html('');
				if(livUpload.SWF)
				{
					livUpload.initPosition();
					livUpload.currentFlagId  = livUpload.moreFlagId;
				}
                
			});
			locks = true;
			var clientW = document.documentElement.clientWidth;
			var top = document.documentElement.scrollTop;
			$("#livwindialog").css('top', (top + 60) + 'px');
			$("#livwindialog").css('left', (clientW / 2 - 250) + 'px');
			gDialog.show();
		}
	};

	hg_clear = function()
	{
	};
	
	window.onclick = hg_clear;
	hg_ajax_batchpost_select = function (obj, op, name, need_confirm, primary_key)
	{
		if ($(obj).val() == -1)
		{
			return;
		}
		var para = '&' + op + '=' + $(obj).val();
		hg_ajax_batchpost(obj, op, name, need_confirm, primary_key, para);
	}
	
	hg_ajax_batchpost = function (obj, op, name, need_confirm, primary_key, para, request_type ,callback)
	{
		var tmp = obj;
		obj = hg_find_nodeparent(obj, 'FORM');
		var ids = hg_get_checked_id(obj);
		if(typeof jAlert != 'undefined'){
			if(!ids){
				jAlert('请选择要' + name + '的记录', name + '提醒').position(tmp);
				return false;
			}

			var wrapcallback = function(){
				if(!primary_key){
					primary_key = 'id';
				}
				
				url = gBatchAction[op];
				para && (url += para);
				var data = {};
				data[primary_key] = ids;
				if(request_type == 'ajax'){
					if(callback){
						hg_request_to(url, data , '', callback);
					}else{
						hg_request_to(url, data);
					}
				}else{
					location.href = url + '&id=' +data.id;
				}
			};

			if(need_confirm){
				jConfirm('您确认批量' + name + '选中记录吗？', name + '提醒', function(result){
					if(!result) return false;
					
					wrapcallback();
				}).position(tmp);
			}else{
				wrapcallback();
			}

		}else{
			if(!ids)
			{
				alert('请选择要' + name + '的记录');
				return false;
			}
			if (need_confirm && !window.confirm('您确认批量' + name + '选中记录吗？'))
			{
				return false;
			}
			if (!primary_key)
			{
				primary_key = 'id';
			}
			
			url = gBatchAction[op];
			if (para)
			{
				url = url + para;
			}
			var data = {};
			data[primary_key] = ids;
			if (request_type == 'ajax')
			{
				if(callback)
				{
					hg_request_to(url, data ,'',callback);
				}
				else
				{
					hg_request_to(url, data);
				}
				
			}
			else
			{
				document.location.href = url + '&id=' +data.id;
				//alert(url + '&id = ' +data.id);
			}
		}


		return false;
	}
	hg_ajax_post_select = function(obj, url,  name, need_confirm)
	{
		var state = $(obj).val();
		if (state == -1)
		{
			return;
		}
		var para = $(obj).attr('name');
		para = para.split('__');
		url = url + '&' + para[0] + '=' + state + '&' + para[1];
		hg_ajax_post(url, name, need_confirm);
	}
	hg_ajax_post = function (obj, name, need_confirm,callback)
	{
		if(typeof jAlert != 'undefined'){
			var wrapcallback = function(){
				url = obj.href || obj;
				var data = {};
				if(callback){
					hg_request_to(url, data, "", callback);
				}else{
					hg_request_to(url, data);
				}
			};
			if(need_confirm){
				jConfirm('您确认' + name + '此条记录吗？', name + '提醒', function(result){
					if(!result) return false;
					wrapcallback();
				}).position(obj);
			}else{
				wrapcallback();
			}
		}else{
			if (need_confirm)
			{
				if (!window.confirm('您确认' + name + '此条记录吗？'))
				{
					return false;
				}
			}
			if (typeof obj == 'string')
			{
				url = obj;
			}
			else
			{
				url = obj.href;
			}
			var data = {};
			if(callback)
			{
				hg_request_to(url, data,"",callback);
			}
			else
			{
				hg_request_to(url, data);
			}
		}
		
		return false;
	}

	hg_change_text = function (id)
	{
		alert(id);
	}

	hg_show_template = function (html)
	{
		if (top)
		{
			top.hg_addDialogHtml(html);
			top.hg_showwindialog();
		}
		else
		{
			hg_addDialogHtml(html);
			hg_showwindialog();
		}
	}

	hg_show_error = function(html)
	{
		if (top)
		{
			top.hg_addDialogHtml(html);
			top.hg_showwindialog();
		}
		else
		{
			hg_addDialogHtml(html);
			hg_showwindialog();
		}
	}


	hg_addDialogHtml = function (html)
	{
		$("#livwindialogbody").html(html);
	}
	hg_selected_pic = function(index, obj, src)
	{
		$('#hg_pic_' + gCurPicIndex).removeClass('cur');
		gCurPicIndex = index;
		$('#hg_pic_' + gCurPicIndex).addClass('cur');
		if (src)
		{
			$('#' + obj).val(src);
		}
	}
	
	hg_ajax_submit = function (formname,beforeSubmit,success,callback)
	{	
		var url = $('#' + formname).attr('action');
		url = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'ajax=1';
		if (beforeSubmit)
		{
			beforeSubmit = beforeSubmit + "('" + formname + "')";
		}
		else
		{
			beforeSubmit = '';
		}
		
		var options = {
     	 	url: url,
     	 	dataType: 'html',
      		success: function(data) {
         	// 'data' is an object representing the the evaluated json data
	         	//print_r(data);	
			if(callback)
			{
				var fn = callback + '(' + data + ')';
				eval(fn);
			}
			
			hg_call_back(data);
      		
		    },
      		beforeSubmit : function (formname)
      		{
      			eval(beforeSubmit);
      		}
		};
		
		hg_msg_show('正在发送请求......', 1);	
		$('#' + formname).ajaxSubmit(options);
		return false;
	}
	hg_dialog_close = function ()
	{
		top.gDialog.close();
	}
	//推荐内容后callback事件
	hg_recommend_call = function (id)
	{
		hg_dialog_close();
	}
	//权限设置后的callback事件
	hg_prms_setting_call = function ()
	{
		hg_dialog_close();
	}
	hg_check_recomend = function (formname)
	{
		var form;
		eval("form = document." + formname);
		if(form.hg_columnid.value == 0)
		{
			hg_msg_show('请选择要推荐到的栏目', 1);
			return false;
		}
		if(form.hg_title.value == '')
		{
			hg_msg_show('请设置推荐内容的标题', 1);
			return false;
		}
	}
	
	hg_set_dom_html = function (html, dom)
	{
		$("#" + dom).html(html);
	}
	if ($("#checkall").length > 0)
	{
		$("#checkall").bind('click',function(){hg_checkall(this)});//绑定全选事件
	}
	
	
});