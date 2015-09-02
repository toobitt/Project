<?php
class qingjia_sort extends initFrm
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function show($condition='')
    {
      	$sql = "SELECT * FROM " .DB_PREFIX. "qingjia_sort WHERE 1 " . $condition;
      	$q = $this->db->query($sql);
        $data = array();
        $source_id = $space = '';
        while($row = $this->db->fetch_array($q))
        {
        	$data[] = $row;
        }
        
        return $data;
    }
    
    public function get_sort_name($sort_id='')
    {    
        $data = array();
        foreach($sort_id as $key => $vo){
      	    $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_sort WHERE 1 and id = " . $vo;
      	    $q = $this->db->query($sql);
            $row = $this->db->fetch_array($q) ;
            $data[] = $row;
            
        }
        
        return  $data;
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
		    $sql = "INSERT INTO " .DB_PREFIX. "qingjia_sort SET " . $extra;
		    
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
		    $sql = "update " .DB_PREFIX. "qingjia_sort SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
		    $data['id'] = $id;
		    return $data;
	    }
    }
    
    public function update_cost($id,$action)
    {
    	if($id)
    	{
    		$data = $this->detail($id);
    		if($data['cost_count']<=0)
    		{
	    		$action = abs($data['cost_count']);
    		}
    		$sql = "UPDATE " .DB_PREFIX. "qingjia_sort SET cost_count=cost_count+" . $action . " WHERE id=" . $id;
	    	$this->db->query($sql);
	    	$data['cost_count']=$data['cost_count']+$action;	
	    	return $data;
    	}
	    return false;
    }
    
    public function reSetCost($id)
    {
	    /*
if($id)
	    {
	    	$sql = "SELECT count(*) AS total FROM " .DB_PREFIX. "qingjia_record WHERE state=1 AND sort_id=" . $id;
    		$f = $this->db->query_first($sql);
    		$sql = "UPDATE " .DB_PREFIX. "qingjia_sort SET cost_count=" . $f['total'] . " WHERE id=" . $id;
    		$this->db->query($sql);
    		return true;
	    }
	    else
	    {
		    $sql = "SELECT count(*) AS total,sort_id FROM " .DB_PREFIX. "qingjia_record WHERE state=1 GROUP BY sort_id";
		    $q = $this->db->query($sql);
		    while($row = $this->db->fetch_array($q))
		    {
			    $sql = "UPDATE " .DB_PREFIX. "qingjia_sort SET cost_count=" . $row['total'] . " WHERE id=" . $row['sort_id'];
			    $this->db->query($sql);
		    }
	    }
*/
    }
    
    
    public function checkName($name,$id=0)
    {
	    if(!empty($name))
	    {
		   $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_sort WHERE name='" . trim($name) . "'";
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
	    $sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "qingjia_sort WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }
    
    public function delete($ids)
    {
    	if($ids)
    	{
    		$sql = "SELECT logo_id FROM " .DB_PREFIX. "qingjia_sort WHERE id IN(" . $ids . ")";
    		$q = $this->db->query($sql);
    		$mid = $space = '';
    		while($row = $this->db->fetch_array($q))
    		{
	    		$mid .= $space . $row['logo_id'];
	    		$space = ',';
    		}
			$sql = "DELETE FROM " .DB_PREFIX. "qingjia_sort WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
    		if($mid)
    		{
	    		include_once(ROOT_PATH . 'lib/class/material.class.php');
	    		$mater = new material();
	    		$mater->delMaterialById($mid);
    		}
		    return array('id' => $ids); 
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_sort WHERE id=" . $id;
	    }
	    else
	    {
		      $sql = "SELECT * FROM " .DB_PREFIX. "qingjia_sort WHERE 1";
	    }
		$f = $this->db->query_first($sql);
		return $f;
    }
}
?>