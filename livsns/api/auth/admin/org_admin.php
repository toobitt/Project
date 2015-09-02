<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once('./global.php');
define('SCRIPT_NAME', 'OrgAdmin');
define('MOD_UNIQUEID','org_admin');
class OrgAdmin extends Auth_frm
{	
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index(){}
	
	public function show()
	{
		$admin_role = '';
		$order = ' ORDER BY t1.id DESC';
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):100;
		$limit = " limit {$offset}, {$count}";

		if($this->input['fid'])
		{
			$sql = 'SELECT childs FROM ' . DB_PREFIX . 'admin_org WHERE id=' . $this->input['fid'];
			$res = $this->db->query_first($sql);
			$childs = $res['childs'];
			
			$con .= ' AND id IN ('.$childs.')';
		}
		else 
		{
			//$con .= ' AND fid=0';
		}
		if($this->input['depath'])
		{
			$con .= ' AND depath = '.$this->input['depath'];
		}
		//查询第一维组织
		$sql = 'SELECT id,fid,name,is_last,depath FROM ' . DB_PREFIX . 'admin_org WHERE 1 '.$con;
		$query = $this->db->query($sql);
		$data = array();
		while ($r = $this->db->fetch_array($query))
		{
			$org_info[$r['id']] = $r;
			$org_ids[] = $r['id'];
		}
		$org_ids = implode(',', $org_ids);
		
		$sql = 'SELECT t1.* FROM ' . DB_PREFIX . 'admin t1 WHERE 1';
		$condition = $this->get_condition();	
		
		//查询第一维组织下的用户	
		if($org_ids)
		{
			$condition .= ' AND t1.father_org_id IN ('.$org_ids.')';
		}		
		
		$sql = $sql . $condition . $order . $limit;
		$q = $this->db->query($sql);
		$user = array();
		$size = '90x90/';
		while ($row = $this->db->fetch_array($q))
		{
			$avatar = array();
			$row['create_time'] =date('Y-m-d h:i:s',$row['create_time']);
			$row['is_bind_card'] = $row['cardid'];
			$row['cardid'] = $row['cardid']?'已绑定':'未绑定';
			$avatar = unserialize($row['avatar']);
			$row['avatar'] = $avatar ? $avatar : '';
			if($avatar['host'] && $avatar['dir'] && $avatar['filepath'] && $avatar['filename'])
			{
				$row['avatar_url'] = $avatar['host'].$avatar['dir'].$size.$avatar['filepath'].$avatar['filename'];
			}
			else 
			{
				$row['avatar_url'] = '';
			}
			$user[] = $row;
		}
		
		//查询角色
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'admin_role ';
		$admin_role = array();
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$admin_role[$row['id']] = $row['name'];
		}
		
		if($org_info)
		{
			if($user)
			{
				foreach ($user as $v)
				{
					$admin_role_name = '';
					//用户所拥有角色id
					$admin_role_ids = $v['admin_role_id'] ? explode(',', $v['admin_role_id']) : '';
					if($admin_role_ids)
					{
						foreach($admin_role_ids as $id)
						{
							//判断是否属于管理员
							if($id <= MAX_ADMIN_TYPE)
							{
								$v['is_admin'] = 1;
							}
							//获取角色名称
							$admin_role_name .= $admin_role[$id].',';
						}
					}
					if(!$v['is_admin'])
					{
						$v['is_admin'] = 0;
					}
					$v['name'] = trim($admin_role_name, ',');
					
					//将用户整合到组织里
					if($org_info[$v['father_org_id']])
					{
						$org_info[$v['father_org_id']]['user'][$v['id']] = $v;
					}
				}
			}
			
			$data_arr['info'] = $org_info;
			if(!$this->input['fid'])
			{
				$data_arr['role'] = $admin_role;
			}
			$this->addItem($data_arr);
		}
		$this->output();
	}
	
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND t1.name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND t1.id = '.intval($this->input['id']);
		}
		if($this->input['_id'])
		{
			$condition .= ' AND t1.father_org_id='.intval($this->input['_id']);
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
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$this->show();
	}
	
	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'admin '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	//获取用户所有角色合并的权限
	public function get_user_prms()
	{
		$role_id = urldecode($this->input['role_id']);
		if(!$role_id)
		{
			return;
		}
		$prms = hg_update_role_prms($role_id);
		$prms = merge_user_prms($prms);
		$apps = @array_keys($prms['app_prms']);
		if($apps)
		{
			$apps = implode('","', $apps);
			$sql = 'SELECT bundle,name FROM '.DB_PREFIX.'apps WHERE bundle IN("'.$apps.'")';
			$query = $this->db->query($sql);
			$apps = array();
			while($row = $this->db->fetch_array($query))
			{
				$apps[$row['bundle']] = $row['name'];
			}
		}
		require_once(ROOT_PATH.'lib/class/publishconfig.class.php');
		$publishconfig = new publishconfig();
		$publish_sites = $publish_columns = array();
		if($prms['site_prms'])
		{
			$publish_sites = $publishconfig->get_sites();
			$prms['site_prms'] = array_intersect_key($publish_sites, array_flip($prms['site_prms']));
		}
		if($prms['publish_prms'])
		{
			$column_ids = implode(',', $prms['publish_prms']);
			$publish_columns = $publishconfig->get_columnname_by_ids('*', $column_ids);
			$prms['publish_prms'] = $publish_columns;
		}
		if($prms['app_prms'])
		{
			foreach ($prms['app_prms'] as $k=>$v)
			{
				if($prms['app_prms'][$k]['action'])
				{
					$prms['app_prms'][$k]['action'] = array_intersect_key($this->settings['auth_op'], array_flip($prms['app_prms'][$k]['action']));
				}
				$prms['app_prms'][$k]['app_name'] = $apps[$k];
			}
		}
		$this->addItem($prms);
		$this->output();
	}
	public function get_roles_prms()
	{
		$sql = "SELECT prms.*, app.name FROM ".DB_PREFIX."role_prms prms LEFT JOIN ".DB_PREFIX.'apps app ON prms.app_uniqueid=app.bundle ';
		$query = $this->db->query($sql);
		$role_prms = array();
		while ($row = $this->db->fetch_array($query))
		{
			if(trim($row['func_prms']))
			{
				$row['func_prms'] = array_flip(explode(',', $row['func_prms']));
				foreach ($row['func_prms'] as $action=>$null)
				{
					if(!$this->settings['auth_op'][$action])
					{
						$row['func_prms'][$action] = $action;
					}
					else
					{
						$row['func_prms'][$action] = $this->settings['auth_op'][$action];
					}
				}
				//$this->settings['auth_op'];
			}
			$role_prms[$row['admin_role_id']]['app_prms'][$row['app_uniqueid']] = $row;
		}
		$sql = 'SELECT id,extend_prms,site_prms,publish_prms FROM '.DB_PREFIX.'admin_role ';
		$query = $this->db->query($sql);
		$column_ids = $site_ids = '';
		while($row = $this->db->fetch_array($query))
		{
			if($row['publish_prms'])
			{
				$column_ids .= $row['publish_prms'] . ',';
			}
			if($row['site_prms'])
			{
				$site_ids .= $row['site_prms'] . ',';
			}
			$row['publish_prms'] = $row['publish_prms'] ? explode(',', $row['publish_prms']) : array();
			$row['site_prms'] = $row['site_prms'] ? explode(',', $row['site_prms']):array();
			$row['extend_prms'] = unserialize($row['extend_prms']);
			$role[$row['id']] = $row;
		}
		$column_ids = trim($column_ids,',');
		$site_ids = trim($site_ids,',');
		if($column_ids)
		{
			require_once(ROOT_PATH.'lib/class/publishconfig.class.php');
			$publishconfig = new publishconfig();
			$publish_columns = $publishconfig->get_columnname_by_ids('*', $column_ids);
		}
		if(!class_exists('publishconfig'))
		{
			require_once(ROOT_PATH.'lib/class/publishconfig.class.php');
			$publishconfig = new publishconfig();
		}
		$publish_sites = $publishconfig->get_sites();
		foreach($role_prms as $role_id=>$v)
		{
			$v['publish_prms'] = array();
			if($role[$role_id]['publish_prms'])
			{
				$v['publish_prms'] = array_intersect_key($publish_columns,array_flip($role[$role_id]['publish_prms']));
			}
			$v['site_prms'] = array();
			if($role[$role_id]['site_prms'])
			{
				$v['site_prms'] = array_intersect_key($publish_sites,array_flip($role[$role_id]['site_prms']));
			}
			$v['extend_prms'] = array();
			if($role[$role_id]['extend_prms'])
			{
				$v['default_setting'] = $role[$role_id]['extend_prms'];
			}
			$this->addItem_withkey($role_id, $v);
		}
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>