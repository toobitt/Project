<?php 
/* $Id: member_extension_field_form.php 24581 2013-08-01 04:34:57Z lijiaying $ */
?>
{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{template:head}
{css:ad_style}
{css:style}
<script type="text/javascript">
</script>
{if $a}
	{code}
		$action = $a;
		
		if (!$formdata['extension_sort'])
		{
			$action = 'create';
		}
	{/code}
{/if}
<div class="ad_middle">
	<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<h2>{$optext}会员扩展分类</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">名称：</span>
					<input type="text" name="extension_sort_name" value="{$extension_sort_name}" style="width:192px"/>
					<font class="important">必填</font>
				</div>
				<div class="form_ul_div">
					<span class="title">标识：</span>
					<input type="text" name="extension_sort" {if $id}readonly = "readonly";{/if} value="{$extension_sort}" style="width:192px"/>
					<font class="important">{if !$id}必填,提交后不可修改{else}不可修改{/if}</font>
				</div>
			</li>
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}