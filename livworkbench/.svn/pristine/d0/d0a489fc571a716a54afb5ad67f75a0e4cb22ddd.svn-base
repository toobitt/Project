<?php
require '../../lib/head/head.php';
$title = '56环形';
$icons = array(
	left => array(
		0 => 'back'
	),
);
require '../../lib/unit/nav.php';
$bus_data = array(
	0 => array(
		name => '金城桥',
		time => '16:04:20'
	),
	1 => array(
		name => '五星家园（金城桥）'
	),
	2 => array(
		name => '沁园新村'
	),
	3 => array(
		name => '沁园市场',
		time => '16:03:10'
	),
	4 => array(
		name => '清河桥'
	),
	5 => array(
		name => '中星苑'
	),
	6 => array(
		name => '运河饭店'
	),
	7 => array(
		name => '中桥二村'
	),
);
?>
<style>
.bus-info{background:#eee;color:#666;font-size:12px;line-height:20px;padding:10px 15px;border-bottom:1px solid #cbcbcb;}
.bus-detail-list{background:#fafafa;}
.bus-detail-list li{border-bottom:1px solid #cbcbcb;height:50px;color:#999;}
.bus-detail-list li .icon-area{width:74px;}
.bus-detail-list li .red{color:#dd5858;}
.bus-detail-list li .name{height:34px;line-height:34px;}
.bus-detail-list li .time{font-size:11px;}
</style>
<div class="subnav m2o-flex">
	<a class="item m2o-flex-one selected" href="../../tpl/bus/geolocation_failed.php">开往雨花台</a>
	<a class="item m2o-flex-one" href="../../tpl/bus/route.php">开往新街口</a>
</div>
<div class="bus-info">
	<p>首末班车：无锡中央车站 发出6:00-21:00</p>
	<p>公交车距离最近站<span>运河饭店</span>还有<span>5站</span></p>
</div>
<div class="bus-detail-list">
	<ul>
		<?php foreach ($bus_data as $k=>$v){?>
		<li class="m2o-flex m2o-flex-center">
			<div class="icon-area">
			</div>
			<div class="<?php if($v['time']){ echo 'red'?><?php }?>">
				<p class="name"><?php echo $v['name']?></p>
				<?php if($v['time']){?><p class="time">到站时间：<?php echo $v['time']?></p><?php }?>
			</div>
		</li>
		<?php }?>
	</ul>
</div>