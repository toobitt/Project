<?php
define('MOD_UNIQUEID','catalog_sort');//模块标识
require('global.php');
require_once CUR_CONF_PATH . 'lib/catalog_sort.class.php';
class catalogsortApi extends adminReadBase
{
	private $catalogsort;
	public function __construct()
	{
		parent::__construct();
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'show'));
		/*********权限验证结束*********/
		$this->catalogsort = new catalogsort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	/*
	public function show_node()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition 	= $this->get_condition();	
	   if($offset || $count)
	   {
	   	  $limit = " LIMIT " . $offset . " , " . $count ;  //分页
	   }
		$info 	= $this->catalogsort->show($condition,$limit);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}
	*/
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$sql = "SELECT * FROM " . DB_PREFIX . "field_sort WHERE 1 ";
		$condition = $this->get_condition();
		if($condition)
	   	{
			$sql.= $condition;
	 	}
	   $sql .= " ORDER BY order_id DESC ";
	   $limit = " LIMIT " . $offset . " , " . $count ;  //分页
		if($limit)
	   {
	   	$sql.=$limit;
	   }
			$query=$this->db->query($sql);
			$node_arr=array();
			while ($row=$this->db->fetch_array($query))
			{
				$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
				$row['update_time'] = date('Y-m-d H:i:s',$row['update_time']);
				$node_arr[$row['id']]=array('id'=>$row['id'],'name'=>$row['catalog_sort_name'],'create_time'=>$row['create_time'],'update_time'=>$row['update_time'],'user_name'=>$row['user_name'],'catalog_sort'=>$row['catalog_sort'],'order_id'=>$row['order_id'],'fid'=>'0','childs'=>$row['id'],'parents'=>$row['id'],'depath'=>'1','is_last'=>'1');
			}
			foreach ($node_arr as $node)
			$this->addItem($node);
			$this->output();
	}

	public function detail()
	{
		$condition = $this->get_condition();
		if (empty($condition))
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$info = $this->catalogsort->detail($condition);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->catalogsort->count($condition);
		echo json_encode($info);
	}

	private function get_condition()
	{
		$condition = '';
		$catalog_sort_id = isset($this->input['id'])?trim($this->input['id']):'';
		  /**************权限控制开始**************/
		/**
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{			
			if($authnode=$this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				if(is_array($authnode))
				{	
					//如果没有_id就查询出所有权限所允许的节点下的视频包括其后代元素
					if(!$catalog_sort_id)
					{
						$condition = " AND id IN (".implode(',', $authnode).",0)";
					}
					else if($catalog_sort_id)
					{
						$catalog_sort_arr = explode(',', $catalog_sort_id);
						if($catalog_sort_arr&&is_array($catalog_sort_arr))
						{
							$_catalog_sort_arr = array();
							foreach ($catalog_sort_arr as $v)
							{
								if(in_array($v,$authnode))
								{
									$_catalog_sort_arr[] = $v;//如果传分类id,则记录拥有权限的分类id
								}
							}
							if($_catalog_sort_arr)
							{
								$condition = " AND id  IN " . implode(',', $catalog_sort_id) ; 
							}
							else {
								$this->errorOutput(NO_PRIVILEGE);//如果记录id为空,认为传过来的id无权限,则报错.
							}
						}
					}
				}
			}
		}
		else 
		{
			if(!empty($catalog_sort_id))
			{
					$condition = " AND id IN (" . $catalog_sort_id . ")"; 
			}
		}
		*/
		/**************权限控制结束**************/
		if(!empty($catalog_sort_id))
		{
			$condition = " AND id IN (" . $catalog_sort_id . ")"; 
		}
		if (isset($this->input['switch']) && $this->input['switch'] != -1)//开关
		{
			$condition .= ' AND switch = '.intval($this->input['switch']);
		}
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND catalog_sort_name LIKE \'%' . trim($this->input['k']) . '%\'';
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
		if($this->input['catalog_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['catalog_time']))
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

}

$out = new catalogsortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>