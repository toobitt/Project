<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
class period_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT p.*,m.host,m.dir,m.filepath,m.filename FROM " . DB_PREFIX . "period p 
				LEFT JOIN " . DB_PREFIX . "material m  
					ON p.indexpic_id = m.id WHERE 1" . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['period_date'] = date('Y-m-d',$r['period_date']);
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['index_pic'] = $r['host'].$r['dir'].$r['filepath'].$r['filename'];
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "period SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		
		$this->db->query($sql);
		
		$vid = $this->db->insert_id();
		
		$data['id'] = $vid;
		
		$sql = " UPDATE ".DB_PREFIX."period SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $data;
	}
	
	public function create_page($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "page SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		
		$this->db->query($sql);
		
		$id = $this->db->insert_id();
		
		return $id;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "period WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "period SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		$row = $this->db->affected_rows();
		return $row;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT id,epaper_id,period_num,period_date FROM " . DB_PREFIX . "period WHERE id = ".$id;
		$res = $this->db->query_first($sql);
		$res['period_date'] = date('Y-m-d',$res['period_date']);
		
		/*$sql = "SELECT stack_id FROM ".DB_PREFIX."page WHERE period_id = ".$id;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$stack_id[$r['stack_id']] = 1;
		}
		$stack_id = array_keys($stack_id);*/
		
		if($res)
		{
			//$stack_ids = implode(',', $stack_id);
			//$sql = "SELECT id,zm,name FROM ".DB_PREFIX."stack WHERE id IN (".$stack_ids.")";
			$sql = "SELECT id,zm,name FROM ".DB_PREFIX."stack WHERE period_id = ".$id." ORDER BY order_id ASC";
			
			$q = $this->db->query($sql);
			
			while ($r = $this->db->fetch_array($q))
			{
				$stack[$r['id']] = array(
					'name'		=> $r['name'],
					'zm' 		=> $r['zm'],
				);
			}
			$res['stack'] = $stack;
		}
		
		//查询电子报名称
		if($res['epaper_id'])
		{
			$sql = "SELECT name FROM " . DB_PREFIX . "epaper WHERE id = " . $res['epaper_id'];
			
			$epaper_name = $this->db->query_first($sql);
			
			$res['epaper_name'] = $epaper_name['name'];
		}
		
		return $res;
	}
	
	//ajax请求期下面版页
	public function get_page_ajax($period_id,$stack_id='')
	{
		$sql = "SELECT p.id as page_id,p.stack_id,p.title,p.page,p.pdf_id,p.order_id,m.host,m.dir,m.filepath,m.filename,m.type,m.id as jpg_id FROM " . DB_PREFIX . "page p 
				LEFT JOIN ".DB_PREFIX."material m
					ON p.jpg_id = m.id
				WHERE p.period_id = ".$period_id;
		if($stack_id)
		{
			$sql .= ' AND p.stack_id = '.$stack_id;
		}
		$sql .= ' ORDER BY p.order_id ASC';
		$q = $this->db->query($sql);
		$info = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['page_num'] = $r['page'];
			$info[] = $r;
		}
		return $info;
	}
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "period AS p WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "period WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "period WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '',$audit = '')
	{
		if(!$id)
		{
			return false;
		}
		
		if($audit == 0)
		{
			$status = 2;
		}
		elseif($audit == 1)
		{
			$status = 1;
		}
		$sql = " UPDATE " .DB_PREFIX. "period SET status = " .$status. " WHERE id in (" . $id . ")";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//获取上传图片的类型
	public function getPhotoConfig($type='')
	{
		$ret = $this->material->get_allow_type();
		if (!$ret) {
			return false;
		}
		$photoConfig = array();
		if(!$type)
		{
			if (is_array($ret['img']) && !empty($ret['img']))
			{
				$img_arr = array_keys($ret['img']);
				foreach ($img_arr as $type)
				{
					$photoConfig['type'][] =  'image/'.$type;
				}
				$photoConfig['hint'] = implode(',', $img_arr);
			}
		}
		else if($type == 'doc')
		{
			if (is_array($ret['doc']) && !empty($ret['doc']))
			{
				$pdf_arr = array_keys($ret['doc']);
				foreach ($pdf_arr as $type)
				{
					$photoConfig['type'][] =  'application/'.$type;
				}
				$photoConfig['hint'] = implode(',', $pdf_arr);
			}
		}
		return $photoConfig;	
	}
	
	//上传图片服务器
	public function uploadToPicServer($file,$page_id='')
	{
		$material = $this->material->addMaterial($file,$page_id); //插入图片服务器
		return $material;
	}
	
	//插入素材表
	public function insert_material($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'material SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	//插入素材表
	public function update_material($id,$data = array())
	{
		if (!is_array($data) || !$id)
		{
			return false;
		}
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "material SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		$res = $this->db->affected_rows();
		return $res;
	}
	
	public function getMaterialById($id)
	{	
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE id=" . $id . " AND isdel=1"; //1表示没删除
		$ret = $this->db->query_first($sql);
		$info = array();
		
		if(empty($ret))
		{
			return false;
		}
		switch($ret['mark'])
		{
			case 'img':
				$ret['pic'] = unserialize($ret['pic']);
				$info[$ret['material_id']] = $ret;
				$info[$ret['material_id']]['url'] = hg_fetchimgurl($ret['pic'],100,75);
				break;
			case 'doc':
				$info[$ret['material_id']] = $ret;
				break;
			default:
				break;
		}
		
		return $info;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * 更新期的叠，版数，索引图id
	 * @param unknown_type $id 期id
	 * @param unknown_type $img_ids 被删除的页下的图片id
	 */
	public function update_period($id,$img_ids=array())
	{
		if(!$id)
		{
			return false;
		}
		
		//判断索引是否被删掉
		$sql = "SELECT indexpic_id FROM ".DB_PREFIX."period WHERE id = ".$id;
		$res = $this->db->query_first($sql);
		$indexpic_id = $res['indexpic_id'];
		
		//如果当期索引图在被删的版页上，重新检索当前最靠前版页中jpg_id
		if($id && $img_ids && in_array($indexpic_id, $img_ids))
		{
			//查询当前最靠前的版页
			$sql = "SELECT jpg_id FROM ".DB_PREFIX."page WHERE stack_id = 1 AND period_id = ".$id." ORDER BY order_id ASC LIMIT 0,1";
			$res = $this->db->query_first($sql);
			if($res['jpg_id'])
			{
				$new_indexpic_id = $res['jpg_id'];
			}
		}
		
		//查询期下有多少叠和版
		$sql = "SELECT id,stack_id FROM ".DB_PREFIX."page WHERE period_id = ".$id;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$page_ids[] = $r['id'];
			$stack_arr[$r['stack_id']] = 1;
		}
		
		//统计叠数
		$stack_num = count($stack_arr);
		
		//统计版数
		$page_count = count($page_ids);
		
		//更新期下叠数和页数
		if($new_indexpic_id)
		{
			$sql = "UPDATE ".DB_PREFIX."period SET stack_num = ".$stack_num.",page_num = ".$page_count.",indexpic_id = ".$new_indexpic_id." WHERE id = ".$id;
		}
		else
		{
			$sql = "UPDATE ".DB_PREFIX."period SET stack_num = ".$stack_num.",page_num = ".$page_count." WHERE id = ".$id;
		}
		$this->db->query($sql);
	}
	
}
?>