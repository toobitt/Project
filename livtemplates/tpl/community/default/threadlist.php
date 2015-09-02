{template:./head}
	</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain group_forums">
				{if $permission}
				<h3>{$permission}</h3>
				{else}
				<div class="group_title clearfix"><h3>讨论列表</h3></div>
				<div class="group_forum_threads">
					<div class="group_forum_thread thead">
						<div class="forum_thread_title">名称</div>
						<div class="forum_thread_author">作者</div>
						<div class="forum_thread_count">回复/查看</div>
						<div class="forum_thread_last">最后发表</div>
					</div>
					{if $no_threads}
					<p style="text-align:center; margin:10px auto;">暂没有帖子，<a href="thread.php?a=create&group_id={$group['group_id']}">发布新帖子</a></p>
					{else}
					{foreach $threads as $thread}
					<div class="group_forum_thread">
						<div class="forum_thread_title"><a href="thread.php?thread_id={$thread['thread_id']}">{$thread['title']}</a><div>{code}echo hg_cutchars(strip_tags($thread['content']), 30);{/code}</div></div>
						<div class="forum_thread_author"><a href="member.php?uid={$thread['user_id']}">{$thread['user_name']}</a><time>{$thread['pub_time']}</time></div>
						<div class="forum_thread_count">{$thread['post_count']}/{$thread['click_count']}</div>
						<div class="forum_thread_last">
							<a href="#">{$thread['last_poster']}</a>
							<time>{$thread['last_post_time']}</time>
						</div>
					</div>
					{/foreach}
					{/if}
				</div>
				<div class="pages_nav">{$pagelink}</div>
				{/if}
			</div><!--end for cmain-->
			<div class="gmain_bottom"></div>
		</article>
		<aside class="gaside hid">
			<div class="gaside_top"></div>
			<div class="gaside_m">
				<div class="create_group"><a href="groups.php?a=create"><img src="img/creategroup.png" /></a></div>
				{template:./join}
			</div>
			<div class="gaside_bottom"></div>
		</aside>
	</section>
{template:./footer}