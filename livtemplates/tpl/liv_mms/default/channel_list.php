<?php 
/* $Id: channel_list.php 10899 2012-08-23 08:13:46Z lijiaying $ */
?>
{template:head}
{css:vod_style}
{css:edit_video_list}
{js:channels}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}

<script type="text/javascript">
/*流状态控制*/
var stream_status_id = "";
function hg_stream_status(id)
{
	stream_status_id = id;
	var url = "./run.php?mid=" + gMid + "&a=stream_state&id=" + id + "&infrm=1";
	hg_ajax_post(url,"","",'hg_stream_state');
}
function hg_stream_state(obj)
{
	var obj = obj[0];
	if(obj.status == 1)
	{
		$('#a_' + stream_status_id).addClass('a');
		$('span[id^=out_uri_'+stream_status_id+']').addClass('channel_stream');
		$('span[id^=out_uri_'+stream_status_id+']').removeClass('channel_stream_b');
		$('#a_' + stream_status_id).attr('title','已启动');
		$('#a_' + stream_status_id).removeClass('b');
		$('#a_info_' + stream_status_id).html('已启动');
	}
	else if(obj.status == 2)
	{
		$('#a_' + stream_status_id).addClass('b');
		$('span[id^=out_uri_'+stream_status_id+']').addClass('channel_stream_b');
		$('span[id^=out_uri_'+stream_status_id+']').removeClass('channel_stream');
		$('#a_' + stream_status_id).attr('title','未启动');
		$('#a_' + stream_status_id).removeClass('a');
		$('#a_info_' + stream_status_id).html('未启动');
	}
	else
	{
		alert('操作失败');
	}
}
function hg_stream_uri(name,id)
{
	var code = $('#formdata_code_preview_uri_' + id + '_' + name).val();
	var streamName = $('#formdata_streamName_preview_uri_' + id + '_' + name).val();
	var codeName = code+' ['+ streamName + ']';
	var url = $('#formdata_preview_uri_' + id + '_' + name).val();
	document.getElementById('pri_small_colorollor').play(codeName, url);
}
function hg_flv_close()
{
	$('#flv_preview').hide();
	$('#arrow_show').hide();
	$('#edit_show').html('');
}
var gId = 0;
function hg_channel_edit_info(id)
{
	if(gDragMode)
	 {
		   return;
	 }
	 /*判断当前有没有打开，打开的话就关闭*/
	 if($('#vodplayer_'+id).length)
	 {
		 hg_close_opration_info();
		 return;
	 }
	/*关闭之前保存选项卡的状态到cookie*/
	 hg_saveItemCookie();

	 gId=id;
	 
	 var ajaxcallback = function(){
		var url = "./run.php?mid="+gMid+"&a=show_channel_info&id="+id+"&relate_module_id="+gRelate_module_id;
		hg_ajax_post(url);
	}

	;(function(){
		var h=$('body',window.parent.document).scrollTop();
		$('#edit_show').html('<img src="'+ RESOURCE_URL + 'loading2.gif' +'" style="width:50px;height:50px;"/>');
		click_title_show(h, ajaxcallback);
	})();
	
	
}

function hg_channel_order_update(id)
{
	if(!gDragMode)
	{
		document.location.href = "./run.php?mid={$_INPUT['mid']}&a=form&id="+id+"&infrm=1";
	}
	else
	{
		return;
	}
}
$(function(){
	tablesort('vodlist','channel','order_id');
	$("#vodlist").sortable('disable');
});

</script>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增直播频道</strong></a>
</div>
{js:ios/switch}
<script>
$(function(){
	var i = 1;
	var custom_stream_status = function(id, callback){
		var url = "./run.php?mid=" + gMid + "&a=stream_state&id=" + id + "&infrm=1";
		hg_ajax_post(url,"","", callback);
	}
	var onandoff = function(self, state){
		var tmp = 'mySwitchCallback' + ++i;
		window[tmp]= function(ajax){
					ajax = ajax[0];
					var title = '';
					if(ajax.status == 1){
						title = '已启动';
					}else if(ajax.status == 2){
						title = '未启动';
					}else{
						self.trigger('callback', [state == 'on' ? 'off' : 'on']);
						return;
					}
					self.selector.attr('title', title);
					self.trigger('callback', ['ok']);
					delete window[tmp];
				}
				custom_stream_status(self.data('data'), tmp);
	}
	$('.need-switch').each(function(){
		$(this).switchButton({
			data : $(this).attr('vid'),
			init : $(this).attr('state') > 0 ? 'on' : 'off',
			on : function(self){
				onandoff(self, 'on');
			},
			off : function(self){
				onandoff(self, 'off');
			}
		})
	});
});
</script>
<div class="search_a" id="info_list_search" style="height:0;padding:0"></div>
<div id="infotip" class="ordertip" ></div>
<div class="v_list_show">
	<form>
	<table  border="0" cellpadding="0" cellspacing="0" width="100%"  id="channel_table" class="form_table">
		<tr style="height:40px;line-height:40px;" id="item_th" class="h" align="left" valign="middle" >
			<th width="30" align="center"><img style="cursor: pointer;" id="is_order" title="开启排序模式/ALT+R" onclick="hg_switch_order('vodlist');" src="{code} echo RESOURCE_URL.'hg_logo.jpg';{/code}" /></th>
			<th width="115" class="text_indent">台标</th>
			<th align="center" width="30" >状态</th>
			<th class="text_indent">频道名称</th>
			<th align="center" width="80">编辑</th>
			<th class="text_indent" width="85">台号</th>
			<th width="80" class="overflow">信号流</th>
			<th width="120"></th>
			<th width="120">输出流</th>
		</tr>	
		<tbody id="vodlist">
		{if $list}
		   {foreach $list as $k => $v}
			<tr orderid="{$v['order_id']}"  id="r_{$v['id']}" class="h"   name="{$v['id']}"  align="left"  valign="middle"  onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');">
				<td width="30" align="center" id="primary_key_img_{$v['id']}"><a class="lb" name="alist[]" style="height: 11px;width: 14px;display: inline-block;background-position:0 0" ><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" class="n-h" style="margin: 0;float:left;"/></a></td>
				<td onclick="hg_channel_edit_info({$v['id']});" style="line-height:1%;padding:4px 0;"><div style="width:112px;height:43px;overflow:hidden;padding:0;font-size:0;">{if $v['logo_url']}<img src="{$v['logo_url']}" style="max-height:43px;" />{/if}</div></td>
				<td 1onclick="hg_stream_status({$v['id']});" id="channel_status_{$v['id']}" align="center" style="padding-top:4px;">
				<div class="need-switch" title="{if $v['stream_state']}已启动{else}未启动{/if}" state="{if $v['stream_state']}1{else}0{/if}" style="cursor:pointer;" vid="{$v['id']}"></div>
				</td>
				<td onclick="hg_channel_edit_info({$v['id']});" class="text_indent" style="cursor:pointer;">{$v['name']}</td>
				<td align="center"><a class="edit" id="a_update_{$v['id']}" title="编辑" href="javascript:void(0);" onclick="hg_channel_order_update({$v['id']});"></a></td>
				<td onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"  class="text_indent" ><span>{$v['code']}</span></td>
				<td onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');">
					<span class="overflow" style="width:80px;display:block;">{$v['stream_display_name']}</span>
				</td>
				<td onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');">
					{foreach $v['stream_uri'] as $kk=>$vv}
						{if $v['s_status']}
						<span class="channel_stream" title="{$vv}"  id="stream_uri_{$v['id']}_{$kk}">{$kk}</span><input type="hidden" id="preview_uri_{$v['id']}_{$kk}" value="{$vv}" />
						{else}
						<span class="channel_stream_b" title="{$vv}"  id="stream_uri_{$v['id']}_{$kk}">{$kk}</span><input type="hidden" id="preview_uri_{$v['id']}_{$kk}" value="{$vv}" />
						{/if}
					{/foreach}
				</td>
				<td onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');">
				{foreach $v['out_streams'] as $kk=>$vv}
				{if $v['stream_state']}
				<span class="channel_stream_b" title="{$vv}"  id="out_uri_{$v['id']}_{$kk}">{$kk}</span><input type="hidden" id="preview_uri_{$v['id']}_{$kk}" value="{$vv}" />
				{else}
				<span class="channel_stream" title="{$vv}"  id="out_uri_{$v['id']}_{$kk}">{$kk}</span><input type="hidden" id="preview_uri_{$v['id']}_{$kk}" value="{$vv}" />
				{/if}
				{/foreach}
				</td>
			</tr>
			{/foreach}
		{/if}
		</tbody>
	</table>
	<div class="live_delete">
		<input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall" class="n-h">
		<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" >批量删除</a>
	</div>
	<div>{$pagelink}</div>
	</form>
	<div class="edit_show">
		<span class="edit_m" id="arrow_show"></span>
		<div id="edit_show"></div>
	</div>
</div>
{template:foot}