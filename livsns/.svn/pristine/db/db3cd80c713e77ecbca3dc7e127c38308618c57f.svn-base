<?php
define('MOD_UNIQUEID', 'food');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require_once(ROOT_PATH.'lib/class/curl.class.php');
class food extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(($this->input['offset'])):0;
		$count = $this->input['count']?intval(($this->input['count'])):15;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = "SELECT s.*,d.name AS district_name FROM " . DB_PREFIX . "shops s LEFT JOIN " . DB_PREFIX . "district_node d ON s.district_id = d.id WHERE 1 " .$condition . " ORDER BY order_id DESC,id DESC ".$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$this->addItem($r);
		}
		$this->output();
	}
	
	public function get_condition()
	{
		
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
		
	}
}

$out = new food();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>