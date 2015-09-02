<ul>
 
{foreach $vipUser AS $k => $v}

	<li class="gz-ul">
		<a href="<?php echo SNS_UCENTER; ?>user.php?user_id={$v['id']}" ><img title="{$v['username']}" style="border: 1px solid silver; height: 50px;padding: 2px;width: 50px;" src="{$v['middle_avatar']}" /></a>
		<a title="{$v['username']}" href="{code} echo hg_build_link(SNS_UCENTER . 'user.php' , array('user_id' => $v['id']));{/code}">{code} echo hg_cutchars($v['username'] , 3 , '...' , 1); {/code}</a>
		
		<div class="close-concern">
	
		{if $_user['id'] == $v['id']} 				<!-- /*自己 */ -->         
		
			
		{else}
		
			{if $v['is_friend'] == 1}   <!-- /*已关注*/ -->
			
		
		<p><span class="been-concern">√已关注</span>	</p>			
			
			{else}
			
		<p id="add_{$v['id']}"><a class="concern" href="javascript:void(0);" onclick="addFriends({$v['id']} , 4)">＋加关注</a></p>
				
			{/if} 
		{/if} 															
		</div>
	</li>
	
	
	
	
{/foreach}
</ul>	
