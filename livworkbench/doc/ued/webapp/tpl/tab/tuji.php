<?php 
$title = '图集';
$icons = array(
	left => array(
		weather => array(
			icon => 'sunny',
			city => '南京',
			max => '22',
			min => '18'
		)
	),
	right => array(
		0 => 'avatar'
	)
);
$foot_icon = array(
	selected => 'pic',
	icons => array(
		0 => 'home',
		1 => 'pic',
		2 => 'topics',	//专题
		3 => 'video',
		4 => 'more'
	)
);
require '../../lib/head/head.php';
require '../../lib/unit/nav.php';
?>
<section class="main-wrap has-foot">
<?php 
require '../../lib/unit/list_pinterest.php';
?>
</section>
<?php 
require '../../lib/unit/tab_foot.php';
?>
<?php 
require '../../lib/footer/footer.php';
?>