<script type="text/javascript">
  var tp_id = "{$formdata['id']}";
  var vs = hg_get_cookie('server_info');
  $(document).ready(function(){
	$('#server_info').css('display',vs?vs:'block');
  });
</script>

<div class="info clear vider_s" id="vodplayer_{$formdata['id']}" style="height:20px;">
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>

<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'server_info')"><span title="展开\收缩"></span>配置信息</h4>
	<div id="server_info" class="channel_info_box">
		<ul class="clear">
<!-- 			<li class="overflow"><span>标识：</span>{$formdata['mark']}</li> -->
			<li class="overflow"><span>名称：</span>{$formdata['name']}</li>
			<li><span>信号总数：</span>{$formdata['counts']}</li>
			<li><span>创建人：</span>{$formdata['user_name']}</li>
			<!-- <li><span>协议：</span>{$formdata['protocol']}</li> -->
			<li style="width: 190px;"><span>创建时间：</span>{$formdata['create_time']}</li>
			<li style="width: 350px;border-top: 1px dotted #7B7B7B;"><span>主控host：</span>{$formdata['core_in_host']}</li>
			<li><span>主控输入端口：</span>{$formdata['core_in_port']}</li>
			<li><span>主控输出端口：</span>{$formdata['core_out_port']}</li>
	{if $formdata['dvr_append_host']}
		{foreach $formdata['dvr_append_host'] AS $kk => $vv}
			<li style="width: 350px;{if $kk > 0}margin-left: 83px;{/if}">{if $kk == 0}<span>时移多个host：</span>{/if}<font>{$vv}</font></li>
		{/foreach}
	{/if}
		{if $formdata['is_dvr_output']}
			<li style="width: 350px;border-top: 1px dotted #7B7B7B;"><span>时移host：</span>{$formdata['dvr_in_host']}</li>
			<li><span>时移输入端口：</span>{$formdata['dvr_in_port']}</li>
			<li><span>时移输出端口：</span>{$formdata['dvr_out_port']}</li>
		{/if}
{if $formdata['is_live_output']}
			<li style="width: 350px;border-top: 1px dotted #7B7B7B;"><span>直播host：</span>{$formdata['live_in_host']}</li>
			<li><span>直播输入端口：</span>{$formdata['live_in_port']}</li>
			<li><span>直播输出端口：</span>{$formdata['live_out_port']}</li>
	{if $formdata['live_append_host']}
		{foreach $formdata['live_append_host'] AS $kk => $vv}
			<li style="width: 350px;{if $kk > 0}margin-left: 83px;{/if}">{if $kk == 0}<span>直播多个host：</span>{/if}<font>{$vv}</font></li>
		{/foreach}
	{/if}
{/if}
			<li style="width: 350px;border-top: 1px dotted #7B7B7B;"><span>录制host：</span>{$formdata['record_host']}</li>
			<li style="width: 350px;"><span>录制端口：</span>{$formdata['record_port']}</li>
			<li style="width: 350px;"><span>录制输出host：</span>{$formdata['record_out_host']}</li>
			<li style="width: 350px;border-top: 1px dotted #7B7B7B;"><span>描述：</span>{$formdata['brief']}</li>
		</ul>
	</div>
</div>
<div class="info clear cz">
	<div id="video_opration" class="clear common-list" style="border:0;height:auto">
		<div class="common-opration-list">
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1">编辑</a>
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);" title="" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}">删除</a>
		</div>
		<div class="common-opration-list">
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&copy=1&infrm=1">复制</a>
		</div>
	</div>
</div>















