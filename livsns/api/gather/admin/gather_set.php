<?php
require_once './global.php';
define('MOD_UNIQUEID','gather_set');//模块标识
require_once CUR_CONF_PATH.'lib/gatherSet.class.php';
class gatherSet extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->set = new ClassgatherSet();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count 	= $this->input['count'] ? intval($this->input['count']) : 20;
		$orderby = ' ORDER BY g.order_id  DESC';
		$condition = $this->get_condition();
		$data = $this->set->show($condition,$orderby,$offset,$count);
		if (!empty($data) && is_array($data))
		{
			foreach ($data as $val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		if ($this->input['k'])
		{
			$condition .= ' AND g.app_name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		return $condition;
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->set->detail($id);
		$this->addItem($data);
		$this->output();
	}
	
	
	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'gather_set g WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	
	//获取应用模块
	public function get_app()	
	{	
		if ($this->settings['App_auth'])
		{
			include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$this->pub = new Auth();
			$app_modules = $this->pub->get_app('id,name,bundle');
			if($app_modules)
			{
				foreach($app_modules as $k=>$v)
				{
					$apps[$v['bundle']] = $v['name'];
				}
			}
			$this->addItem($apps);
		}
		$this->output();
	}
	
	//输出所有分类
	public function show_sort()
	{
		$data = $this->set->show_sort();
		//权限控制
		if (!empty($data) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$nodes = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			foreach ($data as $key=>$val)
			{
				if (!in_array($key, $nodes))
				{
					unset($data[$key]);
				}
			}
		}
		$this->addItem($data);
		$this->output();
	}
		
}
$output = new gatherSet();
if (!method_exists($output, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action =$_INPUT['a'];
}
$output->$action();

