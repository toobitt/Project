<?php
/* $Id: follow.tpl.php 3254 2011-03-31 09:01:02Z repheal $ */
?>
{template:head}
<div class="content clear people" id="equalize">
	<div class="content-left">
		<!-- 个人信息   -->
	    <div class="rounded-top"></div>
		<div class="expression">
	    	<img src="{$user_info['larger_avatar']}" class="pic"/>
        	<div class="people_user">

                <h2 id="username">{$user_info['username']}</h2>
                <p>{$user_info['location']}</p>

				<div class="follow-all">
										
					{if $is_my_page}
											
					
					{else}
					
						{if $relation == 0}    <!-- /*该用户已在黑名单中*/ -->
						
					
					<span class="follw" id="add_{$id}">已加入黑名单</span>
					<span class="close-follw" id="deleteFriend"><a href="javascript:void(0);" onclick="deleteBlock({$id});">解除</a></span>
							
						{/if}
						{if $relation == 1}    <!-- /*源用户和目标用户互相关注*/ -->		
		
					<span class="follw" id="add_{$id}">相互关注</span>
					<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend({$id});"></a></span>
					 		
						{/if}	
						{if $relation == 2}    <!-- /*源用户关注了目标用户*/ -->
					<span class="follw" id="add_{$id}"><a class="been-concern"></a></span>
					<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend({$id});"></a></span>
						{/if}
							
							
						{if $relation == 3 || $relation == 4}	  <!-- //目标用户关注了源用户或源用户和目标用户没有关系  -->
						
							
					<span class="follw" id="add_{$id}"><a class="concern" href="javascript:void(0);" onclick="addFriends({$id} , {code} echo $relation;{/code});"></a></span>
					<span class="close-follw" id="deleteFriend"></span> 		
						{/if}
					{/if}
				</div>
            </div>
        </div>
	
		<!-- 导航  -->
		<div class="menu">
		{foreach $_settings['list'] as $k => $v}
			{if $k == $gScriptName}
			<a href="{code} echo hg_build_link($v['filename'] , $user_param);{/code}" class="{$v['class']}">{$v['name']}</a>
			{else}
			<a href="{code} echo hg_build_link($v['filename'] , $user_param);{/code}" class="{$v['class']}-b">{$v['name']}</a>
			{/if}
		{/foreach}
 
		{if $is_my_page}
		<a href="{code} echo hg_build_link('blacklist.php');{/code}" class="text-bl-b">黑名单</a>
		{/if}
			{if $is_my_page}
			<div style="float:right;margin-right:10px;">
				<form action="follow.php" method="post">
				<input type="hidden" name="search" value="search">
				<input style="font-size:12px;color:gray;border:1px solid #CCCCCC;" class="search" id="search_content" onblur="showText(this);" onclick="clearText(this);" type="text" name="screen_name" value="{code} echo $_input['screen_name'] ? $_input['screen_name'] : $_lang['input_screen_name']; {/code}" />
				<input type="submit" name="search_follow" value="搜 索" style="padding:0px 10px;" />
				</form>		
			</div>
			{/if}
        </div>
        
        <!-- 显示关注和搜索  -->
		{if $is_my_page}
		<div class="my-business">

			<div class="left">我关注了<span id="liv_title_followers_count" >{$user_info['attention_count']}</span>个人</div>	
			
		</div>	
		
		{else}

		<div class="my-business">{$user_info['username']}关注了<span>{$user_info['attention_count']}</span>个人</div>	
       
		{/if}

		<div class="followers_list">
		
		<!-- 记录当前弹出框的ID -->
		<input id="showId" type="hidden" name="showId" value="0" />

		{if $friends}
		
		<ul class="status-item">
			{foreach $friends as $k => $v}
			
				<li class="clear" id="delete_{$v['id']}">
					<div class="blog-content">
					
						<div class="attention clear">
							<p class="name"><a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id']));{/code}" >{$v['username']}</a>：<span><a>{$v['followers_count']}</a>粉丝</span></p>
							<span style="color:gray;font-size:11px;">{code} echo hg_get_date($v['follow_time']);{/code}</span>
						</div>
						
						<div class="close-concern">
							<span style="float:left;width:18px;text-align:left;margin-right:5px;padding-right: 5px;display:block;"><a class="chat" href="javascript:void(0);" onclick="showMsgBox('{$v['username']}','{code} echo md5($v['id'] . $v['salt'] . $user_info['id'] . $user_info['salt']);{/code}')">&nbsp;&nbsp;</a></span>
						
						{if $is_my_page}
						
							{if $v['is_mutual'] == 0}
							
							{else}
						
						<a class="relation"></a>
						 
							{/if}						
						
						<a class="close-follow-a"  href="javascript:void(0);" onclick="moveFollow({$v['id']})"></a>
				
						
						{else}
												
							{if $_user['id'] == $v['id']}			<!-- /*别人关注中含有自己*/ -->	 
								
								<!-- /*没有任何操作*/ -->
	
							{else}
							
								{if $v['is_mutual'] == 0}			<!-- /*没有关注别人*/ -->
								
											
						<p id="add_{$v['id']}"><a class="follow-gz" href="javascript:void(0);" onclick="addFriends({$v['id']} , 4)"></a></p>
					
								{else}							<!-- /*关注了别人*/ -->
								
						<a class="been-concern"></a>
						 			
								{/if} 
							{/if}
						{/if}
						
						
						
						</div>	
						<div id="deleteFollow_{$v['id']}" class="followers-box4"></div>
					</div>		
						<a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id']));{/code}"><img src="{$v['middle_avatar']}" title="{$v['username']}" /></a>				
				</li>		
			
			{/foreach}
		</ul>
		
		
			{code}
				echo $showpages;
			{/code}
		
		{else if $no_result}
		
		<p class="no-result">
			{code}
		$null_title = "真不给力，SORRY!";
		$null_text = '抱歉没有找到<span style="color:red;">'.$screen_name.'</span>相关的结果';
	{/code}
	{template:unit/null}</p>
		{else}		
		<p class="no-result">
			{code}
		$null_title = "真不给力，SORRY!";
		$null_text = '该用户还没有关注任何人！';
	{/code}
	{template:unit/null}</p>
		
		{/if}

		</div>
		
	</div>	
	<div class="content-right">	

		<div class="pad-all">
		<div class="bk-top1">个人资料
		{if $is_my_page}
            <a class="link-right" href="{code} echo hg_build_link(SNS_UCENTER.'userprofile.php' , $user_param);{/code}">设置</a>
		{else}
            <a  class="link-right" href="{code} echo hg_build_link('info.php' , $user_param);{/code}">查看</a>
		{/if}
        </div>
       
       <div class="wb-block1">
       
	       <div class="business">
				<dl class="border">
					<dt>{$user_info['attention_count']}</dt>
					<dd><a href="{code} echo hg_build_link('follow.php' , $user_param);{/code}">{$_lang['friends']}</a></dd>
				</dl>
				<dl class="border">
					<dt>{$user_info['followers_count']}</dt>
					<dd><a href="{code} echo hg_build_link('fans.php' , $user_param);{/code}">{$_lang['followers']}</a></dd>
				</dl>
				<dl class="border">
					<dt>{$user_info['status_count']}</dt>
					<dd><a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , $user_param);{/code}">{$_lang['name']}</a></dd>
				</dl>
				<dl >
					<dt>{$user_info['video_count']}</dt> 
					<dd><a href="{code} echo hg_build_link(SNS_VIDEO.'my_video.php' , $user_param);{/code}">{$_lang['videos']}</a></dd>
				</dl>
			</div>
           
        <ul class="information">
<!-- 1111 -->
			
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
				<a href="{code} echo hg_build_link('k.php' , array('q' => $value['title']));{/code}">
				{$value['title']}</a><span>({$value['relate_count']})</span>
			</li>
			
			{/foreach}
			
		</ul>
		</div>
		
		{/if}
		
		{if $is_my_page}
            
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