<?php 
/* $Id: stream_form.php 16866 2013-01-30 05:18:23Z lijiaying $ */
?>
{template:head}
{css:ad_style}
{css:getBackupInfo}
{css:jPaginate_style}
{js:getBackupInfo}
{js:mms_default}
{js:message}
{js:jquery-ui-1.8.16.custom.min}
{js:jquery.paginate}
<script type="text/javascript">
function input_con(i)
{
	if(!$("#required_" + i).val())
	{
		$('#span_' + i).show();
		$('#sub').attr('disabled','disabled');
	}
	else
	{
		$('#span_' + i).hide();
		$('#sub').removeAttr('disabled');
	}
}
function span_hid(i)
{
	if($("#required_" + i).val())
	{
		$('#span_' + i).hide();
		$('#sub').removeAttr('disabled');
	}
}
 var gTotalInput = '{code} echo count($formdata["other_info"]["input"]);{/code}';
	 gTotalInput = parseInt(gTotalInput) ? parseInt(gTotalInput) : 1;
 function hg_add_input(obj)
 {   
    var pageNum = gTotalInput;

	if($('div[id^=div_input_]').length ==1)
	{
		$('#del_input_0').show();
	}
	
	var sourceName = '来源地址';
	if ($('input[name="wait_relay"]').attr('checked') == 'checked')
	{
		sourceName = '推送地址';
	}
	
	var div = '<div id="div_input_'+pageNum+'" class="div_input clear"><span onclick="hg_add_input(this);" title="继续添加" class="chg_plan_left"></span><span>输出标识</span><input onblur="checkCode(this);" onchange="hg_pushUrl(this);" style="width:73px;" type="text" value="" id="name_'+pageNum+'" name="name_'+pageNum+'" /><span name="source_type">'+sourceName+'</span><input id="no_uri_'+pageNum+'" style="width:250px;" type="text" value="" onchange="hg_get_bit(this,'+pageNum+');"  name="uri_'+pageNum+'" /> <span class="chg_plan_wj" id="backup_file_'+pageNum+'" onclick="hg_getBackupInfo(this,'+pageNum+');"></span><img style="display:none;" id="load_img_'+pageNum+'" src="{$RESOURCE_URL}bit_loading.gif" /><span id="bitrate_'+pageNum+'"></span><input type="hidden" name="bitrate_'+pageNum+'" id="hidden_bitrate_'+pageNum+'" /><input type="hidden" name="counts[]" /><span id="del_input_'+pageNum+'" onclick="hg_del_input(this, '+pageNum+');" title="删除"  class="chg_plan_right"></span>'+'</div>';
	
	$(obj).parent().parent().append(div);
	
	if ($('#action').val() == 'update' && $('input[name^="type"]').val() == 1)
	{
		$('#no_uri_' + pageNum).attr('disabled', 'disabled');
	}
	
	gTotalInput ++ ;
	hg_resize_nodeFrame();
 }
 function hg_del_input(obj, i)
 {
	 var div_id = $(obj).parent('div').attr('id');

	 $('#' + div_id).remove();

	 if ($('#backupInfoBox_' + i).attr('id') == 'backupInfoBox_' + i)
	 {
		 $('#backupInfoBox_' + i).remove();
	 }
	 
	 var row_num = div_id.split('_');

	 rebuild_input_name(row_num[2]);

	 if($('div[id^=div_input_]').length ==1)
	 {
		$('#del_input_0').hide();
	 }

	 gTotalInput -- ;
	 hg_resize_nodeFrame();
 }
 function rebuild_input_name(index)
 {
	for(i = parseInt(index)+1; i <= gTotalInput; i++)
	{
		$('input[name=wait_relay_'+i+']').attr('name', 'wait_relay_'+(i-1));
		$('input[name=audio_only_'+i+']').attr('name', 'audio_only_'+(i-1));
		$('input[name=name_'+i+']').attr('name', 'name_'+(i-1));
		$('input[name=bitrate_'+i+']').attr('name', 'bitrate_'+(i-1));
		$('input[name=uri_'+i+']').attr('name', 'uri_'+(i-1));
		$('#div_input_'+i).attr('id', 'div_input_'+(i-1));
		
		$('#del_input_'+i).removeAttr('onclick');
		$('#backup_file_'+i).removeAttr('onclick');
		$('#no_uri_'+i).removeAttr('onchange');

		$('#del_input_'+i).attr('onclick', 'hg_del_input(this,'+(i-1)+')');
		$('#backup_file_'+i).attr('onclick', 'hg_getBackupInfo(this,'+(i-1)+')');
		$('#no_uri_'+i).attr('onchange', 'hg_get_bit(this,'+(i-1)+')');
		
		$('#del_input_'+i).attr('id', 'del_input_'+(i-1));
		$('#backup_file_'+i).attr('id', 'backup_file_'+(i-1));
		$('#no_uri_'+i).attr('id', 'no_uri_'+(i-1));
		
		$('input[name=id_'+i+']').attr('name', 'id_'+(i-1));
		$('input[id=name_'+i+']').attr('id', 'name_'+(i-1));
		
		$('input[name=source_id_'+i+']').attr('name', 'source_id_'+(i-1));
		$('input[name=chg_stream_id_'+i+']').attr('name', 'chg_stream_id_'+(i-1));
		
		$('span[id=bitrate_'+i+']').attr('id', 'bitrate_'+(i-1));
		$('input[id=hidden_bitrate_'+i+']').attr('id', 'hidden_bitrate_'+(i-1));
		
		$('img[id=load_img_'+i+']').attr('id', 'load_img_'+(i-1));
		if ($('#backupInfoBox_'+i).attr('id'))
		{
			$('#backupInfoBox_'+i).find('input[name^="source_name"]').attr('name', 'source_name_'+(i-1)+'[]');
			$('#backupInfoBox_'+i).find('input[name^="backup_title"]').attr('name', 'backup_title_'+(i-1)+'[]');
			$('#backupInfoBox_'+i).attr('id', 'backupInfoBox_'+(i-1));	
			$('#backupList_'+i).attr('id', 'backupList_'+(i-1));
			$('#getBackup_'+i).attr('id', 'getBackup_'+(i-1));
			$('#pageBox_'+i).attr('id', 'pageBox_'+(i-1));
			$('#postBackupInfo_ul_'+i).attr('id', 'postBackupInfo_ul_'+(i-1));
			$('#backupCount_'+i).attr('id', 'backupCount_'+(i-1));
			
		}
	}
 }
function hg_beibo_url(obj)
{
	var  father_obj = hg_find_nodeparent(obj,'DIV');
	var uri_obj = $(father_obj).find("input[id^='no_uri_']");
	var uri_value = $(obj).val();
	$(uri_obj).val(uri_value);
}

function checkCode(obj)
{
	var gCode =/^[A-Za-z0-9_]+$/;
	var code = $(obj).val();
	if (code)
	{
		if(!gCode.test(code))
		{
			$('#important_1').css('color','#F79607');
			$('#sub').attr('disabled','disabled');
		}
		else
		{
			$('#important_1').css('color','#BEBEBE');
			$('#sub').removeAttr('disabled');
		}
	}
}

/*获得码流*/
var gStreamId = '';
function hg_get_bit(obj,i)
{
	$('#load_img_' + i).show();
	gStreamId = i;
	var uri = $(obj).val();
	if(uri)
	{
		if(!i)
		{
			i = 10;
		}
		var url = './run.php?mid=' + gMid + '&a=getBitrate&uri=' + uri + '&stream_id=' + i;
		hg_ajax_post(url,'','','hg_getBitrate');
	}
}
function hg_getBitrate(obj)
{
	$('#load_img_' + gStreamId).hide();
	var bit= obj[0].bitrate;
	if(bit)
	{
		$('#bitrate_'+gStreamId).html('<span style="font-size:10px;margin-left:-12px;margin-right:0px;">码流:'+bit+'</span>');
		$('#hidden_bitrate_'+gStreamId).val(bit);
	}
	else
	{
		$('#hidden_bitrate_'+gStreamId).remove();
		$('#bitrate_'+gStreamId).html('<span style="font-size:10px;margin-left:-12px;margin-right:0px;">码流</span><input type="text" style="width:24px;" name="bitrate_'+gStreamId+'" value="" />');
	}
	
}

$(function(){
	if($('div[id^=div_input_]').length ==1)
	{
		$('#del_input_0').hide();
	}
});
/*标记音频设置*/
function hg_audio_temp()
{
	$('#audio_temp').val(1);
}

/*信号类型选择*/
function hg_streamType(type)
{
	if (type == 'wait_relay')
	{
		if (!$('input[name="ch_name"]').val())
		{
			jAlert('请填写信号标识');
			
			$('#wait_relay_flag').removeAttr('checked');
			return false;
		}
		if ($('#wait_relay_flag').attr('checked') == 'checked')
		{	
			$('span[name^="source_type"]').html('推送地址');
			var i = 0;
			$('div[id^="div_input_"]').each(function(){
				if ($('#name_'+ i).val())
				{
					hg_pushUrl($('#name_'+ i));
				}
				i ++ ;
			});

			$('input[name^="source_name_"]').attr('disabled','disabled');
			$('input[name^="backup_title_"]').attr('disabled','disabled');
		}
		else
		{
			$('span[name^="source_type"]').html('来源地址');
			$('input[name^="uri_"]').val('');
			
			$('input[name^="source_name_"]').removeAttr('disabled');
			$('input[name^="backup_title_"]').removeAttr('disabled');
		}
		
	}
	else if (type == 'type')
	{
		$('#wait_relay_flag').removeAttr('checked');
		$('input[name^="uri_"]').val('');
		if ($('#wait_relay_flag').attr('checked') != 'checked' && $('#type_flag').attr('checked') == 'checked')
		{
			$('input[name^="source_name_"]').removeAttr('disabled');
			$('input[name^="backup_title_"]').removeAttr('disabled');
			
			var input_length = $('div[id^="div_input"]').length;
			
			for (var i=0; i < input_length; i++)
			{
				$('#backupInfoBox_' + i).show();
				
				hg_resize_nodeFrame();
			}
		}
		else
		{
			$('input[name^="source_name_"]').attr('disabled','disabled');
			$('input[name^="backup_title_"]').attr('disabled','disabled');
			var input_length = $('div[id^="div_input"]').length;
			
			for (var i=0; i < input_length; i++)
			{
				$('#backupInfoBox_' + i).hide();
				
				hg_resize_nodeFrame();
			}
		}
	}
	
}
/*获取推送流地址*/
function hg_pushUrl(obj)
{
	if ($('input[name="wait_relay"]').attr('checked') == 'checked')
	{
		var s_name = $('input[name="ch_name"]').val();
		var name = $(obj).val();
		var protocol = "{$_configs['wowza']['input']['protocol']}";
		var address = "{$_configs['wowza']['core_input_server']['host']}";
		var type = "{$_configs['wowza']['input']['type']}";
		var url = protocol+address+'/'+type+'/'+s_name+'.'+name;
		$(obj).parent().find('input[name^="uri_"]').val(url);
	}
}
/*获取备播文件*/
function hg_getBackupInfo(obj, i)
{	
	/*
	if ($('#type_flag').attr('checked') != 'checked')
	{
		return;
	}
	*/
	
	if ($('#action').val() == 'create')
	{
		$('input[name="type"]').val(1);
		$(obj).parent().find('input[name^="uri_"]').val('');
	}
	else
	{
		if ($('input[name="type"]').val() == 1)
		{
			$(obj).parent().find('input[name^="uri_"]').val('');
		}
	}
	
	hg_backupOrder('getBackup_' + i);
	
	var s_name = $('input[name="ch_name"]').val();
	if (!s_name)
	{
		jAlert('信号标识不能为空');
		return false;
	}
	
	var name = $('#name_' + i).val();
	if (!name)
	{
		jAlert('输出标识不能为空');
		return false;
	}
	
	if ($('#backupList_' + i).html())
	{
		
	}
	else
	{
		hg_getBackupInfoBox(i);
	}
	
/*	
	var protocol = "{$_configs['mms']['file']['protocol']}";
	var address = "{$_configs['mms']['file']['wowzaip']}";
	var type = "{$_configs['mms']['file']['appName']}";
	var suffix = "{$_configs['mms']['file']['suffix']}";
	var url = protocol+address+'/'+type+'/'+s_name+'.'+name + suffix;
	$('#no_uri_' + i).val(url);
*/	
	var backupInfoBox = $('#backupInfoCon').html();
	if ($(obj).parent().next().attr('id') == 'backupInfoBox_'+i)
	{
		if ($('#backupInfoBox_' + i).css('display') == 'block')
		{
			$('#backupInfoBox_' + i).hide();
		}
		else
		{
			$('#backupInfoBox_' + i).css('display','block');
		}
		hg_resize_nodeFrame();
		return false;
	}
	
	if ($.trim(backupInfoBox))
	{
		$(obj).parent().after(backupInfoBox);
	}
	$(obj).parent().next().attr('id', 'backupInfoBox_'+i);
	$(obj).parent().next().find('ul[id^="getBackup"]').attr('id', 'getBackup_' + i);
	$(obj).parent().next().find('input[name^="source_name"]').attr('name', 'source_name_'+i+'[]');
	$(obj).parent().next().find('input[name^="backup_title"]').attr('name', 'backup_title_'+i+'[]');
}
var gI = '';
function hg_getBackupInfoBox(i)
{
	gI = i;
	var offset = 0;
	var count = '{$_configs["stream2BackupCount"]}';
	var url = './run.php?mid=' + gMid + '&a=getBackupInfo&offset=' + offset + '&counts=' + count + '&server_id=' + $('#server_id').val();
	hg_ajax_post(url);
}
function getBackupInfo_back(html)
{
	if (html)
	{
		$('#backupInfoBox_' + gI).append(html);
		$('#backupList').attr('id', 'backupList_' + gI);
		$('#pageBox').attr('id', 'pageBox_' + gI);
		$('#backupCount').attr('id', 'backupCount_' + gI);
		$('#postBackupInfo_ul').attr('id', 'postBackupInfo_ul_' + gI);
		hg_backupPage(gI);
		hg_resize_nodeFrame();
	}
}

</script>
<script type="text/javascript">
/*分页*/
function hg_backupPage(pageId)
{
	$("#pageBox_" + pageId).paginate({
			count 		: $('#backupCount_' + pageId).val(),
			start 		: 1,
			display     : 10,
			border					: true,
			border_color			: '#fff',
			text_color  			: '#fff',
			background_color    	: '#498adb',
			border_hover_color		: '#ccc',
			text_hover_color  		: '#000',
			background_hover_color	: '#D8E8F5',
			images					: false,
			mouse					: 'slide',
			onChange     			: function(page){
										hg_backupPageBox(page, pageId);
									}
	});
}

var mPageId = '';
function hg_backupPageBox(offset, pageId)
{
	mPageId = pageId;
	var count = '{$_configs["stream2BackupCount"]}';
	if (offset > 1)
	{
		offset = (offset-1)*count; 
	}
	else if (offset == 1)
	{
		offset = 0;
	}
	var url = './run.php?mid=' + gMid + '&a=backupPage&offset=' + offset + '&counts=' + count + '&server_id=' + $('#server_id').val();

	hg_ajax_post(url);
}
function backupPage_back(html)
{
	if (html)
	{
		$('#postBackupInfo_ul_' + mPageId).html(html);
	}
}
</script>
<script type="text/javascript">
	function hg_checked_server_stream_count(obj)
	{
		var id = $(obj).attr('attrid');
		if (id == 0)
		{
			$('#over_count').html('');
			return;
		}
		var url = "run.php?mid=" + gMid + "&a=checked_server_stream_count" + "&id=" + id;
		hg_ajax_post(url,'','','checked_server_callback');
	}
	
	function checked_server_callback(obj)
	{
		if (obj)
		{
			hg_setBackupInfoBox();
			$('#over_count').html('剩余'+obj+'条');
		}
		else
		{
			$('#over_count').html('');
		}
	}
	
	function hg_setBackupInfoBox()
	{
		var offset = 0;
		var count = '{$_configs["stream2BackupCount"]}';
		var url = './run.php?mid=' + gMid + '&a=setBackupInfo&offset=' + offset + '&counts=' + count + '&server_id=' + $('#server_id').val();
		hg_ajax_post(url);
	}
	
	var gBackupInfoHtml = '';
	function setBackupInfo_back(html)
	{
		hg_set_backup_info(html);
	}
	
	function hg_set_backup_info(html)
	{
		var counts = $('div[id^="backupInfoBox_"]').length;
		if (counts)
		{
			$('#setBackupInfoBox').html(html);
			if ($.trim($('#backupList').find('div').html()) == '暂无记录')
			{
				jAlert('暂无备播文件请选择其他服务器');
				return;
			}
			$('#backupList').attr('id', 'backupList_-1');
			$('#pageBox').attr('id', 'pageBox_-1');
			$('#backupCount').attr('id', 'backupCount_-1');
			$('#postBackupInfo_ul').attr('id', 'postBackupInfo_ul_-1');
			hg_backupPage(-1);
			for (var i = 0; i < counts; i ++)
			{
				if ($('#backupInfoBox_' + i).css('display') == 'none')
				{
					$('#backupInfoBox_' + i).show();
				}
				
				$('#getBackup_' + i).html('');
				$('#backupList_' + i).find('span[class="count"]').html($('#backupList_-1').find('span[class="count"]').html());
				$('#pageBox_' + i).html($('#pageBox_-1').html());
				$('#backupCount_' + i).val($('#backupCount_-1').val());
				$('#postBackupInfo_ul_' + i).html($('#postBackupInfo_ul_-1').html());
				hg_backupPage(i);
				hg_resize_nodeFrame();
			}
			$('#setBackupInfoBox').html('');
		}
	}
</script>
{if $a}
	{code}
//	hg_pre($formdata);
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
/*hg_pre($formdata);*/
	$other_info_file = $other_info['file'];
	$other_info = $other_info['input'];

{/code}
<div class="ad_middle">
<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
	<h2>{$optext}信号流信息</h2>
	<ul class="form_ul">
		<li class="i">
			<!--
<div class="form_ul_div">
				<span class="title">信号名称：</span>
				<input onblur="input_content_color(2);" onfocus="input_content_color(2);" id="required_2" type="text" name="s_name" value="{$s_name}"/>
				<font class="important" id="important_2">必填</font>
			</div>
-->
			<div class="form_ul_div">
				<span class="title">信号标识：</span>
				<input {if $action == "update"}disabled {/if} onblur="input_content_color(1),checkCode(this);" onfocus="input_content_color(1);" id="required_1" type="text" name="ch_name" value="{$ch_name}"/>
				<input type="hidden" name="ch_name_hidden" value="{$ch_name}"/>
				<font class="important" id="important_1">必填，包含英文，数字</font>
			</div>
		</li>
		{if !empty($server_info)}
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">服务器：</span>
				{code}
					$server_source = array(
						'class' => 'down_list i',
						'show' => 'item_shows_',
						'width' => 100,/*列表宽度*/		
						'state' => 0, /*0--正常数据选择列表，1--日期选择*/
						'is_sub'=>1,
						'onclick' => 'hg_checked_server_stream_count(this)',
					);
					
					$server_id = $server_id ? $server_id : 0;
					$server[$server_id] = '--请选择--';
					foreach ($server_info AS $v)
					{
						$server[$v['id']] = $v['name'];
					}
				{/code}
			{if !$id}
				{template:form/search_source,server_id,$server_id,$server,$server_source}
			{else}
				<div class="down_list i" style="width:100px">
					<span class="input_left"></span>
					<span class="input_right"></span>
					<span class="input_middle">
						<a><em></em><label class="overflow">{$server[$server_id]}</label></a>
					</span>
				</div>
				<input type="hidden" name="server_id" value="{$server_id}" id="server_id" />
			{/if}
				<span id="over_count" style="margin-top: 4px;float: right;color: #bebebe;"></span>
			</div>
		</li>
		{/if}
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title form_ul_div_l">信号类型：</span>
				<div class="form_ul_div clear" style="margin:0;">
					<label>
						<input  onclick="hg_streamType('wait_relay');" id="wait_relay_flag"  type="checkbox" class="n-h" value="1" {if $wait_relay}checked="checked"{/if} name="wait_relay" {if $action == 'update'}disabled="disabled"{/if} />
						<span>接受推送</span>
					</label>
					{if $action == 'update'}<input type="hidden" name="wait_relay" value="{$wait_relay}" />{/if}
				</div>
			</div>
			<!--
<div class="form_ul_div clear">
				<span class="title form_ul_div_l"></span>
				<div class="form_ul_div clear">
					<label>
						<input onclick="hg_streamType('type');" id="type_flag" type="checkbox" class="n-h" name="type" value=1 {if $type}checked="checked"{/if} {if $action == 'update'}disabled="disabled"{/if} />
						<span>备播文件</span>
					</label>
					{if $action == 'update'}<input type="hidden" name="type" value="{$type}" />{/if}
				</div>
			</div>
-->
		</li>
		<li id="con_li" class="i clear">
		<div class="form_ul_div clear">
			<span class="title form_ul_div_l">直播流：</span>
			<div class="form_ul_div_r" style="padding-top:5px;">

		{if $action == 'update'}
				{foreach $other_info AS $kk => $vv}
				<div id="div_input_{$kk}" class="div_input clear">
					<span id="add_input" onclick="hg_add_input(this);" title="添加" class="chg_plan_left"></span>
					<span>输出标识</span>
					<input style="width:73px;" type="text" disabled id="name_{$kk}" name="name_{$kk}" value="{$vv['name']}" />
					<span name="source_type">{if $vv['wait_relay']}推送地址{else}来源地址{/if}</span>
					<input type="text" id="no_uri_{$kk}" onchange="hg_get_bit(this,{$kk});" name="uri_{$kk}" value="{$vv['uri']}" style="width:250px" {if $type} disabled="disabled" {/if} />
					
					<span class="chg_plan_wj" id="backup_file_{$kk}" onclick="hg_getBackupInfo(this, {$kk});"></span>
					<img style="display:none;" id="load_img_{$kk}" src="{$RESOURCE_URL}bit_loading.gif" />
					<span id="bitrate_{$kk}"></span>
					<input type="hidden" name="bitrate_{$kk}" id="hidden_bitrate_{$kk}" value="{$vv['bitrate']}" />
					<span onclick="hg_del_input(this, {$kk});" id="del_input_{$kk}" title="删除" class="chg_plan_right"></span>
					<input type="hidden" name="counts[]" id="counts"/>
					<input type="hidden" name="id_{$kk}" value="{$vv['id']}"/>
					<input type="hidden" name="name_{$kk}" value="{$vv['name']}"/>
					<input type="hidden" name="source_id_{$kk}" value="{$other_info_file[$kk]['source_id']}"/>
					<input type="hidden" name="chg_stream_id_{$kk}" value="{$other_info_file[$kk]['chg_stream_id']}"/>
				</div>
				{if $vv['source_name'] && $vv['backup_title']}
				<div id="backupInfoBox_{$kk}" class="backupInfoBox" style="display:none;">
					<ul id="getBackup_{$kk}" class="getBackupInfo">
					{foreach $vv['source_name'] AS $k => $v}
						<li onmouseover="hg_getBackupDeleteShow(this, 1);" onmouseout="hg_getBackupDeleteShow(this, 0);">
							<input type="hidden" name="source_name_{$kk}[]" value="{$v}" />
							<input type="hidden" name="backup_title_{$kk}[]" value="{$vv['backup_title'][$k]}" />
							<span class="getBackupTitle">{$vv['backup_title'][$k]}</span>
							<span delName="getBackupDelete[]" class="getBackupDelete" title="删除" onclick="hg_getBackupDelete(this);"></span>
						</li>
					{/foreach}
					</ul>
					
				</div>
				{/if}
				{/foreach}
		{else}
				<div id="div_input_0" class="div_input clear">
					<span id="add_input" onclick="hg_add_input(this);" title="添加" class="chg_plan_left"></span>
					<span>输出标识</span><input onblur="checkCode(this);" onchange="hg_pushUrl(this);" style="width:73px;" type="text" id="name_0" name="name_0" value="" />
					<span name="source_type">来源地址</span>
					<input type="text" id="no_uri_0" name="uri_0" value="" onchange="hg_get_bit(this,0);" style="width:250px"/>	
					<span class="chg_plan_wj" id="backup_file_0" onclick="hg_getBackupInfo(this,0);"></span>
					<img style="display:none;" id="load_img_0" src="{$RESOURCE_URL}bit_loading.gif" />
					<span id="bitrate_0"></span>
					<input type="hidden" name="bitrate_0" id="hidden_bitrate_0" value="" />
					<span style="display:none;" onclick="hg_del_input(this,0);" id="del_input_0" title="删除" class="chg_plan_right"></span>
					<input type="hidden" name="counts[]" />
				</div>
		{/if}
			</div>
			<!-- 备播文件 -->
			<div id="backupInfoCon" style="display:none;">
				<div id="backupInfoBox" class="backupInfoBox">
					<ul id="getBackup" class="getBackupInfo"></ul>
				</div>
			</div>
			
			<!-- 选择服务器重置备播文件 -->
			<div id="setBackupInfoBox" style="display:none;"></div>
		</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title form_ul_div_l">选项：</span>
				<div class="form_ul_div_r">
					<label><input type="checkbox" onclick="hg_audio_temp();" class="n-h" value="1" {if $other_info[0]['audio_only']}checked="checked"{/if} name="audio_only"><span>纯音频流</span></label>
					<input type="hidden" name="audio_temp" value="" id="audio_temp" />
				</div>
			</div>
		</li>
	</ul>
	
	<input type="hidden" name="a" id="action" value="{$action}" />
	<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<input type="hidden" name="type" value="{$type}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
<div id="stream_form" style="margin-left:40%;"></div>
{template:foot}