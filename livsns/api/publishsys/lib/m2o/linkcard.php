<?php
define('M2O_ROOT_PATH','./');
require(M2O_ROOT_PATH . 'global.php');
$url = urldecode($_REQUEST['url']);
$pathinfo = pathinfo($url);
$output = array();
if(!$pathinfo)
{
	exit();
}
$contend_id = intval($pathinfo['filename']);

$curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('id',$contend_id);
$curl->addRequestData('a','get_content_by_rid');
$ret = $curl->request('content.php');

if(!is_array($ret[0]))
{
	exit();
}
$ret = $ret[0];
//print_r($ret['indexpic']);exit;
switch($ret['bundle_id'])
{
	case 'livmedia' : 
		{
			$object_type = $ret['video']['is_audio'] ? 'audio':'video';
			break;
		}
	case 'article' : 
		{
			$object_type = 'article';
			break;
		}
	default:
		{
			$object_type = 'webpage';
			break;
		}
}

$output = array(
'display_name'=>$ret['title'],
'author'=>array(
		"display_name"=>$ret['author'],
		"object_type"=> "person",
		"url"=>'',
	),
'summary'=>$ret['brief'],
'url'=>$url,
'links'=>array(
		"scheme"=>"",//暂不支持
		"url"=>"",//介入方h5中间播放
		"display_name"=>"",//暂不支持
	),
'tags'=>array(
	"display_name"=>$ret['keywords'],
	),
'create_at'=>date('Y-m-d', $ret['create_time_stamp']),
'object_type'=>$object_type,
);
//视频
if($ret['video'] && $object_type=='video')
{
	$output['stream']=array(
		"url"=>$ret['video']['host'] . '/' . $ret['video']['dir'] . $ret['video']['filepath'] . $ret['video']['filename'],
		"duration"=>$ret['duration'] ? intval($ret['duration']/1000) : 0,
	);
}

//图文
if($ret['indexpic'])
{
	$output['image']=array(
		"url"=>$ret['indexpic']['host'] . $ret['indexpic']['dir'] . '120x120/' .  $ret['indexpic']['filepath'] . $ret['indexpic']['filename'], 
		"width"=> 120,
		"height"=>120,
	);
	$output['full_image']=array(
		"url"=>$ret['indexpic']['host'] . $ret['indexpic']['dir'] .  $ret['indexpic']['filepath'] . $ret['indexpic']['filename'], 
		"width"=> intval($ret['indexpic']['imgwidth']),
		"height"=>intval($ret['indexpic']['imgheight']),
	);
}
echo json_encode($output);
exit();
?>