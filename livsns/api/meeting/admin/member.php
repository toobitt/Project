<?php
define('MOD_UNIQUEID','member');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class member extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new member_mode();
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
		if($this->input['mrank'])
		{
			$orderby = '  ORDER BY m.exchange_num DESC ';
		}
		else 
		{
			$orderby = '  ORDER BY m.order_id DESC,m.id DESC ';
		}
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
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
		if($this->input['id'])
		{
			$condition .= " AND m.id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND m.name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if(intval($this->input['is_sign']) == 1)
		{
			$condition .= " AND m.is_sign = 0 ";//未签到
		}
		else if(intval($this->input['is_sign']) == 2)
		{
			$condition .= " AND m.is_sign = 1 ";//已签到
		}
		
		if($this->input['guest_type'])
		{
			if(intval($this->input['guest_type']) == 1)
			{
				$condition .= " AND (a.guest_type = '" .$this->input['guest_type']. "' OR m.activate_code_id = 0) ";
			}
			else 
			{
				$condition .= " AND a.guest_type = '" .$this->input['guest_type']. "' ";
			}
		}

		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND m.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND m.create_time <= '".$end_time."'";
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
					$condition .= " AND  m.create_time > '".$yesterday."' AND m.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  m.create_time > '".$today."' AND m.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  m.create_time > '".$last_threeday."' AND m.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  m.create_time > '".$last_sevenday."' AND m.create_time < '".$tomorrow."'";
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
			$_memberInfo = $this->mode->detail($this->input['id']);
			if($_memberInfo)
			{
				$_memberInfo['vcard_url'] = 'http://' . $this->settings['App_meeting']['host'] . '/' . $this->settings['App_meeting']['dir'] .'data/vcard/' . $_memberInfo['vcard_pic_name'];
				$_memberInfo['avatar'] = $_memberInfo['avatar']?unserialize($_memberInfo['avatar']):array();
				$this->addItem($_memberInfo);
				$this->output();
			}
		}
	}
	
	//获取已经和哪些嘉宾交换过名片
	public function get_exchanged_members()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 200;
		$orderby = '  ORDER BY e.create_time DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		//获取当前用户和哪些人已经交换过了名片
		$_exchanged = $this->mode->get_exchanged_members_by_id($this->input['id'],$orderby,$limit);
		if(!$_exchanged)
		{
			$_exchanged = array();
		}
		
		$this->addItem($_exchanged);
		$this->output();
	}
}

$out = new member();
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