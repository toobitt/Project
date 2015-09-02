<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(CUR_CONF_PATH . 'global.php');
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

$isSelfWeb = hg_isSourceDomain($_GET['url']);
if (!$isSelfWeb)
{
	exit;
}
$channelId = $_REQUEST['id'];
$extend_para = hg_parse_para();
$cache_outputxml_dir = $extend_para['cache_outputxml_dir'];
$extend_para = $extend_para['extend_para'];
hg_check_outputxml($cache_outputxml_dir . $channelId, 'channel');
$curl = new curl($gGlobalConfig['App_live']['host'], $gGlobalConfig['App_live']['dir']);
$curl->setSubmitType('post');
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('a', 'get_channel_by_id');
$curl->addRequestData('fetch_live', 1);
$curl->addRequestData('channel_id', $channelId);
$ret = $curl->request('channel.php');
$ret = $ret[0];
if(is_array($ret))
{
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
	if(!$nuallad && ($extend_para['hg_ad_preview'] || ($isSelfWeb && $_GET['first'] == 1)))
	{
		$ret['ad'] = get_adv_data(LIVE_AD_GROUP, $ret, array('flag'=>'before,bottom,logo'));
	}
	$dom = new DOMDocument('1.0', 'utf-8');
	$channel = $dom->createElement('channel');
	$channel->setAttribute('name',$ret['channel']['name']);
	$ret['channel']['drm'] = 1;
	if ($ret['channel']['is_audio'])
	{
		$mask = $dom->createElement('mask', '');
		$snap = $ret['channel']['snap']['host'] . $ret['channel']['snap']['dir'] . $ret['channel']['snap']['filepath'] . $ret['channel']['snap']['filename'];
		$mask->setAttribute('url',$snap);
		$channel->appendChild($mask);
	}
	//防盗链
	if (ANTILEECH && $ret['channel']['drm'] == 1)
	{
		$drm = $dom->createElement('drm', ANTILEECH);
	}
	else
	{
		$drm = $dom->createElement('drm');
	}
	$drm->setAttribute('drm',$ret['channel']['drm']);
	$channel->appendChild($drm);
	/*播放器四个角的logo开始*/
	$logos = $dom->createElement('logos');
	$logos->setAttribute('baseUrl',"");
	$player_logo = $ret['ad']['logo'];
	if ($player_logo)
	{
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
	$channel->appendChild($logos);
	/*播放器四个角的logo开始结束*/
	
	/*视频流部分开始*/
	if ($ret['tviestream'])
	{
		$ret['stream'] = $ret['tviestream'];
	}
	$aspect = $ret['channel']['aspect'] ? $ret['channel']['aspect'] : '4:3';
	if($ret['stream'])
	{
		$video = $dom->createElement('video');
		$video->setAttribute('aspect',$aspect);
		if ($extend_para['live'])
		{
			$urlmark = 'live';
		}
		else
		{
			$urlmark = 'live_url';
		}
		$quality = 4;
		$defultstream = array();
		for($i = 0;$i < 3;$i++)
		{
			$v = $ret['stream'][$i];
			$item1 = $dom->createElement('item');
			if ($v[$urlmark])
			{
				if ($v['bitrate'] >= 1000)
				{
					$quality--;
				}
				elseif ($v['bitrate'] >= 600)
				{
					if ($quality > 2)
					{
						$quality = 3;
					}
					$quality--;
				}
				else
				{
					$quality = 1;
				}
				
				$item1->setAttribute('url',$v['stream_name'] . '/');
				if ($quality == 1)
				{
					$default_item = $item1;
					$default_item_stream = $v;
				}
			}
			else
			{
				$quality = 0;
			}
			if ($v['is_default'])
			{
				$defultstream = $v;
				$item1->setAttribute('default',1);
				$item1->setAttribute('def',1);
			}
			$item1->setAttribute('quality',$quality);
			$video->appendChild($item1);
		}
		$i = intval(count($ret['stream']) /2);
		if ($i < 1)
		{
			$i = 0;
		}
		$item1 = $dom->createElement('item');
		if (!$defultstream && $default_item_stream)
		{
			$defultstream = $default_item_stream;
			$default_item->setAttribute('default',1);
			$default_item->setAttribute('def',1);
		}
		if ($defultstream)
		{
			$v = $defultstream;
		}
		else
		{
			$v = $ret['stream'][$i];
			$item1->setAttribute('default',1);
			$item1->setAttribute('def',1);
		}
		$filename = ltrim(strrchr($v[$urlmark], '/'), '/');
		$baseurl = str_replace($filename, '', $v[$urlmark]);
		$video->setAttribute('baseUrl',$baseurl);
		$item1->setAttribute('url',$v['stream_name'] . '/');
		$video->appendChild($item1);
		$channel->appendChild($video);
	}
	/*视频流部分结束*/
	/*广告部分开始*/
	if($ret['ad'])
	{
		$adverts = $dom->createElement('adverts');
		
		/*播放器底部的广告开始*/
		if($ret['ad']['bottom'])
		{
			foreach($ret['ad']['bottom'] as $v)
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
		if($ret['ad']['before'])
		{	
			foreach($ret['ad']['before'] as $v)
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
		
		/*播放暂停广告开始*/
		if($ret['ad']['pause'])
		{	
			foreach($ret['ad']['pause'] as $v)
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
		if($ret['ad']['float'])
		{	
			foreach($ret['ad']['float'] as $v)
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
		$channel->appendChild($adverts);
	}
	
	/*广告部分结束*/
	
	$dom->appendChild($channel);
	$output_xml = $dom->saveXml();
	hg_cache_outputxml(CACHE_DIR . 'channel/'.$cache_outputxml_dir, $channelId, $output_xml);
	echo $output_xml;
}
exit;
?>