<?php
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
set_time_limit(0);
class download extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function download()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "UPDATE " . DB_PREFIX . "vodinfo SET downcount = downcount + 1 WHERE id = '" .intval($this->input['id'])."'";
		$this->db->query($sql);
		$sql = "SELECT * FROM " . DB_PREFIX ."vodinfo WHERE id = '".intval($this->input['id'])."'";
		$video = $this->db->query_first($sql);
		
		//标识是下载源视频还是转码好的视频
		if($this->input['need_source'])
		{
			//如果不存在去寻找此视频的来源
			if(!$video['source_path'] || !$video['source_filename'])
			{
				if(!$video['original_id'])
				{
					$this->errorOutput(NOT_FIND_SOURCE_VIDEO_FILE);
				}
				$sql = "SELECT * FROM " . DB_PREFIX ."vodinfo WHERE id = '".intval($video['original_id'])."'";
				$video = $this->db->query_first($sql);
				if(!$video)
				{
					$this->errorOutput(NOT_FIND_SOURCE_VIDEO_FILE);
				}
			}
			$file_name = $video['source_filename'];
			$file_path = rtrim($video['source_base_path'],'/') . '/' . $video['source_path'] . $file_name;
		}
		else
		{
			$file_name 	= 	$video['video_filename'];
			$file_path = rtrim($video['video_base_path'],'/') . '/' . $video['video_path'] . $file_name;
		}
		
		//判断文件存不存在
		if(!file_exists($file_path))
		{
			$this->errorOutput(NOTFINDFILE);
		}
		
		$file_size=filesize($file_path);//文件大小
		$type = explode('.',$file_name);//文件类型
		$fp = @fopen($file_path,"r");//读取资源
		
		header("Content-type:text/html;charset=utf-8");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes"); 
		Header("Accept-Length:".$file_size);
		Header("Content-Disposition: attachment; filename=\"" . $video['title'] . '.'. $type[1] ."\"");
		$buffer=1024;
		$file_count=0;
		//向浏览器返回数据
		while(!feof($fp) && $file_count < $file_size)
		{
			 $file_con=fread($fp,$buffer);
			 $file_count += $buffer;
			 echo $file_con;
		}
		fclose($fp);
		$yuan = $this->input['need_source'] ? '源' : '';
		$this->addLogs('下载' .$yuan. '视频', '', $video, '下载' .$yuan. '视频' .$video['title']);
	}
}

$out = new download();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'download';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>