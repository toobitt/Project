<?php
define('NEED_CHECKIN', true);
define('MOD_UNIQUEID','shops');
require_once('global.php');
class shops extends adminReadBase
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
		$sql = "SELECT s.*,d.name AS district_name FROM " . DB_PREFIX . "shops s LEFT JOIN " . DB_PREFIX . "district_node d ON s.district_id = d.id WHERE 1 " .$condition . " ORDER BY order_id DESC,id DESC ".$limit;
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'shops s WHERE 1 '.$this->get_condition();
		$shops_total = $this->db->query_first($sql);
		echo json_encode($shops_total);
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND s.id in (".($this->input['id']).")";
		}
		return $condition;
	}
	
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT * FROM " .DB_PREFIX. "shops WHERE id = '" .$this->input['id']. "'";
		$ret = $this->db->query_first($sql);
		$this->addItem($ret);
		$this->output();
	}
	
	//查看菜谱
	public function look_up_menu()
	{
		if(!$this->input['shop_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT f.*,c.name AS cname FROM "  . DB_PREFIX . "foods f LEFT JOIN ". DB_PREFIX ."cook_style c ON c.id = f.cook_style_id  WHERE f.shop_id = '" .$this->input['shop_id']. "'";
		$q = $this->db->query($sql);
		$foods = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$foods[] = $r;
		}
		$this->addItem($foods);
		$this->output();
	}
}

$out = new shops();
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