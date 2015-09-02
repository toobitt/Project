<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/appAttrGroup.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_attr_group extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appAttrGroup();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	/**
	 * 显示数据
	 */
    public function show()
	{
	    $offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'condition' => $this->condition()
		);
		$attr_group = $this->api->show($data);
		$this->setXmlNode('attr_group', 'group');
		if ($attr_group)
		{
			foreach ($attr_group as $group)
			{
				$this->addItem($group);
			}
		}
		$this->output();
	}
	
	/**
	 * 数据总数
	 */
	public function count()
	{
		$condition = $this->condition();
		$info = $this->api->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 单个数据
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$attr_group = $this->api->detail('attr_group', array('id' => $id));
		$this->addItem($appAttr_info);
		$this->output();
	}
	
	/**
	 * 创建数据
	 */
	public function create()
	{
		$data = $this->filter_data();
		//是否重名
		$checkData = array(
		    'name' => $data['name'],
		    'type' => $data['type']
		);
		$check = $this->api->verify($checkData);
		if ($check > 0) $this->errorOutput(NAME_EXISTS);
		$result = $this->api->create('attr_group', $data);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 更新数据
	 */
	public function update()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$attr_group = $this->api->detail('attr_group', array('id' => $id));
		if (!$attr_group) $this->errorOutput(PARAM_WRONG);
		$data = $this->filter_data();
		$validate = array();
		if ($attr_group['name'] != $data['name'])
		{
			//是否重名
			$checkData = array(
    		    'name' => $data['name'],
    		    'type' => $data['type']
    		);
			$check = $this->api->verify($checkData);
			if ($check > 0) $this->errorOutput(NAME_EXISTS);
			$validate['name'] = $data['name'];
		}
		if ($attr_group['mark'] != $data['mark'])
		{
			$validate['mark'] = $data['mark'];
		}
		if ($attr_group['type'] != $data['type'])
		{
			$validate['type'] = $data['type'];
		}
		if ($validate)
		{
			$result = $this->api->update('attr_group', $validate, array('id' => $id));
		}
		else
		{
			$result = true;
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除数据
	 */
	public function delete()
	{
		$id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$condition = array('attr_id' => $ids);
		$data = array('owning_group' => 0);
		//设置该属性组对应的模板和界面属性值为空
		$this->api->update('temp_attr', $data, $condition);
		$this->api->update('ui_attr', $data, $condition);
		//删除属性组
		$result = $this->api->delete('attr_group', array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$group_name = trim(urldecode($this->input['group_name']));
		$group_mark = trim(urldecode($this->input['group_mark']));
		$group_type = intval($this->input['group_type']);
		$sort_order = intval($this->input['sort_order']);
		if (empty($group_name) || empty($group_mark) || $group_type <= 0)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		if ($sort_order <= 0) $sort_order = 0;
		return array(
		    'name' => $group_name,
		    'mark' => $group_mark,
		    'type' => $group_type,
		    'sort_order' => $sort_order
		);
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		$keyword = trim(urldecode($this->input['k']));
		$type = intval($this->input['flag']);
		return array(
			'keyword' => $keyword,
			'type' => $type
		);
	}
}


function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$out = new app_attr_group();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>