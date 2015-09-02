<?php
/*
 * 视频上传接口
 * 包括（文件上传，webservice）
 * */
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
require_once(CUR_CONF_PATH . 'lib/mediainfo.class.php');
require_once(CUR_CONF_PATH . 'lib/SnapFromVideo.class.php');
require_once(CUR_CONF_PATH . 'lib/TranscodeRoute.class.php');
require_once(CUR_CONF_PATH . 'lib/mosaic_mode.php');
require_once(CUR_CONF_PATH . 'lib/water_config_mode.php');
define('MOD_UNIQUEID','livmedia');//模块标识
set_time_limit(0);

class create extends adminBase
{
	private $dir_info = array();//临时存储目录信息
    public function __construct()
    {
       parent::__construct();
    }
    
    public function submit_transcode()
    {
    	//构建提交转码的参数
	    $vod_config = transcode_config();
	    $is_file = 0;//标识是否是文件上传
    	if($_FILES)
    	{
    		$is_file = 1;
    		$this->check_has_videofile($vod_config);
    		$this->input['filepath'] = $this->dir_info['filepath'];
    	}
    	else 
    	{
			if (!$this->input['notcheck'])
			{
				if(!$this->input['filepath'] || !file_exists(UPLOAD_DIR . $this->input['filepath']))
				{
					$this->errorOutput(NOTFINDFILE);
				}
			}
    		
    		//如果没传title就取文件名
    		if(!$this->input['title'])
    		{
    			$_path_name = basename($this->input['filepath']);
	    		$this->input['title'] = substr($_path_name,0,strrpos($_path_name,'.'));
    		}
    		$this->create_dir($vod_config);
    	}
	    $video 		= $this->input;
	    $v_vinfo   	= $this->storage_data($video,$vod_config);
	    $vid 		= $v_vinfo['vid'];
	    $img_info 	= $v_vinfo['img_info'];

	    if($video['audit_auto'])//如果是自动收录,回调增加一个参数
		{
			$this->settings['App_mediaserver']['extends'] = $video['audit_auto'];
		}

		//传到转码服务器的duration参数的单位是秒，所以此处因为接受外部的时间单位是ms
		if($is_file)
		{
			$duration = '';
			$start = '0';
		}
		else
		{
			$duration = $video['duration']?$video['duration']:(intval($video['end'] - $video['start'])/1000) . '';
			$start = $video['start'];
		}
		$source_arr = array('source' =>  UPLOAD_DIR . $video['filepath'],'start' => $start,'duration' => $duration,'is_water_marked' => '0');
		$this->settings['App_mediaserver']['dir'] = $this->settings['App_mediaserver']['dir'] . 'admin/';
		$data = array(
			"sourceFile" 		=> array($source_arr),
			"id" 				=> "{$vid}",
			"app_id" 			=> APPID,
			"app_key" 			=> APPKEY,
		    "type"				=>"transcode_upload",
		    "targetDir"			=> TARGET_DIR . $this->dir_info['target_dir'],
		    "output_filename" 	=> $this->dir_info['output_filename'],
			"config" 			=> $vod_config,
			"callback" 			=> $this->settings['App_mediaserver'],
			"ts_need_preprocess"=> $video['ts_need_preprocess']?'1':'0',
			"mp4_from_sobey"	=> $video['mp4_from_sobey']?'1':'0',
			"force_recodec"		=> $video['force_recodec']?'1':'0',
			"absolute_path"		=> '1',//采用绝对路径
		);
		
		//加马赛克(优先采用用户提供的自定义的马赛克)
		if($video['mosaic'])
		{
			$mosaic = explode(',',$video['mosaic']);
			if(count($mosaic) == 4)
			{
				$mosaicArr = array(
					'x' 	=> $mosaic[0],
					'y' 	=> $mosaic[1],
					'width' => $mosaic[2],
					'height'=> $mosaic[3],
				);
				
				$data['config']['mosaic'] = $mosaicArr;
			}
		}
		elseif ($video['mosaic_id'])//按照用户从配置里面选取的马赛克添加马赛克
		{
			$mosaic_mode = new mosaic_mode();
			$mosaic_config = $mosaic_mode->detail($video['mosaic_id']);
			if($mosaic_config)
			{
				$data['config']['mosaic'] = array(
					'x' 	=> $mosaic_config['x'],
					'y' 	=> $mosaic_config['y'],
					'width' => $mosaic_config['width'],
					'height'=> $mosaic_config['height'],
				);	
			}
		}

		//指定不用加水印
		if($video['no_water'])
		{
			$data['config']['water_mark'] = '';
		}
		else if($video['water_id'])//如果用户传了水印图片的id 
		{
			$water_mode = new water_config_mode();
			$waterInfo = $water_mode->detail($video['water_id']);
			if($waterInfo)
			{
				$data['config']['water_mark'] = $waterInfo['base_path'] . $waterInfo['img_path'];
				if($video['water_pos'])
				{
					$_pos = explode(',',$video['water_pos']);
				}
				else 
				{
					$_pos = explode(',',WATER_POS);//如果没传位置，就用默认位置
				}
				$data['config']['water_mark_x'] = $_pos[0];
				$data['config']['water_mark_y'] = $_pos[1];
			}
		}
		
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

		//记录水印与马赛克的参数，在强制转码的时候可能会用到这些参数，因为在第一次转码的时候不一定是强制转码，这样水印与马赛克不一定有
		$_param = array(
			'mosaic' 	=> $data['config']['mosaic'],
			'water'	 	=> array(
						'water_mark' 	=> $data['config']['water_mark'],
						'water_mark_x' 	=> $data['config']['water_mark_x'],
						'water_mark_y' 	=> $data['config']['water_mark_y'],
			),
			'metadata'	=> $data['metadata'],
		);
		file_put_contents(DATA_DIR . $vid . '.json' ,json_encode($_param));

    	//选取转码服务器
    	if($video['server_id'])//指定转码服务器
    	{
    		$tran_server = select_servers_by_id($video['server_id'],$vid);
    	}
    	else 
    	{
    		$tran_server = select_servers($vid);//自动选择转码服务器
    	}
		
		if(!$tran_server)
		{
			$this->errorOutput(NO_SELECT_TRANSERVER);
		}
		$trans = new transcode($tran_server);
		//根据选取到转码服务器是否需要携带文件，选择各自的提交方式
		if($tran_server['need_file'])
		{
			$data['upload_file_in_callback'] = "1";
			$data['sourceFile'][0]['url'] = 'http://' . ltrim(SOURCE_VIDEO_DOMIAN,'http://') . '/' . $source_arr['source'];
		}
		$ret = $trans->addTranscodeTask($data);
	    $return = json_decode($ret,1);
		if($return['return'] == 'fail')
		{
			$sql = " UPDATE " . DB_PREFIX ."vodinfo SET status = -1 WHERE id = {$vid}";
			$this->db->query($sql);
			hg_do_transcode_fail($data,$vid);//将转码失败的信息记录下来
			$error_info = array(
				'return' 	=> 'success',//上传成功
				'transcode' => 'fail',//转码失败
				'id'		=> $vid,
				'app'		=> APP_UNIQUEID,
				'module'	=> MOD_UNIQUEID,
				'img'		=> $img_info,
				'ErrorCode' => '0x0025',
				'ErrorText' => '提交转码失败',
			);
			echo json_encode($error_info);//上传成功,转码失败
		}
		else
		{
			//返回数据
			$video_info = array(
				'id' 		=> $vid,
				'protocol' 	=> 'http://',
				'host' 		=> defined("TARGET_VIDEO_DOMAIN")?ltrim(TARGET_VIDEO_DOMAIN,'http://'):$this->settings['videouploads']['host'],
				'dir'		=> $this->dir_info['target_dir'], 
				'file_name' => $this->dir_info['output_filename'],
				'img'		=> $img_info,
				'type'		=> $vod_config['output_format'],
				'app'		=> APP_UNIQUEID,
				'module'	=> MOD_UNIQUEID,
				'return'	=> 'success',
			);
			
			//请求用户提供的callback地址,并且携带用户提供的数据
			if($video['callback_url'])
			{
				if($video['callback_data'])
				{
					$callback_data = json_decode(base64_decode($video['callback_data']),1);
					//此处在视频库里面记录电视剧的id
					if($callback_data['tv_play_id'])
					{
						$sql = "UPDATE " . DB_PREFIX ."vodinfo SET tv_play_id = '" . $callback_data['tv_play_id'] . "' WHERE id = '" .$vid. "'";
						$this->db->query($sql);
					}
					$video_info['callback_data'] = $callback_data;
				}
				
				if($callbackReturnData = $this->mediaserverCallback($video['callback_url'],$video_info))
				{
					$callbackReturnData = json_decode($callbackReturnData,1);
					if($callbackReturnData['ErrorCode'])
					{
						$video_info['callback_return'] = $callbackReturnData;
					}
					else
					{
						$video_info['callback_return'] = $callbackReturnData[0];
					}
				}
			}
			$this->addItem($video_info);
			$this->output();
		}
    }
    
    //首先创建目录
    private function create_dir($vod_config)
    {
	    $v_videodir 	= create_video_dir();
		$vod_dir_names  = $v_videodir[0];
		$video_dir 		= $v_videodir[1];
		if (!hg_mkdir(TARGET_DIR . $video_dir) || !is_writeable(TARGET_DIR . $video_dir))
		{
			$this->errorOutput(NOWRITE);
		}
		$this->dir_info = array(
			'output_filename' 	=> $vod_dir_names . '',
			'output_format' 	=> $vod_config['output_format'],
			'target_dir' 		=> $video_dir
		);
    }
    
    //检测是否携带视频文件
    private function check_has_videofile($vod_config)
    {
    	if (!$_FILES['videofile']['tmp_name'])
		{
			$this->errorOutput(NOFILE);
		}
		
		//判断空间够不够存放视频
    	if(function_exists('disk_free_space'))
    	{
    		if($_FILES['videofile']['size'] > disk_free_space(UPLOAD_DIR))
    		{
    			$this->errorOutput(DISC_SPACE_NOT_ENOUGH);
    		}
    	}
				
		$original 	= urldecode($_FILES['videofile']['name']);
		$filetype 	= strtolower(strrchr($original, '.'));
		$allowtype 	= explode(',', $this->settings['video_type']['allow_type']);
		if (!in_array($filetype, $allowtype))
		{
			$this->errorOutput(FORBIDTYPE);
		}

		$v_videodir 	= create_video_dir();
		$vod_dir_names  = $v_videodir[0];
		$video_dir 		= $v_videodir[1];
		if (!hg_mkdir(UPLOAD_DIR . $video_dir) || !is_writeable(UPLOAD_DIR . $video_dir))
		{
			$this->errorOutput(NOWRITE);
		}
		
		if (!hg_mkdir(TARGET_DIR . $video_dir) || !is_writeable(TARGET_DIR . $video_dir))
		{
			$this->errorOutput(NOWRITE);
		}
		//将上传的文件移动到原视频目录
		$source_filename = 'video_' . $vod_dir_names . $filetype;
		$filepath = $video_dir.$source_filename;
		if (!@move_uploaded_file($_FILES['videofile']['tmp_name'], UPLOAD_DIR . $filepath))
		{
			$this->errorOutput(FAILMOVE);
		}
		
		$this->dir_info = array(
			'output_filename' 	=> $vod_dir_names . '',
			'output_format' 	=> $vod_config['output_format'],
			'target_dir' 		=> $video_dir,
			'source_filename' 	=> $source_filename,
			'filepath' 			=> $filepath,
			'original' 			=> substr($original,0,strrpos($original,'.')),//去除掉文件后缀名
		);
    }
    
    //存储数据
    private function storage_data($video,$vod_config)
    {
		if($video['column_id'])
		{
			$publish_column = new publishconfig();
			$column_id = $video['column_id'];
			$column_id = $publish_column->get_columnname_by_ids('id,name',$column_id);
			$column_id = serialize($column_id);
		}
		$channel_id = $video['channel_id'];
		if ($video['create_time'] != -1)
		{
    		$create_time = strtotime($video['create_time']);
		}
    	$pathinfo = pathinfo($video['filepath']);
        //如果不存在分类就默认其分类与类型相同
        $vod_leixing = $video['vod_leixing']?$video['vod_leixing']:1;
        if(!$video['vod_sort_id'] || intval($video['vod_sort_id']) == -1)
		{
			$video['vod_sort_id'] = $vod_leixing;
		}
		foreach ($video AS $k => $v)
		{
			if(is_string($v))
			{
				$video[$k] = urldecode($v);
			}
		}
	   $data = array(
			'cur_clarity'    	=> $vod_config['unique_id'],//记录当前采用的默认的清晰度标识
			'title'    			=> $video['title']?$video['title']:$this->dir_info['original'],
			'source'  	 		=> $video['source'],
			'subtitle' 			=> $video['subtitle'],
			'keywords' 			=> $video['keywords'],
			'comment'			=> $video['comment'],
			'author' 			=> $video['author'],
			'vod_leixing'		=> $vod_leixing,
			'bitrate' 			=> $vod_config['video_bitrate'],
			'vod_sort_id' 		=> $video['vod_sort_id'],
			'is_allow' 			=> $video['is_mark'],
			'starttime' 		=> $video['starttime']?strtotime($video['starttime']):'',
			'hostwork'			=> defined("TARGET_VIDEO_DOMAIN")?'http://' . ltrim(TARGET_VIDEO_DOMAIN,'http://'):$this->settings['videouploads']['protocol'] . $this->settings['videouploads']['host'],
	   		'source_hostwork'	=> defined("SOURCE_VIDEO_DOMIAN")?'http://' . ltrim(SOURCE_VIDEO_DOMIAN,'http://'):'',
			'source_base_path'	=> UPLOAD_DIR,
			'source_path'		=> rtrim($pathinfo['dirname'],'/') . '/',
	   		'source_filename'	=> $pathinfo['basename'],
	   		'video_base_path'	=> TARGET_DIR,
	   		'video_path'		=> $this->dir_info['target_dir'],
	   		'video_filename'	=> $this->dir_info['output_filename'] . '.' . $this->dir_info['output_format'],
	   		'channel_id' 		=> $channel_id,
	   		'column_id' 		=> $column_id,
	   		'from_appid'		=> $this->user['appid'],
			'from_appname'		=> $this->user['display_name'],
			'user_id'			=> $video['_user_id'] ? $video['_user_id'] : ($this->input['user_id'] ? $this->input['user_id'] : $this->user['user_id']),
			'addperson'			=> $video['_user_name'] ? $video['_user_name'] : ($this->input['user_name'] ? $this->input['user_name'] : $this->user['user_name']),
			'org_id'			=> $this->input['org_id'] ? $this->input['org_id'] : $this->user['org_id'],
			'create_time'		=> $create_time ? $create_time : TIMENOW,
			'update_time'		=> TIMENOW,
			'ip'				=> hg_getip(),
	   		//此处获取来自哪个应用哪个模块(默认来自视频库)
	   		'app_uniqueid'		=> $video['app_uniqueid']?$video['app_uniqueid']:'livmedia',
	   		'mod_uniqueid'		=> $video['mod_uniqueid']?$video['mod_uniqueid']:MOD_UNIQUEID,
		);
		
		if($video['is_time_shift'])
		{
			$sql = "UPDATE " . DB_PREFIX ."vodinfo SET ";	
		}
		else
		{
			$sql = " INSERT INTO ".DB_PREFIX."vodinfo SET ";
		}
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		if($video['is_time_shift'])
		{
			$sql .= ' WHERE id = ' . $video['id'];		
		}	
		$this->db->query($sql);
		if($video['is_time_shift'])
		{
			$vid = $video['id'];	
		}
		else
		{
			$vid = $this->db->insert_id();
		}
		
		//获取一张截图,并且提交到图片服务器
		$img_info = getimage(UPLOAD_DIR . $video['filepath'], TARGET_DIR . $this->dir_info['target_dir'],$vid);		
		if (!$img_info['filename'] && $channel_id)
		{
			if($this->settings['App_live'])
			{
				include_once(ROOT_PATH . 'lib/class/live.class.php');
				$live = new live();
				$channelinfo = $live->getChannelById($channel_id);
				$img_info = $channelinfo[0]['snap'];
			}
		}
		
		$image_info = array(
			'host' 		=> $img_info['host'],
			'dir' 		=> $img_info['dir'],
			'filepath' 	=> $img_info['filepath'],
			'filename' 	=> $img_info['filename'],
			'imgwidth' 	=> $img_info['imgwidth'],
			'imgheight' => $img_info['imgheight'],
		);
		$sql = " UPDATE ".DB_PREFIX."vodinfo SET video_order_id = {$vid},img_info = '".serialize($image_info)."'  WHERE id = {$vid}";
		$this->db->query($sql);
		
		//存储vod_extend表
		if($video['content_id'])
		{
			$extend_data = array(
				'vodinfo_id' => $vid,
				'content_id' => $video['content_id'],
			);
			$sql = " INSERT INTO ".DB_PREFIX."vod_extend SET ";
			foreach ($extend_data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql = trim($sql,',');
			$this->db->query($sql);
		}
		
		//加入日志
		$data['id'] = $vid;
		$this->addLogs('创建视频','',$data,$data['title']);
		return array('vid' => $vid,'img_info' => $image_info);
    }
    
    //上传之后的回调
    private function mediaserverCallback($callback_url = '',$data = array())
    {
    	if(!$callback_url)
    	{
    		return false;
    	}
    	$curl = new curl();
    	return $curl->curl_json($callback_url,$data);
    }
}

$out = new create();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'submit_transcode';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>