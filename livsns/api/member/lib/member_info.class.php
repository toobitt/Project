<?php 
/***************************************************************************

* $Id: member_info.class.php 23864 2013-06-22 07:17:08Z zhuld $

***************************************************************************/
define('MOD_UNIQUEID','member_info');//模块标识
class memberInfo extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 会员基本信息
	 * Enter description here ...
	 */
	
	public function memberInfoDetail($member_id)
	{
		$condition = " WHERE member_id = " . $member_id;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_info " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			if ($row['birth'])
			{
				$row['birth'] = date('Y-m-d' , $row['birth']);
			}
			
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			return $row;
		}

		return false;
	}
	
	public function memberInfoCreate($info, $member_id)
	{
		if (!$this->checkMemberInfo('member_info', $member_id))
		{
			return FALSE;
		}
	
		$data = array(
			'member_id' => $member_id,
			'cn_name' => $info['cn_name'],
			'en_name' => $info['en_name'],
			'sex' => $info['sex'],
			'birth' => $info['birth'],
			'constellation' => $info['constellation'],
			'bloodtype' => $info['bloodtype'],
			'language' => $info['language'],
			'live_country' => $info['live_country'],
			'live_prov' => $info['live_prov'],
			'live_city' => $info['live_city'],
			'live_dist' => $info['live_dist'],
			'home_country' => $info['home_country'],
			'home_prov' => $info['home_prov'],
			'home_city' => $info['home_city'],
			'home_dist' => $info['home_dist'],
			'introduce' => $info['introduce'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip()
		);
		
		$sql = "INSERT INTO " . DB_PREFIX . "member_info SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		if($info['mark'])
		{
			require_once ROOT_PATH . 'lib/class/mark.class.php';
			$this->mark = new mark();
			$mark_data = array(
				'user_id' => $member_id,
				'source' => 'user',
				'source_id' => $member_id,
				'action' => 1,
				'name' =>$info['mark'],
			);
			$ret = $this->mark->create_source_id_mark($mark_data);
		}

		if ($this->db->query($sql))
		{
			return $data;
		}
		
		return false;
	}
	
	public function memberInfoUpdate($info, $member_id)
	{
		$data = array(
			'cn_name' => $info['cn_name'],
			'en_name' => $info['en_name'],
			'sex' => $info['sex'],
			'mark' => $info['mark'],
			'birth' => $info['birth'],
			'constellation' => $info['constellation'],
			'bloodtype' => $info['bloodtype'],
			'language' => $info['language'],
			'live_country' => $info['live_country'],
			'live_prov' => $info['live_prov'],
			'live_city' => $info['live_city'],
			'live_dist' => $info['live_dist'],
			'home_country' => $info['home_country'],
			'home_prov' => $info['home_prov'],
			'home_city' => $info['home_city'],
			'home_dist' => $info['home_dist'],
			'introduce' => $info['introduce'],
			'update_time' => TIMENOW,
		);
		
		$sql = "UPDATE " . DB_PREFIX . "member_info SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE member_id = " . $member_id;
		if($info['mark'])
		{
			include_once(ROOT_PATH . 'lib/class/mark.class.php');
			$this->mark = new mark();
			$mark_data = array(
				'user_id' => $member_id,
				'source' => 'user',
				'source_id' => $member_id,
				'action' => 'myself',
				'name' => $info['mark'],
			);
			$ret = $this->mark->update_source_id_mark($mark_data);
		}
		
		if ($this->db->query($sql))
		{
			$data['member_id'] = $member_id;
			return $data;
		}
		
		return false;
	}
	
	/**
	 * 会员联系方式信息
	 */
	public function memberContactDetail($member_id)
	{
		$condition = " WHERE member_id = " . $member_id;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_contact " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			return $row;
		}

		return false;
	}

	public function memberContactCreate($info, $member_id)
	{
		if (!$this->checkMemberInfo('member_contact', $member_id))
		{
			return FALSE;
		}
		
		$data = array(
			'member_id' => $member_id,
			'qq_num' => $info['qq_num'],
			'other_com' => $info['other_com'],
			'mobile' => $info['mobile'],
			'phone' => $info['phone'],
			'email' => $info['email'],
			'address_country' => $info['address_country'],
			'address_prov' => $info['address_prov'],
			'address_city' => $info['address_city'],
			'address_dist' => $info['address_dist'],
			'address' => $info['address'],
			'zipcode' => $info['zipcode'],
			'website' => $info['website'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip()
		);
		
		$sql = "INSERT INTO " . DB_PREFIX . "member_contact SET ";
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
	
	public function memberContactUpdate($info, $member_id)
	{
		$data = array(
			'qq_num' => $info['qq_num'],
			'other_com' => $info['other_com'],
			'mobile' => $info['mobile'],
			'phone' => $info['phone'],
			'email' => $info['email'],
			'address_country' => $info['address_country'],
			'address_prov' => $info['address_prov'],
			'address_city' => $info['address_city'],
			'address_dist' => $info['address_dist'],
			'address' => $info['address'],
			'zipcode' => $info['zipcode'],
			'website' => $info['website'],
			'update_time' => TIMENOW,
		);
		
		$sql = "UPDATE " . DB_PREFIX . "member_contact SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE member_id = " . $member_id;
		
		if ($this->db->query($sql))
		{
			$data['member_id'] = $member_id;
			return $data;
		}
		
		return false;
	}
	
	/**
	 * 检查用户信息唯一
	 */
	public function checkMemberInfo($table, $member_id)
	{
		$sql = "SELECT member_id FROM " . DB_PREFIX . $table . " WHERE member_id = " . $member_id;
		$info = $this->db->query_first($sql);
		
		if (empty($info))
		{
			return true;
		}
		
		return false;
	}
	
}

?>