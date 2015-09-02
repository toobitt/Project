<li class="clear"  id="r_{$formdata['appid']}"    name="{$formdata['appid']}"   orderid="{$formdata['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');"  onclick="hg_row_interactive(this, 'click', 'cur');" >
	<span class="left">
		<a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$formdata['appid']}" title="{$formdata['appid']}"  /></a>
	</span>
	<span class="right"  style="width:695px">
		<a class="fb" href="javascript:void(0);"  onclick="hg_showAddAuth({$formdata['appid']});"><em class="b2" ></em></a>
		<a class="fb" href="run.php?mid={$_INPUT['mid']}&a=delete&appid={$formdata['appid']}"  onclick="return hg_ajax_post(this,'删除',1);"><em class="b3" ></em></a>
		<a class="fl overflow"  style="width:100px;"><em id="display_name_{$formdata['appid']}">{$formdata['display_name']}</em></a>
		<a class="fl overflow"><em id="bundle_id_{$formdata['appid']}">{$formdata['bundle_id']}</em></a>
		<a class="fl overflow"><em id="custom_desc_{$formdata['appid']}">{$formdata['custom_desc']}</em></a>
		<a class="fl overflow" title="{$formdata['appkey']}" href="run.php?mid={$_INPUT['mid']}&a=see_appkey&appid={$formdata['appid']}&infrm=1"><em>{$formdata['appkey']}</em></a>
		<a class="fl" 			style="position:relative;"  onmouseout="hg_show_auth_audit({$formdata['appid']},0);" onmouseover="hg_show_auth_audit({$formdata['appid']},1);" >
				<em id="auth_status_{$formdata['appid']}">{$formdata['status_name']}</em>
				<span style="position:absolute;width:292px;height:38px;background:#f0eff5;top:-1px;left:45px;visibility:hidden;" id="auth_audit_{$formdata['appid']}" onclick="hg_auth_audit({$formdata['appid']});">
					{if $formdata['status'] != 2}
					<input type="button" value="审核" class="button_2" style="margin-top:6px;" id="audit_button_{$formdata['appid']}"  />
					{else}
					<input type="button" value="打回" class="button_2" style="margin-top:6px;" id="audit_button_{$formdata['appid']}"  />
					{/if}
				</span>
		</a>
		<a class="fl zt"  id="expire_time_{$formdata['appid']}">{$formdata['expire_time']}</a>
		<a class="fl zt" >{$formdata['create_time']}</a>
	</span>
	
	<span class="title overflow"  style="cursor:pointer;">
		<a><span  id="custom_name_{$formdata['appid']}">{$formdata['custom_name']}</span></a>
	</span>
</li>   