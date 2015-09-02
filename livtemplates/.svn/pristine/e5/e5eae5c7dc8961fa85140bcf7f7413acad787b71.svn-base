<?php 
/* $Id: program_list.php 8049 2012-02-13 08:43:22Z repheal $ */
?>
{template:head}
{css:tab_btn}
{js:mms_default}
{js:programs}
{code}
	$programs = $program_list['program'];
	$date = $program_list['date'];
	$channel_info = $program_list['channel_info'];
	$channel_id = $program_list['info']['id'];

	if(!$_INPUT['channel_id'])
	{
		$_INPUT['channel_id'] = 1;
	}
{/code}
<script language="javascript" type="text/javascript">
		function disp_confirm()
		{
			var name=confirm("确定复制上一周节目？当前所有节目将被清空");
			if (name==true)
			{
				window.location.href="run.php?mid={$_INPUT['mid']}&a=copy";
			}

		}
		function record_show() { 
				
				if($("#record_info").css("top")=="20px")
				{
					p_day_ul_show();
				}
				else
				{
					$("#screen_info_title").hide();
					$("#record_info_title").show();
					$("#record_info").animate({"top":"20px"});
					$("#screen_info").animate({"top":"320px"});
				}
          }
		  function screen_show() {  
				if($("#screen_info").css("top")=="20px")
				{
					p_day_ul_show();
				}
				else
				{
					$("#record_info_title").hide();
					$("#screen_info_title").show();
					$("#record_info").animate({"top":"320px"});
					$("#screen_info").animate({"top":"20px"});
				}
          }
		  function p_day_ul_show(){
			$("#record_info").animate({"top":"320px"});
			$("#screen_info").animate({"top":"320px"});
			$("#record_info_title").hide();
			$("#screen_info_title").hide();
		  }
		  function close_all(){
			$("#p_day_ul").hide();
			$("#record_info").hide();
			$("#screen_info").hide();
		  }
          function showdiv() {            
              $("#bg_upload").show();
              $("#show_upload")show();
          }
         function hidediv() {
             $("#bg_upload").hide();
             $("#show_upload").hide();
         }
		
	$(function(){
		var vid = $('#hg_channel').val();
		$('#channel_show_'+vid).css('border','1px solid #939393');

   });
</script>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <div class="button_op">
	<a class="button_4" onclick="showdiv();"><strong>上传节目单</strong></a>
	</div>
</div>
<div class="wrap_conter clear" style="margin:0;border-radius:0;min-width:952px;">
	<div id="channel_hid" class="channel_hid" style="display:block;">
		<div class="hg_program_list"></div>
		<ul class="hg_program_ul">
		{foreach $channel_info as $key => $value}
			<li id="channel_show_{$value['id']}"  class="channel_logo f_l"><a href="run.php?mid={$_INPUT['mid']}&channel_id={$value['id']}{$_ext_link}"><img style="width:113px;height:43px;" id="cha_logo" src="{$value['larger']}"/></a></li>
			<input id="cha_{$value['id']}" type="hidden" name="channel_id" value="{$value['id']}" />
		{/foreach}
		</ul>
		<input id="hg_channel" type="hidden" name="hg_channel" value="{$_INPUT['channel_id']}" />
	</div>
	<h2 class="title_bg">
		{template:menu/btn_menu}
		江苏卫视节目单 &nbsp;2011/6/3&nbsp;-&nbsp;6/10
	</h2>
	<div class="program clear">
	<!--
		<div class="p_time">
			<div class="channel">
				<ul class="channel_con">
					<li class="channel_con_name">
						<span style="float:left;position:relative;top:5px;width:28px;height:28px;background:url({$program_list['info']['small']}) 0px 2px no-repeat;"></span>
						<span class="channel_con_name_span">{$program_list['info']['name']}</span>
					</li>
				
					<li class="channel_con_time">
						<span>{$program_list['info']['start_time']}</span>&nbsp;&nbsp;至&nbsp;&nbsp;<span>{$program_list['info']['end_time']}</span>
					</li>
					<li id="record_info_title" class="channel_con_time" style="display:none">(自动录播）</li>
					<li id="screen_info_title" class="channel_con_time" style="display:none">(屏蔽节目)</li>
				</ul>
			</div>
			<div class="weeks">
				<ul class="weeks_con">
					<li class="con_l_img">
						<a href="run.php?mid={$_INPUT['mid']}&channel_id={$program_list['info']['id']}&weeks={$program_list['info']['weeks']}&pre=1{$_ext_link}">
							<div style="margin-top:12px;width:10px;height:10px;background:url({code} echo RESOURCE_URL.'program_pre.png';{/code}) 2px 0px no-repeat;"></div>
						</a>
					</li>
					<li class="con_l_weeks"><a href="run.php?mid={$_INPUT['mid']}&channel_id={$program_list['info']['id']}&weeks={$program_list['info']['weeks']}&pre=1{$_ext_link}">上一周</a></li>
				
					<li class="con_mid"><span class="con_mid_span">{$program_list['info']['start_times']}-{$program_list['info']['end_times']}</span></li>
					<li class="con_r_weeks"><a href="run.php?mid={$_INPUT['mid']}&channel_id={$program_list['info']['id']}&weeks={$program_list['info']['weeks']}&next=1{$_ext_link}">下一周</a></li>
					<li class="con_r_img">
						<a href="run.php?mid={$_INPUT['mid']}&channel_id={$program_list['info']['id']}&weeks={$program_list['info']['weeks']}&next=1{$_ext_link}">
							<div style="margin-top:12px;width:10px;height:10px;background:url({code} echo RESOURCE_URL.'bg-all.png';{/code}) -100px -8px;"></div>
						</a>
					</li>
				</ul>
			</div>
			<div class="copy">
				<a href="javascript:void(0);" style="color:red" onclick="disp_confirm();">复制上周</a>
				<a onclick="record_show();" href="javascript:void(0);">录播节目</a>
				<a onclick="screen_show();"  href="javascript:void(0);">屏蔽节目</a>
			</div>
		</div>
	-->
		<ul class="p_week_ul">
			<li class="con_li_space"></li>
			{foreach $date as $key => $value}
			<li class="p_week_li">{$value['d']}</li>
			{/foreach}
		</ul>
		<div class="p_item" id="hg_p_item">
			<div class="p_day">
				<ul class="con_ul" style="clear:both">
				<li class="con_li" style="background:#FFF7D8;padding-left:3px;">0:00</li>
				{for $i = 1;$i<24;$i++}
					<li class="con_li">{$i}:00</li>
				{/for}
				</ul>
				<!-- 节目单 -->
				<ul class="day_con" id="p_day_ul" style="top:20px;position:absolute">
				{code}
					for($i = 0;$i < 7;$i++)
					{
						$toff = 0;
						$day_id = strtotime($date[$i]['w'].' 00:00:00');
					{/code}
					
				<li class="day_con_li" id="day_{$i}">
					{template:unit/program_item}
				</li>
				
				{code}
					}
				{/code}
				</ul>
				<!-- 录播节目单 -->
				<ul class="day_con" id="record_info"  style="top:320px;left:19px;position:absolute;" onclick="p_day_ul_show();">
				{code}
					for($i = 0;$i < 7;$i++)
					{
						$toff = 0;
						$day_id = strtotime($date[$i]['w'].' 00:00:00');
					{/code}
					
				<li class="day_con_li" id="day_{$i}">
					<ul class="day_con_ul"  unselectable="on" onselectstart="return false;">
						{foreach $program_record_list['record'][$i] as $key => $val}
							<li id="pro_{$val['id']}" {if $val['theme']}style="background:#e57c37;filter: alpha(opacity=50);-moz-opacity: 0.5;opacity: 0.5"{/if}>
							<div style="width:{code} echo ceil((180/3600)*$val['toff']);{/code}px;">
							<div class="bor_item">
								{if $val['theme']}录播节目{/if}
							</div>
							</div>
							</li>
						{/foreach}
					</ul>
				</li>
				
				{code}
					}
				{/code}
				</ul>
				<!-- 屏蔽节目单 -->
				<ul class="day_con" id="screen_info" style="top:320px;left:19px;position:absolute;" onclick="p_day_ul_show();">
				{code}
					for($i = 0;$i < 7;$i++)
					{
						$toff = 0;
						$day_id = strtotime($date[$i]['w'].' 00:00:00');
					{/code}
				<li class="day_con_li" id="day_{$i}">
					<ul class="day_con_ul"  unselectable="on" onselectstart="return false;">
						{foreach $program_screen_list['screen'][$i] as $key => $val}
						
							<li id="pro_{$val['id']}" {if $val['theme']}style="background:#757575;filter: alpha(opacity=50);-moz-opacity: 0.5;opacity: 0.5;"{/if}>
							<div style="width:{code} echo ceil((180/3600)*$val['toff']);{/code}px;">
							<div class="bor_item">
								{if $val['theme']}屏蔽节目{/if}
							</div>
							</div>
							</li>
						
						{/foreach}
					</ul>
							
				</li>
				{code}
					}
				{/code}
				</ul>
				<div id="all_bg" style="cursor: pointer;position:absolute;width:100%;height:314px;top:0;left:0;display:none;z-index:90;background:#000;opacity:0.05;filter:alpha(opacity=5);" onclick="hid();"><input type= "hidden" value="0" id="item_num"></div>
			</div>
			
		</div>
	</div>
</div>
<!--节目单上传-->
<div id="bg_upload" class="bg_upload"></div>
<div id="show_upload" class="show_upload">
  <span id="btnclose" class="btnclose"  onclick="hidediv();" style="float:right;margin-right:5px;cursor:pointer;">关闭</span>
	<form enctype="multipart/form-data" method="post" action="run.php?mid={$_INPUT['mid']}" id="upload_form" target="form_pos" name="upload_form" style="clear:both;">
		<span>上传节目单</span>
		<input type="file" name="program" id="upload_file"/>
		<input type="button" value="确定" name="sub" onclick="subform()" class="button_2"/>
		<input type="hidden" name="a" value="upload_program" />
		<input type="hidden" name="channel_id" value="{$channel_id}" id="channel_id"/>
	</form>
	</div>
</div>
{template:foot}

