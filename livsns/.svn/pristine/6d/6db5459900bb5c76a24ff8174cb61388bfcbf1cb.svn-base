<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 2354 2011-02-28 09:25:18Z chengqing $
***************************************************************************/

$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_auth',
'charset'  => 'utf8',
'pconnect' => '',
);
define('DB_PREFIX','liv_');
define('PRIVATE_KEY_NUM', 8);
define('KEY_EXPIRE_DATE',60);
define('DYNAMIC_TOKEN_LEN', 6);
define('APP_MOD_SEP', '@');
define('IS_EXTEND', 0);//如果为真，即使不传获取扩展信息参数也附加输出
$gGlobalConfig['auth_setting'] = array(
	'open_audit' => 0,
	'open_encript' => 0,
);
//auth的状态
$gGlobalConfig['auth_status'] = array(
 -1 => '全部状态',
  1 => "待审核",
  2 => "已审核",
  3 => "被打回"
);
$gGlobalConfig['update_audit_content'] = array(
  0 => "保持原状态",
  1 => "待审核",
  2 => "已审核",
  3 => "已打回"
);
$gGlobalConfig['update_publish_content'] = array(
  0 => "保持原状态",
  1 => "待审核",
  2 => "已审核",
  3 => "已打回"
);
$gGlobalConfig['create_content_status'] = array(
  0 => "系统默认",
  1 => "待审核",
  2 => "已审核",
);
$gGlobalConfig['org_state'] = array(
  5 => "全部",
  1 => "组织内",
);
define('APP_UNIQUEID','auth');
//时间搜索
$gGlobalConfig['date_search'] = array(
  1 => '所有时间段',
  2 => '昨天',
  3 => '今天',
  4 => '最近3天',
  5 => '最近7天',
  'other' => '自定义时间',
);
 
$gGlobalConfig['auth_server_api'] = array(
	'protocol' => 'http://',
	'host' => 'auth.hogesoft.com',
    	'port' => '80',
);
$gGlobalConfig['auth_op'] = array(
'create'=>'增加',
'update'=>'更新',
'audit'=>'审核',
'publish'=>'发布',
'put'=>'投放',
'delete'=>'删除',
'show'=>'查看',
'manage'=>'管理',
'manger'=>'管理',
);

//密保卡开关
$gGlobalConfig['mibao'] =  array (
  'open' => '0',
);

//扩展字段类型
$gGlobalConfig['extendFieldType'] = array(
 			'0'=>array('type'=>'text','type_name'=>'文本数据'),
			'1'=>array('type'=>'img','type_name'=>'图片上传'),
		);

define('TOKEN_EXPIRED', 86400);
define('INITED_APP', true);
?>