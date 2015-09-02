<?php
define('MOD_UNIQUEID','ftpSubmitVideo');
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class ftpSubmitVideo_update extends adminUpdateBase
{
	private $curl;
    public function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	//提交
	public function submit()
	{
		$videos = $this->copyFilesToMedia($this->input['videofiles'],$this->input['dir']);
		if(!$videos)
		{
			$this->errorOutput(NO_VIDEO_IN_DIR);
		}
		
		foreach($videos['file'] AS $_k => $_v)
		{
			$this->curl->setSubmitType('post');
			$this->curl->initPostData();
			//构建需要提交的数据
			$data = array(
			   'filepath' 			=> $videos['dir'] . $_v['filename'],
			   'start'				=> '0',
			   'duration'			=> '',
			   'title'				=> $_v['title'],
			   'water_id' => $this->input['water_id'] ? $this->input['water_id'] : '',
			   'server_id' => $this->input['server_id'] ? $this->input['server_id'] : '',
			   'mosaic_id' => $this->input['mosaic_id'] ? $this->input['mosaic_id'] : '',
			   'water_pos' => $this->input['water_pos'] ? $this->input['water_pos'] : '',
			   'no_water' => $this->input['no_water'] ? $this->input['no_water'] : '',
			   'vod_config_id' => $this->input['vod_config_id'] ? $this->input['vod_config_id'] : '',
			);
			
			foreach ($data AS $k => $v)
			{
				$this->curl->addRequestData($k,$v);
			}
			$this->curl->request('create.php');
		}
		
		$this->addItem('success');
		$this->output();
	}
	
	/*
	 * 将文件copy到media目录
	 * $filenames:以逗号分隔的字符串（视频文件名）
	 * $dir:相对FTP_UPLOAD_DIR的视频文件的目录路径
	 **/
	
	private function copyFilesToMedia($filenames,$dir = '')
	{
		if($dir)
		{
			$dir = rtrim($dir,'/') . '/';
		}
		
		$path = FTP_UPLOAD_DIR . $dir;
		if(!$filenames)
		{
			return false;
		}
		$videoFileArr = explode(',',$filenames);
		//首先过滤提交的不合法的视频文件
		$videos = array();
		foreach ($videoFileArr AS $k => $filename)
		{
			if(!file_exists($path . $filename) || !$this->check_type($filename))
			{
				continue;
			}	
			$videos[] = $filename;
		}
		
		//如果存在合法的视频文件，就先在meida里面创建创建目录用于摆放复制过来的视频
		if(!$videos)
		{
			return false;
		}
		
		$targetDir =  TIMENOW . hg_rand_num(5) . '/';//随机产生一个目录
		if (!hg_mkdir(UPLOAD_DIR . $targetDir) || !is_writeable(UPLOAD_DIR . $targetDir))
		{
			return false;
		}
		
		//复制视频到该目录
		$new_videos = array();
		foreach ($videos AS $k => $v)
		{
			$filetype 	= strtolower(strrchr($v, '.'));
			$new_videos_name = TIMENOW . hg_rand_num(5) . $filetype;
			$status = @copy($path . $v,UPLOAD_DIR . $targetDir . $new_videos_name);
			//目录复制后删除原来目录
			if($status && file_exists(UPLOAD_DIR . $targetDir . $new_videos_name))
			{
				$new_videos[] = array(
					'filename' 	=> $new_videos_name,
					'title'		=> substr($v,0,strrpos($v,'.')),
				);
				//删除原来的文件
				@unlink($path . $v);
				$this->saveFilePathToCache($path . $v);
			}
		}

		return array(
			'file' 		=> $new_videos,
			'dir' 		=> $targetDir,
		);
	}
	
	//判断文件类型是不是允许的视频类型
	private function check_type($path = '')
	{
		$type_config = explode(',',$this->settings['video_type']['allow_type']);
		$typetmp = explode('.',$path);
		$filetype = strtolower($typetmp[count($typetmp)-1]);
		return in_array('.' . $filetype,$type_config)?1:0;
	}
	
	//保存已经提交过的文件的信息，主要是用来标识该文件已经提交过，因为删除原文件有可能不成功
	private function saveFilePathToCache($path = '')
	{
		$filepath = CACHE_DIR . 'alreadySub.info';
		if(!file_exists($filepath))
		{
			$info = serialize(array($path));
		}
		else 
		{
			$info = file_get_contents($filepath);
			$info = unserialize($info);
			$info[] = $path;
			$info = serialize($info);
		}
		file_put_contents($filepath,$info);
	}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new ftpSubmitVideo_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'submit';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>