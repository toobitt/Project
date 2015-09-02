<?php
define('MOD_UNIQUEID','period');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/period_mode.php');
class period extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new period_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$epaper_id = $this->input['epaper_id'];
		
		
		$this->verify_content_prms(array('_action'=>'manage_epaper')); //权限
		//允许进入往期页面，进行报刊信息维护
		/**************节点权限*************/
		$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_epaper_ids  && implode(',', $prms_epaper_ids) != '-1' && !in_array($epaper_id,$prms_epaper_ids))
		{
			$this->errorOutput('没有权限');
		}
		/*********************************/

		$sql = "SELECT id,name,picture,init_stage,init_time,pub_company,pub_no,code_name,sponsor,sort_id FROM " . DB_PREFIX . "epaper WHERE id=" . $epaper_id;
		$ret2 = $this->db->query_first($sql);
		$ret2['picture'] = unserialize($ret2['picture']);
		$ret2['init_time'] = date('Y-m-d',$ret2['init_time']);
		
		//$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		//$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 15;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY p.order_id DESC,p.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
	
		
		$ret['epaper_info'] = $ret2;
		$this->addItem($ret);
		
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
		
		/*************权限判断***************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND p.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND p.org_id IN (' . $this->user['slave_org'] . ')';
			}
		}
		/**********************************/
		
		if($this->input['epaper_id'])
		{
			$condition .= " AND p.epaper_id=" . $this->input['epaper_id'];
		}
		
		if($this->input['status'] >= '0')
		{
			$condition .= " AND p.status = " . $this->input['status'];
		}
		if($this->input['key'] || trim(($this->input['key']))== '0')
		{
			$condition .= ' AND  p.period_num  LIKE "%'.trim(($this->input['key'])).'%"';
		}
		
		//创建者
		if($this->input['user_name'] || trim(($this->input['user_name']))== '0')
		{
			$condition .= " AND p.user_name = '".trim($this->input['user_name'])."'";
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND p.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND p.create_time <= '".$end_time."'";
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
					$condition .= " AND  p.create_time > '".$yesterday."' AND p.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  p.create_time > '".$today."' AND p.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  p.create_time > '".$last_threeday."' AND p.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  p.create_time > '".$last_sevenday."' AND p.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	public function detail()
	{
		//权限
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'manage_period')); //管理往期的权限
		}
		
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	//ajax请求叠下页数据
	public function get_page_by_stack()
	{
		
		$period_id = intval($this->input['period_id']);
		if(!$period_id)
		{
			$this->errorOutput('未发现期刊id');
		}
		
		$stack_id = intval($this->input['stack_id']);
		if(!$stack_id)
		{
			$this->errorOutput('未发现叠id');
		}
		
		$data = $this->mode->get_page_ajax($period_id,$stack_id);
		$this->addItem($data);
		$this->output();
	}
	
	
	//根据素材Id获取素材信息
	public function get_mater_by_id()
	{
		$mater_id = $this->input['mater_id'];
		if(!$mater_id)
		{
			$this->errorOutput('未发现图片id');
		}
		
		$data = $this->mode->getMaterialById($mater_id);
		
		if(!$data)
		{
			$data = 0;
		}
		$this->addItem($data);
		$this->output();
	}
	
	//检查新增一期或更新一期的日期是否合理
	public function check_period_date()
	{
		$epaper_id = $this->input['epaper_id'];
		$period_date = strtotime($this->input['period_date']);
		$period_id = $this->input['period_id'];
		
		if(!$epaper_id)
		{
			$this->errorOutput('没有epaper_id');
		}
		if(!$period_date)
		{
			$this->errorOutput('没有日期');
		}
		
		$arr = array();
		if(!$period_id)//新增
		{
			$sql = " SELECT id FROM " . DB_PREFIX . "period WHERE epaper_id = " . $epaper_id . " AND period_date = " . $period_date;
			$re = $this->db->query_first($sql);
			if($re)
			{
				//echo 1;	//这一天的期刊已存在
				$arr = array('error'=>1);
			}
		}
		else//更新
		{
			$sql = " SELECT period_date FROM " . DB_PREFIX . "period WHERE id = " . $period_id;
			$re = $this->db->query_first($sql);
			if($re)
			{
				$old_period_date = $re['period_date'];
			}
			$sql = " SELECT id FROM " . DB_PREFIX . "period WHERE epaper_id = " . $epaper_id . " AND period_date = " . $period_date;
			$re = $this->db->query_first($sql);
			if($re && $old_period_date != $period_date)
			{
				//echo 1;	//这一天的期刊已存在
				$arr = array('error'=>1);
			}
		}
		
		if(empty($arr))
		{
			$arr = array('error'=>0);
		}
		
		$this->addItem($arr);
		$this->output();
	}
	
	
	public function append_epaper()
	{
		$epaper_id = $this->input['epaper_id'];
		
		if(!$epaper_id)
		{
			return false;
		}
		
		$sql = "SELECT id,name,cur_stage,cur_time,init_time,sort_id FROM " . DB_PREFIX . "epaper WHERE id = " . $epaper_id;
		$res = $this->db->query_first($sql);
		
		if($res['cur_time'])
		{
			if(in_array($res['sort_id'],array(1,2,3)))//日报，晨报，晚报
			{
				$res['cur_time'] = date('Y-m-d',$res['cur_time']+86400);
			}
			else if($res['sort_id'] == 4)//周报
			{
				$res['cur_time'] = date('Y-m-d',$res['cur_time']+86400*7);
			} 
			else if($res['sort_id'] == 5)//月报
			{
				$res['cur_time'] = date('Y-m-d',strtotime('+1 month',$res['cur_time']));
			}
			else if ($res['sort_id'] == 6)//季报
			{
				$res['cur_time'] = date('Y-m-d',strtotime('+3 month',$res['cur_time']));
			}
			else if ($res['sort_id'] == 7)//旬报
			{
				$res['cur_time'] = date('Y-m-d',strtotime('+10 day',$res['cur_time']));
			}
			$res['cur_stage'] = $res['cur_stage'] + 1;
		}
		else 
		{
			$res['cur_time'] = $res['init_time'] ? date('Y-m-d',$res['init_time']) : date('Y-m-d',TIMENOW);
			$res['cur_stage'] = $res['init_stage'] ? $res['init_stage'] : 1;
		}
			
		unset($res['init_time'],$res['sort_id']);
		$this->addItem($res);
		$this->output();
	}
}

$out = new period();
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