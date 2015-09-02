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
    		/*
	    	if($this->input['vod_config_id'])
	    	{
		    	$type_id = $this->input['vod_config_id'];
		    	$condition = " AND type_id=".$type_id;
	    	}
	    	else
	    	{
		    	$condition = " AND is_default=1";
	    	}
	    $vod_config = transcode_config($condition);
	    */
	    
	    $vod_config = transcode_config($this->input['vod_config_id']);
	    $is_file = 0;//标识是否是文件上传
	    $is_url = 0;//标识是否是url提交
	    	if($_FILES)
	    	{
	    		$max_size = ini_get('upload_max_filesize');
	    		if($max_size)
	    		{
		    		if($_FILES['videofile']['size'] > $max_size*1024*1024)
		    		{
		    			$this->errorOutput('上传视频不能超过'.$max_size.'M');
		    		}
	    		}
	    		$is_file = 1;
	    		$this->check_has_videofile($vod_config);
	    		$this->input['filepath'] = $this->dir_info['filepath'];
	    	}
	    	elseif($this->input['url'])//如果是url提交,进行下载
	    	{
	    		//检查url连接
	    		$is_url = 1;
	    		$url = $this->input['url'];
		    	$basename = basename($url);
		    	$r = strpos($basename, '.');//这里需要修改
		    	if(!$r)
		    	{
		    		$this->errorOutput('下载链接不正确');
		    	}
		    	//检查文件格式
				$filetype 	= strtolower(strrchr($basename, '.'));
				$allowtype 	= explode(',', $this->settings['video_type']['allow_type']);
				if (!in_array($filetype, $allowtype))
				{
					$this->errorOutput(FORBIDTYPE);
				}
				
				if(!$this->input['title'])
				{
					$this->input['title'] = $basename;
				}
				//构建下载目录
				$dir = UPLOAD_DIR.'url/'.hg_build_dowload_dir();
				
				//构建目标视频目录
				$v_videodir 	= create_video_dir();
				$vod_dir_names  = $v_videodir[0];
				$video_dir 		= $v_videodir[1];
				if (!hg_mkdir(TARGET_DIR . $video_dir) || !is_writeable(TARGET_DIR . $video_dir))
				{
					$this->errorOutput(NOWRITE);
				}
				$this->dir_info['target_dir'] = $video_dir;
				$this->dir_info['output_filename'] = $vod_dir_names . '';
				$this->dir_info['output_format'] = $vod_config['output_format'];
				
				//开始下载
				$re = $this->download_from_url($url,$dir);
				if($re)
				{
					$this->input['filepath'] = strrchr($re,'url');
				}
				else
				{
					$this->errorOutput('下载失败');
				}
				
				
				//如果不需要转码,拷贝一份到目标视频目录(注: IS_TRANSCODE_URL只用于url提交)
				if(!defined('IS_TRANSCODE_URL'))
				{
					//$source = $dir.basename($url);
					$dest = TARGET_DIR.$video_dir.$vod_dir_names.strrchr(basename($url),'.');
					$re = copy($re, $dest);
					if(!$re)
					{
						$this->errorOutput('复制失败');
					}
				}
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
				if ($this->settings['video_cloud'] && $this->settings['video_file_cloud'] == 1)
				{
					include(CUR_CONF_PATH . 'lib/cloud/' . $this->settings['video_cloud'] . '.php');
					$cloud = new $this->settings['video_cloud']();
					$uparray = array(
						'name' =>$this->input['title'],
						'tmp_name' => UPLOAD_DIR . $this->input['filepath'],
						'size' => filesize(UPLOAD_DIR . $this->input['filepath']),
					);
					$cloud->setFiles($uparray);
					$cloud->setInput($this->input);
					$cloud->setSettings($this->settings);
					$ret = $cloud->upload();
					$videoinfo = $cloud->getVideoInfo();
					$this->input['content_id'] = $videoinfo['content_id'];
					$this->input['extend_data'] = $videoinfo['extend_data'];
					$this->input['notranscode'] = $videoinfo['notranscode'];
					$this->dir_info['original'] = $original;
					if ($ret != 'success')
					{
						$this->errorOutput($ret);
					}
				}
				else
				{
	    			$this->create_dir($vod_config);
	    		}
	    	}
	    	
	    $video 		= $this->input;
	    $v_vinfo   	= $this->storage_data($video,$vod_config);
	    //如果不需要转码,则直接返回
	    if(!defined('IS_TRANSCODE_URL') && $is_url)
	    {
	    	//将转码状态置为1
			$sql = "UPDATE " .DB_PREFIX. "vodinfo SET status=1 WHERE id = '" .$v_vinfo['vid']. "'";
			$this->db->query($sql);
	    	$this->addItem($v_vinfo);
			$this->output();
	    }
	    
	    $vid 		= $v_vinfo['vid'];
	    $img_info 	= $v_vinfo['img_info'];

	    if($video['audit_auto'])//如果是自动审核,回调增加一个参数
		{
			$this->settings['App_mediaserver']['extends'] = $video['audit_auto'];
		}
		else
		{
			$defaultstate = $this->get_status_setting('create');
			if ($defaultstate)
			{
				$defaultstate = 2;
			}
			$this->settings['App_mediaserver']['extends'] = $defaultstate;
		}
		

		//传到转码服务器的duration参数的单位是秒，所以此处因为接受外部的时间单位是ms
		if (!$this->input['notranscode'])
		{
			if($is_file || $is_url)
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
			else if($data['config']['water_mark'] && $video['water_pos'])//如果用户选的是系统水印并且选择了位置
			{
				$_pos = explode(',',$video['water_pos']);
				$data['config']['water_mark_x'] = $_pos[0];
				$data['config']['water_mark_y'] = $_pos[1];
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
			
			if(!is_dir(UPLOAD_DIR . 'water/'))
			{
				hg_mkdir(UPLOAD_DIR . 'water/');
			}
			file_put_contents(UPLOAD_DIR . 'water/' . $vid . '.json' ,json_encode($_param));
	
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
	    }
		if($return['return'] == 'fail')
		{
			$sql = " UPDATE " . DB_PREFIX ."vodinfo SET status = -1 WHERE id = {$vid}";
			$this->db->query($sql);
			hg_do_transcode_fail($data,$vid);//将转码失败的信息记录下来
			$error_info = array(
				'return' 	=> 'success',//上传成功
				'transcode' => 'fail',//转码失败
				'tran_server'=> $tran_server, //所使用的转码服务器信息
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
				'tran_server'=>$tran_server, //所使用的转码服务器信息			
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
			
			//可以接受用户传递的callback，这个callback是在转码完成之后回调
			if($video['after_callback_url'])
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
				}
				
				//在data目录产生文件记录这个url
				$after_dir = DATA_DIR . 'after_callback_url/';
				if (!hg_mkdir($after_dir) || !is_writeable($after_dir))
				{
					$this->errorOutput(NOWRITE);
				}
				file_put_contents($after_dir . $vid . '.url',$video['after_callback_url']);
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
		$original 	= rawurldecode($_FILES['videofile']['name']);
		if ($this->settings['video_cloud'] && $this->settings['video_file_cloud'] == 1)
		{
			include(CUR_CONF_PATH . 'lib/cloud/' . $this->settings['video_cloud'] . '.php');
			$cloud = new $this->settings['video_cloud']();
			$cloud->setFiles($_FILES['videofile']);
			$cloud->setInput($this->input);
			$cloud->setSettings($this->settings);
			$ret = $cloud->upload();
			$videoinfo = $cloud->getVideoInfo();
			$this->input['content_id'] = $videoinfo['content_id'];
			$this->input['extend_data'] = $videoinfo['extend_data'];
			$this->input['notranscode'] = $videoinfo['notranscode'];
        	$this->dir_info['original'] = $original;
			if ($ret != 'success')
			{
    			$this->errorOutput($ret);
			}
			return 1;
		}
		//判断空间够不够存放视频
    	if(function_exists('disk_free_space'))
    	{
    		if($_FILES['videofile']['size'] > disk_free_space(UPLOAD_DIR))
    		{
    			$this->errorOutput(DISC_SPACE_NOT_ENOUGH);
    		}
    	}
				
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
		return 0;
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
		/************创建视频的权限控制**********/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$prms['_action'] = 'create';
			$prms['node'] = $video['vod_sort_id'];
			if(!$this->verify_self_prms($prms))
			{
				$this->errorOutput('NO_PRIVILEGE');
				//$this->addItem(array('return' => 'fail'));
				//$this->output();
			}
		}
		foreach ($video AS $k => $v)
		{
			if(is_string($v))
			{
				$video[$k] = rawurldecode($v);
			}
		}
	   $data = array(
			'cur_clarity'    	=> $vod_config['unique_id'],//记录当前采用的默认的清晰度标识
			'title'    			=> $video['title']?$video['title']:$this->dir_info['original'],
			'source'  	 		=> $video['source'],
			'subtitle' 			=> $video['subtitle'],
			'keywords' 			=> $video['keywords'],
			'weight' 			=> $video['weight'],
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
	   		'morebitrate_config_id' => $video['vod_config_id'], //多码流配置id
	   		'template_sign'		=> $video['template_sign'], //叮当最佳样式id
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
        
        //记录发布库栏目分发表
        $this->update_pub_column($vid, $video['column_id']);
        //记录发布库栏目分发表
        
        if($video['index_pic'])
        {
	        $material = new material();
	    	$img_info = $material->localMaterial($video['index_pic'],$vid);
	    	$img_info = $img_info[0];
        }
        else
        {
			//获取一张截图,并且提交到图片服务器
			$img_info = getimage(UPLOAD_DIR . $video['filepath'], TARGET_DIR . $this->dir_info['target_dir'],$vid);			
			if (!$img_info || !$img_info['filename'])
			{
				if($this->settings['App_live'] && $channel_id)
				{
					include_once(ROOT_PATH . 'lib/class/live.class.php');
					$live = new live();
					$channelinfo = $live->getChannelById($channel_id,1,1);
					if($channelinfo && $channelinfo[0] && $channelinfo[0]['snap'])
					{
						$img_info = $channelinfo[0]['snap'];
					}
				}
			}	        
        }
		
		if($img_info && is_array($img_info))
		{
			$image_info = array(
				'host' 		=> $img_info['host'],
				'dir' 		=> $img_info['dir'],
				'filepath' 	=> $img_info['filepath'],
				'filename' 	=> $img_info['filename'],
				'imgwidth' 	=> $img_info['imgwidth'],
				'imgheight' => $img_info['imgheight'],
			);
		}
		else 
		{
			$image_info = array();
		}
		
		$sql = " UPDATE ".DB_PREFIX."vodinfo SET video_order_id = {$vid},img_info = '".serialize($image_info)."'  WHERE id = {$vid}";
		$this->db->query($sql);
		
		//存储vod_extend表
		if($video['content_id'])
		{
			$extend_data = array(
				'vodinfo_id' => $vid,
				'content_id' => $video['content_id'],
				'extend_data' => $video['extend_data'],
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
    
    //控制权限的
    private function verify_self_prms($data = array())
	{
		$action  = $data['_action'] ? $data['_action'] : $this->input['a'];
		if ($this->user['user_id'] < 1)
		{
			return false;
		}
		
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			return true;
		}
		
		if(!in_array($action,(array)$this->user['prms']['app_prms']['livmedia']['action']))
		{
			return false;
		}
		
		if($data['id'])
		{
			$manage_other_data = $this->user['prms']['default_setting']['manage_other_data'];
			if(!$manage_other_data)
			{
				if($this->user['user_id'] != $data['user_id'])
				{
					return false;
				}
			}
			//1 代表组织机构以内
			if($manage_other_data == 1 && $this->user['slave_org'])
			{
				if(!in_array($data['org_id'], explode(',', $this->user['slave_org'])))
				{
					return false;
				}
			}
		}
		
		if($data['node'])
		{
			$auth_prms_nodes = $this->get_childs_nodes();
			if(!in_array($data['node'],$auth_prms_nodes))
			{
				return false;
			}
		}
		return true;
	}
	
	//获取权限允许的节点
	private function get_childs_nodes()
	{
		$prms_nodes = implode(',',$this->user['prms']['app_prms']['livmedia']['nodes']);
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
    	$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$prms_nodes);
		$curl->addRequestData('a','get_childs_nodes');
		$nodes = $curl->request('vod.php');
		return $nodes[0];
	}
    
    
    //修改文稿发布栏目分发表
    public function update_pub_column($ids, $column_ids) {
        if (!$ids) {
            return false;
        }
        $sql = "DELETE FROM " . DB_PREFIX . "pub_column WHERE aid IN(" . $ids . ")";
        $this->db->query($sql);
        
        if ($column_ids) {
            $arr_ids = explode(',', $ids);
            $ar_column_ids = explode(',', $column_ids);
            
            $sql = "INSERT INTO " . DB_PREFIX . "pub_column (aid, column_id) VALUES";
            $space = '';
            foreach ($arr_ids as $k => $v) {
                foreach ($ar_column_ids as $kk => $vv) {
                    $sql .= $space . " ('" . $v . "', '" . $vv . "')";
                    $space = ',';
                }
            }
            $this->db->query($sql);            
        }
        return true;
    }
    
    
    //下载视频
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