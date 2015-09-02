{css:mail-box}
<div class="my-message-box">
	<div class="my-message-top">
		<h3>我的私信</h3>
		<span class="person-total">(已有<em>{$person_num}</em>个联系人)</span> <span
			class="message-public"><a href="#" class="public-btn"></a> </span>
	</div>
	<div class="my-message-con">
		<ul class="message-list">
		{foreach $pm_new_data as $v}
			<li class="clearfix"><a href="member.php?uid={$v['id']}" class="person-pic"><img
					src="{if is_string($v['avatar'])}{$v['avatar']}{else}{$v['avatar']['host']}{$v['avatar']['dir']}50x50/{$v['avatar']['filepath']}{$v['avatar']['filename']}{/if}" width="50" height="50" /> </a>
				{code}$last_pm = end($v['pm']);{/code}
				<div class="message-descr">
					<div class="message-descr-title">
						<b class="message-name">{if $last_pm['fromID'] != $v['id']}发给 {/if}{$v['nick_name']} ：</b>
						{$last_pm['content']}
					</div>
					<div class="message-descr-detail">
						<span class="message-date">{code}echo hg_get_date($last_pm['stime']);{/code}</span> <span
							class="message-control"><a href="#">转发</a> | <a href="#">共<em>{$v['pm_num']}</em>条私信</a> <a
							href="#" class="fast-fw">快速回复</a> </span>
					</div>
				</div>
			</li>
		{/foreach}
		</ul>
	</div>
</div>