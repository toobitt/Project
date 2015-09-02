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
	var sellername = $.trim($("#sellername").attr("value"));
	var sellernumber = $.trim($("#sellernumber").attr("value"));
	var sellerkey = $.trim($("#sellerkey").attr("value"));
	var sellerid = $("#sellerid").val();
	var SelectValue = $("#typecode").val();   
	var order_id = $("#order_id").val();
	var call_back_url = $("#call_back_url").val();
	var notify_url = $("#notify_url").val();
	var merchant_url = $("#merchant_url").val();

	if (sellername==""){
	  alert("!请输入商户名称");
	  return false
	}
	if(SelectValue=="")
	{
		alert("!请选择支付方式");
		return false
	}
	
	
	if(SelectValue=="alipay" || SelectValue=="tenpay")
	{
		var arrays = new Array();
		if(SelectValue=="alipay")
		{
			var items = document.getElementsByName("alipay_c[]");  //获取name为alipay_c[]的一组元素(checkbox)
		}
		else
		{
			var items = document.getElementsByName("tenpay_c[]");  //获取name为alipay_c[]的一组元素(checkbox)
		}
		
		for(i=0; i < items.length; i++){  //循环这组数据
			if(items[i].checked){      //判断是否选中
				arrays.push(items[i].value);  //把符合条件的 添加到数组中.
			}
		}
		if(arrays.length==0)
		{
			alert("!请选择签约类型");
			return false
		}
	}
	
	
	if(sellernumber=="")
	{
		alert("!请输入商户帐号");
		return false
	}
	if(sellerkey=="")
	{
		alert("!请输入商户帐号密钥");
		return false
	}
	if(SelectValue=="alipay")
	{
		if(sellerid=="")
		{
			alert("!请输入商户合作者id");
			return false
		}
	}
	if(call_back_url=="")
	{
		alert("!请填写同步通知地址");
		return false
	}
	if(notify_url=="")
	{
		alert("!请填写异步通知地址");
		return false
	}
	if(merchant_url=="")
	{
		alert("!请填写付款中断返回地址");
		return false
	}
	if(order_id=="")
	{
		alert("!请填写排序值");
		return false
	}
	
}
function change_se()
{
	var currSelectValue = $("#typecode").val();
	if(currSelectValue=="alipay")
	{
		$("#typecode_c").show();
		$("#hezuozheid").show();
		$("#alipay").show();
		$("#tenpay").hide();
	}
	else if(currSelectValue=="tenpay")
	{
		$("#typecode_c").show();
		$("#hezuozheid").hide();
		$("#alipay").hide();
		$("#tenpay").show();
	}
	else
	{
		$("#typecode_c").hide();
		$("#hezuozheid").hide();
		$("#alipay").hide();
		$("#tenpay").hide();
	}
}
</script>
<div class="ad_middle">
	<form name="editform" id="editform" onsubmit="return checknum()" action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>{$optext}支付设置</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">商户名称：</span>
					<input type="text" name="sellername" id="sellername"  value="{$sellername}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">支付方式：</span>
					<select name="typecode" id="typecode" onchange="change_se(this.options[this.options.selectedIndex].value)">
					{if is_array($showlist) &&!empty($showlist) && count($showlist)>0}
						<option value="" >请选择支付方式</option>
						{foreach $showlist as $k => $v}
		                    <option value="{$v['code']}" {if $v['code']==$typecode} selected {/if}>{$v['pname']}</option>
		                {/foreach}
					{/if}
					</select>
					<font class="important">支付类型</font>
				</div>
			</li>
			<li id="typecode_c" class="i" {if $typecode == "alipay" or $typecode == "tenpay" }style="display:block;"{else} style="display:none;"{/if}>
				<div id="alipay" class="form_ul_div clear" {if $typecode == "alipay"}style="display: block;"{else}style="display: none;"{/if}>
					<span class="title">签约类型：</span>
					<INPUT type="checkbox" name="alipay_c[]" {if strstr($paycode,"alipay_y")} checked="checked" {/if} value="alipay_y"> 手机网页支付(alipay_y)
					<INPUT type="checkbox" name="alipay_c[]" {if strstr($paycode,"alipay_j")} checked="checked" {/if} value="alipay_j"> 即时到账交易(alipay_j)
					<INPUT type="checkbox" name="alipay_c[]" {if strstr($paycode,"alipay_d")} checked="checked" {/if} value="alipay_d"> 担保交易(alipay_d)
					<INPUT type="checkbox" name="alipay_c[]" {if strstr($paycode,"alipay_s")} checked="checked" {/if} value="alipay_s"> 双接口交易(alipay_s)
					<font class="important"></font>
				</div>
				<div id="tenpay" class="form_ul_div clear" {if $typecode == "tenpay"}style="display: block;"{else}style="display: none;"{/if}>
					<span class="title">签约类型：</span>
					<INPUT type="checkbox" name="tenpay_c[]" {if strstr($paycode,"tenpay_j")} checked="checked" {/if} value="tenpay_j"> 即时到账交易(tenpay_j)
					<INPUT type="checkbox" name="tenpay_c[]" {if strstr($paycode,"tenpay_d")} checked="checked" {/if} value="tenpay_d"> 担保交易(tenpay_d)
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">商户帐号：</span>
					<input type="text" name="sellernumber" id="sellernumber"  value="{$sellernumber}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">帐号密钥：</span>
					<input type="text" name="sellerkey" id="sellerkey"  value="{$sellerkey}" style="width:192px"/>
					<font class="important">等同支付宝校验码</font>
				</div>
			</li>
			<li id="hezuozheid" {if $typecode == "alipay"}style="display:block;"{else} style="display:none;"{/if} class="i">
				<div class="form_ul_div clear">
					<span class="title">合作者ID：</span>
					<input type="text" name="sellerid" id="sellerid"  value="{$sellerid}" style="width:192px"/>
					<font class="important">仅支付宝需要设置</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">同步通知：</span>
					<input type="text" name="call_back_url" id="call_back_url"  value="{$call_back_url}" style="width:192px"/>
					<font class="important">同步返回文件路径（绝对地址）</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">异步通知：</span>
					<input type="text" name="notify_url" id="notify_url"  value="{$notify_url}" style="width:192px"/>
					<font class="important">异步通知文件路径（绝对地址）</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">中断返回：</span>
					<input type="text" name="merchant_url" id="merchant_url"  value="{$merchant_url}" style="width:192px"/>
					<font class="important">用户支付中断返回文件路径（绝对地址）</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">是否启用：</span>
					<input type="radio" {if !empty($is_on) && $is_on==1} checked {/if} name="is_on" value="1" /> 是 <input type="radio" {if empty($is_on) || $is_on==0} checked {/if}  name="is_on" value="0" /> 否
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