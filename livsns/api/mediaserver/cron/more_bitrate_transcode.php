<?php
/*
 * 计划任务执行的多码流
 */
require('global.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
require_once(CUR_CONF_PATH . 'lib/TranscodeRoute.class.php');
define('MOD_UNIQUEID','more_bitrate_transcode');//模块标识
set_time_limit(0);

class test extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//开始运行
	public function run()
	{
		//在转码服务器空闲的时候进行多码流
		/*
		$route = select_servers();//选取服务器	
		if(!$route)
		{
			$this->errorOutput(NO_SELECT_TRANSERVER);
		}
		$transcode = new transcode($route);
		$task_info = json_decode($transcode->get_transcode_tasks(),1);
		if($task_info['transcoding_tasks'])
		{
			$this->errorOutput(EXECAFTERMOREBIT);
		}
		*/
		
		//先选取转码服务器
		/*
    	if(defined('MORE_BITRATE_SERVER') && MORE_BITRATE_SERVER)//指定转码服务器
    	{
    		$tran_server = select_servers_by_id(intval(MORE_BITRATE_SERVER));
    	}
    	else 
    	{
    		//$tran_server = select_servers($vid);//自动选择转码服务器
    		$this->errorOutput('没有可用于多码流的服务器');
    	}
    	*/
		
		//选取用于多码流的服务器
		$tran_server = select_assign_servers();
		if(!$tran_server)
		{
			$this->errorOutput(NO_SELECT_TRANSERVER);
		}

		//先找出视频需要多码流的视频(排除转码中与暂停状态的视频以及失败的视频)
		$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE morebitrate_config_id != '' AND is_morebitrate=0 AND status NOT IN (0,4,-1,5) ORDER BY create_time DESC LIMIT 0,2";
		$q = $this->db->query($sql);
		$videos = array();
		while ($r = $this->db->fetch_array($q))
		{
			$videos[$r['id']] = $r;
		}
		
		$task_ids = array();//记录任务反馈
		if(!$videos)
		{
			$this->errorOutput(NOVIDEOS);
		}
		
		//判断选取的视频在不在多码流转码中,在的话就不提交这个视频了
		foreach ($videos AS $k => $v)
		{
			if(checkStatusFromAllServers($k . '_more') || checkStatusFromAllServers($k))
			{
				unset($videos[$k]);
			}
		}
		
		if(!$videos)
		{
			$this->errorOutput(NOVIDEOS);
		}
		//$transcode_configs = get_transcode_configs($config_id);

		foreach($videos AS $kk => $video)
		{
			//采用转码之后的视频
			$video_source = rtrim($video['video_path'],'/')  . '/' . $video['video_filename'];
			//构建target的目录	
			$output_file = array();
			$clarityUniqueId = array();//记录清晰度标识
			$transcode_configs = get_transcode_configs($video['morebitrate_config_id']);
			if(!$transcode_configs)
			{
				$this->errorOutput(NO_DATA);
			}
			//如果获取的转码配置个数小于1,就不执行了
			if(count($transcode_configs) < 1)
			{
				$this->errorOutput(NOMOREBITRATE);
			}
			//array_shift($transcode_configs);
			foreach($transcode_configs AS $k => $v)
			{
				if($v['unique_id'] == $video['cur_clarity'])
				{
					continue;
				}
				
				$target_dir_info = pathinfo(rtrim($video['video_path'],'/'));
				$new_target_dir  = $target_dir_info['dirname'] . '/' . $v['unique_id'] . '_' . $target_dir_info['basename'];
				if (!hg_mkdir($video['video_base_path'] . $new_target_dir) || !is_writeable($video['video_base_path'] . $new_target_dir))
				{
					$this->errorOutput(NOWRITE);
				}
				
				$output_file[] = array(
					'targetDir' 		=> $video['video_base_path'] . $new_target_dir,
					'output_filename' 	=> $v['unique_id'] . '_' . $target_dir_info['filename'],
					'config'			=> $v,
				);
				$clarityUniqueId[] = $v['unique_id'];
			}
	    	
			if($tran_server['need_file'])
			{
				/*
				if(defined("TARGET_VIDEO_DOMAIN"))
				{
					$url = 'http://' . ltrim(TARGET_VIDEO_DOMAIN,'http://') . '/' . $video_source;
				}
				else
				{
					$url = $this->settings['videouploads']['protocol'] . $this->settings['videouploads']['host'] . '/' . $video_source;
				}
				*/
				$url = 'http://' . ltrim(rtrim($video['hostwork'],'/'),'http://') . '/' . $video_source;
			}
			else
			{
				$url = '';
			}
			
			$sourceFile = array();
			$sourceFile[] = array(
				'source' 			=> $video['video_base_path'] . $video_source,
				'start' 			=> '0',
				'duration' 			=> '',
				'is_water_marked' 	=> '0',
				'url'				=> $url,
			);
			
			//构建提交转码的数据
			$this->settings['App_mediaserver']['dir'] = $this->settings['App_mediaserver']['dir'] . 'admin/';
			$this->settings['App_mediaserver']['filename'] = 'more_bitrate_callback.php';//设置多码流回调
			$data = array(
			    "sourceFile" 		=> $sourceFile,
			    "id" 				=> $video['id'] . '_more',
			    "app_id" 			=> APPID,
				"app_key" 			=> APPKEY,
			    "type" 				=> 'transcode_multi_bitrate',
			    "outputFile"		=> $output_file,
			    "callback" 			=> $this->settings['App_mediaserver'],
				"absolute_path"		=> '1',//采用绝对路径
		    );
		    
			//头信息
			if($this->settings['metadata'])
			{
				$metadata = $this->settings['metadata'];
				foreach ($metadata AS $k => $v)
				{
					if(!$v)
					{
						unset($metadata[$k]);
					}
					else 
					{
						$metadata[$k] = urlencode($v);
					}
				}
				$data['metadata'] = $metadata;
			}
		    
			$trans = new transcode($tran_server);
			$ret = $trans->addTranscodeTask($data);
			//提交后更新清晰度字段
			$sql = "UPDATE " .DB_PREFIX. "vodinfo SET clarity = '" .serialize($clarityUniqueId). "',is_morebitrate=1 WHERE id = '" .$video['id']. "'";
			$this->db->query($sql);
			$return = json_decode($ret,1);
			if($return['return'] == 'fail')
			{
				$this->addLogs('提交多码流',$data, $return,'提交多码流,视频id:' . $return['id']);
			}
			$task_ids[] = $return;
		}

		$this->addItem($task_ids);
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '多码流',	 
			'brief' => '定时找出没有经过多码流转码的视频进行多码流的处理',
			'space' => '180',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new test();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>