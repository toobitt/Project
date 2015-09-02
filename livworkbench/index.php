<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 9173 2015-06-17 07:39:51Z develop_tong $
***************************************************************************/
//define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'index');
require('./global.php');
class index extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		unset($this->nav);
		$this->tpl->addVar('_nav', $this->nav);
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		//$this->ReportError(var_export($this->user['prms_menus'],1));
		session_start(); 
		$default_page = $_SESSION['livmcp_userinfo']['default_page']?$_SESSION['livmcp_userinfo']['default_page']: './default.php';
		if ($_SESSION['livmcp_userinfo']['open_way']==1 && $_SESSION['livmcp_userinfo']['default_page'])
		{
			header('Location:' . $_SESSION['livmcp_userinfo']['default_page']);		
		}
		//$default_page = './default.php';
		$default_module_id = hg_get_cookie('lastVMod');

		$this->cache->check_cache('menu_group', 'menu_recache');
		$this->cache->check_cache('menu_apps', 'menu_recache');
		$menu_group = $this->cache->cache['menu_group'];
		$menu_apps = $this->cache->cache['menu_apps'];
		#####菜单权限检测开始
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $menu_apps)
		{
			$authored_apps = @array_keys($this->user['prms_menus']);
			if(!$authored_apps)
			{
				$this->ReportError('没有授权的应用');
			}
			foreach ($menu_apps as $k => $v)
			{
				if($v)
				{
					foreach ($v as $kk=>$vv)
					{
						if (!in_array($vv['app_uniqueid'],$authored_apps))
						{
							unset($menu_apps[$k][$kk]);
						}
					}			
				}
			}
			if ($menu_group)
			{
				foreach ($menu_group as $k => $v)
				{
					if (!$menu_apps[$k])
					{
						unset($menu_group[$k]);
					}
				}
			}
		}
		if ($this->input['referto'] && !strpos($this->input['referto'], 'login.php') && !strpos($this->input['referto'], 'index.php') && strpos($this->input['referto'], '.php'))
		{
			$default_page = $this->input['referto'];
		}

        if(DEVELOP_MODE){
            //$sql = $
        }
        
        
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($this->settings['App_settings'])
		{
			$this->curl = new curl($this->settings['App_settings']['host'], $this->settings['App_settings']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', 'show');
			$ret = $this->curl->request('weight.php');
			$this->tpl->addVar('weight', $ret);
		}
		
		$_m2ocode = array(
			'customer_id' => CUSTOM_APPID,
			'customer_appkey' => CUSTOM_APPKEY,
			'user_name' => $this->user['user_name'],
			'user_id' => $this->user['user_id'],
			'time' => TIMENOW
		);
		$_m2ocode = hoge_en(json_encode($_m2ocode));
        
		$this->tpl->addVar('_settings', $this->settings);
		$this->tpl->addVar('_m2ocode', $_m2ocode);
		$this->tpl->addVar('menu_group', $menu_group);
		$this->tpl->addVar('menu_apps', $menu_apps);
		$this->tpl->outTemplate('index');
	}
	
	protected function check_api()
	{
	}
	
	//修改自定义应用菜单
	public function save_app_menus()
	{
		$app_menus = isset($this->input['app_menus']) ? trim($this->input['app_menus']) : '';
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if ($this->settings['App_auth'])
		{
			$this->curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'].'admin/');
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('apps', $app_menus);
			$this->curl->addRequestData('a', 'update_app');
			$ret = $this->curl->request('save_menu.php');
		}
		if ($ret)
		{
			$_SESSION['livmcp_userinfo']['app_custom_menus'] = $app_menus;
			$this->user['app_custom_menus'] = $app_menus;
			echo json_encode(array('error' => '0'));
		}
		else
		{
			echo json_encode(array('error' => 1));
		}
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>