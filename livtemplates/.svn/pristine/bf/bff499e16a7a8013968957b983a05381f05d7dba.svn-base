{template:head}
{css:ad_style}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<h2>广告发布可控条件选择</h2>
<ul class="form_ul">
{code}
$selected = array_keys($formdata['selected']);
unset($formdata['selected']);
{/code}
{foreach $formdata as $k=>$v}
{code}
	$checked = '';
	if(in_array($k, $selected))
	{
		$checked = 'checked = "checked"';
	}
{/code}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v}</span><input type="checkbox" {$checked} value="{$v}@{$k}" name="policy[]">
	</div>
</li>
{/foreach}
</ul>
<input type="hidden" name="a" value="dopolicy" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" name="sub" value="确定" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}