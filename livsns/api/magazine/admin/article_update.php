<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once('./article_sort_update.php');
define('MOD_UNIQUEID','maga_article');//模块标识

class ArticleUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/article.class.php');
		$this->obj = new ArticleClass();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 添加文章
	 */
	public function create()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		$maga_id = $this->input['maga_id'];
		$issue_id = $this->input['issue_id'];
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			/********创建数据上限判断**********/
			$create_data_limit = $this->user['prms']['default_setting']['create_data_limit'];
			if($create_data_limit)
			{
				$sql = "SELECT count(*) FROM ".DB_PREFIX."article WHERE user_id = ".$this->user['user_id'];
				$count = $this->db->query_first($sql);
				if($count['count']>$create_data_limit)
				{
					$this->errorOutput('您只能添加'.$create_data_limit.'条数据');
				}
			}
			//更新他人数据判断(对期刊的判断)
			$sql = 'SELECT user_id,org_id FROM '.DB_PREFIX.'issue WHERE id = '.$issue_id;
			$res = $this->db->query_first($sql);
		
			$info['id'] = $issue_id;
			$info['org_id'] = $res['org_id'];
			$info['user_id'] = $res['user_id'];
			$info['_action'] = 'manage_issue';
			$this->verify_content_prms($info);
			
			//更新他人数据判断（对杂志的判断）
			/*$sql = 'SELECT user_id,org_id FROM '.DB_PREFIX.'magazine WHERE id = '.$maga_id;
			$q = $this->db->query_first($sql);
			$info = array();
			$info['id'] = $maga_id;
			$info['org_id'] = $q['org_id'];
			$info['user_id'] = $q['user_id'];
			$info['_action'] = 'manage_issue';
			$this->verify_content_prms($info);
			*/
		}
		
		$content=str_replace("&nbsp;"," ",$this->input['content']);
		//将html实体转换为html标记，便于正则表达式匹配
		$content = html_entity_decode($content);

		//对分类做处理
		$this->manage_article_sort();
		
		/************************文章节点权限判断***************************/
		//$prms_sort_ids = $this->user['prms'][MOD_UNIQUEID]['create']['node']['article_node'];
		//if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_sort_ids && !in_array($this->input['sort_id'], $prms_sort_ids))
		//{
			//$this->errorOutput('没有权限在'.$this->input['sort'].'栏目下创建文章');
		//}
	
		$info = array();
		$info = array(
			'title' 			=> $this->input['title'],
			'tcolor' 			=> $this->input['tcolor'],
			'isbold' 			=> intval($this->input['isbold']),
			'isitalic' 			=> intval($this->input['isitalic']),
			'subhead' 			=> $this->input['subtitle'],
			'maga_id' 			=> $this->input['maga_id'],
			'issue_id' 			=> intval($this->input['issue_id']),
			'keywords' 			=> $this->input['keywords'],
			'brief' 			=> $this->input['brief'],
			'article_author' 	=> $this->input['author'],
			'redactor' 			=> $this->input['source'],
			'group_id' 			=> intval($this->input['sort_id']),
			'user_id' 			=> $this->user['user_id'],
			'org_id' 			=> $this->user['org_id'],
			'user_name' 		=> $this->user['user_name'],
			'create_time'		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
			'indexpic' 			=> intval($this->input['indexpic']),
			'water_id' 			=> intval($this->input['water_config_id']),
			'water_name' 		=> $this->input['water_config_name'],
			'column_id' 		=> $this->input['column_id'],
			//获取创建数据初始状态
			'state' 			=> $this->get_status_setting('create'),
		);
		
		//发布栏目id
		$info['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
		$info['column_id'] = serialize($info['column_id']);
		
		//文章内容大小
		$content_size = strlen($content.$info['title'].$info['subhead'].$info['article_author'].$info['keywords'].$info['redactor']);
		$info['article_size'] = $content_size;
		
		//文章表
		$sql = "INSERT INTO " . DB_PREFIX . "article SET ";
		$sql_extra = $space = '';
		foreach($info as $k => $v)
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
		$sql .= $sql_extra;
		$this->db->query($sql);
		$article_id = $this->db->insert_id();
		$info['id'] = $article_id;
		
		//内容表
		$sql = "INSERT INTO " . DB_PREFIX . "content(article_id,content) VALUES(" . $article_id . ",'" . $content . "')";
		$this->db->query($sql);
		
		//更新排序
		$update_sql = 'UPDATE '.DB_PREFIX.'article set order_id = '.$article_id.' WHERE id = '.$article_id;
		$this->db->query($update_sql);
		
		//更新文章计数
		if($info['group_id'])
		{
			$sql = "UPDATE ".DB_PREFIX."catalog SET cur_article_num = cur_article_num + 1 WHERE id = ".$info['group_id'];
			$this->db->query($sql);
		}
		
		//更新期刊封面文章
		$sql = 'SELECT cover_article FROM '.DB_PREFIX.'issue WHERE id ='.$info['issue_id'];
		$res = $this->db->query_first($sql);
		if(!$res['cover_article'])
		{
			$arr['id'] = $info['id'];
			$arr['title'] = $info['title'];
			$arr['subhead'] = $info['subhead'];
			$cover_article[] = $arr;
			$cover_article = serialize($cover_article);
			$sql = "UPDATE " . DB_PREFIX . "issue SET cover_article='" . $cover_article . "'  WHERE id = " . $info['issue_id'];
			$this->db->query($sql);
		}
		
	  	//更新素材表
	    $material_id=$this->input['material_id'];
	    if(!empty($material_id))
		{
			$material_history = explode(',',urldecode($this->input['material_history']));
			if(!empty($material_history))
			{
				$del_material = array_diff($material_history,$material_id);
			}
			$mid_str = implode(',',$material_id);
			if(!empty($del_material) && count($del_material)>1)
			{
				$del_material = implode(',',$del_material);
				$this->mater->delMaterialById($del_material,2); //根据素材ID来删除素材信息

				$sql="DELETE FROM " . DB_PREFIX . "material WHERE material_id IN(" . $del_material . ")";
				$this->db->query($sql);
			}
			$this->mater->updateMaterial($mid_str,$article_id);  //更新cid
			$sql = "UPDATE " . DB_PREFIX . "material SET cid=" . $article_id . " WHERE material_id IN (" . $mid_str . ")";
			$this->db->query($sql);
		}
		//查询添加得文章素材大小
		$sql = 'SELECT filesize FROM '.DB_PREFIX.'material WHERE cid ='.$article_id;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$material_size += $r['filesize'];
		}
		//添加文章得内容和素材大小总和
		$article_size = $content_size+$material_size;
		
		//更新杂志大小，文章数目
		$sql = "UPDATE " . DB_PREFIX . "issue SET article_num=article_num+1,issue_size=issue_size+" . $article_size . " WHERE id = " . intval($info['issue_id']);
		$this->db->query($sql);
		
		//放入发布队列
		$sql = "SELECT * FROM " . DB_PREFIX ."article WHERE id = " . $article_id;
		$r = $this->db->query_first($sql);
		if(intval($r['state']) == 1 && !empty($r['column_id']))
		{
			$op = 'insert';
			$this->obj->publish_insert_query($article_id,$op);
		}
		//添加日志
		$this->addLogs('创建文章','',$r,$r['title']);
		$this->addItem($info);
		$this->output();
	}
	/**
	 * 更新文章
	 */
	public function update()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		$article_id = intval($this->input['id']);
		if(empty($article_id))
		{
			$this->errorOutput("文章ID不能为空");
		}
		if(empty($this->input['title']))
		{
			$this->errorOutput("标题不能为空");
		}
		if(empty($this->input['content']))
		{
			$this->errorOutput('内容不能为空');
		}
		
		//查询修改文章之前已经发布到的栏目
		$sql = "select * from " . DB_PREFIX ."article where id = " . $article_id;
		$q = $this->db->query_first($sql);
		
		//更新前文章分类
		$old_group_id = $q['group_id'];
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		
		/**************修改他人数据权限判断**********************/
		$info['id'] = $article_id;
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$this->input['state'] = $q['state'];
		$info['column_id'] = $this->input['column_id'];
		$info['_action'] = 'manage_issue';
		$this->verify_content_prms($info);
		/**************结束***************/
		
		//对分类做处理
		$this->manage_article_sort();
		
		//更新状态判断
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//更新的节点权限
			//$prms_sort_ids = $this->user['prms'][MOD_UNIQUEID]['update']['node']['article_node'];
		
			//更新前的节点权限判断
			/*if($prms_sort_ids && !in_array($q['group_id'], $prms_sort_ids))
			{
				$this->errorOutput('没有权限更新此分类下的文章');
			}
			//更新后的分类权限判断
			if($prms_sort_ids && !in_array($this->input['sort_id'], $prms_sort_ids))
			{
				$this->errorOutput('没有权限更新'.$this->input['sort'].'栏目下文章');
			}*/
			
			//更新已发布内容权限判断
			$publish = $this->user['prms']['default_setting']['update_publish_content'];
			if($q['expand_id'] && $publish)
			{
				if($publish == 1)
				{
					$this->input['state'] = 0;
				}
				else if($publish == 2)
				{
					$this->input['state'] = 1;
				}
				else if($publish == 3)
				{
					$this->input['state'] = 2;
				}
			}
			else //已审核设置低于发布设置
			{
				//更新已审核内容权限判断
				$state = $this->user['prms']['default_setting']['update_audit_content'];
				if($q['state'] == 1 && $state)
				{
					if($state == 1)
					{
						$this->input['state'] = 0;
					}
					else if($state == 2)
					{
						$this->input['state'] = 1;
					}
					else if($state == 3)
					{
						$this->input['state'] = 2;
					}
				}
			}
		}
		
		$content=str_replace("&nbsp;"," ",$this->input['content']);
		//将html实体转换为html标记，便于正则表达式匹配
		
		$content = addslashes(html_entity_decode(trim($this->input['content'])));
		
		$info = array(
			'title' 			=> $this->input['title'],
			'tcolor' 			=> $this->input['tcolor'],
			'isbold' 			=> intval($this->input['isbold']),
			'isitalic' 			=> intval($this->input['isitalic']),
			'subhead' 			=> $this->input['subtitle'],
			'issue_id' 			=> intval($this->input['issue_id']),
			'keywords' 			=> $this->input['keywords'],
			'brief' 			=> urldecode($this->input['brief']),
			'article_author' 	=> $this->input['author'],
			'redactor'			=> $this->input['source'],
			'group_id' 			=> intval($this->input['sort_id']),
			//'user_id' 		=> $this->user['user_id'],
			//'org_id' 			=> $this->user['org_id'],
			//'user_name' 		=> $this->user['user_name'],
			//'update_time' 	=> TIMENOW,
			//'ip' 				=> hg_getip(),
			'indexpic' 			=> intval($this->input['indexpic']),
			'water_id' 			=> intval($this->input['water_config_id']),
			'water_name' 		=> $this->input['water_config_name'],
			'column_id' 		=> $this->input['column_id'],
			//'state' 			=> $this->input['state'],
		);
		
		//更改后的栏目id
		$info['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
		$info['column_id'] = serialize($info['column_id']);
		
		$content_size = strlen($content.$info['title'].$info['subhead'].$info['article_author'].$info['keywords'].$info['redactor']);
		$info['article_size'] = $content_size;
		
		$sql = "UPDATE " . DB_PREFIX . "article SET ";
		$sql_extra = $space = '';
		foreach($info as $k => $v)
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
		$sql .= $sql_extra . " where id=" . $article_id;
		
		$update_tag = false;
		$query = $this->db->query($sql);
		if($this->db->affected_rows())
		{
			$update_tag = true;
		}
		//更新内容表
		$sql = "UPDATE " . DB_PREFIX . "content SET content='" . $content . "' WHERE article_id=" . $article_id;
		$this->db->query($sql);
		
		if($this->db->affected_rows())
		{
			$update_tag = true;
		}
		
		//更新素材表
	    $material_id = $this->input['material_id'];
	    if(!empty($material_id))
		{
			$material_history = explode(',',urldecode($this->input['material_history']));
			if(!empty($material_history))
			{
				$del_material = array_diff($material_history,$material_id);
			}
			$mid_str = implode(',',$material_id);
			
			$del_material = implode(',',$del_material);
			if($del_material)//在某篇内容中操作最后不需要的图片
			{
				$this->mater->delMaterialById($del_material,2); //根据素材ID来删除素材信息
				$sql="DELETE FROM " . DB_PREFIX . "material WHERE material_id IN(" . $del_material . ")";
				$this->db->query($sql);
			}
			
			$this->mater->updateMaterial($mid_str,$article_id);
			$sql = "UPDATE " . DB_PREFIX . "material SET cid=" . $article_id . " WHERE material_id IN (" . $mid_str . ")";
			$this->db->query($sql);
			if($this->db->affected_rows())
			{
				$update_tag = true;
			}
		}
		elseif ($this->input['material_history'])
		{
			$material_history = urldecode($this->input['material_history']);
			$sql="DELETE FROM " . DB_PREFIX . "material WHERE material_id IN(" . $material_history . ")";
			$this->db->query($sql);
			
			$update_tag = true;
		}
		
		if($update_tag)
		{
			$sql = "UPDATE " . DB_PREFIX . "article SET 
					org_id_update ='" . $this->user['org_id'] . "',
					user_id_update = '".$this->user['user_id']."',
					user_name_update = '".$this->user['user_name']."',
					ip_update = '" . $this->user['ip'] . "', 
					update_time = '". TIMENOW . "',
					state = " . intval($this->input['state']) . " WHERE id=" . $article_id;
			$this->db->query($sql);
		}
		//发布系统
		$sql = "select * from " . DB_PREFIX ."article where id = " . $article_id;
		$ret = $this->db->query_first($sql);
		
		//更改期刊后发布的栏目
		$ret['column_id'] = unserialize($ret['column_id']);
		$new_column_id = array();
		if(is_array($ret['column_id']))
		{
			$new_column_id = array_keys($ret['column_id']);
		}
		
		if(intval($ret['state']) == 1)
		{
			if(!empty($ret['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->obj->publish_insert_query($article_id, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->obj->publish_insert_query($article_id, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->obj->publish_insert_query($article_id, 'update',$same_column);
				}
			}
			else//未发布，直接插入
			{
				$op = "insert";
				$this->obj->publish_insert_query($article_id,$op);
			}
		}
		else    //打回
		{
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				$this->obj->publish_insert_query($article_id,$op);
			}
		}
		
		//如果更新内容有改变
		if($update_tag)
		{
			//更新文章计数
			if($info['group_id'] != $old_group_id)
			{
				$sql = "UPDATE ".DB_PREFIX."catalog SET cur_article_num = cur_article_num - 1 WHERE id = ".$old_group_id;
				$this->db->query($sql);
				
				$sql = "UPDATE ".DB_PREFIX."catalog SET cur_article_num = cur_article_num + 1 WHERE id = ".$info['group_id'];
				$this->db->query($sql);
			}
			
			//添加日志
			$this->addLogs('更新文章',$q,$ret,$info['title']);
			
			//查找期刊下所有文章大小
			$sql = 'SELECT id,article_size FROM '.DB_PREFIX.'article WHERE issue_id ="'.$info['issue_id'].'"';
			$q = $this->db->query($sql);
			$article_size = '';
			while($r = $this->db->fetch_array($q))
			{
				$article_size += $r['article_size'];
				$ids[] = $r['id'];
			}
			
			//查找素材大小
			$ids = implode(',',$ids);
			$sql = 'SELECT filesize FROM '.DB_PREFIX.'material WHERE cid IN ('.$ids.')';
			$q = $this->db->query($sql);
			$material_size = '';
			while($r = $this->db->fetch_array($q))
			{
				$material_size += $r['filesize'];
			}
			
			//更新杂志大小
			$issue_size = $article_size+$material_size;
			$sql = "UPDATE " . DB_PREFIX . "issue SET issue_size=file_size+" . $issue_size . " WHERE id = " . $info['issue_id'];
			$this->db->query($sql);
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	//删除文章和内容
	function delete()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		if(empty($this->input['id']))
		{
			$this->errorOutput(NOID);
		}
		$ids = urldecode($this->input['id']);
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'article WHERE id IN (' . $ids . ')';
		
		$q = $this->db->query($sql);
		$admin_type = false;
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$admin_type = true;
		}
		//节点权限判断
		//$prms_sort_ids = $this->user['prms'][MOD_UNIQUEID]['delete']['node']['article_node'];
		while($row = $this->db->fetch_array($q))
		{
			/*if($admin_type)
			{
				if($prms_sort_ids && !in_array($row['group_id'], $prms_sort_ids))
				{
					$this->errorOutput('没有权限删除此栏目下文章');
					break;
				}
			}*/
			//发布
			$column_id = @unserialize($row['column_id']);
			if(intval($row['state']) == 1 && ($row['expand_id'] || $column_id))
			{
				$op = "delete";
				//$this->obj->publish_insert_query($row['id'],$op);
			}
			
			$result[] = $row['id'];
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['title'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['article'] = $row;
			$conInfor[] = $row;
			
			$article_size += $row['article_size'];
			$issue_id = $row['issue_id'];
			
			//收集被删除文章分类计数
			if($row['group_id'])
			{
				if($sort_info[$row['group_id']])
				{
					$sort_info[$row['group_id']] += 1; 
				}
				else 
				{
					$sort_info[$row['group_id']] = 1;
				}
			}
		}
		/******能否修改他人数据********/
		if (!empty($conInfor) && $admin_type)
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage_issue'));
			}
		}
		/******能否修改他人数据********/
		//文章ids
		$art_ids = implode(',', $result);
		
		$sql = "select * from " . DB_PREFIX . "content where article_id in(" . $art_ids .")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$data2[$row['article_id']]['content']['content'] = $row;
		}
		
		
		if(is_array($data2) && count($data2) && $this->settings['App_recycle'])
		{
			include_once(ROOT_PATH . 'lib/class/recycle.class.php');
			$this->recycle = new recycle();
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		//放入回收站结束
		
		//查询被删除文章大小
		/*$sql = 'SELECT issue_id,article_size FROM '.DB_PREFIX.'article WHERE id IN ('.urldecode($this->input['id']).')';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$article_size += $r['article_size'];
			$issue_id = $r['issue_id'];
		}*/
		
		//删除文章
		$sql = 'DELETE FROM '.DB_PREFIX.'article WHERE id IN ('.urldecode($this->input['id']).')';
		if($this->db->query($sql))
		{
			//删除内容
			$sql = 'DELETE FROM '.DB_PREFIX.'content WHERE article_id IN ('.urldecode($this->input['id']).')';
			$this->db->query($sql);
			
			//更新文章分类计数
			if($sort_info)
			{
				foreach ($sort_info as $k => $v)
				{
					$sql = "UPDATE ".DB_PREFIX."catalog SET cur_article_num = cur_article_num - ".$v." WHERE id = ".$k;
					$this->db->query($sql);
				}
			}
			
			if($article_size)
			{
				//更新杂志大小
				$sql = "UPDATE " . DB_PREFIX . "issue SET issue_size=issue_size-" . $article_size . " WHERE id = " . intval($issue_id);
				$this->db->query($sql);
			}
		
			//查询期刊中文章，没有文章删除期刊中封面文章
			$sql = 'SELECT id FROM '.DB_PREFIX.'article WHERE issue_id = '. intval($issue_id);
			$q = $this->db->query_first($sql);
			if(!$q)
			{
				$sql = "UPDATE " . DB_PREFIX . "issue SET cover_article=''  WHERE id = " . intval($issue_id);
				$this->db->query($sql);
			}
			
			$this->addLogs('删除文章',$data2,'', '删除文章+' . $ids);	
			$this->addItem($ids);
		}
		$this->output();
	}
	//彻底删除
	public function delete_comp()
	{
		return true;
	}
	/**
	 * 批量审核文章
	 * 1为审核状态
	 */
	public function audit()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'audit'));
		
		if (!$this->input['id'])
		{
			$this->errorOutput('没有id');
		}
		$ids = urldecode($this->input['id']);
		
		//发布
		$sql = "SELECT id,expand_id,group_id FROM " . DB_PREFIX ."article WHERE id IN(" . $ids . ")";
		$ret = $this->db->query($sql);
		
		//节点权限判断
		//$prms_sort_ids = $this->user['prms'][MOD_UNIQUEID]['audit']['node']['article_node'];
		
		while($info = $this->db->fetch_array($ret))
		{
			/*if($prms_sort_ids && $this->user['group_type'] > MAX_ADMIN_TYPE && !in_array($info['group_id'], $prms_sort_ids))
			{
				$this->errorOutput('没有权限审核该栏目下文章');
				break;
			}*/
		
			if(!empty($info['expand_id']))
			{
				$op = "update";			
			}
			else
			{
				$op = "insert";
			}
			$this->obj->publish_insert_query($info['id'], $op);
		}
		
		$sql = 'UPDATE '.DB_PREFIX.'article SET state = 1 WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		//添加日志
		$this->addLogs('审核文章','','','审核文章' . '+' . $ids);
		$arr = array('id' => $ids,'status' => 1,'audit'=>'已审核','op'=>'back');
		$this->addItem($arr);
		$this->output();

	}
	/**
	 * 批量打回文章
	 *  2为打回状态
	 */
	public function back()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'audit'));
		
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ids = urldecode($this->input['id']);
		
		//发布
		$sql = "SELECT id,expand_id,group_id FROM " . DB_PREFIX ."article WHERE id IN(" . $ids .")";
		$ret = $this->db->query($sql);
		//节点权限判断
		//$prms_sort_ids = $this->user['prms'][MOD_UNIQUEID]['back']['node']['article_node'];
		while($info = $this->db->fetch_array($ret))
		{
			/*if($prms_sort_ids && $this->user['group_type'] > MAX_ADMIN_TYPE && !in_array($info['group_id'], $prms_sort_ids))
			{
				$this->errorOutput('没有权限打回该栏目下文章');
				break;
			}*/
			
			if(!empty($info['expand_id']))
			{
				$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
			}		
			else 
			{
				$op = "";
			}
			$this->obj->publish_insert_query($info['id'], $op);
		}
		$sql = 'UPDATE '.DB_PREFIX.'article SET state = 2 WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		//添加日志
		$this->addLogs('打回文章','','','打回文章' . '+' . $ids);
		$arr = array('id' => $ids,'status' => 2,'audit'=>'已打回','op'=>'audit');
		$this->addItem($arr);
		$this->output();
	}
	//排序
	function sort()
	{
		$data['_action'] = 'manage_issue';
		$this->verify_content_prms($data);
		
		if(!$this->input['content_id'] || !$this->input['order_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$table_name = 'article';
		$order_name = 'order_id';
	
		$ids       = explode(',',urldecode($this->input['content_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . $table_name . " SET " . $order_name . " = '" . $order_ids[$k] . "' WHERE id = '" . $v . "'";
			$this->db->query($sql);
			if($this->db->affected_rows())
			{
				$this->addLogs('文章排序','','','');
			}
		}
		
		//将排序在最前的文章更新到期刊表中的封面文章字段
		$sql = 'SELECT issue_id FROM ' . DB_PREFIX . $table_name . ' WHERE id = '.$ids[0];
		//查询期刊id
		$q = $this->db->query_first($sql);
		$id = $q['issue_id'];
		//查询期刊文章排在最前的
		$sql = 'SELECT id,title,subhead,issue_id FROM ' . DB_PREFIX . $table_name . ' WHERE issue_id = '.$id.' ORDER BY ' . $order_name . ' DESC LIMIT 0,1';
		$res = $this->db->query_first($sql);
		if($res)
		{
			$cover_article[] = $res; 
			$cover_article = serialize($cover_article);
			//更新期刊封面文章
			$sql = "UPDATE " . DB_PREFIX . "issue SET cover_article='" . $cover_article . "'  WHERE id = " . intval($res['issue_id']);
			$this->db->query($sql);
		}
		else 
		{
			//更新期刊封面文章
			$sql = "UPDATE " . DB_PREFIX . "issue SET cover_article=''  WHERE id = " . intval($res['issue_id']);
			$this->db->query($sql);
		}
		
		$this->addItem($ids);
		$this->output();
	}
	//上传图片
	public function upload()
	{
		if($_FILES['Filedata'])
		{
			switch ($_FILES['Filedata']['error'])
			{
				case 1:
					$return = array(
						'success' => false,
						'error' => '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值',
					);
					break;
				case 2:
					$return = array(
						'success'=> false,
						'error' => '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值',
					);
					break;
				case 3:
					$return = array(
						'success' => false,
						'error' => '文件只有部分被上传',
					); 
					break;
				case 6:
					$return = array(
						'success' => false,
						'error' => '找不到临时文件夹',
					);
					break;
				case 7:
					$return = array(
						'success' => false,
						'error' => '文件写入失败',
					);
					break;
				default:
					$return = $this->obj->upload();
					break;					
			}
			$this->addItem($return);
			$this->output();
		}
	}
	/**
	 * 
	 * 本地化图片 ...
	 * 
	 * @name 		img_local
	 * @access		public
	 * @author		wangleyuan
	 * @category	hogesoft
	 * @copyright	hogesoft
	 */
	function img_local()
	{
		if(empty($this->input['url']))
		{
			$this->errorOutput('请传入URL');
		}
		$ret = $this->obj->img_local();
		if(!empty($ret))
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('图片本地化失败');
		}
	}
	//上传水印图片
	public function upload_water()
	{
		if($_FILES['Filedata'])
		{
			if($_FILES['Filedata']['error'] == 0 )
			{
				$return = $this->obj->upload_water();
				if(empty($return))
				{
					$this->errorOutput('上传失败！');
				}
				else
				{
					$this->addItem($return);
					$this->output();
				}
			}
			else 
			{
				$this->errorOutput('上传失败！');
			}
		}
	}
	//using
	/*
	*	旋转图片
	*
	*	@param material_id 附件ID
	*	@param direction 旋转方向 1左旋转 2右转转
	*
	*/
	public function revolveImg()
	{
		if(empty($this->input['material_id']))
		{
			$this->errorOutput('附件ID不能为空');
		}
		if(empty($this->input['direction']))
		{
			$this->errorOutput('旋转方向不能为空');
		}
		$ret = $this->obj->revolveImg();
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('旋转失败');
		}
	}
	//添加新的水印配置
	public function create_water_config()
	{
		if(empty($this->input['config_name']))
		{
			$this->errorOutput('配置名称不能为空');
		}
			
		if(intval($this->input['water_type'])==1 && empty($this->input['water_filename']))
		{
			$this->errorOutput('水印图片不能为空');
		}

		if(intval($this->input['water_type'])==0 && empty($this->input['water_text']))
		{
			$this->errorOutput('水印文字不能为空');
		}
		$return = $this->obj->create_water_config();
		if(empty($return))
		{
			$this->errorOutput('添加失败');
		}
		else
		{
			$this->addItem($return);
			$this->output();
		}
	}
	//查看水印配置列表
	public function water_config_list()
	{
		$return = $this->obj->water_config_list();
		if(empty($return))
		{
			$this->errorOutput('获取失败');
		}
		else
		{
			if(is_array($return) && count($return))
			{
				foreach($return as $k => $v)
				{
					$this->addItem($v);
				}
				$this->output();
			}
		}
	}
	//using
	public function pic_water_list()
	{
		$info = $this->obj->pic_water_list();
		$this->addItem($info);
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
		$ret = $this->obj->publish();
		if(empty($ret))
		{
			$this->errorOutput('发布失败');
		}
		else 
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	private function manage_article_sort()
	{
		$issue_id = intval($this->input['issue_id']);
		if(!$issue_id)
		{
			return false;
		}
		//查找文章分类
		if(!empty($this->input['sort']))
		{
			$sql = 'SELECT id,name FROM '.DB_PREFIX.'catalog WHERE name ="'.urldecode($this->input['sort']).'" AND issue_id = '.$issue_id;
			$res = $this->db->query_first($sql);
			if ($res)//存在直接替换id
			{
				$this->input['sort_id'] = $res['id'];
			}
			else//不存在，插入分类表，返回id
			{
				$obj = new sortUpdateApi();
				$this->input['sort_id'] = $obj->create();
			}
		}
		//如果分类不存在，默认为无分类id=0
		if(!$this->input['sort_id'])
		{
			$this->input['sort_id'] = 0;
			$this->input['sort'] = '无分类';
		}
		//记录用户输入的分类
		$user_id = $this->user['user_id'];
		if($this->input['sort_id'] && $user_id && $this->input['sort_id'] != -1)
		{
			$sql = '';
			$sql = "SELECT sort_id FROM ".DB_PREFIX."user_log WHERE user_id = ".$user_id;
			$res = $this->db->query_first($sql);
		
			if($res)//已经有用户记录，更新
			{
				if($res['sort_id'] != $this->input['sort_id'])
				{
					$sql = "UPDATE ".DB_PREFIX."user_log SET sort_id=".$this->input['sort_id']." WHERE user_id=".$user_id;
				}
			}
			else //没有用户记录，插入用户使用分类表
			{
				$sql = "INSERT INTO " . DB_PREFIX . "user_log SET user_id = ".$user_id.",sort_id = ".$this->input['sort_id'];
			}
			$this->db->query($sql);
		}
	}
	
	public function del_article_sort()
	{
		
		$id = intval($this->input['id']);
		$issue_id = intval($this->input['issue_id']);
		
		if(!$id || !$issue_id)
		{
			return false;
		}
		
		$sql = "SELECT count(*) as num FROM ".DB_PREFIX."article WHERE group_id = ".$id." AND issue_id = ".$issue_id;
		$res = $this->db->query_first($sql);
		
		if($res['num'])
		{
			echo -1;exit();
		}
		
		$sql = "DELETE FROM ".DB_PREFIX."catalog WHERE id = ".$id;
		
		$this->db->query($sql);
		
		$this->addItem('success');
		
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new ArticleUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();