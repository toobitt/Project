<ul class="type_title" id="type_title">
	<li class="live_type_1 cur" id="type_1" onclick="change(1);">
		<span class="type_title_li_span">直播</span>
	</li>
	<li class="type_2" id="type_2" onclick="change(2);">
		<span class="type_title_li_span">文件</span>
	</li>
	<li class="type_3" id="type_3" onclick="change(3);" style="1px solid transparent;">
		<span class="type_title_li_span">时移</span>
	</li>
</ul>
<div class="type_box">
<!-- 直播开始 -->
	<div class="type_content wj" id="type_content_1">
		<ul>
	{if $formdata['get_channel_info']}
		{foreach $formdata['get_channel_info'] AS $key=>$value}
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
	{/if}
		</ul>
	</div>
<!-- 直播结束 -->
<!-- 文件开始 -->
	<div class="type_content" id="type_content_2">
		<ul class="type_content_ul" id="postBackupInfo_ul">
	{if $formdata['get_backup_info']}
		{code}
			$ii = 1;
		{/code}
		{foreach $formdata['get_backup_info'] AS $k=>$v}
			<li onclick="hg_channel_live(this,2,'',{$v['id']});" value="{$v['id']}">
				<input type="hidden" id="_title_{$v['id']}" value="{$v['title']}" />
				<span style="display:none;" id="_toff_{$v['id']}">{$v['toff']}</span>
				<img class="backup_img" src="{$v['img']}" onmouseover="hg_backupTitleShow(this,{$v['id']},'show');" onmouseout="hg_backupTitleShow(this,{$v['id']},'hide');" sid="{$ii}" />
				<div id="backupIdInfo_{$v['id']}" class="backupinfo">
					<span>名称：{$v['title']}</span>
					<span>时长：{$v['toff']}</span>
				</div>
			</li>
			{code}
				$ii ++;
			{/code}
		{/foreach}
	{/if}
		</ul>
		<span class="count">共{$formdata['count']}页/计{$formdata['total']}条</span>
		<div id="pageBox" class="pageBox"></div>
	</div>
<!-- 文件结束 -->
	<div class="type_content type_content_hide sy" id="type_content_3">
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
						'onclick'=>'hg_select_channel_plan(this);',
					);
					$default = $formdata['type_source']['channel2_id'] ? $formdata['type_source']['channel2_id'] : -1;
					$channels[$default] = '--请选择--';
					foreach($channel_info AS $k =>$v)
					{
						$channels[$v['id']] = $v['name'];
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
	</div>
</div>
<script type="text/javascript">
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
/*分页*/
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
</script>