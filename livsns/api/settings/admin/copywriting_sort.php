<?php
define('MOD_UNIQUEID','copywriting_sort');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/copywriting_sort.class.php';
class copywriting_sort_admin extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->copywriting_sort = new copywriting_sort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$this->verify_setting_prms();
		$condition=$this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->copywriting_sort->show($condition,$offset,$count,'id,name,field');

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
		$info = $this->copywriting_sort->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "copywriting_sort WHERE 1 " . $condition;
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

$out = new copywriting_sort_admin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>