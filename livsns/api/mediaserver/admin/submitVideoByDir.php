<?php
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
class submitVideoApi extends adminBase
{
	private $curl;
	public function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
	}
	
	//提交
	public function submit()
	{
		//通过ftp上传上来的目录
		$dir = $this->input['dir'];
		if(!$dir)
		{
			$this->errorOutput(DIR_ERROR);
		}

		if(!$newDirName = $this->doDir(FTP_UPLOAD_DIR . $dir))
		{
			$this->errorOutput(DIR_ERROR);
		}
		
		$video_arr = $this->scanDir(UPLOAD_DIR . $newDirName);
		if(!$video_arr)
		{
			$this->errorOutput(NO_VIDEO_IN_DIR);
		}
		
		foreach($video_arr AS $_k => $_path)
		{
			$this->curl->setSubmitType('post');
			$this->curl->initPostData();
			//构建需要提交的数据
			$data = array(
			   'filepath' 			=> str_replace(UPLOAD_DIR,'',$_path),
			   'start'				=> '0',
			   'duration'			=> '',
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
	 * 解析上传上来的目录
	 * dir是绝对路径
	 */
	private function doDir($dir = '')
	{
		if(!is_dir($dir))
		{
			return false;
		}
		
		//解析输入的目录的最后一级
		$lastDirName = basename(rtrim($dir,'/'));
		if(!$lastDirName)
		{
			return false;
		}
		
		//判断upload目录里面的第一级存不存在所输入的目录名称
		if(is_dir(UPLOAD_DIR . $lastDirName))
		{
			//随机产生一个目录名称
			$lastDirName = $lastDirName . TIMENOW . hg_rand_num(5);
		}
		
		//先将目录移动到upload目录
		$cmd = 'cp -r ' . $dir . ' ' .UPLOAD_DIR .  $lastDirName;
		exec($cmd,$out,$status);
		//目录复制后删除原来目录
		if(!$status)
		{
			$cmd = 'rm -Rf ' . $dir;
			exec($cmd);
		}
		return $lastDirName;
	}
	
	//遍历目录
	private function scanDir($path)
	{
		//存储遍历的视频的路径
		$video_arr = array();
		$this->read_file($path, $video_arr);
		return $video_arr;
	}
	
	//递归读取目录里面的所有文件
	private function read_file($path,&$video_arr)
	{
		if ($handle = opendir($path))//打开路径成功  
        {
            while ($file = readdir($handle))//循环读取目录中的文件名并赋值给$file  
            {
                if ($file != '.' && $file != '..')//排除当前路径和前一路径  
                {
                    if (is_dir($path."/".$file))  
                    {
                        $this->read_file($path . '/' . $file,$video_arr);
                    }  
                    else  
                    {
                    	if($this->check_type($file) && $file[0] != '.')//只取出图片类型的图片,并且屏蔽隐藏文件
                    	{
	                    	 $video_arr[] = realpath($path . '/' . $file);
                    	}
                    }
                }  
            }
            closedir($handle);
        }
	}
	
	//判断文件类型是不是允许的视频类型
	private function check_type($path = '')
	{
		$type_config = explode(',',$this->settings['video_type']['allow_type']);
		$typetmp = explode('.',$path);
		$filetype = strtolower($typetmp[count($typetmp)-1]);
		return in_array('.' . $filetype,$type_config)?1:0;
	}
}

$out = new submitVideoApi();
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