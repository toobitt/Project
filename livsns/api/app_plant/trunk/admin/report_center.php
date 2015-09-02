<?php
define('MOD_UNIQUEID','report_center');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/report_center_mode.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');
require_once(ROOT_PATH . 'lib/class/members.class.php');
class report_center extends adminReadBase
{
	private $mode;
	private $app;
    private $members;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new report_center_mode();
		$this->app = new app();
        $this->members = new members();
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
		$orderby = '  ORDER BY id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
        foreach($ret as $k=>$v)
        {
            if($v['reported_uid'])
            {
                $member_ids[] = $v['reported_uid'];
            }
        }
        if($member_ids)
        {
            $members = array();
            $member_ids = implode(',', $member_ids);
            $temp_members = $this->members->get_newUserInfo_by_ids($member_ids);
            if ($temp_members && !empty($temp_members) && is_array($temp_members))
            {
                foreach ($temp_members as $val)
                {
                    $members[$val['member_id']] = $val;
                }
            }
        }



		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
                $v['guid'] = '';
                if($members)
                {
                    foreach($members as $ko=>$vo)
                    {
                        if($v['reported_uid'] == $ko)
                        {
                            $v['guid'] = $vo['guid'];
                        }
                    }
                }
                $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
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
        if($this->input['type'])
        {
            $condition .= " AND type IN (".html_entity_decode($this->input['type']).")";
        }
		else
		{
			$condition .= " AND type IN ('app','group')";
		}

        if($this->input['app_id'])
        {
            $condition .= " AND app_id IN (".($this->input['app_id']).")";
        }

		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  app_name  LIKE "%'.trim(($this->input['k'])).'%"';
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
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				if($ret['is_debug'] == 1)
				{
					$ret['is_debug'] = '测试版';
				}
				elseif($ret['is_debug'] == 0)
				{
					$ret['is_debug'] = '正式版';
				}
				$this->addItem($ret);
				$this->output();
			}
		}
	}
}

$out = new report_center();
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