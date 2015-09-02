<?php

header("Content-type:text/html;charset=utf-8");

$path = 'cache/error/error.log';
if(file_exists($path))
{
	$data = file_get_contents($path);
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}
else 
{
	echo 'error log file not exists';
}