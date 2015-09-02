<?php
/* $Id: k.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
{template:head}
{template:unit/status_pub}
<input type="hidden" value="点滴" name="source" id="source"/>

<div class="theme clear " id="equalize">
	<div class="content-left">

	<form action="k.php" method="post">
		<div class="search">

	{if $info['member_id']}
		{code}
			$style1 ='style="display:none;"';
			$style2 ='style="display:block;"';
		{/code}
	{else} 
		{code}
			$style1 ='style="display:block;"';
			$style2 ='style="display:none;"';
		{/code}
	{/if}

{if $_input['q']}
	
<a id="addTopics" {$style1} href="javascript:void(0);" onclick="addTopicFollow()"><span class="theme-cy"></span>关注该话题</a>
<a {$style2} id="delTopic" href="javascript:void(0);" onclick="delTopicFollow()"><span class="theme-close"></span>取消关注</a>
<input type="hidden" id="liv_topic_id" value="{$info['topic_id']}"/>
{/if}
			
			<a style="" href="javascript:void(0);" onclick="OpenReleaseds()"><span class="theme-gz"></span>参与该话题</a>
			<input class="text" type="text" name="q" id="q" value="{code} echo stripcslashes($keywords);{/code}"/>
			<input class="search-a" type="submit" id="search" value=" " />
		</div>
	</form>

{if !empty($statusline)&&is_array($statusline)}

	 <div class="my-business">{$_lang['have']}{$data['totalpages']}{$_lang['record']}</div>

{/if}
<!--<div class="update">
        <p><a>有<span>12</span>条点滴更新，点击查看</a></p>
        </div>
-->
<div id="show"></div>
{template:unit/forward}

{if !empty($statusline)&&is_array($statusline)}

<div class="pop" id="pop">
<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
<div id="pop_s"></div>
</div>	
<ul>

{foreach $statusline as $key => $value}
	{code}
		$user_url = hg_build_link(SNS_UCENTER.'user.php' , array('user_id' => $value['member_id']));
		$text = hg_verify($value['text']);
		$text_show = '：'.($value['text']?$value['text']:$_lang['forward_null']);
	{/code}
	{if $value['reply_status_id']}
	
		{code}
			$forward_show = '//@'.$value['user']['username'].' '.$text_show;
			$title = $_lang['forward_one'].$value['retweeted_status']['text'];
			$status_id = $value['reply_user_id'];
		{/code}
	{else}

		{code}
			$forward_show = '';
			$title = $_lang['forward_one'].$value['text'];
			$status_id = $value['member_id'];
		{/code}
	{/if}
	{code}
		$text_show =hg_match_red(hg_verify($text_show),$keywords);
		$transmit_info=$value['retweeted_status'];
	{/code}

	<li class="clear" id="mid_{$value['id']}"  onmouseover="report_show({$value['id']},{$value['user']['id']});" onmouseout="report_hide({$value['id']},{$value['user']['id']});">
		<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}">{$text_show}</div>
		<div style="display:none;" id="ava_{$value['id']}_{$value['user']['id']}">{$value['user']['small_avatar']}</div>
		<div style="display:none;" id="user_{$value['id']}_{$value['user']['id']}">{$value['user']['username']}</div>
		<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}">{code} echo SNS_MBLOG.'show.php?id='.$value['id'];{/code}</div>
		<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">3</div>
		
		<div class="blog-content">
			<p class="subject"><a href="{$user_url}">{$value['user']['username']}</a>
		{$text_show}<br/>	
			</p>
		{template:unit/statusline_content}
			<div class="speak clear">
			<div class="hidden" id="t_{$value['id']}">{code} echo hg_verify($title);{/code}</div>
			<div class="hidden" id="f_{$value['id']}">{$forward_show}</div>
				<span style="position:relative;">
					<a id = "fa{$value['id']}" href="javascript:void(0);" onclick="OpenForward('{$value['id']}','{$status_id}')">{$_lang['forward']}{code} echo '('. ($value['transmit_count']+ $value['reply_count']).')';{/code}</a>|
					<a  id="fal{$value['id']}" href="javascript:void(0);" onclick="favorites('{$value['id']}','{$_user['id']}')">{$_lang['collect']}</a>|
					<a href="javascript:void(0);" onclick="getCommentList({$value['id']},{$_user['id']})">{$_lang['comment']}(<span id="comm_{$value['id']}">{$value['comment_count']}</span>)</a>
				</span>
				<strong>{code} echo hg_get_date($value['create_at']);{/code}</strong>
				<strong class="overflow" style="max-width:230px">{$_lang['source']}{$value['source']}</strong>
				 
					{if $_user['id']}
					
				<a onclick="report_play({$value['id']},{$value['user']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">
					{$_lang['report']}
				</a>	
					{/if}
			</div> 
			<input type="hidden" name="count_comm" id="cnt_comm_{$value['id']}" value="{$value['comment_count']}"/>
			<div id="comment_list_{$value['id']}"></div>
		</div> 
		<a href="{$user_url}">
		<img src="{$value['user']['middle_avatar']}"/>
		</a>
	</li>
{/foreach}
 <li class="more"></li>
 </ul>

{$showpages}
{else}
	{code}
		$search_content = $keywords;
	{/code}
	{template:unit/null_search}
{/if}

</div>

<div class="content-right">

<div class="pad-all">
	<div class="bk-top1">热门话题</div>
	<div class="wb-block1">
		{if $topic}
		<ul class="topic clear">
			{foreach $topic as $value}
			<li>
				<a href="{code} echo hg_build_link('k.php' , array('q' => $value['title']));{/code}">
				{$value['title']}</a><span>({$value['relate_count']})</span>
			</li>
			{/foreach}
		</ul>
		{/if}
	</div>

{if $_user['id']>0}
	
<!-- follow topic  -->	
	<div class="bk-top1">
	{$_lang['topic_follow']}<strong>(<span id="liv_topic_follow_num">{code} echo count($topic_follow);{/code}</span>)</strong></div>
		<div class="wb-block1">
		<ul id="addtopicfollows" class="topic clear">
		
		{if $topic_follow}
		
		{foreach $topic_follow as $key=>$value}
		
		<li class="topic_li" id="liv_topic_{$value['topic_id']}" onmouseover="this.className='topic_li_hover'" onmouseout="this.className='topic_li'">
		{code}
			$title = '<a title="' . $value['title'] . '" href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.hg_cutchars($value['title'] , 8 , ''). '</a>';
		echo $title;
		{/code}
		<a class="close" href="javascript:void(0);" onclick="delTopicFollow()"></a>
		</li>
			{/foreach}
		{/if}
		</ul>	
		</div>
<!-- end follow topic  -->
{/if}
		
		</div>

</div>
</div>


{template:foot}
