{code}
	print_r($formdata);
{/code}
<li class="clear"  id="r_{$formdata['id']}"    name="{$formdata['id']}"   orderid="{$formdata['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$formdata[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$formdata[$primary_key]}" title="{$formdata[$primary_key]}"  /></a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$formdata[id]}', 'click', 'cur');">
		<a class="fb"><em class="b2" onclick="return hg_showAddSort({$formdata['id']});"></em></a>
		<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}"><em class="b3" ></em></a>
		<a class="fl"><em  class="overflow" id="contribute_sort_{$formdata['id']}">{$formdata['sort']}</em></a>
		<a class="fl"><em  class="overflow" id="contribute_client_{$formdata['id']}">{$formdata['client']}</em></a>
		<a class="zt" > <em><span class="zt_a" id="contribute_audit_{$formdata['id']}">{$formdata['audit']}</span></em></a>
		<a class="tjr"><em>{$formdata['user_name']}</em><span>{$formdata['create_time']}</span></a>
	</span>
	<span class="title overflow"  style="cursor:pointer;" onclick="hg_show_opration_info({$formdata['id']})"><a  id="contribute_title_{$formdata['id']}">{$formdata['title']}</a></span>
</li> 