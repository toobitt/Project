<?php 
/* $Id: settings_form.php 9172 2012-05-10 01:09:44Z wangleyuan $ */
?>
{template:head}
{css:ad_style}
{js:ad}
<script type="text/javascript">

</script>
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
<div class="wrap clear">
	<div class="ad_middle">
	<form name="editform" action="" method="post" class="ad_form h_l">
		<h2>{$optext}配置</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">名称：</span>
					<input type="text" name="name" value="{$name}" />
				</div>
			</li>				
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">标识：</span>
					<input type="text" name="mark" value="{$mark}" />
				</div>
			</li>
		</ul>
	<input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
	</form>
	</div>
	<div class="right_version">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
	</div>
</div>
{template:foot}