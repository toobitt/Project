{if $fullNav}
<div class="live-left-nav">
<div class="live-left-nav-inner">
	<ul class="live-nav-list">
	{if $_configs['interactive_nav']}
	{foreach $_configs['interactive_nav'] AS $k => $v}
		<li><a {if $_INPUT['mid'] == $k}class="current-nav"{/if} href="run.php?mid={$k}&infrm=1&channel_id={$_INPUT['channel_id']}">{$v}</a></li>
	{/foreach}
	{/if}
	</ul>
	<?php 
	/*<ul class="live-nav-list">
		<li><a class="backMain">返回导播页面</a></li>
	{if $_configs['director_nav']}
		{foreach $_configs['director_nav'] AS $k => $v}
			<li><a href="run.php?mid={$k}&infrm=1&channel_id={$_INPUT['channel_id']}">{$v}</a></li>
		{/foreach}
	{/if}
	</ul>*/
	?>
</div>
</div>
<script>
$(function () {
	var box = $(".live-left-nav-inner");
	$(".live-left-nav .subnavBtn").click(function (e) {
		box.animate({
			"margin-left": "-132px"
		});
		return false;
	});
	$(".live-left-nav .backMain").click(function (e) {
		box.animate({
			"margin-left": 0
		});
	});
});
</script>
{/if}