<ul class="clear">
	<span style="display:block;margin-left:8px;color:#9E9E9E;">*从直播时移获取需频道支持手机流</span>
	{code}
		$offset_pre = $formdata['offset'] - $formdata['plan_count'] + 1;
		$offset_next = $formdata['offset'] + $formdata['plan_count'] - 1;
		$num = $formdata['plan_count'] - 1;
		$count = $formdata['count'];
	{/code}
	{foreach $formdata['data'] as $key => $value}
		{if $key< $num}
			{if $value['is_mobile_phone']}
				<li class="overflow" onclick="hg_select_channel(this,{$value['id']},{$_INPUT['mid']},{$value['time_shift']});"><span>{$value['name']}</span>&nbsp;&nbsp;{if $value['status']}启动{else}未启动{/if}</li>			
			{else}
				<li class="overflow" onclick="hg_select_channel_error(this,{$value['id']},{$value['time_shift']});"><span>{$value['name']}</span>&nbsp;&nbsp;{if $value['status']}启动{else}未启动{/if}</li>			
			{/if}
		{/if}	
	{/foreach}
</ul>
<div style="float:right;margin-bottom:10px;">
	{if $formdata['offset']}<a style="margin:10px;cursor:pointer;" onclick="hg_channel_show_page({$offset_pre},{$formdata['plan_count']});">上一页</a>{/if}
	{if $count==$formdata['plan_count']}<a style="margin:10px;cursor:pointer;"  onclick="hg_channel_show_page({$offset_next},{$formdata['plan_count']});" href="javascript:void(0);">下一页</a>{/if}
</div>


