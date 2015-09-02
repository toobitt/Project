<?php
class sourceSetting extends initFrm
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
        $sql = "SELECT * FROM " .DB_PREFIX. "source_setting WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $data = array();
        while($row = $this->db->fetch_array($q))
        {
        	$row['create_time_show'] = date('Y-m-d H:i:s',$row['create_time']);
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
		    $sql = "INSERT INTO " .DB_PREFIX. "source_setting SET " . $extra;
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
		    $sql = "update " .DB_PREFIX. "source_setting SET " . $extra . " WHERE id=" . $id;
		    
		    $this->db->query($sql);
		    return $data;
	    }
    }
    public function audit($ids,$state)
    {
		$sql = "update " .DB_PREFIX. "source_setting SET state=" . $state . " WHERE id IN(" . $ids . ")";
	    $this->db->query($sql);
	    return array('id' => $ids,'status' => $state);   
    }
    public function delete($ids)
    {
    	if($ids)
    	{
			$sql = "delete from " .DB_PREFIX. "source_setting WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
		    return array('id' => $ids); 
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "source_setting WHERE id=" . $id;
	        $f = $this->db->query_first($sql);
	        return $f;
	    }
    }
}
?>