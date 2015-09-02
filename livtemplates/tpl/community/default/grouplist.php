{template:./head}
</section><!--展示区完-->
	<section class="wrap clearfix">
		<article class="gmain">
			<div class="gmain_top"></div>
			<div class="cmain">
					<div class="group_title clearfix"><h3>全部圈子</h3></div>
					<div class="group_single">
					{if $groups}
						<ul class="clearfix">
							{foreach $groups as $group}
							<li><img src="{if is_string($group['logo'])}{$group['logo']}{else}{$group['logo']['host']}{$group['logo']['dir']}77x77/{$group['logo']['filepath']}{$group['logo']['filename']}{/if}" width="77" height="77" /><div class="group_single_det"><a href="group.php?group_id={$group['group_id']}" title="{$group['name']}">{code}echo hg_cutchars($group['name'], 10);{/code}</a><div class="group_single_brief">{code}echo hg_cutchars(html_entity_decode($group['description']), 20);{/code}</div><span class="group_single_member">{$group['group_member_count']}</span><span class="group_single_thread">{$group['thread_count']}</span></div></li>
							{/foreach}
						</ul>
						<div class="pages_nav">{$pagelink}</div>
					{else}
					暂没有圈子信息，<a href="groups.php?a=create">创建圈子</a>
					{/if}
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