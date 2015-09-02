<?php
define('MOD_UNIQUEID','gatherapi_node');
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class gatherapi_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeVar('gatherapi_node');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset=$this->input['offset'] ? intval($this->input['offset']) : 0;
		$count=$this->input['count'] ? intval($this->input['count']) : 20;
	
		$sql="select * from ".DB_PREFIX."plan limit $offset ".','." $count";
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[] = $row;
		}
 		foreach ($arr as $k => $v ) {
 			$arr[$k][createtime]=date('Y-m-d',$v[createtime]);
 			if($v[status]=='1'){
 				$arr[$k][status]='已接入';
 			}else{
 				$arr[$k][status]='未接入';
 			}
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
	
	public function create(){
 
	}
	
	//编辑
	public function detail()
	{
// 		$id=$this->input['id'];
// 		$sql="select g.title,c.* from ".DB_PREFIX."gather g left join ".DB_PREFIX."content c on g.id=c.cid where g.id='{$id}'";
// 		$query=$this->db->query($sql);
// 		while($row=$this->db->fetch_array($query)){
// 			$arr[]=$row;
// 		}
		 
// 		$uns=unserialize($arr[0]['othercontent']);
// 		$arr[0]['othercontent'] = $uns;
// 		$arr=$arr[0];
// 		$this->addItem($arr);
// 		$this->output();
	}
	
	
	public function getSelectedNodes()
	{
	
	}
	
	//获取选中的节点树状路径	
	public function get_selected_node_path()
	{

	}
	
	//获取查询条件
	public function get_condition()
	{
	
	}
	
	//用于分页
	public function count()
	{
		$sql="select count(*) as total from ".DB_PREFIX."plan";
		$result=$this->db->query_first($sql);
		echo json_encode($result);
	}
}

$out=new gatherapi_node();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>