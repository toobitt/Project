<?php
$awards = rand(0,5);

$allAwards = array(
	0 => array(
		'hasAwards' => true,
		'awards' => '一等奖'
	),
	1 => array(
		'hasAwards' => true,
		'awards' => '二等奖'
	),
	2 => array(
		'hasAwards' => true,
		'awards' => '二等奖'
	),
	3 => array(
		'hasAwards' => true,
		'awards' => '三等奖'
	),
	4 => array(
		'hasAwards' => true,
		'awards' => '三等奖'
	),
	5 => array(
		'hasAwards' => true,
		'awards' => '三等奖'
	),
);

$a = $_GET['a'];
if( $a == 'getAllAwards' ){
	echo json_encode( $allAwards );
}else if($a=='getTotleTimes'){
	echo json_encode( 10 );
}else if($a=='currentAwards'){
	echo json_encode( $awards );
}
?>