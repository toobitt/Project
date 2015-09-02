<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/dingdone_user.class.php';
define('MOD_UNIQUEID','dingdone_user');//模块标识
class dingdoneUser extends adminReadBase
{
	private $duser;
	
	public function __construct()
	{
		parent::__construct();
		$this->duser = new ClassDingdoneUser();
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
		$orderby = ' ORDER BY u.order_id  DESC';
		$data = $this->duser->show($this->get_condition(),$orderby,$offset,$count);
		if ($data && is_array($data) && !empty($data))
		{
			foreach ($data as $val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = ' ';
		if($this->input['k'])
		{
			$condition .= ' AND u.name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if ($this->input['status'])
		{
			$condition .= ' AND u.status = '.intval($this->input['status']);
		}
		if ($this->input['sort_id'])
		{
			$condition .= ' AND u.sort_id = '.intval($this->input['sort_id']);
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->duser->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->duser->detail($id);
		$this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
		
	}
	
}
$ouput = new dingdoneUser();
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