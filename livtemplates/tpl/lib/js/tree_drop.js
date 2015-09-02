$(document).ready(function()
{
	var gColor = [];
	gColor['hg_anode_0'] = '#282828';
	var gSelect = '0,0';
	var gState = []; //判断展开收起
	hg_drop_node_class = function (objname, depth, id)
	{
		var obj = $('#hg_node_list_'+objname).children('li');
		obj.each(function(){
	
			var ids = $(this).children('a').attr('id');
			var idd = ids.replace('hg_a'+objname+'_','');
			if(rgb2hex($('#'+ ids).css('color')) != '#ffffff')
			{
				gColor[ids] = rgb2hex($('#'+ ids).css('color'));
			}

		//	alert(idd+'---'+gState['child_'+idd]+'---'+gSelect);
		//alert(gSelect);

			var s = gSelect.split(',');

			$('#'+ ids).css('color',gColor[ids]);

			if(s[0] == idd) //选中
			{
				$('#'+ ids).attr('class','cur');
				$('#'+ ids).css('color','#ffffff');
				if(gState['child_'+idd]) //下拉
				{
					$('#hg_em' + objname + '_' + idd).attr('class','cur');
				}
				else
				{
					$('#hg_em' + objname + '_' + idd).attr('class','');
				}
			}
			else //未选中
			{
				$('#'+ ids).attr('class','');
				if(gState['child_'+idd]) //下拉
				{
				//	alert(idd);
					$('#hg_em' + objname + '_' + idd).attr('class','default');
				}
				else
				{
					$('#hg_em' + objname + '_' + idd).attr('class','');
				}
			}
		});
		
	}

	hg_drop_child_class = function (objname, depth, id,fid)
	{
		hg_append_clear();
		$('#hg_node_list_' + objname).find('li').each(function() {
				var ids = $(this).children('a').attr('id');
				if(rgb2hex($('#'+ ids).css('color')) != '#ffffff')
				{
					gColor[ids] = rgb2hex($('#'+ ids).css('color'));
				}
				if(ids.indexOf('child') != -1)
				{
					var idd = ids.replace('hg_achild'+objname+'_','');
					
					if(idd == id && fid != id)
					{
						gSelect = id +',' + '1';
						$('#'+ ids).attr('class','cur');
						//$('#'+ ids).css('color','#ffffff');
					}
					else
					{
						$('#'+ ids).css('color',gColor[ids]);
						$('#'+ ids).attr('class','');
					}
				}
				else
				{
					var idd = ids.replace('hg_a'+objname+'_','');

					if(idd == id && fid == id)
					{
						$('#'+ ids).attr('class','cur');
					//	$('#'+ ids).css('color','#ffffff');
						gSelect = id +',' + '0';
						if(gState['child_'+id])
						{
							$('#hg_em' + objname + '_' + id).attr('class','cur');
						}
						else
						{
							$('#hg_em' + objname + '_' + id).attr('class','');
						}
					}
					else
					{
						$('#'+ ids).attr('class','');
						if(gState['child_'+idd])
						{
							$('#'+ ids).css('color',gColor[ids]);
							$('#hg_em' + objname + '_' + idd).attr('class','default');
						}
						else
						{
							$('#'+ ids).css('color',gColor[ids]);
							$('#hg_em' + objname + '_' + idd).attr('class','');
						}
					}
				}
		});
		
	}

	function rgb2hex(rgb) {
		rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		function hex(x) 
		{
			return ("0" + parseInt(x).toString(16)).slice(-2);
		}
		return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}

	hg_show_node_child = function (objname, url, id, depth, level, is_last)
	{
		var obj = $('#hg_node_list_'+objname).children('li');
			obj.each(function(){
				var ids = $(this).children('a').attr('id');
				var idd = ids.replace('hg_a'+objname+'_','');
				if(rgb2hex($('#'+ ids).css('color')) != '#ffffff')
				{
					gColor[ids] = rgb2hex($('#'+ ids).css('color'));
				}				
				if (is_last == 1)
				{
					return;
				}
				
				if(id == idd)
				{
					if($('#child_'+id).html())
					{
						if(gState['child_'+idd])
						{
							$('#child_'+idd).slideUp(550);
							gState['child_'+idd] = 0;
						}
						else
						{
							$('#child_'+idd).slideDown(550);
							gState['child_'+idd] = 1;
						}
						hg_drop_node_class(objname, depth, id);
					}
					else
					{
						gState['child_'+idd] = 1;
						data = {
							'fid' : id, 
							'objname' : objname, 
							'depth' : depth, 
							'level' : level,
							'node_callback' : 'hg_show_child_node_drop',
							'node_template' : '_nodedrop'
							}
						hg_request_to(url,data);
					}
				}
				
				if(gState['child_'+idd] == undefined)
				{
					gState['child_'+idd] = 0;
				}
				//alert(idd + '-----' +gState['child_'+idd]);
			 });
	}

	hg_show_child_node_drop = function (html, objname, id, depth, nodata, level)
	{
		$('#hg_node' + objname + '_' + id).append(html);
		$('#child_'+id).slideDown(550, function(){top.hg_init_client_info();});
		if(gSelect)
		{
			var s = gSelect.split(',');
			if(s[0] == id)
			{
				$('#hg_em' + objname + '_' + id).attr('class','cur');
			}
			else
			{
				$('#hg_em' + objname + '_' + id).attr('class','default');
			}
		}
		else
		{
			$('#hg_em' + objname + '_' + id).attr('class','default');
		}
		return false;
	}

	hg_drop_clear = function()
	{
		
	//	gColor = [];
	//	gColor['hg_anode_0'] = '#282828';
		var gSelect = '0,0';
		objname = 'node';
		$('#hg_node_list_' + objname).find('li').each(function() {
				var ids = $(this).children('a').attr('id');
				if(ids.indexOf('child') != -1)
				{
					var idd = ids.replace('hg_achild'+objname+'_','');
					$('#'+ ids).css('color',gColor[ids]);
					$('#'+ ids).attr('class','');
				}
				else
				{
					var idd = ids.replace('hg_a'+objname+'_','');
					$('#'+ ids).attr('class','');
					if(gState['child_'+idd])
					{
						$('#'+ ids).css('color',gColor[ids]);
						$('#hg_em' + objname + '_' + idd).attr('class','default');
					}
					else
					{
						$('#'+ ids).css('color',gColor[ids]);
						$('#hg_em' + objname + '_' + idd).attr('class','');
					}
				}
		});
	}


/***********************append_menu**************************/

	hg_append_menu = function(e)
	{
		hg_drop_clear();
		var obj = $('#append_menu').children('li');
		obj.each(function(){
			$(this).children('a').removeClass('append_cur');
		});
		$(e).children('a').addClass('append_cur');

	}

	hg_append_clear = function()
	{
		var obj = $('#append_menu').children('li');
		obj.each(function(){
			$(this).children('a').removeClass('append_cur');
		});
	}
});