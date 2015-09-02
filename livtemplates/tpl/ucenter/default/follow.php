<?php
/* $Id: follow.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
{template:head}
{template:unit/user_info}
<div class="u-bg"></div>

<div class="garea">
	<div class="g_larea">
		{code} echo hg_advert('ucenter_top');{/code}
    <div class="g_are2">
        	<ul>
        	<li class="bt"><a href="<?php echo hg_build_link(SNS_UCENTER . 'follow.php' , $user_param);?>">关注</a></li>
        	<li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'fans.php' , $user_param);?>">粉丝</a></li>
	        <li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'group.php' , $user_param);?>">地盘</a></li>
	        {if $is_my_page}			
        		<li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'blacklist.php' , $user_param);?>">黑名单</a></li>
        	{/if}
          </ul>
    </div>
    <div class="g_are4 clear" id="content">

		<div class="content-left">
		        
	       
	          
			{if $is_my_page}
			<div class="my-business">
				<div class="left">我关注了<span id="liv_title_followers_count" >{$user_info['attention_count']}</span>个人</div>						
			</div>		
			{else}
			<div class="my-business"><div class="left">{$user_info['username']}关注了<span>{$user_info['attention_count']}</span>个人</div></div>	
			{/if}
			
			
			{if $is_my_page}
			<div style="float:right;margin-right:20px;">
				<form action="follow.php" method="post">
				<input type="hidden" name="search" value="search">
				<input style="font-size:12px;color:gray;border:1px solid #CCCCCC;" class="search" id="search_content" onblur="showText(this);" onclick="clearText(this);" type="text" name="screen_name" value="{code} echo $_input['screen_name'] ? $_input['screen_name'] : $_lang['input_screen_name'];{/code}" />
				<input type="submit" name="search_follow" value="搜 索" style="padding:0px 10px;" />
				</form>		
			</div>
			{/if}
	        
			<div class="followers_list">
			
			
			<input id="showId" type="hidden" name="showId" value="0" />
	
			
			{if $friends}
			
			<ul class="status-item">		
				
				{foreach $friends as $k => $v}
				
					<li class="clear" id="delete_{$v['id']}">
						<div class="blog-content">
						
							<div class="attention clear">
								<p class="name"><a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id']));?>" >{$v['username']}</a>：<span><a>{$v['followers_count']}</a>粉丝</span></p>
								<span style="color:gray;font-size:11px;"><?php echo hg_get_date($v['follow_time']); ?></span>
							</div>
							
							<div class="close-concern">
								<span style="float:left;width:18px;text-align:left;margin-right:5px;padding-right: 5px;display:block;"><a class="chat" href="javascript:void(0);" onclick="showMsgBox('<?php echo  $v['username'];?>','<?php echo md5($v['id'] . $v['salt'] . $user_info['id'] . $user_info['salt']);?>')">&nbsp;&nbsp;</a></span>
							
							{if $is_my_page}
							
								{if $v['is_mutual'] == 0}
															
								{else}
							<a class="relation"></a>
								{/if}
							<a class="close-follow-a"  href="javascript:void(0);" onclick="moveFollow({$v['id']})"></a>
							{else}	
												
								{if $_user['id'] == $v['id']} 	 

								{else}
									{if $v['is_mutual'] == 0} 		
									
											
							<p id="add_{$v['id']}"><a class="follow-gz" href="javascript:void(0);" onclick="addFriends({$v['id']} , 4)"></a></p>
									{else}
							<a class="been-concern"></a>
								
									{/if}
								{/if}
							{/if}
						
							
							
							</div>	
							<div id="deleteFollow_{$v['id']}" class="followers-box4"></div>
						</div>		
							<a href="<?php echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id']));?>"><img src="{$v['middle_avatar']}" title="{$v['username']}" /></a>				
					</li>		
				{/foreach}
								
			</ul>
			
			
			{$showpages}
			
			{else if $no_result}
			
			<p class="no-result">
			{code}
				$search_content = $screen_name;
			{/code}
			{template:unit/null_search}
			{else}
			<p class="no-result">
			{code}
				$null_title = "真不给力，SORRY!";
				$null_text = "该用户还没有关注任何人！";
				$null_type = 1;
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}
			{/if}
			</div>
			
		</div>
		
	</div>
	
	</div>
	
	
	
	<div class="g_rarea">
	{code} echo hg_advert('ucenter_right');{/code}
	{if $_user['id'] > 0}
	
      <div class="g_bre1">
      <a style="font-size:12px;color:#00A0EA;float:right;margin-right:10px;font-weight: normal;" href="follow.php?user_id={$user_info['id']}">更多>></a>

      {if $_user['id'] > 0}
      		{if $is_my_page}
				我关注的人
		    {else}
				TA关注的人
			{/if} 
	   {else}
      		TA关注的人
       {/if} 

    	</div>
      <div class="g4_bre2">
            {if !is_array($user_friends)||!$user_friends}
			{code}
				$null_title = "真不给力，SORRY!";
				$null_text = "暂未关注任何人";
				$null_type = 1;
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}   		        		
            {else}
        	<ul>
	        	{code}$i = 1;{/code}
	        	{foreach $user_friends as $key=>$value}
					{code}
						$style = '';
						switch($i)
						{
							case 1:
								break;
							case 4:
								break;
							case 7:
								break;
							default:
								$style = ' class="cus_pad"';
								break;
						}
	        		{/code}
	        			<li{$style}><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><img title="{$value['username']}" src="{$value['middle_avatar']}" width="50" height="50" /></a><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a>
						</li>
	        		{code}$i++;{/code}	
	        	{/foreach}
        	 </ul>
	        {/if}
        	<div class="clear"></div>
      </div>
      <div class="g_bre3" ></div>
     {/if}
	
	
	
    	 <div class="g_bre1">
    	 <a style="font-size:12px;float:right;margin-right:10px;font-weight: normal;" href="fans.php?user_id={$user_info['id']}">更多>></a>

        {if $is_my_page}
			我的粉丝
		{else}
			TA的粉丝
		{/if}
 
    	</div>
         <div class="g4_bre2">
        	
        	{if !is_array($user_fans)}
        	{code}
				$null_title = "真不给力，SORRY!";
				$null_text = "暂无粉丝";
				$null_type = 1;
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}
        	{else}
        	<ul>
	            {code}
	        	  $i = 1;
	        	{/code}
	        	{foreach $user_fans as $key=>$value}
	        	{code}
						$style = '';
						switch($i)
						{
							case 1:
								break;
							case 4:
								break;
							case 7:
								break;
							default:
								$style = ' class="cus_pad"';
								break;
						}
	        		{/code}
	        			<li{$style}><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><img src="{$value['middle_avatar']}" width="50" height="50" /></a><a title="{$value['username']}" href="<?php echo hg_build_link("user.php", array('user_id'=>$value['id']));?>"><?php echo hg_cutchars($value['username'],4," ");?></a></li>
	        		{code}
	        		  $i++;
	        		{/code}	
	        	{/foreach}
        	 </ul>
        	{/if}
        </div>
        <div class="g_bre3"></div>
       <div style="text-align:center;">
    {code}
		echo hg_advert('google_2');
		echo hg_advert('baidu_1');
	{/code}
       </div>
	</div>
</div>

{template:foot}