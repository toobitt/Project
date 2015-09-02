<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
$gDBconfig = array(
	'host' => 'db.dev.hogesoft.com',
	'user' => 'root',
	'pass' => 'hogesoft',
	'database' => 'dev_staff',
	'charset' => 'utf8',
	'pconncet' => '0'
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','staff');//应用标识

//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
$gGlobalConfig['staff_sex'] = array(
	0=>'保密',
	1=>'男',
	2=>'女',
);
$gGlobalConfig['staff_status'] = array(
	0=>'未审核',
	1=>'已审核',
	2=>'被打回',
);
$gGlobalConfig['staff_married'] = array(
	0=>'保密',
	1=>'未婚',
	2=>'已婚',
);
$gGlobalConfig['staff_degree'] = array(
	0=>'保密',
	1=>'博士',
	2=>'硕士研究生',
	3=>'本科',
	4=>'专科',
	5=>'高中',
	6=>'中专',
	7=>'初中',
	8=>'小学',
);
$gGlobalConfig['staff_phpqrcode'] = array(
	'open'=>1,
	'errorCorrectionLevel'=>'L',   //图片质量
	'matrixPointSize'=>4,        //图片大小
	'path'=>ROOT_PATH .'api/staff/cache/qrcode/',    //生成二维码的临时目录
	'margin'=>2                    //二维码边距
);
$gGlobalConfig['staff_card'] = array(
	'protocol' => 'http://',
	'host' => 'localhost',
	'dir' => 'livsns/api/staff/',
	'path'=>'cache/card/',    //生成水印临时目录
);
$gGlobalConfig['staff_infor'] = array(
	'tel' => '4000012000',
	'company' => '南京厚建软件有限责任公司',
	'company_addr' => '南京市雨花大道2号 邦宁科技园 4楼',
	'en_company_addr'=>'4F, Bangning technology park, No.2, Yuhua avenue,  Nanjing',
	'web'=>'www.hoge.cn',
);
$gGlobalConfig['staff_zip'] = array(
	'protocol' => 'http://',
	'host' => '10.0.1.40',			
	'dir' => 'livsns/api/staff/',
	'path'=>'cache/temp/',    //zip临时文件存放位置
	
);
define('INITED_APP', true);
?>