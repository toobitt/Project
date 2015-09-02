<?php
define('ROOT_DIR', '../');
require(ROOT_DIR . 'global.php');

$recommend = @file_get_contents($recommend_list);
$recommend = unserialize($recommend);
if (!$recommend)
{
	$recommend = array();
}
$videos = array();
foreach ($recommend AS $video)
{
	$videos[] = $video;
}
$recommend = array(
	'list' => $videos,
);
output($recommend);
?>