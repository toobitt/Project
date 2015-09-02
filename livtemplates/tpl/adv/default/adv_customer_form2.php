{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<h2>新增客户</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div">
	<span  class="title">客户名：</span><input type="text" value='{$formdata["customer_name"]}' name='customer_name' style="width:275px;">
	<font class="important"></font>
	</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">固定电话：</span><input type="text" name="tel" value="{$formdata['tel']}" style="width:275px;">
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">移动电话：</span><input type="text" name="mobile" value="{$formdata['mobile']}" style="width:275px;"><font class="important"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">邮箱：</span><input type="text" name="email" value="{$formdata['email']}"  style="width:275px;"><font class="important"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear"><span class="title">地址：</span><input type="text" name="address" value="{$formdata['address']}"  style="width:275px;"><font class="important"></font>
</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}