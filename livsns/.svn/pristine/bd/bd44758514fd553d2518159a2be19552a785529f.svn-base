<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(CUR_CONF_PATH . 'global.php');

$isSelfWeb = hg_isSourceDomain($_INPUT['url']);
if (!$isSelfWeb)
{
	exit;
}
$video_id = $_INPUT['id'];
if (!$video_id)
{
	$error = array(
		'error' => true,	
		'msg' => '未指定视频流',	
	);
	output($error);
}
//如果传入的id形式为 id, 1则传入的为实际视频id
$parse_video_id = explode(',', $video_id);
$video_id = $parse_video_id[0];
if(!$parse_video_id[1])
{
	//此处的video_id并非视频id 数据库中记录的id
	$video_info = get_video_info($video_id);
	$video_info['column_id'] = is_array($video_info['column_id']) ? $video_info['column_id'] : @unserialize($video_info['column_id']);
	$video_id= $video_info['id'];
}
if (!$video_info)
{
	$error = array(
		'error' => true,	
		'msg' => '未指定视频流',	
	);
	output($error);
}
//检测缓存
$extend_para = hg_parse_para();
$cache_outputxml_dir = hg_num2dir($video_id) . $video_id . '/';
//参数md5作为文件名
$cache_outputxml_filename = md5($extend_para['cache_outputxml_dir']);
hg_check_outputxml($cache_outputxml_dir.$cache_outputxml_filename);
$extend_para = $extend_para['extend_para'];//注意此处覆盖了原有变量
//广告过滤条件收集开始
$ad_filter_para = array();
$ad_filter_para['colid'] = $extend_para['colid'] ? $extend_para['colid'] : '';
if(!$ad_filter_para['colid'] && $video_info['column_id'])
{
	$ad_filter_para['colid'] = key($video_info['column_id']);
}
//广告过滤条件收集结束	
if ($_GET['nullad'])
{
	$nuallad = $_GET['nullad'];
}
else
{
	$nuallad = $extend_para['nullad'];
}
else
{
	$nuallad = 0;
}
$data = array();
if (!$nuallad)
{
	$ad = get_adv_data(VOD_AD_GROUP, $video_info, $ad_filter_para);
	if($ad)
	{
		$data['ad'] = $ad;
	}
}
/*将数据以指定的xml格式输出*/
function output_xml($array)
{
	global $gConfigs;
	$dom = new DOMDocument('1.0', 'utf-8');
	$video = $dom->createElement('video');
	$video->setAttribute('title', $array['video'][0]['title']);
	$video->setAttribute('list','');
	
	/*视频预览部分开始*/
	$previews = $dom->createElement('previews');
	$previewimg = $array['img_info']['host'].$array['img_info']['dir'];
	if (!$previewimg)
	{
		$previewimg = $gConfigs['default_preview_img'];
	}
	$previews->setAttribute('baseUrl',$previewimg);
	
	foreach($array['preview'] as $v)
	{
		$item = $dom->createElement('item');
		$item->setAttribute('url',$v['url']);
		$item->setAttribute('duration','1000');
		$previews->appendChild($item);
	}
	$video->appendChild($previews);
	/*视频预览部分结束*/
	if ($array['video'][0]['is_audio'])
	{
		$mask = $dom->createElement('mask',ANTILEECH);
		$mask->setAttribute('url',$previewimg . $v['url']);
		$video->appendChild($mask);
	}
	/*视频第一帧的图片开始*/
	$first_frame = $dom->createElement('firstFrame');
	$first_frame->setAttribute('url',$array['first_frame'][0]);
	$video->appendChild($first_frame);
	/*视频第一帧的图片结束*/

	/*视频部分开始*/
	$videos = $dom->createElement('videos');
	$videos->setAttribute('baseUrl', '');
	
	//格式
	$_f4m = defined('MAINFEST_F4M')?MAINFEST_F4M:'manifest.f4m';
	
	foreach($array['video'] as $v)
	{
		$item2 = $dom->createElement('item');
		foreach($v as $k =>$vv)
		{
			if($k == 'url' || $k == 'title' || $k == 'id')
			{
				continue;
			}
			$item2->setAttribute($k,$vv);
		}
		
		foreach ($v['url'] AS $_k => $_v)
		{
			$video2 = $dom->createElement('video');
			if($_v)
			{
				if (substr($_v, -1, 1) == '/')
				{
					$_v .= $_f4m;
				}
				$video2->setAttribute('url',$_v);
			}
			$item2->appendChild($video2);
		}
		
		/*
		for($i = 0;$i<4;$i++)
		{
			
			if($v['url'] && $i == 3)
			{
				$video2->setAttribute('url',$v['url']);
			}
			
			$item2->appendChild($video2);
		}
		*/
		$videos->appendChild($item2);
	}
	$video->appendChild($videos);
	/*视频部分结束*/
	
	
	/*播放器四角的logo开始*/

	$logos = $dom->createElement('logos');
	if($array['ad']['logo'])
	{
		$player_logo = $array['ad']['logo'];
		for($i = 1; $i <=4; $i++)
		{
			$v = array();
			foreach($player_logo as $j =>$jj)
			{
				if($jj['position'] == $i)
				{
					$v = $player_logo[$j];
					break;
				}
			}
			
			$item3 = $dom->createElement('item');
			if($v)
			{
				foreach($v as $k =>$vv)
				{
					if($k == 'id' || $k == 'position')
					{
						continue;
					}
					$item3->setAttribute($k,$vv);
				}
				
			}
			
			$logos->appendChild($item3);
		}
	}
	else 
	{
		for($i = 0;$i<4;$i++)
		{
			$item3 = $dom->createElement('item');
			$logos->appendChild($item3);
		}
	}
	
	$video->appendChild($logos);

	/*播放器四角的logo结束*/
	
	/*广告部分开始*/
	if($array['ad'])
	{
		$adverts = $dom->createElement('adverts');
		
		/*播放器底部的广告开始*/
		if($array['ad']['bottom'])
		{
			foreach($array['ad']['bottom'] as $v)
			{
				$bottom = $dom->createElement('bottom');
				foreach($v as $k =>$vv)
				{
					$bottom->setAttribute($k,$vv);
				}
				$adverts->appendChild($bottom);
			}
		}
		/*播放器底部的广告结束*/
		
		/*播放之前广告开始*/
		if($array['ad']['before'])
		{	
			foreach($array['ad']['before'] as $v)
			{
				$before = $dom->createElement('before');
				foreach($v as $k =>$vv)
				{
					$before->setAttribute($k,$vv);
				}
				$adverts->appendChild($before);
			}
		}
		/*播放之前广告开始结束*/
		
		/*播放之后的广告开始*/
		if($array['ad']['after'])
		{	
			foreach($array['ad']['after'] as $v)
			{
				$after = $dom->createElement('after');
				foreach($v as $k =>$vv)
				{
					$after->setAttribute($k,$vv);
				}
				$adverts->appendChild($after);
			}
		}
		/*播放之后的广告结束*/
		
		/*播放暂停广告开始*/
		if($array['ad']['pause'])
		{	
			foreach($array['ad']['pause'] as $v)
			{
				$pause = $dom->createElement('pause');
				foreach($v as $k =>$vv)
				{
					$pause->setAttribute($k,$vv);
				}
				$adverts->appendChild($pause);
			}
		}
		/*播放暂停广告结束*/
		
		/*播放器浮动广告开始*/
		if($array['ad']['float'])
		{	
			foreach($array['ad']['float'] as $v)
			{
				$float = $dom->createElement('float');
				foreach($v as $k =>$vv)
				{
					if($k == 'num')
					{
						$float->setAttribute('count',$vv);
					}
					else 
					{
						$float->setAttribute($k,$vv);
					}
				}
				$adverts->appendChild($float);
			}
		}
		/*播放器浮动广告开始结束*/
		$video->appendChild($adverts);
	}
	
	/*广告部分结束*/
	
	/*滚动字幕开始*/
	if($array['ad']['captions'])
	{
		$captions = $dom->createElement('captions');
		foreach($array['ad']['captions'] as $v)
		{
			$item4 = $dom->createElement('item');
			foreach($v as $k =>$vv)
			{
				if($k == 'id')
				{
					continue;
				}
				
				if($k == 'num')
				{
					$item4->setAttribute('count',$vv);
				}
				else 
				{
					$item4->setAttribute($k,$vv);
				}
			}
			$captions->appendChild($item4);
		}
		$video->appendChild($captions);
	}
	
	/*滚动字幕结束*/
	
	/*标记部分开始*/
	/*
	$marks = $dom->createElement('marks');
	for($i = 0;$i<2;$i++)
	{
		$item5 = $dom->createElement('item');
		$item5->setAttribute('title',"XXXX");
		$item5 = $dom->createElement('item');
		$marks->appendChild($item5);
	}
	$video->appendChild($marks);
	*/
	/*标记部分结束*/
	//2013.07.16 scala 获取视频打点信息
	$videoid = $array['video'][0]['id'];
	global $gGlobalConfig;
	if(isset($gGlobalConfig['App_video_point']))
	{
		
			$points = get_video_points($videoid);
			if(is_array($points[0]))
			{
				$marks = $dom->createElement('marks');
				foreach($points[0] as $key=>$val)
				{
					$item5 = $dom->createElement('item');
					$item5->setAttribute('title',$val['brief']);
					$item5->setAttribute('time',$val['point'] * 1000);
					$marks->appendChild($item5);
				}
				$video->appendChild($marks);
			}
			
	}
	//2013.07.16 scala end
	$dom->appendChild($video);
	return $dom->saveXml();
}

function my_sort($arr1, $arr2)
{
	$a = intval($arr1['position']);
	$b = intval($arr2['position']);
	if($a == $b)return 0;
	if($a > $b)
	{
		return 1;
	}
	else 
	{
		return -1;
	}
}


//获取视频数据
function get_video_info($id)
{
	global $gGlobalConfig;
	if(!$id)
	{
		return;
	}
	//普通请求id未加密
	if(preg_match('/^\d+$/', $id))
	{
		//此处id是数据库记录id 即系统id 非视频id
		$curl = new curl($gGlobalConfig['App_livmedia']['host'], $gGlobalConfig['App_livmedia']['dir']);
		$curl->initPostData();
		$curl->addRequestData('id', $id);
		$curl->addRequestData('a', 'detail');
		$video_info = $curl->request('vod.php');
		return $video_info[0];
	}
	else//加密的id 请求云视频的接口
	{
		$curl = new curl($gGlobalConfig['App_cloudvideo']['host'], $gGlobalConfig['App_cloudvideo']['dir']);
		$curl->initPostData();
		$curl->addRequestData('vuid', urlencode($id));
		$video_info = $curl->request('');
		return $video_info[0];
	}
}
unset($data['video']);
$data['preview'][] = array('url' => $video_info['img_info']['filepath'].$video_info['img_info']['filename']);
if ($video_info['width'] == '640' && $video_info['height'] == '480')
{
	//$video_info['aspect'] = '4:3';
}
//多码流
$vodurl_arr = $video_info['more_vodurl'];
if(!$video_info['is_link'] && $vodurl_arr)
{
	@array_unshift($vodurl_arr,$video_info['vodurl']);//默认的码流也是最高的码流放入数组第一个
	//控制节点的个数有且只能为4个
	while(count($vodurl_arr) > 3)
	{
		array_pop($vodurl_arr);
	}
	while($vodurl_arr && count($vodurl_arr) < 3)
	{
		@array_push($vodurl_arr,0);
	}
	array_push($vodurl_arr,$video_info['vodurl']);//最后一条是默认自动的码流
}
else
{
	$vodurl_arr = array();
	for($vi=0;$vi<4;$vi++)
	{
		$vodurl_arr[] = $video_info['vodurl'];
	}
	$video_info['first_frame'][0] = array(0=>$video_info['img']);
}
$data['video'][] = array('aspect'=>$video_info['aspect'],'title'=>$video_info['title'],'id'=>$video_info['id'],'url'=>$vodurl_arr,'startTime' => intval($video_info['start']), 'is_audio' => $video_info['is_audio'], 'duration' => intval($video_info['duration']));
$data['first_frame'] = $video_info['first_frame'][0];
$data['img_info'] = $video_info['img_info'];
$output_xml =  output_xml($data);
hg_cache_outputxml(CACHE_DIR . 'vod/' . $cache_outputxml_dir,$cache_outputxml_filename, $output_xml);
echo $output_xml;
//output($data);
?>