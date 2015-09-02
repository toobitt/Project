<?php

/**
 * callback
 * 
 * */
require('global.php');
require(ROOT_PATH.'lib/class/curl.class.php');
$curl = new curl($gGlobalConfig['App_share']['host'],$gGlobalConfig['App_share']['dir']); 
 
//获取第三平台登陆地址
$curl->setSubmitType('post');
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('html',true);
$curl->addRequestData('id',$_GET['id']);
$curl->addRequestData('plat',$_GET['plat']);
$curl->addRequestData('type',$_GET['type']);
$curl->addRequestData('appid',$gGlobalConfig['appid']);
$curl->addRequestData('appkey',$gGlobalConfig['appkey']);
$ret = $curl->request('oauthlogin.php');
session_start();
$_SESSION['id'] = $_GET['id'];
$_SESSION['plat'] = $_GET['plat'];
$_SESSION['refer_url'] = $_GET['refer_url'];
if($ret[0]['access_plat_token'])
{
	$_SESSION['access_plat_token'] = $ret[0]['access_plat_token'];
}


print_r($ret);exit;
?>
