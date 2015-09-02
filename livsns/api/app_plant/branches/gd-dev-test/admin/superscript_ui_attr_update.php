<?php
define('MOD_UNIQUEID','superscript_ui_attr');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/superscript_ui_attr_mode.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');
require_once(CUR_CONF_PATH . '/lib/appMaterial.class.php');

class superscript_ui_attr_update extends adminUpdateBase
{
	private $mode;
	private $_upYunOp;
	private $app_material;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new superscript_ui_attr_mode();
		$this->_upYunOp = new UpYunOp();
		$this->app_material = new appMaterial();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
	    $name           = $this->input['name'];//属性名称
	    $attr_type_id   = $this->input['attr_type_id'];//属性类型id
	    $role_type_id   = $this->input['role_type_id'];//角色id
	    $set_value_type = intval($this->input['set_value_type']);//设置值的方式
	    
	    if(!$name)
	    {
	        $this->errorOutput(NO_ATTR_NAME);
	    }
	    
	    if(!$attr_type_id)
	    {
	        $this->errorOutput(NO_ATTR_TYPE);
	    }
	    else 
	    {
            if(!isset($this->settings['attribute_type'][$attr_type_id]['uniqueid']))
            {
                $this->errorOutput(NO_ATTR_TYPE);
            }
	    }
	    
	    if(!$set_value_type)
	    {
	        $this->errorOutput(NO_SELECT_SET_VALUE_TYPE);
	    }
	    
	    //构建样式设置值
	    switch ($this->settings['attribute_type'][$attr_type_id]['uniqueid'])
	    {
	        //文本框
	        case 'textbox':
	                       $style_value = array(
	                           'name'	     => $this->input['textbox_name'], 
	                           'size'	     => $this->input['size'], 
	                           'maxlength'	 => $this->input['maxlength'], 
	                           'placeholder' => $this->input['placeholder'], 
	                           'validate'	 => $this->input['validate'], 
	                       );
	                       $default_value = $this->input['default_value'];//获取默认值
            	           break;
	        //文本域
	        case 'textfield':
            	            $style_value = array(
            	                'name'         => $this->input['textfield_name'],
            	                'rows'         => $this->input['rows'],
            	                'cols'         => $this->input['cols'],
            	                'placeholder'  => $this->input['placeholder'],
            	                'validate'     => $this->input['validate'],
            	            );
            	            $default_value = $this->input['default_value'];//获取默认值
            	            break;
           
            //单选
	        case 'single_choice':
	                        $datasource = array();
	                        $display_text = $this->input['display_text'];
	                        $value        = $this->input['value'];
	                        $default_value = '';
	                        
	                        if($display_text)
	                        {
    	                        foreach($display_text AS $k => $v)
    	                        {
    	                            $is_selected = ($k == intval($this->input['is_selected']))?1:0;
    	                            $datasource[] = array(
    	                                'text'         => $v,
    	                                'value'        => $value[$k],
    	                                'is_selected'  => $is_selected,
    	                            );
    	                            //默认值取选中的那个值
    	                            if($is_selected)
    	                            {
    	                                $default_value = $value[$k];
    	                            }
    	                        }
	                        }
	                        
	                        $style_value = array(
	                            'name'		 => $this->input['single_choice_name'],
	                            'style'		 => $this->input['style'],
	                            'datasource' => $datasource,
	                        );
            	            break;
            
            //勾选
	        case 'check':
	                        $style_value = array(
	                            'name'        => $this->input['check_name'],
	                            'text'        => $this->input['check_text'],
	                            'is_selected' => $this->input['is_selected']?1:0,
	                        );
	                        $default_value = $this->input['is_selected']?1:0;//获取默认值
	                        break;
	       
	        //取值范围
	        case 'span':    
	                        $style_value = array(
	                            'name'             => $this->input['span_name'],
	                            'start'            => $this->input['start'],
	                            'end'              => $this->input['end'],
	                            'degree'           => $this->input['degree'],
	                            'is_contain_start' => $this->input['is_contain_start']?1:0,
	                            'is_contain_end'   => $this->input['is_contain_end']?1:0,
	                        );
	                        
	                        $default_value = $this->input['default_value'];
	                        break;
	        //图片单选
	        case 'pic_radio':
	                        $is_selected_Arr = $this->input['is_selected'];
	                        $value = $this->input['value'];//每个选项的值，是一个数组
	                        $style_value = array(
	                            'name'             => $this->input['pic_radio_name'],
	                            'width'            => $this->input['width'],
	                            'height'           => $this->input['height'],
	                            'direction'        => $this->input['direction'],
	                        );
	                        $default_value = '';//默认值
	                        
	                        //处理上传上来的图片
	                        if($_FILES['pics'])
	                        {
	                            $files = $_FILES['pics'];
	                            foreach ($files['error'] AS $k => $v)
	                            {
	                                $img_info = array();
	                                $img = array();
	                                if($v)
	                                {
	                                    continue;
	                                }
	                                
	                                $_file = array(
	                                    'name'     => $files['name'][$k],
	                                    'type'     => $files['type'][$k],
	                                    'tmp_name' => $files['tmp_name'][$k],
	                                    'error'    => $files['error'][$k],
	                                    'size'	   => $files['size'][$k],
	                                );
	                                
	                                $img = $this->_upYunOp->uploadToBucket($_file);
                                    if($img)
                                    {
                                        //上传之后保存到数据库
                                        $_imginfo = array(
                                            'name'        => $img['name'],
                                            'mark'        => 'mark',
                                            'type'        => $img['type'],
                                            'filesize'    => $img['filesize'],
                                            'imgwidth'    => $img['imgwidth'],
                                            'imgheight'   => $img['imgheight'],
                                            'host'        => $img['host'],
                                            'filepath'    => $img['filepath'],
                                            'filename'    => $img['filename'],
                                            'user_id'     => $this->user['user_id'],
                                            'user_name'   => $this->user['user_name'],
                                            'org_id'      => $this->user['org_id'],
                                            'create_time' => TIMENOW,
                                            'ip'		  => hg_getip(),
                                        );
                                        $_ret = $this->app_material->create('app_material', $_imginfo);
                                        if($_ret)
                                        {
                                            $img_info = array(
                                                'id'		=> $_ret['id'],
                            					'host' 		=> $img['host'],
                            					'dir' 		=> $img['dir'],
                            					'filepath' 	=> $img['filepath'],
                            					'filename' 	=> $img['filename'],	
                            					'imgwidth'	=> $img['imgwidth'],
                            					'imgheight'	=> $img['imgheight'],
                                            );
                                            
                                            $is_selected = 0;
            	                            if($is_selected_Arr && in_array($k,$is_selected_Arr))
            	                            {
            	                                $is_selected = 1;
            	                            }
                                            $style_value['datasource'][] = array(
                                                    'img_info'    => $img_info,
                                                    'is_selected' => $is_selected,
                                                    'value'       => $value[$k],
                                            );
                                           //选中默认值
                                            if($is_selected)
                                            {
                                                $default_value = $value[$k];                                          
                                            }
                                        }
                                    }
	                            }
	                        }
	                        break;
	                        
	       //图片上传+单选
	       case 'pic_upload_radio':
	                        $is_selected_Arr = $this->input['is_selected'];
	                        $value = $this->input['value'];//每个选项的值，是一个数组
	                        $style_value = array(
	                            'name'             => $this->input['pic_upload_radio_name'],
	                            'width'            => $this->input['width'],
	                            'height'           => $this->input['height'],
	                            'direction'        => $this->input['direction'],
	                        );
	                        
	                        $_img_ids = array();
	                        $_selected_id = '';
	                        
	                        //处理上传上来的图片
	                        if($_FILES['pics'])
	                        {
	                            $files = $_FILES['pics'];
	                            foreach ($files['error'] AS $k => $v)
	                            {
	                                $img_info = array();
	                                $img = array();
	                                if($v)
	                                {
	                                    continue;
	                                }
	                                
	                                $_file = array(
	                                    'name'     => $files['name'][$k],
	                                    'type'     => $files['type'][$k],
	                                    'tmp_name' => $files['tmp_name'][$k],
	                                    'error'    => $files['error'][$k],
	                                    'size'	   => $files['size'][$k],
	                                );
	                                
	                                $img = $this->_upYunOp->uploadToBucket($_file);
	                                if($img)
                                    {
                                        //上传之后保存到数据库
                                        $_imginfo = array(
                                            'name'        => $img['name'],
                                            'mark'        => 'mark',
                                            'type'        => $img['type'],
                                            'filesize'    => $img['filesize'],
                                            'imgwidth'    => $img['imgwidth'],
                                            'imgheight'   => $img['imgheight'],
                                            'host'        => $img['host'],
                                            'filepath'    => $img['filepath'],
                                            'filename'    => $img['filename'],
                                            'user_id'     => $this->user['user_id'],
                                            'user_name'   => $this->user['user_name'],
                                            'org_id'      => $this->user['org_id'],
                                            'create_time' => TIMENOW,
                                            'ip'		  => hg_getip(),
                                        );
                                        $_ret = $this->app_material->create('app_material', $_imginfo);
                                        if($_ret)
                                        {
                                            $img_info = array(
                                                'id'		=> $_ret['id'],
                            					'host' 		=> $img['host'],
                            					'dir' 		=> $img['dir'],
                            					'filepath' 	=> $img['filepath'],
                            					'filename' 	=> $img['filename'],	
                            					'imgwidth'	=> $img['imgwidth'],
                            					'imgheight'	=> $img['imgheight'],
                                            );
                                            
                                            $is_selected = 0;
            	                            if($is_selected_Arr && in_array($k,$is_selected_Arr))
            	                            {
            	                                $is_selected = 1;
            	                            }
                                            $style_value['datasource'][] = array(
                                                    'img_info'    => $img_info,
                                                    'is_selected' => $is_selected,
                                                    'value'       => $value[$k],
                                            );
                                            //构建默认值
                                            $_img_ids[] = $_ret['id'];
                                            if($is_selected)
                                            {
                                                $_selected_id = $_ret['id'];
                                            }
                                        }
                                    }
	                            }
	                        }
	                        
	                        if($_selected_id)
	                        {
	                            $default_value = serialize(array(
	                                    'img_ids'  => implode(',', $_img_ids),
            	                        'selected' => $_selected_id,
	                            ));
	                        }
	                        
	                        break;

	       //多选
	       case 'multiple_choice':
	                        $datasource = array();
	                        $display_text = $this->input['display_text'];
	                        $value        = $this->input['value'];
	                        $is_selected_Arr  = $this->input['is_selected'];
	                        $default_value_arr = array();
	                        
	                        if($display_text)
	                        {
    	                        foreach($display_text AS $k => $v)
    	                        {
    	                            $is_selected = 0;
    	                            if($is_selected_Arr && in_array($k,$is_selected_Arr))
    	                            {
    	                                $is_selected = 1;
    	                            }
    	                            $datasource[] = array(
    	                                'text'         => $v,
    	                                'value'        => $value[$k],
    	                                'is_selected'  => $is_selected,
    	                            );
    	                            
    	                            if($is_selected)
    	                            {
    	                                $default_value_arr[] = $value[$k];
    	                            }
    	                        }
	                        }
	                        
	                        $style_value = array(
	                            'name'		 => $this->input['multiple_choice_name'],
	                            'datasource' => $datasource,
	                        );
	                        
	                        if($default_value_arr)
	                        {
	                            $default_value = implode(',', $default_value_arr);
	                        }
            	            break;
           
           //拾色器
	       case 'color_picker':
	                        $style_value = array(
	                            'name'		 => $this->input['color_picker_name'],
	                        );
	                        $default_value = $this->input['default_value'];
            	            break;
           //高级拾色器
	       case 'advanced_color_picker':
	                        $style_value = array(
	                            'name'		 => $this->input['advanced_color_picker_name'],
	                            'is_alpha'   => $this->input['is_alpha']?1:0,
	                        );
	                        
	                        if($this->input['default_value'])
	                        {
    	                        $_color = explode('|', $this->input['default_value']);
                                if($_color)
                                {
                                    $default_value = serialize(array(
                                            'color' => $_color[0],
                                            'alpha' => $_color[1],
                                    ));
                                }
	                        }
            	            break;
            	            
          //配色方案
	       case 'color_schemes':
	                        $datasource   = array();
	                        $color_name   = $this->input['color_name'];
	                        $color_value  = $this->input['color_value'];
	                        
	                        if($color_name)
	                        {
    	                        foreach($color_name AS $k => $v)
    	                        {
    	                            $is_selected  = ($k == intval($this->input['is_selected']))?1:0;
    	                            $datasource[] = array(
    	                                'color_name'   => $v,
    	                                'color_value'  => $color_value[$k],
    	                                'is_selected'  => $is_selected,
    	                            );
    	                            
    	                            if($is_selected)
    	                            {
    	                                $default_value = $color_value[$k];//获取默认值
    	                            }
    	                        }
	                        }
	                        
	                        $style_value = array(
	                            'name'		 => $this->input['color_schemes_name'],
	                            'datasource' => $datasource,
	                        );
	                        
            	            break;
            	            
          //高级配色方案
	       case 'advanced_color_schemes':
	                        $style_value = array(
	                            'name'		 => $this->input['advanced_color_schemes_name'],
	                        );
	                        
	                        if($this->input['default_value'])
	                        {
    	                        $_color = explode('|', $this->input['default_value']);
                                if($_color)
                                {
                                    $_value = array();
                                    foreach ($_color AS $_kk => $_vv)
                                    {
                                        $_tmp = explode(':', $_vv);
                                        $_value[$_tmp[0]] = $_tmp[1];
                                    }
                                    $default_value = serialize($_value);
                                }
	                        }
	                        
            	            break;
            	            
          //高级背景设置
	       case 'advanced_background_set':
	                       if($_FILES['default_value'])
	                       {
	                            $img = $this->_upYunOp->uploadToBucket($_FILES['default_value']);
                                if($img)
                                {
                                    //上传之后保存到数据库
                                    $_imginfo = array(
                                        'name'        => $img['name'],
                                        'mark'        => 'mark',
                                        'type'        => $img['type'],
                                        'filesize'    => $img['filesize'],
                                        'imgwidth'    => $img['imgwidth'],
                                        'imgheight'   => $img['imgheight'],
                                        'host'        => $img['host'],
                                        'filepath'    => $img['filepath'],
                                        'filename'    => $img['filename'],
                                        'user_id'     => $this->user['user_id'],
                                        'user_name'   => $this->user['user_name'],
                                        'org_id'      => $this->user['org_id'],
                                        'create_time' => TIMENOW,
                                        'ip'		  => hg_getip(),
                                    );
                                    $_ret = $this->app_material->create('app_material', $_imginfo);
                                    if($_ret)
                                    {
                                       $default_value = @serialize(array(
                                               'img_id' => $_ret['id'],
                                               'is_tile'=> intval($this->input['is_tile']),
                                               'width'  => $this->input['width'],//宽度
                                               'height' => $this->input['height'],//高度
                                               'info'   => $this->input['info'],//说明
                                       ));
                                    }
                                }
	                       }
	                       else if($this->input['default_value'])
	                       {
	                           $_def_value = explode('|', $this->input['default_value']);
	                           if($_def_value && is_array($_def_value))
	                           {
	                               if($_def_value[0] == 'color')
	                               {
	                                   $default_value = serialize(array('color' => $_def_value[1],'alpha' => (float)$_def_value[2]));
	                               }
	                               elseif ($_def_value[0] == 'img_id')
	                               {
	                                   $default_value = serialize(array(
                                               'img_id' => $_def_value[1],
                                               'is_tile'=> intval($this->input['is_tile']),
	                                           'width'  => $this->input['width'],//宽度
                                               'height' => $this->input['height'],//高度
                                               'info'   => $this->input['info'],//说明
                                       ));
	                               }
	                           }
	                       }
	                       $style_value = array(
	                            'name'		 => $this->input['advanced_background_set_name'],
	                            'is_tile'	 => $this->input['is_tile']?1:0,
	                        );
            	            break;
            	            
          //高级文字设置
	       case 'advanced_character_set':
	                        $style_value = array(
	                            'name'		 => $this->input['advanced_character_set_name'],
	                        );
	                        
	                       if($_FILES['default_value'])
	                       {
	                            $img = $this->_upYunOp->uploadToBucket($_FILES['default_value']);
                                if($img)
                                {
                                    //上传之后保存到数据库
                                    $_imginfo = array(
                                        'name'        => $img['name'],
                                        'mark'        => 'mark',
                                        'type'        => $img['type'],
                                        'filesize'    => $img['filesize'],
                                        'imgwidth'    => $img['imgwidth'],
                                        'imgheight'   => $img['imgheight'],
                                        'host'        => $img['host'],
                                        'filepath'    => $img['filepath'],
                                        'filename'    => $img['filename'],
                                        'user_id'     => $this->user['user_id'],
                                        'user_name'   => $this->user['user_name'],
                                        'org_id'      => $this->user['org_id'],
                                        'create_time' => TIMENOW,
                                        'ip'		  => hg_getip(),
                                    );
                                    $_ret = $this->app_material->create('app_material', $_imginfo);
                                    if($_ret)
                                    {
                                       $default_value = @serialize(array(
                                               'img_id' => $_ret['id'],
                                               'width'  => $this->input['width'],//宽度
                                               'height' => $this->input['height'],//高度
                                               'info'   => $this->input['info'],//说明
                                       ));
                                    }
                                }
	                       }
	                       else if($this->input['default_value'])
	                       {
	                           $_def_value = explode('|', $this->input['default_value']);
	                           if($_def_value && is_array($_def_value))
	                           {
	                               if($_def_value[0] == 'text')
	                               {
	                                   $default_value = serialize(array('text' => $_def_value[1]));
	                               }
	                               elseif ($_def_value[0] == 'img_id')
	                               {
	                                   $default_value = serialize(array(
                                               'img_id' => $_def_value[1],
	                                           'width'  => $this->input['width'],//宽度
                                               'height' => $this->input['height'],//高度
                                               'info'   => $this->input['info'],//说明
                                       ));
	                               }
	                           }
	                       }
            	           break;
	    }

	    //插入主表
		$data = array(
		    'name'           => $name,
		    'role_type_id'   => $role_type_id,
		    'attr_type_id'   => $attr_type_id,
		    'style_value'    => serialize($style_value),
		    'default_value'  => $default_value,
		    'set_value_type' => $set_value_type,
		    'user_id'	     => $this->user['user_id'],		    
            'user_name'	     => $this->user['user_name'],
		    'create_time'    => TIMENOW,
		    'update_time'    => TIMENOW,
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建前台属性',$data,'','创建前台属性' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
	    $id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}

	    $name           = $this->input['name'];//属性名称
	    $attr_type_id   = $this->input['attr_type_id'];//属性类型id
	    $role_type_id   = $this->input['role_type_id'];//角色id
	    $set_value_type = intval($this->input['set_value_type']);//设置值的方式

	    if(!$name)
	    {
	        $this->errorOutput(NO_ATTR_NAME);
	    }
	    
	    if(!$attr_type_id)
	    {
	        $this->errorOutput(NO_ATTR_TYPE);
	    }
	    else 
	    {
	        if(!isset($this->settings['attribute_type'][$attr_type_id]['uniqueid']))
            {
                $this->errorOutput(NO_ATTR_TYPE);
            }
	    }
	    
	    if(!$set_value_type)
	    {
	        $this->errorOutput(NO_SELECT_SET_VALUE_TYPE);
	    }

	    //构建样式设置值
	    switch ($this->settings['attribute_type'][$attr_type_id]['uniqueid'])
	    {
	        //文本框
	        case 'textbox':
	                       $style_value = array(
	                           'name'	     => $this->input['textbox_name'], 
	                           'size'	     => $this->input['size'], 
	                           'maxlength'	 => $this->input['maxlength'], 
	                           'placeholder' => $this->input['placeholder'], 
	                           'validate'	 => $this->input['validate'], 
	                       );
	                       $default_value = $this->input['default_value'];//获取默认值
            	           break;
	        //文本域
	        case 'textfield':
            	            $style_value = array(
            	                'name'         => $this->input['textfield_name'],
            	                'rows'         => $this->input['rows'],
            	                'cols'         => $this->input['cols'],
            	                'placeholder'  => $this->input['placeholder'],
            	                'validate'     => $this->input['validate'],
            	            );
            	            $default_value = $this->input['default_value'];//获取默认值
            	            break;
           
            //单选
	        case 'single_choice':
	                        $datasource = array();
	                        $display_text = $this->input['display_text'];
	                        $value        = $this->input['value'];
	                        $default_value = '';
	                        
	                        if($display_text)
	                        {
    	                        foreach($display_text AS $k => $v)
    	                        {
    	                            $is_selected = ($k == intval($this->input['is_selected']))?1:0;
    	                            $datasource[] = array(
    	                                'text'         => $v,
    	                                'value'        => $value[$k],
    	                                'is_selected'  => $is_selected,
    	                            );
    	                            //默认值取选中的那个值
    	                            if($is_selected)
    	                            {
    	                                $default_value = $value[$k];
    	                            }
    	                        }
	                        }
	                        
	                        $style_value = array(
	                            'name'		 => $this->input['single_choice_name'],
	                            'style'		 => $this->input['style'],
	                            'datasource' => $datasource,
	                        );
            	            break;
            
            //勾选
	        case 'check':
	                        $style_value = array(
	                            'name'        => $this->input['check_name'],
	                            'text'        => $this->input['check_text'],
	                            'is_selected' => $this->input['is_selected']?1:0,
	                        );
	                        $default_value = $this->input['is_selected']?1:0;//获取默认值
	                        break;
	       
	        //取值范围
	        case 'span':    
	                        $style_value = array(
	                            'name'             => $this->input['span_name'],
	                            'start'            => $this->input['start'],
	                            'end'              => $this->input['end'],
	                            'degree'           => $this->input['degree'],
	                            'is_contain_start' => $this->input['is_contain_start']?1:0,
	                            'is_contain_end'   => $this->input['is_contain_end']?1:0,
	                        );
	                        $default_value = $this->input['default_value'];//获取默认值
	                        break;
	       
	        //图片单选
	        case 'pic_radio':    
	                        $is_selected_Arr = $this->input['is_selected'];
	                        $value = $this->input['value'];//每个选项的值，是一个数组
	                        $style_value = array(
	                            'name'             => $this->input['pic_radio_name'],
	                            'width'            => $this->input['width'],
	                            'height'           => $this->input['height'],
	                            'direction'        => $this->input['direction'],
	                        );
	                        $default_value = '';//默认值
	                        
	                        //处理上传上来的图片
	                        if($_FILES['pics'])
	                        {
	                            $files = $_FILES['pics'];
	                            foreach ($files['error'] AS $k => $v)
	                            {
	                                $img_info = array();
	                                $img = array();
	                                if($v)
	                                {
	                                    continue;
	                                }
	                                
	                                $_file = array(
	                                    'name'     => $files['name'][$k],
	                                    'type'     => $files['type'][$k],
	                                    'tmp_name' => $files['tmp_name'][$k],
	                                    'error'    => $files['error'][$k],
	                                    'size'	   => $files['size'][$k],
	                                );
	                                
	                                $img = $this->_upYunOp->uploadToBucket($_file);
	                                if($img)
                                    {
                                        //上传之后保存到数据库
                                        $_imginfo = array(
                                            'name'        => $img['name'],
                                            'mark'        => 'mark',
                                            'type'        => $img['type'],
                                            'filesize'    => $img['filesize'],
                                            'imgwidth'    => $img['imgwidth'],
                                            'imgheight'   => $img['imgheight'],
                                            'host'        => $img['host'],
                                            'filepath'    => $img['filepath'],
                                            'filename'    => $img['filename'],
                                            'user_id'     => $this->user['user_id'],
                                            'user_name'   => $this->user['user_name'],
                                            'org_id'      => $this->user['org_id'],
                                            'create_time' => TIMENOW,
                                            'ip'		  => hg_getip(),
                                        );
                                        $_ret = $this->app_material->create('app_material', $_imginfo);
                                        if($_ret)
                                        {
                                            $img_info = array(
                                                'id'		=> $_ret['id'],
                            					'host' 		=> $img['host'],
                            					'dir' 		=> $img['dir'],
                            					'filepath' 	=> $img['filepath'],
                            					'filename' 	=> $img['filename'],	
                            					'imgwidth'	=> $img['imgwidth'],
                            					'imgheight'	=> $img['imgheight'],
                                            );
                                            
                                            $is_selected = 0;
            	                            if($is_selected_Arr && in_array($k,$is_selected_Arr))
            	                            {
            	                                $is_selected = 1;
            	                            }
                                            $style_value['datasource'][] = array(
                                                    'img_info'    => $img_info,
                                                    'is_selected' => $is_selected,
                                                    'value'       => $value[$k],
                                            );
                                            //选中默认值
                                            if($is_selected)
                                            {
                                                $default_value = $value[$k];                                          
                                            }
                                        }
                                    }
	                            }
	                        }
	                        break;
	                        
	       //图片上传+单选
	       case 'pic_upload_radio':
	                        $is_selected_Arr = $this->input['is_selected'];
	                        $value = $this->input['value'];
	                        $style_value = array(
	                            'name'             => $this->input['pic_upload_radio_name'],
	                            'width'            => $this->input['width'],
	                            'height'           => $this->input['height'],
	                            'direction'        => $this->input['direction'],
	                        );
	                        
	                        $_img_ids = array();
	                        $_selected_id = '';
	                        
	                        //处理上传上来的图片
	                        if($_FILES['pics'])
	                        {
	                            $files = $_FILES['pics'];
	                            foreach ($files['error'] AS $k => $v)
	                            {
	                                $img_info = array();
	                                $img = array();
	                                if($v)
	                                {
	                                    continue;
	                                }
	                                
	                                $_file = array(
	                                    'name'     => $files['name'][$k],
	                                    'type'     => $files['type'][$k],
	                                    'tmp_name' => $files['tmp_name'][$k],
	                                    'error'    => $files['error'][$k],
	                                    'size'	   => $files['size'][$k],
	                                );
	                                
	                                $img = $this->_upYunOp->uploadToBucket($_file);
	                                if($img)
                                    {
                                        //上传之后保存到数据库
                                        $_imginfo = array(
                                            'name'        => $img['name'],
                                            'mark'        => 'mark',
                                            'type'        => $img['type'],
                                            'filesize'    => $img['filesize'],
                                            'imgwidth'    => $img['imgwidth'],
                                            'imgheight'   => $img['imgheight'],
                                            'host'        => $img['host'],
                                            'filepath'    => $img['filepath'],
                                            'filename'    => $img['filename'],
                                            'user_id'     => $this->user['user_id'],
                                            'user_name'   => $this->user['user_name'],
                                            'org_id'      => $this->user['org_id'],
                                            'create_time' => TIMENOW,
                                            'ip'		  => hg_getip(),
                                        );
                                        $_ret = $this->app_material->create('app_material', $_imginfo);
                                        if($_ret)
                                        {
                                            $img_info = array(
                                                'id'		=> $_ret['id'],
                            					'host' 		=> $img['host'],
                            					'dir' 		=> $img['dir'],
                            					'filepath' 	=> $img['filepath'],
                            					'filename' 	=> $img['filename'],	
                            					'imgwidth'	=> $img['imgwidth'],
                            					'imgheight'	=> $img['imgheight'],
                                            );
                                            
                                            $is_selected = 0;
            	                            if($is_selected_Arr && in_array($k,$is_selected_Arr))
            	                            {
            	                                $is_selected = 1;
            	                            }
                                            $style_value['datasource'][] = array(
                                                    'img_info'    => $img_info,
                                                    'is_selected' => $is_selected,
                                                    'value'       => $value[$k],
                                            );
                                            
                                            //构建默认值
                                            $_img_ids[] = $_ret['id'];
                                            if($is_selected)
                                            {
                                                $_selected_id = $_ret['id'];
                                            }
                                            
                                        }
                                    }
	                            }
	                        }
	                        
	                        if($_selected_id)
	                        {
	                            $default_value = serialize(array(
	                                    'img_ids'  => implode(',', $_img_ids),
            	                        'selected' => $_selected_id,
	                            ));
	                        }
	                        
	                        break;

	       //多选
	       case 'multiple_choice':
	                        $datasource = array();
	                        $display_text = $this->input['display_text'];
	                        $value        = $this->input['value'];
	                        $is_selected_Arr  = $this->input['is_selected'];
	                        $default_value_arr = array();
	                        
	                        if($display_text)
	                        {
    	                        foreach($display_text AS $k => $v)
    	                        {
    	                            $is_selected = 0;
    	                            if($is_selected_Arr && in_array($k,$is_selected_Arr))
    	                            {
    	                                $is_selected = 1;
    	                            }
    	                            $datasource[] = array(
    	                                'text'         => $v,
    	                                'value'        => $value[$k],
    	                                'is_selected'  => $is_selected,
    	                            );
    	                            
    	                            if($is_selected)
    	                            {
    	                                $default_value_arr[] = $value[$k];
    	                            }
    	                            
    	                        }
	                        }
	                        
	                        $style_value = array(
	                            'name'		 => $this->input['multiple_choice_name'],
	                            'datasource' => $datasource,
	                        );
	                        
	                        if($default_value_arr)
	                        {
	                            $default_value = implode(',', $default_value_arr);
	                        }
            	            break;
           
           //拾色器
	       case 'color_picker':
	                       $style_value = array(
	                            'name'		 => $this->input['color_picker_name'],
	                        );
	                        $default_value = $this->input['default_value'];//获取默认值
            	            break;
           //高级拾色器
	       case 'advanced_color_picker':
	                       $style_value = array(
	                            'name'		 => $this->input['advanced_color_picker_name'],
	                            'is_alpha'   => $this->input['is_alpha']?1:0,
	                        );
	                        
	                        if($this->input['default_value'])
	                        {
    	                        $_color = explode('|', $this->input['default_value']);
                                if($_color)
                                {
                                    $default_value = serialize(array(
                                            'color' => $_color[0],
                                            'alpha' => $_color[1],
                                    ));
                                }
	                        }
            	            break;
            	            
          //配色方案
	       case 'color_schemes':
	                        $datasource   = array();
	                        $color_name   = $this->input['color_name'];
	                        $color_value  = $this->input['color_value'];
	                        
	                        if($color_name)
	                        {
    	                        foreach($color_name AS $k => $v)
    	                        {
    	                            $is_selected  = ($k == intval($this->input['is_selected']))?1:0;
    	                            $datasource[] = array(
    	                                'color_name'   => $v,
    	                                'color_value'  => $color_value[$k],
    	                                'is_selected'  => $is_selected,
    	                            );
    	                            
    	                            if($is_selected)
    	                            {
    	                                $default_value = $color_value[$k];//获取默认值
    	                            }
    	                        }
	                        }
	                        
	                        $style_value = array(
	                            'name'		 => $this->input['color_schemes_name'],
	                            'datasource' => $datasource,
	                        );
	                        
            	            break;
            	            
          //高级配色方案
	       case 'advanced_color_schemes':
	                        $style_value = array(
	                            'name'		 => $this->input['advanced_color_schemes_name'],
	                        );
	                        
	                        if($this->input['default_value'])
	                        {
    	                        $_color = explode('|', $this->input['default_value']);
                                if($_color)
                                {
                                    $_value = array();
                                    foreach ($_color AS $_kk => $_vv)
                                    {
                                        $_tmp = explode(':', $_vv);
                                        $_value[$_tmp[0]] = $_tmp[1];
                                    }
                                    $default_value = serialize($_value);
                                }
	                        }
	                        
            	            break;
            	            
          //高级背景设置
	       case 'advanced_background_set':
	                       if($_FILES['default_value'])
	                       {
	                           $img = $this->_upYunOp->uploadToBucket($_FILES['default_value']);
	                           if($img)
                               {
                                    //上传之后保存到数据库
                                    $_imginfo = array(
                                        'name'        => $img['name'],
                                        'mark'        => 'mark',
                                        'type'        => $img['type'],
                                        'filesize'    => $img['filesize'],
                                        'imgwidth'    => $img['imgwidth'],
                                        'imgheight'   => $img['imgheight'],
                                        'host'        => $img['host'],
                                        'filepath'    => $img['filepath'],
                                        'filename'    => $img['filename'],
                                        'user_id'     => $this->user['user_id'],
                                        'user_name'   => $this->user['user_name'],
                                        'org_id'      => $this->user['org_id'],
                                        'create_time' => TIMENOW,
                                        'ip'		  => hg_getip(),
                                    );
                                    $_ret = $this->app_material->create('app_material', $_imginfo);
                                    if($_ret)
                                    {
                                        $default_value = @serialize(array(
                                               'img_id' => $_ret['id'],
                                               'is_tile'=> intval($this->input['is_tile']),
                                               'width'  => $this->input['width'],//宽度
                                               'height' => $this->input['height'],//高度
                                               'info'   => $this->input['info'],//说明
                                       ));
                                    }
                                }
	                       }
	                       else if($this->input['default_value'])
	                       {
	                           $_def_value = explode('|', $this->input['default_value']);
	                           if($_def_value && is_array($_def_value))
	                           {
	                               if($_def_value[0] == 'color')
	                               {
	                                   $default_value = serialize(array('color' => $_def_value[1],'alpha' => (float)$_def_value[2]));
	                               }
	                               elseif ($_def_value[0] == 'img_id')
	                               {
	                                   $default_value = serialize(array(
                                               'img_id' => $_def_value[1],
                                               'is_tile'=> intval($this->input['is_tile']),
	                                           'width'  => $this->input['width'],//宽度
                                               'height' => $this->input['height'],//高度
                                               'info'   => $this->input['info'],//说明
                                       ));
	                               }
	                           }
	                       }
	                       
	                        $style_value = array(
	                            'name'		 => $this->input['advanced_background_set_name'],
	                            'is_tile'	 => $this->input['is_tile']?1:0,
	                        );
            	            break;
            	            
          //高级文字设置
	       case 'advanced_character_set':
	                        $style_value = array(
	                            'name'		 => $this->input['advanced_character_set_name'],
	                        );
	                        
	                       if($_FILES['default_value'])
	                       {
	                            $img = $this->_upYunOp->uploadToBucket($_FILES['default_value']);
                                if($img)
                                {
                                    //上传之后保存到数据库
                                    $_imginfo = array(
                                        'name'        => $img['name'],
                                        'mark'        => 'mark',
                                        'type'        => $img['type'],
                                        'filesize'    => $img['filesize'],
                                        'imgwidth'    => $img['imgwidth'],
                                        'imgheight'   => $img['imgheight'],
                                        'host'        => $img['host'],
                                        'filepath'    => $img['filepath'],
                                        'filename'    => $img['filename'],
                                        'user_id'     => $this->user['user_id'],
                                        'user_name'   => $this->user['user_name'],
                                        'org_id'      => $this->user['org_id'],
                                        'create_time' => TIMENOW,
                                        'ip'		  => hg_getip(),
                                    );
                                    $_ret = $this->app_material->create('app_material', $_imginfo);
                                    if($_ret)
                                    {
                                       $default_value = @serialize(array(
                                               'img_id' => $_ret['id'],
                                               'width'  => $this->input['width'],//宽度
                                               'height' => $this->input['height'],//高度
                                               'info'   => $this->input['info'],//说明
                                       ));
                                    }
                                }
	                       }
	                       else if($this->input['default_value'])
	                       {
	                           $_def_value = explode('|', $this->input['default_value']);
	                           if($_def_value && is_array($_def_value))
	                           {
	                               if($_def_value[0] == 'text')
	                               {
	                                   $default_value = serialize(array('text' => $_def_value[1]));
	                               }
	                               elseif ($_def_value[0] == 'img_id')
	                               {
	                                   $default_value = serialize(array(
                                               'img_id' => $_def_value[1],
	                                           'width'  => $this->input['width'],//宽度
                                               'height' => $this->input['height'],//高度
                                               'info'   => $this->input['info'],//说明
                                       ));
	                               }
	                           }
	                       }
            	           break;
	    }
	    
		$update_data = array(
			'name'           => $name,
		    'role_type_id'   => $role_type_id,
		    'attr_type_id'   => $attr_type_id,
		    'set_value_type' => $set_value_type,
		    'style_value'    => serialize($style_value),
		    'default_value'  => $default_value,
		    'update_time'    => TIMENOW,
		);
		$ret = $this->mode->update($id,$update_data);
		if($ret)
		{
			$this->addLogs('更新属性',$ret,'','更新属性' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new superscript_ui_attr_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();