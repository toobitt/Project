{template:head}
{css:tab_btn}
{css:mms_style}
{css:mark_style}
{css:change_plan}
{js:change_plan}
{js:upload}

<script language="javascript" type="text/javascript">	

</script>
<div class="wrap_conter clear" style="margin:0;border-radius:0;min-width:952px;">
	<h2 class="title_bg">
	{code}
	$source = array('extra' => '&channel_id='.$_INPUT['channel_id']);
	{/code}
		{template:menu/btn_menu,'','','',$source}
		<span class="channel_name">
			{$plan['channel_name']}&nbsp;串联单计划
		</span>
		<input id="hg_channel" type="hidden" name="hg_channel" value="{$_INPUT['channel_id']}" />
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
	{template:unit/change_plan_list}
	</table>	
<div id="plan_form" class="plan_form clear" style="display:none;"></div>
</div>

<div onclick="hg_submit_plan();" class="show_bg" id="show_bg"></div>
{template:foot}

