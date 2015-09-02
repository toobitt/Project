<?php 
/* $Id: score_form.php 17834 2013-03-22 03:25:33Z jeffrey $ */
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
	{code}
	$update_time = date("Y-m-d H:i:s",$update_time);
	{/code}
{/if}

<div class="ad_middle">
	<form name="editform" id="editform" enctype="multipart/form-data"  action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>{$optext}详细信息</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">内容标题：</span>
					<input type="text" name="title" value="{$title}" readonly style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">顶：</span>
					<input type="text" name="ding" value="{$updown['ding']}" readonly style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">踩：</span>
					<input type="text" name="cai" value="{$updown['cai']}" readonly style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">评分值：</span>
					<input type="text" name="score" value="{$score}" readonly style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">心情评分：</span>
					{foreach $mood AS $key=>$value}
					{$key}:{$value}
					{/foreach}
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">更新时间：</span>
					<input type="text" name="update_time" value="{$update_time}" readonly style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">排序：</span>
					<input type="text" id="order_id" name="order_id" value="{$order_id}" style="width:40px"/>
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