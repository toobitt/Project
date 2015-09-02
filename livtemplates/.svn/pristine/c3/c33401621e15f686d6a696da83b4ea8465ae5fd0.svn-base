{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<h2>{$optext}配置</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div">
	<span  class="title">键：</span><input type="text" value='{$formdata["key"]}'  name='key' style="width:275px;"><span style="color:red">*</span>	
	</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">值：</span><input type="text" name="value" value="{$formdata['value']}" style="width:275px;"><font class="important"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">分组：</span>
	<select class="form-control" name="type" style="width:280px">
				{foreach $_configs['config_type'] as $k=>$v}
				<option value="{$k}" {if $k==$formdata['type']}selected="selected"{/if}>{$v}</option>
				{/foreach}
			</select>
	<font class="important"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear"><span class="title">启用：</span><input type="checkbox" class="n-h" name="status" {if $formdata['status']}checked="checked"{/if} value="1"></label>
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