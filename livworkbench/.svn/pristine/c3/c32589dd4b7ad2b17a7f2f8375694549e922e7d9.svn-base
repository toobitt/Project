<?php
include('global.php');
import('v');
import('page');
$v = new video();
$vdata = $v->getM2oVideo();
$count = $v->getM2oVideoTotal();
$page       = new Page($count);
//$vsort_data = $v->getVideoSort();
$settings = $_HOGE['input']['settings'] ? json_decode(stripslashes($_HOGE['input']['settings']),1):$_configs['callback_map'];
include('tpl/video.tpl.php');
