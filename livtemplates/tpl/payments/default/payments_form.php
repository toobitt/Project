<?php 
/* $Id: patments_form.php 17834 2013-03-22 03:25:33Z jeffrey $ */
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
	var pname = $.trim($("#pname").attr("value"));
	var code = $.trim($("#code").attr("value"));

	if (pname==""){
	  alert("!请输入支付名称");
	  return false
	}
	if(code=="")
	{
		alert("!请输入支付标识");
		return false
	}
}
</script>
<div class="ad_middle">
	<form name="editform" id="editform" onsubmit="return checknum()" enctype="multipart/form-data"  action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>{$optext}支付方式</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">支付名称：</span>
					<input type="text" name="pname" id="pname"  value="{$pname}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">LOGO：</span>
					<span class="file_input s" style="float:left;">上传图片</span>
					<span style="float:right;">
					</span>
					<input name="picture" id="picture" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">预览：</span>
					<span style="float:left;">
					<img width="80" height="60" src="{$logo}" />
					<input type="hidden" name="imgurl" value="{$logo}" />
					</span>
					<span style="float:right;"></span>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">标识：</span>
					<input type="text" name="code" id="code" value="{$code}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">是否启用：</span>
					<input type="radio" {if !empty($is_on) && $is_on==1} checked {/if} name="is_on" value="1" /> 是 <input type="radio" {if empty($is_on) || $is_on==0} checked {/if}  name="is_on" value="0" /> 否
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">支付描述：</span>
					<textarea rows="" cols="" name="miaoshu">{$miaoshu}</textarea>
					<font class="important"></font>
				</div>
			</li>
			{if !empty($ip)}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">IP：</span>
					<input type="text" name="ip" value="{$ip}" readonly style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			{/if}
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