<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:appstore}
{js:underscore}
{js:appstore/app}

<script>
var app = '{$app}';
</script>
<h2>正在更新以下应用</h2>
	<div>
		{if $update_apps}
		<ul>
		{foreach $update_apps as $app}
			<li>[{$app['groupname']}] {$app['name']}_{$app['version']}</li>
		{/foreach}
		</ul>
		{/if}
	</div>
		<form name="editform" action="" method="post" class="ad_form h_l" >
		{if $message}
		<div style="color:red;" id="msg" class="msg">{$message}</div>
		{/if}
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span  class="title">数据库用户：</span>
					<input type="text" value="" name='dbuser' style="width:150px;">
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span  class="title">数据库密码：</span>
					<input type="text" value="" name='dbpass' style="width:150px;">
				</div>
			</li>
			<li class='i'>
				<input type="submit" name="sub" value="确认更新" class="button_6_14" style="float:left;margin-left:20px;" />
			</li>
			<input type="hidden" name="a" value="doonekupdate" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="goon" value="1" />
		</ul>
	</form>
</body>
</html>