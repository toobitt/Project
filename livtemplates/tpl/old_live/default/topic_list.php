<?php 
/* $Id: topic_list.php 9977 2012-07-13 02:19:56Z zhoujiafei $ */
?>
{template:head}
{css:mms_style}
{css:tab_btn}
{js:topic}
{code}
	$topic = $topic_list['topic'];
	$date = $topic_list['date'];
	$channel_info = $topic_list['channel_info'];	

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
	</div>
</div>
<div class="wrap_conter clear" style="margin:0;border-radius:0;min-width:952px;">
	<div id="channel_hid" class="channel_hid" style="{if !isset($_INPUT['nav'])}display:none;{/if}">
		<div class="hg_program_list"></div>
		<ul class="hg_program_ul">
		{foreach $channel_info as $key => $value}
			<li id="channel_show_{$value['id']}"  class="channel_logo f_l"><a href="run.php?mid={$_INPUT['mid']}&channel_id={$value['id']}{$_ext_link}">
			{if $value['larger']}<img style="width:113px;height:43px;" id="cha_logo" src="{$value['larger']}" alt="{$value['name']}" />{else}
			{$value['name']}
			{/if}</a></li>
			<input id="cha_{$value['id']}" type="hidden" name="channel_id" value="{$value['id']}" />
		{/foreach}
		</ul>
		<input id="hg_channel" type="hidden" name="hg_channel" value="{$_INPUT['channel_id']}" />
	</div>
	<h2 class="title_bg">
	{code}

	/*$source = array('extra' => '&channel_id='.$_INPUT['channel_id']);
		{template:menu/btn_menu,'','','',$source}
	*/
	{/code}
		<span class="channel_name">
		{code}
			echo $channel_info[$channel_id]['name'].'&nbsp;&nbsp;'.date('Y/m/d',strtotime($date));
		{/code}</span>
	</h2>
	<form action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data" name="theme_form" id="theme_form">
	<div class="single" id="single_day">
		<ul id="single_day_ul">
			{template:unit/topic_list_single}
		</ul>
			  <input type="hidden" value="update_topic" name="a" />
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
			<input type="button" onclick="hg_update_single();" onmouseover="hg_move_focus();" class="button_4 {if !$have_plan}button_none{/if}" value="保存修改" id="save_edit">
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
</div>
{template:foot}
