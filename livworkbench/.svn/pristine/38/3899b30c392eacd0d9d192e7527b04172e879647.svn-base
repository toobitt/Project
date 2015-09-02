<?php 
require '../../lib/head/head.php';
$icons = array(
	left => array(
		0 => 'back'
	),
	right => array(
		0 => 'comment'
	)
);
require '../../lib/unit/nav.php';
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/content.css" />
<link rel="stylesheet" type="text/css" href="../../lib/css/news.css" />
<div class="main-wrap">
<?php 
require '../../lib/unit/artical.php';
?>
</div>
<?php 
$tabbar = array(
	0 => 'share',
	1 => 'comment',
	2 => 'collect',
	3 => 'font'
);
require '../../lib/unit/tabbar.php';
?>
<?php 
require './share.php';
?>
<script>
$(function(){
	$('.icon.share').on('click',function(){
		$('.share-wrap').addClass('show');
	});
});
</script>