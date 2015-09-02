{template:head}
{css:ad_style}
<script type="text/javascript">
var gTotalInput = 0;
function hg_add_input(obj)
{ 
	gTotalInput = find_max_id() + 1;
	var div = '<div id="div_input_'+gTotalInput+'" name="'+gTotalInput+'" class="div_input clear"><span onclick="hg_add_input(this);" title="继续添加" class="chg_plan_left"></span><span>服务名称</span><input style="width:73px;" type="text" value=""  name="conf_name[]" /><span name="source_type">配置路径</span><input  style="width:250px;" type="text" value=""  name="conf_path[]" /><span style="color:green;cursor:pointer;"  onclick="edit_server_conf(this);">查看</span><span id="del_input_'+gTotalInput+'" onclick="hg_del_input(this, '+gTotalInput+');" title="删除"  class="chg_plan_right"></span>'+'</div>';
	$(obj).parent().parent().append(div);
	if($('div[id^=div_input_]').length > 1)
	{
		$('span[id^=del_input_]').show();
	}
	hg_resize_nodeFrame();
}

function hg_del_input(obj, i)
{
	 var div_id = $(obj).parent('div').attr('id');
	 $('#' + div_id).remove();
	 if($('div[id^=div_input_]').length ==1)
	 {
		$('span[id^=del_input_]').hide();
	 }
	 hg_resize_nodeFrame();
}

function find_max_id()
{
	var max = 0;
	$('div[id^=div_input_]').each(function(){
		var num = parseInt($(this).attr('name'));
		if(num > max)
		{
			max = num;
		}
	});	
	return max;
}

function edit_server_conf(obj)
{
	var value = $(obj).parent('div').find('input[name="conf_name[]"]').val();
	var config_path = $(obj).parent('div').find('input[name="conf_path[]"]').val();
	var id = $('#server_id').val();
	var url = "run.php?mid="+gMid+"&a=get_config&id="+id+"&service_name="+value+"&config_path="+config_path+"&infrm=1";
	window.location.href = url;
}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="ad_middle">
<form name="servicesform" id="servicesform" action="./run.php?mid={$_INPUT['mid']}" method="post" onsubmit="return hg_ajax_submit('servicesform');" enctype='multipart/form-data' class="ad_form h_l">
	<h2>{$optext}服务</h2>
	<ul class="form_ul">
		<li class="i">
			<div class="form_ul_div" style="position:relative;">
				<span class="title">服务名称：</span>
				<input  type="text" name="name" value="{$formdata['name']}" style="width:250px;" />
				<font class="important">必填</font>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">启动命令：</span>
				<input  type="text" name="start_cmd" value="{$formdata['start_cmd']}" style="width:250px;" />
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">停止命令：</span>
				<input  type="text" name="stop_cmd" value="{$formdata['stop_cmd']}" style="width:250px;" />
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">重启命令：</span>
				<input  type="text" name="restart_cmd" value="{$formdata['restart_cmd']}" style="width:250px;" />
			</div>
		</li>
		<li id="con_li" class="i clear">
			<div class="form_ul_div clear">
				<span class="title form_ul_div_l">服务配置：</span>
				<div class="form_ul_div_r" style="padding-top:5px;">
				{if $formdata['conf']}
					{code}
					 	$flag = count($formdata['conf']) > 1?1:0;
					{/code}
					{foreach $formdata['conf'] AS $k => $v}
						<div id="div_input_{$k}" class="div_input clear" name="{$k}">
							<span onclick="hg_add_input(this);" title="添加" class="chg_plan_left"></span>
							<span>配置名称</span><input style="width:73px;" type="text"  name="conf_name[]" value="{$v['name']}" />
							<span name="source_type">配置路径</span>
							<input type="text" name="conf_path[]" value="{$v['path']}"  style="width:250px"/>
							<span style="color:green;cursor:pointer;"  onclick="edit_server_conf(this);">查看</span>
							<span style="display:{if $flag}block{else}none{/if};" onclick="hg_del_input(this,0);" id="del_input_0" title="删除" class="chg_plan_right"></span>
						</div>
					{/foreach}
				{else}
					<div id="div_input_0" class="div_input clear" name="0">
						<span onclick="hg_add_input(this);" title="添加" class="chg_plan_left"></span>
						<span>配置名称</span><input style="width:73px;" type="text" name="conf_name[]" value="" />
						<span name="source_type">配置路径</span>
						<input type="text" name="conf_path[]" value=""  style="width:250px"/>
						<span style="color:green;cursor:pointer;" onclick="edit_server_conf(this);">查看</span>
						<span style="display:none;" onclick="hg_del_input(this,0);" id="del_input_0" title="删除" class="chg_plan_right"></span>
					</div>
				{/if}
				</div>
			</div>
		</li>
	</ul>
<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="server_id" id="server_id" value="{$_INPUT['serverid']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}