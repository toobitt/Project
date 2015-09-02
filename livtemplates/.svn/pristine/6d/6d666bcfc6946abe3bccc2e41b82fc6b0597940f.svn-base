<!--  时间搜索模版 -->
{code}
$value = $hg_value;
{/code}
<div class="new-search-item new-search-time" data-widget="hg_search_time" data-type="time">
	<span class="label">{if $hg_attr['title']}{$hg_attr['title']}{else}时间{/if}</span>
	<span class="condition-area">
		<span class="current-condition-show">{$hg_data[$value]}</span>
		{if $hg_data}
		<ul class="condition-area-list defer-hover-target">
			{foreach $hg_data as $k => $v}
			<li>
				<a data-id="{$k}" data-type="time">{$v}</a>
			</li>
			{/foreach}
		</ul>
		{/if}
		<input type="hidden" name="{$hg_name}" value="{$value}" />
	</span>
	<span class="define-condition-area"><input type="text" placeholder="开始时间" {if $hg_attr['hasSecond']}_second="true"{/if} name="{$hg_attr['start_time']}" class="start-time search-date-picker" />-<input type="text" {if $hg_attr['hasSecond']}_second="true"{/if} name="{$hg_attr['end_time']}" class="end-time search-date-picker" placeholder="结束时间" /></span>
</div>