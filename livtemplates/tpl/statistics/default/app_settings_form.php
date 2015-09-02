{css:ad_style}
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="run.php?mid={$_INPUT['mid']}" method="post"   id="content_form" name="content_form" onsubmit="return hg_ajax_submit('content_form');">
<h2>配置</h2>
<ul class="form_ul">
{code}
if(isset($formdata['appset']['status']))
{
	$status = empty($formdata['appset']['status'])?'':1;
}
else
{
	$status = 1;
}


{/code}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">是否打开：</span>
		<input type=radio name="status" value=1 {if $status}checked{/if}>是
		<input type=radio name="status" value=0 {if !$status}checked{/if}>否
	</div>
</li>
</ul>

<input type="hidden" name="a" value="update" />
<input type="hidden" name="app_uniqueid" value="{$formdata['app_uniqueid']}" />
<input type="hidden" name="module_uniqueid" value="{$formdata['module_uniqueid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="更改" class="button_6_14"/>
</form>
</div>
</div>

