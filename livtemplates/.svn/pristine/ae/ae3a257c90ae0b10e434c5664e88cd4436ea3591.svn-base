<?php 
/* $Id: program_list_week.php 5656 2011-12-10 09:30:02Z repheal $ */
?>
<script>
(function($){
	$.fn.mydate = function(){
		return this.each(function(){
			$(this).keydown(function(event){
				var keyCode = event.keyCode;
				if(keyCode == 8 || keyCode == 9)
				{
					return;
				}
				var val = $.trim($(this).val()).replace(':', '');
				if(val.length > 6){
					event.preventDefault();
					return false;	
				}
				if(val.length == 6){
					return;
				}
				val = val.split('');
				var tmp = '';
				$.each(val, function(i, n){
					tmp += n;
					if(i%2){
						tmp += ':';	
					}			
				});
				$(this).val(tmp);	
			});	
		});
	}
})(jQuery);

jQuery(function($){
$('#start_time').mydate();
$('#end_time').mydate();
})
</script>
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
	$default_start = $start_time ? date('H:i:s',$start_time) : '';
	{/code}
	<div class="input" style="padding-left: 4px;width:90px;float: left;">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="start_time" style="width:80px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;" id="start_time" value="{$default_start}"/>
		</span>
	</div>
	{code}
		$name_type = array('width' => 180,'style'=>'float:left;padding-left:10px;');
	{/code}
	{template:form/input,program_name,$program_name,,$name_type}
	{code}
		$default_end = $toff ? date('H:i:s',$start_time+$toff) : '';
	{/code}
	<div class="input" style="float: left;margin-left:5px;width:90px">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="end_time" style="width:80px;height: 18px;font-size:12px;padding-left:5px;line-height: 20px;float: left;" id="end_time" value="{$default_end}"/>
		</span>
	</div>
	<span class="lb-btn {if $item}cur{/if}"style="display:block;" onclick="hg_select_source();" id="source_btn"></span>
	{code}
	if($item)
	{
		$default = $item ? $item : -1;
		$item_source = array(
			'class' => 'down_lists',
			'show' => 'item_shows',
			'width' => 83,	
			'state' => 0, /*0--正常数据选择列表，1--日期选择*/
			'is_sub'=>1,
		);
		$program_item[-1] = '录播分类';
		foreach($program_item as $k =>$v)
		{
			$program_item[$k] = $v;
		}
		{/code}
	<script type="text/javascript">
		$("#plan_form").addClass('i');
	</script>
		{code}
	}
	else
	{
		{/code}
	<script type="text/javascript">
		$("#plan_form").removeClass('i');
	</script>
		{code}	
	}
	{/code}
	<div class="down_list" id="down_list" style="float:left;{if !$item}display:none;{/if}">{if $item}{template:form/search_source,item,$item,$program_item,$item_source}{/if}</div>
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
<input type="hidden" id="id" name="id"value="{$id}"/>
<input type="hidden" id="a" name="a" value="{$action}"/>
<input type="hidden" id="mid" name="mid" value="{$_INPUT['mid']}"/>
<input type="hidden" id="item_source" name="item_source"value="{if $item}1{else}0{/if}"/>
<input type="hidden" id="sys_record" name="sys_record"value="0"/>
<input type="hidden" id="channel_id" name="channel_id" value="{$_INPUT['channel_id']}"/>
</form>