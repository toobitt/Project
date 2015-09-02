<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('../admin/global.php');
define('MOD_UNIQUEID','template_store');//模块标识
define('SCRIPT_NAME', 'template_store');
require_once(CUR_CONF_PATH . 'lib/curd.class.php');
require_once(CUR_CONF_PATH . 'lib/template.class.php');
class template_store extends cronBase
{
	private $curd = null;
	private $livmedia = null;
	function __construct()
	{
		parent::__construct();
		$this->curd = new curd('attach');
		$this->livmedia = new template();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> MOD_UNIQUEID,	 
			'name' 			=> '模板商店同步视频数据',	 
			'brief' 		=> '模板商店同步视频数据',
			'space'			=> '2',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		//删除转码三天仍然未成功的
		$this->curd->delete(0, ' AND status <0 and create_time<'.(TIMENOW - 3*24*3600));
		$report = array();
		$this->input['offset'] = 0;
		$this->input['count']  = 1;
		$attach = $this->curd->show('*', ' AND status <=0 and create_time>'.(TIMENOW - 24*3600), ' order by update_time desc ');
		$attach = $attach[0];
		$update_data = array();
		if(!$attach['attach_id'])
		{
			$this->errorOutput("未查找出需要同步的数据");
		}
		$vid = $attach['attach_id'];
		$vodinfo = $this->livmedia->get_video($vid);
		
		
		if($vodinfo['status'] > 0)
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'templates set status="'.$vodinfo['status'].'",m3u8="'.addslashes($vodinfo['video_m3u8']).'", video_preview="'.addslashes(serialize($vodinfo['img_info'])).'" where video = '.$vid;
			$this->db->query($sql);
			$this->curd->delete($attach['id']);
			$update_data['video'] = $vid;
			$update_data['m3u8'] = $vodinfo['video_m3u8'];
			$update_data['status'] = $vodinfo['status'];
		}
		else
		{	
			$update_data['id'] = $attach['id'];
			$update_data['update_time'] = TIMENOW;
			$this->curd->update($update_data);
		}
		$this->addItem($update_data);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');