<li class="clear" style="z-index:45;border:1px solid #fff;height:169px;width:186px;"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');hg_show_tips({$v['id']},0);" onmouseover="hg_row_interactive(this, 'on');hg_show_tips({$v['id']},1);"   onclick="hg_row_interactive(this, 'click', 'cur');hg_check_boxok({$v['id']});">
		<div style="position:absolute;z-index:100;left:68px;top:49px;">
			<a href="run.php?mid={$_INPUT['mid']}&a=preview&id={$v['id']}&infrm=1" onclick="return hg_ajax_post(this, '预览', 0);">
			<img src="{$image_resource}video_play.png" width="49" height="49"></a>
		</div>
		<span class="left">
			<a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" id="select_{$v['id']}"  /></a>
			<a class="slt" style="left:1px;top:1px;" ><img src="{$v['bschematic']}"   width="40" height="30" style="width:184px;height:137px;" /></a>
		</span>
		<span class="right">
			<a class="fb" id="delete_{$v['material_id']}" href="run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);" style="bottom:4px;display:none;right:9px;"><em class="b3" ></em></a>
			
			<a class="comment_num" style="top:145px;"><span class="overflow">{$v['title']}</span>&nbsp;<span>{$v['toff']}</span></a>
			<a class="rt_img" id="right_{$v['id']}" style="left:1px;top:1px;"></a>
			<a class="new_img" id="new_{$v['id']}" style="left:155px;top:1px;"></a>
		</span>
		<span class="title overflow"  style="cursor:pointer;display:none;">
			<a></a>
		</span>
</li>