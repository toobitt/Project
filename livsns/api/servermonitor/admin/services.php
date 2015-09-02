<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
class  services extends adminReadBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = "SELECT * FROM ".DB_PREFIX."services  WHERE 1 ".$condition."  ORDER BY order_id DESC  ".$limit;
		$q = $this->db->query($sql);
		$sql_server = " SELECT * FROM " .DB_PREFIX. "server WHERE id = '" .intval($this->input['server_id']). "'";
		$server_arr = $this->db->query_first($sql_server);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['start_cmd'] || $r['stop_cmd'] || $r['restart_cmd'])
			{
				$r['status'] = $this->check_pgerp($server_arr,$r['name']);
				$r['is_display'] = 1;
			}
			else
			{
				$r['status'] = 0;
				$r['is_display'] = 0;
			}
			$r['status_name'] = $this->settings['status'][$r['status']];
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$ret['data'][] = $r;
		}
		$ret['server_id'] = intval($this->input['server_id']);
		$this->addItem($ret);
		$this->output();
	}

	public function count()
	{
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'services WHERE 1 '.$this->get_condition();
		$services_total = $this->db->query_first($sql);
		echo json_encode($services_total);
	}

	public function get_condition()
	{
		$condition = '';
		if($this->input['server_id'])
		{
			$condition .= ' AND server_id = '.intval($this->input['server_id']);
		}
		else 
		{
			$this->errorOutput(NOID);
		}

		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}

		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
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
		$sql = "SELECT * FROM " .DB_PREFIX. "services WHERE id = '" .intval($this->input['id']). "'";
		$ret = $this->db->query_first($sql);
		$conf = unserialize($ret['conf']);
		if($conf && is_array($conf))
		{
			$ret['conf'] = $conf;
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_config()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT * FROM " .DB_PREFIX. "server WHERE id = '" .intval($this->input['id']). "'";
		$server = $this->db->query_first($sql);
		$cmd = array(
			'action' => 'getfile',
			'para' => urldecode($this->input['config_path']),
			'user' => $server['user'],
			'pass' => hg_encript_str($server['password'],0),
			'charset' => 'utf8',
		);
		$sock = new hgSocket();
		if(!($status = $sock->connect($server['ip'], $server['port'])))
		{
			$this->errorOutput('未连接上服务器，请检查python有没有启动');
		}
		$sock->sendCmd($cmd);
		$configs = $sock->readall();
		$this->addItem(array('config' => $configs,'config_path' => $cmd['para'],'service_name' =>urldecode($this->input['service_name']),'server_name' => $server['name']));
		$this->output();
	}
	
	public function check_pgerp($server,$name)
	{
		$cmd = array(
			'action' => 'pgrep',
			'para' => $name,
			'user' => $server['user'],
			'pass' => hg_encript_str($server['password'],0),
			'charset' => 'utf8',
		);
		$sock = new hgSocket();
		if(!($status = $sock->connect($server['ip'], $server['port'])))
		{
			$this->errorOutput('未连接上服务器，请检查python有没有启动');
		}
		$source = $sock->sendCmd($cmd);
		if(!$source)
		{
			$this->errorOutput('发送数据有问题');
		}
		$status = $sock->readall();
		return $status?1:2;
	}
}

$out = new services();
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