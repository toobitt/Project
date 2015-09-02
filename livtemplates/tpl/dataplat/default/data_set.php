<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}

<div class="wrap">
<div style="float:right">
<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增配置</strong></a>
</div>
<form>
	<table  border="0" cellpadding="0" cellspacing="0" width="100%"  id="channel_table" class="form_table">
		<tr id="item_th" class="h" align="left" valign="middle" >
			<th width="30" align="center"></th>
			<th class="text_indent">配置名称</th>
			<th class="text_indent">数据库</th>
			<th class="text_indent">是否关闭</th>
			<th class="text_indent">状态</th>
			<th class="text_indent">导入时间</th>
			<th class="text_indent">管理</th>
		</tr>	
		<tbody id="status">
		{code}
			//hg_pre($data_set);
		{/code}
		{if $data_set}
		   {foreach $data_set as $k => $v}
			<tr orderid="{$v['id']}"  id="r_{$v['id']}" class="h"   name="{$v['id']}"  align="left"  valign="middle"  onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
				<td align="center" id="primary_key_img_{$v['id']}"><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" class="n-h" /></td>
				<td class="text_indent"><span id="name_{$v['id']}" class="m2o-common-title">{$v['title']}</span></td>
				<td class="text_indent"><span>{$v['dbtype']}</span></td>
				{code}
					switch($v['status'])
					{
						case 0:$status = '否';break;
						case 1:$status = '是';break;
						default:$status = '是';
					}
				{/code}
				<td class="text_indent" >{$status}</td>
				{code}
					switch($v['importstatus'])
					{
						case 0:$importstatus_name = '准备导入';break;
						case 1:$importstatus_name = '正在导入';break;
						case 2:$importstatus_name = '导入完成';break;
						default:$importstatus_name = '准备导入';
					}
				{/code}
				<td class="text_indent" >{$importstatus_name}</td>
				<td class="text_indent" >{$v['format_runtime']}</td>
				
				<td class="text_indent">
					<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</i></a>
					<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</i></a>
				</td>
				<input type="hidden" id="hidden_uri_{$v['id']}" value="{$v['file_uri']}" />
			</tr>
			{/foreach}
		{else}
		<tr><td class="hg_error" colspan="10">暂无记录</td></tr>
		{/if}
		</tbody>
	</table>
	<div class="live_delete">
		<input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall" class="n-h">
		<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');">删除</a>
	</div>
	<div>{$pagelink}</div>
</form>
<div style="cursor:move;position:absolute;top:81px;left:120px;width:400px;height:314px;background:#000000;display:none;border:5px solid #B2B2B2;border-radius:3px;" id="flv_box"></div>
</div>
{template:foot}