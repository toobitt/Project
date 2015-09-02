<?php
$route = array(
	0 => array(
		"name" => "1路",
		"info" => "无锡火车站方向",
	),
	1 => array(
		"name" => "10路",
		"info" => "金城桥方向",
	),
	2 => array(
		"name" => "11环形",
		"info" => "火车站方向",
	),
	3 => array(
		"name" => "211环形",
		"info" => "无锡火车站方向",
	),
);

$station = array(
	0 => array(
		"name" => "无锡火车站",
		"info" => "11路、211路、2路",
	),
	1 => array(
		"name" => "金城桥",
		"info" => "11路、234路、211路、2路、11路、234路、211路、2路",
	),
	2 => array(
		"name" => "火车站",
		"info" => "1路、111路、2路",
	),
	3 => array(
		"name" => "环形",
		"info" => "11路、211路、2路",
	),
);

$interchange = array(
	0 => array(
		"name" => "无锡火车站-金城桥",
	),
	1 => array(
		"name" => "金城桥-火车站",
	),
	2 => array(
		"name" => "火车站-无锡火车站",
	),
	3 => array(
		"name" => "环形-火车站",
	),
);

$type = $_GET['type'];
 if($type == 'route'){
	echo json_encode($route);
}else if( $type == 'station' ){
	echo json_encode($station);
}else{
	echo json_encode($interchange);
}

?>