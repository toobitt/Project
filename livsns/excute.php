<?php
$api = SCRIPT_NAME;
$$api = new $api();  

$func = $_INPUT['a'];
if (!method_exists($$api, $func))
{
	//默认调用方法
	$func = 'show';	
}
$$api->$func();
?>