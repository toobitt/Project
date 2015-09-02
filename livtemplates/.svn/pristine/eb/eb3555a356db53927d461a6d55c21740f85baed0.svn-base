<!--  权重搜索模版 -->
<div class="new-search-item new-search-weight" data-widget="hg_search_weight" data-type="weight">
	<span class="label">{if $hg_attr['title']}{$hg_attr['title']}{else}权重{/if}</span>
	<span class="condition-area">
		<span class="current-condition-show">{$hg_data[$hg_value]['title']}</span>
		{if $hg_data }
		<ul class="condition-area-list defer-hover-target">
			{foreach $hg_data as $k => $v}
			<li>
				<a data-id="{$v['begin_w']},{$v['end_w']}" title="{$v['title']}" data-type="weight">{if !$v['default']}>={$v['begin_w']} {/if}{$v['title']}</a>
			</li>
			{/foreach}
			<li>
				<a data-id="other" title="自定义权重" data-type="weight">自定义权重</a>
			</li>
		</ul>
		{/if}
		<input type="hidden" name="weight_hidden" />
		<input type="hidden"  class="start-weight-hidden" name="{$hg_attr['start_weight']}" value="0" />
		<input type="hidden"  class="end-weight-hidden" name="{$hg_attr['end_weight']}" value="100" />
	</span>
	<span class="define-condition-area">
		<span class="define-weight-input"><input type="text" class="start-weight-input" value="0" />-<input type="text" class="end-weight-input" value="100" /></span>
		<div class="define-weight-box">
			  <i class="start">0</i>
			  <div class="define-weight-slider"></div>
			  <i class="end">100</i>
		</div>
	</span>
</div>