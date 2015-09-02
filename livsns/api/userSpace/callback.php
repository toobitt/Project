<?php
require_once './global.php';
define('SCRIPT_NAME', 'callback');
define('MOD_UNIQUEID', 'callback');  //模块标识
require_once './lib/upyun.class.php';
require_once ('./lib/AvPretreatment.php');
require_once ('./lib/CallbackValidation.php');

class callback extends appCommonFrm {

	public function __construct()
    {
    	$this->getUserExtendInfo=true;
        parent::__construct();
    }
	public function __destruct()
    {
        parent::__destruct();
    }
	public function show()
	{
		if(!$this->input['callback_type'])
		{
			$this->addItem_withkey('error', 'Unkown Callback Type');
			$this->output();
		}
		$callback_method = '_' . $this->input['callback_type'] . '_callback';
		if(!method_exists($this, $callback_method))
		{
			$this->addItem_withkey('error', 'Method Not Exists');
			$this->output();
		}
		$this->$callback_method();
	}
	public  function _upload_callback()
	{
        $reponses = $this->input['return_type'] == 'sync' ? $_GET : $_POST; //接受回调返回的数据
        log2file($this->user, 'debug', '上传回调数据', $this->input, $reponses);
        if($reponses['code'] != '200')
        {
        	if($this->input['return_type'])
        	{
        		switch($this->input['return_type']){
        		case 'sync':
        			{
	        			if($this->input['return_url'])
		        		{
		        			$url_info = parse_url(urldecode($this->input['return_url']));
			                //header('HTTP/1.1 302 Moved Permanently');
			                header("Location:" . $url_info['scheme'] . '://' . $url_info['host'] . $url_info['path'] . '?' . ($url_info['query'] ? $url_info['query'] . '&': '') . 'data='.urlencode(json_encode($reponses)));
			                exit;
		        		}
		        		elseif($this->input['data_format'] == 'jsonp')
		        		{
		        			exit($this->input['func_name'] . '(' .json_encode($reponses).')');
		        		}
		        		else
		        		{
		        			exit(json_encode($reponses));
		        		}
        			} 
        		case 'asyn':
        			{
        				
        			}
        		
        		default:
        			{
        				exit;
        			}
        		}
        	}
        	else
        	{
        		log2file($this->user, 'error', '上传失败', $this->input, $reponses);
        		exit;
        	}
        	//file_put_contents(CACHE_DIR . 'callback_error.txt', var_export($_POST, 1), FILE_APPEND);
        }
        $reponses['access_token'] = $this->input['access_token'];
        
        //视频入库
        $ext_param = parseQueryString(rawurldecode($reponses['ext-param']));
        $reponses['ext-param'] = $ext_param;
        $filepath = pathinfo($reponses['url']);
		$params = array(
            'title' => $ext_param['title'] ? $ext_param['title'] : '精彩视频',
			'chain_m3u8'=>DEFAULT_M3U8,
			'access_token'=>$this->input['access_token'],
//			'index_pic'=>DEFAULT_IMG,
			'status'=>0,
			'source_filename'=>basename($reponses['url']),
			'source_path'=>$filepath['dirname'],
			'a'=>'create',
        );   
        $curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . 'admin/');
        $curl->initPostData();
        $curl->setSubmitType('post');
        foreach ($params as $key=>$val)
        {
        	$curl->addRequestData($key, $val);
        }
        $result = $curl->request('vod_update.php');
        
        log2file($this->user, 'debug', '视频数据入库', $params, $result);
        
        $result = $result[0];
        if($result['error'] == 'repeat')
        {
        	log2file($this->user, 'error', '重复回调', $params, $reponses);
        	return;
        }
        $reponses['video_id'] = $video_id = $result['id'];
        
        //更新视频库数据，同时提交转码
        $result = $this->transcode($reponses);
        //file_put_contents(CACHE_DIR . 't.txt', var_export($reponses, 1), FILE_APPEND);
        
        if($result)
        {
        	foreach ($result as $key=>$tid)
        	{
        		$this->cache_task2file($video_id, 'write', $tid, true);
        	}	
        }
        analytic_statistics('upload', $this->user['user_id']);

        //上传回调应用
        if($video_id && $reponses['ext-param']['client_id'])
        {
        	unset($params['a']);
        	$params['id'] = $video_id;

        	//同步返回
        	if($this->input['return_type'] == 'sync')
        	{
        		if($this->input['return_url'])
        		{
        			$url_info = parse_url(urldecode($this->input['return_url']));
	                //header('HTTP/1.1 302 Moved Permanently');
	                header("Location:" . $url_info['scheme'] . '://' . $url_info['host'] . $url_info['path'] . '?' . ($url_info['query'] ? $url_info['query'] . '&': '') . 'data='.urlencode(json_encode($params)));
	                exit;
        		}
        		elseif($this->input['data_format'] == 'jsonp')
        		{
        			exit($this->input['func_name'] . '(' .json_encode($params).')');
        		}
        		else
        		{
        			exit(json_encode($params));
        		}
        	}
        	//异步
        	if($this->input['return_type'] == 'asyn')
        	{
        		//先记录到数据库 通过计划任务调用
        		$data = array(
        		'client_id'=>$reponses['ext-param']['client_id'],
        		'data'=>json_encode($params),
        		'update_time'=>TIMENOW,
        		//'times'=>3,
        		);
        		$sql = 'INSERT INTO ' . DB_PREFIX . 'app_upload_queue SET ';
        		foreach ($data as $key => $value) {
        			$sql .= $key . '="'.addslashes($value).'",';
        		}
        		//file_put_contents(CACHE_DIR . 'debug2.txt', $sql);
        		$this->db->query(trim($sql, ','));

        	}
        }
	}
	protected function get_mp4_file_name($fileext)
	{
		return $fileext['filename'] . '_returninfotrue_vb500.'.$fileext['extension'];
	}
	public function transcode($param)
	{
		if(!$param)
		{
			return false;
		}
		$filepath = $param['url'];		
		
	    $sugar = new \Sugar\AvPretreatment(SPACEOPERATORS, SPACEOPERATORSPASSWORD);//操作员的帐号密码
	    
	    $data = array(
	        'bucket_name' => $this->user['extend']['bucket_name']['value'],                   //空间名
	        'source' => $filepath,   //视频地址
	        'notify_url' =>  $this->settings['form_api_param']['notify-url'] . 'callback_type=transcode&video_id='.$param['video_id'].'&access_token='.$this->user['token'].'&upload_type='.$param['ext-param']['upload_type'],         //回调通知地址
	        'tasks' => array(                           //任务 索引0
	            array(
	                'type' => 'hls',
	                'hls_time' => 6,
	                'bitrate' => DEFAULT_BITRATE,
	                'rotate' =>  'auto',
	           //     'format' => 'mp4',
	            ),
	            array(//任务 索引1
	                'type' => 'thumbnail',
	                'thumb_single' => false,
	                'thumb_amount' => 1,
	            	'thumb_start'=>DEFAULT_IMG_START,
	                'format' => 'png'
	            ),
	            array(//任务 索引2
	                'type' => 'video',
	            	'return_info'=> true,
	            	'bitrate'=>DEFAULT_BITRATE,
	            	//'rotate'=>'auto',
	            ),
	        )
	    );
	    if($param['ext-param']['client_id'])
	    {
	    	$data['notify_url'] .= '&client_id='.$param['ext-param']['client_id'];
	    }
	    //file_put_contents(CACHE_DIR . 'data.txt', var_export($data, 1), FILE_APPEND);
    	try {
	        //返回对应的任务ids
	        $task_ids = $sugar->request($data);
	        log2file($this->user, 'debug', '提交转码任务', $data,$task_ids);
	        return $task_ids;
	        
	    } catch(\Exception $e) {
	        //echo "request failed:", $e->getMessage();
	        //$this->addItem_withkey('error', $e->getMessage());
	        //$this->output();
	        log2file($this->user, 'error', $e->getMessage(), $data);
	    }
	}
	protected function cache_task2file($vid = '', $op = 'read', $content='', $append=false)
	{
		$return = array();
		if(!$vid)
		{
			return $return;
		}
		if($op == 'read')
		{
			$content = file_get_contents(CACHE_DIR .$vid.'.data');
			return $content = explode('|', trim($content, '|'));
		}
		if($op == 'write')
		{
			if($append)
			{
				file_put_contents(CACHE_DIR . $vid.'.data', $content . '|', FILE_APPEND);
			}
			else
			{
				file_put_contents(CACHE_DIR . $vid.'.data', $content);
			}
		}
	}
	public  function _transcode_callback()
	{
		//file_put_contents(CACHE_DIR . 'transcode_callback.txt',var_export($_GET, 1), FILE_APPEND);
		$response = $_POST;
        log2file($this->user, 'debug', '转码回调数据', $this->input, $response);
		
		$get  = $_GET;
		
		//回调签名认真callback_validation
		$isvalid =  $this->callback_validation();
		
		if(!$isvalid['status'])
		{
			log2file($this->user, 'error', '验证签名错误', $response, $isvalid);
			//file_put_contents(CACHE_DIR .$response['task_id'].'_error.txt', var_export($response, 1));
			exit;
		}
		$vparams = array(
                'access_token' => $this->user['token'],
				'id'=>$get['video_id'],
				'status'=>0,
            );
		$curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . 'admin/');
        $curl->initPostData();
        $curl->setSubmitType('post');
        foreach ($vparams as $key=>$val)
        {
        	$curl->addRequestData($key, $val);
        }
        $vodinfo = $curl->request('vod.php');
		//file_put_contents(CACHE_DIR . 'v.txt', var_export($vodinfo, 1), FILE_APPEND);
        $vodinfo = $vodinfo[0];
		if(!$vodinfo['id'])
        {
        	log2file($this->user, 'error', '获取视频数据失败', $vparams, $vodinfo);
        	return;
        }
        //更新视频的图片和m3u8地址
        $task_ids = $this->cache_task2file($this->input['video_id']);
		$domain = 'http://' . $this->user['extend']['domain']['value'];
		$file  = $domain . $response['path'][0];
		$repeat_flag = true;
		if($response['task_id'] == $task_ids[0])//视频
		{
			$params = array(
				'chain_m3u8'=>$file,
				'status'=>1,
            );
			$log_title = '流地址';
            $repeat_flag = false;
            //转码完成之后删除根据配置是否删除原文件
            if((defined('UPLOAD_TYPE') && UPLOAD_TYPE) || $get['upload_type'])
            {
            	/*为减少响应时间 进入队列 待计划任务执行
				$upyun = new UpYun($this->user['extend']['bucket_name']['value'], SPACEOPERATORS, SPACEOPERATORSPASSWORD);
				$file_path = $vodinfo['source_path'] . '/' . $vodinfo['source_filename'];
				@$upyun->deleteFile($file_path);
				*/
            	$deleteFile = array(
            	'file_path'=> $vodinfo['source_path'] . '/' . $vodinfo['source_filename'],
            	'bucket_name' => $this->user['extend']['bucket_name']['value'],
            	'create_time' => TIMENOW);
            	$this->delete_file_queue($deleteFile);
            	//file_put_contents(CACHE_DIR .'debug.txt', var_export($deleteFile,1));
            }
		}
		if($response['task_id'] == $task_ids[1])//图片
		{
			$params = array(
				'index_pic'=>$file,
			);
			$log_title = '封面';
			$repeat_flag = false;
		}
		if($response['task_id'] == $task_ids[2])//扩展信息
		{
			$mediainfo = json_decode(base64_decode($response['info']),1);
			log2file($this->user, 'debug', '视频扩展信息', $response,$mediainfo);
			$params = array(
				'duration'=>intval($mediainfo['format']['duration']*1000),//时长
				'totalsize'=>$mediainfo['format']['filesize'],//大小
				'frame_rate'=>$mediainfo['streams'][0]['video_fps'],
				'height'=>$mediainfo['streams'][0]['video_height'],
				'width'=>$mediainfo['streams'][0]['video_width'],
				'bitrate'=>round($mediainfo['format']['bitrate']/1024),
				'sampling_rate'=>$mediainfo['streams'][1]['audio_samplerate'],
				'audio'=>$mediainfo['streams'][1]['codec'],
				'audio_channels'=>$mediainfo['streams'][1]['audio_channels'],
				'video'=>$mediainfo['streams'][0]['codec'],
				'vod_leixing'=>DEFAULT_LEIXING,
			);
			$aspect_z = divisor($params['width'], $params['height']);
			$aspect_x = $params['width']/$aspect_z;
			$aspect_y = $params['height']/$aspect_z;
			$params['aspect'] = $aspect_x . ':' . $aspect_y;
			if($params['audio_channels'] == 1)
			{
				$params['audio_channels'] = 'Front: L';
			}
			if($params['audio_channels'] > 1)
			{
				$params['audio_channels'] = 'Front: L R';
			}
			else
			{
				$params['audio_channels'] = 'Unknown';
			}
			$log_title = '扩展信息';
			$repeat_flag = false;
			
			//扩展信息获取之后删除mp4文件 如果带马流了则文件名不正确 回调入库失败
			$fileext = pathinfo($vodinfo['source_filename']);
			$mp42_path = $this->get_mp4_file_name($fileext);
			$mp42_path = $vodinfo['source_path'] . '/' . $mp42_path;
			//file_put_contents(CACHE_DIR . 'delete.txt', $mp42_path);
			/*
			$upyun = new UpYun($this->user['extend']['bucket_name']['value'], SPACEOPERATORS, SPACEOPERATORSPASSWORD);
			@$upyun->deleteFile($mp42_path);
			*/
			$deleteFile = array(
            	'file_path'=> $mp42_path,
            	'bucket_name' => $this->user['extend']['bucket_name']['value'],
            	'create_time' => TIMENOW);
            $this->delete_file_queue($deleteFile);
		}
		if($repeat_flag)//重复回调的中断
		{
			log2file($this->user, 'error', '重复回调', $response['task_id'], $response['task_id']);
			return;
		}
		$params['access_token']	=	$this->input['access_token'];
		$params['id'] = $this->input['video_id'];
		//file_put_contents(CACHE_DIR . 'param.txt', var_export($params, 1), FILE_APPEND);
		$curl->initPostData();
		$curl->addRequestData('a', 'cloud_vod_update');
        $curl->setSubmitType('post');
        foreach ($params as $key=>$val)
        {
        	$curl->addRequestData($key, $val);
        }
        
        $_result = $curl->request('vod_update.php');
        if(!$_result)
        {
        	log2file($this->user, 'error', '更新视频数据失败', $params, $_result);
        }
        log2file($this->user, 'debug', '更新视频'.$log_title, $params, $_result);
        $this->delete_completed_task($get['video_id'], $response['task_id'], $get['client_id']);
	}
	protected function delete_file_queue($data = array())
	{
		if(!$data)
		{
			return;
		}
		
		$sql = 'INSERT INTO ' . DB_PREFIX . 'delete_file SET ';
		
		foreach($data as $k=>$v)
		{
			$sql .= $k .'="'.$v.'",';
		}
		
		$sql = trim($sql, ',');
		
		$this->db->query($sql);
		
		return true;
	}
	//删除完成的任务id 最终清除零时文件
	protected function delete_completed_task($video_id, $task, $client_id = '')
	{
		log2file($this->user, 'debug', '转码任务回调', array('video_id'=>$video_id,'task_id'=>$task,'client_id'=>$client_id));
		if(!$video_id || !$task)
		{
			return;
		}
		$tasks = $this->cache_task2file($video_id, 'read');
		$tasks[array_search($task, $tasks)] = '*';
		$completed = 0;
		foreach($tasks as $t)
		{
			if($t == '*')
			{
				$completed++;
			}
		}
		if($completed == count($tasks))
		{
			unlink(CACHE_DIR .  $video_id.'.data');
			//如果存在应用对接 则回调应用
			log2file($this->user, 'debug', '转码任务完成', $tasks);
			$this->callback_app_by_client_id($client_id, $video_id);
			return;
		}
		$this->cache_task2file($video_id,'write',implode('|', $tasks));
		return;
	}
	protected function callback_app_by_client_id($client_id = '', $vid = 0)
	{
		log2file($this->user, 'debug', '应用回调', array('video_id'=>$vid, 'client_id'=>$client_id));
		if(!$vid)
		{
			return;
		}
		
		$sql = 'SELECT * FROM ' .DB_PREFIX . 'user_bind_app WHERE user_id = '.$this->user['user_id'];
		$query = $this->db->query($sql);
		$queue_id = array();
		while($row = $this->db->fetch_array($query))
		{
			$data = array(
			//'id'=>null,
			'vid'=>$vid,
			'user_id'=>$this->user['user_id'],
			'app_id'=>$row['app_id'],
			'update_time'=>TIMENOW,
			'level'=> $row['status'],
			);
			$sql = 'INSERT INTO ' . DB_PREFIX . 'distr_app_queue SET ';
			foreach($data as $key=>$val)
			{
				$sql .= "{$key}=\"{$val}\",";
			}
			$sql = trim($sql, ',');
			//file_put_contents(CACHE_DIR . 'debug.txt', $sql);
			$this->db->query($sql);
			$queue_id[$row['app_id']] = $this->db->insert_id();
		}
		if(!$queue_id)
		{
			return;
		}
		if($client_id)
		{
			$data = array(
				//'callback_url'=>$this->input->get_post('redirect_uri'),
				'flag'=>'application',
				'search_field'=>'client_id',
				'client_id'=>$client_id,
				'a'=>'get_specify_settings',
				'access_token' => $this->user['token'],
				//'state'=>$this->input->get_post('state'),
			);
			include_once ROOT_PATH . 'lib/class/curl.class.php';
			$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/');
			$curl->initPostData();
			foreach($data as $k=>$v)
			{
				$curl->addRequestData($k, $v);
			}
			$responce  = $curl->request('preferences.php');
			//file_put_contents(CACHE_DIR . 'app.txt', var_export($responce,1));
			log2file($this->user, 'debug', '获取回调设置', $data, $responce);
			if(is_array($responce[0]))
			{
				$responce = $responce[0];
			}
		
			if(!$responce)
			{
				log2file($this->user, 'error', '获取回调设置失败', $data, $responce);
				return;//应用不存在
			}
			
			//修改自身应用的优先级最高
			$sql = 'UPDAE ' . DB_PREFIX . 'distr_app_queue SET level=3 WHERE id='.$queue_id[$responce['id']];
			$this->query($sql);
		}
		/*
		//如果是m2o视频应用需要包含appid和appkey 因为需要访问mediaserver的外部接口
		$callback_url = $responce['admin_settings']['callback_url'];
		if(!$callback_url)
		{
			log2file($this->user, 'error', '未设置回调地址', $data, $responce);
			return;
		}
		$data = array(
		'access_token'=>$this->user['token'],
		'id'=>$vid,
		'a'=>'detail',
		);
		$curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . 'admin/');
		$curl->initPostData();
		foreach($data as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		$responce  = $curl->request('vod.php');
		
		if(is_array($responce[0]))
		{
			$responce = $responce[0];
		}
		//file_put_contents(CACHE_DIR . 'v.txt', var_export($responce,1) . var_export($data,1) . var_export($this->settings['App_livmedia'],1), FILE_APPEND);
		
		if(!$responce)
		{
			log2file($this->user, 'error', '获取视频数据失败', $data, $responce);
			return;
		}
		$data = array(
		'title' => $responce['title'],
		'subtitle'=> $responce['subtitle'],
		'chain_m3u8'=>$responce['video_m3u8'],
		'keywords'=>$responce['keywords'],
		'index_pic'=>$responce['img_info']['host'].$responce['img_info']['dir'].$responce['img_info']['filepath'].$responce['img_info']['filename'],
		'comment'=>$responce['comment'],
		'author'=>$responce['addperson'],
		'vod_sort_id'=>$responce['vod_sort_id'],
		'duration'=>$responce['duration'],
		'bitrate'=>$responce['bitrate'],
		'a'=>'create',
		);
		$responce = $this->curl_post($callback_url, $data);
		//file_put_contents(CACHE_DIR . 'return.txt', var_export($responce,1) . var_export($data,1) .$callback_url, FILE_APPEND);
		if(!$responce[0]['id'])
		{
			log2file($this->user, 'error', '应用回调失败', $data, $responce);
			//进入错误队列等待重试 提交数据
		}
		*/
	}
	protected function curl_post($url, $postdatas = array())
	{
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $responce = json_decode(curl_exec($ch),true);
	    curl_close($ch);
	    return $responce;
	}	
	public function callback_validation()
	{
		$result = array();
		$validation = new \Sugar\CallbackValidation(new \Sugar\AvPretreatment(SPACEOPERATORS, SPACEOPERATORSPASSWORD));
		if($validation->verifySign()) {
			$result['status'] = 1;
		} else {
			$result['status'] = 0;
		}
		return $result;
	}
}
include(ROOT_PATH . 'excute.php');