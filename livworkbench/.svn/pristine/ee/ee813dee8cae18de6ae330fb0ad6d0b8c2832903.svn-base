<?php
$nearby_route = array(
	0 => array(
		"num" => "211",
		"special_flag" => "环形",
		"terminal" => "中央公园",
		"nearest" => "运河饭店",
		"distance" => "1"
	),
	1 => array(
		"num" => "56",
		"special_flag" => "环形",
		"terminal" => "金城桥",
		"nearest" => "运河饭店",
		"distance" => "1"
	),
	2 => array(
		"num" => "131",
		"terminal" => "市民中心西",
		"nearest" => "运河饭店",
		"distance" => "1"
	),
	3 => array(
		"num" => "宜家",
		"special_flag" => "专线",
		"terminal" => "中央公园",
		"nearest" => "运河饭店",
		"distance" => "1"
	),
);

$detail = array(
	0 => array(
		"name" => "金城桥",
	),
	1 => array(
		"name" => "五星家园",
	),
	2 => array(
		"name" => "馨园新村",
	),
	3 => array(
		"name" => "新洋市场",
	),
	4 => array(
		"name" => "清河桥",
	),
);

$station = array(
	0 => array(
		"name" => "金城桥",
		"detail" => "11路、11路、11路、11路、11路、11路"
	),
	1 => array(
		"name" => "五星家园",
		"detail" => "11路、11路、11路、11路、11路、11路"
	),
	2 => array(
		"name" => "馨园新村",
		"detail" => "11路、11路、11路、11路、11路、11路"
	),
	3 => array(
		"name" => "新洋市场",
		"detail" => "11路、11路、11路、11路、11路、11路11路、11路、11路、11路、11路、11路"
	),
	4 => array(
		"name" => "清河桥",
		"detail" => "11路、11路、11路、11路、11路、11路"
	),
);

$pos = array(
	0 => array(
		"name" => "金城桥",
		"detail" => "江苏省无锡市"
	),
	1 => array(
		"name" => "五星家园",
		"detail" => "江苏省无锡市"
	),
	2 => array(
		"name" => "馨园新村",
		"detail" => "江苏省无锡市"
	),
	3 => array(
		"name" => "新洋市场",
		"detail" => "江苏省无锡市"
	),
	4 => array(
		"name" => "清河桥",
		"detail" => "江苏省无锡市"
	),
);

$a = $_GET['a'];
if($a == 'detail'){
	echo json_encode($detail);
}else if($a == 'station'){
	echo json_encode($station);
}else if($a == 'pos'){
	echo json_encode($pos);
}else{
	echo json_encode($nearby_route);
}

?>