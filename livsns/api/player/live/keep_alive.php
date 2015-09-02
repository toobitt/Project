<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(CUR_CONF_PATH . 'global.php');

$channelId = intval($_REQUEST['id']);
$extend_para = hg_parse_para();
$cache_outxml_filename = md5($extend_para['cache_outputxml_dir']);
//hg_check_outputxml($channelId . '/' .$cache_outxml_filename, 'keepAlive', array('current'=>$ret['current']));
$dom = new DOMDocument('1.0', 'utf-8');
$keep = $dom->createElement('keepAlive');
$keep->setAttribute('current', time() . '000');
/*if(is_array($ret['marks']))
{
	$xml .= '<marks>';
	foreach($ret['marks'] as $key => $value)
	{
		$xml .= '<item time="' . $value['time'] . '" title="' . $value['title'] . '"/>';
	}
	$xml .= '</marks>';
}*/
//define('TMP_DIR', '/storage/www/vapi.thmz.com/api/cache/screen/' . $channelId . '/');//缓存路径
if($channelId)
{
	$ret = array('id' => $channelId);
	if($ret)
	{
		//第三个参数只读取暂停和浮动的广告位
		$ad = get_adv_data(LIVE_AD_GROUP, $ret, array('flag'=>'pause,liv_float,liv_captions'));
		if($ad)
		{
			$adverts = $dom->createElement('adverts');
			/*播放暂停广告开始*/
			if($ad['pause'])
			{	
				foreach($ad['pause'] as $v)
				{
					$pause = $dom->createElement('pause');
					foreach($v as $k =>$vv)
					{
						$pause->setAttribute($k,$vv);
					}
					$adverts->appendChild($pause);
				}
			}
			/*播放器浮动广告开始*/
			if($ad['liv_float'])
			{	
				foreach($ad['liv_float'] as $v)
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
			/*滚动字幕开始*/
			if($ad['liv_captions'])
			{
				$captions = $dom->createElement('captions');
				foreach($ad['liv_captions'] as $v)
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
				$keep->appendChild($captions);
			}
			
			/*滚动字幕结束*/
			$keep->appendChild($adverts);
		}
	}
	//获取屏蔽节目
	if ($_REQUEST['time'])
	{
		$start_time = intval($_REQUEST['time']/1000);
		$curl = new curl($gGlobalConfig['App_live']['host'], $gGlobalConfig['App_live']['dir']);
		$curl->setSubmitType('post');		
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('channel_id', $channelId);
		$curl->addRequestData('start_time', $start_time);
		$shield = $curl->request('program_shield.php');
		$is_shield = !empty($shield[0]) ? 1 : 0;
		if($is_shield)
		{
			$forbit = $dom->createElement('forbit');
			$forbit->setAttribute('value',$is_shield);
			$forbit->setAttribute('text',@$shield[0]['theme']);
			$keep->appendChild($forbit);
		}		
	}
	
	$handlers = $dom->createElement('handlers');
	$time = 1406964620000;
	$item5 = $dom->createElement('item');
	$item5->setAttribute('functionName','player.setUIVisible');
	$item5->setAttribute('time', $time);
	$item5->setAttribute('datetime', date('y-m-d H:i:s', 1406964620));
	$item5->setAttribute('param','');
	$handlers->appendChild($item5);
	$keep->appendChild($handlers);
}
$dom->appendChild($keep);	
$output_xml = $dom->saveXml();
//hg_cache_outputxml(CACHE_DIR . 'keepAlive/'.$channelId.'/', $cache_outxml_filename, $output_xml);
echo $output_xml;
exit;
?>