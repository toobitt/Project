<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
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
<h2>{$optext}用户系统</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">系统名称: </span>
		<input type="text" name="iusname"  value="{$iusname}" style="width:130px;"/>
	</div>
	<div class="form_ul_div clear info">
		<span class="title">描述备注: </span><textarea name="brief" id="brief"  cols="45" rows="4" />{$brief}</textarea>
	</div>
		{code}
				$CmySetDisplay = array(
					'1'=>'是',
					'0'=>'否',
				);
			{/code}
		<div class="form_ul_div clear pre-option">
		<span class="title">开启:</span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{if is_array($CmySetDisplay)}
			{foreach $CmySetDisplay as $k=>$v}
		         <li ><input type="radio" class="opened" name="opened" {if ($opened == $k)} checked="checked"{/if}  value ="{$k}" _val="{code} echo $v;{/code}"><span>{$v}</span></li>
		    {/foreach}
		    {/if}
			</ul>
			<span class="error" id="title_tips" style="display:block;">*是否开启此系统</span>
		</div>
	</div>
</li>
</ul>

<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['iusid']}" />
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

