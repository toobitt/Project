<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="versionform"  id="versionform"  onsubmit="return hg_ajax_submit('versionform');">
	<div style="width:100%;margin-top:10px;">
		<label>版本名称：</label>
		<input type="text" name="version_name" value="{$formdata['version_name']}" style="width:515px;"/>
	</div>
	<div style="width:100%;margin-top:10px;">
		<textarea style="width:100%;height:500px;margin-top:20px;" name="content">{$formdata['content']}</textarea>
	</div>
	<input type="hidden" value="{$a}" name="a" />
	<input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<input type="submit" name="sub" value="更新" class="button_6" style="margin-top:10px;" />
</form>