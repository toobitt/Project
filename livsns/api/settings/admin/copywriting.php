<?php
define('MOD_UNIQUEID','copywriting');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/copywriting.class.php';
class copywriting_admin extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->copywriting = new copywriting();
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
		$leftjoin="LEFT JOIN ".DB_PREFIX."copywriting_sort as cs ON c.field = cs.field";
		$info 	= $this->copywriting->show($condition,$offset,$count,'c.id,c.name,c.operate,cs.name as sort_name',$leftjoin);

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
		$info = $this->copywriting->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "copywriting c WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND c.name LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		return $condition;
	}

}

$out = new copywriting_admin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>