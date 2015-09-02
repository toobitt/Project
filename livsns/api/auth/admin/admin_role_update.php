<?php
require_once('global.php');
define('SCRIPT_NAME', 'AdminRoleUpdate');
define('MOD_UNIQUEID','role');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class  AdminRoleUpdate extends Auth_frm
{
	public function __construct()
	{
		parent::__construct();
		$this->curl = new curl();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function update2()
	{
		//$data = json_decode(html_entity_decode($this->input['data']));
		//$data = array();
		//
		$this->verify_content_prms(array('_action'=>'auth_user'));
		$extend_prms = $this->input['extend'];
		$extend_prms['set_weight_limit'] = intval($extend_prms['set_weight_limit']);
		if($extend_prms['set_weight_limit'] > 100)
		{
			$extend_prms['set_weight_limit'] = 100;
		}
		if($extend_prms['set_weight_limit'] < 0)
		{
			$extend_prms['set_weight_limit'] = 0;
		}
		//$extend_prms['show_other_data'] = $extend_prms['show_other_data'] ? $extend_prms['show_other_data_org'] : 0;
        //$extend_prms['manage_other_data'] = $extend_prms['manage_other_data'] ? $extend_prms['manage_other_data_org'] : 0;
		//$extend_prms['create_data_limit'] = abs(intval($extend_prms['create_data_limit']));
		$extend_prms['set_weight_limit'] = abs(intval($extend_prms['set_weight_limit']));
		$data = array(
			'name' 	=> trim($this->input['name']),
			'brief' => trim($this->input['brief']),
			'index_page'=>addslashes(trim($this->input['index_page'])),
        	'open_way'=>intval($this->input['open_way']),
        	'domain' => trim(($this->input['domain'])),
        	'extend_prms' => addslashes(serialize($extend_prms)),
			'publish_prms'=>$this->input['column_id'],
			'site_prms'=>$this->input['siteid'],
		);
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$publish_prms = $data['publish_prms'] ? explode(',', $data['publish_prms']) : array();
			$site_prms = $data['site_prms'] ? explode(',', $data['site_prms']) : array();
			if(@array_diff($site_prms, $this->user['prms']['site_prms']))
			{
				$this->errorOutput("非整站授权站点，授权失败");
			}
			if (!class_exists('publishconfig'))
            {
                include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            }
            $this->publish_column = new publishconfig();
            if($publish_prms)
            {
            	//排除全站授权栏目
                $column_site = $this->publish_column->get_column_site(array('column_id' => implode(',', $publish_prms)));
                if (is_array($column_site) && $column_site)
                {
                    foreach ($column_site as $site_id => $col)
                    {
                        if (in_array($site_id, $this->user['prms']['site_prms']))
                        {
                        	//$this->errorOutput(var_export($col,1));
                            $publish_prms = array_diff($publish_prms, $col);
                        }
                    }
                }
				if(@array_diff($publish_prms, $this->user['prms']['publish_prms']))
				{
					$this->errorOutput("非授权栏目，授权失败");
				} 
            }         
		}
		$role_id = intval($this->input['id']);
		//if role_id -1 create data
		if($role_id == -1)
		{
			$role_id = 0;
		}
		$admin_role = array();
		if($role_id)
		{
			$sql = 'SELECT id,name,user_id,extend_prms FROM '.DB_PREFIX.'admin_role WHERE id = "'.$role_id.'"';
			$admin_role = $this->db->query_first($sql);
			if($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if(($admin_role['user_id']!=$this->user['user_id']))
				{
					$this->errorOutput("没有权限管理此角色");
				}
				$sql = 'SELECT admin_role_id FROM  ' .DB_PREFIX . 'admin WHERE id = '.$this->user['user_id'];
				$user_roles = $this->db->query_first($sql);
				$user_roles = explode(',', $user_roles['admin_role_id']);
				if(in_array($role_id, $user_roles))
				{
					$this->errorOutput('无法修改当前用户所在的角色');
				}
			}
		}
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//完全继承扩展权限 无论更新创建角色
			$data['extend_prms'] = addslashes(serialize($this->user['prms']['default_setting']));
		}
		if(!$admin_role)
		{
			//create
			$data['user_id'] = $this->user['user_id'];
			$data['user_name']=$this->user['user_name'];
			$data['org_id'] = $this->user['org_id'];
			if($this->check_name_unique($data['name']))
			{
				$this->errorOutput(ROLE_NAME_EXISTS);
			}
			$data['create_time'] = TIMENOW;
			$sql = 'INSERT INTO '.DB_PREFIX.'admin_role SET ';
			foreach ($data as $filed=>$value)
			{
				$sql .= ' `'.$filed.'`="'.$value.'",';
			}
			$sql = trim($sql, ',');
			$op = '创建角色';
		}
		else
		{
			//update
			$sql = 'UPDATE '.DB_PREFIX.'admin_role SET ';
			foreach ($data as $filed=>$value)
			{
				$sql .= '`'.$filed.'`="'.$value.'",';
			}
			$sql = trim($sql, ',') . ' WHERE id='.$admin_role['id'];	
			//查出原来权限
			$sql1 = 'SELECT * FROM '.DB_PREFIX.'role_prms WHERE admin_role_id = '.$admin_role['id'] . ' ORDER BY order_id';
			$query = $this->db->query($sql1);
			while($row = $this->db->fetch_array($query))
			{
				$return['prms'][$row['app_uniqueid'].'-'.$row['mod_uniqueid']] = $row;
			}
			$op = '修改角色';
		}
		
		$this->db->query($sql);
		
		if($admin_role)
		{
			$data['id'] = $admin_role['id'];
			$this->db->affected_rows() ? $this->db->query("UPDATE ".DB_PREFIX.'admin_role SET update_user_id='.$this->user['user_id'].', update_user_name="'.$this->user['user_name'].'", update_time='.TIMENOW.' WHERE id = '.$admin_role['id']) : '';
		}
		else
		{
			$data['id'] = $this->db->insert_id();
		}
		//if update then delete authorized data
		if($admin_role)
		{
			$this->db->query('DELETE FROM '.DB_PREFIX.'role_prms WHERE admin_role_id = '.intval($admin_role['id']));
		}
		$prms = json_decode(html_entity_decode($this->input['prms']),1);
		if($prms)
		{
			$order_id = 0;
			$sql = 'INSERT INTO '.DB_PREFIX.'role_prms VALUES ';
			foreach ($prms as $app_mod=>$v)
			{
				$app_mod = explode('-', $app_mod);
				if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					if(!$this->user['prms']['app_prms'][$app_mod[0]]['is_complete'])
					{
						$this->errorOutput('存在非多级授权应用!');
					}
				}
				$sql .= '('.$data['id'].',"'.$app_mod['0'].'", "'.$app_mod['1'].'", "'.addslashes($v['op']).'", "'.$v['node'].'","'.$v['setting'].'", "'.$v['is_all'].'",'.$order_id.'),';
				$order_id++;
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
		}
		hg_update_role_prms($role_id);
		//日志
		$pre_data = array_merge(unserialize($admin_role['extend_prms']), $return);
		$up_data = array_merge($this->input['extend'], json_decode(htmlspecialchars_decode($this->input['prms']),1));
		$this->addLogs($op, $pre_data, $up_data, $data['name']);
		$this->addItem($data);
		$this->output();
	}
	public function delete()
	{
	    $id = $this->input['id'];
		if (!$id)
	    {
	    	$this->errorOutput(NOID);
	    }
	    $ids = explode(',', $id);
	    foreach ($ids as $val)
	    {
	    	if ($val<=MAX_ADMIN_TYPE)
	    	{
	    		$this->errorOutput('该角色不允许删除!');
	    	}
	    }
	    $sql = 'SELECT id FROM '.DB_PREFIX.'admin WHERE admin_role_id IN('.$id.')';
	    $result = $this->db->query_first($sql);
	    if($result['id'])
	    {
	    	$this->errorOutput(ROLE_IS_NOT_NULL);
	    }
		$sql = 'DELETE FROM '.DB_PREFIX.'admin_role WHERE id IN('.$id.')';
		$this->db->query($sql);
		foreach ($ids as $_role_id)
		{
			hg_rmove_cache_file($_role_id);
		}
		$this->addLogs('删除角色', '', '', $id);
		$this->addItem('success');
		$this->output();
	}
	/*
	public function create()
	{
		if (!trim($this->input['name']))
		{
			$this->errorOutput('请填写角色名称');
		}
		if (!$this->check_unique())
		{
			$this->errorOutput('角色名已经存在');
		}
		$data = array(
            'create_time'=>TIMENOW,
            'update_time'=>TIMENOW,
            'name'=>trim($this->input['name']),
            'brief'=>trim($this->input['brief']),
            'user_name_add'=>trim($this->user['user_name']),
			'index_page'=>addslashes(trim($this->input['index_page'])),
        	'open_way'=>intval($this->input['open_way']),
			'domain' => trim(($this->input['domain'])),
			'extend_prms' => addslashes(serialize($this->input['extend'])),
		);
        $sql = 'INSERT INTO '.DB_PREFIX.'admin_role SET ';
		foreach($data as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		$this->addItem($data);
		$this->output();
	}
	*/
	private function check_name_unique($user_name = '')
	{
		$this->input['id'] = $this->input['id']?$this->input['id']:0;
		$sql = 'SELECT count(*) AS total FROM ' . DB_PREFIX . 'admin_role WHERE name = "' . $user_name .'"';
		$row = $this->db->query_first($sql);
		if ($row['total'])
		{
			return true;
		}
		return false;
	}
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	/*
	public function get_module()
	{
		$fid = intval($this->input['root']);
		$sql = 'SELECT bundle from '.DB_PREFIX.'app WHERE id = '.$fid;
		$appinfo = $this->db->query_first($sql);
		
		if(!$appinfo['bundle'])
		{
			$this->errorOutput(UNKNOWN_APP);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'app WHERE father = '.$fid;
		$query = $this->db->query($sql);
		$modinfo = array();
		while($row = $this->db->fetch_array($query))
		{
			$tmp = array();
			$tmp['text'] = $row['name'];
			$tmp['expanded'] = false;
			$tmp['mod_uniqueid'] = $row['bundle'];
			$tmp['id'] = $row['id'];
			$tmp['app_mod_bundle'] = $appinfo['bundle'] . APP_MOD_SEP . $row['bundle'];
			$modinfo[$row['bundle']] = $tmp;
			$mod_uniqueid[] = $row['bundle'];
		}
		//取已设置权限
		if($mod_uniqueid)
		{
			//取操作
			$sql = 'SELECT * FROM '.DB_PREFIX.'mod_op WHERE mod_uniqueid IN("'.implode('","', $mod_uniqueid).'") AND app_uniqueid = "'.$appinfo['bundle'].'"';
			$query = $this->db->query($sql);
			$op = array();
			while($row = $this->db->fetch_array($query))
			{
				$op[$row['mod_uniqueid']][$row['op_en_name']] = $row['op_zh_name'];
			}
			$admin_role_id = intval($this->input['admin_role_id']);
			$sql = 'SELECT * FROM '.DB_PREFIX.'admin_func_prms WHERE mod_uniqueid IN("'.implode('","', $mod_uniqueid).'") AND admin_role_id = "'.$admin_role_id.'" and app_uniqueid="'.$appinfo['bundle'].'"';
			
			$query = $this->db->query($sql);
			$authorize = array();
			while($row = $this->db->fetch_array($query))
			{
				$authorize[$row['mod_uniqueid']][] = $row['op'];
			}
		}
		if($modinfo)
		{
			foreach ($modinfo as $k => $v) {
				$v['auth'] = $authorize[$k];
				$v['op'] = $op[$k];
				$this->addItem($v);
			}
		}
		$this->output();
	}
	*/
	/**
	 * 查询角色的发布权限
	 * @name update_publish_column
	 * @param $role_id string 角色id
	 * @return $col string 栏目ids
	 
	function set_publish_column()
	{
		$role_id = intval($this->input['id']);
		if(!$role_id)
		{
			$this->errorOutput(UNKNOWN_ADMIN_ROLE);
		}
		$sql = 'SELECT publish_prms as column_id,site_prms as site_id FROM '.DB_PREFIX.'admin_role WHERE  id = '.$role_id;
		$col = $this->db->query_first($sql);
		$this->addItem($col);
		$this->output();
	}
	*/
	function check_is_adminrole($admin_role_id = 1)
	{
		if($admin_role_id <= MAX_ADMIN_TYPE)
		{
			$this->errorOutput(IS_ADMIN_ROLE);
		}
	}
	/**
	 * 更新角色的发布权限
	 * @name update_publish_column
	 * @param $role_id string 角色id
	 * @param $role_col_prms string 栏目ids
	
	function update_publish_column()
	{
		$role_id = intval($this->input['admin_id']);
		$this->check_is_adminrole($role_id);
		$role_col_prms = urldecode($this->input['column_id']);//授权栏目ids
		$role_site_prms = urldecode($this->input['siteid']);//授权站点ids
		if(!$role_id)
		{
			$this->errorOutput(UNKNOWN_ADMIN_ROLE);
		}
		
		if($role_id <= MAX_ADMIN_TYPE)
		{
			$this->errorOutput(THIS_IS_SUPERADMIN);
		}
		$sql = 'UPDATE '.DB_PREFIX.'admin_role SET site_prms = "'.$role_site_prms.'", publish_prms = "'.$role_col_prms.'" WHERE id='.$role_id;
		$this->db->query($sql);
		hg_rmove_cache_file($role_id);
		$this->addItem('success');
		$this->output();
	}
	 */
	/**
	 * 更新角色权限
	 * @name update_role_power
	 * @param $role_id string 角色id
	 * @param $app_uniqueid string 应用标识
	 * @param $mod_uniqueid string 模块标识
	 * @param $mod_set array 模块下各操作权限设置
	
	public function update_role_power()
	{
		$role_id = $this->input['id'];
		$this->check_is_adminrole($role_id);
		if(!$role_id)
		{
			$this->errorOutput(NOID);
		}
		$app_uniqueid = $this->input['app_uniqueid'];
		if(!$app_uniqueid)
		{
			$this->errorOutput(NOAPP);
		}
		$mod_uniqueid = $this->input['mod_uniqueid'];
		if($mod_uniqueid)
		{
			foreach ($mod_uniqueid as $k => $v)
			{
				//$v 模块标识
				if($this->input[$v])
				{
					$ac = array();
					foreach ($this->input[$v] as $kk => $a)
					{
						if(!is_array($a))
						{
							//功能权限
							$ac[$a]['func'] = 1;
							
							//节点权限 判断模块下操作是否设置节点 
							if($this->input[$v.'_'.$a])//news_show模块标识_操作名
							{
								$node = json_decode(html_entity_decode($this->input[$v.'_'.$a]), 1);
								$tag = array();
								foreach ($node as $key => $val)
								{
									$tag = explode('@', $val['biaoshi']);
									$node_en = $tag[1];
									//各节点下的节点ids
									$ac[$a]['node'][$node_en][] = $val['id'];
								}
							}
							
							//方法中存在append  复制方法的权限给append方法 $v模块标识 $a操作名
							if($this->input[$v]['append'][$a])
							{
								$ac[$this->input[$v]['append'][$a]] = $ac[$a];
							}
						}
					}
					$mod_set[$v] = $ac;
				}
			}
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'role_prms WHERE admin_role_id = '.$role_id.' AND app_uniqueid = "'.$app_uniqueid.'"';
		$this->db->query($sql);
		
		if($mod_set)
		{
			$sql = "INSERT INTO ".DB_PREFIX."role_prms VALUES";
			$val = '';
			foreach ($mod_set as $mod => $set)
			{
				if($set)
				{
					$val .=  "(".$role_id.",'".$app_uniqueid."','".$mod."','".serialize($set)."'),";
				}
				
			}
			if($val)
			{
				$sql .= $val; 
				$sql = rtrim($sql,',');
				$this->db->query($sql);
			}
			
		}
		//更新权限缓存数据
		hg_update_role_prms($role_id, $app_uniqueid);
		$this->addItem('sucess');
		$this->output();
	}
	 */
}

include(ROOT_PATH . 'excute.php');
?>