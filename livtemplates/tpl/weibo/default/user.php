<?php 
/* $Id: user.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
{template:head}
<input type="hidden" value="点滴" name="source" id="source"/>

    <div class="content clear people" id="equalize">
    <div class="content-left">
		<div class="rounded-top"></div>
    	<div class="expression_user">
	    	<img src="{$user_info['larger_avatar']}" class="pic"/>
        	<div class="people_user">
                <h2 id="username">{$user_info['username']}</h2>
                <p>{$user_info['location']}</p>
					{if !$is_my_page && $_user['id']}
						{if $relation == 0}	<!-- /*该用户已在黑名单中*/ -->	
						<div class="blacklist">
						<span class="follw" id="add_{$id}">已加入黑名单</span>
						<span class="close-follw" id="deleteFriend"><a href="javascript:void(0);" onclick="deleteBlock({$id});">解除</a></span>
						</div>
							
						{/if}
						{if $relation == 1}    <!-- /*源用户和目标用户互相关注*/ -->
						<div class="follow-all">
						<span class="follw" id="add_{$id}"><a class="mul-concern"></a></span>
						<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend({$id});"></a></span>
						</div>		
						{/if}	
						{if $relation == 2}    <!-- /*源用户关注了目标用户*/ -->
						<div class="follow-all">
						<span class="follw" id="add_{$id}"><a class="been-concern"></a></span>
						<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend({$id});"></a></span>
						</div>			
						{/if}	
						{if $relation == 3 || $relation == 4}	 <!--  /*目标用户关注了源用户或源用户和目标用户没有关系 */ -->
						<div class="follow-all">
						<span class="follw" id="add_{$id}"><a class="concern" href="javascript:void(0);" onclick="addFriends({$id} , {$relation});"></a></span>
						<span class="close-follw" id="deleteFriend"></span>
						</div>		
						{/if}						
					{/if}    
                {if $user_info['id'] == $_user['id']}
					<a class="set" href="javascript:void(0);" onclick="OpenReleased('')">{$_lang['status_mine']}</a>
				{else}
					<div style="position: relative;right: -390px;top: -40px;width: 25px;">
						<a class="set" href="javascript:void(0);" onclick="OpenReleased('{$user_info['username']}')">{$_lang['chat']}</a> 
						<a class="chat" title="和他聊天" style="position:absolute;right:30px;top:2px;" href="javascript:void(0);" onclick="showMsgBox('{$user_info['username']}','{code} echo md5($user_info['id'] . $user_info['salt'] . $_user['id'] . $_user['salt']);{/code}')">&nbsp;&nbsp;</a>
					</div>
				 	
				{/if}
            </div>
        </div>
        <div class="menu">
        {foreach $_settings['list'] as $k => $v}
		
			{if $k == $gScriptName}
        		<a href="{code} echo hg_build_link($v['filename'] , $user_param);{/code}" class="{$v['class']}">{$v['name']}</a>	
			{else} 
				<a href="{code} echo hg_build_link($v['filename'] , $user_param);{/code}" class="{$v['class']}-b">{$v['name']}</a>
			{/if}
		{/foreach}
  
  		<!-- 自己的页面中添加黑名单  -->
		{if $is_my_page}
  		<a class="text-hm-b" href="{code} echo hg_build_link('blacklist.php');{/code}" >黑名单</a>
  		{/if}
        </div>

{if !empty($statusline)&&is_array($statusline)}
<div class="pop" id="pop">
<span style="font-size:12px;color:#0082CB;width:auto;" onclick="closevideo()">关闭</span>
<div id="pop_s"></div>
</div>	
<ul>

{foreach $statusline as $key => $value}
	{code}
		$user_url = hg_build_link('user.php' , array('user_id' => $value['member_id']));
		$text = hg_verify($value['text']);
		$text_show = $value['text']?$value['text']:$_lang['forward_null'];
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
		$text_show = hg_verify($text_show);
		$transmit_info=$value['retweeted_status'];
	{/code}

	<li class="clear" id="mid_{$value['id']}"  onmouseover="report_show({$value['id']},{$value['user']['id']});" onmouseout="report_hide({$value['id']},{$value['user']['id']});">
		<div style="display:none;" id="cons_{$value['id']}_{$value['user']['id']}">{code} echo $text_show;{/code}</div>
		<div style="display:none;" id="ava_{$value['id']}_{$value['user']['id']}">{$value['user']['small_avatar']}</div>
		<div style="display:none;" id="user_{$value['id']}_{$value['user']['id']}">{$value['user']['username']}</div>
		<div style="display:none;" id="url_{$value['id']}_{$value['user']['id']}">{code} echo SNS_MBLOG.'show.php?id='.$value['id'];{/code}</div>
		<div style="display:none;" id="type_{$value['id']}_{$value['user']['id']}">3</div>
		
		<div class="blog-content">
			<p class="subject">
			{code} echo $text_show."<br/>";{/code}		
			</p>
{template:unit/statusline_content}	
			<div class="speak">
				<div class="hidden" id="t_{$value['id']}">{code} echo hg_verify($title);{/code}</div>
				<div class="hidden" id="f_{$value['id']}">{code} echo $forward_show;{/code}</div>
				<span id = "fa{$value['id']}" style="position:relative;">
					{if $is_my_page}
						<a href="javascript:void(0);" onclick="unfshowd({$value['id']})">{$_lang['delete']}</a>	
					{/if}
					<a href="javascript:void(0);" onclick="OpenForward('{$value['id']}','{code} echo $status_id;{/code}')">{$_lang['forward']}{code} echo '('.($value['transmit_count']+$value['reply_count']).')';{/code}</a>|
					<a  id="fal{$value['id']}" href="javascript:void(0);" onclick="favorites('{$value['id']}','{$_user['id']}')">{$_lang['collect']}</a>|
					<a href="javascript:void(0);" onclick="getCommentList({$value['id']},{$_user['id']})">{$_lang['comment']}(<span id="comm_{$value['id']}">{$value['comment_count']}</span>)</a>
				</span>
				<strong>{code} echo hg_get_date($value['create_at']);{/code}</strong>
				<strong class="overflow" style="max-width:230px">{$_lang['source']}{$value['source']}</strong> 
					{if $this->user['id']}
				<a onclick="report_play({$value['id']},{$value['user']['id']});" href="javascript:void(0);" style="display:none;" id="re_{$value['id']}_{$value['user']['id']}">
			{$_lang['report']}</a>	 	
					{/if}
			</div> 
			<input type="hidden" name="count_comm" id="cnt_comm_{$value['id']}" value="{$value['comment_count']}"/>
			<div id="comment_list_{$value['id']}"></div>
		</div> 
	</li>
{/foreach}
 <li class="more">{code} echo $showpages;{/code}</li>
 </ul>
{else}
	{code}
		$null_title = "真不给力，SORRY!";
		$null_text = '暂无没发表点滴或您的权限不够！';
	{/code}
	{template:unit/null}
{/if}
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
		       	<span class="u-show1">
					<a href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , $user_param);{/code}">{$user_info['status_count']}</a>
	    		</span>
    			<span class="u-show2">
    				<a href="{code} echo hg_build_link('follow.php' , $user_param);{/code}">{$user_info['attention_count']}</a>
	    		</span>
    			<span class="u-show3">
	    			<a href="{code} echo hg_build_link('fans.php' , $user_param);{/code}">{$user_info['followers_count']}</a>
	    		</span>
    			<span class="u-show4">
	    			<a href="{code} echo hg_build_link(SNS_VIDEO.'user.php', array('user_id'=>$user_info['id']));{/code}">{$user_info['video_count']}</a>
	    		</span>
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
				<a title="{$value['title']}" href="{code} echo hg_build_link('k.php' , array('q' => $value['title']));{/code}">
				{code} echo hg_cutchars($value['title'],10," ");{/code}</a><span>({$value['relate_count']})</span>
			</li>
			{/foreach}
		</ul>
		</div>
{/if}
{if $is_my_page}

            
   		<div class="clear2"></div>
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
		
			$title = '<a title="'.$value['title'].'" href="' . hg_build_link('k.php' , array('q' => $value['title'])) . '">'.hg_cutchars($value['title'],10," ") . '</a>';
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
{template:unit/forward}	
{template:unit/status_pub}	
{template:foot}	