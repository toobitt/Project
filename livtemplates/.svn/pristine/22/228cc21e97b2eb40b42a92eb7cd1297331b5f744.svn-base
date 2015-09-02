{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<h2>{$optext}服务器信息</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div">
	<span  class="title">主机：</span><input type="text" value='{$formdata["host"]}' name='host' style="width:275px;">
	<font class="important"></font>
	</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">端口：</span><input type="text" name="port" value="{$formdata['port']}" style="width:275px;">
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">用户名：</span><input type="text" name="user" value="{$formdata['user']}" style="width:275px;"><font class="important"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">密码：</span><input type="text" name="pass" value="{$formdata['pass']}"  style="width:275px;"><font class="important"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear"><span class="title">数据库：</span><input type="text" name="database" value="{$formdata['database']}"  style="width:275px;"><font class="important"></font>
<font class="important">数据库不存在时自动创建</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear"><span class="title">编码：</span><input type="text" name="charset" value="{$formdata['charset']}"  style="width:275px;"><font class="important"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear"><span class="title">永久连接：</span><input type="checkbox" name="pconnect" value=1 {if $formdata['pconnect']}checked="checked"{/if}  style="width:275px;"><font class="important"></font>
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