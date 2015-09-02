<?php
require_once('./global.php');
require_once(CUR_CONF_PATH . 'core/message_module.dat.php');
define('MOD_UNIQUEID','message_set');//模块标识
class MessSet extends adminReadBase
{
	function __construct()
	{
		$this->resetPrmsMethods();
		$this->mModPrmsMethods['manage'] = array(
			'name' => '管理',
		);
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index(){}
	
	function show()
	{
		$sql = "SELECT * FROM  ".DB_PREFIX."app_settings WHERE content_id = 0 ORDER BY id DESC";
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{
			$row['type'] = $row['module_id'] ? '模块' : '应用';
			$row['state'] = $row['is_open'] ? '开启' : '关闭';
			
			$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
			
			$this->addItem($row);
		}
		$this->output();
	}

	//获取某个应用配置
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		
		$g_set = $this->settings['message_form_set'];
		if(!$g_set)
		{
			$this->errorOutput('配置模板不存在');
		}
		
		$sql = "select * from " . DB_PREFIX . "app_settings where 1 AND id=".$id;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($row['module_id'])
			{
				$row['mod_name'] = $row['name'];
			}
			
			if($row['value'])
			{
				$arr_val = unserialize($row['value']);
			}
			if(is_array($g_set) && count($g_set)>0)
			{
				foreach($g_set as $key =>$val)
				{
					foreach($val as $k=>$v)
					{
						if($arr_val[$k] != $v['def_val'])
						{
							$g_set[$key][$k]['def_val'] = $arr_val[$k];
						}
						
						//验证码配置
						if($k == 'verify_mode' && $arr_val['verify_type'])
						{
							$g_set[$key][$k]['verify_type'] = $arr_val['verify_type'];
						}
					}
				}
			}
			$row['value'] = $g_set;	
			$this->addItem($row);
		}
		$this->output();
	}
	/**
	 * 获取验证码种类
	 */
	public function get_verify_type()
	{
		include_once(ROOT_PATH . 'lib/class/verifycode.class.php');
		$this->verifycode = new verifycode();
		$ret = $this->verifycode->get_verify_type();
		$this->addItem($ret);
		$this->output();	
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'app_settings  WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	private function get_condition()
	{
		
	}
	//获取应用模块
	public function get_app_module()	
	{	
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->pub = new Auth();
		$app_modules = $this->pub->get_app('id,name,bundle');
		if($app_modules)
		{
			foreach($app_modules as $k=>$v)
			{
				$apps[$v['bundle'].'@'.$v['name']] = $v['name'];
			}
		}
		$apps['column@栏目'] = '栏目';
		$this->addItem($apps);
		$this->output();
	}	
	/**
	 * 
	 * ajax获取应用下的模块名称
	 * 
	 */
	public function get_app()
	{	
		$appid = urldecode($this->input['app_uniqueid']);
		if(!$appid)
		{
			$this->errorOutput(NO_APPID);
		}
		$tag_arr = explode('@', $appid);
		$appid = $tag_arr[0];
		
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->pub = new Auth();
		$app_modules = $this->pub->get_module('name,mod_uniqueid','',$appid);
		if($app_modules)
		{
			foreach ($app_modules as $k => $v)
			{
				$modules[$v['mod_uniqueid']] = $v['name'];
			}
		}
		$this->addItem($modules);
		$this->output();
	}

}
$output = new MessSet();
if(!method_exists($output,$_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();
?>