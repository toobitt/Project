<?php
class churu extends initFrm
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        parent::__construct();
    }
    public function show($type='waichu',$condition)
    {
	    $sql = 'SELECT * FROM '.DB_PREFIX."churu WHERE type='".$type."' ".$condition;
		$array = $this->db->query($sql);
		if($array)
		{
			include_once(ROOT_PATH . 'lib/class/auth.class.php');//根据uid获取员工其它信息。
			$auth = new auth();
			while($re = mysql_fetch_assoc($array))
			{	
				$arr = $auth->getMemberById($re['uid']);
				$re['user_name'] = $arr[0]['user_name'];
				$re['org_name'] = $arr[0]['org_name'];
				$res[] = $re; 
			};
			//print_r($res);die;
		}
		return $res;
    }
    public function create($data)
    {	
    	if($data)
    	{
    		$sql = 'INSERT INTO '.DB_PREFIX.'churu SET ';
    		$p ='';
	    	foreach($data as $k => $v)
	    	{
		    	$sql.= $p.$k."='".$v."'";
		    	$p = ',';
	    	}
	    	$re = $this->db->query($sql);
	    	$data['id'] = $this->db->insert_id();
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
			$arr = $auth->getMemberById($data['uid']);
			$data['user_name'] = $arr[0]['user_name'];
			$data['org_name'] = $arr[0]['org_name'];
	    	//print_r($data);die;
	    	return $data;
    	}else{
	    	return false;
    	}
	    
    }
    public function update($id,$time)
    {
	    if($id)
	    {
		    $sql = 'UPDATE '.DB_PREFIX.'churu SET end_time='.$time.' WHERE ID='.$id;
		    $this->db->query($sql);
		    $data['id'] = $id;
		    $data['end_time'] = $time;
		    return $data;
	    }else{
		    return false;
	    }
    }
    public function delete($id)
    {
	    if($id)
	    {
		    $sql = 'DELETE FROM '.DB_PREFIX.'churu WHERE ID='.$id;
		    $this->db->query($sql);
		    $data['id'] = $id;
		    return $data;
	    }else{
		    return false;
	    }
    }
    public function detail()
    {
	    
    }
    public function count()
    {
	    
    }
}

?>