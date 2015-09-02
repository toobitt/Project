<?php
define('MOD_UNIQUEID','member_credit_type');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_credit_type.class.php';
class member_credit_type extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->credittype = new credittype();
		$this->Members = new members();
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

		$info 	= $this->credittype->show($condition);

		if (!empty($info)&&is_array($info))
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
		$info = $this->credittype->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "credit_type WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}

	/**
	 *
	 * 获取已启用的积分类型 ...
	 */
	public function get_credit_type()
	{
		$datas=$this->Members->get_credit_type();		
		if ($datas&&is_array($datas))
		{
			foreach ($datas as $key => $data)
			{
				$this->addItem_withkey($key, $data);
			}
		}
		$this->output();
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		return $condition;
	}

}

$out = new member_credit_type();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>