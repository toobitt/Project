<div class="search-box" _url="{$url}">
	<div class="search-item">
		<span class="search-item-title">本周</span>
		<div class="search-time-box search-info">
			<ul class="time-list">
				{foreach $_configs['date_search'] as $k => $v}
				{if $k !=5}
				<li {if $k==1}class="current"{/if} _value="{$k}">{$v}</li>
				{/if}
				{/foreach}
			</ul>
			<span class="custom-tip">自定义:</span>
			<div class="custom">
				<input type="text" name="start_time" class="date-picker" placeholder="输入起始时间"/>
				<span>--</span>
				<input type="text" name="end_time" class="date-picker" placeholder="输入结束时间"/>
			</div>
			<input type="hidden" name="date_search" value="0"/>
		</div>
	</div>
	{if $flag}
	<div class="search-item">
		<span class="search-item-title">全部类型</span>
		<ul class="type-list search-info">
			{if !$muti}
			<li _value="0"><input type="{if $muti}checkbox{else}radio{/if}" {if !$muti}name="radio"{/if} value="0"/>全部类型</li>
			{/if}
			{foreach $apps as $k => $v}
			<li _value="{$v['app_uniq']}">
			<input type="{if $muti}checkbox{else}radio{/if}" {if !$muti}name="radio"{/if} value="{$v['app_uniq']}"/>{$v['name']}</li>
			{/foreach}
		</ul>
		<input type="hidden" name="app" />
	</div>
	{/if}
	{if $toggle}
	<div class="toggle-chart">
		<span class="line current"></span>
		<span class="bar"></span>
	</div>
	{/if}
</div>