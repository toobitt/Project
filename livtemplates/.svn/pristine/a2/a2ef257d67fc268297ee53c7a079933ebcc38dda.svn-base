<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>
{template:head}
{css:ad_style}
{js:mms_default}
{js:upload}
{js:column_node}
{css:column_node}

{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<script type="text/javascript">
	var record_start = {code} echo $start_time ? strtotime($start_time) : 0{/code};	
	var record_end = {code} echo $end_time ? strtotime($end_time) : 0{/code};
</script>
{code}
$channels = array();
foreach($channel_info as $k => $v)
{
	$channels[$v['id']] = $v['name'];
}
{/code}
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
$('#start_times').mydate();
$('#end_times').mydate();
})
</script>
	<div class="ad_middle">
	<form name="editform" action="" method="post" class="ad_form h_l" onsubmit="return hg_form_check();">
		<h2>{$optext}收录计划</h2>
		<ul class="form_ul">
		{if $program_id || $plan_id}
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">频道：</span>
						<span class="channel_btn" id="show_span" style="cursor:default;"><a id="channel_name" onclick="hg_show_record_list();" style="color:#fff;">{$channels[$channel_id]}</a></span><input id="channel_id" name="channel_id" value="{$channel_id}" type="hidden"/>
						<span class="error" id="channel_tips" style="display:none;"></span>
						<a title="来源于节目单、节目单计划的录制无法修改" class="record_help">?</a>
					</div>								
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">标题：</span>
						<div class="input " style="width:110px;float: left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="title" id="title" size="14" disabled="disabled" style="width:100px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" value="{$title}"></span>
						</div>
						<span class="error" id="channel_tips" style="display:none;"></span>
						<a title="来源于节目单、节目单计划的录制无法修改" class="record_help">?</a>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<p class="clear" style="margin-bottom:10px;">
						<span class="title"></span>
						<label><input disabled="disabled" class="n-h" type="checkbox" {if count($week_day)}checked{/if}/><span>周期性节目</span></label>
					</p>
					<div id="week_date" class="clear" {if !count($week_day)}style="display:none;"{/if}>
						{code}
							$week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
						{/code}
						<span class="title">重复：</span>
							<label>
								<input class="n-h" type="checkbox" disabled="disabled" id="every_day" name="every_day" {if count($week_day)==7}checked{/if}/><span>每天</span>
							</label>
						{foreach $week_day_arr as $key => $value}
							<label>
							<input disabled="disabled" class="n-h" type="checkbox" name="week_day[]" id="week_day_{$key}" {foreach $week_day as $k => $v}{if $v == $key}checked{/if}{/foreach} value="{$key}" /><span>{$value}</span>
							</label>
						{/foreach}
					<a title="来源于节目单、节目单计划的录制无法修改" class="record_help">?</a>
					</div>
					<div id="date_list" class="clear" {if count($week_day)}style="display:none;"{else} style="background:url('{$RESOURCE_URL}dottedLine.png') repeat-x 0 top;padding-top: 15px;"{/if}>
						<span class="title">日期：</span>
						{code}
						$type_source = array('other'=>' size="14" autocomplete="off" disabled="disabled" style="width:165px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" onblur="hg_plan_check_day();"','name'=>'dates','style'=>'width:190px;float: left;','type'=>'yyyy-MM-dd','focus' => "$('[lang=zh-cn]').hide();",'other_focus' => "hg_plan_check_day()");
						$dates = $start_time ? date('Y-m-d',strtotime($start_time)) : date('Y-m-d');
						{/code}
						{template:form/wdatePicker,dates,$dates,'',$type_source}
						<span class="error" id="date_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">时间：</span>
						{code}
						$default_start = $start_time ? date('H:i:s',strtotime($start_time)) : '';
						{/code}
					<div class="input" style="float: left;margin-left:5px;width:110px">
						<span class="input_left"></span>
						<span class="input_right"></span>
						<span class="input_middle">
							<input type="text" name="start_time" id="start_times" disabled="disabled" value="{$default_start}" size="14" autocomplete="off" style="width:100px;height: 18px;font-size:12px;padding-left:5px;line-height: 20px;float: left;border:none;"/>
						</span>
					</div>
						<span class="time-h-k">-</span> 
						{code}
						$default_end = $end_time ? date('H:i:s',strtotime($end_time)) : '';
						{/code}
					<div class="input" style="float: left;margin-left:5px;width:110px">
						<span class="input_left"></span>
						<span class="input_right"></span>
						<span class="input_middle">
							<input type="text" name="end_time" id="end_times" disabled="disabled" value="{$default_end}" size="14" autocomplete="off" style="width:100px;height: 18px;font-size:12px;padding-left:5px;line-height: 20px;float: left;border:none;"/>
						</span>
					</div>
					<span id="toff" style="padding-left:10px;line-height:24px;">{$toff_decode}</span>
					<span style="padding-left:10px;line-height:24px;"></span>
					<span class="error" id="day_tips" style="display:none;"></span>
					<a title="来源于节目单、节目单计划的录制无法修改" class="record_help">?</a>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">分类：</span>
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'item_show',
							'width' => 100,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $item ? $item : -1;
						$program[$default] = '选择分类';
						foreach($program_item as $k =>$v)
						{
							$program[$k] = $v;
						}
					{/code}					
					<div class="down_list" style="width:100px">
						<span class="input_left"></span>
						<span class="input_right"></span>
						<span class="input_middle"><a><em></em><label id="display_item_show" class="overflow">{code} echo $program[$default];{/code}</label></a></span>
					</div>
					<input type="hidden" name="item" id="item" value="{$default}">
					<span class="error" id="item_tips" style="display:none;"></span>
					<a title="来源于节目单、节目单计划的录制无法修改" class="record_help">?</a>
				</div>
			</li>
		{else}
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">频道：</span>
						<span class="channel_btn" id="show_span" onclick="hg_show_channel();">{if !$channel_id}选择频道{else}重新选择频道{/if}</span><span id="default_value" class="default_value" {if !$channel_id}style="display:none;"{/if}>当前选取：<a id="channel_name" onclick="hg_show_record_list();">{$channels[$channel_id]}</a></span><input id="channel_id" name="channel_id" value="{$channel_id}" type="hidden"/>
						<span class="error" id="channel_tips" style="display:none;"></span>
					</div>
					<div class="channel_list clear" id="channel_list" style="display:none;">
						{if is_array($channel_info)}
						<ul>
							{foreach $channel_info as $key => $value}
							<li class="overflow" onclick="hg_plan_channel(this,{$value['id']},{$value['save_time']});"><span>{$value['name']}</span>&nbsp;&nbsp;{if $value['stream_state']}启动{else}未启动{/if}</li>
							{/foreach}
						</ul>
						{/if}
					</div>
					
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">标题：</span>
						<div class="input " style="width:110px;float: left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="title" id="title" size="14" style="width:100px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" value="{$title}"></span>
						</div>
						<span class="error" id="channel_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
				
			<li class="i">
				<div class="form_ul_div clear">
					<p class="clear" style="margin-bottom:10px;">
						<span class="title"></span>
						<label><input class="n-h" type="checkbox" onclick="hg_plan_repeat(this);" {if count($week_day)}checked{/if}/><span>周期性节目</span></label>
					</p>
					<div id="week_date" class="clear" {if !count($week_day)}style="display:none;"{/if}>
						{code}
							$week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
						{/code}
						<span class="title">重复：</span>
							<label>
								<input class="n-h" type="checkbox" onclick="hg_plan_repeat(this,1);" id="every_day" name="every_day" {if count($week_day)==7}checked{/if}/><span>每天</span>
							</label>
						{foreach $week_day_arr as $key => $value}
							<label>
							<input onclick="hg_plan_repeat(this,2);" class="n-h" type="checkbox" name="week_day[]" id="week_day_{$key}" {foreach $week_day as $k => $v}{if $v == $key}checked{/if}{/foreach} value="{$key}" /><span>{$value}</span>
							</label>
						{/foreach}
					</div>
					<div id="date_list" class="clear" {if count($week_day)}style="display:none;"{else} style="background:url('{$RESOURCE_URL}dottedLine.png') repeat-x 0 top;padding-top: 15px;"{/if}>
						<span class="title">日期：</span>
						{code}
						$type_source = array('other'=>' size="14" autocomplete="off" style="width:165px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" onblur="hg_plan_check_day();"','name'=>'dates','style'=>'width:190px;float: left;','type'=>'yyyy-MM-dd','focus' => "$('[lang=zh-cn]').hide();",'other_focus' => "hg_plan_check_day()");
						$dates = $start_time ? date('Y-m-d',strtotime($start_time)) : date('Y-m-d');
						{/code}
						{template:form/wdatePicker,dates,$dates,'',$type_source}
						<span class="error" id="date_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				
				<div class="form_ul_div clear">
					<span class="title">时间：</span>
						{code}
						$default_start = $start_time ? date('H:i:s',strtotime($start_time)) : '';
						{/code}
					<div class="input" style="float: left;margin-left:5px;width:110px">
						<span class="input_left"></span>
						<span class="input_right"></span>
						<span class="input_middle">
							<input type="text" name="start_time" id="start_times" value="{$default_start}" size="14" autocomplete="off" style="width:100px;height: 18px;font-size:12px;padding-left:5px;line-height: 20px;float: left;border:none;" onfocus="hg_plan_check_day();" onblur="hg_plan_toff();"/>
						</span>
					</div>
						<span class="time-h-k">-</span> 
						{code}
						$default_end = $end_time ? date('H:i:s',strtotime($end_time)) : '';
						{/code}
					<div class="input" style="float: left;margin-left:5px;width:110px">
						<span class="input_left"></span>
						<span class="input_right"></span>
						<span class="input_middle">
							<input type="text" name="end_time" id="end_times" value="{$default_end}" size="14" autocomplete="off" style="width:100px;height: 18px;font-size:12px;padding-left:5px;line-height: 20px;float: left;border:none;" onfocus="hg_plan_check_day();" onblur="hg_plan_toff();"/>
						</span>
					</div>
					<span id="toff" style="padding-left:10px;line-height:24px;">{$toff_decode}</span>
					<span style="padding-left:10px;line-height:24px;"></span>
					<span class="error" id="day_tips" style="display:none;"></span>
				</div>
			</li>			
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">分类：</span>
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'item_show',
							'width' => 100,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $item ? $item : -1;
						$program[$default] = '选择分类';
						foreach($program_item as $k =>$v)
						{
							$program[$k] = $v;
						}
					{/code}
					{template:form/search_source,item,$default,$program,$item_source}
					<span class="error" id="item_tips" style="display:none;"></span>
				</div>
			</li>			
			{/if}
			
			
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">强制转码：</span>
						<div class="input " style="width:110px;float: left;">
							<input type="radio" name="force_codec" value="1" {if $force_codec}checked{/if}/>是
							<input type="radio" name="force_codec" value="0" {if !$force_codec}checked{/if}/>否
						</div>
						<span class="error" id="force_codec_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">标注：</span>
						<div class="input " style="width:110px;float: left;">
							<input type="radio" name="is_mark" value="0" {if !$is_mark}checked{/if}/>是
							<input type="radio" name="is_mark" value="1" {if $is_mark}checked{/if}/>否
						</div>
						<span class="error" id="mark_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			{code}
			$audit_auto = ($action == 'create') ? 1 :$audit_auto;
			{/code}
			<!----><li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">审核通过：</span>
						<div class="input " style="width:110px;float: left;">
							<input type="radio" name="audit_auto" value="1" {if $audit_auto}checked{/if}/>是
							<input type="radio" name="audit_auto" value="0" {if !$audit_auto}checked{/if}/>否
						</div>
						<span class="error" id="mark_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li>
			<div class="form_ul_div">
			  <span class="title">发布至：</span>
			{code}
                  $hg_attr['multiple'] = 1;
				  $hg_attr['multiple_site'] = 1;
				  $default = $formdata['haspub'];
             	{/code}
             {template:unit/publish, 1, $formdata['columnid']}
             <script>
             jQuery(function($){
                $('#publish-1').css('margin', '10px auto').commonPublish({
                    column : 3,
                    maxcolumn : 3,
                    height : 224,
                    absolute : false
                });
             });
             </script>
             </div>
			</li>
		</ul>
	<input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="is_del" id="is_del" value="0" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
	</form>
	</div>
	<div class="right_version" style="width:290px;">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		{if $program_id || $plan_id}
		<div class="help-info">
		    <span class="flag">?</span>
		    <span class="dscr">表示:来源于节目单、节目单计划的录制无法修改</span>
		</div>
		{/if}
	</div>
{template:foot}