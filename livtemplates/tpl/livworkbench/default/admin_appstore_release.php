<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<h2>正在{$action}[{$appinfo['name']}]应用</h2>
<div class="wrap n">
	<form name="editform" action="" method="post" class="ad_form h_l" >
		{if $message}
		<div style="color:red;" id="msg" class="msg">{$message}</div>
		{/if}
			<ul class="form_ul">
			<li class='i'>
				<span>程序版本:</span>
				{if $doaction != 'dorelease'}
					<input type="text" name="version" id="version"  size="30" value="{$appinfo[$version]}" onchange="if(this.value != $('#hideversion').val()){$('#content').val('')}else
					{$('#content').val('{$appinfo['version_features']['preversion_content']}')}" />
					<input type="hidden" name="hideversion" id="hideversion"  size="30" value="{$appinfo[$version]}" />
					<font class="important" style="color:red">同版本号则更新当前版本</font>
					<div>
				   <span>版本特性:</span>  <textarea name="content" id="content" rows="5" cols="100">{$appinfo['version_features']['preversion_content']}</textarea></div>
				{else}
					{$appinfo['pre_version']}
					<div>{$appinfo['version_features']['preversion_content']}</div>
				{/if}
			</li>
			<li class='i'>
				<input type="submit" name="sub" value="{$action}" class="button_6_14" style="float:left;margin-left:20px;" />
			</li>
			<input type="hidden" name="app" value="{$appinfo['app_uniqueid']}" />
			<input type="hidden" name="a" value="{$doaction}" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="goon" value="1" />
		</ul>
	</form>
</div>

{template:foot}