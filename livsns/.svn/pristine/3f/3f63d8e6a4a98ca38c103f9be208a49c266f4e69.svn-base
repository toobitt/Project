<?php
header('Content-Type: text/xml; charset=UTF-8');
define('M2O_ROOT_PATH', '../');
@include_once(M2O_ROOT_PATH . 'conf/config.php');
echo $content = file_get_contents('http://' . $gGlobalConfig['App_player']['host'] . '/' . $gGlobalConfig['App_player']['dir'] . 'vod/video.php?extend=' . addslashes($_REQUEST['extend']) . '&id=' . addslashes($_REQUEST['id']) . '&url=' . addslashes(urlencode($_REQUEST['url'])));
?>