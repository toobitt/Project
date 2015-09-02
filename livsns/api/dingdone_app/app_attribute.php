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
define('MOD_UNIQUEID', 'dingdone_app');

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
		$data = array('id' => $id);
		$appAttr_info = $this->api->detail('app_attribute', $data);
		if (unserialize($appAttr_info['def_val']))
		{
			$appAttr_info['def_val'] = unserialize($appAttr_info['def_val']);
		}
		if ($this->input['ui_id'])
		{
			$data = array(
				'ui_id' => intval($this->input['ui_id']),
				'attr_id' => $id
			);
			$relation = $this->api->detail('ui_attr', $data);
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
			}
		}
		$this->addItem($appAttr_info);
		$this->output();
	}
	
	/**
	 * 保存属性设置的值
	 */
	public function save_attribute()
	{
		$ui_id = intval($this->input['id']);
		$module_id = intval($this->input['module_id']);
		$attrs = $this->input['attr_val'];
		$queryData = array(
		    'id' => $module_id,
		    'user_id' => $this->user['user_id'],
			'ui_id' => $ui_id
		);
		$module_info = $this->api->detail('app_module', $queryData);
		if (!$module_info) $this->errorOutput(PARAM_WRONG);
		$attr_id = array_keys($attrs); //获取属性的id
		//查询界面属性表
		$attr_info = $this->api->getInterfaceAttr($ui_id);
		$ui_attr_id = array_keys($attr_info);
		//提交的属性不在界面对应的属性里
		if (!array_intersect($attr_id, $ui_attr_id))
		{
		    $this->errorOutput(PARAM_WRONG);
		}
		$commit_ids = array();
		foreach ($attr_info as $v)
		{
			if (in_array($v['id'], $attr_id))
			{
    			if ($v['type'] == 'color' && $attrs[$v['id']])
    		    {
    		        if (checkColor($attrs[$v['id']]) === false)
    		        {
    		            $this->errorOutput(COLOR_ERROR);
    		        }
    		    }
    		    $commit_ids[$v['id']] = $v['id'];
    		    $defaultValue = $v['def_val'] ? $v['def_val'] : $v['dVal'];
    		    if (unserialize($defaultValue)) $defaultValue = unserialize($defaultValue);
    		    if (!empty($attrs[$v['id']]) && !empty($defaultValue))
    		    {
    		        //验证数据有效性
    		        $checkResult = $this->checkValidate($attrs[$v['id']], $defaultValue, $v['type']);
    		        if (!$checkResult) $this->errorOutput(PARAM_WRONG);
    		    }
    		    $attr_value = is_array($attrs[$v['id']]) ? serialize($attrs[$v['id']]) : $attrs[$v['id']];
    			$content = array('attr_value' => $attr_value);
				$condition = array('ui_id' => $ui_id, 'attr_id' => $v['id'], 'module_id' => $module_id);
				$ret = $this->api->detail('ui_value', $condition);
				if ($ret)
				{
				    if ($ret['attr_value'] != $attr_value)
    			    {
            			//XXX 是否为图片(混合类型的上传图片暂未支持)
            		    if (($v['type'] == 'singlefile' || $v['type'] == 'multiplefiles'))
            		    {
            		        $material_id = array();
            		        if ($attr_value)
            		        {
                		        //验证图片是否存在
                		        include_once CUR_CONF_PATH . 'lib/appMaterial.class.php';
                		        $material = new appMaterial();
                		        $material_info = $material->show(array('count' => -1, 'condition' => array('id' => $attr_value)));
                		        if (!$material_info && $ret['attr_value']) $this->errorOutput(PARAM_WRONG);
                		        if ($material_info)
                		        {
                    		        foreach ($material_info as $v)
                    		        {
                    		            $material_id[$v['id']] = $v['id'];
                    		        }
                    		        $content['attr_value'] = implode(',', $material_id);
                		        }
            		        }
            		        //将已删除的图片删除
            		        if ($ret['attr_value'])
            		        {
                		        $old_v = explode(',', $ret['attr_value']);
                		        if ($diff = array_diff($old_v, $material_id))
                		        {
                		            $this->api->delete('app_material', array('id' => implode(',', $diff)));
                		        }
            		        }
            		    }
    			        $result = $this->api->update('ui_value', $content, $condition);
    			    }
    			    else
    			    {
    			        $result = true;
    			    }
				}
				else
				{
					$data = array_merge($content, $condition);
					$result = $this->api->create('ui_value', $data);
				}
			}
		}
		//将设置为空的属性原有的值置空
		$attrs_arr = $this->api->get_interface_attr(array('module_id' => $module_id, 'ui_id' => $ui_id));
		if ($attrs_arr)
		{
		    $own_ids = array();
		    foreach ($attrs_arr as $attr)
		    {
		        $own_ids[$attr['attr_id']] = $attr['attr_id'];
		    }
		    $setEmpty = array_diff($own_ids, $commit_ids);
		    if ($setEmpty)
		    {
		        $updateData = array(
		            'module_id' => $module_id,
		            'ui_id' => $ui_id,
		            'attr_id' => implode(',', $setEmpty)
		        );
		        $this->api->update('ui_value', array('attr_value' => ''), $updateData);
		    }
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 验证提交的属性的有效性
	 * @param Int|String $val
	 * @param Int|String|Array $def_val
	 * @param String $type
	 */
	private function checkValidate($val, $def_val, $type)
	{
	    if ($type == 'radio' || $type == 'checkbox' || $type == 'select')
	    {
	        $defaultVal = array();
	        if (!is_array($def_val)) $def_val = (array)$def_val;
	        if ($def_val)
	        {
    	        foreach ($def_val as $v)
    	        {
    	            $defaultVal[] = $v['value'];
    	        }
	        }
	        if (is_string($val))
	        {
	            $val = explode(',', $val);
	        }
	        if (array_diff($val, $defaultVal))
	        {
	            return false;
	        }
	    }
	    return true;
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		return array();
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		return array();
	}
}

/**
 * 检测颜色值的有效性
 */
function checkColor($val)
{
    if (empty($val)) return false;
    if (strpos($val, '#') === false) $val = '#' . $val;
    if (!preg_match('/^#[0-9a-f]{6}|[0-9a-f]{3}$/i', $val)) return false;
    if (strlen($val) == 4)
    {
        $newStr = substr($val, 1);
        $len = strlen($newStr);
        $out = '#';
        for ($i = 0; $i < $len; $i++)
        {
            $color = substr($newStr, $i, 1);
            $out .= str_repeat($color, 2);
        }
        $val = $out;
    }
    return $val;
}

$out = new app_attribute();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>