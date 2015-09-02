<?php

/**
 * 根据内容id获取链接地址；
 * */
define('M2O_ROOT_PATH', './');
require(M2O_ROOT_PATH . 'global.php');
$id = intval($_REQUEST['id']);
if ($id)
{

    $curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
    $curl->setReturnFormat('json');
    $curl->initPostData();
    $curl->addRequestData('content_id', $id);
	$curl->addRequestData('a', 'get_content');
    $ret = $curl->request('content.php');
    $content = $ret[0];
    if($content['content_url'])
    {
        header('Location: '.$content['content_url']);
    }
    else
    {
        header('Location: http://'.$gGlobalConfig['v_site']['site_info']['url']);
    }

}
else
    {
        header('Location: http://'.$gGlobalConfig['v_site']['site_info']['url']);
    }
?>
