<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="serverform"  id="serverform"  onsubmit="return hg_ajax_submit('serverform');">
	<div style="width:100%;margin-top:10px;">
		<label style="margin-left:24px;">服务器名称：</label>
		<input type="text" name="name" value="{$formdata['name']}" style="width:442px;"/>
	</div>
	<div style="width:100%;margin-top:10px;margin-left:23px;">
		<label>服务器标识：</label>
		<input type="text" name="uniqueid"  value="{$formdata['uniqueid']}"  style="width:442px;" />
	</div>
	<div style="width:100%;margin-top:10px;margin-left:49px;">
		<label>内网ip：</label>
		<input type="text" name="ip"  value="{$formdata['ip']}"  style="width:442px;" />
	</div>
	<div style="width:100%;margin-top:10px;margin-left:49px;">
		<label>外网ip：</label>
		<input type="text" name="outside_ip"  value="{$formdata['outside_ip']}"  style="width:442px;" />
	</div>
	<div style="width:100%;margin-top:10px;margin-left:45px;">
		<label>端口号：</label>
		<input type="text" name="port"  value="{$formdata['port']}"  style="width:442px;" />
	</div>
	<div style="width:100%;margin-top:10px;margin-left:45px;">
		<label>用户名：</label>
		<input type="text" name="user"  value="{$formdata['user']}"  style="width:442px;" />
	</div>
	<div style="width:100%;margin-top:10px;margin-left:56px;">
		<label>密码：</label>
		<input type="text" name="password"  value="{$formdata['password']}"  style="width:442px;" />
	</div>
	<div style="width:100%;margin-top:10px;margin-left:20px;">
		<label>当前服务器：</label>
		<input type="checkbox" name="iscur"  value="1"  {if $formdata['iscur']}checked="checked"{/if} />
	</div>
	<div style="width:100%;margin-top:10px;">
		<input type="submit"  value="{$optext}" class="button_6" style="margin-left:441px;" />
	</div>
	<input type="hidden" value="{$a}" name="a" />
	<input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
