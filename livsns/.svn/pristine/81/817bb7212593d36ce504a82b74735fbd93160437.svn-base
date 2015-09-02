<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: weight.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/weight.class.php';
define('MOD_UNIQUEID', 'weightset'); //模块标识

class weightApi extends adminReadBase
{
	private $weight;
	
	public function __construct()
	{
		$this->mPrmsMethods = array(
			'manage'		=>'管理',
		);
		parent::__construct();
		$this->weight = new weightClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->weight);
	}
	
	public function index()
	{
		
	}
	
	/**
	 * 信息列表
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$weight_info = array();
		$weight_info = $this->weight->show($offset, $count, $condition);
		$this->setXmlNode('weight_info', 'weight');
		
		if ($weight_info)
		{
			foreach ($weight_info as $value)
			{
				$this->addItem($value);
			}
		}
		
		$this->output();
	}
	
	/**
	 * 信息数据总数
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->weight->count($condition);
		echo json_encode($info);
	}

	/**
	**	信息编辑
	**/
	public function detail()
	{
		$id = trim($this->input['id']);
		if(!$id){
			$this->errorOutput(OBJECT_NULL);
		}
		
		$info = array();
		$info = $this->weight->detail($id);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 查询条件
	 * @param Array $data
	 */
	private function get_condition()
	{	
		return array(
			'key' => trim(urldecode($this->input['key'])),
		);
	}
}
$out = new weightApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();

?>