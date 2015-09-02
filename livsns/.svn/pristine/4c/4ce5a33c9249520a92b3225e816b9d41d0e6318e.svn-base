<?php
class billRecord extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "bill_record WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $data = array();
        $user_id_array = array();
        $user_id = $space = '';
        $bill_id = 0;//目前取记录，根据某个订单的来检索
        include_once(CUR_CONF_PATH . 'lib/bill.class.php');
	    $this->bill = new bill();
        while($row = $this->db->fetch_array($q))
        {
        	$checkbool = $row['bill_id'] ? $this->bill->checkLocked($row['bill_id']):0;
        	$row['locked'] = $checkbool ? 1 : 0;
        	if($row['user_id'])
        	{
        		$user_id_array[$row['user_id']] = $row['user_id'];
        	}
	        $data[] = $row;
        }
        
        if($user_id_array)
        {
        	$user_id = implode(',', $user_id_array);
	        include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $tmp = $auth->getMemberById($user_id);
		    $user_info = array();
		    foreach($tmp as $k => $v)
		    {
		   		$user_info[$v['id']] = $v['user_name'];
		    }
		    foreach($data as $k => $v)
		    {
			    $data[$k]['user_name'] = $user_info[$v['user_id']];
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
		    $sql = "INSERT INTO " .DB_PREFIX. "bill_record SET " . $extra;
		    $this->db->query($sql);
		    $data['id'] = $this->db->insert_id();
		    $sql = "UPDATE " .DB_PREFIX. "bill_record SET order_id=" . $data['id'] . " WHERE id=" . $data['id'];
		    $this->db->query($sql);
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
		    $sql = "update " .DB_PREFIX. "bill_record SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
		    $data['id'] = $id;
		    return $data;
	    }
    }
    
    public function audit($ids,$state)
    {
    	 $sql = "SELECT * FROM " .DB_PREFIX. "bill_record WHERE id IN(" . $ids . ")";
    	 $f = $this->db->query_first($sql);
    	 if($f['bill_id'])//目前审核，只是批量某个订单的全部审核
    	 {
	    	include_once(CUR_CONF_PATH . 'lib/bill.class.php');
	    	$this->bill = new bill();
	    	$checkbool = $this->bill->checkLocked($f['bill_id']);
	    	if($checkbool)
	    	{
		    	return array('error' => THIS_IS_LOCKED);
	    	}
    	 }
    	 
	    $sql = "UPDATE " .DB_PREFIX. "bill_record SET state=" . $state . " WHERE id IN(" . $ids . ")";
	    $this->db->query($sql);
	    $sql = "SELECT * FROM " .DB_PREFIX. "bill_record WHERE id IN(" . $ids . ")";
	    $bill_id = 0;//目前审核，只是批量某个订单的全部审核
	    $q = $this->db->query($sql);
	    while($row = $this->db->fetch_array($q))
	    {
	    	$bill_id = $row['bill_id'];
		    if($state == 1)//审核通过 +1
		    {
			    $this->updateSortCount($row['sort_id'],1);
		    }
		    if($state == 2)//打回 -1
		    {
			    $this->updateSortCount($row['sort_id'],-1);
		    }		    
	    }	    	    
	    return array('id' => $ids,'status' => $state,'bill_id' => $bill_id); 
    }
    
    public function updateSortCount($sort_id,$action)
    {
    	if($sort_id)
    	{
    		$con = '';
    		$sql = "SELECT * FROM " .DB_PREFIX. "sort WHERE id=" . $sort_id;
    		$f = $this->db->query_first($sql);
    		if($f['cost_count'] < 0)
    		{
	    		$con = '0';
    		}
    		else
    		{
	    		$con = "cost_count+" . $action;
    		}
		    $sql = "UPDATE " . DB_PREFIX . "sort SET cost_count=" . $con . " WHERE id=" . $sort_id;
		    $this->db->query($sql);
		    return true;
    	}
    	return false;
     }
    
    public function count($condition='')
    {
	    $sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "bill_record WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }
    
    public function reaccess($bill_id,$cost)
    {
	    $sql = "UPDATE  " .DB_PREFIX. "bill SET cost=" . $cost . " WHERE id=" . $bill_id;
	    $this->db->query($sql);
	    return true;
    }
    
    public function delete($ids)
    {
    	if($ids)
    	{
    		$sql = "SELECT material_id,sort_id,bill_id FROM " .DB_PREFIX. "bill_record WHERE id IN(" . $ids . ")";
    		$q = $this->db->query($sql);
    		$mid = $space = '';
    		$bill_id = 0;
    		$sort = array();
    		while($row = $this->db->fetch_array($q))
    		{
	    		$mid .= $space . $row['material_id'];
	    		$space = ',';
	    		if($row['sort_id'])
	    		{
	    			$sort[] = $row['sort_id'];		    		
	    		}
	    		$bill_id = $row['bill_id'];//目前删除，批量删除，只能删除某个订单下的
    		}
	    	if($bill_id)
	    	{
		    	include_once(CUR_CONF_PATH . 'lib/bill.class.php');
		    	$this->bill = new bill();
		    	$checkbool = $this->bill->checkLocked($bill_id);
		    	if($checkbool)
		    	{
			    	return array('error' => THIS_IS_LOCKED);
		    	} 
	    	}
			$sql = "DELETE FROM " .DB_PREFIX. "bill_record WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);
    		/****删除成功成功执行以下操作*****/
    		if($mid)
    		{
	    		include_once(ROOT_PATH . 'lib/class/material.class.php');
	    		$mater = new material();
	    		$mater->delMaterialById($mid);
    		}
    		if($sort)
    		{
	    		foreach($sort as $key => $value)
	    		{
		    		$this->updateSortCount($value,-1);
	    		}
    		}
    		/*****end******/
		    return array('id' => $ids,'bill_id' => $bill_id); 
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "bill_record WHERE id=" . $id;
	    }
	    else
	    {
		      $sql = "SELECT * FROM " .DB_PREFIX. "bill_record WHERE 1";
	    }
		$f = $this->db->query_first($sql);
		if($f)
		{
			include_once(CUR_CONF_PATH . 'lib/bill.class.php');
			$this->bill = new bill();
        	if($f['bill_id'])
        	{
	        	$checkbool = $this->bill->checkLocked($f['bill_id']);
	        	$f['locked'] = $checkbool ? 1 : 0;
        	}
        	$f['img'] = unserialize($f['img']);
        	$f['img_url'] = hg_fetchimgurl($f['img'],200,200);
		}
		return $f;
    }
    
    public function checkState($record_id)
    {
	    if($record_id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "bill_record WHERE id IN(" . trim($record_id) . ")";
		    $q = $this->db->query($sql);
		    while($row = $this->db->fetch_array($q))
		    {
			    if($row['state'] != 1)
			    {
				    return array('error' => NO_AUDITED);
			    }
		    }
		    return true;
	    }
	    return array('error' => NO_RECORDID);
    }
    
    public function update_bill($bill_id,$record_id)
    {
	    if($bill_id && $record_id)
	    {
	    	$sql = "UPDATE " .DB_PREFIX. "bill_record SET bill_id=0 WHERE bill_id=" . intval($bill_id);
	    	$this->db->query($sql);
		    $sql = "UPDATE " .DB_PREFIX. "bill_record SET bill_id=" . intval($bill_id) . " WHERE state=1 AND id IN(" . trim($record_id) . ")";//只能更新审核通过
		    $this->db->query($sql);
		    $rows = $this->db->affected_rows();
		    if($rows)
		    {
		    	$condition = " AND state=1 AND bill_id=" . $bill_id;//某个单子下的已审核的状态
		        $data = array();
		        $data = $this->show($condition);
		        $ret = array();
		        if($data)
		        {
		        	$tmp = 0;
			        foreach($data as $key => $value)
			        {
			        	$tmp += $value['cost'];
			        }
			        if($tmp)
			        {
				        $ret = array(
				        	'total' => $tmp,
				        );
			        }
			        $this->reaccess($bill_id,$ret['total']);//并且重新统计
		        }
		    	return true;   
		    }
		    else
		    {
			    return false;
		    }
	    }
	    return false;
    }
}
?>