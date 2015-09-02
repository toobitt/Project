{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
<div class="ad_middle">
<form action="columns.php" method="post" enctype="multipart/form-data" class="ad_form h_l">
<h2>正在发布的频道-{$formdata['name']}</h2>
<ul class="form_ul">
	<li class='i'>
	<div class="form_ul_div">
	<span class="title">栏目名称：</span><input type="text" name="name" value="{$formdata['name']}" /><font class="important">默认为频道名称</font>
	</div>
	</li>
	<li class='i'>
	<div class="form_ul_div">
	<span class="title">栏目描述：</span><textarea name="brief" cols="60" rows="5">{$formdata['brief']}</textarea>
	</div>
	</li>
	{code}
	$hg_attr['multiple'] = 0;
	$hg_attr['_callcounter'] = 3;
	{/code}
	<li class='i'>
	<div class="form_ul_div clear">
	<span class="title" style="line-height: 32px;">发布设置：</span>
	<div class="form_fb">{template:unit/column_node,pubfcol,$pub_col}</div>
	</div>
	</li>
	
</ul>
<input type="hidden" name="a" value="create" />
<input type="hidden" name="channelid" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" /><br>
<input type="submit" name="sub" value="确定发布" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2>返回前一页</h2></div>
{template:foot}