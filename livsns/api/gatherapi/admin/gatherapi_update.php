<?php
define('MOD_UNIQUEID','gatherUpdate');//模块标识
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/gather.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
include_once CUR_CONF_PATH . 'lib/function.php';
include_once CUR_CONF_PATH . 'lib/gatherapi.class.php';
// require_once CUR_CONF_PATH . 'core/forward.core.php';
class gatherUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->gathercontent=new gatheraccess();
		
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function update(){
		
		$data = array(
			'title'	=>$this->input['title'],
				
		);
		
		$this->addItem(true);
		$this->output();
	}
	
	public function show()
	{
		echo "testapi_update";exit;
	}
	
	public function delete(){
		$id=$this->input[id];
		$gsql="delete from ".DB_PREFIX."gather where id in ($id)";
		$csql="delete from ".DB_PREFIX."content where cid in ($id)";
		$this->db->query($gsql);
		$this->db->query($csql);
		$this->addItem($id);
		$this->output();
	}
	
	public function publish() {
		$id=$this->input[id];
		if(!$id){
			echo 'id null';
			return false;
		}
		$sqlid='select * from '.DB_PREFIX."gather where id in ($id)";
		$resultid=$this->db->query($sqlid);
		while ($row=$this->db->fetch_array($resultid)) {
			$arr1[]=$row;
		}
		
		$sqlcid='select content,othercontent from '.DB_PREFIX."content where cid in ($id)";
		$resultcid=$this->db->query($sqlcid);
		while($row=$this->db->fetch_array($resultcid)){
			$arr2[]=$row;
		}  
		
		foreach ($arr1 as $k => $v) {
			$arrall[]=array_merge($arr1[$k],$arr2[$k]);
		}
	    
		//循环提交数据到采集库
		post_datagather($arrall);
		
		//更新签发状态
		$updata=array(
			'is_publish' => '1'
		);
     	$this->gathercontent->update($updata,$id);
		$this->addItem($arrall);
		$this->output();
		
	}
	
	//编辑
	public function detail()
	{
		
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

	}
	/* (non-PHPdoc)
	 * @see adminUpdateBase::create()
	 */
	public function create() {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see adminUpdateBase::audit()
	 */
	public function audit() {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see adminUpdateBase::sort()
	 */
	public function sort() {
		// TODO Auto-generated method stub
		
	}
}
$out = new gatherUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
