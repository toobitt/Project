<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: global.conf.php 2271 2011-02-26 09:04:15Z repheal $
***************************************************************************/
$gNavConfig = array(
	'ucenter_url' => 'index.php',
	'main' => array(
		'index' => '',
		'city' => SNS_TOPIC,
		'live' => '',
		'channel' => SNS_VIDEO,
		'hzchannel' => '',
	),
	'lang' => array(
		'index' => '首页',
		'city' => '城事',
		'live' => '聚友',
		'channel' => '葫芦台',
		'hzchannel' => '杭州台',
		'upload_video' => '发视频',
		'upload_pic' => '晒图片',
		'write_blog' => '说两句',
		'listen_bc' => '听广播',
		'watch_tv' => '看电视',
		'check_group' => '逛社区'
	),
	'user' => array(
		'upload_video' => '',
		'upload_pic' => '',
		'write_blog' => '',
		'listen_bc' => '',
		'watch_tv' => '',
		'check_group' => 'http://127.0.0.1/topic/group/'
	),
);

?>