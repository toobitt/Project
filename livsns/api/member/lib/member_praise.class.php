<?php 
/***************************************************************************

* $Id: member_praise.class.php 15421 2012-12-12 09:28:06Z repheal $

***************************************************************************/
define('MOD_UNIQUEID','member_praise');//模块标识
class memberPraise extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function getMemberPraiseCountsByContentId($content_id, $type)
	{
		$sql = "SELECT id, member_id, content_id FROM " . DB_PREFIX . "member_praise ";
		$sql.= " WHERE content_id IN (" . $content_id . ")";
		$sql.= " AND type = '" . $type . "'";
		$sql.= " ORDER BY id DESC ";
		
		$q = $this->db->query($sql);
		
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[$row['content_id']][$row['member_id']] = $row;
		}
		return $info;
	}
	
	public function memberPraiseAdd($member_id, $content_id, $type)
	{
		$data = array(
			'member_id' => $member_id,
			'content_id' => $content_id,
			'type' => $type,
			'counts' => 1,
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip()
		);

		$sql = "INSERT INTO " . DB_PREFIX . "member_praise SET ";
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
			return $data['id'];
		}
		return false;
	}
	
	public function memberPraiseDelete($member_id, $content_id, $type)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "member_praise ";
		$sql.= " WHERE member_id = " . $member_id;
		$sql.= " AND content_id = " . $content_id;
		$sql.= " AND type = '" . $type . "'";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function checkMemberPraiseExists($member_id, $content_id, $type)
	{
		$sql = "SELECT id FROM " . DB_PREFIX . "member_praise ";
		$sql.= " WHERE member_id = " . $member_id;
		$sql.= " AND content_id = " . $content_id;
		$sql.= " AND type = '" . $type . "'";
		
		$info = $this->db->query_first($sql);
		return $info;
	}

}

?>