<?php
class business_auth_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "business_auth  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['status_text'] = $this->settings['business_auth_status'][$r['status']];
			$r['type_text'] = $this->settings['business_auth_type'][$r['type']];
			if($r['open_time'])
			{
			    $r['open_time_text'] = date('Y-m-d H:i',$r['open_time']);//开通时间
			    $r['expire_time_text'] = date('Y-m-d H:i',$r['open_time'] + $r['auth_duration']);//到期时间
			}
			else 
			{
			    $r['open_time_text']   = '未开通';
			    $r['expire_time_text'] = '未开通';
			}
			
			$r['pay_status_text'] = $r['pay_status']?'是':'否';
			
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "business_auth SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."business_auth SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "business_auth WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "business_auth SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '',$cond = '')
	{
	    if(!$id && !$cond)
		{
			return false;
		}
		
	    if($id)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "business_auth  WHERE id = '" .$id. "'";
		}
		else
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "business_auth  WHERE 1 " . $cond;
		}
		
		$info = $this->db->query_first($sql);
		if($info)
		{
		    $info['type_text'] = $this->settings['business_auth_type'][$info['type']];
		    $info['status_text'] = $this->settings['business_auth_status'][$info['status']];
		    return $info;
		}
		else 
		{
		    return FALSE;
		}
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "business_auth WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "business_auth WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "business_auth WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "business_auth WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "business_auth SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//记录交易日志
    public function createTradeLog($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "business_log SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	public function updateTradeLog($id,$data = array())
	{
	    if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "business_log WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "business_log SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	//创建一条发票申请
	public function createInvoiceApply($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "business_invoice SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."business_invoice SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	//更新一条发票申请
	public function updateInvoiceApply($id,$data = array())
	{
	    if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "business_invoice WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "business_invoice SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function getPayLogByUserId($user_id = '',$cond = '')
	{
	    if(!$user_id)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "business_log WHERE user_id = '" .$user_id. "' " .$cond. " ORDER BY create_time DESC ";
	    $q = $this->db->query($sql);
	    $ret = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $r['type_text'] = $this->settings['pay_type'][$r['type']];
	        $r['pay_reason_text'] = $this->settings['business_pay_reason'][$r['pay_reason']];
	        $r['bank'] = $this->settings['banks'][$r['bank_id']]; 
	        $r['status_text'] = $r['status']?'已付款':'未付款';
	        $r['pay_time_text'] = $r['pay_time']?date('Y-m-d H:i',$r['pay_time']):'未付款';
	        $r['create_time'] = date('Y-m-d H:i',$r['create_time']);
	        $r['open_time_text'] = $r['open_time']?date('Y-m-d',$r['open_time']):'未开通';
	        $r['over_time_text'] = $r['open_time']?date('Y-m-d',$r['open_time'] + $r['auth_duration']):'未开通';
	        $r['auth_duration'] = $r['auth_duration'] / (365 * 24 * 3600) . '年';
	        $ret[] = $r;
	    }
	    return $ret;
	}
	
	public function updateApply($cond = '',$data = array())
	{
	    if(!$cond || !$data)
	    {
	        return FALSE;
	    }
	    
	    //查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "business_auth WHERE 1 " .$cond;
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
	    
	    //更新数据
		$sql = " UPDATE " . DB_PREFIX . "business_auth SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE 1 "  .$cond;
		$this->db->query($sql);
	    return $pre_data;
	}
	
	//获取发票申请详情
    public function detailInvoiceApply($id = '',$cond = '')
	{
	    if(!$id && !$cond)
		{
			return false;
		}
		
	    if($id)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "business_invoice  WHERE id = '" .$id. "'";
		}
		else
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "business_invoice  WHERE 1 " . $cond;
		}
		
		$info = $this->db->query_first($sql);
		if($info)
		{
		    $info['invoice_type_text'] = $this->settings['invoice_type'][$info['invoice_type']];
		    if($info['taxpayer_cert'] && unserialize($info['taxpayer_cert']))
			{
				$info['taxpayer_cert'] = unserialize($info['taxpayer_cert']);
			}
			else 
			{
				$info['taxpayer_cert'] = array();
			}
			
		    if($info['tax_register_cert'] && unserialize($info['tax_register_cert']))
			{
				$info['tax_register_cert'] = unserialize($info['tax_register_cert']);
			}
			else 
			{
				$info['tax_register_cert'] = array();
			}
			$info['status_text'] = $this->settings['invoice_status'][$info['status']];
		    return $info;
		}
		else 
		{
		    return FALSE;
		}
	}
	
	//获取付款详情
    public function detailPayLog($id = '',$cond = '')
	{
	    if(!$id && !$cond)
		{
			return false;
		}
		
	    if($id)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "business_log  WHERE id = '" .$id. "'";
		}
		else
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "business_log  WHERE 1 " . $cond;
		}
		
		$info = $this->db->query_first($sql);
		if($info)
		{
		    return $info;
		}
		else 
		{
		    return FALSE;
		}
	}
}