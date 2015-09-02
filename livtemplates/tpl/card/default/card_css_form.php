<?php 
/* $Id: card_css_form.php 17834 2013-03-22 03:25:33Z jeffrey $ */
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
	var order_id = $.trim($("#order_id").attr("value"));
	var divcss = $.trim($("#divcss").attr("value"));

	if (title==""){
	  alert("!请输入标题");
	  return false
	}
	if(order_id=="")
	{
		alert("!请输入排序值");
		return false
	}
	if(divcss=="")
	{
		alert("!请输入样式内容");
		return false
	}
}
</script>
<div class="ad_middle">
	<form name="editform" id="editform" onsubmit="return checknum()" enctype="multipart/form-data"  action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>{$optext}样式配置</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">样式标题：</span>
					<input type="text" name="title" id="title"  value="{$title}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">缩略图：</span>
					<span class="file_input s" style="float:left;">上传图片</span>
					<span style="float:right;">
					</span>
					<input name="picture" id="picture" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
				</div>
			</li>
			{if $id!="" }
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">预览：</span>
					<span style="float:left;">
					<img width="150" height="60" src="{$picture}" />
					<input type="hidden" name="imgurl" value="{$picture}" />
					</span>
					<span style="float:right;"></span>
				</div>
			</li>
			{/if}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">Div+Css：</span>
					<textarea  id="divcss" name="divcss">{$divcss}</textarea>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">排序：</span>
					<input type="text" id="order_id" name="order_id" value="{$order_id}" style="width:40px"/>
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