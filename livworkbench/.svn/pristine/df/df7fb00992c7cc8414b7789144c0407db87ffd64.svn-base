<?php
$list = array(
	0 => array(
		"id" => "1",
		"credit" => "3000000",
		"title" => "湖景桃，全球最好吃的水蜜桃限量供应",
		"image" => './images/shopping.png',
		"lottery" => true,
		"num" => 78
	),
	1 => array(
		"id" => "2",
		"credit" => "280",
		"title" => "DQ 冰雪皇后 10元代金券",
		"image" => './images/shopping1.png',
		"lottery" => false,
		"num" => 33
	),
	2 => array(
		"id" => "3",
		"credit" => "280",
		"title" => "全家 FamilyMart 20元代金券",
		"image" => './images/shopping2.png',
		"lottery" => false,
		"num" => 520
	),
	3 => array(
		"id" => "4",
		"credit" => "42000",
		"title" => "智慧无锡移动电源 13000MAH",
		"image" => './images/shopping3.png',
		"lottery" => false,
		"num" => 9
	),
);

$detail = array(
	"title" => "湖景桃 全球最好吃的水蜜桃限量供应",
	"image" => './images/shopping5.png',
	"credit" => "998",
	"desc" => '千盼万盼，阳山水蜜桃里的甜度之王终于上市了，前段时间雨水充足，白凤的甜度可能没那么高，可是等来了湖景，湖景就算是雨水再多都是甜阿，今年雨水充足，湖景也变得多汁甜美，口感一级棒。智慧无锡“水蜜桃季特惠活动”继续中。',
	"hot" => '千盼万盼，阳山水蜜桃里的甜度之王终于上市了，前段时间雨水充足，白凤的甜度可能没那么高，可是等来了湖景，湖景就算是雨水再多都是甜阿，今年雨水充足，湖景也变得多汁甜美，口感一级棒。智慧无锡“水蜜桃季特惠活动”继续中。',
	"num" => 171,
	"surplus" => 99829,
	"regulation" => '1.同一个订单中可以使用多张商品兑换券2.商品兑换券不和账户绑定，用户中心无法查询商品兑换券3.提交订单成功后，订单中的商品兑换券视为“已使用”'
);

$record = array(
	0 => array(
		"id" => "1",
		"credit" => "12998",
		"title" => "湖景桃，全球最好吃的水蜜桃限量供应",
		"image" => './images/shopping.png',
		"num" => 1,
		"create_time" => '2014-07-13 14:30',
		"order" => '6374172741',
		"status" => '待领取',
		'state' => 0
	),
	1 => array(
		"id" => "2",
		"credit" => "280",
		"title" => "DQ 冰雪皇后 10元代金券",
		"image" => './images/shopping1.png',
		"num" => 1,
		"create_time" => '2014-07-13 14:30',
		"order" => '6374172741',
		"status" => '待领取',
		'state' => 1
	),
	2 => array(
		"id" => "3",
		"credit" => "280",
		"title" => "全家 FamilyMart 20元代金券",
		"image" => './images/shopping2.png',
		"num" => 2,
		"create_time" => '2014-07-13 14:30',
		"order" => '6374172741',
		"status" => '待领取',
		'state' => 2
	),
	3 => array(
		"id" => "4",
		"credit" => "42000",
		"title" => "智慧无锡移动电源 13000MAH",
		"image" => './images/shopping3.png',
		"num" => 9,
		"create_time" => '2014-07-13 14:30',
		"order" => '6374172741',
		"status" => '待领取',
		'state' => 1
	),
);

$type = $_GET['type'];
 if($type == 'list'){
	echo json_encode( $list );
}else if( $type == 'detail' ){
	echo json_encode($detail);
}else if( $type == 'record' ){
	echo json_encode($record);
}else{
	echo json_encode($interchange);
}

?>