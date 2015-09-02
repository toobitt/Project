<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
		<a class="fb overflow"  style="width:120px;"  id="news_sort_name_{$v['id']}" href="run.php?mid={$_INPUT['mid']}&fid={$v['id']}&infrm=1">{$v['name']}</a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"  style="width:239px;">
		<a class="fb" title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2" ></em></a>
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
		<a class="tjr"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
	</span>
	<span class="title overflow"  style="cursor:pointer;"><a id="contribute_sort_desc_{$v['id']}">{$v['brief']}</a></span>
</li>