<?php
require_once('global.php');
define('MOD_UNIQUEID','mediaserver');//模块标识
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH.'lib/class/livmedia.class.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
class transcode_status_manger extends adminReadBase
{
	public $total;
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function detail(){}
	
	//显示转码状态详情
	public function show()
	{
		if(!$this->input['server_id'])
		{
			$this->errorOutput(NO_SERVER_ID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."transcode_center  WHERE id = '".intval($this->input['server_id'])."'";
		$arr = $this->db->query_first($sql);
		$trans = new transcode(array('host' => $arr['trans_host'],'port' => $arr['trans_port']));
		$ret = $trans->get_transcode_status();
		$ret = json_decode($ret,1);
		$status = array();
		$task_ids = array();
		$more_task = array();
		if($ret['return'] != 'fail')
		{
			foreach((array)$ret['running'] as $k => $v)
			{
				$ret['running'][$k]['status'] = 'running'; 
			}
			if($ret['running'])
			{
				//如果存在等待也加进去
				if($ret['waiting'])
				{
					foreach((array)$ret['waiting'] as $k => $v)
					{
						$ret['waiting'][$k]['status'] = 'waiting'; 
					}
					$ret['running'] = array_merge($ret['running'],$ret['waiting']);
				}
				$status['status'] = $ret['running'];
				foreach($ret['running'] AS $k => $v)
				{
					//处理多码流
					if(strstr($v['id'],'_more'))
					{
						$more_task[] = $v['id'];//保存多码流任务id
						$m_ids = explode('_',$v['id']);
						$task_ids[] = $m_ids[0];
						continue;
					}
					$task_ids[] = $v['id'];
				}
								
				$livmedia = new livmedia();
				$video_info = $livmedia->get_videos(implode(',',$task_ids));
				if($video_info && $video_info[0])
				{
					foreach($status['status'] AS $k => $v)
					{
						if(in_array($v['id'],$more_task))
						{
							$t_ids = explode('_',$v['id']);
							$status['status'][$k]['title'] = '多码流:(' . $video_info[0][$t_ids[0]]['title'] . ')';
							continue;
						}
						$status['status'][$k]['title'] = $video_info[0][$v['id']]['title'];
					}
				}
			}
		}
		$this->total = count($status[0]['status']);
		$this->addItem($status);
		$this->output();
	}
	public function count()
	{
		if(!$this->input['server_id'])
		{
			$this->errorOutput(NO_SERVER_ID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."transcode_center  WHERE id = '".intval($this->input['server_id'])."'";
		$arr = $this->db->query_first($sql);
		$trans = new transcode(array('host' => $arr['trans_host'],'port' => $arr['trans_port']));
		$ret = $trans->get_transcode_status();
		$ret = json_decode($ret,1);
		$total = count($ret['running'])+count($ret['waiting']);
		echo json_encode($total);
	}
}

$out = new transcode_status_manger();
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