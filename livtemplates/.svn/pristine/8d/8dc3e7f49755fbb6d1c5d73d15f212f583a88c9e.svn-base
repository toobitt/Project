function frame_inner_Height(add_height){
	var add_height = add_height ? add_height : 0;
	var out_curHeight = $(parent.document).find("#livnodewin").height();
	$(parent.document).find("#livnodewin").height(document.body.scrollHeight + add_height);
}
function toggleElem(_this, elem, toggleclass){
		elem = (elem == ".publish_box") ? $(_this).parent().next() : elem;
		$(_this).toggleClass(toggleclass);
		$(elem).toggle();
		frame_inner_Height($(elem).height());
}
function hg_local_matrial_show()
{
	$('#add_collect_form').hide();
	$('#local_material_contianer').show();
	$('#remote_two').hide();
	$('#ad_js_code').hide();
}
function hg_remote_matrial_show()
{
	$('#add_collect_form').hide();
	$('#local_material_contianer').hide();
	$('#remote_two').show();
	$('#ad_js_code').hide();
}
function hg_js_matrial_show()
{
	$('#add_collect_form').hide();
	$('#ad_js_code').show();
	$('#local_material_contianer').hide();
	$('#remote_two').hide();
	frame_inner_Height();
}

function hg_ad_text()
{
	var bool = $('input[name=ad_text]').attr('checked');
	if(bool)
	{
		ad_mtype_select('select_matrial_five');
	}
	else
	{
		$('input[name=mtype]').val('');
	}
}
function switch_ad_mtype(ad_mtype)
{
	switch(ad_mtype)
	{
		case 'select_matrial_one':hg_local_matrial_show();break;
		case 'select_matrial_two':hg_remote_matrial_show();break;
		case 'select_matrial_three':hg_js_matrial_show();break;
		case 'select_matrial_four':show_video();break;
		case 'select_matrial_five':
		{
			$('input[name=mtype]').val('text');
			$('input[name=ad_text]').attr('checked',true)
			break;
		}
		default:;
	}
	var boxid = ['one', 'two', 'three', 'four'];
	for (var n in boxid)
	{
		var index = 'select_matrial_' + boxid[n];
		if( index == ad_mtype)
		{
			$("#"+index).css("background", "#3b3b3b");
		}
		else
		{
			$("#"+index).css("background", "#5b5b5b");
		}
	}
	
}
function ad_mtype_select(ad_mtpye)
{
	var mtype = $('input[name=mtype]').val();
	var m = $('#material_url').val();
	if(mtype || m)
	{
		jConfirm('广告素材已经录入，是否清除素材重新选择？', '用户操作提示', function(res){
			if(res)
			{
				$('input[name=mtype]').val('');
				$('#material_url').val('');
				$('#thumbnails').html('');
				$('textarea[name=js_code]').val('');
				$('input[name=ad_text]').attr('checked',false)
				switch_ad_mtype(ad_mtpye);
			}
			else if(ad_mtpye == 'select_matrial_five')
			{
				$('input[name=ad_text]').attr('checked',false);
			}
		})
	}
	else
	{
		switch_ad_mtype(ad_mtpye);
	}
	
}
$(function(){
	$('#select_matrial_one').click(function(){
		ad_mtype_select('select_matrial_one');
		}
	);
	$('#select_matrial_two').click(
		function(){
			ad_mtype_select('select_matrial_two');
		});
	$('#select_matrial_three').click(
		function(){
			ad_mtype_select('select_matrial_three');
		});
	$('#select_matrial_four').click(
		function(){
			ad_mtype_select('select_matrial_four');
		});
	
	$('#select_matrial_five').click(
		function(){
			hg_ad_text();
		});
});

function submit_form_verify()
{
	var title = $.trim($('input[name="title"]').val());
	if(!title)
	{
		return false;
	}
}
function add_remote_material()
{
	var value = $.trim($('#remote_matrial').val());
	if(value == 'http://将图片、flash链接粘贴到这里' || value == '')
	{
		return false;
	}
	if(!/^http:\/\/.*/i.test(value))
	{
		return;
	}
	$('#material_url').val(value);
	var mtype = '';
	pos = value.indexOf('?');
	if(pos != -1)
	{
		prefix = value.substring(0,pos);
	}
	else
	{
		prefix = value;
	}
	switch(prefix.match(/\.([^\.]+)$/i)[1].toLowerCase())
	{
		case 'swf':mtype='flash';break;
		case 'jpg':
		case 'jpeg':
		case 'png':
		case 'gif':mtype='image';break;
		default:{alert('暂不支持此类型文件预览');$('#thumbnails').html('');}
	}
	url2preview(value, mtype)
}
function show_progress_bar_callback(json)
{
	//alert(json[0].status);
	if(json[0].errorno === '002')
	{
		$('#submit_ok').attr('disabled', true);
		$('#thumbnails').html("<img src='"+RESOURCE_URL+"loading.gif' /><span>视频转码中请等待，完成后生成广告缩略图……</span>");
		setTimeout('show_progress_bar('+gVid+')', 3000);
	}
	if(!json[0].img_src)
	{
		setTimeout('show_progress_bar('+gVid+')', 3000);
	}
	else
	{
		$('#material_url').val(gVid);
		$('#thumbnails').html("<img src='"+json[0].img_src+"'/>");
	}
}
function url2preview(murl, mtype)
{
	if(!mtype)
	{
		return;
	}
	$('input[name=mtype]').val(mtype);
	switch(mtype)
	{
		case 'flash':addSWF(murl);break;
		case 'video':;
		case 'image':addImage(murl);break;
		default:{alert('暂不支持此类型文件预览');$('#thumbnails').html('');}
	}
}
function addImage(value)
{
	$('#thumbnails').html("<img src='"+value+"'/>");
}
function addSWF(value)
{
	var swf_html = '<object type="application/x-shockwave-flash" data="'+value+'" width="640" height="510"><param name="movie" value="'+value+'"><param value="transparent" name="wmode"></object>';
	$('#thumbnails').html(swf_html);
}

function hg_replace_advpos(template,id)
{
	$('#advsettings_'+id).html('');
	$('#advpara_'+id).html('');
	$('#advpos_span_'+id).html(template);
}

function hg_add_publish()
{
	var html = $('<div></div>').append($('#li_clone_obj').children('li').first().clone().removeAttr('style')).html();
	var re = /\"list_([0-9]*)\"/g;	
	var arr = re.exec(html);
	var pid = arr[1];
	var reg = eval("/"+pid+"/ig");
	var new_id = hg_rand_num(10);
	html = html.replace(reg, new_id);
	$('#alllist').append(html);
	$('#plus_li').hide();
	frame_inner_Height(50);
}
function hg_del_publish(htmlobj)
{
	$(htmlobj).parents('li[handler="catch"]').remove();
	if($('#alllist').children('li').length == 1)
	{
		$('#plus_li').fadeIn(1000);
	}
}
function hg_replace_advpara(template, id)
{
	$('#advpara_'+id).html(template);
	hg_resize_nodeFrame();
}
function hg_next_publish()
{
	$('#goon').val('1');
	var content_id = $('#ad_id').val();
	if(content_id)
	{
		window.location.href="run.php?mid={$_INPUT['mid']}&a=advanced_settings&content_id="+content_id;
	}
	else
	{
		$('#content_form').submit();
	}
}
function hg_get_advsettings(url, id)
{
	//修改隐藏域 这里的隐藏域是为了解决高级参数数据丢失问题
	$('#needupdate_'+id).val('1');
	if($('#advsettings_'+id).html())
	{
		if($('#advsettings_'+id).css('display')=='block')
		{
			$('#advsettings_'+id).hide(200);
			return;
		}
		else
		{
			$('#advsettings_'+id).show(200);
		}
		return;
	}
	hg_request_to(url);
}
function hg_show_advsettings(template, id)
{
	$('#advsettings_'+id).html(template);
}
function hg_cancel_publish()
{
	$('#needconfirm').val('1');
	$('#publish_form').submit();
}
function hg_publish_submit()
{
	if($('#needconfirm').val() == 1)
	{
		if(confirm('确定取消该广告的所有发布吗？'))
		{
			$('#alllist').html('');
			return true;
		}
		$('#needconfirm').val('0');
		return false;
	}
	return true;
}
function hg_reset_publish()
{
	if(confirm('确定清空所填写的所有数据!?'))
	{
		$('#alllist').children('li:gt(0)').remove();
		$('#plus_li').fadeIn(1000);
	}
}
//同一广告不能发布在同一客户端的同一广告位 用此函数做检测
function hg_ad_check(id)
{
	var pos = $('#advpos_'+id).val();
	var group = $('#group_'+id).val();
	//alert(pos + '  '+ group);
	$('#alllist').find('input[id^="group_"]').each(function(){
		objid = $(this).attr('id').replace('group_', '');
		if(objid == id)
		{
			return;
		}
		g = $(this).val();
		p = $('#advpos_'+objid).val();
		//alert(g +'  '+p);
		if(g == group && p== pos)
		{
			alert("此广告位已被占用!");
			$('#advpos_ul_'+id).children('li:first').find('a').click();
		}
	})
}
function hg_add_para()
{
	var parali = $('#adpos_para').children("li:last").clone();
	parali.children('a:last-child').remove();
	$('#adpos_para').append(parali.append('<a title="删除" href="javascript:void(0)" onclick="hg_delete_para(this)">-</a>'));
	hg_resize_nodeFrame();
}
function hg_delete_para(obj)
{
	$(obj).parent('li').remove();
	var para_box = $('#adpos_para');
	hg_resize_nodeFrame();
}
//广告发布加载函数
function hg_getadvpos(id)
{
	var flag = $('#group_'+id).val();
	if(!flag)
	{
		return;
	}
	var url = 'run.php?mid='+gMid+'+&a=getadvpos&flag='+flag+'&id='+id;
	hg_request_to(url);/**/
}
function hg_advpos_para(id, ani_id, p)
{
	ani_id = parseInt(ani_id);
	var pid = $('#advpos_'+id).val();
	if(ani_id)
	{
		/*请求效果参数*/
		var url = 'run.php?mid='+gMid+'&a=getadvpara&ani_id='+ani_id+'&pid='+pid+'&id='+id+'&groupflag='+$('#group_'+id).val();
	}
	else
	{
		/*请求效果*/
		var p = p ? p : 0;
		var search = $('#serach_con').val();
		if(!search)
		{
			search = '';
		}
		var url = 'run.php?mid='+gMid+'&a=getadvpara&pid='+pid+'&id='+id+'&groupflag='+$('#group_'+id).val()+'&page='+p+'&condition='+search;
	}
	hg_request_to(url);
	hg_resize_nodeFrame();
}
function hg_advpos_para_search(id)
{
	var pid = $('#advpos_'+id).val();
	var search = $('#serach_con').val();
	var url = 'run.php?mid='+gMid+'&a=getadvpara&pid='+pid+'&id='+id+'&groupflag='+$('#group_'+id).val()+'&condition='+search;
	hg_request_to(url);
}
function hg_cancell_select_ani(id)
{
	$('#advpos_ul_'+id).find('a[attrid="0"]').click();
}