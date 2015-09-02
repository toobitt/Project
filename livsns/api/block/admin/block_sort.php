<?php
require('global.php');
define('MOD_UNIQUEID','block');//模块标识
require_once(ROOT_PATH . 'frm/node_frm.php');
class block_sortApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/block_sort.class.php');
		$this->obj = new block_sort();	

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
		$data = $this->obj->get_block_sort($condition,$limit);
		foreach($data as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function get_condition()
	{	
		$site_id = intval($this->input['site_id']) ? intval($this->input['site_id']) : $this->settings['site_default'];
		$condition .=" AND fid =". intval($this->input['fid']);
		if($site_id)
		{
			$condition .=" AND site_id =". $site_id;
		}
		$condition .=" ORDER BY order_id ";
		return $condition;
	}
	
	public function create()
	{
		$site_id = intval($this->input['siteid']);
		if(!$site_id)
		{
			$this->errorOutput('未选择站点');
		}
		//先插入节点
		$sort_data = array(
			'ip'=>hg_getip(),
			'create_time'=>TIMENOW,
			'fid'=>intval($this->input['fid']),
			'update_time'=>TIMENOW,
			'name'=>trim($this->input['name']),
			'brief'=>'',
			'user_name'=>trim(urldecode($this->user['user_name']))
		);
		$this->initNodeData();
		$this->setNodeTable('block_sort');
		$this->setCondition(',site_id='.$site_id.' ');
		$this->setNodeData($sort_data);
		$id = $this->addNode();
		$tmp['id'] = $id;
		$this->addItem($tmp);
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
			'name' => trim($this->input['name']),
			'brief' => trim($this->input['brief']),
		);
		$this->obj->update($data,'block_sort');
	}
	
	public function delete()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput('NO_ID');
		}
		$this->initNodeData();
		$this->setNodeTable('block_sort');
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
					$this->obj->update($data,'block_sort');
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		$detail = $this->obj->get_sort_by_id($id);
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

$out = new block_sortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
