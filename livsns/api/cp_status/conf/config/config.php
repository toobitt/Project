<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 3344 2011-04-02 08:00:14Z repheal $
***************************************************************************/

$gDBconfig = array(
	'host'     => '10.0.1.31',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_sns_mblog',
	'charset'  => 'utf8',
	'pconncet' => 0,
);
define('INITED_APP', true);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('WORDS_NUM',420); //点滴发表字数
define('DOMAIN','video.hcrt.cn/vod');
define('APP_UNIQUEID','cp_status');

define( "WB_AKEY" , '2613888264');
define( "WB_SKEY" , '96a32662abb819e1ae64a03b470bb8cc');
define('TOKEN', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
//举报类型定义 与liv_report表中type字段一致
$gReportType = array(
		1		=>	'地盘',
		2		=>	'帖子',
		3		=>	'视频',
		4		=>	'微博',
		5		=>	'相册',
		7		=>	'照片',
		10		=>	'频道',
		11		=>	'用户',
		13		=>	'活动',
		6		=>	'视频评论',
		8		=>	'帖子回复',
		9		=>	'点滴评论',		
		12		=>	'频道评论',
		
);
define('TOPIC_URL',  'k.php');

$gGlobalConfig['report_node_type']=array(
	1 => '最近更新',
	2 =>'最多举报',
);

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);

//微博查询条件
$gGlobalConfig['status_node'] = array(
  1 => '最新更新',
  2 => '最多评论',
  3 => '最多转发',
  4 => '最多举报'
);

//点滴状态
$gGlobalConfig['state'] = array(
  -1 => '全部状态',
   0 => '已审核',
   1 => '待审核',
);

//点滴评论查询条件
$gGlobalConfig['comment_node'] = array(
  1 => '最新更新',
  2 => '最多评论',
  3 => '最多@'
);

//点滴话题查询条件
$gGlobalConfig['topic_node'] = array(
  1 => '最新更新',
  2 => '最多点滴',
  3 => '最多相似'
);
?>