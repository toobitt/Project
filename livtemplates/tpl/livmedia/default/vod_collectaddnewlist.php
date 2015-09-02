<li class="clear"  id="r_{$formdata['id']}"    name="{$formdata['id']}"   orderid="{$formdata['video_order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$formdata[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$formdata['id']}" title="{$formdata['id']}"  /></a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$formdata[id]}', 'click', 'cur');"  style="width:380px">
		<a class="fb" onclick="hg_showAddCollect(true,{$formdata['id']});" ><em class="b2" ></em></a>
		<a class="fb" href="javascript:void(0);"  title="删除"  onclick="return hg_deleteCollect({$formdata['id']})" ><em class="b3" ></em></a>
		<a class="fl zt" ><em style="color:{$formdata['vod_sort_color']}"   id="sort_{$formdata['id']}" >{$formdata['vod_sort_id']}</em></a>
		<a class="fl zt"><em>{$formdata['admin_name']}</em></a>
		<a class="tjr">{$formdata['create_time']}</a>
	</span>
	
	<span class="title overflow"  style="cursor:pointer;"  >
		<a href="./run.php?mid={$formdata['relate_module_id']}&collect_id={$formdata['id']}&infrm=1"   onclick="return hg_look_videos(this);" ><span id="collect_name_{$formdata['id']}">{$formdata['collect_name']}</span><strong  id="duration_{$formdata['id']}">{$formdata['count']}</strong></a>
	</span>
</li>   