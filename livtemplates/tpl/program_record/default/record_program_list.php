{if !empty($formdata)}
<div class="program">
	<ul class="program_ul">
		<li class="program_dates"><span>{code} echo date('Y-m-d');{/code}</span></li>
		{foreach $formdata as $key => $value}
		<li class="program_li"><span class="program_time">{$value['start']}</span><a class="program_theme">{$value['theme']}</a></li>
		{/foreach}
	</ul>	
	<div class="clear"></div>
</div>
{else}<span style="color: red;opacity: 0.5;">暂无内容</span>{/if}