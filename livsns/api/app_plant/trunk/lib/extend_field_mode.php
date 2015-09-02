<?php
class extend_field_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function getExtendFieldByModuleId($module_id = '',$cond = '')
	{
	    if(!$module_id)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "expend_field WHERE module_id = '" .$module_id. "' " .$cond. " ORDER BY position ASC ";
	    $q = $this->db->query($sql);
	    $ret = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $ret[] = $r;
	    }
	    return $ret;
	}
	
	public function deleteExtendFieldStyle($module_id = '')
	{
	    if(!$module_id)
	    {
	        return FALSE;
	    } 

		$sql = " DELETE FROM " .DB_PREFIX. "expend_field WHERE module_id = '" .$module_id. "' ";
		$this->db->query($sql);
		return TRUE;
	}
	
	public function createExtendFieldStyle($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "expend_field SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}	
	
	public function getCornerData($module_id = '')
	{
	    if(!$module_id)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "corner WHERE module_id = '" .$module_id. "'";
	    $ret = $this->db->query_first($sql);
	    return $ret;
	}
	
	//设置角标的值
	public function setCornerStyle($data = array())
	{
	    if(!$data || !isset($data['module_id']) || !$data['module_id'])
	    {
	        return FALSE;
	    }
	    $module_id = $data['module_id'];
	    
	    //查询该模块有没有存在值
	    $_sql = "SELECT * FROM " .DB_PREFIX. "corner WHERE module_id = '" .$module_id. "'";
	    $pre_data = $this->db->query_first($_sql);
	    if($pre_data)
		{
		    unset($data['module_id']);
		    $sql = " UPDATE " . DB_PREFIX . "corner SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE module_id = '"  .$module_id. "'";
    		$this->db->query($sql);
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "corner SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
    		$this->db->query($sql);
		}
		return TRUE;
	}
}