<?php
require_once('global.php');
define('MOD_UNIQUEID','mediaserver');//模块标识
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
class transcode_center extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
			'show'				=>'任务查看',
		);
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{
		$offset = $this->input['offset']?intval($this->input['offset']):0;
		$count = $this->input['count']?intval($this->input['count']):15;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = "SELECT * FROM ".DB_PREFIX."transcode_center  WHERE 1 " . $condition . " ORDER BY order_id  DESC  ".$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$server_info = array('host' => $r['trans_host'],'port' => $r['trans_port']);
			//$task = $this->get_transcode_tasks($server_info);
			$task = json_decode($r['transcode_tasks'],1);
			$r['cur_num'] = $task['transcoding_tasks'] . '个';//($task['return'] == 'fail')?'连不上服务器':$task['transcoding_tasks'] . '个';
			$r['max_transcode_tasks'] = $task['max_transcode_tasks'] . '个';//($task['return'] == 'fail')?'连不上服务器':$task['max_transcode_tasks'] . '个';
			$r['waiting_tasks'] = $task['waiting_tasks'] . '个';//($task['return'] == 'fail')?'连不上服务器':$task['waiting_tasks'] . '个';
			//$all_tasks = $this->get_transcode_task_info($server_info);
			$all_tasks = json_decode($r['transcode_status'],1);
			if($all_tasks['return'] == 'success')
			{
				$all_tasks['waiting'] = $all_tasks['waiting']?$all_tasks['waiting']:array();
				$all_tasks['running'] = $all_tasks['running']?$all_tasks['running']:array();
				if($all_tasks['waiting'])
				{
					$all_tasks['running'] = array_merge($all_tasks['running'],$all_tasks['waiting']);
				}
				$r['tasks_status'] = $all_tasks['running'];
			}
			else 
			{
				$r['tasks_status'] = '连不上服务器';
			}
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$this->addItem($r);
		}
		$this->output();	
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."transcode_center WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function get_condition()
	{
		$condition = "";
		if($this->input['id'])
		{
			$condition .= " AND id = '".intval($this->input['id'])."'";
		}
		
		if($this->input['k'] || $this->input['k'] == '0')
		{
			$condition .= ' AND  name LIKE "%'.trim($this->input['k']).'%"';
		}

		if($this->input['name'])
		{
			$condition .= ' AND name LIKE "%'.$this->input['name'].'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."transcode_center  WHERE id = '".intval($this->input['id'])."'"; 
		$return = $this->db->query_first($sql);
		$return['create_time'] = date('Y-m-d',$return['create_time']);
		//$transcode_config = $this->get_transcode_config(array('host' => $return['trans_host'],'port' => $return['trans_port']));
		$transcode_config = json_decode($return['transcode_config'],1);
		$return['source_path'] = $transcode_config['default_transcode_file_source_path'];
		$return['target_path'] = $transcode_config['default_transcode_file_destination_path'];
		//获取当前转码服务版本号
		//$version = $this->get_transcode_version(array('host' => $return['trans_host'],'port' => $return['trans_port']));
		$version = json_decode($return['transcode_version'],1);
		$version['version'] ? $return['version'] = $version['version'] : $return['version'] = '无法获取版本号';
		$this->addItem($return);
		$this->output();
	}
	
	//获取转码服务器配置
	private function get_transcode_config($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_config();
		$ret = json_decode($ret,1);
		return $ret;
	}

	//获取当前正在转码的个数
	public function get_transcode_tasks($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_tasks();
		$ret = json_decode($ret,1);
		return $ret;
	}
	
	//获取当前转码服务版本号
	public function get_transcode_version($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_version();
		$ret = json_decode($ret,1);
		return $ret;
	}
	
	//获取某台转码服务器所有正在转码的任务信息
	public function get_transcode_task_info($arr)
	{
		$trans = new transcode($arr);
		$ret = $trans->get_transcode_status();
		$ret = json_decode($ret,1);
		return $ret;
	}
	
	//显示转码状态详情
	public function show_detail_status()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."transcode_center  WHERE id = '".intval($this->input['id'])."'";
		$arr = $this->db->query_first($sql);
		/*
		$trans = new transcode(array('host' => $arr['trans_host'],'port' => $arr['trans_port']));
		$ret = $trans->get_transcode_status();
		$ret = json_decode($ret,1);
		*/
		$re = json_decode($arr['transcode_status'],1);
		$status = array();
		if($re['return'] != 'fail')
		{
			$status['status'] = $re['tasks_status'];
		}
		
		$status['id'] = $this->input['id'];
		$this->addItem($status);
		$this->output();
	}
	
	//获取可用的转码服务器
	public function getCanUseServers()
	{
		$servers = hg_get_transcode_servers();
		if($servers)
		{
			foreach($servers AS $k => $v)
			{
				//$status = $this->get_transcode_tasks(array('host' => $v['trans_host'],'port' => $v['trans_port']));
				$status = json_decode($v['transcode_status'],1);
				if($status['return'] != 'success')
				{
					unset($servers[$k]);continue;
				}
				//$task = $this->get_transcode_tasks(array('host' => $v['trans_host'],'port' => $v['trans_port']));
				$task = json_decode($v['transcode_tasks'],1);
				/*
				$servers[$k]['transcode_on'] = ($task['return'] == 'fail')?'连不上服务器':$task['transcoding_tasks'] . '个';
				$servers[$k]['transcode_wait'] = ($task['return'] == 'fail')?'连不上服务器':$task['waiting_tasks'] . '个';
				*/
				$servers[$k]['transcode_on'] = $task['transcoding_tasks'] . '个';
				$servers[$k]['transcode_wait'] = $task['waiting_tasks'] . '个';
			}
		}
		$servers['max_size'] = ini_get('upload_max_filesize'); 
		$this->addItem($servers);
		$this->output();
	}
}

$out = new transcode_center();
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