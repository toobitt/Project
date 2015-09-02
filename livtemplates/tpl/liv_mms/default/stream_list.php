<?php 
/* $Id: stream_list.php 11479 2012-09-05 09:07:10Z develop_tong $ */
?>
{template:head}
{js:channels}
{js:jquery-ui-1.8.16.custom.min}
<script type="text/javascript">
/*流状态控制*/
var stream_status_id = "";
function  hg_stream_status(id)
{
	stream_status_id = id;
	var url = "./run.php?mid=" + gMid + "&a=stream_status&id=" + id + "&infrm=1";
	hg_ajax_post(url,"","",'hg_stream_states');
}
function hg_stream_states(obj)
{	
	var obj = obj[0];
	if(obj.stream_status == 1)
	{
		$('#a_' + stream_status_id).addClass('a');
		$('span[id^=out_uri_'+stream_status_id+']').addClass('channel_stream');
		$('span[id^=out_uri_'+stream_status_id+']').removeClass('channel_stream_b');
		$('#a_' + stream_status_id).attr('title','已启动');
		$('#a_' + stream_status_id).removeClass('b');
	}
	else if(obj.stream_status == 2)
	{
		$('#a_' + stream_status_id).addClass('b');
		$('span[id^=out_uri_'+stream_status_id+']').addClass('channel_stream_b');
		$('span[id^=out_uri_'+stream_status_id+']').removeClass('channel_stream');
		$('#a_' + stream_status_id).attr('title','未启动');
		$('#a_' + stream_status_id).removeClass('a');
	}
	else
	{
		alert('操作失败！');
	}
}
/*删除信号时，检测此信号是否已被频道所用*/
var gStreamId = '';
function hg_stream_delete_check(ids)
{
	gStreamId = ids;
	var url = "./run.php?mid="+gMid+"&a=goBackTip&id="+ids;
	hg_ajax_post(url);
}

function hg_goback(obj)
{
	var obj = eval('('+obj+')');
	id = gStreamId;
	if(obj)
	{
		var channel=confirm("["+obj+"] 在用此信号，还确定删除此信号吗？");
		if(channel == true)
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
			hg_ajax_post(url);
		/*
			var channel_url = './run.php?mid=' + gMid + '&a=channel_stream_reset&stream_id=' + id;
			hg_ajax_post(channel_url);
			$('#stream_hold').html('正在提交，请不要离开页面！');
		*/
		}
	}
	else
	{
		var stream=confirm("确定删除该信号吗？");
		if (stream==true)
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
			hg_ajax_post(url);
		}
	}
}
function hg_stream_hold(obj)
{
	if(obj)
	{
		$('#stream_hold').html('');
	}
}
/*预览*/
function hg_stream_uri(name,id,s_status)
{
	$('#flv_preview').show();
	$('#flv_colse').show(4000);
	var url = $('#preview_uri_' + id + '_' + name).val();
	var s_name = $('#s_name_' + id).html();
	$('#flv_preview').html('<a id="flv_colse" href="javascript:void(0);" onclick="hg_flv_close();" style="margin-left:387px;display:block;color:#ffffff;" title="关闭">X</a><object id="pri_small_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/mms_control.swf?12012901" width="400" height="300"><param name="movie" value="{$RESOURCE_URL}swf/mms_control.swf?12012901"/><param name="movie" value="{$RESOURCE_URL}swf/mms_control.swf?11112501"/><param name="allowscriptaccess" value="always"><param name="bgcolor" value="#000000"><param name="allowFullScreen" value="true"><param name="wmode" value="transparent"><param name="flashvars" value="streamUrl='+url+'&streamName='+s_name +' ['+ name+']&keepAliveUrl=keep_alive.php?access_token=' + ACCESS_TOKEN + '&clientCount=0"></object>');	
}
function hg_flv_close()
{
	$('#flv_preview').hide();
	$('#flv_colse').hide();
	$('#pri_small_colorollor').remove();
}
/*流同步*/
function hg_up_stream_create()
{
	var url = "./run.php?mid="+ gMid + "&a=up_stream_create";
	var name = confirm('确定要同步流？');
	if(name==true)
	{
		hg_ajax_post(url,'','','up_stream_create');
	}
	
}
function up_stream_create(obj)
{
	var obj = obj[0];
	if(obj == 'error')
	{
		alert("已经最新，无需同步！");
	}
	else
	{
		window.location.reload();
	}
}
$(function(){
	$("#flv_preview").draggable({containment:'document'});
});
</script>
{js:ios/switch}
<script>
$(function(){
	var i = 1;
	var custom_stream_status = function(id, callback){
		var url = "./run.php?mid=" + gMid + "&a=stream_status&id=" + id + "&infrm=1";
		hg_ajax_post(url,"","", callback);
	}
	var onandoff = function(self, state){
		var tmp = 'mySwitchCallback' + ++i;
		window[tmp]= function(ajax){
					ajax = ajax[0];
					var title = '';
					if(ajax.stream_status == 1){
						title = '已启动';
					}else if(ajax.stream_status == 2){
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
<div class="form_tit">
{template:menu/btn_menu}
</div>
<div class="v_list_show">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<!-- <a href="javascript:void(0);" onclick="hg_up_stream_create()" class="button_4"><strong>同步流</strong></a> -->
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增信号流</strong></a>
</div>
<form>
<div id="stream_hold" style="margin-left:50%;"></div>
<table cellspacing="0" cellpadding="0" width="100%" class="form_table">
	<tr id="item_th" class="h" align="left" valign="middle">
		<th width="30" align="center"></th>
		<th width="80">标识</th>
		<th  class="text_indent" width="100">状态</th>
		<th class="text_indent">信号名称</th>
		<th  class="text_indent" width="220">视频流</th>
		<th width="180"  class="text_indent">操作</th>
	</tr>	
	<tbody id="status">
	{if $list}
       {foreach $list as $k => $v}
		{if $v['ch_id']}
		<tr id="r_{$v['id']}" onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" class="h" align="left" valign="middle">
			<td align="center"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" class="n-h"/></td>
			<td>{$v['ch_name']}</td>
			<td class="text_indent" id="stream_state_{$v['id']}" 1onclick="hg_stream_status({$v['id']});" >
			<div class="need-switch" title="{if $v['s_status'] == '已启动'}已启动{else}未启动{/if}" state="{if $v['s_status'] == '已启动'}1{else}0{/if}" style="cursor:pointer;" vid="{$v['id']}"></div>
			</td>
			<td class="text_indent"><span id="s_name_{$v['id']}">{$v['s_name']}</span></td>
			<td class="text_indent">
			{foreach $v['out_uri'] as $kk => $vv}
				{if $v['s_status'] == "已启动"}
					<span class="channel_stream" style="cursor:pointer;" title="{$vv}" id="out_uri_{$v['id']}_{$kk}" onclick="hg_stream_uri('{$kk}',{$v['id']},'{$v["s_status"]}');">
						{$kk}
						<input type="hidden" id="preview_uri_{$v['id']}_{$kk}" value="{$vv}" />
					</span>
				{else}
					<span class="channel_stream_b" style="cursor:pointer;" title="{$vv}" id="out_uri_{$v['id']}_{$kk}" onclick="hg_stream_uri('{$kk}',{$v['id']},'{$v["s_status"]}');">
						{$kk}
						<input type="hidden" id="preview_uri_{$v['id']}_{$kk}" value="{$vv}" />
					</span>
				{/if}
			{/foreach}
			</td>
			
			<td class="text_indent">
				<a title="" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
				<a onclick="hg_stream_delete_check({$v['id']})" href="javascript:void(0);">删除</a>
			</td>
		</tr>
		 {/if}
		{/foreach}
	{/if}
	</tbody>
</table>
<div class="live_delete">
<input type="checkbox" title="全选" value="infolist" id="checkall" name="checkall" class="n-h" >
<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');">批量删除</a>
</div>
<div>{$pagelink}</div>
</form>
</div>
<div id="flv_preview" style="cursor:move;display:none;width:400px;height:314px;position:absolute;left:355px;top:40px;background:#000000;border:5px solid #B2B2B2;border-radius:3px;"></div>
{template:foot}