<?php 
/* $Id: channel_mms_form.php 9948M 2012-07-23 06:25:50Z (local) $ */
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
//	return hg_ajax_submit('editform');

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

function hg_stream_select()
{
	var id = $("#stream_id").val();
	if(id == -1)
	{
		hg_select_choose();
	}
	else
	{
		var url = "./run.php?mid="+gMid+"&a=getUriName&id="+id;
		hg_ajax_post(url,'','','hg_stream_name_input');
	}

}
function hg_stream_name_input(obj)
{
	var obj = obj[0];
	
	if (obj['type'] == 1)
	{
		$('input[name="live_delay"]').attr('disabled', 'disabled');
		$('#type_box').show();
	}
	else
	{
		$('input[name="live_delay"]').removeAttr('disabled');
		$('#type_box').hide();
	}
	
	$('#uri_name').html('');

	var checked ='';

	for(var i in obj['stream_name'])
	{
		$('#uri_name').append('<label><input type="checkbox"  class="n-h" checked="checked"  onclick="displayCheckbox(this,\'' + obj['stream_name'][i] + '\');"   name="stream_name['+i+']" value="'+obj['stream_name'][i]+'" />'+'<span title="'+obj['stream_name'][i]+'">'+obj['stream_name'][i]+'</span></label>');
	}

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
    $('input[name^="stream_name"]').each(function(){
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
		$(e).attr('checked','checked');
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
	bindFileInput('file_input','f_file_mobile','file_text_mobile');
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


</script>
{if $a}
	{code}
/*	hg_pre($formdata);*/
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
		<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}&a={$action}" method="post" onsubmit="hg_channel_return();" enctype='multipart/form-data' class="ad_form h_l">
			<h2>{$optext}频道</h2>
			<ul class="form_ul">
				<li class="i">
					<div class="form_ul_div">
						<span class="title">频道名称：</span>
						<input type="text" onblur="input_content_color(2);" onfocus="input_content_color(2);" id="required_2" name="name" value="{$name}" style="width:192px"/>
						<font class="important" id="important_2">必填</font>
					</div>
					<div class="form_ul_div">	
						<span class="title">台号：</span>
						<input style="width:85px;" type="text" name="code" value="{$code}" onblur="input_content_color(1),checkCode(this);" onfocus="input_content_color(1);" id="required_1"  />
						<input type="hidden" name="code2" value="{$code}" />
						<font class="important" id="important_1">必填，包含英文、数字</font>
					</div>
					<div class="form_ul_div clear">
						<span class="title">长形台标：</span>
						<span class="file_input s" id="file_input" style="float:left;">选择文件</span>
						<span id="logo_img" style="float:right;">{if $logo_url}<img width="80" height="30" src="{$logo_url}" />{/if}</span>
						<input name="files" type="file"  value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;"  />
					</div>
					<div class="form_ul_div clear">
						<span class="title">方形台标：</span>
						<span class="file_input s" id="file_input" style="float:left;">选择文件</span>
						<input style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" name="files_mobile" type="file" />
						<span id="logo_img_mobile" style="float:right;">{if $logo_mobile_url}<img width="30" height="30" src="{$logo_mobile_url}" />{/if}</span>
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
							$stream[$default] = '--请选择--';
							
							if (!empty($streamInfo))
							{
								foreach($streamInfo AS $k =>$v)
								{
									$stream[$v['id']] = $v['ch_name'];
								}
							}
						{/code}
						{template:form/search_source,stream_id,$default,$stream,$item_source}
						<span class ="s" id="uri_name" style="display:block;margin-left:190px;">
						{if $action == 'update'}
						{foreach $stream_name_all as $kk => $vv}
						<label style="margin-left:5px;">
							<input class="n-h" onclick="displayCheckbox(this,'{$vv}');" type="checkbox" 
								{foreach $stream_name as $k => $v}
									{if $vv == $v} 
										checked="checked"
									{/if}
								{/foreach} 
								name="stream_name[{$kk}]" value="{$vv}"
							/>
							<span class="s">{$vv}</span>
						</label>
						{/foreach}
						{/if}
						</span>
						<font id="type_box" style="float: right;color: #F79607;line-height: 24px;{if !$type || !$id}display:none;{/if}">选择文件流作为信号，不可以添加延时</font>
					</div>
				<!--	<div class="form_ul_div clear" style="line-height: 22px;{if !$main_stream_name}display:none;{/if}" id="main_stream">
						
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
					</div>-->
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
						<input style="height:18px;width:49px;text-align:center;" onblur="input_content_color(3);" onfocus="input_content_color(3);" id="required_3" type="text" name="save_time" {if $action == 'create'} value="24" {else} value="{$save_time}"{/if} {if $action == 'update'}onchange="hg_show_tip(save_time);"{/if} />
						<span class="s">小时</span>
						<font class="important" id="important_3">时移时间(最大{$_configs['max_save_time']}小时)</font>
						<span class="s" id="alert_tip" style="color:red;display:none;position:relative;left: 10px;top:0;margin-left:10px;">改变时移时间，流将被重启！</span>
					</div>
					<div class="form_ul_div">
						<span class="title">延时：</span>
						<input style="height:18px;width:49px;text-align:center;" onblur="input_content_color(4);" onfocus="input_content_color(4);" id="required_4" type="text" name="live_delay" {if $action == 'create'} value="0" {else} value="{$live_delay}" {/if} {if $action == 'update'} onchange="hg_show_tip1();" {/if} {if $type} disabled="disabled" {/if} />
						<span class="s">秒</span>
						<font class="important" id="important_4">延时时间(最大{$_configs['max_live_delay']}秒)</font>
						<span id="alert_tip1" class="s" style="color:red;display:none;position:relative;left: 10px;top:0;margin-left:10px;">改变延时时间，流将被重启！</span>
					</div>
				</li>
				<!--
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

				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">发布至：</span>
	            		{template:unit/publish, 1, $formdata['column_id']}
			             <script>
			             jQuery(function($){
			                $('#publish-1').css('margin', '10px auto').commonPublish({
			                    column : 2,
			                    maxcolumn : 2,
			                    height : 224,
			                    absolute : false
			                });
			             });
	             		 </script>
				    </div>
				</li>
-->
			</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" id="channel_id" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</form>
		</div>
		<div class="right_version">
			<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
		</div>
{template:foot}