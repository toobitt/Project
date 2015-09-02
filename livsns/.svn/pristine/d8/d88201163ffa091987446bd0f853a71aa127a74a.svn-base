<?php
function hg_num2dir($num)
{
	$dir = number_format($num);
	$dir = explode(',', $dir);
	$dir[0] = str_pad($dir[0], 3, '0', STR_PAD_LEFT);
	$dir = implode('/', $dir) . '/';
	return $dir;
}
function output($data)
{
	$debug = $_REQUEST['debug'];
	if (!$debug)
	{
		echo json_encode($data);
	}
	else
	{
			echo '<pre>';
			print_r($data);
			echo '</pre>';
	}
	exit;
}


$video_id = $_REQUEST['hash'];
if (!$video_id)
{
	$error = array(
		'error' => true,	
		'msg' => '未指定视频流',	
	);
	output($error);
}
if ($video_id > 50)
{
	$vprefix = hg_num2dir($video_id);
}
$adprefix = $_REQUEST['adpre'];
$imgprefix = $_REQUEST['imgpre'];
$data = json_decode(file_get_contents('config.php'),true);
if(is_array($data['preview']))
{
	foreach($data['preview'] as $key => $value)
	{
		$data['preview'][$key]['url'] = $vprefix . $video_id.'.ssm/preview.jpg';
	}
}
unset($data['program']);
$data['program'][] = array('id'=>$video_id,'url'=> $vprefix . $video_id . '.ssm/manifest.f4m');
output($data);
?>
