<?php
require('./global.php');
define('MOD_UNIQUEID','road_user');
class UserUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/share.class.php');
		$this->share = new share();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function sort(){}
	public function publish(){}
	public function create()
	{
		if(!$this->input['name'])
		{
			$this->errorOutput('用户昵称不能为空');
		}
		if(!$this->input['group_id'])
		{
			$this->errorOutput('微博类型不能为空');			
		}
		$name = $this->input['name'];
		$group_id = intval($this->input['group_id']);
		$name = str_replace(array('，',',',' '),array(',',',',','),$name);
		$name = explode(',',$name);
		$nameValue = array_values($name);
		array_walk($nameValue,array($this,'add_quotes'));
		$valuestring = implode(',',$nameValue);
		$sql = "SELECT name FROM ".DB_PREFIX."user WHERE name IN(".$valuestring.") AND group_id = " . $group_id;	
		$q = $this->db->query($sql);
		$exists_name = array();
		while($row = $this->db->fetch_array($q))
		{
			$exists_name[] = $row['name'];
		}	
		$name = array_diff($name,$exists_name);
		
		$type = $this->share->get_plat_type();
		$type = $type[0];
		$group_name = $type[$group_id];
		
		$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE appid = " . intval($this->user['appid']) ." AND type = " . $group_id ." ORDER BY lastusetime ASC ";
		$plat_token = $this->db->query_first($sql);		
		
		foreach ($name as $k => $v)
		{	
			$sql = "INSERT INTO " . DB_PREFIX . "user(name,group_id,group_name,create_time,update_time,user_id,user_name) VALUES		
			('{$v}', ".$group_id.", '".$group_name."', ".TIMENOW.", ". TIMENOW.", ".intval($this->user['user_id']).", '".urldecode($this->user['user_name'])."')";
			$this->db->query($sql);
			$insert_id = $this->db->insert_id();
			$user_info = $this->share->get_user('',$v,$plat_token['plat_token']);
			if(!empty($user_info))
			{
				$uid = $user_info[0]['uid'];
				$avatar = array('host'=>$user_info[0]['avatar'],'dir' => '','filepath' =>'','filename' => '');
				$sql = "UPDATE " . DB_PREFIX ."user SET uid = '".$uid."',avatar = '" . serialize($avatar) . "' WHERE id = " . $insert_id;
				$this->db->query($sql);
			}
		}
		$this->addLogs('添加用户','','','添加用户+' . implode(',',$name));			
		$this->addItem('success');
		$this->output();	
	}
	
	
	public function update()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');	
		}
		if(empty($this->input['name']))
		{
			$this->errorOutput('用户昵称不能为空');
		}
		if(empty($this->input['group_id']))
		{
			$this->errorOutput('微博类型不能为空');			
		}	
		$info = array(
			'name'        => $this->input['name'],
			'group_id'    => intval($this->input['group_id']),
		);
		$type = $this->share->get_plat_type();
		$type = $type[0];
		$info['group_name'] = $type[$info['group_id']];
		
		$sql = "UPDATE " . DB_PREFIX . "user SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v ."'";
			$space = ',';
		}
		$sql .=  " WHERE id=" . intval($this->input['id']);
		$this->db->query($sql);
		if($this->db->affected_rows() > 0)
		{
			$update_info = array(
				'update_time'  => TIMENOW,
			);
			$sql = "UPDATE " . DB_PREFIX . "user SET ";
			$space = '';
			foreach($info as $k => $v)
			{
				$sql .= $space . $k . "='" . $v ."'";
				$space = ',';
			}
			$sql .=  " WHERE id=" . intval($this->input['id']);
			$this->db->query($sql);				
		}				
		$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE appid = " . intval($this->user['appid']) ."  AND type = " . intval($this->input['group_id']) . " ORDER BY lastusetime ASC ";
		$plat_token = $this->db->query_first($sql);
		$user_info = $this->share->get_user('',$info['name'],$plat_token['plat_token']);
		if(!empty($user_info))
		{
			$uid = $user_info[0]['uid'];
			$avatar = array('host'=>$user_info[0]['avatar'],'dir' => '','filepath' =>'','filename' => '');
			$sql = "UPDATE " . DB_PREFIX ."user SET  uid = '".$uid."',avatar = '" . addslashes(serialize($avatar)) . "' WHERE id = " . intval($this->input['id']);
			$this->db->query($sql);
		}	
		$info['id'] = intval($this->input['id']);
		$this->addLogs('修改用户','',$info, $info['name']);	
		$this->addItem($info);
		$this->output();
	}
		
	public function delete()
	{
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput('NOID');
		}
		$sql = "DELETE FROM " . DB_PREFIX ."user WHERE id IN(". $ids .")";
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX ."road WHERE uid IN(". $ids .")";
		$this->db->query($sql);
		//如果队列中有该用户，需删除队列中的该用户信息
		$sql = "DELETE FROM " .DB_PREFIX."queue_user WHERE id IN(".$ids.")";
		$this->db->query($sql);
		$this->addLogs('删除用户','','', '删除用户+' . $ids);	 
		$this->addItem($ids);
		$this->output();
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}	
		$ids = explode(',',urldecode($this->input['id']));
		//判断是审核还是打回
		if(intval($this->input['audit']) == 1)//审核 
		{
			$sql = "UPDATE ".DB_PREFIX."user  SET  status=1  WHERE id IN (".urldecode($this->input['id']).")";
	    	$this->db->query($sql);
	    	$opration = '审核';
			$return = array('id' => $ids,'status' => 1);
		}
		else if(intval($this->input['audit']) == 0)  //打回
		{
			$sql = "UPDATE ".DB_PREFIX."user  SET  status=2  WHERE id IN (".urldecode($this->input['id']).")";
	    	$this->db->query($sql);
	    	$opration = '打回';
			$return = array('id' => $ids,'status' => 2);
		}
		$this->addLogs($opration, '', '', $opration .'+'. $ids);	
		$this->addItem($return);
		$this->output();		
	}	

	public function search_user()
	{
		if(empty($this->input['key']))
		{
			$this->errorOutput('关键字不能为空');
		}
		if(empty($this->input['type_id']))
		{
			$this->errorOutput(NOPLATID);
		}
		$key = urldecode($this->input['key']);
		$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE appid = " . intval($this->user['appid']) ." AND type = " . intval($this->input['type_id']) . " ORDER BY lastusetime ASC ";
		$ret = $this->db->query_first($sql);
		$plat_token = $this->share->search_user($ret['appid'],$ret['platid'],$ret['plat_token'],$key);	
		if(!empty($plat_token))
		{
			if(!empty($plat_token['info']))
			{
				foreach ($plat_token['info'] as $kk => $vv)
				{
					$plat_token['info'][$kk]['platId'] = $plat_token['platId'];
					$plat_token['info'][$kk]['plat_type_name'] = $plat_token['plat_type_name'];
				}				
			}
		}	
		if(!empty($plat_token['info']))
		{
			foreach ($plat_token['info'] as $kk => $vv)
			{
				$this->addItem($vv);	
			}
		}
		$this->output();
	}
		
	public function user_put_queue()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('NOID');
		}
		$id = intval($this->input['id']);
		
		$sql = "SELECT * FROM " . DB_PREFIX ."user WHERE id = " . $id;
		$info = $this->db->query_first($sql);	
		if($info['status'] != 1)
		{
			$return = array('error' => 1, 'msg' => '账号未审核', 'id' => $id);
		}
		else
		{
			$info['last_time'] = '';
			$sql = "INSERT INTO ".DB_PREFIX."queue_user SET ";
			$space = "";
			foreach ($info as $k => $v)
			{
				$sql .= $space . $k . "= '".$v ."'";
				$space = ",";
			}
			$this->db->query($sql);		
			$return = array('msg' => '正在获取...', 'id' => $id);	
		}
		$this->addItem($return);
		$this->output();
	}
	
	

	public function wb_show_group()
	{
		$plat = $this->share->get_plat_type($this->user['appid']);
		$this->addItem($plat[0]);
		$this->output();
	}
	
	private function add_quotes(&$value,$key)
	{
		$q = "'";
		$value = $q.$value.$q;
		return $value;		
	}
	
	public function unknow()
	{
		$this->errorOutput('方法不存在');
	}
}

$out = new UserUpdate();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>