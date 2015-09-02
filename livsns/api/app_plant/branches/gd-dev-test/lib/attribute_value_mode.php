<?php
require_once(CUR_CONF_PATH . 'lib/appMaterial.class.php');
class attribute_value_mode extends InitFrm
{
    private $app_material;
	public function __construct()
	{
		parent::__construct();
		$this->app_material = new appMaterial();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	/*
	 * 获取属性数据
	 * $id:风格ui
	 * $is_group：输出的数据是否按照分组id来分组
	 * $is_config:是否按照配置文件的格式要求输出默认值的数据格式
	 * $role_id:角色，默认普通用户
	 */
	public function getAttributeData($id = '',$is_group = true,$app_id = 0,$module_id = 0,$is_config = FALSE,$role_id = 1)
	{
	    if(!$id)
	    {
	        return false;
	    }
	    
	    /***********************************************获取用户自己设置的属性值***************************************/
	    
	    if($app_id)//如果存在app_id，取出该app_id相应的属性值
	    {
	        $sql = "SELECT * FROM " .DB_PREFIX. "attribute_main_value WHERE app_id = '" .$app_id. "' ";
	    }
	    elseif ($module_id)//如果存在$module_id，取出该$module_id相应的属性值
	    {
	        $sql = "SELECT * FROM " .DB_PREFIX. "attribute_list_value WHERE module_id = '" .$module_id. "' ";
	    }
	    
	    $attr_value_arr = array();
	    if($sql)
	    {
	        $q = $this->db->query($sql);
	        while ($r = $this->db->fetch_array($q))
	        {
                $attr_value_arr[$r['relate_id']] = $r['attr_value'];          
	        }
	    }
	    /***********************************************获取用户自己设置的属性值***************************************/
	    
	    //增加角色的筛选(后台的属性现在不需要区分角色)
	    /*
	    $_cond = '';
	    if($role_id && in_array($role_id, array(-1,1,2)))
	    {
	        $_cond = " AND ar.role_type_id = '" .$role_id. "' ";
	    }
	    else 
	    {
	        $_cond = " AND ar.role_type_id = -1 ";//如果传入的角色id不合法就默认就取出适合所有
	    }
	    */
	    
	    //获取属性
	    $sql = "SELECT a.*,ar.name AS attr_name,ar.group_id,ar.role_type_id,ar.style_value AS attr_style_value,ar.default_value AS attr_default_value,ar.id AS relate_id FROM " .DB_PREFIX. "attribute_relate ar 
	    														  LEFT JOIN " .DB_PREFIX. "attribute a ON ar.attr_id = a.id
	    														  WHERE ar.ui_id = '" .$id. "' AND ar.role_type_id = 2 ";
	    $q = $this->db->query($sql);
	    $attrArr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        //获取样式
	        if($r['attr_style_value'] && unserialize($r['attr_style_value']))
	        {
	            $r['attr_style_value'] = unserialize($r['attr_style_value']);
	        }
	        else if($r['style_value'] && unserialize($r['style_value'])) 
	        {
	            $r['attr_style_value'] = unserialize($r['style_value']);
	        }
	        
	        //获取默认值
	        if(isset($attr_value_arr[$r['relate_id']]))//说明用户自己设置过值，没有设置就用默认值
	        {
	            $r['attr_default_value'] = $attr_value_arr[$r['relate_id']];
	        }
            
	        if($r['attr_default_value'] && unserialize($r['attr_default_value']))
	        {
	            $r['attr_default_value'] = unserialize($r['attr_default_value']);
	        }
	        
	        $r['attr_type_uniqueid'] = $this->settings['attribute_type'][intval($r['attr_type_id'])]['uniqueid'];

	        if($r['attr_type_uniqueid'])
	        {
	            //按照打包配置文件的需要的数据结构输出
	            if($is_config)
	            {
	                switch ($r['attr_type_uniqueid'])
	                {
    	                //文本框
    	                case 'textbox':break;
                        //文本域
            	        case 'textfield':break;
            	        //单选
            	        case 'single_choice':break;
            	        //勾选
            	        case 'check':$r['attr_default_value'] = (bool)$r['attr_default_value'];break;
            	        //取值范围
            	        case 'span':break;
            	        //图片单选
            	        case 'pic_radio':break;//图片单选只要输出值就可以了
            	        //图片上传+单选，他的值是选中了某个图片的id
            	        case 'pic_upload_radio':
            	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['selected']) && $r['attr_default_value']['selected'])
            	                        {
            	                            //取这张图片
            	                            $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['selected']));
            	                            if($_img_info)
            	                            {
            	                                $r['attr_default_value'] = array(
            	                                        'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
            	                                        'isRepeat' => FALSE,
            	                                        'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
            	                                        'img_id'   => $r['attr_default_value']['selected'],
            	                                );
            	                            }
            	                        }
            	                        break;
            	        //多选
            	        case 'multiple_choice':break;
            	        //拾色器
            	        case 'color_picker':
            	                        if($r['attr_default_value'])
            	                        {
            	                           $r['attr_default_value'] = Common::convertColor($r['attr_default_value']);
            	                        }
            	                        else
            	                        {
            	                            $r['attr_default_value'] = Common::convertColor();//用户没有设置，采用默认的值
            	                        }
            	                        break;
            	        //高级拾色器
            	        case 'advanced_color_picker':
            	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['color']) && $r['attr_default_value']['color'])
            	                        {
            	                           $r['attr_default_value'] = Common::convertColor($r['attr_default_value']['color'],$r['attr_default_value']['alpha']);
            	                        }
	                                    else 
            	                        {
            	                            $r['attr_default_value'] = Common::convertColor();//用户没有设置，采用默认的值
            	                        }
            	                        break;
            	        //配色方案
            	        case 'color_schemes':
	                                    if($r['attr_default_value'])
            	                        {
            	                           $r['attr_default_value'] = Common::convertColor($r['attr_default_value']);
            	                        }       
            	                        break;
            	        //高级配色方案
            	        case 'advanced_color_schemes':
            	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['main']) && $r['attr_default_value']['main'])
            	                        {
            	                            $r['attr_default_value'] = Common::convertColor($r['attr_default_value']['color']);
            	                        }
            	                        break;
            	        //高级背景设置
            	        case 'advanced_background_set':
            	                        //背景是图片的情况
            	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['img_id']) && $r['attr_default_value']['img_id'])
            	                        {
            	                            //取这张图片
            	                            $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['img_id']));
            	                            if($_img_info)
            	                            {
            	                                $r['attr_default_value'] = array(
            	                                        'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
            	                                        'isRepeat' => isset($r['attr_default_value']['is_tile'])?(bool)($r['attr_default_value']['is_tile']):FALSE,
            	                                        'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
            	                                        'img_id'   => $r['attr_default_value']['img_id'],
            	                                );
            	                            }
            	                        }
            	                        elseif (is_array($r['attr_default_value']) && isset($r['attr_default_value']['color']) && $r['attr_default_value']['color'])//背景是颜色的情况      
            	                        {
                	                        $r['attr_default_value'] = Common::convertColor($r['attr_default_value']['color'],(float)$r['attr_default_value']['alpha']);
            	                        }
            	                        else //临时解决一下
            	                        {
            	                            $r['attr_default_value'] = Common::convertColor($r['attr_default_value']);
            	                        }
            	                        break;
            	        //高级文字设置
            	        case 'advanced_character_set':
	                                    //背景是图片的情况
            	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['img_id']) && $r['attr_default_value']['img_id'])
            	                        {
            	                            //取这张图片
            	                            $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['img_id']));
            	                            if($_img_info)
            	                            {                            
            	                                //titleContent做特殊处理
            	                                if($r['uniqueid'] == 'titleContent')
            	                                {
            	                                    $r['attr_default_value'] = array(
            	                                             'text' => '',
                        		                             'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
                        		                             'drawableHeight' => 40,
                        		                             'drawableWHScale' => 4,
            	                                             'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
            	                                             'img_id'  => $r['attr_default_value']['img_id'],
            	                                    );
            	                                }
            	                                else 
            	                                {
            	                                    $r['attr_default_value'] = array(
            	                                        'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
            	                                        'isRepeat' => FALSE,
            	                                        'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
            	                                        'img_id'   => $r['attr_default_value']['img_id'],
            	                                    );
            	                                }
            	                            }
            	                        }
            	                        elseif (is_array($r['attr_default_value']) && isset($r['attr_default_value']['text']))//单纯文字的情况    
            	                        {
            	                            //titleContent做特殊处理
        	                                if($r['uniqueid'] == 'titleContent')
        	                                {
        	                                    $r['attr_default_value'] = array(
        	                                             'text' => $r['attr_default_value']['text'],
                    		                             'drawable' => '',
                    		                             'drawableHeight' => 40,
                    		                             'drawableWHScale' => 4,
        	                                    );
        	                                }
        	                                else 
        	                                {
        	                                    $r['attr_default_value'] = $r['attr_default_value']['text'];
        	                                }
            	                        }
            	                        else
            	                        {
            	                            //做一个特殊处理，主要是解决普通用户前台的titleContent是文本样式与后台高级文字设置绑定
            	                            if($r['uniqueid'] == 'titleContent')
        	                                {
        	                                    $r['attr_default_value'] = array(
        	                                             'text' => $r['attr_default_value'],
                    		                             'drawable' => '',
                    		                             'drawableHeight' => 40,
                    		                             'drawableWHScale' => 4,
        	                                    );
        	                                }
            	                        }
            	                        break;
	                }
	            }
	            else //按照前台需要的数据结构输出
	            {
    	            //图片上传+单选
        	        if($r['attr_type_uniqueid'] == 'pic_upload_radio')
        	        {
        	             //获取图片信息
        	             if(isset($r['attr_default_value']['img_ids']) && $r['attr_default_value']['img_ids'])
        	             {
        	                 $_img_info = $this->app_material->getMaterial(" AND id IN (" .$r['attr_default_value']['img_ids']. ") ");
        	                 $r['attr_default_value'] = array(
                                 'img'      => $_img_info,
                                 'selected' => isset($r['attr_default_value']['selected'])?$r['attr_default_value']['selected']:0,
                             );
        	             }
        	        }
        	        elseif ($r['attr_type_uniqueid'] == 'advanced_background_set')//高级背景设置
        	        {
        	            if(isset($r['attr_default_value']['img_id']) && $r['attr_default_value']['img_id'])
        	            {
        	                $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['img_id']));
        	                $r['attr_default_value'] = array(
                                 'img'      => $_img_info,
                                 'is_tile'  => isset($r['attr_default_value']['is_tile'])?intval($r['attr_default_value']['is_tile']):0,
                            );
        	            }
        	        }
        	        elseif ($r['attr_type_uniqueid'] == 'advanced_character_set')//高级文字设置
        	        {
        	            if(isset($r['attr_default_value']['img_id']) && $r['attr_default_value']['img_id'])
        	            {
        	                $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['img_id']));
        	                $r['attr_default_value'] = array(
                                 'img'      => $_img_info,
                            );
        	            }
        	        }
	            }
	        }
	        
	        unset($r['default_value'],$r['style_value']);
	        
	        if($is_group)
	        {
	            $attrArr[$r['group_id']][] = $r;
	        }
	        else 
	        {
	            $attrArr[] = $r;
	        }
	    }
	    return $attrArr;
	}
	
	//获取属性组数据
	public function getGroupData()
	{
	    $sql = "SELECT * FROM " .DB_PREFIX. "attribute_group";
	    $q = $this->db->query($sql);
	    $groupArr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $groupArr[] = $r;
	    }
	    return $groupArr;
	}
	
	//获取模块信息
	public function getModuleInfo($module_id = '')
	{
	    if(!$module_id)
	    {
	        return false;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "app_module WHERE id = '" .$module_id. "' ";
	    $ret = array();
	    $ret = $this->db->query_first($sql);
	    if($ret)
	    {
	        if (unserialize($ret['pic']))
            {
                $ret['pic'] = unserialize($ret['pic']);
            }
            	
            if (unserialize($ret['press_pic']))
            {
                $ret['press_pic'] = unserialize($ret['press_pic']);
            }
            
            if ($ret['bind_id'] && unserialize($ret['bind_params']))
            {
                $ret['bind_params'] = unserialize($ret['bind_params']);
            }
	    }
	    return $ret;
	}
	
	public function setMainUIValue($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "attribute_main_value WHERE app_id = '" .$data['app_id']. "' AND relate_id = '" .$data['relate_id']. "' ";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
		    $sql = " UPDATE " . DB_PREFIX . "attribute_main_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE id = '"  .$pre_data['id']. "'";
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "attribute_main_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
		}
		$this->db->query($sql);
	}
	
	//获取属性类型groupBy relate_id
	public function getAttrTypeWithids($relate_ids = array())
	{
	    if(!$relate_ids)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT ar.id,a.attr_type_id FROM " . DB_PREFIX . "attribute_relate ar LEFT JOIN "
	                            . DB_PREFIX . "attribute a ON a.id = ar.attr_id WHERE ar.id IN (" .implode(',', $relate_ids). ") ";
	    $q = $this->db->query($sql);
	    $ret = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $ret[$r['id']] = $this->settings['attribute_type'][$r['attr_type_id']]['uniqueid'];
	    }
	    return $ret;
	}
	
	//设置LIST_UI值
	public function setListUIValue($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "attribute_list_value WHERE module_id = '" .$data['module_id']. "' AND relate_id = '" .$data['relate_id']. "' ";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
		    $sql = " UPDATE " . DB_PREFIX . "attribute_list_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE id = '"  .$pre_data['id']. "'";
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "attribute_list_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
		}
		$this->db->query($sql);
	}
	
	//获取某个UI下的前台属性
	public function getFrontAttributeData($id = '',$app_id = 0,$module_id = 0,$role_id = 1)
	{
        if(!$id)
	    {
	        return false;
	    }
	    
	    /***********************************************获取用户自己设置的属性值***************************************/
	    
	    if($app_id)//如果存在app_id，取出该app_id相应的属性值
	    {
	        $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_main_value WHERE app_id = '" .$app_id. "' ";
	    }
	    elseif ($module_id)//如果存在$module_id，取出该$module_id相应的属性值
	    {
	        $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_list_value WHERE module_id = '" .$module_id. "' ";
	    }
	    
	    $attr_value_arr = array();
	    if($sql)
	    {
	        $q = $this->db->query($sql);
	        while ($r = $this->db->fetch_array($q))
	        {
                $attr_value_arr[$r['ui_attr_id']] = $r['attr_value'];          
	        }
	    }
	    /***********************************************获取用户自己设置的属性值***************************************/
	    
	    //增加角色的筛选
	    $_cond = '';
	    if($role_id && in_array($role_id, array(-1,1,2)))
	    {
	        $_cond = " AND ua.role_type_id = '" .$role_id. "' ";
	    }
	    else 
	    {
	        $_cond = " AND ua.role_type_id = -1 ";//如果传入的角色id不合法就默认就取出适合所有
	    }
	    
	    $_cond .= " AND ua.is_display = 1 ";
	    
	    $order_by = " ORDER BY uag.order_id ASC,ua.order_id DESC ";
	    
	    //获取属性
	    $sql = "SELECT ua.* FROM " . DB_PREFIX . "ui_attribute ua LEFT JOIN " .DB_PREFIX. "ui_attribute_group uag ON ua.group_id = uag.id WHERE ua.ui_id = '" .$id. "' " . $_cond . $order_by;
	    $q = $this->db->query($sql);
	    $attrArr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        //获取样式
	        if($r['style_value'] && unserialize($r['style_value']))
	        {
	            $r['style_value'] = unserialize($r['style_value']);
	        }
	        
	        //获取默认值
	        if(isset($attr_value_arr[$r['id']]))
	        {
	            $r['default_value'] = $attr_value_arr[$r['id']];
	        }
            
	        if($r['default_value'] && unserialize($r['default_value']))
	        {
	            $r['default_value'] = unserialize($r['default_value']);
	        }
	        
	        $r['attr_type_uniqueid'] = $this->settings['attribute_type'][$r['attr_type_id']]['uniqueid'];

	        if($r['default_value'])
	        {
	            //图片上传+单选
    	        if($r['attr_type_uniqueid'] == 'pic_upload_radio')
    	        {
    	             //获取图片信息
    	             if(isset($r['default_value']['img_ids']) && $r['default_value']['img_ids'])
    	             {
    	                 $_img_info = $this->app_material->getMaterial(" AND id IN (" .$r['default_value']['img_ids']. ") ");
    	                 $r['default_value'] = array(
                             'img'      => $_img_info,
                             'selected' => isset($r['default_value']['selected'])?$r['default_value']['selected']:0,
                         );
    	             }
    	        }
    	        elseif ($r['attr_type_uniqueid'] == 'advanced_background_set')//高级背景设置
    	        {
    	            if(isset($r['default_value']['img_id']) && $r['default_value']['img_id'])
    	            {
    	                $_img_info = $this->app_material->detail('app_material', array('id' => $r['default_value']['img_id']));
    	                $r['default_value'] = array(
                             'img'      => $_img_info,
                             'is_tile'  => isset($r['default_value']['is_tile'])?intval($r['default_value']['is_tile']):0,
    	                     'width'    => isset($r['default_value']['width'])?$r['default_value']['width']:$this->settings['attr_pic_set']['width'],//宽度
                             'height'   => isset($r['default_value']['height'])?$r['default_value']['height']:$this->settings['attr_pic_set']['height'],//高度
                             'info'     => isset($r['default_value']['info'])?$r['default_value']['info']:'',//说明
                        );
    	            }
    	        }
    	        elseif ($r['attr_type_uniqueid'] == 'advanced_character_set')//高级文字设置
    	        {
    	            if(isset($r['default_value']['img_id']) && $r['default_value']['img_id'])
    	            {
    	                $_img_info = $this->app_material->detail('app_material', array('id' => $r['default_value']['img_id']));
    	                $r['default_value'] = array(
                             'img'      => $_img_info,
    	                     'width'    => isset($r['default_value']['width'])?$r['default_value']['width']:$this->settings['attr_pic_set']['width'],//宽度
                             'height'   => isset($r['default_value']['height'])?$r['default_value']['height']:$this->settings['attr_pic_set']['height'],//高度
                             'info'     => isset($r['default_value']['info'])?$r['default_value']['info']:'',//说明
                        );
    	            }
    	        }
	        }
	        
	        $r['attr_style_value']   = $r['style_value'];
	        $r['attr_default_value'] = $r['default_value'];
	        unset($r['style_value'],$r['default_value']);
	        $attrArr[$r['group_id']][] = $r;
	    }
	    return $attrArr;
	}
	
	//获取前端属性组
    public function getFrontGroupData()
	{
	    $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_group";
	    $q = $this->db->query($sql);
	    $groupArr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $groupArr[] = $r;
	    }
	    return $groupArr;
	}
	
	//获取前台属性可能是多个
	public function getFrontAttrByIds($ids = array())
	{
	    if(!$ids)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute WHERE id IN (" .implode(',', $ids). ")";
	    $q = $this->db->query($sql);
	    $ret = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $r['attr_type_name'] = $this->settings['attribute_type'][$r['attr_type_id']]['uniqueid']; 
	        $ret[] = $r;
	    }
	    return $ret;
	}
	
	//设置前台MAIN_UI属性值
    public function setFrontMainUIValue($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_main_value WHERE app_id = '" .$data['app_id']. "' AND ui_attr_id = '" .$data['ui_attr_id']. "' ";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
		    $sql = " UPDATE " . DB_PREFIX . "ui_attribute_main_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE id = '"  .$pre_data['id']. "'";
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "ui_attribute_main_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
		}
		$this->db->query($sql);
	}
	
	//设置前台LIST_UI的属性值
	public function setFrontListUIValue($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_list_value WHERE module_id = '" .$data['module_id']. "' AND ui_attr_id = '" .$data['ui_attr_id']. "' ";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
		    $sql = " UPDATE " . DB_PREFIX . "ui_attribute_list_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE id = '"  .$pre_data['id']. "'";
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "ui_attribute_list_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
		}
		$this->db->query($sql);
	}
	
	//对前台属性关联的后台属性统一设置值
	public function setFrontAttrSameToRelate($ui_attr_id = '',$value = '',$app_id = '',$module_id = '')
	{
	    if(!$ui_attr_id || (!$app_id && !$module_id))
	    {
	        return FALSE;
	    }
	    
	    //首先查询出关联关系
	    $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_relate WHERE ui_attr_id = '" .$ui_attr_id. "' ";
	    $q = $this->db->query($sql);
	    $relate_ids = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $relate_ids[] = $r['relate_id'];
	    }
	    
	    if($relate_ids)
	    {
	        foreach ($relate_ids AS $_id)
	        {
	            if($app_id)
	            {
	                $this->setMainUIValue(array(
                        'app_id'    => $app_id,
                        'relate_id' => $_id,
                        'attr_value'=> $value,
                    ));
	            }
	            elseif ($module_id)
	            {
	                $this->setListUIValue(array(
                        'module_id' => $module_id,
                        'relate_id' => $_id,
                        'attr_value'=> $value,
                    ));
	            }
	        }
	    }
	}
	
	//根据前端属性的值从预设表里面查出对应设置给后台属性的值
	public function setFrontAttrEachToRelate($ui_attr_id = '',$value = '',$app_id = '',$module_id = '')
	{
	    if(!$ui_attr_id || !intval($value) || (!$app_id && !$module_id))
	    {
	        return FALSE;
	    }
	    $value_id = intval($value);
	    
	    //查询出预设值
	    $sql = "SELECT relate_id,default_value FROM " .DB_PREFIX. "ui_attribute_value_config WHERE value_id = '" .$value_id. "' ";
	    $q = $this->db->query($sql);
	    $value_arr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	       $value_arr[] = $r;
	    }
	    
	    if($value_arr)
	    {
	        foreach ($value_arr AS $_v)
	        {
	            if($app_id)
	            {
	                $this->setMainUIValue(array(
                        'app_id'    => $app_id,
                        'relate_id' => $_v['relate_id'],
                        'attr_value'=> $_v['default_value'],
                    ));
	            }
	            elseif ($module_id)
	            {
	                $this->setListUIValue(array(
                        'module_id' => $module_id,
                        'relate_id' => $_v['relate_id'],
                        'attr_value'=> $_v['default_value'],
                    ));
	            }
	        }
	    }
	}
	
	/******************************************************针对组件的listUI相关配置的操作**********************************************************/

	//获取针对组件的前台的属性
	public function getFrontCompAttributeData($id = '',$comp_id = 0,$role_id = 1)
	{
        if(!$id)
	    {
	        return false;
	    }
	    
	    /***********************************************获取用户自己设置的属性值***************************************/
	    if ($comp_id)//如果存在$comp_id，取出该$comp_id相应的属性值
	    {
	        $sql = "SELECT * FROM " .DB_PREFIX. "components_ui_list_value WHERE comp_id = '" .$comp_id. "' ";
	    }
	    
	    $attr_value_arr = array();
	    if($sql)
	    {
	        $q = $this->db->query($sql);
	        while ($r = $this->db->fetch_array($q))
	        {
                $attr_value_arr[$r['ui_attr_id']] = $r['attr_value'];          
	        }
	    }
	    /***********************************************获取用户自己设置的属性值***************************************/
	    
	    //增加角色的筛选
	    $_cond = '';
	    if($role_id && in_array($role_id, array(-1,1,2)))
	    {
	        $_cond = " AND ua.role_type_id = '" .$role_id. "' ";
	    }
	    else 
	    {
	        $_cond = " AND ua.role_type_id = -1 ";//如果传入的角色id不合法就默认就取出适合所有
	    }
	    
	    $_cond .= " AND ua.is_comp = 1 ";//获取适用于组件的属性

	    $order_by = " ORDER BY uag.order_id ASC,ua.order_id DESC ";
	    
	    //获取属性
	    $sql = "SELECT ua.* FROM " . DB_PREFIX . "ui_attribute ua LEFT JOIN " .DB_PREFIX. "ui_attribute_group uag ON ua.group_id = uag.id WHERE ua.ui_id = '" .$id. "' " . $_cond . $order_by;
	    $q = $this->db->query($sql);
	    $attrArr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        //获取样式
	        if($r['style_value'] && unserialize($r['style_value']))
	        {
	            $r['style_value'] = unserialize($r['style_value']);
	        }
	        
	        //获取默认值
	        if(isset($attr_value_arr[$r['id']]))
	        {
	            $r['default_value'] = $attr_value_arr[$r['id']];
	        }
            
	        if($r['default_value'] && unserialize($r['default_value']))
	        {
	            $r['default_value'] = unserialize($r['default_value']);
	        }
	        
	        $r['attr_type_uniqueid'] = $this->settings['attribute_type'][$r['attr_type_id']]['uniqueid'];

	        if($r['default_value'])
	        {
	            //图片上传+单选
    	        if($r['attr_type_uniqueid'] == 'pic_upload_radio')
    	        {
    	             //获取图片信息
    	             if(isset($r['default_value']['img_ids']) && $r['default_value']['img_ids'])
    	             {
    	                 $_img_info = $this->app_material->getMaterial(" AND id IN (" .$r['default_value']['img_ids']. ") ");
    	                 $r['default_value'] = array(
                             'img'      => $_img_info,
                             'selected' => isset($r['default_value']['selected'])?$r['default_value']['selected']:0,
                         );
    	             }
    	        }
    	        elseif ($r['attr_type_uniqueid'] == 'advanced_background_set')//高级背景设置
    	        {
    	            if(isset($r['default_value']['img_id']) && $r['default_value']['img_id'])
    	            {
    	                $_img_info = $this->app_material->detail('app_material', array('id' => $r['default_value']['img_id']));
    	                $r['default_value'] = array(
                             'img'      => $_img_info,
                             'is_tile'  => isset($r['default_value']['is_tile'])?intval($r['default_value']['is_tile']):0,
    	                     'width'    => isset($r['default_value']['width'])?$r['default_value']['width']:$this->settings['attr_pic_set']['width'],//宽度
                             'height'   => isset($r['default_value']['height'])?$r['default_value']['height']:$this->settings['attr_pic_set']['height'],//高度
                             'info'     => isset($r['default_value']['info'])?$r['default_value']['info']:'',//说明
                        );
    	            }
    	        }
    	        elseif ($r['attr_type_uniqueid'] == 'advanced_character_set')//高级文字设置
    	        {
    	            if(isset($r['default_value']['img_id']) && $r['default_value']['img_id'])
    	            {
    	                $_img_info = $this->app_material->detail('app_material', array('id' => $r['default_value']['img_id']));
    	                $r['default_value'] = array(
                             'img'      => $_img_info,
    	                     'width'    => isset($r['default_value']['width'])?$r['default_value']['width']:$this->settings['attr_pic_set']['width'],//宽度
                             'height'   => isset($r['default_value']['height'])?$r['default_value']['height']:$this->settings['attr_pic_set']['height'],//高度
                             'info'     => isset($r['default_value']['info'])?$r['default_value']['info']:'',//说明
                        );
    	            }
    	        }
	        }
	        
	        $r['attr_style_value']   = $r['style_value'];
	        $r['attr_default_value'] = $r['default_value'];
	        unset($r['style_value'],$r['default_value']);
	        $attrArr[$r['group_id']][] = $r;
	    }
	    return $attrArr;
	}
	
    //设置针对组件前台LIST_UI的属性值
	public function setFrontCompListUIValue($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "components_ui_list_value WHERE comp_id = '" .$data['comp_id']. "' AND ui_attr_id = '" .$data['ui_attr_id']. "' ";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
		    $sql = " UPDATE " . DB_PREFIX . "components_ui_list_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE id = '"  .$pre_data['id']. "'";
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "components_ui_list_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
		}
		$this->db->query($sql);
	}
	
    //设置针对组件LIST_UI值
	public function setCompListUIValue($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "components_list_value WHERE comp_id = '" .$data['comp_id']. "' AND relate_id = '" .$data['relate_id']. "' ";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
		    $sql = " UPDATE " . DB_PREFIX . "components_list_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE id = '"  .$pre_data['id']. "'";
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "components_list_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
		}
		$this->db->query($sql);
	}
	
    //对组件前台listui属性关联的后台属性统一设置值
	public function setFrontCompAttrSameToRelate($ui_attr_id = '',$value = '',$comp_id = '')
	{
	    if(!$ui_attr_id || !$comp_id)
	    {
	        return FALSE;
	    }
	    
	    //首先查询出关联关系
	    $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_relate WHERE ui_attr_id = '" .$ui_attr_id. "' ";
	    $q = $this->db->query($sql);
	    $relate_ids = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $relate_ids[] = $r['relate_id'];
	    }
	    
	    if($relate_ids)
	    {
	        foreach ($relate_ids AS $_id)
	        {
                $this->setCompListUIValue(array(
                    'comp_id'   => $comp_id,
                    'relate_id' => $_id,
                    'attr_value'=> $value,
                ));
	        }
	    }
	}
	
    //根据组件前端属性的值从预设表里面查出对应设置给后台属性的值
	public function setFrontCompAttrEachToRelate($ui_attr_id = '',$value = '',$comp_id = '')
	{
	    if(!$ui_attr_id || !intval($value) || !$comp_id)
	    {
	        return FALSE;
	    }
	    $value_id = intval($value);
	    
	    //查询出预设值
	    $sql = "SELECT relate_id,default_value FROM " .DB_PREFIX. "ui_attribute_value_config WHERE value_id = '" .$value_id. "' ";
	    $q = $this->db->query($sql);
	    $value_arr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	       $value_arr[] = $r;
	    }
	    
	    if($value_arr)
	    {
	        foreach ($value_arr AS $_v)
	        {
                $this->setCompListUIValue(array(
                    'comp_id'   => $comp_id,
                    'relate_id' => $_v['relate_id'],
                    'attr_value'=> $_v['default_value'],
                ));
	        }
	    }
	}
	
	/*
	 * 获取后台针对组件属性数据
	 * $id:风格ui
	 * $is_config:是否按照配置文件的格式要求输出默认值的数据格式
	 */
	public function getCompAttributeData($id = '',$comp_id = 0)
	{
	    if(!$id)
	    {
	        return false;
	    }
	    
	    /***********************************************获取用户自己设置的属性值***************************************/
	    if ($comp_id)//如果存在$comp_id，取出该$comp_id相应的属性值
	    {
	        $sql = "SELECT * FROM " .DB_PREFIX. "components_list_value WHERE comp_id = '" .$comp_id. "' ";
	    }
	    
	    $attr_value_arr = array();
	    if($sql)
	    {
	        $q = $this->db->query($sql);
	        while ($r = $this->db->fetch_array($q))
	        {
                $attr_value_arr[$r['relate_id']] = $r['attr_value'];          
	        }
	    }
	    /***********************************************获取用户自己设置的属性值***************************************/
	   
	    //获取属性
	    $sql = "SELECT a.*,ar.name AS attr_name,ar.group_id,ar.role_type_id,ar.style_value AS attr_style_value,ar.default_value AS attr_default_value,ar.id AS relate_id FROM " .DB_PREFIX. "attribute_relate ar 
	    														  LEFT JOIN " .DB_PREFIX. "attribute a ON ar.attr_id = a.id
	    														  WHERE ar.ui_id = '" .$id. "' AND ar.role_type_id = 2 AND ar.is_comp = 1 ";
	    $q = $this->db->query($sql);
	    $attrArr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        //获取样式
	        if($r['attr_style_value'] && unserialize($r['attr_style_value']))
	        {
	            $r['attr_style_value'] = unserialize($r['attr_style_value']);
	        }
	        else if($r['style_value'] && unserialize($r['style_value'])) 
	        {
	            $r['attr_style_value'] = unserialize($r['style_value']);
	        }
	        
	        //获取默认值
	        if(isset($attr_value_arr[$r['relate_id']]))//说明用户自己设置过值，没有设置就用默认值
	        {
	            $r['attr_default_value'] = $attr_value_arr[$r['relate_id']];
	        }
            
	        if($r['attr_default_value'] && unserialize($r['attr_default_value']))
	        {
	            $r['attr_default_value'] = unserialize($r['attr_default_value']);
	        }
	        
	        $r['attr_type_uniqueid'] = $this->settings['attribute_type'][intval($r['attr_type_id'])]['uniqueid'];

	        if($r['attr_type_uniqueid'])
	        {
	            //按照打包配置文件的需要的数据结构输出
                switch ($r['attr_type_uniqueid'])
                {
	                //文本框
	                case 'textbox':break;
                    //文本域
        	        case 'textfield':break;
        	        //单选
        	        case 'single_choice':break;
        	        //勾选
        	        case 'check':$r['attr_default_value'] = (bool)$r['attr_default_value'];break;
        	        //取值范围
        	        case 'span':break;
        	        //图片单选
        	        case 'pic_radio':break;//图片单选只要输出值就可以了
        	        //图片上传+单选，他的值是选中了某个图片的id
        	        case 'pic_upload_radio':
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['selected']) && $r['attr_default_value']['selected'])
        	                        {
        	                            //取这张图片
        	                            $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['selected']));
        	                            if($_img_info)
        	                            {
        	                                $r['attr_default_value'] = array(
        	                                        'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
        	                                        'isRepeat' => FALSE,
        	                                        'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
        	                                        'img_id'   => $r['attr_default_value']['selected'],
        	                                );
        	                            }
        	                        }
        	                        break;
        	        //多选
        	        case 'multiple_choice':break;
        	        //拾色器
        	        case 'color_picker':
        	                        if($r['attr_default_value'])
        	                        {
        	                           $r['attr_default_value'] = Common::convertColor($r['attr_default_value']);
        	                        }
        	                        else
        	                        {
        	                            $r['attr_default_value'] = Common::convertColor();//用户没有设置，采用默认的值
        	                        }
        	                        break;
        	        //高级拾色器
        	        case 'advanced_color_picker':
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['color']) && $r['attr_default_value']['color'])
        	                        {
        	                           $r['attr_default_value'] = Common::convertColor($r['attr_default_value']['color'],$r['attr_default_value']['alpha']);
        	                        }
                                    else 
        	                        {
        	                            $r['attr_default_value'] = Common::convertColor();//用户没有设置，采用默认的值
        	                        }
        	                        break;
        	        //配色方案
        	        case 'color_schemes':
                                    if($r['attr_default_value'])
        	                        {
        	                           $r['attr_default_value'] = Common::convertColor($r['attr_default_value']);
        	                        }       
        	                        break;
        	        //高级配色方案
        	        case 'advanced_color_schemes':
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['main']) && $r['attr_default_value']['main'])
        	                        {
        	                            $r['attr_default_value'] = Common::convertColor($r['attr_default_value']['color']);
        	                        }
        	                        break;
        	        //高级背景设置
        	        case 'advanced_background_set':
        	                        //背景是图片的情况
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['img_id']) && $r['attr_default_value']['img_id'])
        	                        {
        	                            //取这张图片
        	                            $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['img_id']));
        	                            if($_img_info)
        	                            {
        	                                $r['attr_default_value'] = array(
        	                                        'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
        	                                        'isRepeat' => isset($r['attr_default_value']['is_tile'])?(bool)($r['attr_default_value']['is_tile']):FALSE,
        	                                        'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
        	                                        'img_id'   => $r['attr_default_value']['img_id'],
        	                                );
        	                            }
        	                        }
        	                        elseif (is_array($r['attr_default_value']) && isset($r['attr_default_value']['color']) && $r['attr_default_value']['color'])//背景是颜色的情况      
        	                        {
            	                        $r['attr_default_value'] = Common::convertColor($r['attr_default_value']['color'],(float)$r['attr_default_value']['alpha']);
        	                        }
        	                        else //临时解决一下
        	                        {
        	                            $r['attr_default_value'] = Common::convertColor($r['attr_default_value']);
        	                        }
        	                        break;
        	        //高级文字设置
        	        case 'advanced_character_set':
                                    //背景是图片的情况
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['img_id']) && $r['attr_default_value']['img_id'])
        	                        {
        	                            //取这张图片
        	                            $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['img_id']));
        	                            if($_img_info)
        	                            {                            
        	                                //titleContent做特殊处理
        	                                if($r['uniqueid'] == 'titleContent')
        	                                {
        	                                    $r['attr_default_value'] = array(
        	                                             'text' => '',
                    		                             'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
                    		                             'drawableHeight' => 40,
                    		                             'drawableWHScale' => 4,
        	                                             'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
        	                                             'img_id'  => $r['attr_default_value']['img_id'],
        	                                    );
        	                                }
        	                                else 
        	                                {
        	                                    $r['attr_default_value'] = array(
        	                                        'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
        	                                        'isRepeat' => FALSE,
        	                                        'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
        	                                        'img_id'   => $r['attr_default_value']['img_id'],
        	                                    );
        	                                }
        	                            }
        	                        }
        	                        elseif (is_array($r['attr_default_value']) && isset($r['attr_default_value']['text']))//单纯文字的情况    
        	                        {
        	                            //titleContent做特殊处理
    	                                if($r['uniqueid'] == 'titleContent')
    	                                {
    	                                    $r['attr_default_value'] = array(
    	                                             'text' => $r['attr_default_value']['text'],
                		                             'drawable' => '',
                		                             'drawableHeight' => 40,
                		                             'drawableWHScale' => 4,
    	                                    );
    	                                }
    	                                else 
    	                                {
    	                                    $r['attr_default_value'] = $r['attr_default_value']['text'];
    	                                }
        	                        }
        	                        else
        	                        {
        	                            //做一个特殊处理，主要是解决普通用户前台的titleContent是文本样式与后台高级文字设置绑定
        	                            if($r['uniqueid'] == 'titleContent')
    	                                {
    	                                    $r['attr_default_value'] = array(
    	                                             'text' => $r['attr_default_value'],
                		                             'drawable' => '',
                		                             'drawableHeight' => 40,
                		                             'drawableWHScale' => 4,
    	                                    );
    	                                }
        	                        }
        	                        break;
                }
    	    }
    	    
    	    unset($r['default_value'],$r['style_value']);
    	    $attrArr[$r['group_id']][] = $r;
    	}
    	return $attrArr;
	}
	/******************************************************针对组件的listUI相关配置的操作**********************************************************/
	
	/******************************************************针对角标的listUI相关配置的操作**********************************************************/
    //获取针对角标的前台的属性
	public function getFrontCornerAttributeData($id = '',$mod_corner_id = 0,$role_id = 1)
	{
        if(!$id)
	    {
	        return false;
	    }
	    
	    /***********************************************获取用户自己设置的属性值***************************************/
	    if ($mod_corner_id)//如果存在$mod_corner_id，取出该$mod_corner_id相应的属性值
	    {
	        $sql = "SELECT * FROM " .DB_PREFIX. "superscript_ui_attr_value WHERE mod_corner_id = '" .$mod_corner_id. "' ";
	    }
	    
	    $attr_value_arr = array();
	    if($sql)
	    {
	        $q = $this->db->query($sql);
	        while ($r = $this->db->fetch_array($q))
	        {
                $attr_value_arr[$r['ui_attr_id']] = $r['attr_value'];          
	        }
	    }
	    /***********************************************获取用户自己设置的属性值***************************************/
	    
	    //增加角色的筛选
	    $_cond = '';
	    if($role_id && in_array($role_id, array(-1,1,2)))
	    {
	        $_cond = " AND ua.role_type_id = '" .$role_id. "' ";
	    }
	    else 
	    {
	        $_cond = " AND ua.role_type_id = -1 ";//如果传入的角色id不合法就默认就取出适合所有
	    }
	    
	    $_cond .= " AND ua.is_corner = 1 ";//获取适用于角标的属性

	    $order_by = " ORDER BY uag.order_id ASC,ua.order_id DESC ";
	    
	    //获取属性
	    $sql = "SELECT ua.* FROM " . DB_PREFIX . "ui_attribute ua LEFT JOIN " .DB_PREFIX. "ui_attribute_group uag ON ua.group_id = uag.id WHERE ua.ui_id = '" .$id. "' " . $_cond . $order_by;
	    $q = $this->db->query($sql);
	    $attrArr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        //获取样式
	        if($r['style_value'] && unserialize($r['style_value']))
	        {
	            $r['style_value'] = unserialize($r['style_value']);
	        }
	        
	        //获取默认值
	        if(isset($attr_value_arr[$r['id']]))
	        {
	            $r['default_value'] = $attr_value_arr[$r['id']];
	        }
            
	        if($r['default_value'] && unserialize($r['default_value']))
	        {
	            $r['default_value'] = unserialize($r['default_value']);
	        }
	        
	        $r['attr_type_uniqueid'] = $this->settings['attribute_type'][$r['attr_type_id']]['uniqueid'];

	        if($r['default_value'])
	        {
	            //图片上传+单选
    	        if($r['attr_type_uniqueid'] == 'pic_upload_radio')
    	        {
    	             //获取图片信息
    	             if(isset($r['default_value']['img_ids']) && $r['default_value']['img_ids'])
    	             {
    	                 $_img_info = $this->app_material->getMaterial(" AND id IN (" .$r['default_value']['img_ids']. ") ");
    	                 $r['default_value'] = array(
                             'img'      => $_img_info,
                             'selected' => isset($r['default_value']['selected'])?$r['default_value']['selected']:0,
                         );
    	             }
    	        }
    	        elseif ($r['attr_type_uniqueid'] == 'advanced_background_set')//高级背景设置
    	        {
    	            if(isset($r['default_value']['img_id']) && $r['default_value']['img_id'])
    	            {
    	                $_img_info = $this->app_material->detail('app_material', array('id' => $r['default_value']['img_id']));
    	                $r['default_value'] = array(
                             'img'      => $_img_info,
                             'is_tile'  => isset($r['default_value']['is_tile'])?intval($r['default_value']['is_tile']):0,
    	                     'width'    => isset($r['default_value']['width'])?$r['default_value']['width']:$this->settings['attr_pic_set']['width'],//宽度
                             'height'   => isset($r['default_value']['height'])?$r['default_value']['height']:$this->settings['attr_pic_set']['height'],//高度
                             'info'     => isset($r['default_value']['info'])?$r['default_value']['info']:'',//说明
                        );
    	            }
    	        }
    	        elseif ($r['attr_type_uniqueid'] == 'advanced_character_set')//高级文字设置
    	        {
    	            if(isset($r['default_value']['img_id']) && $r['default_value']['img_id'])
    	            {
    	                $_img_info = $this->app_material->detail('app_material', array('id' => $r['default_value']['img_id']));
    	                $r['default_value'] = array(
                             'img'      => $_img_info,
    	                     'width'    => isset($r['default_value']['width'])?$r['default_value']['width']:$this->settings['attr_pic_set']['width'],//宽度
                             'height'   => isset($r['default_value']['height'])?$r['default_value']['height']:$this->settings['attr_pic_set']['height'],//高度
                             'info'     => isset($r['default_value']['info'])?$r['default_value']['info']:'',//说明
                        );
    	            }
    	        }
	        }
	        
	        $r['attr_style_value']   = $r['style_value'];
	        $r['attr_default_value'] = $r['default_value'];
	        unset($r['style_value'],$r['default_value']);
	        $attrArr[] = $r;
	    }
	    return $attrArr;
	}
	
	//设置前台角标属性的值
	public function setFrontCornerListUIValue($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "superscript_ui_attr_value WHERE mod_corner_id = '" .$data['mod_corner_id']. "' AND ui_attr_id = '" .$data['ui_attr_id']. "' ";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
		    $sql = " UPDATE " . DB_PREFIX . "superscript_ui_attr_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE id = '"  .$pre_data['id']. "'";
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "superscript_ui_attr_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
		}
		$this->db->query($sql);
	}
	
    //对角标前台属性关联的后台属性统一设置值
	public function setFrontCornerAttrSameToRelate($ui_attr_id = '',$value = '',$mod_corner_id = '')
	{
	    if(!$ui_attr_id || !$mod_corner_id)
	    {
	        return FALSE;
	    }
	    
	    //首先查询出关联关系
	    $sql = "SELECT * FROM " .DB_PREFIX. "ui_attribute_relate WHERE ui_attr_id = '" .$ui_attr_id. "' ";
	    $q = $this->db->query($sql);
	    $relate_ids = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $relate_ids[] = $r['relate_id'];
	    }
	    
	    if($relate_ids)
	    {
	        foreach ($relate_ids AS $_id)
	        {
                $this->setCornerListUIValue(array(
                    'mod_corner_id'    => $mod_corner_id,
                    'relate_id'        => $_id,
                    'attr_value'       => $value,
                ));
	        }
	    }
	}
	
    //根据角标前端属性的值从预设表里面查出对应设置给后台属性的值
	public function setFrontCornerAttrEachToRelate($ui_attr_id = '',$value = '',$mod_corner_id = '')
	{
	    if(!$ui_attr_id || !intval($value) || !$mod_corner_id)
	    {
	        return FALSE;
	    }
	    $value_id = intval($value);
	    
	    //查询出预设值
	    $sql = "SELECT relate_id,default_value FROM " .DB_PREFIX. "ui_attribute_value_config WHERE value_id = '" .$value_id. "' ";
	    $q = $this->db->query($sql);
	    $value_arr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	       $value_arr[] = $r;
	    }
	    
	    if($value_arr)
	    {
	        foreach ($value_arr AS $_v)
	        {
                $this->setCornerListUIValue(array(
                    'mod_corner_id'    => $mod_corner_id,
                    'relate_id'        => $_v['relate_id'],
                    'attr_value'       => $_v['default_value'],
                ));
	        }
	    }
	}
	
    //设置针对角标后台LIST_UI值
	public function setCornerListUIValue($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "superscript_attr_value WHERE mod_corner_id = '" .$data['mod_corner_id']. "' AND relate_id = '" .$data['relate_id']. "' ";
		$pre_data = $this->db->query_first($sql);
		if($pre_data)
		{
		    $sql = " UPDATE " . DB_PREFIX . "superscript_attr_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql  = trim($sql,',');
    		$sql .= " WHERE id = '"  .$pre_data['id']. "'";
		}
		else 
		{
		    $sql = " INSERT INTO " . DB_PREFIX . "superscript_attr_value SET ";
    		foreach ($data AS $k => $v)
    		{
    			$sql .= " {$k} = '{$v}',";
    		}
    		$sql = trim($sql,',');
		}
		$this->db->query($sql);
	}
	
	
	/*
	 * 获取后台针对用户使用的角标属性数据
	 */
	public function getCornerAttributeData($mod_corner_id = 0)
	{
	    /***********************************************获取用户自己设置的属性值***************************************/
	    if ($mod_corner_id)//如果存在$mod_corner_id，取出该$mod_corner_id相应的属性值
	    {
	        $sql = "SELECT * FROM " .DB_PREFIX. "superscript_attr_value WHERE mod_corner_id = '" .$mod_corner_id. "' ";
	    }
	    
	    $attr_value_arr = array();
	    if($sql)
	    {
	        $q = $this->db->query($sql);
	        while ($r = $this->db->fetch_array($q))
	        {
                $attr_value_arr[$r['relate_id']] = $r['attr_value'];          
	        }
	    }
	    /***********************************************获取用户自己设置的属性值***************************************/
	    
	    $sql = "SELECT id FROM " .DB_PREFIX. "user_interface WHERE uniqueid = '" .$this->settings['superscript']['corner_list_ui']. "' ";
	    $cornerIdArr = $this->db->query_first($sql);
	    if(!$cornerIdArr)
	    {
	        return FALSE;
	    }
	    $cornerListUIId = $cornerIdArr['id'];
	    //获取属性
	    $sql = "SELECT a.*,ar.name AS attr_name,ar.group_id,ar.role_type_id,ar.style_value AS attr_style_value,ar.default_value AS attr_default_value,ar.id AS relate_id FROM " .DB_PREFIX. "attribute_relate ar 
	    														  LEFT JOIN " .DB_PREFIX. "attribute a ON ar.attr_id = a.id
	    														  WHERE ar.ui_id = '" .$cornerListUIId. "' AND ar.role_type_id = 2 AND ar.is_corner = 1 ";
	    $q = $this->db->query($sql);
	    $attrArr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        //获取样式
	        if($r['attr_style_value'] && unserialize($r['attr_style_value']))
	        {
	            $r['attr_style_value'] = unserialize($r['attr_style_value']);
	        }
	        else if($r['style_value'] && unserialize($r['style_value'])) 
	        {
	            $r['attr_style_value'] = unserialize($r['style_value']);
	        }
	        
	        //获取默认值
	        if(isset($attr_value_arr[$r['relate_id']]))//说明用户自己设置过值，没有设置就用默认值
	        {
	            $r['attr_default_value'] = $attr_value_arr[$r['relate_id']];
	        }
            
	        if($r['attr_default_value'] && unserialize($r['attr_default_value']))
	        {
	            $r['attr_default_value'] = unserialize($r['attr_default_value']);
	        }
	        
	        $r['attr_type_uniqueid'] = $this->settings['attribute_type'][intval($r['attr_type_id'])]['uniqueid'];

	        if($r['attr_type_uniqueid'])
	        {
	            //按照打包配置文件的需要的数据结构输出
                switch ($r['attr_type_uniqueid'])
                {
	                //文本框
	                case 'textbox':break;
                    //文本域
        	        case 'textfield':break;
        	        //单选
        	        case 'single_choice':break;
        	        //勾选
        	        case 'check':$r['attr_default_value'] = (bool)$r['attr_default_value'];break;
        	        //取值范围
        	        case 'span':break;
        	        //图片单选
        	        case 'pic_radio':break;//图片单选只要输出值就可以了
        	        //图片上传+单选，他的值是选中了某个图片的id
        	        case 'pic_upload_radio':
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['selected']) && $r['attr_default_value']['selected'])
        	                        {
        	                            //取这张图片
        	                            $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['selected']));
        	                            if($_img_info)
        	                            {
        	                                $r['attr_default_value'] = array(
        	                                        'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
        	                                        'isRepeat' => FALSE,
        	                                        'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
        	                                        'img_id'   => $r['attr_default_value']['selected'],
        	                                );
        	                            }
        	                        }
        	                        break;
        	        //多选
        	        case 'multiple_choice':break;
        	        //拾色器
        	        case 'color_picker':
        	                        if($r['attr_default_value'])
        	                        {
        	                           $r['attr_default_value'] = Common::convertColor($r['attr_default_value']);
        	                        }
        	                        else
        	                        {
        	                            $r['attr_default_value'] = Common::convertColor();//用户没有设置，采用默认的值
        	                        }
        	                        break;
        	        //高级拾色器
        	        case 'advanced_color_picker':
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['color']) && $r['attr_default_value']['color'])
        	                        {
        	                           $r['attr_default_value'] = Common::convertColor($r['attr_default_value']['color'],$r['attr_default_value']['alpha']);
        	                        }
                                    else 
        	                        {
        	                            $r['attr_default_value'] = Common::convertColor();//用户没有设置，采用默认的值
        	                        }
        	                        break;
        	        //配色方案
        	        case 'color_schemes':
                                    if($r['attr_default_value'])
        	                        {
        	                           $r['attr_default_value'] = Common::convertColor($r['attr_default_value']);
        	                        }       
        	                        break;
        	        //高级配色方案
        	        case 'advanced_color_schemes':
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['main']) && $r['attr_default_value']['main'])
        	                        {
        	                            $r['attr_default_value'] = Common::convertColor($r['attr_default_value']['color']);
        	                        }
        	                        break;
        	        //高级背景设置
        	        case 'advanced_background_set':
        	                        //背景是图片的情况
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['img_id']) && $r['attr_default_value']['img_id'])
        	                        {
        	                            //取这张图片
        	                            $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['img_id']));
        	                            if($_img_info)
        	                            {
        	                                $r['attr_default_value'] = array(
        	                                        'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
        	                                        'isRepeat' => isset($r['attr_default_value']['is_tile'])?(bool)($r['attr_default_value']['is_tile']):FALSE,
        	                                        'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
        	                                        'img_id'   => $r['attr_default_value']['img_id'],
        	                                );
        	                            }
        	                        }
        	                        elseif (is_array($r['attr_default_value']) && isset($r['attr_default_value']['color']) && $r['attr_default_value']['color'])//背景是颜色的情况      
        	                        {
            	                        $r['attr_default_value'] = Common::convertColor($r['attr_default_value']['color'],(float)$r['attr_default_value']['alpha']);
        	                        }
        	                        else //临时解决一下
        	                        {
        	                            $r['attr_default_value'] = Common::convertColor($r['attr_default_value']);
        	                        }
        	                        break;
        	        //高级文字设置
        	        case 'advanced_character_set':
                                    //背景是图片的情况
        	                        if(is_array($r['attr_default_value']) && isset($r['attr_default_value']['img_id']) && $r['attr_default_value']['img_id'])
        	                        {
        	                            //取这张图片
        	                            $_img_info = $this->app_material->detail('app_material', array('id' => $r['attr_default_value']['img_id']));
        	                            if($_img_info)
        	                            {                            
        	                                //titleContent做特殊处理
        	                                if($r['uniqueid'] == 'titleContent')
        	                                {
        	                                    $r['attr_default_value'] = array(
        	                                             'text' => '',
                    		                             'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
                    		                             'drawableHeight' => 40,
                    		                             'drawableWHScale' => 4,
        	                                             'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
        	                                             'img_id'  => $r['attr_default_value']['img_id'],
        	                                    );
        	                                }
        	                                else 
        	                                {
        	                                    $r['attr_default_value'] = array(
        	                                        'drawable' => 'dd_' . Common::pickPicName($_img_info['filename']),
        	                                        'isRepeat' => FALSE,
        	                                        'url'	   => $_img_info['host'] . $_img_info['dir'] . $_img_info['filepath'] . $_img_info['filename'],
        	                                        'img_id'   => $r['attr_default_value']['img_id'],
        	                                    );
        	                                }
        	                            }
        	                        }
        	                        elseif (is_array($r['attr_default_value']) && isset($r['attr_default_value']['text']))//单纯文字的情况    
        	                        {
        	                            //titleContent做特殊处理
    	                                if($r['uniqueid'] == 'titleContent')
    	                                {
    	                                    $r['attr_default_value'] = array(
    	                                             'text' => $r['attr_default_value']['text'],
                		                             'drawable' => '',
                		                             'drawableHeight' => 40,
                		                             'drawableWHScale' => 4,
    	                                    );
    	                                }
    	                                else 
    	                                {
    	                                    $r['attr_default_value'] = $r['attr_default_value']['text'];
    	                                }
        	                        }
        	                        else
        	                        {
        	                            //做一个特殊处理，主要是解决普通用户前台的titleContent是文本样式与后台高级文字设置绑定
        	                            if($r['uniqueid'] == 'titleContent')
    	                                {
    	                                    $r['attr_default_value'] = array(
    	                                             'text' => $r['attr_default_value'],
                		                             'drawable' => '',
                		                             'drawableHeight' => 40,
                		                             'drawableWHScale' => 4,
    	                                    );
    	                                }
        	                        }
        	                        break;
                }
    	    }
    	    
    	    unset($r['default_value'],$r['style_value']);
    	    $attrArr[$r['group_id']][] = $r;
    	}
    	return $attrArr;
	}
	/******************************************************针对角标的listUI相关配置的操作**********************************************************/
	
}