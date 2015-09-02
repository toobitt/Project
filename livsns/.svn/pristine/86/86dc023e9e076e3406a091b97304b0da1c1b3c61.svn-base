<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: getblog.php 3873 2011-05-05 08:38:24Z repheal $
***************************************************************************/

class status extends InitFrm
{
	private $member;
	
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 获取微博数据
	 * @param Int $offset
	 * @param Int $count
	 * @param Array $data
	 */
	public function show($offset, $count, $data)
	{
		if ($count != -1)
		{
			$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		}
		$sql = 'SELECT s.*, se.transmit_count, se.reply_count, se.comment_count 
		FROM ' . DB_PREFIX . 'status s LEFT JOIN ' . DB_PREFIX . 'status_extra se 
		ON s.id = se.status_id WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data);
		$sql = $sql . $condition;
		if ($data_limit) $sql .= $data_limit;
		return $this->dispose($sql);
	}
	
	/**
	 * 获取微博总数
	 * @param Array $data
	 */
	public function count($data)
	{
		$condition = $this->get_condition($data);
		$sql = 'SELECT COUNT(s.id) AS total FROM ' . DB_PREFIX . 'status s WHERE 1';
		$sql .= $condition;
		return $this->db->query_first($sql);				
	}
	
	/**
	 * 获取单个微博信息
	 * @param Int $id
	 */
	public function detail($id)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'status WHERE status = 0 AND id = ' . $id;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 检测发布的频率
	 * @param Int $uid 用户id
	 * @param Int $time 间隔时间
	 * @param Int $num 限制次数
	 * @param boolean $flag 
	 * true转发 false发布
	 */
	public function check_num($uid, $time, $num, $flag = false)
	{
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'status 
		WHERE member_id = ' . $uid . ' AND (' . TIMENOW . ' - create_at) < ' . $time;
		if ($flag) $sql .= ' AND reply_status_id != 0';
		$info = $this->db->query_first($sql);
		if ($info['total'] >= $num) return true;
		return false;
	}
	
	/**
	 * 验证发布的信息是否重复
	 * @param Int $uid
	 * @param Array $data
	 */
	public function verify_repeat($uid, $text)
	{
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'status 
		WHERE member_id = ' . $uid . ' AND text = "' . $text . '" AND status = 0';
		$info = $this->db->query_first($sql);
		return $info['total'];
	}
	
	/**
	 * 创建数据
	 * @param Array $data
	 */
	public function create($table, $data, $pk = 'id')
	{
		$fields = array();
		foreach($data as $k=>$v)
		{
			if (is_string($v))
			{
				$fields[] = $k . "='" . $v . "'";
			}
			elseif (is_int($v))
			{
				$fields[] = $k . '=' . $v;
			}
		}
		$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' SET ' . implode(',', $fields);
		$this->db->query($sql);
		$data[$pk] = $this->db->insert_id();
		return $data;
	}
	
	/**
	 * 更新操作
	 * @param String $table
	 * @param Array $data
	 * @param Array $idsArr
	 * @param Boolean $flag
	 */
	public function update($table, $data, $idsArr, $flag = false)
	{
		$fields = array();
		foreach($data as $k=>$v)
		{
			if ($flag)
			{
				$v = $v > 0 ? '+' . $v : $v;
				$fields[] = $k . '=' . $k . $v;
			}
			else
			{
				if (is_string($v))
				{
					$fields[] = $k . "='" . $v . "'";
				}
				elseif (is_int($v))
				{
					$fields[] = $k . '=' . $v;
				}
			}
		}
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . implode(',', $fields) . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val))
				{
					$sql .= ' AND ' . $key . ' in (' . $val . ')';
				}
			}
		}
		return $this->db->query($sql);
	}
	
	/**
	 * 验证微博中是否含有话题,用户名
	 * @param Int $status_id 微博ID
	 * @param String $content 微博内容
	 */
	public function check_topic($status_id, $content)
	{
		//这里牵扯到用户名命名规则问题
		$pattern = "/@([\x{4e00}-\x{9fa5}0-9A-Za-z_-]+)[\s:：,，.。\'‘’\"“”、！!]/iu";
		if (preg_match_all($pattern, $content, $username))
		{
			foreach ($username[1] as $value)
			{
				$name[] = $value;
			}
			$screen_name = implode(',', $name);
			$userInfo = $this->getMemberInfo('getMemberByName', $screen_name);
			//$userInfo = $this->member->getMemberByName($screen_name);
			if ($userInfo)
			{
				$userInfo = explode(',', $userInfo);
				$sql = 'INSERT IGNORE INTO ' . DB_PREFIX . 'status_member (status_id, member_id) VALUES ';
				foreach ($userInfo as $m_id)
				{
					$sql .= '(' . $status_id . ', ' . $m_id . '),';
				}
				$sql = rtrim($sql, ',');
				$this->db->query($sql);
			}
		}
		//这里牵扯到话题规则问题
		$pattern = "/#([\x{4e00}-\x{9fa5}0-9A-Za-z_-]+)[\s#]/iu";
		if (preg_match_all($pattern, $content, $topic))
		{
			foreach ($topic[1] as $key => $value)
			{
				$topics[] = "'" . strtoupper($value) . "'";
				$title[] = $value;
			}
			$topics = implode(',', $topics);
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'topic WHERE title IN (' . $topics . ')';	
			$query = $this->db->query($sql);
			while ($array = $this->db->fetch_array($query))
			{
				$topicid[] = $array['id'];
				$data[] = $array;
				$topictitle[] = $array['title'];
			}		
			if (!count($topictitle))
			{
				$newtitle = $title;		
			}
			else
			{
				if (count($topictitle) <= count($title))
				{
					$newtitle = array_diff($title, $topictitle);
				}
				else
				{
					$newtitle = array_diff($topictitle, $title);
				}
			}
			if ($newtitle)
			{
				$topicids = array();
				foreach ($newtitle as $value)
				{
					$value = str_replace('#', '', trim($value));
					$sql = 'INSERT INTO ' . DB_PREFIX . 'topic (title, relate_count, status) VALUES ("' . $value . '", 1, 0)';
					$this->db->query($sql);
					$topicids[] = $this->db->insert_id();
				}
				$this->updateStatusTopic($status_id, $topicids);
			}
			else
			{
				foreach ($data as $value)
				{
					$relate_count = $value['relate_count'] + 1;
					$sql ='UPDATE ' . DB_PREFIX . 'topic SET relate_count = ' . $relate_count . ' WHERE id = ' . $value['id'];
					$this->db->query($sql);
				}
				$this->updateStatusTopic($status_id, $topicid);
			}
		}
	}
	
	/**
	 * 更新status_topic表中数据
	 * @param Int $status_id 微博ID
	 * @param Array $topicid 话题ID
	 */
	public function updateStatusTopic($status_id, $topicid)
	{
		$sql = 'INSERT IGNORE INTO ' . DB_PREFIX . 'status_topic (topic_id, status_id) VALUES ';
		foreach ($topicid as $value)
		{
			$sql .= '(' . $value . ',' . $status_id . '),';
		}
		$sql = rtrim($sql, ',');
		$this->db->query($sql);
	}
	
	/**
	 * 物理删除微博数据
	 * @param String $table
	 * @param Array $data
	 */
	public function delete($table, $data)
	{
		$condition = '';
		foreach ($data as $k => $v)
		{
			if (is_int($v))
			{
				$condition .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$condition .= ' AND ' . $k . ' IN (' . $v . ')';
			}
		}
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		if ($condition) $sql .= $condition;
		return $this->db->query($sql);
	}
	
	/**
	 * 处理关联数据
	 * @param String $sql
	 */
	private function dispose($sql)
	{
		if (empty($sql)) return false;
		$q = $this->db->query($sql);
		$info = array();
		$member_id = array();
		$trans = array();
		$status_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
			$member_id[$row['member_id']] = $row['member_id'];
			if ($row['pic'] || $row['video']) $status_id[$row['id']] = $row['id'];
			if ($row['reply_status_id'])
			{
				$trans[$row['reply_status_id']] = $row['reply_status_id'];	
			}
		}
		if (!empty($member_id))
		{
			$member_id = implode(',', $member_id);
			$member_info = $this->getMemberInfo('getMemberById', $member_id, 1);
		}
		if ($trans) $trans_status = $this->show(0, -1, array('status_id' => implode(',', $trans)));
		if ($status_id) $media_info = $this->getMedia(implode(',', $status_id));
		$status = array();
		foreach ($info as $value)
		{
			if ($member_info)
			{
				$value['avatar'] = $member_info[$value['member_id']]['avatar'];
				$value['user_name'] = $member_info[$value['member_id']]['user_name'];
			}
			if ($media_info) $value['media'] = $media_info[$value['id']];
			if ($trans_status) $value['retweeted_status'] = $trans_status[$value['reply_status_id']];
			$status[$value['id']] = $value;
		}	
		return $status;
	}

	/**
	 * 获取用户上传的图片和视频
	 * @param Int|string|Array $id
	 */
	public function getMedia($id)
	{
		if (is_array($id))
		{
			$ids = implode(',', $id);
		}
		else 
		{
			$ids = $id;		
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'material WHERE status_id IN (' . $ids . ') AND isdel = 1';
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[$row['status_id']]['img'][] = $row;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'video WHERE status_id IN (' . $ids . ') AND isdel = 1';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$info[$row['status_id']]['video'][] = $row;
		}		
		return $info;
	}
	
	/**
	 * 获取当前用户的某个未使用的图片素材
	 * @param Int $id
	 * @param Int $uid
	 */
	public function getMaterial($id, $uid)
	{
		$sql = 'SELECT material_id FROM ' . DB_PREFIX . 'material WHERE id = ' . $id . ' 
		AND status_id = 0 AND user_id = ' . $uid;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 获取当前用户未使用的图片素材
	 * @param Int $uid
	 */
	public function getNotUsedMaterial($uid)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'material WHERE user_id = ' . $uid . ' AND status_id = 0';
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		return $info;
	}
	
	/**
	 * 获取当前用户的某个未使用的视频
	 * @param Int $id
	 * @param Int $uid
	 */
	public function getVideo($id, $uid)
	{
		$sql = 'SELECT video_id, type FROM ' . DB_PREFIX . 'video WHERE id = ' . $id . ' 
		AND status_id = 0 AND user_id = ' . $uid;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 获取当前用户未使用的视频
	 * @param Int $uid
	 */
	public function getNotUsedVideo($uid)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'video WHERE user_id = ' . $uid . ' AND status_id = 0';
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		return $info;
	}
	
	/**
	 * 获取查询条件
	 * @param Array $data
	 */
	private function get_condition($data)
	{
		$condition = '';
		
		//查询的关键字
		if ($data['keywords'])
		{
			$condition .= " AND s.text LIKE '%" . $data['keywords'] . "%' ";
		}
		
		//获取原创微博
		if ($data['noreply'])
		{
			$condition .= " AND s.reply_status_id = 0";
		}
		
		//查询微博的状态
		switch($data['state'])
		{
			case 1:
				$condition .= " AND s.status = 0"; //正常
				break;
			case 2:
				$condition .= " AND s.status = 1"; //删除
				break;
			default:
				$condition .= " AND s.status = 0";
				break;
		}
		
		//根据微博ID获取数据
		if ($data['status_id'])
		{
			$condition .= " AND s.id IN (" . $data['status_id'] . ")";
		}
		
		//获取用户发布的微博信息
		if ($data['member_id'])
		{
			$condition .= " AND s.member_id IN (" . $data['member_id'] . ")";
		}
		
		//获取部门用户发布的微博信息
		if ($data['depart_id'] && !$data['member_id'])
		{
			$member_ids = $this->getMemberInfo('getMemberByOrg', $data['depart_id']);
			if (!$member_ids) $member_ids = 0;
			$condition .= " AND s.member_id IN (" . $member_ids . ")";
		}
		
		//获取提到我的微博信息
		if ($data['mentions_id'])
		{
			$status_ids = $this->mentionsMe($data['mentions_id']);
			if (!$status_ids) $status_ids = 0;
			$condition .= " AND s.id IN (" . $status_ids . ")";
		}
		
		//获取评论我的微博信息
		if ($data['commentMe_id'])
		{
			$commentMe = $this->commentMe($data['commentMe_id']);
			if (!$commentMe) $commentMe = 0;
			$condition .= " AND s.member_id IN (" . $commentMe . ")";
		}
		
		//排序
		$sort = ' ORDER BY ';
		if ($data['order'] && is_array($data['order']))
		{
			foreach ($data['order'] as $k => $v)
			{
				$sort .= $k . ' ' . $v . ', ';
			}
			$sort = rtrim($sort, ',');
		}
		else
		{
			$sort .= 's.id DESC';
		}
		
		$condition = $condition . $sort;
		return $condition;
	}
	
	/**
	 * 获取用户信息
	 * @param String $method
	 * @param Int|String $ids
	 * @param Int $return
	 */
	private function getMemberInfo($method, $ids, $return = 0)
	{
		$member = new Auth();
		$members = $member->$method($ids);
		$member_ids = array();
		$member_info = array();
		if ($members)
		{
			foreach ($members as $val)
			{
				$member_ids[] = $val['id'];
				$member_info[$val['id']] = $val;
			}
			$member_ids = implode(',', $member_ids);
		}
		if ($return)
		{
			$out = $member_info;
		}
		else
		{
			$out = $member_ids;
		}
		if (!$out) return false;
		return $out;
	}
	
	/**
	 * 获取提到我的微博ID
	 * @param Int $uid
	 */
	private function mentionsMe($uid)
	{
		$sql = 'SELECT status_id FROM ' . DB_PREFIX . 'status_member WHERE member_id = ' . $uid;
		$q = $this->db->query($sql);
		$status_ids = array();
		while ($rows = $this->db->fetch_array($q))
		{
			$status_ids[] = $rows['status_id'];
		}
		if ($status_ids) $status_ids = implode(',', $status_ids);
		return $status_ids;
	}
	
	/**
	 * 获取评论我的用户ID
	 * @param Int $uid
	 */
	private function commentMe($uid)
	{
		$sql = 'SELECT sc.member_id FROM ' . DB_PREFIX . 'status_comments sc 
		INNER JOIN ' . DB_PREFIX . 'status s ON sc.status_id = s.id WHERE sc.flag = 0 
		AND s.status = 0 AND s.member_id = ' . $uid;
		$q = $this->db->query($sql);
		$member_ids = array();
		while ($rows = $this->db->fetch_array($q))
		{
			$member_ids[$rows['member_id']] = $rows['member_id'];
		}
		if ($member_ids) $member_ids = implode(',', $member_ids);
		return $member_ids;
	}
}
?>