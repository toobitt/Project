<?php
class tables_sort extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "table_sort WHERE 1 " . $condition;
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
		    $sql = "INSERT INTO " .DB_PREFIX. "table_sort SET " . $extra;
		    
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
		    $sql = "UPDATE " .DB_PREFIX. "table_sort SET " . $extra . " WHERE id=" . $id;
		    
		    $this->db->query($sql);
		    return $data;
	    }
    }
    
    public function count($condition='')
    {
	    $sql = "SELECT count(*) as total FROM " .DB_PREFIX. "table_sort WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }
    
    public function audit($ids,$state)
    {
 
    }
    
    public function delete($ids)
    {
    	if($ids)
    	{
			/*
			$sql = "delete from " .DB_PREFIX. "company WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
		    return array('id' => $ids); 
			*/
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "table_sort WHERE id=" . $id;
	    }
	    else
	    {
		     $sql = "SELECT * FROM " .DB_PREFIX. "table_sort WHERE 1";
	    }
        $f = $this->db->query_first($sql);
        if(empty($f))
        {
	        return false;
        }
        else
        {
	       // $f['table_format'] = unserialize($f['table_format']);
	        return $f;
        }
    }
    
    public function checkName($table_name,$id = 0)
    {
	    if($table_name)
	    {
	    	$con = '';
	    	if($id)
	    	{
		    	$con = " AND id NOT IN(" . $id . ")";
	    	}
		    $sql = "SELECT * FROM " .DB_PREFIX. "table_sort WHERE name='" . trim($table_name) . "'";
		    $f = $this->db->query_first($sql);
		    if($f)
		    {
			    return true;
		    }
	    }
	    return false;
    }
}
?>