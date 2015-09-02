<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 获取mian_ui相关数据
 **************************************************************************/
define('MOD_UNIQUEID', 'get_main_data');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/attribute_value_mode.php');
require_once(CUR_CONF_PATH . 'lib/user_interface_mode.php');

class app_attribute_main extends outerReadBase
{
    private $mode;
    private $ui_mode;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new attribute_value_mode();
        $this->ui_mode = new user_interface_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function detail(){}
    public function count(){}
    public function show(){}
    
    //获取main_ui属性
    public function get_main_attribute()
    {
        $ui_id = $this->input['ui_id'];
        if(!$ui_id)
        {
            $this->errorOutput(NOID);
        }
        
        //首先判断存不存在该UI
        if(!$uiArr = $this->ui_mode->detail($ui_id))
        {
            $this->errorOutput(NOT_EXISTS_UI);
        }
        
        //查询出该UI下对应的属性
        $attrData = $this->mode->getAttributeData($ui_id);
        if(!$attrData)
        {
            $this->errorOutput(NOT_EXISTS_ATTR_IN_UI);
        }
        
        //获取分组数据
        $groupData = $this->mode->getGroupData();
        //输出树形
        $uiArr['attr'] = $this->buildTreeUI(0, $groupData,$attrData);
        $this->addItem($uiArr);
        $this->output();
    }
    
    //按照分组构建树形数据结构共前台ui显示
    private function buildTreeUI($fid,$groupData,$attrData)
    {
        $output = array();
        foreach($groupData AS $k => $v)
    	{
    		if($v['fid'] == $fid)
    		{
    		    $output['node'] = $v['name'];
    			$output['group'] = $attrData[$v['id']];
    			$childs = $this->buildTreeUI($v['id'],$groupData,$attrData);
    			if($childs)
    			{
    			    $output['group'][] = $this->buildTreeUI($v['id'],$groupData,$attrData);
    			}
    			return $output;
    		}
    	}
    	return FALSE;
    }
    
    //按照分组构建树形数据结构共打包使用
    private function buildTreeDataConfig($fid,$groupData,$attrData)
    {
        $output = array();
        foreach($groupData AS $k => $v)
    	{
    		if($v['fid'] == $fid)
    		{
    		    foreach ($attrData[$v['id']] AS $_k => $_v)
    		    {
    		        $output[$v['name']][$_v['uniqueid']] = $_v['attr_default_value'];
    		    }
    		    $childs = $this->buildTreeDataConfig($v['id'],$groupData,$attrData);
    		    if($childs)
    		    {
    		        foreach ($childs AS $kk => $vv)
    		        {
    		            $output[$v['name']][$kk] = $vv;
    		        }
    		    }
    		    return $output;
    		}
    	}
    	return FALSE;
    }
    
    public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new app_attribute_main();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();