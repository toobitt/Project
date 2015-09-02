<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
?>
{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:copywriting_form}
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
<h2>{$optext}配置分类</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">名称: </span>
		<input type="text" name="grouptitle"  value="{$grouptitle}" />{if !$grouptitle}*必填{/if}
	</div>
		<div class="form_ul_div clear info">
		<span class="title">标识: </span>
		<input type="text" name="groupmark"  value="{$groupmark}" {if $groupmark}readonly="true"{/if}/>{if $groupmark}*禁止修改,会出现问题{else}*必填{/if}
	</div>
	<div class="form_ul_div clear info">
		<span class="title">描述: </span><textarea name="description" id="description"  cols="45" rows="4" />{$description}</textarea>
	</div>
		<div class="form_ul_div clear">
		<span class="title">所属应用: </span>
		<ul class="type-choose clear">
				{if is_array($configSetInfo)}
				{foreach $configSetInfo as  $value}
		         {code}
		             $appuniqueid = $value['app_uniqueid'];
			         $appname = $value['appname'];
			        $flag = 0;
			        if(is_array($app_uniqueid))
			        {
			            if (in_array($appuniqueid,$app_uniqueid)) $flag=1;
			        }
			        else 
			        {
			            if($appuniqueid == $app_uniqueid) $flag=1;
			        }
		         {/code}
		         <li><input type="checkbox" {if $flag} checked="checked"{/if} value="{$appuniqueid}" name="app_uniqueid[]"/><span>{code}echo $appname;{/code}</span></li>
	        {/foreach}
	        {/if}	
		</ul>
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

