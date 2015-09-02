<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
{css:column_node}
{js:column_node}
{code}
$mod = $_INPUT['mod'];
$group_name = $_INPUT['name'];
$id = $id;
$op = $op;

$type = $type;

if($list)
{
	foreach($list as $key => $val)
	{
		$val = $val[0];
		foreach($val as $k=>$v)
		{
			if($v['act'] && $v['node_en'])
			{
				$node_en[$v['node_en']][] = $k;
			}
		}
	}
}
if($node_en)
{
	foreach($node_en as $k => $v)
	{
		$default_node_en = $k;
		$default_node_id[] = $v;
		break;
	}
}
else if($multi_node)
{
	foreach($multi_node as $k => $v)
	{
		$default_node_en = $k;
		break;
	}
}
$hg_attr['multiple_node'] = $multi_node;
$default_node_id = $default_node_id[0];
$hg_attr['node_en'] = $default_node_en;

$hg_attr['multiple'] = 1;
$hg_attr['multiple_site'] = 1;
$hg_attr['slidedown'] = 1;
$hg_attr['siteid'] = $default_node_en;
{/code}
<script type="text/javascript">
	function show_node()
		{
			$("#add_node").show();
		}
</script>
<form  action="privilege.php" enctype="multipart/form-data" method="post" class="form">

	<table  border="1" cellpadding="0" cellspacing="0" width="100%"  id="channel_table" class="form_table">
		<tr class="h" align="center" valign="middle" >
			<th width="20%" align="center" colspan="5">{$group_name}的节点权限</th>
		</tr>
		<tr id="item_th" class="h" align="left" valign="middle" >
			<th width="20%" align="center">节点名称</th>
			<th width="30" align="center"></th>
		{if count($op)>0}
			{foreach $op as $k => $v}
			<th class="text_indent">{$v['op_name']}</th>
			{/foreach}
		{/if}
			
		</tr>	
		<tbody id="status">
		
		{if is_array($list) && count($list)>0}
		   {foreach $list as $key => $val}
		   
		{code}
			$val = $val[0];
			$num = count($val);
		{/code}
		{if $num >0}
			{foreach $val as $k=> $v}
				{code}
				$length = $v['depath'];
				
				$str = '';
				for($i=1;$i<$length;$i++)
				{
					$str .= '-'; 
				}
				{/code}
			<tr  id="r_{$v['id']}" class="h"   name="{$v['id']}"  align="left"  valign="middle"  onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
				<td  class="text_indent" >
					{$str.$v['name']}
				</td>
				<th width="30" align="center"></th>
				{if !$v['act']}
					<td class="text_indent" colspan="10">本级节点无权限</td>
				{else}
					{if count($op)>0}
						{foreach $op as $kk => $vv}

						<td class="text_indent">
							<input name="{$vv['op_en']}[{$k}]" type="checkbox" class="n-h" {if in_array($vv['op_en'],$v['act'] )}checked="checked"{/if} {if in_array('all_op',$v['act'])}checked="checked"{/if} value="{$v['node_en']}"/>{$vv['op_name']}
							<input type="hidden" name="node_en[]" value="{$v['node_en']}" />
						</td>
					
						{/foreach}
					{/if}
				{/if}
			</tr>
			{/foreach}
			{/if}
			{/foreach}
		{else}
		<tr><td class="hg_error" colspan="10">暂无记录<td></tr>
		{/if}
		</tbody>
	</table>
<input type="hidden" name="a" value="set_node_auth" />
<input type="hidden" name="mod_en" value="{$mod}" />
<input type="hidden" name="app_en" value="{$app_en}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="type" value="{$type}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<span class="label">&nbsp;</span><input type="submit" name="rsub" value="修改权限" class="button_4" />
<span class="label">&nbsp;</span><input type="button" onclick="show_node();" name="rsub" value="增加权限" class="button_4" />
</form>
<div id="add_node" style="display:none; float:left;">
	<form  action="privilege.php" enctype="multipart/form-data" method="post" class="form">
		{template:unit/class,node_id,$default_node_id,$node_data, $hg_attr}
		<input type="hidden" name="a" value="set_node_auth" />
		<input type="hidden" name="mod_en" value="{$mod}" />
		<input type="hidden" name="app_en" value="{$app_en}" />
		<input type="hidden" name="id" value="{$id}" />
		<input type="hidden" name="type" value="{$type}" />
		<input type="hidden" name="add_node" value=1 />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<span class="label">&nbsp;</span><input type="submit" name="rsub" style="clear:both;float:left;" value="添加节点" class="button_4" />
	</form>
</div>

{template:foot}