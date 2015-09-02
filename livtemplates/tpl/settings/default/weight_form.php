<?php 
/* $Id: weight_form.php 17834 2013-03-22 03:25:33Z jeffrey $ */
?>
{template:head}
{css:ad_style}

{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;
		{/code}
	{/foreach}
{/if}
<script type="text/javascript">
function checknum()
{
	var title = $.trim($("#title").attr("value"));
	var begin_w = $.trim($("#begin_w").attr("value"));
	var end_w = $.trim($("#end_w").attr("value"));
	var order_id = $.trim($("#order_id").attr("value"));

	if (title==""){
	  alert("请输入标题");
	  return false
	}
	if(begin_w=="")
	{
		alert("请输入初始值");
		return false
	}
	if(end_w=="")
	{
		alert("请输入终点值 ");
		return false
	}
	if(order_id=="")
	{
		alert("请输入排序值");
		return false
	}
	
	if(isNaN(begin_w) || (begin_w>100) || (begin_w<0) || isNaN(end_w) || (end_w>100) || (end_w<0) || isNaN(order_id))
	{
		alert("请正确填写数字");
		return false
	}
	
	if(parseInt(begin_w) > parseInt(end_w))
	{
		alert("初始值不能大于终点值");
		return false
	}
}
</script>
<div class="ad_middle">
	<form name="editform" onsubmit="return checknum()" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data"  class="ad_form h_l">
		<h2>查看编辑详情</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">类别标题：</span>
					<input type="text" id="title" name="title" value="{$title}" style="width:192px"/>
					<font class="important">必填</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">初始值：</span>
					<input type="text" id="begin_w" name="begin_w" value="{$begin_w}" style="width:40px"/>&nbsp;&nbsp;(包含该值)
					<font class="important">0&le;X&le;100</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">终点值：</span>
					<input type="text" id="end_w" name="end_w" value="{$end_w}" style="width:40px"/>&nbsp;&nbsp;(包含该值)
					<font class="important">X&le;y&le;100</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">排序：</span>
					<input type="text" id="order_id" name="order_id" value="{if($order_id)}{$order_id}{else}9999{/if}" style="width:40px"/>
					<font class="important"></font>
				</div>
			</li>
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="id" value="{$id}" />
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}