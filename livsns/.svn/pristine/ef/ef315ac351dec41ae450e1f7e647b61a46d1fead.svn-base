<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');

function encrypt_func($info)
{
	$num = strlen($info);
	if($num)
	{
		for($i=1; $i<=$num; $i++)
		{
			$allnum = $allnum + ord(substr($info, $num-$i, 1));
		}
		$allnum = $allnum * 2;
		for($i=1; $i<=$num; $i++)
		{
			$chg = $allnum % 26;
			$allnum = $allnum - ord(substr($info, $num-$i, 1));
			$pass = $pass . chr(ord("A")+$chg);
		}
		$other = 12 - $num;
		$allnum = $allnum * 3;
		for($i=1; $i<=$other; $i++)
		{
			$chg = $allnum % 26;
			$allnum = $allnum -$chg*3;
			$otherpass = $otherpass . chr(ord("A")+$chg);
		}
		return $otherpass.$pass;
	}
	else
	{
		return "";
	}
}
echo encrypt_func('0');
exit;
$recommend = @file_get_contents($recommend_list);
$recommend = unserialize($recommend);
if (!$recommend)
{
	$recommend = array();
}
$videos = array();
foreach ($recommend AS $video)
{
	$videos[] = array('video' => $video['video'], 'title' => $video['title'], 'name' => $video['title']);
}
$recommend = array(
	'list' => $videos,
);
output($recommend);
?>