<?php
define('MOD_UNIQUEID','movie');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/movie_mode.php');
class movie_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new movie_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		//权限
		$this->verify_content_prms(array('_action'=>'manage'));
		###获取默认数据状态
		$status = $this->get_status_setting('create');
		$data = array(
			'title' => trim($this->input['title']),
			'director' => trim($this->input['director']),
			'main_performer' => trim($this->input['main_performer']),
			'area' => intval($this->input['area']),
			'type' => implode(',',$this->input['type']),
			//'dimension' => $this->input['dimension'], //影片维度
			'duration' => trim($this->input['duration']),
			'release_time' => strtotime($this->input['release_time']),
			'prevue_url' => trim($this->input['prevue_url']), //预告片链接
			'brief' => trim($this->input['brief']),
			'status' => $status ? $status : 0,
			'user_id' => $this->user['user_id'],
			'org_id' => $this->user['org_id'],
			'user_name' => $this->user['user_name'],
			'ip' => hg_getip(),
			'create_time' => TIMENOW,
			'language'	=> trim($this->input['language']),
		);
		
		if(!$data['title'])
		{
			$this->errorOutput(NO_MOVIE_NAME);
		}
		if(!$data['release_time'])
		{
			$this->errorOutput('请选择上映时间');
		}
		//海报
		if ($_FILES['img'])
		{
			$pic_info = $this->mode->add_material($_FILES['img']);
			$index_pic = array(
			    'host' => $pic_info['host'],
			    'dir'  => $pic_info['dir'],
			    'filepath' => $pic_info['filepath'],
			    'filename' => $pic_info['filename'],
			    'imgwidth' => $pic_info['imgwidth'],
			    'imgheight'=> $pic_info['imgheight'],
			);
			$data['index_pic'] = $pic_info ? @serialize($index_pic) : '';
		}
		
		//剧照
		$data['still_id'] = $this->input['still_id'] ? $this->input['still_id'] : '';
		//预告片
		$data['prevue_id'] = $this->input['prevue_id'] ? $this->input['prevue_id'] : '';
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		/**************更新数据权限判断***************/
		$sql = "select * from " . DB_PREFIX ."movie where id = " . $this->input['id'];
		$q = $this->db->query_first($sql);
		$info['id'] = $q['id'];
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage';
		$s = $q['status'];
		$this->verify_content_prms($info);
		/*********************************************/
		###获取默认数据状态
		$status = $this->get_status_setting('update_audit',$s);
		$update_data = array(
			'title' => trim($this->input['title']),
			'director' => trim($this->input['director']),
			'main_performer' => trim($this->input['main_performer']),
			'area' => intval($this->input['area']),
			'type' => implode(',',$this->input['type']),
			'duration' => trim($this->input['duration']),
			'prevue_url' => trim($this->input['prevue_url']), //预告片链接
			'language'	=> trim($this->input['language']),
			'release_time' => strtotime(trim($this->input['release_time'])),
			'brief' => trim($this->input['brief']),
			'status' => $status ? $status : 0,
		);
		if(!$update_data['release_time'])
		{
			$this->errorOutput('请选择上映时间');
		}
		//海报
		if ($_FILES['img'])
		{
			$img['Filedata'] = $_FILES['img']; 
			$pic_info = $this->mode->add_material($_FILES['img']);
			$index_pic = array(
			    'host' => $pic_info['host'],
			    'dir'  => $pic_info['dir'],
			    'filepath' => $pic_info['filepath'],
			    'filename' => $pic_info['filename'],
			    'imgwidth' => $pic_info['imgwidth'],
			    'imgheight'=> $pic_info['imgheight'],
			);
			$update_data['index_pic'] = $pic_info ? @serialize($index_pic) : '';
		}
		
		//剧照
		$update_data['still_id'] = $this->input['still_id'] ? $this->input['still_id'] : '';
		//预告片
		$update_data['prevue_id'] = $this->input['prevue_id'] ? $this->input['prevue_id'] : '';
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$sql = "UPDATE " . DB_PREFIX . "movie SET 
						update_user_name ='" . $this->user['user_name'] . "',
						update_user_id = '".$this->user['user_id']."',
						update_org_id = '".$this->user['org_id']."',
						update_ip = '" . hg_getip() . "', 
						update_time = '". TIMENOW . "' WHERE id=" . $this->input['id'];
			$this->db->query($sql);
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		/**************删除权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'movie WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		/*********************************************/
		//检测该影片是否存在排片信息
		$sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "project WHERE movie_id IN(" .$this->input['id']. ")";
		$q = $this->db->query_first($sql);
		if($q['total'])
		{
			$this->errorOutput('该影片存在排片信息,不能删除');
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		/**************审核权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'movie WHERE id IN ('. $this->input['id'] .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		/*********************************************/
		
		$audit = intval($this->input['audit']);
		if($audit == 2)
		{
			//检测该影片是否存在排片信息
			$sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "project WHERE movie_id IN(" .$this->input['id']. ")";
			$q = $this->db->query_first($sql);
			if($q['total'])
			{
				$this->errorOutput('该影片存在排片信息,不能打回');
			}
		}
		$ret = $this->mode->audit($this->input['id'],$audit);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	/**
	 * 上传图片
	 */
	public function img_upload()
	{
		$picture['Filedata'] = $_FILES['pic'];
		
		if($picture['Filedata'])
		{
			$picture_pic = $this->mode->add_material($picture['Filedata']);
			$img_info = addslashes(serialize($picture_pic));	
		}
		if(!$picture_pic) 
		{
			$this->errorOutput(NO_IMGINFO);
		}
		$sql = " INSERT INTO " . DB_PREFIX . "material SET img_info = '" . $img_info ."',create_time = '" . TIMENOW ."'";
		$query = $this->db->query($sql);
		
		$vid = $this->db->insert_id();
		$data = array(
			'id' => $vid,
			'img_info' => hg_fetchimgurl($picture_pic),
			'upload_type' => '剧照',
		);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 上传预告片
	 */
	public function video_upload()
	{
		if(!$_FILES['videofile'])
		{
			$this->errorOutput(NO_VIDEOINFO);
		}
		
		if ($_FILES['videofile'])
		{
			$video = array();
			//检测视频服务器
			if (!$this->settings['App_mediaserver'])
			{
				//$this->errorOutput('视频服务器未安装!');
				$arr['error_code'] = 1;
				$arr['msg'] = '视频服务器未安装!';
				$this->addItem($arr);
				$this->output();
			}
			if ($_FILES['videofile']['error']>0)
			{
				//$this->errorOutput('视频上传错误！');
				$arr['error_code'] = 1;
				$arr['msg'] = '视频上传错误！';
				$this->addItem($arr);
				$this->output();
			}
			
			//获取视频服务器上传配置
			$videoConfig = $this->mode->getVideoConfig();
			if (!$videoConfig)
			{
				//$this->errorOutput('获取允许上传的视频类型失败！');
				$arr['error_code'] = 1;
				$arr['msg'] = '获取允许上传的视频类型失败！';
				$this->addItem($arr);
				$this->output();
				
			}
			$filetype = strtolower(strrchr($_FILES['videofile']['name'], '.'));			
			if (!in_array($filetype, $videoConfig['type']))
			{
				$arr['error_code'] = 1;
				$arr['msg'] = '格式错误';
				$this->addItem($arr);
				$this->output();
				//$this->errorOutput('只允许上传'.$videoConfig['hit'].'格式的视频');
			}
			//上传视频服务器
			$videodata = $this->mode->uploadToVideoServer($_FILES);
			if (!$videodata && !is_array($videodata))
			{
				//$this->errorOutput('视频上传无返回信息');
				$arr['error_code'] = 1;
				$arr['msg'] = '视频上传无返回信息';
				$this->addItem($arr);
				$this->output();
			}
			$sql = " INSERT INTO " . DB_PREFIX . "material SET video_info = '" .serialize($videodata)."',create_time = '" . TIMENOW ."'";
			$query = $this->db->query($sql);
			$vid = $this->db->insert_id();
			$data = array(
				'id' => $vid,
				'vod_img_info' => $videodata['img']['host'].$videodata['img']['dir'].$videodata['img']['filepath'].$videodata['img']['filename'],
				'upload_type' => '预告片',
			);
            	$this->addItem($data);
			$this->output();
		}
		
	}
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new movie_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>