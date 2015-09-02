<?php

/**
 * 发爆料
 * */
header('Content-Type:text/html; charset=utf-8');
define('M2O_ROOT_PATH', './');
require(M2O_ROOT_PATH . 'global.php');
$curl = new curl($gGlobalConfig['App_contribute']['host'], $gGlobalConfig['App_contribute']['dir']);
$curl->setSubmitType('post');
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('content', str_replace("\n",'<br>',$_INPUT['content']));
$curl->addRequestData('title', $_INPUT['title']);
$curl->addRequestData('brief', $_INPUT['brief']);
$curl->addRequestData('user_id', $_INPUT['user_id']);
$curl->addRequestData('user_name', $_INPUT['user_name']);
$curl->addRequestData('create_time', time());
$curl->addRequestData('longitude', $_INPUT['longitude']);
$curl->addRequestData('latitude', $_INPUT['latitude']);
$curl->addRequestData('sort_id', $_INPUT['sort_id']);
$curl->addRequestData('tel', $_INPUT['tel']);
$curl->addRequestData('email', $_INPUT['email']);
$curl->addRequestData('addr', $_INPUT['addr']);
if (is_array($_FILES) && count($_FILES) > 0 )
{
    $curl->addFile($_FILES);
}
$curl->addRequestData('a', 'create');
$ret  = $curl->request('contribute_update.php');
echo "<script>alert('提交成功');window.location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
?>
