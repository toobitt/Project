<?php
class app_cards_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "app_cards  WHERE 1 " . $condition . $orderby . $limit;
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "app_cards SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."app_cards SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_cards WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "app_cards SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "app_cards  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "app_cards WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_cards WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "app_cards WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_cards WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "app_cards SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	/**
	 * 配置app个人中心的卡片
	 */
	public function SetAppCards($app_id, $data = array())
	{
	    if (!$data && !$app_id)
	    {
	        return array();
	    }
	    $old_card_id = array();
	    //查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "app_cards WHERE app_id = '" .$app_id. "'";
		$q = $this->db->query($sql);
	    while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
			$pre_data[] = $r;
		}
	    if ($pre_data)
	    {
	        foreach ($pre_data as $k=>$v)
	        {
	            $old_card_id[] = $v['card_id'];
	        }
	    }
	    $new_card_id = $data['card_id'];
	    
	    $x = array_diff($new_card_id, $old_card_id);
	    if(array_diff($new_card_id, $old_card_id))
	    {
	        $createData = array_diff($new_card_id, $old_card_id);
	        foreach ($createData AS $k => $v)
	        {
    	        $sql = " INSERT INTO " . DB_PREFIX . "app_cards (card_id,app_id) VALUES";
	            $sql .= "({$v},{$app_id})";
    	        $sql = trim($sql,',');
    	        $this->db->query($sql);
	        }
	    }
	    
	    if(array_diff($old_card_id,$new_card_id))
	    {
	        $deleteData = array_diff($old_card_id,$new_card_id);
	        foreach ($deleteData AS $k => $v)
	        {
	            $sql = " DELETE FROM " .DB_PREFIX. "app_cards WHERE app_id IN (" . $app_id . ") AND card_id=".$v."";
		        $this->db->query($sql);
	        }
	    }
	    
	    return $data;
	}
}
?>