<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
class  server extends adminReadBase
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
		$sql = "SELECT * FROM ".DB_PREFIX."server  WHERE 1 ".$condition."  ORDER BY order_id DESC  ".$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('server','item');
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$this->addItem($r);
		}
		$this->output();
	}

	public function count()
	{
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'server WHERE 1 '.$this->get_condition();
		$server_total = $this->db->query_first($sql);
		echo json_encode($server_total);
	}

	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}
		
		if($this->input['k'] || trim(urldecode($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(urldecode($this->input['k'])).'%"';
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
		if($this->input['id'])
		{
			$sql = "SELECT * FROM ".DB_PREFIX."server WHERE id = '".intval($this->input['id'])."'";
			$ret = $this->db->query_first($sql);
			$ret['password'] = hg_encript_str($ret['password'],0);
			$ret['create_time'] = date('Y-m-d',$ret['create_time']);
			$this->addItem($ret);
		}
		$this->output();
	}
	
	public function show_opration()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT * FROM " .DB_PREFIX. "server WHERE id = '" .intval($this->input['id']). "'";
		$server = $this->db->query_first($sql);
		$cmd = array(
			'action' => 'top',
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
		if($this->input['replace'])
		{
			$this->addItem($configs);
		}
		else 
		{
			$cmd2 = array(
				'action' => 'df',
				'para' => '-h',
				'user' => $server['user'],
				'pass' => hg_encript_str($server['password'],0),
				'charset' => 'utf8',
			);
			$sock2 = new hgSocket();
			if(!($status = $sock2->connect($server['ip'], $server['port'])))
			{
				$this->errorOutput('未连接上服务器，请检查python有没有启动');
			}
			$sock2->sendCmd($cmd2);
			$df = $sock2->readall();
			$this->addItem(array('config' => $configs,'id' => intval($this->input['id']),'df' => $df));
		}
		$this->output();
	}
}

$out = new server();
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