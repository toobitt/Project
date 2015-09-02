<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
$gDBconfig = array(
'host'     => 'db.dev.hogesoft.com',
'user'     => 'root',
'pass'     => 'hogesoft',
'database' => 'dev_seekhelp',
'charset'  => 'utf8',
'pconnect' => '',
);
define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','seekhelp');//应用标识
define('SEEKHELP_ROLE', 77); //创建机构时默认角色
define('SEEKHELP_ORG', 4); //创建机构时默认组织
define('SHOW_OTHER_DATA', 0);//是否查看其他机构数据
define('IS_HIDE_MOBILE', 1);//是否隐藏手机用户部分用户名(手机号)

define('SEEKHELP_COMMENT_STATUS', 0); //用户评论时默认评论状态
define('SEEKHELP_STATUS', 1);//创建求助默认审核状态，0－未审核，1－已审核,不含图片和视频的
define('SEEKHELP_MATERIAL_STATUS', 1);////创建求助默认审核状态，0－未审核，1－已审核,含图片和视频的
define('BAIDU_CONVERT_DOMAIN','http://api.map.baidu.com/ag/coord/convert?from=0&to=4');
define('INITED_APP', true);
define('SEEKHELP_NEW_MEMBER', 1);
define('IS_DINGDONE_ROLE',1);//标识是否是叮当角色，如果是的话不做权限验证

$gGlobalConfig['default_state'] = 0;

//帐号状态
$gGlobalConfig['account_status'] = array(
  0 => '未审核',
  1 => '已审核',
  2 => '被打回',
);

$gGlobalConfig['comment_status'] = array(
  0 => '未审核',
  1 => '已审核',
  2 => '被打回',
);

$gGlobalConfig['seekhelp_status'] = array(
  0 => '未审核',
  1 => '已审核',
  2 => '被打回',
  3 => '已关注',
  4 => '已处理',
  5 => '未处理',
);

$gGlobalConfig['seekhelp_push'] = array(
  0 => '未推送',
  1 => '已推送',
);
$gGlobalConfig['seekhelp_reply'] = array(
  0 => '未回复',
  1 => '已回复',
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
//敏感词过滤规则
$gGlobalConfig['contribute_colation'] = array(
  1 => "禁止入库", 
  2 => "入库未审核",
  3 => "过滤敏感词"
 );
$gGlobalConfig['used_search_condition'] =  array (
);

define('IS_BANWORD', 0);

define('COLATION_TYPE', 1);

define('NODE_COUNT', 20);

define('MAX_SECTION_NUMBER', 6); //默认社区版块创建数量

define('LIMIT_POSTING_TIME', 180); //发帖的限制时间
?>