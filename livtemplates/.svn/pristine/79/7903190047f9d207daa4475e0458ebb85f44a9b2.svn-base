<ul class="type_title" id="type_title">
	<li class="live_type_4 cur" id="type_4" onclick="change(4);" {if !$_configs['mms']['record_server_callback']} style="height: 127px;line-height: 127px;"{/if}>
		<span>信号</span>
	</li>
	<li class="type_1" id="type_1" onclick="change(1);" style="display:none;">
		<span>直播</span>
	</li>
	<li class="type_2" id="type_2" onclick="change(2);" {if !$_configs['mms']['record_server_callback']} style="height: 127px;line-height: 127px;"{/if}>
		<span>文件</span>
	</li>
	<li class="type_3" id="type_3" onclick="change(3);" {if !$_configs['mms']['record_server_callback']} style="display:none;"{/if}>
		<span>时移</span>
	</li>
</ul>
<div class="type_box">
<!-- 直播开始 -->
	<div class="type_content wj" id="type_content_1" style="display:none;">
	{if $formdata['channel']['info']}
		<ul class="ul">
		{foreach $formdata['channel']['info'] AS $key=>$value}
		{if $value['id'] != $formdata['type_source']['channel_id'] && $value['audio_only'] == $formdata['type_source']['audio_only']}
			{if $formdata['type_source']['type'] == 1 && $formdata['type_source']['channel2_id'] == $value['id']} 
				<li class="f_l overflow"  id="live_{$value['id']}" value="{$value['id']}" onclick="hg_channel_live(this,1,'','');">
					<a id="num_{$value['id']}" class="overflow type_source" >{$value['name']}</span></a>
					<span>{if $value['stream_state']}已启动{else}未启动{/if}</span>
				</li>
			{else}
				<li class="f_l overflow" id="live_{$value['id']}" value="{$value['id']}" onclick="hg_channel_live(this,1,'','');">
					<a id="num_{$value['id']}" class="overflow">{$value['name']}</a>
					<span>{if $value['stream_state']}已启动{else}未启动{/if}</span>
				</li>
			{/if}
		{/if}
		{/foreach}
		</ul>
		<span class="count">共{$formdata['channel']['count']}页/计{$formdata['channel']['total']}条</span>
		<div id="channelPageBox" class="pageBox" style="width: 250px;"></div>
	{else}
		<div style="text-align: center;color:red;">暂无记录</div>
	{/if}
	</div>
<!-- 直播结束 -->
<!-- 信号开始 -->
	<div class="type_content wj" id="type_content_4">
	{if $formdata['stream']['info']}
		<ul class="ul" id="postStreamInfo_ul">
		{foreach $formdata['stream']['info'] AS $key=>$value}
		{if $value['id'] != $formdata['type_source']['channel_id'] && $value['audio_only'] == $formdata['type_source']['audio_only']}
			{if $formdata['type_source']['type'] == 1 && $formdata['type_source']['channel2_id'] == $value['id']} 
				<li class="f_l overflow"  id="live_{$value['id']}" value="{$value['id']}" onclick="hg_channel_live(this,4,'','');">
					<a id="num_{$value['id']}" class="overflow type_source" >{$value['ch_name']}</span></a>
					<span>{if $value['s_status']}已启动{else}未启动{/if}</span>
				</li>
			{else}
				<li class="f_l overflow" id="live_{$value['id']}" value="{$value['id']}" onclick="hg_channel_live(this,4,'','');">
					<a id="num_{$value['id']}" class="overflow">{$value['ch_name']}</a>
					<span>{if $value['s_status']}已启动{else}未启动{/if}</span>
				</li>
			{/if}
		{/if}
		{/foreach}
		</ul>
		<span class="count">共{$formdata['stream']['count']}页/计{$formdata['stream']['total']}条</span>
		<div id="streamPageBox" class="pageBox" style="width: 250px;"></div>
	{else}
		<div style="text-align: center;color:red;">暂无记录</div>
	{/if}
	</div>
<!-- 信号结束 -->
<!-- 文件开始 -->
	<div class="type_content" id="type_content_2">
	{if $formdata['backup']['info']}
		<div class="search">
			<span onclick="hg_search_info();" title="搜索" style="display: inline-block;float: right;width: 16px;height: 16px;position: relative;right: 20px;top: 2px;background: url({$RESOURCE_URL}bg-all.png) no-repeat;background-position: -115px -48px;cursor: pointer;"></span>
			<input name="k" id="search_key_words" type="text" style="height: 14px;float: right;margin-right: 3px;width: 106px;" />
		</div>
		<div id="backup_box">
			<ul class="type_content_ul" id="postBackupInfo_ul">
			{code}
				$ii = 1;
			{/code}
			{foreach $formdata['backup']['info'] AS $k=>$v}
				<li onclick="hg_channel_live(this,2,'',{$v['id']});" value="{$v['id']}">
					<input type="hidden" id="_title_{$v['id']}" value="{$v['title']}" />
					<span style="display:none;" id="_toff_{$v['id']}">{$v['toff']}</span>
					<img class="backup_img" src="{$v['img']}" onmouseover="hg_backupTitleShow(this,{$v['id']},'show');" onmouseout="hg_backupTitleShow(this,{$v['id']},'hide');" sid="{$ii}" />
					<input type="hidden" name="toff_s[]" value="{$v['toff']}" />
					<div id="backupIdInfo_{$v['id']}" class="backupinfo">
						<span>名称：{$v['title']}</span>
						<span>时长：{$v['toff']}</span>
					</div>
				</li>
				{code}
					$ii ++;
				{/code}
			{/foreach}
			</ul>
			<span class="count">共{$formdata['backup']['count']}页/计{$formdata['backup']['total']}条</span>
			<div id="backupPageBox" class="pageBox" style="width: 250px;"></div>
		</div>
	{else}
		<div style="text-align: center;color:red;">暂无记录</div>
	{/if}
	</div>
<!-- 文件结束 -->

	<div class="type_content type_content_hide sy" id="type_content_3">
	{if $_configs['mms']['record_server_callback']}
	<!-- 时移开始 -->
		 <div class="info clear" style="padding-bottom:0;background:none">
			<div class="channel_list clear" id="channel_list" style="display:block;">
				{code}
					$item_source = array(
						'class' => 'down_list f_l',
						'show' => 'item_shows_',
						'width' => 156,/*列表宽度*/
						'state' => 0, /*0--正常数据选择列表，1--日期选择*/
						'is_sub'=>1,
						'onclick' => "hg_select_channel_plan(this);" 
					);
					$default = $formdata['type_source']['channel2_id'] ? $formdata['type_source']['channel2_id'] : -1;
					$channels[$default] = '--请选择--';
					if ($formdata['channel']['info'])
					{
						foreach($formdata['channel']['info'] AS $k =>$v)
						{
							if ($v['open_ts'])
							{
								$name = '<span open_ts=1 title="已启手机流" style="display:inline-block;width:100%;">' . $v['name'] . '</span>';
							}
							else
							{
								$name = '<span open_ts=0 title="未启手机流" style="display:inline-block;width:100%;">' . $v['name'] . '</span>';
							}
							$channels[$v['id']] = $name;
						}
					}
				{/code}
				{template:form/search_source,channel2_id,$default,$channels,$item_source}
			{code}
				$types_source = array('other'=>' size="14" autocomplete="off" onchange="hg_channel_chg_plan_start_time(this,3,1)" style="width:130px;height: 18px;line-height: 22px;font-size:10px;font-family: Verdana;"','name'=>'start_time');
			{/code}
			{template:form/wdatePicker,start_times,$formdata['type_source']['program_start_time'],'',$types_source}
			{code}
				$types_source = array('other'=>' size="14" autocomplete="off" onchange="hg_channel_chg_plan_start_time(this,3)" style="width:130px;height: 18px;line-height: 22px;font-size:10px;font-family: Verdana;"','name'=>'end_time');
			{/code}
			<span class="time-h-k">-</span>
			{template:form/wdatePicker,end_times,$formdata['type_source']['program_end_time'],'',$types_source}
			</div>
			<div class="info_live clear">
				<div class="tv" id="info_live" style="display:none;"></div>
			</div>
		</div>
	<!-- 时移结束 -->
	{/if}
	</div>
	
</div>
<script type="text/javascript">
var gStreamCount = "{$formdata['stream']['count']}";
var gBackupCount = "{$formdata['backup']['count']}";
$(function(){
	if (gStreamCount != 0)
	{
		hg_page_ui('streamPageBox', gStreamCount, 1, 6, hg_page, 4);
	}
	if (gBackupCount != 0)
	{
		hg_page_ui('backupPageBox', gBackupCount, 1, 6, hg_page, 2);
	}
});
function hg_page_ui(id, count, start, display, func, type)
{
	$("#" + id).paginate({
		count 		: count,
		start 		: start,
		display     : display,
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
									func(page,type);
								}
	});
}
var gTpye = '';
function hg_page(offset, type)
{
	var count = '';
	gType = type;

	if (type == 4)
	{
		count = '{$_configs["channelChgPlan2StreamCount"]}';
	}
	else if (type == 2)
	{
		count = '{$_configs["channelChgPlan2BackupCount"]}';
		var k = $('#search_key_words').val();
	}
	
	if (offset > 1)
	{
		offset = (offset-1)*count; 
	}
	else if (offset == 1)
	{
		offset = 0;
	}
	var url = './run.php?mid=' + gMid + '&a=page&offset=' + offset + '&counts=' + count + "&type=" + type + "&audio_only=" + "{$formdata['type_source']['audio_only']}" + '&server_id=' + "{$formdata['type_source']['server_id']}" + '&k=' + k;

	hg_ajax_post(url);
}
function page_back(html)
{
	var id = '';
	if (gType == 4)
	{
		id = 'postStreamInfo_ul';
	}
	else if (gType == 2)
	{
		id = 'postBackupInfo_ul';
	}

	if (html)
	{
		$('#' + id).html(html);
	}
}
/*
var gCount = '{$formdata["count"]}';
if (gCount != 0)
{
	$(function(){
		$("#pageBox").paginate({
			count 		: '{$formdata["count"]}',
			start 		: 1,
			display     : 6,
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
										hg_backupPageBox(page);
									}
		});
	});
}

function hg_backupPage()
{
	$("#pageBox").paginate({
		count 		: '{$formdata["count"]}',
		start 		: 1,
		display     : 6,
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
									hg_backupPageBox(page);
								}
	});
}

function hg_backupPageBox(offset)
{
	var count = '{$_configs["channelChgPlan2BackupCount"]}';
	if (offset > 1)
	{
		offset = (offset-1)*count; 
	}
	else if (offset == 1)
	{
		offset = 0;
	}
	var url = './run.php?mid=' + gMid + '&a=backupPage&offset=' + offset + '&counts=' + count;

	hg_ajax_post(url);
}
function backupPage_back(html)
{
	if (html)
	{
		$('#postBackupInfo_ul').html(html);
	}
}
*/
/*备播文件标题显示与隐藏*/
function hg_backupTitleShow(obj,id,type)
{
	if (type == 'show')
	{
		$(obj).parent().find('div').show();
		var sid = ($(obj).attr('sid')*1)%7;
		if (!sid)
		{
			$(obj).parent().find('div').css({'position':'relative','left':'-51px'});
		}
	}
	else if (type == 'hide')
	{
		$(obj).parent().find('div').hide();
	}
	
}
/*备播文件搜索*/
function hg_search_info()
{
	var k = $('#search_key_words').val();
	var server_id = "{$formdata['type_source']['server_id']}";
	
	var url = './run.php?mid=' + gMid + '&a=search_info&k=' + k + '&server_id=' + server_id;

	hg_ajax_post(url);
}
function search_info_back(html)
{
	if (html)
	{
		$('#backup_box').html(html);
	}
}
</script>