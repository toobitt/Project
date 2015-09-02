<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 4234 2011-07-28 05:14:16Z repheal $
***************************************************************************/
require('global.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
define('MOD_UNIQUEID','transcode_config');//模块标识
class vod_config_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function audit(){}
	function sort(){}
	function publish(){}
	protected function reset_default_config($tid=0)
	{
		$sql = 'UPDATE ' . DB_PREFIX . 'vod_config SET is_default=0 WHERE is_default=1 and type_id='.$tid;
		$this->db->query($sql);
	}
	public function  create()
	{
		if(!$this->input['unique_id'])
		{
			$this->errorOutput('没有标识');
		}
		
		//查询标识是否已经存在
		$sql = "SELECT * FROM " .DB_PREFIX. "vod_config WHERE unique_id = '" .$this->input['unique_id']. "'";
		$udata = $this->db->query_first($sql);
		if($udata)
		{
			$this->errorOutput('该标识已经存在，请换一个标识');
		}

		if($this->input['water_pic_position'])
		{
			$water_pic_position = $this->input['water_pic_position'];
		}
		else 
		{
			$water_pic_position = '0,0';
		}
		$insert_data = array(
			'name'			 	=> $this->input['config_name'],
			'unique_id'			=> $this->input['unique_id'],
			'type_id'			=> $this->input['type_id'],
			'output_format'		=> $this->input['output_format'],
			'codec_format'		=> $this->input['codec_format'],
			'codec_profile'		=> $this->input['codec_profile'],
			'video_bitrate'		=> $this->input['video_bitrate'],
			'audio_bitrate'		=> $this->input['audio_bitrate'],
			'frame_rate'		=> $this->input['frame_rate'],
			'width'			 	=> $this->input['width'],
			'height'			=> $this->input['height'],
			'vpre'			 	=> $this->input['vpre'],
			'gop'			 	=> $this->input['gop'],
			'water_mark'		=> $this->input['water_mark'],
			'water_pic_position'=> $water_pic_position,
			'is_open_water'		=> $this->input['is_open_water'],
			'is_use'			=> $this->input['is_use'],
			'is_default'			=> $this->input['is_default'],
		);
		if($insert_data['is_default'])
		{
			$this->reset_default_config($insert_data['type_id']);
		}
		$sql = "INSERT INTO ".DB_PREFIX."vod_config  SET ";
		foreach ($insert_data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
	    $this->db->query($sql);
	    $vid = $this->db->insert_id();
	    //如果开启水印并且水印图片存在
	    if($insert_data['is_open_water'] && $insert_data['water_mark'])
	    {
	    	if(strrpos($insert_data['water_mark'],'?'))
		    {
		    	$insert_data['water_mark'] = substr($insert_data['water_mark'],0,strrpos($insert_data['water_mark'],'?'));
		    }
		    $water_pos = $this->submit2TranscodeServer($insert_data['water_mark']);
	    }

	    $update_data = array(
	    	'config_order_id' => $vid,
	    );
	    if($water_pos)
	    {
			$update_data['water_pos'] =  $water_pos;   
		}

	    $sql = "UPDATE ".DB_PREFIX."vod_config  SET ";
	    foreach ($update_data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '" .$vid. "'";
	    $this->db->query($sql);
	    $sql = "SELECT * FROM ".DB_PREFIX."vod_config  WHERE id = ".$vid;
	    $return = $this->db->query_first($sql);
	    //记录日志
	    $this->addLogs('创建转码配置','',$return,$return['name']);
		$this->addItem($return);
		$this->output();
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->input['unique_id'])
		{
			$this->errorOutput('没有标识');
		}
		
		//查询标识是否已经存在
		$sql = "SELECT * FROM " .DB_PREFIX. "vod_config WHERE unique_id = '" .$this->input['unique_id']. "' AND id != '" .$this->input['id']. "'";
		$udata = $this->db->query_first($sql);
		if($udata)
		{
			$this->errorOutput('该标识已经存在，请换一个标识');
		}

		if($this->input['water_pic_position'])
		{
			$water_pic_position = $this->input['water_pic_position'];
		}
		else 
		{
			$water_pic_position = '0,0';
		}
		
	    //获取原来的数据
	    $sql =  "SELECT * FROM " . DB_PREFIX . "vod_config WHERE id = " . intval($this->input['id']) ;
		$pre_data = $this->db->query_first($sql);
		$update_data = array(
			'name'			 	=> $this->input['config_name'],
			'unique_id'			=> $this->input['unique_id'],
			'output_format'		=> $this->input['output_format'],
			'codec_format'		=> $this->input['codec_format'],
			'codec_profile'		=> $this->input['codec_profile'],
			'video_bitrate'		=> $this->input['video_bitrate'],
			'audio_bitrate'		=> $this->input['audio_bitrate'],
			'frame_rate'		=> $this->input['frame_rate'],
			'width'			 	=> $this->input['width'],
			'height'			=> $this->input['height'],
			'vpre'			 	=> $this->input['vpre'],
			'gop'			 	=> $this->input['gop'],
			'water_mark'		=> $this->input['water_mark'],
			'water_pic_position'=> $water_pic_position,
			'is_open_water'		=> $this->input['is_open_water'],
			'is_use'			=> $this->input['is_use'],
			'is_default'			=> $this->input['is_default'],
		);
		if($update_data['is_default'])
	    {
	    	$this->reset_default_config($pre_data['type_id']);
	    }
	    elseif($pre_data['is_default'])
	    {
	    	$this->errorOutput("无法取消默认配置");
	    }
	 	//如果开启水印并且水印图片存在
	    if($update_data['is_open_water'] && $update_data['water_mark'])
	    {
	    	if(strrpos($update_data['water_mark'],'?'))
		    {
		    	$update_data['water_mark'] = substr($update_data['water_mark'],0,strrpos($update_data['water_mark'],'?'));
		    }
		    $water_pos = $this->submit2TranscodeServer($update_data['water_mark']);
	    }
	    
	 	if($water_pos)
	    {
			$update_data['water_pos'] =  $water_pos;   
		}

		$sql = "UPDATE ".DB_PREFIX."vod_config  SET ";
	    foreach ($update_data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '" .$this->input['id']. "'";
	    $this->db->query($sql);
		//返回数据
	    $sql = "SELECT * FROM ".DB_PREFIX."vod_config WHERE id = '".intval($this->input['id'])."'";
	    $return = $this->db->query_first($sql);
	    //记录日志
	    $this->addLogs('更新转码配置', $pre_data, $return,$return['name']);
		$return['is_use'] = $return['is_use']?'是':'否';
	    $this->addItem($return);
	    $this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql =  "SELECT * FROM " . DB_PREFIX . "vod_config WHERE id IN (" . $this->input['id'] . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while($row = $this->db->fetch_array($q))
		{
			$pre_data[] = $row;
		}
		$sql = "DELETE FROM ".DB_PREFIX."vod_config  WHERE  1  AND  id  in (".$this->input['id'].")";
		$this->db->query($sql);
		//记录日志
		$this->addLogs('删除转码配置', $pre_data, '','删除转码配置' . $this->input['id']);
		$this->addItem($pre_data);
		//$this->addItem($sql);
		$this->output();
	}
	
	//上传水印图片
	public function upload_water_img()
	{
		if(!$_FILES['Filedata'])
		{
			$this->errorOutput('请选择上传文件');
		}
		$material_pic = new material();
		$img_info = $material_pic->addMaterial($_FILES,0);
		$img_thumb_info = hg_fetchimgurl($img_info, 160);
		$this->addItem(array('url' => $img_thumb_info));
		$this->output();
	}
	
	//提交水印图片到转码服务器
	public function submit2TranscodeServer($url)
	{
		$transservers =  $this->get_trans_server();
		$ret = array();
		$flag = 0;
		foreach($transservers AS $k => $v)
		{
			$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
			$curl->setSubmitType('get');
			$curl->initPostData();
			$curl->addRequestData('url',$url);
			$curl->addRequestData('host',$v['trans_host']);
			$curl->addRequestData('port',$v['trans_port']);
			$curl->addRequestData('a','upload_water_transcode_task');
			$ret = $curl->request('video_transcode.php');
			if($ret['return'] == 'fail')
			{
				$error_log = $v;
				$error_log['url'] = $url;
				$this->addLogs('提交水印图片失败','',$error_log,'提交水印图片到' .$v['trans_host'] .':'.  $v['trans_port']. '失败');
				$flag = 1;
			}
		}
		if($flag || empty($ret) || !$ret['path'])
		{
			return false;
		}
		return $ret['path'];
	}
	
	//获取转码服务器
	public function get_trans_server()
	{
		$servers = hg_get_transcode_servers();
		if($servers && !empty($servers))
		{
			return $servers;
		}
		else
		{
			$this->errorOutput('没有可提交的转码服务器');
		}
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new vod_config_update();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>