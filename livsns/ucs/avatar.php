<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: avatar.php 2483 2011-03-03 04:24:30Z develop_tong $
*/


error_reporting(0);

define('UC_API', strtolower(($_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'))));

$uid = isset($_GET['uid']) ? $_GET['uid'] : 0;
$size = isset($_GET['size']) ? $_GET['size'] : '';
$random = isset($_GET['random']) ? $_GET['random'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$check = isset($_GET['check_file_exists']) ? $_GET['check_file_exists'] : '';

$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
$aindex = array(
	'big' => 0,
	'middle' => 1,
	'small' => 2,
);
$url = 'http://api.hcrt.cn/users/show_avatar.php?user_id=' . $uid . '&type=' . $aindex[$size];
header('Location:' . $url);
?>