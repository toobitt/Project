<?php
define('MOD_UNIQUEID','movie');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/movie_mode.php');
class movie extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new movie_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'manage'));
		/*********权限验证结束*********/
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
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
		/***** 权限 *****/
		$this->verify_content_prms(array('_action'=>'manage'));
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
		/***** 权限 *****/
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		if($this->input['state'] >= '0')
		{
			$condition .= " AND status = " . $this->input['state'];
		}
		if($this->input['type'])
		{
			$condition .= ' AND type LIKE "%'.trim(($this->input['type'])).'%"';
		}
		if($this->input['area'])
		{
			$condition .= " AND area =".($this->input['area']);
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
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
		/***************权限*****************/
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
		/***********************************/
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
	
	/*
	 * 获取所有影片类型
	 */
	public function get_movie_type()
	{
		global $gGlobalConfig;
		foreach((array)$gGlobalConfig['movie_type'] as $k => $v)
		{
			$type = array(
				'id' 	=> $k,
				'name'	=> $v,
			);
			$this->addItem($type);
		}
		$this->output();
	}
	
	/*
	 * 获取所有地区类型
	 */
	public function get_movie_area()
	{
		global $gGlobalConfig;
		foreach((array)$gGlobalConfig['area'] as $k => $v)
		{
			$type = array(
				'id' 	=> $k,
				'name'	=> $v,
			);
			$this->addItem($type);
		}
		$this->output();
	}
	
	/*
	 * 获取放映改影片的所有影院信息
	 */
	public function get_cinema_info()
	{
		$movie_id = $this->input['movie_id']; //影片id
		$province_id = $this->input['province_id']; //省
		$city_id = $this->input['city_id']; //市
		$area_id = $this->input['area_id']; //区
		$cinema_id = $this->input['cinema_id']; //影院id
		
		if(!$movie_id)
		{
			$this->errorOutput('缺少影片id');
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "cinema WHERE id IN(SELECT cinema_id FROM " .DB_PREFIX. "project_list WHERE movie_id = " .$movie_id. " GROUP BY cinema_id)" .$condition;
		if($province_id && $city_id && $area_id)
		{
			$condition .= " AND province_id = " .$province_id. " AND city_id = " .$city_id. " AND area_id = " .$area_id;
		}
		$query = $this->db->query($sql);	
		while($row = $this->db->fetch_array($query))
		{
			$row['stime'] = date('H:i',$row['stime']);
			$row['etime'] = date('H:i',$row['etime']);
			$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
			//$row['content'] = stripslashes($row['content']);
			$this->addItem($row);
		}
		$this->output();
	}
	
	/*
	 * 获取改影片的排片信息
	 */
	public function get_project_info()
	{
		$movie_id = $this->input['movie_id']; //影片id
		$cinema_id = $this->input['cinema_id']; //影院id
		
		if(!$movie_id)
		{
			$this->errorOutput('缺少影片id');
		}
		if(!$cinema_id)
		{
			$this->errorOutput('缺少影院id');
		}
		
		$re = $this->mode->get_project_info($cinema_id, $movie_id);
		$this->addItem($re);
		$this->output();
	}
	
}

$out = new movie();
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