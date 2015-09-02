<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/iconCategory.class.php';
define('MOD_UNIQUEID', 'app_plant');

class icon_category extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new iconCategory();
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
		$category_info = $this->api->show($data);
		$this->setXmlNode('category_info', 'category');
		if ($category_info)
		{
			foreach ($category_info as $category)
			{
				$this->addItem($category);
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
		$queryData = array('id' => $id);
		$category_info = $this->api->detail('icon_category', $queryData);
		$this->addItem($category_info);
		$this->output();
	}
	
	/**
	 * 创建数据
	 */
	public function create()
	{
		$data = $this->filter_data();
		//验证标识是否重复
		$validateData = array(
		    'mark' => $data['mark']
		);
		$check = $this->api->verify($validateData);
		if ($check > 0) $this->errorOutput(MARK_EXISTS);
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$data['org_id'] = $this->user['org_id'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$result = $this->api->create('icon_category', $data);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 更新数据
	 */
	public function update()
	{
		$id = intval($this->input['id']);
		$queryData = array('id' => $id);
		$info = $this->api->detail('icon_category', $data);
		if (!$info) $this->errorOutput(OBJECT_NULL);
		$data = $this->filter_data();
		$validate = array();
		if ($info['name'] != $data['name'])
		{
		    $validate['name'] = $data['name'];
		}
		if ($info['mark'] != $data['mark'])
		{
		    //验证标识是否重复
    		$validateData = array(
    		    'mark' => $data['mark']
    		);
    		$check = $this->api->verify($validateData);
    		if ($check > 0) $this->errorOutput(MARK_EXISTS);
    		$validate['mark'] = $data['mark'];
		}
		if ($validate)
		{
		    $result = $this->api->update('icon_category', $validate, $queryData);
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
		//删除分类对应的图片
		$this->api->delete('app_material', array('material_id' => $ids));
		//删除分类
		$result = $this->api->delete('icon_category', array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$name = trim(urldecode($this->input['category_name']));
		$mark = trim(urldecode($this->input['category_mark']));
		if (empty($name) || empty($mark))
		{
		    $this->errorOutput(PARAM_WRONG);
		}
		return array(
		    'name' => $name,
		    'mark' => $mark
		);
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		$keyword = trim(urldecode($this->input['k']));
		return array(
			'keyword' => $keyword,
		);
	}
}

$out = new icon_category();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>