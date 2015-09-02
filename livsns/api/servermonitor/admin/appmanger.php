<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
class  appmanger extends adminReadBase
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
		$sql = "SELECT a.*,s.name AS server_name FROM ".DB_PREFIX."appmanger a LEFT JOIN ".DB_PREFIX."server s ON s.id = a.server_id  WHERE 1 ".$condition."  ORDER BY a.order_id DESC  ".$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('appmanger','item');
		while($r = $this->db->fetch_array($q))
		{
			$r['type'] = $this->settings['program_type'][$r['type']];
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$this->addItem($r);
		}
		$this->output();
	}

	public function count()
	{
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'appmanger a WHERE 1 '.$this->get_condition();
		$appmanger_total = $this->db->query_first($sql);
		echo json_encode($appmanger_total);
	}

	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= ' AND a.id = '.intval($this->input['id']);
		}
		
		if($this->input['k'] || trim(urldecode($this->input['k']))== '0')
		{
			$condition .= ' AND  a.name  LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}

		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND a.create_time >= '".$start_time."'";
		}

		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND a.create_time <= '".$end_time."'";
		}

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  a.create_time > ".$yesterday." AND a.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  a.create_time > ".$today." AND a.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  a.create_time > ".$last_threeday." AND a.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  a.create_time > ".$last_sevenday." AND a.create_time < ".$tomorrow;
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
			$sql = "SELECT * FROM ".DB_PREFIX."appmanger WHERE id = '".intval($this->input['id'])."'";
			$ret = $this->db->query_first($sql);
			$ret['create_time'] = date('Y-m-d',$ret['create_time']);
			$this->addItem($ret);
		}
		$this->output();
	}
	
	public function add_new_app()
	{
		if($this->input['id'])
		{
			$sql = "SELECT a.*,s.name AS server_name FROM ".DB_PREFIX."appmanger a LEFT JOIN ".DB_PREFIX."server s ON s.id = a.server_id  WHERE a.id = '" .intval($this->input['id'])."'";
			$ret = $this->db->query_first($sql);
			$ret['type'] = $this->settings['program_type'][$ret['type']];
			$ret['create_time'] = date('Y-m-d',$ret['create_time']);
			$this->addItem($ret);
		}
		$this->output();
	}
	
	//获取应用商店里面的应用
	public function get_app()
	{
		$curl = new curl($this->settings['App_appstore']['host'], $this->settings['App_appstore']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','open_app');
		$ret = $curl->request('appstore.php');
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_dir()
	{
		if(!$this->input['server_id'] || intval($this->input['server_id']) == -1)
		{
			$this->errorOutput('请选择一台服务器');
		}
		
		$dir = $this->input['dir']?$this->input['dir']:'/';
		$sql = " SELECT * FROM " .DB_PREFIX. "server WHERE id = '" .intval($this->input['server_id']). "'";
		$server = $this->db->query_first($sql);
		$cmd = array(
			'action' => 'ls',
			'para'	 => $dir,
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
		$dir_content = $sock->readall();
		$dir_arr = explode("\n",trim($dir_content));
		$this->addItem($dir_arr);
		$this->output();
	}
}

$out = new appmanger();
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