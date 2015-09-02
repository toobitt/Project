<?php
class bill extends initFrm
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
      	$sql = "SELECT * FROM " .DB_PREFIX. "bill WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $data = array();
        $user_id = $space = '';
        $project_id = $space_second = '';
        while($row = $this->db->fetch_array($q))
        {
        	if($row['user_id'])
        	{
	        	$user_id .= $space . $row['user_id'];
	        	$space = ',';
        	}
        	if($row['project_id'])
        	{
	        	$project_id .= $space_second . $row['project_id'];
	        	$space_second = ',';
        	}
        	$row['cost_capital'] = hg_cny($row['cost']);
        	$row['advice_capital'] = hg_cny($row['advice']);
        	$data[] = $row;
        }
        if($user_id)
        {
	        include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
		    $tmp = $auth->getMemberById($user_id);
		    $user_info = array();
		    foreach($tmp as $k => $v)
		    {
		   		$user_info[$v['id']] = $v['user_name'];
		    }
        }
        if($project_id)
        {
	        include_once(CUR_CONF_PATH . 'lib/project.class.php');
			$project = new project();
			$project_info = $tmp = array();
			$tmp = $project->show(' AND id IN(' . $project_id . ')');
			foreach($tmp as $k => $v)
			{
				$project_info[$v['id']] = $v['name'];
			}
        }
        
	    foreach($data as $k => $v)
	    {
	    	if($user_info)
		    	$data[$k]['user_name'] = $user_info[$v['user_id']];
		    if($project_info)
		    	$data[$k]['project_name'] = $project_info[$v['project_id']];
		    if(!$v['title'])
		    	$data[$k]['title'] = date('Y-m-d',$v['business_time']) . '-' . $data[$k]['project_name'];
		    
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
		    $sql = "INSERT INTO " .DB_PREFIX. "bill SET " . $extra;
		    $this->db->query($sql);
		    $data['id'] = $this->db->insert_id();
		    $sql = "UPDATE " .DB_PREFIX. "bill SET order_id=" . $data['id'] . " WHERE id=" . $data['id'];
		    $this->db->query($sql);
		    if($data['state'] == 1 && $data['project_id'])
		    {
			    $this->updateBusinessCount($data['project_id'] , 1);
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
		    $old_info = $this->detail($id);
		    if($old_info['state'])//原来是审核通过，不管是否换project，旧的project 先-1
	    	{
	    		$this->updateBusinessCount($old_info['project_id'],-1);
	    	}
	    	$sql = "UPDATE " .DB_PREFIX. "bill SET " . $extra . " WHERE id=" . $id;
		    $this->db->query($sql);
	    	if($data['state'])//如果新的审核通过，不管是否换project,当前的project +1
	    	{
	    		$this->updateBusinessCount($data['project_id'],1);
	    	}		    
		    $data['id'] = $id;
		    return $data;
	    }
    }
    /*
    public function update_cost($id,$action)
    {
    	if($id)
    	{
    		$data = $this->detail($id);
    		if($data['cost_count']<=0)
    		{
	    		$action = abs($data['cost_count']);
    		}
    		$sql = "update " .DB_PREFIX. "bill SET cost_count=cost_count+" . $action . " WHERE id=" . $id;
	    	$this->db->query($sql);
	    	$data['cost_count']=$data['cost_count']+$action;	
	    	return $data;
    	}
	    return false;
    }*/
    
    public function audit($ids,$state)
    {
	   // $sql = "UPDATE " .DB_PREFIX. "bill SET state=" . $state . " WHERE id IN(" . $ids . ")";
	   // $this->db->query($sql);    
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "bill WHERE id IN(" . $ids . ")";
	    $q = $this->db->query($sql);
	    if($state == 1)
	    {
		    include_once(CUR_CONF_PATH . 'lib/auditor.class.php');
			$this->auditor = new auditor();
	    }
	    while($row = $this->db->fetch_array($q))
	    {
	    	$conn = '';
	    	if($row['auditor_id'] && $state == 1)
	    	{
		    	$auditor = $this->auditor->detail($row['auditor_id']);
		    	$auditor_record = $auditor['info'];    	
		    	$this->auditor->delete_record($row['id']);//先删除，再增加
		    	foreach($auditor_record as $k => $v)
		    	{
			    	$auditor_record[$k]['auditor_id'] = $row['user_id'];//被审核人id
			    	$auditor_record[$k]['bill_id'] = $row['id'];
			    	$this->auditor->add_record($auditor_record[$k]);
		    	}
		    	
	    		$conn = ',locked=1 ';//报销单，审核通过，并且有审核人之后，提交，锁住当前审核人，不准进行任何操作
		    	$this->auditor->locked_auditor($row['id']);
	    	}
	    	$sql = "UPDATE " .DB_PREFIX. "bill SET state=" . $state . $conn . " WHERE id =" . $row['id'];
	    	$this->db->query($sql); 
		    if($state == 1)//审核通过 +1
		    {
			    $this->updateBusinessCount($row['project_id'],1);
		    }
		    if($state == 2)//打回 -1
		    {
			   $this->updateBusinessCount($row['project_id'],-1);
		    }
		}    
	    return array('id' => $ids,'status' => $state); 
    }
    
    
    public function updateBusinessCount($project_id,$action)
    {
    	if($project_id)
    	{
    		$con = '';
    		$sql = "SELECT * FROM " .DB_PREFIX. "project WHERE id=" . $project_id;
    		$f = $this->db->query_first($sql);
    		if($f['business_count'] < 0)
    		{
	    		$con = '0';
    		}
    		else
    		{
	    		$con = "business_count+" . $action;
    		}
		    $sql = "UPDATE " . DB_PREFIX . "project SET business_count=" . $con . " WHERE id=" . $project_id;
		    $this->db->query($sql);
		    return true;
    	}
    	return false;
    }
    
    public function count($condition='')
    {
	    $sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "bill WHERE 1 " . $condition;
	    $f = $this->db->query_first($sql);
	    return $f; 
    }
    
    public function delete($ids)
    {
    	if($ids)
    	{
    		$sql = "SELECT project_id FROM " .DB_PREFIX. "bill WHERE id IN(" . $ids . ")";
    		$q = $this->db->query($sql);
    		$project = array();
    		while($row = $this->db->fetch_array($q))
    		{
	    		$project[] = $row['project_id'];
    		}
			$sql = "DELETE FROM " .DB_PREFIX. "bill WHERE id IN(" . $ids . ")";
		    $this->db->query($sql);    		
    		if($project)
    		{
	    		foreach($project as $key => $value)
	    		{
		    		$this->updateBusinessCount($value,-1);
	    		}
    		}
		    return array('id' => $ids); 
    	}  
    }
    
    public function detail($id)
    {
	    if($id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "bill WHERE id=" . $id;
	    }
	    else
	    {
		      $sql = "SELECT * FROM " .DB_PREFIX. "bill WHERE 1";
	    }
		$f = $this->db->query_first($sql);
		$f['cost_capital'] = hg_cny($f['cost']);
		return $f;
    }
    
    public function checkLocked($bill_id)
    {
	    if($bill_id)
	    {	    
		    $f = $this->detail($bill_id);
		    return $f['locked'] ? true : false;
	    }
	    else
	    {
		    return false;
	    }
    }
    
    public function checkContent($bill_id)
    {
	    if($bill_id)
	    {
		    $sql = "SELECT * FROM " .DB_PREFIX. "bill WHERE id=" . $bill_id;
		    $f = $this->db->query_first($sql);
		    if($f)
		    {
			    $check_params = array(
			    	'user_id',
			    	'project_id',
			    	'auditor_id',
			    	'business_time',
			    	'back_time',
			    	'cost',
			    	'baoxiao_time'
			    );
			    foreach($f as $k =>$v)
			    {
				    if(in_array($k,$check_params) && empty($v))
				    {
					    return strtoupper($k) . ' IS NULL';
				    }
			    }
		    }
	    }
	    return false;
    }
}
?>