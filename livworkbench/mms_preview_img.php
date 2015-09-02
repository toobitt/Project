<?php
define('WITHOUT_LOGIN', true);
require_once('./global.php');
//默认图片url
$url = RESOURCE_URL.'nopic.png';
if($_GET['url'] && ($handle = @fopen($_GET['url'], 'rb')))
{
	$header = unpack('C2ch',@fread($handle, 2));
	//13780代表PNG
	if($header['ch1'].$header['ch2'] == 13780)
	{
		$url = $_GET['url'] . '?' . time();
	}
}
exit($url);
$img = file_get_contents($url);
header("Content-type:image/png");
echo $img;
?>