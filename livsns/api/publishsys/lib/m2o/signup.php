<?php

/**
 * 活动报名
 * */
header('Content-Type:text/html; charset=utf-8');
define('M2O_ROOT_PATH','./');
require(M2O_ROOT_PATH . 'global.php');
require_once(M2O_ROOT_PATH . 'lib/class/http.class.php');

function show_message($message) {
    if ($_SERVER['HTTP_REFERER']) {
        echo "<script>alert('".$message."');window.location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
    }
    else {
        echo "<script>alert('".$message."')</script>";
    }
    exit();
}

$data = array();
if (!$_REQUEST['activityid'] || !$_REQUEST['type'] || !$_REQUEST['type_name']) {
    show_message('没有选择活动');
}

if (!$_REQUEST['nick_name']) {
     show_message('请填写姓名');
}

$data = array(
    'type' => $_REQUEST['type'],
    'type_name' => $_REQUEST['type_name'],
    'platform_id' => uniqid() . hg_rand_num(),
    'nick_name' => $_REQUEST['nick_name'],
    'activityid' => $_REQUEST['activityid'],
    'member_info' => $_REQUEST['member_info'],
    'a'           => 'login',
    'appid'       => APPID,
    'appkey'      => APPKEY,  
);

$url = $gGlobalConfig['App_members']['protocol'] . $gGlobalConfig['App_members']['host']  . '/'. $gGlobalConfig['App_members']['dir'] . 'login.php';
$http = new Http();
$ret = $http->post($url, $data, false);
//print_r($ret);
if (empty($ret) || $ret['ErrorCode']) {
    show_message($ret['ErrorText']);
}
show_message('提交成功');
?>
