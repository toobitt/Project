<?php
/***************************************************************************
* $Id: verifycode.php  2013-12-02
***************************************************************************/
define('MOD_UNIQUEID', 'cinema');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require_once(CUR_CONF_PATH . 'lib/cinema_mode.php');
class cinema extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->mode = new cinema_mode();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
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
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND c.id IN (".$this->input['id'].")";
		}
		$condition .= " AND c.status = 1";
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  c.title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND c.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND c.create_time <= '".$end_time."'";
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
					$condition .= " AND  c.create_time > '".$yesterday."' AND c.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  c.create_time > '".$today."' AND c.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  c.create_time > '".$last_threeday."' AND c.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  c.create_time > '".$last_sevenday."' AND c.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	public function detail()
	{
		if(intval($this->input['id']))
		{
			$ret = $this->mode->detail(intval($this->input['id']));
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
		else 
		{
			$this->errorOutput(NOID);
		}
	}
	
	
	/*
	 * 获取该影院所有的影片信息
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
	 * 获取改影院的排片信息
	 */
	public function get_project_info()
	{
		$movie_id = $this->input['movie_id']; //影片id
		$cinema_id = $this->input['cinema_id']; //影院id
		
		if(!$movie_id)
		{
			$this->errorOutput(NOMOVIEID);
		}
		if(!$cinema_id)
		{
			$this->errorOutput(NOCINEMAID);
		}
		
		$re = $this->mode->get_project_info($cinema_id, $movie_id);
		$this->addItem($re);
		$this->output();
	}
	
	
	public function count(){}

	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
}

$out = new cinema();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>