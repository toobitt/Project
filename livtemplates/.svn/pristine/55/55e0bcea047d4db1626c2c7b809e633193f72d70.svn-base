<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
{css:share_list}
{js:share}
{js:jquery-ui-1.8.16.custom.min}
<script type="text/javascript">
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
{if $message}
<div class="error">{$message}</div>
{/if}
{code}//print_r($formdata);{/code}
<form name="editform" id='editform' action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l" onsubmit="return hg_ajax_submit('editform')">
<h2>{if $primary_key}编辑{else}新增{/if}分享</h2>
<ul class="form_ul">

<input type="text" name="jsontext" id="jsontext" value="{$json}"  style="display:none"/>
<li class="i">
<div class="form_ul_div clear">
<span class="title">名称: </span>
<span class="title" style="width:250px;text-align:left;">{$formdata['apparr']['custom_name']}</span>

</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">状态: </span>
	<select  name="status" id='status' style="height:20px" >
		{foreach $_configs['status'] AS $k => $v}
		<option value="{$k}" {if $formdata['apparr']['status']==$k} selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span  class="title">平台: </span>
<div style="overflow:hidden;">
{code}$i=1;$pids = $formdata['apparr']['platIds']?explode(',',$formdata['apparr']['platIds']):array();{/code}
{foreach $formdata['platdata'] as $k1=>$v1}
<span style="font-size:12px;color:#505050;">
<input type="checkbox" name="platlist[]"  value="{$k1}" {code}if(in_array($k1,$pids)) echo "checked";{/code}  />
{$v1}</span>
{code}
if($i%5==0)
echo "<br>";
$i++;
{/code}
{/foreach}
</div>


</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" id="{$primary_key}"  name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
<br>
<input type="button" value="更新"   class="button_6" id="direct_create" onclick="hg_direct_create_tuji('editform');" style="margin-left:75px;"/>
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
{template:foot}
