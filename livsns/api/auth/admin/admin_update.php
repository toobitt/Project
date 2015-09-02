<?php
define('MOD_UNIQUEID','auth');
require_once('global.php');
define('SCRIPT_NAME', 'AdminUpdate');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once('../lib/MibaoCard.class.php');
class  AdminUpdate extends Auth_frm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->mibao = new MibaoCard();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function update()
	{
		if (!intval($this->input['id']))
		{
			$this->errorOutput(NOID);
		}
		if (!trim($this->input['user_name']))
		{
			$this->errorOutput('请填写用户名称');
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'admin WHERE id = '.intval($this->input['id']);
		$admin_info = $this->db->query_first($sql);
		if(!$admin_info)
		{
			$this->errorOutput('用户信息不存在！');
		}
		if (!$this->check_unique())
		{
			$this->errorOutput('用户名须唯一');
		}
		#####节点权限检测数据收集
		$vdata = array('_action'=>'manage_user','id'=>$admin_info['id'],'user_id'=>$admin_info['user_id'], 'org_id'=>$admin_info['org_id']);
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_sort_ids = '';
			if($admin_info['father_org_id'])
			{
				$_sort_ids = $admin_info['father_org_id'];
			}
			if($this->input['father_org_id'])
			{
				$_sort_ids  = $_sort_ids ? $_sort_ids . ',' . $this->input['father_org_id'] : $this->input['father_org_id'];
			}
			if($_sort_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'admin_org WHERE id IN('.$_sort_ids.')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$vdata['nodes'][$row['id']] = $row['parents'];
				}
				//$this->errorOutput(var_export($vdata,1));
			}
		}
		#####节点权限
		$this->verify_content_prms($vdata);
		if($admin_info['source'] && $this->input['source'] != $admin_info['source'])
		{
			$this->errorOutput('该用户属于其他应用无法修改，请进入相应应用修改此用户');
		}
		$password = '';
		$password = trim($this->input['password']);
		if(empty($password))
		{
			$data = array(
				'id' => intval($this->input['id']),
	        	'admin_role_id' => $this->input['admin_role_id'] ? $this->input['admin_role_id'] : "",
	        	'father_org_id' => intval($this->input['father_org_id']),
			//'cardid' => intval($this->input['cardid']),
				'user_name' => trim(urldecode($this->input['user_name'])),
				'brief' => trim(urldecode($this->input['brief'])),
				'update_time' =>TIMENOW,
				'domain'=>trim($this->input['domain']),
			);
		}
		else
		{
			$salt = '';
			$salt = hg_generate_salt();
			$password = md5(md5(trim($this->input['password'])).$salt);
			$data = array(
				'id' => intval($this->input['id']),
				'admin_role_id' => $this->input['admin_role_id'] ? $this->input['admin_role_id'] : "",
				'father_org_id' => intval($this->input['father_org_id']),
			//'cardid' => intval($this->input['cardid']),
				'user_name' => trim(urldecode($this->input['user_name'])),
				'brief' => trim(urldecode($this->input['brief'])),
				'update_time' =>TIMENOW,
				'password'=>$password ,
				'salt'=>$salt,
			);
		}
		//是否需要重新绑定密保
		if(intval($this->input['cardid']))
		{
			$this->mibao->bind_card($data['id']);
		}
		$data['source'] = $this->input['source'];
		if ($data['admin_role_id'] && is_array($data['admin_role_id']))
		{
			$data['admin_role_id'] = implode(',',$data['admin_role_id']);
		}
		if(!$data['admin_role_id'])
		{
			$this->errorOutput('用户必须属于一个角色');
		}
		$this->check_admin_type($admin_info['id'], $data['admin_role_id']);

		//检测修改后的角色是否比自己大
		if ($this->user['group_type']>MAX_ADMIN_TYPE)
		{
			$temp1 = array_diff(explode(',', $data['admin_role_id']), explode(',', $admin_info['admin_role_id']));
			$temp2 = array_diff(explode(',', $admin_info['admin_role_id']),explode(',', $data['admin_role_id']));
			$temp = array_filter(array_merge($temp1,$temp2));
			if (!empty($temp))
			{
				if (min($temp)<=MAX_ADMIN_TYPE)
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
		}else {
			$temp1 = array_diff(explode(',', $data['admin_role_id']), explode(',', $admin_info['admin_role_id']));
			$temp2 = array_diff(explode(',', $admin_info['admin_role_id']),explode(',', $data['admin_role_id']));
			$temp = array_filter(array_merge($temp1,$temp2));
			if (!empty($temp))
			{
				if (min($temp)<$this->user['group_type'])
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
		}

		if ($_FILES['Filedata'])
		{
			$material = $this->uploadToPicServer($_FILES, intval($this->input['id']));
			if ($material)
			{
				$avatar = array(
					'host'=>$material['host'],
					'dir'=>$material['dir'],
					'filepath'=>$material['filepath'],
					'filename'=>$material['filename'],
				);
				$data['avatar'] = addslashes(serialize($avatar));
			}
		}
		$data['forced_change_pwd'] = $this->input['forced_change_pwd']; //是否强制修改密码
		$sql = 'UPDATE '.DB_PREFIX.'admin SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$sql = $sql.' WHERE id = '.$data['id'];
		$this->update_user_role($data['id'], $data['admin_role_id']);
		$this->db->query($sql);

		//写入日志系统
		$this->addLogs('更新用户', $admin_info, $data, $data['user_name'], $data['id']);
		$this->addItem($data);
		$this->output();
	}

	public function updateExtend($user_id = 0,$extendEditInfo = array(),$isRe = false)
	{
		include (CUR_CONF_PATH . 'lib/extendInfo.class.php');
		$extendInfo	 = new extendInfo();
		$user_id = $user_id?$user_id:intval($this->input['user_id']);
		$reData = false;
		if(!$isRe&&$this->checkUser($user_id))
		{
			$extendEditInfo = $extendEditInfo?$extendEditInfo:$this->input['extendInfo'];
			$reData = $extendInfo->extendEdit($user_id, $extendEditInfo);
		}
		if($isRe){
			return $reData;
		}
		$this->addItem($reData);
		$this->output();
	}
	public function checkUser($user_id)
	{
		$sql = 'SELECT id FROM '.DB_PREFIX.'admin WHERE id = '.intval($user_id);
		$user = $this->db->query_first($sql);
		return intval($user['id']);
	}
	public function uploadToPicServer($file,$content_id)
	{
		$material = $this->material->addMaterial($file,$content_id); //插入图片服务器
		return $material;
	}
	private function check_admin_type($admin_id, $role_id)
	{
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			return;
		}
		if($admin_id && ($admin_id == $this->user['user_id']))
		{
			$this->errorOutput("无法更改自身用户信息，联系管理员修改");
		}
		if($role_id)
		{
			$sql = 'SELECT user_id FROM '  . DB_PREFIX . 'admin_role WHERE id IN('.$role_id.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				if($row['user_id']!=$this->user['user_id'])
				{
					$this->errorOutput("所选角色非当前用户创建，无法授权！");
				}
			}
		}
	}
	public function delete()
	{
		$this->verify_content_prms(array('_action'=>'manage_user'));
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput(NOID);
			return ;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'admin WHERE id IN ('.$id.')';
		$query = $this->db->query($sql);
		$adminRoleIds = array();
		while ($row = $this->db->fetch_array($query))
		{
			$before_data[] = $row;
			$adminRoleIds[] = $row['admin_role_id'];
		}
		if (!empty($adminRoleIds))
		{
			$adminRoleIds = explode(',', implode(',', $adminRoleIds));
			//检测修改后的角色是否比自己大
			if ($this->user['group_type']>MAX_ADMIN_TYPE)
			{
				$temp = array_filter($adminRoleIds);
				if (!empty($temp))
				{
					if (min($temp)<=MAX_ADMIN_TYPE)
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
				}

			}else {
				$temp = array_filter($adminRoleIds);
				if (!empty($temp))
				{
					if (min($temp)<$this->user['group_type'])
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
				}
			}
			if (in_array(1, $adminRoleIds))
			{
				$sql = 'SELECT COUNT(*)AS total FROM '.DB_PREFIX.'admin WHERE id NOT IN ('.$id.') and admin_role_id = 1';
				$ret = $this->db->query_first($sql);
				if ($ret['total']<1)
				{
					$this->errorOutput('至少保留一个系统用户！');
				}
			}
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'admin WHERE id in('.$id.')';
		$this->db->query($sql);
		$ids = explode(',', $id);
		foreach ($ids as $v)
		{
			$this->update_user_role($v);
		}
		//$this->update_user_role($id);
		//写入日志系统
		$this->addLogs('删除用户', $before_data, '', $id, $id);
		$this->addItem('success');
		$this->output();
	}
	private function update_user_role($admin_user_id = 0, $role_id = '')
	{
		if($admin_user_id)
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'user_role WHERE admin_user_id = '.$admin_user_id;
			$this->db->query($sql);
		}
		if($role_id)
		{
			$role_id = is_array($role_id) ? $role_id  : explode(',', $role_id);
			$sql = 'INSERT INTO '.DB_PREFIX.'user_role VALUES ';
			foreach ($role_id as $i)
			{
				$sql .= '('.$admin_user_id.','.$i.','.TIMENOW.'),';
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
		}
	}
	public function create()
	{
		$this->verify_content_prms(array('_action'=>'manage_user'));
		if (!$this->check_unique())
		{
			$this->errorOutput('用户名须唯一');
		}
		if (!trim($this->input['user_name']))
		{
			$this->errorOutput('请填写用户名称');
		}
		if (!trim($this->input['password']))
		{
			$this->errorOutput('请填写密码');
		}
		$salt = hg_generate_salt();
		if ($this->input['md5once'])
		{
			$password = md5(trim($this->input['password']).$salt);
		}
		else
		{
			$password = md5(md5(trim($this->input['password'])).$salt);
		}
		$data = array(
            'create_time'=>TIMENOW,
            'update_time'=>TIMENOW,
            'user_name'=>trim($this->input['user_name']),
			'password'=>$password,
            'brief'=>trim(urldecode($this->input['brief'])),
            'user_name_add'=>trim(urldecode($this->user['user_name'])),
			'admin_role_id' => $this->input['admin_role_id'] ? $this->input['admin_role_id'] : "",
			'father_org_id' => intval($this->input['father_org_id']),
		//'cardid' => intval($this->input['cardid']),
			'domain'=>trim($this->input['domain']),
			'salt'=>$salt,
			'source'=>$this->input['source'],
			'org_id'=>$this->user['org_id'],
			'user_id'=>$this->user['user_id'],
			'forced_change_pwd' => $this->input['forced_change_pwd'], //是否强制修改密码
		);
		if (!$data['father_org_id'])
		{
			$this->errorOutput('用户必须属于一个组织');
		}
		if ($data['admin_role_id'] && is_array($data['admin_role_id']))
		{
			$data['admin_role_id'] = implode(',',$data['admin_role_id']);
		}
		if (!$data['admin_role_id'])
		{
			$this->errorOutput('用户必须属于一个角色');
		}
		$this->check_admin_type(0, $data['admin_role_id']);
		//检测修改后的角色是否比自己大
		if ($this->user['group_type']>MAX_ADMIN_TYPE)
		{
			$temp = array_filter(explode(',', $data['admin_role_id']));
			if (!empty($temp))
			{
				if (min($temp)<=MAX_ADMIN_TYPE)
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}

		}else {
			$temp = array_filter(explode(',', $data['admin_role_id']));
			if (!empty($temp))
			{
				if (min($temp)<$this->user['group_type'])
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}

		}

		if ($_FILES['Filedata'])
		{
			$material = $this->uploadToPicServer($_FILES, '');
			if ($material)
			{
				$avatar = array(
					'host'=>$material['host'],
					'dir'=>$material['dir'],
					'filepath'=>$material['filepath'],
					'filename'=>$material['filename'],
				);
				$data['avatar'] = addslashes(serialize($avatar));
			}
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'admin SET ';
		foreach($data as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$this->db->query(rtrim($sql, ','));

		//insert user_role  table
		$data['id'] = $this->db->insert_id();
		//是否需要绑定密保卡
		if(intval($this->input['cardid']))
		{
			$this->mibao->bind_card($data['id']);
		}
		$this->update_user_role($data['id'], $data['admin_role_id']);
		
		//写入日志系统
		$this->addLogs('创建用户', '', $data, $data['user_name'], $data['id']);
		$this->addItem($data);
		$this->output();
	}
	//其他应用创建和更新管理员接口
	function register()
	{
		$this->input['source'] = $this->input['app_uniqueid'] ? $this->input['app_uniqueid'] : '1';
		if($this->input['id'])
		{
			$this->update();
		}
		$this->create();
	}
	private function check_unique($num = 0, $field = 'user_name')
	{
		$this->input['id'] = $this->input['id']?$this->input['id']:0;
		$sql = 'SELECT count(*) AS total FROM ' . DB_PREFIX . 'admin WHERE ' . $field . '=\'' . $this->input[$field] . "'".'and id!='.$this->input['id'];
		$row = $this->db->query_first($sql);
		if ($row['total'] > $num)
		{
			return false;
		}
		return true;
	}
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	/**
	 *
	 * 修改自己的密码和头像 ...
	 */
	function update_password()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'admin WHERE id = '.$id;
		$admin_info = $this->db->query_first($sql);
		$password = '';
		$password = trim($this->input['password']);
		$data = array();
		if ($password)
		{
			$salt = '';
			$salt = hg_generate_salt();
			$password = md5(md5(trim($password)).$salt);
			$data = array(
				'password'=>$password,
				'salt'=>$salt,
				'update_time'=>TIMENOW,
			);
		}
		if ($_FILES['Filedata'])
		{
			$material = $this->uploadToPicServer($_FILES, intval($this->input['id']));
			if ($material)
			{
				$avatar = array(
					'host'=>$material['host'],
					'dir'=>$material['dir'],
					'filepath'=>$material['filepath'],
					'filename'=>$material['filename'],
				);
				$data['avatar'] = addslashes(serialize($avatar));
				$data['update_time'] = TIMENOW;
			}
		}
		if (!empty($data))
		{
			$sql = 'UPDATE '.DB_PREFIX.'admin SET ';
			foreach($data as $k=>$v)
			{
				$sql .= '`'.$k . '`="' . $v . '",';
			}
			$sql = rtrim($sql,',');
			$sql = $sql.' WHERE id = '.$this->user['user_id'];
			$this->db->query($sql);

			//写入日志系统
			$this->addLogs('更新用户', $admin_info, $data, $admin_info['user_name'], $admin_info['id']);
			$this->addItem($data);
		}
		$this->output();
	}
	/**
	 *
	 *  超级管理员通过用户id修改用户密码 ...
	 */
	public function updatePassword($userinfo = array())
	{
		$updateData = array();
		$reData = array();
		$salt = '';
		$password = '';
		$userid = 0;
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->errorOutput(NO_PURVIEW);//没有权限
		}
		$userinfo = $userinfo?$userinfo:array('userid'=>intval($this->input['userid']),'password'=>$this->input['password']);
		if($userinfo['userid']>0&&($userid = $this->checkUser($userinfo['userid'])))
		{
			if($userinfo['password'])
			{
				$salt = hg_generate_salt();
				$password = md5(md5(trim($userinfo['password'])).$salt);
				$updateData = array(
					'password'=>$password,
					'salt'=>$salt,
					'update_time'=>TIMENOW,
				);
			}else {
				$this->errorOutput(NO_PASSWORD);
			}
			if($updateData&&is_array($updateData)&&$userid>0){
				$this->db->update_data($updateData, 'admin','id = '.$userid);
				$reData = array(
				'userid' => $userid,
				'response' => '密码已经修改成功',
				);
			}

		}else{
			$this->errorOutput(NO_USER_ID);
		}
		foreach ($reData as $k => $v)
		$this->addItem_withkey($k, $v);
		$this->output();
	}

	//绑定密保卡(如果原来已经绑定就重新绑定)
	public function bind_card()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//查看原来有没有绑定密保卡，如果已经绑定了就删除原来的密保数据
		$sql = "SELECT cardid FROM " .DB_PREFIX. "admin WHERE id = '" .$id. "'";
		$user = $this->db->query_first($sql);
		//产生密保数据
		$secret = $this->create_card_data();
		$data = array(
			'zuobiao'=>serialize($secret),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW
		);
		$sql = " INSERT INTO ".DB_PREFIX."security_card SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$cardid = $this->db->insert_id();
		//执行绑定
		$sql = "UPDATE " .DB_PREFIX. "admin SET cardid = '" .$cardid. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		$ret = array(
			'secret' => $secret,
			'cardid' => $cardid,
		);
		//如果原来绑定了密保卡将原来的密保卡数据删除掉
		if($user['cardid'])
		{
			$sql = "DELETE FROM " .DB_PREFIX. "security_card WHERE id = '" .$user['cardid']. "'";
			$this->db->query($sql);
		}
		$this->addItem($ret);
		$this->output();
	}
	/**
	 *
	 * 强制用户退出,非管理员角色使用需要赋予权限应用的“强制退出”权限 ...
	 * @param user_id 强制退出用户ID
	 * @param is_member 是否会员用户
	 * 
	 */
	public function force_logout_user()
	{
		$this->verify_content_prms(array('_action'=>'force_logout_user'));
		$userId = (int)$this->input['user_id'];
		!($userId>0)&&$this->errorOutput(NO_USER_ID);
		$isMember = isset($this->input['is_member'])&&$this->input['is_member'] ? 1 : 0;
		$databasename = '';
		$servers = hg_load_login_serv();
		if (!$servers||!$isMember)
		{
			$newdb = hg_ConnectDB();
		}
		elseif ($servers&&$isMember)
		{
			$server_index = $userId % count($servers);
			$server = $servers[$server_index];
			if($server){
				class_exists('db',false) OR include ROOT_PATH . 'lib/db/db_mysql.class.php';
				$server['pass'] = hg_encript_str($server['pass'], false);
				$newdb = new db();
				$newdb->connect($server['host'], $server['user'], $server['pass'], $server['database'], $server['charset'], $server['pconnect']);
				$databasename = $server['database'] . '.';
			}
			else {
				 $newdb = hg_ConnectDB();
			}
		}
		$sql = 'DELETE FROM ' . $databasename . DB_PREFIX . 'user_login WHERE user_id = "'.$userId.'" AND is_member = '.$isMember;
		$redata = array(
		'status' => 0,
		'user_id' => $userId,
		'is_member' => $isMember,
		'copywriting' => 'UserID = '.$userId.'的TOKEN清除失败',
		);
		$query = $newdb->query($sql);
		if($query&&$newdb->affected_rows())
		{
			$redata['status'] = 1;
			$redata['copywriting'] = 'UserID = '.$userId.'的TOKEN清除成功';
		}
		elseif($query){
			$redata['copywriting'] = 'UserID = '.$userId.'的用户未登录';
		}
	 $this->addItem($redata);
	 $this->output();
	}

	function cancel_bind()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//取出已经绑定的密保卡id
		$sql = "SELECT cardid FROM " .DB_PREFIX. "admin WHERE id = '" .$id. "'";
		$card = $this->db->query_first($sql);
		//更新admin
		$sql = "UPDATE " .DB_PREFIX. "admin SET cardid = 0 WHERE id = '" .$id. "'";
		$this->db->query($sql);
		//删除原来绑定的密保卡
		if($card['cardid'])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "security_card WHERE id = '" .$card['cardid']. "'";
			$this->db->query($sql);
		}
		$ret = array('return' => 'success');
		$this->addItem($ret);
		$this->output();
	}

	//产生密保卡数据
	private function create_card_data()
	{
		/*产生密保随机数*/
		$secret = array();/*将密保数据以值对的形式保存*/
		for($i = 'A';$i<='H';$i++)
		{
			for($j = 1;$j<=8;$j++)
			{
				$num = rand(0,99);
				if($num < 10)
				{
					$num = '0'.$num;
				}
				$secret["$i$j"] = $num;
			}
		}
		return $secret;
	}
	public function update_user_org()
	{
		$user_id = $this->input['id'];
		$org_id = $this->input['org_id'];
		$sql = 'UPDATE ' . DB_PREFIX . 'admin set father_org_id = '.$org_id . ' WHERE id = '.$user_id;
		//file_put_contents(CACHE_DIR . 'debug.txt', $sql);
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');
?>