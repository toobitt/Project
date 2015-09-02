<?php 
/* $Id: stream_form.php 10918 2012-08-24 05:33:12Z lijiaying $ */
?>
{template:head}
{css:ad_style}
{js:mms_default}
{js:message}
<script type="text/javascript">
function input_con(i)
{
	if(!$("#required_" + i).val())
	{
		$('#span_' + i).show();
		$('#sub').attr('disabled','disabled')
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
 var gTotalInput = '{code} echo count($formdata["other_info"]);{/code}';
	 gTotalInput = parseInt(gTotalInput) ? parseInt(gTotalInput) : 1;
 function hg_add_input()
 {   
    var pageNum = gTotalInput;

	if($('div[id^=div_input_]').length ==1)
	{
		$('#del_input_0').show();
	}

	var div = '<div id="div_input_'+pageNum+'" class="div_input clear"><span onclick="hg_add_input();" title="继续添加" class="chg_plan_left"></span><span>输出标识</span><input style="width:73px;" type="text" value="" id="name_'+pageNum+'" name="name_'+pageNum+'" /><span>来源地址</span><input id="no_uri_'+pageNum+'" style="width:250px;" type="text" value="" onchange="hg_get_bit(this,'+pageNum+');"  name="uri_'+pageNum+'" /> <span class="chg_plan_wj"></span><img style="display:none;" id="load_img_'+pageNum+'" src="{$RESOURCE_URL}bit_loading.gif" /><span id="bitrate_'+pageNum+'"></span><input type="hidden" name="bitrate_'+pageNum+'" id="hidden_bitrate_'+pageNum+'" /><input type="hidden" name="counts[]" /><span id="del_input_'+pageNum+'" onclick="hg_del_input(this);" title="删除"  class="chg_plan_right"></span>'+'</div>';
	
	$('#all_input').append(div);

	gTotalInput ++ ;
 }
 function hg_del_input(obj)
 {
	 var div_id = $(obj).parent('div').attr('id');
	 $('#' + div_id).remove();
	 var row_num = div_id.split('_');
	 rebuild_input_name(row_num[2]);

	 if($('div[id^=div_input_]').length ==1)
	 {
		$('#del_input_0').hide();
	 }

	 gTotalInput -- ;
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
		$('#del_input_'+i).attr('id', 'del_input_'+(i-1));

		$('input[name=id_'+i+']').attr('name', 'id_'+(i-1));
		$('input[id=name_'+i+']').attr('id', 'name_'+(i-1));
		$('span[id=bitrate_'+i+']').attr('id', 'bitrate_'+(i-1));
		$('input[id=hidden_bitrate_'+i+']').attr('id', 'hidden_bitrate_'+(i-1));
		$('input[id=no_uri_'+i+']').attr('id', 'no_uri_'+(i-1));
		$('img[id=load_img_'+i+']').attr('id', 'load_img_'+(i-1));
		$('input[onchange="hg_get_bit(this,'+i+');"]').attr('onchange', 'hg_get_bit(this,'+(i-1)+')');
	}
 }
function hg_beibo_url(obj)
{
	var  father_obj = hg_find_nodeparent(obj,'DIV');
	var uri_obj = $(father_obj).find("input[id^='no_uri_']");
	var uri_value = $(obj).val();
	$(uri_obj).val(uri_value);
}

function checkCode()
{
	var gCode =/^[A-Za-z0-9]+$/;
	var code = $('#required_1').val();
	if(!gCode.test(code))
	{
	/*	alert("信号名必须为字母、数字"); */
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
		$('#bitrate_'+gStreamId).html('<span style="font-size:10px;margin-left:-12px;margin-right:1px;">码流:'+bit+'</span>');
		$('#hidden_bitrate_'+gStreamId).val(bit);
	}
	else
	{
		$('#hidden_bitrate_'+gStreamId).remove();
		$('#bitrate_'+gStreamId).html('<span style="font-size:10px;margin-left:-12px;margin-right:1px;">码流</span><input type="text" style="width:22px;" name="bitrate_'+gStreamId+'" value="" />');
	}
	
}
/*
function hg_update_stream()
{
	for(var i=0; i<$('div[id^=div_input_]').length; i++)
	{
		if($('#required_2').val() && $('#required_1').val() && $('#name_' + i).val() && $('#no_uri_' + i).val())
		{
			$('#stream_form').html('正在提交，请稍候...');
		}
	}
	return hg_ajax_submit('editform');
}
function hg_overEditStream(obj)
{
	var obj = eval('('+obj+')');
	if(obj)
	{
		hg_update_channel_stream();
	}
}
function hg_update_channel_stream()
{
	var action = $('#action').val();
	if(action == 'update_stream')
	{
		var id= $('input[name=id]').val();
		var url = './run.php?mid=' + gMid + '&a=DelChannelStreamName&id=' + id;
		hg_ajax_post(url);
	}
	else
	{
		location.href = './run.php?mid='+gMid+'&infrm=1';
	}
	$('#stream_form').hide();
}
function hg_stream_href_list(obj)
{
	if(obj)
	{
		location.href = './run.php?mid='+gMid+'&infrm=1';
	}
}
*/
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
</script>
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<div class="ad_middle">
<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
	<h2>{$optext}信号流信息</h2>
	<ul class="form_ul">
		<li class="i">
			<div class="form_ul_div">
				<span class="title">信号名称：</span>
				<input onblur="input_content_color(2);" onfocus="input_content_color(2);" id="required_2" type="text" name="s_name" value="{$s_name}"/>
				<font class="important" id="important_2">必填</font>
			</div>
			<div class="form_ul_div">
				<span class="title">信号标识：</span>
				<input {if $action == "update"}disabled {/if} onblur="input_content_color(1),checkCode();" onfocus="input_content_color(1);" id="required_1" type="text" name="ch_name" value="{$ch_name}"/>
				<input type="hidden" name="ch_name_hidden" value="{$ch_name}"/>
				<font class="important" id="important_1">必填，包含英文，数字</font>
			</div>
		</li>
		<li id="con_li" class="i clear">
		<div class="form_ul_div clear">
			<span class="title form_ul_div_l">直播流：</span>
			<div class="form_ul_div_r" style="padding-top:5px;">
		{if $action == 'update'}
				{foreach $other_info as $kk => $vv}
				<div id="div_input_{$kk}" class="div_input clear">
					<span id="add_input" onclick="hg_add_input();" title="添加" class="chg_plan_left"></span>
					<span>输出标识</span>
					<input style="width:73px;" type="text" disabled id="name_{$kk}" name="name_{$kk}" value="{$vv['name']}" />
					<span>来源地址</span>
					<input type="text" id="no_uri_{$kk}" onchange="hg_get_bit(this,{$kk});" name="uri_{$kk}" id="uri" value="{$vv['uri']}" style="width:250px"/>
					
					<span class="chg_plan_wj"></span>
					<img style="display:none;" id="load_img_{$kk}" src="{$RESOURCE_URL}bit_loading.gif" />
					<span id="bitrate_{$kk}"></span>
					<input type="hidden" name="bitrate_{$kk}" id="hidden_bitrate_{$kk}" value="{$vv['bitrate']}" />
					<span onclick="hg_del_input(this);" id="del_input_{$kk}" title="删除" class="chg_plan_right"></span>
					<input type="hidden" name="counts[]" id="counts"/>
					<input type="hidden" name="id_{$kk}" value="{$vv['id']}"/>
					<input type="hidden" name="name_{$kk}" value="{$vv['name']}"/>
				</div>
				{/foreach}
		{else}
				<div id="div_input_0" class="div_input clear">
					<span id="add_input" onclick="hg_add_input();" title="添加" class="chg_plan_left"></span>
					<span>输出标识</span><input  style="width:73px;" type="text" id="name_0" name="name_0" value="" />
					<span>来源地址</span>
					<input type="text" id="no_uri_0" name="uri_0" value="" onchange="hg_get_bit(this,0);" style="width:250px"/>	
					<span class="chg_plan_wj"></span>
					<img style="display:none;" id="load_img_0" src="{$RESOURCE_URL}bit_loading.gif" />
					<span id="bitrate_0"></span>
					<input type="hidden" name="bitrate_0" id="hidden_bitrate_0" value="" />
					<span style="display:none;" onclick="hg_del_input();" id="del_input" title="删除" class="chg_plan_right"></span>
					<input type="hidden" name="counts[]" />
				</div>
		{/if}
				<div id="all_input"></div>
			</div>
		</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title form_ul_div_l">选项：</span>
				<div class="form_ul_div_r">
					<div class="form_ul_div clear" style="margin:0;"><label><input type="checkbox" class="n-h" value="1" {if $other_info[0]['wait_relay']}checked{/if} name="wait_relay"><span>接受推送</span></label></div>
					<div class="form_ul_div clear" style="margin-bottom:0;">
					<label><input type="checkbox" onclick="hg_audio_temp();" class="n-h" value="1" {if $other_info[0]['audio_only']}checked{/if} name="audio_only"><span>纯音频流</span></label>
					<input type="hidden" name="audio_temp" value="" id="audio_temp" />
					</div>
				</div>
			</div>
		</li>
	</ul>

<input type="hidden" name="a" id="action" value="{$action}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</br>
<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
<div id="stream_form" style="margin-left:40%;"></div>
{template:foot}