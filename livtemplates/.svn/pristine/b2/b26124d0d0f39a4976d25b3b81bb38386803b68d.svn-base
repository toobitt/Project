<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);
if(!isset($_INPUT['status']))
{
	
    $_INPUT['status'] = -1;
}
$_configs['app_store_status'][-1]  = "全部";
/*状态控件*/
$status_source = array(
	'class' => 'transcoding down_list',
	'show' => 'status_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
);
{/code}
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
<div class="search_a" id="info_list_search">
  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
        <div class="right_1">
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			{template:form/search_source,status,$_INPUT['status'],$_configs['app_store_status'],$status_source}

			<input type="hidden" name="a" value="show" />
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
			<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
        </div>
        <div class="right_2">
        	<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
            </div>
			{template:form/search_input,k,$_INPUT['k']}                        
        </div>
   </form>
</div>
</div>

<div class="wrap">
<form>

	<table  border="0" cellpadding="0" cellspacing="0" width="100%"  id="channel_table" class="form_table">
		<tr id="item_th" class="h" align="left" valign="middle" >
			<th width="30" align="center"></th>
			<th class="text_indent">应用名称</th>
			<th class="text_indent" width="100">用户名</th>
			<th class="text_indent">申请时间</th>
			<th class="text_indent" width="40">状态</th>
			<th class="text_indent" width="120">审核时间</th>
			<th class="text_indent" width="250">管理</th>
		</tr>	
		<tbody id="status">
		{if $list}
		   {foreach $list as $k => $v}
			<tr orderid="{$v['id']}"  id="r_{$v['id']}" class="h"   name="{$v['id']}"  align="left"  valign="middle"  onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
				<td align="center" id="primary_key_img_{$v['id']}"><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" class="n-h" /></td>
				<td class="text_indent"><span id="name_{$v['id']}">{$v['app_name']}</span></td>
				<td class="text_indent"><span onclick="hg_backup_flv({$v['id']});" style="cursor:pointer;" title="预览">{$v['user_name']}</span></td>
				<td class="text_indent" >{$v['create_time']}</td>
				<td class="text_indent" style="color:{$_configs['app_store_status_colors'][$v[status]]}">{$_configs['app_store_status'][$v[status]]}</td>
				<td class="text_indent">{$v['audit_time']}</td>
				<td class="text_indent">
					<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</i></a>
					<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}&infrm=1">删除</a>
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