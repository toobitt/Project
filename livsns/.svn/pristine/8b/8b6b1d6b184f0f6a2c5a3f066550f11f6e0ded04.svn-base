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
define('MOD_UNIQUEID', 'get_ui_attr_data');
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


class app_attribute_ui extends outerReadBase
{
    private $mode;
    private $ui_mode;
    private $app_module;
    private $app;
    private $app_material;
    private $comp_mode;
    private $corner_mode;
    private $corner_comp;
    private $new_extend;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new attribute_value_mode();
        $this->ui_mode = new user_interface_mode();
        $this->app_module = new appModule();
        $this->app = new app();
        $this->app_material = new appMaterial();
        $this->comp_mode = new components_mode();
        $this->corner_mode = new superscript_mode();
        $this->corner_comp = new superscript_comp();
        $this->new_extend = new new_extend();
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
        
        //获取用户自己设置的值
        $app_id = 0;
        if($this->user['user_id'])
        {
            $app_info = $this->app->getAppInfoByUserId($this->user['user_id']);
            if($app_info)
            {
                $app_id = $app_info['id'];
            }
        }
        
        //查询出该UI下对应的属性
        $attrData = $this->mode->getAttributeData($ui_id,TRUE,$app_id,0,FALSE,$this->input['role_id']);
        if(!$attrData)
        {
            $this->errorOutput(NOT_EXISTS_ATTR_IN_UI);
        }
        
        //获取分组数据
        $groupData = $this->mode->getGroupData();
        //输出树形
        if($this->input['is_front'])
        {
            //配置型
            $uiConfig = $this->buildTreeDataConfig(0, $groupData,$attrData);
            foreach ($uiConfig AS $k => $v)
            {
                foreach ($v AS $_kk => $_vv)
                {
                    $uiArr['attr'][$_kk] = $_vv;
                }
            }
        }
        else //默认树形结构
        {
            $_attrs = $this->buildTreeUI(0, $groupData,$attrData);
            if($_attrs)
            {
                foreach ($_attrs AS $k => $v)
                {
                    if($v['node'] == 'main_ui')
                    {
                        $uiArr['attr'] = $v;
                        break;
                    }
                }
            }
        }
        
        $this->addItem($uiArr);
        $this->output();
    }
    
    //获取模块信息及其属性
    public function get_module_info()
    {
        $module_id = intval($this->input['module_id']);
        if (!$module_id)
        {
            $this->errorOutput(NOID);
        }
        
        $module_info = $this->mode->getModuleInfo($module_id);
        if ($module_info)
        {
            //绑定了LIST_UI
            if ($module_info['ui_id'])
            {
                $groupData = $this->mode->getGroupData();
                $attrData  = $this->mode->getAttributeData($module_info['ui_id']);
                $module_info['attr'] = $this->buildTreeUI(0,$groupData,$attrData);
            }

            //获取正文的模板信息
            if ($module_info['body_tpl_id'])
            {
                $queryData = array(
			        'id' => $module_info['body_tpl_id']
                );
                $tpl_info = $this->app_module->detail('body_tpl', $queryData);
                if ($tpl_info)
                {
                    $tpl_info['html_str'] = html_entity_decode($tpl_info['body_html']);
                    if ($tpl_info['img_info'] && unserialize($tpl_info['img_info']))
                    {
                        $tpl_info['img_info'] = unserialize($tpl_info['img_info']);
                    }
                    $module_info['body_tpl'] = $tpl_info;
                }
            }
            
            //获取第三方接口的信息
            if ($module_info['bind_id'])
            {
                $queryData = array(
                    'id' => $module_info['bind_id']
                );
                $bind_info = $this->app_module->detail('data_bind', $queryData);
                if ($bind_info)
                {
                    $module_info['bind_mark'] = $bind_info['mark'];
                }
            }
        }
        $this->addItem($module_info);
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
    		    $_data = array(
    		        'node' => $v['name'],
    		        'label'=> $v['label'],
    		        'group'=> $attrData[$v['id']], 
    		    );

    		    $childs = $this->buildTreeUI($v['id'],$groupData,$attrData);
    			if($childs)
    			{
    			    $_data['group'][] = $this->buildTreeUI($v['id'],$groupData,$attrData);
    			}
    			$output[] = $_data;
    		}
    	}
    	return $output;
    }
    
    //按照分组构建树形数据结构共打包使用
    private function buildTreeDataConfig($fid,$groupData,$attrData)
    {
        $output = array();
        foreach($groupData AS $k => $v)
    	{
    	    $_data = array();
    		if($v['fid'] == $fid)
    		{
    		    $_data[$v['name']] = array();
    		    if(isset($attrData[$v['id']]) && $attrData[$v['id']])
    		    {
        		    foreach ($attrData[$v['id']] AS $_k => $_v)
        		    {
        		        $_data[$v['name']][$_v['uniqueid']] = $_v['attr_default_value'];
        		    }      		    
    		    }
    		    
    		    $childs = $this->buildTreeDataConfig($v['id'],$groupData,$attrData);
    		    if($childs)
    		    {
    		        foreach ($childs AS $kk => $vv)
    		        {
    		            foreach($vv AS $_kk => $_vv)
    		            {
    		                $_data[$v['name']][$_kk] = $_vv;
    		            }
    		        }
    		    }
    		    
    		    //做一下特殊处理，如果此节点没有子级内容就不输出此节点
    		    if(empty($_data[$v['name']]))
    		    {
    		        continue;
    		    }
    		    $output[] = $_data;
    		}
    	}
    	return $output;
    }
    
    //设置main_ui的值
    public function set_main_ui_value()
    {
        $app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        
        //先判断该用户存不存在这个应用
        $app_info = $this->app->detail('app_info', array(
            'id'      => $app_id,
		    'user_id' => $this->user['user_id'],
		    'del'     => 0,
        ));
        
        if(!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
        
        $attr_value = $this->input['attr_value'];
        if($attr_value)
        {
            //查询出对应的属性的类型
            $relate_ids = array_keys($attr_value);
            $attr_type_arr = $this->mode->getAttrTypeWithids($relate_ids);
            foreach ($attr_value AS $k => $v)
            {
                switch ($attr_type_arr[$k])
                {
                    //文本框
	                case 'textbox':$_value = $v;break;
                    //文本域
        	        case 'textfield':$_value = $v;break;
        	        //单选
        	        case 'single_choice':$_value = $v;break;
        	        //勾选
        	        case 'check':$_value = intval($v);break;
        	        //取值范围
        	        case 'span':$_value = $v;break;
        	        //图片单选
        	        case 'pic_radio':$_value = $v;break;
        	        //图片上传+单选
        	        case 'pic_upload_radio':
        	                                $_img_ids = explode('|', $v);
        	                                if($_img_ids)
        	                                {
            	                                 $_value = serialize(array(
            	                                         'img_ids'  => $_img_ids[1],
            	                                         'selected' => $_img_ids[0],
            	                                 ));
        	                                }
        	                                break;
        	        //多选
        	        case 'multiple_choice':$_value = $v;break;
        	        //拾色器
        	        case 'color_picker':$_value = $v;break;
        	        //高级拾色器
        	        case 'advanced_color_picker':
        	                                    $_color = explode('|', $v);
        	                                    if($_color)
        	                                    {
        	                                        $_value = serialize(array(
        	                                                'color' => $_color[0],
        	                                                'alpha' => $_color[1],
        	                                        ));
        	                                    }
        	                                    break;
        	        //配色方案
        	        case 'color_schemes':$_value = $v;break;
        	        //高级配色方案
        	        case 'advanced_color_schemes':
        	                                    $_color = explode('|', $v);
        	                                    if($_color)
        	                                    {
        	                                        $_value = array();
        	                                        foreach ($_color AS $_kk => $_vv)
        	                                        {
        	                                            $_tmp = explode(':', $_vv);
        	                                            $_value[$_tmp[0]] = $_tmp[1];
        	                                        }
        	                                        $_value = serialize($_value);
        	                                    }
        	                                    break;
        	        //高级背景设置
        	        case 'advanced_background_set':
        	                                   $_bg = explode('|',$v);
        	                                   if($_bg)
        	                                   {
        	                                       if($_bg[0] == 'img')
        	                                       {
                                                       $_value = serialize(array('img_id' => $_bg[1],'is_tile' => $_bg[2]));
        	                                       }
        	                                       elseif($_bg[0] == 'color')
        	                                       {
        	                                           $_value = serialize(array('color' => $_bg[1]));
        	                                       }
        	                                   }
        	                                   break;                  
        	        //高级文字设置
        	        case 'advanced_character_set':
        	                                   $_text = explode('|',$v);
        	                                   if($_text)
        	                                   {
        	                                       if($_text[0] == 'img')
        	                                       {
                                                       $_value = serialize(array('img_id' => $_text[1]));
        	                                       }
        	                                       elseif($_text[0] == 'text')
        	                                       {
        	                                           $_value = serialize(array('text' => $_text[1]));
        	                                       }
        	                                   }
        	                                   break;                   
                }
                
                $this->mode->setMainUIValue(array(
                        'app_id'    => $app_id,
                        'relate_id' => $k,
                        'attr_value'=> $_value,
                ));
            }
        }
        $this->addItem(array('return' => 1));
        $this->output();
    }
    
    //获取LIST_UI属性
    public function get_list_attribute()
    {
        $module_id = $this->input['module_id'];
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        //查询出该模块有没有绑定LIST_UI
        $module_info = $this->app_module->detail('app_module', array('id' => $module_id),'ui_id');
        if(!$module_info)
        {
            $this->errorOutput(MODULE_NOT_EXISTS);
        }
        
        if(!$module_info['ui_id'])
        {
            $this->errorOutput(THIS_MODULE_NOT_BIND_LIST_UI);
        }
        
        //获取LIST_UI
        if(!$ui_info = $this->ui_mode->detail($module_info['ui_id']))
        {
            $this->errorOutput(NOT_EXISTS_UI);
        }
        
        //获取该UI的属性
        $attrData = $this->mode->getAttributeData($module_info['ui_id'],TRUE,'',$module_id,FALSE,$this->input['role_id']);
        if(!$attrData)
        {
            $this->errorOutput(NOT_EXISTS_ATTR_IN_UI);
        }
        
        //获取分组数据
        $groupData = $this->mode->getGroupData();
        //输出树形
        if($this->input['is_front'])
        {
            //配置型
            $uiConfig = $this->buildTreeDataConfig(0, $groupData,$attrData);
            foreach ($uiConfig AS $k => $v)
            {
                foreach ($v AS $_kk => $_vv)
                {
                    $ui_info['attr'][$_kk] = $_vv;
                }
            }
        }
        else //默认树形结构
        {
            $_attrs = $this->buildTreeUI(0, $groupData,$attrData);
            if($_attrs)
            {
                foreach ($_attrs AS $k => $v)
                {
                    if($v['node'] == 'list_ui')
                    {
                        $ui_info['attr'] = $v;
                        break;
                    }
                }
            }
        }
        
        $this->addItem($ui_info);
        $this->output();
    }

    //设置list_ui的值
    public function set_list_ui_value()
    {
        $module_id = $this->input['module_id'];
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }

        //查询出该模块有没有绑定LIST_UI
        $module_info = $this->app_module->detail('app_module', array('id' => $module_id),'ui_id');
        if(!$module_info)
        {
            $this->errorOutput(MODULE_NOT_EXISTS);
        }
        
        if(!$module_info['ui_id'])
        {
            $this->errorOutput(THIS_MODULE_NOT_BIND_LIST_UI);
        }
        
        $attr_value = $this->input['attr_value'];
        if($attr_value)
        {
            //查询出对应的属性的类型
            $relate_ids = array_keys($attr_value);
            $attr_type_arr = $this->mode->getAttrTypeWithids($relate_ids);
            foreach ($attr_value AS $k => $v)
            {
                switch ($attr_type_arr[$k])
                {
                    //文本框
	                case 'textbox':$_value = $v;break;
                    //文本域
        	        case 'textfield':$_value = $v;break;
        	        //单选
        	        case 'single_choice':$_value = $v;break;
        	        //勾选
        	        case 'check':$_value = intval($v);break;
        	        //取值范围
        	        case 'span':$_value = $v;break;
        	        //图片单选
        	        case 'pic_radio':$_value = $v;break;
        	        //图片上传+单选
        	        case 'pic_upload_radio':
        	                                $_img_ids = explode('|', $v);
        	                                if($_img_ids)
        	                                {
            	                                 $_value = serialize(array(
            	                                         'img_ids'  => $_img_ids[1],
            	                                         'selected' => $_img_ids[0],
            	                                 ));
        	                                }
        	                                break;
        	        //多选
        	        case 'multiple_choice':$_value = $v;break;
        	        //拾色器
        	        case 'color_picker':$_value = $v;break;
        	        //高级拾色器
        	        case 'advanced_color_picker':
        	                                    $_color = explode('|', $v);
        	                                    if($_color)
        	                                    {
        	                                        $_value = serialize(array(
        	                                                'color' => $_color[0],
        	                                                'alpha' => $_color[1],
        	                                        ));
        	                                    }
        	                                    break;
        	        //配色方案
        	        case 'color_schemes':$_value = $v;break;
        	        //高级配色方案
        	        case 'advanced_color_schemes':
        	                                    $_color = explode('|', $v);
        	                                    if($_color)
        	                                    {
        	                                        $_value = array();
        	                                        foreach ($_color AS $_kk => $_vv)
        	                                        {
        	                                            $_tmp = explode(':', $_vv);
        	                                            $_value[$_tmp[0]] = $_tmp[1];
        	                                        }
        	                                        $_value = serialize($_value);
        	                                    }
        	                                    break;
        	        //高级背景设置
        	        case 'advanced_background_set':
        	                                   $_bg = explode('|',$v);
        	                                   if($_bg)
        	                                   {
        	                                       if($_bg[0] == 'img')
        	                                       {
                                                       $_value = serialize(array('img_id' => $_bg[1],'is_tile' => $_bg[2]));
        	                                       }
        	                                       elseif($_bg[0] == 'color')
        	                                       {
        	                                           $_value = serialize(array('color' => $_bg[1]));
        	                                       }
        	                                   }
        	                                   break;                  
        	        //高级文字设置
        	        case 'advanced_character_set':
        	                                   $_text = explode('|',$v);
        	                                   if($_text)
        	                                   {
        	                                       if($_text[0] == 'img')
        	                                       {
                                                       $_value = serialize(array('img_id' => $_text[1]));
        	                                       }
        	                                       elseif($_text[0] == 'text')
        	                                       {
        	                                           $_value = serialize(array('text' => $_text[1]));
        	                                       }
        	                                   }
        	                                   break;                   
                }
                
                $this->mode->setListUIValue(array(
                    'module_id' => $module_id,
                    'relate_id' => $k,
                    'attr_value'=> $_value,
                ));
            }
        }
        $this->addItem(array('return' => 1));
        $this->output();
    }
    
    //获取main_ui前台属性
    public function get_front_main_attribute()
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
        
        //获取用户自己设置的值
        $app_id = 0;
        if($this->user['user_id'])
        {
            $app_info = $this->app->getAppInfoByUserId($this->user['user_id']);
            if($app_info)
            {
                $app_id = $app_info['id'];
            }
        }
        
        //查询出该UI下对应的前台属性
        $attrData = $this->mode->getFrontAttributeData($ui_id,$app_id,0,$this->input['role_id']);
        if(!$attrData)
        {
            $this->errorOutput(NOT_EXISTS_ATTR_IN_UI);
        }
        
        $frontAttr = array();
        //按照分组输出
        $groupData = $this->mode->getFrontGroupData();
        $_group_arr = array();
        foreach($groupData AS $k => $v)
        {
            $_group_arr[$v['id']] = $v['name'];
        }
        
        foreach($attrData AS $k => $v)
        {
            $frontAttr[] = array(
                'name' => $_group_arr[$k],
                'group'=> $v,
            );
        }

        $uiArr['attr'] = $frontAttr;
        $this->addItem($uiArr);
        $this->output();
    }
    
    //获取前台LIST_UI属性
    public function get_front_list_attribute()
    {
        $module_id = $this->input['module_id'];
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        //查询出该模块有没有绑定LIST_UI
        $module_info = $this->app_module->detail('app_module', array('id' => $module_id),'ui_id');
        if(!$module_info)
        {
            $this->errorOutput(MODULE_NOT_EXISTS);
        }
        
        if(!$module_info['ui_id'])
        {
            $this->errorOutput(THIS_MODULE_NOT_BIND_LIST_UI);
        }
        
        //获取LIST_UI
        if(!$ui_info = $this->ui_mode->detail($module_info['ui_id']))
        {
            $this->errorOutput(NOT_EXISTS_UI);
        }
        
        //获取该UI的属性
        $attrData = $this->mode->getFrontAttributeData($module_info['ui_id'],'',$module_id,$this->input['role_id']);
        if(!$attrData)
        {
            $this->errorOutput(NOT_EXISTS_ATTR_IN_UI);
        }
        
        $frontAttr = array();
        //按照分组输出
        $groupData = $this->mode->getFrontGroupData();
        if($groupData)
        {
            foreach ($groupData AS $k => $v)
            {
                if(isset($attrData[$v['id']]))
                {
                    $frontAttr[] = array(
                        'name'  => $v['name'],
                        'group' => $attrData[$v['id']],
                    );
                }
            }
        }
        
        $ui_info['attr'] = $frontAttr;
        $this->addItem($ui_info);
        $this->output();
    }
    
    //设置前台Main_ui的属性值
    public function set_front_main_ui_value()
    {
        $app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        
        //先判断该用户存不存在这个应用
        $app_info = $this->app->detail('app_info', array(
            'id'      => $app_id,
		    'user_id' => $this->user['user_id'],
		    'del'     => 0,
        ));
        
        if(!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
        
        $attr_value = $this->input['attr_value'];
        if($attr_value)
        {
            //获取这些属性的信息
            $ids = array_keys($attr_value);
            $attr_data = $this->mode->getFrontAttrByIds($ids);
            if($attr_data)
            {
                foreach ($attr_data AS $k => $v)
                {
                    if(!isset($attr_value[$v['id']]))
                    {
                        continue;
                    }
                    
                    $_value = '';
                    $_front_value = $attr_value[$v['id']];
                    switch ($v['attr_type_name'])
                    {
                        //文本框
    	                case 'textbox':$_value = $_front_value;break;
                        //文本域
            	        case 'textfield':$_value = $_front_value;break;
            	        //单选
            	        case 'single_choice':$_value = $_front_value;break;
            	        //勾选
            	        case 'check':$_value = intval($_front_value);break;
            	        //取值范围
            	        case 'span':$_value = $_front_value;break;
            	        //图片单选
            	        case 'pic_radio':$_value = $_front_value;break;
            	        //图片上传+单选
            	        case 'pic_upload_radio':
            	                                $_img_ids = explode('|', $_front_value);
            	                                if($_img_ids)
            	                                {
                	                                 $_value = serialize(array(
                	                                         'img_ids'  => $_img_ids[1],
                	                                         'selected' => $_img_ids[0],
                	                                 ));
            	                                }
            	                                break;
            	        //多选
            	        case 'multiple_choice':$_value = $_front_value;break;
            	        //拾色器
            	        case 'color_picker':$_value = $_front_value;break;
            	        //高级拾色器
            	        case 'advanced_color_picker':
            	                                    $_color = explode('|', $_front_value);
            	                                    if($_color)
            	                                    {
            	                                        $_value = serialize(array(
            	                                                'color' => $_color[0],
            	                                                'alpha' => $_color[1],
            	                                        ));
            	                                    }
            	                                    break;
            	        //配色方案
            	        case 'color_schemes':$_value = $_front_value;break;
            	        //高级配色方案
            	        case 'advanced_color_schemes':
            	                                    $_color = explode('|', $_front_value);
            	                                    if($_color)
            	                                    {
            	                                        $_value = array();
            	                                        foreach ($_color AS $_kk => $_vv)
            	                                        {
            	                                            $_tmp = explode(':', $_vv);
            	                                            $_value[$_tmp[0]] = $_tmp[1];
            	                                        }
            	                                        $_value = serialize($_value);
            	                                    }
            	                                    break;
            	        //高级背景设置
            	        case 'advanced_background_set':
            	                                   $_bg = explode('|',$_front_value);
            	                                   if($_bg)
            	                                   {
            	                                       if($_bg[0] == 'img')
            	                                       {
                                                           $_value = serialize(array('img_id' => $_bg[1],'is_tile' => $_bg[2]));
            	                                       }
            	                                       elseif($_bg[0] == 'color')
            	                                       {
            	                                           $_value = serialize(array('color' => $_bg[1],'alpha' => $_bg[2]));
            	                                       }
            	                                   }
            	                                   break;                  
            	        //高级文字设置
            	        case 'advanced_character_set':
            	                                   $_text = explode('|',$_front_value);
            	                                   if($_text)
            	                                   {
            	                                       if($_text[0] == 'img')
            	                                       {
                                                           $_value = serialize(array('img_id' => $_text[1]));
            	                                       }
            	                                       elseif($_text[0] == 'text')
            	                                       {
            	                                           $_value = serialize(array('text' => $_text[1]));
            	                                       }
            	                                   }
            	                                   
                                                   if(!$_value)
            	                                   {
            	                                       $_value = serialize(array('text' => ''));
            	                                   }
            	                                   break;                   
                    }
                    
                    $this->mode->setFrontMainUIValue(array(
                            'app_id'     => $app_id,
                            'ui_attr_id' => $v['id'],
                            'attr_value' => $_value,
                    ));
                    
                    //设置完前台属性值还要根据关系设置后台属性的值
                    
                    //对关联的后台属性统一设值
                    if($v['set_value_type'] == 1)
                    {
                        $this->mode->setFrontAttrSameToRelate($v['id'],$_value,$app_id);
                    }
                    elseif($v['set_value_type'] == 2)//对关联属性分别设置
                    {
                        $this->mode->setFrontAttrEachToRelate($v['id'],$_value,$app_id);
                    }
                }
            }
        }
        
        $this->addItem(array('return' => 1));
        $this->output();
    }
    
    //设置前台LIST_UI的值
    public function set_front_list_ui_value()
    {
        $module_id = $this->input['module_id'];
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }

        //查询出该模块有没有绑定LIST_UI
        $module_info = $this->app_module->detail('app_module', array('id' => $module_id),'ui_id');
        if(!$module_info)
        {
            $this->errorOutput(MODULE_NOT_EXISTS);
        }
        
        if(!$module_info['ui_id'])
        {
            $this->errorOutput(THIS_MODULE_NOT_BIND_LIST_UI);
        }
        
        $attr_value = $this->input['attr_value'];
        if($attr_value)
        {
            //获取这些属性的信息
            $ids = array_keys($attr_value);
            $attr_data = $this->mode->getFrontAttrByIds($ids);
            if($attr_data)
            {
                foreach ($attr_data AS $k => $v)
                {
                    if(!isset($attr_value[$v['id']]))
                    {
                        continue;
                    }
                    
                    $_value = '';
                    $_front_value = $attr_value[$v['id']];
                    switch ($v['attr_type_name'])
                    {
                        //文本框
    	                case 'textbox':$_value = $_front_value;break;
                        //文本域
            	        case 'textfield':$_value = $_front_value;break;
            	        //单选
            	        case 'single_choice':$_value = $_front_value;break;
            	        //勾选
            	        case 'check':$_value = intval($_front_value);break;
            	        //取值范围
            	        case 'span':$_value = $_front_value;break;
            	        //图片单选
            	        case 'pic_radio':$_value = $_front_value;break;
            	        //图片上传+单选
            	        case 'pic_upload_radio':
            	                                $_img_ids = explode('|', $_front_value);
            	                                if($_img_ids)
            	                                {
                	                                 $_value = serialize(array(
                	                                         'img_ids'  => $_img_ids[1],
                	                                         'selected' => $_img_ids[0],
                	                                 ));
            	                                }
            	                                break;
            	        //多选
            	        case 'multiple_choice':$_value = $_front_value;break;
            	        //拾色器
            	        case 'color_picker':$_value = $_front_value;break;
            	        //高级拾色器
            	        case 'advanced_color_picker':
            	                                    $_color = explode('|', $_front_value);
            	                                    if($_color)
            	                                    {
            	                                        $_value = serialize(array(
            	                                                'color' => $_color[0],
            	                                                'alpha' => $_color[1],
            	                                        ));
            	                                    }
            	                                    break;
            	        //配色方案
            	        case 'color_schemes':$_value = $_front_value;break;
            	        //高级配色方案
            	        case 'advanced_color_schemes':
            	                                    $_color = explode('|', $_front_value);
            	                                    if($_color)
            	                                    {
            	                                        $_value = array();
            	                                        foreach ($_color AS $_kk => $_vv)
            	                                        {
            	                                            $_tmp = explode(':', $_vv);
            	                                            $_value[$_tmp[0]] = $_tmp[1];
            	                                        }
            	                                        $_value = serialize($_value);
            	                                    }
            	                                    break;
            	        //高级背景设置
            	        case 'advanced_background_set':
            	                                   $_bg = explode('|',$_front_value);
            	                                   if($_bg)
            	                                   {
            	                                       if($_bg[0] == 'img')
            	                                       {
                                                           $_value = serialize(array('img_id' => $_bg[1],'is_tile' => $_bg[2]));
            	                                       }
            	                                       elseif($_bg[0] == 'color')
            	                                       {
            	                                           $_value = serialize(array('color' => $_bg[1],'alpha' => $_bg[2]));
            	                                       }
            	                                   }
            	                                   break;                  
            	        //高级文字设置
            	        case 'advanced_character_set':
            	                                   $_text = explode('|',$_front_value);
            	                                   if($_text)
            	                                   {
            	                                       if($_text[0] == 'img')
            	                                       {
                                                           $_value = serialize(array('img_id' => $_text[1]));
            	                                       }
            	                                       elseif($_text[0] == 'text')
            	                                       {
            	                                           $_value = serialize(array('text' => $_text[1]));
            	                                       }
            	                                   }
            	                                   
                                                   if(!$_value)
            	                                   {
            	                                       $_value = serialize(array('text' => ''));
            	                                   }
            	                                   break;                   
                    }
                    
                    $this->mode->setFrontListUIValue(array(
                            'module_id'  => $module_id,
                            'ui_attr_id' => $v['id'],
                            'attr_value' => $_value,
                    ));
                    
                    //设置完前台属性值还要根据关系设置后台属性的值
                    
                    //对关联的后台属性统一设值
                    if($v['set_value_type'] == 1)
                    {
                        $this->mode->setFrontAttrSameToRelate($v['id'],$_value,0,$module_id);
                    }
                    elseif($v['set_value_type'] == 2)//对关联属性分别设置
                    {
                        $this->mode->setFrontAttrEachToRelate($v['id'],$_value,0,$module_id);
                    }
                }
            }
        }
        
        $this->addItem(array('return' => 1));
        $this->output();
    }
    
    /**********************************************组件相关listUI方法***************************************************/
    //设置组件对应前台的listUI的配置的值
    public function set_front_comp_list_ui_value()
    {
        $comp_id = $this->input['comp_id'];
        if(!$comp_id)
        {
            $this->errorOutput(NO_COMP_ID);
        }
        
        //查询出改组件的信息
        $comp_info = $this->comp_mode->detail($comp_id);
        if(!$comp_info)
        {
            $this->errorOutput(COMP_NOT_EXTSTS);
        }
        
        if(!$comp_info['listui_mark'])
        {
            $this->errorOutput(THIS_COMP_NOT_BIND_LIST_UI);
        }
        
        //获取LIST_UI
        if(!$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$comp_info['listui_mark']. "' AND is_comp = 1 "))
        {
            $this->errorOutput(NOT_EXISTS_UI);
        }
        
        $attr_value = $this->input['attr_value'];
        if($attr_value)
        {
            //获取这些属性的信息
            $ids = array_keys($attr_value);
            $attr_data = $this->mode->getFrontAttrByIds($ids);
            if($attr_data)
            {
                foreach ($attr_data AS $k => $v)
                {
                    if(!isset($attr_value[$v['id']]))
                    {
                        continue;
                    }
                    
                    $_value = '';
                    $_front_value = $attr_value[$v['id']];
                    switch ($v['attr_type_name'])
                    {
                        //文本框
    	                case 'textbox':$_value = $_front_value;break;
                        //文本域
            	        case 'textfield':$_value = $_front_value;break;
            	        //单选
            	        case 'single_choice':$_value = $_front_value;break;
            	        //勾选
            	        case 'check':$_value = intval($_front_value);break;
            	        //取值范围
            	        case 'span':$_value = $_front_value;break;
            	        //图片单选
            	        case 'pic_radio':$_value = $_front_value;break;
            	        //图片上传+单选
            	        case 'pic_upload_radio':
            	                                $_img_ids = explode('|', $_front_value);
            	                                if($_img_ids)
            	                                {
                	                                 $_value = serialize(array(
                	                                         'img_ids'  => $_img_ids[1],
                	                                         'selected' => $_img_ids[0],
                	                                 ));
            	                                }
            	                                break;
            	        //多选
            	        case 'multiple_choice':$_value = $_front_value;break;
            	        //拾色器
            	        case 'color_picker':$_value = $_front_value;break;
            	        //高级拾色器
            	        case 'advanced_color_picker':
            	                                    $_color = explode('|', $_front_value);
            	                                    if($_color)
            	                                    {
            	                                        $_value = serialize(array(
            	                                                'color' => $_color[0],
            	                                                'alpha' => $_color[1],
            	                                        ));
            	                                    }
            	                                    break;
            	        //配色方案
            	        case 'color_schemes':$_value = $_front_value;break;
            	        //高级配色方案
            	        case 'advanced_color_schemes':
            	                                    $_color = explode('|', $_front_value);
            	                                    if($_color)
            	                                    {
            	                                        $_value = array();
            	                                        foreach ($_color AS $_kk => $_vv)
            	                                        {
            	                                            $_tmp = explode(':', $_vv);
            	                                            $_value[$_tmp[0]] = $_tmp[1];
            	                                        }
            	                                        $_value = serialize($_value);
            	                                    }
            	                                    break;
            	        //高级背景设置
            	        case 'advanced_background_set':
            	                                   $_bg = explode('|',$_front_value);
            	                                   if($_bg)
            	                                   {
            	                                       if($_bg[0] == 'img')
            	                                       {
                                                           $_value = serialize(array('img_id' => $_bg[1],'is_tile' => $_bg[2]));
            	                                       }
            	                                       elseif($_bg[0] == 'color')
            	                                       {
            	                                           $_value = serialize(array('color' => $_bg[1],'alpha' => $_bg[2]));
            	                                       }
            	                                   }
            	                                   break;                  
            	        //高级文字设置
            	        case 'advanced_character_set':
            	                                   $_text = explode('|',$_front_value);
            	                                   if($_text)
            	                                   {
            	                                       if($_text[0] == 'img')
            	                                       {
                                                           $_value = serialize(array('img_id' => $_text[1]));
            	                                       }
            	                                       elseif($_text[0] == 'text')
            	                                       {
            	                                           $_value = serialize(array('text' => $_text[1]));
            	                                       }
            	                                   }
            	                                   
                                                   if(!$_value)
            	                                   {
            	                                       $_value = serialize(array('text' => ''));
            	                                   }
            	                                   break;                   
                    }
                    
                    //设置前台的值
                    $this->mode->setFrontCompListUIValue(array(
                            'comp_id'    => $comp_id,
                            'ui_attr_id' => $v['id'],
                            'attr_value' => $_value,
                    ));
                    
                    //设置完前台属性值还要根据关系设置后台属性的值
                    
                    //对关联的后台属性统一设值
                    if($v['set_value_type'] == 1)
                    {
                        $this->mode->setFrontCompAttrSameToRelate($v['id'],$_value,$comp_id);
                    }
                    elseif($v['set_value_type'] == 2)//对关联属性分别设置
                    {
                        $this->mode->setFrontCompAttrEachToRelate($v['id'],$_value,$comp_id);
                    }
                }
            }
        }
        
        $this->addItem(array('return' => 1));
        $this->output();
    }
    
    //获取组件对应前台的listUI的配置的值
    public function get_front_comp_list_ui_value()
    {
        $comp_id = $this->input['comp_id'];
        if(!$comp_id)
        {
            $this->errorOutput(NO_COMP_ID);
        }
        
        //查询出改组件的信息
        $comp_info = $this->comp_mode->detail($comp_id);
        if(!$comp_info)
        {
            $this->errorOutput(COMP_NOT_EXTSTS);
        }
        
        if(!$comp_info['listui_mark'])
        {
            $this->errorOutput(THIS_COMP_NOT_BIND_LIST_UI);
        }
        
        //获取LIST_UI
        if(!$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$comp_info['listui_mark']. "' AND is_comp = 1 "))
        {
            $this->errorOutput(NOT_EXISTS_UI);
        }
        
        //获取该UI的属性
        $attrData = $this->mode->getFrontCompAttributeData($ui_info['id'],$comp_id,$this->input['role_id']);
        if(!$attrData)
        {
            $this->errorOutput(NOT_EXISTS_ATTR_IN_UI);
        }
        
        $frontAttr = array();
        //按照分组输出
        $groupData = $this->mode->getFrontGroupData();
        if($groupData)
        {
            foreach ($groupData AS $k => $v)
            {
                if(isset($attrData[$v['id']]))
                {
                    $frontAttr[] = array(
                        'name'  => $v['name'],
                        'group' => $attrData[$v['id']],
                    );
                }
            }
        }
        
        $ui_info['attr'] = $frontAttr;
        $this->addItem($ui_info);
        $this->output();
    }
    /**********************************************组件相关listUI方法***************************************************/

    /**********************************************角标相关listUI方法***************************************************/
    public function get_corner_attr_value()
    {
        $user_id = intval($this->user['user_id']);
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //模块ID
        $module_id = intval($this->input['module_id']);
	    if(!$module_id)
	    {
	        $this->errorOutput(NO_MODULE_ID);
	    }
	    
        //获取角标LIST_UI
        if(!$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$this->settings['superscript']['corner_list_ui']. "' AND is_corner = 1 "))
        {
            $this->errorOutput(NOT_EXISTS_UI);
        }
	    
	    //首先查看该模块有没有使用角标
	    $_cond = " AND module_id = '" . $module_id . "' AND user_id = '" .$user_id. "' order by order_id desc ";
	    $mod_corner = $this->corner_mode->getModCornerByCond($_cond,'id,superscript_id');
	    
	    //模块类型还是组件 1为模块 2为组价
	    $type = 1;
	    
	    //如果存在，就说明该模块使用了角标，就需要找出每一个使用的角标对应的属性值
	    if($mod_corner)
	    {
	        foreach($mod_corner AS $k => $_mod_cor)
	        {
	            //获取该UI的属性
                $attrData = $this->mode->getFrontCornerAttributeData($ui_info['id'],$_mod_cor['id'],$this->input['role_id'],$type);
                if(!$attrData)
                {
                    continue;
                }
                $frontAttr = array(
                    'mod_corner_id'    => $_mod_cor['id'],
                    'superscript_id'   => $_mod_cor['superscript_id'],
                    'group'            => $attrData,
                );
                $ui_info['attr'][] = $frontAttr;
	        }
	    }
	    
	    $this->addItem($ui_info);
        $this->output();
    }
    
    //设置某个角标设置的属性值
    public function set_corner_attr_value()
    {
        $user_id = intval($this->user['user_id']);
        if(!$user_id)
        {
             $this->errorOutput(NO_LOGIN);
        }
        
        $module_id = intval($this->input['module_id']);
	    if(!$module_id)
	    {
	         $this->errorOutput(NO_MODULE_ID);
	    }
	    
        $show_type = intval($this->input['show_type']);
	    if(!$show_type || !in_array($show_type, array(1,2)))
	    {
	        $this->out_error(NO_SHOW_TYPE);
	    }
	    
	    //验证这个模块是不是这个人的
	    $isExistsMod = $this->app_module->detail('app_module', array(
	                            'id'      => $module_id,
	                            'user_id' => $user_id,
	    ),'id');
	    
	    if(!$isExistsMod)
	    {
	        $this->errorOutput(THIS_MOUDLE_NOT_EXIST);
	    }
	    
	    $attr_value = $this->input['corner_attrs_value'];
	    //判断用户设置的角标个数有没有超过限制
	    if($attr_value && !is_array($attr_value) && (count($attr_value) > $this->settings['superscript']['max_num']))
	    {
	        $this->errorOutput(CORNER_NUM_IS_OVER);
	    }
	    
	    //模块类型还是组件 1为模块 2为组价
	    $type = 1;
	    
	    //然后根据这个模块获取当前这个模块使用的角标的id
	    $modCornerIds = $this->corner_mode->getModCornerByCond(" AND module_id = '" .$module_id. "' ",'id','id');
	    if($modCornerIds)
	    {
	        $modCornerIdsStr = implode(',', array_keys($modCornerIds));
	        //首先删除这个模块使用的角标情况
	        $this->corner_mode->deleteModCornerByCond(" AND module_id = '" .$module_id. "' ");
	        $this->corner_mode->deleteFrontCornerAttrByCond(" AND mod_corner_id IN (" .$modCornerIdsStr. ") AND type = ".$type);
	        $this->corner_mode->deleteCornerAttrByCond(" AND mod_corner_id IN (" .$modCornerIdsStr. ") AND type = ".$type);
	    }
	    
        if($attr_value && is_array($attr_value))
        {
            foreach ($attr_value AS $_kk => $_vv)
            {
                //首先产生一条模块与角标的关系
                $mod_corner_id = $this->corner_mode->createModCorner(array(
                        'module_id'      => $module_id,
                        'superscript_id' => $_vv['id'],
                        'user_id'		 => $this->user['user_id'],
                        'user_name'		 => $this->user['user_name'],
                        'create_time'    => TIMENOW,
                ));
                
                if(!$mod_corner_id)
                {
                    continue;
                }
                
                //创建好之后，保存用户设置的属性值
                if($_vv['attr'] && is_array($_vv['attr']))
                {
                    $ids = array_keys($_vv['attr']);
                    $attr_data = $this->mode->getFrontAttrByIds($ids);
                    if($attr_data)
                    {
                        foreach ($attr_data AS $k => $v)
                        {
                            if(!isset($_vv['attr'][$v['id']]))
                            {
                                continue;
                            }
                            
                            $_value = '';
                            $_front_value = $_vv['attr'][$v['id']];
                            switch ($v['attr_type_name'])
                            {
                                //文本框
            	                case 'textbox':$_value = $_front_value;break;
                                //文本域
                    	        case 'textfield':$_value = $_front_value;break;
                    	        //单选
                    	        case 'single_choice':$_value = $_front_value;break;
                    	        //勾选
                    	        case 'check':$_value = intval($_front_value);break;
                    	        //取值范围
                    	        case 'span':$_value = $_front_value;break;
                    	        //图片单选
                    	        case 'pic_radio':$_value = $_front_value;break;
                    	        //图片上传+单选
                    	        case 'pic_upload_radio':
                    	                                $_img_ids = explode('|', $_front_value);
                    	                                if($_img_ids)
                    	                                {
                        	                                 $_value = serialize(array(
                        	                                         'img_ids'  => $_img_ids[1],
                        	                                         'selected' => $_img_ids[0],
                        	                                 ));
                    	                                }
                    	                                break;
                    	        //多选
                    	        case 'multiple_choice':$_value = $_front_value;break;
                    	        //拾色器
                    	        case 'color_picker':$_value = $_front_value;break;
                    	        //高级拾色器
                    	        case 'advanced_color_picker':
                    	                                    $_color = explode('|', $_front_value);
                    	                                    if($_color)
                    	                                    {
                    	                                        $_value = serialize(array(
                    	                                                'color' => $_color[0],
                    	                                                'alpha' => $_color[1],
                    	                                        ));
                    	                                    }
                    	                                    break;
                    	        //配色方案
                    	        case 'color_schemes':$_value = $_front_value;break;
                    	        //高级配色方案
                    	        case 'advanced_color_schemes':
                    	                                    $_color = explode('|', $_front_value);
                    	                                    if($_color)
                    	                                    {
                    	                                        $_value = array();
                    	                                        foreach ($_color AS $_kk => $_vv)
                    	                                        {
                    	                                            $_tmp = explode(':', $_vv);
                    	                                            $_value[$_tmp[0]] = $_tmp[1];
                    	                                        }
                    	                                        $_value = serialize($_value);
                    	                                    }
                    	                                    break;
                    	        //高级背景设置
                    	        case 'advanced_background_set':
                    	                                   $_bg = explode('|',$_front_value);
                    	                                   if($_bg)
                    	                                   {
                    	                                       if($_bg[0] == 'img')
                    	                                       {
                                                                   $_value = serialize(array('img_id' => $_bg[1],'is_tile' => $_bg[2]));
                    	                                       }
                    	                                       elseif($_bg[0] == 'color')
                    	                                       {
                    	                                           $_value = serialize(array('color' => $_bg[1],'alpha' => $_bg[2]));
                    	                                       }
                    	                                   }
                    	                                   break;                  
                    	        //高级文字设置
                    	        case 'advanced_character_set':
                    	                                   $_text = explode('|',$_front_value);
                    	                                   if($_text)
                    	                                   {
                    	                                       if($_text[0] == 'img')
                    	                                       {
                                                                   $_value = serialize(array('img_id' => $_text[1]));
                    	                                       }
                    	                                       elseif($_text[0] == 'text')
                    	                                       {
                    	                                           $_value = serialize(array('text' => $_text[1]));
                    	                                       }
                    	                                   }
                    	                                   
                                                           if(!$_value)
                    	                                   {
                    	                                       $_value = serialize(array('text' => ''));
                    	                                   }
                    	                                   break;                   
                            }
                            
                            
                            //设置前台的值
                            $this->mode->setFrontCornerListUIValue(array(
                                    'mod_corner_id'     => $mod_corner_id,
                                    'ui_attr_id'        => $v['id'],
                                    'attr_value'        => $_value,
                            		'type'				=> $type,
                            ));
                            
                            //设置完前台属性值还要根据关系设置后台属性的值
                            
                            //对关联的后台属性统一设值
                            if($v['set_value_type'] == 1)
                            {
                                $this->mode->setFrontCornerAttrSameToRelate($v['id'],$_value,$mod_corner_id,$type);
                            }
                            elseif($v['set_value_type'] == 2)//对关联属性分别设置
                            {
                                $this->mode->setFrontCornerAttrEachToRelate($v['id'],$_value,$mod_corner_id,$type);
                            }
                        }
                    }
                }
            }
        }
        
        //设置完了之后，更新针对这个模块的corner_show_type
        $this->app_module->update('app_module', array(
                        'corner_show_type' => $show_type,
        ), array(
                        'id' => $module_id,
        ));

        $this->addItem(array('return' => 1));
        $this->output();
    }
    
    //获取角标前台基础属性
    public function get_corner_base_attr()
    {
        //获取角标LIST_UI
        if(!$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$this->settings['superscript']['corner_list_ui']. "' AND is_corner = 1 "))
        {
            $this->errorOutput(NOT_EXISTS_UI);
        }
        
        //获取基础的角标属性
        $attrData = $this->mode->getFrontCornerAttributeData($ui_info['id'],0,$this->input['role_id']);
        if($attrData)
        {
            $this->addItem($attrData);
            $this->output();
        }
        else 
        {
            $this->errorOutput(NO_DATA);
        }
    }
    /**********************************************角标相关listUI方法***************************************************/
    
    
    /**********************************************角标相关组件方法end**************************************************************/

    /**
     * 获取
     */
    
    public function get_components_corner_value()
    {
    	$user_id = intval($this->user['user_id']);
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	
    	//模块ID
    	$comp_id = intval($this->input['comp_id']);
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMPONENTS_ID);
    	}
    	 
    	//获取角标LIST_UI
    	if(!$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$this->settings['superscript']['corner_list_ui']. "' AND is_corner = 1 "))
    	{
    		$this->errorOutput(NOT_EXISTS_UI);
    	}
    	 
    	//首先查看该组件有没有使用角标
    	$_cond = " AND comp_id = '" . $comp_id . "' AND user_id = '" .$user_id. "' order by order_id desc ";
    	$comp_corner = $this->corner_comp->getModCornerByCond($_cond,'id,superscript_id');
    	 
    	//模块类型还是组件 1为模块 2为组价
    	$type = 2;
    	 
    	//如果存在，就说明该模块使用了角标，就需要找出每一个使用的角标对应的属性值
    	if($comp_corner)
    	{
    		foreach($comp_corner AS $k => $_mod_cor)
    		{
    			//获取该UI的属性
    			$attrData = $this->mode->getFrontCornerAttributeData($ui_info['id'],$_mod_cor['id'],$this->input['role_id'],$type);
    			if(!$attrData)
    			{
    				continue;
    			}
    			$frontAttr = array(
    					'mod_corner_id'    => $_mod_cor['id'],
    					'superscript_id'   => $_mod_cor['superscript_id'],
    					'group'            => $attrData,
    			);
    			$ui_info['attr'][] = $frontAttr;
    		}
    	}
    	 
    	$this->addItem($ui_info);
    	$this->output();
    	
    } 
    
    /**
     * 保存组件的角标的属性值
     */
    public function set_components_corner_value()
    {
    	$user_id = intval($this->user['user_id']);
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	
    	$comp_id = intval($this->input['comp_id']);
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMPONENTS_ID);
    	}
    	 
    	$show_type = intval($this->input['show_type']);
    	if(!$show_type || !in_array($show_type, array(1,2)))
    	{
    		$this->out_error(NO_SHOW_TYPE);
    	}
    	 
    	//验证这个组件是不是这个人的
    	$isExistsMod = $this->comp_mode->detail('',' and id = '.$comp_id.' and user_id = '.$user_id);    	 
    	if(!$isExistsMod)
    	{
    		$this->errorOutput(COMP_NOT_EXTSTS);
    	}
    	$attr_value = $this->input['corner_attrs_value'];
    	//判断用户设置的角标个数有没有超过限制
    	if($attr_value && !is_array($attr_value) && (count($attr_value) > $this->settings['superscript']['max_num']))
    	{
    		$this->errorOutput(CORNER_NUM_IS_OVER);
    	}

    	//模块类型还是组件 1为模块 2为组价
    	$type = 2;
    	
    	//根据这个组件获取当前这个组件使用的角标的id
    	$compCornerIds = $this->corner_comp->getModCornerByCond(' and comp_id = '.$comp_id , 'id' , 'id');
//     	file_put_contents('33333.txt', var_export($compCornerIds,1));
    	if($compCornerIds)
    	{
    		$compCornerIdsStr = implode(',', array_keys($compCornerIds));
    		//首先删除这个组件中使用的角标
    		$this->corner_comp->deleteCompCornerByCond(' and comp_id = '.$comp_id);
    		$this->corner_comp->deleteFrontCornerAttrByCond(" AND mod_corner_id IN (" .$compCornerIdsStr. ") AND type =".$type);
    		$this->corner_comp->deleteCornerAttrByCond(" AND mod_corner_id IN (" .$compCornerIdsStr. ") AND type =".$type);
    	}
   	
//     	file_put_contents('222222.txt', var_export($attr_value,1));
    	if($attr_value && is_array($attr_value))
    	{
    		foreach ($attr_value AS $_kk => $_vv)
    		{
//     			file_put_contents('11111.txt', var_export($_vv,1));
    			//首先产生一条模块与角标的关系
    			$mod_corner_id = $this->corner_comp->createModCorner(array(
    					'comp_id'        => $comp_id,
    					'superscript_id' => $_vv['id'],
    					'user_id'		 => $this->user['user_id'],
    					'user_name'		 => $this->user['user_name'],
    					'create_time'    => TIMENOW,
    			));
    	
    			if(!$mod_corner_id)
    			{
    				continue;
    			}
    	
    			//创建好之后，保存用户设置的属性值
    			if($_vv['attr'] && is_array($_vv['attr']))
    			{
    				$ids = array_keys($_vv['attr']);
    				$attr_data = $this->mode->getFrontAttrByIds($ids);
    				if($attr_data)
    				{
    					foreach ($attr_data AS $k => $v)
    					{
    						if(!isset($_vv['attr'][$v['id']]))
    						{
    							continue;
    						}
    	
    						$_value = '';
    						$_front_value = $_vv['attr'][$v['id']];
    						switch ($v['attr_type_name'])
    						{
    							//文本框
    							case 'textbox':$_value = $_front_value;break;
    							//文本域
    							case 'textfield':$_value = $_front_value;break;
    							//单选
    							case 'single_choice':$_value = $_front_value;break;
    							//勾选
    							case 'check':$_value = intval($_front_value);break;
    							//取值范围
    							case 'span':$_value = $_front_value;break;
    							//图片单选
    							case 'pic_radio':$_value = $_front_value;break;
    							//图片上传+单选
    							case 'pic_upload_radio':
    								$_img_ids = explode('|', $_front_value);
    								if($_img_ids)
    								{
    									$_value = serialize(array(
    											'img_ids'  => $_img_ids[1],
    											'selected' => $_img_ids[0],
    									));
    								}
    								break;
    								//多选
    							case 'multiple_choice':$_value = $_front_value;break;
    							//拾色器
    							case 'color_picker':$_value = $_front_value;break;
    							//高级拾色器
    							case 'advanced_color_picker':
    								$_color = explode('|', $_front_value);
    								if($_color)
    								{
    									$_value = serialize(array(
    											'color' => $_color[0],
    											'alpha' => $_color[1],
    									));
    								}
    								break;
    								//配色方案
    							case 'color_schemes':$_value = $_front_value;break;
    							//高级配色方案
    							case 'advanced_color_schemes':
    								$_color = explode('|', $_front_value);
    								if($_color)
    								{
    									$_value = array();
    									foreach ($_color AS $_kk => $_vv)
    									{
    										$_tmp = explode(':', $_vv);
    										$_value[$_tmp[0]] = $_tmp[1];
    									}
    									$_value = serialize($_value);
    								}
    								break;
    								//高级背景设置
    							case 'advanced_background_set':
    								$_bg = explode('|',$_front_value);
    								if($_bg)
    								{
    									if($_bg[0] == 'img')
    									{
    										$_value = serialize(array('img_id' => $_bg[1],'is_tile' => $_bg[2]));
    									}
    									elseif($_bg[0] == 'color')
    									{
    										$_value = serialize(array('color' => $_bg[1],'alpha' => $_bg[2]));
    									}
    								}
    								break;
    								//高级文字设置
    							case 'advanced_character_set':
    								$_text = explode('|',$_front_value);
    								if($_text)
    								{
    									if($_text[0] == 'img')
    									{
    										$_value = serialize(array('img_id' => $_text[1]));
    									}
    									elseif($_text[0] == 'text')
    									{
    										$_value = serialize(array('text' => $_text[1]));
    									}
    								}
    	
    								if(!$_value)
    								{
    									$_value = serialize(array('text' => ''));
    								}
    								break;
    						}
    	
    						//设置前台的值
    						$this->mode->setFrontCornerListUIValue(array(
    								'mod_corner_id'     => $mod_corner_id,
    								'ui_attr_id'        => $v['id'],
    								'attr_value'        => $_value,
    								'type'				=> $type,
    						));
    	
    						//设置完前台属性值还要根据关系设置后台属性的值
    	
    						//对关联的后台属性统一设值
    						if($v['set_value_type'] == 1)
    						{
    							$this->mode->setFrontCornerAttrSameToRelate($v['id'],$_value,$mod_corner_id,$type);
    						}
    						elseif($v['set_value_type'] == 2)//对关联属性分别设置
    						{
    							$this->mode->setFrontCornerAttrEachToRelate($v['id'],$_value,$mod_corner_id,$type);
    						}
    					}
    				}
    			}
    		}
    	}
    	
    	//设置完了之后，更新针对这个组件的corner_show_type
    	$this->comp_mode->update($comp_id,array(
    		'corner_show_type' => $show_type,
    	));    	
    	
    	
    	$this->addItem(array('return' => 1));
    	$this->output();	
    }
    
    /**********************************************角标相关组件方法end**************************************************************/
    
    /**
     * 获取这个listUi用到的所有的前端属性 
     */
    public function getListUiAttr()
    {
    	$module_id = intval($this->input['module_id']);
    	//查询出该模块有没有绑定LIST_UI
    	$module_info = $this->app_module->detail('app_module', array('id' => $module_id),'ui_id');
    	if(!$module_info['ui_id'])
    	{
    		$this->errorOutput(THIS_MODULE_NOT_BIND_LIST_UI);
    	}
		$attrData = $this->mode->getALListAttrByUiId($module_info['ui_id'],$this->input['role_id']);
		$this->addItem($attrData);
		$this->output();
    }
    
    
    
    /**********************************************新扩展字段**************************************************************/
    
    /**
     * 设置模块扩展区域属性
     */
    public function setNewExtendAreaPosition()
    {
    	$module_id = intval($this->input['module_id']);
    	$area_position = intval($this->input['area_position']);
    	$user_id = intval($this->user['user_id']);
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	if($area_position != 1 && $area_position != 2)
    	{
    		$this->errorOutput(AREA_POSITION_WRONG);		
    	}
    	
    	//如果是换成固定高度，，验证此模块是否支持
    	if($area_position == $this->settings['new_extend']['extend_area_position']['fixed'])
    	{
    		//获取模块信息
    		$module_info = $this->app_module->detail('app_module', array('id' => $module_id));
    		$ui_id = $module_info['ui_id'];
    		$list_ui_info = $this->ui_mode->detail($ui_id);
    		if($list_ui_info['uniqueid'] != $this->settings['new_extend']['need_set_list_ui_uniqueid'])
    		{
    			$this->errorOutput(MODULE_LISTUI_WORING);
    		}
    	}
    	
    	$data = array(
    		'extend_area_position'	=> $area_position,
    	);
    	$idsArr = array(
    		'id'		=> $module_id,
    		'user_id'	=> $user_id,
    	);
    	$ret = $this->new_extend->update('app_module', $data, $idsArr);
    	
    	//同时更新这个模块的已经添加的扩展行的信息
    	//先获取所有的扩展行信息
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
    	$this->addItem(array('return' => 1));
    	$this->output();
    }  
    
    
    /**
     * 设置扩展行的信息
     */	
    public function setNewExtendLineInfo()
    {
    	$user_id = intval($this->user['user_id']);
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$module_id = intval($this->input['module_id']);
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	
    	//验证这个模块是不是这个人的
    	$isExistsMod = $this->app_module->detail('app_module', array(
    			'id'      => $module_id,
    			'user_id' => $user_id,
    	),'id'); 
    	if(!$isExistsMod)
    	{
    		$this->errorOutput(THIS_MOUDLE_NOT_EXIST);
    	}
    	
    	$attr_value = $this->input['attr_value'];
    	$line_num = intval($this->input['line_num']);
    	$line_type = intval($this->input['line_type']);
    	if($line_type == $this->settings['new_extend']['line_type']['one'])
    	{
    		$line_num = 1;
    	}
    	$line_position = intval($this->input['line_position']); 	
    	//获取line_id 有就是编辑 如果没有则是添加
    	$line_id = intval($this->input['line_id']);
    	if($line_id)
    	{
    		//有line_id 则进行编辑
    		//先获取当前line_info 判断是否需要对扩展行下的单元进行处理
    		$old_line_info = $this->new_extend->detail('new_extend_line',array(
    					'module_id'	=> $module_id,
    					'user_id'	=> $user_id, 
    					'id'	=> $line_id,				
    		));
    		//如果line_type变了，，则要对这个扩展行下的单元进行处理
    		if($old_line_info['line_type'] != $line_type)
    		{
    			//获取目前这个行里所有的单元
    			$all_fields = $this->new_extend->getInfo('new_extend_field',array(
    						'module_id'	=> $module_id,
    						'line_id'	=> $line_id,
    						'user_id'	=> $user_id,
    			));
    			if($all_fields)//存在单元则处理
    			{
    				//两种情况，1、原本单行现在多行
    				if($line_type == 2)
    				{
    					$left_fields = $this->new_extend->getInfo('new_extend_field',array(
    							'field_position' => $this->settings['new_extend']['field_position']['left'],
    							'line_id'		 => $line_id,
    							'user_id'	     => $user_id,
    							'module_id'		 => $module_id,	
    					));
    					//又有两种情况，1、设了左边
    					if($left_fields)
    					{
    						//最左的不变，将剩下的field_position 改为右
    						$temp_left_est_field = $this->new_extend->getOne($module_id,$line_id,'asc',$this->settings['new_extend']['field_position']['left']);
    						$left_est_field = $temp_left_est_field[0];
    						foreach ($all_fields as $___k => $___v)
    						{
    							if($___v['id'] != $left_est_field['id'])
    							{
    								$this->new_extend->update('new_extend_field', array(
    									'field_position'	=> $this->settings['new_extend']['field_position']['right'],
    								), array(
    									'id'		=> $___v['id'],
    									'line_id'	=> $line_id,
    									'module_id'	=> $module_id,
    									'user_id'	=> $user_id,
    								));
    							}
    						}   						
    					}
    					else//2、原本只设了右边，没有左边
    					{
    						//最右的一个field_position改为左，其他不变
    						$temp_right_est_field = $this->new_extend->getAllFields($module_id,$line_id,'asc');
    						$right_est_field = $temp_right_est_field[0];
    						$this->new_extend->update('new_extend_field', array(
    								'field_position'	=> $this->settings['new_extend']['field_position']['left'],
    						), array(
    								'id'		=> $___v['id'],
    								'line_id'	=> $line_id,
    								'module_id'	=> $module_id,
    								'user_id'	=> $user_id,
    						));
    					}	
    				}
    				elseif ($line_type == 1)//2、原本多行 现在单行
    				{
    					//将所有扩展单元设置居左
    					$left_data = array(
    						'field_position'	=> $this->settings['new_extend']['field_position']['left'],
    					);
    					$left_condition = array(
    						'user_id'	=> $user_id,
    						'module_id'	=> $module_id,
    						'line_id'	=> $line_id,
    					);
    					$this->new_extend->update('new_extend_field', $left_data, $left_condition);
    				}
    			}
    		}	
    		//先更新line_info的基本信息
    		$update_line_infodata = array(
    			'line_num'		=> $line_num,
    			'line_type'		=> $line_type,
    			'line_position'	=> $line_position,
    		);
    		$idsArr = array(
    			'id'		=> $line_id,
    			'module_id'	=> $module_id,
    			'user_id'	=> $user_id,
    		);
    		$this->new_extend->update('new_extend_line', $update_line_infodata, $idsArr);
    		//更新属性
    		if($attr_value && is_array($attr_value))
    		{
    			$ids = array_keys($attr_value);
    			$attr_data = $this->mode->getFrontAttrByIds($ids);
    			if($attr_data)
    			{
    				foreach ($attr_data as $__k => $__v)
    				{
    					if(!isset($attr_value[$__v['id']]))
    					{
    						continue;
    					}
    					 
    					$_value = '';
    					$_front_value = $attr_value[$__v['id']];
    					switch ($__v['attr_type_name'])
    					{
    						//取值范围
    						case 'span':$_value = trim($_front_value);break;
    					}
    					//先编辑前台的值
    					$updata_data = array(
    						'attr_value'	=> $_value,
    					);
    					$ids_arr = array(
    						'line_id'		=> $line_id,
    						'ui_attr_id'	=> $__v['id'],
    						'module_id'		=> $module_id,
    						'user_id'		=> $user_id,
    					);
    					$this->new_extend->update('new_extend_line_ui_attr_value', $updata_data, $ids_arr);		 
    					//编辑完前台属性值还要根据关系编辑后台属性的值
    					$set_type = 'update'; 
    					//对关联的后台属性统一编辑值
    					if($__v['set_value_type'] == 1)
    					{
    						$this->new_extend->setNewExtendAttrSameToRelate($__v['id'] , $_value , $line_id , $set_type);
    					}
    				}
    			}			
    		}  
    		$this->addItem(array(
    			'line_id' 	=> $line_id,
    		));  		
    	}
    	else
    	{
    		//如果没有line_id，添加新扩展行,判断当前模块有多少扩展行了，最多XX个
    		$all_line_info = $this->new_extend->getInfo('new_extend_line',array(
    				'user_id'		=> $user_id,
    				'module_id'		=> $module_id,
    			)
    		);
    		if(count($all_line_info) >= $this->settings['new_extend']['extend_line_max_num'])
    		{
    			$this->errorOutput(EXTEND_LINE_NUM_MAX);
    		}
    		
    		//先创建一条extend_line的信息
    		$new_line_info = $create_line = $this->new_extend->create('new_extend_line', array(
    				'create_time'	=> TIMENOW,
    				'module_id'		=> $module_id,
    				'line_num'		=> $line_num,
    				'line_type'		=> $line_type,
    				'line_position'	=> $line_position,
    				'user_id'		=> $user_id,	
    			)
    		);
    		//将order_id更新进去
    		$up_order_arr = array(
    			'module_id'	=> $module_id,
    			'id'		=> $new_line_info['id'],
    		);
    		$this->new_extend->update('new_extend_line', array('order_id' => $new_line_info['id']),$up_order_arr);
    		
		    //创建好之后，保存用户设置的属性值
    		if($attr_value && is_array($attr_value))
    		{		        
	        	$ids = array_keys($attr_value);
	            $attr_data = $this->mode->getFrontAttrByIds($ids);
	            if($attr_data)
				{
					foreach ($attr_data AS $k => $v)
					{
						if(!isset($attr_value[$v['id']]))
						{
							continue;
						}
	                            
						$_value = '';
						$_front_value = $attr_value[$v['id']];
						switch ($v['attr_type_name'])
						{
							//取值范围
							case 'span':$_value = trim($_front_value);break;                 
						}                
						//设置前台的值
						$this->new_extend->setNewExtendLineUiValue(array(
    							'line_id' 		=> $new_line_info['id'],
								'module_id' 	=> $module_id,
								'ui_attr_id' 	=> $v['id'],
								'attr_value' 	=> $_value,
								'user_id'		=> $user_id,
							)		
						);
						
	                            
						//设置完前台属性值还要根据关系设置后台属性的值
	                    $set_type = 'create';
						//对关联的后台属性统一设值
						if($v['set_value_type'] == 1)
						{
							$this->new_extend->setNewExtendAttrSameToRelate($v['id'] , $_value , $new_line_info['id'] , $set_type);
						}		
					}
				}		        		
			}
			$this->addItem(array(
				'line_id' => $new_line_info['id'],
			));
    	}
    	$this->output();
    }
    
    /**
     * 获取扩展行的信息
     */
    public function getNewExtendLineInfo()
    {
    	$user_id = intval($this->user['user_id']);
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$line_id = intval($this->input['line_id']);
    	$module_id = intval($this->input['module_id']);
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	//拿出module信息，查询出该模块有没有绑定LIST_UI
        $module_info = $this->app_module->detail('app_module', array('id' => $module_id));
        if(!$module_info)
        {
            $this->errorOutput(MODULE_NOT_EXISTS);
        }      
        $role_id = intval($this->input['role_id']);
        
        //获取list_ui_info 
        //获取LIST_UI
        $list_ui_info = $this->ui_mode->detail($module_info['ui_id']);
        if(!$list_ui_info)
        {
        	$this->errorOutput(NOT_EXISTS_UI);
        }      
        $info = array();
		//判断需不要添加固定高度选项（先根据listui类型） ListUI1左右图时可以设置
//         $info['need_set_position'] = ($list_ui_info['uniqueid'] == $this->settings['new_extend']['need_set_list_ui_uniqueid']) ? 1 : 0;
		//获取当前listui中扩展区域属性选择的类型， 如果是固定高度=2  可以设置，如果是自适应则不可以
		if($list_ui_info['uniqueid'] == $this->settings['new_extend']['need_set_list_ui_uniqueid'] && $module_info['extend_area_position'] == 1)
		{
			$info['need_set_position'] = 1;	
		}
        else
        {
        	$info['need_set_position'] = 0;
        }
        //获取ui_info( 后台属性组里的信息)
        $ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$this->settings['new_extend']['new_extend_list_ui']. "' AND is_extend = 1 ");          

        //获取对应的前端属性
        $attrData = $this->new_extend->getFrontExtendAttributeData($line_id,$module_id,$role_id,$ui_info['id']); 
        $frontAttr = array();
        $groupData = $this->mode->getFrontGroupData();    
        foreach ($groupData as $_k => $_v)
        {
        	foreach ($attrData as $__k => $__v)
        	{
        		if($__v['group_id'] == $_v['id'] && $_v['uniqueid'] == $this->settings['new_extend']['new_extend_line_group_uniqueid'])
        		{
        			$frontAttr[] = $__v;
        		}
        	}
        }  
        $info['attr'] = $frontAttr;
        //如果line_id存在 获取对应的line的info
        $line_info = array();
        if($line_id)
        {
        	$line_info = $this->new_extend->detail('new_extend_line', array(
        					'id' => $line_id,	
        					'module_id' => $module_id,
        					'user_id' => $user_id,
        			)
        	);
        }
        $info['line_info'] = $line_info;
        $this->addItem($info);
        $this->output();
    }
    
    /**
     * 删除扩展行
     */
    public function deleteExtendLine()
    {
    	$module_id = intval($this->input['module_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$line_id = intval($this->input['line_id']);
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_EXTEND_LINE_ID);
    	}
    	//判断是否存在此line
    	$line_info = $this->new_extend->detail('new_extend_line', array(
    			'id'		=> $line_id,
    			'module_id' => $module_id,
    			'user_id'	=> $user_id,
    	));
    	if(!$line_info)
    	{
    		$this->errorOutput(NO_THIS_LINE_INFO);
    	}
    	$delete_line  = $this->new_extend->delete('new_extend_line', array(
    			'id'	=> $line_id,
    			'module_id'	=> $module_id,
    			'user_id'	=> $user_id,
    	));
    	$delete_attr = $this->new_extend->delete('new_extend_line_attr_value', array(
    			'line_id'	=> $line_id
    	));
    	$delete_ui = $this->new_extend->delete('new_extend_line_ui_attr_value', array(
    			'line_id'	=> $line_id,
    			'module_id'	=> $module_id,
    			'user_id'	=> $user_id,
    	));
    	
    	//删除这个行下的单元信息
    	$delete_field = $this->new_extend->delete('new_extend_field', array(
    				'module_id'	=> $module_id,
    				'user_id'	=> $user_id,
    				'line_id'	=> $line_id,
    	));
    	
    	$delete_field_attr = $this->new_extend->delete('new_extend_field_attr_value', array(
    			'line_id'	=> $line_id,
    	));
    	
    	$delete_field_ui = $this->new_extend->delete('new_extend_field_ui_attr_value', array(
    			'line_id'	=> $line_id,
    	));
    	
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    
    /**
     * 获取扩展行下所有的扩展单元的信息
     */
    public function getExtendFieldsInByLineId()
    {
    	$module_id = intval($this->input['module_id']);
    	$line_id = intval($this->input['line_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_LINE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	
    	$ret_module = $this->new_extend->getAllFieldsInModule($module_id,$user_id);
    	$ret_line = $this->new_extend->getAllFieldsInLine($module_id,$line_id,$user_id);
    	$line_info = $this->new_extend->detail('new_extend_line', array(
    				'id' => $line_id,
    				'user_id'	=>	$user_id,
    	));
    	
    	
//     	$ret = $this->new_extend->getInfo('new_extend_field',array(
//     			'line_id'	=> $line_id,
//     			'module_id'	=> $module_id,
//     	));
    	
    	$this->addItem(array(
    		'ret_module'	=> $ret_module,
    		'ret_line'		=> $ret_line,
    		'line_info'		=> $line_info,
    	));
    	$this->output();
    }
    
    /**
     * 创建扩展单元同时保存位置
     */
    public function setFieldPosition()
    {
    	$module_id = intval($this->input['module_id']);
    	$line_id = intval($this->input['line_id']);
    	$user_id = intval($this->user['user_id']);
    	$uniqueid = trim($this->input['uniqueid']);
    	$field_type = intval($this->input['field_type']);
    	$field_position = intval($this->input['field_position']);
    	$uni_name = trim($this->input['uni_name']);
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_EXTEND_LINE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	if(!in_array($field_position, $this->settings['new_extend']['field_position']))
    	{
    		$this->errorOutput(EXTEND_FIELD_POSTTION_WRONG);
    	}
    	//验证此uniqueid是否已经添加过
    	$is_add = $this->new_extend->detail('new_extend_field', array(
    			'module_id'	=>	$module_id,
    			'line_id'	=>	$line_id,
    			'user_id'	=>	$user_id,
    			'uniqueid'	=>	$uniqueid,		
    	));
    	if($is_add)
    	{
    		$this->errorOutput(UNIQUEID_HAS_EXISTS);
    	}
    	//验证field_type与uniqued
    	$special_type = $this->settings['new_extend']['special'];
    	foreach ($special_type as $_k => $_v)
    	{
    		if($_v['uniqueid'] == $uniqueid)
    		{
    			if($_v['field_type'] != $field_type)
    			{
    				$this->errorOutput(UNIQUEID_WORNG);
    			}
    		}
    	}
    	//验证此行是否已经超过个数
    	$now_field_num = $this->new_extend->getInfo('new_extend_field',array(
    				'line_id' 	=> $line_id,
    				'module_id'	=> $module_id,
    				'user_id'	=> $user_id, 
    	));
    	if($now_field_num && is_array($now_field_num) && count($now_field_num) >= $this->settings['new_extend']['extend_field_max_num'])
    	{
    		$this->errorOutput(MAX_FIELD_NUM);
    	}
    	
    	//多行情况下，左边只能添加1个
    	$line_info = $this->new_extend->detail('new_extend_line', array(
    				'id'	=> $line_id,
    	));   	
    	if($line_info['line_type'] == $this->settings['new_extend']['line_type']['much'] && $field_position == $this->settings['new_extend']['field_position']['left'])
    	{
    		//获取当前行下居左的field的数量
    		$temp_left_fields = $this->new_extend->getInfo('new_extend_field',array(
    				'module_id'	=> $module_id,
    				'line_id'	=> $line_id,
    				'field_position'	=> $this->settings['new_extend']['field_position']['left'],
    				'user_id'	=> $user_id,
    		));
    		if(count($temp_left_fields) >= $this->settings['new_extend']['much_lines_left_field_num'])
    		{
    			$this->errorOutput(MUCH_LINE_LEFT_MAX_NUM);
    		}
    	}
    	
    	//添加 
    	$add_arr = array(
    		'field_type'		=> $field_type,
    		'module_id'			=> $module_id,
    		'user_id'			=> $user_id,
    		'line_id'			=> $line_id,
    		'uniqueid'			=> $uniqueid,
    		'field_position'	=> $field_position,
    		'create_time'		=> TIMENOW,		
    		'uni_name'			=> $uni_name,
    		'style_type'		=> 1,
    	);
    	$new_field = $this->new_extend->create('new_extend_field', $add_arr);
    	$anthor_new_field = $this->new_extend->detail('new_extend_field', array(
    			'module_id' => $module_id,
    			'uniqueid'  => $uniqueid,
    			'line_id'	=> $line_id,    			
    	));
    	if($anthor_new_field['id'] == $new_field['id'])
    	{
    		$insert_field_id = $new_field['id'];
    	}
    	else
    	{
    		$insert_field_id = $anthor_new_field['id'];
    	}
    	
    	//更新orderid
    	$up_order_arr = array(
    		'id'		=> $insert_field_id,
    		'module_id'	=> $module_id,
    		'line_id'	=> $line_id,
    		'user_id'	=> $user_id,	
    	);
    	$this->new_extend->update('new_extend_field', array('order_id' => $insert_field_id), $up_order_arr);
    	/*********添加默认属性*********/
    	$role_id = intval($this->input['role_id']);
    	$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$this->settings['new_extend']['new_extend_list_ui']. "' AND is_extend = 1 ");
    	//获取对应的前端属性
    	$attrData = $this->new_extend->getFrontExtendFieldAttributeData(0,$line_id,$module_id,$role_id,$ui_info['id']);
    	$frontAttr = array();
    	$groupData = $this->mode->getFrontGroupData();
    	foreach ($groupData as $_k => $_v)
    	{
    		foreach ($attrData as $__k => $__v)
    		{
    			if($__v['group_id'] == $_v['id'] && $_v['uniqueid'] == $this->settings['new_extend']['new_extent_field_group_uniqueid'])
    			{
    				$frontAttr[] = $__v;
    			}
    		}
    	}
    	//添加进去
    	//处理数据
    	$set_type = 'create';
    	$attr_value = array();
    	foreach ($frontAttr as $_k => $_v)
    	{
    		$attr_value[$_v['id']] = $_v['attr_default_value'];
    	}
    	$ids = array_keys($attr_value);
    	$attr_data = $this->mode->getFrontAttrByIds($ids);
    	if($attr_data)
    	{
    		foreach ($attr_data as $k => $v)
    		{
    			if(!isset($attr_value[$v['id']]))
    			{
    				continue;
    			}
    			$_value = '';
    			$_front_value = $attr_value[$v['id']];
    			switch ($v['attr_type_name'])
    			{
    				//取值范围
    				case 'span':$_value = trim($_front_value);break;
    				//单选
    				case 'single_choice':$_value = trim($_front_value);break;
    				//拾色器
    				case 'color_picker':$_value = trim($_front_value);break;
    			}
    			//设置前台的值
    			$this->new_extend->setNewExtendFieldUiAttrValue(array(
    					'field_id' 		=> $insert_field_id,
    					'module_id' 	=> $module_id,
    					'ui_attr_id' 	=> $v['id'],
    					'attr_value' 	=> $_value,
    					'user_id'		=> $user_id,
    					'line_id'		=> $line_id,
    				)
    			);
    			//设置完前台属性值还要根据关系设置后台属性的值
    			//对关联的后台属性统一设值
    			if($v['set_value_type'] == 1)
    			{
    				$this->new_extend->setNewExtendFieldAttrSameToRelate($v['id'] , $_value , $insert_field_id , $line_id , $set_type);
    			}
    		}   		
    	}
    	/*********添加默认属性end*********/
    	
    	$this->addItem(array('field_id'	=> $new_field['id']));
    	$this->output();	
    }
    
    
    /**
     * 获取扩展单元的信息
     */
    public function getNewExtendFieldInfo()
    {
    	$module_id = intval($this->input['module_id']);
    	$line_id = intval($this->input['line_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_EXTEND_LINE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$role_id = intval($this->input['role_id']);
    	$field_id = intval($this->input['field_id']);
    	
    	$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$this->settings['new_extend']['new_extend_list_ui']. "' AND is_extend = 1 ");
    	//获取对应的前端属性
    	$attrData = $this->new_extend->getFrontExtendFieldAttributeData($field_id,$line_id,$module_id,$role_id,$ui_info['id']);
    	$frontAttr = array();
    	$groupData = $this->mode->getFrontGroupData();
    	foreach ($groupData as $_k => $_v)
    	{
    		foreach ($attrData as $__k => $__v)
    		{
    			if($__v['group_id'] == $_v['id'] && $_v['uniqueid'] == $this->settings['new_extend']['new_extent_field_group_uniqueid'])
    			{
    				$frontAttr[] = $__v;
    			}
    		}
    	}
    	$info['attr'] = $frontAttr;
    	//如果field_id存在 获取对应的field的info
    	$field_info = array();
    	if($field_id)
    	{
    		$field_info = $this->new_extend->detail('new_extend_field', array(
    				'id' 		=> $field_id,
    				'module_id' => $module_id,
    				'user_id' 	=> $user_id,
    				'line_id'	=> $line_id,
    			)
    		);
    	}
    	$info['field_info'] = $field_info;
    	$this->addItem($info);
    	$this->output();	
    }
    
    /**
     * 保存扩展单元其实属性
     */
    public function setNewExtendFieldInfo()
    {
    	$module_id = intval($this->input['module_id']);
    	$line_id = intval($this->input['line_id']);
    	$user_id = intval($this->user['user_id']);
    	$img_info = $this->input['img_info'];
    	$style_type = intval($this->input['style_type']);
    	$field_id = intval($this->input['field_id']);
    	$attr_value = $this->input['attr_value'];
    	
    	//先更新field的基本信息
    	$idsArr = array(
    		'module_id'	=> $module_id,
    		'line_id'	=> $line_id,
    		'user_id'	=> $user_id,
    		'id'		=> $field_id,
    	);
    	$update_arr = array(
    		'style_type'	=> $style_type,	
    	);
    	$this->new_extend->update('new_extend_field', $update_arr, $idsArr);
    	//更新或者插入属性
    	//先要判断当前是否有插入过属性
    	$temp = $this->new_extend->getInfo('new_extend_field_ui_attr_value',array(
    			'line_id'	=>	$line_id,
    			'field_id'	=>	$field_id,
    			'module_id'	=>	$module_id,
    			'user_id'	=>	$user_id,  			
    	));
    	$set_type = '';
    	if($temp)//已经插入属性，update
    	{
    		$set_type = 'update';
    	}
    	else//还未插入，insert
    	{
    		$set_type = 'create';	
    	}
    	
    	$ids = array_keys($attr_value);
    	$attr_data = $this->mode->getFrontAttrByIds($ids);
    	if($attr_data)
    	{
    		foreach ($attr_data AS $k => $v)
    		{
    			if(!isset($attr_value[$v['id']]))
    			{
    				continue;
    			}
    			 
    			$_value = '';
    			$_front_value = $attr_value[$v['id']];
    			switch ($v['attr_type_name'])
    			{
    				//取值范围
    				case 'span':$_value = trim($_front_value);break;
    				//单选
    				case 'single_choice':$_value = trim($_front_value);break;
    				//拾色器
    				case 'color_picker':$_value = trim($_front_value);break;
    			}
    			
    			if($set_type == 'create')
    			{
    				//设置前台的值
    				$this->new_extend->setNewExtendFieldUiAttrValue(array(
		    				'field_id' 		=> $field_id,
		    				'module_id' 	=> $module_id,
		    				'ui_attr_id' 	=> $v['id'],
		    				'attr_value' 	=> $_value,
		    				'user_id'		=> $user_id,
    						'line_id'		=> $line_id,
	    				)
    				);
    			}
    			elseif ($set_type == 'update')
    			{
    				$up_arr = array(
    					'attr_value' 	=> $_value,
    				);
    				$up_condition = array(
    					'field_id' 		=> $field_id,
		    			'module_id' 	=> $module_id,
		    			'ui_attr_id' 	=> $v['id'],
		    			'user_id'		=> $user_id,
    					'line_id'		=> $line_id,
    				);
    				$this->new_extend->update('new_extend_field_ui_attr_value', $up_arr , $up_condition);
    			}
    			 
    			//设置完前台属性值还要根据关系设置后台属性的值
    			//对关联的后台属性统一设值
    			if($v['set_value_type'] == 1)
    			{
    				$this->new_extend->setNewExtendFieldAttrSameToRelate($v['id'] , $_value , $field_id , $line_id , $set_type);
    			}
    		}
    	}
    	
    	$this->addItem(array('return' => 1));
    	$this->output();
    }  
    
    /**
     * deleteExtendField
     * 删除扩展单元
     */
    public function deleteExtendField()
    {
    	$line_id = intval($this->input['line_id']);
    	$module_id = intval($this->input['module_id']);
    	$field_id = intval($this->input['field_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$field_id)
    	{
    		$this->errorOutput(NO_FIELD_ID);
    	}
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_LINE_ID);
    	}
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$delete_arr = array(
    		'module_id'	=> $module_id,
    		'line_id'	=> $line_id,
    		'id'		=> $field_id,
    		'user_id'	=> $user_id,
    	);
    	//先删除new_extend_field
    	$this->new_extend->delete('new_extend_field', $delete_arr);
    	
    	//然后删new_extend_field_attr_value
    	$delete_arr_value = array(
    			'line_id'		=> $line_id,
    			'field_id'		=> $field_id,
    	);
    	$this->new_extend->delete('new_extend_field_attr_value', $delete_arr_value);
    	
    	//最后删new_extend_field_ui_attr_value
    	$this->new_extend->delete('new_extend_field_ui_attr_value', $delete_arr_value);
    	
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    
    /**
     * 扩展行排序
     */
    public function extendLineOrder()
    {
    	$up_ids = trim($this->input['up_ids']);
    	$down_ids = trim($this->input['down_ids']);
    	$module_id = intval($this->input['module_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$module_id)
    	{
    		$this->errorOutput(NO_MODULE_ID);
    	}
    	
    	//获取当下所有的行按照升序排列
    	$all_asc_lines = $this->new_extend->getAllLines($user_id,$module_id,'asc');
    	
    	//获取当下所有的行按照order降序排列
    	$all_desc_lines = $this->new_extend->getAllLines($user_id,$module_id,'desc');
    	//先处理up部分
    	if($up_ids)
    	{ 		
    		$up_arr = explode(',', $up_ids);
    		foreach ($up_arr as $k => $v)
    		{
    			//update最新的up部分的line的order_id
    			$up_data = array(
    				'order_id'		=> $all_asc_lines[$k]['order_id'],
    				'line_position'	=> 1,
    			);
    			$up_condtion = array(
    				'id'		=> $v,
    				'module_id'	=> $module_id,
    				'user_id'	=> $user_id,
    			); 			
    			$this->new_extend->update('new_extend_line', $up_data, $up_condtion);
    		}
    		
    	}
    	//down部分
    	if($down_ids)
    	{
    		$down_arr = explode(',', $down_ids);
    		foreach ($down_arr as $_k => $_v)
    		{
    			//update最新的down部分的line的order_id
    			$down_data = array(
    					'order_id'		=> $all_desc_lines[$_k]['order_id'],
    					'line_position'	=> 2,
    			);
    			$down_condtion = array(
    					'id'		=> $_v,
    					'module_id'	=> $module_id,
    					'user_id'	=> $user_id,
    			);
    			$this->new_extend->update('new_extend_line', $down_data, $down_condtion);
    		}
    	}	
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    
    /**
     * 扩展单元排序
     */
    public function extendFieldOrder()
    {
		$module_id = intval($this->input['module_id']);
		$line_id = intval($this->input['line_id']);
		$user_id = intval($this->user['user_id']);
		$left_ids = trim($this->input['left_ids']);
		$right_ids = trim($this->input['right_ids']);
		//先获取当下行内所有的单元asc
    	$all_fields_asc = $this->new_extend->getAllFields($module_id,$line_id,'asc');
		//先获取当下行内所有的单元desc
    	$all_fields_desc = $this->new_extend->getAllFields($module_id,$line_id,'desc');
    	if($left_ids)
    	{
    		$left_arr = explode(',', $left_ids);
    		foreach ($left_arr as $k => $v)
    		{
    			$left_data = array(
    				'order_id'			=> $all_fields_asc[$k]['order_id'],
    				'field_position'	=> 1,
    			);
    			$left_condition = array(
    				'id'		=> $v,
    				'line_id'	=> $line_id,
    				'module_id'	=> $module_id,
    			);
    			$this->new_extend->update('new_extend_field', $left_data, $left_condition);
    		}
    	}
    	if ($right_ids)
    	{
    		$right_arr = explode(',', $right_ids);
    		foreach ($right_arr as $_k => $_v)
    		{
    			 $right_data = array(
    			 	'order_id'			=>	$all_fields_desc[$_k]['order_id'],
    			 	'field_position'	=> 2,
    			 );
    			 $right_condition = array(
    			 	'id'		=> $_v,
    			 	'line_id'	=> $line_id,
    			 	'module_id'	=> $module_id,
    			 );
    			 $this->new_extend->update('new_extend_field', $right_data, $right_condition);
    		}
    	}
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    /**********************************************新扩展字段end**************************************************************/
    
    
    
    
    //暂时用于测试
    public function getCompAttrData()
    {
        $ret = $this->mode->getCompAttributeData(13);
        $this->addItem($ret);
        $this->output();
    }
    
    //暂时用于测试
    public function getCornerAttrData()
    {
        $ret = $this->mode->getCornerAttributeData(21);
        $this->addItem($ret);
        $this->output();
    }
    
    public function test()
    {
        $ret = $this->mode->getUseCornerInfoByModId(2408);
        $this->addItem($ret);
        $this->output();
    }
    
    public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new app_attribute_ui();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();