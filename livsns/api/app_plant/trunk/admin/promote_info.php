<?php
define('MOD_UNIQUEID','promote_info');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/promote_info_mode.php');
require_once(ROOT_PATH . 'lib/class/applant.class.php');
class promote_info extends adminReadBase
{
	private $mode;
    private $applant;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new promote_info_mode();
        $this->applant = new applant();
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
		$ret = $this->mode->show($condition,$orderby,$limit);

        foreach($ret as $k => $v)
        {
            $app_ids_arr[] = $v['app_id'];
        }
        $app_ids = implode(",",$app_ids_arr);
        $appInfo = $this->applant->getAppinfoByAppids($app_ids);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				foreach($appInfo as $ko=>$vo)
                {
                    if($v['app_id'] == $vo['id'])
                    {
                        $v['app_name'] = $vo['name'];
                    }
                }
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
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  user_name  LIKE "%'.trim(($this->input['k'])).'%"';
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

        if(isset($this->input['status']))
        {
            $condition .= ' AND  status='.intval(($this->input['status'])).'';
        }
        else
        {
            $condition .= ' AND  status=1';
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
                $appInfo = $this->applant->getAppinfoByAppids($ret['app_id']);
                if($appInfo)
                {
                    foreach($appInfo as $k=>$v)
                    {
                        if($ret['app_id'] == $v['id'])
                        {
                            $ret['app_name'] = $v['name'];
                        }
                    }
                }
				$this->addItem($ret);
				$this->output();
			}
		}
	}
}

$out = new promote_info();
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