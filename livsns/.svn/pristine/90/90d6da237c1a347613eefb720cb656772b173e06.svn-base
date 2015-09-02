<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID','live_split_data_clean');//模块标识
define('SCRIPT_NAME', 'live_split_data_clean');
require('./global.php');
class live_split_data_clean extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> 'live_split',	 
			'name' 			=> '直播拆条历史数据清理',	 
			'brief' 		=> '直播拆条历史数据清理',
			'space'			=> '3600',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	function show()
	{
		$cleanarr = array();
		$cleandata = $this->getCleanLiveData();
		foreach ($cleandata as $v)
		{
			$this->cleandata($v,$cleanarr);
		}
		if($cleanarr)
		{
		  $retlts = $retm = array();
		  $cleanarr['live_time_shift_id'] && $retlts = $this->delete_live_time_shift($cleanarr['live_time_shift_id']);
		  $cleanarr['video_id'] && $retm = $this->delete_media($cleanarr['video_id']);
		  if($cleanarr['live_data_id'] && $retm && $retlts)
		  {
		  	 $this->updatecleanfield($cleanarr['live_data_id']);
		  }
		}
		$this->addItem('success');
		$this->output();
	}
	
	public function cleandata($data, & $cleanarr)
	{
		if($data['create_time'] + (int)$this->settings['live_time_shift_data_timeout'] * 3600 > TIMENOW)
		{
			return ;
		}
		if(in_array($data['status'], array('-1','1','2','3','4')))
		{
			if($data['status'] == 1 &&  $data['create_time'] + $this->settings['live_time_shift_timeout'] > TIMENOW)
			{
				return;
			}
			$cleanarr['live_time_shift_id'][] = $data['live_time_shift_id'];
		}
		if (in_array($data['status'], array('3','4')))
		{
			$cleanarr['video_id'][] = $data['video_id'];
		}
		$cleanarr['live_data_id'][] = $data['id'];
		return true;
	}
	
	public function updatecleanfield($live_data_id)
	{
		$sql = 'UPDATE '.DB_PREFIX.'live_data SET  dataclean =  1 WHERE id IN ('.(is_array($live_data_id) ? implode(',', $live_data_id) : $live_data_id) .')';
		return $this->db->query($sql);
	}
	
	public function delete_live_time_shift($live_time_shift_log_id)
	{
		if(!$this->settings['App_live_time_shift'])
		{
			$this->errorOutput('时移应用未安装');
		}
		$curl = $this->makeCurl('live_time_shift');
  	    $curl->setSubmitType('post');
		$curl->initPostData();
		$data = array(
				'a' => 'delete',
				'id'=> is_array($live_time_shift_log_id) ? implode(',', $live_time_shift_log_id) : $live_time_shift_log_id,
			);			
		return $this->addrequest($curl,$data)->request('admin/live_time_shift_update.php');
	}
	
	public function delete_media($media_id)
	{
		//此处是为了判断视频库有没有安装
		if(!$this->settings['App_livmedia'])
		{
			$this->errorOutput('视频应用未安装');
		}
		$curl = $this->makeCurl('livmedia');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$data = array(
				'a' => 'delete',
				'id'=> is_array($media_id) ? implode(',', $media_id) : $media_id,
			);
		return $this->addrequest($curl,$data)->request('admin/vod_update.php');
	}
	
	public function addrequest(curl $curl,$data)
	{
		foreach ($data as $k => $v)
		{
			$this->array_to_add($curl, $k, $v);
		}
		return $curl;
	}
	
	public function array_to_add(curl $curl,$str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($curl, $str . "[$kk]" , $vv);
				}
				else
				{
					$curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
		else
		{
			$curl->addRequestData($str, $data);
		}
		return $curl;
	}
	
	public function makeCurl($app_uniqueid = '')
	{
		class_exists('curl') OR require  ROOT_PATH.'lib/class/curl.class.php';
		if(!$app_uniqueid)
		{
			return new curl();
		}
		return new curl($this->settings['App_'.$app_uniqueid]['host'],$this->settings['App_'.$app_uniqueid]['dir']);	
	}
	
	public function getCleanLiveData()
	{
		$ret = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'live_data WHERE dataclean = 0';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$ret[] = $row;
		}
		return $ret;
	}
	
}
include(ROOT_PATH . 'excute.php');
?>