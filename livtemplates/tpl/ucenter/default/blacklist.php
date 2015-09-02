<?php
/* $Id: blacklist.php 8320 2012-03-16 08:08:52Z repheal $ */

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
	            <li class="bt_d"><a href="<?php echo hg_build_link(SNS_UCENTER . 'group.php' , $user_param);?>">地盘</a></li>
	          
				{if $is_my_page}
	        	   <li class="bt"><a href="<?php echo hg_build_link(SNS_UCENTER . 'blacklist.php' , $user_param);?>">黑名单</a></li>
	        	{/if}
	          </ul>
	    </div>
		<div class="g_are4 clear" id="content">
			<div class="content-left">
				
				
						
				<div class="black_list">
				
				<!-- 记录当前弹出框的ID -->
				<input id="showId" type="hidden" name="showId" value="0" />
				
			
				{if $hava_blocks && is_array($black_list)}
					<ul class="black_ul">
					{foreach $black_list as $k => $v}
					
						<li class="clear" id="deleteBlock_{$v['id']}" >
												
							<div class="attention clear">					
								<a href="<?php echo hg_build_link('user.php' , array('user_id' => $v['id']));?>"><img src="{$v['middle_avatar']}" title="{$v['screen_name']}" /></a>&nbsp;&nbsp;
								<a href="<?php echo hg_build_link('user.php' , array('user_id' => $v['id'])); ?>">{$v['screen_name']}</a>
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
				$null_title = "sorry!!!";
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
    	 我的粉丝
    	 </div>
         <div class="g4_bre2">
        	
        	{if !is_array($user_fans)||!$user_fans}
			{code}
				$null_title = "sorry!!!";
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
	</div>
</div>
{template:foot}