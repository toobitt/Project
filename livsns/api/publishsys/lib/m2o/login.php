<?php
//error_reporting(0);
header('Content-Type:text/html; charset=utf-8');
session_start();
define('M2O_ROOT_PATH', './');
require (M2O_ROOT_PATH . 'global.php');
/**
 * 正式环境中需要注释该代码
 */

function isTelNumber($number) {
    return 0 < preg_match('/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/', $number);
}
function curl_post($ch,$url, $data) {
    
    if (!$data)
        return false;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $string = curl_exec($ch);
    
    return $string;
}


$type = $_REQUEST['login_type'];

if ($type == 'm2o') {
    if (!$_REQUEST['password']) {
        $data['state'] = 2;
        $data['msg'] = '没有输入密码';
        echo json_encode($data);
        return 0;
    }
    $member_name = trim(addslashes($_REQUEST['member_name']));
    $password = trim(addslashes($_REQUEST['password']));
    $params['type_name'] = 'm2o';
    $params['member_name'] = $member_name;
    $params['password'] = $password;
}


if ($type == 'sina_weibo') {
    header("location:".SSO_SINA_LOGIN);
    exit();
}

if($type=='qq_weibo'){
    
    header("location:".SSO_QQ_LOGIN);
    exit(); 
}

$reffer = addslashes($_SERVER['HTTP_REFERER']);
$params['reffer'] = $reffer;
$params['login_type'] = $type;
$params['a'] = 'login';
$params['r'] = 'login';
$url = SSO_M2O_LOGIN;
$ch = curl_init();
$ret = curl_post($ch, $url, $params);
curl_close($ch);

echo $ret;exit();
?>

