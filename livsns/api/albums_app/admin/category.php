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
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//权限验证
			$this->verify_content_prms(array('_action' => 'show'));
		}
		require_once ROOT_PATH . 'frm/node_frm.php';
		$node = new nodeFrm();
		$node->setXmlNode('nodes', 'node');
		$node->setNodeTable('category');
		$node->setNodeID(intval($this->input['fid']));
		$node->addExcludeNodeId($this->input['_exclude']);
		$data = $this->filter_data();
		$condition = $this->api->get_conditions($data);
		$node->getNodeChilds($condition);
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
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//权限验证
			$this->verify_content_prms(array('_action' => 'show'));
		}
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$category_info = $this->api->detail(array('id' => $id));
		$this->addItem($category_info);
		$this->output();
	}
	
	/**
	 * 创建相册分类
	 */
	public function create()
	{
		$category_name = trim(urldecode($this->input['name']));
		if (empty($category_name)) $this->errorOutput(PARAM_WRONG);
		$fid = isset($this->input['fid']) ? intval($this->input['fid']) : 0;
		if ($fid < 0) $this->errorOutput(PARAM_WRONG);
		//判断是否重名
		$info = $this->api->detail(array('name' => $category_name, 'fid' => $fid), 'id');
		if ($info) $this->errorOutput(NAME_EXISTS);
		$brief = isset($this->input['brief']) ? trim(urldecode($this->input['brief'])) : '';
		//权限判断
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action' => 'update'));
			if ($fid === 0) $this->errorOutput(NO_PRIVILEGE);
		}
		$data = array(
			'name' => $category_name,
			'fid' => $fid,
			'brief' => $brief,
			'user_name' => $this->user['user_name'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip()
		);
		$result = $this->api->create($data);
		$this->addItem(array('id' => $result));
		$this->output();
	}
	
	/**
	 * 编辑相册分类
	 */
	public function update()
	{
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//权限验证
			$this->verify_content_prms();
		}
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$category_info = $this->api->detail(array('id' => $id));
		if (!$category_info) $this->errorOutput(PARAM_WRONG);
		$category_name = trim(urldecode($this->input['name']));
		if (empty($category_name)) $this->errorOutput(PARAM_WRONG);
		$validate = array();
		if ($category_name != $category_info['name'])
		{
			//判断是否重名
			$info = $this->api->detail(array(
				'name' => $category_name,
				'fid' => $category_info['fid']
			), 'id');
			if ($info) $this->errorOutput(NAME_EXISTS);
			$validate['name'] = $category_name;
		}
		$validate['brief'] = trim(urldecode($this->input['brief']));
		$validate['update_time'] = TIMENOW;
		$result = $this->api->update('category', $validate, array('id' => $id));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除相册分类
	 */
	public function delete()
	{
		$id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$ids = array_filter($id_arr, 'filter_arr');
		$id = implode(',', $ids);
		if (empty($id)) $this->errorOutput(PARAM_WRONG);
		$info = $this->api->show(array('count' => -1, 'condition' => array('id' => $id)));
		if (!$info) $this->errorOutput(OBJECT_NULL);
		foreach ($info as $v)
		{
			//权限验证
			if ($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				$this->verify_content_prms();
				if ($v['fid'] === 0) $this->errorOutput(NO_PRIVILEGE);
			}
			if (!$v['is_last']) $this->errorOutput('请删除分类下的子分类');
		}
		$result = $this->api->delete(array('id' => $id));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 分类排序
	 */
	public function drag_order()
	{
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//权限验证
			$this->verify_content_prms(array('_action' => 'update'));
		}
		$sort = json_decode(html_entity_decode($this->input['sort']), true);
        if (!empty($sort))
        {
            foreach ($sort as $key => $val)
            {
                $data = array('order_id' => $val);
                if (intval($key) && intval($val))
                {
                	$this->api->update('category', $data, array('id' => $key));
                }
            }
        }
        $this->addItem('success');
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
		$fid = intval($this->input['fid']);
		$data =  array(
			'keyword' => $name,
			'date_search' => $time,
			'start_time' => $start_time,
			'end_time' => $end_time,
			'fid' => $fid
		);
		return $data;
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