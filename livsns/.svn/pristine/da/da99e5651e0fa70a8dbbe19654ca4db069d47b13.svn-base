<?php
require_once './global.php';
define('SCRIPT_NAME', 'cloudvod');
define('MOD_UNIQUEID', 'cloudvod');  //模块标识
require_once './lib/upyun.class.php';
require_once ('./lib/AvPretreatment.php');
class cloudvod extends appCommonFrm
{
	public function __construct()
	{
		$this->getUserExtendInfo = true;
		parent::__construct();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function upload()
	{
		$upyun = new UpYun($this->user['extend']['bucket_name']['value'], SPACEOPERATORS, SPACEOPERATORSPASSWORD);
		try {
			//exit($this->user['extend']['bucket_name']['value']);
		    $fh = fopen('sample.jpg', 'rb');
		    $rsp = $upyun->writeFile('/demo/bmw.flv', $fh, True);   // 上传图片，自动创建目录
		    fclose($fh);
			
		    var_dump($rsp);
		    
		    /**
		     * md5上传
		     * Enter description here ...
		     * @var unknown_type
		     
		    $opts = array(
		        UpYun::CONTENT_MD5 => md5(file_get_contents("sample.jpg"))
		    );
		    $fh = fopen('sample.jpg', 'rb');
		    $rsp = $upyun->writeFile('/demo/sample_md5.jpeg', $fh, True, $opts);   // 上传图片，自动创建目录
		    fclose($fh);
		    */
		}
		catch(Exception $e) {
		    echo $e->getCode();
		    echo $e->getMessage();
		}
	}
	public function getList()
	{
		$upyun = new UpYun($this->user['extend']['bucket_name']['value'], SPACEOPERATORS, SPACEOPERATORSPASSWORD);
		
		try {
		    $list = $upyun->getList('/');
		    var_dump($list);
		}
		catch(Exception $e) {
		    echo $e->getCode();
		    echo $e->getMessage();
		}
	}
	public function audit()
	{
		$params = array(
			'id'=>$this->input['id'],
			'audit'=>$this->input['status'], //1审核通过 0打回
			'a'=>'audit',
        );
        $result = $this->request_livmedia($params);
        $opration = $params['audit'] ? '发布视频' : '打回视频';
        $this->addLogs($opration, null, $result, '[云视频'.$params['id'].']');
        if(!empty($result))
        {
        	if($params['audit'])
        	{
        		analytic_statistics('audit', $this->user['user_id']);
        	}
        	else
        	{
        		analytic_statistics('callback', $this->user['user_id']);
        	}
        }
        else
        {
        	$this->errorOutput('转码失败或转码中视频无法发布');
        }
        $this->addItem($result[0]);
        $this->output();
	}
	public function delete()
	{
		//获取需要删除的视频信息
		$params = array(
				'id'=>$this->input['id'],
				'a'=>'show',
            );
        $vodinfo = $this->request_livmedia($params);
        
		$filepath = array();
		
        if(is_array($vodinfo) && !empty($vodinfo))
        {
        	foreach($vodinfo as $val)
        	{
        		$fileext = pathinfo($val['source_filename']);
        		$filepath[$val['id']] = array(
        			'source'=>$val['source_path'] . '/' . $val['source_filename'],//视频原文件
        			'mp4'=>$val['source_path'] . '/' . $val['source_filename'],//mp4文件
        			'm3u8'=>'/' . $val['video_path'],//m3u8文件
        			//'mp42'=>$val['source_path'] . '/' . $fileext['filename'] . '_returninfotrue.'.$fileext['extension'],
        			//图片文件
        			'png'=>$val['source_path'] . '/' . $fileext['filename'] . '_n1_onefalse.'.$fileext['extension'].'1.png'
        		);
        		//file_put_contents(CACHE_DIR.'debug.txt', var_export($this->user['extend'],1));exit;
        	}
        }
		if(!$filepath)
		{
			$this->errorOutput('无效视频数据');
		}
		//删除视频信息
		$params = array(
				'id'=>$filepath ? implode(',', array_keys($filepath)) : '',
				'a'=>'delete',
            );
        $responce = $this->request_livmedia($params);
        $responce = $responce[0];
        //删除又拍视频物理文件
       	$this->addLogs('删除视频', $responce, null, '云平台视频删除');
       	if(is_array($responce) && !empty($responce))
       	{
       		foreach ($responce['id'] as $video_id)
       		{
       			if($filepath[$video_id])
       			{
       				foreach($filepath[$video_id] as $t=>$file)
       				{
       					if($t=='m3u8')
       					{
       						$fileurl = 'http://'.$this->user['extend']['domain']['value'] . '/'.$file;
       						$ts_str = file_get_contents($fileurl);
       						preg_match_all('/^\/.*?.ts$/mi', $ts_str, $ts_array);
       						if(is_array($ts_array))
       						{
       							$ts_array = $ts_array[0];
       							foreach ($ts_array as $ts)
       							{
       								$this->deleteFile($ts);
       							}
       						}
       						$this->deleteFile($file);
       					}
       					else
       					{
       						$this->deleteFile($file);
       					}
       				}
       			}
       			@unlink(CACHE_DIR .$video_id.'.data');//删除任务临时文件
       		}
       		analytic_statistics('delete', $this->user['user_id']);
       	}       	
       	$this->addItem($responce);
       	$this->output();
	}
	public function detail()
	{
		$params = array(
			'id'=>$this->input['id'],
			'a'=>'detail',
         );
        $result = $this->request_livmedia($params);
        $this->addItem($result[0]);
        $this->output();
	}
	public function get_video()
	{
		$params = array(
			'id'=>$this->input['id'],
			'a'=>'detail',
         );
        $result = $this->request_livmedia($params, false);
        $this->addItem($result[0]);
        $this->output();
	}
	public function update()
	{
		$params = array(
                'a' => 'update',
				'id'=>$this->input['id'],
				'title'=>$this->input['title'],
				'author'=>$this->input['author'],
	            'comment'=>$this->input['comment'],
	            'keywords'=>$this->input['keywords'],
				'source'=>$this->input['source'],
            );
        if($this->input['img_src_cpu'])
        {
        	$params['img_src_cpu'] = urldecode($this->input['img_src_cpu']);
        }
        $result = $this->request_livmedia($params);
        $this->addLogs('视频更新', null, $result[0], '云视频更新['.$params['id'].']');
        $this->addItem($result[0]);
        $this->output();
	}
	public function deleteFile($filepath)
	{
		$upyun = new UpYun($this->user['extend']['bucket_name']['value'], SPACEOPERATORS, SPACEOPERATORSPASSWORD);
		//file_put_contents(CACHE_DIR . 'delete.txt', var_export($this->input,1), FILE_APPEND);
		
		try {
		    $result = $upyun->deleteFile($filepath);
		    ///$this->addItem_withkey('error', 0);
		    return true;
		}
		catch(Exception $e) {
		    //$this->addItem_withkey('error', '1');
		    
			return false;
		}
	}
	private function request_livmedia($params = array(), $admin=true)
	{
		if($admin)
		{
			$admin_dir = 'admin/';
		}
		else
		{
			$admin_dir = '';
		}
		$curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . $admin_dir);
		$curl->initPostData();
		$curl->setSubmitType('post');
		$params['_outercall'] = 1;
        if($params)
        {
	        foreach ($params as $k=>$v)
	        {
	        	$curl->addRequestData($k, $v);
	        }
        }
        $file = 'vod.php';
        if(in_array($params['a'], array('audit', 'update', 'delete')))
        {
        	$file = 'vod_update.php';
        }
        $reponses = $curl->request($file);
        if($result['ErrorCode'] || $result['ErrorText'])
    	{
    		$this->errorOutput($result['ErrorCode'] . $result['ErrorText']);
    	}
    	if(!is_array($reponses))
    	{
    		$this->errorOutput("内部错误!");
    	}
        return $reponses;	
	}
	public function show()
	{
		$curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . 'admin/');
		$curl->initPostData();
		$curl->setSubmitType('post');
		
        $params = $this->search_conditions();
        $params['_outercall'] = 1;
        foreach ($params as $k=>$v)
        {
        	$curl->addRequestData($k, $v);
        }
        
        $reponses = $curl->request('vod.php');
        if($result['ErrorCode'] || $result['ErrorText'])
    	{
    		$this->errorOutput($result['ErrorCode'] . $result['ErrorText']);
    	}
        $curl->addRequestData('a', 'count');
        $total = $curl->request('vod.php');
        $data['data'] = $reponses;
        $data['total'] = $total['total'];
        $data['settings'] = $this->settings['form_api_param'];
        $this->addItem($data);
        $this->output();
	}
	protected function search_conditions()
	{
		$conditions = array('_id'=>DEFAULT_LEIXING);
		//trans_status=-2&date_search=2&title=d&offset=20
		if(trim($this->input['title']))
		{
			$conditions['title'] = urldecode(trim($this->input['title']));
		}
		if(intval($this->input['date_search']))
		{
			$conditions['date_search'] = $this->input['date_search'];
		}
		if(isset($_REQUEST['trans_status']))
		{
			$conditions['trans_status'] = $this->input['trans_status'];
		}
		if($this->input['start_time'])
		{
			$conditions['start_time'] = $this->input['start_time'];
		}
		if($this->input['end_time'])
		{
			$conditions['end_time'] = $this->input['end_time'];
		}
		if(intval($this->input['offset']))
		{
			$conditions['offset'] = $this->input['offset'];
		}
		if(intval($this->input['count']))
		{
			$conditions['count'] = $this->input['count'];
		}
		if(intval($this->input['id']))
		{
			$conditions['id'] = $this->input['id'];
		}
		return $conditions;
	}
	public function get_player_code()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput('无效视频id');
		}
		$diy_data = array();
		if($this->input['diy'])
		{
			$diy_data = json_decode($this->input['diy'],1);
		}
		//获取播放器参数
		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/');
		$curl->initPostData();
		$data = array(
		'flag'=>'player',
		//'status'=>1,
		'admin_id'=>$this->user['user_id'],
		);
		foreach($data as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		$paramters = $curl->request('preferences.php');
		$all_player = array();
		if(!$paramters || $paramters['ErrorCode'] || $paramters['ErrorText'])
		{
			$paramters = array(
				'width'=> $diy_data['width'] ? $diy_data['width'] : 640,
				'height'=> $diy_data['height'] ? $diy_data['height'] : 480,
				'auto_play'=> $diy_data['auto_play'] ? $diy_data['auto_play'] : 0,
				'config_xml'=> $this->settings['player']['config_xml'],
			);
		}
		else
		{
			$default_player = array();
			$all_player = $paramters;
			foreach($paramters as $val)
			{
				if($val['status'] == 1)
				{
					$default_player = $val;
				}
				if($diy_data && ($val['id'] == $diy_data['player_id']))
				{
					$default_player = $val;
					break;
				}
			}
			if(!$default_player)
			{
				$default_player = $paramters[0];
			}
			$default_config_xml = $this->settings['player']['config_xml_prefix'] . $this->user['user_id'] . '_' . $default_player['id'] . 'vod.xml';
			$paramters = $default_player['admin_settings'];
			$paramters = array(
				'width'=> $diy_data['width'] ? $diy_data['width'] : $paramters['player_width'],
				'height'=> $diy_data['height'] ? $diy_data['height'] : $paramters['player_height'],
				'auto_play'=> isset($diy_data['auto_play']) ? $diy_data['auto_play'] : $paramters['auto_play'],
				'config_xml'=>$default_config_xml,
			);
		}
		$player_code = player_code($id, $paramters);
		$player_code['player'] = $all_player;//追加所有播放器用于选择
		if(!$diy_data)
		{//只有首次加载返回二维码
			$qrcode = $this->get_qrcode(array('content'=>$player_code['url']));
			$player_code['qrcode'] = is_array($qrcode) ? hg_fetchimgurl($qrcode) : '';
		}
		if($player_code)
		{
			$this->addItem($player_code);
			$this->output();
		}
	}
	public function get_qrcode($data=array())
	{
		include_once(ROOT_PATH . 'lib/class/qrcode.class.php');
		$qrcode_server = new qrcode();
		return $qrcode_server->create($data,-1);
	}
	public function upload_material()
	{
		if(!$_FILES['Filedata'])
		{
			$this->errorOutput("素材内容不能为空");
		}
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$material_server = new material();
		$responce = $material_server->addMaterial($_FILES);
		if($responce['ErrorCode'] || $responce['ErrorTexts'])
		{
			$this->errorOutput($responce['ErrorCode'] . $responce['ErrorTexts']);
		}
		$this->addItem($responce);
		$this->output();
	}
	public function get_transcode_progress_bar()
	{
		$video_id = $this->input['id'] ? explode(',', $this->input['id']) : array();
		if(!$video_id)
		{
			return;
		}
		$_task_ids = array();
		$completed = array();
		foreach ($video_id as $id)
		{
			$file = CACHE_DIR .$id.'.data';
			
			if(!file_exists($file))
			{
				$completed[$id] = 100;
			}
			else
			{
				$task_ids = @file_get_contents($file);
				
				if($task_ids)
				{
					$task_ids = explode('|', $task_ids);
					if($task_ids[0] == '*')
					{
						$completed[$id] = 100;
					}
					else
					{
						$_task_ids[$id] = $task_ids[0];
					}
				}
			}
		}
		$output = array();
		if($_task_ids)
		{
			try {
				$sugar = new \Sugar\AvPretreatment(SPACEOPERATORS, SPACEOPERATORSPASSWORD, TRANSCODE_PROGRESS_BAR);//操作员的帐号密码
		    
			    $data = array(
			        'bucket_name' => $this->user['extend']['bucket_name']['value'],                   //空间名
			        'task_ids'=>implode(',', $_task_ids),
			    );
				//返回对应的任务ids
		        $progress = $sugar->request($data, 3,'GET');
		        
		        if(is_array($progress['tasks']) && !empty($progress['tasks']))
		        {
		        	foreach ($progress['tasks'] as $tid=>$progress_value)
		        	{
		        		$output[array_search($tid, $_task_ids)] = $progress_value;
		        	}
		        }
		    } catch(\Exception $e) {
		        //echo "request failed:", $e->getMessage();
		        $this->addItem_withkey('error', $e->getMessage());
		        $this->output();
		    }
		}
		
	 	if($output)
	 	{
	 		foreach ($output as $vid=>$val)
	 		{
	 			$completed[$vid] = $val;
	 		}
	 	}
	 	
	    $this->addItem_withkey('progress', $completed);
	    $this->output();
    	
	}
}
include(ROOT_PATH . 'excute.php');