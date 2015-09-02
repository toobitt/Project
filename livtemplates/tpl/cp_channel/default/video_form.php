{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
{code}

{/code}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<h2>编辑视频</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div">
		<span  class="title">名称：</span><input type="text" value='{$formdata["title"]}' name='title' style="width:275px;">
	</div>
</li>
<li class="i">
	<div class="form_ul_div">
		<span  class="title">标签：</span><input type="text" value='{$formdata["tags"]}' name='tags' style="width:275px;">
	</div>
</li>
<li class="i">
	<div class="form_ul_div"><span class="title">简介：</span>
		<textarea name="brief" style="width:300px;height:50px;">{$formdata['brief']}</textarea>
	</div>
</li>
<li class="i">
<div class="form_ul_div clear">
	<span class="title">状态：</span>
	<label><input type="radio" class="n-h" name="is_show" {if !$formdata['is_show']}checked="checked"{/if} value="0">待审核</label>
	<label><input type="radio" class="n-h" name="is_show" {if $formdata['is_show'] == 1}checked="checked"{/if} value="1">未审核</label>
	<label><input type="radio" class="n-h" name="is_show" {if $formdata['is_show'] == 2}checked="checked"{/if} value="2">已发布</label>
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