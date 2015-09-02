<?php
class win_info_mode extends InitFrm
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
		$sql = "SELECT w.*,p.name,p.type,p.prize FROM " . DB_PREFIX . "win_info w
				LEFT JOIN " . DB_PREFIX . "prize p 
					ON w.prize_id = p.id 
				WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			switch ($r['status'])
			{
				case  1: $r['audit'] = '已审核';break;
				case  2: $r['audit'] = '已打回';break;
				default: $r['audit'] = '待审核';
			}
			
			switch ($r['provide_status'])
			{
				case  0: $r['provide'] = '未发放';break;
				case  1: $r['provide'] = '已发放';break;
			}
			
			$r['create_time']	= date('Y-m-d H:i',$r['create_time']);
			$member_id[] 		= $r['member_id'];
			$info[] 			= $r;
		}
		
		if(!empty($member_id))
		{
			$member_info = $this->get_memberInfo($member_id);
		}
		
	
		if(!empty($info) && $member_info)
		{
			foreach ($info as $val)
			{
				foreach ($val as $k => $v)
				{
					if($k == 'member_id' && $member_info[$v])
					{
						$val['member_name'] 	= $member_info[$v]['member_name'];
						//$val['phone_num']	 	= $member_info[$v]['phone_num'];
						//$val['address']	 	= $member_info[$v]['address'];
						$val['avatar']	 		= $member_info[$v]['avatar'];
					}
				}
				if(!$val['member_name'])
				{
					$val['member_name'] = '用户已经不存在';
				}
				$data[] = $val;
			}
		}
		else 
		{
			$data = $info;
		}
		
		return $data;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "win_info SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "win_info WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "win_info SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "win_info  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "win_info w WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "win_info WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = $prize_id = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
			$prize_id[$r['prize_id']] += 1;
		}
		if(!$pre_data)
		{
			return false;
		}
		
		foreach ($prize_id as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . "prize SET prize_win = prize_win - " . $v . " WHERE id = " . $k . " AND prize_num >0";
			$this->db->query($sql);
		}
		
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "win_info WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '',$audit)
	{
		if(!$id)
		{
			return false;
		}
		
		switch ($audit)
		{
			case 1:$status = 1;$audit_status = '已审核';break;//审核
			case 0:$status = 2;$audit_status = '已打回';break;//打回
		}
		
		$sql = "UPDATE " .DB_PREFIX. "win_info SET status = '" .$status. "' WHERE id IN (" .$id. ")";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id,'audit'=>$audit_status);
	}
	
	
	public function get_memberInfo($member_id)
	{
		if(empty($member_id))
		{
			return FALSE;
		}
		
		include_once ROOT_DIR . 'lib/class/members.class.php';
		
		$obj = new members();
		$member_id 			= implode(',', $member_id);
		$member_info	 	= array();
		$member_info_tmp 	= array();
		$member_info_tmp 	= $obj->get_member_info($member_id);
		
		
		if(!empty($member_info_tmp))
		{
			$size = '30x30/';
			foreach ($member_info_tmp as $val)
			{
				$member_info[$val['member_id']]['member_name'] 	= $val['member_name'];
				if(!empty($val['avatar']))
				{
					$member_info[$val['member_id']]['avatar']	= hg_material_link($val['avatar']['host'], $val['avatar']['dir'], $val['avatar']['filepath'], $val['avatar']['filename'],$size);
				}
				else 
				{
					$member_info[$val['member_id']]['avatar']	= array();
				}
				$member_info[$val['member_id']]['phone_num']	= $val['mobile'];
			}
		}
		
		return $member_info;
	}
}
?>