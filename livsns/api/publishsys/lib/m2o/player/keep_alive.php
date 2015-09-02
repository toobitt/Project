<?php
header('Content-Type: text/xml; charset=UTF-8');
define('M2O_ROOT_PATH', '../');
set_time_limit(5);
@include_once(M2O_ROOT_PATH . 'conf/config.php');
echo file_get_contents('http://' . $gGlobalConfig['App_player']['host'] . '/' . $gGlobalConfig['App_player']['dir'] . 'live/keep_alive.php?id=' . addslashes($_REQUEST['id']) . '&time=' . addslashes($_REQUEST['time']));
?>