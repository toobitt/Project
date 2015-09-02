<?php 
/* $Id: program_record_list_list.php 5784 2011-12-13 06:38:33Z repheal $ */
?>
{template:head}
{js:channels}
<script type="text/javascript">
	
	
	function hg_program_record_log(id,record_id)
	{
		if(confirm('是否删除？！'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete_log&id=' + id + '&record_id=' + record_id + '&infrm=1';
			hg_request_to(url);
		}
	}
	
	function hg_call_delete_log(html,id)
	{
		$("#log_"+id).remove();
	}
</script>
<style>
span.yes,span.no{background:url('{$RESOURCE_URL}bg-all.png') -2px -164px  no-repeat;height:15px;width:15px;display:inline-block;margin:6px 0 0 0}
span.yes{background-position:-121px  -164px;}
span.no{background-position:-121px  -197px;}
</style>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
<div class="form_tit common-list-search" id="info_list_search">
{template:menu/btn_menu}
<form name="searchform" id="searchform" action="" method="get" class="hg_pr_l">
<div class="right_1">
	<!---->{code}
		$attr_source = array(
			'class' => 'transcoding down_list',
			'show' => 'transcoding_show',
			'width' => 104,/*列表宽度*/		
			'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		);
		$_INPUT['channel_id'] = $_INPUT['channel_id']?$_INPUT['channel_id']:-1;
		$attr_date = array(
			'class' => 'colonm down_list data_time',
			'show' => 'colonm_show',
			'width' => 104,/*列表宽度*/
			'state' => 1,/*0--正常数据选择列表，1--日期选择*/
		);
		$channel[-1] = '所有频道';
		foreach($channel_info as $k =>$v)
		{
			$channel[$v['id']] = $v['name'];
		}
		$_INPUT['date_search'] = $_INPUT['date_search']?$_INPUT['date_search']:1;
	{/code}
	{template:form/search_source,channel_id,$_INPUT['channel_id'],$channel,$attr_source}
	{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
	<input type="hidden" name="a" value="show" />
	<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
	<input type="hidden" name="record_id" value="{$_INPUT['record_id']}"/>
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}"/>
</div>
<!--<div class="right_2">
	<div class="button_search">
		<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
	</div>
	{template:form/search_input,k,$_INPUT['k']}            
</div>-->
</form>
</div>
<div class="clear"></div>
<table cellspacing="0" cellpadding="0" width="100%" class="hg_tab form_table">
	<tr id="item_th"   class="h" align="left" valign="middle">
		<!--<th align="center" width="30"></th>-->
		<th align="center" width="50">状态</th>
		<th width="30"></th>
		<th class="text_indent">收录节目名称</th>
		<th width="150">进度</th>
		<th width="70">操作</th>
		<th width="105" class="text_indent">执行时间</th>
	</tr>	
	<tbody id="status">
	{if $list}
       {foreach $list as $k => $v}
		<tr id="log_{$v['id']}" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" align="left" valign="middle">
			<td align="center">
				{if $v['state']==1}
					<span class="yes"></span>
				{else}
					<span class="no"></span>
				{/if}
			</td>
			<td>{code} echo $v['auto']?'自动':'手动';{/code}</td>
			<td class="text_indent">{$v['operation']}</td>
{if $v['isError']}
	{code} 
		$color = 'red';
		$precent = 100;
	{/code}
{else}
	{if $v['has_completed'] <= $v['toff']}
		{code}
			$color = 'red'; 
			$precent = round($v['has_completed']/$v['toff'],4)*100 <= 0 ? 0 : round($v['has_completed']/$v['toff'],4)*100; 
		{/code}
	{else}
		{code} 
			$color = '#94C100';
			$precent = 100;
		{/code}
	{/if}
{/if}<td width="150"><div style="border: 1px solid {$color};width: 100px;height: 6px;float: left;margin-top: 7px;"><div style="background-color:{$color};width:{$precent}%;height: 6px;"></div></div>&nbsp;&nbsp;{$precent}%</td>
			<td width="70">{if $precent <= 0}<a href="javascript:void(0);" onclick="hg_program_record_log({$v['id']},{$v['record_id']});">删除</a>{else}暂无{/if}</td>
			<td class="text_indent">{$v['dates']}</td>
		</tr>
		{/foreach}
	{/if}
	</tbody>
</table>
<div class="live_delete">
<!--<input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall" class="n-h">
<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" >删除</a>-->
</div>
<div>{$pagelink}</div>
</form>
{template:foot}