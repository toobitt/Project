<?php
class res_sort extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "res_sort" .' '. $condition;
      	//return $sql;
        $q = $this->db->query($sql);
        $data = array();
        $source_id = $space = '';
        while($row = $this->db->fetch_array($q))
        {
        	$data[] = $row;
        }
        //print_r($data);die;
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
		    $sql = "INSERT INTO " .DB_PREFIX. "res_sort SET " . $extra;
		   // return $sql;
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
		    $sql = "update " .DB_PREFIX. "res_sort SET " . $extra . " WHERE id=" . $id;
		 //   file_put_contents('../cache/sss2',$sql);
		    $this->db->query($sql);
		    $data['id'] = $id;
		    return $data;
	    }
    }
    public function delete($ids)
    {
    	if($ids)
    	{
    		$sql = "SELECT logo_id FROM " .DB_PREFIX. "res_sort WHERE id IN(" . $ids . ")";
    		$q = $this->db->query($sql);
    		$mid = $space = '';
    		while($row = $this->db->fetch_array($q))
    		{
	    		$mid .= $space . $row['logo_id'];
	    		$space = ',';
    		}
			$sql = "DELETE FROM " .DB_PREFIX. "res_sort WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
		    $sql = "DELETE FROM " .DB_PREFIX."res WHERE sort_id IN(" . $ids . ")";//删除res对应子项目
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
     public function checkName($name,$id=0)
    {
	    if(!empty($name))
	    {
		   $sql = "SELECT * FROM " .DB_PREFIX. "res_sort WHERE name='" . trim($name) . "'";
		   if($id)
		   {
			   $sql .= ' AND id !=' .$id;
		   }
		   return $this->db->query_first($sql);
	    }
    }
  }
?>