<?php 
/* $Id: fans.php 396 2011-07-28 00:52:08Z zhoujiafei $ */
?>
<div class="out-bg">
<ul class="out-me">
	<li class="u-con"><a href="<?php echo MAIN_URL;?>">首页</a></li>
	<li class="u-interval"></li>
	<li class="u-con"><a href="<?php echo hg_build_link(SNS_UCENTER."user.php",array('user_id'=>$user_info['id']));?>">个人空间</a></li>
	<li class="u-interval"></li>

	{foreach $_settings['umenu'] as $key => $value}
		{if SCRIPTNAME == $key && $value}
			<li class="u-con"><a href="#">{$value}</a></li>
			<li class="u-interval"></li>
		{/if}
	{/foreach}
	<li class="u-other"></li>
</ul>
<div class="garea">
	<div class="photo">
        <div class="pdline"><img src="{$user_info['larger_avatar']}" width="123" height="122"/></div>
        <a class="u-enter" href="<?php echo hg_build_link(SNS_VIDEO."user.php", array('user_id'=>$user_info['id']));?>">点击进入频道 >></a>
    </div>
    <ul class="pho_cot">
    	<li><span class="namecs">
    			
    				{$user_info['username']}
    				{if $is_my_page || $relation == 1 || $relation == 3}
    				   {if $user_info['truename']}
    				     ({$user_info['truename']})
    				   {else}
    				   {/if}
    				{/if} 				
    			
    		</span></li>
    	<li class="u-per">
    	<div class="cus_btn">
      
               		 {if !$is_my_page}
	                	{if $relation == 0}   
							<div class="blacklist">
							<span class="follw" id="add_{$id}">已加入黑名单</span>
							<span class="close-follw" id="deleteFriend"><a href="javascript:void(0);" onclick="deleteBlock({$id});">解除</a></span>
							</div>
						{/if}
						
						{if $relation == 1}   
							<div class="follow-all">
							<span class="follw" id="add_{$id}"><a class="mul-concern"></a></span>
							<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend({$id});"></a></span>
							</div>
						{/if}
						
						{if $relation == 2}   
							<div class="follow-all">
							<span class="follw" id="add_{$id}"><a class="been-concern"></a></span>
							<span class="close-follw" id="deleteFriend"><a class="cancel-concern" href="javascript:void(0);" onclick="delFriend({$id});"></a></span>
							</div>
						{/if}
						
						{if $relation == 3 || $relation == 4}  
							<div class="follow-all">
							<span class="follw" id="add_{$id}"><a class="concern" href="javascript:void(0);" onclick="addFriends({$id} ,{$relation});"></a></span>
							<span class="close-follw" id="deleteFriend"></span>
							</div>
						{/if}
					{/if}
                </div>
                <span class="txt">个人主页 ：<?php echo SNS_UCENTER; ?>user.php?user_id={$user_info['id']}</span>
    	</li>
    	<li class="u-menus">
            	<ul class="pho_1">
                	<li>性    别：<span class="txt">
                	{code}

                	echo hg_show_sex($user_info['sex']);
                	
                	{/code}
                	      
                	{if is_numeric($user_info['birthday'])}
                	
                	({$_lang['xingzuo'][$user_info['birthday']]})
                	
                	{else if $user_info['birthday']}
                	
                	 ({$user_info['birthday']})
                	{/if}
                	
                	
                	</span></li>
					<li>所在地盘：<span class="txt">
				
						{if $is_my_page}
						<a href="<?php echo hg_build_link('geoinfo.php');?>">
							{if $user_info['group_name']}
							{$user_info['group_name']}
							{else}
							暂无
							{/if}
						</a>
						{else}
							{if $user_info['group_name']}
							   <a href="<?php echo hg_build_link(SNS_TOPIC, array('m' => 'thread' , 'group_id' => $user_info['group_id']));?>">{$user_info['group_name']}</a>
							{else}
							暂无
							{/if}
						{/if}
							
					</span></li>
				
                {code}
/*,'qq'=>'QQ','msn'=>'MSN','mobile'=>'手机'*/
				$relation1 = array('email'=>'邮    箱');
				$relation_count =0;
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
						{code}
						  $relation_count ++;
					    {/code}
					{/if}
				{/foreach}
		
                </ul>
    	</li>
    	<li class="u-sites"><span class="u-site">
    				{if $is_my_page}				
						我关注的地盘
					{else}
						TA关注的地盘
					{/if}
                
                	</span><a class="u-more" href="group.php?user_id={$user_info['id']}">查看全部 >></a></li>
    	<li class="u-menu clear">
    	<span class="u-show1">
    		<a onclick="changeContent(2 , {$id});" href="javascript:void(0);" id="status_count">{$user_info['status_count']}</a>
    	</span>
    	<span class="u-show2">
    		<a href="<?php echo hg_build_link(SNS_UCENTER."follow.php", array('user_id'=>$user_info['id']));?>">{$user_info['attention_count']}</a>
    	</span>
    	<span class="u-show3">
    		<a href="<?php echo hg_build_link(SNS_UCENTER."fans.php", array('user_id'=>$user_info['id']));?>">{$user_info['followers_count']}</a>
    	</span>
    	<span class="u-show4">
    		<a href="<?php echo hg_build_link(SNS_VIDEO."user.php", array('user_id'=>$user_info['id']));?>">{$user_info['video_count']}</a>
    	</span>
            	</li>
    	<li class="u-list">
    		                	
                	<ul>
                	
                		{if !is_array($group)}
                		
                			<li>暂未加入任何社区</li>
                		{else}
                			{code}
                			   $i = 1;
                			{/code}
                			{foreach $group as $key=>$value}
                			
                				{if $i<=5}         				
                				    <li><a title="{$value['name']}" href="{$value['href']}"><img src="{$value['logo']}" width="48" height="48" /></a><a title="{$value['name']}" href="{$value['href']}"><?php echo hg_cutchars($value['name'],4," ");?></a></li>
                				{/if}
                			 {code}
                			   $i++;
                			 {/code}
                			{/foreach}
                		{/if}
                    </ul>
    	</li>
    	<li></li>
    	<li></li>
    </ul>
</div>
</div>