<?php
define('MOD_UNIQUEID', 'fleamarket');
require('global.php');
class storeApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function index(){}
	public function detail(){}
	public function count(){}
	/**
	 * 路况节点
	 */
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ',' . $count ;
		$sql = "SELECT * FROM " . DB_PREFIX ."group WHERE 1 AND status=1" . $condition . $data_limit;
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['name'] = $row['title'];
			$row['is_last'] = 1;
			$row['fid'] = $row['id'];
			$row['input_k'] = "_id";
			$ret[] = $row;
		}
		if(is_array($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();		
	}
	
	
	public function get_condition()
	{
		$condition = '';
		return $condition;
	}
}

$out = new storeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>