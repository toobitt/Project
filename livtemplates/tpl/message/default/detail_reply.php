{template:head}
{code}
	if($id)
	{
		$optext="编辑";
		$a="update";
	}
	else
	{
		$optext="添加";
		$a="add_reply";
	}
{/code}
{css:calendar}
{css:ad_style}
{js:ad}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}回复</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
	<span class="title">所属分组：</span><input type="text" disabled="true" value='{$formdata["info"][0]["groupname"]}' name='groupname' class="title">
	</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">留言对象id：</span><input type="text" disabled="true" value='{$formdata["info"][0]["contentid"]}' name='contentid' class="title">
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">留言内容：</span><textarea name="content" disabled="true">{$formdata["info"][0]["content"]}</textarea>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">回复作者：</span><input type="text" value='{$formdata["info"][0]["answerer"]}' name='answerer' class="title" >
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">回复内容：</span><textarea name="content_reply">{$formdata["info"][0]["content_reply"]}</textarea>
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
<input type="submit" id="submit_ok" name="sub" value="{$optext}回复" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}