{template:./head}
{js:qingao/reply_thread}
</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain">			
				<div class="thread">
					<div class="thread_header clearfix">
						<a href="member.php?uid={$thread['user_id']}"><img class="thread_author_avatar" src="{if is_string($thread['avatar'])}{$thread['avatar']}{else}{$thread['avatar']['host']}{$thread['avatar']['dir']}50x50/{$thread['avatar']['filepath']}{$thread['avatar']['filename']}{/if}" /></a>
						<div class="thread_header_main">							
							<h1>{$thread['title']}</h1>													
							<div class="thread_assist"><a href="javascript:;" class="thread_share jiathis jiathis_txt jiathis_separator jtico jtico_jiathis">分享</a><!--<a href="#" class="thread_favorite" title="收藏" >收藏</a><a href="#" title="举报" class="thread_report" >举报</a>--><div class="thread_poll"></div><a href="member.php?uid={$thread['user_id']}" class="thread_author_text">{$thread['user_name']}</a><time  class="thread_by_time">{$thread['pub_time']}</time></div>
							<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js" charset="utf-8"></script>
						</div>
					</div>					
					<div class="thread_main">
						<p>{$thread['content']}</p>
						{foreach $thread['material'] as $v}
						<p><img src="{$v['img_info']['host']}500x1/{$v['img_info']['filepath']}" /></p>
						{/foreach}
					</div>
				</div>	
				<div class="thread_replys">
					{foreach $post_info as $post}
					<div class="thread_reply">
						<a name="reply{$post['post_id']}"></a>
						<a href="member.php?uid={$post['user_id']}"><img class="thread_reply_avatar" src="{if is_string($post['avatar'])}{$post['avatar']}{else}{$post['avatar']['host']}{$post['avatar']['dir']}50x50/{$post['avatar']['filepath']}{$post['avatar']['filename']}{/if}" /></a>
						<div class="thread_reply_main">						
							<div class="thread_reply_assist"><span class="thread_reply_floor">{$post['floor']}楼</span><a href="#reply" onclick="show(this)" args="{$post['floor']}|{$post['post_id']}|{$post['user_name']}|{$post['user_id']}|{code}echo hg_cutchars($post['pagetext'], 40);{/code}" class="thread_reply_reply" title="回复">回复</a><!--<a href="#" title="举报" class="thread_reply_report" >举报</a>--><a href="member.php?uid={$post['user_id']}" class="thread_reply_author">{$post['user_name']}</a><time  class="thread_reply_time">{code}echo hg_get_format_date($post['pub_time'], 2);{/code}</time></div>
							{if $post['stair_num'] != 0}
							<div class="thread_reply_cite"><span class="thread_reply_quote"><cite class="reply_cite">回复{$post['stair_num']}楼 {$post['reply_user_name']}:</cite> {$post['reply_des']}</span></div>
							{/if}
							<div class="thread_reply_c">{$post['pagetext']}</div>
						</div>
					</div>
					{/foreach}
					<div id="post_page">{$pagelink}</div>
				</div>
				<div class="thread_reply_now">
					<a name="reply"></a>
					<form action="#" method="post">
						<div class="thread_reply_cite"><span class="thread_reply_quote" id="quoteCon"><cite class="reply_cite">回复楼主 {$thread['user_name']}:</cite> {code}echo hg_cutchars(strip_tags($thread['content']), 30);{/code}</span></div>
						<textarea name="replyContent" id="replyContent" class="thread_reply_content">我来回应</textarea>
						<input type="hidden" name="thread_id" value="{$thread['thread_id']}" />
						<input type="hidden" name="post_id" value="{$thread['first_post_id']}" />
						<input type="hidden" name="fnum" value="0" />
						<input type="hidden" name="reply_user_id" value="{$thread['user_id']}" />
						<input type="hidden" name="reply_user_name" value="{$thread['user_name']}" />
						<input type="hidden" name="reply_des" value="{code}echo hg_cutchars(strip_tags($thread['content']), 40);{/code}" />
						<div class="thread_reply_sub"><!--<div class="sys_face">头像</div>--><input type="button" value="发表" id="reply_submit" class="thread_reply_btn"></div>
					</form>
				</div>
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