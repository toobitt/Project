<?php
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/audit.class.php';
define('MOD_UNIQUEID', 'auditset'); //模块标识
class auditApi extends adminReadBase
{	
	public function __construct()
	{
		parent::__construct();
		$this->audit = new Classaudit();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$order = ' ORDER BY order_id DESC';
		$data = $this->audit->show($condition,$order,$offset,$count);		
		if ($data)
		{
			foreach ($data as $val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function count()
	{
		$info = $this->audit->count($this->get_condition());
		echo json_encode($info);
	}

	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}	
		$data = $this->audit->detail($id);
		$this->addItem($data);
		$this->output();
	}
	
	public function get_condition()
	{	
		$condition = '';
		if(trim($this->input['k']))
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		return $condition;
	}
	
	//获取应用模块
	public function get_app()	
	{	
		@include_once(ROOT_PATH . 'lib/class/auth.class.php');
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
		$this->output();
	}
}
$ouput = new auditApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();

?>