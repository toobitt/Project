{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<h2>新增广告客户端</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div">
	<span  class="title">名称：</span><input type="text" value='{$formdata["name"]}' {if $formdata['name']}readonly="readonly" disabled="disabled"{/if} name='name' style="width:275px;"><span style="color:red">*</span>
	<font class="important">创建之后无法修改</font>
	</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">描述：</span><textarea name="brief" style="width:300px;height:50px;">{$formdata['brief']}</textarea>
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">关联模块：</span><input type="text" name="model_name" value="{$formdata['model_name']}" style="width:275px;"><font class="important">指客户端关联的应用，无特殊需求留空即可</font>
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">英文标志：</span><input type="text" name="flag" value="{$formdata['flag']}"  {if $formdata['flag']}readonly="readonly" disabled="disabled"{/if} style="width:275px;"><span style="color:red">*</span><font class="important">客户端标识，英文和数字，创建后无法修改</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear"><span class="title">启用：</span><input type="checkbox" class="n-h" name="is_use" {if $formdata['is_use']}checked="checked"{/if} value="1"></label>
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