<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/seekhelp_account.class.php';
define('MOD_UNIQUEID','seekhelp_account');//模块标识
class seekhelAccountpApi extends outerReadBase
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
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$orderby = ' ORDER BY order_id  DESC';
		$res = $this->account->show($this->get_condition(),$orderby,$offset,$count);
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = ' AND a.status = 1 ';
		if ($this->input['account_id'])
		{
			$condition .= ' AND a.account_id = '.intval($this->input['account_id']);
		}
		if ($this->input['sort_id'])
		{
			$condition .= ' AND a.sort_id = '.intval($this->input['sort_id']);
		}
		if (isset($this->input['status']))
		{
			$condition .= ' AND sh.status = '.intval($this->input['status']);
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
	
	public function show_reply()
	{
		$id = intval($this->input['account_id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$data = $this->account->show_reply($condition,$offset,$count);
		if($data&&is_array($data))
		{
			foreach ($data as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}	
}
$ouput = new seekhelAccountpApi();
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