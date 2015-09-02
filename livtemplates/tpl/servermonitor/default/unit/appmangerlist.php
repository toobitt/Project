<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');"  onclick="hg_row_interactive(this, 'click', 'cur');" >
	<span class="left">
		<a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
	</span>
	<span class="right"  style="width:750px">
		<a class="fb" href="javascript:void(0);"  onclick="hg_showAddApp({$v['id']})"><em class="b2"></em></a>
		<a class="fb" href="run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"  onclick="return hg_ajax_post(this,'删除',1);"><em class="b3"></em></a>
		<a class="fl overflow"><em id="type_{$v['id']}">{$v['type']}</em></a>
		<a class="fl overflow"  style="width:150px;"><em id="install_dir_{$v['id']}">{$v['install_dir']}</em></a>
		<a class="fl overflow"  style="width:150px;"><em id="dns_{$v['id']}">{$v['dns']}</em></a>
		<a class="fl overflow"><em id="version_{$v['id']}">{$v['version']}</em></a>
		<a class="fl overflow"><em id="server_name_{$v['id']}">{$v['server_name']}</em></a>
		<a class="fl zt" >{$v['create_time']}</a>
	</span>
	<span class="title overflow"  style="cursor:pointer;">
		<a href="run.php?mid={$relate_module_id}&server_id={$v['id']}&infrm=1"><span id="name_{$v['id']}">{$v['name']}</span></a>
	</span>
</li>