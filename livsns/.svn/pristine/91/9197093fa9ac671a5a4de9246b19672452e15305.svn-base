<?php
/**
 * 组件与角标关系
 */
require_once(CUR_CONF_PATH . 'lib/appMaterial.class.php');
class superscript_comp extends InitFrm
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
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "superscript  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
		    if($r['img_type'] == 2)
		    {
    		    if($r['img_info'] && unserialize($r['img_info']))
    			{
    				$r['img_info'] = unserialize($r['img_info']);
    			}
    			else
    			{
    				$r['img_info'] = array();
    			}
		    }
		    elseif($r['img_type'] == 1)
		    {
                $r['img_info'] = $this->settings['base_url'] . $this->settings['superscript']['save_path'] . $r['img_info'];
		    }
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "superscript SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."superscript SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "superscript WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "superscript SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '',$cond = '')
	{
		if(!$id && !$cond)
		{
			return FALSE;
		}
		
		if($id)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "superscript  WHERE id = '" .$id. "'";
		}
		else
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "superscript  WHERE 1 " . $cond;
		}
		
		$info = $this->db->query_first($sql);
		if($info)
		{
		    if($info['img_type'] == 2)
		    {
    		    if($info['img_info'] && unserialize($info['img_info']))
    			{
    				$info['img_info'] = unserialize($info['img_info']);
    			}
    			else
    			{
    				$info['img_info'] = array();
    			}
		    }
		    return $info;
		}
		else 
		{
		    return FALSE;
		}
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "superscript WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "superscript WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "superscript WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "superscript WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "superscript SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//创建角标的条件
	public function createSuperScriptCond($data = array())
	{
	    if(!$data)
		{
			return FALSE;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "superscript_cond SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."superscript_cond SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
    //获取角标的条件
	public function getSuperscriptByCond($cond = '',$group_key = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "superscript_cond WHERE 1 " . $cond;
	    $ret = $this->db->fetch_all($sql,$group_key);
	    return $ret;
	}
	
	//根据条件删除组件对应的条件
	public function deleteSuperscriptCond($cond = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    $sql = "DELETE FROM " .DB_PREFIX. "superscript_cond WHERE 1 " . $cond;
	    $this->db->query($sql);
	    return TRUE;
	}
	
    //获取角标的前台属性
	public function getFrontAttrByCond($cond = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "superscript_ui_attr WHERE 1 " . $cond;
	    $q = $this->db->query($sql);
	    $ret = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $r['attr_type_name'] = $this->settings['attribute_type'][$r['attr_type_id']]['uniqueid']; 
	        $ret[] = $r;
	    }
	    return $ret;
	}
	
    //设置针对角标的前台属性值
	public function setFrontCornerAttrValue($data = array())
	{
	    if(!$data)
		{
			return FALSE;
		}
		
		//判断该属性的值是否存在
		$sql = "SELECT * FROM " .DB_PREFIX. "superscript_ui_attr_value WHERE superscript_id = '" .$data['superscript_id']. "' AND ui_attr_id = '" .$data['ui_attr_id']. "' ";
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
	
	//获取角标前台的属性
	public function getFrontCornerAttrData($id = '',$role_id = 1)
	{
        if(!$id)
	    {
	        return FALSE;
	    }
	    
	    /***********************************************获取用户自己设置的属性值***************************************/
	    $sql = "SELECT * FROM " .DB_PREFIX. "superscript_ui_attr_value WHERE superscript_id = '" .$id. "' ";
	    $attr_value_arr = array();
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $attr_value_arr[$r['ui_attr_id']] = $r['attr_value'];          
        }
	    /***********************************************获取用户自己设置的属性值***************************************/
	    
	    //增加角色的筛选
	    $_cond = '';
	    if($role_id && in_array($role_id, array(1,2)))
	    {
	        $_cond = " AND role_type_id = '" . $role_id . "' ";
	    }
	    else 
	    {
	        $_cond = " AND role_type_id = -1 ";//如果传入的角色id不合法就默认就取出适合所有
	    }

	    $order_by = " ORDER BY order_id DESC ";
	    
	    //获取属性
	    $sql = "SELECT * FROM " . DB_PREFIX . "superscript_ui_attr WHERE 1 " . $_cond . $order_by;
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
	
	//获取组件与角标的关系数据（也就是某个组件使用了哪些角标）
	public function getModCornerByCond($cond = '',$fields = '*',$group_id = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT " . $fields . " FROM " .DB_PREFIX. "components_new_corner WHERE 1 " . $cond;
	    $ret = $this->db->fetch_all($sql,$group_id);
	    if($ret)
	    {
	        return $ret;
	    }
	    else 
	    {
	        return FALSE;
	    }
	}
	
	//创建一条模块与角标的关系数据
	public function createModCorner($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "components_new_corner SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."components_new_corner SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	//按照条件删除模块角标关系数据
	public function deleteCompCornerByCond($cond = '')
	{
        if(!$cond)
        {
            return FALSE;
        }
	    
        $sql = "DELETE FROM " .DB_PREFIX. "components_new_corner WHERE 1 " . $cond;
        $this->db->query($sql);
        return TRUE;
	}
	
	//按照条件删除前台角标属性设置数据
    public function deleteFrontCornerAttrByCond($cond = '')
	{
        if(!$cond)
        {
            return FALSE;
        }
	    
        $sql = "DELETE FROM " .DB_PREFIX. "superscript_ui_attr_value WHERE 1 " . $cond;
        $this->db->query($sql);
        return TRUE;
	}
	
    //按照条件删除后台角标属性设置数据
    public function deleteCornerAttrByCond($cond = '')
	{
        if(!$cond)
        {
            return FALSE;
        }
	    
        $sql = "DELETE FROM " .DB_PREFIX. "superscript_attr_value WHERE 1 " . $cond;
        $this->db->query($sql);
        return TRUE;
	}
	
	//获取某个zujian使用了哪些角标，并且连带着返回角标的详细信息
	public function getUseCornerInfoByCompId($comp_id = '')
	{
	    if(!$comp_id)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT s.*,mc.id AS mod_corner_id FROM " .DB_PREFIX. "components_new_corner mc LEFT JOIN " . DB_PREFIX . "superscript s ON mc.superscript_id = s.id WHERE mc.comp_id = '" .$comp_id. "' ";
	    $q = $this->db->query($sql);
	    $arr = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        if($r['img_type'] == 2)
	        {
    	        if($r['img_info'] && unserialize($r['img_info']))
    	        {
    	            $r['img_info'] = unserialize($r['img_info']);
    	        }
    	        else
    	        {
    	            $r['img_info'] = array();
    	        }
	        }
	        $arr[] = $r;
	    }
	    return $arr;
	}
	
	//保存用户自己上传
	public function createUserCornerIcon($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "superscript_icon SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."superscript_icon SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	//根据条件获取用户的角标
	public function getUserCornerIcon($cond = '',$fields = '*',$group_id = '')
	{
	    if(!$cond)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT " . $fields . " FROM " .DB_PREFIX. "superscript_icon WHERE 1 " . $cond;
	    $ret = $this->db->fetch_all($sql,$group_id);
	    if($ret)
	    {
	        return $ret;
	    }
	    else 
	    {
	        return FALSE;
	    }
	}
	
    //按照条件删除后台角标属性设置数据
    public function deleteUserCornerIconByCond($cond = '')
	{
        if(!$cond)
        {
            return FALSE;
        }
	    
        $sql = "DELETE FROM " .DB_PREFIX. "superscript_icon WHERE 1 " . $cond;
        $this->db->query($sql);
        return TRUE;
	}
	
	//获取角标并且附带角标的条件
	public function getSuperscriptWithCondById($superscript_id = '',$fields = '*')
	{
	    if(!$superscript_id)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT " .$fields. " FROM " .DB_PREFIX. "superscript WHERE id = '" . $superscript_id . "' ";
	    $ret = $this->db->query_first($sql);
	    if(!$ret)
	    {
	        return FALSE;
	    }
	    
	    $sql = "SELECT * FROM " .DB_PREFIX. "superscript_cond WHERE superscript_id = '" .$superscript_id. "' ";
        $q   = $this->db->query($sql);
        $corner_cond = array();
        while ($r = $this->db->fetch_array($q))
        {
            $corner_cond[] = $r;
        }
        $ret['corner_cond'] = $corner_cond;
        return $ret;
	}
	
	/**
	 * 获取角标使用信息
	 */
	public function cornerIsUse($superscript_id = 0 , $user_id = 0)
	{
		$sql = "select * from " . DB_PREFIX . "module_corner where user_id =".$user_id." and superscript_id =".$superscript_id;
		$q   = $this->db->query($sql);
        $ret = array();
        while ($r = $this->db->fetch_array($q))
        {
            $ret[] = $r;
        }
        return $ret;
	}
	
}