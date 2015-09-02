{template:head}
{code}
	if($id)
	{
		$optext="回复";
		$a="reply_comment";
	}
{/code}
{css:calendar}
{css:ad_style}
{js:ad}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}评论</h2>
<ul class="form_ul">

	<li class="i">
		<div class="form_ul_div clear">
		<span class="title">标题：</span><input type="text" value='{$formdata["title"]}' class="title" disabled="true">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">评论内容：</span><textarea  disabled="true">{$formdata["content"]}</textarea>
		</div>
	</li>

	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">回复者id：</span><input type="text"  name='member_id' class="title" >
			<font class="important">不填默认管理员回复</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
		<span class="title">回复标题：</span><input type="text" name="title" class="title">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">回复内容：</span><textarea name="reply_content"></textarea>
		</div>
	</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="cmid" value="{$formdata['cmid']}" />
<input type="hidden" name="contentid" value="{$formdata['contentid']}" />
<input type="hidden" name="app_uniqueid" value="{$formdata['app_uniqueid']}" />
<input type="hidden" name="mod_uniqueid" value="{$formdata['mod_uniqueid']}" />
<input type="hidden" name="site_id" value="{$formdata['site_id']}" />
<input type="hidden" name="column_id" value="{$formdata['column_id']}" />
<input type="hidden" name="content_title" value="{$formdata['content_title']}" />


<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="run.php?mid={$_INPUT['mid']}&infrm=1" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}评论" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}