<?php 
/* $Id: program_list_day.php 9559 2012-06-02 09:29:20Z lijiaying $ */
?>
{template:head}
{css:mms_style}
{css:tab_btn}
{js:jquery-ui-1.8.16.custom.min}
{js:program_day}
{js:programs}
{code}
	$programs = $program_list['program'];
	$date = $program_list['date'];
	$channel_info = $program_list['channel_info'];	

	if(!$_INPUT['channel_id'])
	{
		$_INPUT['channel_id'] = $channel_info['default']['id'];
	}
	unset($channel_info['default']);
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
	$(function(){
		var vid = $('#hg_channel').val();
		$('#channel_show_'+vid).css({'background':'#EFEFEF','border':'1px solid #CCC'});

   });
</script>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <div class="button_op">
	<a class="button_4" onclick="switch_channel();"><strong>切换频道</strong></a>
	<a class="button_6" onclick="showdiv();"><strong>上传节目单</strong></a>
	</div>
</div>
<div class="wrap_conter clear" style="margin:0;border-radius:0;min-width:952px;">
	<div id="channel_hid" class="channel_hid" style="{if !isset($_INPUT['nav'])}display:none;{/if}">
		<div class="hg_program_list"></div>
		<ul class="hg_program_ul">
		{foreach $channel_info as $key => $value}
			<li id="channel_show_{$value['id']}"  class="channel_logo f_l"><a href="run.php?mid={$_INPUT['mid']}&channel_id={$value['id']}{$_ext_link}">
			{if $value['logo_url']}<img style="width:113px;height:43px;" id="cha_logo" src="{$value['logo_url']}" alt="{$value['name']}" />{else}
			{$value['name']}
			{/if}</a></li>
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
			echo $channel_info[$channel_id]['name'].'&nbsp;&nbsp;'.date('Y/m/d',strtotime($date));
		{/code}</span>
	</h2>
	<form action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data" name="theme_form" id="theme_form">
	<div class="single" id="single_day">
		<ul id="single_day_ul">
			{template:unit/program_list_day_single}
		</ul>
			  <input type="hidden" value="update_day" name="a" />
			  <input type="hidden" value="{$channel_id}" name="channel_id" id="channel_id"/>
			  <input type="hidden" value="{$date}" name="dates"/>
			  <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</div></form>
	<div onclick="hg_clear_day();" style="cursor: pointer; position: absolute; width: 100%; height: 100%; top: 0pt; left: 0pt; display: block; z-index: 9; background: none repeat scroll 0% 0% rgb(0, 0, 0); opacity: 0;display:none;" id="show_bg"></div>
	<div class="single_date">
	{code}
		$date_source = array(
			'default' =>1,
			'class' => '',
			'id' => 'date_show',
			'extra_onclick' => "hg_href_single(" . $channel_id . ");",
		);
	{/code}
		{template:form/hg_date,dates,$date,,$date_source}
		<div class="btn_div">
			<input type="button" onclick="hg_update_single();" onmouseover="hg_move_focus();" class="button_4 {if !$have_plan}button_none{/if}" value="保存修改" id="save_edit"><input type="button" class="button_4" value="复制到" onclick="hg_copy_show('copy_date_show','dates');" style="margin-left: 17px;">
		</div>
	
	{code}
		$copy_source = array(
			'default' =>1,
			'class' => 'copy_date',
			'id' => 'copy_date_show',
			'extra_onclick' => 'hg_check_copy("copy_dates","dates");',
		);
	{/code}
	{template:form/hg_date,copy_dates,$date,,$copy_source}
	</div>
	<!--<div id="single"></div>-->
<!--节目单上传-->
	<!-- <div id="bg_upload" class="bg_upload"></div> -->
	<div id="show_upload" class="show_upload_1">
	  <span id="btnclose" class="btnclose"  onclick="hidediv();" style="float:right;margin-right:5px;cursor:pointer;">关闭</span>
		<form enctype="multipart/form-data" method="post" action="run.php?mid={$_INPUT['mid']}" id="upload_form" target="form_pos" name="upload_form" style="clear:both;">
			<span>上传节目单</span>
			<input type="file" name="program" id="upload_file"/>
			<input type="button" value="确定" name="sub" onclick="subform()" class="button_2"/>
			<input type="hidden" name="a" value="upload_program" />
			<input type="hidden" name="channel_id" value="{$channel_id}" id="channel_id"/>
		</form>
		<div style="margin-top:10px;line-height:22px;padding-left:8px;">
			<span>节目单格式:(暂支持txt格式，编码为UTF-8) <a href="./download.php?a=example" style="text-decoration: underline;">下载模板</a> </span>
			<ul style="margin-top:5px;">
				<li>{code} echo date("Y-m-d",TIMENOW);{/code}</li>
				<li>00:30:00-00:32:00,精彩节目</li>
				<li>00:33:00,精彩节目</li>
				<li>16:55:00-17:25:00,精彩节目,精彩节目的副标题</li>
			</ul>
			<ul>
				<li>{code} echo date("Y-m-d",TIMENOW+24*3600);{/code}</li>
				<li>00:30:00,精彩节目</li>
				<li>00:33:00,精彩节目</li>
				<li>06:25:00,精彩节目</li>
			</ul>
		</div>
	</div>
</div>
{template:foot}

