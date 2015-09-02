<?php
require_once('./global.php');
require_once (CUR_CONF_PATH.'lib/fastInput.class.php');
define('MOD_UNIQUEID','reporter_fast_input');
class interview extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->fastInput = new fastInput();
		$this->mNodes = array(
			'reporter_fastInput_sort'=>'记者列表',
		);
		unset($this->mPrmsMethods['audit']);
	}
	function __destruct()
	{
		parent::__destruct();
	}
    function index()
    {
    	
    }
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):20;
		$orderby = ' ORDER BY order_id DESC';
		$condition = $this->get_condition();
		$data = $this->fastInput->show($condition,$orderby,$offset,$count);
		if (!empty($data))
		{
			foreach ($data as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}

	public function count()
	{
		$ret = $this->fastInput->count($this->get_condition());
		echo $ret;
	}

	public function get_condition()
	{
		$condition = '';
		
		/**************权限控制开始**************/
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND fi.user_id = '.$this->user['user_id'];
			}
			if($this->user['prms'][MOD_UNIQUEID]['show']['node'])
			{
				$authnode_str = '';
				foreach ($this->user['prms'][MOD_UNIQUEID]['show']['node'] as $nodevar=>$authnode)
				{
					$authnode_str = $authnode ? implode(',', $authnode) : '';
					if($authnode_str)
					{
						$condition .= 'AND fi.sort_id IN ('.$authnode_str.')'; 
					}else {
						$condition .= 'AND fi.sort_id = 0'; 
					}
				}
			}
		}

		/**************权限控制结束**************/
		
		if($this->input['k'])
		{
			$condition .= ' AND content LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND fi.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND fi.create_time <= ".$end_time;
		}
		if($this->input['fi_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['fi_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  fi.create_time > ".$yesterday." AND fi.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  fi.create_time > ".$today." AND fi.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  fi.create_time > ".$last_threeday." AND fi.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND fi.create_time > ".$last_sevenday." AND fi.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function detail()
	{
		if (!$this->input['id'])
		{
			return ;
		}
		$data = $this->fastInput->detail(urldecode($this->input['id']));	
		$this->addItem($data);
		$this->output();
	}
	/**
	 * 获取分类信息
	 * 
	 */
	public function sort()
	{
		$data = $this->fastInput->get_sort();
		$this->addItem($data);
		$this->output();
	}

}

$ouput= new interview();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
