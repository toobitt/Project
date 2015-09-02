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
 * @description 获取mian_ui以及list_ui相关数据
 **************************************************************************/
define('MOD_UNIQUEID', 'new_extends');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/attribute_value_mode.php');
require_once(CUR_CONF_PATH . 'lib/user_interface_mode.php');
require_once(CUR_CONF_PATH . 'lib/appModule.class.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');
require_once(CUR_CONF_PATH . 'lib/appMaterial.class.php');
require_once(CUR_CONF_PATH . 'lib/components_mode.php');
require_once(CUR_CONF_PATH . 'lib/superscript_mode.php');
require_once(CUR_CONF_PATH . 'lib/superscript_comp.php');
require_once(CUR_CONF_PATH . 'lib/new_extend.class.php');


class new_extends extends outerReadBase
{
    private $mode;
    private $ui_mode;
    private $app_module;
    private $app;
    private $app_material;
    private $new_extend;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new attribute_value_mode();
        $this->ui_mode = new user_interface_mode();
        $this->app_module = new appModule();
        $this->app = new app();
        $this->app_material = new appMaterial();
        $this->new_extend = new new_extend();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function detail(){}
    public function count(){}
    public function show(){}
    
    /**
     * 获取这个模块下所有的line_info
     */
    public function getAllLineInfoByModuleId()
    {
    	$user_id = intval($this->user['user_id']);
    	$module_id = intval($this->input['module_id']);
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$ret = $this->new_extend->getALLLineInfoByModuleId($user_id,$module_id);
    	$up_num = 0;
    	$down_num = 0;
    	//处理名字
    	if($ret['up'])
    	{
    		$up_num = count($ret['up']);
    		foreach ($ret['up'] as $k => &$v)
    		{
    			$v['show_name'] = "扩展行".($k+1);
    		}
    	}
    	if($ret['down'])
    	{
    		$down_num = count($ret['down']);
    		foreach ($ret['down'] as $_k => &$_v)
    		{
    			$_v['show_name'] = "扩展行".($down_num+$up_num-$_k);
    		}
    	}
    	
    	$info = array();
    	$info['lines'] = $ret;
    	//获取当前模块信息，是否需要设置扩展区域属性
    	$module_info = $this->app_module->detail('app_module', array('id' => $module_id));
    	$list_ui_info = $this->ui_mode->detail($module_info['ui_id']);
    	$info['need_set_position'] = ($list_ui_info['uniqueid'] == $this->settings['new_extend']['need_set_list_ui_uniqueid']) ? 1 : 0;	
    	$info['now_position'] = intval($module_info['extend_area_position']);
    	$this->addItem($info);
    	$this->output();
    }
    
    /**
     * 模块切换listui的时候对所有的line重新设置 设为全部在up中
     */
    public function reOrder()
    {
    	$module_id = intval($this->input['module_id']);
    	$user_id = intval($this->user['user_id']);
    	$all_lines = $this->new_extend->getInfo('new_extend_line',array(
    			'user_id'	=> $user_id,
    			'module_id'	=> $module_id,
    	));
    	if($all_lines)
    	{
    		//位置全部换成up
    		$up_data = array(
    				'line_position'	=>	$this->settings['new_extend']['line_position']['up'],
    		);
    		$up_condition = array(
    				'user_id'	=>	$user_id,
    				'module_id'	=>	$module_id,
    		);
    		$this->new_extend->update('new_extend_line', $up_data, $up_condition);
    	}
    	$temp_up_data = array(
    		'extend_area_position' => $this->settings['new_extend']['extend_area_position']['adaptive'],
    	);
    	$temp_ids_data = array(
    		'id'	=> $module_id,
    		'user_id'	=> $user_id,	
    	);
    	$this->new_extend->update('app_module',$temp_up_data, $temp_ids_data);
    }
    
    /**
     * 获取信息
     */
    public function getNewExtendFieldInfo()
    {
    	$uniqueid = trim($this->input['uniqueid']);
    	$user_id = intval($this->input['user_id']);
    	$info = $this->new_extend->detail('new_extend_field', array(
    			'uniqueid'	=> $uniqueid,
    			'user_id'	=> $user_id,
    	));
    	if($info)
    	{
    		$this->addItem($info);
    	}
    	$this->output();	
    }
    
    public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new new_extends();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();