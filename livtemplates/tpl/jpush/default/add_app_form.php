{template:head}
{code}
	foreach($formdata as $k=>$v)
	{
		$$k = $v;
	}
	if($id)
	{
		$optext="更新";
		$a="update";
	}
	else
	{
		$optext="添加";
		$a="create";
	}
{/code}
{css:calendar}
{css:ad_style}

<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" name="editform" action="run.php?mid={$_INPUT['mid']}&a=update" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}应用</h2>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">应用名称: </span><input type="text" name="name" size="48" value="{$name}"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">AppKey: </span><input type="text" name="app_key" size="48" value="{$app_key}" />
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">MasterSecret: </span><input type="text" name="master_secret" size="48" value="{$master_secret}" />
		</div>
	</li>
	
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}应用" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}