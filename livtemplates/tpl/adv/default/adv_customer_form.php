
<form action="run.php?mid={$_INPUT['mid']}&a=create_customer" method="post" enctype="multipart/form-data" class="ad_form h_l" onsubmit="return hg_ajax_submit('add_customer')" id="add_customer" name="add_customer">
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">客户名：</span><input type="text" value='{$formdata["customer_name"]}' name='customer_name'>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">固定电话：</span><input type="text" value='{$formdata["tel"]}' name='tel'>
	</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">移动电话：</span><input type="text" value='{$formdata["mobile"]}' name='mobile'>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">邮箱：</span><input type="text" value='{$formdata["email"]}' name='email'>
</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">地址：</span><input style="width:300px;" type="text" value='{$formdata["address"]}' name='address'>
	</div>
</li>
</ul>
<input type="hidden" name="infrm" value="1" />
<br />
<input style="margin-left:77px;" type="submit" name="sub" value="确定" class="button_2"/>
<input type="button" name="sub" onclick="$('#ad_customer').fadeOut()" value="返回" class="button_2"/>
</form>