<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID','livmedia_node');
require_once('global.php');
class  vod_get_sort_name extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*根据传过来的类型返回该类型下的分类*/
	public function get_leixing_sort()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node  WHERE fid = '".intval($this->input['vod_leixing'])."'";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	/*获取编辑上传的类别*/
	public function get_sort_name()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node  WHERE fid = 1";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
		
	}
	
	/*获取标注归档的类别*/
	public function get_mark_sort_name()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node  WHERE fid = 4";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
		
	}
	
	/*获取 直播归档的类别*/
	public function get_live_sort_name()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node  WHERE fid = 3";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
		
	}
	
	/*获取所有类别*/
	public function get_all_sort_name()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$r['leixing_name'] = $this->settings['video_upload_type'][$r['father']];
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	public function get_all_leixing()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node WHERE fid = 0";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	
}

$out = new vod_get_sort_name();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_sort_name';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>