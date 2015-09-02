<?php
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_load_flash extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:video_id(视频的记录id)
	 *功能:重新加载flash
	 *返回值:加载flash的时候必备的一些信息
	 **/
	public function load_flash()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}

		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = ".intval($this->input['video_id']);
		$arr = $this->db->query_first($sql);
		$return = array();
		$return['video_mark'] = $arr['hostwork'].'/'.$arr['video_path'].MAINFEST_F4M;
		$return['vodid'] = $arr['id'];
		$return['aspect'] = $arr['aspect'];
		if($this->input['start_time'])
		{
			$return['start'] = intval($this->input['start_time']);
		}
		else 
		{
			$return['start'] = 0;
		}
		
		if($this->input['duration'])
		{
			$return['duration'] = intval($this->input['duration']);
		}
		else 
		{
			$return['duration'] = '';
		}
		
		$this->addItem($return);
		$this->output();
		
	}
	
}

$out = new vod_load_flash();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'load_flash';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
	
?>