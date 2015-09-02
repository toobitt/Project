<?php
define('MOD_UNIQUEID','copywriting');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require CUR_CONF_PATH . 'lib/copywriting.class.php';
class CopywritingApi extends outerReadBase
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

	public function show()
	{
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->copywriting->show($condition,$offset,$count,'c.name,c.operate,c.icon,c.value');

		if (!empty($info))
		{
			foreach ($info AS $k => $v)
			{
				$this->addItem_withkey($k, $v);
			}
		}

		$this->output();
	}

	public function detail()
	{
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
		$condition = "";
		if ($this->input['operate'])
		{
			$condition .= " AND c.operate = '" . trim($this->input['operate']) . "'";
		}
		
		if ($this->input['field'])
		{
			$condition .= " AND c.field = '" . trim($this->input['field']) . "'";
		}
		

		return $condition;
	}


}

$out = new CopywritingApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>