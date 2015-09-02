{if !empty($formdata)}
<div class="plan">
	<ul class="plan_ul">
	{foreach $formdata as $k => $v}
		<li class="plan_li"><span class="plan_time">{$v['start']}</span><a class="plan_name">{$v['program_name']}</a><span _week="{code}echo implode(',',$v['week_day']);{/code}" class="plan_week">周期性：{if count($v['week_day_ch']) == 7}每天{else}{foreach $v['week_day_ch'] as $kk => $vv}{code} echo $vv;{/code}{/foreach}{/if}</span></li>
	{/foreach}
	</ul>
	<div class="clear"></div>
</div>
{else}<span style="color: red;opacity: 0.5;">暂无内容</span>{/if}