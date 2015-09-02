<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/appTemplate.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_template extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appTemplate();
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
		$appTemplate_info = $this->api->show($data);
		$this->setXmlNode('appTemplate_info', 'template');
		if ($appTemplate_info)
		{
			foreach ($appTemplate_info as $template)
			{
				$this->addItem($template);
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
		$appTemplate_info = $this->api->detail('app_template', $data);
		if ($appTemplate_info)
		{
		    if (unserialize($appTemplate_info['pic']))
		    {
		        $appTemplate_info['pic'] = unserialize($appTemplate_info['pic']);
		    }
			$app_id = isset($this->input['app_id']) ? intval($this->input['app_id']) : 0;
			if ($app_id > 0)
			{
			    $queryData = array(
			        'id' => $app_id,
			        'user_id' => $this->user['user_id'],
			        'del' => 0
			    );
			    $app_info = $this->api->detail('app_info', $queryData);
			    if (!$app_info) $app_id = 0;
			}
			$attr_info = $this->api->get_attribute($id, $app_id, true);
			if ($attr_info)
			{
			    $attr_info = $attr_info[$id];
			    $arr = $group_ids = array();
			    foreach ($attr_info as $v)
			    {
			        if ($v['owning_group'])
			        {
    			        $arr[$v['owning_group']][] = $v;
    			        $group_ids[$v['owning_group']] = $v['owning_group'];
			        }
			    }
			    if ($group_ids)
			    {
			        $ids = implode(',', $group_ids);
			        $group_info = $this->api->getGroup($ids);
			        if ($group_info)
			        {
			            foreach ($group_info as $k => $v)
			            {
			                $group_info[$k]['group'] = $arr[$k];
			            }
			            $arr = $group_info;
			        }
			    }
			    if (empty($arr)) $arr = $attr_info;
			    $appTemplate_info['attr'] = $arr;
			}
		}
		$this->addItem($appTemplate_info);
		$this->output();
	}
	
	/**
	 * 保存属性设置的值
	 */
	public function save_attribute()
	{
		$app_id = intval($this->input['id']);
		$temp_id = intval($this->input['t_id']);
		$attrs = $this->input['attr_val'];
		$queryData = array(
		    'id' => $app_id,
		    'user_id' => $this->user['user_id'],
		    'del' => 0,
		    'temp_id' => $temp_id
		);
		$app_info = $this->api->detail('app_info', $queryData);
		if (!$app_info) $this->errorOutput(PARAM_WRONG);
		$attr_id = array_keys($attrs); //获取属性的id
		//查询模板属性表
		$attr_info = $this->api->getTemplateAttr($temp_id);
		$temp_attr_id = array_keys($attr_info);
		//提交的属性不在模板对应的属性里
		if (!array_intersect($attr_id, $temp_attr_id))
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
    		        if (!$checkResult) $this->errorOutput(PROPERTY_AUTH_FAIL);
    		    }
    			$attr_value = is_array($attrs[$v['id']]) ? serialize($attrs[$v['id']]) : $attrs[$v['id']];
    			$content = array('attr_value' => $attr_value);
    			$condition = array('app_id' => $app_id, 'temp_id' => $temp_id, 'attr_id' => $v['id']);
    			$ret = $this->api->detail('temp_value', $condition);
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
    			        $result = $this->api->update('temp_value', $content, $condition);
    			    }
    			    else
    			    {
    			        $result = true;
    			    }
    			}
    			else
    			{
    				$data = array_merge($content, $condition);
    				$result = $this->api->create('temp_value', $data);
    			}
			}
		}
		//将设置为空的属性原有的值置空
		$attrs_arr = $this->api->get_template_attr(array('app_id' => $app_id, 'temp_id' => $temp_id));
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
		            'app_id' => $app_id,
		            'temp_id' => $temp_id,
		            'attr_id' => implode(',', $setEmpty)
		        );
		        $this->api->update('temp_value', array('attr_value' => ''), $updateData);
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
	        if ($def_val && is_array($def_val))
	        {
    	        foreach ($def_val as $v)
    	        {
    	            $defaultVal[] = $v['value'];
    	        }
	        }
	        if (is_scalar($val))
	        {
	            $val = explode(',', $val);
	        }
	        if ($defaultVal && array_diff($val, $defaultVal))
	        {
	            return false;
	        }
	    }
	    elseif ($type == 'range')
	    {
	        if (isset($def_val['from']))
	        {
	            if ($def_val['from']['include'] && $val < $def_val['from']['value'])
	            {
	                return false;
	            }
	            elseif (!$def_val['from']['include'] && $val <= $def_val['from']['value'])
	            {
	                return false;
	            }
	        }
	        if (isset($def_val['to']))
	        {
	            if ($def_val['to']['include'] && $val > $def_val['to']['value'])
	            {
	                return false;
	            }
	            elseif (!$def_val['to']['include'] && $val >= $def_val['to']['value'])
	            {
	                return false;
	            }
	        }
	    }
	    return true;
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		return array();
	}
}

$out = new app_template();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>