<?php
$awards = rand(0,3);

$allAwards = array(
	0 => array(
		'id' => 1,
		'cname' => '一等奖',
		'flag' => 'yi'
	),
	1 => array(
		'id' => 2,
		'cname' => '二等奖',
		'flag' => 'er'
	),
	2 => array(
		'id' => 3,
		'cname' => '三等奖',
		'flag' => 'san'
	),
	3 => array(
		'id' => 4,
		'cname' => '四等奖',
		'flag' => 'si'
	),
);

$nullAwards = array(
	'msg' => 'nooooo',
	'flag' => ''
);

$a = $_GET['a'];
if( $a == 'getAllAwards' ){
	echo json_encode( $allAwards );
}else if($a=='currentAwards'){
//	echo json_encode( $allAwards[ $awards ] );
	echo json_encode( $nullAwards );
}
?>