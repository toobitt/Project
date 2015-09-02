<?php 
$title = '南京';
$icons = array(
	left => array(
		icons => array(
			0 => 'back'
		)
	),
);
require '../../lib/head/head.php';
?>
<?php
$list = array(
	0 => array(
		week => '星期三',
		max => '28',
		min => '22',
	),
	1 => array(
		week => '星期四',
		max => '22',
		min => '18',
	),
	2 => array(
		week => '星期五',
		max => '19',
		min => '16',
	),
)
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/weather.css" />
<style>
.m2o-nav{background:transparent;}
</style>
<div class="weather-wrap weather m2o-flex m2o-vertical">
	<?php require '../../lib/unit/nav.php';?>
	<div class="m2o-flex m2o-flex-center m2o-flex-one">
		<div class="today m2o-flex-one">
			<div class="today-pic">
				<img src="../../lib/images/weather/today.png"/>
			</div>
			<p class="temperature">19<span class="icon-du">°</span>/17<span class="icon-du">°</span></p>
			<p class="date">星期二 11月13日</p>
		</div>
	</div>
	<section class="m2o-flex forecast">
		<?php 
		foreach ( $list as $k => $v ){
		?>
		<div class="forecast-item m2o-flex-one">
			<p class="week"><?php echo $v['week']?></p>
			<div class="weather-pic">
				<img src="../../lib/images/weather/pic.png">
			</div>
			<p class="temperature"><?php echo $v['max']?><span class="icon-du">°</span>/<?php echo $v['min']?><span class="icon-du">°</span></p>
		</div>
		<?php }?>
	</section>
	<footer>
		<p class="update-time">更新于今天12：50</p>
	</footer>
</div>
<?php 
require '../../lib/footer/footer.php';
?>