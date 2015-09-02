<?php
require('global.php');
require_once(ROOT_PATH . 'lib/class/livmedia.class.php');
define('MOD_UNIQUEID','publish_day');//模块标识
set_time_limit(0);

class publish_day extends cronBase
{
	private $livmedia;
	public function __construct()
	{
		parent::__construct();
		$this->livmedia = new livmedia();
	}
	
	public function show()
	{
		//查询有没有过期的电视剧并且设置了每天发布的电视剧
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_play 
				WHERE status = 2 
				AND publish_num_day != 0 
				AND (copyright_limit > " . TIMENOW . " OR copyright_limit = 0)";
		
		$q = $this->db->query($sql);
		$tv_play = array();
		while ($r = $this->db->fetch_array($q))
		{
			$tv_play[] = $r;
			$tv_play_id[] = $r['id'];
		}
		
		//删除不在计划内的记录
		$sql = "DELETE FROM " . DB_PREFIX . "publish_cron WHERE tv_play_id NOT IN (" . implode(',', $tv_play_id) . ")";
		$this->db->query($sql);
		
		
		$today = strtotime(date('Y-m-d'));
		
		//查询当天发布过的电视剧id
		$sql = "SELECT tv_play_id,publish_num FROM " . DB_PREFIX . "publish_cron WHERE create_time = " . $today;
		$q = $this->db->query($sql);
		$tv_play_ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$tv_play_ids[] = $r['tv_play_id'];
			$publish_num[$r['tv_play_id']] = $r['publish_num'];
		}
		
		//hg_pre($publish_num);
		//hg_pre($tv_play_ids);
		if($tv_play)
		{
			foreach ($tv_play AS $k => $v)
			{
				//已经发布过的个数
				$published_num = $publish_num[$v['id']];
				$published_num = $published_num ? $published_num : 0;
				//过滤已经发布过的
				if(in_array($v['id'], $tv_play_ids) && $published_num >= $v['publish_num_day'])
				{
					continue;
				}
				
				if($v['column_id'] && $v['status'] == 2)
				{
					
					$count = $v['publish_num_day'] - $published_num;
					
					$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode 
							WHERE expand_id = 0 
							AND transcode_status = 1 
							AND tv_play_id = '" .$v['id']. "' 
							ORDER BY index_num ASC 
							LIMIT 0," . $count;
					
					//echo $sql;
					$q = $this->db->query($sql);
					
					$video_ids = array();
					$ep = array();
					while ($r = $this->db->fetch_array($q))
					{
						 $video_ids[] = $r['video_id'];
						 $ep[] = $r;
					}
					
					//hg_pre($v);
					//hg_pre($video_ids);
					//hg_pre($ep);
					if($video_ids)
					{
						$video_num = $video_ids ? count($video_ids) : 0;
						
						$video_num += $published_num;
						$column_id_arr = array_keys(unserialize($v['column_id']));
						
						//触发视频发布
						$this->livmedia->insertQueueToLivmedia(implode(',',$video_ids),'insert',implode(',',$column_id_arr),$v['column_id']);
						
						//发布剧集
						foreach($ep AS $_k => $_v)
						{
							publish_insert_query($_v, 'insert',$column_id_arr,1);
						}
						publish_insert_query($v, 'update',$column_id_arr);
						
						//发布成功记录
						$sql = "REPLACE INTO " . DB_PREFIX . "publish_cron SET tv_play_id = " . $v['id'] . ',create_time = ' . $today . ',publish_num = ' . $video_num;
						//echo $sql;
						$this->db->query($sql);
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
			'name' => '计划任务发布剧集',	 
			'brief' => '计划任务发布剧集',
			'space' => '1800',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
}

$out = new publish_day();
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