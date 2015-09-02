<?php
define('MOD_UNIQUEID','member_credit_rules');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_credit_rules.class.php';
class membercreditrules extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->creditrules = new creditrules();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{	
		$this->verify_setting_prms();
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->creditrules->show($condition,$offset,$count);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}
	
		$this->output();
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			return false;
		}
		$info = $this->creditrules->detail($id);
		$this->addItem($info);
		$this->output();
	}
	/**
	 * 
	 * 获取支持自定义积分规则列表...
	 */
	public function showcredit_rules()
	{
		$condition = ' AND iscustom = 1';
		$info 	= $this->creditrules->show($condition);
		if(is_array($info))
		foreach ($info as $val)
		{
			$this->addItem($val);
		}
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		echo json_encode($this->creditrules->count($condition));
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$binary = '';//不区分大小些
			if(defined('IS_BINARY') && !IS_BINARY)//区分大小些
			{
				$binary = 'binary ';
			}	
			$condition .= ' AND ' . $binary . ' rname like \'%'.trim($this->input['k']).'%\'';
		}
		if (isset($this->input['opened']) && $this->input['opened'] != -1)
		{
			$condition .= " AND opened = " . intval($this->input['opened']);
		}
		if (isset($this->input['iscustom']) && $this->input['iscustom'] != -1)
		{
			$condition .= " AND iscustom = " . intval($this->input['iscustom']);
		}
		if (isset($this->input['cycletype']) && $this->input['cycletype'] != -1)
		{
			$condition .= " AND cycletype = " . intval($this->input['cycletype']);
		}		 
		return $condition;
	}

}

$out = new membercreditrules();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>