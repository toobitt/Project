<li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');"  onclick="hg_row_interactive(this, 'click', 'cur');" >
	<span class="left">
		<a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
	</span>
	<span class="right">
		<a class="fb" href="javascript:void(0);"  onclick="hg_serices_form({$v['id']});"><em class="b2"></em></a>
		<a class="fb" href="run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"  onclick="return hg_ajax_post(this,'删除',1);"><em class="b3"></em></a>
		<a class="fl"  href="javascript:void(0);"   onmouseout="hg_show_server_status({$v['id']},0);" onmouseover="hg_show_server_status({$v['id']},1);">
				<em>{$v['status_name']}</em>
				{if $v['is_display']}
				<span style="position:absolute;width:233px;height:38px;background:#f0eff5;top:-1px;left:136px;visibility:hidden;" id="server_status_{$v['id']}">
					{if $v['status'] != 1}
					<input type="button" value="启动" class="button_4" style="margin-top:6px;"  onclick="hg_exec_cmd({$v['id']},1);" />
					{else}
					<input type="button" value="停止" class="button_4" style="margin-top:6px;"  onclick="hg_exec_cmd({$v['id']},0);" />
					<input type="button" value="重启" class="button_4" style="margin-top:6px;"  onclick="hg_exec_cmd({$v['id']},2);" />
					{/if}
				</span>
				{/if}
		</a>
		<a class="fl zt" >{$v['create_time']}</a>
	</span>
	<span class="title overflow"  style="cursor:pointer;">
		<a><span id="name_{$v['id']}">{$v['name']}</span></a>
	</span>
</li>