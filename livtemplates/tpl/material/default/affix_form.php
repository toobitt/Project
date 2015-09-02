{template:head}
{js:hg_affix}
{css:ad_style}
{js:ad}
<script type="text/javascript">
$(function(){
	hg_swf_affix();
});
</script>

<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post"   id="content_form">
<h2>编辑图片附件</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">缩略图：</span>
		<div id="slt" style="display:inline-block;"><img src="{$formdata['url']}"  alt="缩略图" style="width:100px;height:75px;"/></div>
		<div id="image_affix"></div>
	</div>
</li>
</ul>
<input type="hidden" name="filename" value="{$formdata['filename']}" />
<input type="hidden" name="filepath" value="{$formdata['filepath']}" />
<input type="hidden" name="server_mark" value="{$formdata['server_mark']}" />
<input type="hidden" name="app_bundle" value="{$formdata['bundle_id']}" />
<input type="hidden" name="a" value="update" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="更新" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}">返回前一页</a></h2>
</div>
</div>

<script type="text/javascript">

</script>
{template:foot}