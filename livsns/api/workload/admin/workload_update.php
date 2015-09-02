<?php
define('MOD_UNIQUEID','workload');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/workload_mode.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class workload_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new workload_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{}
	
	public function update()
	{}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
    
	//从各应用接口获取工作量
    public function get_work()
    {
    	$static_date = $this->input['static_date'];
    	$app = $this->input['app'];
    	if(!$static_date)
    	{
    		$static_date = date("Y-m-d",strtotime("-1 day"));
    	}
    	if(!$this->settings["App_{$app}"])
    	{
    		$this->errorOutput('该应用尚未安装');
    	}
    	$curl = new curl($this->settings["App_{$app}"]['host'],$this->settings["App_{$app}"]['dir']);
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','get_work');
		$curl->addRequestData('static_date',$static_date);
		$ret = $curl->request("admin/{$app}.php");
		$ret = $ret[0];
		if(!$ret)
		{
			$this->errorOutput('当天没有工作数据');
		}
    	$date = strtotime($static_date);
		$user = array();
		$sql = 'SELECT w.user_id,w.id,c.column_id FROM '.DB_PREFIX. 'workload w LEFT JOIN '.DB_PREFIX.'column c  ON w.id = c.wid  WHERE w.date = "'.$date.'"';
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$user[] = $r['user_id'];
			$vids[$r['user_id']] = $r['id'];
			$column_ids[$r['id']][] = $r['column_id'];
		}
		foreach ($ret as $user_id => $value)
		{
			$data = array(
				'user_id'	=> $user_id,
				'user_name'	=> $value['user_name'],
				'org_id'	=> $value['org_id'],
				'date'		=> $date,
				'year'		=> date('Y',$date),
				'month'		=> date('m',$date),
				'week'		=> date('W',$date),
				'day'		=> date('d',$date),
				'create_time'=> TIMENOW,
			);
			$vid = $vids[$user_id];
			if(!in_array($user_id,$user))//如果统计日的该用户还不存在，则新增一个记录
			{
				$vid = $this->mode->create($data);
			}
			$count = $value['count'] ? $value['count'] : 0;
			$statued = $value['statued'] ? $value['statued'] : 0;
			$unstatued = $value['unstatued'] ? $value['unstatued'] : 0;
			$publish = $value['publish'] ? $value['publish'] : 0;
			$published = $value['published'] ? $value['published'] : 0;
			
			$sql = " UPDATE " . DB_PREFIX . "workload SET ";
			$sql .= " count = count + {$count} , 
						statued = statued + {$statued} ,
						unstatued = unstatued + {$unstatued} ,
						publish = publish + {$publish} ,
						published = published + {$published} ";
			$sql .= ' WHERE id = '.$vid;
			$this->db->query($sql);//更新总数相加
			
			//添加到详细表
			$detail = array(
				'wid'			=> $vid,
				'app_uniquedid'	=> $app ,
				'date'			=> $date,
				'count' 		=> $count , 
				'statued' 		=> $statued ,
				'unstatued' 	=> $unstatued ,
				'publish' 		=> $publish ,
				'published' 	=> $published ,
				'create_time'	=> TIMENOW,
			);
			$did = $this->mode->insert_data($detail, 'work_detail');
			
			if($value['column'])
			{
				foreach ($value['column'] as $column_id => $column)
				{
					$total = $column['total'] ? intval($column['total']) : 0;
					$successed = $column['success'] ? intval($column['success']) : 0;
					$column_info = array(
						'wid'			=> $vid,
						'column_id'		=> $column_id,
						'column_name'	=> $column['column_name'],
						'total'			=> $total,
						'successed'		=> $successed,
						'date'			=> $date,
					);
					if($column_ids[$vid] && in_array($column_id , $column_ids[$vid]))
					{
						$sql = " UPDATE " . DB_PREFIX . "column SET total = total + {$total} , successed = successed + {$statued}";
						$sql .= ' WHERE wid = '.$vid .' AND column_id = '.$column_id .' AND date = '.$date ;
						$this->db->query($sql);
					}
					else
					{
						$cid = $this->mode->insert_data($column_info, 'column');
					}
				}
			}
		}
		$this->addItem($ret);
		$this->output();
    }
	
	public function get_log_operation()
	{
		$static_date = $this->input['static_date'];
		$user_id = $this->input['user_id'];
		if(!$static_date)
    	{
    		$static_date = date("Y-m-d 00:00:00",strtotime("-1 day"));
    	}
    	$date = strtotime($static_date);
		if(!$this->settings['App_logs'])
    	{
    		$this->errorOutput('日志应用尚未安装');
    	}
    	$curl = new curl($this->settings['App_logs']['host'],$this->settings['App_logs']['dir']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','get_work');
		$curl->addRequestData('user_id',$user_id);
		$curl->addRequestData('start_time',$static_date);
		$curl->addRequestData('end_time',date("Y-m-d",$date+86400));
		$ret = $curl->request("admin/logs.php");
		$ret = $ret[0];
		if(!$ret)
		{
			$this->errorOutput('当天没有工作数据');
		}
		if($ret['total'] && is_array($ret['total']))
		{ //每个操作的总数入库
			foreach ($ret['total'] as $k=>$v)
			{
				if($k)
				{
					$data = array(
						'user_id' 		=> $user_id,
						'action'	=> $k,
						'count'		=> $v,
						'date'		=> $date,
					);
					$ac = $this->mode->insert_data($data, 'operation');
				}
			}
		}
		if($ret['total'] && is_array($ret['total']))
		{ //每个操作详细操作入库
			foreach ($ret['detail'] as $app=>$v)
			{
				if($v && is_array($v))
				{
					foreach ($v as $op_id => $op)
					{
						$operation = array(
							'user_id'		=> $user_id,
							'app_bundle'	=> $app,
							'operation_id'	=> $op_id,
							'operation_name'=> $op['op_name'],
							'count'			=> $op['count'],
							'action'		=> $op['action'],
							'date'			=> $date,
						);
						$op = $this->mode->insert_data($operation, 'operate_detail');
					}
				}
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new workload_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>