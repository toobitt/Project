<div class="config_show_box">
	<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="cfg_const" id="cfg_const" onsubmit="return hg_ajax_submit('cfg_const');">
		<div class="global_config">
			<h2>常量</h2>
			{if  $formdata[2]}
			{foreach $formdata[2] AS $k => $v}
			<div class="item_box">
				<div>{$v['var_name']}</div>
				<input type="hidden" name="df_key[]"  value="{$v['var_name']}" />
				<input type="text" name="df_val[]"  value="{$v['value']}" />
				<input type="hidden" name="df_name[]"  value="{$v['name']}" />
				<input type="hidden" name="df_bundle_id[]"  value="{$v['bundle_id']}" />
			</div>
			{/foreach}
			{else}
			<div class="item_box">
				没有常量
			</div>
			{/if}
			<div style="margin-top:20px;width:103px;float:right;margin-right:20px;height:50px;">
				<input type="hidden" name="cfg_type" value="2" />
				<input type="hidden" name="a" value="update" />
				<input type="submit" value="更新" class="button_6" />
			</div>
		</div>
	</form>
	
	<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="cfg_global" id="cfg_global"  onsubmit="return hg_ajax_submit('cfg_global');">
		<div class="global_config"  style="margin-top:16px;">
			<h2>全局配置</h2>
			{if  $formdata[0]}
			{foreach $formdata[0] AS $k => $v}
			<div class="item_box">
				<div>{$v['var_name']}</div>
				<input type="hidden" name="gg_key[]"  value="{$v['var_name']}" />
				<input type="text" name="gg_val[]"  value="{$v['value']}" />
				<input type="hidden" name="gg_name[]"  value="{$v['name']}" />
				<input type="hidden" name="gg_bundle_id[]"  value="{$v['bundle_id']}" />
			</div>
			{/foreach}
			{else}
			<div class="item_box">
				没有全局配置
			</div>
			{/if}
			<div style="margin-top:20px;width:103px;float:right;margin-right:20px;height:50px;">
				<input type="hidden" name="html" value=true />
				<input type="hidden" name="cfg_type" value="3" />
				<input type="hidden" name="a" value="update" />
				<input type="submit" value="更新" class="button_6" />
			</div>
		</div>
	</form>
</div>

	