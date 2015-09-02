<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{css:ad_style}
{js:share}
{js:jquery-ui-1.8.16.custom.min}
<script type="text/javascript">
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
{if $message}
<div class="error">{$message}</div>
{/if}
{code}//print_r($formdata['appplatId']);{/code}
<form name="editform" id='editform' action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l" onsubmit="return hg_ajax_submit('editform')">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
</div>

<a href="javascript:void(0)"  onclick="hg_close_opration_info();" title="关闭/ALT+Q">关闭</a>

</li>
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
		<option value="{$k}" {if $formdata['apparr']['status']==$k}selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span  class="title">平台: </span>
{code}$i=1;$pids = explode(',',$formdata['apparr']['platIds']);{/code}
{foreach $pids as $k1=>$v1}
<span style="font-size:12px;color:#505050;">
{$formdata['platdata'][$v1]}&nbsp;&nbsp;&nbsp;</span>
{code}
if($i%4==0)
echo "<br>";
$i++;
{/code}
{/foreach}



</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" id="{$primary_key}"  name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
<br>
<a class="button_4"  onclick="hg_showAddShare({$formdata['apparr']['appid']});" href="javascript:void(0);">编辑</a>
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
