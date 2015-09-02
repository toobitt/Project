<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once('./global.php');
define('SCRIPT_NAME', 'AuthAdmin');
define('MOD_UNIQUEID','auth');
class AuthAdmin extends Auth_frm
{	
	function __construct()
	{
		//权限设置数据
		$this->mPrmsMethods = array(
		'force_logout_user'		=>'强制退出',
		'manage_user'	=>'用户管理',
		'auth_user'		=>'授权用户',
		'_node'=>array(
			'name'=>'组织管理',
			'filename'=>'admin_org.php',
			'node_uniqueid'=>'admin_org',
			),
		);
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
		
	}
	public function show()
	{
		//$this->errorOutput(var_export($this->user['prms'],1));
		$this->verify_content_prms(array('_action'=>'manage_user'));
		$admin_role = '';
		$order = ' ORDER BY t1.id DESC';
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		
		$sql = 'SELECT t1.* FROM ' . DB_PREFIX . 'admin t1 WHERE 1';
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $order . $limit;
		
		$q = $this->db->query($sql);
		$user = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] =date('Y-m-d h:i:s',$row['create_time']);
			$row['is_bind_card'] = $row['cardid'];
			//$row['cardid'] = $row['cardid']?'已绑定':'未绑定';
			$avatar = unserialize($row['avatar']);
			$row['avatar'] = $avatar ? $avatar : '';
			$user[] = $row;
			$admin_role .= $row['admin_role_id'] . ',';
		}
		
		$admin_role = array_unique(array_filter(explode(',', $admin_role)));
		if($admin_role)
		{
			$admin_role = implode(',',$admin_role);
			$sql = 'SELECT id,name FROM '.DB_PREFIX.'admin_role WHERE id IN('.$admin_role.')';
			$admin_role = array();
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$admin_role[$row['id']] = $row['name'];
			}
		}
		if($user)
		{
			foreach ($user as $v)
			{
				$admin_role_name = '';
				$admin_role_ids = $v['admin_role_id'] ? explode(',', $v['admin_role_id']) : '';
				if($admin_role_ids)
				{
					foreach($admin_role_ids as $id)
					{
						$admin_role_name .= $admin_role[$id].',';
					}
				}
				$v['name'] = trim($admin_role_name, ',');
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND t1.user_name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND t1.user_id = '.$this->user['user_id'];
			}
			else
			{
				//组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND t1.org_id IN('.$this->user['slave_org'].')';
				}
			}
			if($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str === '0')
				{
					$condition .= ' AND t1.father_org_id IN(' . $authnode_str . ')';
				}
				if($authnode_str)
				{
					$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
					$sql = 'SELECT id,childs FROM '.DB_PREFIX.'admin_org WHERE id IN('.$authnode_str.')';
					$query = $this->db->query($sql);
					$authnode_array = array();
					while($row = $this->db->fetch_array($query))
					{
						$authnode_array[$row['id']]= explode(',', $row['childs']);
					}
					$authnode_str = '';
					foreach ($authnode_array as $node_id=>$n)
					{
						if($node_id == intval($this->input['_id']))
						{
							$node_father_array = $n;
							if(!in_array(intval($this->input['_id']), $authnode))
							{
								continue;
							}
						}
						$authnode_str .= implode(',', $n) . ',';
					}
					$authnode_str = true ? $authnode_str . '0' : trim($authnode_str,',');
					if(!$this->input['_id'])
					{
						$condition .= ' AND t1.father_org_id IN(' . $authnode_str . ')';
					}
					else
					{
						$authnode_array = explode(',', $authnode_str);
						if(!in_array($this->input['_id'], $authnode_array))
						{
							//
							if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							//$this->errorOutput(var_export($auth_child_node_array,1));
							$condition .= ' AND t1.father_org_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
			}
		}
		if($this->input['id'])
		{
			$condition .= ' AND t1.id = '.intval($this->input['id']);
		}
		if($this->input['_id'])
		{
			$condition .= ' AND t1.father_org_id IN('.$this->input['_id'].')';
		}
		if($this->input['admin_role'] && $this->input['admin_role'] != -1)
		{
			$condition .= ' AND t1.admin_role_id = '.$this->input['admin_role'];	
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND t1.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND t1.create_time <= ".$end_time;
		}
		if($this->input['admin_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['admin_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  t1.create_time > ".$yesterday." AND t1.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  t1.create_time > ".$today." AND t1.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  t1.create_time > ".$last_threeday." AND t1.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND t1.create_time > ".$last_sevenday." AND t1.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function detail()
	{
		$this->verify_content_prms(array('_action'=>'manage_user'));
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$this->show();
	}


	public function count()
	{
		$this->verify_content_prms(array('_action'=>'manage_user'));
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'admin as t1 WHERE 1 '.$this->get_condition();
		//$this->errorOutput($sql);
		echo json_encode($this->db->query_first($sql));
	}
	//查找角色id,name
	public function append_admin_role()
	{
		$condition = '';
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//组织以内
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else
			{
				//组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND org_id IN('.$this->user['slave_org'].')';
				}
			}
		}
		$sql = "SELECT id,name FROM " . DB_PREFIX . "admin_role WHERE 1 ".$condition;
		$query = $this->db->query($sql);
		$return = array();
		while($j = $this->db->fetch_array($query))
		{
			$return[$j['id']] = $j['name'];	
		}
		if(!$return)
		{
			//$this->errorOutput("没有可用角色，无法新建用户");
		}
		$this->addItem($return);
		$this->output();
	}
	
	//获取密保信息
	public function get_mibao_info()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT a.cardid,s.zuobiao FROM " .DB_PREFIX. "admin a LEFT JOIN " .DB_PREFIX. "security_card s ON a.cardid = s.id WHERE a.id = '" .$this->input['id']. "'";
		$card = $this->db->query_first($sql);
		$card['zuobiao'] = unserialize($card['zuobiao']);
		$this->addItem($card);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>