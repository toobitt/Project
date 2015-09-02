<?php
define('MOD_UNIQUEID','verify_bgpicture');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/verify_pic_font_mode.php');
class verify_bgpicture extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new verify_pic_font_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;		
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit,'bgpicture');
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$verify = $this->settings['App_verifycode'];
				$v['dir'] = $verify['protocol'].$verify['host'].'/'.$verify['dir'].'/data/pictures/'.$v['name'].'.'.$v['type'];
				$v['create_time'] = date('Y-m-d H:i',$v['create_time']);
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition,'bgpicture');
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		
		//权限
		/*
		$this->verify_content_prms(array('_action'=>'manage_verify_see'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		*/
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		if($this->input['state'] >= '0')
		{
			$condition .= " AND status = " . $this->input['state'];
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
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND weight <= " . $this->input['end_weight'];
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
		if($this->input['id'])
		{
			$condition = '';
			//权限
			/*
			$this->verify_content_prms(array('_action'=>'manage_verify_change'));
			if($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				//查看他人数据
				if(!$this->user['prms']['default_setting']['show_other_data'])
				{
					$condition .= ' AND user_id = '.$this->user['user_id'];
				}
				else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
				{
					$condition .= ' AND org_id IN (' . $this->user['slave_org'] .')';
				}
			}
			*/
			$ret = $this->mode->detail($this->input['id'],$condition,'bgpicture');
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
		else
		{
			$this->errorOutput(NOID);
		}
	}
}

$out = new verify_bgpicture();
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