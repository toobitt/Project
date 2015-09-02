<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 7642 2012-07-06 09:13:47Z wangleyuan $
***************************************************************************/

$gDBconfig = array(
    'host'     => 'db.dev.hogesoft.com',
    'user'     => 'root',
    'pass'     => 'hogesoft',
    'database' => 'dev_im',
    'charset'  => 'utf8',
    'pconncet' => 0,
);

define('DB_PREFIX','liv_');     //定义数据库表前缀
define('APP_UNIQUEID','im');
define('AUDIO_DOMAIN', 'http://10.0.1.40/livsns/api/im/data/');  //音频文件访问域名  指向data目录

define('INITED_APP', true);

/****************融云配置start*****************/
define('RC_APPLY_URL','http://api.developer.rongcloud.cn/app/createApp.json');  //创建应用 申请appkey appsecret
define('RC_DELETE_URL','http://api.developer.rongcloud.cn/app/deleteApp.json');  //删除应用
define('RC_GET_APPINFO_URL','http://api.developer.rongcloud.cn/app/getAppInfo.json');  //获取指定应用名称的应用信息
$gGlobalConfig['rc_func'] = array(
        'create' => '/group/create',
        'quit'   => '/group/quit',
        'join'   => '/group/join',
        'sync'   => '/group/sync',
        'dismiss'=> '/group/dismiss',
        'getToken'=>'/user/getToken',
        'refresh' => '/user/refresh',
        'addBlacklist' => '/user/blacklist/add',
        'removeBlacklist' => '/user/blacklist/remove',
);
define('CATEGORY','19');  //新闻
define('APP_KEY','c1c54d894cd24');
define('APP_SECRET','8b31d175a5894e4');
define('RC_USERID','8182');
define('RC_APP_VERSION','dev');
define('MAX_GROUP_NUM', 3);

/****************融云配置end******************/

$gGlobalConfig['type'] = array(
 5 => '所有用户',
 1 => '单一用户',
 2 => '会员组',
 3 => 'm2o角色',
 4 => 'm2o部门',
);

$gGlobalConfig['statu'] = array(
 0 => '未阅读',
 1 => '已阅读',
 2 => '已删除',
);
$gGlobalConfig['status'] = array(
 0 => '未审核',
 1 => '已审核',
 2 => '已打回',
);


