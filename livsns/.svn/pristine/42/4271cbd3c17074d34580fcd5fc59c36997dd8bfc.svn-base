<?php
class qingjia_audit_record extends initFrm
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
        $sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE  type = 'qingjia'". $condition; 
    	$q = $this->db->query($sql);
    	$data = $audit_record = array();
        $bill_id = $space = '';
    	while($row = $this->db->fetch_array($q))
        {
	        if($row['bill_id'])
	        {
		        $bill_id .= $space . $row['bill_id'];
		        $space = ',';   	
                $data[] = $row;
		    }

        }
        if($bill_id)
        {
        	include_once(CUR_CONF_PATH . 'lib/qingjia_record.class.php');
        	$bill = new qingjia_record();
        	$bill_info = $tmp = array();
        	$tmp = $bill->show(' AND id IN(' . $bill_id . ')'); // print_r($tmp);
            if($tmp)
        	{
        		foreach($tmp as $k => $v)
        		{
		
    		     $bill_info[$v['id']] = array(
    							'is_approve' => $v['is_approve'],
    							'remark' => $v['remark'],
    							'start_time' => $v['start_time'],
    							'end_time' => $v['end_time'],
    							'sort_id' => $v['sort_id'],
    							'cause' => $v['cause'],
    							'user_id' => $v['user_id'],
    							'user_name' => $v['user_name'],
    							'sort_name' => $v['sort_name'],
    							'img' => $v['img'],
    							'real_start_time' => $v['xiaojia']['start_time'],
    							'real_end_time' => $v['xiaojia']['end_time'],
					           );
	        		  
        		} 
		        foreach($data as $k => $v)
		        {
			        $data[$k]['bill'] = $bill_info[$v['bill_id']];
		        }
        	}
        }	
        include_once(CUR_CONF_PATH . 'lib/qingjia_record.class.php');
    	$message = new qingjia_record();
    	$message_info = $message_tmp = array();
    	$message_tmp = $message->show_qingjia_message( ); // print_r($tmp);
   
    	foreach($data as $k => $v)
		{
             foreach($message_tmp as $key => $vo)
    		 { 
    		     if($v['bill_id'] == $vo['record_id'])
    		     {
   	                $message_info[$v['id']][] = array(
	                   'reason' => $vo['reason'], 
	   	               'remark' => $vo['remark'], 
	   	               'user_id' => $vo['user_id'], 
	   	               'user_name' => $vo['user_name'], 
	   	               'create_time' => $vo['create_time'], 
	    	           );
 	              }
 	         }
	    }
        foreach($data as $k => $v)
	    {
		    if($message_info)
		    	$data[$k]['message'] = $message_info[$v['id']];
	    }
	    
        return $data;    
    }
    

     public function showinfo($bill_id, $condition)
     {
      	$sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE 1 and  reason <> '' and state = 2 and bill_id = ".$bill_id . $condition;
        $q = $this->db->query_first($sql);
        return $q ;    
     }
   
     public function show_message($id)
     {
      	 $sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE id = ".$id  ;
         $q = $this->db->query($sql);
         while($row = $this->db->fetch_array($q)){
	        $sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE bill_id = ".$row['bill_id']." and type ='qingjia' and reason <> '' ";
	        $r = $this->db->query_first($sql);      
         }
         return $r;    
     }

    public function audit($id,$state,$reason = '')
    {
	    if($id)
	    {
	    	switch($state)
	    	{
		    	case 1://审核
		    		$sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE id  = " . $id ;
					$q = $this->db->query($sql);
					while($row = $this->db->fetch_array($q))
					{
		        		$sql = "UPDATE " .DB_PREFIX. "audit_record SET state=1 WHERE type ='qingjia' and  bill_id =" . $row['bill_id']; 
		        		$this->db->query($sql);
	        			$sql = "UPDATE " .DB_PREFIX. "qingjia_record SET is_approve=1 WHERE id=" . $row['bill_id'];
	        			$this->db->query($sql); 
		        		return array('id' => $id,'status' => $state);         		
					}		    		
		    	break;
		    	case 2://打回
		    		$sql = "SELECT * FROM " .DB_PREFIX. "audit_record WHERE id=" . $id;
					$f = $this->db->query_first($sql);
					if(!$f)
					{
						return array('error' => NO_CONTENT);
					}
                    if(!$reason)
					{
						return array('error' => NO_CALL_REASON);
					}
                    $sql = "UPDATE " .DB_PREFIX. "audit_record SET state=2,reason='" . $reason . "' WHERE id=" . $id; //打回
                    $this->db->query($sql);
					$sql = "UPDATE " .DB_PREFIX. "audit_record SET state=2 WHERE type='qingjia' and bill_id=" . $f['bill_id']; //打回
		        	$this->db->query($sql);
        			$sql = "UPDATE " .DB_PREFIX. "qingjia_record SET is_approve=2 WHERE id=" . $f['bill_id'];
        			$this->db->query($sql);        			
        			return array('id' => $id,'status' => $state);
		    	break;
		    	default:
		    	break;
	    	}
	    }
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
		    $sql = "INSERT INTO " .DB_PREFIX. "audit_record SET " . $extra;
		    
		    $this->db->query($sql);
		    $data['id'] = $this->db->insert_id();
		    return $data;
	    }
	    
    }
    
    public function update($data,$id)
    {
    
    }
    
    public function count($condition='')
    {
	    $sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "audit_record WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }

   
        
    public function delete($ids)
    {
 
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
}
?>