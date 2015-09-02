<?php
class auditRecord extends initFrm
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        parent::__construct();
    }
    
    public function show($condition)
    {
    	$sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE ".$condition;
    	//echo $sql;die;
    	$q = $this->db->query($sql);
    	$data = $audit_record = array();
        $bill_id = $space = '';
        //$i = 0;
    	while($row = $this->db->fetch_array($q))
        {
        	if($row['audit_level'] > 0)//说明有上下审核先后顺序的
        	{
        		$sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE state=0 AND bill_id=" . $row['bill_id'] . " AND audit_level<" . $row['audit_level'];
        		//echo $sql;die;
        		$f = $this->db->query_first($sql);
        		if(!$f)
        		{
		        	if($row['bill_id'])
		        	{
			        	$bill_id .= $space . $row['bill_id'];
			        	$space = ',';
		        	}
	        		$data[] = $row;
        		}
        		//存在，说明上一级审核未通过	        	
        	}
        	else//无顺序
        	{
	        	if($row['bill_id'])
	        	{
		        	$bill_id .= $space . $row['bill_id'];
		        	$space = ',';
	        	}
        		$data[] = $row;
        	}
        }
        
        if($bill_id)
        {
        	include_once(CUR_CONF_PATH . 'lib/bill.class.php');
        	$bill = new bill();
        	$bill_info = $tmp = array();
        	$tmp = $bill->show(' AND id IN(' . $bill_id . ')');
        	if($tmp)
        	{
        		foreach($tmp as $k => $v)
        		{
	        		$bill_info[$v['id']] = array(
	        							'project_name' => $v['project_name'],
	        							'cause' => $v['cause'],
	        							'user_id' => $v['user_id'],
	        							'user_name' => $v['user_name'],
	        						);
        		}
		        foreach($data as $k => $v)
		        {
			        $data[$k]['bill'] = $bill_info[$v['bill_id']];
		        }
        	}
        }
        return $data;
    }
    
    public function create($data)
    {
    /*
	    if(!empty($data))
	    {
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "INSERT INTO " .DB_PREFIX. "audit_record SET " . $extra;
		    
		    $this->db->query($sql);
		    $data['id'] = $this->db->insert_id();
		    return $data;
	    }
	    */
    }
    
    public function update($data,$id)
    {
    	/*
	    if(!empty($data) && $id)
	    {
	    	$extra = $space = '';
		    foreach($data as $k => $v)
		    {
			    $extra .= $space . $k . "='" . $v . "'";
			    $space = ',';
		    }
		    $sql = "UPDATE " .DB_PREFIX. "audit_record SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
		    return $data;
	    }
	    */
    }
    
    public function count($condition='')
    {
	    $sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "audit_record WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }
    
    public function audit($id,$state,$reason = '111')
    {
	    if($id)
	    {
	    	switch($state)
	    	{
		    	case 1://审核
		    		$sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE state!=1 AND type='baoxiao' AND id IN(" . $id . ")";
					$q = $this->db->query($sql);
					while($row = $this->db->fetch_array($q))
					{
						if($row['bill_id'])
						{
							$sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE state=0 AND bill_id=" . $row['bill_id'] . " AND audit_level<" . $row['audit_level'];
			        		$f = $this->db->query_first($sql);
			        		if($f)
			        		{
				        		return array('error' => WAIT_PRIMARY_AUDIT);
			        		}
						}
		        		
		        		$sql = "UPDATE " .DB_PREFIX. "audit_record SET state=1 WHERE id=" . $row['id'];
		        		$this->db->query($sql);
		        		$sql = "UPDATE " .DB_PREFIX. "bill SET is_approve=0,audit_level=audit_level+1,update_time=".TIMENOW." WHERE id=" . $row['bill_id'];//前者打回后审核通过 
		        		$this->db->query($sql);
		        		$sql = "DELETE FROM " . DB_PREFIX . "audit_queue WHERE audit_record_id=" . $row['id'];
		        		$this->db->query($sql);
		        		if($row['bill_id'])
		        		{
			        		/****验证是否审核完毕****/
			        		$sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "audit_queue WHERE bill_id=" . $row['bill_id'];
			        		$sen = $this->db->query_first($sql);
			        		if(!$sen['total'])//审核通过，需要解锁，解除审核人的锁，无需解除报销单的锁，并且更新报销单状态，审批通过
			        		{
			        			$sql = "SELECT auditor_id FROM " .DB_PREFIX. "bill WHERE id=" . $row['bill_id'];
			        			$third = $this->db->query_first($sql);
			        			$sql = "UPDATE " .DB_PREFIX. "auditor SET locked=locked-1 WHERE id=" . $third['auditor_id'];
			        			$this->db->query($sql);
			        			$sql = "UPDATE " .DB_PREFIX. "bill SET is_approve=1,update_time=".TIMENOW." WHERE id=" . $row['bill_id'];
			        			$this->db->query($sql);
			        		}
		        		}
		        		return array('id' => $id,'status' => $state);         		
					}		    		
		    	break;
		    	case 2://打回
		    		$sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE state=0 AND type='baoxiao' AND id=" . $id;
					$f = $this->db->query_first($sql);
					//print_r($f);die;
					if(!$f)
					{
						return array('error' => NO_CONTENT);
					}
					if(!$reason)
					{
						return array('error' => NO_CALL_REASON);
					}
					$sql = "UPDATE " .DB_PREFIX. "audit_record SET state=2,reason='" . $reason . "' WHERE id=" . $f['id'];//打回
		        	$this->db->query($sql);
        			$sql = "UPDATE " .DB_PREFIX. "bill SET is_approve=2,update_time=".TIMENOW." WHERE id=" . $f['bill_id'];
        			$this->db->query($sql);        			
        			return array('id' => $id,'status' => $state);
		    	break;
		    	default:
		    	break;
	    	}
	    }
    }
  
    public function delete($ids)
    {
    /*
    	if($ids)
    	{
			$sql = "delete from " .DB_PREFIX. "audit_record WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
		    return array('id' => $ids); 
    	}  
    	*/
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE id=" . $id;
	        
	    }
	    else
	    {
	    	$sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE 1";
	    }
	    $f = $this->db->query_first($sql);
        if($f)
        {
	        $f['info'] = unserialize($f['info']);
        }
        return $f;
    }
    public function show_audit($bill_id)
    {
        $sql = "SELECT * FROM " .DB_PREFIX. "bill WHERE id=" . $bill_id;
	    $f = $this->db->query_first($sql);
	
	    $sql = "SELECT * FROM " .DB_PREFIX. "audit_queue WHERE bill_id=" . $bill_id;
	    $q = $this->db->query($sql);
	    $audit_record_id = $space = "";
		while($row = $this->db->fetch_array($q))
	    {
	        $audit_record_id .=  $space . $row["audit_record_id"];
	        $space = ",";
	    }
	   //还有下一级审批则获取下一级审批人 若打回或审批完成获取报销人
	    if($audit_record_id != "" && $f['is_approve'] != 2 )
	    {   
		    $sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE id IN(" . $audit_record_id .")";
	        $q = $this->db->query($sql);
		    while($row = $this->db->fetch_array($q))
		    {     
		        if($row['audit_level'] == $f['audit_level'])
		        {
			        $audit_user_name = $row['user_name'];
			        $audit_user_id = $row['user_id'];
		        }
		    }
		    $state = 1;
	    } 
	    else
	    {
		    $audit_user_id = $f['user_id'];
		    include_once(ROOT_PATH . 'lib/class/auth.class.php');
		    $auth = new auth();
	        $tmp = $auth->getMemberById($audit_user_id);
	        foreach($tmp as $k => $v)
	        {
	   		    $audit_user_name = $v['user_name'];
	        }
	        $state = 2 ;
	    }
	    return array('id' => $id,'audit_user_name' => $audit_user_name,'audit_user_id' => $audit_user_id , 'state' => $state);	   	
    }

}
?>