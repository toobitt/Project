<?php

/**
 * callback
 * 
 * */
require('global.php');
session_start();
$_SESSION['refer_url'] = urldecode($_GET['refer_url']);
$_SESSION['access_plat_token'] = $_GET['access_plat_token'];
header('Location:'.urldecode($_GET['oauth_url']));

?>
