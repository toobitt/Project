<?php 
/* $Id: group.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
{template:head}
{template:unit/user_info}
<div class="u-bg"></div>
 
<div class="garea">
	<div class="g_larea">
		{code} echo hg_advert('ucenter_top');{/code}
	<div class="g_are2">
        	<ul>
        	<li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'follow.php' , $user_param);?>">关注</a></li>
        	<li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'fans.php' , $user_param);?>">粉丝</a></li>
        	<li class="bt"><a href="<?php echo hg_build_link(SNS_UCENTER . 'group.php' , $user_param);?>">地盘</a></li>
        	  {if $is_my_page}			
        		<li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'blacklist.php' , $user_param);?>">黑名单</a></li>
        	  {/if}
          </ul>
    </div>
    <div class="g_are4 clear" id="content">

		<div class="content-left">
								
			<div class="followers_list">
			
			<!-- 记录当前弹出框的ID -->
			<input id="showId" type="hidden" name="showId" value="0" />
			
		
			{if $have_group == true}
			
			<ul class="status-item">		
				
				{foreach $group as $k => $v}
				
					<li class="clear">
						<div class="blog-content">
							
							<div class="attention clear">
								<p class="name"><a href="{$v['href']}" >{$v['name']}</a><span style="margin-left:100px;"><a>{$v['thread_count']}</a>帖子</span><span style="margin-left:50px;"><a>{$v['group_member_count']}</a>成员</span><span style="margin-left:50px;"><a>{$v['total_visit']}</a>访问</span></p>
								<span style="display:inline-block;margin-top:10px;">地址：{$v['group_addr']}</span>
							</div>
						
						</div>						
						<a href="{$v['href']}"><img src="{$v['logo']}" title="{$v['name']}" /></a>											
					</li>		
				{/foreach}
									
			</ul>
			
		
			{$showpages}
			{else}
			<p class="no-result">
			{code}
				$null_title = "真不给力，SORRY!";
				$null_text = "暂未加入任何地盘！";
				$null_type = 1;
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
			{template:unit/null}</p>
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
    	 <a style="font-size:12px; float:right;margin-right:10px;font-weight: normal;" href="fans.php?user_id={$user_info['id']}">更多>></a>

        {if $is_my_page}
			我的粉丝
		{else}
			TA的粉丝
		{/if}
    	</div>
         <div class="g4_bre2">
        	
        	{if !is_array($user_fans)||!$user_fans}
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