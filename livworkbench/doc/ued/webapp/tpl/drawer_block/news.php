<?php
$title = '新闻';
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
<div class="drawer-box drawer-third m2o-flex">
		<?php 
			require '../../lib/unit/drawer_nav_block.php';
			?>
	<div class="content m2o-flex-one">
		<?php 
		require '../../lib/unit/nav.php';
		?>
		<section class="main-wrap main-wrap-nopadding">
		<?php 
		require '../../lib/unit/slide.php';
		?>
		<?php 
		require '../../lib/unit/list.php';
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