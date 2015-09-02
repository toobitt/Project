<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once(ROOT_PATH . 'lib/class/opinion.class.php');
require_once(CUR_CONF_PATH . 'lib/contribute.class.php');
include_once(ROOT_PATH . 'lib/class/recycle.class.php');
include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
define('MOD_UNIQUEID','reporter_con');//模块标识
class contribute_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	
		$this->recycle = new recycle();
		$this->opinion = new opinion();
		$this->con = new contribute();
		$this->publish_column = new publishconfig();
		$this->mNodes = array(
			'reporter_node'	=> '记者列表',
		);
		$this->mPrmsMethods['back'] =  array(
										'name' => '打回',
										'node' => true,
									);
		$this->mPrmsMethods['update_indexpic'] = array(
										'name' => '更新索引图',
										'node' => true,
										);
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = $this->input['id'];
		
		//爆料信息表
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$conInfor = array();
		$colunmIds = array();
		$sorts = array();
		while($row = $this->db->fetch_array($query))
		{
			$result[] = $row['id'];
			$sorts[] =$row['sort_id'];
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['title'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['content'] = $row;
			//准备删除发布系统上的数据
			$colunm_id = unserialize($row['column_id']);
			if (!empty($colunm_id) && is_array($colunm_id) )
			{
				$colunmIds[$row['id']] = array_keys($colunm_id);
			}
			$conInfor[$row['id']] = $row;
		}
		
		/**************权限控制开始**************/
		//节点验证
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN ('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			$nodes =array();
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes']['reporter_node'][$row['id']] = $row['parents'];
			}
			if (!empty($nodes))
			{
				$this->verify_content_prms($nodes);
			}
			else 
			{
				$nodes['nodes']['reporter_node'][0] = 0;
				$this->verify_content_prms($nodes);
			}
		}
		//能否修改他人数据
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
		/**************权限控制结束**************/
		/**********************添加日志************************/
		if (!empty($conInfor))
		{
			$this->addLogs('删除报料', $conInfor,'');
		}
		/**********************添加日志***********************/
		//内容表
		$sql = "SELECT * FROM " . DB_PREFIX . "contentbody WHERE id IN(" . $ids .")";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$data2[$row['id']]['content']['contentbody'] = $row;
		}
		
		//素材表
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$data2[$row['content_id']]['content']['materials'][$row['materialid']] = $row; 
		}
		
		if(is_array($data2) && count($data2))
		{
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
			//放入回收站结束
		}
		if($res['sucess'])
		{	
			//删除发布中心数据		
			if (!empty($colunmIds) && is_array($colunmIds))
			{
				foreach ($colunmIds as $key=>$value)
				{
					$op = 'delete';
					$this->con->publish_insert_query($key, $op, $value);
				}
			}
			//删除转发数据
			$this->con->del_send_contribute($ids);
			//删除信息表
			$this->con->del_content($ids);
			//删除内容表
			$this->con->del_contentbody($ids);
			//删除素材表
			$this->con->del_materials($ids);		
		}
		$this->addItem('sucess');
		$this->output();
	}	
	//彻底删除
	public function delete_comp()
	{
		return true;
	}
	function update()
	{
		if(!$this->input['id'])
		{
			return;
		}
		$id = intval($this->input['id']);
		if (!$this->input['content'])
		{
			$this->errorOutput('内容不能为空');
		}
		//查询修改文章之前的数据
		$sql = "SELECT * FROM " . DB_PREFIX ."content WHERE id = " . $id;
		$ret = $this->db->query_first($sql);
		$preData = $ret;
		$ret['column_id'] = unserialize($ret['column_id']);
		$old_column_id = array();
		if(is_array($ret['column_id']))
		{
			$old_column_id = array_keys($ret['column_id']);
		}
		
		$data = array(
					'title'=>addslashes(trim(urldecode($this->input['title']))),
					'sort_id'=>urldecode($this->input['sort_id']),
					'brief'=>addslashes(trim(urldecode($this->input['brief']))),
					'update_time'=>TIMENOW,
					'column_id' => $this->input['column_id'],
					'user_id'=>$this->input['user_id'],
					'user_name'=>$this->input['user_name'],
					'longitude'=>$this->input['longitude'],
					'latitude'=>$this->input['latitude'],
				);
		$data['title'] = $data['title'] ? $data['title'] : addslashes(hg_cutchars($this->input['content'],20));
		$data['brief'] = $data['brief'] ? $data['brief'] : addslashes(hg_cutchars($this->input['content'],20));
		
		
		/**************权限控制开始**************/
		//节点权限
		//修改前
		if($ret['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfo = $this->db->query_first($sql);
			$node['nodes']['reporter_node'][$sortInfo['id']] = $sortInfo['parents'];
		}
		else
		{
			$node['nodes']['reporter_node'] = array(0=>0);
		}
		$this->verify_content_prms($node);
		
		//修改后
		if($data['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id ='.$data['sort_id'];
			$sortInfo = $this->db->query_first($sql);
			$node['nodes']['reporter_node'][$sortInfo['id']] = $sortInfo['parents'];
		}
		else
		{
			$node['nodes']['reporter_node'] = array(0=>0);
		}
		$this->verify_content_prms($node);
		
		
		//发布权限
		$published_column_id = !empty($old_column_id) ? implode(',', $old_column_id) : '';
		if ($published_column_id)
		{
			$arr = array(
				'column_id'=> $this->input['column_id'],
				'_action'=>'publish',
				'published_column_id'=>$published_column_id,
			);
			$this->verify_content_prms($arr);
		}
		//能否修改他人数据
		$arr = array(
			'id'=>$id,
			'user_id'=>$ret['user_id'],
			'org_id'=>$ret['org_id'],
		);
		$this->verify_content_prms($arr);
		//修改审核数据后的状态
		if ($ret['audit']==2 && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			
			if ($this->user['prms']['default_setting']['update_audit_content']==0)
			{
				$data['audit'] = intval($this->input['audit']);
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==1)
			{
				$data['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
			{
				$data['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
			{
				$data['audit'] = 3;
			}
		}
		//修改发布后数据的状态
		if ($ret['expand_id'] && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_publish_content']==0)
			{
				$data['audit'] = intval($this->input['audit']);
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==1)
			{
				$data['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==2)
			{
				$data['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==3)
			{
				$data['audit'] = 3;
			}
		}
		/**************权限控制结束**************/
		
		
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$data['column_id']);
		//更新视频库的视频状态
		switch ($data['audit'])
		{
			case 2:$this->con->video_audit($id, 1);break;
			case 3:$this->con->video_audit($id, 0);break;
			default:$this->con->video_audit($id, 0);
		}
		$data['column_id'] = addslashes(serialize($column_id));
		//更新爆料主表
		$this->con->update_content($data, $id);
		//爆料内容表
		$content = addslashes($this->input['content']);
		$this->con->update_contentbody($content, $id);
		//如果有审核意见，进入审核意见系统
      	if (isset($this->input['opinion']))
      	{
      		$opinion = addslashes(trim(urldecode($this->input['opinion'])));
      		$this->opinion->addOpinion($id, $opinion);
      	}
      	//用户信息
      	$userinfo = array(
      		'con_id'=>intval($this->input['id']),
      		'tel'=>intval($this->input['tel']),
      		'email'=>addslashes($this->input['email']),
      		'addr'=>addslashes($this->input['addr']),
			'money'=>addslashes($this->input['money']),
      		'is_bounty'=>$this->input['is_bounty']?1:0,
      	);
      	$this->con->user_info($userinfo);
      	//转发路况
      	/*
      	if ($this->settings['App_road']['sort_id'])
      	{
      		$this->con->to_road($id);
      	}*/
      	//准备更改发布内容
      	$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
      	$ret = $this->db->query_first($sql);
      	$newData = $ret;
      	
      	/********************添加日志******************/
      	$this->addLogs('更新报料',$preData, $newData);
      	/********************添加日志******************/
		//更改文章后发布的栏目
		$ret['column_id'] = unserialize($ret['column_id']);
		$new_column_id = array();
		if(is_array($ret['column_id']))
		{
			$new_column_id = array_keys($ret['column_id']);
		}
		if($ret['audit'] == 2)
		{
			$this->con->update_send_contribute($id);
			if(!empty($ret['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($old_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->con->publish_insert_query($id, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$old_column_id);
				if(!empty($add_column))
				{
					$this->con->publish_insert_query($id, 'insert',$add_column);
				}
				$same_column = array_intersect($old_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->con->publish_insert_query($id, 'update',$same_column);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				$this->con->publish_insert_query($id,$op);
			}
		}
		else    
		{
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				$this->con->publish_insert_query($id,$op);
			}
		}
		$this->addItem('sucess');
		$this->output();
	}
	/**
	 * 增加投稿
	 */
	function create()
	{
		//添加爆料主表
		$data = array(
					'title'=>trim(urldecode($this->input['title'])),
					'brief'=>addslashes(trim(urldecode($this->input['brief']))),
					'appid'=>$this->user['appid'],
					'client'=>$this->user['display_name'],
		 			'longitude'=>trim(urldecode($this->input['longitude'])),
		 			'latitude'=>trim(urldecode($this->input['latitude'])),
					'create_time'=>TIMENOW,
					'user_id'=>$this->input['user_name']? 0 : $this->user['user_id'],
					'user_name'=>addslashes($this->input['user_name'])?addslashes($this->input['user_name']):addslashes($this->user['user_name']),
					'audit'=>1,
		 			'sort_id'=>trim(urldecode($this->input['sort_id'])),
					'org_id'=>$this->user['org_id'],			 	
		);	
		$content = addslashes(trim(urldecode($this->input['content'])));
		if (!$content)
		{
			$this->errorOutput('请输入投稿内容');
		}
		
		/**************权限控制开始**************/
		//节点权限
		if($data['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id = '.$data['sort_id'];
			$sort = $this->db->query_first($sql);
			$nodes['nodes']['reporter_node'][$sort['id']] = $sort['parents'];
		}
		else
		{
			$nodes['nodes']['reporter_node'] = array(0=>0);
		}
		$this->verify_content_prms($nodes);
		//创建数据后的审核状态
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['create_content_status']==1)
			{
				$data['audit'] = 1;
			}elseif ($this->user['prms']['default_setting']['create_content_status']==2)
			{
				$data['audit'] = 2;
			}
		}

		/**************权限控制结束**************/	

		if (!$data['sort_id'])
		{
			$data['sort_id'] = 0; 
		}else{
			//获取该分类下的发布栏目
			$sortInfor = $this->con->getSortInfor($data['sort_id']);
			if (!empty($sortInfor))
			{
				$data['column_id'] = addslashes($sortInfor[$data['sort_id']]['column_id']);
			}
			
		}
		if (!$data['title'])
		{
			$data['title'] = hg_cutchars($content,20);
		}
		if (!$data['brief'])
		{
			$data['brief'] = hg_cutchars($content,100);
		}	
		$contribute_id = $this->con->add_content($data);
		
		/****************添加日志*****************/
		if ($contribute_id)
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$contribute_id;
			$ret = $this->db->query_first($sql);
			$this->addLogs('添加报料', '', $ret);
		}
		/****************添加日志****************/
		
		//添加内容表	
		$body = array(
			'id'=>$contribute_id,
			'text'=>$content
		);	
		$this->con->add_contentbody($body);
		$userinfo = array();
		//用户信息
		if ($this->input['user_name'])
		{
	      	$userinfo = array(
	      		'con_id'=>intval($contribute_id),
	      		'tel'=>$this->input['tel'],
	      		'email'=>addslashes($this->input['email']),
	      		'addr'=>addslashes($this->input['addr']),
	      	);
		}elseif ($this->user['user_id'] && !$this->input['user_name'])
		{
			$return = $this->con->get_userinfo_by_id($this->user['user_id']);
			if (!empty($return))
			{
				$userinfo = array(
					'con_id'=>intval($contribute_id),		      		
		      		'tel'=>$return['mobile'],
		      		'email'=>$return['email'],
		      		'addr'=>$return['address'],
				);
			}
		}
		if (!empty($userinfo))
		{
			$this->con->user_info($userinfo);		
		}
		//图片上传
		if ($_FILES['photos'])
		{
			$count = count($_FILES['photos']['error']);
			for($i = 0;$i<=$count;$i++)
			{
				if ($_FILES['photos']['error'][$i]===0)
				{
					$pics = array();
					foreach($_FILES['photos'] AS $k =>$v)
					{
						$pics['Filedata'][$k] = $_FILES['photos'][$k][$i];
					}
					//插入图片服务器
					$ret = $this->con->uploadToPicServer($pics, $contribute_id);
					//准备入库数据
					$arr = array(
							'content_id'=>$contribute_id,
							'mtype'=>$ret['type'],						
							'original_id'=>$ret['id'],
							'host'=>$ret['host'],
							'dir'=>$ret['dir'],
							'material_path'=>$ret['filepath'],
							'pic_name'=>$ret['filename'],
					);
					$id = $this->con->upload($arr);
					//默认第一张图片为索引图
					if (!$indexpic)
					{
						$indexpic = $this->con->update_indexpic($id, $contribute_id);
					}					
				}
			}
		}		
		//视频上传
		if ($_FILES['videofile'])
		{
			//上传视频服务器
			$videodata = $this->con->uploadToVideoServer($_FILES, $data['title'], $data['brief']);
			//有视频没有图片时，将视频截图上传作为索引图
			if (!$indexpic)
			{			
				$url = $videodata['img']['host'].$videodata['img']['dir'].$videodata['img']['filepath'].$videodata['img']['filename'];
				$material = $this->con->localMaterial($url, $contribute_id);
				$arr = array(
						'content_id'=>$contribute_id,
						'mtype'=>$material['type'],
						'original_id'=>$material['id'],
						'host'=>$material['host'],
						'dir'=>$material['dir'],
						'material_path'=>$material['filepath'],
						'pic_name'=>$material['filename'],
				);
				$indexpic = $this->con->upload($arr);
				$this->con->update_indexpic($indexpic, $contribute_id);
			}
			//视频入库
			$arr = array(
						'content_id'=>$contribute_id,
						'mtype'=>$videodata['type'],
						'host'=>$videodata['protocol'].$videodata['host'],
						'dir'=>$videodata['dir'],
						'vodid'=>$videodata['id'],
						'filename'=>$videodata['file_name'],
					);
					
			$this->con->upload($arr);
		}
		if ($contribute_id)
		{
			$this->con->send_contribute($contribute_id,$flag=1);	
		}
		$this->addItem($contribute_id);
		$this->output();
	}	
	/**
	 * 改变审核状态
	 * 1为未审核状态  2为审核状态
	 */
	public function stateAudit()
	{
		if (!intval($this->input['audit']) || !intval($this->input['id']))
		{
			$this->errorOutput(NOID);
		}
		/**************权限控制开始**************/	
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		if ($ret['sort_id'])
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfor = $this->db->query_first($sql);
			
		}
		/**************权限控制结束**************/	
		
		
		if (intval($this->input['audit']) == 1){
			/**************权限控制开始**************/	
			$node['_action'] = 'audit';
			if ($sortInfor)
			{
				$node['nodes']['reporter_node'][$sortInfor['id']] = $sortInfor['parents'];
				
			}else {
				$node['nodes']['reporter_node']= array(0=>0);
			}
			$this->verify_content_prms($node);
			/**************权限控制结束**************/	
			$state = 2;
			$this->con->send_contribute(intval($this->input['id']));
			//审核视频
			$this->con->video_audit(intval($this->input['id']), 1);
			//$this->con->to_road($this->input['id']);
		}
		if (intval($this->input['audit']) == 2){
			
			/**************权限控制开始**************/	
			$node['_action'] = 'back';
			if ($sortInfor)
			{
				$node['nodes']['reporter_node'][$sortInfor['id']] = $sortInfor['parents'];
			}else {
				$node['nodes']['reporter_node'] = array(0=>0);
			}
			$this->verify_content_prms($node);
			/**************权限控制结束**************/	
			
			
			$this->con->del_send_contribute(intval($this->input['id']));
			//打回视频
			$this->con->video_audit(intval($this->input['id']), 0);
			$state = 3;
		}
		if (intval($this->input['audit']) == 3){
			/**************权限控制开始**************/	
			$node['_action'] = 'audit';
			if ($sortInfor)
			{
				$node['nodes']['reporter_node'][$sortInfor['id']] = $sortInfor['parents'];
				
			}else {
				$node['nodes']['reporter_node'] = array(0=>0);
			}
			$this->verify_content_prms($node);
			/**************权限控制结束**************/	
			$this->con->send_contribute(intval($this->input['id']));
			//审核视频
			$this->con->video_audit(intval($this->input['id']), 1);
			$state = 2;
		}
		$ret = $this->con->changeAudit($state, intval(urldecode($this->input['id'])));
		$this->addItem($ret);
		$this->output();
	}
	/**
	 * 批量审核
	 * 1为未审核状态  2为审核状态
	 */
	public function audit()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = $this->input['id'];
		/**************权限控制开始**************/	
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$userInfor = array();
		$sorts = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$userInfor[] = $row;
		}
		$nodes = array();
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes']['reporter_node'][$row['id']] = $row['parents'];
			}
			
		}
		if (empty($nodes))
		{
			$nodes['nodes']['reporter_node'] = array(0=>0);
		}
		$this->verify_content_prms($nodes);
		if (!empty($userInfor))
		{
			foreach ($userInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
		/**************权限控制结束**************/	
		
		$ret = $this->con->audit($ids);
		$arr = explode(',', $ret);
		$this->con->send_contribute($ids);
		//审核视频
		$this->con->video_audit($ids, 1);
		$this->addItem($arr);
		$this->output();

	}
	/**
	 * 批量打回
	 * 1为未审核状态  2为审核状态
	 */
	public function back()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = $this->input['id'];
		
		/**************权限控制开始**************/	
		$sql = 'SELECT sort_id FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$sorts = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
		}
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			$nodes = array();
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes']['reporter_node'][$row['id']] = $row['parents'];
			}
			if (!empty($nodes))
			{
				$this->verify_content_prms($nodes);
			}else {
				$nodes['nodes']['reporter_node'] = array(0=>0);
				$this->verify_content_prms($nodes);
			}
		}
		/**************权限控制结束**************/
		
		$ret = $this->con->back($ids);
		$arr = explode(',', $ret);
		$this->con->del_send_contribute($ids);
		//打回视频
		$this->con->video_audit($ids, 0);
		$this->addItem($arr);
		$this->output();
	}
	/**
	 *
	 * swfupload  上传文件
	 */
	public function upload()
	{		
		if (!$this->settings['App_material'])
		{
			$this->errorOutput('图片服务器未安装！');
		}
		$id = intval($this->input['content_id']);
		
		/**************权限控制开始**************/	
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		//能否修改他人数据
		$userInfor = array(
			'_action'=>'update',
			'id'=>$id,
			'user_id'=>$ret['user_id'],
			'org_id'=>$ret['org_id'],
		);
		$this->verify_content_prms($userInfor);
		//节点权限
		if ($ret['sort_id'])
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfor = $this->db->query_first($sql);
		}	
		if ($sortInfor)
		{
			$node['nodes']['reporter_node'][$sortInfor['id']] = $sortInfor['parents'];
		}else {
			$node['nodes']['reporter_node'][0] = 0;
		}
		$node['_action'] = 'update';
		$this->verify_content_prms($node);
		/**************权限控制结束**************/
		
		$this->mater = new material();
		$material = $this->mater->addMaterial($_FILES,$id); //插入各类服务器
		if (!$material || empty($material))
		{
			$this->errorOutput('图片上传失败!');
		}
		$data = array(
					'content_id'	=> $id,
					'mtype'			=> $material['type'],
					'host'			=> $material['host'],
					'dir'			=> $material['dir'],
					'material_path' => $material['filepath'],
					'pic_name'		=> $material['filename'],
					'original_id'	=> $material['id']
				);
		$id = $this->con->upload($data);
		$material['pic'] = array(
								'host'		=> $material['host'],
								'dir'		=> $material['dir'],
								'file_path' => $material['filepath'],
								'file_name' => $material['filename'],	
							);
        $material['id'] = $id;
		//更新发布库和转发数据
		$ret = $this->con->detail($id);
		if ($ret['audit'] == 2)
		{
			if (!empty($ret['expand_id']))
			{
				$ret['column_id'] = array($ret['column_id']);
				$this->con->publish_insert_query($id, 'update',$ret['column_id']);
			}
			$this->con->update_send_contribute($id);			
		}
		$this->addItem($material);
		$this->output();

	}
	/**
	 * 删除图片
	 */
	public function del_material()
	{
		$mid =  $this->input['pic']; // 素材id
		if (!$mid)
		{
			$this->errorOutput(NOID);
		}
		/**************权限控制开始**************/	
		//获取报料id
		$sql = 'SELECT content_id FROM '.DB_PREFIX.'materials WHERE materialid = '.$mid;
		$material = $this->db->query_first($sql);
		$id = $material['content_id'];
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;		
		$ret = $this->db->query_first($sql);
		//能否修改他人数据
		$userInfor = array(
			'_action'=>'update',
			'id'=>$id,
			'user_id'=>$ret['user_id'],
			'org_id'=>$ret['org_id'],
		);
		$this->verify_content_prms($userInfor);
		//节点权限
		if ($ret['sort_id'])
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfor = $this->db->query_first($sql);
		}	
		if ($sortInfor)
		{
			$node['nodes']['reporter_node'][$sortInfor['id']] = $sortInfor['parents'];
		}else {
			$node['nodes']['reporter_node'][0] = 0;
		}
		$node['_action'] = 'update';
		$this->verify_content_prms($node);
		/**************权限控制结束**************/
		
		$ret = $this->con->del_pic($ids);
		if (!$ret)
		{
			$this->errorOutput('删除图片失败！');
		}
		//更新发布库和转发数据
		$ret = $this->con->detail($id);
		if ($ret['audit'] == 2)
		{
			if (!empty($ret['expand_id']))
			{
				$ret['column_id'] = array($ret['column_id']);
				$this->con->publish_insert_query($id, 'update',$ret['column_id']);
			}
			$this->con->update_send_contribute($id);			
		}
		$this->addItem($ret);
		$this->output();
	}
	/**
	 * 更新索引图
	 */
	public function update_indexpic()
	{
		if (!$this->input['content_id'] || !$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		/**************权限控制开始**************/	
		$mid = intval($this->input['id']);
		$id = intval($this->input['content_id']);
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		//能否修改他人数据
		$userInfor = array(
			'id'=>$id,
			'user_id'=>$ret['user_id'],
			'org_id'=>$ret['org_id'],
		);
		$this->verify_content_prms($userInfor);
		//节点权限
		if ($ret['sort_id'])
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfor = $this->db->query_first($sql);
		}	
		if ($sortInfor)
		{
			$node['nodes']['reporter_node'][$sortInfor['id']] = $sortInfor['parents'];
		}else {
			$node['nodes']['reporter_node'][0] = 0;
		}
		$this->verify_content_prms($node);
		/**************权限控制结束**************/
		
		$data = $this->con->update_indexpic($mid,$id);	
		//更新发布库和转发数据
		$ret = $this->con->detail($this->input['content_id']);
		if ($ret['audit']==2)
		{
			if (!empty($ret['expand_id']))
			{
				$ret['column_id'] = array($ret['column_id']);
				$this->con->publish_insert_query($this->input['content_id'], 'update',$ret['column_id']);
			}
			$this->con->update_send_contribute($this->input['content_id']);			
		}	
		$this->addItem($data);
		$this->output();

	}
	/**
	 * 即时发布
	 * @param id  int   文章id
	 * @param column_id string  发布的栏目id
	 */
	public function publish()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');
		}
		
		/**************权限控制开始**************/	
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id='.$id;
		$ret = $this->db->query_first($sql);
		//能否修改他人数据
		$userInfor = array(
			'id'=>$id,
			'user_id'=>$ret['user_id'],
			'org_id'=>$ret['org_id'],
		);
		$this->verify_content_prms($userInfor);
		//发布权限验证
		$ret['column_id'] = unserialize($ret['column_id']);
		$published_column_id = array();
		if(is_array($ret['column_id']))
		{
			$published_column_id = array_keys($ret['column_id']);
		}
		$this->verify_content_prms(array('column_id'=> $this->input['column_id'],'_action'=>'publish','published_column_id'=>$published_column_id));
		//节点权限
		if($ret['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN('.$ret['sort_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$data['nodes']['reporter_node'][$row['id']] = $row['parents'];
			}
		}
		else
		{
			$data['nodes']['reporter_node'][0] = 0;
		}
		$this->verify_content_prms($data);
		/**************权限控制结束**************/
		
		$ret = $this->con->publish();
		if(empty($ret))
		{
			$this->errorOutput('发布失败');
		}
		$this->addItem($ret);
		$this->output();
		
	}	
	
	/**
	 * 同步访问统计
	 */
	 function access_sync()
	 {
	 	if(!$this->input['id'])
	 	{
	 		$this->errorOutput('NOID');
	 	}
	 	$data = array();
	 	if($this->input['click_num'])
			$data['click_num'] = intval($this->input['click_num']);	 
		if($this->input['comm_num'])
			$data['comm_num'] = intval($this->input['comm_num']);
		if($this->input['share_num'])
			$data['share_num'] = intval($this->input['share_num']);	
		if($this->input['down_num'])
			$data['down_num'] = intval($this->input['down_num']);
		$return = $this->con->access_sync($data,intval($this->input['id']));
		$this->addItem($return);
		$this->output();
	 }
	 	
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	
	
	function sort()
	{
		$this->verify_content_prms();
		$ret = $this->drag_order('content', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	
}
$ouput= new contribute_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();