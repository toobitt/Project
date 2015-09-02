<?php
/***************************************************************************
* $Id: channels.class.php 8250 2012-07-23 07:34:59Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive');//模块标识
class interactive extends InitFrm
{
	private $mLive;
	
	public function __construct()
	{
		parent::__construct();
		
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$this->mLive = new curl($this->settings['App_live']['host'],$this->settings['App_live']['dir']);
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function get_interactive_info($condition, $offset, $count, $orderby, $start_time = '', $end_time = '')
	{
		if ($start_time && $end_time)
		{
			$condition .= " AND create_time >= " . $start_time . " AND create_time < " . $end_time;
		}
		
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		
		$sql = "SELECT *, i.member_id AS member_id, i.plat_id AS plat_id FROM " . DB_PREFIX . "interactive i ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "member m ON i.member_id = m.member_id ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;

		$q = $this->db->query($sql);
		
		$interactive = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$interactive[$row['id']] = $row;
		}
		
		return $interactive;
	}

	function count($condition, $start_time = '', $end_time = '')
	{
		if ($start_time && $end_time)
		{
			$condition .= " AND create_time >= " . $start_time . " AND create_time < " . $end_time;
		}
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "interactive WHERE 1" . $condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}

	function get_interactive_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "interactive ";
		$sql.= " WHERE id IN (" . $id . ")";

		$q = $this->db->query($sql);
		
		$interactive = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$interactive[$row['id']] = $row;
		}
		
		return $interactive;
	}
	
	function interactive_add($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "interactive SET ";
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
	
	function delete_by_channel_id($channel_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "interactive WHERE channel_id IN (" . $channel_id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "interactive WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	function is_delete($id)
	{
		$sql = "UPDATE " . DB_PREFIX . "interactive SET is_delete=1 WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	function type($id, $type, $recommend_time)
	{
		$sql = "UPDATE " . DB_PREFIX . "interactive SET type = " . $type . ", recommend_time = " . $recommend_time . " WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function audit($id, $audit, $status_time)
	{
		$sql = "UPDATE " . DB_PREFIX . "interactive SET status = " . $audit . ", status_time = " . $status_time . " WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_member_by_id($member_id, $plat_id = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "member WHERE member_id = '" . $member_id . "' ";
		if ($plat_id)
		{
			$sql .= " AND plat_id = " . $plat_id;
		}
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function member_add($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "member SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}
	
	/**
	 * 警告操作
	 * Enter description here ...
	 * @param unknown_type $member_id
	 * @param unknown_type $plat_id
	 * @param unknown_type $plat_member_id
	 */
	function get_warn_by_member_id($member_id, $plat_id = '', $plat_member_id = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "warn WHERE member_id IN (" . $member_id . ") AND plat_id = " . $plat_id . " AND plat_member_id = '" . $plat_member_id . "' ";
		$q = $this->db->query($sql);
		
		$return = array();
		
		while ($row = $this->db->fetch_array($q))
		{
			$return[$row['id']] = $row;
		}
		
		if (!empty($return))
		{
			return $return;
		}
		return false;
	}
	
	/**
	 * 警告数目
	 * Enter description here ...
	 * @param unknown_type $member_id
	 */
	function get_warn_count_by_member_id($member_id, $dates)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "warn WHERE member_id = '" . $member_id . "' AND dates = '" . $dates . "'";
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	/**
	 * 警告添加
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	function warn_add($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "warn SET ";
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
	
	/**
	 * 屏蔽数目
	 * Enter description here ...
	 * @param unknown_type $member_id
	 */
	function get_shield_count_by_member_id($member_id, $dates)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "shield WHERE member_id = '" . $member_id . "' AND dates = '" . $dates . "'";
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	/**
	 * 屏蔽添加
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	function shield_add($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "shield SET ";
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
	
	/**
	 * 互动数据操作 (推送, 推荐, 警告, 屏蔽)
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $type
	 */
	public function interactive_operate($id, $type, $flag, $recommend_time)
	{
		$sql = "UPDATE " . DB_PREFIX . "interactive SET  " . $type . " = " . $flag . ", recommend_time = " . $recommend_time . " WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function is_read($id, $type)
	{
		$sql = "SELECT " . $type . " FROM " . DB_PREFIX . "interactive WHERE id = " . $id;
		$info = $this->db->query_first($sql);

		$status = $info[$type];
		
		$new_status = 0; //操作失败
		
		if (!$status)	//已读
		{
			$sql = "UPDATE " . DB_PREFIX . "interactive SET ".$type." = 1 WHERE id = " . $id;
			$this->db->query($sql);
			
			$new_status = 1;
		}
		else			//未读
		{
			$sql = "UPDATE " . DB_PREFIX . "interactive SET ".$type." = 0 WHERE id = " . $id;
			$this->db->query($sql);
			
			$new_status = 2;
		}

		return $new_status;
	}
	
	function get_channel_info($channel_id, $img_size = '60x23')
	{
		$ret = $this->get_channel_by_id($channel_id);
		$_channel_info = $ret[0];
		$channel_logo = '';
		if ($_channel_info['logo_info'])
		{
			$channel_logo = hg_material_link($_channel_info['logo_info']['host'], $_channel_info['logo_info']['dir'], $_channel_info['logo_info']['filepath'], $_channel_info['logo_info']['filename'], $img_size . '/');
		}
		
		$return = array(
			'channel_id'  	=> $_channel_info['id'],
			'channel_name'  => $_channel_info['name'],
			'channel_logo'  => $channel_logo,
		);
		return $return;
	}
	
	function get_channel_by_id($channel_id = '', $offset = '', $count = '', $k = '')
	{
		if (!$this->mLive)
		{
			return array();
		}
		
		$this->mLive->setSubmitType('post');
		$this->mLive->initPostData();
		$this->mLive->setReturnFormat('json');
		$this->mLive->addRequestData('a', 'get_channel_info');
		$this->mLive->addRequestData('id', $channel_id);
		$this->mLive->addRequestData('offset', $offset);
		$this->mLive->addRequestData('count', $count);
		$this->mLive->addRequestData('k', $k);
		$ret = $this->mLive->request('channel.php');
		return $ret;
	}
	
	function get_channel_count($k = '')
	{
		if (!$this->mLive)
		{
			return array();
		}
		
		$this->mLive->setSubmitType('post');
		$this->mLive->initPostData();
		$this->mLive->setReturnFormat('json');
		$this->mLive->addRequestData('a', 'get_channel_count');
		$this->mLive->addRequestData('k', $k);
		$ret = $this->mLive->request('channel.php');
		return $ret[0];
	}
}
?>