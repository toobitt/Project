<?php 
/* $Id: channel_form.php 9557 2012-06-02 09:28:39Z lijiaying $ */
?>
{template:head}
{css:ad_style}
{js:mms_default}
{js:input_file}
{js:message}
<script type="text/javascript">
var gChannel_id;
 function hg_disabled_pri()
 {
	 var value = $("#stream_id").val();
	 if(value != -1)
	 {
		 $('#'+value).attr('disabled', 'disabled');
		 if(gChannel_id)
		 {
			$('#'+gChannel_id).attr('disabled', false);
		 }
		 gChannel_id = value;
	 }
 }
 $(function(){
	gChannel_id = $('#primary_stream').val();
	 $('#'+gChannel_id).attr('disabled', 'disabled');
 });
 function control_beibo_max(obj)
 {
	 var count = 2;
	 var num = $(':checkbox[name="beibo[]"]:checked').length;
	 if(num > count)
	 {
		 alert('暂时支持两个备播信号');
		 $('#'+obj.id).attr('checked', false);
	 }
 }

function  hg_show_tip()
{
	$('#alert_tip').css('display','inline-block');
	setTimeout(function(){
	$('#alert_tip').fadeOut(1500);
	},3000);
/*	var name=confirm("确定修改回看时间，流将会被重启");*/
}
function  hg_show_tip1()
{
	$('#alert_tip1').css('display','inline-block');
	setTimeout(function(){
	$('#alert_tip1').fadeOut(1500);
	},3000);
/*	var name=confirm("确定修改延时时间，流将会被重启");*/
}


function hg_channel_return()
{
	$('#channel_form').append('正在提交，请稍候...');
}
function checkCode()
{
	var gCode =/^[A-Za-z0-9]+$/;
	var code = $('#required_1').val();
	if(!gCode.test(code))
	{
	/*	alert("台号必须为字母、数字");*/
	} 

}
function hg_stream_select()
{
	var id = $("#stream_id").val();
	if(id == -1)
	{
		hg_select_choose();
	}
	else
	{
		var url = "./run.php?mid="+gMid+"&a=getUriname&id="+id;
		hg_ajax_post(url,'','','hg_stream_name_input');
	}

}
function hg_stream_name_input(obj)
{
	$('#uri_name').html('');
	var n = 0;
	var checked ='';
	$("#main_stream").css('display','block');
	$("#main_stream").html('');
	var str = '';
	for(var i in obj)
	{
		$('#uri_name').append('<label><input type="checkbox"  class="n-h" checked="checked"  onclick="displayCheckbox(this,\'' + i + '\');"   name="stream_name[]" value="'+i+'" />'+'<span title="'+obj[i]+'">'+i+'</span></label>');
		if(!n)
		{
			checked = 'checked';
		}
		str += '<label id="main_' + i + '"><input type="radio" ' + checked + ' name="main_stream_name[]" class="n-h" value="'+i+'" /><span class="s">'+i+'</span></label>';
		n++;		
	}
	$("#main_stream").html('<span class="title">主信号：</span>' + str);
	displayCheckbox('','');

}

function hg_check_only(e,name)
{
	$("input[name^="+name+"]").each(function(){
		$(this).removeAttr('checked');	
	});
	$(e).attr('checked','checked');
}

function hg_checkInput()
{
	var num = 0;
    $('input[name="stream_name[]"]').each(function(){
	    if($(this).attr('checked') == 'checked')
		{
		   num++;
		}
	});
	return num;
}

function displayCheckbox(e,vv)
{
    if(hg_checkInput() == 1)
	{
	    $('#is_live').show();
		$('#is_live_checked').click(hg_beibo_stream_show());
		$('#is_live_checked').attr('checked','checked');
		$('#is_live').removeAttr('disabled');
	}
	else
	{
	    $('#is_live').hide();
		$('#is_live_checked').removeAttr('checked');
		$('#is_live').attr('disabled','disabled');
	}
	if(!hg_checkInput())
	{
		alert('流不能为空，请至少选择一条流！');
	/*	$('input[name="stream_name[]"]').each(function(){
			$(this).attr('checked','checked');
		});
		displayCheckbox();*/
	}
	if(e && vv)
	{
		if($(e).attr('checked'))
		{
			if(!$("#main_"+vv).html())
			{
				var str = '<label id="main_' + vv + '"><input type="radio" name="main_stream_name[]" class="n-h" value="' + vv + '" /><span class="s">' + vv + '</span></label>';
				$("#main_stream").append(str);
			}
		}
		else
		{
			if(hg_checkInput())
			{
				$("#main_" + vv).remove();
			}	
		}
		var i = $('input[name="main_stream_name[]"]:checked').length;
		if(!i)
		{
			$($('input[name="main_stream_name[]"]')[0]).attr('checked','checked');
		}
	}
}

function hg_select_choose()
{
	$('#uri_name').html('');
	$('#is_live').hide();
}
function hg_beibo_stream_show()
{
	$('#beibo_stream').show();
}
function hg_beibo_stream_hide()
{
	$('#beibo_stream').hide();
}

function hg_beibo_stream_checkbox(is_live)
{
	if(is_live)
	{
		hg_beibo_stream_show();
	}
	else
	{
		hg_beibo_stream_hide();
	}

}

function hg_text_change(obj,id){
	$(obj).html($(id).val()) ;
}

$(function(){
	bindFileInput('file_input','f_file','file_text');
	var is_live = '{$formdata["is_live"]}';
	var a = $('#action').val();
	if(is_live == 0 && a == 'update' )
	{
		$('#beibo_stream').hide();
	}

});
function hg_logo_value()
{
	$('#logo_img').hide();
}
/*开启按钮*/
function hg_codeAlter()
{
	if($('#edit_code_2').css('display') == 'none')
	{
		$('#edit_code_2').removeAttr('disabled');
		$('#sub').attr('disabled','disabled');
		$('#edit_code_2').show();
		$('#is_code_2').html('关闭修改');
		$('#important_1').addClass('i');
		$('#important_1').html('修改后时移数据将会被清除');
	}
	else
	{
		$('#edit_code_2').attr('disabled','disabled');
		$('#sub').removeAttr('disabled');
		$('#edit_code_2').hide();
		$('#is_code_2').html('开启修改');
		$('#important_1').removeClass('i');
		$('#important_1').html('必填，包含英文、数字');
	}
}
/*修改台号*/
function hg_channelCodeEdit()
{
	if(confirm('确定修改台号吗？'))
	{
		var url = './run.php?mid=' + gMid + '&a=channelCodeEdit&channel_id=' + $('#channel_id').val() + '&code_2=' + $('#code_2').val();
		hg_ajax_post(url);
	}
	else
	{
		hg_codeAlter();
		$('#code_2').val($('#required_1').val());
	}
}
function hg_channelCodeEdit_back(obj)
{
	var obj = eval('('+obj+')');
	$('#required_1').val(obj);
	$('#edit_code_2').hide();
	$('#is_code_2').html('开启修改');
	$('#important_1').removeClass('i');
	$('#important_1').html('必填，包含英文、数字');
	$('#sub').removeAttr('disabled');
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
		<div id="channel_form" style="margin-left:40%;"></div>
		<div class="ad_middle">
		<form name="editform" action="./run.php?mid={$_INPUT['mid']}&a={$action}" method="post" onsubmit="hg_channel_return();" enctype='multipart/form-data' class="ad_form h_l">
			<h2>{$optext}频道</h2>
			<ul class="form_ul">
				<li class="i">
					<div class="form_ul_div">
						<span class="title">频道名称：</span>
						<input type="text" onblur="input_content_color(2);" onfocus="input_content_color(2);" id="required_2" name="name" value="{$name}" style="width:192px"/>
						<font class="important" id="important_2">必填，不能包含特殊字符</font>
					</div>
					<div class="form_ul_div">	
						<span class="title">台号：</span>
						<input style="width:85px;" type="text" name="code" value="{$code}" onblur="input_content_color(1),checkCode();" onfocus="input_content_color(1);" id="required_1" {if $action == 'update'}disabled="disabled"{/if} />
						{if $action == 'update'}
							<span>
								<a href="javascript:void(0);" id="is_code_2" onclick="hg_codeAlter();">开启修改</a>
							</span>
							<span id="edit_code_2" style="display:none;">
								<input name="code_2" id="code_2" onchange="hg_channelCodeEdit();" style="border:1px solid #F79607;width:87px;margin-left:-147px;z-index:100;" type="text" value="{$code}"/>
							</span>
						{/if}
						<font class="important" id="important_1">必填，包含英文、数字</font>
					</div>
					<div class="form_ul_div clear">
						<span class="title">台标：</span>
						<span class="file_input s" id="file_input" style="float:left;">选择文件</span>
						<span id="file_text" class="overflow file-text s">{$logo}</span>
						<span id="logo_img" style="float:right;">{if $logo_url}<img width="80" height="30" src="{$logo_url}" />{/if}</span>
						<input  onclick="hg_logo_value();" name="files" type="file"  value="" class="file" id="f_file"  hidefocus>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">流信息：</span>
						{code}
							$item_source = array(
								'class' => 'down_list i',
								'show' => 'item_shows_',
								'width' => 100,/*列表宽度*/		
								'state' => 0, /*0--正常数据选择列表，1--日期选择*/
								'is_sub'=>1,
								'onclick'=>'hg_disabled_pri();hg_stream_select();',
							);
							$default = $stream_id ? $stream_id : -1;
							$streamd[$default] = '--请选择--';
							foreach($stream_info as $k =>$v)
							{
								$streamd[$v['id']] = $v['s_name'];
							}
						{/code}
						{template:form/search_source,stream_id,$default,$streamd,$item_source}
						<span class ="s" id="uri_name" style="display:block;margin-left:190px;">
						{if $action == 'update'}
						{foreach $stream_name_all as $kk => $vv}
						<label style="margin-left:5px;">
							<input class="n-h" onclick="displayCheckbox(this,'{$vv}');" type="checkbox" {foreach $stream_name as $k => $v} {if $vv == $v} checked {/if} {/foreach} name="stream_name[]" value="{$vv}" />
							<span class="s">{$vv}</span>
						</label>
						{/foreach}
						{/if}
						</span>
					</div>
					<div class="form_ul_div clear" style="line-height: 22px;{if !$main_stream_name}display:none;{/if}" id="main_stream">
						<span class="title">主信号：</span>
						{foreach $stream_name_all as $ks => $vs}
						{if $vs == $main_stream_name}
							<label id="main_{$vs}"><input type="radio" checked name="main_stream_name[]" class="n-h" value="{$vs}" /><span class="s">{$vs}</span></label>
						{else}
							<label id="main_{$vs}"><input type="radio" name="main_stream_name[]" class="n-h" value="{$vs}" /><span class="s">{$vs}</span></label>
						{/if}
						{/foreach}
					</div>
					<div id="is_live" style="display:{if $action=='update' && $count==1}block{else}none{/if};">
						<div class="form_ul_div clear" style="line-height: 22px;">
							<span class="title">播控：</span>
							<label>
							<input type="checkbox" name="is_live" id="is_live_checked" value=1 {if $is_live}checked{/if} onchange="hg_beibo_stream_checkbox(this.checked);" class="n-h"/><span>允许播控</span>
							</label>
						</div>
						<div class="form_ul_div clear">
							<div id="beibo_stream">
								<span class="title">备播信号：</span>
								<div style="width:460px;float:left;line-height: 24px;">
								{foreach $stream_info as $key => $value}
								{if $action == 'create'}
									<label class="gx"><input id="{$value['id']}" onclick="control_beibo_max(this)" class="n-h" type="checkbox" name="beibo[]" value="{$value['id']}#{$value['s_name']}" />
									{$value['s_name']}</label>
								{else}
									<label class="gx"><input type="checkbox" id="{$value['id']}" onclick="control_beibo_max(this)" {if $value['id'] ==  $stream_id} disabled{/if} {if $formdata['beibo'][$value['id']]} checked="checked"{/if} name="beibo[]" value="{$value['id']}#{$value['s_name']}"  class="n-h"/>
									{$value['s_name']}</label>
								{/if}
								{/foreach}
								</div>
								<font class="important" style="line-height:22px;">支持两个备播信号</font>
							</div>
						</div>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div">
						<span class="title">输出设置：</span>
						{if $action == 'update'}
							<label><input type="checkbox" value=1 {if $open_ts == 1} checked {/if} name="open_ts" class="n-h"><span>打开手机流</span></label>
						{else}
							<label><input type="checkbox" value=1 checked name="open_ts" class="n-h"><span class="s">允许打开手机流</span></label>
						{/if}
						<span class="s">(开启后将输出m3u8流)</span>
					</div>
					<div class="form_ul_div">
						<span class="title">时移：</span>
						<input style="height:18px;width:49px;text-align:center;" onblur="input_content_color(3);" onfocus="input_content_color(3);" id="required_3" type="text" name="save_time" {if $action == 'create'} value="1" {else} value="{$save_time}"{/if} {if $action == 'update'}onchange="hg_show_tip(save_time);"{/if} />
						<span class="s">小时</span>
						<font class="important" id="important_3">时移时间</font>
						<span class="s" id="alert_tip" style="color:red;display:none;position:relative;left: 10px;top:0;margin-left:10px;">改变时移时间，流将被重启！</span>
					</div>
					<div class="form_ul_div">
						<span class="title">延时：</span>
						<input style="height:18px;width:49px;text-align:center;" onblur="input_content_color(4);" onfocus="input_content_color(4);" id="required_4" type="text" name="live_delay" {if $action == 'create'} value="1" {else} value="{$live_delay}" {/if} {if $action == 'update'} onchange="hg_show_tip1();" {/if}/>
						<span class="s">分钟</span>
						<font class="important" id="important_4">延时时间</font>
						<span id="alert_tip1" class="s" style="color:red;display:none;position:relative;left: 10px;top:0;margin-left:10px;">改变延时时间，流将被重启！</span>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div">
						<span class="title">时间偏差：</span>
						<input type="text" name="record_time" {if $action == 'create'} value="0" {else}value="{$record_time}" {/if} style="height:18px;width:49px;text-align:center;" />
						<span class="s">秒</span>
						<font class="important" id="important_4">录制节目时间偏差设置&nbsp;(±30秒)</font>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div">
						<span class="title">防盗链：</span>
							<label><input type="checkbox" value=1 {if $drm == 1} checked {/if} name="drm" class="n-h"><span>开启防盗链</span></label>
						<span class="s">&nbsp;</span>
					</div>
				</li>
			</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" id="channel_id" />
		<input type="hidden" name="mmid" value="{$_INPUT['mid']}" id="mid" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</form>
		</div>
		<div class="right_version">
			<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		</div>
{template:foot}