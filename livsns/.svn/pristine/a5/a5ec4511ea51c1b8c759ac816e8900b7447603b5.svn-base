<?php
require('global.php');
define('MOD_UNIQUEID','mode');//模块标识
require_once(ROOT_PATH . 'frm/node_frm.php');
class modeNodeApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/mode.class.php');
		$this->obj = new mode();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " LIMIT {$offset}, {$count}";
		$data = $this->obj->get_mode_node($condition,$limit);
		foreach($data as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function get_condition()
	{	
		$condition = ' AND mod_id=2 ';
		$condition .=" AND fid =". intval($this->input['fid']);
		$condition .=" ORDER BY order_id ";
		return $condition;
	}
	
	public function create()
	{
		//先插入节点
		$sort_data = array(
			'ip'=>hg_getip(),
			'create_time'=>TIMENOW,
			'fid'=>intval($this->input['fid']),
			'update_time'=>TIMENOW,
			'name'=>$this->input['name'],
			'brief'=>'',
			'user_name'=>trim(urldecode($this->user['user_name']))
		);
		//数据源id
		$mode_id = intval($this->input['mode_id']);
		$this->initNodeData();
		$this->setNodeTable('out_variable');
		//$this->setCondition(',mod_id=1,expand_id='.$datasource_id.' ');
		$this->setCondition(",mod_id=2,expand_id='".$mode_id."',flag='".$this->input['flag']."'");
		$this->setNodeData($sort_data);
		$id = $this->addNode();
		$this->addItem($id);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NO_ID');
		}
		$data = array(
			'id' => $id,
			'name' => $this->input['name'],
			'brief' => $this->input['brief'],
			'type' => intval($this->input['variable_type']),
			'value' => $this->input['var_value'],
			'fuction_value' => serialize($this->input['default_value']),
		);
		if( '-1' != $mode_fuction = $this->input['mode_fuction'] )
		{
			$data['mode_fuction']	 = $mode_fuction;
		}
		else
		{
			$data['mode_fuction']	 = '';
		}
		$this->obj->update($data,'out_variable');
	}
	
	public function delete()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput('NO_ID');
		}
		$this->initNodeData();
		$this->setNodeTable('out_variable');
		$this->batchDeleteNode($id);
		$this->addItem(1);
		$this->output();
	}
	
	public function sort()
	{
		$sort = json_decode(html_entity_decode($this->input['sort']),true);
		if(!empty($sort))
		{
			foreach($sort as $k=>$v)
			{
				$data = array(
					'id' => $k,
					'order_id' => $v,
				);
				if(intval($k) && intval($v))
				{
					$this->obj->update($data,'out_variable');
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		$detail = $this->obj->get_node_by_id($id);
		$this->addItem($detail);
		$this->output();
	}
		
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new modeNodeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
