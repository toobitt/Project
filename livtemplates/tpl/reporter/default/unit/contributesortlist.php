<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
		<a class="fb overflow"  style="width:120px;"  id="contribute_sort_name_{$v['id']}">{$v['name']}</a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"  style="width:425px;">
		<a class="fl" style="width: 130px"><em id="sortname_{$v[id]}">{foreach $v['sortname'] as $key=>$val}<span style="color: #9AAACC;text-decoration:underline">{$val}</span>&nbsp;&nbsp;{/foreach}</em></a>
		<a class="fl"><em><span id="auto_{$v['id']}" style="color: #9AAACC;text-decoration:underline">{$v['auto']}</span></em></a>
		<a class="fb" title="编辑" href="javascript:void(0)" onclick="return hg_showAddcontributesort({$v['id']})"><em class="b2" ></em></a>
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
		<a class="tjr"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
	</span>
	<span class="title overflow"  style="cursor:pointer;"><a id="contribute_sort_desc_{$v['id']}">{$v['brief']}</a></span>
</li>