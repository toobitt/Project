{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
{js:iColorPicker}
{css:2013/iframe}
<div class="wrap">
	<div class="ad_middle">
		<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l" id="tuji_sort" name="tuji_sort" onsubmit="return hg_ajax_submit('tuji_sort');">
		<h2>新增分类</h2>
		<ul class="form_ul">
		<li class="i">
			<div class="form_ul_div">
			<span  class="title">名称：</span>
			<input  type="text" name="sort_name" id="sort_name" style="width:420px;{if $formdata['color']}color:{$formdata['color']};{/if}"  class="info-title info-input-left t_c_b iColorPicker" value="{if $formdata['name']}{$formdata['name']}{else}在这里添加标题{/if}" onfocus="text_value_onfocus(this,'在这里添加标题');" onblur="text_value_onblur(this,'在这里添加标题');"  ishidden="true" />
			<input type="hidden" value="{$formdata['color']}" id="sort_name_cv" name="color" />
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">分类描述：</span>
				<textarea rows="2" style="width:440px;" class="info-description info-input-left t_c_b" name="sort_desc" onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">{if $formdata['brief']}{$formdata['brief']}{else}这里输入描述{/if}</textarea>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">父级分类：</span>
				{code}
					$hg_attr['node_en'] = 'tuji_node';
				{/code}
				{template:unit/class,father_node_id,$formdata['fid'],$node_data}
			</div>
		</li>
		<li>
		    <input type="submit" name="sub" value="{$optext}" class="button_6_14" style="margin-top:15px;"/>
		</li>
		</ul>
		<input type="hidden" name="a" value="{$a}" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</form>
	</div>
	<div class="right_version">
			<h2><a href="/livworkbench/run.php?mid=13&a=configuare&infrm=1">返回前一页</a></h2>
	</div>
</div>