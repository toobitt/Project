<?php
require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID', 'plan_node');
class plan_nodeApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	//默认列出顶级节点
	public function show()
	{
		$fid = $this->input['fid']?'0':$this->input['fid'];
		foreach($this->settings['action_type'] as $k=>$v)
		{
			$m = array('id'=>$k,"name"=>$v,"fid"=>$fid,"depth"=>1 ,'is_last'=>1);
			$this->addItem($m);
		}
		$this->output();
	}
	
	public function plan_set()
	{
		$this->setXmlNode('nodes' , 'node');
		$this->setNodeTable('plan_set');
		$this->setNodeID(intval($this->input['fid']));
		$this->addExcludeNodeId($this->input['_exclude']);
		$this->getNodeChilds();
		$this->output();
	}
	
	public function insert_plan()
	{
		$backidarr = array();
		$data = $this->input['data'];
		foreach($data as $k=>$v)
		{
			$indata['fid'] = empty($v['fid'])?0:(empty($data[$v['fid']]['set_id'])?0:$data[$v['fid']]['set_id']);
			
			//先插入节点
			$sort_data = array(
				'ip'=>hg_getip(),
				'create_time'=>TIMENOW,
				'fid'=>$indata['fid'],
				'update_time'=>TIMENOW,
				'name'=>$v['name'],
				'brief'=>'',
				'user_name'=>'',
			);
			$this->initNodeData();
			$this->setNodeTable('plan_set');
			$this->setCondition(",bundle_id='".$v['bundle_id']."',module_id='".$v['module_id']."',struct_id='".$v['struct_id']."',struct_ast_id='".$v['struct_ast_id']."',host='".$v['host']."',path='".$v['path']."',filename='".$v['filename']."',action_insert_contentid='".$v['action_insert_contentid']."',action_get_content='".$v['action_get_content']."',num='".(empty($v['num'])?10:$v['num'])."'");
			$this->setNodeData($sort_data);
			$fid = $this->addNode();
			$data[$k]['set_id'] = $fid;
			$backidarr[$k] = $fid;
		}
		$this->addItem($backidarr);
		$this->output();
	}
		
	public function insert_plan_set()
	{
		$set_fid = urldecode($this->input['set_fid']);
		$name = urldecode($this->input['name']);
		$bundle_id = urldecode($this->input['bundle_id']);
		$module_id = urldecode($this->input['module_id']);
		$struct_id = urldecode($this->input['struct_id']);
		$struct_ast_id = urldecode($this->input['struct_ast_id']);
		$num = intval($this->input['num'])?intval($this->input['num']):10;
		$host = urldecode($this->input['host']);
		$path= urldecode($this->input['path']);
		$filename = urldecode($this->input['filename']);
		$action_get_content = urldecode($this->input['action_get_content']);
		$action_insert_contentid = urldecode($this->input['action_insert_contentid']);
		
		if(empty($name) || empty($num) || empty($host) || empty($path) || empty($filename) || empty($action_get_content) || empty($action_insert_contentid))
		{
			$this->errorOutput('缺少相关参数');
		}

		//先插入节点
		$sort_data = array(
			'ip'=>hg_getip(),
			'create_time'=>TIMENOW,
			'fid'=>$set_fid,
			'update_time'=>TIMENOW,
			'name'=>$name,
			'brief'=>'',
			'user_name'=>'',
		);
		$this->initNodeData();
		$this->setNodeTable('plan_set');
		$this->setCondition(",bundle_id='".$bundle_id."',module_id='".$module_id."',struct_id='".$struct_id."',struct_ast_id='".$struct_ast_id."',host='".$host."',path='".$path."',filename='".$filename."',action_insert_contentid='".$action_insert_contentid."',action_get_content='".$action_get_content."',num='".$num."'");
		$this->setNodeData($sort_data);
		$fid = $this->addNode();
		
	}
	
	public function delete()
	{
		$id = urldecode($this->input['id']);
		//删除节点
		$this->initNodeData();
		$this->setNodeTable('plan_set');
		$this->batchDeleteNode($id);
		$this->addItem('');
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
		
}
//include(ROOT_PATH . 'excute.php');
$out = new plan_nodeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
