<?php
define('MOD_UNIQUEID','ftpSubmitVideo');
require_once('global.php');
class ftpSubmitVideo extends adminReadBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function count(){}
	public function detail(){}
	
	public function show()
	{
		if(!defined('FTP_UPLOAD_DIR') || !is_dir(FTP_UPLOAD_DIR))
		{
			$this->errorOutput(FTP_UPLOAD_DIR_EMPTY);
		}
		
		$dir = '';
		if($this->input['dir'])
		{
			$dir = rtrim($this->input['dir'],'/') . '/';
		}
		$cmd = " ls " . FTP_UPLOAD_DIR . $dir .  "\n";
		exec($cmd,$out,$status);
		$out_arr = array();
		if(!$status)
		{
			foreach($out AS $k => $v)
			{
				if(is_dir(FTP_UPLOAD_DIR . $dir . $v))
				{
					$out_arr['dir'][] = $v;
				}
				else if($this->check_type($v))
				{
					$out_arr['file'][] = array(
						'filename' => $v,
						'filesize' => hg_fetch_number_format(filesize(FTP_UPLOAD_DIR . $dir . $v),1),
						'byte_size'=> filesize(FTP_UPLOAD_DIR . $dir . $v),
						'is_submit'=> $this->isAlreadySubmit(FTP_UPLOAD_DIR . $dir . $v),
					);
				}
			}
		}
		//输出目录层级链接
		if($dir)
		{
			$dir_tmp = rtrim($dir,'/');
			$dir_arr = explode('/',$dir_tmp);
			$_dir_arr = array();
			$_tmp_val = '';
			foreach($dir_arr AS $k => $v)
			{
				if($_tmp_val)
				{
					$_tmp_val = $_tmp_val . '/';
				}
				$_dir_arr[$v] = $_tmp_val . $v;
				$_tmp_val  .= $v;
			}
			$out_arr['dir_path'] = $_dir_arr;
		}
		$this->addItem($out_arr);
		$this->output();
	}
	
	public function getFileSize()
	{
		if(!$this->input['filename'])
		{
			$this->errorOutput(NOFILE);
		}
		
		$filename_arr = explode(',',$this->input['filename']);
		
		$dir = '';
		if($this->input['dir'])
		{
			$dir = rtrim($this->input['dir'],'/') . '/';
		}
		
		$path = FTP_UPLOAD_DIR . $dir;
		$out_arr = array();
		foreach ($filename_arr AS $k => $v)
		{
			if(is_file($path . $v))
			{
				$out_arr[$v] = array(
					'filesize' => hg_fetch_number_format(filesize($path . $v),1),
					'byte_size'=> filesize($path . $v),
				);
			}
		}
		
		$this->addItem($out_arr);
		$this->output();	
	}
	
	//判断文件类型是不是允许的视频类型
	private function check_type($path = '')
	{
		$type_config = explode(',',$this->settings['video_type']['allow_type']);
		$typetmp = explode('.',$path);
		$filetype = strtolower($typetmp[count($typetmp)-1]);
		return in_array('.' . $filetype,$type_config)?1:0;
	}
	
	//判断该视频是否已经提交过
	private function isAlreadySubmit($video_path = '')
	{
		$filepath = CACHE_DIR . 'alreadySub.info';
		if(!file_exists($filepath))
		{
			return 0;
		}
		
		$info = file_get_contents($filepath);
		$info = unserialize($info);
		if(in_array($video_path,$info))
		{
			return 1;
		}
		return 0;
	}
}

$out = new ftpSubmitVideo();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>