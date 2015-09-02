$(document).ready(function()
{
	return;
	var gCurNode = [];
	var gCurDepth = [];
	hg_chg_node_class = function (objname, depth, id)
	{
		return;
		var lastNode = gCurNode[objname + '_' + depth];
		if (lastNode)
		{
			$('#hg_node' + objname + '_' + lastNode).removeClass('cur');		
		}
		$('#hg_node' + objname + '_0').children('a').attr('class', 'cur n');
		$('#hg_node' + objname + '_' + id).addClass('cur');
		gCurNode[objname + '_' + depth] = id;
	}
	hg_top_remove_child_class = function (objname)
	{
		$('#hg_node_list_' + objname + '_0').children('li[class="cur"]').removeClass('cur');
		$('#hg_node' + objname + '_0').children('a').attr('class', 'cur');
	}
	hg_show_child_node_list = function (html, objname, id, depth, nodata, level)
	{
		if ($('#hg_node_depth_' + objname + '_' + depth).length > 0)
		{
			var ldepth = depth + 10;
			for(var i = depth; i < ldepth; i++)
			{
				$('#hg_node_depth_' + objname + '_' + i).remove();
			}
		}
		if (nodata == 1)
		{
			return;
		}
		if (depth >= level)
		{		
			var fadenode = depth - level;
			if (level == 0)
			{
				if (fadenode == 0)
				{
					fadenode = '';
				}
				else
				{
					fadenode = fadenode - 1;
				}
			}
			$('#hg_node_depth_' + objname + '_' + fadenode).fadeOut('fast', function(){$('#hg_node_' + objname).append(html);hg_set_tree_scroll(objname, depth);});
		}
		else
		{
			$('#hg_node_' + objname).append(html);
		}
		if (level == 0)
		{
			var height = $('#hg_node_depth_' + objname + '_' + depth).height();
		}
		else
		{
			var height = $('#hg_node_depth_' + objname + '_').height();
		}
		$('#hg_node_' + objname).height(height);
		
		gCurDepth[objname] = depth;
	}
	
	hg_show_node_nav = function (objname, url, id, depth, level, is_last)
	{
		hg_chg_node_class(objname, depth, id);
		if (is_last == 1)
		{
			return;
		}
		
		var check = false;
		(function(){
			var cache = $('#hg_node_node').data('cache');
			if(cache && cache[id])
				check = true;
		})();
		if(check){
			return false;
		}

		data = {
			'fid' : id, 
			'objname' : objname, 
			'depth' : depth, 
			'level' : level,
			'node_callback' : 'hg_show_child_node_list',
			'node_template' : '_nodelist'
			}
		hg_request_to(url,data);
	}
	
	hg_select_node = function (objname, url, data, multiple, height, level, is_last)
	{
		var id = data.id;
		var name = data.name;
		hg_choice_node(objname, id, name, multiple);
		if (is_last == 1)
		{
			hg_chg_node_class(objname, data.depth, id);
			return;
		}
		data = {
			'fid' : id, 
			'objname' : objname, 
			'depth' : data.depth, 
			'height' : height, 
			'multiple' : multiple,
			'node_callback' : 'hg_show_next_node',
			'node_template' : '_nodedata',
			'level' : level
			}
		hg_request_to(url,data);
	}
	
	hg_show_next_node = function(html, objname, id, depth, nodata, level)
	{
		hg_chg_node_class(objname, depth, id);
		
		if ($('#hg_node_depth_' + objname + '_' + depth).length > 0)
		{
			var ldepth = depth + 10;
			for(var i = depth; i < ldepth; i++)
			{
				$('#hg_node_depth_' + objname + '_' + i).remove();
			}
		}
		var width = $('#hg_node_depth_' + objname + '_').width() + 16;
		if (nodata == 1)
		{
			if (depth < level)
			{
				width = parseInt(width) + parseInt(depth) * 176;
				$('#hg_node_' + objname).width(width);
			}
			return;
		}
		if (depth >= level)
		{		
			var fadenode = depth - level;
			if (level == 0)
			{
				if (fadenode == 0)
				{
					fadenode = '';
				}
				else
				{
					fadenode = fadenode - 1;
				}
			}
			$('#hg_node_depth_' + objname + '_' + fadenode).fadeOut('fast', function(){$('#hg_node_' + objname).append(html);});
		}
		else
		{		
			width = parseInt(width) + (parseInt(depth) + 1) * 176;
			$('#hg_node_' + objname).width(width);
			$('#hg_node_' + objname).append(html);
		}
		gCurDepth[objname] = depth;
		
	}
	
	hg_choice_node = function(objname, id, name, multiple)
	{
		var multiple_suffix = '';
		if (multiple == 1)
		{
			multiple_suffix = '[]';
		}
		
		if ($('#hg_selected_' + objname + '_' + id).length > 0)
		{
			return;
		}
		var html = '<li style="cursor:pointer;" id="hg_selected_' + objname + '_' + id + '" ondblclick="hg_remove_node(\'' + objname + '\', ' + id + ');"><input type="hidden" name="' + objname + multiple_suffix + '" value="' + id + '" />' + name + '<span style="float:right" onclick="hg_remove_node(\'' + objname + '\', ' + id + ');">X</span>';
		
		if (multiple == 1)
		{
			$('#hg_selected_' + objname).append(html);
		}
		else
		{
			$('#hg_selected_' + objname).html(html);
		}
	}
	
	hg_remove_node = function(objname, id)
	{
		$('#hg_selected_' + objname + '_' + id).remove();
	}
	
	hg_return_parent_node = function(objname, level, nocal)
	{
		var curdepth = gCurDepth[objname];
		var lastdepth = curdepth - 1;
		if (lastdepth < 0)
		{
			lastdepth = '';
		}
		$('#hg_node_depth_' + objname + '_' + curdepth).fadeOut('fast', function(){$('#hg_node_depth_' + objname + '_' + lastdepth).fadeIn('fast');});
		if (!nocal)
		{
			var width = $('#hg_node_depth_' + objname + '_').width() + 16;
			if (curdepth > level)
			{
				curdepth = level;
			}
			width = parseInt(width) + parseInt(curdepth) * 176;
			$('#hg_node_' + objname).width(width);
		}
		gCurDepth[objname] = lastdepth;
	}

	var gScrollingTimer;
	hg_set_tree_scroll = function (objname, depth)
	{
		if (!objname)
		{
			objname = 'node';
		}
		if (!depth)
		{
			depth = '';
		}
		var h = $("#hg_node_list_" + objname + '_' + depth).height();
		if(h>500)
		{
			hg_display_tree_scroll(objname, depth);
		}
	}

	hg_display_tree_scroll = function (objname, depth)
	{
		$("#hg_node_list_" + objname + '_' + depth).jscroll({
				W:"0",
				Fn:function(){
					//clearTimeout(gScrollingTimer);
					$("#hg_node_list_" + objname + '_' + depth).jscroll({ W:"4px"//设置滚动条宽度
						,Bg:"none"//设置滚动条背景图片position,颜色等
						,Bar:{Bd:{Out:"#000",Hover:"#000"}//设置滚动滚轴边框颜色：鼠标离开(默认)，经过
							 ,Bg:{Out:"#000",Hover:"#000",Focus:"#000"}}//设置滚动条滚轴背景：鼠标离开(默认)，经过，点击
						,Btn:{btn:false}
					});
					//gScrollingTimer = setTimeout(function(){
					//	hg_display_tree_scroll(objname, depth);
					//}, 1000);
				}//滚动时候触发的方法
			});
	}
hg_set_tree_scroll();
})