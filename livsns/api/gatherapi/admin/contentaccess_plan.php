<?php
define ( 'MOD_UNIQUEID', 'gather' ); // 模块标识
require_once ('./global.php');
class caccessplan extends adminReadBase {
	public function __construct() {
		parent::__construct ();
	}
	public function __destruct() {
		parent::__destruct ();
	}
    
	 public function show(){
	 	$offset=$this->input['offset'] ? intval($this->input['offset']):0;
		$count=$this->input['count'] ? intval($this->input['count']):20 ;
		
		$sql="select * from ".DB_PREFIX."plan limit $offset".','."$count";
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[] = $row;
		}
		if (!empty($arr))
		{
			foreach ($arr as $val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	 }
	
	 public function detail(){
	 	$id = intval($this->input['id']);
	 	$sql="select * from ".DB_PREFIX."plan where id ='{$id}'";
	 	$query=$this->db->query($sql);
	 	$data=$this->db->fetch_array($query);
	 	$data[next_time]=date('Y-m-d H:i:s',$data[next_time]);
	 	$this->addItem($data);
	 	$this->output();
	 }
	
	 public function count(){
	 	$sql="select count(*) as total from ".DB_PREFIX."plan";
	 	$result=$this->db->query_first($sql);
	 	echo json_encode($result);
	 }
	
	 public function index(){
	 	
	 }
	
}

$out = new caccessplan ();
$action = $_INPUT ['a'];
if (! method_exists ( $out, $action )) {
	$action = 'show';
}
$out->$action ();
?>
