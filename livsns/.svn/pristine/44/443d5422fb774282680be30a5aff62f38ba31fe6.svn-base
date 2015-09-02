<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/appInterface.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
define('MOD_UNIQUEID', 'dingdone_app');

class app_interface extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appInterface();
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
		$appInterface_info = $this->api->show($data);
		$this->setXmlNode('appInterface_info', 'interface');
		if ($appInterface_info)
		{
			foreach ($appInterface_info as $interface)
			{
				$this->addItem($interface);
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
		$data = array('id' => $id);
		$appInterface_info = $this->api->detail('app_interface', $data);
		if ($appInterface_info)
		{
			if (unserialize($appInterface_info['pic']))
			{
				$appInterface_info['pic'] = unserialize($appInterface_info['pic']);
			}
			//获取对应的属性
			$attr_info = $this->api->get_attribute($id);
			if ($attr_info) $appInterface_info['attr'] = $attr_info;
		}
		$this->addItem($appInterface_info);
		$this->output();
	}
	
	/**
	 * 创建数据
	 */
	public function create()
	{
		$data = $this->filter_data();
		//是否重名
		$verifyData = array('name' => $data['name']);
		$check = $this->api->verify($verifyData);
		if ($check > 0) $this->errorOutput(NAME_EXISTS);
		if ($_FILES['ui_pic'])
		{
			$_FILES['Filedata'] = $_FILES['ui_pic'];
			unset($_FILES['ui_pic']);
			$data['pic'] = $this->upload();
		}
		if ($data['attr_ids']) $attr_ids = $data['attr_ids'];
		unset($data['attr_ids']);
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$data['org_id'] = $this->user['org_id'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$result = $this->api->create('app_interface', $data);
		//绑定属性
		if ($attr_ids) $this->set_attr($result['id'], $attr_ids);
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
		$appInterface_info = $this->api->detail('app_interface', array('id' => $id));
		if (!$appInterface_info) $this->errorOutput(PARAM_WRONG);
		$data = $this->filter_data();
		$validate = array();
		if ($appInterface_info['name'] != $data['name'])
		{
			//是否重名
			$verifyData = array('name' => $data['name']);
			$check = $this->api->verify($verifyData);
			if ($check > 0) $this->errorOutput(NAME_EXISTS);
			$validate['name'] = $data['name'];
		}
		if ($appInterface_info['mark'] != $data['mark'])
		{
			$validate['mark'] = $data['mark'];
		}
		if ($appInterface_info['sort_order'] != $data['sort_order'])
		{
			$validate['sort_order'] = $data['sort_order'];
		}
		if ($_FILES['ui_pic'])
		{
			$_FILES['Filedata'] = $_FILES['ui_pic'];
			unset($_FILES['ui_pic']);
			$validate['pic'] = $this->upload();
		}
		if ($validate || $data['attr_ids'])
		{
			if ($validate)
			{
				$result = $this->api->update('app_interface', $validate, array('id' => $id));
			}
			if ($data['attr_ids'])
			{
				$result = $this->set_attr($id, $data['attr_ids']);
			}
			$this->addItem($result);
		}
		$this->output();
	}
	
	/**
	 * 图片上传
	 */
	public function upload()
	{
		$material = new material();
		$result = $material->addMaterial($_FILES);
		if (!$result) $this->errorOutput(PARAM_WRONG);
		return serialize($result);
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
		$ui_info = $this->api->show(array('count' => -1, 'condition' => array('id' => $ids)));
		if (!$ui_info) $this->errorOutput(PARAM_WRONG);
		$interface_id = array();
		foreach ($ui_info as $ui)
		{
			$interface_id[$ui['id']] = $ui['id'];
		}
		$interface_id = implode(',', $interface_id);
		$interface_info = $this->api->detail('app_module', array('ui_id' => $interface_id));
		if ($interface_info) $this->errorOutput(PARAM_WRONG);
		//删除界面对应的属性
		$this->api->delete('ui_attr', array('ui_id' => $interface_id));
		//删除界面
		$result = $this->api->delete('app_interface', array('id' => $interface_id));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 绑定属性
	 * @param Int $ui_id
	 * @param String $attr_ids
	 */
	private function set_attr($ui_id, $attr_ids)
	{
		$ui_info = $this->api->detail('app_interface', array('id' => $ui_id));
		if (!$ui_info) $this->errorOutput(PARAM_WRONG);
		include_once CUR_CONF_PATH . 'lib/appAttr.class.php';
		$attr = new appAttr();
		$info = $attr->show(array('count' => -1, 'condition' => array('id' => $attr_ids)));
		if (!$info)
		{
			$attr_ids = array();
		}
		else
		{
			$ids = array();
			foreach ($info as $v)
			{
				$ids[] = $v['id'];
			}
			$attr_ids = $ids;
		}
		$attr_info = $this->api->get_attribute($ui_id);
		if ($attr_info)
		{
			$original = array();
			foreach ($attr_info as $attr)
			{
				$original[] = $attr['attr_id'];
			}
			$delete_ids = array_diff($original, $attr_ids);
			$insert_ids = array_diff($attr_ids, $original);
		}
		else
		{
			$insert_ids = $attr_ids;
		}
		if ($delete_ids)
		{
			$data = array(
				'ui_id' => $ui_id,
				'attr_id' => implode(',', $delete_ids)
			);
			$result = $this->api->delete('ui_attr', $data);
		}
		if ($insert_ids)
		{
			foreach ($insert_ids as $id)
			{
				$data = array(
					'ui_id' => $ui_id,
					'attr_id' => $id
				);
				$result = $this->api->create('ui_attr', $data);
			}
		}
		return $result;
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$ui_name = trim($this->input['interface_name']);
		$ui_mark = trim($this->input['interface_mark']);
		$ui_order = intval($this->input['interface_order']);
		$attr_ids = $this->input['attribute_ids'];
		if (empty($ui_name) || empty($ui_mark))
		{
			$this->errorOutput(PARAM_WRONG);
		}
		if ($attr_ids)
		{
			$id_arr = array_filter($attr_ids, 'filter_arr');
			if (!$id_arr) $this->errorOutput(PARAM_WRONG);
			$attr_ids = implode(',', $id_arr);
		}
		$data = array(
			'name' => $ui_name,
			'mark' => $ui_mark,
		    'sort_order' => $ui_order,
			'attr_ids' => $attr_ids
		);
		return $data;
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		return array();
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$out = new app_interface();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>