{code}
$res = array();
$res[] = array(
	'src' => $RESOURCE_URL.'m2o/pic2.png',
	'title' => '希拉里就钓鱼岛问题警告中国',
	'content' => '希拉里就钓鱼岛问题警告中国...',
);
$res[] = array(
	'src' => $RESOURCE_URL.'m2o/pic3.png',
	'title' => '中国不甩希拉里的废话',
	'content' => '希拉里就钓鱼岛问题警告中国...',
);
$res[] = array(
	'src' => $RESOURCE_URL.'m2o/pic4.png',
	'title' => '希拉里很是尴尬',
	'content' => '希拉里就钓鱼岛问题警告中国...',
);
$res[] = array(
	'src' => $RESOURCE_URL.'m2o/pic5.png',
	'title' => '希拉里很是尴尬',
	'content' => '希拉里就钓鱼岛问题警告中国...',
);
$res[] = array(
	'src' => $RESOURCE_URL.'m2o/pic3.png',
	'title' => '希拉里很是尴尬',
	'content' => '希拉里就钓鱼岛问题警告中国...',
);
$res[] = array(
	'src' => $RESOURCE_URL.'m2o/pic3.png',
	'title' => '希拉里很是尴尬',
	'content' => '希拉里就钓鱼岛问题警告中国...',
);
$res[] = array(
	'src' => $RESOURCE_URL.'m2o/pic3.png',
	'title' => '希拉里很是尴尬',
	'content' => '希拉里就钓鱼岛问题警告中国...',
);
$res[] = array(
	'src' => $RESOURCE_URL.'m2o/pic3.png',
	'title' => '希拉里很是尴尬',
	'content' => '希拉里就钓鱼岛问题警告中国...',
);
$res[] = array(
	'src' => $RESOURCE_URL.'m2o/pic3.png',
	'title' => '希拉里很是尴尬',
	'content' => '希拉里就钓鱼岛问题警告中国...',
);
{/code}


<div class="long_slide_display_wrapper">
	<div class="long_slide_display">
		<ul class="conts">
			{foreach $res as $v}
			<li><img src="{$v['src']}" /><em>{$v['title']}</em></li>
			{/foreach}
		</ul>
	</div>
	<div class="ctrlBtn">
		<a class="ctrlPrev"><em></em></a>
		<a class="ctrlNext"><em></em></a>
	</div>
</div>

<script>
$(function() {
	  $('.long_slide_display').switchable({
	    triggers: false,
	    putTriggers: 'insertBefore',
	    panels: 'li',
	    easing: 'ease-in-out',
	    effect: 'scrollLeft',
	    steps: 1,
	    visible: 6, // important
		loop: true,
	    end2end: true,
	    autoplay: true,
	    prev: $(".ctrlPrev"),
	    next: $('.ctrlNext')
	  });
});
</script>
