<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
	<span class="left">
		<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
	</span>
	<span class="right" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');">
		{if $v['id'] > 3}
		<a  style="display:none;" onclick="return hg_ajax_post(this, '设置发布栏目', 0);" href="run.php?mid={$_INPUT['mid']}&a=set_publish_column&id={$v['id']}">限制发布栏目</a>
		<a  style="display:none;" class="fl" href="./run.php?mid={$_INPUT['mid']}&a=get_app&role_name={$v['name']}&id={$v['id']}&infrm=1">权限设置</a>
		{else}
		<a style="visibility: hidden;">限制发布栏目</a>
		<a style="visibility: hidden;" class="fl">权限设置</a>
		{/if}
		<a class="fb"  style="display:none;" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin"><em class="b2"></em></a>
		{if $v['id'] > 3}
			<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
		{else}
		<a style="width: 25px"><em></em></a>
		{/if}
		<a class="tjr"><em>{$v['user_name_add']}</em><span>{$v['create_time']}</span></a>
		
	</span>
	<span class="title overflow"  style="cursor:pointer;">
		<a  id="contribute_title_{$v['id']}" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">{$v['name']}</a>
	</span>
</li> 
