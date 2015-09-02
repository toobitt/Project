<?php

/**
 * 根据视频id取视频内容；
 * */
define('M2O_ROOT_PATH', './');
require(M2O_ROOT_PATH . 'global.php');
$appid  = intval($_REQUEST['appid']);
$appkey = ($_REQUEST['appkey']);
$fid = intval($_REQUEST['fid']);
$site_id = intval($_REQUEST['site_id']);
if (!$appid || !$appkey)
{
    echo 'NO_KEY';
    exit;
}
$this->globalConfig['appid']  = $appid;
$this->globalConfig['appkey'] = $appkey;

$curl     = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('site_id', $site_id);
$curl->addRequestData('fid', $fid);
$ret      = $curl->request('column.php');
if(is_array($ret))
{
    echo json_encode($ret);
    exit;
}
echo "NO_DATA";
exit;

?>