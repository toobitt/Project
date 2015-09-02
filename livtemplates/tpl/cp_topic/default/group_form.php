<?php 
/* $Id: score_setting_form.php 17834 2013-03-22 03:25:33Z jeffrey $ */
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
		<h2>{$optext}群组信息</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">群组名称：</span>
					<input type="text" id="webname" name="webname"  value="{$name}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">群组描述：</span>
					<input type="text" id="webmark" name="webmark"  value="{$description}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">创建者：</span>
					<input type="text" id="webappname" name="webappname"  value="{$user_name}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">成员数量：</span>
					<input type="text" id="webappname" name="webappname"  value="{$group_member_count}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">未验证成员数量：</span>
					<input type="text" id="webappname" name="webappname"  value="{$group_unconfirmed_member_count}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">话题数：</span>
					<input type="text" id="webappname" name="webappname"  value="{$thread_count}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">帖子数：</span>
					<input type="text" id="webappname" name="webappname"  value="{$post_count}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">图片数量：</span>
					<input type="text" id="webappname" name="webappname"  value="{$picture_count}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">留言数量：</span>
					<input type="text" id="webappname" name="webappname"  value="{$bulletin_count}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">总访问数：</span>
					<input type="text" id="webappname" name="webappname"  value="{$total_visit}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">创建时间：</span>
					<input type="text" id="webappmark" name="webappmark"  value="{$create_time}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">更新时间：</span>
					<input type="text" id="min_value" name="min_value"  value="{$update_time}" style="width:192px"/>
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