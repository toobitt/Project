<?php 
/* $Id: updown_setting_form.php 17834 2013-03-22 03:25:33Z jeffrey $ */
?>
{template:head}
{css:ad_style}

{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;
		{/code}
	{/foreach}
{/if}
<div class="ad_middle">
	<form name="editform" onsubmit="return checknum()" id="editform"  action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>{$optext}帖子信息</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">帖子名称：</span>
					<input type="text" id="webname" name="webname"  value="{$title}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">分类名称：</span>
					<input type="text" id="webmark" name="webmark"  value="{$category_name}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">作者：</span>
					<input type="text" id="webappname" name="webappname"  value="{$user_name}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">群组名称：</span>
					<input type="text" id="webappmark" name="webappmark"  value="{$group_name}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">创建时间：</span>
					<input type="text" name="create_time" value="{$pub_time}" readonly style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
		</ul>
		</br>
		<!--<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>-->
		<input type="hidden" name="id" value="{$id}" />
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}