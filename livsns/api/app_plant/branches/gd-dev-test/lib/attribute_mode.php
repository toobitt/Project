<?php
require_once(CUR_CONF_PATH . 'lib/appMaterial.class.php');
class attribute_mode extends InitFrm
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
		$sql = "SELECT * FROM "  . DB_PREFIX . "attribute WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['attribute_name'] = $this->settings['attribute_type'][$r['attr_type_id']]['name'];
			$r['is_has_default_value'] = $r['default_value']?1:0;
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "attribute SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."attribute SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "attribute WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "attribute SET ";
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
			return false;
		}
		
		if($id)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "attribute  WHERE id = '" .$id. "'";
		}
		else if($cond)
		{
		    $sql = "SELECT * FROM " . DB_PREFIX . "attribute  WHERE 1 " . $cond;
		}
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
		        //图片上传+单选
		        if($attr_type_mark == 'pic_upload_radio')
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
		    return $info;
		}
		else 
		{
		    return FALSE;
		}
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "attribute WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "attribute WHERE id IN (" . $id . ")";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "attribute WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "attribute SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//创建属性关联
	public function createAttrRelate($data = array())
	{
	    if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "attribute_relate SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid; 
	}
}