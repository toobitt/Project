<li class="clear" style="z-index:45;border:1px solid #fff;height:169px;width:186px;"  id="r_{$v['material_id']}"    name="{$v['material_id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');hg_show_tips({$v['material_id']},0);" onmouseover="hg_row_interactive(this, 'on');hg_show_tips({$v['material_id']},1);"   onclick="hg_row_interactive(this, 'click', 'cur');hg_check_boxok({$v['material_id']});">
		<span class="left">
			<a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" id="select_{$v['material_id']}"  /></a>
			<a class="slt" style="left:1px;top:1px;" ><img src="{$v['file_path']}"   width="40" height="30" style="width:184px;height:137px;" /></a>
		</span>
		<span class="right">
			<a class="fb" id="delete_{$v['material_id']}" href="run.php?mid={$_INPUT['mid']}&a=delete&material_id={$v['material_id']}" onclick="return hg_ajax_post(this, '删除', 1);" style="bottom:4px;display:none;right:9px;"><em class="b3" ></em></a>
			<a class="fl" ><em>{$v['picture_count']}</em></a>
			<a class="fl"><em class="overflow">{$v['comment_count']}</em></a>
			<span id="hg_t_{$v['id']}" class="hg_t_time" style="display:none"></span>
			<a class="tjr"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
			<a class="comment_num" style="top:145px;"><span class="overflow">{$v['description']}</span></a>
			<a class="rt_img" id="right_{$v['material_id']}" style="left:1px;top:1px;"></a>
			<a class="new_img" id="new_{$v['material_id']}" style="left:155px;top:1px;"></a>
			<a class="ava_img" style="left:1px;top:103px;"><img src="{$v['file_path']}" width="34" height="34" /></a>
			<a class="fl_text overflow" id="tips_{$v['material_id']}" style="top:118px;left:38px;width:147px;">{$v['user_name']}</a>
		</span>
		<span class="title overflow"  style="cursor:pointer;display:none;">
			<a></a>
		</span>
</li>