{code}
$lottery_bg = $formdata['lottery_bg'];
$account_limit_times = $formdata['account_limit'];
$prize = $formdata['prize'];
$type = $_INPUT['type'] ? $_INPUT['type'] : $formdata['type'];	//1代表刮刮卡 2代表大转盘
$type = $type ? $type : 1;
{/code}
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>hogesoft 刮刮卡</title>
	{csshere}
	{css:lottery_preview}
	
	
	{if $lottery_bg}
		<style>
			body.ggk-body , body.zhuanpan-body{background-image:url({$lottery_bg['host']}{$lottery_bg['dir']}{$lottery_bg['filepath']}{$lottery_bg['filename']});}
		</style>
	{/if}
	
	<script type="text/javascript">
		var RESOURCE_URL = "{$RESOURCE_URL}";
		var gMid = "{$_INPUT['mid']}";
	</script>
	{$this->mHeaderCode}

	{jshere}
	
	{js:lottery/common/device.min}
	{js:lottery/common/zepto}
	{js:lottery/common/spin.min}
	{js:lottery/common/viewPC}
	{js:lottery/common/print}
	{js:lottery/common/api}
	{js:lottery/common/template}
	{js:lottery/common/lottery}
	{js:lottery/ggk/hg_scratch}
	{js:lottery/ggk/myggk}
	{js:lottery/zhuanpan/hg_spin}
	{js:lottery/zhuanpan/myspin}
</head>
{if $type == '1'}
<body class="ggk-body">
<div class="ggk-game-box lottery-game-box" _id="{$formdata['id']}">
{/if}
{if $type == '2'}
<body class="zhuanpan-body">
<div class="zhuanpan-game-box lottery-game-box" _id="{$formdata['id']}">
{/if}
	<div>
		{if $type == '1'}
		<div class="ggk-time-box"><div class="ggk-time"><span>{$formdata['effective_time']}</span></div></div>
		<div class="ggk-prizenumber">{$formdata['title']}</div>
		<div class="ggk-wrap lottery-canvas-wrap">
			<div class="ggk-inner">
				<div class="ggk-canvas-area" id="ggk-canvas"></div>
				<div class="ggk-mask lottery-mask"></div>
			</div>
			<a class="ggk-game-btn">再刮一次</a>
		</div>
		{/if}
		{if $type == '2'}
			<div class="zhuanpan-head"></div>
			<div class="zhuanpan-wrap">
				<canvas id="zhuanpan-canvas" class="zhuanpan-canvas-area" width="260" height="300"></canvas>
				<div class="zhuanpan-arrow"></div>
				<a class="zhuanpan-start-btn lottery-start-btn">再玩一次</a>
				<div class="ggk-mask lottery-mask"></div>
			</div>
		{/if}
		<div class="ggk-awards-pop lottery-awards-pop">
			<div class="ggk-awards-pop-inner">
				<div class="close"></div>
				<div class="awards-tip-box lottery-result-box">
					<div class="awards-tip"></div>
					<div class="other-lottery-info"></div>
				</div>
				<div class="lottery-personinfo-box form-lottery-info">
					<div><span>联系电话：</span><input type="text" class="tel-txt" /></div>
					<div><span>联系地址：</span><textarea class="address-txtarea"></textarea></div>
					<div class="submit lottery-submit">提交</div>
				</div>
			</div>
		</div>
		<div class="lottery-nologin-area">
		<a class="lottery-refresh">刷新</a><a class="lottery-gologin">登录</a>
		</div>
	</div>
	<div class="lottery-limit-tip">
		<div class="lottery-limit-tip-area">
			<div class="msg"></div>
			<div class="controll">
				<span class="sure sure-mask">确定</span>
				<span class="cancle cancle-mask">取消</span>
			</div>
		</div>
	</div>
</div>
		
<script id="people-lottery-info" type="text/html">
{{each list as value i}}
<div class="item">
<span class="member-name">{{value.member_name}}</span><span class="member-prize">{{value.prize}}</span><span class="member-time">{{value.create_time}}</span>
</div>
{{/each}}
</script>
		
<script id="lottery-tip-info" type="text/html">
{{if !+id}}<div class="cry-icon"></div>{{/if}}
<span class="awards-name {{if !+id}}no-awards-name{{/if}}">{{tip}}</span>
{{if +id}}<div>恭喜你!</div>{{/if}}
{{if +id}}<div class="awards-duijiang lottery-reward-btn">去兑奖</div>{{/if}}
{{if pic}}<div class="awards-pic"><img src="{{pic}}" /></div>{{/if}}
</script>

<script>
var	globalprize = {code} echo $prize ? json_encode( $prize ) : 'null';{/code};
</script>

<script>
	function hg_window_destruct(){
		
	}
	
	$(function(){
		if( parent.window.lottery_bg_src ){
			var src = parent.window.lottery_bg_src;
			$('body').css({
				'background-image' : 'url(' +  src + ')'
			})
		}
	});
</script>
</body>
</html>