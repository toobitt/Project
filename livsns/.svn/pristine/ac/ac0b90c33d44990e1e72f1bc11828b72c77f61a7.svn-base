<?php
/*
 * 计划任务执行的强制转码
 */
require('global.php');
require_once(ROOT_PATH . 'lib/class/livmedia.class.php');
define('MOD_UNIQUEID','check_copyright');//模块标识
set_time_limit(0);

class check_copyright extends cronBase
{
	private $livmedia;
	public function __construct()
	{
		parent::__construct();
		$this->livmedia = new livmedia();
	}
	
	public function show()
	{
		//查询有没有过期的电视剧
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_play WHERE copyright_limit < " . TIMENOW . " AND copyright_limit != 0 ";
		$q = $this->db->query($sql);
		$tv_play = array();
		while ($r = $this->db->fetch_array($q))
		{
			$tv_play[] = $r;
		}
		
		if($tv_play)
		{
			foreach ($tv_play AS $k => $v)
			{
				if($v['column_id'] && $v['status'] == 2)
				{
					$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id = '" .$v['id']. "' ";
					$q = $this->db->query($sql);
					$video_ids = array();
					$ep = array();
					while ($r = $this->db->fetch_array($q))
					{
						 $video_ids[] = $r['video_id'];
						 $ep[] = $r;
					}
					
					if($video_ids)
					{
						$column_id_arr = array_keys(unserialize($v['column_id']));
						$this->livmedia->insertQueueToLivmedia(implode(',',$video_ids),'delete',implode(',',$column_id_arr));	
						
						//清空剧集里面的url
						$sql = "UPDATE " .DB_PREFIX. "tv_episode SET url = '' WHERE tv_play_id = '" .$v['id']. "'";
						$this->db->query($sql);
						
						//更新电视剧
						foreach($ep AS $_k => $_v)
						{
							publish_insert_query($_v, 'update',$column_id_arr,1);
						}
						publish_insert_query($v, 'update',$column_id_arr);
						
					}
				}
			}
		}
		
		$this->addItem('success');
		$this->output();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '检测电视剧是否过期',	 
			'brief' => '检测电视剧是否过期，过期则下架',
			'space' => '1800',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new check_copyright();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>