<?php 
/* $Id: search.php 8320 2012-03-16 08:08:52Z repheal $ */
?>
{template:head}
<div class="content">
	<div class="con-left">
		 
		{if $have_result}
			
		<div class="left_list_first_line">
			<div class="search-name">
				<span>共有{$total_nums}位用户</span>
			</div>		
		</div>
		<div class="search_list">
			<ul class="status-item">
			{foreach $search_friend as $k => $v}
				<li>
					<div class="conBox_l">
						<a href="{code} echo hg_build_link('user.php' , array('user_id' => $v['id'])); {/code}"><img src="{$v['middle_avatar']}" title="{$v['username']}" /></a>
					</div>
					<div class="conBox_c">
						<span><a href="{code} echo hg_build_link('user.php' , array('user_id' => $v['id'])); {/code}" >{$v['username']}</a></span>
						<p style="margin-top:10px;">
						   {$v['location']}
						   <span>{$_lang['followers']}<strong>{$v['followers_count']}</strong>{$_lang['people']}</span>
						</p>
					</div>
					<div class="conBox_r">
				
					{if $_user['id'] == $v['id']} 				<!-- //自己  -->         
				
					{else}
					
						{if $v['is_friend'] == 1}    <!-- //已关注 -->
					<p>已关注</p>
						{else}
					
					<p id="add_{$v['id']}"><a href="javascript:void(0);" onclick="addFriends({$v['id']} , 4)">{$_lang['add_friends']}</a></p>
						{/if}
					{/if}
					</div>
				</li>
			{/foreach}		
			</ul>
			<div style="clear:both;"></div>
			{$showpages}
		</div>		
	
		{else}
	{code}
		$null_title = "真不给力，SORRY!";
		$null_text = '抱歉没有找到<span style="color:red;">'.$screen_name.'</span>相关的结果';
	{/code}
	{template:unit/null}
		{/if}
	
	</div>
</div>
{template:foot}