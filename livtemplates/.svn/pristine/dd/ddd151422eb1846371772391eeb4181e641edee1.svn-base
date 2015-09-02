{css:ad_style}
{css:column_node}
{js:column_node}
{js:iColorPicker}
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l" id="vod_media_node_form" name="vod_media_node_form" onsubmit="return hg_ajax_submit('vod_media_node_form');">
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div">
	<span  class="title">名称：</span>
	<input  type="text" name="sort_name" id="sort_name" style="width:420px;{if $formdata['color']}color:{$formdata['color']};{/if}"  class="info-title info-input-left t_c_b iColorPicker" value="{if $formdata['name']}{$formdata['name']}{else}在这里添加标题{/if}" onfocus="text_value_onfocus(this,'在这里添加标题');" onblur="text_value_onblur(this,'在这里添加标题');" ishidden="true" />
	<input type="hidden" value="{$formdata['color']}" id="sort_name_cv" name="color" />
	</div>
</li>
<li class="i">
	<div class="form_ul_div">
		<span class="title">分类描述：</span>
		<textarea rows="2" style="width:440px;"  class="info-description info-input-left t_c_b" name="sort_desc" onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">{if $formdata['brief']}{$formdata['brief']}{else}这里输入描述{/if}</textarea>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">父级分类：</span>
	{code}
		$hg_attr['node_en'] = 'vod_media_node';
	{/code}
	{template:unit/class,father_node_id,$formdata['fid'],$node_data}
	</div>
	</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" name="sub" value="{$optext}" class="button_6_14"  style="float: right;margin-right:58px;" />
</form>