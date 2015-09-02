<?php
/***************************************************************************
* $Id: mobile_module.php 11744 2012-09-22 09:24:58Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID', 'mobile_module');
require('global.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
class mobileModuleApi extends adminReadBase
{
	private $mMobileModule;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/mobile_module.class.php';
		$this->mMobileModule = new mobileModule();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function index()
	{
		
	}
	public function show()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	//$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$condition = $this->get_condition();
		$offset = intval($this->input['offset']);
		$count = intval($this->input['count']);
		
		$mobileModuleList = $this->mMobileModule->show($condition, $offset, $count);

		if ($mobileModuleList)
		{
			foreach ($mobileModuleList AS $mobileModule)
			{
				if ($mobileModule['icon1'])
				{
					$mobileModule['icon1'] = hg_material_link($mobileModule['icon1']['host'], $mobileModule['icon1']['dir'], $mobileModule['icon1']['filepath'], $mobileModule['icon1']['filename']);
				}
				else 
				{
					if ($mobileModule['filename'])
					{
						$mobileModule['icon1'] = hg_material_link($mobileModule['host'], $mobileModule['dir'], $mobileModule['filepath'], $mobileModule['filename']);
					}
				}
				/*if ($mobileModule['icon2'])
				{
					$mobileModule['icon2'] = hg_material_link($mobileModule['icon2']['host'], $mobileModule['icon2']['dir'], $mobileModule['icon2']['filepath'], $mobileModule['icon2']['filename']);
				}
				if ($mobileModule['icon3'])
				{
					$mobileModule['icon3'] = hg_material_link($mobileModule['icon3']['host'], $mobileModule['icon3']['dir'], $mobileModule['icon3']['filepath'], $mobileModule['icon3']['filename']);
				}
				if ($mobileModule['icon4'])
				{
					$mobileModule['icon4'] = hg_material_link($mobileModule['icon4']['host'], $mobileModule['icon4']['dir'], $mobileModule['icon4']['filepath'], $mobileModule['icon4']['filename']);
				}*/
				//$info[] = $mobileModule;
				$this->addItem($mobileModule);
			}
			//hg_pre($info,0);
		}
		$this->output();
	}

	public function detail()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = urldecode($this->input['id']);
		$info = $this->mMobileModule->detail($id);
		$this->addItem($info);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mMobileModule->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = $this->mMobileModule->get_condition();
		return $condition;
	}
	
	/**
	 * 获取模块分类
	 * Enter description here ...
	 */
	public function append_sort()
	{
		$sql = "SELECT id,name FROM " . DB_PREFIX . "module_sort ORDER BY order_id ASC";
		$g = $this->db->query($sql);
		while($j = $this->db->fetch_array($g))
		{
			$data[$j['id']] = $j['name'];
		}
		$this->addItem($data);
		$this->output();
	}
	/**
	 * 获取应用
	 * Enter description here ...
	 */
	public function append_app()
	{
		$appAuthInfo = $this->get_app_auth();
		
		if($appAuthInfo)
		{
			foreach ($appAuthInfo as $k => $v)
			{
				$authInfo[$v['appid']] = $v['custom_name'];
			}
		}
	
			
		$sql = "SELECT appid,appname FROM " . DB_PREFIX . "certificate ORDER BY appid DESC";
		$g = $this->db->query($sql);
		while($j = $this->db->fetch_array($g))
		{
			if($authInfo[$j['appid']])
			{
				$j['appname'] = $authInfo[$j['appid']];
			}
			else 
			{
				$j['appname'] = '应用已删除';
			}
			$arr[$j['appid']] = $j['appname'];
		}
		$this->addItem($arr);
		$this->output();
	}
	/**
	 * 获取应用
	 * Enter description here ...
	 */
	public function get_app_auth()
	{
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->pub = new Auth();
		$app_auth = $this->pub->get_auth_list(0,100);
		return $app_auth;
	}
}

$out = new mobileModuleApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>