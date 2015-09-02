<?php
/*
 * 提取视频(将指定的视频提取出来放在用户指定的目录,并且附属视频的信息)
 * */
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
set_time_limit(0);
class pickUp extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function pick()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//判断配置的提取的到哪个目录
		if(!file_exists(PICK_UP_DIR) || !is_writeable(PICK_UP_DIR))
		{
			$this->errorOutput(DIR_NOT_EXISTS_OR_NOT_WRITEABLE);
		}
		
		//查询出需要提取的视频信息
		$sql 	= " SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN (" .$this->input['id']. ")";
		$q 		= $this->db->query($sql);
		$video  = array();
		$videoInfo = array();
		while($r = $this->db->fetch_array($q))
		{
			$videoInfo[] = $r;
			$video_tmp 	= explode('.',$r['video_filename']);
			$video[] = array(
				'id'	=> $r['id'],
				'path' 	=> rtrim($r['video_base_path'],'/') . '/' . $r['video_path'] . $r['video_filename'],
				'title' => $r['starttime']?$r['title'] . '('.date('Y-m-d',$r['starttime']).')':$r['title'],
				'type'	=> $video_tmp[1],
			);
		}
		
		//判断视频存不存在
		if(!$video)
		{
			$this->errorOutput(VIDEO_NOT_EXISTS);
		}
		
		//创建目录
		$targetDir = date('YmdHis',TIMENOW) . hg_rand_num(2) . '/';
		if (!hg_mkdir(PICK_UP_DIR . $targetDir) || !is_writeable(PICK_UP_DIR . $targetDir))
		{
			$this->errorOutput(NOWRITE);
		}
		
		//copy视频到指定的目录
		foreach ($video AS $k => $v)
		{
			$targetVideoPath = PICK_UP_DIR . $targetDir . $v['title'] . '.' . $v['type'];
			if(file_exists($targetVideoPath))
			{
				$targetVideoPath = PICK_UP_DIR . $targetDir . $v['title'] . '('  .$v['id'].  ').' . $v['type'];
			}
			@copy($v['path'],$targetVideoPath);
		}
		
		//将视频的json信息放入该目录下
		file_put_contents(PICK_UP_DIR . $targetDir . 'videoinfo.json', json_encode($videoInfo));
		
		//将视频的xml信息放入该目录下
		//$xml = arrtoxml($videoInfo);
		//file_put_contents(PICK_UP_DIR . $targetDir . 'videoinfo.xml', $xml);
		
		//返回信息
		$ret = array(
			'path' => PICK_UP_DIR . $targetDir,
		);
		
		$this->addItem($ret);
		$this->output();
	}
}

$out = new pickUp();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'pick';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>