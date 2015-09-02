<?php
class auditor extends initFrm
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        parent::__construct();
    }
    
    public function show($condition='')
    {
      	$sql = "SELECT * FROM " .DB_PREFIX. "auditor WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $data = array();
        $source_id = $space = '';
        while($row = $this->db->fetch_array($q))
        {
        	if($row['info'])
        	{
	        	$row['info'] = unserialize($row['info']);
        	}
        	$data[] = $row;
        }
        
        return $data;
    }
    
    public function create($data)
    {
	    if(!empty($data))
	    {
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "INSERT INTO " .DB_PREFIX. "auditor SET " . $extra;
		    
		    $this->db->query($sql);
		    $data['id'] = $this->db->insert_id();
		    return $data;
	    }
    }
    
    public function update($data,$id)
    {
	    if(!empty($data) && $id)
	    {
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "UPDATE " .DB_PREFIX. "auditor SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
		    return $data;
	    }
    }
    
    public function audit($ids,$state)
    {
 
    }
    
    public function count($condition='')
    {
	    $sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "auditor WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }
    
    public function delete($ids)
    {
    	if($ids)
    	{
    		$sql = "SELECT * FROM " .DB_PREFIX. "auditor WHERE id IN(" . $ids . ")";
    		$q = $this->db->query($sql);
    		$id = $space = '';
    		while($row = $this->db->fetch_array($q))
    		{
	    		if(!$row['locked'])
	    		{
		    		$id .= $space . $row['id'];
		    		$space = ',';
	    		}
    		}
    		if($id)
    		{
				$sql = "DELETE FROM " .DB_PREFIX. "auditor WHERE id IN(" . $id . ")";
			    $this->db->query($sql);
			    return array('id' => $id);
    		}
    		else
    		{
	    		return array('error' => THIS_IS_LOCKED);
    		}
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "auditor WHERE id=" . $id;
	        
	    }
	    else
	    {
	    	$sql = "SELECT * FROM " .DB_PREFIX. "auditor WHERE 1";
	    }
	    $f = $this->db->query_first($sql);
        if($f)
        {
	        $f['info'] = unserialize($f['info']);
        }
        return $f;
    }
    
    public function add_record($data)
    {
	    if($data)
	    {
		    $extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "INSERT INTO " .DB_PREFIX. "audit_record SET " . $extra;
		    $this->db->query($sql);
		    $data['id'] = $this->db->insert_id();
		    /****插入队列****/
		    $sql = "INSERT INTO " .DB_PREFIX. "audit_queue SET audit_record_id=" . $data['id'] . ",bill_id=" . $data['bill_id'];
		    $this->db->query($sql);
		    return $data;
	    }
    }
    
    public function update_record($data,$bill_id)
    {
	    if($data)
	    {
	    	
	    }
    }
    
    public function delete_record($bill_id)
    {
	    if($bill_id)
	    {
		    $sql = "DELETE FROM " .DB_PREFIX. "audit_record WHERE bill_id=" . $bill_id;
		    $this->db->query($sql);
		    $sql = "DELETE FROM " .DB_PREFIX. "audit_queue WHERE bill_id=" . $bill_id;
		    $this->db->query($sql);
		    return array('id' => $bill_id);
	    }
    }
    
    public function locked_auditor($bill_id)
    {
	    if($bill_id)
	    {
	    	$sql = "SELECT * FROM " .DB_PREFIX. "bill WHERE id=" . $bill_id;
	    	$f = $this->db->query_first($sql);
	    	if($f['auditor_id'] && $f['user_id'])
	    	{
			    $sql = "SELECT count(*) as total FROM " .DB_PREFIX. "audit_record WHERE auditor_id=" . $f['user_id'] . " AND bill_id=" . $bill_id;
			    $sen = $this->db->query_first($sql);
			    if($sen['total'])
			    {
				    $sql = "UPDATE " . DB_PREFIX . "auditor SET locked=locked+1 WHERE id=" . $f['auditor_id'];
				    $this->db->query($sql);
				    return true;
			    }
			    return false;
	    	}
	    }
	    return false;
    }
    
    public function open_auditor($auditor_id,$bill_id)
    {
    /*
    	if($auditor_id && $bill_id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE auditor_id=" . $auditor_id . " AND bill_id=" . $bill_id;
		    $q = $this->db->query($sql);
		    $locked_num = 0;
		    while($row = $this->db->fetch_array($q))
		    {
			    $locked_num += $row['audit_level'];
		    }
		    $sql = "UPDATE " . DB_PREFIX . "auditor SET locked=locked+" . $locked_num . " WHERE id=" . $auditor_id;
		    $this->db->query($sql);
		    return true;
	    }
	    */
	   // $sql = "UPDATE " .DB_PREFIX. "auditor SET ";
    }
}
?>