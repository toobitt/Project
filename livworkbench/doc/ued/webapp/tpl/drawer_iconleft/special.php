<?php 
$title = '专题';
$icons = array(
	left => array(
		icons => array(
			0 => 'drawer',
		)
	),
	right => array(
		0 => 'drawer_avatar'
	),
);
require '../../lib/head/head.php';
?>
<div class="drawer-wrap">
<div class="drawer-box drawer-second m2o-flex">
		<?php 
			require '../../lib/unit/drawer_nav_iconleft.php';
			?>
	<div class="content m2o-flex-one">
		<?php 
		require '../../lib/unit/nav.php';
		?>
<link rel="stylesheet" type="text/css" href="../../lib/css/list.css" />
<section class="main-wrap">
		<?php 
			require '../../lib/unit/list_big_pic.php';
		?>
		</section>
	</div>
	<?php 
	require '../../lib/unit/drawer_login.php';
	?>
</div>
</div>
<?php 
require '../../lib/footer/footer.php';
?>
