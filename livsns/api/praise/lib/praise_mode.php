<?php
class praise_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "praise  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
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
		$sql = " INSERT INTO " . DB_PREFIX . "praise SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
// 		$sql = " UPDATE ".DB_PREFIX."praise SET order_id = {$vid}  WHERE id = {$vid}";
// 		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "praise WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "praise SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "praise  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $info;
	}
	
	public function getOneDoPraiseInfo($content_id = 0 , $member_id = 0)
	{
		if(!$content_id || !$member_id)
		{
			return false;
		}
		$sql = "select * from ".DB_PREFIX."do_praise where content_id = ".$content_id . " and member_id = ".$member_id;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	
	public function getPraiseInfoByContentId($content_id = 0)
	{
		if(!$content_id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "praise  WHERE content_id = '" .$content_id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $info;
	}
	
	
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "praise WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "praise WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "praise WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "praise WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "praise SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	
	/**
	 * 执行赞的操作，do_praise表增加或删除信息
	 * @param number $content_id
	 * @param string $device_token
	 * @param string $operate
	 */
	public function doPraise($content_id = 0 , $device_token = '' , $operate = '' , $member_id = 0)
	{
		if(!$content_id || !$operate)
		{
			return false;
		}
		if($operate == 'add')
		{
			$sql = "insert into " . DB_PREFIX . "do_praise set content_id = " . $content_id . ",device_token = '". $device_token . "',praise_time =".TIMENOW." , member_id = ".$member_id;
		}
		elseif($operate == 'cancel')
		{
			$sql = "delete from " . DB_PREFIX . "do_praise where content_id = " . $content_id . " and member_id = " . $member_id;
		}
		if($this->db->query($sql))
		{
			return true;
		}
	}
	
	
	public function updatePraise($prais_id = 0 , $operate = '')
	{
		if($operate == 'add')
		{
			$sql = "update " . DB_PREFIX . "praise set count = count + 1 where id = ".$prais_id;
		}
		elseif($operate == 'cancel')
		{
			$sql = "update " . DB_PREFIX . "praise set count = count - 1 where id = ".$prais_id;
		}
		if($ret = $this->db->query($sql))
		{
			$info = $this->detail($prais_id);
			return $info;
		}
	}
	
	public function deletePraiseByContentId($content_id = 0 , $content_module = '')
	{
		$sql = "delete from ".DB_PREFIX."praise where content_id = ".$content_id." and content_module = '".$content_module."'";
		file_put_contents('111sq.txt', $sql);
		if($this->db->query($sql))
		{
			return true;
		}
	}
	
	public function deleteDoPraise($content_id = 0)
	{
		$sql = "delete from ".DB_PREFIX."do_praise where content_id = ".$content_id;
		file_put_contents('222q.txt', $sql);
		if($this->db->query($sql))
		{
			return true;
		}
	}
}
?>