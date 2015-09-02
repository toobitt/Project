<?php
define('MOD_UNIQUEID','logs');
require('global.php');
class logsApi extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'	=>'管理',
		'_node'=>array(
			'name'=>'应用日志',
			'filename'=>'logs_node.php',
			'node_uniqueid'=>'logs_node',
			),
		);
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/logs.class.php');
		$this->obj = new logs();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$op_con = $this->get_operation();
		$ret = $this->obj->showLogs($condition,$limit,$op_con);
		
		if(!empty($ret))
		{
			foreach ($ret as $k => $v)
			{
				$this->addItem($v);
			}
		}		
		$this->output();		
	}
	
	
	public function count()
	{	
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'system_log WHERE 1 '.$this->get_condition();
		$logs_total = $this->db->query_first($sql);
		echo json_encode($logs_total);	
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function get_condition()
	{		
		$condition = '';
		
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim($this->input['k']).'%"';
		}
		//查询应用分组
		if($this->input['_id']&&$this->input['para']=='app')
		{	
			require_once(ROOT_PATH . 'lib/class/auth.class.php');
			$this->auth = new Auth();
			$apps = $this->auth->get_app();
			if(is_array($apps))
			{
				foreach($apps as $k=>$v)
				{
					$ret[$v['id']] = $v['bundle'];
				}
			}
			$condition .= " AND bundle_id = '". $ret[$this->input['_id']] . "'";
			$bundle_id = $ret[$this->input['_id']];
		}
		if($this->input['_id']&&$this->input['para']!='app')
		{	
			$condition .= " AND moudle_id = '". $this->input['para'] . "'";
		}
		//查询操作
		if (urldecode($this->input['ops'])!=''&&(urldecode($this->input['ops'])!= -1))
		{			
			if($bundle_id)
			{
				$sq = "SELECT * FROM  " . DB_PREFIX ."system_log_operation WHERE bundle_id =  '".$bundle_id."'";
				$qq = $this->db->query($sq);
				while($re = $this->db->fetch_array($qq))
				{
					$operation[$re['id']] = $re['op_name'];
				}
			}
			if($operation[$this->input['ops']])
			{
				$condition .=" AND operation= '" . urldecode($this->input['ops']) . "'";
			}
		}
		
		//操作人查询
		if($this->input['ops_per'])
		{
			$condition .= " AND user_name LIKE '%" . $this->input['ops_per'] . "%'";
		}
		
		//ip地址查询
		if($this->input['ip'])
		{
			$condition .= " AND ip LIKE '%" . $this->input['ip'] . "%'";
		}
		
		//查询来源
		if (urldecode($this->input['sos'])!=''&&(urldecode($this->input['sos'])!= -1))
		{			
			$condition .=" AND source= '" . urldecode($this->input['sos']) . "'";
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND create_time <= ".$end_time;
		}

		if($this->input['create_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['create_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND  create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		
		if(isset($this->input['user_id']))
		{
			$condition .= " AND user_id = ".intval($this->input['user_id']);
		}
		return $condition;
		
	}
	
	/**
	 *	取所有的应用
	 */
	/*public function show_bun()
	{
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		if($this->input['fid'])
		{
			$modules = $this->auth->get_module('id,mod_uniqueid,name',$this->input['fid']);
			if(is_array($modules))
			{
				foreach($modules as $k=>$v)
				{
					 $m = array('id'=>$v['id'],
								'name'=>$v['name'],
								'fid'=>$this->input['fid'],
								'depth'=>0,
								'is_last'=>1,
								'para'=>$v['mod_uniqueid']);
				 	 $this->addItem($m);
				}
			}
			$this->output();
		}
		else
		{
			$apps_arr = array();
			$app_info = $this->auth->get_app();
			/*if(is_array($app_info))
			{
				foreach($app_info as $k=>$v)
				{
					$apps_arr[$v['bundle']]['id'] = $v['id'];
					$apps_arr[$v['bundle']]['name'] = $v['name'];
				}
			}
			$sq = "SELECT distinct  bundle_id
					FROM  " . DB_PREFIX ."system_log_operation 
					WHERE 1";
			$qq = $this->db->query($sq);
			while($re = $this->db->fetch_array($qq))
			{
			foreach($app_info as $k=>$v)
			{
				if('logs' !=$v['bundle'])
				{
					$apps = array('id'=>$v['id'],
								  'name'=>$v['name'],
								  'fid'=>0,
								  'depth'=>0,
								  'is_last'=>0,
								  'input_k'=>'_id',
								  'para'=>'app');
					$this->addItem($apps);
				}
			 }
			$this->output();
		}
	}*/
	
	
	function query()
	{	
		$condition = '';
		//获取应用标识
		if(urldecode($this->input['bundle_id']))
		{
			$condition .= " AND a.bundle_id = '". (urldecode($this->input['bundle_id'])) . "'";
		}
		//获取模板标识
		if(urldecode($this->input['moudle_id']))
		{
			$condition .= " AND a.moudle_id = '". (urldecode($this->input['moudle_id'])) . "'";
		}
		//获取操作人id
		if(intval($this->input['user_id']))
		{
			$condition .= " AND a.user_id = '". (intval($this->input['user_id'])) . "'";
		}
		//获取操作人
		if(urldecode($this->input['user_name']))
		{
			$condition .= " AND a.user_name = '". (urldecode($this->input['user_name'])) . "'";
		}
		//获取操作类型
		if(urldecode($this->input['operation']))
		{
			$condition .= " AND a.operation = '". (urldecode($this->input['operation'])) . "'";
		}
		//获取ip
		if(urldecode($this->input['ip']))
		{
			$condition .= " AND a.ip = '". (urldecode($this->input['ip'])) . "'";
		}
		//获取来源
		if(urldecode($this->input['source']))
		{
			$condition .= " AND a.source = '". (urldecode($this->input['source'])) . "'";
		}
		//获取内容id
		if(intval($this->input['content_id']))
		{
			$condition .= " AND a.content_id = '". (intval($this->input['content_id'])) . "'";
		}
		//获取内容id
		if(intval($this->input['sort_id']))
		{
			$condition .= " AND a.sort_id = '". (intval($this->input['sort_id'])) . "'";
		}
		
		if($this->input['other_condition'])
		{
			$condition .= urldecode($this->input['other_condition']);
		}
		
		//获取时间
		if(urldecode($this->input['create_time']))
		{
			$condition .= urldecode($this->input['create_time']);
		}
		
		$offset = $this->input['offset']?intval($this->input['offset']):0;
		$count = $this->input['count']?intval($this->input['count']):20;
		$orderby = $this->input['orderby']?urldecode($this->input['orderby']):'ORDER BY a.id DESC';
		$ret = $this->obj->queryLogs($condition,$orderby,$offset,$count);
		$this->addItem($ret);
		$this->output();
	}
	
	
	function showCount()
	{	
		$condition = '';
		//获取应用标识
		if(urldecode($this->input['bundle_id']))
		{
			$condition .= " AND a.bundle_id = '". (urldecode($this->input['bundle_id'])) . "'";
		}
		//获取模板标识
		if(urldecode($this->input['moudle_id']))
		{
			$condition .= " AND a.moudle_id = '". (urldecode($this->input['moudle_id'])) . "'";
		}
		//获取操作人id
		if(intval($this->input['user_id']))
		{
			$condition .= " AND a.user_ide = '". (intval($this->input['user_id'])) . "'";
		}
		//获取操作人
		if(urldecode($this->input['user_name']))
		{
			$condition .= " AND a.user_name = '". (urldecode($this->input['user_name'])) . "'";
		}
		//获取操作类型
		if(urldecode($this->input['operation']))
		{
			$condition .= " AND a.operation = '". (urldecode($this->input['operation'])) . "'";
		}
		//获取ip
		if(urldecode($this->input['ip']))
		{
			$condition .= " AND a.ip = '". (urldecode($this->input['ip'])) . "'";
		}
		//获取来源
		if(urldecode($this->input['source']))
		{
			$condition .= " AND a.source = '". (urldecode($this->input['source'])) . "'";
		}
		//获取内容id
		if(intval($this->input['content_id']))
		{
			$condition .= " AND a.content_id = '". (intval($this->input['content_id'])) . "'";
		}
		//获取内容id
		if(intval($this->input['sort_id']))
		{
			$condition .= " AND a.sort_id = '". (intval($this->input['sort_id'])) . "'";
		}
	
		if($this->input['other_condition'])
		{
			$condition .= urldecode($this->input['other_condition']);
		}
		
		//获取时间
		if(urldecode($this->input['create_time']))
		{
			$condition .= urldecode($this->input['create_time']);
		}
		$ret = $this->obj->showCount($condition);
		$this->addItem($ret);
		$this->output();
	}
	
	public function getLogsById()
	{
		$id = $this->input['id'] ? urldecode($this->input['id']) : '';
		if(empty($id))
		{
			$this->errorOutput('请传入日志ID');
		}
		$ret = $this->obj->getLogsById($id);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 *	取所有的应用
	 */
	public function get_content()
	{
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'system_log_content WHERE log_id  = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		
		$sql_ = 'SELECT *
				FROM '.DB_PREFIX.'system_log WHERE id  = '.$this->input['id'];
		$logs = $this->db->query_first($sql_);
		
		$sq = "SELECT * 
				FROM  " . DB_PREFIX ."system_log_operation 
				WHERE 1";
		$qq = $this->db->query($sq);
		while($re = $this->db->fetch_array($qq))
		{
			$operation[$re['id']] = $re['op_name'];
		}
		
		$r['operation'] = $operation[$logs['operation']];
		$r['title'] = "[".date("Y-m-d H:i:s",$logs['create_time'])."]".$logs['user_name'].$r['operation'].":".$logs['title'];
		$this->addItem($r);
		$this->output();
	}
	
	public function get_operation()
	{
		$operation = array();
		$con = '';
		
		if($this->input['_id']&&$this->input['para']=='app')
		{	
			require_once(ROOT_PATH . 'lib/class/auth.class.php');
			$this->auth = new Auth();
			$apps = $this->auth->get_app();
			if(is_array($apps))
			{
				foreach($apps as $k=>$v)
				{
					$ret[$v['id']] = $v['bundle'];
				}
			}
			$con .= " AND bundle_id = '". $ret[$this->input['_id']] . "'";
		}
		if($this->input['_id']&&$this->input['para']!='app')
		{	
			$con .= " AND moudle_id = '". $this->input['para'] . "'";
		}
		
		return $con;
	}
	
	function index()
	{	
	}
	function detail()
	{	
	}
	
	public function get_workload()
	{
		$condition = $this->workload_condition();
		$sql = 'SELECT l.operation,l.bundle_id,lo.action,lo.op_name,l.user_id FROM '.DB_PREFIX.'system_log l LEFT JOIN '.DB_PREFIX.'system_log_operation lo ON l.operation = lo.id WHERE 1 ';
		$sql .= $condition ;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$data[$r['user_id']]['total'][$r['action']]++;
			$data[$r['user_id']]['detail'][$r['bundle_id']][$r['operation']]['count']++;
			$data[$r['user_id']]['detail'][$r['bundle_id']][$r['operation']]['op_name'] = $r['op_name'];
			$data[$r['user_id']]['detail'][$r['bundle_id']][$r['operation']]['action']	= $r['action'];
		}
		$this->addItem($data);
		$this->output();
	}
	
	public function workload_condition()
	{
		$condition = '';
		//查询应用分组
		if($this->input['app']&&$this->input['para']=='app')
		{	
			$app = $this->input['app'];
			$bundles = str_replace(',',"','",$app);
			$condition .= " AND l.bundle_id in( '". $bundles. "')";
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND create_time <= ".$end_time;
		}

		if($this->input['create_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['create_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND  create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		
		if(isset($this->input['user_id']))
		{
			$condition .= " AND user_id = ".intval($this->input['user_id']);
		}
		return $condition;
		
	
	}
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new logsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>