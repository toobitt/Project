<?php 
/* $Id: encode_list.php 5342 2011-12-06 05:37:04Z gengll $ */
?>
{template:head}
{js:channels}
{css:tab_btn}
{css:vod_style}

<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_4"><strong>添加</strong></a>
</div>
<!--<form name="searchform" id="searchform" action="" method="get">
<div class="right_1">
	{code}
		$attr_source = array(
			'class' => 'transcoding down_list',
			'show' => 'transcoding_show',
			'value_name' => 'channel_id',
			'style' => 'display:none;width:104px;',
			'width' => 104,
			'state' => 0,						
			'type' => 0, /*数据，不包含其他的扩展*/
		);
		$_INPUT['channel_id'] = $_INPUT['channel_id']?$_INPUT['channel_id']:-1;
		$attr_date = array(
			'class' => 'colonm down_list data_time',
			'show' => 'colonm_show',
			'value_name' => 'date_search',
			'style' => 'display:none;width:104px;',
			'width' => 104,
			'state' => 1,
			'type' => 1, /*数据，包含日期扩展*/
		);
		$channel[-1] = '请选择';
		foreach($channel_info as $k =>$v)
		{
			$channel[$v['id']] = $v['name'];
		}
		$_INPUT['date_search'] = $_INPUT['date_search']?$_INPUT['date_search']:1;
	{/code}
	{template:form/search_source,trans_status,$_INPUT['channel_id'],$channel,$attr_source}
	{template:form/search_source,colonm_id,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
	<input type="hidden" name="a" value="show" />
	<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</div>
</form>
<div class="clear"></div>

{code}
print_r($list);
{/code}-->

<table cellspacing="0" cellpadding="0" width="100%" style="padding:0 10px;">
	<tr id="item_th" style="font-size:12px;height:40px;border:1px solid red;">
		<th width="30px;" align="center"><img src="{code} echo RESOURCE_URL.'hg_logo.jpg';{/code}" /></th>
		<th width="70px;" align="left">编码器名称</th>
		<th width="70px;" align="left">IP</th>
		<th width="70px;" align="left">是否开启</th>
		<th width="80px;" align="center">操作</th>
	</tr>	
	<tbody id="status">
	{if $list}
       {foreach $list as $k => $v}
		<tr style="font-size:12px;height:58px;border:1px;">
			<td width="30px;" align="center"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v[$primary_key]}" /></td>
			<td width="70px;" align="left">{$v['name']}</td>
			<td width="70px;" align="left">{$v['ip']}</td>
			<td width="70px;" align="left">{$v['is_used_show']}</td>
			<td width="80px;" align="center">
				<a title="" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
				<a href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>
			</td>
		</tr>
		{/foreach}
	{/if}
	</tbody>
</table>
<div>
<input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall" style="margin-top:10px;margin-left:14px;float:left;">
<span onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" style="cursor:pointer;font-size:12px;margin-left:14px;margin-top:12px;display:block;float:left;">删除</span>
</div>
<div>{$pagelink}</div>
</form>
{template:foot}