<?php 
/* $Id: program_record_list_list.php 5784 2011-12-13 06:38:33Z repheal $ */
?>
{template:head}
{js:channels}
<style>
span.yes,span.no{background:url('{$RESOURCE_URL}bg-all.png') -2px -164px  no-repeat;height:15px;width:15px;display:inline-block;margin:6px 0 0 0}
span.yes{background-position:-121px  -164px;}
span.no{background-position:-121px  -197px;}
</style>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
<div class="form_tit">
{template:menu/btn_menu}
<form name="searchform" id="searchform" action="" method="get" class="hg_pr_l">
<div class="right_1">
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
		<th width="170" class="text_indent">归档为</th>
		<th width="285" class="text_indent">来源</th>
		<th width="105" class="text_indent">执行时间</th>
		<!--<th width="65" class="text_indent">操作</th>-->
	</tr>	
	<tbody id="status">
	{if $list}
       {foreach $list as $k => $v}
		<tr  onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" align="left" valign="middle">
			<!--<td align="center"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v[$primary_key]}" /></td>-->
			<td align="center">
				{if $v['state']==1}
					<span class="yes"></span>
				{else}
					<span class="no"></span>
				{/if}
			</td>
			<td>{code} echo $v['auto']?'自动':'手动';{/code}</td>
			<td class="text_indent">{if$v['state'] == 2}{$v['error']}{else}{$v['program_name']}({$v['toff_decode']}){/if}</td>
			<td class="text_indent">{$v['sortname']}-- <a class="fb" href="./run.php?mid={$v['mid']}&amp;a=form&amp;id={$v['vod_id']}&amp;infrm=1">{$v['vod_id']}</a></td>
			<td class="text_indent">{$v['channel_name']}&nbsp;{code} echo date('m-d H:i:s',$v['start_time']){/code}~{code} echo date('H:i:s',$v['start_time']+$v['toff']){/code}</td>
			<td class="text_indent">{$v['dates']}</td>
			<!--<td class="text_indent">
			{if $v['state']==2}
				<a title="" href="./run.php?mid={$_INPUT['mid']}&a=record&id={$v['id']}&infrm=1">重新收录</a>
			{/if}
			<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>
				<a title="" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
				
			</td>-->
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