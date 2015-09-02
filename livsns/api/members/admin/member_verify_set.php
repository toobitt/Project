<?php
define('MOD_UNIQUEID','member_verify_set');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_verify_set.class.php';
/**
 * 
 * 因现有需求不需要复杂的实名认证功能，此模块暂时取消 ...
 * @author Ayou
 *
 */
class member_verify extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->verifyset = new verifyset();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$this->verify_setting_prms();
		$info 	= $this->verifyset->show('id,available');

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
		$info = $this->verifyset->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "verify_set WHERE 1 ";
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}


}

$out = new member_verify();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>