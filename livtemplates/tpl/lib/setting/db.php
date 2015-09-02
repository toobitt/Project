<?php 
/* $Id: list.php 18206 2013-03-20 02:07:46Z yizhongyue $ */
?>
{css:setting}
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">主机名：</span>
			<input class="form_ul_input" type="text" value="{$settings['db']['host']}" name='db[host]'>
			<font class="important" style="color:red">IP或者域名，必填</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">用户名：</span>
			<input class="form_ul_input" type="text" value="{$settings['db']['user']}" name='db[user]'>
			<font class="important" style="color:red">数据库连接用户名</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">密码：</span>
			<input class="form_ul_input" type="text" value="" name='db[pass]'>
			<font class="important" style="color:red">数据库连接密码</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">数据库名：</span>
			<input class="form_ul_input" type="text" value="{$settings['db']['database']}" name='db[database]'>
			<font class="important" style="color:red">选用的数据库</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">前缀：</span>
			<input class="form_ul_input" type="text" value="{$settings['db']['dbprefix']}" name='db[dbprefix]'>
			<font class="important">数据库表前缀</font>
		</div>
	</li>

	<li class="i"style="padding-top: 5px;">
		<div class="form_ul_div">
			<span  class="title">长连接：</span>
			<div style="display:inline-block;width:255px">{template:form/radio,db[pconncet],$settings['db']['pconncet'],$option}</div>
			<font class="important">是否启用长连接</font>
		</div>
	</li>
</ul>