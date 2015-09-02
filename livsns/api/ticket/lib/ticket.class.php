<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
class ticket extends InitFrm
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
	public function show($condition,$orderby,$offset,$count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT s.id AS sid,m.index_url,s.*,so.name,v.venue_name,v.venue_address FROM '.DB_PREFIX.'show s
				LEFT JOIN '.DB_PREFIX.'material m ON s.index_id = m.id
				LEFT JOIN '.DB_PREFIX.'sort so ON s.sort_id = so.id
				LEFT JOIN '.DB_PREFIX.'venue v ON s.venue_id = v.id
				WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		$prices = array();
		while ($row = $this->db->fetch_array($query))
		{
			//兼容老版本场馆和地址
			if(!$row['address'] && $row['venue_address'])
			{
				$row['address'] = $row['venue_address'];
			}
			
			if(!$row['venue'] && $row['venue_name'])
			{
				$row['venue'] = $row['venue_name'];
			}
			
			//输出发布栏目
         	if ($row['column_id'])
         	{
         		$column_id = unserialize($row['column_id']);
         		if ($column_id)
         		{
         			$row['column_id'] = $column_id;
         		}else {
         			$row['column_id'] = array();
         		}
         	}
         	
			$index_url = unserialize($row['index_url']);
			$row['index_url'] = $index_url ? $index_url : '';
			if ($row['price'])
			{
				//$prices[$row['sid']] = $row['price']; 
			}
			$row['update_time'] = date('Y-m-d H:i',$row['create_time']);
			$row['id'] = $row['sid'];
			switch ($row['sale_state'])
			{
				case 0:$row['sale_state_name'] = '新建';break;
				case 1:$row['sale_state_name'] = '设计/预售';break;
				case 2:$row['sale_state_name'] = '出售';break;
				case 3:$row['sale_state_name'] = '结束';break;
				case 10:$row['sale_state_name'] = '删除';break;
			}
			switch($row['status'])
			{
				case 0:$row['status_name'] = '未审核';break;
				case 1:$row['status_name'] = '已审核';break;
				case 2:$row['status_name'] = '已打回';break;
				default:$row['status_name'] = '未审核';break;
			}
			unset($row['sid']);
			$k[$row['id']] = $row;
			$index_url = '';
		}		
		/*if (!empty($prices))
		{
			$un_price = implode(',', array_unique(explode(',', implode(',', $prices))));			
			$sql = 'SELECT * FROM '.DB_PREFIX.'price WHERE id IN ('.$un_price.')';
			$query = $this->db->query($sql);
			$price = array();
			while ($row = $this->db->fetch_array($query))
			{
				$price[$row['id']] = $row['price'];
			}		
			foreach ($prices as $key=>$val)
			{
				if ($val)
				{		
					$k_price = explode(',', $val);
					$temp='';
					foreach ($k_price as $vv)
					{			
						$temp[]= $price[$vv];
					}
					$k[$key]['price'] = $temp;
				}
			}
		}*/
			
		return $k;	
	}
	
	
	//获取子集分类
	public function child_sort($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT childs FROM '.DB_PREFIX.'sort WHERE id IN (' . $id . ')';
		$q = $this->db->query($sql);
		
		$str_tmp = '';
		while ($r = $this->db->fetch_array($q))
		{
			$str_tmp .= $r['childs'] . ',';
		}		
		if($str_tmp)
		{
			$ids = rtrim($str_tmp,',');
			return $ids;
		}
	}
	
	
	public function detail($id,$flag=0)
	{
		$sql = 'SELECT s.id AS sid,c.*,so.name,m.index_url,s.*,v.venue_name,v.venue_address FROM '.DB_PREFIX.'show s
				LEFT JOIN '.DB_PREFIX.'content c ON s.id = c.id
				LEFT JOIN '.DB_PREFIX.'sort so ON s.sort_id = so.id
				LEFT JOIN '.DB_PREFIX.'material m ON s.index_id = m.id
				LEFT JOIN '.DB_PREFIX.'venue v ON s.venue_id = v.id
				WHERE s.id = '.$id;
		$k = $this->db->query_first($sql);
		
		//兼容老版本场馆和地址
		if(!$k['address'] && $k['venue_address'])
		{
			$k['address'] = $k['venue_address'];
		}
		
		if(!$k['venue'] && $k['venue_name'])
		{
			$k['venue'] = $k['venue_name'];
		}
			
		//座位图
		if($k['seat_map'])
		{
			$k['seat_map']	= unserialize($k['seat_map']);
		}
		$k['index_url'] = unserialize($k['index_url']) ? unserialize($k['index_url']) : '';
		$k['brief'] = strip_tags(htmlspecialchars_decode($k['brief']));
		$k['brief'] = str_replace('&nbsp;', ' ', $k['brief']);
		$k['content'] = htmlspecialchars_decode($k['content']);
		if ($flag)
		{
			$ret = $this->content_manage($k['content']);
			$tmp = strip_tags($ret['content'], '<p><br><a>');
			$tmp = preg_replace('#<p[^>]*>#i','<p>',$tmp);
			$k['content'] = $tmp;
			$k['content_img'] = $ret['content_img'];
		}
		$state_name = '';	
		switch ($k['sale_state'])
		{
			case 0:$k['sale_state_name'] = '新建';break;
			case 1:$k['sale_state_name'] = '设计中';break;
			case 2:$k['sale_state_name'] = '售票中';break;
			case 3:$k['sale_state_name'] = '结束';break;
			case 10:$k['sale_state_name'] = '删除';break;
		}
		$k['start_time'] = date('Y-m-d H:i:s',$k['start_time']);
		$k['end_time'] = date('Y-m-d H:i:s',$k['end_time']);	
		$k['id'] = $k['sid'];
		if ($k['tel'])
		{
			$k['tel'] = unserialize($k['tel']);
		}
		$k['sell_tel'] = '';
		if ($k['tel'] && is_array($k['tel']))
		{
			foreach ($k['tel'] as $key=>$val)
			{
				if (strtotime($val['start_time'])<TIMENOW && strtotime($val['end_time'])>TIMENOW)
				{
					$k['sell_tel'][] = $k['tel'][$key];
				}
			}	
		}	
		unset($k['sid']);
		return $k;
	}
	private function content_manage($content)
	{
		
		$res = array('content'=>$content,'content_img'=>array());
		preg_match_all('/<img.*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/is', $content, $match_mat);
		//处理图片
		$pics = array();
		//hg_pre($match_mat);exit();
		if($match_mat[1])
		{
			$i = 0;
			foreach($match_mat[1] as $k=>$v)
			{
				$ismatch = preg_match('/^(.*?)(material\/.*?img\/)([0-9]*[?|-][0-9]*\/)?(\d{0,4}\/\d{0,2}\/)(.*?)$/is', $v, $match);
				//hg_pre($match);exit();	
				if ($ismatch)
				{
					$pics[$i]['host'] = $match[1];
					$pics[$i]['dir'] = $match[2];
					$pics[$i]['filepath'] = $match[4];
					$pics[$i]['filename'] = $match[5];
					$i++;
				}
			}
		}
		$res['content'] = str_replace($match_mat[0], '', $content);
		$res['content_img'] = $pics;
		
		return $res;
	}
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'show s WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	public function delete($ids)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'show WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'material WHERE show_id IN ('.$ids.')';
		$this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'xcpw_show WHERE show_id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}
	/**
	 * 输出所有分类
	 * 
	 */
	public function all_sort()
	{
		$k = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'sort ';
		$query =$this->db->query($sql);
		while ($row = $this->db->fetch_array($query)) {
			$k[$row['id']] = $row['name'];
		}
		return $k;
	}
	public function update_show($data,$id)
	{
		if (!$id || !is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'show SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id; 
		$this->db->query($sql);
		return true;
	}
	//更新内容表
	public function update_content($content,$id)
	{
		if (!$content || !$id)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'content SET content = "'.$content.'" WHERE id = '.$id;
		$this->db->query($sql);
		return true;
	}
	public function update_indexPic($file,$show_id,$mid)
	{
		
		$res = $this->uploadToPicServer($file, $show_id);
		$return  = $mid;
		if ($res)
		{
			$url = array(
				'host'=>$res['host'],
				'dir'=>$res['dir'],
				'filepath'=>$res['filepath'],
				'filename'=>$res['filename'],
			);
			$data = array(
				'show_id'=>$show_id,
				'index_url'=>addslashes(serialize($url)),
				'type'=>$res['type'],
				'original_id'=>$res['id'],
				'create_time'=>TIMENOW,
				'user_id'=>$this->user['user_id'],
				'user_name'=>$this->user['user_name'],	
			);
			if ($mid)
			{
				$sql = 'UPDATE '.DB_PREFIX.'material SET ';
				foreach ($data as $key=>$val)
				{
					$sql .= $key.'="'.$val.'",';
				}
				$sql = rtrim($sql,',');
				$sql .= ' WHERE id = '.$mid; 
				$this->db->query($sql);
			}else 
			{
				$sql = 'INSERT INTO '.DB_PREFIX.'material SET ';
				foreach ($data as $key=>$val)
				{
					$sql .= $key.'="'.$val.'",';
				}
				$sql = rtrim($sql,',');
				$ret = $this->db->query($sql);
				$insert_id = $this->db->insert_id();
				if ($insert_id)
				{
					$sql = 'UPDATE '.DB_PREFIX.'show SET index_id = '.$insert_id. ' WHERE id = '.$show_id;
					$this->db->query($sql);
					$return = $insert_id;
				}
			}
			
			return $return;
		}else {
			return false;
		}
	}
	//图片插入图片服务器
	public function uploadToPicServer($file,$id='')
	{
		$material = $this->material->addMaterial($file,$id); //插入图片服务器
		return $material;
	} 
	public function add_show($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'show SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'=\''.$val.'\',';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$update_sql = 'UPDATE '.DB_PREFIX.'show set order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($update_sql);
		return $id;
	}
	public function add_content($data)
	{
		$sql  = 'REPLACE INTO '.DB_PREFIX.'content SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	public function add_indexPic($file,$id)
	{
		$res = $this->uploadToPicServer($file, $id);
		if ($res)
		{
			$url = array(
				'host'=>$res['host'],
				'dir'=>$res['dir'],
				'filepath'=>$res['filepath'],
				'filename'=>$res['filename'],
			);
			$data = array(
				'show_id'=>$id,
				'index_url'=>addslashes(serialize($url)),
				'type'=>$res['type'],
				'original_id'=>$res['id'],
				'create_time'=>TIMENOW,
				'user_id'=>$this->user['user_id'],
				'user_name'=>$this->user['user_name'],	
			);
			$sql = 'INSERT INTO '.DB_PREFIX.'material SET ';
			foreach ($data as $key=>$val)
			{
				$sql .= $key.'="'.$val.'",';
			}
			$sql = rtrim($sql,',');
			$this->db->query($sql);
			$mid = $this->db->insert_id();
			$this->indexPic($id,$mid);
			return $mid;
			
		}else {
			return false;
		}
	}
	//更新索引图
	private function indexPic($show_id,$mid)
	{
		$sql = 'UPDATE '.DB_PREFIX.'show SET index_id ='.$mid.' WHERE id='.$show_id;
		$this->db->query($sql);
		return true;
	}
	public function audit($ids,$audit)
	{
		if(!$ids)
		{
			return false;
		}
		$arr_id = explode(',',$ids);
		if($audit == 1)
		{
			$sql = "UPDATE " . DB_PREFIX ."show SET status = 1 WHERE id IN (".$ids.")";
			$this->db->query($sql);
			return array('id' => $arr_id,'status' => 1);
		}
		else if($audit === 0) 
 		{
			$sql = "UPDATE " . DB_PREFIX ."show SET status = 2 WHERE id IN (".$ids.")";
			$this->db->query($sql);
			return array('id' => $arr_id,'status' => 2);
		}
	}
	public function show_opration($id)
	{
		$res = $this->detail($id);
		return $res;
	}
	public function sale_state($ids,$state)
	{
		if(!$ids)
		{
			return false;
		}
		$arr_id = explode(',',$ids);
		if($state == 1)
		{
			$sql = "UPDATE " . DB_PREFIX ."show SET sale_state = 1 WHERE id IN (".$ids.")";
			$this->db->query($sql);
			return array('id' => $arr_id,'state' => 1);
		}
		else if($state == 2) 
 		{
			$sql = "UPDATE " . DB_PREFIX ."show SET sale_state = 2 WHERE id IN (".$ids.")";
			$this->db->query($sql);
			return array('id' => $arr_id,'state' => 2);
		}
		else if($state == 3) 
 		{
			$sql = "UPDATE " . DB_PREFIX ."show SET sale_state = 3 WHERE id IN (".$ids.")";
			$this->db->query($sql);
			return array('id' => $arr_id,'state' => 3);
		}
	}
	
	public function get_content($show_id,$flag='')
	{
		if(!$show_id)
		{
			return FALSE;
		}
		$sql = "SELECT content,content_link FROM " . DB_PREFIX . "content WHERE id = " . $show_id;
		$res = $this->db->query_first($sql);
		
		if ($flag)
		{
			$ret = $this->content_manage($res['content']);
			$tmp = strip_tags($ret['content'], '<p><br><a>');
			$tmp = preg_replace('#<p[^>]*>#i','<p>',$tmp);
			$res['content'] = $tmp;
			$res['content_img'] = $ret['content_img'];
			$res['content_link'] = $ret['content_link'];
		}
		return $res;
	}
}