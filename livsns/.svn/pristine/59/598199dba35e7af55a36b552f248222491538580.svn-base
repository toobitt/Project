<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/seekhelp_account.class.php';
define('MOD_UNIQUEID','seekhelp_account');//模块标识
class seekhelpAccount extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->account = new ClassSeekhelpAccount();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 10;
		$orderby = ' ORDER BY a.order_id  DESC';
		$data = $this->account->show($this->get_condition(),$orderby,$offset,$count);
		$this->addItem($data);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = ' ';
		if($this->input['k'])
		{
			$condition .= ' AND a.name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if ($this->input['status'])
		{
			$condition .= ' AND a.status = '.intval($this->input['status']);
		}
		if ($this->input['sort_id'])
		{
			$condition .= ' AND a.sort_id = '.intval($this->input['sort_id']);
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->account->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->account->detail($id);
		$this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
		
	}
	
}
$ouput = new seekhelpAccount();
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