<?php 
/* $Id: message_received_form.php 17834 2013-03-22 03:25:33Z jeffrey $ */
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
{code}
$send_time = date("Y-m-d H:i:s",($send_time/1000));
{/code}
<script type="text/javascript">
var i = 2;
function pic_more()
{
	var pic_html;
	pic_html = '<li id="pic_li_'+i+'" class="i">';
	pic_html += '<div class="form_ul_div clear">';
	pic_html += '<span class="title"></span>';
	pic_html += '<span class="file_input s" style="float:left;">第'+i+'图片</span>';
	pic_html += '<span style="float:right;"></span>';
	pic_html += '<input name="picture[]" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />';
	pic_html += '</div></li>';
	i = i + 1;
	$("#pic_div").append(pic_html);
	hg_resize_nodeFrame();
}

function pic_lost()
{
	if(i>1)
	{
		$("#pic_li_"+i).remove();
		if(i>2)
		{
			i = i - 1;
		}
	}
	
}

var n = 2;
function video_more()
{
	var video_html;
	video_html = '<li id="video_li_'+n+'" class="i">';
	video_html += '<div class="form_ul_div clear">';
	video_html += '<span class="title"></span>';
	video_html += '<span class="file_input s" style="float:left;">第'+n+'视频</span>';
	video_html += '<span style="float:right;"></span>';
	video_html += '<input name="video[]" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />';
	video_html += '</div></li>';
	n = n + 1;
	$("#video_div").append(video_html);
	hg_resize_nodeFrame();
}

function video_lost()
{
	if(n>1)
	{
		$("#video_li_"+n).remove();
		if(n>2)
		{
			n = n - 1;
		}
	}
	
}

var m = 2;
function annex_more()
{
	var annex_html;
	annex_html = '<li id="annex_li_'+m+'" class="i">';
	annex_html += '<div class="form_ul_div clear">';
	annex_html += '<span class="title"></span>';
	annex_html += '<span class="file_input s" style="float:left;">第'+m+'附件</span>';
	annex_html += '<span style="float:right;"></span>';
	annex_html += '<input name="annex[]" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />';
	annex_html += '</div></li>';
	m = m + 1;
	$("#annex_div").append(annex_html);
	hg_resize_nodeFrame();
}

function annex_lost()
{
	if(m>1)
	{
		$("#annex_li_"+m).remove();
		if(m>2)
		{
			m = m - 1;
		}
	}
	
}

function show_video(a,b)
{
	var video_p_html;
	video_p_html = '<span style="float:left;background: none repeat scroll 0% 0% rgb(0, 0, 0); border-radius: 3px 3px 3px 3px; box-shadow: 0px 0px 10px black;">';
	video_p_html += '<object width="250" height="150" data="./../livtemplates/tpl/lib/images/swf/vodPlayer.swf?'+a+'" type="application/x-shockwave-flash" id="vodPlayer">';
	video_p_html += '<param value="./../livtemplates/tpl/lib/images/swf/vodPlayer.swf?'+a+'" name="movie">';
	video_p_html += '<param value="always" name="allowscriptaccess">';
	video_p_html += '<param value="true" name="allowFullScreen">';
	video_p_html += '<param value="transparent" name="wmode">';
	video_p_html += '<param value="videoUrl='+b+'manifest.f4m&amp;autoPlay=true" name="flashvars">';
	video_p_html += '</object>';
	video_p_html += '</span>';
	
	$("#video_play").html(video_p_html);
	hg_resize_nodeFrame();
}
</script>
<div class="ad_middle">
	<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data"  class="ad_form h_l">
		<h2>查看信息详情</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">信息标题：</span>
					<input type="text" name="title" value="{$title}" style="width:192px"/>
					<font class="important">必填</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">图片：</span>
					<span class="file_input s" style="float:left;">第1图片</span>
					<span style="float:right;">
						<span onclick="pic_more();">++</span>&nbsp;&nbsp;<span onclick="pic_lost();">--</span>
						{if $picture}<input name="is_picture" type="hidden" value=1 />{else}<input name="is_picture" type="hidden" value=0 />{/if}
					</span>
					<input name="picture[]" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
				</div>
			</li>
			<span id="pic_div"></span>
			{if is_array($picture) &&!empty($picture) && count($picture)>0}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">预览：</span>
					<span style="float:left;">
					{foreach $picture AS  $value}
					<img width="80" height="60" src="{$value}" style="float:left; margin-right:10px;" />
					{/foreach}
					</span>
					<span style="float:right;"></span>
				</div>
			</li>
			{/if}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">视频：</span>
					<span class="file_input s" style="float:left;">第1视频</span>
					<span style="float:right;">
						<span onclick="video_more();">++</span>&nbsp;&nbsp;<span onclick="video_lost();">--</span>
						{if $video}<input name="is_video" type="hidden" value=1 />{else}<input name="is_video" type="hidden" value=0 />{/if}
					</span>
					<input name="video[]" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
				</div>
			</li>
			<span id="video_div"></span>
			{if is_array($video) &&!empty($video) && count($video)>0}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">播放：</span>
					{foreach $video AS $value}
					<img width="80" onclick="show_video({$value['vid']},'{$value['url']}');" height="60" src="{$value['pic']}" style="float:left; margin-right:10px;">
					{/foreach}
					<span id="video_play" style="float:right;">
						
					</span>
				</div>
			</li>
			{/if}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">附件：</span>
					<span class="file_input s" style="float:left;">第1附件</span>
					<span style="float:right;">
						<span onclick="annex_more();">++</span>&nbsp;&nbsp;<span onclick="annex_lost();">--</span>
						{if $annex}<input name="is_annex" type="hidden" value=1 />{else}<input name="is_annex" type="hidden" value=0 />{/if}
					</span>
					<input name="annex[]" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
				</div>
			</li>
			<span id="annex_div"></span>
			{if is_array($annex) &&!empty($annex) && count($annex)>0}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">预览：</span>
					<span style="float:left;">
					{foreach $annex AS $value}
					<a href="{$value['host'].$value['dir'].$value['filepath'].$value['filename']}" target="_blank"><span style="color:#F00;">{$value['filename']}</span></a> || 
					{/foreach}
					</span>
					<span style="float:right;"></span>
				</div>
			</li>
			{/if}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">发送人：</span>
					<input type="text" name="send_name" value="{$send_name}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">发送号码：</span>
					<input type="text" name="send_phone" value="{$send_phone}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">收信号码：</span>
					<input type="text" name="receive_phone" value="{$receive_phone}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">发送时间：</span>
					<input type="text" name="send_time" readonly value="{$send_time}" style="width:192px"/>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">信息状态：</span>
					<label>
						<input name="status" type="radio" value=0 class="n-h"
							{if $status==0 && $id}
								checked="checked"
							{/if}
						/>待审核&nbsp;
					</label>
					<label>
						<input name="status" type="radio" value=1 class="n-h"
							{if $status==1 && $id}
								checked="checked"
							{/if}
						/>已审核&nbsp;
					</label>
					<label>
						<input name="status" type="radio" value=2 class="n-h"
							{if $status==2 && $id}
								checked="checked"
							{/if}
						/>已打回&nbsp;
					</label>
					<font class="important"></font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">排序：</span>
					<input type="text" name="sort" value="{$sort}" style="width:192px"/>
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
					<span class="title">信息内容：</span>
					<textarea name="content">{$content}</textarea>
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
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}