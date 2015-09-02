<?php
define('NEED_CHECKIN', true);
define('MOD_UNIQUEID','food_user');
require_once('global.php');
class food_user extends adminReadBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}

	public function show()
	{
	    $offset = $this->input['offset']?intval(($this->input['offset'])):0;
		$count = $this->input['count']?intval(($this->input['count'])):15;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = "SELECT * FROM " . DB_PREFIX . "user  ORDER BY order_id DESC ".$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$this->addItem($r);
		}
		$this->output();
	}

	public function count()
	{
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'user WHERE 1 '.$this->get_condition();
		$total = $this->db->query_first($sql);
		echo json_encode($total);
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id in (".($this->input['id']).")";
		}
		return $condition;
	}
	
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT * FROM "  . DB_PREFIX . "user WHERE id = '" .$this->input['id']. "'";
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$ret[] = $r;
		}
		$this->addItem($ret);
		$this->output();
	}
}

$out = new food_user();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>