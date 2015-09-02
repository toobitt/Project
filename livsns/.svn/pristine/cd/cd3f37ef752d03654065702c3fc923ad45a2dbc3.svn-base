<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 6831 2012-05-29 00:55:16Z repheal $
***************************************************************************/
define('UPLOAD_ABSOLUTE_URL', 'http://img.dev.hogesoft.com:83/material/share/img/');
define('UPLOAD_THUMB_URL','http://img.dev.hogesoft.com:83/material/share/img/100x100/');//缩略图URL
$gDBconfig = array(
	'host'     => 'db.dev.hogesoft.com',
	'user'     => 'root',
	'pass'     => 'hogesoft',
	'database' => 'dev_share',
	'charset'  => 'utf8',
	'pconncet' => 0,
);

define('DB_PREFIX','liv_');//定义数据库表前缀
define('APP_UNIQUEID','share');

$gGlobalConfig['image_cache'] = 'cache';

$gGlobalConfig['cookie_dir'] = CUR_CONF_PATH.'cache/cookie/';


$gGlobalConfig['status'] = array(
	'1'=>'启用',
	'2'=>'未启用',
	);
	
$gGlobalConfig['sync_third_url'] = 'http://mcp.dev.hogesoft.com:233/access_plat/index.php';

$gGlobalConfig['share_plat'] = array(
	'1'=>array(
		'name'=>'sinaweibo',
		'name_ch'=>'新浪微博',
		'picurl'=>'',
		'oauthurl'=>'https://api.weibo.com/oauth2/authorize',
		'shareurl'=>'https://api.weibo.com/2/statuses/update',
		'sharepicurl'=>'https://upload.api.weibo.com/2/statuses/upload',
		'sharepicurl_advance'=>'https://api.weibo.com/2/statuses/upload_url_text',
		'callback'=>'http://localhost.com', 
		'accessurl'=>'https://api.weibo.com/oauth2/access_token', 
		'followurl'=>'https://api.weibo.com/2/friendships/create', 
		'del_followurl'=>'https://api.weibo.com/2/friendships/destroy', 
		'userurl'=>'https://api.weibo.com/2/users/show',
		'other_userurl'=>'https://api.weibo.com/2/users/show',
		'user_timelineurl'=>'https://api.weibo.com/2/statuses/user_timeline',
		'home_timelineurl'=>'https://api.weibo.com/2/statuses/home_timeline',
		'search_userurl'=>'https://api.weibo.com/2/search/suggestions/users',
		'reposturl'=>'https://api.weibo.com/2/statuses/repost',
		'commentsurl'=>'https://api.weibo.com/2/comments/create',
		'comments_commenturl'=>'https://api.weibo.com/2/comments/reply',
		'detailurl'=>'https://api.weibo.com/2/statuses/show',
		'comment_showurl'=>'https://api.weibo.com/2/comments/show',
		'user_mentionurl'=>'https://api.weibo.com/2/statuses/mentions',
		'favorite_addurl'=>'https://api.weibo.com/2/favorites/create',
		'revoke_authurl'=>'https://api.weibo.com/2/account/end_session',
		'topicurl' => 'https://api.weibo.com/2/trends/statuses',
	),
	'2'=>array(
		'name'=>'renren',
		'name_ch'=>'人人网',
		'picurl'=>'',
		'oauthurl'=>'https://graph.renren.com/oauth/authorize',
		'shareurl'=>'http://api.renren.com/restserver.do',
		'callback'=>'http://localhost.com', 
		'accessurl'=>'https://graph.renren.com/oauth/token', 
		'followurl'=>'http://api.renren.com/restserver.do', 
		'userurl'=>'http://api.renren.com/restserver.do',
		'other_userurl'=>'http://api.renren.com/restserver.do',
		'user_timelineurl'=>'http://api.renren.com/restserver.do',
		'user_timeline'=>'',
		'search_userurl'=>'',
		'reposturl'=>'',
		'commentsurl'=>'',
	),
	'3'=>array(
		'name'=>'txweibo',
		'name_ch'=>'腾讯微博',
		'picurl'=>'',
		'oauthurl'=>'https://open.t.qq.com/cgi-bin/oauth2/authorize',
		'shareurl'=>'https://open.t.qq.com/api/t/add',
		'sharepicurl'=>'https://open.t.qq.com/api/t/add_pic_url',
		'callback'=>'http://localhost.com',
		'accessurl'=>'https://open.t.qq.com/cgi-bin/oauth2/access_token',
		'followurl'=>'http://open.t.qq.com/api/friends/add', 
		'del_followurl'=>'http://open.t.qq.com/api/friends/del',
		'userurl'=>'http://open.t.qq.com/api/user/info', 
		'other_userurl'=>'http://open.t.qq.com/api/user/other_info',
		'user_timelineurl'=>'http://open.t.qq.com/api/statuses/user_timeline',
		'search_userurl'=>'http://open.t.qq.com/api/search/user',
		'reposturl'=>'https://open.t.qq.com/api/t/re_add',
		'commentsurl'=>'https://open.t.qq.com/api/t/reply',
		'detailurl'=>'https://open.t.qq.com/api/t/show',
		'comment_showurl'=>'https://open.t.qq.com/api/t/re_list',
		'user_mentionurl'=>'http://open.t.qq.com/api/statuses/mentions_timeline',
		'favorite_addurl'=>' http://open.t.qq.com/api/fav/addt',
		'revoke_authurl'=>'http://open.t.qq.com/api/auth/revoke_auth',
		'topicurl' => 'http://open.t.qq.com/api/statuses/ht_timeline',
	),
	'4'=>array(
		'name'=>'douban',
		'name_ch'=>'豆瓣网',
		'picurl'=>'',
		'oauthurl'=>'https://www.douban.com/service/auth2/auth',
		'shareurl'=>'https://open.t.qq.com/api/t/add',
		'callback'=>'http://localhost.com',
		'accessurl'=>'https://www.douban.com/service/auth2/token',
		'followurl'=>'https://api.tx.com/2/friendships/create', 
		'userurl'=>'', 
		'other_userurl'=>'',
		'user_timelineurl'=>'',
		'search_userurl'=>'',
		'reposturl'=>'',
		'commentsurl'=>'',
	),
	'5'=>array(
		'name'=>'wangyi',
		'name_ch'=>'网易微博',
		'picurl'=>'',
		'oauthurl'=>'https://api.t.163.com/oauth2/authorize',
		'shareurl'=>'https://open.t.qq.com/api/t/add',
		'callback'=>'http://localhost.com',
		'accessurl'=>'https://api.t.163.com/oauth2/access_token',
		'followurl'=>'https://api.tx.com/2/friendships/create',  
		'userurl'=>'',
		'other_userurl'=>'',
		'user_timelineurl'=>'',
		'search_userurl'=>'',
		'reposturl'=>'',
		'commentsurl'=>'',
	),
	'6'=>array(
		'name'=>'txqq',
		'name_ch'=>'腾讯qq',
		'picurl'=>'',
		'oauthurl'=>'https://graph.qq.com/oauth2.0/authorize',
		'accessurl'=>'https://graph.qq.com/oauth2.0/token',
		'userurl'=>'https://graph.qq.com/user/get_user_info',
		'openidurl'=>'https://graph.qq.com/oauth2.0/me',
		'revoke_authurl'=>'http://open.t.qq.com/api/auth/revoke_auth',
	),
        '7'=>array(
            'name'=>'dz',
		'name_ch'=>'discuz论坛',
		'picurl'=>'',
		'oauthurl'=>'http://10.0.1.40/livworkbench/access_plat/dz.php',
		'shareurl'=>'http://10.0.2.89/api/auth.php',
		'sharepicurl'=>'https://upload.api.weibo.com/2/statuses/upload',
		'sharepicurl_advance'=>'https://api.weibo.com/2/statuses/upload_url_text',
		'callback'=>'http://localhost.com', 
		//'accessurl'=>'http://10.0.2.59/api/auth.php', 
		'accessurl'=>'http://10.0.2.89/api/auth.php', 
		'followurl'=>'https://api.weibo.com/2/friendships/create', 
		'del_followurl'=>'https://api.weibo.com/2/friendships/destroy', 
		'userurl'=>'https://api.weibo.com/2/users/show',
		'other_userurl'=>'https://api.weibo.com/2/users/show',
		'user_timelineurl'=>'https://api.weibo.com/2/statuses/user_timeline',
		'search_userurl'=>'https://api.weibo.com/2/search/suggestions/users',
		'reposturl'=>'https://api.weibo.com/2/statuses/repost',
		'commentsurl'=>'https://api.weibo.com/2/comments/create',
		'comments_commenturl'=>'https://api.weibo.com/2/comments/reply',
		'detailurl'=>'https://api.weibo.com/2/statuses/show',
		'comment_showurl'=>'https://api.weibo.com/2/comments/show',
		'user_mentionurl'=>'https://api.weibo.com/2/statuses/mentions',
		'favorite_addurl'=>'https://api.weibo.com/2/favorites/create',
		'revoke_authurl'=>'https://api.weibo.com/2/account/end_session',
		'topicurl' => 'https://api.weibo.com/2/trends/statuses',
        ),
	'127'=>array(
		'name'=>'other',
		'name_ch'=>'其他',
		'para'=>array(
			'0' => array('name'=>'oauthurl','param'=>'oauthurl'),
			'1' => array('name'=>'accessurl','param'=>'accessurl'),
			'2' => array('name'=>'用户信息url','param'=>'userurl'),
			'3' => array('name'=>'取消授权url','param'=>'revoke_authurl'),
		),
	),
);


$gGlobalConfig['operation_type'] = array(
	1 => '发布',
	2 => '转发',
	3 => '评论',
	4 => '关注',
);


define('INITED_APP', true);
?>