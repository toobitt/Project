<?php
//WEB视频的数据库操作
class webvod extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//新增
	public function create($info)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."webvod SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		$_id = $this->db->insert_id();
	}
	
	//上传缩略图
	public function upload_pic($info)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."webvodpic SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return  $this->db->insert_id();
	}
	
	//更新web视频相关信息
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."webvod SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE program_id =".$info['program_id'];
		$this->db->query($sql);		
	}
	
	//根据条件查询WEB视频内容
	public function show($condition,$limit)	
	{		
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."webvod
				WHERE 1".$condition." ORDER BY orderid DESC".$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$row['pub'] = unserialize($row['column_id']);
			$row['pub_url'] = unserialize($row['column_url']);
			$sql_ = "SELECT indexpic,is_now,id,url
					 FROM  " . DB_PREFIX ."webvodpic
					 WHERE program_id = ".$row['program_id'];
			$picq = $this->db->query($sql_);
			$pic = array();
			while($ro = $this->db->fetch_array($picq))
			{
				if($ro)
				{
					/*$picinfo = unserialize($ro['indexpic']);
			       	$url = $picinfo['host'].$picinfo['dir'].'40x30/'.$picinfo['filepath'].$picinfo['filename'];
					$pic[$ro['id']] = $url;*/
					$pic[$ro['id']] = $ro['url'];
					if($ro['is_now'])
					{
						$pinf = unserialize($ro['indexpic']);
			       		$u = $pinf['host'].$pinf['dir'].'40x30/'.$pinf['filepath'].$pinf['filename'];
						$row['indexpic'] = $u;
						//$row['indexpic'] = $ro['url'];
					}
				}
				$row['pic'] = $pic;
			}
			if(!$row['indexpic'])
			{
				$index = $this->db->query_first($sql_);
				/*$pinf = unserialize($index['indexpic']);
	       		$u = $pinf['host'].$pinf['dir'].'40x30/'.$pinf['filepath'].$pinf['filename'];
				$row['indexpic'] = $u;*/
				$row['indexpic'] = $index['url'];
			}
			$row['cre_time'] = date("Y-m-d H:i",$row['create_date']);
			$ret[] = $row;
		}
		return $ret;
	}
	
	//WEB视频发布
	public function publish()
	{
		$id = intval($this->input['id']);
		$column_id = urldecode($this->input['column_id']);
		
		$new_column_id = explode(',',$column_id);
		if(!$new_column_id[0])
		{
			//$this->errorOutput('请选择栏目');
		}
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
		
		//查询修改WEB视频之前已经发布到的栏目
		$sql = "SELECT * FROM " . DB_PREFIX ."webvod WHERE program_id = " . $id;
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		
		$sql = "UPDATE " . DB_PREFIX ."webvod SET column_id = '". $column_id ."' WHERE program_id = " . $id;
		$this->db->query($sql);
		if(intval($q['status']) ==1)
		{
			if(!empty($q['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->publish_insert_query($id, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->publish_insert_query($id, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->publish_insert_query($id, 'update',$same_column);
				}
			}
			else //未发布，直接插入
			{
				$op = "insert";
				$this->publish_insert_query($id,$op);
			}
		}
		else    //打回
		{
			if(!empty($q['expand_id']))
			{
				$op = "delete";
				$this->publish_insert_query($id,$op);
			}
		}
		return true;
		
	}
	
	
	
	/**
	*  发布系统，将内容传入发布队列
	*  
	*/	
	public function publish_insert_query($article_id,$op,$column_id = array(),$child_queue = 0,$is_childId = 0)
	{
		$id = intval($article_id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX ."webvod WHERE program_id = " . $id;
		$info = $this->db->query_first($sql);
		
		$sql_ = "SELECT * FROM " . DB_PREFIX ."webvodpic WHERE program_id = " . $id ." AND is_now = 1";
		$pic_info = $this->db->query_first($sql_);
		if(!$pic_info)
		{
			$sqlstr = "SELECT * FROM " . DB_PREFIX ."webvodpic WHERE program_id = " . $id;
			$pic_info = $this->db->query_first($sqlstr);
		}
		$indexpic  = $pic_info['indexpic'];
		if(empty($column_id))
		{		
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}			
		}
		else 
		{
			$column_id = implode(',',$column_id);
		}
 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=>	PUBLISH_SET_ID,
			'from_id'   =>  $info['program_id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['title'],
			'action_type' => $op,
			'publish_time'  => TIMENOW,
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		if($is_childId)
		{
			$data['title'] = $info['title'];
		}
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	
	
	public function access_sync($data,$id)
	{
		if(!empty($data) && is_array($data))
		{
			$sql = "UPDATE ".DB_PREFIX."webvod SET ";
			$space = '';
			foreach($data as $k => $v)
			{
				$sql.= $space . $k ."='".$v."'";
				$space = ',';
			}
			$sql .= " WHERE id = " . $id;
			$this->db->query($sql);
			$sql = "SELECT status,expand_id FROM ".DB_PREFIX."webvod WHERE id = " . $id;
			$info = $this->db->query_first($sql);
			if($info['status'] == 1)
			{
				if(!empty($info['expand_id']))
				{
					$op = 'update';
				}
				else
				{
					$op = 'insert';
				}
				$this->publish_insert_query($id, $op);
			}
			else
			{
				if(!empty($info['expand_id']))
				{
					$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
				}		
				else 
				{
					$op = "";
				}
				$this->publish_insert_query($id, $op);				
			}
		}
		return $data;
	}	
}


?>