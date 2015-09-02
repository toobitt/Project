<div class="group_hot">
	{if $join_group}
	<h3>大家都已加入</h3>
	<div class="group_hot_grid">
		<ul class="group_hot1 clearfix" id="join_group" style="position:relative;">
			{foreach $join_group as $v}
			<li><a href="group.php?group_id={$v['group_id']}"><img src="{if is_string($v['logo'])}{$v['logo']}{else}{$v['logo']['host']}{$v['logo']['dir']}68x68/{$v['logo']['filepath']}{$v['logo']['filename']}{/if}" width="68" height="68" alt="{$v['name']}" /></a>
			<div class="group_hot_grid3">
				<a href="group.php?group_id={$v['group_id']}"><img src="{if is_string($v['logo'])}{$v['logo']}{else}{$v['logo']['host']}{$v['logo']['dir']}207x207/{$v['logo']['filepath']}{$v['logo']['filename']}{/if}" width="207" height="207" /></a>
				<div class="group_hot_grid_title"><a href="group.php?group_id={$v['group_id']}">{$v['name']}</a><br />{code}echo hg_cutchars($v['description'], 20);{/code}</div>
				<span class="opbg"></span>
			</div>
			</li>
			{/foreach}
		</ul>
	</div>
	{/if}
</div>