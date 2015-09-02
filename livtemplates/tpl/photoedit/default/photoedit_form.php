<?php 
/* $Id: message_send_form.php 17834 2013-03-22 03:25:33Z jeffrey $ */
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
	$imgurl = $host.$dir.$filepath.$filename;
	{/code}
{/if}

<div class="ad_middle">
	{if !empty($formdata['id'])}
	<form name="editform" id="editform" enctype="multipart/form-data"  action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>{$optext}图片信息</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">图片：</span>
					<span class="file_input s" style="float:left;">上传图片</span>
					<span style="float:right;">
					</span>
					<input name="picture" id="picture" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">预览：</span>
					<span style="float:left;">
					<img width="80" height="60" src="{$imgurl}" />
					<input type="hidden" name="imgurl" value="{$imgurl}" />
					</span>
					<span style="float:right;"></span>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">最新操作：</span>
					<input type="text" name="send_time" readonly value="{$update_time}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">IP：</span>
					<input type="text" name="ip" value="{$ip}" readonly style="width:192px"/>
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
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="id" value="{$id}" />
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
	{else}
	<form name="editform" enctype="multipart/form-data"  id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>{$optext}图片信息</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">图片：</span>
					<span class="file_input s" style="float:left;">上传图片</span>
					<span style="float:right;">
					</span>
					<input name="picture" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
				</div>
			</li>
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="id" value="{$id}" />
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
	{/if}
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}