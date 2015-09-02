<?php
error_reporting(0);
header('Content-Type:text/html; charset=utf-8');
define('M2O_ROOT_PATH', './');
require (M2O_ROOT_PATH . 'global.php');
/**

 * 正式环境中需要注释该代码
 */
function curl_post($ch, $url, $data) {

    if (!$data)
        return false;

    //$params = "value=" . json_encode($data);
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

function isTelNumber($number) {
    return 0 < preg_match('/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/', $number);
}

if (!$_REQUEST) {
    $data['msg'] = '非法操作';
    $data['state'] = 2;
    echo json_encode($data);
    exit();
}

if (!$_REQUEST['reg_type']) {
    $data['state'] = 2;
    $data['msg'] = '没有设置注册类型';
    echo json_encode($data);
    return 0;
}

if (!$_REQUEST['member_name']) {
    $data['state'] = 2;
    $data['msg'] = '用户没有填写';
    echo json_encode($data);
    return 0;
}
$member_name = trim(addslashes($_REQUEST['member_name']));

$type = trim($_REQUEST['reg_type']);

if ($type == 'm2o') {
    if (isTelNumber($member_name)) {
        $re = array('state' => 2, 'msg' => '用户名必须含有非数字字符');
        echo json_encode($re);
        return 0;
    }

    $params['member_name'] = $member_name;
    if (@!$_REQUEST['password']) {
        $re = array('state' => 2, 'msg' => '没有填写密码');
         echo json_encode($re);
        return 0;
    }
    if (@!$_REQUEST['s_password']) {
        $re = array('state' => 2, 'msg' => '确认密码没有填写');
        echo json_encode($re);
        return 0;
    }
    if ($_REQUEST['s_password'] != $_REQUEST['password']) {
        $re = array('state' => 2, 'msg' => '两次密码不一致');
        echo json_encode($re);
        return 0;
    }
    $params['password'] = trim($_REQUEST['password']);
    $params['s_password']= trim($_REQUEST['s_password']);
    if (@!$_REQUEST['email']) {
        $re = array('state' => 2, 'msg' => '必须填写email.');
        echo json_encode($re);
        return 0;

    }
    $params['email'] = trim($_REQUEST['email']);
}

if ($type == 'uc') {

}
$reffer = addslashes($_SERVER['HTTP_REFERER']);

$params['reffer'] = $reffer;
$params['reg_type'] = $type;
$params['a'] = 'register';
$params['r'] = 'register';
$url = SSO_M2O_REGISTER;
$ch = curl_init();
$ret = curl_post($ch, $url, $params);
curl_close($ch);
echo $ret;exit();

?>

