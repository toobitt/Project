<?php
/***************************************************************************
* $Id: member_update.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
require('global.php');
define('UC_CLIENT_PATH', '../');
class memberUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$member_name = trim($this->input['member_name']);
		$email = trim($this->input['email']);
		
		if (!$member_name)
		{
			$this->errorOutput('用户名不能为空');
		}
		
		if (!$email)
		{
			$this->errorOutput('邮箱不能为空');
		}
		
		$password = trim($this->input['password']);
		if (!$password)
		{
			$this->errorOutput('密码不能能为空');
		}
		
		if ($this->mMember->_check_member_name($member_name) == -1)
		{
			$this->errorOutput('用户名不合法');
		}
		else if($this->mMember->_check_member_name($member_name) == -2)
		{
			$this->errorOutput('用户名已被注册');
		}

		if ($this->mMember->_check_email($email) == -3)
		{
			$this->errorOutput('邮箱不合法');
		}
		else if($this->mMember->_check_email($email) == -4)
		{
			$this->errorOutput('邮箱已被注册');
		}
		
		$data = array(
			'member_name' 	=> $member_name,
			'password' 		=> $password,
			'email' 		=> $email,
			'node_id' 		=> intval($this->input['node_id']),
			'column_id' 	=> trim($this->input['column_id']),
		);
	
		if ($this->settings['ucenter']['open'])
		{
			$ret = $this->mMember->uc_user_register($member_name, $password, $email);

			switch ($ret)
			{
				case -1 :
					$this->errorOutput('用户名不合法');
					break;
				case -2 :
					$this->errorOutput('包含不允许注册的词语');
					break;
				case -3 :
					$this->errorOutput('用户名已经存在');
					break;
				case -4 :
					$this->errorOutput('Email 格式有误');
					break;
				case -5 :
					$this->errorOutput('Email 不允许注册');
					break;
				case -6 :
					$this->errorOutput('该 Email 已经被注册');
					break;
				default:
					break;
			}
			
			$uc_id = $ret;
		}
		
		$info = $this->mMember->create($data, $uc_id);
		
		if (!$info)
		{
			$this->errorOutput('添加失败');
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入会员ID');
		}
		
		$member_name = trim($this->input['member_name']);
		$old_member_name = trim($this->input['old_member_name']);
		
		$email = trim($this->input['email']);
		$old_email = trim($this->input['old_email']);
		
		if (!$member_name)
		{
			$this->errorOutput('用户名不能为空');
		}
		
		if (!$email)
		{
			$this->errorOutput('邮箱不能为空');
		}
		
		if ($member_name != $old_member_name)
		{
			if ($this->mMember->_check_member_name($member_name) == -1)
			{
				$this->errorOutput('用户名不合法');
			}
			else if($this->mMember->_check_member_name($member_name) == -2)
			{
				$this->errorOutput('用户名已被注册');
			}
		}
		
		if ($email != $old_email)
		{
			if ($this->mMember->_check_email($email) == -3)
			{
				$this->errorOutput('邮箱不合法');
			}
			else if($this->mMember->_check_email($email) == -4)
			{
				$this->errorOutput('邮箱已被注册');
			}
		}
		
		$data = array(
			'id' 				=> $id,
			'uc_id' 			=> intval($this->input['uc_id']),
			'member_name'		=> $member_name,
			'old_member_name' 	=> $old_member_name,
			'old_password' 		=> trim($this->input['old_password']),
			'email' 			=> $email,
			'password' 			=> trim($this->input['password']),
			'column_id' 		=> trim($this->input['column_id']),
		);
		
		$node_id = intval($this->input['node_id']);
		
		$info = $this->mMember->update($data, $node_id,'',$this->user['user_name']);
		if (!$info)
		{
			$this->errorOutput('更新失败');
		}
		$this->addItem($info);
		$this->output();
	}

	public function delete()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}

		$info = $this->mMember->delete($id);
		if (!$info)
		{
			$this->errorOutput('删除失败');
		}
		
		$this->addItem($id);
		$this->output();
	}

	public function audit()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$type = trim($this->input['type']);
		if (!$type)
		{
			$this->errorOutput('请传入要审核的字段');
		}
		
		$info = $this->mMember->audit($id, $type);
		$this->addItem($info);
		$this->output();
	}

	public function getMemberInfoById()
	{
		$member_id = intval($this->input['id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		
		$info = $this->mMember->getMemberInfoDetail($member_id);
		
		$width = $height = 100;
		
		if ($info[$member_id]['filename'])
		{
			$info[$member_id]['avatar_url'] = hg_material_link($info[$member_id]['host'], $info[$member_id]['dir'], $info[$member_id]['filepath'], $info[$member_id]['filename'], $width.'x'.$height.'/');
		}

		$this->addItem($info[$member_id]);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
		
	public function sort()
	{
		
	}
	
	/**
	 * 即时发布
	 * @param id  int   
	 * @param column_id string  发布的栏目id
	 */
	public function publish()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');
		}
		$ret = $this->mMember->publish($this->user['user_name']);
		if(empty($ret))
		{
			$this->errorOutput('发布失败');
		}
		else 
		{
			$this->addItem($ret);
			$this->output();
		}
	}

}

$out = new memberUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>