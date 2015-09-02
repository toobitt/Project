<?php 
/* $Id: program_record_list.php 1344 2011-10-13 01:26:04Z lijiaying $ */
?>
{template:head}
{css:tab_btn}
{js:channels}
<script type="text/javascript">
function hg_plan_del(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
}
function hg_call_plan_del(data)
{
	var ids = data.split(",");
	for(i=0;i<ids.length;i++)
	{
		$("#plan"+ids[i]).slideUp(1000).remove();
	}
	if($("#checkall").attr('checked'))
	{
		$("#checkall").removeAttr('checked');
	}
}
</script>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增收录计划</strong></a>
</div>
<div class="form_tit">
{template:menu/btn_menu}
	<form name="searchform" id="searchform" action="" method="get" class="hg_pr_l">
	{code}
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
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
</div>
<div class="clear"></div>

<form name="listform" id="listform" action="" method="get" >
<table cellspacing="0" cellpadding="0" width="100%" class="hg_tab form_table">
	<tr id="item_th"  class="h" align="left" valign="middle">
		<th width="30" align="center"></th>
		<th class="text_indent" width="150">收录频道</th>
		<th class="text_indent" width="150">周期/日期</th>
		<th width="155" class="text_indent">起止时间</th>
		<th width="60" align="center"></th>
		<th width="150">节目名称</th>
		<th width="30" align="center"></th>
		<th class="text_indent" width="100">归档分类</th>
		<th width="80" align="center">计划执行</th>
		<th width="100" class="text_indent">操作</th>
	</tr>	
	<tbody id="status">
	{if $list}
       {foreach $list as $k => $v}
		<tr align="left" valign="middle" class="h" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="plan{$v['id']}">
			<td align="center"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v[$primary_key]}" class="n-h"/></td>
			<td class="text_indent" width="150" class="overflow">{$v['channel']}</td>
			<td class="text_indent" width="150" class="overflow">{$v['cycle']}</td>
			<td class="text_indent">{$v['start_time']} - {$v['end_time']}</td>
			<td><span class="text_b">{$v['toff_decode']}</span></td>
			<td class="overflow" width="160"><a class="text_p" title="{$v['title']}">{code} echo hg_cutchars($v['title'],8,'');{/code}</a>
			</td>
			<td align="center">
				<span class="c_a">
					{if $v['columnid']}
						 <span class="lm"><em></em></span>
					{/if}
				</span>
			</td>
			<td class="text_indent">{$v['sort_name']}</td>
			<td align="center">{$v['action']}</td>
			<td class="text_indent">
				<a title="" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>&nbsp;&nbsp;
				<a href="javascript:void(0);" onclick="hg_plan_del({$v['id']});">删除</a>
				{foreach $_relate_module AS $kkk => $vvv}
				<a href="./run.php?mid={$kkk}&record_id={$v['id']}&infrm=1">{$vvv}</a>
				{/foreach}
			</td>
		</tr>
		{/foreach}
	{/if}
	</tbody>
</table>
<div class="live_delete">
<input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall" class="n-h">
<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');">删除</a>
</div>
<div>{$pagelink}</div>
</form>
{template:foot}