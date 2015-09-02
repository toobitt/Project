<?php 
/***************************************************************************

* $Id: member_collect.class.php 15421 2012-12-12 09:28:06Z repheal $

***************************************************************************/
class memberCollect extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition, $offset, $count)
	{
		$data_limit = " LIMIT " . $offset . " , " . $count;

		$sql = "SELECT * FROM " . DB_PREFIX . "member_collect ";
		$sql .= " WHERE 1 " . $condition . " ORDER BY id DESC " . $data_limit;
		$q = $this->db->query($sql);

		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$info[$row['id']] = $row;
		}
		return $info;
	}
	
	public function detail($id)
	{
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN (' . $id .')';
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_collect " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			return $row;
		}

		return false;
	}
	
	public function create($info,$user)
	{
		$data = array(
			'member_id' => $info['member_id'],
			'appid' => $user['appid'],
			'appname' => $user['display_name'],
			'appuniqueid' => $info['appuniqueid'],
			'user_id' => $user['user_id'],
			'user_name' => $user['user_name'],
			'content_id' => $info['content_id'],
			'title' => $info['title'],
			'brief' => $info['brief'],
			'imgurl' => $info['imgurl'],
			'url' => $info['url'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
		);

		$sql = "INSERT INTO " . DB_PREFIX . "member_collect SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}

		return false;
	}
	
	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "member_collect WHERE id IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function getMemberCollectByMemberId($member_id, $condition, $offset, $count)
	{
		$offset = $offset ? $offset : 0;
		$count = $count ? $count : 20;
		$limit = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT * FROM " . DB_PREFIX . "member_collect ";
		$sql .= " WHERE member_id = " . $member_id;
		$sql .= $condition . " ORDER BY id DESC " . $limit;
		$q = $this->db->query($sql);
		
		$member_collect = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$member_collect[$row['id']] = $row;
		}
		
		if (!empty($member_collect))
		{
			return $member_collect;
		}
		
		return false;
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_collect WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}

	public function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title like \'%'.urldecode($this->input['k']).'%\'';
		}
	
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}
}
?>