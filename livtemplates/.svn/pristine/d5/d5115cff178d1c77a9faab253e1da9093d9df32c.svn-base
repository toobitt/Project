{template:./head}
{js:qingao/group_reply}
		<div class="gshow_top"></div>
		<div class="gshow_c clearfix">
			<div class="gshow clearfix">
				<img src="{if is_string($group['background'])}{$group['background']}{else}{$group['background']['host']}{$group['background']['dir']}333x222/{$group['background']['filepath']}{$group['background']['filename']}{/if}" class="gshow_logo" width="333" height="222">
				<div class="gshow_me">
					<h1>{code}echo hg_cutchars($group['name'], 12);{/code}</h1>
					<div class="gshow_intro">{code}echo hg_cutchars($group['description'], 30);{/code}</div>
					<div class="gshow_join">
						{if $level == -1}
						<a href="javascript:;" class="joinGroup">加入圈子</a>
						{elseif $level == 0 || $level == 1}
						<a href="javascript:;" class="ourGroup">退出圈子</a>
						{/if}
					</div>
					<img src="{if is_string($group['logo'])}{$group['logo']}{else}{$group['logo']['host']}{$group['logo']['dir']}89x89/{$group['logo']['filepath']}{$group['logo']['filename']}{/if}" class="gshow_avatar"  width="89" height="89" />
				</div>
			</div>
			<aside class="gshow_aside">
				<div class="gshow_link">
					{if $level == -1}
					<a href="javascript:;" class="gshow_joinow joinGroup">加入圈子</a>
					{elseif $level == 0 || $level == 1}
					<a href="javascript:;" class="gshow_manager ourGroup">退出圈子</a>
					{/if}
					{if $level == 2}
					<a href="manage.php?group_id={$group['group_id']}" class="gshow_manager">管理圈子</a>
					{/if}
				</div>
				<div class="gshow_note">
					<h3><a href="#">圈子公告</a></h3>
					<ul>
						{foreach $notices as $notice}
						<li><div><a href="group.php?a=notice&group_id={$group['group_id']}&nid={$notice['id']}">{$notice['title']}</a></div></li>
						{/foreach}
					</ul>
				</div>
			</aside>
		</div>
		<div class="gshow_bottom"></div>
	</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain clearfix">
				{if $permission}
				<h3>{$permission}</h3>
				{else}
				<h1 class="gt1">圈子讨论区</h1>
				<div class="gtalk">
					<h3><a href="thread.php?a=create&group_id={$group['group_id']}" class="title">发言</a>{if $group['threads']}<a href="group.php?a=all&group_id={$group['group_id']}" class="tmore">浏览全部</a>{/if}</h3>
					{if $group['threads']}
					<ul class="gtalk_list">
						{foreach $group['threads'] as $thread}
						<li class="gtalk_item">
							<div class="gtalk_title"><a href="thread.php?thread_id={$thread['thread_id']}">{$thread['title']}</a></div>
							{if $thread['material']}
							<ul class="gtalk_img">
								{foreach $thread['material'] as $v}
								<li><img src="{$v['img_info']['host']}88x1/{$v['img_info']['filepath']}" height="88" /></li>
								{/foreach}
							</ul>
							{/if}
							<div class="gtalk_content">{code}echo hg_cutchars(strip_tags($thread['content']), 40);{/code}</div>
							<div class="clearfix">
								<div class="gtalk_info">来自：<a href="member.php?uid={$thread['user_id']}" class="gtalk_user">{$thread['user_name']}</a>/<span class="gtalk_digg_num"><strong id="digg_{$thread['thread_id']}">{if $thread['praise']['counts']}{$thread['praise']['counts']}{else}0{/if}</strong>赞</span><span class="gtalk_reply_num"><a href="thread.php?thread_id={$thread['thread_id']}#reply"><strong id="reply_{$thread['thread_id']}">{$thread['post_count']}</strong>回应</a></span><!--<span class="gtalk_share_num"><strong>40</strong>分享</span>--></div>
								<div class="gtalk_assist"><span class="gtalk_pubdate">{code}echo hg_get_date(strtotime($thread['pub_time']));{/code}</span><a href="javascript:;" cid="{$thread['thread_id']}" m="{$thread['praise']['m']}" class="{if $thread['praise']['m'] == 'add'}gtalk_digg{elseif $thread['praise']['m'] == 'drop'}gtalk_digg gtalk_digg_v{/if}">赞</a>
								<!-- JiaThis Button BEGIN -->
								<a href="#" class="gtalk_share jiathis jiathis_txt jiathis_separator jtico jtico_jiathis">分享</a>
								<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js" charset="utf-8"></script>
								<!-- JiaThis Button END -->
								</div>
							</div>
							<div class="gtalk_reply">
								<form onsubmit="javascript:show(this); return false;">
									<input type="text" class="gtalk_reply_content" name="replyContent" value="回应..." />
									<input type="hidden" name="thread_id" value="{$thread['thread_id']}" />
									<input type="hidden" name="post_id" value="{$thread['first_post_id']}" />
									<input type="hidden" name="reply_user_id" value="{$thread['user_id']}" />
									<input type="hidden" name="reply_user_name" value="{$thread['user_name']}" />
									<input type="hidden" name="reply_des" value="{code}echo hg_cutchars(strip_tags($thread['content']), 40);{/code}" />
									<input type="button" onclick="show(this.form)" value="回应" class="gtalk_reply_btn" />
								</form>
							</div>
						</li>
						{/foreach}									
					</ul>
					{else}
					<p style="margin:10px auto;">暂没有帖子，<a href="thread.php?a=create&group_id={$group['group_id']}">发布新帖子</a></p>
					{/if}
					<!--<div class="gtalk_more">正在加载更多...请稍等</div>-->
				</div>
				{/if}
			</div>
			<div class="gmain_bottom"></div>
		</article>
		<aside class="gaside">
			<div class="gaside_top"></div>
			<div class="gaside_m">
				{if $members && !$permission}
				<div class="g_user">
					<h3><span class="title">圈子成员</span><a href="manage.php?a=member&group_id={$_INPUT['group_id']}&visit=1" class="tmore">{$membersNum}</a></h3>
					<ul class="clearfix">
						{foreach $members as $member}
						<li><a href="member.php?uid={$member['id']}"><img src="{if is_string($member['avatar'])}{$member['avatar']}{else}{$member['avatar']['host']}{$member['avatar']['dir']}50x50/{$member['avatar']['filepath']}{$member['avatar']['filename']}{/if}" width="50" height="50" /></a><p><a href="member.php?uid={$member['id']}">{$member['user_name']}</a></p></li>
						{/foreach}
					</ul>
				</div>
				{/if}
				{if $activities && !$permission}
				<div class="g_event">
					<h3><span class="title">成员创建的活动</span><!--<a href="#" class="tmore">浏览全部</a>--></h3>
					<ul class="clearfix">
						{foreach $activities as $activity}
						<li><a href="activity.php?action_id={$activity['id']}"><img src="{$activity['action_img']['host']}79x79/{$activity['action_img']['filepath']}" width="79" height="79" /></a><div class="gevt_desc"><a href="activity.php?action_id={$activity['id']}" class="gevt_title">{code}echo hg_cutchars($activity['action_name'], 20);{/code}</a><div class="gevt_count"><span>{$activity['collect_num']}人感兴趣</span>/<span>{$activity['yet_join']}人参加</span></div></div></li>
						{/foreach}
					</ul>
				</div>
				{/if}
				<div class="gaside_ad"><a href="#"><img src="img/gasidead.jpg" /></a></div>				
			</div>
			<div class="gaside_bottom"></div>
		</aside>
	</section>


<script type="text/javascript">
window.onload = function(){
    var zhong = $('.cmain');
    var height = $('.gaside_m').outerHeight(true) - 8;
    if(zhong.height() < height){
        zhong.height(height);
    }
};
</script>
{template:./footer}