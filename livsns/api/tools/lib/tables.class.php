<?php
class tables extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "table_info WHERE 1 " . $condition;
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
	    	$table_format = array();
	    	if($data['table_format'])
	    	{
	    		$table_format = $data['table_format'];
		    	$length = count($table_format['field_name']);
		    	$info = array();
		    	for($i = 0;$i < $length;$i++)
		    	{
		    		if($table_format['field_name'][$i] && $table_format['field_type'][$i])
		    		{
			    		$info[$table_format['field_key'][$i]] = array(
			    			'field_name' => $table_format['field_name'][$i],
			    			'field_type' => $table_format['field_type'][$i],
			    			'field_auto' => $table_format['field_auto'][$i],
			    			'field_length' => $table_format['field_length'][$i],
			    			'field_index' => $table_format['field_index'][$i],
			    			'field_mark' => $table_format['field_mark'][$i],
			    			'field_key' => $table_format['field_key'][$i], 	    		
			    		);
		    		}
		    	}
		    	if($info)
		    	{
		    		$index_key = $space = "";
		    		$primary_key = $p_space = "";
		    		$tables_exists = $this->checkTableExists($data['table_name']);		    		
		    		if($tables_exists)
		    		{
			    		return array('error' => TABLES_IS_EXISTS);
		    		}    		
				    $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . $data['table_name'] . "` (";
				    foreach($info as $k => $v)
				    {
				    	$sql .= "`" . $v['field_name'] . "` " . $v['field_type'] . (intval($v['field_length']) ? "(" . intval($v['field_length']) . ")" : "") . " NOT NULL " . ($v['field_auto'] ? "AUTO_INCREMENT":"") . ($v['field_mark'] ? " COMMENT '" . $v['field_mark'] . "' " : '') . ',';//
				    	if($v['field_index'] == 'index')
				    	{
					    	$index_key .= $space . '`' . $v['field_name'] . '`';
					    	$space = ',';
				    	}				    	
				    	if($v['field_index'] == 'primary')
				    	{
					    	$primary_key .= $p_space . '`' . $v['field_name'] . '`';
					    	$p_space = ',';
				    	}
				    }
				    $other_key .= $index_key ? $this->settings['field_index_value']['index'] . "(" . $index_key . ")," : "";
				    $other_key .= $primary_key ? $this->settings['field_index_value']['primary'] . "(" . $primary_key . ")," : "";
				    $other_key = trim($other_key,',');
			    	$sql .= $other_key . ') ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';	
			    	$this->db->query($sql);
			    	$data['table_format'] = serialize($info);
		    	}		    	
	    	}
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "INSERT INTO " .DB_PREFIX. "table_info SET " . $extra;
		    
		    $this->db->query($sql);
		    $data['id'] = $this->db->insert_id();
		    return $data;
	    }
    }
    
    public function update($data,$id)
    {
	    if(!empty($data) && $id)
	    {
	    	$table_info = $this->detail($id);
	    //ALTER TABLE  `liv_car_info` CHANGE  `HPZL`  `HPZLss` CHAR( 155 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'aaaa'
	    //ALTER TABLE  `liv_car_info` ADD  `addd` VARCHAR( 18 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'aaaa'
	  //  return $table_info;exit;
	    	$table_format = array();
	    	if($table_info && $data['table_format'])
	    	{
	    		$table_format = $data['table_format'];
		    	$length = count($table_format['field_name']);
		    	$info = array();
		    	for($i = 0;$i < $length;$i++)
		    	{
		    		if($table_format['field_name'][$i] && $table_format['field_type'][$i])
		    		{
			    		$info[$table_format['field_key'][$i]] = array(
			    			'field_name' => $table_format['field_name'][$i],
			    			'field_type' => $table_format['field_type'][$i],
			    			'field_auto' => $table_format['field_auto'][$i],
			    			'field_length' => $table_format['field_length'][$i],
			    			'field_index' => $table_format['field_index'][$i],
			    			'field_mark' => $table_format['field_mark'][$i],
			    			'field_key' => $table_format['field_key'][$i], 	    		
			    		);
		    		}
		    	}
	    		 
		    	if($info)
		    	{
		    		$index_key = $space = "";
		    		$primary_key = $p_space = "";
		    		$tables_exists = $this->checkTableExists($table_info['table_name']);    		
		    		if(!$tables_exists)
		    		{
			    		return array('error' => TABLES_IS_NOT_EXISTS);
		    		}
		    		$action = array();
		    		$action['create'] = array_diff_key($info,$table_info['table_format']);//取老数据中没有的键
		    		$action['delete'] = array_diff_key($table_info['table_format'],$info);//取新数据中没有的键
		    		$action['update'] = array_intersect_key($info,$table_info['table_format']);//取新旧数据重合的键
		    		
		    		if($table_info['table_name'] != $data['table_name'])
		    		{
			    		$sql = "RENAME TABLE  `" . DB_PREFIX . $table_info['table_name'] . "` TO  `" . DB_PREFIX . $data['table_name'] . "` ;";
			    		$this->db->query($sql);
		    		}
		    		
		    		if($action['create'])
		    		{
		    			$sql = "ALTER TABLE  `" . DB_PREFIX . $data['table_name'] . "`";
		    			$space = '';
		    			foreach($action['create'] as $k => $v)
		    			{
			    			$sql .= $space . " ADD  `" . $v['field_name'] . "` " . $v['field_type'] . (intval($v['field_length']) ? "(" . intval($v['field_length']) . ")" : "") . " " . (in_array($v['field_type'],array('tinyint','int'))? '' : "CHARACTER SET utf8 COLLATE utf8_general_ci") . " NOT NULL " . ( $v['field_index'] == 'index' ? " ADD INDEX (  `" . $v['field_name'] . "` ) " : "") . ($v['field_mark'] ? " COMMENT '" . $v['field_mark'] . "' " : '');
			    			$space = ',';
		    			}
			    		$this->db->query($sql);
		    		}
		    		if($action['update'])
		    		{
		    			$action['update'] = array_intersect($action['update'],$table_info['table_format']);
		    			if($action['update'])
		    			{
			    			$sql = "ALTER TABLE  `" . DB_PREFIX . $data['table_name'] . "`";
			    			$sql_extra = $space = '';
			    			foreach($action['update'] as $k => $v)
			    			{
				    			$sql_extra .= $space . " CHANGE `" . $table_info['table_format'][$k]['field_name'] . "` `" . $v['field_name'] . "` " . $v['field_type'] . (intval($v['field_length']) ? "(" . intval($v['field_length']) . ")" : "") . " " . (in_array($v['field_type'],array('tinyint','int')) ? '' : "CHARACTER SET utf8 COLLATE utf8_general_ci") . " NOT NULL " . ($v['field_auto'] ? 'AUTO_INCREMENT' : '') . " " . ($v['field_mark'] ? " COMMENT '" . $v['field_mark'] . "' " : '');
				    			$space = ',';
				    			if($v['field_index'] == 'index')
				    			{
					    		//	$sql_index = $sql . " DROP INDEX (`" . $v['field_name'] . "*`) ";
					    		//	$this->db->query($sql_index);
					    			$sql_index = $sql . " ADD INDEX (`" . $v['field_name'] . "`) ";
					    			$this->db->query($sql_index);
				    			}
			    			}
			    			
			    			$sql .= $sql_extra;
			    			$this->db->query($sql);
		    			}
		    		}
		    	
		    		if($action['delete'])
		    		{
			    		$sql = "ALTER TABLE  `" . DB_PREFIX . $data['table_name'] . "`";
		    			$space = '';
		    			foreach($action['delete'] as $k => $v)
		    			{
			    			$sql .= $space . " DROP  `" . $v['field_name'] . "` ";
			    			$space = ',';
		    			}
			    		$this->db->query($sql);
		    		}
			    	$data['table_format'] = serialize($info);
		    	}		    	
	    	}
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "UPDATE " .DB_PREFIX. "table_info SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
		    return $data;
		    
	    }
    }
    
    public function count($condition='')
    {
	    $sql = "SELECT count(*) as total FROM " .DB_PREFIX. "table_info WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }
    
    public function audit($ids,$state)
    {
 
    }
    
    public function delete($id)
    {
    	if($id)
    	{
		    $table_info = $this->detail($id);
		    if($table_info)
		    {
		    	$sql = "DROP TABLE " . DB_PREFIX . $table_info['table_name'];
	    		$tables_exists = $this->checkTableExists($table_info['table_name']);		    		
	    		if(!$tables_exists)
	    		{
		    		return array('error' => 'TABLES IS EXISTS');
	    		}
				$sql = "DELETE FROM " .DB_PREFIX. "table_info WHERE id=" . $id;
		    	$this->db->query($sql);
		    	return array('id' => $id); 
		    }		
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "table_info WHERE id=" . $id;
	    }
	    else
	    {
		     $sql = "SELECT * FROM " .DB_PREFIX. "table_info WHERE 1";
	    }
        $f = $this->db->query_first($sql);
        if(empty($f))
        {
	        return false;
        }
        else
        {
	        $f['table_format'] = unserialize($f['table_format']);
	        return $f;
        }
    }
    
    public function checkTableExists($table_name)
    {
	    $sql = "SHOW TABLES LIKE '" . DB_PREFIX . $table_name . "'";
		$tables_exists = $this->db->num_rows($this->db->query($sql));
		if($tables_exists == 1)
		{
    		return true;
		}
		else
		{
			return false;
		}
    }
    
    public function checkTableName($table_name,$id = 0)
    {
	    if($table_name)
	    {
	    	$con = '';
	    	if($id)
	    	{
		    	$con = " AND id NOT IN(" . $id . ")";
	    	}
		    $sql = "SELECT * FROM " .DB_PREFIX. "table_info WHERE table_name='" . trim($table_name) . "'" . $con;
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