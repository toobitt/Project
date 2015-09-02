<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: config.php 17931 2013-02-26 01:34:49Z lijiaying $
***************************************************************************/

$gDBconfig = array('host' => 'db.dev.hogesoft.com',
'user' => 'root',
'pass' => 'hogesoft',
'database' => 'dev_payment',
'charset' => 'utf8',
'pconncet' => '0',
);

define('APP_UNIQUEID','payment');//应用标识

define('DB_PREFIX','liv_');//定义数据库表前缀

$gGlobalConfig['payment_config'] = array(
	'alipay' => array(
		'partner' => '2088101011913539',//合作身份者id，以2088开头的16位纯数字
		'key' => '',//安全检验码，以数字和字母组成的32位字符
		'sign_type' => strtoupper('MD5'),//签名方式 不需修改
		'input_charset' => strtolower('utf-8'),//字符编码格式 目前支持 gbk 或 utf-8
		'cacert' => getcwd().'/cacert.pem',//请保证cacert.pem文件在当前文件夹目录中----ca证书路径地址，用于curl中ssl校验
		'transport' => 'http',//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
	),
);

$gGlobalConfig['payment_key'] = array(
	'alipay' => array(
		'key' => 'service,partner,payment_type,notify_url,return_url,seller_email,out_trade_no,subject,total_fee,body,show_url,anti_phishing_key,exter_invoke_ip,_input_charset',
		'partner' => trim($gGlobalConfig['payment_config']['alipay']['partner']),
		'notify_url' => 'http://localhost/livsns/demo/index.php?pay_type=alipay&a=notify',//通知url
		'return_url' => 'http://localhost/livsns/demo/index.php?pay_type=alipay&a=return',//返回url
		'service' => 'create_direct_pay_by_user',
		'anti_phishing_key' => TIMENOW,
		'payment_type' => 1,
		'exter_invoke_ip' => hg_getip(),
		'_input_charset' => trim(strtolower($gGlobalConfig['payment_config']['alipay']['input_charset'])),
	),
);

?>