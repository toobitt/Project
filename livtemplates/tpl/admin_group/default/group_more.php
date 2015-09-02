<?php 
/* $Id: group_more.php 1234 2011-10-10 06:28:09Z repheal $ */
?>
{if is_array($formdata)}
	<span style="float:right;margin-right:4px;cursor:pointer;" onclick="hg_more_close();">X</span>
	<div class="more_up">
		<a class="logo" href="{$formdata['link']}" title="{$formdata['user_name']}创建于{$formdata['create_time']}，访问数:{$formdata['total_visit']}，留言数:{$formdata['bulletin_count']}"><img src="{$formdata['logo']}" alt="" /></a>
		<ul>
			<li><span class="marks">地盘名：</span><span class="contents"><a href="{$formdata['link']}" title="{$formdata['user_name']}创建于{$formdata['create_time']}，访问数:{$formdata['total_visit']}，留言数:{$formdata['bulletin_count']}">{$formdata['name']}</a></span></li>
			<li><span class="marks">分类：</span><span class="contents">{$formdata['type_name']}</span></li>
			<li><span class="marks">居民：</span><span class="contents">{$formdata['group_member_count']}</span></li>
			<li><span class="marks">主题/帖子：</span><span class="contents">{$formdata['thread_count']}/{$formdata['post_count']}</span></li>
			<li><span class="marks">相册：</span><span class="contents">{$formdata['picture_count']}</span></li>
		</ul>
	</div>
	<div class="more_down clear">
		<span class="marks">描述</span>
		<span class="contents">{$formdata['description']}</span>
	</div>
{else}
	地盘不存在或已删除。
{/if}
