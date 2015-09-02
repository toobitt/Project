{template:head}
{js:news}
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a  class="button_6" href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1"><strong>添加配置</strong></a>
</div>
<div class="wrap">
<form name="listform" action="" method="post" class="news_list">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" id="channel_table" class="form_table">
		<tr id="item_th" class="h" align="left" valign="middle">
			<th width="30" class="center"></th>
			<th class="text_indent">ID</th>
			<th class="text_indent">配置名称</th>
			<th class="text_indent">类型</th>
			<th class="text_indent"width="450">解析代码</th>
			<th class="text_indent" >启用</th>
			<th class="text_indent">管理</th>
		 </tr>

		 <tbody id="status">
		{if !empty($affix_setting_list)}
			{foreach $affix_setting_list as $k => $v}
				<tr orderid="{$v['aid']}" id="r_{$v['aid']}" class="h" name="{$v['aid']}" align="left" valign="middle"
				onmouseover="hg_row_interactive(this,'on');" onmouseout="hg_row_interactive(this,'out');" onclick="hg_row_interactive(this,'click','cur');">
					<td class="center"><input type="checkbox" name="infolist[]"  value="{$v['aid']}" title="{$v['aid']}" /></td>
					<td class="text_indent"><span class="m2o-common-title">{$v['aid']}</span></td>
                    <td class="text_indent"><span id="name_{$v['aid']}">{$v['aname']}</span></td>
					<td class="text_indent">{$v['expand']}</td>
					<td class="text_indent"><textarea style="width:300px;height:30px;margin-top:6px;">{$v['code']}</textarea></td>
					<td class="text_indent">
						{if $v['is_open']}
							<span title="已开启">是</span>
						{else}
							<span title="未开启">否</span>
						{/if}
						</span>
					</td>
					<td class="text_indent">
					   <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['aid']}&infrm=1">编辑</a>
					   <a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['aid']}">删除</a>
					</td>
				</tr>
			{/foreach}
		{else}
			<tr><td class="hg_error" colspan="10">暂无记录</td></tr>
		{/if}
		</tbody>
	</table>
	<div class="live_delete">
		<input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall" class="n-h">
        <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
	</div>
	<div class="live_page">{$pagelink}</div>
</form>
</div>
<script type="text/javascript">
function hg_delete_call(id)
{
     var ids=id.split(",");
     for(var i=0;i<ids.length;i++)
     {
        $("#r_"+ids[i]).remove();
     }
}
</script>
{template:foot}