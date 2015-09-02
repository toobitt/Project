<?php
$gDBconfig = array(

'host'     => 'db.dev.hogesoft.com',

'user'     => 'root',

'pass'     => 'hogesoft',

'database' => 'dev_cdn',

'charset'  => 'utf8',

'pconnect' => '',

);


define('DB_PREFIX','liv_');            //定义数据库表前缀
define('APP_UNIQUEID','cdn');        	//应用标识
define('DEBUG_OPEN',false);
define('INITED_APP', true);

$gGlobalConfig['date_search'] = array(
		1 => '今天',
		2 => '最近3天',
		3 => '最近7天',
		4 => '最近15天',
		5 => '最近30天',
		'other' => '自定义时间',
);

$gGlobalConfig['cdn_log_type'] = array(
		'-1'		=> '全部类型',
		'auth'  	=> '登陆',
		'account'   => '帐号',
		'bucket'    => '空间',
		'operator'  => '操作员',
		'file'  	=> '文件',
);

$gGlobalConfig['cdn_log_analysis_type'] = array(
		'url'  			=> '热名文件',
		'ip'   			=> '热门IP',
		'referer'    	=> '热门引用页面',
		'user_agent'  	=> '热门客户端',
		'http_status'  	=> '资源状态',
		'size'  		=> '文件大小',
);


/******************************** chinacache configure *****************************/
/*
 * email 表示接收反馈的电子邮箱,可以为空。
 * acptNotice 表示接收成功时是否反馈,仅在 email 有效时有效。
 * 当 url 和 email 都为空时,不反馈。
 */
//push define
define('CDN_STATUS', 1); 
define('FAIL_DATA_LIMIT',1000);						//计划任务每次处理记录数

define('CDN_MAX_DIR_NUM',100);				//需要刷新的 url 的地址,可以是多个,最多支持 100 条
define('CDN_MAX_URL_NUM',10);				//是需要刷新的目录的地址,最多支持 10 条

define('ChinaCache_UserName', '');
define('ChinaCache_Password', '');
//curl 会删除
$gGlobalConfig['ChinaCache']['host'] = 'https://r.chinacache.com';
$gGlobalConfig['ChinaCache']['dir'] = '/content/refresh';
$gGlobalConfig['close_push'] = true;
//curl

$gGlobalConfig['ChinaCache']['apiurl'] = 'https://r.chinacache.com/content/refresh';
$gGlobalConfig['ChinaCache']['callback']['url'] = '';
//$gGlobalConfig['ChinaCache']['callback']['email'] = array('yuanzhigang@hoge.cn','donghuichun@hoge.cn'); //暂不启用
$gGlobalConfig['ChinaCache']['callback']['email'] = array();
$gGlobalConfig['ChinaCache']['callback']['acptNotice'] = true;//暂不使用

//$gGlobalConfig['cdn']['type'] = array('ChinaCache','Varnish'); //cdn 类型需要与类名同名
$gGlobalConfig['cdn']['type'] = array('ChinaCache','UpYun'); //cdn 类型需要与类名同名

$gGlobalConfig['cdn']['token_error'] = array('Invalid Access Token','Expired Access Token'); 

define('CDN_TYPE', 'ChinaCache');
define('DELETE_DATA', 100);



define('CDN_CACHE_FILE', 'preheat');//缓存刷新purge、文件缓存预热preheat

$gGlobalConfig['UpYun']['user_apiurl'] 		= 'https://api.upyun.com/accounts/';
$gGlobalConfig['UpYun']['client_id'] 		= '10295';
$gGlobalConfig['UpYun']['client_secret'] 	= '5abffc6b581eea01a73efc7901c91da78bf699fd';

define('OAUTH_CLIENT_ID', '10295');
define('OAUTH_CLIENT_SECRET', '5abffc6b581eea01a73efc7901c91da78bf699fd');

define('OAUTH_BASE_URI', 'https://api.upyun.com/');
define('OAUTH_AUTHORIZE_URI', 'https://api.upyun.com/oauth/authorize/');
define('OAUTH_ACCESS_TOKEN_URI', 'https://api.upyun.com/oauth/access_token/');
define('OAUTH_REDIRECT_URI', 'http://callbackurl/');

$gGlobalConfig['cdn']['space_type'] = array(	
	'cdn'	  => 'CDN空间',//CDN空间，功能类似普通CDN，创建之后需要调用配置接口设置回源域名和IP
	'file'    => '文件空间',//文件空间，可以存储所有格式的文件，但是不能使用缩略图等功能
	'image'	  => '图片空间',//图片空间，只能存储图片文件，可以使用缩略图等特色功能
); 
$gGlobalConfig['cdn']['ip_type'] = array(	
	'ip_tel'    => '回源电信线路IP',
	'ip_cnc'	=> '回源网通线路IP',
); 

?>