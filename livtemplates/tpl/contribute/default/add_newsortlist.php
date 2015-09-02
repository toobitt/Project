<li class="clear"  id="r_{$formdata[0]['id']}"    name="{$formdata[0]['id']}"   orderid="{$formdata[0]['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$formdata[0][id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$formdata[0][$primary_key]}" title="{$formdata[0][$primary_key]}"  /></a>
		<a class="fb overflow"  style="width:120px;"  id="contribute_sort_name_{$formdata[0]['id']}">{$formdata[0]['name']}</a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$formdata[0][id]}', 'click', 'cur');"  style="width:380px;">
		<a class="fl" style="width: 130px"><em>{if $formdata[0]}{foreach $formdata[0]['sortname'] as $key=>$val}<span style="color: #9AAACC;text-decoration:underline">{$val}</span>&nbsp;&nbsp;{/foreach}{/if}</em></a>
		<a class="fl"><em><span id="auto_{$v['id']}"  style="color: #9AAACC;text-decoration:underline">{$v['auto']}</span></em></a>
		<a class="fb" title="编辑" href="javascript:void(0)" onclick="return hg_showAddcontributesort({$formdata[0]['id']})"><em class="b2" ></em></a>
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata[0]['id']}"><em class="b3" ></em></a>
		<a class="tjr"><em>{$formdata[0]['user_name']}</em><span>{$formdata[0]['create_time']}</span></a>
	</span>
	<span class="title overflow"  style="cursor:pointer;"><a id="contribute_sort_desc_{$formdata[0]['id']}">{$formdata[0]['brief']}</a></span>
</li>