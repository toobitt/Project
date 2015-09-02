/*
var i=0;
var gCurrentlist = 1;
var ghasChangedColor = [];
*/
function hg_show_column(counter){
	if(hg_itoggle[counter]==0)
	{
		$("#"+counter+"edit").attr('class','edit_click');
		hg_openall(counter);
		hg_itoggle[counter]=1;
	}
	else
	{
		hg_closeall(counter);
	}
}
 function hg_show_mousemove(counter){
	 if(hg_itoggle[counter]==0)
	 {
		$("#"+counter+"edit").attr('class','edit_move');
	 }
	 else
	 {
		 hg_openall(counter);
	 }
 }
function hg_show_mouseout(counter){
	if(hg_itoggle[counter]==0)
	 {
		hg_closeall(counter);
	 }
	 else
	 {
		 hg_openall(counter);
	}
 }
 function  hg_closeall(counter)
 {
	hg_itoggle[counter]=0;

	$("#"+counter+"column").slideUp('fast',function(){
		$("#"+counter+"edit").attr('class','edit');
		$("#"+counter+"show").attr('class','show clear');
		hg_resize_nodeFrame();
	});
}

function hg_openall(counter)
{
	$("#"+counter+"column").slideDown('fast',function(){hg_resize_nodeFrame();});
	$("#"+counter+"show").attr('class','show_move clear');

}
function hg_getcol_childs(event,counter,formname,formtype, url,fid, deep)
{
	if(deep)
	{
		gCurrentlist[counter] = deep = parseInt(deep);
	}
	if(!fid)
	{
		fid = 0;
	}
	gColTempFid[counter] = parseInt(fid);
	hg_toggle_color(fid, gCurrentlist[counter], counter);
	hg_cellBubble(event);
	if(parseInt($('#'+counter+'sourcefid_'+(deep+1)).text()) == fid)
	{
		hg_roll_col(gCurrentlist[counter], 1, counter);
		return;
	}
	if(url.substring(parseInt(url.indexOf('?')+1)))
	{
		url = url + '&';
	}
	url = url+'fid='+fid+'&counter='+counter+'&formtype='+formtype+'&formname='+formname+'&formurl='+url;
	var _siteid = $('#'+counter+'siteid').val();
	if(_siteid)
	{
		url += 'multi='+_siteid;
	}
	hg_request_to(url,{}, '','hg_show_childs');
}
//flag为1代表是更换平台 手机和网站
function hg_show_childs(data ,flag)
{
	var counter = data['para']['counter'];
	var formname = data['para']['formname'];
	var formtype = data['para']['formtype'];
	var formurl = data['para']['formurl'];
	var nexElementIndex = parseInt(gCurrentlist[counter])+1;
	var html = '';
	var sourcefid = data['para']['fid'];
	//切换平台
	if(flag)
	{
		html = '<li class="first"><span class="checkbox"></span><a href="##">最近使用<strong>»</strong></a></li>';
		hg_clear_div(counter);
		nexElementIndex = 1;
		for(i=gCurrentlist[counter];i>3;i--)
		{
			$('#'+counter+'level_'+i).remove();
		}
		$('#'+counter+'allcol').css('margin-left','0');
		html += hg_build_col_li(data, nexElementIndex, counter, formname, formtype, formurl, sourcefid);
		$('#'+counter+'level' + nexElementIndex + 'col').html(html).hide().fadeIn('fast');
		return;
	}
	//不切换平台
	if($('#'+counter+'level_'+nexElementIndex).length)
	{
		html += hg_build_col_li(data, nexElementIndex, counter, formname, formtype, formurl, sourcefid);
		if(nexElementIndex == 2)
		{
			$('#'+counter+'level3col').html('');
		}
		if(!html)
		{
			$('#'+counter+'level' + nexElementIndex + 'col').html('');
			hg_roll_col(gCurrentlist[counter], 0,counter);
			return;
		}
		$('#'+counter+'level' + nexElementIndex + 'col').html(html).hide().fadeIn('fast');
	}
	else
	{
		hg_build_levelNcol(data, nexElementIndex, counter, formname, formtype, formurl, sourcefid);
	}
	hg_roll_col(gCurrentlist[counter], 1, counter);
}
function hg_toggle_color(aid,divid, counter)
{
	if(ghasChangedColor[counter][divid])
	{
		if(ghasChangedColor[counter][divid] == aid)
		{
			$('#'+counter+'hg_colid_'+aid).addClass('cur');
			return;
		}
		$('#'+counter+'hg_colid_'+aid).addClass('cur');
		$('#'+counter+'hg_colid_'+ghasChangedColor[counter][divid]).removeClass('cur');
		ghasChangedColor[counter][divid] = aid;
	}
	else
	{
		ghasChangedColor[counter][divid] = aid;
		$('#'+counter+'hg_colid_'+aid).addClass('cur');
	}
}
function hg_cellBubble(event)
{
	event = event || window.event;
	if(event.stopPropagation)
	{
	   event.stopPropagation();
	}else
	{
	   event.cancelBubble = true;
	}
}
function hg_build_col_li(obj, Index, counter, formname, formtype, formurl, sourcefid)
{
	var str = '';
	delete obj.para;
	if(!obj) return str;
	for(var n in obj)
	{
		var html = '<li>';
		var onclick = '';
		var strong = '';
		var checked = '';
		if($('#'+counter+'hg_hidden_'+obj[n]['id']).length)
		{
			checked = 'checked = "checked"';
		}
		html += '<input '+checked+' name="_'+formname+'" type="'+formtype+'" value="'+obj[n]['id']+'" onclick=hg_selected_col("'+obj[n]['name']+'",this.value,event,"'+counter+'","'+formname+'","'+formtype+'") id="'+counter+'checkbox_'+obj[n]['id']+'" class="checkbox" />';
		/*a = '<a href="javascript:void(0)">'+obj[n]['name'];*/
		if(parseInt(obj[n]['is_last']) == 1)
		{
			a = '<a class="overflow" href="javascript:void(0)" onclick=hg_getcol_childs(event,"'+counter+'","'+formname+'","'+formtype+'","'+formurl+'",'+obj[n]['id']+','+Index+') ondblclick=hg_coldbclick("'+obj[n]['name']+'",'+obj[n]['id']+',event,"'+counter+'","'+formname+'","'+formtype+'") id="'+counter+'hg_colid_'+obj[n]['id']+'">'+obj[n]['name'];
			strong = '<strong>»</strong>';
		}
		else
		{
			a = '<a class="overflow" href="javascript:void(0)" onclick=hg_coldbclick("'+obj[n]['name']+'",'+obj[n]['id']+',event,"'+counter+'","'+formname+'","'+formtype+'") id="'+counter+'hg_colid_'+obj[n]['id']+'">'+obj[n]['name'];
		}
		html += a+strong;
		html += '</a>';
		html += '</li>';
		str += html;
	}
	if(!str) return str;
	str += '<span id="'+counter+'sourcefid_'+Index+'" style="display:none">'+sourcefid+'</span>';
	return str;
}
function hg_build_levelNcol(data, Index, counter, formname, formtype, formurl, sourcefid)
{
	var li = hg_build_col_li(data, Index, counter, formname, formtype, formurl, sourcefid);
	if(!li)
	{
		return;
	}
	var html = '<div class="pub_div" id="'+counter+'level_'+Index+'" onclick=hg_roll_col(this.id,0,"'+counter+'") showit="no"><ul id="'+counter+'level'+Index+'col">'+li+'</ul></div>';
	$('#'+counter+'allcol').append(html);
}
function hg_roll_col(n, allow, counter)
{
	if(!allow)
	{
		allow = 0;
	}
	if(typeof n == 'string')
	{
		var id = n.split('_');
		var index = parseInt(id[id.length-1]);
	}
	else
	{
		var index = n;
	}
	var currentid = '#'+counter+'level_'+index;
	var nextid = '#'+counter+'level_'+(index+1);
	var preid = '#'+counter+'level_'+(index-1);
	var nextid_display = $(nextid).attr('showit');
	var preid_display = $(preid).attr('showit');
	if(nextid_display == 'yes' && preid_display == 'yes')
	{
		return;
	}
	if(nextid_display == 'no' && preid_display == 'yes'  && allow)
	{
		$('#'+counter+'allcol').animate({
			marginLeft : -175*(index-2)
			}, 500);
		$('#'+counter+'level_'+(index-2)).attr('showit', 'no');
		$(nextid).attr('showit', 'yes');
		//hg_resetChangedColor(counter, counter+'level_'+(index-2));
		return;
	}
	if(nextid_display == 'yes' && preid_display == 'no')
	{
		$('#'+counter+'level_'+(index+2)).attr('showit', 'no');
		$('#'+counter+'allcol').animate({
			marginLeft : parseInt($('#'+counter+'allcol').css('margin-left')) + 175
		})
		$(preid).attr('showit', 'yes');
		hg_resetChangedColor(counter, index+2);
	}

}
function hg_selected_col(colname, id, event, counter, formname, formtype)
{
	hg_cellBubble(event);
	if(formtype=="checkbox")
	{
		if($('#'+counter+'checkbox_'+id).attr('checked'))
		{
			var li = '<li id="'+counter+'li_'+id+'"><span class="a"></span><span class="b"></span><span class="c overflow">'+colname+'</span><span class="close" onclick=hg_cancell_selected('+id+',"'+counter+'")></span></li>';
			//var li = '<li onclick=hg_cancell_selected('+id+',"'+counter+'") id="'+counter+'li_'+id+'">'+colname+'</li>';
			$('#'+counter+'column_id').append(li);
			var hidden = '<input type="hidden" name="'+formname+'" value="'+id+'" id="'+counter+'hg_hidden_'+id+'">';
			$('#'+counter+'hg_selected_hidden').append(hidden);
		}
		else
		{
			$('#'+counter+'li_'+id).remove();
			$('#'+counter+'hg_hidden_'+id).remove();
		}
	}
	else
	{
		//var li = '<li onclick=hg_cancell_selected('+id+',"'+counter+'") id="'+counter+'li_'+id+'">'+colname+'</li>';
		if($('#'+counter+'checkbox_'+id).attr('checked'))
		{
			var li = '<li id="'+counter+'li_'+id+'"><span class="a"></span><span class="b"></span><span class="c overflow">'+colname+'</span><span class="close" onclick=hg_cancell_selected('+id+',"'+counter+'")></span></li>';
			$('#'+counter+'column_id').html(li);
			var hidden = '<input type="hidden" name="'+formname+'" value="'+id+'" id="'+counter+'hg_hidden_'+id+'">';
			$('#'+counter+'hg_selected_hidden').html(hidden);
		}
		else
		{
			$('#'+counter+'li_'+id).remove();
			$('#'+counter+'hg_hidden_'+id).remove();
		}
	}
}
function hg_cancell_selected(id, counter)
{
	$('#'+counter+'checkbox_'+id).attr('checked', false);
	$('#'+counter+'li_'+id).remove();
	$('#'+counter+'hg_hidden_'+id).remove();
}
function hg_resetChangedColor(counter, divid)
{
	var aid = ghasChangedColor[counter][divid];
	if(!aid) return;
	$('#'+counter+'hg_colid_'+aid).removeClass('cur');
	delete ghasChangedColor[counter][divid];
}
function hg_coldbclick(colname, id, event, counter, formname, formtype)
{
	if($('#'+counter+'checkbox_'+id).attr('checked'))
	{
		$('#'+counter+'checkbox_'+id).attr('checked', false);
	}
	else
	{
		$('#'+counter+'checkbox_'+id).attr('checked', true);
	}
	hg_selected_col(colname, id, event, counter, formname, formtype);
}
function hg_get_coltype(counter)
{
	if($('#'+counter+'coltype').html())
	{
		$('#'+counter+'coltype').show();
	}
}
function hg_change_site(counter)
{
	$('#'+counter+'coltype').children().first('span').click();
}
function hg_change_coltype(counter, n, formname,formtype,url, obj, siteid)
{
	siteid = $('#'+counter+'siteid').val() ? $('#'+counter+'siteid').val() : siteid;
	var url = url+'&counter='+counter+'&formtype='+formtype+'&formname='+formname+'&type='+n+'&formurl='+url+'&siteid='+siteid;
	hg_request_to(url,{}, '','hg_show_coltype');
	if(hg_itoggle[counter] == 0)
	{
		hg_openall(counter);
		hg_itoggle[counter] = 1;
	}
	$('#'+counter+'type').text($(obj).text());
	$('#'+counter+'coltype').hide();
	if($('#'+counter+'changecoltype').length)
	{
		$('#'+counter+'changecoltype').val(n);
	}
}
function hg_show_coltype(data)
{
	if(data == 0)
	{
		return;
	}
	hg_show_childs(data, 1);
}
function hg_clear_div(counter)
{
	for(i = 1; i<=3;i++)
	{
		$('#'+counter+'level'+i+'col').html('');
		$('#'+counter+'level_'+i).attr('showit','yes');
	}
}