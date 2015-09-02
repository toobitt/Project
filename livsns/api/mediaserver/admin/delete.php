<?php
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
set_time_limit(0);
class delete extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql 	= " SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN (" .($this->input['id']). ")";
		$q 		= $this->db->query($sql);
		$video  = array();
		while($r = $this->db->fetch_array($q))
		{
			if(file_exists($r['video_base_path'] . $r['video_path'] . $r['video_filename']))
			{
				$_pathinfo = pathinfo($r['video_path']);
				if($_pathinfo['extension'] == 'ssm')
				{
					$video_tmp 	= explode('.',$r['video_filename']);
					$video[] 	= $r['video_base_path'] . $r['video_path'] . $video_tmp[0];
				}
			}
		}
		
		if($video)
		{
			foreach($video AS $v)
			{
				if(!defined('NOT_CREATE_ISMV') || !NOT_CREATE_ISMV)
				{
					@unlink($v . '.ism');
					@unlink($v . '.ismv');
				}
				
				//重命名.ssm目录
				$_dir = pathinfo($v);
				if($_dir['dirname'] && is_dir($_dir['dirname']))
				{
					rename($_dir['dirname'],$_dir['dirname'] . '_removed');
				}
			}
		}
		
		/*********************判断删除的视频中有没有正在转码的，如果有就停止掉***************/
		$ids = explode(',',$this->input['id']);
		foreach($ids AS $id)
		{
			if($trans_servers = checkStatusFromAllServers($id))
			{
				$transcode = new transcode($trans_servers);
				$transcode->stop_transcode_task("{$id}");
			}
		}
		/**************************************************************************/
		$this->addItem('success');
		$this->output();
	}
	
	//视频还原的时候再生成ism与ismv文件
	public function recover()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql 	= " SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN (" .($this->input['id']). ")";
		$q 		= $this->db->query($sql);
		$video  = array();
		while($r = $this->db->fetch_array($q))
		{
			$video_tmp 	= explode('.',$r['video_filename']);
			$video[] 	= $r['video_base_path'] . $r['video_path'] . $video_tmp[0];
		}
		
		if($video)
		{
			foreach($video AS $v)
			{
				//还原目录名称
				$_dir = pathinfo($v);
				if($_dir['dirname'] && is_dir($_dir['dirname'] . '_removed'))
				{
					rename($_dir['dirname'] . '_removed',$_dir['dirname']);
				}
				
				if(!defined('NOT_CREATE_ISMV') || !NOT_CREATE_ISMV)
				{
					$cmd = MP4SPLIT_CMD . $v . '.ismv ' . $v . '.mp4';
					$cmd .= "\n" . MP4SPLIT_CMD . $v . '.ism ' . $v . '.ismv';
					exec($cmd);
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	//删除视频的物理文件
	public function delete_video_file()
	{	
		$source_file = UPLOAD_DIR . $this->input['source_path'] . $this->input['source_filename'];
		$target_file = TARGET_DIR . $this->input['video_path']  . $this->input['video_filename'];
		if($this->check_file($source_file))
		{
			@unlink($source_file);
		}
		
		if($this->check_file($target_file))
		{
			@unlink($target_file);
		}
	}
	
	//检测文件
	private function check_file($file = '')
	{
		if(!$file)
		{
			return false;
		}
		
		if(!file_exists($file))
		{
			return false;
		}
		
		$filetype = strtolower(strrchr($file, '.'));
		$allowtype 	= explode(',', $this->settings['video_type']['allow_type']);
		if (!in_array($filetype, $allowtype))
		{
			return false;
		}
		return true;
	}
}

$out = new delete();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'delete';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>