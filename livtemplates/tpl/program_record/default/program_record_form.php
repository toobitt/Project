<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>
{template:head}
{js:2013/ajaxload_new}
{js:mms_default}
{js:upload}
{js:hg_sort_box}
{js:common/common_form}
{js:common}
{js:program_record/form}
{css:2013/form}
{css:common/common}
{css:record_form}
{if $a}
	{code}
		$action = $a;
		//hg_pre($formdata);
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
$(".m2o-save-as").click(function(){
	$("input[name='a']").val('create');
});
})
</script>
<form class="m2o-form" name="editform" action="" method="post" onsubmit="return hg_form_check();">
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{if $formdata['id']}编辑收录计划{else}新增收录计划{/if}</h1>
            <div class="m2o-m m2o-flex-one">
                <input placeholder="填写收录计划" name="title" id="titles" class="m2o-m-title" value="{$title}" />
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存" class="m2o-save" name="sub" id="sub" />
                <input type="submit" value="另存为" class="m2o-save-as submit" name="lcw" />
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>
    </header>
   <div class="m2o-inner">
    <div class="m2o-main m2o-flex">
        <aside class="m2o-l">
        	   <div class="m2o-item">
        	        <span class="title" style="width: 30px;">分类:</span>
					{code}
						$item_source = array(
							'class' => 'down_list',
							'show' => 'item_show',
							'width' => 135,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $item ? $item : -1;
						$program[$default] = '选择分类';
						foreach($program_item as $k =>$v)
						{
							$program[$v['id']] = $v['name'];
						}
					{/code}
					{template:form/search_source,item,$default,$program,$item_source}
					<span class="error" id="force_codec_tips" style="display:none;"></span>
   			   </div>
   			 
 <div class="m2o-item">
        	        <span class="title">服务器:</span>
					{code}
						$server_source = array(
							'class' => 'down_list',
							'show' => 'server_show',
							'width' => 100,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
						);
						$default = $server_id ? $server_id : 0;
						$server_item[$default] = '--选择--';
						foreach($server as $k =>$v)
						{
							$server_item[$v['id']] = $v['name'];
						}
					{/code}
					{template:form/search_source,server_id,$default,$server_item,$server_source}
					<br/>
					<span class="error" id="server_id_tips" style="display:none;"></span>
   			   </div>
   			   <div class="m2o-item">
   			        <span class="title">强制转码：</span>
   			        <div class="common-switch {if $force_codec}common-switch-on{/if}">
				       <div class="switch-item switch-left" data-number="0"></div>
				       <div class="switch-slide"></div>
				       <div class="switch-item switch-right" data-number="100"></div>
				    </div>
					<input type="radio" name="force_codec" value="1" {if $force_codec}checked{/if}/>
					<input type="radio" name="force_codec" value="0" {if !$force_codec}checked{/if}/>
					<span class="error" id="force_codec_tips" style="display:none;"></span>
   			   </div>
   			   <div class="m2o-item">
   			        <span class="title">拆条：</span>
   			        <div class="common-switch {if !$is_mark}common-switch-on{/if}">
				       <div class="switch-item switch-left" data-number="0"></div>
				       <div class="switch-slide"></div>
				       <div class="switch-item switch-right" data-number="100"></div>
				    </div>
					<input type="radio" name="is_mark" value="0" {if !$is_mark}checked{/if}/>
					<input type="radio" name="is_mark" value="1" {if $is_mark}checked{/if}/>
					<span class="error" id="mark_tips" style="display:none;"></span>
   			   </div>
   			    {code}
				$audit_auto = ($action == 'create') ? 1 :$audit_auto;
				{/code}
				{if $_user['group_type'] <= 3}
   			   <div class="m2o-item">
   			        <span class="title">审核通过：</span>
   			        <div class="common-switch {if $audit_auto}common-switch-on{/if}">
				       <div class="switch-item switch-left" data-number="0"></div>
				       <div class="switch-slide"></div>
				       <div class="switch-item switch-right" data-number="100"></div>
				    </div>
					<input type="radio" name="audit_auto" value="1" {if $audit_auto}checked{/if}/>
					<input type="radio" name="audit_auto" value="0" {if !$audit_auto}checked{/if}/>
					<span class="error" id="mark_tips" style="display:none;"></span>
   			   </div>
   			   {/if}
        	   <div class="m2o-item">
        	        <a class="common-publish-button overflow" href="javascript:;" _default="发布至: 无" _prev="发布至: ">发布至</a>
        	        {code}
					$formdata['column_id'] = $columnid;
					{/code}
					{template:unit/publish_for_form, 1, $columnid}
   			   </div>
        </aside>
        <section class="m2o-m m2o-flex-one">
               <div class="m2o-item channel-area">
                  <div class="channel-title">
                    <span class="title">频道：</span>
                    <span id="default_value" class="default_value" {if !$channel_id}style="display:none;"{/if}>当前选取：<a id="channel_name">{$channels[$channel_id]}</a></span>
               		<span class="m2o-channel-btn" id="show_span" onclick="hg_show_channel();">{if !$channel_id}选择频道{else}重新选择频道{/if}</span>
               		<span class="error" id="channel_tips" style="display:none;"></span>
               	  </div>
               	  <input id="channel_id" name="channel_id" value="{$channel_id}" type="hidden"/>
               	  <div class="channel-toggle">
               	  	  <div class="channel-list-box m2o-flex">
	               	       <div class="channel-list list">
	               	            <h3>频道列表</h3>
	               	            {if is_array($channel_info)}
								<ul>
									{foreach $channel_info as $key => $value}
									{if $value['status']==1}
									<li class="channel-item" data-id="{$value['id']}" data-plan="true"><span class="name">{$value['name']}</span>&nbsp;&nbsp;{if $value['status']}启动{else}未启动{/if}</li>
									{/if}
									{/foreach}
								</ul>
								{/if}
	               	       </div>
	               	       <div class="program-list list">
	               	       		<h3>节目列表</h3>
	               	       		<div class="program-box"><span style="color: red;opacity: 0.5;">暂无内容</span></div>
	               	       </div>
	               	       <div class="plan-list list">
	               	       		<h3>节目列表</h3>
	               	       		<div class="plan-box"><span style="color: red;opacity: 0.5;">暂无内容</span></div>
	               	       </div>
               	    </div>
               	  </div>
               </div>
               <div class="m2o-item">
               		<p class="clear" style="margin:0 0 10px 35px;">
						<span class="title"></span>
						<label><input class="n-h" type="checkbox" onclick="hg_plan_repeat(this);" {if count($week_day)}checked{/if}/><span>周期性节目</span></label>
					</p>
					<div id="week_date" class="clear" style="padding: 2px 0 15px 0px;" {if !count($week_day)}style="display:none;"{/if}>
						{code}
							$week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
						{/code}
						<span class="title">重复：</span>
							<label>
								<input class="n-h" type="checkbox" onclick="hg_plan_repeat(this,1);" id="every_day" name="every_day" {if count($week_day)==7}checked{/if}/><span>每天</span>
							</label>
						{foreach $week_day_arr as $key => $value}
							<label>
							<input onclick="hg_plan_repeat(this,2);" class="n-h" type="checkbox" name="week_day[]" id="week_day_{$key}" {if $week_day} {foreach $week_day as $k => $v}{if $v == $key}checked{/if}{/foreach}{/if} value="{$key}" /><span>{$value}</span>
							</label>
						{/foreach}
					</div>
					<div id="date_list" class="clear" style="background:url('{$RESOURCE_URL}dottedLine.png') repeat-x 0 top;padding-top: 15px;">
						<span class="title">日期：</span>
						{code}
						$type_source = array('other'=>' size="14" autocomplete="off" style="width:165px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;display:inline-block;border:none;" onblur="hg_plan_check_day();"','name'=>'dates','style'=>'width:190px;display:inline-block;','type'=>'yyyy-MM-dd','focus' => "$('[lang=zh-cn]').hide();",'other_focus' => "hg_plan_check_day()");
						$dates = $start_time ? date('Y-m-d',strtotime($start_time)) : date('Y-m-d');
						{/code}
						<input class="date-picker" name="dates" id="dates" value={$dates}  />
						<span class="error" id="date_tips" style="display:none;"></span>
					</div>
               </div>
               <div class="m2o-item">
                    <span class="title">时间：</span>
						{code}
						$default_start = $start_time ? date('H:i:s',strtotime($start_time)) : '';
						{/code}
					<div class="input" style="display:inline-block;width:110px;vertical-align:middle;">
						<span class="input_left"></span>
						<span class="input_right"></span>
						<span class="input_middle">
							<input type="text" name="start_time" id="start_times" value="{$default_start}" size="14" autocomplete="off" style="width:100px;height: 18px;font-size:12px;padding-left:5px;line-height: 20px;float: left;border:none;" onfocus="hg_plan_check_day();" onblur="hg_plan_toff();"/>
						</span>
					</div>
						<span style="padding-left:5px;">-</span> 
						{code}
						$default_end = $end_time ? date('H:i:s',strtotime($end_time)) : '';
						{/code}
					<div class="input" style="display:inline-block;margin-left:5px;width:110px;vertical-align:middle;">
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
        </section>
    </div>
   </div>
    <input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="is_del" id="is_del" value="0" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>

{template:foot}