<?php 
/***************************************************************************

* $Id: member.class.php 13002 2012-10-25 07:24:30Z lijiaying $

***************************************************************************/
class talk extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition = '',$user_id, $offset, $count)
	{
		if(empty($user_id))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "talk WHERE member_id=" . $user_id;
		$f = $this->db->query_first($sql);
		$sql = "SELECT * FROM " . DB_PREFIX . "talk_history WHERE member_id=" . $user_id . $condition . " ORDER BY create_time DESC LIMIT " . $offset . "," . $count;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{	
			if($f['tid'] == $row['id'])
			{
				$row['now'] = 1;
			}
			$info[$row['id']] = $row;
		}
		return $info;
	}
	
	public function create($user_id)
	{
		$info = array(
			'member_id' => $user_id,
			'content' => urldecode($this->input['content']),
			'source' => $this->input['source'],
			'create_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		$sql = "INSERT INTO " . DB_PREFIX . "talk_history SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$sql .= $space . $key . "='" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);
		$info['tid'] = $this->db->insert_id();
		$sql = "SELECT id FROM " . DB_PREFIX . "talk WHERE member_id=" . $info['member_id'];
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			$sql = "INSERT INTO " . DB_PREFIX . "talk SET ";
			$space = "";
			foreach($info as $key => $value)
			{
				$sql .= $space . $key . "='" . $value . "'";
				$space = ",";
			}
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
		}
		else
		{
			$sql = "UPDATE " . DB_PREFIX . "talk SET ";
			$space = "";
			foreach($info as $key => $value)
			{
				$sql .= $space . $key . "='" . $value . "'";
				$space = ",";
			}
			$sql .= " WHERE id=" . $f['id'];
			$this->db->query($sql);
			$info['id'] = $f['id'];
		}
		
		return $info;	
		
	}
	
	public function update()
	{
		
	}
	
	public function delete($tid,$user_id)
	{
		if(empty($tid))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "talk_history SET state=0 WHERE id=" . $tid;
		$this->db->query($sql);
		$sql = "SELECT * FROM " . DB_PREFIX . "talk_history WHERE state=1 AND member_id=" . $user_id . " ORDER BY create_time DESC LIMIT 0,1";
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			$f = array(
				'id' => 0,
				'content' => '',
				'source' => '',
				'create_time' => TIMENOW,
				'ip' => hg_getip(),
			);
		}
		$sql = "UPDATE " . DB_PREFIX . "talk SET tid=" . $f['id'] . ",content='" . $f['content'] . "',source='" . $f['source'] . "',create_time='" . $f['create_time'] . "',ip='" . $f['ip'] . "' WHERE member_id=" . $user_id;
		$this->db->query($sql);
		return $tid;
	}
	
	public function delete_history($tid)
	{
		if(empty($tid))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "talk_history SET state=0 WHERE id=" . $tid;
		$this->db->query($sql);
		return $tid;
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "talk_history WHERE 1 AND state=1 " . $condition;
		$f = $this->db->query_first($sql);
		return $f;
	}
	
	public function detail($user_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "talk WHERE member_id=" . $user_id;
		$f = $this->db->query_first($sql);
		return $f;
	}
}

?>