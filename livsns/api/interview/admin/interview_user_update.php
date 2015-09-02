<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require(CUR_CONF_PATH."lib/interview_admin.class.php");
define('MOD_UNIQUEID','interview_user');//模块标识
class interview_user_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->obj = new interview_admin();
	}
	function __destruct()
	{
		parent::__destruct();
	}

	function delete()
	{
		if (!$this->input['id']){
			$this->errorOutput(NOID);
		}
		$arr = explode(',', urldecode($this->input['id']));
		foreach ($arr as $key=>$val)
		{
			//获取访谈ID和用户ID
			$sql = 'SELECT interview_id,user_id,`group` FROM '.DB_PREFIX.'interview_user WHERE id='.$val;
			$view = $this->db->query_first($sql);
			$interview_id = $view['interview_id'];
			$user_id = $view['user_id'];
			$user_info = $this->obj->get_userInfo($user_id);
			$user_name = $user_info['member_name'];
			$group = $view['group']; 
			//删除的是否是主持人或者嘉宾
			$this->obj->change($interview_id, $user_id,0);
			//此处已封装，备用
			/*
			$sql = 'SELECT role FROM '.DB_PREFIX.'user_group WHERE id='.$group;
			$res = $this->db->query_first($sql);
			if ($res['role'] ==2)
			{
				$sql = 'SELECT moderator FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
				$m = $this->db->query_first($sql);
				$moder = unserialize($m['moderator']);
				$u_arr = array($user_name);
				$moder =  array_diff($moder, $u_arr);
				$new_moderator = addslashes(serialize($moder));
				$sql = 'UPDATE '.DB_PREFIX.'interview SET moderator ="'.$new_moderator.'" WHERE id='.$interview_id;
				
				$this->db->query($sql);		 
			}elseif ($res['role'] ==3){
				$sql = 'SELECT honor_guests FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
				$h = $this->db->query_first($sql);
				$honor = unserialize($h['honor_guests']);
				$u_arr = array($user_name);
				$honor = array_diff($honor,$u_arr);
				$new_honor = addslashes(serialize($honor));
				$sql = 'UPDATE '.DB_PREFIX.'interview SET honor_guests ="'.$new_honor.'" WHERE id='.$interview_id;
				$this->db->query($sql);		 	
			}
			*/
			//更新用户组下人数,用户组下人数减1
			$sql = 'SELECT `group` FROM '.DB_PREFIX.'interview_user WHERE id ='.$val;
			$role = $this->db->query_first($sql);
			$this->obj->reduce_user_num($role['group']);
		
		
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'interview_user WHERE id IN ('.urldecode($this->input['id']).')';	
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	/**
	 * 改变用户组
	 */
	function change_group()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		//获取访谈ID和用户ID
		$sql = 'SELECT interview_id,user_id,`group` FROM '.DB_PREFIX.'interview_user WHERE id='.urldecode($this->input['id']);
		$view = $this->db->query_first($sql);
		$interview_id = $view['interview_id'];
		$user_id = $view['user_id'];
		$user_info = $this->obj->get_userInfo($user_id);
		$user_name = $user_info['nick_name'];
		$old_group = $view['group']; 
		
		//判断更新的是否的主持人或者嘉宾，如果是则更新主持人或者嘉宾信息
		$this->obj->change($interview_id, $user_id,0);
		//此处已封装，备用
		/*
		$sql = 'SELECT role FROM '.DB_PREFIX.'user_group WHERE id='.$old_group;
		$res = $this->db->query_first($sql);
		if ($res['role'] ==2)
		{
			$sql = 'SELECT moderator FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
			$m = $this->db->query_first($sql);
			$moder = unserialize($m['moderator']);
			$u_arr = array($user_name);
			$moder =  array_diff($moder, $u_arr);
			$new_moderator = addslashes(serialize($moder));
			$sql = 'UPDATE '.DB_PREFIX.'interview SET moderator ="'.$new_moderator.'" WHERE id='.$interview_id;
			$this->db->query($sql);		 
		}elseif ($res['role'] ==3){
			$sql = 'SELECT honor_guests FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
			$h = $this->db->query_first($sql);
			$honor = unserialize($h['honor_guests']);
			$u_arr = array($user_name);
			$honor = array_diff($honor,$u_arr);
			$new_honor = addslashes(serialize($honor));
			$sql = 'UPDATE '.DB_PREFIX.'interview SET honor_guests ="'.$new_honor.'" WHERE id='.$interview_id;
			$this->db->query($sql);		 	
			
		}
		*/
		//更新用户组下人数,原用户组下人数减1
		$sql = 'SELECT `group` FROM '.DB_PREFIX.'interview_user WHERE id ='.urldecode($this->input['id']);
		$role = $this->db->query_first($sql);
		$this->obj->reduce_user_num($role['group']);
		
		//更新用户组
		$sql = 'UPDATE '.DB_PREFIX.'interview_user SET `group` = '.urldecode($this->input['role']).	
		' WHERE id = '.urldecode($this->input['id']);
		$this->db->query($sql);
		
		//获取更新后的组的角色
		$sql = 'SELECT role FROM '.DB_PREFIX.'user_group WHERE id ='.urldecode($this->input['role']);
		$auth = $this->db->query_first($sql);
		
		//更新用户组下人数,更新后用户组下人数加1
		$this->obj->add_user_num(urldecode($this->input['role']));
		
		//判断更新的是否的主持人或者嘉宾，如果是则更新主持人或者嘉宾信息
		$this->obj->change($interview_id, $user_id,1);
		
		//此处已封装备用
		/*
		$sql = 'SELECT role FROM '.DB_PREFIX.'user_group WHERE id='.urldecode($this->input['role']);
		$res = $this->db->query_first($sql);
		if ($res['role'] ==2)
		{
			$sql = 'SELECT moderator FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
			$m = $this->db->query_first($sql);
			$moder  = unserialize($m['moderator']);
			$moder[$user_id] = $user_name;
			$new_moderator = addslashes(serialize($moder));
			$sql = 'UPDATE '.DB_PREFIX.'interview SET moderator ="'.$new_moderator.'" WHERE id='.$interview_id;
			$this->db->query($sql);		 
		}elseif ($res['role'] ==3){
			$sql = 'SELECT honor_guests FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
			$h = $this->db->query_first($sql);
			$honor  = unserialize($h['honor_guests']);
			$honor[$user_id] = $user_name;
			$new_honor = addslashes(serialize($honor));
			$sql = 'UPDATE '.DB_PREFIX.'interview SET honor_guests ="'.$new_honor.'" WHERE id='.$interview_id;
			file_put_contents('aa.txt', $sql);
			$this->db->query($sql);		 	
			
		}
		*/
		$authority = $this->settings['roles'][$auth['role']];
		$arr = array();
		$arr['id'] = urldecode($this->input['id']);
		$arr['auth'] = $authority;
		
		$this->addItem($arr);
		$this->output();
	}
	function addUser()
	{
		//参数接受
		$data = array(
			'user_id'=>urldecode($this->input['id']),
			'interview_id'=>intval(urldecode($this->input['interview_id']))
		);		
		$user_id = explode(',', $data['user_id']);
		//获取分组信息
		$group = $this->obj->group_name();
		foreach ($user_id as $v)
		{
			$res = $this->obj->addInterviewUser($v, $data['interview_id']);
			//返回参数
			$arr =array();
			if ($res)
			{
				$info = $this->obj->get_userInfo($v);
				$arr['id'] = $res;
				$arr['avatar'] = $info['avatar']? $info['avatar'] : '';
				$arr['name'] = $info['nick_name'];
				$arr['group_name'] = $group;
				$k[] = $arr;
			}
			
		}
		
		$this->addItem($k);
		$this->output();
	}
	public function create()
	{
	
	}
	
	public function update()
	{
	
	}
	
	public function audit()
	{
	
	}
	
	public function sort()
	{
	
	}
	
	public function publish()
	{
	
	}
}

$ouput= new interview_user_update();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
