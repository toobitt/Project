<?php
define('MOD_UNIQUEID','member_sign_set');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_sign_set.class.php';

class member_sign extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->signset = new signset();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$this->verify_setting_prms();
		$info 	= $this->signset->show('id,is_on');

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
		$info = $this->signset->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "sign_set WHERE 1 ";
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}


}

$out = new member_sign();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>