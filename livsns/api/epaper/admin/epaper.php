<?php
define('MOD_UNIQUEID','epaper');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/epaper_mode.php');
class epaper extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		$this->mPrmsMethods = array(
		'manage_epaper'		=>'管理报刊',
		'manage_period'		=>'管理往期',
		'audit'				=>'审核',
		'_node'=>array(
			'name'=>'报刊名称',
			'filename'=>'epaper_sort.php',
			'node_uniqueid'=>'epaper_sort',
			),
		);
		parent::__construct();
		$this->mode = new epaper_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$this->verify_content_prms(array('_action'=>'manage_epaper')); //权限
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY e.order_id DESC,e.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$epaper = DB_PREFIX . "epaper";
		$period = DB_PREFIX . "period";
		$material = DB_PREFIX . "material";
		$sql = "SELECT e.id,e.period_id,e.name,e.user_name as e_user_name,e.picture,e.sort_id,e.cur_stage,e.cur_time,e.init_time,e.create_time,e.init_stage,p.id as pid,p.indexpic_id,p.status,p.period_date,p.update_time,p.period_num,p.stack_num,p.page_num,m.id as m_id,m.host,m.dir,m.filepath,m.filename FROM ".$epaper." e 
				LEFT JOIN ".$period." p 
					ON e.period_id=p.id 
				LEFT JOIN ".$material." m 
					ON p.indexpic_id=m.id 
				WHERE 1 " . $condition . $orderby . $limit;
		
		$query = $this->db->query($sql);
		while($v = $this->db->fetch_array($query))
		{
			$v['user_name'] = $v['e_user_name'];
			if(!$v['period_id'])
			{
				$v['period_date'] =$v['init_time'];
				$v['period_num'] = $v['init_stage'];
				
				$v['update_time'] = $v['create_time'];
				
				$v['page_num'] = 0;
				$v['stack_num'] = 0;
			}
			if($v['cur_time'])
			{
				if(in_array($v['sort_id'],array(1,2,3)))//日报，晨报，晚报
				{
					$v['cur_time'] = date('Y-m-d',$v['cur_time']+24*3600);
				}
				else if($v['sort_id'] == 4)//周报
				{
					$v['cur_time'] = date('Y-m-d',$v['cur_time']+24*3600*7);
				} 
				else if($v['sort_id'] == 5)//月报
				{
					$v['cur_time'] = date('Y-m-d',$v['cur_time']+24*3600*30);
				}
				else if ($v['sort_id'] == 6)//季报
				{
					$v['cur_time'] = date('Y-m-d',$v['cur_time']+24*3600*120);
				}
				else if ($v['sort_id'] == 7)//旬报
				{
					$v['cur_time'] = date('Y-m-d',$v['cur_time']+24*3600*365);
				}
				$v['cur_stage'] = $v['cur_stage'] + 1;
			}
			else 
			{
				$v['cur_time'] = $v['init_time'] ? date('Y-m-d',$v['init_time']) : date('Y-m-d',TIMENOW);
				$v['cur_stage'] = $v['init_stage'] ? $v['init_stage'] : 1;
			}
			
			$v['picture'] = unserialize($v['picture']);
			$v['period_date'] = date('Y-m-d',$v['period_date']);
			$v['update_time'] = date('Y-m-d H:i',$v['create_time']);
			$v['index_pic'] = $v['host'].$v['dir'].$v['filepath'].$v['filename'];
			
			$this->addItem($v);
		}
		$this->output();
		
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他人数据
			/*if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND e.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND e.org_id IN (' . $this->user['slave_org'] .')';
			}*/
			//节点权限判断
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str && $authnode_str !=-1)
				{
					$condition .= ' AND e.id IN (' . $authnode_str . ')'; 
				}
			}
		}
		if($this->input['status'] >= '0')
		{
			$condition .= "AND p.status = " . $this->input['status'];
		}
		if($this->input['id'])
		{
			$condition .= " AND e.id IN (".($this->input['id']).")";
		}
		
		if($this->input['key'] || trim(($this->input['key']))== '0')
		{
			$condition .= ' AND  e.name  LIKE "%'.trim(($this->input['key'])).'%"';
		}
		//创建者
		if($this->input['user_name'] || trim(($this->input['user_name']))== '0')
		{
			$condition .= " AND e.user_name = '".trim($this->input['user_name'])."'";
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND e.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND e.create_time <= '".$end_time."'";
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
					$condition .= " AND  e.create_time > '".$yesterday."' AND e.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  e.create_time > '".$today."' AND e.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  e.create_time > '".$last_threeday."' AND e.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  e.create_time > '".$last_sevenday."' AND e.create_time < '".$tomorrow."'";
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
			//权限判断
			$this->verify_content_prms(array('_action'=>'manage_epaper'));
			$node = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
			if($node && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if(implode(',', $node) != -1 && !in_array($this->input['id'],$node))
				{
					$this->errorOutput('没有权限编辑此报刊');
				}
			}
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
}

$out = new epaper();
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