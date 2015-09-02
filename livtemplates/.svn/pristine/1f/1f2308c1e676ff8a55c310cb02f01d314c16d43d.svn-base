{template:head}
{code}
	if($id)
	{
		$optext="更新";
		$a="update";
	}
{/code}
{css:calendar}
{css:ad_style}
{js:calendar}
{js:ad}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}消息</h2>
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">用户id：</span><input type="text" value='{$formdata["info"][0]["user_id"]}' name='user_id' class="title">
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">消息内容：</span><textarea name="message">{$formdata["info"][0]["message"]}</textarea>
</div>
</li>

</ul>
<input type="hidden" value="{$formdata['info'][0]['id']}" id="id" name="id" />
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="goon" value="0" id="goon"/>
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}消息" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}">返回前一页</a></h2>
</div>
</div>
{template:foot}