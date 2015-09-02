<?php
/***************************************************************************

* $Id: member.class.php 47046 2015-08-03 07:55:51Z jitao $

***************************************************************************/
class member extends InitFrm
{
	public function __construct()
	{
		parent::__construct();

	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition, $offset = 0, $count = 20, $orderby = '',$extend_sql='',$field='m.*,mb.nick_name')
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = $orderby ? $orderby : " ORDER BY m.member_id DESC ";
		$sql = "SELECT {$field},mb.type as mbtype,mb.platform_id,g.name as groupname,g.starnum,g.usernamecolor,g.icon as groupicon,gra.icon as graicon,gra.name as graname,gra.digital FROM " . DB_PREFIX . "member as m
		LEFT JOIN ".DB_PREFIX."member_bind as mb ON mb.member_id=m.member_id 
		LEFT JOIN ".DB_PREFIX.'group as g ON m.gid=g.id 
		LEFT JOIN '.DB_PREFIX.'grade gra ON gra.id=m.gradeid'.$extend_sql;
		$sql.= " WHERE 1 " . $condition .' GROUP BY m.member_id'. $orderby . $limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['digitalname'] = (defined('GRADEDIGITAL_PREFIX')?GRADEDIGITAL_PREFIX:'LV.').$row['digital'];
			$row['createTimeStamp'] = $row['create_time'];
			$row['create_time'] 	= date('m-d H:i', $row['create_time']);
			$row['updateTimeStamp'] = $row['update_time'];
			$row['update_time'] 	= date('m-d H:i', $row['update_time']);
			if ($row['avatar'])
			{
				$row['avatar'] = maybe_unserialize($row['avatar']);
			}
			else
			{
				$row['avatar'] = new stdClass;
			}
			// $row['avatar'] 			= $row['avatar'] ? maybe_unserialize($row['avatar']) : array();
			$row['last_login_time']&&$row['last_login_time'] = date('Y-m-d H:i:s', $row['last_login_time']);
			$row['final_login_time']&&$row['final_login_time'] = date('Y-m-d H:i:s', $row['final_login_time']);
			if($row['groupicon'])
			{
				$row['groupicon'] = maybe_unserialize($row['groupicon']);
			}
			if($row['graicon'])
			{
				$row['graicon'] = maybe_unserialize($row['graicon']);
			}
			if($row['mbtype']=='shouji'&&empty($row['mobile']))
			{
				$row['mobile']=$row['platform_id'];
			}
			unset($row['platform_id'],$row['mbtype']);
			$row['medal_info']=array();
			$return[$row['member_id']] = $row;
		}

		return $return;
	}

	public function detail($member_id)
	{
		if(!$member_id)
		{
			$condition = " ORDER BY member_id DESC LIMIT 1 ";
		}
		else
		{
			$condition = " WHERE member_id IN (" . $member_id .")";
		}

		$sql = "SELECT m.*,g.name as groupname,gra.name as graname,gra.digital FROM " . DB_PREFIX . "member as m
		LEFT JOIN ".DB_PREFIX."group as g on g.id=m.gid 
		LEFT JOIN ".DB_PREFIX."grade as gra ON gra.id=m.gradeid". $condition;		
		$row = $this->db->query_first($sql);

		if(is_array($row) && $row)
		{
			$row['digitalname'] = (defined('GRADEDIGITAL_PREFIX')?GRADEDIGITAL_PREFIX:'LV.').$row['digital'];
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			if (!empty($row['groupexpiry']))
			{
				$row['groupexpiry'] = date('Y-m-d' , $row['groupexpiry']);
			}
			$row['avatar'] 	= $row['avatar']&&$row['avatar']!='a:0:{}'?hg_fetchimgurl(maybe_unserialize($row['avatar'])):'';
			if($row['myData'])
			{
				$row['myData'] = maybe_unserialize($row['myData']);
			}
			return $row;
		}
		return false;
	}

	public function count($condition = '',$extend_sql='')
	{
		$sql = "SELECT COUNT(DISTINCT m.member_id) AS total FROM " . DB_PREFIX . "member as m
		LEFT JOIN ".DB_PREFIX."member_bind as mb ON mb.member_id=m.member_id ".$extend_sql." WHERE 1 " . $condition;
		return $this->db->query_first($sql);
	}
	
	public function getIdentifierName(array $memberInfo)
	{
		$Identifier = array();
		foreach ($memberInfo as $v)
		{
			if(isset($v['identifier']) && ! in_array($v['identifier'], $Identifier)){
				$Identifier[] = $v['identifier'];
			}
		}
		$identifierUserSystem = new identifierUserSystem();
		$IdentifierName = $identifierUserSystem->getIusNameForIdentifierAll($Identifier);
		
		foreach ($memberInfo as $k => $v)
		{
			if(isset($v['identifier'])&&$tmp_IdentifierName = $IdentifierName[$v['identifier']])
			{
				$memberInfo[$k]['iusname'] = $tmp_IdentifierName;
			}
		}
		return $memberInfo;
	}

	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "member SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);

		$data['member_id'] = $this->db->insert_id();

		if ($data['member_id'])
		{
			return $data;
		}
		return false;
	}


	public function update($data, array $where = array())
	{
		$idsArr = array();
		$membersql = new membersql();
		$data['member_id'] && $idsArr = array('member_id'=>$data['member_id']);
		$where && $idsArr = $where;
		$membersql -> update('member', $data, $idsArr);
		if ($idsArr)
		{
			return $data;
		}
		return false;
	}

	public function delete($member_id)
	{
		//会员主表
		$sql = "DELETE FROM " . DB_PREFIX . "member WHERE member_id IN (" . $member_id . ")";
		if($this->db->query($sql))
		{
		//绑定表
		$sql = "DELETE FROM " . DB_PREFIX . "member_bind WHERE member_id IN (" . $member_id . ")";
		$this->db->query($sql);

		//会员数据统计表
		$sql = "DELETE FROM " . DB_PREFIX . "member_count WHERE u_id IN (" . $member_id . ")";
		$this->db->query($sql);

		//会员积分日志
		$sql = "DELETE FROM " . DB_PREFIX . "credit_log WHERE member_id IN (" . $member_id . ")";
		$this->db->query($sql);

		//会员积分规则日志
		$sql = "DELETE FROM " . DB_PREFIX . "credit_rules_log WHERE uid IN (" . $member_id . ")";
		$this->db->query($sql);

		//会员好友黑名单表
		$sql = "DELETE FROM " . DB_PREFIX . "friend_blacklist WHERE uid IN (" . $member_id . ")";
		$this->db->query($sql);

		//会员黑名单表
		$sql = "DELETE FROM " . DB_PREFIX . "member_blacklist WHERE uid IN (" . $member_id . ")";
		$this->db->query($sql);

		//会员痕迹表
		$sql = "DELETE FROM " . DB_PREFIX . "member_trace WHERE member_id IN (" . $member_id . ")";
		$this->db->query($sql);

		//会员签到表
		$sql = "DELETE FROM " . DB_PREFIX . "sign WHERE member_id IN (" . $member_id . ")";
		$this->db->query($sql);

		//邀请记录表(邀请人)
		$sql = "DELETE FROM " . DB_PREFIX . "member_invite WHERE member_id IN (" . $member_id . ")";
		$this->db->query($sql);

		//邀请记录表(被邀请人)
		$sql = "DELETE FROM " . DB_PREFIX . "member_invite WHERE fuid IN (" . $member_id . ")";
		$this->db->query($sql);

		//扩展数据表
		$sql = "DELETE FROM " . DB_PREFIX . "member_info WHERE member_id IN (" . $member_id . ")";
		$this->db->query($sql);
		
		//我的模块数据表
		$sql = "DELETE FROM " . DB_PREFIX . "member_my WHERE member_id IN (" . $member_id . ")";
		$this->db->query($sql);
		
		$memberMyData = new memberMyData();
		$memberMyData->setParams('member_id',$member_id);
		$memberMyData->delete();
		return true;
	  }
		return false;
	}

	public function bind_create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "member_bind SET ";
		$space = '';
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

	public function bind_update($data,$where = '')
	{
		$sql = "UPDATE " . DB_PREFIX . "member_bind SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= $where;
		if(empty($where))
		{
			$sql .= " WHERE member_id = " . $data['member_id'] . " AND platform_id = '" . $data['platform_id'] . "' AND type = '" . $data['type'] . "'";
		}
		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}

	public function bind_delete($member_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "member_bind WHERE member_id IN (" . $member_id . ")";

		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}

	public function uc_register($register_data)
	{
		if(is_array($register_data))
		{
			foreach($register_data as $key=>$val)
			{
				if(!$key)
				{
					continue;
				}
				$$key = $val;
			}
		}
		include_once(CUR_CONF_PATH . 'uc_client/client.php');
		$is_success = uc_user_register($member_name, $password, $email);
		if($is_success <= 0)
		{
			return $is_success;
		}
		unset($register_data['password']);
		$register_data['member_id'] = $is_success;
		return $register_data;
	}

	public function syncUcRegister($memberId,$memberName,$password,$email)
	{
		if(!$this->settings['ucenter']['open'])
		{
			return 0;
		}
		if($memberId&&$memberName&&$password&&$email)
		{
			$register_data = array(
		 		'member_name'	=> $memberName,
		 		'password'		=> $password,
		 		'email'			=> $email,
			);
			$register_data = $this->uc_register($register_data);
			$tmp_inuc = 0;
			$inuc = 0;
			if(!is_array($register_data)&&$register_data <= 0)
			{
				$tmp_inuc = $register_data;
			}
			else {
				$tmp_inuc = $register_data['member_id'];
			}
			if($tmp_inuc>0)
			{
				$inuc = $tmp_inuc;
			}
			return $this->bind_uc($memberId, $inuc);
		}
	}

	public function bind_uc($memberId,$inuc)
	{
		if($inuc)
		{
			$condition = " AND mb.member_id = " . $memberId;
			$_bind = $this->get_bind_info($condition);
			if(is_array($_bind))
			foreach ($_bind as $v)
			{
				if($v['type'] != 'uc' &&$v['inuc'] == '0')
				{
					$bind[] = $v;
				}
			}
			$ret_bind = array();
			if($bind&&is_array($bind))	//已绑定
			{
				//更新绑定表
				foreach ($bind as $v)
				{
					$bind_data = array();
					if($v['type'] == 'm2o')
					{
						$bind_data = array(
								'platform_id' => $inuc,
								'inuc' => $inuc,
						);
						$where = 'WHERE member_id = '.$memberId.' AND type = \'m2o\'';
					}
					elseif ($v['type'] != 'm2o'&&$v['type']!='uc')
					{
						$bind_data = array(
								'inuc' => $inuc,
						);
						$where = 'WHERE member_id = '.$memberId.' AND type = \''.$v['type'].'\'';
					}
					if($bind_data)
					{
						$this->bind_update($bind_data,$where);
					}
				}
			}
			return intval($inuc);
		}
		return 0;
	}

	public function uc_user_edit($username, $oldpw, $newpw, $email, $ignoreoldpw = 0, $questionid = '', $answer = '')
	{
		if(empty($this->input['member_name']))
		{
			$this->input['member_name'] = $username;
		}
		$check_Bind = new check_Bind();
		if(hg_check_email_format($username))
		{
			$type = 'email';
			$member_id = $check_Bind->bind_to_memberid($username,$type,true);//如果用户名为邮箱则检测邮箱类型
			if($member_id){
				$sql='SELECT member_name FROM '.DB_PREFIX.'member AS m WHERE member_id = \''.$member_id.'\'';
				$row = $this->db->query_first($sql);
				$this->input['member_name'] = $username = $row['member_name'];
			}
		}
		else if(hg_verify_mobile($username))
		{
			$type = 'shouji';
			$member_id = $check_Bind->bind_to_memberid($username,$type,true);//如果用户名为邮箱则检测邮箱类型
			if($member_id){
				$sql='SELECT member_name FROM '.DB_PREFIX.'member AS m WHERE member_id = \''.$member_id.'\'';
				$row = $this->db->query_first($sql);
				$this->input['member_name'] = $username = $row['member_name'];
			}
		}
		if(empty($member_id))
		{
			$member_id = $check_Bind->bind_to_memberid($username,'uc');//优先检测uc类型
			$type = 'uc';
		}
		if(empty($member_id))//如果uc类型不存在则检测m2o
		{
			$member_id = $check_Bind->bind_to_memberid($username,'m2o');
			$type = 'm2o';
		}
		$is_ucid = 0;
		if($member_id){
			$is_ucid = $check_Bind->check_uc($member_id,$type);
		}		
		include_once (CUR_CONF_PATH . 'uc_client/client.php');
		$uc_userinfo = uc_get_user($this->input['member_name']);
		if($is_ucid&&$is_ucid == $uc_userinfo[0]){
			return uc_user_edit($username, $oldpw, $newpw, $email,$ignoreoldpw, $questionid, $answer);
		}
		return 0;//UC信息未修改
	}

	/**
	 *
	 * 删除uc会员 ...
	 * @param int $memberId
	 */
	public function delUcUser($memberId)
	{
		if(empty($memberId))
		{
			return 0;
		}
		$condition = ' AND m.member_id IN ('.$memberId.')';
		$delMemberInfo = $this->get_member_info($condition,'m.member_name,m.type','','member_id',0);
		if(empty($delMemberInfo))
		{
			return 0;
		}
		$check_Bind = new check_Bind();
		include_once (CUR_CONF_PATH . 'uc_client/client.php');
		$ucId = array();
		foreach ($delMemberInfo AS $k => $v)
		{
			if(in_array($v['type'], array('m2o','uc'))){
				$ucId[] = $check_Bind->check_uc($k,$v['type']);
			}
		}
		if($ucId){
			return uc_user_delete($ucId);//支持批量删除
		}
	}

	public function get_member_info($condition, $field = ' * ',$leftjoin='',$key = '',$isMyData = 1)
	{
		if($key&&$field!='*') {
			$field .= ','.$key;
		}
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "member as m {$leftjoin} WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['create_time'])
			{
				$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			}

			if ($row['update_time'])
			{
				$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			}

			$row['last_login_time']&&$row['last_login_time'] = date('Y-m-d H:i:s', $row['last_login_time']);
			$row['final_login_time']&&$row['final_login_time'] = date('Y-m-d H:i:s', $row['final_login_time']);

			if ($row['avatar'])
			{
				$row['avatar'] 	= maybe_unserialize($row['avatar']);
			}
			if ($row['background'])
			{
			    $row['background'] 	= maybe_unserialize($row['background']);
			}
			if($row['groupicon'])
			{
				$row['groupicon'] = maybe_unserialize($row['groupicon']);
			}
			if($isMyData)
			{
				$memberMy = new memberMy();
				if($row['myData'])
				{
					$row['myData'] = $memberMy->outPutDataFormat(maybe_unserialize($row['myData']),(int)$this->input['usesource']);
				}
				elseif(isset($row['myData'])&&$member_id!=$row[member_id]) {
					$member_id = $row[member_id];
					$row['myData'] = $memberMy->outPutDataFormat($memberMy->cache($member_id),(int)$this->input['usesource']);
				}
			}
			if($key)
			{	$valKey = '';
			$valKey = $row[$key];
			unset($row[$key]);
			$return[$valKey] = $row;
			}
			else {
				$return[] = $row;
			}
		}
		return $return;
	}
	
	public function getMemberType($memberId)
	{
		$where = membersql::where(array('member_id'=>$memberId));
		$memberInfo = $this->get_member_info($where,'type','','',0);
		return $memberInfo[0]['type'];
	}

	public function get_bind_info($condition, $field = ' mb.*,m.avatar')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "member_bind mb LEFT JOIN ".DB_PREFIX."member m ON m.member_id = mb.member_id WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['bind_time'])
			{
				$row['bind_time'] = date('Y-m-d', $row['bind_time']);
			}
			if ($row['avatar'])
			{
				$row['avatar'] = maybe_unserialize($row['avatar']);
			}
			$return[] = $row;
		}
		return $return;
	}

	/**
	 * 会员名存在
	 * Enter description here ...
	 * @param unknown_type $member_name
	 * @param unknown_type $member_id
	 */
	function member_name_exists($member_name, $member_id = 0,$identifier = 0)
	{
		$condition = '';

		if ($member_id)
		{
			$condition .= " AND member_id NOT IN (" . $member_id . ")";
		}

		$binary = '';//不区分大小写
		if(defined('IS_BINARY') && !IS_BINARY)//区分大小写
		{
			$binary = 'binary ';
		}
			
		$sql = "SELECT member_id, member_name FROM " . DB_PREFIX . "member WHERE " . $binary . " member_name='" . $member_name . "' AND identifier = '".$identifier .'\' '. $condition;
		$data = $this->db->query_first($sql);
		return $data;
	}

	/**
	 * 会员名认证
	 * $member_name 会员名
	 * 返回
	 * -1 超出最大长度
	 * -2 低于最小长度
	 * -3 含有 % @ < > * 特殊符号
	 */
	function member_name_auth($member_name)
	{
		//	$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^root|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
		$len = $this->dstrlen($member_name);
		$max = $this->settings['member_name_length']['max'] ? $this->settings['member_name_length']['max'] : 15;
		$min = $this->settings['member_name_length']['min'] ? $this->settings['member_name_length']['min'] : 3;
		//	if($len > $max || $len < $min || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\@\&]|$guestexp/is", $member_name))
		if($len > $max)
		{
			return -4;
		}
		else if($len < $min)
		{
			return -5;
		}
		else if (preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\@\&]/is", $member_name))
		{
			return -6;
		}
		return 1;
	}

	function dstrlen($str)
	{
		$count = 0;
		for($i = 0; $i < strlen($str); $i++)
		{
			$value = ord($str[$i]);
			if($value > 127)
			{
				$count++;
				if($value >= 192 && $value <= 223) $i++;
				elseif($value >= 224 && $value <= 239) $i = $i + 2;
				elseif($value >= 240 && $value <= 247) $i = $i + 3;
			}
			$count++;
		}
		return $count;
	}

	/**
	 * 验证会员名
	 * Enter description here ...
	 * @param unknown_type $member_name
	 */
	function verify_member_name($member_name, $member_id = 0,$identifier=0,$type,$isUc = 1)
	{
		$member_name = addslashes(trim(stripslashes($member_name)));
		$ret = $this->member_name_auth($member_name);
		if($ret < 0 && $type == 'm2o')
		{
			return $ret;//-4 超出最大长度 -5 低于最小长度 -6 含有 % @ < > * 特殊符号
		}
		else if($this->member_name_exists($member_name, $member_id,$identifier))
		{
			return -7;//用户名存在
		}
		elseif ($this->settings['ucenter']['open']&&$isUc&&!$identifier)
		{
			include_once (CUR_CONF_PATH . 'uc_client/client.php');
			return uc_user_checkname($member_name);
		}
		return 1;
	}

	/**
	 * 头像入素材库
	 * Enter description here ...
	 * @param unknown_type $file
	 * @param unknown_type $id
	 */
	public function add_material($file, $id)
	{
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$mMaterial = new material();
		if (!$mMaterial)
		{
			return false;
		}

		$files['Filedata'] = $file;
		$material = $mMaterial->addMaterial($files, $id);
		$return = array();
		if (!empty($material))
		{
			$return['host'] 	= $material['host'];
			$return['dir'] 		= $material['dir'];
			$return['filepath'] = $material['filepath'];
			$return['filename'] = $material['filename'];
		}

		return $return;
	}
	
	
	/**
	 * 头像入素材库
	 * Enter description here ...
	 * @param unknown_type $file
	 * @param unknown_type $id
	 */
	public function local_material($avatar_url, $id)
	{
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$mMaterial = new material();
		if (!$mMaterial)
		{
			return false;
		}
		$material = $mMaterial->localMaterial($avatar_url, $id);
		$return = array();
		if (!empty($material))
		{
			$return['host'] 	= $material['host'];
			$return['dir'] 		= $material['dir'];
			$return['filepath'] = $material['filepath'];
			$return['filename'] = $material['filename'];
		}
		return $return;
	}
	
	

	/**
	 * 获取access_token
	 * $id
	 * $user_name
	 * $appid
	 * $appkey
	 * $ip
	 * $verify_user_cb 回调地址
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function get_access_token($data)
	{
		require_once ROOT_PATH . 'lib/class/curl.class.php';
		$this->curlAuth = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		if (!$this->curlAuth)
		{
			return array();
		}
		$this->curlAuth->setSubmitType('post');
		$this->curlAuth->setReturnFormat('json');
		$this->curlAuth->addRequestData('a', 'show');
		if (empty($data))
		{
			return array();
		}

		foreach ($data AS $k => $v)
		{
			$this->curlAuth->addRequestData($k, $v);
		}

		$this->curlAuth->addRequestData('admin_group_id', 0);
		$this->curlAuth->addRequestData('group_type', 0);
		$ret = $this->curlAuth->request('get_access_token.php');
		return $ret[0];
	}

	public function member_trace_create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "member_trace SET ";
		$space = '';
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

	public function getMemberTrace($params,$field = '*',$key='',$orderby = 'ORDER BY create_time DESC',$isBatch = 0,$limit='')
	{
		class_exists('membersql') OR include  CUR_CONF_PATH . 'core/membersql.core.php';
		$membersql = new membersql();
		$where = $membersql->where($params);
		$sql = 'SELECT '.$field.' FROM '.DB_PREFIX.'member_trace WHERE 1 '.$where.' '.$orderby.' '.$limit;
		$query = $this->db->query($sql);
		while ($row  = $this->db->fetch_array($query))
		{
			if(!$isBatch){
				if($key)
				$ret[$row[$key]] = $row;
				else
				$ret = $row;
				break;
			}else {
				if($key)
				$ret[$row[$key]][] = $row;
				else
				$ret[] = $row;
			}
		}
		return $ret;
	}


	/**
	 *
	 * 根据图片url更新头像
	 * @param string $avatar_url 新头像链接
	 * @param array $bind 原始头像数据
	 */
	public function update_avatar($avatar_url,$bind,$member_id = 0,$is_enforce = false)
	{
		$attatch = array();
		$avatar_array = array();
		$old_avatar=array();
		$avatar_num = 0;
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$mMaterial = new material();
		if (!$mMaterial)
		{
			return false;
		}
		if($member_id&&!$is_enforce)//$is_enforce控制是否强制更新头像
		{
			$sql = 'SELECT count(member_id) as total FROM '.DB_PREFIX.'member as m WHERE m.member_id = '.$member_id.' AND ( m.avatar != \'\' AND m.avatar !=\'a:0:{}\')';
			$avatar_total = $this->db->query_first($sql);
			$avatar_num = intval($avatar_total['total']);
		}
		if($avatar_url)
		{
			if((empty($bind)||empty($bind['avatar']))&&!$member_id ||(!$avatar_num&&$member_id))
			{
				$attatch = $mMaterial->localMaterial($avatar_url,$member_id,0,0);
				$attatch = $attatch[0];
				if(!$attatch['error'])
				{
					$avatar_array = array(
						'host'		=>$attatch['host'],
						'dir'		=>$attatch['dir'],
						'filepath'	=>$attatch['filepath'],
						'filename'	=>$attatch['filename'],
					);
				}
			}
			//else {
			//$old_avatar = $bind['avatar'];
			//$attatch = $mMaterial->replaceImg(hg_fetchimgurl($old_avatar),$avatar_url);
			//	}
		}
		return $avatar_array;
	}
	/**
	 *
	 * 检测手机号是否在主表mobile字段存在 ...
	 * @param int $mobile 检测的手机号
	 * @param int $member_id 检测的用户
	 * @return int -1为如果
	 */
	public function checkMobile($mobile,$member_id=0)
	{
		if(!hg_verify_mobile($mobile))
		{
			return -1;//手机号格式不正确
		}
		$reMember = array();
		if($mobile)
		{
			$sql = 'SELECT member_id FROM '. DB_PREFIX . 'member WHERE mobile = \''.$mobile.'\'';
			$reMember = $this->db->query_first($sql);
		}
		if($reMember)
		{
			if ($member_id&&$member_id==$reMember[member_id])
			{
				return 2;//已存在，但是属于自己
			}
			return 1;//已存在
		}
		return 0;//未绑定
	}

	public function loginInfoRecord($member_id,$loginInfo)
	{
		class_exists('membersql') OR include  CUR_CONF_PATH . 'core/membersql.core.php';
		$newLoginInfo = array();
		if(is_numeric($member_id)&&$member_id>0)
		if(is_array($loginInfo))
		foreach ($loginInfo as $k => $v)
		{
			if($k == 'last_login_device')
			{
				if($v&&$v!='www'&&$v!='unknown')
				{
					$newLoginInfo[$k] = $v;
				}
			}elseif($v) {
				$newLoginInfo[$k] = $v;
			}
		}
		if($newLoginInfo){
			$membersql = new membersql();
			$membersql->update('member', $newLoginInfo,array('member_id'=>$member_id));
			return true;
		}
		return false;
	}

	/**
	 *
	 * 用户名修改判断 ...
	 * @param int $member_id 用户ID
	 * @param int $isUpdate 在UC未同步情况下是否强制修改(比如后台使用)
	 */
	public function isMemberNameUpdate($member_id,$isUpdate = 0)
	{
		$isRet = 0;
		$checkUc = $this->checkUc($member_id);
		if ((!$this->settings['ucenter']['open']&&!$checkUc)&&$isUpdate==1){
			$isRet = 1;//正常流程
		}
		else if (!$this->settings['ucenter']['open']&&$isUpdate==2)//不检测是否已经绑定，仅检测是否开启
		{
			$isRet = 2;//建议处理流程：需要处理取消UC绑定关系
		}
		else if (!$checkUc&&$isUpdate==3)//不检测是否开启，仅检测是否绑定
		{
			$isRet = 3;
		}
		else if($isUpdate==4)//不管是否绑定或者UC是否开启
		{
			$isRet = 4;//建议处理流程：需要处理取消原绑定UC并删除原UC帐号后，在重新添加绑定UC
		}
		else if(!$this->settings['ucenter']['open']&&!$checkUc&&(defined('ALLOW_UPDATE_MEMBERNAME')&&ALLOW_UPDATE_MEMBERNAME)){
			$isRet = 1;//正常流程
		}
		return $isRet;
	}


	public function checkUc($member_id)
	{
		$ucid = 0;
		$checkBind = new check_Bind();
		$ucid = $checkBind->check_uc($member_id);
		if(!$ucid)
		{
			$ucid = $checkBind->check_uc($member_id,'uc');
		}
		return $ucid;
	}

	/**
	 *
	 * 验证本地记录密码是否正确 ...
	 * @param unknown_type $member_name
	 */
	public function verifyPassword($memberName,$password,$type, $encrypt_num = 0)
	{
		$condition = ' AND m.member_name = \''.$memberName.'\' AND type = \''.$type.'\'';
		$member_info = $this->get_member_info($condition,'m.member_id,m.password,m.salt','','',0);
		if (empty($member_info)||!$password)
		{
			return 0;
		}
		if ($encrypt_num == 1)
		{
			$md5_password = md5($password . $member_info[0]['salt']);
		}
		else
		{
			$md5_password = md5(md5($password) . $member_info[0]['salt']);
		}
		if ($md5_password == $member_info[0]['password'])
		{
			return $member_info[0]['member_id'];
		}
		return 0;
	}

	public function ExportbindData($bind,&$data)
	{
		$data['is_bind_mobile'] = 0;
		$data['is_bind_email'] = 0;
		$data['is_bind_qq'] = 0;
		$data['is_bind_sina'] = 0;
		$is_flag = true;
		foreach ($bind as $bin)
		{
			if($is_flag)
			{
				$data['nick_name'] = $bin['nick_name'];
				$data['nick_name']&&$is_flag = false;
			}
			if($bin['type'] == 'shouji')
			{
				$data['is_bind_mobile'] = 1;
				$data['mobile'] = $bin['platform_id'];
			}
			elseif ($bin['type'] == 'email')
			{
				$data['is_bind_email'] = 1;
				$data['email'] = $bin['platform_id'];
			}
			elseif($bin['type'] == 'sina')
			{
				$data['is_bind_sina'] = 1;
			}
			else if ($bin['type'] == 'qq')
			{
				$data['is_bind_qq'] = 1;
			}
		}
	}
	/**
	 * 
	 * 计算资料完成度 ...
	 * @param unknown_type $data
	 */
	public function profilePercentComplete($data = array())
	{
		$profileField = array(
		 'avatar',
		 'nick_name',
		 'extension' => array(
		'birthday',
		'gender',
		'prov',
		'city',
		'dist',
		'qqnum',
		'wechat',
		),
		);
		$i = $n = 0;
		foreach ($profileField as $k => $v)
		{
			if(is_array($v))
			{
				foreach ($v as $vv)
				{
					$n++;
					if($k == 'extension')
					{
						if($data['extension'][$vv]['value'])
						{
							$i++;
						}
					}
				}
			}
			else if ($v && $data[$v])
			{	
				$n++;			
				if('avatar' == $v)
				{
					if($data[$v] !='a:0:{}')
					{						
						$i++;
					}
					else 
					{
						continue;
					}
				}
				else $i++;
			}
			else $n++;
		}
		return roundToPercent(round($i/$n,3));
	}
	
	private function getMemberIdForMI(array $params)
	{
		$membersql = new membersql();
		$membersql->setTable('member');
		$membersql->where($params);
		$membersql->setSelectField('member_id,identifier');
		$membersql->setKey('member_id');
		$membersql->setOtherKey('identifier');
		return $membersql;
	}
	
	public function getIdentifierForMemberIdAll(array $memberId)
	{
		$membersql = $this->getMemberIdForMI(array('member_id' => $memberId));
		$membersql->setType(4);
		return $membersql->show();
	}
	
	public function getIdentifierForMemberId($memberId)
	{
		$memberId = (int)$memberId;
		$IdentifierInfo = $this->getIdentifierForMemberIdAll(array($memberId));
		return (int)$IdentifierInfo[$memberId];
	}
	
	public function getMemberIdForIdentifierAll(array $identifier)
	{
		$membersql = $this->getMemberIdForMI(array('identifier' => $identifier));
		$membersql->setType(5);
		return $membersql->show();
	}
	
	public function getMemberIdForIdentifier($identifier)
	{
		$identifier = (int)$identifier;
		$memberIdInfo = $this->getMemberIdForIdentifierAll(array($identifier));
		return $memberIdInfo[$identifier];
	}
	
	public function getActivateMemberCount($start_time = 0 , $end_time = 0)
	{
		if($start_time == 0 && $end_time == 0)
		{
			$sql = "select count(*) as total from ".DB_PREFIX."member";
		}
		else
		{
			$sql = "select count(*) as total from ".DB_PREFIX."member where create_time > ".$start_time." and create_time <".$end_time;
		}

		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function getTodayMemberInfo($start_time = 0)
	{		
		$sql = "select count(*) as total,t as hour from (SELECT FROM_UNIXTIME(create_time,'%H') as t,create_time FROM ".DB_PREFIX."member where create_time > ".$start_time." ) as new_table  group by new_table.t";
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query))
		{
			$info[] = $rows;
		}
		$ret = array();
		if($info && is_array($info))
		{
			foreach ($info as $k => $v)
			{
				$ret[intval($v['hour'])] =  intval($v['total']);
			}
		}
		return $ret;
	}

}

?>