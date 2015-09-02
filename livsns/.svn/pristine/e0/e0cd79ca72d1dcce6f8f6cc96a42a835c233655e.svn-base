<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_library');//模块标识

class libraryPropertyApi extends adminReadBase
{
	private $obj;
	
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/library.class.php');
		$this->obj = new library();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}
	
	/**
	 * 获取所有属性数据
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;			
		$count = isset($this->input['count']) ? intval($this->input['count']) : -1;
		$condition = $this->get_condition();
		$property_info = $this->obj->show_property($offset, $count, $condition);
		$this->setXmlNode('property_info', 'property');
		if ($property_info)
		{
			foreach ($property_info as $property)
			{
				$this->addItem($property);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取所有数据总数
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count_property($condition);
		echo json_encode($info);
	}
	
	/**
	 * 获取单个属性数据
	 */
	public function detail()
	{
		$pid = isset($this->input['pid']) ? intval($this->input['pid']) : '';
		if (empty($pid)) $this->errorOutput(NOID);
		$property = $this->obj->detail_property($pid);
		$this->addItem($property);
		$this->output();
	}
	
	public function index()
	{
		
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		return array(
			'key' => trim(urldecode($this->input['k']))
		);
	}
}

$out = new libraryPropertyApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>