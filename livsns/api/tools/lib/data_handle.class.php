<?php
class data_handle extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "data_handle WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $data = array();
        $source_id = $space = '';
        while($row = $this->db->fetch_array($q))
        {
        	$row['link'] = DATAURL . $row['filename'] . '.php';
        	$row['sql_content'] = stripcslashes($row['sql_content']);
        	$row['parameter'] = str_replace("\'","'",stripcslashes($row['parameter'])); 
        	$row['dataformat'] = str_replace("\'","'",stripcslashes($row['dataformat'])); 
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
		    $sql = "INSERT INTO " .DB_PREFIX. "data_handle SET " . $extra;
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
		    $sql = "UPDATE " .DB_PREFIX. "data_handle SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
		    return $data;
	    }
    }
    
    public function count($condition='')
    {
	    $sql = "SELECT count(*) as total FROM " .DB_PREFIX. "data_handle WHERE 1 " . $condition;
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
			$sql = "delete from " .DB_PREFIX. "data_handle WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
		    return array('id' => $ids);
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "data_handle WHERE id=" . $id;
	    }
	    else
	    {
		     $sql = "SELECT * FROM " .DB_PREFIX. "data_handle WHERE 1";
	    }
        $f = $this->db->query_first($sql);
        if(empty($f))
        {
	        return false;
        }
        else
        {
	        $f['link'] = DATAURL . $f['filename'] . '.php';
        	$f['sql_content'] = stripcslashes($f['sql_content']);
        	$f['parameter'] = str_replace("\'","'",stripcslashes($f['parameter'])); 
        	$f['dataformat'] = str_replace("\'","'",stripcslashes($f['dataformat'])); 
	        return $f;
        }
    }
    
    public function checkName($filename,$id = 0)
    {
	    if($filename)
	    {
	    	$con = '';
	    	if($id)
	    	{
		    	$con = " AND id NOT IN(" . $id . ")";
	    	}
		    $sql = "SELECT * FROM " .DB_PREFIX. "data_handle WHERE filename='" . trim($filename) . "'" . $con;
		    $f = $this->db->query_first($sql);
		    if($f)
		    {
			    return true;
		    }
	    }
	    return false;
    }
    
    public function create_file($id = 0)
    {
	   $demo_file = DATA_DIR . 'template.demo';
	   $file_content = file_get_contents($demo_file);
	   $sql = "SELECT * FROM " .DB_PREFIX. "data_handle WHERE 1";
	   $sql .= $id ? " AND id IN(" . $id . ")":'';
	   $q = $this->db->query($sql);
	   $info = array();
	   while($row = $this->db->fetch_array($q))
	   {
		   $info[] = $row;
	   }
	   if($info)
	   {
		   foreach($info as $k => $v)
		   {
				$tmp_content = $file_content;
				$data = array();
				$find_str = array(
					'{$name}',
					'{$parameter}',
					'{$sql_content}',
					'{$dataformat}'
				);
				if($v['sql_content'])
				{
					$sql_child = str_replace(array('{DB_PREFIX}',''),array(DB_PREFIX,''),$v['sql_content']);
					$sql_child = trim($sql_child,'"');
					
				/*
					$sql_child = eval($sql_child);
					//echo $sql_child;exit;
					$q = $this->db->query($sql_child);
					while($row = $this->db->fetch_array($q))
					{
						$data[] = $row;
					}
					hg_pre($data);exit;
				*/
				}
				$replace_str = array(
					$v['filename'],
					$v['parameter'],
					$sql_child,
					$v['dataformat'],
				);
		   		$tmp_content = str_replace($find_str,$replace_str,$tmp_content);
		   		file_put_contents(DATA_DIR .$v['filename'] . '.php', '<?php ' . $tmp_content . '?>');
		   		return true;
		   }
	   }
	   return false;
    }
}
?>