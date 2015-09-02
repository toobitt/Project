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

class app_attribute_ui extends outerReadBase
{
    private $mode;
    private $ui_mode;
    private $app_module;
    private $app;
    private $app_material;
    private $comp_mode;
    private $corner_mode;
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
	    //如果存在，就说明该模块使用了角标，就需要找出每一个使用的角标对应的属性值
	    if($mod_corner)
	    {
	        foreach($mod_corner AS $k => $_mod_cor)
	        {
	            //获取该UI的属性
                $attrData = $this->mode->getFrontCornerAttributeData($ui_info['id'],$_mod_cor['id'],$this->input['role_id']);
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
	    
	    //然后根据这个模块获取当前这个模块使用的角标的id
	    $modCornerIds = $this->corner_mode->getModCornerByCond(" AND module_id = '" .$module_id. "' ",'id','id');
	    if($modCornerIds)
	    {
	        $modCornerIdsStr = implode(',', array_keys($modCornerIds));
	        //首先删除这个模块使用的角标情况
	        $this->corner_mode->deleteModCornerByCond(" AND module_id = '" .$module_id. "' ");
	        $this->corner_mode->deleteFrontCornerAttrByCond(" AND mod_corner_id IN (" .$modCornerIdsStr. ") ");
	        $this->corner_mode->deleteCornerAttrByCond(" AND mod_corner_id IN (" .$modCornerIdsStr. ") ");
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
                            ));
                            
                            //设置完前台属性值还要根据关系设置后台属性的值
                            
                            //对关联的后台属性统一设值
                            if($v['set_value_type'] == 1)
                            {
                                $this->mode->setFrontCornerAttrSameToRelate($v['id'],$_value,$mod_corner_id);
                            }
                            elseif($v['set_value_type'] == 2)//对关联属性分别设置
                            {
                                $this->mode->setFrontCornerAttrEachToRelate($v['id'],$_value,$mod_corner_id);
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