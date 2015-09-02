<?php
require('global.php');
define('MOD_UNIQUEID','memcache');//模块标识
class memcacheApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/mcache.class.php');
		$this->obj = new mcache();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$uniqueid = '';
		$appname = $record = array();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$result = $this->obj->get_memcache($offset,$count,$this->get_condition);
		$this->addItem($result);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."memcache WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		$condition = '';
		return $condition;	
	}
	
	public function detail()
	{
		$id = $this->input['id'];
		if($id)
		{
			$info = $this->obj->get_memcache_first($id);
		}
		
		$result['info'] = $info;
		$this->addItem($result);
		$this->output();
	}
	
	public function index(){}
	
}

$out = new memcacheApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			