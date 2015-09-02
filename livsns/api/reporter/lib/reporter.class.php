<?php
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class reporter extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$offset,$count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT  * FROM '.DB_PREFIX.'reporter WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$temp['id']			= $row['id'];
			$temp['account'] 	= $row['account'];
			$temp['name'] 		= $row['name'];
			$temp['status'] 	= $row['status'];
			$temp['avatar'] 	= $row['avatar'] ? unserialize($row['avatar']) : array();
			$temp['sex'] 		= $row['sex'];
			$temp['tel']		= $row['tel'];
			$temp['email']     	= $row['email'];
			$temp['update_time']= date('Y-m-d H:i:s',$row['update_time']);
			$temp['user_name']	= $row['user_name'];
			$k[$row['id']]   	= $temp;
		}
		return $k;
	}
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'reporter  WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function detail($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'reporter WHERE id ='.$id;
		$ret = $this->db->query_first($sql);
		$ret['avatar'] = unserialize($ret['avatar']);
		return $ret;	
	}
	
	public function delete($id)
	{	
		//查询帐户id
		$sql = 'SELECT account_id FROM '.DB_PREFIX.'reporter WHERE id IN ('.$id.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[] = $row['account_id'];
		}
		$k = array_filter($k);
		if (!empty($k))
		{
			$ids = implode(',', $k);
			$this->deleteAccount($ids);
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'reporter WHERE id IN ('.$id.')';
		$this->db->query($sql);
		return $id;
	}
	
	public function create($data)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.'reporter SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if (!$id)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'reporter SET order_id= ' .$id. ' WHERE id  = '.$id;
		$this->db->query($sql);
		$data['id'] =$id;
		return $id;
	}
	
	public function update($data,$id)
	{
		if (!$id)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'reporter SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id;
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;
	}
	
	public function audit($ids,$status)
	{
		$sql = 'UPDATE '.DB_PREFIX.'reporter SET status = '.$status.' WHERE id IN ('. $ids .')';
		$ret = $this->db->query($sql);
		$arr = explode(',', $ids);
		$data = array(
			'id'=>$arr,
			'status'=>$status,
		);
		return $data;
	}
	
	public function getAuthRole()
	{
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir'].'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','show');
		$ret = $curl->request('admin_role.php');
		return $ret;
	}
	//创建帐户
	public function createAccount($data,$file='')
	{
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir'].'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		if ($file)
		{
			$curl->addFile($file);
		}	
		$curl->addRequestData('user_name',$data['account']);
		$curl->addRequestData('password',$data['password']);
		$curl->addRequestData('brief',$data['brief']);
		if (is_array($data['role_id']))
		{
			foreach ($data['role_id'] as $val)
			{
				$curl->addRequestData('admin_role_id[]',$val);
			}
		}else {
			$curl->addRequestData('admin_role_id[]',$data['role_id']);
		}
		
		$curl->addRequestData('cardid',$data['card_id']);
		$curl->addRequestData('domain',$data['domain']);
		$ret = $curl->request('admin_update.php');
		return $ret[0];
	}
	
	//更新帐户
	public function updateAccount($data,$file='',$id)
	{
		//查询帐户id
		$sql = 'SELECT account_id FROM '.DB_PREFIX.'reporter WHERE id='.$id;
		$ret = $this->db->query_first($sql);		
		$account_id = $ret['account_id'];
		if (!$account_id)
		{
			return false;
		}
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir'].'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','update');
		if ($file)
		{
			$curl->addFile($file);
		}
		$curl->addRequestData('id',$account_id);	
		$curl->addRequestData('user_name',$data['account']);
		$curl->addRequestData('password',$data['password']);
		$curl->addRequestData('brief',$data['brief']);
		if (is_array($data['role_id']))
		{
			foreach ($data['role_id'] as $key=>$val)
			{
				$curl->addRequestData('admin_role_id['.$key.']',$val);
			}
		}else {
			$curl->addRequestData('admin_role_id[]',$data['role_id']);
		}
		$curl->addRequestData('cardid',$data['card_id']);
		$curl->addRequestData('domain',$data['domain']);
		$ret = $curl->request('admin_update.php');	
		return $ret[0];
	}
	
	//删除帐户信息
	public function deleteAccount($ids)
	{
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir'].'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','delete');	
		$curl->addRequestData('id',$ids);	
		$ret = $curl->request('admin_update.php');		
		return $ret[0];
	}
}