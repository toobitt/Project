<?php
class company extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "company WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $data = array();
        $source_id = $space = '';
        while($row = $this->db->fetch_array($q))
        {
        	$data[] = $row;
        }
        return $data;
    }
    
    public function getCompanyByToken($token)
    {
    	if($token)
    	{
    		$condition = " AND token='" . $token . "'";
			$sql = "SELECT * FROM " .DB_PREFIX. "company WHERE 1 " . $condition;
			$f = $this->db->query_first($sql);
			return $f;
    	}
    	return false;
    }
    
    public function checkRight($org_id)
    {
	    if(!$org_id)
	    {
		    return array('error' => NO_ORG_ID);
	    }
	    $all_org = $space = "";
	    $sql = "SELECT * FROM " .DB_PREFIX. "company WHERE 1 ";
	    $q = $this->db->query($sql);
	    while($row = $this->db->fetch_array($q))
	    {
		    $all_org .= $space . $row['org_children'];
		    $space = ',';
	    }
	    if($all_org)
	    {
		    $all_org_array = explode(',',$all_org);
		    if(in_array($org_id,$all_org_array))
		    {
			    return array('error' => COMPANY_HAVE_ONLY_ONE);
		    }
	    }
	    return true;
	    
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
		    $sql = "INSERT INTO " .DB_PREFIX. "company SET " . $extra;
		    
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
		    $sql = "UPDATE " .DB_PREFIX. "company SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
		    return $data;
	    }
    }
    
    public function count($condition='')
    {
	    $sql = "SELECT count(*) as total FROM " .DB_PREFIX. "company WHERE 1 " . $condition;
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
		    $sql = "SELECT * FROM " .DB_PREFIX. "company WHERE id=" . $id;
	    }
	    else
	    {
		     $sql = "SELECT * FROM " .DB_PREFIX. "company WHERE 1";
	    }
        $f = $this->db->query_first($sql);
        return $f;
    }
    
    public function checkExists($org_id,$need_father=0)
    {
	    if($org_id)
	    {
	    	$sql = "SELECT * FROM " .DB_PREFIX. "company WHERE 1";
	    	$q = $this->db->query($sql);
	    	while($row = $this->db->fetch_array($q))
	    	{
	    		if($need_father&&$row['org_id'])
	    		{
		    		return $row['org_id'];
	    		}
	    		elseif($row['org_children'])
	    		{
		    		$tmp_org = explode(',',$row['org_children']);
		    		if(in_array($org_id,$tmp_org))
		    		{
			    		return $tmp_org;
		    		}
	    		}    	
	    	}
	    	return false;
	    }
	    else
	    {
		    return false;
	    }
    }
    
    public function update_org($org_id,$id)
    {
	    if($org_id && $id)
	    {
	    	$sql = "UPDATE " .DB_PREFIX. "company SET org_id=" . $org_id . " WHERE id=" . $id;
	    	$this->db->query($sql);
	    	return true;
	    }
	    return false;
    }
    
    public function sysOrg()
	{
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$auth = new auth();
		$sql = "SELECT * FROM " .DB_PREFIX. "company WHERE 1";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($row['org_id'])
			{
				$ret = $auth->get_one_org($row['org_id']);
				$ret = $ret[0];
				if($row['org_children'] != $ret['childs'])
				{
					$sql = "UPDATE " .DB_PREFIX. "company SET org_children='" . $ret['childs'] . "' WHERE id=" . $row['id'];
					$this->db->query($sql);		
				}
			}
		}
		return true;
	}
}
?>