<?php
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
require_once(ROOT_PATH . 'lib/class/ftp.class.php');
set_time_limit(0);
class FtpUpload extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	//支持批量
	public function upload()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}
		$sql 	= " SELECT video_base_path,video_path,video_filename,id FROM " . DB_PREFIX . "vodinfo WHERE id IN (" . $this->input['video_id'] .")";
		$q = $this->db->query($sql);
		$video = array();
		while($r = $this->db->fetch_array($q))
		{
			$video[] = $r;
		}
		
		//实例化ftp,并连接
		$ftp_config = array(
			'hostname' => $this->input['hostname'],
			'username' => $this->input['username'],
			'password' => $this->input['password'],
		);
		$ftp = new Ftp();
		if(!$ftp->connect($ftp_config))
		{
			$this->errorOutput('CAN NOT CONNECT FTP SERVER');
		}
		
		foreach($video AS $k => $v)
		{
			$target_dir = date('Y',TIMENOW) . '/' . date('m',TIMENOW) . '/' . TIMENOW . hg_rand_num(6) . '/';
			$target_path = $target_dir . $v['video_filename'];
			$video_filepath = $v['video_base_path'] . $v['video_path'] . $v['video_filename'];
			
			if(!$ftp->mkdir($target_dir))
			{
				$this->errorOutput('CAN NOT MAKE DIR');
			}
			
			if(!$ftp->upload($video_filepath,$target_path))
			{
				$this->errorOutput('CAN NOT UPLOAD FILE');
			}
			
			$pathinfo = pathinfo($target_path);
			$filename = basename($pathinfo['basename'],'.'.$pathinfo['extension']);
			$this->addItem(array('path' => $target_path,'id' => $v['id'],'dir' => $pathinfo['dirname'],'filename' => $filename));
		}	
		$this->output();
	}
}

$out = new FtpUpload();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'upload';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>