
<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');hg_show_tips({$v['id']},0);" onmouseover="hg_row_interactive(this, 'on');hg_show_tips({$v['id']},1);"   onclick="hg_row_interactive(this, 'click', 'cur');hg_check_boxok({$v['id']});">
		<div class="top_box" id="top_box_{$v['id']}">
			<span class="left">
				<a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" id="primary_key_{$v['id']}"  /></a>
				<a class="slt" ><img src=""   width="40" height="30" /></a>
			</span>
		</div>
		<div class="top_box2" id="top_box2_{$v['id']}"></div>
		<span class="right">
			<a class="fb" id="delete_{$v['id']}" href="run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);" ><em class="b3" ></em></a>
			<a class="fl" ><em>{$v['picture_count']}</em></a>
			<a class="fl"><em class="overflow">{$v['comment_count']}</em></a>
			<span id="hg_t_{$v['id']}" class="hg_t_time" style="display:none"></span>
			<a class="tjr"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
			<a class="comment_num"><span>评论{$v['comment_count']}|照片{$v['picture_count']}</span></a>
			<a class="ava_img"><img src="{$v['cover_file_name']}" width="30" height="30" /></a>
			<a class="rt_img" id="right_{$v['albums_id']}"></a>
			<a class="fl_text overflow" id="tips_{$v['albums_id']}">{$v['user_name']}</a>
			<a class="new_img" id="new_{$v['albums_id']}"></a>
		</span>
		<span class="title overflow"  style="cursor:pointer;" >
			<a href="run.php?mid={$_INPUT['mid']}&a=look_alubms_pic&albums_id={$v['albums_id']}&infrm=1" title="点击查看图片" >{$v['albums_name']}</a>
		</span>
</li>