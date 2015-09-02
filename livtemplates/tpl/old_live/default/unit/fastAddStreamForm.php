{css:ad_style}
{js:message}
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

function hg_beibo_url(obj)
{
	var  father_obj = hg_find_nodeparent(obj,'DIV');
	var uri_obj = $(father_obj).find("input[id^='no_uri_']");
	var uri_value = $(obj).val();
	$(uri_obj).val(uri_value);
}

function checkCode(obj)
{
	var gCode =/^[A-Za-z0-9]+$/;
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


/*获取备播文件*/
function hg_getBackupInfo(obj, i)
{	
	var s_name = $('input[name="ch_name"]').val();
	if (!s_name)
	{
		$('#sub').attr('disabled', 'disabled');
		return false;
	}
	
	var name = $('#name_' + i).val();
	if (!name)
	{
		$('#sub').attr('disabled', 'disabled');
		return false;
	}
	
	var protocol = "{$_configs['mms']['file']['protocol']}";
	var address = "{$_configs['mms']['file']['wowzaip']}";
	var type = "{$_configs['mms']['file']['appName']}";
	var suffix = "{$_configs['mms']['file']['suffix']}";

	var url = protocol+address+'/'+type+'/'+s_name+'.'+name + suffix;
/*
	$('#no_uri_' + i).val(url);
	$('#hidden_no_uri_' + i).val(url);
*/	
	$('#sub').removeAttr('disabled');
}


</script>

<div class="ad_middle">
<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
	<ul class="form_ul">
		<li class="i">
			<!--
<div class="form_ul_div">
				<span class="title">信号名称：</span>
				<input onblur="input_content_color(2);" onfocus="input_content_color(2);" id="required_2" type="text" name="s_name" value=""/>
				<font class="important" id="important_2">必填</font>
			</div>
-->
			<div class="form_ul_div">
				<span class="title">信号标识：</span>
				<input onblur="input_content_color(1),checkCode(this),hg_getBackupInfo(this,0);" onfocus="input_content_color(1);" id="required_1" type="text" name="ch_name" value=""/>
				<font class="important" id="important_1">必填，包含英文，数字</font>
			</div>
		</li>
		<li id="con_li" class="i clear">
			<div class="form_ul_div clear">
				<span class="title form_ul_div_l">直播流：</span>
				<div class="form_ul_div_r" style="padding-top:5px;">
					<div id="div_input_0" class="div_input clear">
						<span class="chg_plan_left" style="display:none;"></span>
						<span>输出标识</span><input onblur="checkCode(this);" onchange="hg_getBackupInfo(this,0);" style="width:73px;" type="text" id="name_0" name="name_0" value="" />
						<span name="source_type">来源地址</span>
						<input type="text" id="no_uri_0" value="" disabled="disabled" style="width:250px"/>
						<input type="hidden" id="hidden_no_uri_0" name="uri_0" value="" />
						<span class="chg_plan_wj" id="backup_file_0"></span>
						<img style="display:none;" id="load_img_0" src="{$RESOURCE_URL}bit_loading.gif" />
						<span id="bitrate_0"></span>
						<input type="hidden" name="bitrate_0" id="hidden_bitrate_0" value="" />
						<input type="hidden" name="counts[]" />
					</div>
				</div>
				<div id="sourceNameBox"></div>
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
	
	<input type="hidden" name="a" id="action" value="fastAddStream" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	<input type="hidden" name="type" value="1" />
	<input type="hidden" name="server_id" value="" id="_server_id" />
	<input type="hidden" name="flag" value="fastAddStream" />
	</br>
	<input type="submit" name="sub" value="添加" id="sub" class="button_6_14" />
</form>
</div>