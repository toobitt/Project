var gDragMode = false;
function object2str(arr)
{
	if (typeof(arr) == 'string')
	{
		return arr;
	}
	var s = "Array(\n";
	for (var k in arr)
	{
		if (typeof(arr[k]) == 'object')
		{
			s = s + "\t" + k + ' => ' + object2str(arr[k]);
		}
		else
		{
			s = s + "\t" + k + ' => ' + arr[k] + "\n";
		}
	}
	s = s + ')';
	return s;
}

function print_r(arr)
{
	s = object2str(arr);
	alert(s);
}


hg_checkall = function(obj, checkedclass, defaultclass)
{
	if (!checkedclass)
	{
		checkedclass = 'cur';
	}

	if (!defaultclass)
	{
		defaultclass = '';
	}
	var liobj;
	var rowtag = $(obj).attr('rowtag');
	if (!rowtag || rowtag == '')
	{
		rowtag = 'TR';
	}
	if ($(obj).attr("checked"))
	{
		$('input[name="' + obj.value + '[]"]').each(function() 
		{
			$(this).attr("checked", true);
			liobj = hg_find_nodeparent(this, rowtag);
			$(liobj).attr("class", checkedclass);
		});
	}
	else
	{
		$('input[name="' + obj.value + '[]"]').each(function() 
		{
			$(this).attr("checked", false);
			liobj = hg_find_nodeparent(this, rowtag);
			$(liobj).attr("class", defaultclass);
		});
	}
	return false;
};

var gRowCls = '';
hg_row_interactive = function(obj, onout, classname, defaultclass, checkobj)
{
	if (!classname)
	{
		classname = 'hover';
	}
	if (!checkobj)
	{
		checkobj = 'infolist';
	}
	if (!defaultclass)
	{
		defaultclass = '';
	}
	if (!obj)
	{
		return;
	}
	if (onout == 'on')
	{
		gRowCls = $(obj).attr('class');
		if (!gRowCls)
		{
			gRowCls = '';
		}
		$(obj).attr('class', classname);
	}
	else if (onout == 'click')
	{
		if (gDragMode)
		{
			return false;
		}
		if (gRowCls != classname)
		{
			gRowCls = classname;
			$(obj).attr('class', classname);
			$(obj).find('input[name="' + checkobj + '[]"]').attr('checked', true);
		}
		else
		{
			gRowCls = defaultclass;
			$(obj).find('input[name="' + checkobj + '[]"]').attr('checked', false);
		}
	}
	else
	{
		$(obj).attr('class', gRowCls);
	}
};

hg_find_nodeparent = function (obj, tagName)
{
	var parentTag = obj.tagName;
	var loop = 0;
	while (parentTag != tagName && loop < 10)
	{
		obj = obj.parentNode;
		parentTag = obj.tagName;
		loop++;
	}
	if (parentTag == tagName)
	{
		return obj;
	}
	else
	{
		return null;
	}
};

hg_get_checked_id = function(obj)
{
	var ids = new Array();
	$(obj).find('input[type="checkbox"]').each(
		function()
		{
			if ($(this).attr('checked') && $(this).attr('id') != 'checkall' && $(this).attr('name') != 'checkall')
			{
				ids.push($(this).attr('value'));
			}
		}
	);
	return ids.join(',');
};


function  hg_select_value(obj,flag,show,value_name,is_sub)
{
	if($(obj).attr('attrid') != $('#' + value_name).val())
	{
		$('#display_'+ show).text($(obj).text());
		$('#' + value_name).val($(obj).attr('attrid'));
		$('#' + show).css('display','none');
		if(flag == 1)
		{
			if($(obj).attr('attrid') == 'other')
			{
			   $('#start_time_box').css('display','block');
			   $('#end_time_box').css('display','block');
				$('#go_date').css('display','block');
			}
			else
			{
				$('#start_time').val('');
				$('#end_time').val('');
				$('#start_time_box').css('display','none');
				$('#end_time_box').css('display','none');
				$('#go_date').css('display','none');
			}
		}
		if(is_sub == 1)
		{
			$("#searchform").submit();
		}
		return true;
	}
	else
	{
		$('#' + show).css('display','none');
		return false;
	}
}


function hg_search_show(flag,id)
{
	switch(flag)
	{
		case 0:
			$('#'+id).hide(); 
			break;
		case 1:
			$('#'+id).show();
			break;
		default:
			break;
	}
}

function text_value_onfocus(obj,text){
	$(obj).removeClass("t_c_b");
	if($(obj).val()==text)
	{
		$(obj).val("");
	}
}
function text_value_onblur(obj,text){
	if($(obj).val()=="")
	{
		$(obj).addClass("t_c_b");
		$(obj).val(text);
	}
}
function textarea_value_onfocus(obj,text){
	$(obj).removeClass("t_c_b");
	if($(obj).text()==text)
	{
		$(obj).text("");
	}
}
function textarea_value_onblur(obj,text){
	if($(obj).text()=="")
	{
		$(obj).addClass("t_c_b");
		$(obj).text(text);
	}
	else
	{
		$(obj).removeClass("t_c_b");
	}
}
function close_show_clew(){$("#liv_show_clew").fadeOut();}
hg_show_clew = function(msg, unfadeout)
{
	if (!$('#liv_show_clew').attr('id'))
	{
		$('body').append('<div id="liv_show_clew" style="display:none;"><span class="left"></span><span class="right"></span><span class="middle">' + msg + '</span></div>');
	}
	else
	{
		$('#liv_show_clew').html('<span class="left"></span><span class="right"></span><span class="middle">' + msg + '</span>');
	}
	if (unfadeout == 1)
	{
		$('#liv_show_clew').fadeIn(600); 
	}
	else if(unfadeout > 600)
	{
		$('#liv_show_clew').fadeIn(600, function(){$('#liv_show_clew').fadeOut(unfadeout);}); 
	}
	else
	{
		$('#liv_show_clew').fadeIn(600, function(){$('#liv_show_clew').fadeOut(2000);}); 
	}
}

hg_msg_show = function(data, unfadout)
{
	if (top)
	{
		top.hg_show_clew(data, unfadout);
	}
	else
	{
		hg_show_clew(data, unfadout);
	}
}

hg_call_back = function(data, showmsg)
{
	var unfideout = 0;
	try
	{
		var obj = new Function("return" + data)();
		if (typeof(obj) != 'object')
		{
			data = '请求成功'; 
		}
		else
		{
			if (obj.msg)
			{
				data = obj.msg; 
			}
			else
			{
				data = '请求成功';
			}
		}
		if (obj.callback)
		{
			eval(obj.callback);
		}
	}
	catch (e)
	{
		data = e.message + ' msg:' + object2str(e);
	}
	if (!showmsg || showmsg == 0)
	{
		hg_msg_show(data, unfideout);
	}
}

hg_remove_row = function(ids)
{  
	var id = ids.split(',');
	for (var i=0; i < ids.length; i++)
	{
		if($('#r' + id[i]))
		{
			$('#r' + id[i]).remove();
		}
		
		if($('#r_' + id[i]))
		{
			$('#r_' + id[i]).remove();
		}
	}	
	if($('#edit_show').length)
	{
		hg_close_opration_info();
	}
}


function dateToUnix(str)
{
	str = str.replace(/(^\s*)|(\s*$)/g, "");
	var new_str = str.replace(/:/g,'-');
	new_str = new_str.replace(/ /g,'-');
	var arr = new_str.split('-');

	var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
	return (datum.getTime()/1000);
}

function unixToDate(timestamp,jstimestamp)
{
	timestamp = timestamp*1000;
	jstimestamp = jstimestamp ? jstimestamp :"Y-m-d H:i:s";
	var d = new Date(timestamp);
	jstimestamp = jstimestamp.replace("Y", d.getFullYear());
	jstimestamp = jstimestamp.replace("m", ((d.getMonth()+1)<10?"0"+(d.getMonth()+1) : (d.getMonth()+1)));
	jstimestamp = jstimestamp.replace("d", (d.getDate()<10 ? "0"+d.getDate() : d.getDate()));
	jstimestamp = jstimestamp.replace("H", (d.getHours()<10 ? "0"+d.getHours() : d.getHours()));
	jstimestamp = jstimestamp.replace("i", (d.getMinutes()<10 ? "0"+d.getMinutes() : d.getMinutes()));
	jstimestamp = jstimestamp.replace("s", (d.getSeconds()<10 ? "0"+d.getSeconds() : d.getSeconds()));
	return jstimestamp;
}

function trim(str)
{
	return str.replace(/(^\s*)|(\s*$)/g, "");
}
function hg_rand_num(leng)
{
	leng = leng ? leng : 5;
	var salt = '';
	for(i=0 ; i< leng ; i++)
	{
		n = Math.floor(Math.random()*10);
		if(!n && !i)
		{
			n = 3;
		}
		salt += n.toString();
	}
	return salt;
}

/*内容获取错误效果*/
function hg_error_html(obj,num){
	if(num==0)
	{
		var Frame_id = top.$(obj);
	}
	else
	{
		var Frame_id = $(obj);
	}
	Frame_id.animate({'marginLeft':'-14px'},5,function(){
		Frame_id.animate({'marginLeft':'14px'},150,function(){
			Frame_id.animate({'marginLeft':'-10px'},80,function(){
				Frame_id.animate({'marginLeft':'10px'},80,function(){
					Frame_id.animate({'marginLeft':'-6px'},40,function(){
						Frame_id.animate({'marginLeft':'6px'},40,function(){
							Frame_id.animate({'marginLeft':'-2px'},20,function(){
								Frame_id.animate({'marginLeft':'2px'},20,function(){
									Frame_id.animate({'marginLeft':'0px'},10);
								});
							});
						});
					});
				});
			});
		});
	});
}