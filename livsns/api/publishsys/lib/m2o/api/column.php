<?php

/**
 * 根据视频id取视频内容；
 * */
define('M2O_ROOT_PATH', '../');
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
if(!$site_id)
{
    echo 'NO_SITEID';
    exit;
}
$gGlobalConfig['appid']  = $appid;
$gGlobalConfig['appkey'] = $appkey;
$curl                         = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('site_id', $site_id);
if(isset($_REQUEST['fid']))
$curl->addRequestData('fid', $fid);
$curl->addRequestData('count', '10000');
$ret                          = $curl->request('column.php');
if (is_array($ret))
{
    $r = array();
    foreach($ret as $k=>$v)
    {
        $row = array(
            'id' => $v['id'],
            'title' => $v['name'],
            'site_id' => $v['site_id'],
            'fid' => $v['fid'],
        );
        $r[] = $row;
    }
    echo json_encode($r);
    exit;
}
echo "NO_DATA";
exit;

?>