<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}
	$type = $_GET['_type']?$_GET['_type']:-1;
{/code}
<script type="text/javascript">
	var gServers = new Array();
	var run = 0;
	function testlink() {
		if(gServers[run]) {

			var link = ($('#link_' + gServers[run]).attr('testlink'));

			$('#status_' + gServers[run]).html('正在连接……');

			$('#link_' + gServers[run]).attr('src', link);
		}
		run++;
	}
	window.onload = testlink;
</script>
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
<a href="?a=form&type={$type}&infrm=1" class="button_6"><strong>添加服务器</strong></a>
</div>
<div class="wrap">
<form>
	<table  border="0" cellpadding="0" cellspacing="0" width="100%"  id="channel_table" class="form_table">
		<tr id="item_th" class="h" align="left" valign="middle" >
			<th width="30" align="center"></th>
			<th class="text_indent">名称</th>
			<th class="text_indent">标识</th>
			<th class="text_indent">简介</th>

			<!-- <th class="text_indent">创建时间</th> -->

			<th class="text_indent">内网ip</th>
			<th class="text_indent">外网ip</th>
			<th class="text_indent">域名</th>
			<th class="text_indent">端口</th>
			<th class="text_indent">访问协议</th>
			<th class="text_indent">用户名</th>
			<!-- <th class="text_indent">密码</th> -->
			<th class="text_indent">token</th>
			
			<th class="text_indent">链接状态</th>
			<!--
			<th class="text_indent">服务器状态</th>
			-->
			<th class="text_indent">管理</th>
		</tr>	
		<tbody id="status">
		
		{if count($list)>0}
		   {foreach $list as $k => $v}
			<tr orderid="{$v['id']}"  id="r_{$v['id']}" class="h"   name="{$v['id']}"  align="left"  valign="middle"  onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
				<td align="center" id="primary_key_img_{$v['id']}"><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" class="n-h" /></td>
				<td class="text_indent" ><span id="name_{$v['id']}">{$v['name']}</span></td>
				<td class="text_indent" >{$v['ident']}</td>
				<td class="text_indent" >{$v['brief']}</td>

			<!-- <td class="text_indent" >{$v['create_time']}</td> -->

				<td class="text_indent" >{$v['n_ip']}</td>
				<td class="text_indent" >{$v['o_ip']}</td>
				<td class="text_indent" >{$v['site_name']}</td>
				<td class="text_indent" >{$v['port']}</td>
				<td class="text_indent" >{$v['access_deal']}</td>
				<td class="text_indent" >{$v['user_name']}</td>
				
				<!-- <td class="text_indent" >{$v['password']}</td> -->
				<td class="text_indent" >{$v['token']}</td>
				
				<td class="text_indent">
					<!--
					{if $v['link_state']}连接{else}断开{/if}
					-->
					<strong id="status_{$v['id']}"></strong>
					<script id="link_{$v['id']}" testlink="server.php?a=bash_ping_server&type={$v['type']}&random={code}echo time()+$v['id']{/code}&sid={$v['id']}"></script><script>gServers[{$k}] = '{$v["id"]}';
					</script>
				</td>
				<!--
				<td class="text_indent" >{if $v['state']}启用{else}禁用{/if}</td>
				-->
				<td class="text_indent">
					<a title="编辑" href="?a=form&id={$v['id']}&infrm=1">编辑</a>
					<a onclick="return hg_ajax_post(this, '删除', 1);" href="?a=delete&id={$v['id']}">删除</a>
				</td>
				<input type="hidden" id="hidden_uri_{$v['groupid']}" value="{$v['file_uri']}" />
			</tr>
			{/foreach}
		{else}
		<tr><td class="hg_error" colspan="10">暂无记录<td></tr>
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