<?php
require('./global.php');
define('MOD_UNIQUEID', 'formstyle'); //模块标识
class formstyle extends adminReadBase
{
	
	public function __construct()
	{	
		parent::__construct();	    
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'show'));
		/*********权限验证结束*********/
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{
		
	}
	
	//编目列表
	
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 15;
        $sql = "SELECT id,zh_name FROM " . DB_PREFIX . "style ORDER BY id DESC";
        $sql .= " LIMIT " . $offset . " , " . $count ; //分页
        $q = $this->db->query($sql);
	    while($data = $this->db->fetch_array($q))
	    {	
			$this->addItem($data);
	    }
	    $this->output();       
	}
	
	// 数据总数
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "style";
		$count = $this->db->query_first($sql);
		echo json_encode($count);
	}

	// 取出一条纪录
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "style WHERE id=" . $id;
		$q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
			$this->addItem($data);
		}
		$this->output();
	}
	
	
	//
	private function get_condition()
	{
			
	}
}

$out=new formstyle();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>