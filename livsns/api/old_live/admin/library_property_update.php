<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_update.php 4728 2011-10-12 10:38:02Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_library');//模块标识

class libraryPropertyUpdateApi extends adminUpdateBase
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
	 * 创建属性
	 */
	public function create()
	{
		$data = $this->filter_data();
		//验证属性名称是否已经存在
		$check_result = $this->obj->check_property_exists($data['name']);
		if ($check_result) $this->errorOutput(EXISTS);
		$result = $this->obj->create_property($data);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 修改属性
	 */
	public function update()
	{
		$p_id = isset($this->input['pid']) ? intval($this->input['pid']) : '';
		if (empty($p_id)) $this->errorOutput(NOID);
		//检测属性是否存在
		$property_info = $this->obj->detail_property($p_id);
		if (!$property_info) $this->errorOutput(OBJECT_NULL);
		$data = $this->filter_data();
		$verify_data = array();
		if ($property_info['name'] != $data['name'])
		{
			$check_result = $this->obj->check_property_exists($data['name']);
			if ($check_result) $this->errorOutput(EXISTS);
			$verify_data['name'] = $data['name'];
		}
		if ($verify_data)
		{
			$result = $this->obj->update_property($verify_data, $p_id);
			$this->addItem($result);
		}
		$this->output();
	}
	
	/**
	 * 删除属性
	 */
	public function delete()
	{
		$p_id = isset($this->input['pid']) ? trim(urldecode($this->input['pid'])) : '';
		if (empty($p_id)) $this->errorOutput(NOID);
		if (is_numeric($p_id)) $p_id = intval($p_id);
		//删除属性与类型的关系
		$this->obj->drop_relation(array('property_id' => $p_id));
		//删除属性对应的关系
		$this->obj->delete_link($p_id);
		//删除属性
		$result = $this->obj->delete_property($p_id);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 过滤属性提交数据
	 */
	private function filter_data()
	{
		$property_name = isset($this->input['p_name']) ? trim(urldecode($this->input['p_name'])) : '';
		//$append = isset($this->input['append']) ? trim(urldecode($this->input['append'])) : '';
		if (empty($property_name)) $this->errorOutput(OBJECT_NULL);
		//if (empty($append)) $append = hg_getPinyin(hg_utf82gb($property_name));
		return array(
			'name' => $property_name,
			//'append' => $append
		);
	}
	
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	/**
	 * 调用不存在的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
}

$out = new libraryPropertyUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();
?>