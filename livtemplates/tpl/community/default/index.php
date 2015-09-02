{template:./head_index}
{js:qingao/selection}
	</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain">
				<div  class="trends">
					
					{template:unit/statusline_public}
					
					
				</div><!-- end for trends
				<div class="trends_more">正在载入更多，请稍候…</div> -->
			</div><!--end for cmain-->
			<div class="gmain_bottom"></div>
		</article>
		<aside class="gaside hid">
			<div class="gaside_top"></div>
			<div class="gaside_m">				
				<div class="g_user" id="tslide">
					<h3><a href="{$more_action_link}" class="title">发现更多行动家...</a></h3>
					<div class="slides_container">
						<div>
						<ul class="clearfix">
						{if !empty($userinfos)}
						{foreach $userinfos as $k => $v}
							{code}
								$avatar_url = 'img/avatar.jpg';
								if($v['filename'])
								{
									$avatar_url = $v['host'] . $v['dir'] . '50x50/'. $v['filepath'] . $v['filename'];
								}
								$linkUrl = hg_build_href_link(SNS_YOUTH . USER_URL . '?uid=' . $v['id']);
							{/code}
							<li><a href="{$linkUrl}"><img src="{$avatar_url}" width="50" height="50" /></a><p><a href="{$linkUrl}">{$v['nick_name']}</a></p></li>
						{/foreach}
						{/if}
						</ul>
						</div>
					</div>
				</div>
				<div class="acreate"><a href="{$action_link}"><img src="img/createaction.png" /></a></div>
				<div class="hot_action">
					<h3><span class="title">热门行动</span><a href="{$more_action_link}" class="tmore">浏览更多...</a></h3>
					<ul class="clearfix">
					{foreach $activity as $k => $v}
					{code}
						$avatar = unserialize($v['action_img']);
						$avatar_url = $avatar['host'] . $avatar['dir'] . '80x80/'. $avatar['filepath'] . $avatar['filename'];
						$linkUrl = hg_build_href_link(SNS_YOUTH . 'activity.php?action_id=' . $v['id']);
					{/code}
						<li><a href="{$linkUrl}"><img src="{$avatar_url}" alt="{$v['action_name']}" /></a><div class="ahot_sdesc"><a href="{$linkUrl}" class="ahot_stitle">{$v['action_name']}</a><p>地点:{$v['place']}</p><p>{$v['collect_num']}人感兴趣</p><p>{$v['apply_num']}人参加</p></div></li>
					{/foreach}					
					</ul>
				</div>
			</div>
			<div class="gaside_bottom"></div>
		</aside>
	</section>
{template:unit/relay-box}
{template:./footer}