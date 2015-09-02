<?php
define('MOD_UNIQUEID','attribute_relate');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/attribute_relate_mode.php');
class attribute_relate extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new attribute_relate_mode();
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
		$orderby = '  ORDER BY ar.order_id DESC ';
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
			$condition .= " AND ar.id IN (".($this->input['id']).")";
		}
		
	    //按照角色筛选
        if($this->input['role_type_id'])
        {
            $condition .= " AND ar.role_type_id = '" .$this->input['role_type_id']. "' ";
        }
        
        //按照角色筛选
        if($this->input['attr_type_id'])
        {
            $condition .= " AND a.attr_type_id = '" .$this->input['attr_type_id']. "' ";
        }
        
        //按照UI来
        if($this->input['ui_id'])
        {
            $condition .= " AND ar.ui_id = '" .$this->input['ui_id']. "' ";
        }
		else 
		{
		    $this->errorOutput(NO_UI_ID);
		}
        
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND ar.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND ar.create_time <= '".$end_time."'";
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
					$condition .= " AND  ar.create_time > '".$yesterday."' AND ar.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  ar.create_time > '".$today."' AND ar.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  ar.create_time > '".$last_threeday."' AND ar.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  ar.create_time > '".$last_sevenday."' AND ar.create_time < '".$tomorrow."'";
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
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	//设置属性值
	public function set_attr_value()
	{
	    if(!$this->input['id'])
	    {
	        $this->errorOutput(NOID);
	    }
	    
	    $ret = $this->mode->getAttrById($this->input['id']);
	    if($ret)
	    {
	        $this->addItem($ret);
	        $this->output();
	    }
	}
}

$out = new attribute_relate();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();