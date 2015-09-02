<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once('./global.php');
define('SCRIPT_NAME', 'OrgAdminUpdate');
define('MOD_UNIQUEID','org_admin');
class OrgAdminUpdate extends Auth_frm
{	
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function sort(){}
	public function audit(){}
	public function publish(){}
	public function delete(){}	
	//创建用户
	public function create()
	{
		if (!$this->check_unique())
		{
			$this->errorReturn('用户名须唯一');
		}
		if (!trim($this->input['user_name']))
		{
			$this->errorReturn('请填写用户名称');
		}
		if (!trim($this->input['password']))
		{
			$this->errorReturn('请填写密码');
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
			'admin_role_id' => $this->input['admin_role_id'] ? implode(',',$this->input['admin_role_id']) : "",
			'father_org_id' => intval($this->input['father_org_id']),
        	'cardid' => intval($this->input['cardid']),
			'domain'=>trim($this->input['domain']),
			'salt'=>$salt,
		);
		if (!$data['father_org_id'])
		{
			$this->errorReturn('用户必须属于一个组织');
		}
		if (!$data['admin_role_id'])
		{
			$this->errorReturn('用户必须属于一个角色');
		}
		//检测修改后的角色是否比自己大
		if ($this->user['group_type']>MAX_ADMIN_TYPE)
		{
			$temp = array_filter(explode(',', $data['admin_role_id']));
			if (!empty($temp))
			{
				if (min($temp)<=MAX_ADMIN_TYPE)
				{
					$this->errorReturn('没权限');
				}
			}
			
		}
		else
		{
			$temp = array_filter(explode(',', $data['admin_role_id']));
			if (!empty($temp))
			{
				if (min($temp)<$this->user['group_type'])
				{
					$this->errorReturn('没权限');
				}
			}
			
		}
		
		$material = $this->input['avatar'];
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
		
        $sql = "INSERT INTO ".DB_PREFIX."admin SET ";
		foreach($data as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$this->db->query(rtrim($sql, ','));
		$data['id'] = $this->db->insert_id();
		
		$data['avatar'] = $avatar ? $avatar : '';
		
		$this->addItem($data);
		$this->output();
	}
	public function update()
	{
		if (!intval($this->input['id']))
		{
			$this->errorReturn('id不存在');
		}
		if (!trim($this->input['user_name']))
		{
			$this->errorReturn('请填写用户名称');
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'admin WHERE id = '.intval($this->input['id']);
		$admin_info = $this->db->query_first($sql);
		if(!$admin_info)
		{
			$this->errorReturn('用户信息不存在！');
		}
		$password = '';
		$password = trim($this->input['password']);
		if(empty($password))
		{
			$data = array(
				'id' => intval($this->input['id']),
	        	'admin_role_id' => $this->input['admin_role_id'] ? implode(',',$this->input['admin_role_id']) : "",
	        	'father_org_id' => intval($this->input['father_org_id']),
	        	//'cardid' => intval($this->input['cardid']),
				'user_name' => trim(urldecode($this->input['user_name'])),
				//'brief' => trim(urldecode($this->input['brief'])),
				'update_time' =>TIMENOW,
				//'domain'=>trim($this->input['domain']),
			);			
		}
		else
		{
			$salt = '';
			$salt = hg_generate_salt();
			$password = md5(md5(trim($this->input['password'])).$salt);
			$data = array(
				'id' => intval($this->input['id']),
				'admin_role_id' => $this->input['admin_role_id'] ? implode(',',$this->input['admin_role_id']) : "",
				'father_org_id' => intval($this->input['father_org_id']),
	        	//'cardid' => intval($this->input['cardid']),
				'user_name' => trim(urldecode($this->input['user_name'])),
				//'brief' => trim(urldecode($this->input['brief'])),
				'update_time' =>TIMENOW,
				'password'=>$password ,
				'salt'=>$salt,
			);
		}
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
					$this->errorReturn('没有权限');
				}
			}
		}
		else
		{
			$temp1 = array_diff(explode(',', $data['admin_role_id']), explode(',', $admin_info['admin_role_id']));
			$temp2 = array_diff(explode(',', $admin_info['admin_role_id']),explode(',', $data['admin_role_id']));
			$temp = array_filter(array_merge($temp1,$temp2));
			if (!empty($temp))
			{
				if (min($temp)<$this->user['group_type'])
				{
					$this->errorReturn('没有权限');
				}
			}
		}
	
		$material = $this->input['avatar'];
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
		
		$sql = "UPDATE ".DB_PREFIX."admin SET ";
		foreach($data as $k=>$v)
		{
			$sql .= "`".$k . "`='" . $v . "',";
		}
        $sql = rtrim($sql,',');
		$sql = $sql." WHERE id = ".$this->input['id'];
		
		$this->db->query($sql);
		
		$data['avatar'] = $avatar ? $avatar : '';
		
		$this->addItem($data);
		$this->output();
	}
	
	//创建用户头像
	public function create_avatar()
	{
		if ($_FILES['Filedata'])
		{
			$id = intval($this->input['id']);
			$cid = $id ? $id : '';
			$material = $this->uploadToPicServer($_FILES, $cid);
			if ($material)
			{
				$avatar = array(
					'host'=>$material['host'],
					'dir'=>$material['dir'],
					'filepath'=>$material['filepath'],
					'filename'=>$material['filename'],
				);
			}
			$this->addItem($avatar);
			$this->output();
		}
	}
	
	//更新用户组织
	public function update_admin_org()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorReturn('id不存在');
		}
		
		if (!intval($this->input['father_org_id']))
		{
			$this->errorReturn('组织id不存在');
		}
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'admin WHERE id = ' . $id;
		$admin_info = $this->db->query_first($sql);
		if(!$admin_info)
		{
			$this->errorReturn('用户信息不存在！');
		}
		
		$data = array(
	        	'father_org_id' => intval($this->input['father_org_id']),
				'update_time' =>TIMENOW,
		);			
		
		$sql = 'UPDATE '.DB_PREFIX.'admin SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
        $sql = rtrim($sql,',');
		$sql = $sql.' WHERE id = ' . $id;
		$this->db->query($sql);
		
		$this->addItem($data);
		$this->output();
	}
	
	//上传头像
	public function uploadToPicServer($file,$content_id)
	{
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$material_obj = new material();
		$material = $material_obj->addMaterial($file,$content_id); //插入图片服务器
		return $material;
	}
	
	//验证用户名唯一
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
	function errorReturn($error)
	{
		$data['error'] = $error;
		echo json_encode($data);
		exit();
	}
	
	
}
include(ROOT_PATH . 'excute.php');
?>