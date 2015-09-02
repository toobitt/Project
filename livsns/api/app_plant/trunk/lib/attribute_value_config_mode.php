<?php
require_once(CUR_CONF_PATH . 'lib/appMaterial.class.php');
class attribute_value_config_mode extends InitFrm
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
		$sql = "SELECT uc.*,a.attr_type_id,a.uniqueid,ar.name AS attr_name,ui.name AS ui_name,ag.name AS group_name FROM " . DB_PREFIX . "ui_attribute_value_config uc LEFT JOIN " 
		                        . DB_PREFIX . "attribute_relate ar ON ar.id = uc.relate_id LEFT JOIN "
		                        . DB_PREFIX . "attribute a ON a.id = ar.attr_id LEFT JOIN " 
		                        . DB_PREFIX . "user_interface ui ON ui.id = ar.ui_id LEFT JOIN " 
		                        . DB_PREFIX . "attribute_group ag ON ag.id = ar.group_id WHERE 1 " . $condition . $orderby . $limit;
		                        
		
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['attr_type_name'] = $this->settings['attribute_type'][$r['attr_type_id']]['name'];
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "ui_attribute_value_config SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "ui_attribute_value_config WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "ui_attribute_value_config SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "ui_attribute_value_config  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		if($info)
		{
		    $info['style_value'] = @unserialize($info['style_value']);
		    if($info['default_value'] && @unserialize($info['default_value']))
		    {
                $info['default_value'] = @unserialize($info['default_value']);  
		    }
		    
		    $attr_type_mark = $this->settings['attribute_type'][$info['attr_type_id']]['uniqueid'];
		    if($info['default_value'])
		    {
		        if($attr_type_mark == 'pic_upload_radio')//图片上传+单选
		        {
		            //获取图片信息
    	             if(isset($info['default_value']['img_ids']) && $info['default_value']['img_ids'])
    	             {
    	                 $_img_info = $this->app_material->getMaterial(" AND id IN (" .$info['default_value']['img_ids']. ") ");
    	                 $info['default_value'] = array(
                             'img'      => $_img_info,
                             'selected' => isset($info['default_value']['selected'])?$info['default_value']['selected']:0,
                         );
    	             }
		        }
		        elseif ($attr_type_mark == 'advanced_background_set')//高级背景设置
		        {
		            if(isset($info['default_value']['img_id']) && $info['default_value']['img_id'])
    	            {
    	                $_img_info = $this->app_material->detail('app_material', array('id' => $info['default_value']['img_id']));
    	                $info['default_value'] = array(
                             'img'      => $_img_info,
                             'is_tile'  => isset($info['default_value']['is_tile'])?intval($info['default_value']['is_tile']):0,
                        );
    	            }
		        }
		        elseif ($attr_type_mark == 'advanced_character_set')//高级文字设置
		        {
		            if(isset($info['default_value']['img_id']) && $info['default_value']['img_id'])
    	            {
    	                $_img_info = $this->app_material->detail('app_material', array('id' => $info['default_value']['img_id']));
    	                $info['default_value'] = array(
                             'img'      => $_img_info,
                        );
    	            }
		        }
		    }
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ui_attribute_value_config WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "ui_attribute_value_config WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "ui_attribute_value_config WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "ui_attribute_value_config WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "ui_attribute_value_config SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
}