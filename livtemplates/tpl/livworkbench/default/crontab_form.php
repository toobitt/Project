<?php 
/* $Id: crontab_form.php 7841 2012-01-30 02:37:21Z develop_tong $ */
?>
{template:head}
{js:crontab}
{css:ad_style}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_columns first"><em></em><a>计划任务</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$optext}计划任务</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div">
<span  class="title">主机(host): </span><input type="text" name="host" value="{$formdata['host']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span  class="title">端口(port): </span><input type="text" name="port" value="{code} echo $formdata['port'] ? $formdata['port']:80;{/code}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span  class="title">路径(dir): </span><input type="text" name="dir" value="{$formdata['dir']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span  class="title">文件: </span><input type="text" name="file_name" size="50" value="{$formdata['file_name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span  class="title">Token: </span><input type="text" name="token" size="50" value="{$formdata['token']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span  class="title">执行时间: </span><input type="text" name="run_time" size="24" value="{$formdata['run_time']}" /><font class="important"></font>
</div>
</li>
</li>
<li class="i">
<div class="form_ul_div">
<span  class="title">间隔时间: </span><input type="text" name="space" size="4" value="{$formdata['space']}" /><font class="important">秒</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">记录日志 </span>
{if $formdata['is_log']}
	{code}
		$checked_true = 'checked';
		$checked_false = '';
	{/code}
{else}
	{code}
		$checked_true = '';
		$checked_false = 'checked';
	{/code}
{/if}
<label><input type="radio" name="is_log" size="50" class="n-h" value="1" {$checked_true}/><span>是</span></label>
<label><input type="radio" name="is_log" size="50" class="n-h" value="0" {$checked_false}/><span>否</span></label>
</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}