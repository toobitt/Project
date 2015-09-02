<?php
class data_manager extends initFrm
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        parent::__construct();
    }
    
    public function show($condition = '',$need_field=0 ,$child_limit = '')
    {
      	$sql = "SELECT * FROM " .DB_PREFIX. "table_info WHERE 1 " . $condition;
        $f = $this->db->query_first($sql);
        $data = array();
        if($f)
        {
        	$sql = "SELECT * FROM " .DB_PREFIX . $f['table_name'] . " WHERE 1 " . $child_limit;
        	$q = $this->db->query($sql);
	        while($row = $this->db->fetch_array($q))
	        {
	        	$data[] = $row;
	        }
        }
        if($need_field)
        {
	       $data['field'] = $this->get_field($f['table_name']);
	       $data['field_mark'] = $this->get_field_type($f['table_name'],'field_mark');
        }
        return $data;
    }
    
    public function get_field($table_name)
    {
    	$data = array();
	    if($table_name)
	    {
		    $sql = "DESC " .DB_PREFIX . $table_name;
        	$q = $this->db->query($sql);
        	while($row = $this->db->fetch_array($q))
	        {
	        	$data[] = $row['Field'];
	        }
	    }
	    return $data;
    }
    
    
    public function get_field_type($table_name,$type_name = 'field_type' )
    {
    	$data = $tmp_data = array();
	    if($table_name)
	    {
		    $sql = "DESC " .DB_PREFIX . $table_name;
		    $sql = "SELECT * FROM ". DB_PREFIX . "table_info WHERE table_name='" . $table_name . "'";
        	$f = $this->db->query_first($sql);
        	$tmp_data = unserialize($f['table_format']);
        	foreach($tmp_data as $k => $v)
        	{
	        	$data[$v['field_name']] = $v[$type_name];
        	}
        	
	    }
	    return $data;
    }
    
    public function create($table_name,$data)
    {
	    if(!empty($data) && !empty($table_name))
	    {
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "INSERT INTO " .DB_PREFIX. $table_name .  " SET " . $extra;
		    $this->db->query($sql);
		  //  $data['id'] = $this->db->insert_id();
		  	unset($data);
		    return array('success' => SUCCESS);
	    }
		    return array('error' => FAILED);
    }
    
    public function update()
    {
	    
    }
    
    public function checkTableExists($table_name)
    {
	    $sql = "SHOW TABLES LIKE '" . DB_PREFIX . $table_name . "'";
		$tables_exists = $this->db->num_rows($this->db->query($sql));
		if($tables_exists != 1)
		{
    		return array('error' => TABLES_IS_NOT_EXISTS);
		}
		return true;
    }
    
    public function start_import($table_name)
    {
	    if($table_name)
	    {
		    $sql = "UPDATE " .DB_PREFIX. "table_info SET is_data=1 WHERE table_name='" . $table_name . "'";
		    $this->db->query($sql);
	    }
    }
    
    public function add_truncate($table_name)
    {
	    if(!$table_name)
	    {
		    return array('error' => NO_TABLE_NAME);
	    }
	    else
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "table_info WHERE table_name='" . $table_name . "'";
		    $f = $this->db->query_first($sql);
		    if(empty($f))
		    {
			    return array('error' => TABLES_IS_NOT_EXISTS);
		    }
		    else
		    {
			    $sql = "UPDATE " .DB_PREFIX. "table_info SET is_empty=1 WHERE table_name='" . $table_name . "'";
			    $this->db->query($sql);
			    return true;
		    }
	    }
    }
    
    public function truncate($table_name)
    {
	    if(empty($table_name))
	    {
	    	return array('error' => NO_TABLE_NAME);
	    }
	    $ret = $this->checkTableExists($table_name);
	    if(isset($ret['error']))
	    {
		    return $ret;
	    }
	    $sql = "TRUNCATE TABLE `" . DB_PREFIX . $table_name ."`";
	    $this->db->query($sql);
	    return true;
    }
    
    public function update_field($data,$primary,$primary_key='id')
    {
	    if(!empty($data) && $primary)
	    {
	    	if(isset($data['table_name']) && isset($data['key']))
	    	{
		   		$sql = "UPDATE " . DB_PREFIX . $data['table_name'] . " SET " . $data['key'] . "='" . $data['key_value'] . "' WHERE " . $primary_key . "=" . $primary;
			    $this->db->query($sql);
			    $data['primary'] = $primary;
			    return $data;
	    	}
		    return array('error' => FAILED);
	    }
    }
    
    public function count($condition='')
    {
    	$sql = "SELECT * FROM " .DB_PREFIX. "table_info WHERE 1 " . $condition;
        $f = $this->db->query_first($sql);
        $data = array();
        if($f)
        {
        	$sql = "SELECT count(*) as total FROM " .DB_PREFIX . $f['table_name'] . " WHERE 1 ";
        	$sen = $this->db->query_first($sql);
        	return $sen;
        }
    }
    
    public function audit($ids,$state)
    {
 
    }
    
    public function delete($ids,$table_name,$primary='id')
    {
    	if($ids && $table_name)
    	{
    		
			$sql = "DELETE FROM " .DB_PREFIX . $table_name .  " WHERE " . $primary . " IN(" . $ids . ")";
		    $this->db->query($sql);
		    return array($primary => $ids);
    	}
    	else
    	{
	    	return array('error' => FAILED);
    	}
    }
    
    public function detail($table_name,$id)
    {
	    if($id>0)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "$table_name WHERE id=" . $id;
	    }
	    else
	    {
		     $sql = "SELECT * FROM " .DB_PREFIX. "$table_name WHERE 1";
	    }
	  //  return $sql;
        $f = $this->db->query_first($sql);
        $f['field'] = $this->get_field($table_name);
        $f['field_type'] = $this->get_field_type($table_name);
        $f['field_mark'] = $this->get_field_type($table_name,'field_mark');
        if(empty($f))
        {
	        return false;
        }
        else
        {
        	foreach($f['field'] as $k => $v)
        	{
	        	if($f['field_type'][$v] == 'column')
	        	{
		        	$f[$v] = unserialize($f[$v]);
	        	}
        	}
	        return $f;
        }
    }
}
?>