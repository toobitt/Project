<?php 
/* $Id: program_list_week.php 8051 2012-02-13 08:46:17Z repheal $ */
?>
{template:head}
{css:tab_btn}
{js:mms_default}
{js:programs}
{js:program_week}
{code}
	$programs = $program_list['program'];
	$date = $program_list['date'];
	
	$channel_info = $program_list['channel_info'];
	if(!$_INPUT['channel_id'])
	{
		$_INPUT['channel_id'] = 1;
	}
	$channel_id = $_INPUT['channel_id'];
{/code}
<script language="javascript" type="text/javascript">	

	function switch_channel()
	{
		$("#channel_hid").slideToggle();
	}
	function showdiv() {            
		$("#bg_upload").show();
		$("#show_upload").show();
	}
	function hidediv() {
		$("#bg_upload").hide();
		$("#show_upload").hide();
	}

</script>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <div class="button_op">
	<a class="button_4" onclick="switch_channel();"><strong>切换频道</strong></a>
	<a class="button_4" onclick="showdiv();"><strong>上传节目单</strong></a>
	</div>
</div>
<div class="wrap_conter clear" style="margin:0;border-radius:0;min-width:952px;">
	<div id="channel_hid" class="channel_hid" style="display:none;">
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
	{code}
	$source = array('extra' => '&channel_id='.$_INPUT['channel_id']);
	{/code}
		{template:menu/btn_menu,'','','',$source}
		<span class="channel_name">
		{code}
			echo $channel_info[$channel_id]['name'].'&nbsp;&nbsp;';
			if(date("Y",strtotime($date[1]['w'])) ==  date("Y",strtotime($date[7]['w'])))
			{
				echo date("Y/m/d",strtotime($date[1]['w'])) . '-' . date("m/d",strtotime($date[7]['w']));
			}
			else
			{
				echo date("Y/m/d",strtotime($date[1]['w'])) . '-' . date("Y/m/d",strtotime($date[7]['w']));
			}
			$date_source = array(
				'sort' =>1,
				'class' => 'date_div',
				'id' => 'date_show',
				'extra_onclick' => 'switch_date',
			);
		{/code}</span>
		<span class="date_btn" onclick="switch_date_show('date_show');"></span>
		{template:form/hg_date,dates,$date[1]['w'],,$date_source}
	</h2>
	<div onclick="switch_date_bg('date_show');" style="cursor: pointer; position: absolute; width: 100%; height: 100%; top: 0pt; left: 0pt; display: block; z-index: 9; background: none repeat scroll 0% 0% rgb(0, 0, 0); opacity: 0;display:none;" id="date_show_bg"></div>
	<div class="program clear">
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
				{for $j = 1;$j<24;$j++}
					<li class="con_li">{$j}:00</li>
				{/for}
				</ul>
				<!-- 节目单 -->
				<ul class="day_con" id="p_day_ul" style="top:20px;position:absolute">
				{code}
					for($i = 1;$i < 8;$i++)
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
		<input type="hidden" name="channel_id" value="{$_INPUT['channel_id']}" id="channel_id"/>
	</form>
	</div>
</div>
{template:foot}

