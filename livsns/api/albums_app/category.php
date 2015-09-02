<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: category.php 7586 2013-07-05 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/category.class.php';
define('MOD_UNIQUEID', 'category');  //模块标识

class categoryApi extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new category();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	/**
	 * 获取相册分类
	 */
	public function show()
	{
		require_once ROOT_PATH . 'frm/node_frm.php';
		$node = new nodeFrm();
		$node->setXmlNode('nodes', 'node');
		$node->setNodeTable('category');
		$node->setNodeID(intval($this->input['fid']));
		$node->addExcludeNodeId($this->input['_exclude']);
		$data = $this->filter_data();
		$condition = $this->api->get_conditions($data);
		$node->getNodeChilds($condition, false);
		$node->output();
	}
	
	/**
	 * 获取相册分类总数
	 */
	public function count()
	{
		$condition = $this->filter_data();
		$info = $this->api->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 获取单个相册分类
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$category_info = $this->api->detail(array('id' => $id));
		$this->addItem($category_info);
		$this->output();
	}
	
	/**
	 * 过滤查询数据
	 */
	private function filter_data()
	{
		$name = isset($this->input['k']) ? trim(urldecode($this->input['k'])) : '';
		$time = isset($this->input['date_search']) ? intval($this->input['date_search']) : '';
		$start_time = trim($this->input['start_time']);
		$end_time = trim($this->input['end_time']);
		return array(
			'keyword' => $name,
			'date_search' => $time,
			'start_time' => $start_time,
			'end_time' => $end_time
		);
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$out = new categoryApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>