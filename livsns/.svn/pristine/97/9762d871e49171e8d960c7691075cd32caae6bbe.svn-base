<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/appAttr.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_attribute extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appAttr();
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
		$appAttr_info = $this->api->show($data);
		$this->setXmlNode('appAttr_info', 'attribute');
		if ($appAttr_info)
		{
			foreach ($appAttr_info as $attribute)
			{
				$this->addItem($attribute);
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
		$appAttr_info = $this->api->detail('app_attribute', array('id' => $id));
		if (unserialize($appAttr_info['def_val']))
		{
			$appAttr_info['def_val'] = unserialize($appAttr_info['def_val']);
		}
		$queryData = array('attr_id' => $id);
		if ($this->input['ui_id'])
		{
			$queryData['ui_id'] = intval($this->input['ui_id']);
			$table = 'ui_attr';
		}
		elseif ($this->input['temp_id'])
		{
			$queryData['temp_id'] = intval($this->input['temp_id']);
			$table = 'temp_attr';
		}
		if ($table)
		{
			$relation = $this->api->detail($table, $queryData);
			if ($relation)
			{
				if ($relation['name']) $appAttr_info['name'] = $relation['name'];
				if ($relation['brief']) $appAttr_info['brief'] = $relation['brief'];
				if ($relation['def_val'])
				{
					$appAttr_info['def_val'] = $relation['def_val'];
					if (unserialize($relation['def_val']))
					{
						$appAttr_info['def_val'] = unserialize($relation['def_val']);
					}
				}
				$appAttr_info['sort_order'] = $relation['sort_order'];
				$appAttr_info['owning_group'] = $relation['owning_group'];
			}
		}
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
		$check = $this->api->verify(array('name' => $data['name'], 'flag' => $data['flag']));
		if ($check > 0) $this->errorOutput(NAME_EXISTS);
		if ($data['def_val'] && is_array($data['def_val']))
		{
			$data['def_val'] = serialize($data['def_val']);
		}
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$data['org_id'] = $this->user['org_id'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$data['attr_ids_extends'] = $this->input['ids_extends'];
		$result = $this->api->create('app_attribute', $data);
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
		$appAttr_info = $this->api->detail('app_attribute', array('id' => $id));
		if (!$appAttr_info) $this->errorOutput(PARAM_WRONG);
		$data = $this->filter_data();
		$validate = array();
		if ($appAttr_info['name'] != $data['name'])
		{
			//是否重名
			$check = $this->api->verify(array('name' => $data['name'], 'flag' => $data['flag']));
			if ($check > 0) $this->errorOutput(NAME_EXISTS);
			$validate['name'] = $data['name'];
		}
		if ($appAttr_info['mark'] != $data['mark'])
		{
			$validate['mark'] = $data['mark'];
		}
		if ($appAttr_info['type'] != $data['type'])
		{
			$validate['type'] = $data['type'];
		}
		if ($appAttr_info['brief'] != $data['brief'])
		{
			$validate['brief'] = $data['brief'];
		}
		if ($appAttr_info['flag'] != $data['flag'])
		{
			$validate['flag'] = $data['flag'];
		}
		if($appAttr_info['ids_extends'] != $data['ids_extends']){
			$validate['ids_extends'] = $data['ids_extends'];
		}
		if ($data['def_val'] && is_array($data['def_val']))
		{
			foreach ($data['def_val'] as $k => $val)
			{
				if ($val['type'] == 'image' && empty($val['name']) && $val['pic_id'])
				{
					$pic_info = $this->api->detail('app_material', array('id' => $val['pic_id']));
					if ($pic_info) $data['def_val'][$k]['name'] = $pic_info;
				}
			}
			$data['def_val'] = serialize($data['def_val']);
		}
		if ($appAttr_info['def_val'] != $data['def_val'])
		{
			$validate['def_val'] = $data['def_val'];
		}
		if ($validate)
		{
			$result = $this->api->update('app_attribute', $validate, array('id' => $id));
			if ($validate['def_val'])
			{
				$updateData = array('def_val' => '');
				$updateCondition = array('attr_id' => $id);
				$this->api->update('ui_attr', $updateData, $updateCondition);
				$this->api->update('temp_attr', $updateData, $updateCondition);
			}
		}
		else
		{
			$result = true;
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 设置模板和界面与属性的关系
	 */
	public function update_attr()
	{
	    //属性组
        $group_id = intval($this->input['group_id']);
        if ($group_id <= 0) $group_id = 0;
		if (isset($this->input['ui_id']))
		{
			//界面属性
			$ui_id = intval($this->input['ui_id']);
			$ui_info = $this->api->detail('app_interface', array('id' => $ui_id));
			if (!$ui_info) $this->errorOutput(PARAM_WRONG);
			$condition = array('flag' => 1);
			$table = 'ui_attr';
		}
		elseif (isset($this->input['temp_id']))
		{
			//模板属性
			$temp_id = intval($this->input['temp_id']);
			$temp_info = $this->api->detail('app_template', array('id' => $temp_id));
			if (!$temp_info) $this->errorOutput(PARAM_WRONG);
			$condition = array('flag' => 2);
			$table = 'temp_attr';
		}
		else
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$attr_id = intval($this->input['id']);
		$condition['id'] = $attr_id;
		$attr_info = $this->api->detail('app_attribute', $condition);
		if (!$attr_info) $this->errorOutput(PARAM_WRONG);
		
		$name = trim(urldecode($this->input['attr_name']));
		$brief = trim(urldecode($this->input['attr_brief']));
		if (empty($name)) $this->errorOutput(PARAM_WRONG);
		$attr_type = trim(urldecode($this->input['attr_type']));
		$sort_order = intval($this->input['sort_order']);
		if ($attr_type == 'input' || $attr_type == 'textarea' || $attr_type == 'color')
		{
			$attr_val = trim($this->input['attr_defValue']);
		}
		elseif ($attr_type == 'radio' || $attr_type == 'checkbox' || $attr_type == 'select')
		{
			$attr_arr = array();
			if ($attr_type == 'radio' || $attr_type == 'select')
			{
				$defaultIndex = array($this->input['radioIndex'] => 1);
			}
			else
			{
				$defaultIndex = $this->input['defIndex'];
			}
			if ($this->input['attr_def_name'])
			{
				$attr_def_name = $this->input['attr_def_name'];
				$attr_def_val = $this->input['attr_def_val'];
				foreach ($attr_def_name as $k => $v)
				{
					$attr_arr[] = array(
						'name' => $v,
						'value' => $attr_def_val[$k],
						'type' => 'text',
						'default' => intval($defaultIndex[$k])
					);
				}
			}
			if ($this->input['attr_def_pic_val'])
			{
				$attr_def_name = $this->input['attr_def_pic_val'];
				$attr_def_val = $this->input['attr_def_val'];
				foreach ($attr_def_name as $k => $v)
				{
				    $pic_info = $this->api->detail('app_material', array('id' => $v));
					$attr_arr[] = array(
						'name' => $pic_info,
						'pic_id' => $v,
						'value' => $attr_def_val[$k],
						'type' => 'image',
						'default' => intval($defaultIndex[$k])
					);
				}
			}
			if ($_FILES['attr_def_pic'])
			{
				$attr_def_val = $this->input['attr_def_val'];
				$file = array();
				foreach ($_FILES['attr_def_pic']['name'] as $k => $v)
				{
					$file[] = array(
						'name' => $v,
						'type' => $_FILES['attr_def_pic']['type'][$k],
						'tmp_name' => $_FILES['attr_def_pic']['tmp_name'][$k],
						'error' => $_FILES['attr_def_pic']['error'][$k],
						'size' => $_FILES['attr_def_pic']['size'][$k]
					);
				}
				foreach ($file as $k => $v)
				{
					$_FILES['Filedata'] = $v;
					$pic_info = $this->upload();
					$attr_arr[] = array(
						'name' => $pic_info,
						'pic_id' => $pic_info['id'],
						'value' => $attr_def_val[$k],
						'type' => 'image',
						'default' => intval($defaultIndex[$k])
					);
				}
			}
			$attr_val = $attr_arr;
		}
		elseif ($attr_type == 'mix')
		{
		    $attr_arr = array(
		        'text' => array(
		            'def_value' => trim($this->input['mixText']),
		            'default' => 0
		        ),
		        'file' => array(
		            'def_value' => '',
		            'default' => 0
		        )
		    );
		    if ($_FILES['mixFile'])
		    {
		        $_FILES['Filedata'] = $_FILES['mixFile'];
		        $attr_arr['file']['def_value'] = $this->upload();
		    }
		    $mixType = trim($this->input['mixType']);
		    if (!empty($mixType) && in_array($mixType, array('text', 'file')))
		    {
		        $attr_arr[$mixType]['default'] = 1;
		    }
		    $attr_val = $attr_arr;
		}
		elseif ($attr_type == 'range')
		{
		    $attr_arr = array();
		    $from_value = isset($this->input['rangeFrom']) ? floatval($this->input['rangeFrom']) : false;
		    $to_value = isset($this->input['rangeTo']) ? floatval($this->input['rangeTo']) : false;
		    if ($from_value !== false) $include_from = intval($this->input['includeFrom']);
		    if ($to_value !== false) $include_to = intval($this->input['includeTo']);
		    $def_value = isset($this->input['attr_defValue']) ? floatval($this->input['attr_defValue']) : false;
		    if ($from_value === false && $to_value === false)
		    {
		        $this->errorOutput(PARAM_WRONG);
		    }
		    elseif ($from_value !== false && $to_value !== false)
		    {
    		    //起始值大于终止值则交换两者值
    		    if ($from_value > $to_value)
    		    {
    		        $tmp = $from_value;
    		        $from_value = $to_value;
    		        $to_value = $tmp;
    		    }
    		    if ($def_value !== false && ($def_value < $from_value || $def_value > $to_value || 
    		    ($def_value == $from_value && !$include_from) || 
    		    ($def_value == $to_value && !$include_to)))
    		    {
    		        $this->errorOutput(PARAM_WRONG);
    		    }
    		    $attr_arr['from']['value'] = $from_value;
    		    $attr_arr['to']['value'] = $to_value;
    		    $attr_arr['from']['include'] = $include_from;
    		    $attr_arr['to']['include'] = $include_to;
		    }
		    elseif ($from_value !== false && $to_value === false)
		    {
		        if ($def_value !== false && 
		        ($def_value < $from_value || ($def_value == $from_value && !$include_from)))
		        {
		            $this->errorOutput(PARAM_WRONG);
		        }
		        $attr_arr['from']['value'] = $from_value;
		        $attr_arr['from']['include'] = $include_from;
		    }
		    elseif ($from_value === false && $to_value !== false)
		    {
		        if ($def_value !== false && 
		        ($def_value > $to_value || ($def_value == $to_value && !$include_to)))
		        {
		            $this->errorOutput(PARAM_WRONG);
		        }
		        $attr_arr['to']['value'] = $to_value;
		        $attr_arr['to']['include'] = $include_to;
		    }
		    if ($def_value !== false) $attr_arr['def_value'] = $def_value;
		    $attr_val = $attr_arr;
		}
		if ($attr_val && is_array($attr_val))
		{
			$attr_val = serialize($attr_val);
		}
		else
		{
			$attr_val = trim(urldecode($attr_val));
		}
		$data = array();
		if ($attr_info['name'] != $name)
		{
		    /*
			//是否重名
			$queryData = array('name' => $name);
			//$queryData = array('attr_id' => $attr_id);
			if ($ui_id) $queryData['ui_id'] = $ui_id;
			if ($temp_id) $queryData['temp_id'] = $temp_id;
			$check = $this->api->detail($table, $queryData);
			if ($check) $this->errorOutput(NAME_EXISTS);
			*/
			$data['name'] = $name;
		}
		else
		{
			$data['name'] = '';
		}
		$data['brief'] = ($attr_info['brief'] != $brief) ? $brief : '';
		$data['def_val'] = ($attr_info['def_val'] != $attr_val) ? $attr_val : '';
		$data['sort_order'] = $sort_order > 255 || $sort_order < 0 ? 255 : $sort_order;
		$data['owning_group'] = $group_id;
		$primary_key = array('attr_id' => $attr_id);
		if ($ui_id) $primary_key['ui_id'] = $ui_id;
		if ($temp_id) $primary_key['temp_id'] = $temp_id;
		if ($this->api->detail($table, $primary_key))
		{
			if ($data)
			{
				$result = $this->api->update($table, $data, $primary_key);
			}
			else
			{
				$result = true;
			}
		}
		else
		{
			$data = array_merge($data, $primary_key);
			//创建操作
			$result = $this->api->create($table, $data);
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
		//删除对应模板的属性与值
		$this->api->delete('temp_attr', $condition);
		$this->api->delete('temp_value', $condition);
		//删除对应界面的属性与值
		$this->api->delete('ui_attr', $condition);
		$this->api->delete('ui_value', $condition);
		//删除属性
		$result = $this->api->delete('app_attribute', array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 上传图片
	 */
	public function upload()
	{
		$material = new material();
		$result = $material->addMaterial($_FILES);
		if (!$result) $this->errorOutput(PARAM_WRONG);
		$picData = array(
			'material_id' => $result['id'],
			'name' => $result['name'],
			'mark' => $result['mark'],
			'type' => $result['type'],
			'filesize' => $result['filesize'],
			'imgwidth' => $result['imgwidth'],
			'imgheight' => $result['imgheight'],
			'host' => $result['host'],
			'dir' => $result['dir'],
			'filepath' => $result['filepath'],
			'filename' => $result['filename'],
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'org_id' => $this->user['org_id'],
			'create_time' => $result['create_time'],
			'ip' => $result['ip']
		);
		return $this->api->create('app_material', $picData);
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$attr_name = trim(urldecode($this->input['attr_name']));
		$attr_mark = trim(urldecode($this->input['attr_mark']));
		$attr_type = trim(urldecode($this->input['attr_type']));
		$attr_brief = trim(urldecode($this->input['attr_brief']));
		$attr_ids_extends = trim(urldecode($this->input['attr_ids_extends']));
		$flag = isset($this->input['flag']) ? intval($this->input['flag']) : 1;
		if (empty($attr_name) || empty($attr_mark) || empty($attr_type))
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$data = array(
			'name' => $attr_name,
			'mark' => $attr_mark,
			'type' => $attr_type,
			'brief' => $attr_brief,
			'flag' => $flag,
			'ids_extends' => $attr_ids_extends,
		);
		if ($attr_type == 'input' || $attr_type == 'textarea' || $attr_type == 'color')
		{
			$data['def_val'] = trim($this->input['attr_defValue']);
		}
		elseif ($attr_type == 'radio' || $attr_type == 'checkbox' || $attr_type == 'select')
		{
			$attr_arr = array();
			if ($attr_type == 'radio' || $attr_type == 'select')
			{
				$defaultIndex = array($this->input['radioIndex'] => 1);
			}
			else
			{
				$defaultIndex = $this->input['defIndex'];
			}
			if ($this->input['attr_def_name'])
			{
				$attr_def_name = $this->input['attr_def_name'];
				$attr_def_val = $this->input['attr_def_val'];
				foreach ($attr_def_name as $k => $v)
				{
					$attr_arr[] = array(
						'name' => $v,
						'value' => $attr_def_val[$k],
						'type' => 'text',
						'default' => intval($defaultIndex[$k])
					);
				}
			}
			if ($this->input['attr_def_pic_val'])
			{
				$attr_def_name = $this->input['attr_def_pic_val'];
				$attr_def_val = $this->input['attr_def_val'];
				foreach ($attr_def_name as $k => $v)
				{
					$attr_arr[] = array(
						'name' => '',
						'pic_id' => $v,
						'value' => $attr_def_val[$k],
						'type' => 'image',
						'default' => intval($defaultIndex[$k])
					);
				}
			}
			if ($_FILES['attr_def_pic'])
			{
				$attr_def_val = $this->input['attr_def_val'];
				$file = array();
				foreach ($_FILES['attr_def_pic']['name'] as $k => $v)
				{
					$file[$k] = array(
						'name' => $v,
						'type' => $_FILES['attr_def_pic']['type'][$k],
						'tmp_name' => $_FILES['attr_def_pic']['tmp_name'][$k],
						'error' => $_FILES['attr_def_pic']['error'][$k],
						'size' => $_FILES['attr_def_pic']['size'][$k]
					);
				}
				foreach ($file as $k => $v)
				{
					$_FILES['Filedata'] = $v;
					$pic_info = $this->upload();
					$attr_arr[] = array(
						'name' => $pic_info,
						'pic_id' => $pic_info['id'],
						'value' => $attr_def_val[$k],
						'type' => 'image',
						'default' => intval($defaultIndex[$k])
					);
				}
			}
			$data['def_val'] = $attr_arr;
		}
		elseif ($attr_type == 'mix')
		{
		    $attr_arr = array(
		        'text' => array(
		            'def_value' => trim($this->input['mixText']),
		            'default' => 0
		        ),
		        'file' => array(
		            'def_value' => '',
		            'default' => 0
		        )
		    );
		    if ($_FILES['mixFile'])
		    {
		        $_FILES['Filedata'] = $_FILES['mixFile'];
		        $attr_arr['file']['def_value'] = $this->upload();
		    }
		    $mixType = trim($this->input['mixType']);
		    if (!empty($mixType) && in_array($mixType, array('text', 'file')))
		    {
		        $attr_arr[$mixType]['default'] = 1;
		    }
		    $data['def_val'] = $attr_arr;
		}
		elseif ($attr_type == 'range')
		{
		    $attr_arr = array();
		    $from_value = isset($this->input['rangeFrom']) ? floatval($this->input['rangeFrom']) : false;
		    $to_value = isset($this->input['rangeTo']) ? floatval($this->input['rangeTo']) : false;
		    if ($from_value !== false) $include_from = intval($this->input['includeFrom']);
		    if ($to_value !== false) $include_to = intval($this->input['includeTo']);
		    $def_value = isset($this->input['attr_defValue']) ? floatval($this->input['attr_defValue']) : false;
		    if ($from_value === false && $to_value === false)
		    {
		        $this->errorOutput(PARAM_WRONG);
		    }
		    elseif ($from_value !== false && $to_value !== false)
		    {
    		    //起始值大于终止值则交换两者值
    		    if ($from_value > $to_value)
    		    {
    		        $tmp = $from_value;
    		        $from_value = $to_value;
    		        $to_value = $tmp;
    		    }
    		    if ($def_value !== false && ($def_value < $from_value || $def_value > $to_value || 
    		    ($def_value == $from_value && !$include_from) || 
    		    ($def_value == $to_value && !$include_to)))
    		    {
    		        $this->errorOutput(PARAM_WRONG);
    		    }
    		    $attr_arr['from']['value'] = $from_value;
    		    $attr_arr['to']['value'] = $to_value;
    		    $attr_arr['from']['include'] = $include_from;
    		    $attr_arr['to']['include'] = $include_to;
		    }
		    elseif ($from_value !== false && $to_value === false)
		    {
		        if ($def_value !== false && 
		        ($def_value < $from_value || ($def_value == $from_value && !$include_from)))
		        {
		            $this->errorOutput(PARAM_WRONG);
		        }
		        $attr_arr['from']['value'] = $from_value;
		        $attr_arr['from']['include'] = $include_from;
		    }
		    elseif ($from_value === false && $to_value !== false)
		    {
		        if ($def_value !== false && 
		        ($def_value > $to_value || ($def_value == $to_value && !$include_to)))
		        {
		            $this->errorOutput(PARAM_WRONG);
		        }
		        $attr_arr['to']['value'] = $to_value;
		        $attr_arr['to']['include'] = $include_to;
		    }
		    if ($def_value !== false) $attr_arr['def_value'] = $def_value;
		    $data['def_val'] = $attr_arr;
		}
		return $data;
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		$keyword = trim(urldecode($this->input['k']));
		$flag = intval($this->input['flag']);
		$type = trim(urldecode($this->input['type']));
		return array(
			'keyword' => $keyword,
			'status' => $flag,
			'type' => $type
		);
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$out = new app_attribute();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>