{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
{if $formdata}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<form name="planform" id="planform" action="" method="post">
	{code}
	$type_source = array('other'=>' size="14" autocomplete="off" style="width:80px;height: 18px;text-align: center;line-height: 20px;font-size:12px;padding-left:5px;float: left;" ','name'=>'plan_start_time','style'=>'padding-left: 4px;width:90px;float: left;','type'=>'HH:mm:ss');
	$default_start = $start_time ? date('H:i:s',$start_time) : '';
	{/code}
	{template:form/wdatePicker,start_time,$default_start,'',$type_source} 
	<span id="channel2_name_info" onclick="hg_type_source_plan();" class="channel2_name_info">
		{if $action=='update'}
			<span class="chg_type_fir">
			{if $type==1}直播{else if $type==2}文件{else if $type == 3}时移{else}信号{/if}
			</span>
		{/if}
		{code}
			$today = date('N', TIMENOW);
			$week = date('W',$program_start_time);
			$this_week = date('W',TIMENOW);
			$offset_week = ($this_week - $week)*24*3600*7;
			if($week_days == $week_d)
			{
				$program_start = date('m-d H:i:s', ($program_start_time + $offset_week));
			}
			else if($week_days > $week_d)
			{
				$program_start = date('m-d H:i:s', ($program_start_time - (86400*($week_days-$week_d)) + $offset_week));
			}
			else if($week_days < $week_d)
			{
				$program_start = date('m-d H:i:s', ($program_start_time + (86400*($week_d-$week_days)) + $offset_week));
			}
			$program_end = date('H:i:s', ($program_start_time+$toff));
		{/code}
		<span class="title">{$channel2_name}{if $type==3}<span class="time_style">{$program_start} - {$program_end}</span>{/if}</span>
	</span>
	{code}
	$type_source = array('other'=>' size="14" autocomplete="off" style="width:80px;height: 18px;text-align: center;font-size:12px;padding-left:5px;line-height: 20px;float: left;" ','name'=>'plan_end_time','style'=>'float: left;margin-left:5px;width:90px','type'=>'HH:mm:ss');
	$default_end = $toff ? date('H:i:s',$start_time+$toff) : '';
	{/code}
	{template:form/wdatePicker,end_time,$default_end,'',$type_source}
	{code}
		$week_day_arr = array('1' => '周一', '2' => '周二', '3' => '周三', '4' => '周四', '5' => '周五', '6' => '周六', '7' => '周日');
	{/code}
	<div id="week_date" class="week_date">
		<label>
			<input class="n-h" type="checkbox" onclick="hg_plan_repeat(this,1);" id="every_day" name="every_day" {if count($week_day) == 7} checked{/if}/><span>每天</span>
		</label>
		{foreach $week_day_arr as $key => $value}
			<label>
			<input onclick="hg_plan_repeat(this,0);" value="{$key}" class="n-h" type="checkbox" name="week_day[]" id="week_day_{$key}" {if $week_day[$key]} checked{/if}/><span>{$value}</span>
			</label>
		{/foreach}		
		<span class="close_plan" {if $id}onclick="hg_delete_plan({$id});"{else}onclick="hg_del_plan_dom();"{/if}></span>
	</div>
	<div style="clear:both;"></div>
	<div id="change_type_source" style="width:500px;"></div>
	<input type="hidden" id="id" name="id"value="{$id}"/>
	<input type="hidden" id="a" name="a" value="{$action}"/>
	<input type="hidden" id="mid" name="mid" value="{$_INPUT['mid']}"/>
	<input type="hidden" id="channel_id" name="channel_id" value="{$_INPUT['channel_id']}"/>
	<input type="hidden" id="channel2_ids" name="channel2_ids" value="{$channel2_id}"/>
	<input type="hidden" id="channel2_name" name="channel2_name" value="{$channel2_name}"/>
	<input type="hidden" id="type" name="type" value="{$type}"/>
	<input type="hidden" id="start_hidden" value="{$default_start}"/>
	<input type="hidden" id="end_hidden" value="{$default_end}"/>
	<input type="hidden" id="program_start_time" name="program_start_time" value="{code} if($program_start_time){ echo date('Y-m-d H:i:s', $program_start_time);}{/code}"/>
	<input type="hidden" id="program_end_time" name="program_end_time" value="{code} if($program_start_time){ echo date('Y-m-d H:i:s', ($program_start_time + $toff));}{/code}"/>
	<input type="hidden" id="week_d" name="week_d" value="{$week_days}"/>
</form>