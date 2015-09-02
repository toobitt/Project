<?php
class IssueClass extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition='',$orderby=' ORDER BY i.order_id  DESC',$offset=0,$count=10)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT m.name,i.*,i.total_issue as volume,s.name as sort_name FROM '.DB_PREFIX.'issue i  
				LEFT  JOIN  '.DB_PREFIX.'magazine m 
				ON i.magazine_id = m.id 
				LEFT JOIN '.DB_PREFIX.'magazine_node s 
				ON m.sort_id = s.id WHERE 1 '.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		//$size = '40x30/';//缩略图尺寸
		$size1 = '400x300/';
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			if ($r['update_time'])
			{
				$r['update_time'] = date('Y-m-d H:i:s',$r['update_time']);
			}
			else 
			{
				$r['update_time'] = '- -';
			}
			
			if ($r['pub_date'])
			{
				$r['pub_date'] = date('Y-m-d',$r['pub_date']);
			}
			else 
			{
				$r['pub_date'] = '- -';
			}
			switch ($r['state'])
			{
				case  1: $r['audit'] = '已审核';break;
				case  2: $r['audit'] = '已打回';break;
				default: $r['audit'] = '待审核';
			}
			if (!$r['user_name'])
			{
				$r['user_name'] = '匿名用户';
			}
			$r['year'] = substr($r['pub_date'],0,4);
			$r['url'] = hg_material_link($r['host'], $r['dir'], $r['file_path'], $r['file_name'],$size);
			$r['url1'] = hg_material_link($r['host'], $r['dir'], $r['file_path'], $r['file_name'],$size1);
			$r['sort_name'] = $r['sort_name'] ? $r['sort_name'] : '未分类';
			$r['indexarticle'] = unserialize($r['cover_article']);
			unset($r['cover_article']);
			$res[] = $r;
		}
		return $res;
	} 
	//查询具体期刊信息
	public function detail($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT * FROM  '.DB_PREFIX.'issue 
		WHERE id = '.$id;
		$r = $this->db->query_first($sql);
		$size='70x90/';
		$r['url'] = hg_material_link($r['host'], $r['dir'], $r['file_path'], $r['file_name'],$size);
		$r['pub_date'] = date('Y-m-d',$r['pub_date']);
		if ($r['cover_article'])
		{
			$r['indexarticle'] = unserialize($r['cover_article']);
		}
		else 
		{
			$r['indexarticle'] = array();
		}
		unset($r['cover_article']);
		//发布栏目
        $column_id = unserialize($r['column_id'])?unserialize($r['column_id']):array();
        if (is_array($column_id))
        {
        	$r['column_id'] = implode(',', array_keys($column_id));
        }	
		return $r;
	}
	//查询期刊下的文章
	public function get_article($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT a.*,c.content,l.name as sort_name FROM  '.DB_PREFIX.'article a 
		LEFT JOIN '.DB_PREFIX.'content c 
		ON a.id = c.article_id 
		LEFT JOIN '.DB_PREFIX.'catalog l 
		ON a.group_id = l.id  WHERE a.issue_id = '.intval($id).' ORDER BY a.order_id DESC';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$r['user_name'] = $r['user_name'] ? $r['user_name'] : '匿名用户'; 
			$r['sort_name'] = $r['sort_name'] ? $r['sort_name'] : '未分类';
			if($r['indexpic'])//索引图
			{
				$r['indexpic_url'] = $this->getThumbById($r['indexpic']);
			}
			switch ($r['state'])
			{
				case  1: $r['audit'] = '已审核';break;
				case  2: $r['audit'] = '打回';break;
				default: $r['audit'] = '未审核';
			}
			$info[] = $r;
		}
		return $info;
	}
	//查询文章信息
	public function form_article($id)
	{
		$sql = 'SELECT a.*,c.content FROM  '.DB_PREFIX.'article a 
		LEFT JOIN '.DB_PREFIX.'content c 
		ON a.id = c.article_id WHERE a.id = '.$id;
		$r = $this->db->query_first($sql);
		$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
		if(!empty($r['indexpic']))
		{
			//查找索引图
			$r['indexpic_url'] = $this->getThumbById($r['indexpic'],$this->settings['default_index']);
		}
		else
		{
			$r['indexpic_url'] = '';
		}
		return $r;
	}

	private function getThumbById($id,$size = array())
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE 1 AND material_id=" . $id;
		$ret = $this->db->query_first($sql);
		if(empty($ret))
		{
			return false;
		}
		if($ret['mark'] != 'img')
		{
			return false;
		}
		$size = $size ? $size : $this->settings['small_size'];
		return hg_material_link($ret['host'] ,$ret['dir'], $ret['filepath'], $ret['filename'],$size['label'] . "/");
	}
	public function upload()
	{
		if($_FILES['Filedata'])
		{			
			$typetmp = explode('.',$_FILES['Filedata']['name']);
			$filetype = strtolower($typetmp[count($typetmp)-1]);
			$gMaterialType = $this->mater->check_cache();
			$type = '';
			if(!empty($gMaterialType))
			{
				foreach($gMaterialType as $k => $v)
				{
					if(in_array($filetype,$v))
					{
						$type = $k;
					}
				}
			}
			
			if(empty($type))
			{
				$return = array(
					'success' => false,
					'error' => '上传文件格式不正确',
				);
				return $return;
			}
			
			$material = $this->mater->addMaterial($_FILES,0); //插入各类服务器
			
			if(!empty($material))
			{
				$sql = "REPLACE INTO " . DB_PREFIX ."material SET ";
				$material['material_id'] = $material['id'];
				unset($material['bundle_id'], $material['mid'], $material['id'], $material['url']);
				$sql_extra = $space ='';
				foreach($material as $k => $v)
				{
					$sql_extra .= $space . $k . "='" . $v . "'";
					$space = ',';
				}
				$this->db->query($sql . $sql_extra);
				//图片信息返回后，更新内容标签

				$material['filesize'] = hg_bytes_to_size($material['filesize']);
				
				switch($type)
				{
					case 'img':
						$ret = array(
							'success' => true,
							'id' => $material['material_id'],	
							'filename' => $material['filename'],
							'name' => $material['name'],
							'mark' => 'img',
							'type' => $filetype,
							'filesize' => $material['filesize'],
							'path' => $material['host'].$material['dir'],
							'dir' => $material['filepath'],
						);
						break;
					case 'doc':
						$ret = array(
							'success' => true,
							'id' => $material['material_id'],	
							'filename' => $material['filename'],
							'name' => $material['name'],
							'mark' => 'doc',
							'type' => $filetype,
							'filesize' => $material['filesize'],
							'url' => MATERIAL_TYPE_THUMB . 'doc.png', //返回小图
						);
						break;
					case 'real':						
						$ret = array(
							'success' => true,
							'id' => $material['material_id'],	
							'filename' => $material['filename'],
							'name' => $material['name'],
							'mark' => 'real',	
							'type' => $filetype,
							'filesize' => $material['filesize'],
						);
						break;
					default:
						break;
				}
				return $ret ;
			}
			else
			{
				$return = array(
					'success' => false,
					'error' => '文件上传失败',
				);
				return $return;
			}
		}
	}
	
	//图片本地化
	function img_local()
	{
		$url = urldecode($this->input['url']);
		$water_id = urldecode($this->input['water_id']);		//如果设置了水印则要传水印
		$material = $this->mater->news_localMaterial($url,0,$water_id); 	//调用图片服务器本地化接口
		if(!empty($material))
		{
			$url_arr = explode(',', $url);
			$info = array();
			foreach ($material as $k => $v)
			{
				if(!empty($v))
				{
					foreach ($v as $kk => $vv) 
					{
						if(in_array($kk, $url_arr))
						{
							$info[$kk] = array('url'=>$v[$kk],'id' => $v['id'],'remote_url'=>$kk,'path' => $v['host'].$v['dir'],'dir' => $v['filepath'],'filename' => $v['filename']);
							unset($material[$k][$kk]);
							$material[$k]['remote_url'] = $kk;
						}
					}
				}
			}
			$sql = "INSERT INTO " . DB_PREFIX ."material SET ";
			foreach($material as $key => $value)
			{
				if(!empty($value))
				{
					$value['material_id'] = $value['id'];
					unset($value['mid'],$value['id'],$value['bundle_id']);
					$sql_extra = $space ='';
					foreach($value as $k => $v)
					{
						$sql_extra .= $space . $k . "='" . $v . "'";
						$space=',';
					}
					$this->db->query($sql . $sql_extra);
				}
			}
		}
		return $info;
	}
	public function publish()
	{
		$id = intval($this->input['id']);
		
		//发布之前检测该条杂志的状态，只有审核通过的才可以发布
		$sql = "SELECT * FROM ".DB_PREFIX."issue WHERE id = ".$id;
	    $ret = $this->db->query_first($sql);	
	    //获取栏目发布的id
	    //$sql = "SELECT expand_id FROM ".DB_PREFIX."magazine WHERE id = ".$ret['magazine_id'];
	    //$expand = $this->db->query_first($sql);	
	    //$expand_id = $expand['expand_id'];
		$column_id = urldecode($this->input['column_id']);
		$new_column_id = explode(',',$column_id);
		//通过id获取发布栏目名称
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
	    		
		$sql = "UPDATE " . DB_PREFIX ."issue SET column_id = '". $column_id ."' WHERE id = " . $id;
		$this->db->query($sql);
		
		//查询该杂志是否发布，以及发布到哪个栏目下
		$ret['column_id'] = unserialize($ret['column_id']);
		
		//将之前的栏目id放入数组中，准备对比
		$old_column_id = array();
		if (is_array($ret['column_id']))
		{
			$old_column_id = array_keys($ret['column_id']);
		}
	 	if($ret['state'] == 1)
	 	{
			if (!empty($ret['expand_id']))
			{
				$del_column = array_diff($old_column_id,$new_column_id);
				
				if (!empty($del_column))
				{
					$this->publish_insert_query($id, 'delete',$del_column);
				}		
				$add_column = array_diff($new_column_id,$old_column_id);
				if (!empty($add_column))
				{
					$this->publish_insert_query($id, 'insert',$add_column);
				}
				$same_column = array_intersect($old_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->publish_insert_query($id, 'update',$same_column);
				}
			}
			else 
			{
				$op = "insert";
				$this->publish_insert_query($id,$op);
			}
	 	}
		return true;	
	}
	/**
	*  发布系统，将内容传入发布队列
	*  
	*/	
	public function publish_insert_query($issueId,$op,$column_id = array())
	{
		$id = intval($issueId);
		if (empty($id) || empty($op))
		{
			return false;
		}
		$sql = "SELECT  *  FROM ".DB_PREFIX."issue WHERE id = " . $id;
		$info = $this->db->query_first($sql);
		
		if (empty($column_id))
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
			'set_id' 	=>	ISSUE_PLAN_SET_ID,
			'from_id'   =>  $info['magazine_id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['issue'],
			'action_type' => $op,
			'publish_time'  => TIMENOW,
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
			//'expand_id' => $expand_id,
		);
		$ret = $plan->insert_queue($data);
		//file_put_contents('2.txt', var_export($ret,1));
		return $ret;
	}
	
}