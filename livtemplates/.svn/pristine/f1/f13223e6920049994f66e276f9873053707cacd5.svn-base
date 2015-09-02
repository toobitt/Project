<?php 
/* $Id: program_list_week.php 5656 2011-12-10 09:30:02Z repheal $ */
?>
{template:head}
{css:tab_btn}
{css:program_plan}
{js:program_plan}

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
	<div id="channel_hid" class="channel_hid" style="display:none;">
		<div class="hg_program_list"></div>
		<ul class="hg_program_ul">
		{foreach $channel_info as $key => $value}
		{if $value['id']==$_INPUT['channel_id']}
			{code}
				$channel_name = $value['name'];
			{/code}			
		{/if}
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
			echo $channel_name.'&nbsp;&nbsp;节目计划';
		{/code}</span>
	</h2>
	<table width="98%" border="0" cellpadding="0" cellspacing="0" class="channel_table">
	  <tr class="title" align="left" valign="top">
		<th>星期一</th>
		<th>星期二</th>
		<th>星期三</th>
		<th>星期四</th>
		<th>星期五</th>
		<th>星期六</th>
		<th>星期日</th>
	  </tr>
	{template:unit/program_plan_list}
	</table>	
<div id="plan_form" class="plan_form clear" style="display:none;"></div>
</div>

<div onclick="hg_submit_plan();" class="show_bg" id="show_bg"></div>
{template:foot}

