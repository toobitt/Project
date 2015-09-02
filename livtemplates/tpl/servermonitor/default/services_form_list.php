<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="servicesform"  id="servicesform"  onsubmit="return hg_ajax_submit('servicesform');">
	<div style="width:100%;margin-top:10px;">
		<label style="margin-left:24px;">服务名称：</label>
		<input type="text" name="name" value="{$formdata['name']}" style="width:442px;"/>
	</div>
	<div style="width:100%;margin-top:10px;">
		<input type="submit"  value="{$optext}" class="button_6" style="margin-left:441px;" />
	</div>
	<input type="hidden" value="{$a}" name="a" />
	<input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
