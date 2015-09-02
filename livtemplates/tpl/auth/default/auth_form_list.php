<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="appauthform"  id="appauthform" onsubmit="return hg_ajax_submit('appauthform');">
<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">客户名称：</label>
	<input type="text" name="custom_name"   id="custom_name"  value="{$formdata['custom_name']}" style="width:450px;" />
</div>

<div style="width:100%;margin-top:10px;">
	<label>客户显示名称：</label>
	<input type="text" name="display_name" id="display_name"  value="{$formdata['display_name']}" style="width:450px;"  />
</div>

<div style="width:100%;margin-top:10px;">
	<label  style="margin-left:24px;">客户标识：</label>
	<input type="text" name="bundle_id" id="bundle_id"  value="{$formdata['bundle_id']}" style="width:450px;" {if !$formdata['is_update']&& $a!="create" } disabled="disabled" {/if} />
</div>

<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">客户描述：</label>
	<textarea style="width:450px;height:100px;" name="custom_desc" id="custom_desc"  {if !$formdata['is_update']&& $a!="create" } disabled="disabled" {/if}>{$formdata['custom_desc']}</textarea>
</div>

<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">过期时间：</label>
	<input type="text" name="expire_time" id="expire_time" autocomplete="off" size="26" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="{$formdata['expire_time']}"  {if !$formdata['is_update']&& $a!="create" } disabled="disabled" {/if}>
	<label>("0"代表永久有效)</label>
</div>
<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">手机使用：</label>
	<label><input {if $formdata['mobile']}checked="checked"{/if}type="checkbox" name="mobile" id="mobile" value=1/>(标记是否用于手机访问api)</label>
</div>
<div style="width:100%;margin-top:10px;">
	<label style="margin-left:24px;">启用授权：</label>
	<label><input {if $formdata['is_auth']}checked="checked"{/if}type="checkbox" name="is_auth" id="is_auth" style="vertical-align: middle;margin-right: 8px;" value="1"  {if !$formdata['is_update']&& $a!="create" } disabled="disabled" {/if}>(启用授权之后客户端必须通过登陆访问API)</label>
</div>

<div style="width:100%;margin-top:10px;">
	<input type="submit"  value="{$optext}" class="button_6" style="margin-left:441px;" />
</div>
<input type="hidden" value="{$a}" name="a" />
<input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
