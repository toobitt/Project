<?php
/* $Id: blacklist.php 387 2011-07-26 05:31:22Z lijiaying $ */

?>
{template:head}
<div class="content clear people" id="equalize">
	<div class="content-left">
		
		<div class="news-latest">
			<div class="tp"></div>
			<div class="md"><h3 style="padding-left:20px;font-size:15px;">{$_lang['block']}</h3></div>
		</div>
				
		<div class="black_list">
		
		<!-- 记录当前弹出框的ID -->
		<input id="showId" type="hidden" name="showId" value="0" />
 
		{if $hava_blocks}
			<ul class="black_ul">
		
			{foreach $black_list as $k => $v}
				<li class="clear" id="deleteBlock_{$v['id']}" style="border-left:1px solid #CCCCCC;border-right:1px solid #CCCCCC;">
										
					<div class="attention clear">					
						<a href="{code} echo hg_build_link('user.php' , array('user_id' => $v['id']));{/code}"><img style="border:1px solid silver; padding:2px;" src="{$v['middle_avatar']}" title="{$v['screen_name']}" /></a>&nbsp;&nbsp;
						<a href="{code} echo hg_build_link('user.php' , array('user_id' => $v['id']));{/code}">{$v['screen_name']}</a>
						<span class="black-cr" style="margin-left:20px;font-size:12px;color:gray">
					{code}  echo date("m月  d日 H:i:s" , $v['join_time']); {/code}
					</span>
					
					</div>
					
					
					
					<span class="close-concern">
						<a href="javascript:void(0);"  onclick="moveBlocks({$v['id']})">{$_lang['destroy_block']}</a>
					</span>
										
					<span id="showMove_{$v['id']}" class="black-show">
					</span>
				</li>
				
			{/foreach}		
			</ul>
		
		{else}

		<p class="no-result">{$_lang['no_blocks']}</p>
		<p style="line-height:20px;font-size:15px;padding-left:20px;padding-bottom:10px;border-bottom:1px solid #CCC;">{$_lang['black_explain']}</p>	
		
		{/if}
		</div>
		
	</div>
	
	
	<div class="content-right">	

		<div class="pad-all">
		
		<div class="bk-top1">个人资料
				 
		{if $_user['id'] > 0}
            <a class="link-right" href="{code} echo hg_build_link(SNS_UCENTER.'userprofile.php' , $user_param);{/code}">设置</a>
		{else}
            <a  class="link-right" href="{code} echo hg_build_link('info.php' , $user_param);{/code}">查看</a>
		{/if}
        </div>
       
       <div class="wb-block1">
       
	       <div class="business">
				<dl class="border">
					<dt><a href="{code} echo hg_build_link('follow.php' , $user_param);{/code}" id="liv_info_attention_count">{$user_info['attention_count']}</a></dt>
					<dd><a href="{code} echo hg_build_link('follow.php' , $user_param);{/code}">{$_lang['friends']}</a></dd>
				</dl>
				<dl class="border">
					<dt><a href="{code} echo hg_build_link('fans.php' , $user_param);{/code}" id="liv_info_followers_count">{$user_info['followers_count']}</a></dt>
					<dd><a href="{code} echo hg_build_link('fans.php' , $user_param);{/code}">{$_lang['followers']}</a></dd>
				</dl>
				<dl class="border">
					<dt><a href="{code} echo hg_build_link('user.php' , $user_param);{/code}">{$user_info['status_count']}</a></dt>
					<dd><a href="{code} echo hg_build_link('user.php' , $user_param);{/code}">{$_lang['name']}</a></dd>
				</dl>
				<dl >
					<dt><a href="{code} echo hg_build_link(SNS_VIDEO.'my_video.php' , $user_param);{/code}" id="liv_info_attention_count">{$user_info['video_count']}</a></dt>
					<dd><a href="{code} echo hg_build_link(SNS_VIDEO.'my_video.php' , $user_param);{/code}">{$_lang['videos']}</a></dd>
				</dl>
			</div>
            
        <ul class="information">

				{code}
					$relation = array('truename'=>'真实姓名','birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
				{/code}
				{foreach $relation as $key =>$value}
					{code}
						$temp = $user_info[$key];
					{/code}
					{if $temp}

						{if strcmp($key,"birthday")==0 && is_numeric($temp)}
							{code}
								echo "<li>".$value."： ".$_lang['xingzuo'][$temp]."</li>";
							{/code}
						{else}
							{code}
								echo "<li>".$value."： ".$temp."</li>";
							{/code}
						{/if}
					{/if}
				{/foreach}
         </ul>
         </div>
		
		
		
		
		<div class="bk-top1">点滴导航</div>
		<div class="wb-block1">
		
		<div class="menu">

		{foreach $_settings['nav'] as $k => $v}
			{if $k == $gScriptName}
			<a class="{$v['class']}_click" href="{code} echo hg_build_link($v['filename']);{/code}"></a> 
			{else}
			<a class="{$v['class']}" href="{code} echo hg_build_link($v['filename']);{/code}"></a> 
			{/if}
		{/foreach}
		
		</div>
		</div>
		         
		{if $topic}
		<div class="bk-top1">热门话题</div>
		<div class="wb-block1">
		<ul class="topic clear">
			{foreach $topic as $value}
			<li>
				<a href="{code} echo hg_build_link('k.php' , array('q' => $value['title'])); {/code}">
				{$value['title']}</a><span>({$value['relate_count']})</span>
			</li>
			{/foreach}
		</ul>
		</div>
		{/if}
		
		{if $_user['id'] > 0}	
            
   		<div class="clear"></div>
		<!-- follow topic -->
		<div class="bk-top1">
		{$_lang['topic_follow']}<strong>(<span id="liv_topic_follow_num">{code} echo count($topic_follow);{/code}</span>)</strong>
		</div>
		<div class="wb-block1">
		
		<ul class="topic clear">
		{if $topic_follow}
			{foreach $topic_follow as $key=>$value}
		<li class="topic_li" onmouseover="this.className='topic_li_hover'" onmouseout="this.className='topic_li'">
			{code}
				$title = '<a href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.$value['title'] . '</a>';
				echo $title;
			{/code}
		<a class="close" href="javascript:void(0);" onclick="del_Topic_Follow('{$value['topic_id']}',this)"></a>
		<div class="hidden" id="topic_{$value['topic_id']}">{$value['title']}</div>
		</li>
			{/foreach}
		{/if}
		<!-- add follow topic -->
		<li id="addtopicfollow" class="topic-add"><a href="javascript:void(0);" onclick="add_Topic_Follow()">{$_lang['insert']}</a></li>
		<!-- end add follow topic -->
		</ul>
		</div>
		
		<dl id="topicbox" class="topicbox">
		<dt><a id="TopicBoxClose" href="javascript:void(0);" onclick="topicBoxClose()">x</a></dt>
		<dd class="topic_dd_title">
		<input type="text" name="topic" id="topic" style="font-size:12px;width:118px;height:20px;"/>
		<input type="button" style="font-size:12px;width:50px;height:25px;" value=" {$_lang['save']} " onclick="addTopic($('#topic').val())"/>
		</dd>
		<dd class="topic_dd_about" id="topic_dd_about">{$_lang['topic_about']}</dd>
		</dl>	
		{/if}
		</div>

	</div>
</div>

{template:foot}
