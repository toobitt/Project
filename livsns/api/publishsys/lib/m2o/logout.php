<?php
session_start();
error_reporting(0);
header('Content-Type:text/html; charset=utf-8');
define('M2O_ROOT_PATH', './');
require (M2O_ROOT_PATH . 'global.php');
unset($_SESSION['user']);
session_destroy();
$data['msg'] = '成功退出';
$data['state'] = 1;
echo json_encode($data);
?>

