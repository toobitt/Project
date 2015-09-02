<?php
require('global.php');
define('MOD_UNIQUEID','notice');//模块标识
define('SCRIPT_NAME', 'notice');
class notice extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/station.class.php');
		$this->obj = new station();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	function  show()
	{	
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;	
		$count = $this->input['count'] ? intval($this->input['count']) : 20;				
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$condition = $this->get_condition();
		$sql = "SELECT n.*,s.name station_name FROM " . DB_PREFIX . "notice n
				LEFT JOIN ".DB_PREFIX."station s 
				ON n.station_id=s.id 
				WHERE 1 " . $condition; 
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{ 	
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			switch ($row['state'])
			{
				case 0 :
					$row['audit'] = '待审核';
					break;
				case 1 :
					$row['audit'] = '已审核';
					break;
				default:
					$row['audit'] = '已打回';
					break;
			}
			if($row['station_id'] == -1)
			{
				$row['station_name'] = '全局公告';
			}
			$this->addItem($row);
		}
		$this->output();	
	}

	function detail()
	{	
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT * FROM ".DB_PREFIX."notice WHERE id = ".$id;
		$info = $this->db->query_first($sql);
		$this->addItem($info);
		$this->output();
	}

	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 */
	public function count()
	{	
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'notice n WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 */
	private function get_condition()
	{		
		$condition = '';
		//查询应用分组
		if($this->input['k'])
		{
			$condition .= ' AND n.title LIKE "%'.trim($this->input['k']).'%"';
		}
		
		//创建者
		if($this->input['user_name'])
		{
			$condition .= " AND n.user_name = '".trim($this->input['user_name'])."'";
		}
		if($this->input['id'])
		{
			$condition .= ' WHERE n.id = '.intval($this->input['id']);
		}
		if (isset($this->input['state']) && $this->input['state'] != -1)
		{
			$condition .= ' AND n.state = '.intval($this->input['state']);
		}
		if($this->input['station_id'])
		{
			$condition .= ' AND n.station_id = '.intval($this->input['station_id']);
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND n.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND n.create_time <= ".$end_time;
		}
		
		if($this->input['date'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND n.create_time > ".$yesterday." AND n.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND n.create_time > ".$today." AND n.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND n.create_time > ".$last_threeday." AND n.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND n.create_time > ".$last_sevenday." AND n.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		$condition .=" ORDER BY n.order_id DESC,n.create_time DESC  ";
		
		return $condition;
	}
	public function get_station()
	{
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'station ORDER BY name';
		$q = $this->db->query($sql);
		$data = array();
		while ($row = $this->db->fetch_array($q))
		{
			$data[$row['id']] = $row['name'];
		}
		$this->addItem($data);
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');

?>
