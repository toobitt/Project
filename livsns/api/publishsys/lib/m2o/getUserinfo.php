<?php
error_reporting(0);
header('Content-Type:text/html; charset=utf-8');
session_start();
define('M2O_ROOT_PATH', './');
require (M2O_ROOT_PATH . 'global.php');
setcookie('access_token',$_REQUEST['access_token'], time()+3600*24, '/');
/**
 * 正式环境中需要注释该代码
 */
if(!$_REQUEST['access_token'])
{
    exit(json_encode(array('status'=>2,'message'=>'no_access_token')));
} 
function curl_post($ch,$url, $data) {
    if (!$data)
        return false;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $string = curl_exec($ch);
    return $string;
}
$reffer = addslashes($_SERVER['HTTP_REFERER']);
$params['reffer'] = $reffer;
$params['access_token'] = $_REQUEST['access_token'];
$params['a'] = 'detail';
$url = $gGlobalConfig['App_members']['protocol'].
       $gGlobalConfig['App_members']['host']."/".
       $gGlobalConfig['App_members']['dir'].'/member.php';
$ch = curl_init();
$ret = curl_post($ch, $url, $params);
$ret = json_decode($ret,1);
curl_close($ch);
if(!$ret[0]['member_id'])
{
    exit(json_encode(array('status'=>2,'message'=>'no_user_info')));
}
//setcookie放到头部 放到此处会导致用户退出登录后cookie没有删除  20140823
//setcookie('access_token',$_REQUEST['access_token'], time()+3600*24, '/');
echo json_encode($ret[0]);exit();
?>

