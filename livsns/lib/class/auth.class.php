<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: auth.class.php 45919 2015-05-26 07:40:22Z sign $
 ***************************************************************************/
class auth
{
	var $mAppid;
	function __construct($appid = '')
	{
		global $gGlobalConfig;
		$this->mAppid = $appid;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_auth'])
		{
			$this->curl = new curl($gGlobalConfig['App_auth']['host'], $gGlobalConfig['App_auth']['dir']);
		}
	}

	function __destruct()
	{
	}

	function setCurl()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_auth']['host'], $gGlobalConfig['App_auth']['dir']);
	}

	public function rebuild()
	{
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'rebuild');
		$this->curl->addRequestData('appid', $this->mAppid);
		return $this->curl->request('auth.php');
	}

	public function get_admin_role()
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		return $this->curl->request('admin_role.php');
	}

	//取角色列表
	public function get_role_list($offset = 0,$count = -1)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('admin/admin_role.php');
	}

	public function get_auth_list($offset,$count)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('admin/auth.php');
	}

	public function get_auth_detail($appid)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'other_detail');
		$this->curl->addRequestData('app_id', $appid);
		$ret = $this->curl->request('admin/auth.php');
		return $ret[0];
	}

	public function getMemberById($id)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getMemberById');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('member.php');
		return $ret[0];
	}

	public function getMemberByName($name)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getMemberByName');
		$this->curl->addRequestData('name', $name);
		$ret = $this->curl->request('member.php');
		return $ret[0];
	}

	public function getMemberByOrg($id = 0)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getMemberByOrg');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('member.php');
		return $ret[0];
	}

	public function getAccessToken($appid, $appkey)
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid', $appid);
		$this->curl->addRequestData('appkey', $appkey);
		$this->curl->addRequestData('a', 'get_user_info');
		$result = $this->curl->request('get_access_token.php');
		return $result[0];
	}
	
	public function login($data)
	{
		$this->setCurl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('a', 'show');
		if (empty($data))
		{
			return array();
		}
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('get_access_token.php');
		return $ret[0];
	}
	
	public function logout($data)
	{
		$this->setCurl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('a', 'logout');
		if (empty($data))
		{
			return array();
		}
		if ($data['access_token'])
		{
			$params['access_token'] = $data['access_token'];
		}
		else{
			return array();
		}
		foreach ($params AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('get_access_token.php');
		return $ret[0];
	}

	public function get_app($fields='',$app_uniqueid='',$id='',$offset=0,$count=1000,$data=array())
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('app_uniqueid', $app_uniqueid);
		$this->curl->addRequestData('use_' . APP_UNIQUEID, 1);
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('fields', $fields);
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('a', 'show');
		if($data)
		{
			foreach($data as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$result = $this->curl->request('applications.php');
		return $result;
	}

	public function get_module($fields='',$application_id='',$app_uniqueid='',$id='',$offset=0,$count=1000,$data=array() )
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('application_id', $application_id);
		$this->curl->addRequestData('app_uniqueid', $app_uniqueid);
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('fields', $fields);
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('a', 'show');
		if($data)
		{
			foreach($data as $k=>$v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$result = $this->curl->request('modules.php');
		return $result;
	}

	public function get_role($id)
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'detail');
		$result = $this->curl->request('admin/admin_role.php');
		return $result[0];
	}

	public function get_auth_info($data = array())
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_auth_info');
		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$result = $this->curl->request('get_app_info.php');
		return $result[0];
	}

	public function get_auth_count()
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_auth_count');
		$result = $this->curl->request('get_app_info.php');
		return $result[0];
	}

	public function get_org($fid)
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('fid', $fid);
		$this->curl->addRequestData('a', 'show');
		$ret = $this->curl->request('admin/admin_org.php');
		return $ret;
	}

	public function get_one_org($id)
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'detail');
		$ret = $this->curl->request('admin/admin_org.php');
		return $ret;
	}

	public function create_org($data)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addRequestData('a', 'create');
		$ret = $this->curl->request('admin/admin_org_update.php');
		return $ret[0];
	}

	public function update_org($data)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addRequestData('a', 'update');
		$ret = $this->curl->request('admin/admin_org_update.php');
		return $ret[0];
	}

	public function delete_org($id)
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'delete');
		$ret = $this->curl->request('admin/admin_org_update.php');
		return $ret[0];
	}

	public function create_user($data)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addFile($_FILES);
		$this->curl->addRequestData('a', 'create');
		$ret = $this->curl->request('admin/admin_update.php');
		return $ret[0];
	}

	public function updateExtend($user_id,$extendInfo)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if(empty($user_id)||empty($extendInfo)||!is_array($extendInfo))
		{
			return false;
		}
		$this->curl->addRequestData('user_id', $user_id);
		foreach ($extendInfo as $k => $v)
		{
			if(is_array($v)){
				$this->array_to_add($k, $v);
			}else {
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addRequestData('a', 'updateExtend');
		$ret = $this->curl->request('admin/admin_update.php');
		return $ret[0];
	}
	
	public function force_logout_user($user_id,$isMember = 1)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->setErrorOut(false);
		if(empty($user_id))
		{
			return array();
		}
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('is_member', $isMember);
		$this->curl->addRequestData('a', 'force_logout_user');
		$ret = $this->curl->request('admin/admin_update.php');
		return $ret[0];
	}

	public function update_user($data)
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addFile($_FILES);
		$this->curl->addRequestData('a', 'update');
		$ret = $this->curl->request('admin/admin_update.php');
		return $ret[0];
	}

	public function modify_password($data, $file = array())
	{
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		if ($file && is_array($file))
		{
		    $this->curl->addFile($file);
		}
		$this->curl->addRequestData('a', 'update_password');
		$ret = $this->curl->request('admin/admin_update.php');
		return $ret[0];
	}

	public function delete_user($id)
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'delete');
		$ret = $this->curl->request('admin/admin_update.php');
		return $ret[0];
	}

	public function getUserOrg()
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getUserOrg');
		$ret = $this->curl->request('member.php');
		return $ret;
	}

	public function getAllUser()
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getAllUser');
		$ret = $this->curl->request('member.php');
		return $ret;
	}

	public function CheckUserName($params,$errortype = 1)
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if(is_array($params))
		foreach ($params as $k => $v)
		{
			if(is_array($v)){
				$this->array_to_add($k, $v);
			}else {
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addRequestData('errortype', $errortype);
		$this->curl->addRequestData('a', 'check_username_existed');
		$result = $this->curl->request('get_access_token.php');
		//var_dump($result);die;
		return $result;
	}

	public function array_to_add($str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
	}
	public function getUserExtendInfo($access_token='')
	{
		$this->setCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid', $appid);
		$this->curl->addRequestData('appkey', $appkey);
		$this->curl->addRequestData('isextend', 1);
		$this->curl->addRequestData('access_token', $access_token);
		$this->curl->addRequestData('a', 'get_user_info');
		$result = $this->curl->request('get_access_token.php');
		return $result[0];
	}
	
	/**
	 *
	 * @Description  系统用户更新
	 * @author Kin
	 * @date 2013-7-3 上午09:26:46
	 */
	public function auth_update($id, $name, $password, $role, $org, $avatar='',$cardid='')
	{
		if (!$id || !$name  || !$role || !$org)
		{
			return false;
		}
		$this->setCurl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','register');
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('id',$id);
		$this->curl->addRequestData('user_name',$name);
		$this->curl->addRequestData('password',$password);
		$this->curl->addRequestData('admin_role_id',$role);
		$this->curl->addRequestData('father_org_id',$org);
		$this->curl->addRequestData('cardid',$cardid);
		if ($avatar)
		{
			$this->curl->addFile($avatar);
		}
		$ret = $this->curl->request('admin/admin_update.php');
		$ret = $ret[0];
		return $ret;
	}
	
	/**
	 *
	 * @Description 系统用户删除
	 * @author Kin
	 * @date 2013-7-3 上午09:31:21
	 */
	public function auth_delete($id)
	{
		if (!$id)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('id',$id);
		$ret = $this->curl->request('admin/admin_update.php');
		$ret = $ret[0];
		return $ret;
	}
	
	/**
	 *
	 * @Description 系统用户注册
	 * @author Kin
	 * @date 2013-7-2 下午04:29:54
	 */
	public function auth_register($name, $password, $role, $org, $avatar='',$cardid)
	{
		if (!$name || !$password || !$role || !$org)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','register');
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('user_name',$name);
		$this->curl->addRequestData('password',$password);
		$this->curl->addRequestData('admin_role_id',$role);
		$this->curl->addRequestData('father_org_id',$org);
		$this->curl->addRequestData('cardid',$cardid);
		if ($avatar)
		{
			$this->curl->addFile($avatar);
		}
		$ret = $this->curl->request('admin/admin_update.php');
		$ret = $ret[0];
		return $ret;
	}
	
}
?>