<?php
class xmlSetting extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "xml_setting WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $data = array();
        $source_id = $space = '';
        while($row = $this->db->fetch_array($q))
        {
        	$row['create_time_show'] = date('Y-m-d H:i:s',$row['create_time']);
        	$source_id .= $space . $row['source_id'];
        	$space = ',';
        	$data[] = $row;
        }
        if($source_id)
        {
	        $sql = "SELECT * FROM " .DB_PREFIX. "source_setting WHERE id IN(" . $source_id . ")";
	        $q = $this->db->query($sql);
	        $source = array();
	        while($row = $this->db->fetch_array($q))
	        {
		        $source[$row['id']] = $row['title'];
	        }
	        foreach($data as $k => $v)
	        {
		        $data[$k]['source_title'] = $source[$v['source_id']];
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
		    $sql = "INSERT INTO " .DB_PREFIX. "xml_setting SET " . $extra;
		    
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
		    $sql = "update " .DB_PREFIX. "xml_setting SET " . $extra . " WHERE id=" . $id;
		 //   file_put_contents('../cache/sss2',$sql);
		    $this->db->query($sql);
		    return $data;
	    }
    }
    public function audit($ids,$state)
    {
		$sql = "update " .DB_PREFIX. "xml_setting SET state=" . $state . " WHERE id IN(" . $ids . ")";
	    $this->db->query($sql);
	    return array('id' => $ids,'status' => $state);   
    }
    public function delete($ids)
    {
    	if($ids)
    	{
			$sql = "delete from " .DB_PREFIX. "xml_setting WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
		    return array('id' => $ids); 
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "xml_setting WHERE id=" . $id;
	        $f = $this->db->query_first($sql);
	        return $f;
	    }
    }
    
    public function verify_name($filename,$id=0)
    {
	    if($filename)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "xml_setting WHERE file_name='" . $filename . "'";
		    if($id)
		    	$sql .= " AND id != " . $id;
	        $f = $this->db->query_first($sql);
	        return $f;
	    }
	    else
	    {
		    return false;
	    }
    }
    
    public function xml_struct()
    {
	    $sql = "desc " .DB_PREFIX. "bulid_xml";
	    $q = $this->db->query($sql);
	    $data = array();
	    while($row = $this->db->fetch_array($q))
	    {
		    if(!in_array($row['Field'],array('id','create_time','create_user')))
		    {
			    $data[] = $row['Field'];
		    }
	    }
	    return $data;
    }
}
?>