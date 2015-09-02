<?php
require('global.php');
define('MOD_UNIQUEID','share');
class userUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		
	}
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NO Id');
		}
		$sql = "SELECT * FROM ".DB_PREFIX."plat_user WHERE id = " . $id;
		$ori_info = $this->db->query_first($sql);
		$ori_roles = $ori_info['roles'];
		$roles = implode(',', $this->input['roles']); 
		$info = array(
			'roles'        => $roles,
		);
		$plat_user_affected = $this->db->update_data($info,'plat_user',"id = {$id}");
		$this->update_user_role($ori_roles, $roles, $id);
		if($plat_user_affected)
		{
			$data = array('update_time' => TIMENOW);
			$this->db->update_data($data,'plat_user', "id = {$id}");
			$this->addLogs('修改用户',$ori_info,$info,$info['name']);	
		}
		$this->addItem($info);
		$this->output();
	}
	public function delete()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput('NOID');
		}
		$sql = "DELETE FROM ".DB_PREFIX."plat_user WHERE id IN(".$ids.")";
		$this->db->query($sql);
		$sql = "DELETE FROM ".DB_PREFIX."user_role WHERE user_id IN(".$ids.")";
		$this->db->query($sql);
		$this->addLogs('删除文稿','','', '删除文稿+' . $ids);
		$this->addItem($ids);
		$this->output();
	}
	
	private function update_user_role($old_roleid,$new_roleid,$uid)
	{
		$old_id = array();
		$new_id = array();
		if(!empty($old_roleid))
		{
			$old_id = explode(',',$old_roleid);
		}
		if(!empty($new_roleid))
		{
			$new_id = explode(',',$new_roleid);
		}
		$del_id = array_diff($old_id,$new_id);
		$add_id = array_diff($new_id,$old_id);
		if(!empty($del_id))
		{
			$del_id = implode(',',$del_id);
			$sql = "DELETE FROM " . DB_PREFIX ."user_role WHERE user_id = " . $uid ." AND role_id IN(" . $del_id . ")";
			$this->db->query($sql);
		}
		if(!empty($add_id))
		{
			$sql = "INSERT INTO " . DB_PREFIX ."user_role (user_id, role_id) VALUES ";
			$space = '';
			foreach ($add_id as $k => $v)
			{
				$sql .= $space ."(" . $uid .", " . $v . ")";
				$space = ',';
			}
			$this->db->query($sql);				
		}
		return true;
	}
	public function audit(){}
	public function sort(){}
	public function publish(){}		
	public function unknow()
	{
		$this->errorOutput('此方法不存在!');
	}
}
$out = new userUpdateApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
