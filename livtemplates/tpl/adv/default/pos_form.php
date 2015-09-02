{template:head}
{code}
	$type = array('0'=>'图片','1'=>'flash','2'=>'视频','3'=>'文字');
	$is_use = array(1=>'是',0=>'否');
	$unit = array('日','月','年');
	$is_global = array(1=>'是',0=>'否');
{/code}
{css:ad_style}
{js:ad}
<style type="text/css">
.ad_form .form_ul .form_ul_div span.title{width:75px;margin-right: 0px;}
#adpos_para li{line-height: 36px;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<h2>新增广告位</h2>
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<ul id="form_ul" class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">位名称：</span><input style="width:275px;" type="text" value="{$formdata['zh_name']}" name='zh_name' {if $formdata['zh_name']}readonly="readonly" disabled="disabled"{/if} />
	<font class="important">创建之后无法修改</font>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">英文标识：</span><input style="width:275px;" type="text" value="{$formdata['name']}" name='name' {if $formdata['name']}readonly="readonly" disabled="disabled"{/if} />
	<font class="important">创建之后无法修改</font>
	</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">浮动：</span><input type="radio" {if $formdata['ani_id']==1}checked="checked"{/if} value='1' name='ani' style="float:left">
<span class="title">固定：</span><input type="radio" {if $formdata['ani_id']==2}checked="checked"{/if} value='2' name='ani'>
<font class="important">广告位类型</font>
</div>
</li>
<li class="i" id="group_list" style="display:none">
<div class="form_ul_div clear">
<span class="title">所属分组：</span>
<!-- group包含所有分组 flag=>name -->
	{foreach $group as $k=>$v}
	{code}
		$checked = '';
		if($v == $formdata['group_flag'][$k])
		{
			$checked = 'checked';
		}
	{/code}
		<label><input type="checkbox" value="{$k}" name="select_group[]" {$checked}  class="n-h" /><span>{$v}</span></label>
	{/foreach}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">启用：</span><input type="checkbox" class="n-h" name="is_use" {if $formdata['is_use']}checked="checked"{/if} value="1">
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">多投：</span><input type="checkbox" class="n-h" name="multi" {if $formdata['multi']}checked="checked"{/if} value="1">
<font class="important">指广告位支持投放多个广告，非按权重显示</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">自定义参数：</span>
	<div style="margin-left:75px;">
		<ul id="adpos_para">
			{template:unit/dynamic_para, p,p,$formdata['para']}
		</ul>
	</div>
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
<script type="text/javascript">
$(function(){
	show_group = function(){
		var is_global = $('input[name="is_global"]:checked').val();
		if(!parseInt(is_global))
		{
			$('#group_list').show();
		}
		else
		{
			$('#group_list').hide();
		}
	};
	show_group();
	$('input[name="is_global"]').change(function(){show_group();});
})
</script>
{template:foot}