<?php 
require '../../lib/head/head.php';
$title = '路况';
$icons = array(
	left => array(
		0 => 'modules'
	),
	right => array(
		0 => 'avatar'
	)
);
require '../../lib/unit/nav.php';
$selected = '全部';
$subnav = array(
	0 => array(
		name => '全部',
		url => '../../tpl/road/road_all.php'
	),
	1 => array(
		name => '事故',
		url => '../../tpl/road/road_accident.php'
	),
	2 => array(
		name => '拥堵',
		url => '../../tpl/road/road_congestion.php'
	),
	3 => array(
		name => '施工',
		url => '../../tpl/road/road_construct.php'
	),
	4 => array(
		name => '管制',
		url => '../../tpl/road/road_regulate.php'
	),
	5 => array(
		name => '其他',
		url => '../../tpl/road/road_other.php'
	)
);
require '../../lib/unit/subnav.php';
$list = array(
	0 => array(
		title => '新华路锡义路两车相擦；',
		type => '事故',
		ntime => '08:48',
		aobj => '无锡交通广播',
	),
	1 => array(
		title => '兴园路永乐东路不到往火车站方向两起追尾事故。',
		type => '管制',
		ntime => '08:29',
		aobj => '无锡交通广播',
	),
	2 => array(
		title => '锡曾路锡沪路进城方向小车和电动车相撞。',
		type => '其他',
		ntime => '08:23',
		aobj => '无锡交通广播',
	),
	3 => array(
		title => '北环路转312国道车流量大；',
		type => '拥堵',
		ntime => '08:03',
		aobj => '无锡交通广播',
	),
	4 => array(
		title => '惠山隧道往万达方向车多缓行；',
		type => '施工',
		ntime => '08:02',
		aobj => '无锡交通广播',
	),
);
function getBgdColor( $flag ){
	switch( $flag ){
		case '事故' : 
			echo 'accident';
			break;
		case '拥堵' : 
			echo 'congestion';
			break;
		case '管制' : 
			echo 'regulate';
			break;
		case '施工' : 
			echo 'construct';
			break;
		case '其他' : 
			echo 'other';
			break;
	}
}
?>
<link href="../../lib/css/road.css" type="text/css" rel="stylesheet" />
<div class="main-wrap road">
	<?php if($list){?>
		<ul class="road-list">
		<?php 
		foreach( $list as $k => $v ){
			require '../../lib/unit/list_road.php';
		}
		?>
		</ul>
	<?php }else{?>
		<p class="nodata">暂无此类路况信息</p>
	<?php } ?>
</div>
</body>
</html>