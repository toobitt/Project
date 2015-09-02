<?php
define('MOD_UNIQUEID','member_qdxq');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_qdxq.class.php';
class member_qdxq extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->qdxq = new qdxq();
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
		$info 	= $this->qdxq->show($condition,$offset,$count);

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
		$info = $this->qdxq->detail($id);
		$this->addItem($info);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "sign_emot WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}	

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		return $condition;
	}

}

$out = new member_qdxq();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>