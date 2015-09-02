<?php
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','auth');
require(ROOT_DIR . 'global.php');
define('SCRIPT_NAME', 'get_app_info');
class get_app_info extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function count()
	{
		
	}
	public function detail()
	{
		
	}
	public function show()
	{
		//$this->input['appid'] = 21;
		$app_en = urldecode($this->input['app_en']);
		if(!$app_en)
		{
			$this->erroroutput(NO_APPID);
		}
		
		$sql = 'SELECT a.app_en,a.app_name,a.is_auth as a_auth,m.module_id,m.module_name,m.module_en,m.is_auth as m_auth,o.op_id,o.op_name,o.op_en,o.is_auth as o_auth FROM '.DB_PREFIX.'app_privilege a 
				LEFT JOIN '.DB_PREFIX.'module_privilege m 
				ON a.app_id=m.module_app_id 
				LEFT JOIN '.DB_PREFIX.'op_privilege o 
				ON m.module_id =o.op_module_id 
				WHERE a.app_id='.$this->input['appid'] .' AND m.is_auth=1';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
		}
		$this->addItem($arr);
		$this->output();
	}
	//获取应用,模块标识
	public function get_app_en()
	{
		$mod_en = urldecode($this->input['mod_en']);
		if(!$mod_en)
		{
			$this->erroroutput('没有模块标识');
		}
		$sql = 'SELECT app_en FROM '.DB_PREFIX.'module_privilege 
				WHERE module_en='."'".$mod_en."'".' 
				LIMIT 0,1';
		$this->addItem($this->db->query_first($sql));
		$this->output();
	}
	//获取应用下面模块信息
	public function get_mod_info()
	{
		$sql = 'SELECT * from '.DB_PREFIX.'module_privilege WHERE 1 ';
		$app_en = urldecode($this->input['app_en']);
		if($app_en)
		{
			$sql .= ' AND app_en='."'".$app_en."'";
		}
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
		}
		$this->addItem($arr);
		$this->output();
		
	}
	//获取应用信息
	public function get_app_info()
	{
		$sql = 'SELECT * from '.DB_PREFIX.'app_privilege WHERE 1 ';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
		}
		$this->addItem($arr);
		$this->output();
	}
	
	//获取apps表和modules表,use_xxx字段开启后的app_uniqueid和mod_uniqueid信息
	public function get_use_info()
	{
		$app_use = urldecode($this->input['use_app']);
		if(!$app_use)
		{
			$this->erroroutput('请传需要获取字段,如:use_catalog,仅传catalog即可');
		}
		$app='a.id as aid,a.name as app_name,a.bundle as app_uniqueid,a.host,a.port,a.dir,a.admin_dir';
		$mod='m.id as mid,m.name as mod_name,m.mod_uniqueid';
		$sql = 'SELECT '.$app.','.$mod.' FROM '.DB_PREFIX.'apps a 
				LEFT JOIN '.DB_PREFIX.'modules m 
				ON a.bundle=m.app_uniqueid 
				WHERE 1 AND a.use_'.$app_use.'=1';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);
		}
		
		$this->output();
	}
	
	//获取模块下面操作
	public function get_op_privilege()
	{
		$mod_en = urldecode($this->input['mod_en']);
		$app_en = urldecode($this->input['app_en']);
		if(!$mod_en)
		{
			$this->erroroutput('没有模块标识');
		}
		$sql = 'SELECT * from '.DB_PREFIX.'op_privilege WHERE module_en='."'".$mod_en."'".' AND app_en='."'".$app_en."'";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
		}
		//file_put_contents('test.txt',var_export($arr,1));
		return $arr;
	}
	//获取模块下面拥有的操作
	public function get_op()
	{
		//$mod_en = urldecode($this->input['mod_en']);
		$app_en = urldecode($this->input['app_en']);
		if(!$app_en)
		{
			$this->erroroutput('没有应用标识');
		}
		/*$sql = 'SELECT * from '.DB_PREFIX.'op_privilege WHERE module_en='."'".$mod_en."'".' AND app_en='."'".$app_en."'";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
		}*/
		//查找模块下操作
		$arr = $this->get_op_privilege();

		$id = intval($this->input['id']);//用户id
		$type = urldecode($this->input['type']);//标识用户组还是用户
		if($id)
		{
			if($type == 'group')
			{
				//查找用户组操作权限
				$sql = 'SELECT app_en,module_en,op_en FROM '.DB_PREFIX.'auth_group WHERE 1 AND  auth_type!="nod" AND group_id='.$id.' AND app_en='."'".$app_en."'";
			}
			else
			{
				//查找用户操作权限(继承用户组权限)
				$gid = intval($this->input['gid']);//用户组id
				$sql =	'SELECT app_en,module_en,op_en,group_id FROM '.DB_PREFIX.'auth_group WHERE 1 AND auth_type!="nod" AND group_id='.$gid
						.' AND app_en='."'".$app_en."'".' UNION 
						SELECT app_en,module_en,op_en,auth_type FROM '.DB_PREFIX.'auth_user WHERE 1 AND auth_type!="nod" AND user_id='.$id.' AND app_en='."'".$app_en."'";
			}

			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				//$info[] = $row;
				$group_id = intval($row['group_id']);
				//如果有模块标识,没有操作,说明全选
				if($row['module_en'] && !$row['op_en'])
				{
					if($group_id)
					{
						$is_all_group = 'group';//用户组的全选	
					}
					else
					{
						$is_all_user = 'user';//用户自己得全选
					}
					$is_all = 1;
				}
				//应用授权,只有应用标识,标识应用授权
				if($row['app_en']==$app_en && !$row['module_en'] && !$row['op_en'])
				{
					if($group_id)
					{
						$app_global_group = 'group';//组的应用授权	
					}
					else
					{
						$app_global_user = 'user';//用户的应用授权	
					}	
				}
				
				//获得操作
				if($row['op_en'])
				{
					if($group_id)//组的权限
					{
						$auth_group[$row['op_en']] = 'group';
					}
					else//用户的权限
					{
						$auth_group[$row['op_en']] = 'user';
					}	
				}
			}
			//将权限传递给操作
			if($is_all)//选中全选
			{		
				if(is_array($arr) && count($arr)>0)
				{
					if($is_all_group == 'group')//用户组选中全选
					{
						foreach($arr as $k=>$v)
						{
							$arr[$k]['perm'] = 2;
						}
					}
					else//用户组没有选中全选
					{
						foreach($arr as $k=>$v)
						{
							if($auth_group[$v['op_en']] == 'group')//用户继承用户组的操作
							{
								$arr[$k]['perm'] = 2;
							}
							else if($auth_group[$v['op_en']] == 'user')//用户自己的操作
							{
								$arr[$k]['perm'] = 1;
							}
						}
					}	
				}
				$arr['is_all'] = 1;
			}
			else//没点全选
			{
				if(is_array($arr) && count($arr)>0)
				{
					foreach($arr as $k=>$v)
					{
						if($auth_group[$v['op_en']] == 'user')//用户权限
						{
							$arr[$k]['perm'] = 1;
						}
						else if($auth_group[$v['op_en']] == 'group')//组的权限
						{
							$arr[$k]['perm'] = 2;
						}
						else//未选中的操作
						{
							$arr[$k]['perm'] = 0;
						}
					}
					$arr['is_all'] = 0;
				}
			}
			//判断全选是组的还是用户的
			if($is_all_group && !$is_all_user)//用户组全选,用户没全选
			{
				$arr['is_all'] = 0;
			}
			else if(!$is_all_group && $is_all_user)//用户组没全选,用户全选
			{
				$arr['is_all'] = 1;
			}
			//1是组的应用授权,2用户的应用授权
			if($app_global_group == 'group')
			{
				$arr['app_global'] = 1;
			}
			else if($app_global_user == 'user')
			{
				$arr['app_global'] = 2;
			}
		}
		
		$this->addItem($arr);
		$this->output();
	}


	//节点权限,获取模块分类
	function accredit_node()
	{
		$app_en = urldecode($this->input['app_en']);
		$mod_en = urldecode($this->input['mod_en']);
		if(!$mod_en)
		{
			$this->erroroutput('没有模块标识');
		}
		$id = $this->input['id'];//用户id
		$type = urldecode($this->input['type']);//标识用户组还是用户

		//获得模块下所有操作
		$op = $this->get_op_privilege();

		//查找模块下的节点
		$sql = 'SELECT * from '.DB_PREFIX.'node_privilege WHERE app_en='."'".$app_en."'".' AND module_en='."'".$mod_en."'";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
		}
		
		$default_node = $arr[0]['node_en'];
		if($id)
		{
			//根据分类查询操作权限
			if($type == 'group')
			{
				//查找用户组操作权限
				$sql = 'SELECT app_en,module_en,op_en,node_en,perm FROM '.DB_PREFIX.'auth_group WHERE group_id='.$id.' AND app_en="'.$app_en.'" AND module_en="'.$mod_en.'" AND node_en="'.$default_node.'"';
			}
			else
			{
				//查找用户操作权限(继承用户组权限)
				$gid = intval($this->input['gid']);//用户组id
				$sql =	'SELECT app_en,module_en,op_en,node_en,perm,group_id FROM '.DB_PREFIX.'auth_group WHERE group_id='.$gid
						.' AND app_en="'.$app_en.'" AND module_en="'.$mod_en.'" AND node_en="'.$default_node.'" 
						UNION 
						SELECT app_en,module_en,op_en,node_en,perm,auth_type FROM '.DB_PREFIX.'auth_user WHERE  user_id='.$id
						.' AND app_en="'.$app_en.'" AND module_en="'.$mod_en.'" AND node_en="'.$default_node.'"';
			
			}
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$group_id = intval($row['group_id']);
				$set['node_id'][$row['perm']] = 1;//被设置节点id
				//$set['op'][$row['op_en']] = 1;//节点拥有的操作

				$info[$row['perm']][] = $row['op_en'];
			}
			//file_put_contents('1.txt',var_export($info,1));
			//将选中的操作,放到所有操作数组中
			/*if(is_array($op) && count($op)>0)
			{
				foreach($op as $k => $v)
				{
					if($set['op'][$v['op_en']])
					{
						$op[$k]['perm'] = 1;
					}
					else
					{
						$op[$k]['perm'] = 0;
					}
				}
			}*/
			
			//节点id
			if(is_array($set['node_id']))
			{
				foreach($set['node_id'] as $k => $v)
				{
					$node_id[] = $k;
				}
			}
			$arr['node_id'] = $node_id;
			$arr['info'] = $info;
			$arr['op'] = $op;
			$this->addItem($arr);
		}
		else
		{
			$this->addItem($op);
		}
		
		$this->output();
	}

	//根据用户组或者用户id查询所有权限节点id
	public function getNodeId()
	{
		$app_en = urldecode($this->input['app_en']);
		$mod_en = urldecode($this->input['mod_en']);
		if(!$mod_en)
		{
			$this->erroroutput('没有模块标识');
		}
		$id = $this->input['id'];//用户组或者用户id
		$type = urldecode($this->input['type']);//标识用户组还是用户
		
		//获得模块下所有操作
		$op = $this->get_op_privilege();
		if($id)
		{
			//根据分类查询操作权限
			if($type == 'group')
			{
				//查找用户组操作权限
				$sql = 'SELECT a.app_en,a.module_en,a.op_en,a.node_en,a.perm FROM '.DB_PREFIX.'auth_group a 
				WHERE a.group_id='.$id.' AND a.auth_type="nod" AND a.app_en="'.$app_en.'" AND a.module_en="'.$mod_en.'"';
			}
			else
			{
				//查找用户操作权限(继承用户组权限)
				$gid = intval($this->input['gid']);//用户组id
				$sql =	'SELECT a.app_en,a.module_en,a.op_en,a.node_en,a.perm,a.group_id FROM '.DB_PREFIX.'auth_group a
				WHERE a.group_id='.$gid
				.' AND a.auth_type="nod" AND a.app_en="'.$app_en.'" AND a.module_en="'.$mod_en.'" 
				UNION 
				SELECT u.app_en,u.module_en,u.op_en,u.node_en,u.perm,u.auth_type FROM '.DB_PREFIX.'auth_user u 
				WHERE u.user_id='.$id
				.' AND u.auth_type="nod" AND u.app_en="'.$app_en.'" AND u.module_en="'.$mod_en.'"';
			
			}
			//file_put_contents('1.txt',$sql);
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$arr[] = $row;
				$group_id = intval($row['group_id']);
				
				$node_id[$row['perm']] = $row['node_en'];//被设置节点id
				$info[$row['perm']][] = $row['op_en'];	//节点的权限
			}

			//查找模块下所有节点
			$sql_multi = '';
			$sql_multi = 'SELECT node_en,node_name FROM '.DB_PREFIX.'node_privilege WHERE app_en="'.$app_en.'" AND module_en="'.$mod_en.'"';
			$q = $this->db->query($sql_multi);
			while($row = $this->db->fetch_array($q))
			{
				$multi_node[$row['node_en']] = $row['node_name']; //多个节点的节点标识与名称
			}

			//file_put_contents('2.txt',var_export($multi_node,1));
			$arr['multi_node'] = $multi_node;//多节点标识与名称
			$arr['node_id'] = $node_id;		//有权限节点id
			$arr['info'] = $info;			//节点的权限
			$arr['op'] = $op;				//节点的权限
			//file_put_contents('2.txt',var_export($arr,1));
			$this->addItem($arr);
			$this->output();
		}
	}
	
	public function verifyToken()
	{

	}
	
	//输出当前有效的app信息,供天气系统使用
	public function effective_app()
	{
		$sql = 'SELECT appid,custom_name,display_name FROM '.DB_PREFIX.'authinfo WHERE expire_time >'.TIMENOW.' or expire_time=0';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[] = $row; 
		}
		$this->addItem($k);
		$this->output();
	}
	
	/**
	 * 取auth信息
	 * $offset 分页参数
	 * $count 分页参数
	 * Enter description here ...
	 */
	public function get_auth_info()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$orderby = " ORDER BY order_id DESC ";
		$limit 	 = " LIMIT {$offset} , {$count} "; 
		
		$sql = "SELECT appid, custom_name, display_name FROM " . DB_PREFIX . "authinfo ";
		$sql.= " WHERE expire_time >" . TIMENOW . " or expire_time=0 " . $orderby . $limit;
		$query = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($query))
		{
			$return[] = $row; 
		}
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 取有效期内auth总数
	 * Enter description here ...
	 */
	public function get_auth_count()
	{
		$sql = "SELECT COUNT(appid) AS total FROM " . DB_PREFIX . "authinfo ";
		$sql.= " WHERE expire_time >" . TIMENOW . " or expire_time=0 ";
		
		$return = $this->db->query_first($sql);
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * access_token是否过期
	 * $access_token
	 * Enter description here ...
	 */
	public function access_token_expired()
	{
		$access_token = trim($this->input['access_token']);
		if (!$access_token)
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
		
		$sql = "SELECT login_time FROM " . DB_PREFIX . "user_login ";
		$sql.= " WHERE token = '" . $access_token . "'";
		
		$user_login = $this->db->query_first($sql);
		
		if (empty($user_login))
		{
			$return = array(
					'result' => 1,
				);
		}
		else
		{
			$login_time 	= intval($user_login['login_time']);
			$token_expired 	= intval(TOKEN_EXPIRED);
			
			$time = $login_time + $token_expired;
			
			$return = array(
				'result' => 1,
			);
			if ($time >= TIMENOW)
			{
				$return = array(
					'result' => 0,
				);
			}
		}
		$this->addItem($return);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>
