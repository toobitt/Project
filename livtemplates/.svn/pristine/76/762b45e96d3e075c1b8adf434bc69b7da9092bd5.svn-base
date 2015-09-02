{template:head}
{code}
	if($id)
	{
		$optext="回复";
		$a="mailbox_reply";
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
<h2>{$optext}留言</h2>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">标题：</span><input type="text" value='{$formdata["info"][0]["title"]}' name='title' class="title" disabled="true">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">用户名：</span><input type="text" value='{$formdata["info"][0]["author"]}' name='author' class="title" disabled="true">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">邮箱：</span><input type="text" value='{$formdata["info"][0]["email"]}' name='email' class="title" disabled="true">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">留言时间：</span><input type="text" value='{$formdata["info"][0]["time"]}' name='time' class="title" disabled="true">
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">留言内容：</span><textarea name="issue" disabled="true">{$formdata["info"][0]["issue"]}</textarea>
		</div>
	</li>
	{if(isset($formdata["type"]))}
	<li class="i">
		<div class="form_ul_div clear">
		<span class="title">选择分组：</span>
			<select name="tid" disabled="true">
				<option value="0" >请选择</option>
					{foreach $formdata["type"] as $ke=>$v}
					 {if $v['id'] == $formdata["info"][0]["tid"]}
					 <option value="{$v['id']}" selected="selected" >{$v["type_name"]}</option>
					 {else}
					 <option value="{$v['id']}" >{$v["type_name"]}</option>
					 {/if}
					{/foreach}
			</select>
		</div>
	</li>
	{/if}
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">回复者：</span><input type="text" value='{$formdata["info"][0]["poster"]}' name='poster' class="title" >
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">回复内容：</span><textarea name="repcontent">{$formdata["info"][0]["repcontent"]}</textarea>
		</div>
	</li>
</ul>
<input type="hidden" value="{$formdata['id']}" id="id" name="id" />
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="goon" value="0" id="goon"/>
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}留言" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}">返回前一页</a></h2>
</div>
</div>