<?php 
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/member.class.php');
class ClassDingdoneUser extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->member = new member();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition, $orderby, $offset, $count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT u.* FROM '.DB_PREFIX.'user u 
				WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$res = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['avatar'] = unserialize($row['avatar']) ? unserialize($row['avatar']) : '' ;
			$row['format_create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['status_name'] = $this->settings['account_status'][$row['status']];
			$res[] = $row; 
		}		
		return $res;
	}
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'user u WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT u.*, ub.brief FROM '.DB_PREFIX.'user u
				LEFT JOIN '.DB_PREFIX.'user_brief ub ON u.id = ub.id
				WHERE u.id = '.$id;
		$ret = $this->db->query_first($sql);
		if (!$ret)
		{
			return false;
		}
		$ret['avatar'] = unserialize($ret['avatar']) ? unserialize($ret['avatar']) : '';
		return $ret;
	}
	
	/**
	 * 
	 * @Description 审核操作
	 * @author Kin
	 * @date 2013-6-19 下午05:25:47
	 */
	public function audit($ids, $status)
	{
		$sql = 'UPDATE '.DB_PREFIX.'user SET status = '.$status.' WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'status'=>$status,
		);
		return $arr;
	}
	
	public function create($data, $role, $org, $brief='', $file='')
	{
		$accountInfor = $this->auth_register($data['account'], $data['password'], $role, $org, $file);
		if (!$accountInfor)
		{
			return false;
		}
		$data['account_id'] = $accountInfor['id'];
		$data['avatar'] = stripslashes($accountInfor['avatar']);
		$data['account'] = $accountInfor['user_name'];
		unset($data['password']);  //不存储密码
		$sql = 'INSERT INTO '.DB_PREFIX.'user SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql, ',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = 'UPDATE '.DB_PREFIX.'user SET order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($sql);
		//插入描述
		if ($brief)
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'user_brief (id, brief) VALUES ('.$id.',"'.addslashes($brief).'")';
			$this->db->query($sql);
			$data['brief'] = $brief;
		}
		$data['avatar'] = unserialize($data['avatar']) ? unserialize($data['avatar']) : ''; 
		return $data;
	}
	
	public function update($id, $data, $brief = '', $file = '')
	{
		if (!$id)
		{
			return false;	
		}
		//查询之前数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'user WHERE id = '.$id;
		$preData = $this->db->query_first($sql);
		if (!$preData)
		{
			return false;
		}
		if ($data['account'] != $preData['account'] || $data['password'] || $file)
		{
			$role = implode(',', $this->settings['default_role']);
			$accountInfor = $this->auth_update($preData['account_id'], $data['account'], $data['password'], $role, $preData['org_id'], $file);
			if (!$accountInfor)
			{
				return false;	
			}
			if ($file)
			{
				$data['avatar'] = stripslashes($accountInfor['avatar']);
			}
			$data['account'] = $accountInfor['user_name'];
		}
		if (!empty($data) && is_array($data))
		{
			unset($data['password']);
			$sql = 'UPDATE '.DB_PREFIX.'user SET ';
			foreach ($data as $key=>$val)
			{
				$sql .= $key .'="'.addslashes($val).'",'; 
			}
			$sql = rtrim($sql, ',');
			$sql .= ' WHERE id = '.$id;
			$this->db->query($sql); 
		}
		else 
		{
			return false;	
		}
		//插入描述
		$sql = 'REPLACE INTO '.DB_PREFIX.'user_brief (id, brief) VALUES ('.$id.',"'.addslashes($brief).'")';
		$this->db->query($sql);
		$data['brief'] = $brief;
		$data['id'] = $id;
		return $data;
	}
	
	public function delete($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'user WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$accountIds = array();
		while ($row = $this->db->fetch_array($query))
		{
			$accountIds[] = $row['account_id'];
		}
		if (!empty($accountIds))
		{
			$account_ids = implode(',', $accountIds);
			$accountInfor = $this->auth_delete($account_ids);
			if (!$accountInfor)
			{
				return false;
			}
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'user WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'user_brief WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
		
	}
	/**
	 * 
	 * @Description 系统用户注册
	 * @author Kin
	 * @date 2013-7-2 下午04:29:54
	 */
	private function auth_register($name, $password, $role, $org, $avatar='')
	{
		if (!$name || !$password || !$role || !$org)
		{
			return false;
		}
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir'].'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','register');
		$curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$curl->addRequestData('user_name',$name);
		$curl->addRequestData('password',$password);
		$curl->addRequestData('admin_role_id',$role);
		$curl->addRequestData('father_org_id',$org);
		if (defined('DINGDONE_DOMAIN') && DINGDONE_DOMAIN)
		{
			$curl->addRequestData('domain',DINGDONE_DOMAIN);
		}
		if ($avatar)
		{
			$curl->addFile($avatar);		
		}
		$ret = $curl->request('admin_update.php');
		$ret = $ret[0];
		return $ret;
	}
	
	/**
	 * 
	 * @Description  系统用户更新
	 * @author Kin
	 * @date 2013-7-3 上午09:26:46
	 */
	private function auth_update($id, $name, $password, $role, $org, $avatar='')
	{
		if (!$id || !$name  || !$role || !$org)
		{
			return false;
		}
		
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir'].'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','register');
		$curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$curl->addRequestData('id',$id);
		$curl->addRequestData('user_name',$name);
		$curl->addRequestData('password',$password);
		$curl->addRequestData('admin_role_id',$role);
		$curl->addRequestData('father_org_id',$org);
		if ($avatar)
		{
			$curl->addFile($avatar);		
		}
		$ret = $curl->request('admin_update.php');		
		$ret = $ret[0];
		return $ret;
	}
	/**
	 * 
	 * @Description 系统用户删除
	 * @author Kin
	 * @date 2013-7-3 上午09:31:21
	 */
	private function auth_delete($id)
	{
		if (!$id)
		{
			return false;
		}
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir'].'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','delete');
		$curl->addRequestData('id',$id);
		$ret = $curl->request('admin_update.php');		
		$ret = $ret[0];
		return $ret;
	}
	
}
?>