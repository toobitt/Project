<?php

/**
 * 根据视频id取视频内容；
 * */
define('M2O_ROOT_PATH','./');
require(M2O_ROOT_PATH . 'global.php');
$id = intval($_REQUEST['id']);
$_REQUEST['nodebugcell'] = 1;
if (!$id)
{
	exit;
}
$appunid = addslashes($_REQUEST['app_uniqueid']);
$modunid = addslashes($_REQUEST['module_uniqueid']);
$title = addslashes($_REQUEST['title']);
$column_id= intval($_REQUEST['column_id']);
$reffer = addslashes($_SERVER['HTTP_REFERER']);
$rec = intval($_REQUEST['rec']);//是否记录点击
$curl = new curl($gGlobalConfig['App_access']['host'], $gGlobalConfig['App_access']['dir']);
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('id',$id);
$curl->addRequestData('column_id',$column_id);
$curl->addRequestData('app_uniqueid',$appunid);
$curl->addRequestData('mod_uniqueid',$modunid);
$curl->addRequestData('title',$title);
$curl->addRequestData('reffer',$reffer);
$curl->addRequestData('rec',$rec);
$ret = $curl->request('stats.php');
?>
document.write('<span class="click_nums"><?php echo $ret[0]; ?></span>');
