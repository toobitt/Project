{code}
$msg_mid = 308;
$basic_mid = 347;
$current_programe = -1;
$zhi_play = -1;
$fullNav = $_user["group_type"] < $_configs["admin_type"]["presenter"] ? 1 : 0;
{/code}
<div class="live-head">
	<div class="live-head-left">
	{if $channel_id}
		<div class="tv-logo"><img src="{$channel_logo}"></div>
		<div class="tv-name">{$channel_name}直播互动平台</div>
	{else}
		<div class="tv-name">直播互动平台</div>
	{/if}
	</div>
	<ul class="live-head-right">
		<li class="live-head-right-item">
			<input id="datePicker" style="width:200px;height:25px;padding:0;" value="{$list['dates']}" />
		</li>
		<li class="customSelect live-head-right-item">
			{if !$fullNav}
			<div style="display:inline-block;position:relative;cursor:pointer;" class="customSelect">
			{foreach $program AS $k => $v}
				{if $v['zhi_play']}
				<label class="customSelect-label">{$v['start']}-{$v['end']} | <a>{$v['theme']}</a></label>
				{code}break;{/code}
				{/if}
			{/foreach}
			</div>
			{else}
			<select id="programSelect">
			{foreach $program AS $k => $v}
				{code}if ($v['zhi_play']) $zhi_play = $k;{/code}
				<option {if $v['norm_start'].','.$v['norm_end'] == $start_end}selected="selected"{code}$current_programe = $k;{/code}{/if} value="{$k}">{$v['start']}-{$v['end']} | {$v['theme']}</option>
			{/foreach}
			</select>
			{/if}
		</li>
		<li class="live-head-right-item">
			<span class="user-pic"><img src="{code} echo $_user['avatar']['host'] . $_user['avatar']['dir'], $_user['avatar']['filepath'] . $_user['avatar']['filename'];{/code}" width="36" height="36"></span>
			<span>{$_user['group_name']}： {$_user['user_name']}</span>
			<span><a href="login.php?a=logout" class="live-logout">退出</a></span>
		</li>
	
	</ul>
</div>
{code}
$time_modal = 0;
if ($zhi_play == -1) {
	$time_modal = $dates > date('Y-m-d', TIMENOW) ? 1 : -1;
} else {
	if ($zhi_play == $current_programe) {
		$time_modal = 0;
	} else {
		$time_modal = $zhi_play > $current_programe ? -1 : 1;
	}
}
{/code} 
<script>
$(function ($) {
	
var select = $('#programSelect').data('first', true);
select.customSelect({
	hoverModel: false,
	onchange: function (value, ui) {
		/*这里采用换节目，刷新页面的方式*/
		var data = globalData.program[value];
		var start_end = [ data['norm_start'], data['norm_end'] ].join(',');
		if ( this.data('first') ) {
			this.data('first', false);
			ui.find('.customSelect-label').html($.format('{0}-{1} | <a>{2}</a><em></em>', data['start'], data['end'], data['theme']) );
		} else {
			var new_href;
			if ({$msg_mid} == gMid && value > globalData.zhi_play) {
				new_href = "run.php?channel_id={$channel_id}&dates={$dates}&mid=" + {$basic_mid} + '&start_end=' + start_end;
			} else {
				new_href = "run.php?channel_id={$channel_id}&dates={$dates}&mid=" + gMid + '&start_end=' + start_end;
			}
			location.href = new_href;
		}
	}
});

$('#datePicker').datepicker({
	onSelect: function (dateText, inst) {
		var now = new Date();
		var nowText = now.toISOString().substr(0, 10);
		var new_href;
		if ({$msg_mid} == gMid && dateText > nowText) {
			new_href = "run.php?channel_id={$channel_id}&mid=" + {$basic_mid} + "&dates=" + dateText;
		} else {
			new_href = "run.php?channel_id={$channel_id}&mid=" + gMid + "&dates=" + dateText;
		}
		location.href = new_href;
	}
});

});
</script>