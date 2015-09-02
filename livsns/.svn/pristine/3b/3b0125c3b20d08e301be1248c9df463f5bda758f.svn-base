<?php
/***************************************************************************

* $Id: group 26794 2013-08-01 04:34:02Z purview $

***************************************************************************/
class check_bind extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members=new members();
		$this->membersql = new membersql();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function check_Bind($member_id,$type,$outputType = 1)
	{
		$is_bind = 0;
		if($member_id)
		{
			$sql = 'SELECT platform_id'.($outputType?'':' AS is_bind').',is_primary FROM ' . DB_PREFIX . 'member_bind WHERE member_id = "' . $member_id . '"';
			if($type)
			{
				$sql .=' AND type = \''.$type.'\'';
			}
			if($row = $this->db->query_first($sql))
			{
				if($outputType)
				$is_bind =  $row['platform_id'];
				else {
					$is_bind = $row;
				}
			}
		}
		return $is_bind;
	}
	
	public function checkMobileBind($memberId)
	{
		return $this->check_Bind($memberId, 'shouji');
	}
	
	public function checkEmailBind($memberId)
	{
		return $this->check_Bind($memberId, 'email');
	}

	/**
	 * 
	 * 检测uc是否与帐号绑定 ...
	 * @param unknown_type $member_id
	 * @param unknown_type $type
	 * @param unknown_type $isEnforce 是否强制检测
	 */
	function check_uc($member_id,$type = '',$isEnforce = 1)
	{
		if(!$this->settings['ucenter']['open']&&!$isEnforce)
		{
			return 0;
		}
		$is_bind = 0;
		$field = 'inuc';
		$extend = ' AND inuc!=0';
		if($type == 'uc')//兼容uc类型会员历史性遗留bug
		{
			$field = 'platform_id';
			$extend = ' AND type = \'uc\'';
		}
		if($member_id)
		{
			$sql = 'SELECT '.$field.' FROM ' . DB_PREFIX . 'member_bind WHERE member_id = ' . $member_id .$extend;			
			if($row = $this->db->query_first($sql))
			{
				$is_bind =  intval($row[$field]);
			}
		}
		return $is_bind;
	}

	function bind_to_memberid($platform_id,$type,$is_bind = false,$identifier = 0)
	{
		$member_id = 0;
		$member_info = array();
		if(in_array($type, array('m2o','uc','shouji'))&&!$is_bind)
		{
			$member_info = $this->db->query_first('SELECT member_id FROM '.DB_PREFIX.'member WHERE member_name = \''.$platform_id.'\''.' AND type = \''.$type.'\' AND identifier = \''.$identifier.'\'');
			$member_id = $member_info['member_id']?$member_info['member_id']:0;
		}
		elseif($type)
		{
			$member_info = $this->db->query_first('SELECT member_id FROM '.DB_PREFIX.'member_bind WHERE platform_id = \''.$platform_id.'\''.' AND type =\''.$type.'\' AND identifier = \''.$identifier.'\'');
			$member_id = $member_info['member_id'];
		}
		return $member_id;
	}
	
	/**
	 * 
	 * 检测用户名是否被注册或者绑定 ...
	 * @param unknown_type $username
	 * @param unknown_type $identifier
	 */
	public function checkmembernamereg($username,$identifier)
	{
		$member_id = $this->bind_to_memberid($username,'uc');//优先检测uc类型
		$type = 'uc';
		if(empty($member_id))//如果uc类型不存在则检测m2o
		{
			$member_id =  $this->bind_to_memberid($username,'m2o',false,$identifier);
		}
		if(empty($member_id))
		{
			$member_id =  $this->bind_to_memberid($username,'shouji',false,$identifier);
		}
		if(empty($member_id))
		{
			$member_id =  $this->bind_to_memberid($username,'shouji',true,$identifier);
		}
		return $member_id;
	}

}

?>