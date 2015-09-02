<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once('./global.php');
define('SCRIPT_NAME', 'AuthAdminRole');
define('MOD_UNIQUEID','role');
class AuthAdminRole extends Auth_frm
{	
	function __construct()
	{
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
		$this->verify_content_prms(array('_action'=>'auth_user'));
		$order = ' ORDER BY id DESC';
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'admin_role WHERE 1 ';
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $order;
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$row['create_time'] = date('Y-m-d h:i:s',$row['create_time']);
			$row['extend_prms'] = unserialize($row['extend_prms']);
			$row['is_delete']  = 1;
			$role[$row['id']] = $row;
		}
		if($role_id = @array_keys($role))
		{
			$role_id = implode(',', $role_id);
			$sql = 'SELECT admin_role_id,is_complete,app_uniqueid,mod_uniqueid FROM '.DB_PREFIX.'role_prms WHERE admin_role_id IN('.$role_id.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$app[$row['app_uniqueid']] = $row['mod_uniqueid'];
				$role[$row['admin_role_id']]['prms'][$row['app_uniqueid'].'-'.$row['mod_uniqueid']]['is_complete'] = $row['is_complete'];
			}
			if($app)
			{
				$sql = 'SELECT app_uniqueid,mod_uniqueid,name	FROM '.DB_PREFIX.'modules WHERE app_uniqueid IN("'.implode('","', array_keys($app)).'") AND mod_uniqueid IN("'.implode('","', $app).'")';
				$query = $this->db->query($sql);
				$app = array();
				while($row = $this->db->fetch_array($query))
				{
					$app[$row['app_uniqueid'].'-'.$row['mod_uniqueid']] = $row['name'];
				}
			}
			$sql = "SELECT distinct admin_role_id FROM ".DB_PREFIX."user_role WHERE 1 ";
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				if(isset($role[$row['admin_role_id']]) && !empty($role[$row['admin_role_id']]))
				{
				$role[$row['admin_role_id']]['is_delete'] = 0;
				}
			}
		}
		if($role)
		{
			foreach ($role as $role_id=>$row)
			{
				if($row['prms'])
				{
					foreach($row['prms'] as $k=>$v)
					{
						$row['prms'][$k]['name'] = $app[$k];
					}
				}
				$this->addItem($row);
			}
			$this->output();
		}
	}
	public function detail()
	{
		$this->verify_content_prms(array('_action'=>'auth_user'));
		$apps = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'admin_role WHERE id = '.intval($this->input['id']);
		$role = $this->db->query_first($sql);
		if(!$role && $this->input['id'] != -1)
		{
			$this->errorOutput("角色不存在或已删除");
		}
		if ($role)
		{
			if( ($this->user['group_type'] > MAX_ADMIN_TYPE) && ($role['user_id'] != $this->user['user_id']))
			{
				$this->errorOutput("没有权限管理此角色");
			}
			$role['extend_prms'] = $role['extend_prms'] ? unserialize($role['extend_prms']) : '';
			$return = array();
			$return['role'] = $role;
			$sql = 'SELECT * FROM '.DB_PREFIX.'role_prms WHERE admin_role_id = '.$role['id'] . ' ORDER BY order_id';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$return['role']['prms'][$row['app_uniqueid'].'-'.$row['mod_uniqueid']] = $row;
			}
		}
		$where = '';
		if( $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach($this->user['prms']['app_prms'] as $app=>$val)
			{
				if(!$val['is_complete']) continue;
				$apps[] = $app;
			}
			if(!$apps)
			{
				$this->errorOutput("不存在多级授权应用");
			}
			$where .= ' AND app_uniqueid IN ("'.implode('","', $apps).'")';
		}
		$sql = 'SELECT id,name,class_id,mod_uniqueid,app_uniqueid FROM '.DB_PREFIX.'modules WHERE main_module=1 AND need_auth = 1 ' . $where . ' ORDER BY class_id ASC';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$return['apps'][$row['class_id']][] = $row;
		}
		
		$this->addItem($return);
		$this->output();
	}
	//find user by role_id
	public function user_in_role()
	{
		$role_id = $this->input['role_id'];
		if(!$role_id)
		{
			$this->errorOutput('未知角色ID');
		}
		$sql = 'SELECT ur.admin_role_id,a.user_name FROM '.DB_PREFIX.'user_role ur LEFT JOIN '.DB_PREFIX.'admin a ON ur.admin_user_id = a.id WHERE ur.admin_role_id = '.intval($role_id) . ' ORDER BY ur.create_time DESC ';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	//角色编辑点击ajax请求操作和节点数据
	public function prms_setting()
	{
		$role_id = intval($this->input['id']);
		$app_uniqueid = $this->input['app_uniqueid'];
		$mod_uniqueid = $this->input['mod_uniqueid'];
		$sql = 'SELECT bundle,host,admin_dir,dir FROM '.DB_PREFIX.'apps WHERE bundle="'.$app_uniqueid.'"';
		$appinfo = $this->db->query_first($sql);
		if(!$appinfo)
		{
			$this->errorOutput("应用不存在");
		}
		$appinfo['host'] = $this->settings['hostprefix'] . $appinfo['host'];
		$sql = 'SELECT file_name FROM '.DB_PREFIX.'modules WHERE app_uniqueid="'.$app_uniqueid.'" AND mod_uniqueid="'.$mod_uniqueid.'"';
		$filename = $this->db->query_first($sql);
		$filename = $filename['file_name'];
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$curl = new curl($appinfo['host'], $appinfo['dir'] . $appinfo['admin_dir']);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','show_prms_methods');
		$action = $curl->request($filename . '.php');
		if(!$action || !is_array($action))
		{
			$return = array('error'=>'该应用权限配置异常');
			$this->addItem($return);
			$this->output();
		}
		$return = array();
		$node = $action['_node'];
		unset($action['_node']);
		$return['op'] = $action ? $action : array();
		$return['node']  = array();
		$return['extra'] = array();
        $return['settings'] = array();
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($node,1).var_export($appinfo,1).$filename, FILE_APPEND);
		if($node)
		{
			$node['host'] = $appinfo['host'];
			$node['dir'] = $appinfo['dir'] . $appinfo['admin_dir'];
			if($node['node_uniqueid'] != 'cloumn_node')
			{
				$curl->initPostData();
				$curl->addRequestData('_from_auth', 1);
				if(isset($node['ext_parameter']) && is_array($node['ext_parameter']))
				{
					foreach($node['ext_parameter'] as $key=>$val)
					{
						$curl->addRequestData($key, $val);
					}
				}
				$return['node'] = $curl->request($node['filename'] ? $node['filename'] : $node['node_uniqueid'] . '.php');
				if(!is_array($return['node']) || $return['node']['ErrorCode'])
				{
					$return['node'] = array();
				}
			}
			$return['extra'] = $node;
		}

        //配置里模块
        $sql = 'SELECT id,app_uniqueid,mod_uniqueid,name FROM '.DB_PREFIX.'modules WHERE app_uniqueid="'.$app_uniqueid.'" AND main_module != 1';
        $q = $this->db->query($sql);
        while ($row = $this->db->fetch_array($q))
        {
            $return['settings'][] = $row;
        }
		$this->addItem($return);
		$this->output();
	}
	function get_condition()
	{
		$condition = '';
	
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$condition .= ' AND user_id = ' . $this->user['user_id'];
		}
		if($this->input['k'])
		{
			$condition .= ' AND name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= ".$end_time;
		}
		if($this->input['role_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['role_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	public function count()
	{
		$this->verify_content_prms(array('_action'=>'auth_user'));
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'admin_role '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
}
include(ROOT_PATH . 'excute.php');
?>