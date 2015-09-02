<?php
require_once('./global.php');
require_once (CUR_CONF_PATH.'lib/fastInputSort.class.php');
define('MOD_UNIQUEID','reporter_fast_input_sort');
class interview extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->sort = new fastInputSort();
		$this->mPrmsMethods= array(
								'show'=>array(
										'name' => '查看',
										'node' => false,
										),
								'create'=>array(
										'name'=>'创建',
										'node'=>false,
										),
								'update'=>array(
										'name'=>'更新',
										'node'=>false,
										),
								'delete'=>array(
										'name'=>'删除',
										'node'=>false,
										),
								'sort'=>array(
										'name'=>'排序',
										'node'=>false,
										),	
								);
	}
	function __destruct()
	{
		parent::__destruct();
	}
    public function index()
    {
    
    }
	public function show()
	{
		/**************权限控制开始**************/
		$this->verify_content_prms();
		/**************权限控制结束**************/
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):20;
		$orderby = ' ORDER BY order_id DESC ';
		$condition = $this->get_condition();
		$data = $this->sort->show($condition,$orderby,$offset,$count);
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
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'fastInput_sort '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	public function get_condition()
	{
		$condition = '';
		/**************权限控制开始**************/
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
				
		}

		/**************权限控制结束**************/
		if($this->input['k'])
		{
			$condition .= ' AND name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= ".$end_time;
		}
		if($this->input['sort_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['sort_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	function detail()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->sort->detail(urldecode($this->input['id']));		
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_selected_node_path()
	{
		$ids = $this->input['id'];
		if(!isset($ids))
		{
			$this->errorOutput(NO_ID);
		}
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'fastInput_sort  WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$nodes = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['fid'] = 0;
			$row['parents'] = $row['id'];
			$row['childs'] = $row['id'];
			$row['is_last'] = 1;
     	 	$row['depath'] = 1;
      		$row['is_auth'] = 1;
			$nodes[][$row['id']] = $row;
		}
		if (!empty($nodes))
		{
			foreach ($nodes as $key=>$val)
			{
				$this->addItem($val);
			}
		}
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
