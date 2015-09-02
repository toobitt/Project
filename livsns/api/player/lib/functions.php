<?php
function output($data)
{
	$debug = $_REQUEST['debug'];
	if (!$debug)
	{
		header('Content-Type:text/plain; charset=utf-8');
		echo json_encode($data);
	}
	else
	{
		header('Content-Type:text/html; charset=utf-8');
			echo '<pre>';
			print_r($data);
			echo '</pre>';
	}
	exit;
}

function error_output($errorno, $errortext, $more = '')
{
	$error = array(
		'errorno' => $errorno,	
		'errortext' => $errortext,	
		'more' => $more,	
	);
	output($error);
}
//获取广告数据
function get_adv_data($group='', $video_info=array(), $para=array())
{
	global $gGlobalConfig;
	/*if(!isset($this->input['ad']))
	{
		return;
	}*/
	$curl = new curl($gGlobalConfig['App_adv']['host'], $gGlobalConfig['App_adv']['dir']);
	$curl->initPostData();
	$ad = array();
	if($group)
	{
		$curl->addRequestData('group', $group);
	}
	//传递视频信息
	if($video_info)
	{
		$curl->addRequestData('vinfo', urlencode(json_encode($video_info)));
	}
	//额外参数
	if($para)
	{
		foreach($para as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
	}
	$ad = $curl->request('ad.php');
	if(!$ad || $ad == 'null')
	{
		return;
	}
	if (!is_array($ad))
	{
		return;
	}
	$return = array();
	//设置要显示的字段
	$fields = array('pubid','url','param');
	foreach ($ad as $v)
	{
		$temp_array = array();
		foreach ($v as $k=>$vv)
		{
			//只允许显示设置的字段
			if(!in_array($k, $fields))
			{
				continue;
			}
			if($k != 'param')
			{
				//pubid转换成xml的id 用于点击统计
				if($k == 'pubid')
				{
					$temp_array['id'] = $vv;
				}
				else
				{
					$temp_array[$k] = $vv;
				}
				continue;
			}
			//广告位自定义参数
			if(is_array($vv['pos']) && $vv['pos'])
			{
				foreach ($vv['pos'] as $para=>$value)
				{
					$temp_array[$para] = $value;
				}
			}
			//效果自定义参数
			if(is_array($vv['ani']) && $vv['ani'])
			{
				foreach ($vv['ani'] as $para=>$value)
				{
					$temp_array[$para] = $value;
				}
			}
		}
		$return[$v['name']][] = $temp_array;
	}
	return $return;
}
//视频或者频道缓存xml检测
function hg_check_outputxml($filepath = '',$type  = 'vod', $replace = array())
{
	if(!CACHE_TIME)
	{
		return;
	}
	if(!$filepath)
	{
		return;
	}
	$cachexmlfile = CACHE_DIR . $type . '/' .$filepath . '.xml';
	if(!file_exists($cachexmlfile))
	{
		return;
	}
	if(TIMENOW - filectime($cachexmlfile)>CACHE_TIME)
	{
		return;
	}
	ob_clean();
	ob_start();
	$output_xml = file_get_contents($cachexmlfile);
	if($replace)
	{
		foreach ($replace as $attr=>$value)
		{
			$output_xml = preg_replace('/'.$attr.'=".*?"/', $attr . '="'.$value.'"', $output_xml);
		}
	}
	echo $output_xml;
	ob_flush();
	exit;
}
function hg_cache_outputxml($filepath = '', $filename = '', $content = '')
{
	if(!CACHE_TIME)
	{
		return;
	}
	if(!$filename || !$filepath)
	{
		return false;
	}
	if(hg_mkdir($filepath))
	{
		hg_file_write($filepath . $filename . '.xml', $content);
	}
}
function hg_parse_para()
{
	$parameters = array();
	$para = explode('&',urldecode($_GET['extend']));
	if(!$para)
	{
		return $parameters;
	}
	$cache_outputxml_dir = '';
	foreach ($para AS $k => $v)
	{
		if (!$v)
		{
			continue;
		}
		$v = explode('=', $v);
		$parameters['extend_para'][$v[0]] = $v[1];
		$parameters['cache_outputxml_dir'] .= urlencode($v[0].'_'.$v[1]) . '/';
	}
	return $parameters;
}
//get video points 
function get_video_points($id)
{
	
	global $gGlobalConfig;
	$curl = new curl($gGlobalConfig['App_video_point']['host'], $gGlobalConfig['App_video_point']['dir']);
	$curl->setSubmitType('post');
	$curl->setReturnFormat('json');
	$curl->initPostData();
	$curl->addRequestData('videoid', intval($id));
	$curl->addRequestData('a', 'is_pointed');
	$datas = $curl->request('admin/videopoint.php');
	//file_put_contents('return.txt',var_export($datas,1));
	return $datas;
}
function hg_isSourceDomain($url)
{
	//var_dump(strpos($url, CURDOMAIN));
	if (!CURDOMAIN)
	{
		return true;
	}
	if (strpos($url, CURDOMAIN))
	{
		return true;
	}
	else
	{
		global $gGlobalConfig;
		if ($gGlobalConfig['white_list'])
		{
			foreach ($gGlobalConfig['white_list'] AS $v)
			{
				if (strpos($url, $v))
				{
					return true;
				}
			}
		}
		return false;
	}
}
?>