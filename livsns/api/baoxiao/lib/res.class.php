<?php
class res extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "res " .' '. $condition;
      	//echo $sql;die;
        $q = $this->db->query($sql);
        $data = array();
        $source_id = $space = '';
        while($row = $this->db->fetch_array($q))
        {
        	$data[] = $row;
        }
        $sql = "SELECT * FROM " .DB_PREFIX. "res_sort";
        $p =  $this->db->query($sql);
        while($re = mysql_fetch_assoc($p))
        {
	        $sort[] = $re;
        }
        if($sort)
        {
	        foreach($sort as $vo)
	        {
		        foreach($data as $key => $v)
		        {
			        if($vo['id'] == $v['sort_id'])
			        {
				        $data[$key]['sort_name'] = $vo['name'];
			        }
		        }
	        }
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
		    $sql = "INSERT INTO " .DB_PREFIX. "res SET " . $extra;
		    $this->db->query($sql);
		    $data['id'] = $this->db->insert_id();
		    if(mysql_insert_id())
		    {
			    $sql = "update ".DB_PREFIX. "res_sort SET stock=stock+1,usable=usable+1 WHERE id=" .$data['sort_id'];
			   
			    $this->db->query($sql);
		    }
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
		    $sql = "update " .DB_PREFIX. "res SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
		    if(isset($data['state']))
		    {
			    $sql = "SELECT sort_id FROM ".DB_PREFIX."res WHERE id=".$id;
				$re = $this->db->query($sql);
				$arr = mysql_fetch_assoc($re);
				$sort_id = $arr['sort_id'];
				if($data['state'] == 0){ //判断更改库存可用数
					$sql = "UPDATE ".DB_PREFIX."res_sort SET usable=usable+1 WHERE id=".$sort_id;
				}else{
					$sql = "UPDATE ".DB_PREFIX."res_sort SET usable=usable-1 WHERE id=".$sort_id;
				}
				$this->db->query($sql);
		    }
		    $data['id'] = $id;
		    return $data;
	    }
    }
    public function delete($ids)
    {
    	if($ids)
    	{
    		$sql ="SELECT sort_id,state FROM ".DB_PREFIX. "res WHERE id IN(" . $ids . ")";
    		$re = $this->db->query($sql);
    		$re =  mysql_fetch_assoc($re);
    		$sort_id = $re['sort_id'];
    		$state = $re['state'];
			$sql = "DELETE FROM " .DB_PREFIX. "res WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
		    if($state == 0)
		    {
		    	$sql = "UPDATE ".DB_PREFIX."res_sort SET stock=stock-1,usable=usable-1 WHERE id=".$sort_id;
		    }else{
			    $sql = "UPDATE ".DB_PREFIX."res_sort  SET stock=stock-1 WHERE id=".$sort_id;
		    }
		    $this->db->query($sql);
   		    return array('id' => $ids); 
    	}  
    }
     public function checkName($name,$id=0)
    {
	    if(!empty($name))
	    {
		   $sql = "SELECT * FROM " .DB_PREFIX. "res WHERE name='" . trim($name) . "'";
		   if($id)
		   {
			   $sql .= ' AND id !=' .$id;
		   }
		   return $this->db->query_first($sql);
	    }
    }
}
?>