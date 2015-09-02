{template:head}
{css:ad_style}
<style>
.ad_middle textarea{min-height: 250px;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
<h2>查看证书</h2>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">开发版：</span><textarea name="develop" style="width: 500px;">{$formdata["develop"]}</textarea>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">应用版：</span><textarea name="apply" style="width: 500px;">{$formdata["apply"]}</textarea>
		</div>
	</li>

</ul>
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="button" value="返回" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}