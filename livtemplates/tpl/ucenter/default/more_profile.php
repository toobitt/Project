<?php 
/* $Id: more_profile.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
{template:head}
{template:unit/user_info}
<div class="u-bg"></div>

<div class="garea">
	<div class="g_larea">
	{code} echo hg_advert('ucenter_top');{/code}
        <div class="g_are2">
        	<ul>
        		<li id="t_2"  style="width:100px;">个人详细资料</li>
        	</ul>
        </div>
        <div class="g_are4" id="content">
        <div class="content-left" id="status_list" style="height:609px;">
			<div class="more_profile">
			<ul>
			
	
				{code}
					$relation1 = array('truename'=>'真实姓名','location'=>'所在地','birthday'=>'生日','email'=>'邮箱','qq'=>'QQ','msn'=>'MSN','mobile'=>'手机');
				{/code}
				{foreach $relation1 as $key =>$value}
				
					{code}
					  $temp = $user_info[$key];
					{/code}
					{if $temp}
					
						{if strcmp($key,"birthday")==0 && is_numeric($temp)}
						
							<li>{$value}： <span class='txt'>{$_lang['xingzuo'][$temp]}</span></li>
						
						{else}
						
							<li>{$value}： <span class='txt'>{$temp}</span></li>
						{/if}
					{/if}
					
				{/foreach}
		
			</ul>
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
{template:unit/forward}
{template:unit/status_pub}
{template:foot}