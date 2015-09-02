<?php
/*****构建视频转码配置******/
function transcode_config($vod_config_id = '')
{
	$gDB = hg_ConnectDB();
	/*** 选取转码配置(如果没选分组,就取默认分组下的默认配置;如果选择了分组,就选择该分组下的默认配置) ***/
	if(!$vod_config_id)
    	{
    		$vod_config_id = $gDB->query_first("SELECT id FROM " .DB_PREFIX. "vod_config_type WHERE is_default = 1");
	    	$condition = " AND type_id = " .$vod_config_id['id']. " AND is_default = 1 ";
    	}
    	else
    	{
    		$condition = " AND type_id=".$vod_config_id. " AND is_default = 1 ";
    	}
	$sql = " SELECT * FROM ".DB_PREFIX."vod_config WHERE is_use = 1 ".$condition;
	$vod_config = $gDB->query_first($sql);
	if(!$vod_config)
	{
		return false;
	}
	$vod_config['codec_format'] = 'libx264';
	$vod_config['codec_profile'] = 'main';
	if($vod_config['water_pic_position'])
	{
		$p_water = explode(',',trim($vod_config['water_pic_position']));
		$vod_config['water_mark_x'] = $p_water[0];
		$vod_config['water_mark_y'] = $p_water[1];
	}
	else
	{
		$vod_config['water_mark_x'] = '0';
		$vod_config['water_mark_y'] = '0';
	}
	$vod_config['water_mark']   = $vod_config['is_open_water']?basename($vod_config['water_pos']):'';
	unset($vod_config['id'],$vod_config['water_pos'],$vod_config['water_pic_position'],$vod_config['config_order_id'],$vod_config['name'],$vod_config['is_use']);
	return $vod_config;
}

function hg_mc_sec2format($time)
{
	$mcr_time_sec = $time % 1000;
	$mcr_time_sec = str_pad($mcr_time_sec, 3, '0', STR_PAD_LEFT);
	$time = intval($time / 1000);
	$h = intval($time / 3600);
	$h = str_pad($h, 2, '0', STR_PAD_LEFT);
	$sec = $time % 3600;
	$m = intval($sec / 60);
	$m = str_pad($m, 2, '0', STR_PAD_LEFT);
	$sec = $sec % 60;
	$sec = str_pad($sec, 2, '0', STR_PAD_LEFT);
	return $dur = $h . ':' . $m . ':' . $sec . '.' . $mcr_time_sec;
}

function hg_snap($time, $snapdir, $width, $height, $source, $times = 0, $spec_fname = '')
{
	$maxtimes = 2;
	if (!$spec_fname)
	{
		$spec_fname = $time;
	}
	$file = $spec_fname . '.jpg';
	$jpg = $snapdir .$file;
	$time1 = hg_mc_sec2format($time);
	$cmd = FFMPEG_CMD . ' -ss ' . $time1 . ' -i "' . $source .  '" -s ' . $width .'x' . $height . ' -y "' . $jpg .'" -vframes 1';
	exec($cmd, $out, $s);
	if (!is_file($snapdir . $file) && $times < $maxtimes)
	{
		$times = intval($times) + 1;
		$file = hg_snap($time - $times * 40, $snapdir, $width, $height, $source, $times, $spec_fname);
	}
	if (!is_file($snapdir . $file))
	{
		return false;
	}
	return $file;
}

//将xml解析成数组
function hg_xml2Array($xmlstr = '')
{
	$xmlstr = preg_replace('/\sxmlns="(.*?)"/', ' _xmlns="${1}"', $xmlstr);
    $xmlstr = preg_replace('/<(\/)?(\w+):(\w+)/', '<${1}${2}_${3}', $xmlstr);
    $xmlstr = preg_replace('/(\w+):(\w+)="(.*?)"/', '${1}_${2}="${3}"', $xmlstr);
    $xmlobj = @simplexml_load_string($xmlstr);
    return json_decode(json_encode($xmlobj), true);
}

//转码提交失败或者失败就做的操作
function hg_do_transcode_fail($commit_data,$video_id)
{
	if(!$video_id)
	{
		return false;
	}
	$gDB = hg_ConnectDB();
	//先查询库里面是不是已经有这条记录了
	$sql 	= " SELECT id FROM " . DB_PREFIX . "vod_fail_video WHERE video_id = '" .$video_id. "'";
	$video 	= $gDB->query_first($sql);
	$data = array(
		'video_id' 		=> $video_id,
		'commit_data' 	=> json_encode($commit_data),
	);
	if($video['id'])
	{
		$sql = " UPDATE ".DB_PREFIX."vod_fail_video SET ";
	}
	else
	{
		$sql = " INSERT INTO ".DB_PREFIX."vod_fail_video SET ";
	}
	
	foreach ($data AS $k => $v)
	{
		$sql .= " {$k} = '{$v}',";
	}
	$sql = trim($sql,',');
	if($video['id'])
	{
		$sql .=  " WHERE id = '" .$video['id']. "'";
	}
	$gDB->query($sql);
	return true;
}

//转码成功后，判断vod_fail_video有没有数据，有的话就删除
function hg_do_transcode_success($video_id)
{
	if(!$video_id)
	{
		return false;
	}
	$gDB = hg_ConnectDB();
	$sql 	= " SELECT id FROM " . DB_PREFIX . "vod_fail_video WHERE video_id = '" .$video_id. "'";
	$video 	= $gDB->query_first($sql);
	if($video['id'])
	{
		$sql = " DELETE FROM " . DB_PREFIX . "vod_fail_video WHERE id = '" . $video['id'] . "'";
		$gDB->query($sql);
	}
	return true;
}

//获取转码服务器信息
function hg_get_transcode_servers($server_ids = '')
{
	$gDB = hg_ConnectDB();
	$cond = '';
	if($server_ids)
	{
		$cond .= " AND id IN (" .$server_ids. ")";
	}
	$sql = "SELECT * FROM " .DB_PREFIX. "transcode_center WHERE is_open = 1 " . $cond;
	$q = $gDB->query($sql);
	$servers = array();
	while($r = $gDB->fetch_array($q))
	{
		$servers[$r['id']] = $r;
	}
	return $servers;
}

//创建目录
function create_video_dir()
{
	$vod_dir_names  = TIMENOW . hg_rand_num(2);
	$video_dir = date('Y',TIMENOW).'/'. hg_split_num($vod_dir_names,4,'/') . $vod_dir_names .'.ssm/';
	if(file_exists(UPLOAD_DIR . $video_dir) || file_exists(TARGET_DIR . $video_dir))
	{
		return create_video_dir();
	}
	return array($vod_dir_names,$video_dir);
}

//从原视频截取一张图
function getimage($source_path,$targerdir,$cid)
{
	$snapobj = new SnapFromVideo();
	$path = $snapobj->snapPicture($source_path,$targerdir);
	if($path)
	{
		$material = new material();
    	$img_info = $material->localMaterial($path,$cid);
    	return $img_info[0];
	}
	return false;
}

//选择转码服务器
function select_servers($id = '')
{
	//选取正在转码中的视频最少的一台服务器
	$route = new TranscodeRoute();
	$ret = $route->route();
	//如果没有选取到转码服务器，就取默认的转码服务器配置
	if($ret['return'] == 'fail')
	{
		global $gGlobalConfig;
		if($gGlobalConfig['transcode'])
		{
			$ret = $gGlobalConfig['transcode'];
		}
		else
		{
			$ret = array();
		}
	}
	if($id)
	{
		$gDB = hg_ConnectDB();
		$sql = " UPDATE " .DB_PREFIX."vodinfo SET transcode_server = '" .serialize($ret). "' WHERE id = '" .$id. "'";
		$gDB->query($sql);
	}
	return $ret;
}

//选择指定的转码服务器
function select_servers_by_id($server_id = '',$video_id = '')
{
	if(!$server_id)
	{
		return false;
	}
	
	$gDB = hg_ConnectDB();
	$sql = "SELECT * FROM " .DB_PREFIX. "transcode_center WHERE is_open = 1 AND id = '" . $server_id . "'";
	$server = $gDB->query_first($sql);
	if(!$server)
	{
		return false;
	}
	
	$server = array(
		'protocol' 	=> 'http://',
		'host' 		=> $server['trans_host'],
		'port' 		=> $server['trans_port'],
		'need_file' => $server['is_carry_file'],
	);
	
	if($video_id)
	{
		$sql = " UPDATE " .DB_PREFIX."vodinfo SET transcode_server = '" .serialize($server). "' WHERE id = '" .$video_id. "'";
		$gDB->query($sql);
	}
	return $server;
}

//查找所有转码服务器中是否存在某个转码任务，存在返回服务器信息
function checkStatusFromAllServers($id = '')
{
	if(!$id)
	{
		return false;
	}
	
	//获取开启的转码服务器
	if(!$servers = hg_get_transcode_servers())
	{
		return false;
	}
	
	if(!class_exists('transcode'))
	{
		include_once(CUR_CONF_PATH . 'lib/transcode.class.php');
	}
	
	foreach ($servers AS $k => $v)
	{
		$t_server = array('host' => $v['trans_host'],'port' => $v['trans_port']);
		$transcode = new transcode($t_server);
		$ret = $transcode->get_transcode_status("{$id}");//返回状态
		$ret = json_decode($ret,1);
		//查询到有该任务并且不是回调失败
		if($ret['return'] == 'success' && $ret['status'] != 'callback_failed')
		{
			return $t_server;
		}
	}
	return false;
}

//检索所有转码服务器中有没有正在转码的视频
function checkIsExistTranscode()
{
	//获取开启的转码服务器
	if(!$servers = hg_get_transcode_servers())
	{
		return 0;
	}
	
	if(!class_exists('transcode'))
	{
		include_once(CUR_CONF_PATH . 'lib/transcode.class.php');
	}
	
	foreach ($servers AS $k => $v)
	{
		$t_server = array('host' => $v['trans_host'],'port' => $v['trans_port']);
		$transcode = new transcode($t_server);
		$task_info = json_decode($transcode->get_transcode_tasks(),1);
		if($task_info['transcoding_tasks'])
		{
			return 1;
		}
	}
	return 2;
}

/************************************************多码流相关的函数****************************************************/

//获取所有开启的转码配置
function get_transcode_configs($config_id)
{
	$gDB = hg_ConnectDB();
	//获取不同码流的转码配置
	$sql = "SELECT * FROM " .DB_PREFIX."vod_config WHERE is_use = 1 AND type_id=".$config_id."
	AND is_default != 1 ";
	$q   = $gDB->query($sql);
	$vod_config = array();
	while($r = $gDB->fetch_array($q))
	{
		$r['codec_format'] = 'libx264';
		$r['codec_profile'] = 'main';
		if($r['water_pic_position'])
		{
			$p_water = explode(',',trim($r['water_pic_position']));
			$r['water_mark_x'] = $p_water[0];
			$r['water_mark_y'] = $p_water[1];
		}
		else
		{
			$r['water_mark_x'] = '0';
			$r['water_mark_y'] = '0';
		}
		$r['water_mark']   = $r['is_open_water']?basename($r['water_pos']):'';
		unset($r['water_pos'],$r['water_pic_position'],$r['config_order_id'],$r['name'],$r['is_use'],$r['id']);
		$vod_config[] = $r;
	}
	if(!$vod_config)
	{
		return false;
	}
	return $vod_config;
}

//选取指定的转码服务器，并且挑选出最优的转码服务器
function select_assign_servers($type = false)
{
	//选取正在转码中的视频最少的一台服务器
	if($type)
	{
		if(!defined('MANDATORY_SERVER') || !MANDATORY_SERVER)
		{
			return false;
		}
		$server_ids = MANDATORY_SERVER;
	}
	else 
	{
		if(!defined('MORE_BITRATE_SERVER') || !MORE_BITRATE_SERVER)
		{
			return false;
		}
		$server_ids = MORE_BITRATE_SERVER;
	}

	$route = new TranscodeRoute();
	$ret = $route->route($server_ids);
	//如果没有选取到转码服务器，就取默认的转码服务器配置
	if($ret['return'] == 'fail')
	{
		global $gGlobalConfig;
		if($gGlobalConfig['transcode'])
		{
			$ret = $gGlobalConfig['transcode'];
		}
		else
		{
			$ret = array();
		}
	}
	return $ret;
}

/************************************************多码流相关的函数****************************************************/
function hg_build_dowload_dir()
{
	$year = date('Y');
	$month = date('m');
	$day  = date('d');
	$dir = $year.'/'.$month.'/' . $day . '/';
	return $dir;
}

/*
 *通过视频真实地址下载视频,返回下载后文件地址
 *$url 视频地址
 *$dir 指定要保存的位置
 */
function download_from_url($url,$dir)
{
	if(!$url)
	{
		return false;
	}
	if(!$dir)
	{
		return false;
	}
	$file = fopen ($url, "rb");
	if ($file) 
	{
		if(!is_dir($dir))
		{
			hg_mkdir($dir);
		}
		$basename = basename($url);
		$type = strtolower(strrchr($basename, '.'));
		$newfname = $dir.rand(10000,99999).rand(10000,99999).$type;
		$newf = fopen($newfname, "wb");
		if($newf)
		{
			while(!feof($file)) 
			{
				fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
			}
			
			if ($file) 
			{
				fclose($file);
			}
			
			if ($newf) 
			{
				fclose($newf);
			}
		}
	}

	return $newfname;
}

/*
 * 将数组写到xml文件
 * 
 */
function arrtoxml($arr,$dom=0,$item=0)
{
    if (!$dom)
    {
        $dom = new DOMDocument("1.0", "UTF-8");
    }
    if(!$item)
    {
        $item = $dom->createElement("root"); 
        $dom->appendChild($item);
    }
    foreach ($arr as $key => $val)
    {
        $itemx = $dom->createElement(is_string($key) ? $key : "item");
        $item->appendChild($itemx);
        if (!is_array($val))
        {
            $text = $dom->createTextNode($val);
            $itemx->appendChild($text);
            
        }
        else
        {
            arrtoxml($val,$dom,$itemx);
        }
    }
    return $dom->saveXML();
}









?>