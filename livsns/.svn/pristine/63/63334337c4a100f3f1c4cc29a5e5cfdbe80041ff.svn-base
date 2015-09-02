<?php
class user_interface_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "user_interface  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['type_text'] = $this->settings['ui_type'][$r['type']];
			if($r['img_info'] && @unserialize($r['img_info']))
			{
			    $r['img_info'] = unserialize($r['img_info']);
			}
			else 
			{
			    $r['img_info'] = array();
			}
			
		    if($r['comp_img'] && @unserialize($r['comp_img']))
			{
			    $r['comp_img'] = unserialize($r['comp_img']);
			}
			else 
			{
			    $r['comp_img'] = array();
			}
			
			if(intval($r['type']) == 1)
			{
			    $_sql = "SELECT * FROM " .DB_PREFIX. "main_ui_pics WHERE ui_id = '" .$r['id']. "'";
			    $_q = $this->db->query($_sql);
			    while ($_r = $this->db->fetch_array($_q))
			    {
			        $_r['img_info'] = unserialize($_r['img_info']);
			        $r['example_pics'][] = $_r;
			    }
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "user_interface SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."user_interface SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "user_interface WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "user_interface SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '',$cond = '')
	{
		if(!$id && !$cond)
		{
			return false;
		}
		
		if($id)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "user_interface  WHERE id = '" .$id. "'";
		}
		else if($cond)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "user_interface  WHERE 1 " . $cond;
		}
		$info = $this->db->query_first($sql);
		if($info)
		{
		    if($info['img_info'] && @unserialize($info['img_info']))
			{
			    $info['img_info'] = unserialize($info['img_info']);
			}
			else 
			{
			    $info['img_info'] = array();
			}
			
		    if($info['comp_img'] && @unserialize($info['comp_img']))
			{
			    $info['comp_img'] = unserialize($info['comp_img']);
			}
			else 
			{
			    $info['comp_img'] = array();
			}
		    return $info;
		}
		else 
		{
		    return FALSE;
		}
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_interface WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "user_interface WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "user_interface WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "user_interface WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "user_interface SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//判断某个UI下存不存在属性
	public function isHavAttr($ui_id = '')
	{
	    if(!$ui_id)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "attribute_relate WHERE ui_id = '" .$ui_id. "' ";
	    $ret = $this->db->query_first($sql);
	    if($ret)
	    {
	        return TRUE;
	    }
	    else 
	    {
	        return FALSE;
	    }
	}
	
	//获取某个UI下的所有属性
	public function getAttrByUI($ui_id = '')
	{
	    if(!$ui_id)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "attribute_relate WHERE ui_id = '" .$ui_id. "' ";
	    $q   = $this->db->query($sql);
	    $ret = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $ret[] = $r;
	    }
	    return $ret;
	}
}