<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>

{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:member_form}
{js:jqueryfn/jquery.tmpl.min}
{js:bigcolorpicker}
{js:area}

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
{code}//print_r($updatetype);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>{$optext}权限</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">权限名称: </span>
		<input type="text" name="pname"  value="{$pname}" style="width:200px;"/>
	</div>
	<div class="form_ul_div clear info">
		<span class="title">标识: </span>
		<input type="text" name="operation"  value="{$operation}" {if $operation}disabled="disabled"{/if} style="width:180px;"/>
	</div>
</li>
<li class="i option-type">
	<div class="form_ul_div clear pre-option">
		<span class="title">操作类型: </span>
			<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{foreach $_configs['member_purview_type'] as $purview_type_id=>$purview_type_name}
		         <li ><input type="radio" class="allow" name="allow" {if ($purview_type_id==$allow)} checked="checked"{/if}  value ="{$purview_type_id}" _val="{code} echo $purview_type_name;{/code}"><span>{$purview_type_name}</span></li>
		    {/foreach}
		</ul><span class="error" id="title_tips" style="display:block;">*拥有此权限的用户是通过还是拒绝.</span>
		</div>
	
	</div>
</li>
</ul>

<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<div class="temp-edit-buttons">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}

