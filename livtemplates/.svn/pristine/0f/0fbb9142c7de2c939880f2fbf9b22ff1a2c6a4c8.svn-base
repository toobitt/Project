<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');"  onclick="hg_row_interactive(this, 'click', 'cur');" >
	<span class="left">
		<a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
	</span>
	<span class="right"  style="width:400px;">
		<a class="fb" href="javascript:void(0);"  onclick="hg_showAddServer({$v['id']})"><em class="b2"></em></a>
		<a class="fb" href="run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"  onclick="return hg_ajax_post(this,'删除',1);"><em class="b3"></em></a>
		<a class="fl overflow"  style="width:100px;"><em>{$v['app_name']}</em></a>
		<a class="fl zt" style="width:140px;">{$v['create_time']}</a>
	</span>
	<span class="title overflow"  style="cursor:pointer;">
		<a><span id="version_name_{$v['id']}">{$v['version_name']}</span></a>
	</span>
</li>