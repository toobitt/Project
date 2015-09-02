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
define('MOD_UNIQUEID','vod_config');//模块标识
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
	
	function audit()
	{
		
	}
	
	function sort()
	{
		
	}
	
	function publish()
	{
		
	}
	
	public function  create()
	{
		if(!$this->input['water_pic_position'])
		{
			$water_pic_position = '0,0';
		}
		$water_pic_position = $this->input['water_pic_position'];
		$sql = "INSERT INTO ".DB_PREFIX."vod_config  SET ";
		$sql.= "name='".urldecode($this->input['config_name'])."',".
			  "output_format='".urldecode($this->input['output_format'])."',".
			  "codec_format='".urldecode($this->input['codec_format'])."',".
			  "codec_profile='".urldecode($this->input['codec_profile'])."',".
			  "video_bitrate='".urldecode($this->input['video_bitrate'])."',".
			  "audio_bitrate='".urldecode($this->input['audio_bitrate'])."',".
			  "frame_rate='".urldecode($this->input['frame_rate'])."',".
			  "width='".urldecode($this->input['width'])."',".
			  "height='".urldecode($this->input['height'])."',".
			  "vpre='".urldecode($this->input['vpre'])."',".
			  "gop='".urldecode($this->input['gop'])."',".
			  "water_mark='".urldecode($this->input['water_mark'])."',".
			  "water_pic_position='".urldecode($this->input['water_pic_position'])."',".
			  "is_open_water='".intval($this->input['is_open_water'])."',".
			  "is_use='".intval($this->input['is_use'])."'";
	    $this->db->query($sql);
	    $vid = $this->db->insert_id();
	   	$water_mark_url = $this->input['water_mark'];
	    if(strrpos($water_mark_url,'?'))
	    {
	    	$water_mark_url = substr($water_mark_url,0,strrpos($water_mark_url,'?'));
	    }
	    $water_pos = $this->submit2TranscodeServer($water_mark_url);
	    $sql = "UPDATE ".DB_PREFIX."vod_config  SET config_order_id = '" . $vid . "',water_pos = '" .$water_pos. "' WHERE id = ".$vid;
	    $this->db->query($sql);
	    $sql = "SELECT * FROM ".DB_PREFIX."vod_config  WHERE id = ".$vid;
	    $return = $this->db->query_first($sql);
		$this->addItem($return);
		$this->output();
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->input['water_pic_position'])
		{
			$water_pic_position = '0,0';
		}
		$water_pic_position = $this->input['water_pic_position'];
		
		$water_mark_url = $this->input['water_mark'];
	    if(strrpos($water_mark_url,'?'))
	    {
	    	$water_mark_url = substr($water_mark_url,0,strrpos($water_mark_url,'?'));
	    }
	    $water_pos = $this->submit2TranscodeServer($water_mark_url);
	    $sql_ =  "SELECT * FROM " . DB_PREFIX . "vod_config WHERE id = " . intval($this->input['id']) ;
		$pre_data = $this->db->query_first($sql_);
	    
		$fields = ' SET  ';
		/*配置名称*/
		$fields .= '  name = \''.urldecode($this->input['config_name']).'\',';
		/*输出格式*/
		$fields .= '  output_format = \''.urldecode($this->input['output_format']).'\',';
		/*编码格式*/
		$fields .= '  codec_format = \''.urldecode($this->input['codec_format']).'\',';
		/*编码质量*/
		$fields .= '  codec_profile = \''.urldecode($this->input['codec_profile']).'\',';
		/*宽度*/
		$fields .= '  width = \''.intval($this->input['width']).'\',';
		/*高度*/
		$fields .= '  height = \''.intval($this->input['height']).'\',';
		/*视频码率*/
		$fields .= '  video_bitrate = \''.intval($this->input['video_bitrate']).'\',';
		/*音频码率*/
		$fields .= '  audio_bitrate = \''.intval($this->input['audio_bitrate']).'\',';
		/*帧频*/
		$fields .= '  frame_rate = \''.intval($this->input['frame_rate']).'\',';
		/*关键帧距离*/
		$fields .= '  gop = \''.intval($this->input['gop']).'\',';
		/*转码质量*/
		$fields .= '  vpre = \''.urldecode($this->input['vpre']).'\',';
		/*水印*/
		$fields .= '  water_mark = \''.urldecode($this->input['water_mark']).'\',';
		/*水印位置*/
		$fields .= '  water_pos = \''.$water_pos.'\',';
		/*水印图片位置*/
		$fields .= '  water_pic_position = \''.$water_pic_position.'\',';
		/*是否使用*/
		$fields .= '  is_use = \''.intval($this->input['is_use']) . '\',';
		/*是否开启水印*/
		$fields .= '  is_open_water = '.intval($this->input['is_open_water']);
	    $sql = "UPDATE ".DB_PREFIX.'vod_config ' . $fields .'  WHERE  id = ' . intval($this->input['id']);
	    $this->db->query($sql);
	    $sql = "SELECT * FROM ".DB_PREFIX."vod_config WHERE id = '".intval($this->input['id'])."'";
	    $return = $this->db->query_first($sql);
	    
	    $this->addLogs('update' , $pre_data , $return , '' , '');
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
	
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "vod_config WHERE id IN (" . $this->input['id'] . ")";
		$q = $this->db->query($sql_);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$ret[] = $row;
		}
			
		$sql = "DELETE FROM ".DB_PREFIX."vod_config  WHERE  1  AND  id  in (".urldecode($this->input['id']).")";
		$this->db->query($sql);
		$this->addLogs('delete' , $ret , '' , '' , '');
			
		$this->addItem($sql);
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
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('url',$url);
		$curl->addRequestData('a','upload_water_transcode_task');
		$ret = $curl->request('video_transcode.php');
		return $ret['path'];
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