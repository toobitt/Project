<?php
require_once './global.php';
class indexTemplateApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建话题
	 */
	public function show()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "index ";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function get_rand()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "index";
		$f = $this->db->query_first($sql);
		$rand = mt_rand(0,$f['total']-1);
		$sql = "SELECT * FROM " . DB_PREFIX . "index LIMIT $rand,1";
		$sen = $this->db->query_first($sql);
		$this->addItem($sen);
		$this->output();
	}
	
	public function detail()
	{
		$con = intval($this->input['id']) ? " WHERE id=" . intval($this->input['id']) : '';
		$sql = "SELECT * FROM " . DB_PREFIX . "index " . $con;
		$f = $this->db->query_first($sql);
		$this->addItem($f);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "index";
		$f = $this->db->query_first($sql);
		$this->addItem($f);
		$this->output();
	}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
}

$out = new indexTemplateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();