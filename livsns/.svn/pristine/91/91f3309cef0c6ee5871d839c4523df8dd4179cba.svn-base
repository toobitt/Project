<?php
require_once(CUR_CONF_PATH . 'lib/workload_mode.php');
require_once(CUR_CONF_PATH . 'lib/appset_mode.php');
include_once ROOT_PATH.'lib/class/auth.class.php';
class statistics extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->mode = new workload_mode();
		$this->appset = new appset_mode();
		$this->auth = new auth();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function get_workloads($app,$static_date)
	{
		$appname = $app['app_uniqueid'];
		$files = $app['filename'] ? $app['filename'] : $appname.'.php';
		$func = $app['functions'] ? $app['functions'] : 'statistics';
	   	if(!$this->settings["App_{$appname}"])
    	{
    		return false;
    	}
    	$curl = new curl($this->settings["App_{$appname}"]['host'],$this->settings["App_{$appname}"]['dir']);
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a',$func);
		$curl->addRequestData('static_date',$static_date);
		$ret = $curl->request("admin/{$files}");
		$ret = $ret[0]['data'];
		if(!$ret)
		{
			return false;
		}
    	$date = strtotime($static_date);
    	$year = date('Y',$date);
    	$month = date('m',$date);
    	$week = date('W',$date);
		if(TIMENOW >= strtotime(date('Y-12-26')) || TIMENOW < strtotime(date('Y-01-07')))
		{
			if($month == '01' && $week > '05' )
			{
				$week = 1;
			}
			if($week == '01' && $month === '12')
			{
				$week = date('W',strtotime('-1 week'))+1;
			}
		}
		$user = array();
		$sql = 'SELECT w.user_id,w.id,c.column_id FROM '.DB_PREFIX. 'workload w LEFT JOIN '.DB_PREFIX.'column c  ON w.id = c.wid  WHERE w.date = "'.$date.'"';
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$user[] = $r['user_id'];
			$vids[$r['user_id']] = $r['id'];
			$column_ids[$r['id']][] = $r['column_id'];
		}
		$users = $this->auth->getAllUser();
		if(!$users)
		{
			return false;
		}
		foreach ($users as $k =>$userinfo)
		{
			$org_id = $userinfo['org_id'];
			$user_id = $userinfo['id'];
			$value = $ret[$user_id];
			$data = array(
				'user_id'	=> $userinfo['id'],
				'user_name'	=> $userinfo['user_name'],
				'avatar'	=> $userinfo['avatar'] ? serialize($userinfo['avatar']) : '',
				'org_id'	=> $org_id,
				'date'		=> $date,
				'year'		=> date('Y',$date),
				'month'		=> date('m',$date),
				'week'		=> $week,
				'create_time'=> TIMENOW,
				'create_user_id'=> $this->user['user_id'] ?  $this->user['user_id'] : 0,
				'create_user_name'=> $this->user['user_name'] ?  $this->user['user_name'] : '计划收录',
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
			if($value)
			{
				//添加到详细表
				$detail = array(
					'wid'			=> $vid,
					'app_uniquedid'	=> $appname ,
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
			
			$orgs[$org_id]['count'] += $count;
			$orgs[$org_id]['statued'] += $statued;
			$orgs[$org_id]['unstatued'] += $unstatued;
			$orgs[$org_id]['publish'] += $publish;
			$orgs[$org_id]['published'] += $published;
			
			$wtotal['count'] += $count;
			$wtotal['statued'] += $statued;
			$wtotal['unstatued'] += $unstatued;
			$wtotal['publish'] += $publish;
			$wtotal['published'] += $published;
			
		}
		$wtotal['app_uniquedid'] = $appname;
		$wtotal['date']		= $date;
		$wtotal['year']		= $year;
		$wtotal['month']	= $month;
		$wtotal['week']		= $week;
		$tid = $this->mode->insert_data($wtotal, 'workload_total');
		if($orgs)
		{
			foreach ($orgs as $org_id=>$v)
			{
				if($v['count'])
				{
					$v['app_uniquedid'] = $appname;
					$v['date']		= $date;
					$v['year']		= $year;
					$v['month']		= $month;
					$v['week']		= $week;
					$v['org_id']	= $org_id;
					$oid = $this->mode->insert_data($v, 'workload_org');
				}
			}
		}
		return true;
	}
	
	public function get_log_operation($static_date)
	{
		$condition = ' AND state = 1';
		$appinfo = $this->appset->show($condition);
    	foreach ($appinfo as $k=>$v)
    	{
    		$app_bundle[] = $v['app_uniqueid'] ;
    	}
    	$bundles = implode(',',$app_bundle);
		if(!$static_date)
    	{
    		$static_date = date('Y-m-d',strtotime("-1 day"));
    	}
    	$date = strtotime($static_date);
		if(!$this->settings['App_logs'])
    	{
    		return false;
    	}
    	$curl = new curl($this->settings['App_logs']['host'],$this->settings['App_logs']['dir']);
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','get_workload');
		$curl->addRequestData('start_time',$static_date);
		$curl->addRequestData('end_time',date('Y-m-d',$date+86400));
		$curl->addRequestData('app',$bundles);
		$curl->addRequestData('para','app');
		$back = $curl->request("admin/logs.php");
		$back = $back[0];
		if(!$back)
		{
			return false;
		}
		foreach ($back as $user_id => $ret)
		{
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
							'year'		=> date('Y',$date),
							'month'		=> date('m',$date),
							'week'		=> date('W',$date),
							'create_time'=> TIMENOW,
							'create_user_id'=> $this->user['user_id'] ?  $this->user['user_id'] : 0,
							'create_user_name'=> $this->user['user_name'] ?  $this->user['user_name'] : '计划收录',
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
		}
		return true;
	}
}