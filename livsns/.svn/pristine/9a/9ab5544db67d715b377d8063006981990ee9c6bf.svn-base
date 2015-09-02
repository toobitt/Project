<?php
require_once './global.php';
define('MOD_UNIQUEID','template_style');
define('SCRIPT_NAME','template_style');
class template_style extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/style.class.php';
		$this->obj = new style();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function index(){}
	public function show()
	{
//		if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if(!in_array('template_style',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit = " LIMIT " . $offset . ", " . $count;
		$ret = $this->obj->show($condition . $limit);
		foreach($ret as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		if($id)
		{
			$limit = " AND id = " . $id;
		}
		else
		{
			$limit = " LIMIT 1";
		}
		$ret = $this->obj->detail($limit);
		if($ret)
		{
			$ret['create_time'] = date('Y-m-d H:i',$ret['create_time']);
			$ret['update_time'] = date('Y-m-d H:i',$ret['update_time']);
			if($ret['pic'])
			{
				$ret['pic'] = json_decode($ret['pic'],1);
				if(is_array($ret['pic']) && count($ret['pic'])>0)
				{
					$ret['pic_json'] = array();
					foreach($ret['pic'] as $k => $v)
					{
						$tmp = array();
						$tmp[0] = $v;
						$ret['pic_json'][$k] = htmlspecialchars(json_encode($tmp));
					}
				}
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	public function count()
	{
		$condition = $this->get_condition();
		$totalNum = $this->obj->count($condition);
		echo json_encode($totalNum);
	}
	public function get_condition()
	{
		$condition = '';
		//查询
		if($this->input['key'])
		{
			$condition .= " AND title LIKE '%" . trim(urldecode($this->input['key'])) . "%' ";
		}		
		//查询站点
		if($this->input['site_id'])
		{
			$condition .= " AND site_id = '". intval($this->input['site_id']) . "' OR site_id = 0 ";
		}	
		//查询创建的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND create_time > " . strtotime($this->input['start_time']);
		}
		//查询创建的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND create_time < " . strtotime($this->input['end_time']);	
		}
        if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
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
}
require_once ROOT_PATH . 'excute.php';
?>
