<?php

/**
 * 根据视频id取视频内容；
 * */
define('M2O_ROOT_PATH', '../');
require(M2O_ROOT_PATH . 'global.php');
$appid  = intval($_REQUEST['appid']);
$appkey = ($_REQUEST['appkey']);
$site_id = intval($_REQUEST['site_id']);
$offset = intval($_REQUEST['offset']);
if (!$appid || !$appkey || !$site_id)
{
    echo 'NO_KEY';
    exit;
}
$gGlobalConfig['appid']  = $appid;
$gGlobalConfig['appkey'] = $appkey;
$curl                         = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('site_id', $site_id);
$curl->addRequestData('offset', $offset);
$curl->addRequestData('bundle_id', 'livmedia');
$curl->addRequestData('module_id', 'vod');
$curl->addRequestData('need_video', 1);
$curl->addRequestData('column_id', DOWNLOAD_VIDEO_COLUMNID);
$curl->addRequestData('a', 'get_content');
$ret                          = $curl->request('content.php');
if (is_array($ret))
{
    $r = array();
    foreach($ret as $k=>$v)
    {
        $row = array(
            'id' => $v['id'],
            'title' => $v['title'],
            'site_id' => $v['site_id'],
            'content_url' => $v['content_url'],
            'publish_time' => $v['publish_time'],
            'column_name' => $v['column_info']['name'],
            'video' => $v['video'],
        );
        $r[] = $row;
    }
    echo json_encode($r);
    exit;
}
echo "NO_DATA";
exit;

?>