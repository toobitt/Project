<?php
class project extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "project WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $data = array();
        $source_id = $space = '';
        while($row = $this->db->fetch_array($q))
        {
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
		    $sql = "INSERT INTO " .DB_PREFIX. "project SET " . $extra;
		    
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
		    $sql = "update " .DB_PREFIX. "project SET " . $extra . " WHERE id=" . $id;
		 //   file_put_contents('../cache/sss2',$sql);
		    $this->db->query($sql);
		    $data['id'] = $id;
		    return $data;
	    }
    }
    
    public function update_business($id,$action)
    {
    	if($id)
    	{
    		$data = $this->detail($id);
    		if($data['business_count']<=0)
    		{
	    		$action = abs($data['business_count']);
    		}
    		$sql = "update " .DB_PREFIX. "project SET business_count=business_count+" . $action . " WHERE id=" . $id;
	    	$this->db->query($sql);
	    	$data['business_count']=$data['business_count']+$action;  	
	    	return $data;
    	}
	    return false;
    }
    
    public function reSetBusiness($id)
    {
	    if($id)
	    {
	    	$sql = "SELECT count(*) AS total FROM " .DB_PREFIX. "bill WHERE state=1 AND project_id=" . $id;
    		$f = $this->db->query_first($sql);
    		$sql = "UPDATE " .DB_PREFIX. "project SET business_count=" . $f['total'] . " WHERE id=" . $id;
    		$this->db->query($sql);
    		return true;
	    }
	    else
	    {
		    $sql = "SELECT count(*) AS total,project_id FROM " .DB_PREFIX. "bill WHERE 1 GROUP BY project_id";
		    $q = $this->db->query($sql);
		    while($row = $this->db->fetch_array($q))
		    {
		    	if($row['total'])
		    	{
				   $sql = "UPDATE " .DB_PREFIX. "project SET business_count=" . $row['total'] . " WHERE id=" . $row['project_id'];
				   $this->db->query($sql);
		    	}
		    }
		    
		    $sql = "SELECT count(*) AS total,project_id FROM " .DB_PREFIX. "bill WHERE state <> 1 GROUP BY project_id";
		    $q = $this->db->query($sql);
		    while($row = $this->db->fetch_array($q))
		    {
		    	if($row['total'])
		    	{
				   $sql = "UPDATE " .DB_PREFIX. "project SET business_count=business_count-" . $row['total'] . " WHERE id=" . $row['project_id'];
				   $this->db->query($sql);
		    	}
		    }
		    return true;
	    }
    }
    
    public function checkName($name,$id=0)
    {
	    if(!empty($name))
	    {
		   $sql = "SELECT * FROM " .DB_PREFIX. "project WHERE name='" . trim($name) . "'";
		   if($id)
		   {
			   $sql .= ' AND id !=' .$id;
		   }
		   return $this->db->query_first($sql);
	    }
    }
    
    public function audit($ids,$state)
    {
 
    }
    
    public function count($condition='')
    {
	    $sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "project WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }
    
    public function delete($ids)
    {
    	if($ids)
    	{
			$sql = "DELETE FROM " .DB_PREFIX. "project WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
		    return array('id' => $ids); 
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "project WHERE id=" . $id;
	    }
	    else
	    {
		      $sql = "SELECT * FROM " .DB_PREFIX. "project WHERE 1";
	    }
		$f = $this->db->query_first($sql);
		return $f;
    }
}
?>