<?php
include('global.php');
import('v');
$v = new video();
$cb = $_HOGE['input']['cb'] ? $_HOGE['input']['cb'] : CALLBACK;
$settings = $_HOGE['input']['settings'];
if($settings)
{
	$parse_settings = explode(',', $settings);
	if($parse_settings)
	{
		$settings = array();
		for($i = 0; $i <= count($parse_settings); $i = $i+2)
		{
			if($parse_settings[$i] && $parse_settings[$i+1])
			{
				$settings[$parse_settings[$i]] = $parse_settings[$i+1];
			}
		}
	}
	else
	{
		$settings = $_configs['callback_map'];
	}
} 
else
{
	$settings = $_configs['callback_map'];
}
if($_FILES)
{
	echo json_encode($v->create());
	exit;
}
else
{
	//$vdata = $v->getM2oVideo();
	//$count = $v->getM2oVideoTotal();
	//$page       = new Page($count);
	$vsort_data = $v->getVideoSort();
}
include('tpl/index.tpl.php');

