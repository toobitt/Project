<!--  话题状态搜索模版 -->
{code}
$value = $hg_value;
{/code}
<div class="new-search-item new-search-status" data-type="select">
	<span class="label">{if $hg_attr['title']}{$hg_attr['title']}{else}话题状态{/if}</span>
	<span class="condition-area">
		<span class="current-condition-show">{$hg_data[$value]}</span>
		{if $hg_data}
		<ul class="condition-area-list defer-hover-target">
			{foreach $hg_data as $k => $v}
			<li>
				<a data-id="{$k}">{$v}</a>
			</li>
			{/foreach}
		</ul>
		{/if}
		<input type="hidden" name="{$hg_name}" value="{$value}" />
	</span>
</div>