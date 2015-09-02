<?php 
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/member.class.php');
require_once(ROOT_PATH . 'lib/class/auth.class.php');
class ClassSeekhelpAccount extends InitFrm
{
	private $auth;
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->member = new member();
		$this->auth = new auth();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition, $orderby, $offset, $count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT a.*,ab.brief,s.name AS sort_name FROM '.DB_PREFIX.'account a
				LEFT JOIN '.DB_PREFIX.'account_brief ab ON a.id=ab.id 
				LEFT JOIN '.DB_PREFIX.'account_sort s ON a.sort_id = s.id 
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
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'account a WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT a.*, ab.brief FROM '.DB_PREFIX.'account a 
				LEFT JOIN '.DB_PREFIX.'account_brief ab ON a.id = ab.id
				WHERE a.id = '.$id;
		$ret = $this->db->query_first($sql);
		if (!$ret)
		{
			return false;
		}
		$condition=' AND account_id = '.$ret['id'];
		$count=$this->help_count($condition);
		$ret['help_count']=$count['total'];
		$ret['avatar'] = unserialize($ret['avatar']) ? unserialize($ret['avatar']) : '';
		return $ret;
	}
	public function show_reply($condition,$offset,$count)
	{
		if(!$condition)
		{
			return false;
		}
		$orderby = ' ORDER BY r.create_time DESC';
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT a.name,a.avatar,sh.id,sh.title,r.content as reply_content FROM '.DB_PREFIX.'account a 
		LEFT JOIN '.DB_PREFIX.'seekhelp sh ON a.id = sh.account_id 
		LEFT JOIN '.DB_PREFIX.'reply r ON sh.id = r.cid WHERE 1 AND sh.status = 1 AND sh.is_reply = 1'.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$ret_r=array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['avatar'] = unserialize($row['avatar']) ? unserialize($row['avatar']) : '';
			$ret_r[]=$row;
		}
		return $ret_r;
	}
	
	private function help_count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'seekhelp WHERE status = 1 AND is_reply = 1 '.$condition;
		$ret = $this->db->query_first($sql);
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
		$sql = 'UPDATE '.DB_PREFIX.'account SET status = '.$status.' WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'status'=>$status,
		);
		return $arr;
	}
	
	public function create($data, $brief='', $file='')
	{
		$accountInfor = $this->auth->auth_register($data['account'], $data['password'], SEEKHELP_ROLE, SEEKHELP_ORG, $file,$data['cardid']);
		if (!$accountInfor)
		{
			return FALSE;
		}
		$data['account_id'] = $accountInfor['id'];
		$data['avatar'] = stripslashes($accountInfor['avatar']);
		$data['account'] = $accountInfor['user_name'];
		unset($data['password']);  //不存储密码
		$sql = 'INSERT INTO '.DB_PREFIX.'account SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql, ',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = 'UPDATE '.DB_PREFIX.'account SET order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($sql);
		//插入描述
		if ($brief)
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'account_brief (id, brief) VALUES ('.$id.',"'.addslashes($brief).'")';
			$this->db->query($sql);
			$data['brief'] = $brief;
		}
		$data['avatar'] = unserialize($data['avatar']) ? unserialize($data['avatar']) : ''; 
		return $data;
	}
	
	public function update($id, $data, $brief='', $file='')
	{
		if (!$id)
		{
			return false;	
		}
		//查询之前数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'account WHERE id = '.$id;
		$preData = $this->db->query_first($sql);
		if (!$preData)
		{
			return false;
		}
		if ($data['account']!=$preData['account'] || $data['password'] || $file || $data['cardid'])
		{
			$accountInfor = $this->auth->auth_update($preData['account_id'], $data['account'], $data['password'], SEEKHELP_ROLE, SEEKHELP_ORG, $file,$data['cardid']);
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
			$sql = 'UPDATE '.DB_PREFIX.'account SET ';
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
		$sql = 'REPLACE INTO '.DB_PREFIX.'account_brief (id, brief) VALUES ('.$id.',"'.addslashes($brief).'")';
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'account WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$accountIds = array();
		while ($row = $this->db->fetch_array($query))
		{
			$accountIds[] = $row['account_id'];
		}
		if (!empty($accountIds))
		{
			$account_ids = implode(',', $accountIds);
			$accountInfor = $this->auth->auth_delete($account_ids);
			if (!$accountInfor)
			{
				return false;
			}
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'account WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'account_brief WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
		
	}
	
}
?>