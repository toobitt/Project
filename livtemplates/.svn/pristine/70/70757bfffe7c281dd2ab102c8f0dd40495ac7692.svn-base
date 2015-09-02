<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="appauthform"  id="appauthform" onsubmit="return hg_ajax_submit('appauthform');">
{if $formdata}
	{foreach $formdata as $k => $v} 
	<input type="hidden" value="{$v['mod_uniqueid']}" name="mod_uniqueid[]" />
    <div style="width:100%;margin-top:10px;">
		<label style="margin-left:24px;color:#17b202;">模块名称：</label>
		<span name="mod_name"   id="mod_name_{$v['id']}"  style="width:250px;color:#17b202;">{$v['name']}</span>
	</div>
	 <div style="width:100%;margin-top:10px;">
		<label style="margin-left:24px;">操作：</label>
		{if $v['limit']}
			{foreach $v['limit'] as $key => $ac}
			<span style="color: blue;">{$ac['name']}</span>
			<input type="checkbox" name="{$v['mod_uniqueid']}[]" onclick="hg_show_role_node('node_{$v['mod_uniqueid']}_{$key}');" {if $ac['prms']['func'] || $v['func_flag']}checked{/if} value="{$key}">
			{if $ac['append']}
			<input type="hidden" name="{$v['mod_uniqueid']}[append][{$key}]" value="{$ac['append']}">
			{/if}
			{if $ac['node']}
			<span id="{$v['mod_uniqueid']}_{$key}" >
				<input type="hidden" name="{$v['mod_uniqueid']}_{$key}" value='{$ac["prms"]["node"]}' />
			
				<a href="javascript:void(0);" id="node_{$v['mod_uniqueid']}_{$key}"{if !$ac['prms']['func'] && !$v['func_flag']}style="display: none;"{/if} onclick="hg_statePublish({$_GET['id']},'{$v['app_uniqueid']}','{$v['mod_uniqueid']}','{$key}', this);" data-dataid="{$v['mod_uniqueid']}_{$key}">节点授权</a>
			</span>
			{/if}
			{/foreach}
		{/if}
	</div>
	<!--
	<div style="width:100%;margin-top:10px;">
		<label style="margin-left:24px;">修改他人数据：</label>
		<input type="checkbox" name="{$v['mod_uniqueid']}[extend][manage_other_data]" {if $v['extend']['manage_other_data'] || $v['func_flag']}checked{/if} value="1">
	</div>
	{if $v['app_uniqueid'] == $v['mod_uniqueid']}
	<div style="width:100%;margin-top:10px;">
		<label style="margin-left:24px;">添加数据上限：</label>
		<input type="text" size="8" name="{$v['mod_uniqueid']}[extend][create_data_limit]"  value="{$v['extend']['create_data_limit']}">
	</div>
	{/if}
	<div style="width:100%;margin-top:10px;">
		<label style="margin-left:24px;">修改审核内容：</label>
		<select name="{$v['mod_uniqueid']}[extend][update_audit_content]">
		{foreach $_configs['update_audit_content'] as $key=>$val}
			<option value="{$key}" {if $v['extend']['update_audit_content'] == $key}selected="selected"{/if}>{$val}</option>
		{/foreach}
		</select>
	</div>
	<div style="width:100%;margin-top:10px;">
		<label style="margin-left:24px;">修改发布内容：</label>
		<select name="{$v['mod_uniqueid']}[extend][update_publish_content]">
		{foreach $_configs['update_publish_content'] as $key=>$val}
			<option value="{$key}" {if $v['extend']['update_publish_content'] == $key}selected="selected"{/if}>{$val}</option>
		{/foreach}
		</select>
	</div>
	<div style="width:100%;margin-top:10px;">
		<label style="margin-left:24px;">创建内容状态：</label>
		<select name="{$v['mod_uniqueid']}[extend][create_content_status]">
		{foreach $_configs['create_content_status'] as $key=>$val}
			<option value="{$key}" {if $v['extend']['create_content_status'] == $key}selected="selected"{/if}>{$val}</option>
		{/foreach}
		</select>
	</div>   
	-->    	
    {/foreach}
{/if}


<div style="width:100%;margin-top:30px;">
	<input type="submit"  value="更新权限" class="button_6" style="margin-left:430px;" />
</div>
<input type="hidden" value="update_role_power" name="a" />
<input type="hidden" value="{$v['app_uniqueid']}" name="app_uniqueid" />
<input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
