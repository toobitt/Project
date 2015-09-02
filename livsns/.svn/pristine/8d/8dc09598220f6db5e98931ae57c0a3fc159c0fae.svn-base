<?php
class activate_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "device  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
			$info[] = $r;
		}
		return $info;
	}
	
	//获取激活量按照天来列出来
	public function getActivateNum($condition = '')
	{
		$sql = "SELECT DATE_FORMAT(FROM_UNIXTIME(create_time),'%Y-%m-%d') AS time,SUM(activate_num) AS total_activates FROM "  .DB_PREFIX. "device WHERE 1 " . $condition . " GROUP BY time ";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[$r['time']] = $r['total_activates'];
		}
		return $ret;
	}
	
	//获取活跃数按照天列出来
	public function getLivenessNums($condition = '')
	{
		$sql = "SELECT DATE_FORMAT(FROM_UNIXTIME(l.create_time),'%Y-%m-%d') AS time,COUNT(l.id) AS total_liveness FROM " .DB_PREFIX. "device d LEFT JOIN " .DB_PREFIX. "liveness l ON l.device_id = d.id WHERE 1" . $condition . " GROUP BY time ";
		$q = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[$r['time']] = $r['total_liveness'];
		}
		return $ret;
	}

	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "device SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	public function update($id = '',$condition = '',$data = array())
	{
		if(!$data || (!$id && !$condition))
		{
			return false;
		}
		
		if($id)
		{
			$cond = " AND id = '" .$id. "' ";
		}
		else 
		{
			$cond = $condition;
		}

		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "device WHERE 1 " . $cond ;
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "device SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE 1 " . $cond;
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "device WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "device WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "device WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	//判断今天是不是已经统计过活跃度了
	public function isTodayHasLiveness($app_id,$device_token)
	{
		$start 	= strtotime(date('Y-m-d',TIMENOW));
		$end 	= $start + 24 * 3600;
		$sql = "SELECT l.* FROM " .DB_PREFIX. "device d LEFT JOIN " .DB_PREFIX. "liveness l 
														ON l.device_id = d.id WHERE 
														d.app_id = '" .$app_id. "' AND 
														d.device_token = '" .$device_token. "' AND 
														l.create_time >= '" .$start."' AND 
														l.create_time < '" .$end. "' ";
		$ret = $this->db->query_first($sql);
		return $ret?true:false;
	}
	
	//判断激活是不是统计过了
	public function isHasStatistic($app_id,$device_token)
	{
		$sql = "SELECT id FROM " . DB_PREFIX . "device WHERE device_token = '" .$device_token. "' AND app_id = '" .$app_id. "'";
		$ret = $this->db->query_first($sql);
		if($ret)
		{
			return true;
		}		
		return false;
	}
	
	//增加活跃数
	public function addLiveness($app_id = '',$device_token = '' , $source = 0)
	{
		//查看有没有该应用对应的设备
		$sql = "SELECT id FROM " .DB_PREFIX. "device WHERE app_id = '" .$app_id. "' AND device_token = '" .$device_token. "' ";
		$arr = $this->db->query_first($sql);
		if(!$arr)
		{
			return false;
		}
		
		//创建一条活跃
		$sql = "INSERT INTO " .DB_PREFIX. "liveness SET device_id = '" .$arr['id']. "' ,create_time = '" .TIMENOW. "' , app_id =  '".$app_id."' , source = '".$source."'";
		$this->db->query($sql);
		
		//更新总的活跃数
		$sql = "UPDATE " .DB_PREFIX. "device SET update_time = ".TIMENOW." and liveness_num = liveness_num + 1 WHERE id = '" .$arr['id']. "'";
		$this->db->query($sql);
		return true;
	}
	
	public function getTotalLivenessNums($app_id = '')
	{
		if(!$app_id)
		{
			return false;
		}
		
		$sql = "SELECT SUM(liveness_num) AS total_liveness FROM " .DB_PREFIX. "device WHERE app_id = '" .$app_id. "' ";
		$arr = $this->db->query_first($sql);
		return $arr;
	}
	
	public function getTotalActivateNums($condition = '')
	{
		$sql = "SELECT SUM(activate_num) AS total_activates FROM " .DB_PREFIX. "device WHERE 1 " . $condition;
		$arr = $this->db->query_first($sql);
		return $arr;
	}
	
	//获取当日的活跃量
	public function getTotalLivnessByCurrentDay($app_id = '')
	{
		$start 	= strtotime(date('Y-m-d',TIMENOW));
		$end 	= $start + 24 * 3600;
		$sql = "SELECT COUNT(l.id) AS total_liveness FROM " .DB_PREFIX. "device d LEFT JOIN " .DB_PREFIX. "liveness l 
														ON l.device_id = d.id WHERE 
														d.app_id = '" .$app_id. "' AND 
														l.create_time >= '" .$start."' AND 
														l.create_time < '" .$end. "' ";
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	//按照指定的时间区间获取总的活跃量(同设备号只算一次)
	public function getTotalLivenessNumsBySection($condition = '')
	{
		$sql = "SELECT COUNT(DISTINCT(d.device_token))  AS total_liveness FROM " .DB_PREFIX. "device d LEFT JOIN " .DB_PREFIX. "liveness l 
														ON l.device_id = d.id WHERE 1 " . $condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	//获取安装排名
	public function getInstallRank($orderNum = 20)
	{
	    $sql = "SELECT app_id,SUM(activate_num) AS install_num FROM " .DB_PREFIX. "device GROUP BY app_id ORDER BY install_num DESC LIMIT 0," . $orderNum;
	    $q   = $this->db->query($sql);
	    $ret = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	       $ret[] = $r; 
	    }
	    return $ret;
	}
	
	public function getIosNums($app_id = '')
	{
		$sql = "select * from " . DB_PREFIX . "device where app_id = " . $app_id . " and source = 2";
		$info = array();
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function getLivenessAppInTimes($times = '')
	{
		$time = strtotime($times);
		$sql = "SELECT * FROM ".DB_PREFIX."liveness AS l LEFT JOIN ".DB_PREFIX."device AS d ON l.device_id = d.id WHERE l.create_time > ".$time." AND d.app_id !=  '' GROUP BY d.app_id";
		$info = array();
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function getTopTen()
	{
		$start 	= strtotime(date('Y-m-d',TIMENOW));
		$end 	= $start + 24 * 3600;
		$sql = "SELECT COUNT(l.id) AS total_liveness,d.app_id FROM ".DB_PREFIX."device d LEFT JOIN ".DB_PREFIX."liveness l ON l.device_id = d.id WHERE l.create_time >= ".$start." AND l.create_time < ".$end." group by d.app_id order by total_liveness desc limit 0,10";
		$info = array();
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function getCoverNums($start_time = 0 ,$end_time = 0)
	{
		if($start_time == 0 && $end_time == 0)
		{
			$sql = "SELECT count(distinct(device_token)) as total FROM ".DB_PREFIX."device";
		}
		else
		{
			$sql = "SELECT count(distinct(device_token)) as total FROM ".DB_PREFIX."device where create_time>".$start_time." and create_time < ".$end_time;
		}
		$info = $this->db->query_first($sql);
		return $info;	
	}
	
	public function getStart($day = '')
	{
		$time = strtotime($day);
		$sql = "SELECT COUNT(DISTINCT(d.device_token))  AS total_liveness FROM " .DB_PREFIX. "device d LEFT JOIN " .DB_PREFIX. "liveness l 
														ON l.device_id = d.id WHERE l.create_time> " . $time;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function getTopDown($start_time = 0 , $end_time = 0 , $source = 0 )
	{
		if($start_time == 0 && $end_time == 0)
		{
			$sql = "SELECT count(*) as num ,app_id FROM ".DB_PREFIX."device where source = ".$source." group by app_id order by num desc limit 0,10";
		}
		else
		{
			$sql = "SELECT count(*) as num ,app_id FROM ".DB_PREFIX."device where source = ".$source." and create_time > ".$start_time." and create_time < ".$end_time." group by app_id order by num desc limit 0,10";
		}
		$info = array();
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function getAppActivateRank($start_time = 0 , $end_time = 0 , $source = 0 )
	{
		if($start_time == 0 && $end_time == 0)
		{
			$sql = "SELECT app_id,sum(liveness_num) as num FROM ".DB_PREFIX."device where source = ".$source." group by app_id order by num desc limit 0 , 10";
			
		}
		else 
		{
			$sql = "select d.app_id,count(distinct(d.id)) as num from ".DB_PREFIX."device as d left join ".DB_PREFIX."liveness as l on d.id = l.device_id where d.source = ".$source." and l.create_time > ".$start_time . " and l.create_time < ".$end_time ." group by d.app_id order by num desc limit 0 , 10";
		}
		
		$info = array();
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function getAllActivateAndDown($start_time = 0 , $end_time = 0)
	{
		$info = array();
		if($start_time == 0 && $end_time == 0)
		{
			$and_ac_sql = "SELECT sum(liveness_num) as total FROM ".DB_PREFIX."device where source = 1";
			$ios_ac_sql = "SELECT sum(liveness_num) as total FROM ".DB_PREFIX."device where source = 2";
			$and_down_sql = "SELECT count(*) as total FROM ".DB_PREFIX."device where source = 1";
			$ios_down_sql = "SELECT count(*) as total FROM ".DB_PREFIX."device where source = 2";
		}
		else
		{
			$and_ac_sql = "SELECT count(distinct(d.id)) as total FROM ".DB_PREFIX."device as d 
						left join ".DB_PREFIX."liveness as l on d.id = l.device_id where d.source = 1 and l.create_time > ".$start_time."  and l.create_time < ".$end_time;
			$ios_ac_sql = "SELECT count(distinct(d.id)) as total FROM ".DB_PREFIX."device as d
						left join ".DB_PREFIX."liveness as l on d.id = l.device_id where d.source = 2 and l.create_time > ".$start_time."  and l.create_time < ".$end_time;
			
			$and_down_sql = "SELECT count(*) as total FROM ".DB_PREFIX."device where source = 1 and create_time > ".$start_time." and create_time <".$end_time;
			$ios_down_sql = "SELECT count(*) as total FROM ".DB_PREFIX."device where source = 2 and create_time > ".$start_time." and create_time <".$end_time;
		}
		$and_ac_info = $this->db->query_first($and_ac_sql);
		$ios_ac_info = $this->db->query_first($ios_ac_sql);
		$and_down_info = $this->db->query_first($and_down_sql);
		$ios_down_info = $this->db->query_first($ios_down_sql);
		$info = array(
			'and_ac_count' => $and_ac_info['total'],
			'ios_ac_count' => $ios_ac_info['total'],
			'and_down_count' => $and_down_info['total'],
			'ios_down_count' => $ios_down_info['total'],
		);
		return $info;
	}
	
	public function getTodayDeviceInfo($zero_time = 0)
	{
		$sql = "select count(*) as total,t as hour from (SELECT FROM_UNIXTIME(create_time,'%H') as t,create_time FROM ".DB_PREFIX."device where create_time > ".$zero_time." ) as new_table  group by new_table.t";
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		$ret = array();
		if($info && is_array($info))
		{
			foreach ($info as $k => $v)
			{
				$ret[intval($v['hour'])] =  intval($v['total']);
			}
		}
		return $ret;
	}
	
	public function getAllActivateInfo($start_time = 0 , $end_time = 0)
	{
		$sql = "SELECT COUNT(DISTINCT(d.device_token))  AS total FROM " .DB_PREFIX. "device d LEFT JOIN " .DB_PREFIX. "liveness l
														ON l.device_id = d.id WHERE l.create_time> " . $start_time . " and l.create_time < ".$end_time;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function getTodayActivateInfo($start_time = 0)
	{
		$sql = "select count(*) as total,t as hour from (SELECT FROM_UNIXTIME(create_time,'%H') as t,create_time FROM ".DB_PREFIX."liveness where create_time > ".$start_time." ) as new_table  group by new_table.t";
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		$ret = array();
		if($info && is_array($info))
		{
			foreach ($info as $k => $v)
			{
				$ret[intval($v['hour'])] =  intval($v['total']);
			}
		}
		return $ret;
	}
	
	public function validateFirstThreeMonths($start_time = 0 ,$end_time = 0, $app_id = 0 , $source = 0)
	{
		$sql = "select count(*) as total from ".DB_PREFIX."device where create_time > ".$start_time. " and create_time < ".$end_time . " and source = " . $source . " and app_id = ".intval($app_id);
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function valiedateNextThreeMothns($start_time = 0 ,$end_time = 0, $app_id = 0 , $source = 0)
	{
		$sql = "select count(*) as total from ".DB_PREFIX."device as d left join ".DB_PREFIX."liveness as l on d.id = l.device_id where d.app_id = ".$app_id." and d.source = ".$source." and l.create_time > ".$start_time."  and l.create_time < ".$end_time;
		$info = $this->db->query_first($sql);
		return $info;
	}
}
?>