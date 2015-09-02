<?php
$title = '应用配色/背景管理';
require '../../lib/head/head.php';
require '../../lib/unit/nav.php';
$set_color = array(
//	blue => '#22ade6',
	red => '#cd0102',
	yellow => '#ffb53c',
	grey => '#5a7797',
	green => '#5a965a'
);
$magazine_foot = array(
	left => array(
		0 => 'back',
	),
);

?>
<link rel="stylesheet" type="text/css" href="../../lib/css/setting.css" />
<div class="main-wrap setting has-foot">
	<section class="theme">
		<h3>配色选择</h3>
		<div class="select-box m2o-column-five">
			<div class="color-item theme-blue selected" style="background:#22ade6"></div>
			<?php foreach( $set_color as $k=>$v ){?>
			<div class="color-item theme-<?php echo $k?>" style="background:<?php echo $v?>"></div>
			<?php }?>
		</div>
	</section>
	<section class="bg">
		<h3>背景选择</h3>
		<div class="select-box clear">
			<div class="bg-item add">
				<img src="">
			</div>
			<?php for( $i=1; $i<=8; $i++ ){?>
			<div class="bg-item">
				<a>
					<img src="../../lib/images/instasrc/200x200_<?php echo $i?>.jpg"/>
				</a>
			</div>
			<?php }?>
		</div>
	</section>
</div>
<?php 
require '../../lib/unit/magazine_foot.php';
?>
<?php 
require '../../lib/footer/footer.php';
?>