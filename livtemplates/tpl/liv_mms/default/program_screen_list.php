<?php 
/* $Id: program_screen_list.php 9977 2012-07-13 02:19:56Z zhoujiafei $ */
?>
{template:head}
{css:tab_btn}
{js:channels}
<script type="text/javascript">
function hg_screen_delete(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
	
}
function hg_screen_call_delete(data)
{
	var ids = eval(data).split(",");
	for(var i=0;i<ids.length;i++)
	{
		$("#plan"+ids[i]).slideUp(1000).remove();
	}

	if($("#checkall").attr('checked'))
	{
		$("#checkall").removeAttr('checked');
	}
}
function hg_screen_state(id,state)
{
	var opt = state ? '开启' : '关闭';
	if(confirm('是否' + opt + '？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=audit&state=' + state + '&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
}

function hg_screen_call_state(data)
{
	var obj = new Function("return" + data)();
	if(obj.id)
	{
		var class_name = obj.state ? 'a' : 'b';
		var title = obj.state ? '已启动' : '未启动';
		$("#a_" + obj.id).attr('class',class_name).attr('title',title).attr('onclick','hg_screen_state(' + obj.id + ',' + (obj.state ? 0 : 1) + ');');
	}
}
</script>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增屏蔽</strong></a>
</div>
<div class="form_tit">
{code}
/*暂时屏蔽{template:menu/btn_menu}*/
{/code}
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
		<th class="text_indent" width="150">需屏蔽频道</th>
		<th class="text_indent" width="150">周期/日期</th>
		<th width="155" class="text_indent">时间段</th>
		<th width="150" class="text_indent">原节目</th>
		<th width="150" class="text_indent">垫片</th>
		<th width="150" class="text_indent">启用</th>
		<th width="100" class="text_indent">操作</th>
	</tr>	
	<tbody id="status">
	{if $list}
       {foreach $list as $k => $v}
		<tr align="left" valign="middle" class="h" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="plan{$v['id']}">
			<td align="center"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v[$primary_key]}" class="n-h"/></td>
			<td class="text_indent" width="150" class="overflow">{$channel[$v['channel_id']]}</td>
			<td class="text_indent">{$v['cycle']}</td>
			<td><span class="text_b">{$v['start']} - {$v['end']}</span></td>
			<td class="text_indent">{$v['title']}</td>
			<td class="text_indent">{$v['new_title']}</td>
			<td class="text_indent">
				<span class="channel_start" style="cursor:pointer;">
				{if !$v['state']}
					<span  title="未启动" id="a_{$v['id']}" class="b" onclick="hg_screen_state({$v['id']},1);"></span>
				{else}
					<span  title="已启动" id="a_{$v['id']}" class="a" onclick="hg_screen_state({$v['id']},0);"></span>
				{/if}
				</span>
			</td>
			<td class="text_indent">
				<a title="" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>&nbsp;&nbsp;
				<a href="javascript:void(0);" onclick="hg_screen_delete({$v['id']});">删除</a>
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