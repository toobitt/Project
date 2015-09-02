{template:head}
{template:head/nav}
{js:jquery-ui-1.8.16.custom.min}
<script type="text/javascript">
function hg_backup_flv(id)
{
	$('#flv_box').show();
	var name = $('#name_' + id).html();
	var uri = $('#hidden_uri_' + id).val();
	if(uri)
	{
		$('#flv_box').html('<a id="flv_colse" href="javascript:void(0);" onclick="hg_flv_close();" style="margin-left:390px;display:block;color:#ffffff;" title="关闭">X</a><object id="backup_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/backup.swf?12012901" width="400" height="300"><param name="movie" value="{$RESOURCE_URL}swf/backup.swf?12012901"/><param name="allowscriptaccess" value="always"><param name="wmode" value="transparent"><param name="flashvars" value="mute=false&streamName='+name+'&streamUrl='+uri+'&connectName=synTime_{code}echo TIMENOW;{/code}&connectIndex={$syn_index}&jsNameSpace=gControllor"></object>');
	}
}
function hg_flv_close()
{
	$('#flv_box').hide();
	$('#flv_colse').hide();
	$('#backup_colorollor').remove();
}
$(function(){
	$("#flv_box").draggable({containment:'document'});
});
</script>
<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>
<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增备播文件</strong></a>
</div>
<div class="form_tit">
{template:menu/btn_menu}
</div>
<form>
	<table  border="0" cellpadding="0" cellspacing="0" width="100%"  id="channel_table" class="form_table">
		<tr id="item_th" class="h" align="left" valign="middle" >
			<th width="30" align="center"></th>
			<th class="text_indent" width="50">缩略图</th>
			<th class="text_indent">标题</th>
			<th class="text_indent" width="130" >文件名</th>
			<th class="text_indent" width="180">发布时间</th>
			<th class="text_indent" width="120">用户名</th>
			<th class="text_indent" width="150">管理</th>
		</tr>	
		<tbody id="status">
		{if $live_backup_list}
		   {foreach $live_backup_list as $k => $v}
			<tr orderid="{$v['id']}"  id="r_{$v['id']}" class="h"   name="{$v['id']}"  align="left"  valign="middle"  onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');">
				<td align="center" id="primary_key_img_{$v['id']}"><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></td>
				<td class="text_indent"><span id="img_{$v['id']}"><img width=40 height=30 src="{$v['img']}" /></span></td>
				<td class="text_indent"><span id="name_{$v['id']}">{$v['title']}</span><span id="toff" style="margin-left:10px;">{if $v['toff']}{$v['toff']}{/if}</span></td>
				<td class="text_indent overflow"><span onclick="hg_backup_flv({$v['id']});" style="cursor:pointer;" title="预览">{$v['filename']}</span></td>
				<td class="text_indent">{$v['create_time']}</td>
				<td class="text_indent">{$v['user_name']}</td>
				<td class="text_indent">
					<a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
					<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>
				</td>
				<input type="hidden" id="hidden_uri_{$v['id']}" value="{$v['file_uri']}" />
			</tr>
			{/foreach}
		{else}
		<tr><td>暂无记录<td></tr>
		{/if}
		</tbody>
	</table>
	<div class="live_delete">
		<input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall" class="n-h">
		<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" >批量删除</a>
	</div>
	<div>{$pagelink}</div>
</form>
<div style="cursor:move;position:absolute;top:81px;left:120px;width:400px;height:314px;background:#000000;display:none;border:5px solid #B2B2B2;border-radius:3px;" id="flv_box"></div>
{template:foot}