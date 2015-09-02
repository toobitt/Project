<?php
class app_store_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "app_store  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['audit_time'] = $r['audit_time'] ? date('Y-m-d H:i',$r['audit_time']) : '';
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "app_store SET ";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_store WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "app_store SET ";
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
		    $sql = "SELECT * FROM " . DB_PREFIX . "app_store  WHERE id = '" .$id. "'";
		}
		else
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "app_store  WHERE 1 " . $cond;
		}
		
		$info = $this->db->query_first($sql);
		if($info)
		{
			$info['attach_id'] = unserialize($info['attach_id']);
			$info['attach'] = $this->get_attach($info);
			$info['update_time'] = $info['update_time'] ? date('Y-m-d H:i',$info['update_time']) : '';
		    $info['create_time'] = $info['create_time'] ? date('Y-m-d H:i',$info['create_time']) : '';
		    $info['audit_time'] = $info['audit_time'] ? date('Y-m-d H:i',$info['audit_time']) : '';
			return $info;
		}
		else 
		{
		    return FALSE;
		}
	}
	public function get_attach($info = array())
	{
	   	$attach_id = array();
	   	$attach = array();
    	$attach_id[] = $info['baidu_koubei_snap'];
    	$attach_id[] = $info['share_snap'];
    	if($info['app_icon'])
    	{
    		$attach_id[] = $info['app_icon'];
    	}
    	$tmp = $info['attach_id'];
	    if($tmp && is_array($tmp))
	    {
	    	foreach ($tmp as $k=>$v)
	    	{
	    		if($v)
	    		{
	    			$attach_id[] = $v;
	    		}
	    	}
	    }
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($attach_id,1));
    	if($attach_id)
    	{
    		$sql = 'SELECT * FROM ' . DB_PREFIX.'app_material WHERE id in('.implode(',', $attach_id).')';
    		$query = $this->db->query($sql);
    		while($row = $this->db->fetch_array($query))
    		{
    			$attach[$row['id']] = $row;
    		}
    	}
    	return $attach;
	}
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "app_store WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "app_store WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "app_store WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
}