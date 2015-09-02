<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','interview_user_group');//模块标识
class interview_user_group_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	function delete()
	{
		if (!$this->input['id'])
		{
			return ;
		}
		if($this->input['num']>0)
		{
			$this->errorOutput('该用户组下还存在用户，请先删除该用户组下的用户');
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'user_group WHERE id IN('.urldecode($this->input['id']).')';
		if ($this->db->query($sql))
		{
			$this->addItem(urldecode($this->input['id']));
		}else {
		
			$this->addItem('error');
		}

		$this->output();
	}

	function create()
	{

		if (!$this->input['group_name'])
		{
			$this->errorOutput('请填写用户组名称');
			return ;
		}
		$g_sql = 'SELECT group_name FROM '.DB_PREFIX.'user_group';
		$res = $this->db->query($g_sql);
		$group_name = trim($this->input['group_name']);
		while ($r = $this->db->fetch_array($res))
		{
			if (in_array($group_name, $r))
			{
				$this->errorOutput('用户组已存在！');
			}
		}
		//参数接收
		$data = array(
			'group_name'=>$group_name,
		    'description'=>trim(urldecode($this->input['description'])),
			'role'=>trim(urldecode($this->input['role'])),
			'create_time'=>TIMENOW,
			'update_time'=>TIMENOW,
			'user_number'=>0,
		);
		
		//数据库插入
		$sql = 'INSERT INTO '.DB_PREFIX.'user_group SET group_name = "'.addslashes($data['group_name']).
		'",description = "'.addslashes($data['description']).
		'",role = "'.$data['role'].
		'",user_number = "'.$data['user_number'].
		'",create_time = "'.$data['create_time'].
		'",update_time = "'.$data['update_time'].'"';
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		//更新order_id，默认与ID值相同
		$u_sql = 'UPDATE '.DB_PREFIX.'user_group SET order_id = '.$data['id'].' WHERE id ='.$data['id'];
		$this->db->query($u_sql);
		$this->addItem('sucess');
		$this->output();
	}
	
	function update()
	{
		if (!$this->input['group_name'])
		{
			$this->errorOutput('请填写用户组名称');
			return ;
		}
		//参数预处理
		$group_name = trim(urldecode($this->input['group_name']));
		//参数接收
		$data = array(
			'group_name'=>$group_name,
		    'description'=>trim($this->input['description']),
			'role'=>intval($this->input['role']),
			'update_time'=>TIMENOW,
		);
		//数据库更新
		$sql = 'UPDATE '.DB_PREFIX.'user_group SET group_name = "'.addslashes($data['group_name']).
		'",description = "'.addslashes($data['description']).
		'",role = '.$data['role'].
		',update_time = "'.$data['update_time'].'"
		WHERE id = '.urldecode($this->input['id']);
		$this->db->query($sql);
		$this->addItem('sucess');
		$this->output();
		
	}

	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}

     function audit()
     {
     
     }
     function sort()
     {
   
     }
     
     function publish()
     {
     	
     }





}
$ouput= new interview_user_group_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();