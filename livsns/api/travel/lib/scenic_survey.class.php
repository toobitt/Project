<?php
//模板的数据库操作

class scenicSurvey extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if(urldecode($this->input['outlink']) && urldecode($this->input['outlink']) !='请填写超链接！')
		{
			$content = '';
		}
		else
		{
			$check_info = hg_check_content(html_entity_decode($this->input['content']));
			$content=addslashes($this->input['content']);
		}
		
		if(!(strpos(urldecode($this->input['indexpic']),'http://') === false))
		{
			$this->input['indexpic'] = $this->indexpic_img_local(urldecode($this->input['indexpic']));
		}
		
		$info = array(
			'title' => $this->input['title'],
			'page_title' => ($this->input['pagetitles']),
			'tcolor' => ($this->input['tcolor']),
			'isbold' => intval($this->input['isbold']),
			'isitalic' => intval($this->input['isitalic']),
			'istop' => $this->input['istop']==1 ? 1 : 0, 
			'iscomm' => $this->input['iscomm']==1 ? 0 : 1,
			'istpl' => $this->input['istpl']==1 ? 1 : 0,
			'subtitle' => ($this->input['subtitle']),
			'keywords' => ($this->input['keywords']),
			'brief' => ($this->input['brief']),
			'author' => ($this->input['author']),
			'source' => ($this->input['source']),
			'indexpic' => intval($this->input['indexpic']),
			'outlink' => ($this->input['outlink']),
			'sort_id' => intval($this->input['sort_id']),
			'column_id' => $this->input['column_id'],
			'user_id'   => intval($this->user['user_id']),
			'user_name' => ($this->user['user_name']),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'pub_time' =>strtotime(($this->input['pub_time'])),
			'ip' => hg_getip(),
			'weight' => intval($this->input['weight']),
			'water_id' => $this->input['water_config_id'],
			'water_name' => ($this->input['water_config_name']),
			'is_img' => $check_info['is_img'],
			'is_video' => $check_info['is_video'],
			'is_tuji'  => $check_info['is_tuji'],
			'is_vote' => $check_info['is_vote'],
			'appid'   => intval($this->user['appid']),
			'appname'  => trim(($this->user['appname'])),
		   );
		$info['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
		$info['column_id'] = serialize($info['column_id']);
		
		$sql = "INSERT INTO " . DB_PREFIX . "survey SET ";
		$sql_extra = $space = '';
		foreach($info as $k => $v)
		{
			if(!empty($v))
			{
				switch($k)
				{
					case 'keywords':
						$sql_extra .= $space . $k . "='" . str_replace(array("，",","," "),array(",",",",","),$v) ."'";
						break;
					default:
						$sql_extra .= $space . $k . "='" . $v ."'";
						break;
				}
				$space = ',';
			}
		} 
		$sql .= $sql_extra;
		$this->db->query($sql);
		$article_id = $this->db->insert_id();
		$this->input['id'] = $article_id;
		$sql = "UPDATE " . DB_PREFIX ."survey SET order_id = {$article_id} WHERE id = {$article_id}";
		$this->db->query($sql);
		$arr = array();

	     //更新素材表
	    $material_id=$this->input['material_id'];
	    if(!empty($material_id))
		{
			$material_history = array();
			if(trim(urldecode($this->input['material_history'])))
			{
				$material_history = explode(',',urldecode($this->input['material_history']));
			}
			$del_material = array_diff($material_history,$material_id);
			$mid_str = implode(',',$material_id);
			if(!empty($del_material))
			{
				$del_material = implode(',',$del_material);
				$this->mater->delMaterialById($del_material,2); //根据素材ID来删除素材信息

				$sql="DELETE FROM " . DB_PREFIX . "material WHERE material_id IN(" . $del_material . ")";
				$this->db->query($sql);
			}

			$this->mater->updateMaterial($mid_str,$article_id,$info['sort_id']);  //更新cid,catid
			$sql = "UPDATE " . DB_PREFIX . "material SET cid=" . $article_id . " WHERE material_id IN (" . $mid_str . ")";
			$this->db->query($sql);
		}

 		//查询文章所属的所有父级分类
		$sort = $this->getSortByFather($info['sort_id']);
		
		//入水印关系表
//		if(!empty($this->input['water_config_id']) && intval($this->input['water_config_id'])!=-1)
//		{
			$this->mater->insertMaterialWater($sort,$article_id,intval($this->input['water_config_id']));
//		}
		
		
		//内容表
		$sql = "INSERT INTO " . DB_PREFIX . "survey_contentbody(articleid,content) VALUES(" . $article_id . ",'" . $content . "')";
		$this->db->query($sql);
		
		
		//放入发布队列
		$sql = "SELECT * FROM " . DB_PREFIX ."survey WHERE id = " . $article_id;
		$r = $this->db->query_first($sql);
		if(intval($r['state']) == 1 && !empty($r['column_id']))
		{
			$op = 'insert';
			$this->publish_insert_query($article_id,$op);
		}
		
		$info['id'] = $article_id;
		return $info;
	}
	
	//更新模板相关信息
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."scenic_spots SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$info['id'];
		$this->db->query($sql);		
	}
	
	//删除主题
	public function delete($id)
	{			
		$sql = "DELETE FROM " . DB_PREFIX . "scenic_spots WHERE id =".$id;
		$this->db->query($sql);
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	//根据条件查询专题
	public function show($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."scenic_spots 
				WHERE 1".$condition.' ORDER BY id DESC '.$limit;
		$q = $this->db->query($sql);
		$sql_ = "select name,id from " . DB_PREFIX . "scenic_sort where 1";
		$sorts = $this->db->fetch_all($sql_);
		
		while($row = $this->db->fetch_array($q))
		{				
			foreach ($sorts as $k=>$v){			
				if( $v['id']== $row['sort_id']){
					$row['sort_name'] = $v['name'];
				}
				$row['cre_time'] = date("Y-m-d H:i",$row['create_time']);
			}	
			$ret[] = $row;
		}
		//file_put_contents('00',var_export($ret,1));
		return $ret;
	}
	
	//新增介绍
	public function insert_content($scenic_spots_id,$introduce)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."scenic_spots_introduce SET introduce = ". "'".$introduce."'" .'AND scenic_spots_id = '.$scenic_spots_id;
		$this->db->query($sql);
		return $this->db->insert_id();
		
	}
	
	//更新介绍
	public function update_content($id,$introduce)
	{	
		//插入数据操作
		$sql = "UPDATE " . DB_PREFIX ."scenic_spots_introduce SET introduce = ". "'".$introduce."'"." WHERE id =".$id;
		$this->db->query($sql);
		return $id;
	}
}


?>