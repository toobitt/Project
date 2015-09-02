<?php 
/***************************************************************************

* $Id: user.class.php 12811 2012-10-22 09:10:44Z lijiaying $

***************************************************************************/
class user extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		
		include_once ROOT_PATH . UC_PATH . 'uc_client/client.php';
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show1($condition, $offset, $count, $field, $order)
	{
		$field = $field ? $field : 'uid';
		$order = $order ? $order : 'DESC';
		$offset = $offset ? $offset : 0;
		$count = $count ? $count : 20;
		
		$orderby = " ORDER BY " . $field . " " . $order;
		$limit = " LIMIT " . $offset . " , " . $count;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "members ";
		$sql .= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		$info =array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['regdate'] = date('Y-m-d H:i:s', $row['regdate']);
			$info[$row['uid']] = $row;
		}
		
		if (!empty($info))
		{
			return $info;
		}
		return false;
	}

	public function uc_get_list($page, $ppp, $totalnum, $sqladd = '')
	{
		$ret = uc_get_list($page, $ppp, $totalnum, $sqladd);
		return $ret;
	}
	
	public function uc_get_total_num($sqladd='')
	{
		$ret = uc_get_total_num($sqladd);
		return $ret;
	}
	
	public function uc_get_user($username)
	{
		$ret = uc_get_user($username);
		return $ret;
	}

	public function uc_user_register($username, $password, $email)
	{
		$ret = uc_user_register($username, $password, $email);
		return $ret;
	}

	public function uc_user_edit($username, $oldpw, $newpw, $email)
	{
		$ret = uc_user_edit($username, $oldpw, $newpw, $email);
		return $ret;
	}

	public function uc_user_delete($uid)
	{
		$uid = explode(',', $uid);
		$ret = uc_user_delete($uid);
		return $ret;
	}
	
	public function uc_user_login($username, $password)
	{
		$ret = uc_user_login($username, $password);
		return $ret;
	}
}
?>