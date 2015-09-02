<?php 
$title = $_GET['address'];
$name = $_GET['name'];
$icons = array(
	left => array(
		0 => 'back'
	),
);
require '../../lib/head/head.php';
$bike_icon = array(
	selected => 'general',
	icons => array(
		0 => 'general',
		1 => 'notice'
	)
);
require '../../lib/unit/nav.php';
?>
<link href="../../lib/css/bike.css" type="text/css" rel="stylesheet" />
<script src="../../lib/js/baidu_map.js" type="text/javascript"></script>
<?php require '../../lib/unit/bike/website_tab.php';?>
<div class="main-wrap website">
	<div class="general m2o-flex">
		<div class="pic"><img src="../../lib/images/bike/public-bike.jpg" /></div>
		<div class="m2o-flex-one">
			<p class="web-item bikenum">可借车数: <span>12</span></p>
			<p class="web-item carportnum">可停车位: <span>3</span></p>
			<p class="update">数据更新于：01-17 08:20</p>
			<p class="address">地址：鸿山遗址博物馆广场</p>
			<div class="distance">450m</div>
		</div>
	</div>
	<div class="website-map" id="allmap"></div>
	<div class="gohere"><a href="./website_map.php">到这里去</a></div>
	<!-- <p class="time">工作日（周一~周五9:00~19:00），周末（周六周日）10:00~18:00；</p>
	<p class="tips">本站点可以办理自行车卡；</p> -->
	<p class="business">运营单位：无锡万紫共用传系统有限公司</p>
	<p class="hotline">服务热线：400-0510-889</p>
	<p class="reminding">本公司自行车与3Q自行车暂时不能通还通借，请大家还车时注意！</p>
</div>
</body>
</html>