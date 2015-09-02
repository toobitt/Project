<?php
require('global.php');
define('MOD_UNIQUEID','data_source');//模块标识
require_once(ROOT_PATH . 'frm/node_frm.php');
class dataSourceApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/data_source.class.php');
		$this->obj = new dataSource();	

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):500;
		$limit = " LIMIT {$offset}, {$count}";
		$data = $this->obj->get_data_source_node($condition,$limit);
		foreach($data as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function get_condition()
	{	
		$condition = ' AND mod_id=1 ';
		$condition .=" AND fid =". intval($this->input['fid']);
		if($this->input['expand_id'])
		{
			$condition .=" AND expand_id=".intval($this->input['expand_id']);
		}
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
		$datasource_id = intval($this->input['data_source_id']);
		$this->initNodeData();
		$this->setNodeTable('out_variable');
		//$this->setCondition(',mod_id=1,expand_id='.$datasource_id.' ');
		$this->setCondition(",mod_id=1,title='".$this->input['title']."',expand_id='".$datasource_id."'".",value='".$this->input['value']."'");
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
			'title' => $this->input['title'],
			'value' => $this->input['value'],
		);
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

$out = new dataSourceApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
