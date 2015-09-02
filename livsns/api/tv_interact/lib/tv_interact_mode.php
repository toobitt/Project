<?php
class tv_interact_mode extends InitFrm
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
		$sql = "SELECT t1.*,t2.name as sort_name FROM " . DB_PREFIX . "tv_interact t1 
				LEFT JOIN " . DB_PREFIX . "tv_interact_node t2
					ON t1.sort_id = t2.id 
				WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['create_time'])
			{
				$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			}
			
			if($r['start_time'] > TIMENOW)
			{
				$r['activ_status'] = '未开始';
				$r['activ_color'] = 0;
			}
			else if($r['start_time'] < TIMENOW && $r['end_time'] > TIMENOW)
			{
				$r['activ_status'] = '进行中';
				$r['activ_color'] = 1;
			}
			else if($r['end_time'] < TIMENOW)
			{
				$r['activ_status'] = '已结束';
				$r['activ_color'] = 2;
			}
			
			if($r['indexpic'])
			{
				$r['indexpic'] = unserialize($r['indexpic']);
			}
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "tv_interact SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."tv_interact SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "tv_interact WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "tv_interact SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT t.*,s.name as sort_name FROM " . DB_PREFIX . "tv_interact t
				LEFT JOIN " . DB_PREFIX . "tv_interact_node s
					ON t.sort_id = s.id 
				WHERE t.id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		
		if($info['start_time'])
		{
			$info['start_time'] = date('Y-m-d',$info['start_time']);
		}
		if($info['end_time'])
		{
			$info['end_time'] = date('Y-m-d',$info['end_time']);
		}
		
		if(isset($info['start_hour']))
		{
			$info['start_hour'] = $info['start_hour'] ? date('H:i:s', strtotime($info['start_hour'])) : '00:00:00';
		}
		if(isset($info['end_hour']))
		{
			$info['end_hour'] = $info['end_hour'] ? date('H:i:s', strtotime($info['end_hour'])) : '00:00:00';
		}
		
		if($info['indexpic'])
		{
			$info['indexpic'] = unserialize($info['indexpic']);
		}
		if($info['un_start_icon'])
		{
			$info['un_start_icon'] = unserialize($info['un_start_icon']);
		}
		if($info['sense_icon'])
		{
			$info['sense_icon'] = unserialize($info['sense_icon']);
		}
		if($info['un_win_icon'])
		{
			$info['un_win_icon'] = unserialize($info['un_win_icon']);
		}
		if($info['points_icon'])
		{
			$info['points_icon'] = unserialize($info['points_icon']);
		}
		if($info['week_day'])
		{
			$info['week_day'] = explode(',', $info['week_day']);
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tv_interact t1 WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "tv_interact WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "tv_interact WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		//删除中奖记录
		$sql = "DELETE FROM " . DB_PREFIX . "win_info WHERE tv_interact_id IN (" . $id . ")";
		$this->db->query($sql);
		
		return $pre_data;
	}
	
	public function audit($id = '',$audit='')
	{
		if(!$id)
		{
			return false;
		}
		
		switch ($audit)
		{
			case 0:$status = 2;break;//打回
			case 1:$status = 1;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "tv_interact SET status = '" .$status. "' WHERE id IN (" .$id. ")";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
}
?>