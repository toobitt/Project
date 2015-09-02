<?php 
/* $Id: stream_server_form.php 8048 2012-02-13 08:32:11Z repheal $ */
?>
{template:head}
{css:mms_style}
{js:mms_default}
<div class="wrap">
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
	{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;
		{/code}
	{/foreach}
	{/if}

		<div class="title">{$optext}服务器信息</div>
		<form name="editform" action="" method="post">
			<ul class="create_list">
				<li class="i"><span>服务器名称:</span><input type="text" name="name" value="{$name}"/></li>
				<li class="i"><span>服务器简介:</span><textarea name="brief" rows="2" cols="35">{$brief}</textarea></li>
				<li class="i"><span>服务器域名:</span><input type="text" name="server_name" value="{$server_name}"/></li>
				<li class="i"><span>服务器地址:</span><input type="text" name="server_path" value="{$server_path}"/></li>
				<li class="i"><span>服务器IP:</span><input type="text" name="server_ip" value="{$server_ip}"/></li>
				<li class="i btn"><input type="submit" name="sub" value="{$optext}" /></li>
			</ul>
		<input type="hidden" name="a" value="{$action}" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</form>
	
</div>
{template:foot}