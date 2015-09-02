<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/opinion.class.php');
include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/member.class.php');
require_once(ROOT_PATH . 'lib/class/ftp.class.php');
class contribute extends InitFrm
{
	static private $sortId = array();
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->opinion = new opinion();
		$this->publish_column = new publishconfig();
		$this->member = new member();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$offset,$count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT c.id as cid,c.*,s.name,m.host,m.dir,m.material_path,m.pic_name FROM '.DB_PREFIX.'content c  
				LEFT  JOIN  '.DB_PREFIX.'sort s ON c.sort_id = s.id 
				LEFT JOIN '.DB_PREFIX.'materials m ON m.materialid = c.material_id 
				WHERE 1 '.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$k = array();
		$ids = array();
		while(!false ==($r = $this->db->fetch_array($q)))
		{
			$r['pass_time'] = TIMENOW-$r['create_time'];
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$r['zt'] = $r['audit'];
			switch ($r['audit'])
			{
				case  1: $r['audit'] = '未审核';break;
				case  2: $r['audit'] = '已审核';break;
				case  3: $r['audit'] = '被打回';break;
				default: $r['audit'] = '未审核';
			}
			if (!$r['user_name'])
			{
				$r['user_name'] = '匿名用户';
			}
			$r['name'] = $r['name'] ? $r['name'] : '未分类';
			$r['indexpic'] = array();
			if ($r['host'] && $r['dir'] && $r['material_path'] && $r['pic_name'])
			{
				$r['indexpic'] = array(
					'host'=>$r['host'],
					'dir'=>$r['dir'],
					'file_path'=>$r['material_path'],
					'file_name'=>$r['pic_name']
				);
			}
         	//输出发布栏目
         	if ($r['column_id'])
         	{
         		$column_id = unserialize($r['column_id']);
         		if ($column_id)
         		{
         			$r['column_id'] = $column_id;
         		}else {
         			$r['column_id'] = array();
         		}
         	}
         	$ids[] = $r['cid'];
         	$r['id'] = $r['cid'];
         	unset($r['cid']);
			$k[] = $r;
			
		}
		$content_ids = '';
		//取所有图片
		if (!empty($ids))
		{
			$content_ids = implode(',', $ids) ;		
		}
		if ($content_ids)
		{
			//$pic = $this->all_pic($content_ids);
			$pic = array();
			//输出视频标识
			$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$content_ids.')' ;
	        $res = $this->db->query($sql);
	        $videolog = array();
	        while ($row = $this->db->fetch_array($res))
	        {
	       		if (in_array($row['mtype'], $this->settings['video_type'])){
	       			$videolog[$row['content_id']] = 1;
	       		}
	       		if (!$row['vodid'] && $row['pic_name'])
	       		{
	       			$pic[$row['content_id']][] = array(
						'content_id'=>$row['content_id'],
						'material_id'=>$row['materialid'],
						'host'=>$row['host'],
						'dir'=>$row['dir'],
						'filepath'=>$row['material_path'],
						'filename'=>$row['pic_name'],
					);
	       		}
	        }
	        
			if (!empty($k))
			{
				foreach ($k as $key=>$val)
				{	
					$k[$key]['pic'] = $pic[$val['id']];
					$k[$key]['videolog'] = $videolog[$val['id']];
				}
				
			}	
		}		
		return $k;
	}
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'content c WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	public function detail($id)
	{
		$sql = 'SELECT c.*,cb.text,s.name,u.*  FROM  '.DB_PREFIX.'content c 
				LEFT JOIN '.DB_PREFIX.'contentbody cb ON c.id = cb.id  
				LEFT JOIN '.DB_PREFIX.'sort s ON c.sort_id = s.id
				LEFT JOIN '.DB_PREFIX.'content_user u ON c.id = u.con_id
				WHERE c.id = '.$id;
		$ret = $this->db->query_first($sql);
		$ret['brief'] = html_entity_decode($ret['brief']);
		$ret['text'] = html_entity_decode($ret['text']);
		//输出所有分类
			
		//根据索引图ID搜索图片
		$ret['indexpic'] = $this->get_indexpic($ret['material_id']);

		//视频地址
		$ret['video_url'] = $this->get_video($id);
		
		//图片信息
		$ret['pic'] = $this->get_pic($id);
		
		$ret['create_time'] = date('Y-m-d H:i:s',$ret['create_time']);
		//审核意见
		$opinion = $this->opinion->showOpinion($id);
		$ret['opinion'] = $opinion ? $opinion[0]['content'] : '';
	    //发布栏目
        $column_id = unserialize($ret['column_id'])?unserialize($ret['column_id']):array();
        if (is_array($column_id))
        {
        	$ret['column_id'] = implode(',', array_keys($column_id));
        }
        $ret['open_bounty'] = BOUNTY;	
		return $ret;
	}
	public function show_opration($id)
	{
		$sql = 'SELECT c.*,cb.text,s.name,u.* FROM '.DB_PREFIX.'content c
				LEFT JOIN '.DB_PREFIX.'contentbody cb ON c.id = cb.id
				LEFT JOIN '.DB_PREFIX.'sort s ON c.sort_id =s.id
				LEFT JOIN '.DB_PREFIX.'content_user u ON c.id =u.con_id
				WHERE c.id='.$id;
		$ret =  $this->db->query_first($sql);
		$ret['create_time'] = date('Y-m-d H:i:s',$ret['create_time']);
		$ret['text'] = html_entity_decode($ret['text']);
		switch ($ret['audit'])
		{
			case  1: $ret['zt'] = '未审核';break;
			case  2: $ret['zt'] = '已审核';break;
			case  3: $ret['zt'] = '被打回';break;
			default: $ret['zt'] = '未审核';
		}
		//获取审核意见
		$res = $this->opinion->showOpinion($id);
		$ret['opinion'] = $res[0]['content'];
		$ret['pic'] = $this->get_pic($id);
		$ret['video_url'] = $this->get_video($id);
		return $ret;
	}
	//根据图片id获得索引图
	public function get_indexpic($id)
	{
		$sql = 'SELECT host,dir,material_path,pic_name FROM '.DB_PREFIX.'materials WHERE materialid = '.$id;
		$ret = $this->db->query_first($sql);
		$k =array();
		if ($ret)
		{
			$k = array(
				'host'=>$ret['host'],
				'dir'=>$ret['dir'],
				'file_path'=>$ret['material_path'],
				'file_name'=>$ret['pic_name'],
			);
		}
		return $k;		
	}
	
	//根据内容id获取所有图片
	public function get_pic($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX.'materials WHERE content_id = '.$id;
		$query = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			if ($row['pic_name'])
			{
				$k[] = array(
					'material_id'=>$row['materialid'],
					'host' =>$row['host'],
					'dir'=>$row['dir'],		
					'file_path'=>$row['material_path'], 
					'file_name'=>$row['pic_name'],
				);
			}
		}
		return $k;
	}
		
	//根据内容id获取所有视频信息
	public function get_video($id)
	{
		$k = array();
		$sql = "SELECT * FROM ".DB_PREFIX.'materials WHERE content_id = '.$id.' AND vodid!=""';
		$res = $this->db->query_first($sql);
		$url = $res['host'].'/'.$res['dir'].MANIFEST;
		$vodid = $res['vodid'];
		
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'].'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','get_video');
		$curl->addRequestData('id',$vodid);
		$ret = $curl->request('vod.php');
		$ret = $ret[0];		
		if (is_array($ret) &&  !empty($ret))
		{
			$arr = explode('.', $ret['video_filename']);
			$type = $arr[1];
			$m3u8 = $ret['hostwork'].'/'.$ret['video_path'].str_replace($type, 'm3u8', $ret['video_filename']);
		}
		if ($vodid)
		{
			$k[] = array(
			'm3u8'=>$m3u8,
			'url'=>$url,
			'vodid'=>$vodid,
			);
		}
		/*
		$query = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			if ($row['vodid'])
			{
				$v['m3u8'] = $row['host'].'/'.$row['dir'].$row['filename'].'.m3u8';
				$v['url'] = $row['host'].'/'.$row['dir'].MANIFEST;
				$v['vodid'] = $row['vodid'];
				$k[] = $v;
			}
		}
		*/
		return $k;
	}
    //根据内容id获取相关信息
    public function get_contentinfo($id)
    {
    	$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$id.')';
    	$query = $this->db->query($sql);
    	$k = array();
    	while (!false == ($row = $this->db->fetch_array($query)))
    	{
    		$k[$row['id']] = $row;
    	}
    	return $k;
    }
	//单图片上传
	public function upload($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	//删除图片
	public function del_pic($ids)
	{
		//搜索原始素材id，准备删除图片服务器上文件
		$sql = 'SELECT original_id FROM '.DB_PREFIX.'materials WHERE materialid IN('.$ids.')';
		$query  = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			$k[] = $row['original_id'];
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'materials  WHERE  materialid IN ('.$ids.')';
		$this->db->query($sql);
		//$id = implode(',', $k);
		//$this->material->delMaterialById($id,2);
		return true;		
	}
	//删除视频
	public function del_video($ids)
	{
		//搜索原始素材id，准备删除视频服务器上文件
		$sql = 'SELECT original_id FROM '.DB_PREFIX.'materials WHERE materialid IN('.$ids.')';
		$query  = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			$k[] = $row['original_id'];
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'materials  WHERE  materialid IN ('.$ids.')';
		$this->db->query($sql);
		/*
		$id = implode(',', $k);	
		$curl = new curl($this->settings['video_api']['host'],$this->settings['video_api']['dir'],$this->settings['video_api']['token']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','delete');
		$curl->addRequestData('id',$id);
		$curl->request('vod_update.php');
		*/
		return true;
	}
	
	//更新索引图
	public function update_indexpic($mid,$cid)
	{
		$sql = 'UPDATE '.DB_PREFIX.'content SET material_id = '.$mid.' WHERE id = '.$cid;
		$this->db->query($sql);
		$pic_sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE materialid = '.$mid;
		$pic = $this->db->query_first($pic_sql);
		$url = array(
					'host'=>$pic['host'],
					'dir'=>$pic['dir'],
					'file_path'=>$pic['material_path'],
					'file_name'=>$pic['pic_name'],
					'cid'=>$cid
				);
		return $url;
	}
	
	//更新爆料主表
	public function update_content($data,$id)
	{
		if (!$id || !is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'content SET ';
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
	public function update_contentbody($content,$id)
	{
		if (!$content || !$id)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'contentbody SET text = "'.$content.'" WHERE id = '.$id;
		$this->db->query($sql);
		return true;
	}
	
	//删除信息表
	public function del_content($ids)
	{
		if (!$ids)
		{
			return false;
		}
		
		$sql = 'DELETE FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return true;
	}
	//删除内容表
	public function del_contentbody($ids)
	{
		if (!$ids)
		{
			return false;
		}
		
		$sql = 'DELETE FROM '.DB_PREFIX.'contentbody WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$this->opinion->deleteOpinion($ids);
		return true;
	}
	//删除素材表
	public function del_materials($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$p = array();
		$v = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			if ($row['pic_name'])
			{
				$p[] = $row['materialid'];
			}
			if ($row['vodid'])
			{
				$v[] = $row['materialid'];
			}
		}
		if (!empty($p))
		{
			$pids = implode(',', $p);
			$this->del_pic($pids);
		}
		if (!empty($v))
		{
			$vids = implode(',', $v);
			$this->del_video($vids);
		}
		return true;		
	}
	//改变审核状态
	public function changeAudit($state,$id)
	{
		if (!$state)
		{
			return false;
		}

		$sql = 'UPDATE '.DB_PREFIX.'content SET audit = '.$state.' WHERE id = '.$id;
		$this->db->query($sql);
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id='.$id;
		$ret = $this->db->query_first($sql);
		//改变审核状态控制发布状态
		if ($state == 2)
		{
			if (!empty($ret['expand_id']))
			{
				$op = "update";			
			}else {
				$op = "insert";
			}			
		}else {
			if (!empty($ret['expand_id']))
			{
				$op = 'delete';	
			}
		}
		$this->publish_insert_query($id, $op);
		return $state;
	}
	//多个审核
	public function audit($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'content SET audit = 2 WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		//循环插队列，存在效率问题
		while ($row = $this->db->fetch_array($query))
		{
			//改变审核状态控制发布状态
			if (!empty($row['expand_id']))
			{
				$op = "update";			
			}else {
				$op = "insert";
			}
			$this->publish_insert_query($row['id'], $op);	
		}
		//$this->to_road($ids);
		return $ids;
	}
	//多个打回	
	public function back($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'content SET audit = 3 WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		//循环插队列，存在效率问题
		while ($row = $this->db->fetch_array($query))
		{
			if (!empty($row['expand_id']))
			{
				$op = 'delete';
			}
			$this->publish_insert_query($row['id'], $op);
		}
		return $ids;
	}
	//添加爆料内容
	public function add_contentbody($data)	
	{
		$sql  = 'REPLACE INTO '.DB_PREFIX.'contentbody SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	//添加爆料信息
	public function add_content($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'content SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$update_sql = 'UPDATE '.DB_PREFIX.'content set order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($update_sql);
		//添加内容表	
		return $id;
	}
	//图片插入图片服务器
	public function uploadToPicServer($file,$content_id)
	{
		$material = $this->material->addMaterial($file,$content_id); //插入图片服务器
		return $material;
	}
	//根据url上传图片
	public function localMaterial($url,$cid)
	{
		$material = $this->material->localMaterial($url,$cid);
		return $material[0];
	}
	//上传视频
	public function uploadToVideoServer($file,$title,$brief)
	{

		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($file);
		$curl->addRequestData('title', $title);
		$curl->addRequestData('comment',$brief);
		$curl->addRequestData('vod_leixing',2);
		$ret = $curl->request('create.php');
		return $ret[0];
	}
	//每一级分类
	public function sort($id, $exclude_id = 0)
	{
		if ($exclude_id)
		{
			$cond = ' AND id NOT IN (' . $exclude_id . ')';
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'sort WHERE fid=' . intval($id) . $cond .' ORDER BY order_id ASC';
		$query = $this->db->query($sql);
		$k = array();
		while(!false == ($row = $this->db->fetch_array($query)))
		{
			if ($row['userinfo'])
			{
				$row['userinfo'] = unserialize($row['userinfo']);
			}
			if ($row['image'])
			{
				$row['image'] = unserialize($row['image']);
			}
			$k[$row['id']] = $row;
		}
		return $k;
	}
	//获取所有分类
	public function allsort($condition='')
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'sort WHERE 1'.$condition;
		$query = $this->db->query($sql);
		$k = array();
		while(!false == ($row = $this->db->fetch_array($query)))
		{
			$k[$row['id']] = $row;
		}
		return $k;
	}
	//根据分类id获取所有的快捷输入
	public function fastInput($id)
	{
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$k = array();
		$sql = 'SELECT childs FROM '.DB_PREFIX.'sort WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		if ($ret)
		{
			$sql = 'SELECT input_sort FROM '.DB_PREFIX.'sort WHERE id IN ('.$ret['childs'].')';
			$query = $this->db->query($sql);
			while (!false == ($row = $this->db->fetch_array($query)))
			{
				$row['input_sort'] = explode(',', $row['input_sort']);
				foreach ($row['input_sort'] as $key=>$val)
				{
					if ($val)
					{
						$k[] = $val;
					}		
				}
			}
		}
		$k = array_unique($k);
		$sortIds = implode(',', $k);
		$res = $this->fastInput_by_sort($sortIds);
		return $res;
	}
	//根据快捷输分类获取快捷输入
	public function fastInput_by_sort($ids)
	{
		$k = array();
		if ($ids)
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput WHERE sort_id IN ('.$ids.') ORDER BY order_id DESC';
			$query = $this->db->query($sql);	
			while (!false == ($row = $this->db->fetch_array($query)))
			{
				if (!in_array($row['content'], $k))
				{
					$k[$row['id']] = $row['content'];
				}
			}
			//$k = array_unique($k);
		}	
		return $k;
	}
	
	public function publish()
	{
		
		$id = intval($this->input['id']);
		 //获取栏目发布的id
		$column_id = $this->input['column_id'];
		$new_column_id = explode(',',$column_id);
		//发布之前检测该条爆料的状态，只有审核通过的才可以发布
		$sql = "SELECT * FROM ".DB_PREFIX."content WHERE id = ".$id;
	    $ret = $this->db->query_first($sql);
	   
		//通过id获取发布栏目名称
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
		
		$sql = "UPDATE " . DB_PREFIX ."content SET column_id = '". addslashes($column_id) ."' WHERE id = " . $id;
		$this->db->query($sql);
		
		//查询该爆料是否发布，以及发布到哪个栏目下
		$ret['column_id'] = unserialize($ret['column_id']);
		
		//将之前的栏目id放入数组中，准备对比
		$old_column_id = array();
		if (is_array($ret['column_id']))
		{
			$old_column_id = array_keys($ret['column_id']);
		}
		
		//栏目对比
		if ($ret['audit'] == 2)
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
			}else {
				$op = "insert";
				$this->publish_insert_query($id,$op);
			}
		}else {
			if (!empty($ret['expand_id']))
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
	public function publish_insert_query($contributeId,$op,$column_id = array())
	{
		$id = intval($contributeId);
		if (empty($id) || empty($op))
		{
			return false;
		}
		$sql = "SELECT  *  FROM ".DB_PREFIX."content WHERE id = " . $id;
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
			'set_id' 	=>	CONTRIBUTE_PLAN_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['title'],
			'action_type' => $op,
			'publish_time'  => TIMENOW,
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	//生成xml文件
	public function forward_suobei($id)
	{
		//获取视频id
		$sql = 'SELECT content_id,vodid FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$id.') AND vodid !=""';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['content_id']] = $row['vodid'];
		}
		$ids = '';
		$ret = array();
		if (!empty($k))
		{
			//获取视频信息
			$ids = implode(',', $k);
			$keys = array_keys($k);
			$vodpath = array();
			
			//获取报料标题
			$sql = 'SELECT id,title FROM '.DB_PREFIX.'content WHERE id IN ('.implode(',', $keys).')';
			$query = $this->db->query($sql);
			$title = array();
			while ($row=$this->db->fetch_array($query))
			{
				$title[$row['id']] = $row['title'];
			}
			$title = array_combine($k, $title);
			$ftp = $this->settings['App_suobei']['ftp'];
			$ids = implode(',', $k);
			$ret = $this->get_vodinfo($ids,$ftp['host'],$ftp['username'],$ftp['password']);
			$vodpath = array();
			if (!empty($ret) && is_array($ret))
			{
				foreach ($ret as $key=>$val)
				{
					$vodpath[$val['id']] = $ret[$key];
				}
			}else {
				$this->errorOutput('ftp上传失败');
			}	
		}
		if (!empty($vodpath) && !empty($title))
		{
			//获取报料标题
			
			//写xml文件
			$this->vod_xml($vodpath,$title);
			//ftp上传
			//实例化ftp,并连接
			$ftp_config = array(
				'hostname' => $ftp['host'],
				'username' => $ftp['username'],
				'password' => $ftp['password'],
			);
			$ftp_up =new Ftp();
			if(!$ftp_up->connect($ftp_config))
			{
				$this->errorOutput('CAN NOT CONNECT FTP SERVER');
			}
			
			foreach($vodpath AS $k => $v)
			{
				$target_dir = $v['dir'] . '/' ;
				$target_path = $target_dir . $v['filename'].'.xml';
				$xml_filepath = $this->settings['App_suobei']['xmldir'].$v['filename'].'.xml';
				if(!file_exists($xml_filepath))
				{
					$this->errorOutput('CAN NOT FIND XML');
				}
				
				if(!$ftp_up->mkdir($target_dir))
				{
					$this->errorOutput('CAN NOT MAKE DIR');
				}
				
				if(!$ftp_up->upload($xml_filepath,$target_path))
				{
					$this->errorOutput('CAN NOT UPLOAD FILE');
				}
				
			}
			$ftp_up->close();	
			//更新状态位
			$sql = 'UPDATE '.DB_PREFIX.'content SET suobei=1 WHERE id IN ('.implode(',', $keys).')';
			$this->db->query($sql);	
		}
		return $id;	
	}
	//获取视频物理路径
	private  function get_vodinfo($id,$host,$username,$pass)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','upload');
		$curl->addRequestData('video_id',$id);
		$curl->addRequestData('hostname',$host);
		$curl->addRequestData('username',$username);
		$curl->addRequestData('password',$pass);
		$ret = $curl->request('ftp_upload.php');
		return $ret;		
	}
	private function vod_xml($data,$title)
	{  
		foreach ($data as $key=>$val)
		{
			$dom = new DOMDocument('1.0', 'utf-8');   
			$ClipItem = $dom->createElement('ClipItem');
			$ClipItem = $dom->appendChild($ClipItem);     
	 		$ClipName = $dom->createElement('ClipName');
			$ClipName=$ClipItem->appendChild($ClipName); 
			$ClipIn = $dom->createElement('ClipIn');
			$ClipIn=$ClipItem->appendChild($ClipIn); 
			$ClipOut = $dom->createElement('ClipOut');
			$ClipOut=$ClipItem->appendChild($ClipOut); 
			$ClipLength = $dom->createElement('ClipLength');
			$ClipLength=$ClipItem->appendChild($ClipLength); 
			$LockFlag = $dom->createElement('LockFlag');
			$LockFlag=$ClipItem->appendChild($LockFlag); 
			$KeepDays = $dom->createElement('KeepDays');
			$KeepDays=$ClipItem->appendChild($KeepDays); 
			$ClipNote = $dom->createElement('ClipNote');
			$ClipNote=$ClipItem->appendChild($ClipNote); 
			$Catalog = $dom->createElement('Catalog');
			$Catalog=$ClipItem->appendChild($Catalog);  
			$FileItem = $dom->createElement('FileItem');	
			$FileItem=$ClipItem->appendChild($FileItem);
			$FileName = $dom->createElement('FileName');	
			$FileName=$FileItem->appendChild($FileName); 
				
			$ClipName->appendChild($dom->createTextNode($title[$key]));			
			$ClipIn->appendChild($dom->createTextNode(0));
			$ClipOut->appendChild($dom->createTextNode(-1));			
			$ClipLength->appendChild($dom->createTextNode(-1));
			$LockFlag->appendChild($dom->createTextNode(0));
			$KeepDays->appendChild($dom->createTextNode(7));
			$ClipNote->appendChild($dom->createTextNode(''));
			$Catalog->appendChild($dom->createTextNode('\\\公共资源库\CUTV总台\济南台\电视电台类\都市\都市新女报\\'));
			$FileName->appendChild($dom->createTextNode($this->settings['App_suobei']['xmlpath'].$val['path']));
			if (!hg_mkdir($this->settings['App_suobei']['xmldir']) || !is_writeable($this->settings['App_suobei']['xmldir']))
			{
				$this->errorOutput(NOWRITE);
			}
			$dom->save($this->settings['App_suobei']['xmldir'].$val['filename'].".xml");				
		}
		
		
		 return true;
	}
	//更新用户信息
	public function user_info($data)
	{
		$sql = 'REPLACE INTO '.DB_PREFIX.'content_user SET ';
		foreach ($data as $key=>$val)
		{
			$sql.= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		return true;
	}
	//根据用户id获取用户信息
	public function get_userinfo_by_id($uid)
	{
		$ret = $this->member->getUserinfoById($uid);
		return $ret[0];
	}
	//
	public function to_road($ids)
	{
		if (!$ids)
		{
			return false;
		}
		if ($this->settings['App_road']['sort_id'])
		{
			$sql = 'SELECT id FROM '.DB_PREFIX.'content WHERE sort_id='.$this->settings['App_road']['sort_id'].' AND id IN ('.$ids.')';
			$query = $this->db->query($sql);
			$f = array();
			while ($row = $this->db->fetch_array($query))
			{
				$f[] =$row['id']; 
			}
			if (!empty($f))
			{
				$ids = implode(',', $f);
				if ($this->settings['App_road']['is_open'])
				{
					$sql = 'SELECT c.id as cid,c.*,cb.* FROM '.DB_PREFIX.'content c 
						LEFT JOIN '.DB_PREFIX.'contentbody cb ON c.id = cb.id 
						WHERE c.id IN ('.$ids.')';
					$query = $this->db->query($sql);
					$k = array();
					while ( $row = $this->db->fetch_array($query))
					{
						$k[$row['cid']] = $row;
					}
					$materials = $this->get_materials($ids);
					$state = array();
					if (!empty($k))
					{
						foreach ($k as $key=>$val)
						{
							$val['pic'] = $materials[$key] ? $materials[$key][$val['material_id']] : '';				
							$return = $this->forward_road($val,$ret);
							$state[$key] = $return[0];
						}
					}
					//检查是否有失败的，失败的再次重新发送
					foreach ($state as $key=>$val)
					{					
						if (!$val)
						{
							$val['pic'] = $materials[$key] ? $materials[$key][$val['material_id']] : '';
							$kk = $this->forward_road($k[$key]);
							$state[$key] = $kk[0];
						}
					}
					$new_state = array();
					foreach ($state as $key=>$val)
					{
						if ($val)
						{
							$new_state[] = $key; 
						}
					}
					
					$this->update_contribute(implode(',', $new_state));
				}
			}
		}
		return true;
	}
	
	private function forward_road($data)
	{
		
		//hg_pre($ret);exit();
		$curl = new curl($this->settings['App_road']['host'],$this->settings['App_road']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		$curl->addRequestData('content',$data['text']);
		$curl->addRequestData('longitude',$data['longitude']);
		$curl->addRequestData('latitude',$data['latitude']);
		$curl->addRequestData('pic', $data['pic']);
		if ($data['pic'])
		{
			foreach ($data['pic'] as $key=>$val)
			{
				$curl->addRequestData("pic[$key]", $val);
			}
		}
		$ret = $curl->request($this->settings['App_road']['filename']);
		return $ret;
	}
	private function get_materials($ids)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			//目前只处理图片
			if (!$row['vodid'])
			{
				$k[$row['content_id']][$row['materialid']] = array(
					'id'=>$row['original_id'],
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'filepath'=>$row['material_path'],
					'filename'=>$row['pic_name'],
				);
			}
			
		}
		return $k;
	}
	private function update_contribute($ids)
	{
		$sql = 'UPDATE '.DB_PREFIX.'content SET is_road=1 WHERE id IN ('.$ids.')';
		$query= $this->db->query($sql);
		return true;
	}

	public function access_sync($data,$id)
	{
		if(!empty($data) && is_array($data))
		{
			$sql = "UPDATE ".DB_PREFIX."content SET ";
			$space = '';
			foreach($data as $k => $v)
			{
				$sql.= $space . $k ."='".$v."'";
				$space = ',';
			}
			$sql .= " WHERE id = " . $id;
			$this->db->query($sql);
			$sql = "SELECT audit,expand_id FROM ".DB_PREFIX."content WHERE id = " . $id;
			$info = $this->db->query_first($sql);
			if($info['audit'] == 2)
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
	public function all_pic($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE  1 AND vodid="" AND content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k =array();
		while ($row = $this->db->fetch_array($query))
		{		
			$k[] = array(
				'content_id'=>$row['content_id'],
				'material_id'=>$row['materialid'],
				'host'=>$row['host'],
				'dir'=>$row['dir'],
				'filepath'=>$row['material_path'],
				'filename'=>$row['pic_name'],
			);
		}
		return $k;
	}
	//爆料的转发
	public function send_contribute($id,$flag = 0)
	{
		//获取已转发的数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'content_forward WHERE cid IN ('.$id.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['cid']][] = $row['fid'];
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$id.')';
		$query = $this->db->query($sql);
		$sort = array();
		$sorts = array();
		$forward_id = array();
		$forward = array();
		$info = array();
		$relation = array(); 
		$return = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[$row['id']] = $row['sort_id'];
		}
		if (!empty($sorts))
		{
			$sort = array_unique($sorts);
			$sql = 'SELECT * FROM '.DB_PREFIX.'forward WHERE 1 AND is_open=1 AND sort_id IN ('.implode(',', $sort).')';
			if ($flag==0)
			{
				$sql .= ' AND direct_forward = 0';
			}
			if ($flag==1)
			{
				$sql .= ' AND direct_forward = 1'; 
			}
			$query = $this->db->query($sql);
			while ($row = $this->db->fetch_array($query))
			{
				$protocol = '';
				$request_type = '';
				$protocol = $this->settings['con_api_protocol'][$row['protocol']];
				$row['protocol'] = $protocol ? $protocol : 'HTTP';
				$request_type = $this->settings['con_request_type'][$row['request_type']];
				$row['request_type'] = $request_type ? strtolower($request_type) : 'post';
				$row['match_rule'] = unserialize($row['match_rule']);
				$forward[$row['sort_id']][$row['id']] = $row;
			}
			if (!empty($forward))
			{
				foreach ($sorts as $key=>$val)
				{
					if (in_array($val, array_keys($forward)))
					{
						$forward_id[$key] = $val;
					}
				}
				if (!empty($forward_id))
				{
					//获取满足条件的爆料
					$info = $this->get_contribute(implode(',', array_keys($forward_id)));
					foreach ($forward_id as $key=>$val)
					{
						$data = '';
						$data = $info[$key];
						
						if (!empty($data))
						{	
							foreach ($forward[$val] as $kk=>$vv)
							{						
								if ((is_array($k[$key]) && !in_array($kk, $k[$key])) || !$k[$key] || empty($k[$key]))
								{
									$ret = $this->forward_curl($data, $vv);
									if ($ret)
									{
										$return[] = array(
											'cid'=>$key,
											'fid'=>$vv['id'],
											'rid'=>addslashes($ret['id']),
										);
									}
								}
							
								
							}						
						}
					}
				}
			}
		}
		//转发关系入库
		if (!empty($return) && is_array($return))
		{
			foreach ($return as $key=>$val)
			{
				$this->content_forward_create($val);
			}
		}
		return true;
	}
	private function get_contribute($ids)
	{
		$sql = 'SELECT c.id AS cid,c.*,s.name,m.original_id,m.host,m.dir,m.material_path,m.pic_name,m.mtype,cb.* FROM '.DB_PREFIX.'content c  
				LEFT  JOIN  '.DB_PREFIX.'sort s ON c.sort_id = s.id 
				LEFT JOIN '.DB_PREFIX.'materials m ON m.materialid = c.material_id
				LEFT JOIN '.DB_PREFIX.'contentbody cb ON cb.id = c.id
				WHERE 1 AND c.id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$temp =  array();
			$temp['title'] = $row['title'];
			$temp['brief'] = $row['brief'];
			$temp['index_pic'] = array(); 
			if ($row['host'] && $row['dir'] && $row['material_path'] && $row['pic_name'])
			{
				$temp['index_pic'] = array(
					'id'=>$row['original_id'],
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'filepath'=>$row['material_path'],
					'filename'=>$row['pic_name'],
					'type'=>$row['mtype'],
				);
			}
			$temp['longitude'] = $row['longitude'];
			$temp['latitude'] = $row['latitude'];
			$temp['content'] = $row['text'];
			$temp['user_name'] = $row['user_name'];
			$k[$row['cid']] = $temp;
		}
		//获取图片和视频
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['vodid'])
			{
				$k[$row['content_id']]['video'] = array(
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'id'=>$row['vodid'],
					'type'=>$row['mtype'],
				);
			}else 
			{
				if ($row['host'] && $row['dir'] && $row['material_path'] && $row['pic_name'])
				{
					$k[$row['content_id']]['picture'][] = array(
						'id'=>$row['original_id'],
						'host'=>$row['host'],
						'dir'=>$row['dir'],
						'filepath'=>$row['material_path'],
						'filename'=>$row['pic_name'],
						'type'=>$row['mtype'],
					);
				}
			}
		}
		return $k;	
	}
	//转发
	private function forward_curl($data,$config)
	{
		$curl = new curl($config['host'],$config['dir']);
		$curl->setSubmitType($config['request_type']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		if ($config['match_rule']['way'] &&is_array($config['match_rule']['way']) && !empty($config['match_rule']['way']))
		{
		   foreach ($config['match_rule']['way'] as $key=>$val)
		   {
		   		if ($val ==1)
		   		{
		   			if ($config['match_rule']['dict'][$key] && $config['match_rule']['mark'][$key])
		   			{
				   		
		   				if ($config['match_rule']['dict'][$key]=='index_pic')
						{
							foreach ($data['index_pic'] as $kk=>$vv)
							{
								$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']',$vv);
							}
						}elseif ($config['match_rule']['dict'][$key]=='video')
						{
							foreach ($data['video'] as $kk=>$vv)
							{
								$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']',$vv);
							}
						}elseif ($config['match_rule']['dict'][$key]=='picture')
						{
							foreach ($data['picture'] as $kk=>$vv)
							{
								foreach ($vv as $kkk=>$vvv)
								{
									$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']'.'['.$kkk.']',$vvv);
								}		
							}
						}else {					
							$curl->addRequestData($config['match_rule']['mark'][$key],$data[$config['match_rule']['dict'][$key]]);
						}
		   			}
		   		}elseif ($val==2)
		   		{
		   			if ($config['match_rule']['value'][$key] && $config['match_rule']['mark'][$key])
		   			{
		   				$curl->addRequestData($config['match_rule']['mark'][$key],$config['match_rule']['value'][$key]);
		   			}		
		   		}
		   }
		}
		$ret = $curl->request($config['filename']);
		if (is_array($ret) && !empty($ret))
		{
			return $ret[0];
		}else {
			return false;
		}		
	}
	//内容和转发关系表
	private function content_forward_create($data)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.'content_forward SET ';
		foreach ($data as $key=>$val)
		{
			$sql.= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		return true;	
	}
	//删除转发的数据
	public function del_send_contribute($ids,$flag=0)
	{
		//获取内容和转发的关系表
		//$sql = 'SELECT * FROM '.DB_PREFIX.'content_forward WHERE cid IN ('.$ids.')';
		$sql = 'SELECT cf.*,f.direct_forward FROM '.DB_PREFIX.'content_forward cf 
				LEFT JOIN '.DB_PREFIX.'forward f ON cf.fid = f.id WHERE cf.cid IN ('.$ids.')';
		$query=$this->db->query($sql);
		$relation = array();
		$fid = array();
		$rid = array();
		$del = array();
		$forward = array();
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['direct_forward'] != 1)
			{
				$fid[] = $row['fid'];
				$rid[$row['fid']][] = $row['rid']; 
				$k[$row['id']] = $row;
			}
		
		}
		if (!empty($fid))
		{
			$fid = array_unique($fid);
			//获取所有的配置
			$forward = $this->get_forward(implode(',', $fid));
			/*
			$sql = 'SELECT * FROM '.DB_PREFIX.'forward WHERE id IN ('.implode(',', $fid).')';
			$query = $this->db->query($sql);
			while ($row = $this->db->fetch_array($query))
			{
				$protocol = '';
				$request_type = '';
				$protocol = $this->settings['con_api_protocol'][$row['protocol']];
				$row['protocol'] = $protocol ? $protocol : 'HTTP';
				$request_type = $this->settings['con_request_type'][$row['request_type']];
				$row['request_type'] = $request_type ? strtolower($request_type) : 'post';
				$row['match_rule'] = unserialize($row['match_rule']);
				$forward[$row['id']] = $row;
			}
			*/
			if (!empty($forward))
			{
				foreach ($rid as $key=>$val)
				{
					$return = $this->del_forward_curl($forward[$key], implode(',', $val));
				}
			}
			$this->content_forward_delete(implode(',', array_keys($k)));
		}
		return true;
	}
	
	//根据配置id获取配置
	private function get_forward($ids)
	{
		//获取所有的配置
		$sql = 'SELECT * FROM '.DB_PREFIX.'forward WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$forward = array();
		while ($row = $this->db->fetch_array($query))
		{
			$protocol = '';
			$request_type = '';
			$protocol = $this->settings['con_api_protocol'][$row['protocol']];
			$row['protocol'] = $protocol ? $protocol : 'HTTP';
			$request_type = $this->settings['con_request_type'][$row['request_type']];
			$row['request_type'] = $request_type ? strtolower($request_type) : 'post';
			$row['match_rule'] = unserialize($row['match_rule']);
			$forward[$row['id']] = $row;
		}
		
		return $forward;
	}
	private function del_forward_curl($config,$ids)
	{
		$curl = new curl($config['host'],$config['dir']);
		$curl->setSubmitType($config['request_type']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','delete');
		$curl->addRequestData('id',$ids);
		$ret = $curl->request($config['filename']);
		return $ret;
	}
	//内容和转发关系表
	private function content_forward_delete($ids)
	{
		if ($ids)
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'content_forward WHERE id IN ('.$ids.')';
			$this->db->query($sql);
			return true;	
			
		}		
	}	
	//更新转发的数据
	public function update_send_contribute($ids)
	{
		if ($ids)
		{
			//检索配置
			$sql = 'SELECT * FROM '.DB_PREFIX.'content_forward WHERE cid IN ('.$ids.')';
			$query = $this->db->query($sql);
			$config = array();
			$cid = array();
			$fid = array();
			$data = array();
			$forward = array();
			$arr = array();
			while ($row = $this->db->fetch_array($query))
			{
				$config[$row['id']] = $row['cid'];
				$cid[$row['cid']][] = $row['fid'];
				$fid[$row['fid']][] = $row['rid'];
			}
			if (!empty($config))
			{
				$data = $this->get_contribute(implode(',', $config));
				foreach ($cid as $kkk=>$vvv)
				{
					foreach ($vvv as $vvvv)
					{
						$arr[] = $vvvv; 
					}
				}
				$arr = array_unique($arr);
				$forward = $this->get_forward(implode(',', $arr));
				$config = array_unique($config);
				foreach ($config as $key=>$val)
				{
					foreach ($cid[$val] as $kk=>$vv)
					{
						$this->update_forward_curl($data[$val], $forward[$vv], implode(',', $fid[$vv]));
					}
				}
			}
		}
		return true;
	}
	private function update_forward_curl($data,$config,$ids)
	{
		$curl = new curl($config['host'],$config['dir']);
		$curl->setSubmitType($config['request_type']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','update');
		$curl->addRequestData('id',$ids);
		if ($config['match_rule']['way'] &&is_array($config['match_rule']['way']) && !empty($config['match_rule']['way']))
		{
		   foreach ($config['match_rule']['way'] as $key=>$val)
		   {
		   		if ($val ==1)
		   		{
		   			if ($config['match_rule']['dict'][$key] && $config['match_rule']['mark'][$key])
		   			{
				   		
		   				if ($config['match_rule']['dict'][$key]=='index_pic')
						{
							foreach ($data['index_pic'] as $kk=>$vv)
							{
								$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']',$vv);
							}
						}elseif ($config['match_rule']['dict'][$key]=='video')
						{
							foreach ($data['video'] as $kk=>$vv)
							{
								$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']',$vv);
							}
						}elseif ($config['match_rule']['dict'][$key]=='picture')
						{
							foreach ($data['picture'] as $kk=>$vv)
							{
								foreach ($vv as $kkk=>$vvv)
								{
									$curl->addRequestData($config['match_rule']['mark'][$key][$kk].'['.$kkk.']',$vvv);
								}		
							}
						}else {					
							$curl->addRequestData($config['match_rule']['mark'][$key],$data[$config['match_rule']['dict'][$key]]);
						}
		   			}
		   		}elseif ($val==2)
		   		{
		   			if ($config['match_rule']['value'][$key] && $config['match_rule']['mark'][$key])
		   			{
		   				$curl->addRequestData($config['match_rule']['mark'][$key],$config['match_rule']['value'][$key]);
		   			}		
		   		}
		   }
		}
		$ret = $curl->request($config['filename']);
		if (is_array($ret) && !empty($ret))
		{
			return $ret[0];
		}else {
			return false;
		}		
	}
	//审核爆料时同时审核视频
	public function video_audit($ids,$audit)
	{
		if ($ids)
		{
			$sql = 'SELECT vodid FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.') AND vodid !=""';
			$query = $this->db->query($sql);
			$k = array();
			while ($row = $this->db->fetch_array($query))
			{
				$k[] = $row['vodid'];
			}
			if (!empty($k))
			{
				$curl = new curl($this->settings['APP_livmedia']['host'],$this->settings['APP_livmedia']['dir']);
				$curl->setSubmitType('post');
				$curl->setReturnFormat('json');
				$curl->initPostData();
				$curl->addRequestData('a', 'audit');
				$curl->addRequestData('id', implode(',', $k));
				$curl->addRequestData('audit',$audit);
				$ret = $curl->request('vod_update.php');
				if ($ret && is_array($ret))
				{
					return true;
				}
			}
		}
	}
	//通过分类id 查询转发信息
	public function check_sort($id,$flag=0)
	{
		if (isset($id))
		{
			$sql = 'SELECT sort_id FROM '.DB_PREFIX.'content WHERE id = '.$id;
			$ret = $this->db->query_first($sql);
			if ($ret['sort_id'])
			{
				$sql = 'SELECT title FROM '.DB_PREFIX.'forward WHERE sort_id = '.$ret['sort_id'].' AND is_open =1';
				if ($flag==0)
				{
					$sql .= ' AND direct_forward = 0';
				}
				if ($flag==1)
				{
					$sql .= ' AND direct_forward = 1';
				}
				$query = $this->db->query($sql);
				$k = array();
				$return = '';
				while ($row = $this->db->fetch_array($query))
				{
					$k[] = $row['title'];
				}
				if (!empty($k))
				{
					$return = implode(',', $k);
				}
				if ($return)
				{
					return $return;
				}else {
					return false;
				}
				
			}else {
				return false;
			} 
			
		}else 
		{
			return false;
		}	
	}
	
	//获取分类信息
	public function getSortInfor($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'sort WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['id']] = $row;
		}
		return $k;	
	}
	
}