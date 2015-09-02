<?php
/* $Id: my_comments.php 452 2011-08-05 05:59:26Z repheal $ */
?>
{template:head}
<div class="main_div">
	<div class="right_tips right_window">
	<h3>我的评论</h3>
	<div class="show_info">
		<div class="comment_menu">
			<ul class="comment_state">
			{foreach $list as $key => $value}
			{if $key == $state}
				<li class="comment_state_now"><a href="{$value['url']}" class="blod">{$value['name']}</a></li>
			{else}
				<li><a href="{$value['url']}">{$value['name']}</a></li>
			{/if}
			{/foreach}
			</ul>		

			<ul class="comment_type">
			{foreach $menu as $key => $value}
				{if $key == $type}
					<li class="comment_type_now"><a href="{$value['url']}">{$value['name']}</a></li>
				{else}
					<li><a href="{$value['url']}">{$value['name']}</a></li>
				{/if}
			{/foreach}
			</ul>		
		</div>
		<div class="comment_manage clear">
			{if $stationInfo}
			<ul class="comment_list">
				{foreach $stationInfo as $key => $value}
				<li id="com_{$value['id']}" class="clear">
					<div class="comment-img"><a href="<?php echo hg_build_link('user.php', array('user_id'=>$value['user']['id'],));?>"><img src="{$value['user']['middle_avatar']}"/></a></div>
					<div class="comment-bar">
						<a class="bar-left" href="<?php echo hg_build_link('user.php', array('user_id'=>$value['user']['id'],));?>">{$value['user']['username']}</a>
						<div class="bar-right">
							<span><?php echo hg_get_date($v['create_time']);?></span>
							{if $state != 2}
								<a href="javascript:void(0);" onclick="del_comment({$value['id']},{$value['cid']},{$type});">删除</a>
							{else}
								<a href="javascript:void(0);" onclick="recover_comment({$value['id']},{$value['cid']},{$type});">恢复</a>
							{/if}
						</div>
					</div>
					<div class="comment-con"><?php echo hg_show_face($value['content']);?></div>
				{if is_array($value['reply'])}
				<ul class="reply_list" id="rep_{$value['id']}">
				{foreach $value['reply'] as $k=>$v}
					<li id="com_{$v['id']}" class="clear">
						<div class="comment-img"><a href="<?php echo hg_build_link('user.php', array('user_id'=>$v['user']['id'],));?>"><img src="{$v['user']['middle_avatar']}"/></a></div>
						<div class="comment-bar">
							<a class="bar-left" href="<?php echo hg_build_link('user.php', array('user_id'=>$v['user']['id'],));?>">{$v['user']['username']}</a>
							<div class="bar-right">
								<span><?php echo hg_get_date($v['create_time']);?></span>
								{if $state != 2}
								<a href="javascript:void(0);" onclick="del_comment({$v['id']},{$v['cid']},{$type});">删除</a>
								{else}
								<a href="javascript:void(0);" onclick="recover_comment({$v['id']},{$v['cid']},{$type});">恢复</a>
								{/if}
							</div>
						</div>
						<div class="comment-con"><?php echo hg_show_face($value['content']);?></div>
					</li>
				{/foreach}
				</ul>
				{/if}
				</li>
				{/foreach}
				
			</ul>
			{else}
			{code}
				$null_title = "I’m Sorry";
				$null_text = "暂无评论";
				$null_url = $_SERVER['HTTP_REFERER'];
			{/code}
				{template:unit/null}
			{/if}
		</div>
		</div>
	</div>
	{template:unit/my_right_menu}
</div>
{template:foot}