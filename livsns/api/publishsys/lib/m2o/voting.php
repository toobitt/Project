<?php

/**
 * 投票接口
 * @param $question_id int 投票ID
 * @param $single_total string 3,4,5
 * @param $verify_code string 验证码
 * @param $other_title string 用户提交过来的其他选项
 * 
 */
define('M2O_ROOT_PATH','./');
require(M2O_ROOT_PATH . 'global.php');
include(M2O_ROOT_PATH . 'lib/class/vote.class.php');
$obj = new vote();

//投票选项处理  投票接口需要索引下标的数组
$_REQUEST['single_total'] = is_array($_REQUEST['single_total']) ? $_REQUEST['single_total'] : explode(',', $_REQUEST['single_total']);
if (is_array($_REQUEST['single_total']) && count($_REQUEST['single_total']) > 0 && $_REQUEST['single_total'][0]) {
    $tmp_array = array();
    foreach ($_REQUEST['single_total'] as $k => $v) {
        $tmp_array[$v] = $v;
    }
    $_REQUEST['single_total'] = $tmp_array;
}
else {
     $_REQUEST['single_total'] = array();
}

$ret = $obj->submitVote();
$ret = is_array($ret) ? $ret[0] : array();

//echo json_encode($ret);exit;

if($ret) {
    $message = '投票成功';
    $success = 1;
}
else {
    $message = '投票失败';
    $success = 0;
}

$refer_url = $_REQUEST['refer_url'] ? $_REQUEST['refer_url'] : $_SERVER['HTTP_REFERER'];
$preg = array('<', '>');
$replace = array('&lt;', '&gt;');
$refer_url = str_replace($preg, $replace, $refer_url);
// $refer_url = htmlentities($refer_url);
if (intval(strpos($refer_url, '?')) != 0) {
    $refer_url .= '&success=' . $success;
} else {
    $refer_url .= '?success=' . $success;
}
$blalert = $_REQUEST['blalert'];
$str = "<script>";
$str .= $blalert ? "alert('".$message."');" : '';
echo $str . "window.location.href='".$refer_url."';</script>";

?>