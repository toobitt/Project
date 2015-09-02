<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once('./article_sort_update.php');
define('MOD_UNIQUEID','issue');//模块标识

class IssueUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/issue.class.php');
		$this->obj = new IssueClass();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	function sort()
	{
	}
	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ids = urldecode($this->input['id']);
		
		//查询期刊id
		$sql = 'SELECT id FROM '.DB_PREFIX.'article WHERE issue_id IN ('.$ids.')';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$issue_ids[] = $row['id'];
		}
		if(count($issue_ids))
		{
			echo -1;exit();
		}
		
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'issue WHERE id IN (' . $ids . ')';
		
		$q = $this->db->query($sql);
		
		//可以管理的杂志ids
		$admin_type = false;
		if($this->user['group_type']>MAX_ADMIN_TYPE)
		{
			$admin_type = true;
			$prms_maga_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		}
		$num = 0;
		while($row = $this->db->fetch_array($q))
		{
			++$num; 
			$conInfor[] = $row;
			
			$maga_id = $row['magazine_id'];
			/*********判断被删除期刊是否属于有权限管理的杂志*******/
			if($prms_maga_ids && $admin_type && !in_array($row['magazine_id'],$prms_maga_ids))
			{
				$this->errorOutput('没有权限删除此期刊');
				break;
			}
			/*********判断被删除期刊是否属于有权限管理的杂志*******/
			
			//发布
			if($row['expand_id'])
			{
				$op = "delete";
				$this->obj->publish_insert_query($row['id'],$op);
			}
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['issue'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['issue'] = $row;
		}
		
		/******能否修改他人数据********/
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage_issue'));
			}
		}
		/******能否修改他人数据结束********/
		
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
		
		$sql = 'DELETE FROM '.DB_PREFIX.'issue WHERE id IN ('. $ids .')';
		$this->db->query($sql);
		
		//删除期刊下分类
		$sql = "DELETE FROM ".DB_PREFIX."catalog WHERE issue_id IN (".$ids.")";
		$this->db->query($sql);
		
		//更新杂志管理其数，总期数
		if($maga_id)
		{
			//查询被删除后的最新一期id
			$sql = "SELECT id,issue FROM ".DB_PREFIX."issue WHERE state=1 AND magazine_id =".$maga_id." ORDER BY id DESC";
			$res = $this->db->query_first($sql);
			$cur_issue_id = $res['id']?$res['id']:0;
			$cur_issue = $res['issue'];
			
			//更新杂志表,一般只会删除最新一期未审核通过的,计数默认最新一期退后一期
			$sql = 'UPDATE '.DB_PREFIX.'magazine SET '; 
			$sql .= ' mana_nper = mana_nper-'.$num.' ,volume = volume-'.$num.',issue_id='.$cur_issue_id.',current_nper=current_nper-'.$num;
			$sql .= ' WHERE id = '.$maga_id;
			$this->db->query($sql);
		}
		
		/***********添加日志***********/
		$this->addLogs('删除期刊', $data2, '','删除期刊'.$ids);
		
		$this->addItem('success');
		$this->output();
		
	}
	public function recover()
	{
		$content=$this->input['content'];
		if(!empty($content))
		{
			foreach($content as $key => $value)
			{
				if(!empty($value))
				{
					$sql = "insert into " . DB_PREFIX . $key . " set ";
					$space='';
					foreach($value as $k => $v)
					{
						if(is_array($v))
						{
							$sql2 = "insert into " . DB_PREFIX . $key . " set ";
							$space='';
							foreach($v as $kk=>$vv)
							{
								$vv  = urldecode(urldecode($vv));
								$sql2 .= $space . $kk . "='" . $vv . "'";
								$space=',';
							}
							$this->db->query($sql2);
						}
						else
						{
							$v = urldecode(urldecode($v));
							$sql .= $space . $k . "='" . $v . "'";
							$space=',';
						}
					}
					$this->db->query($sql);
				}
				//更新杂志计数
				$maga_id = $value['magazine_id'];
				//查询被删除后的最新一期id
				$sql = "SELECT id,issue FROM ".DB_PREFIX."issue WHERE magazine_id =".$maga_id." ORDER BY id DESC";
				$res = $this->db->query_first($sql);
				$cur_issue_id = $res['id'];
				$cur_issue = $res['issue'];
				//更新杂志表
				$sql = 'UPDATE '.DB_PREFIX.'magazine SET '; 
				$sql .= ' mana_nper = mana_nper+1 ,volume = volume+1,issue_id='.$cur_issue_id.',current_nper='.$cur_issue;
				$sql .= ' WHERE id = '.$maga_id;
				$this->db->query($sql);
			}
		}
		$this->addItem(true);
		$this->output();
	}
	
	//彻底删除
	public function delete_comp()
	{
		if(empty($this->input['cid']))
		{
			return false;
		}
		//期刊id
		$id = urldecode($this->input['cid']);
		//查询文章id
		$sql = 'SELECT id FROM '.DB_PREFIX.'article WHERE issue_id IN ('.$id.')';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$ids[] = $row['id'];
		}
		//文章id
		$article_id = implode(',', $ids);
		
		//删除文章
		$sql = 'DELETE FROM '.DB_PREFIX.'article WHERE id IN ('.$article_id.')';
		$this->db->query($sql);
		
		//删除文章内容
		$sql = 'DELETE FROM '.DB_PREFIX.'content WHERE article_id IN ('.$article_id.')';
		$this->db->query($sql);
		return true;
	}
	
	
	function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('期刊id不存在');
		}
			
		if(!intval($this->input['issue']))
		{
			$this->errorOutput("刊号不能为空");
		}
		if(!intval($this->input['total_issue']))
		{
			$this->errorOutput("总期数不能为空");
		}
		
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		$maga_id = intval($this->input['magazine_id']);
		//更新他人数据杂志判断
		$sql = 'SELECT user_id,org_id,name FROM '.DB_PREFIX.'magazine WHERE id = '.$maga_id;
		$q = $this->db->query_first($sql);
		/*
		$info['id'] = $maga_id;
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage_issue';
		$this->verify_content_prms($info);
		*/
		$maga_name = $q['name'];
			
		//判断被更新期刊是否属于有权限管理的杂志
		$prms_maga_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_maga_ids && !in_array($maga_id,$prms_maga_ids))
		{
			$this->errorOutput('没有权限更新此期刊');
		}
			
		//查询修改期刊之前已经发布到的栏目
		$sql = "select * from " . DB_PREFIX ."issue where id = ".$id;
		$q = $this->db->query_first($sql);
		
		/*******修改他人数据权限判断期刊判断************/
		$info['id'] = $id;
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$this->input['state'] = $q['state'];
		$info['column_id'] = $this->input['column_id'];
		$info['_action'] = 'manage_issue';
		$this->verify_content_prms($info);
		/**************结束***************/
	
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			/**************更新已发布内容权限判断*******************/
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
			else //发布设置高于已审核设置
			{
				/******************更新已审核内容权限判断****************/
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
		
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		
		$year = substr($this->input['pub_date'],0,4);
		$pub_date = strtotime(trim(urldecode($this->input['pub_date'])));
		$data =array(
			'id'			=>	$id,
			'issue'			=>	intval($this->input['issue']),
			'total_issue' 	=> 	intval($this->input['total_issue']),
			'total_article' => 	intval($this->input['total_article']),
			'magazine_id'	=>	$maga_id,
			'column_id' 	=> 	$this->input['column_id'],
			'pub_date'		=>	$pub_date,
 			//'user_name'	=>	$this->user['user_name'],
 			//'user_id'		=>	$this->user['user_id'],
			//'org_id' 		=> 	$this->user['org_id'],
 			//'update_time'	=>	TIMENOW,
			//'ip'			=>	hg_getip(),
			//'state'		=>	intval($this->input['state']),
		);
		
		//更改后的栏目id
		$data['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$data['column_id']);
		$data['column_id'] = serialize($data['column_id']);
		
      	$sql = 'UPDATE '.DB_PREFIX.'issue SET ';
		foreach($data as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$sql = rtrim($sql,',');
		
		$sql = $sql.' WHERE id = ' . $id;
		$this->db->query($sql);
		
		$update_tag = false;
		if($this->input['article_sort_id'])
		{
			foreach ($this->input['article_sort_id'] as $k => $v)
			{
				$sql = "UPDATE ".DB_PREFIX."catalog SET name = '".$this->input['article_sort_name'][$k]."',article_num = '".$this->input['article_num'][$k]."' WHERE id = ".$v;
				$this->db->query($sql);
				
				if($this->db->affected_rows())
				{
					$update_tag = true;
				}
			}
		}
		//创建期刊下分类
		if($this->input['article_sort_name_add'])
		{
			$article_num = $this->input['article_num_add'];
			$ip				= $this->user['ip'];
			$user_id 		= $this->user['user_id'];
			$user_name		= $this->user['user_name'];
			$add_sql = "INSERT INTO ".DB_PREFIX."catalog (name, article_num, issue_id, user_id, user_name, ip, create_time) VALUES";
			
			foreach ($this->input['article_sort_name_add'] as $k => $v)
			{
				if(empty($v))
				{
					continue;
				}
				
				$vals.= "('".$v."','".$article_num[$k]."', '".$id."','".$user_id."','".$user_name."','".$ip."',".TIMENOW."),";
			}
			
			if($vals)
			{
				$vals = rtrim($vals,',');
				$add_sql .= $vals;
				
				$this->db->query($add_sql);
			}
			
			$update_tag = true;
		}
		
		if($this->db->affected_rows() || $_FILES || $update_tag)
		{
			$update_tag = true;
			$sql = "UPDATE " . DB_PREFIX . "issue SET 
				org_id_update ='" . $this->user['org_id'] . "',
				user_id_update = '".$this->user['user_id']."',
				user_name_update = '".$this->user['user_name']."',
				ip_update = '" . $this->user['ip'] . "', 
				update_time = '". TIMENOW . "',
				state = " . intval($this->input['state']) . " WHERE id=" . $id;
			$this->db->query($sql);
			
		}
		
		//上传图片处理
		if ($_FILES)
		{
			//删除之前图片
			$res['original_id'] = $q['original_id'];
			
			$this->mater = new material();
			if($res['original_id'])
			{
				$this->mater->delMaterialById($res['original_id'],2);
			}
			//重新上传
			$pics = array();
			$pics['Filedata'] = $_FILES['files'];
			$arr = $this->mater->addMaterial($pics,$data['id']); //插入图片服务器
			$sql = 'UPDATE '.DB_PREFIX.'issue SET file_name="'.$arr['filename'].
					'",file_path="'.$arr['filepath'].
					'",file_type="'.$arr['type'].
					'",file_size="'.$arr['filesize'].
					'",host="'.$arr['host'].
					'",dir="'.$arr['dir'].
					'",original_id='.$arr['id'].
					' WHERE id='.$data['id'];
			$this->db->query($sql);
			
			//返回上传的图片信息
			$data['img_info'] = $arr;
		}
		
		//发布系统
		$sql = "select * from " . DB_PREFIX ."issue where id = " . $id;
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
					$this->obj->publish_insert_query($id, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->obj->publish_insert_query($id, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->obj->publish_insert_query($id, 'update',$same_column);
					//有新插入素材时需插入子队列
					//$this->obj->publish_insert_query($id, 'insert',$same_column,1);
				}
			}
			else //未发布，直接插入
			{
				$op = "insert";
				$this->obj->publish_insert_query($id,$op);
			}
		}
		else //打回
		{
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				$this->obj->publish_insert_query($article_id,$op);
			}
		}
		
		
		//判断是否更改期刊
		if($update_tag || $_FILES)
		{
			//添加日志
			$this->addLogs('更新期刊',$q,$ret, $maga_name.' '.$year.'年 第'.$data['issue'].'期');
		}
		
		if($data)
		{
			$data['pub_date'] = $this->input['pub_date'];
			
			$data['year'] = $year;
		}
		
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 创建新刊
	 */
	function create()
	{
		if(!intval($this->input['issue']))
		{
			$this->errorOutput("刊号不能为空");
		}
		if(!intval($this->input['total_issue']))
		{
			$this->errorOutput("总期数不能为空");
		}
		if(empty($this->input['maga_id']))
		{
			$this->errorOutput("请选择杂志");
		}
		if(empty($this->input['pub_date']))
		{
			$this->errorOutput("请选择出版时间");
		}
		
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		$maga_id = intval($this->input['maga_id']);
		
		$prms_maga_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		
		//更新他人数据判断
		$sql = 'SELECT user_id,org_id,name FROM '.DB_PREFIX.'magazine WHERE id = '.$maga_id;
		$q = $this->db->query_first($sql);
			
		$maga_name = $q['name'];
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			/******判断被创建期刊是否属于有权限管理的杂志*****/
			if($prms_maga_ids && !in_array($maga_id,$prms_maga_ids))
			{
				$this->errorOutput('没有权限创建此杂志下的期刊');
			}
			/******判断被创建期刊是否属于有权限管理的杂志*****/
			/*
			$info['id'] = $maga_id;
			$info['org_id'] = $q['org_id'];
			$info['user_id'] = $q['user_id'];
			$info['_action'] = 'manage_issue';
			$this->verify_content_prms($info);
			*/
		}
	
		
		/********创建数据上限判断**********/
		$create_data_limit = $this->user['prms']['default_setting']['create_data_limit'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $create_data_limit)
		{
			$sql = "SELECT count(*) FROM ".DB_PREFIX."issue WHERE user_id = ".$this->user['user_id'];
			$count = $this->db->query_first($sql);
			if($count['count']>$create_data_limit)
			{
				$this->errorOutput('您只能添加'.$create_data_limit.'条数据');
			}
		}
		/********创建数据上限判断**********/
		
		//接收参数
		$year = substr($this->input['pub_date'],0,4);
		$pub_date = strtotime(trim(urldecode($this->input['pub_date'])));
		$data =array(
			'issue'				=>	intval($this->input['issue']),
			'total_issue' 		=> 	intval($this->input['total_issue']),
			'total_article' 	=> 	intval($this->input['total_article']),
			'magazine_id'		=>	$maga_id,
 			'user_name'			=>	$this->user['user_name'],
 			'user_id'			=>	intval($this->user['user_id']),
			'org_id' 			=> 	$this->user['org_id'],
 			'pub_date'			=>	$pub_date,
 			'create_time'		=>	TIMENOW,
			'ip'				=>	hg_getip(),
			//获取状态设置值
			'state'				=>	$this->get_status_setting('create'),
			'column_id' 		=> 	$this->input['column_id'],
		);
		//更改后的栏目id
		$data['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$data['column_id']);
		$data['column_id'] = serialize($data['column_id']);
						
		//插入期刊信息表
		$sql = 'INSERT INTO '.DB_PREFIX.'issue SET ';
		foreach($data as $k=>$v)
		{
			$sql .= "{$k} = '".$v."',";
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		
		//获得新插入期刊的ID
		$issue_id = $this->db->insert_id();
		$data['id'] = $issue_id;
		
		//更新排序
		$update_sql = 'UPDATE '.DB_PREFIX.'issue set order_id = '.$issue_id.' WHERE id = '.$issue_id;
		$this->db->query($update_sql);
		
		
		//创建期刊下分类
		if($this->input['article_sort_name'])
		{
			$article_num = $this->input['article_num'];
			$ip				= $this->user['ip'];
			$user_id 		= $this->user['user_id'];
			$user_name		= $this->user['user_name'];
			$add_sql = "INSERT INTO ".DB_PREFIX."catalog (name, article_num, issue_id, user_id, user_name, ip, create_time) VALUES";
			
			foreach ($this->input['article_sort_name'] as $k => $v)
			{
				if(empty($v))
				{
					continue;
				}
				$vals.= "('".$v."','".$article_num[$k]."', '".$issue_id."','".$user_id."','".$user_name."','".$ip."',".TIMENOW."),";
			}
			
			if($vals)
			{
				$vals = rtrim($vals,',');
				$add_sql .= $vals;
				$this->db->query($add_sql);
			}
		}
		
		//接收图片参数
		if ($_FILES['files'] && $_FILES['files']['error'] == 0)
		{
			$this->mater = new material();
			$pics = array();
			$pics['Filedata'] = $_FILES['files'];
			$arr = $this->mater->addMaterial($pics,$data['id']); //插入各类服务器
			
			$sql = 'UPDATE '.DB_PREFIX.'issue SET file_name="'.$arr['filename'].
				'",file_path="'.$arr['filepath'].
				'",file_type="'.$arr['type'].
				'",file_size="'.$arr['filesize'].
				'",host="'.$arr['host'].
				'",dir="'.$arr['dir'].
				'",original_id='.$arr['id'].
				' WHERE id='.$data['id'];
			$this->db->query($sql);
			
			//返回上传的图片信息
			$data['img_info'] = $arr;
		}
		
		//放入发布队列
		$sql = "SELECT * FROM " . DB_PREFIX ."issue WHERE id = " . $issue_id;
		$r = $this->db->query_first($sql);
		if(intval($r['state']) == 1 && !empty($r['column_id']))
		{
			$op = 'insert';
			$this->obj->publish_insert_query($issue_id,$op);
		}
		
		//更新杂志最新期刊
		if($issue_id && $data['state'])
		{
			//更新杂志表最新期刊id和计数
			$sql = 'UPDATE '.DB_PREFIX.'magazine SET issue_id="'.$issue_id.'",'; 
		}
		else 
		{
			//更新杂志表计数
			$sql = 'UPDATE '.DB_PREFIX.'magazine SET '; 
		}
		//$sql .= ' current_nper = current_nper+1, mana_nper = mana_nper+1,volume = volume+1';
		
		$sql .= ' current_nper = "'.$data['issue'].'", mana_nper = mana_nper+1,volume = "'.$data['total_issue'].'"';
		$sql .= ' WHERE id="'.$data['magazine_id'].'"';
		
		$this->db->query($sql);
		
		//添加日志
		$this->addLogs('创建期刊','',$r,$maga_name.' '.$year.'年 第'.$r['issue'].'期');
		
		if($data)
		{
			$data['pub_date'] = $this->input['pub_date'];
			
			$data['create_time'] = date('Y-m-d H:i',$data['create_time']);
			$data['year'] = $year;
			
		}
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 批量审核期刊
	 * 1为未审核状态  2为审核状态
	 */
	public function audit()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'audit'));
		
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = urldecode($this->input['id']);
		
		//判断计数
		/*$sql = "SELECT state FROM " . DB_PREFIX ."issue WHERE id IN(" . $ids . ")";
		$q = $this->db->query($sql);
		$count = 0;
		while ($g = $this->db->fetch_array($q))
		{
			if($g['state'] != 1)
			{
				$count = $count + 1;
			}
		}*/
			
		$sql = 'UPDATE '.DB_PREFIX.'issue SET state = 1 WHERE id IN (' . $ids . ')';
		$this->db->query($sql);
		
		//发布
		$sql = "SELECT * FROM " . DB_PREFIX ."issue WHERE id IN(" . $ids . ")";
		$ret = $this->db->query($sql);
		while($info = $this->db->fetch_array($ret))
		{
			if(!empty($info['expand_id']))
			{
				$op = "update";			
			}
			else
			{
				$op = "insert";
			}
			$issue_info[] = $info;
			$this->obj->publish_insert_query($info['id'], $op);
		}
		$magazine_id = $issue_info[0]['magazine_id'];
		
		//更新杂志计数
		$this->update_magazine($magazine_id);
		
		//添加日志
		$op = '审核期刊';
		$this->addLogs($op,'','',$op . '+' . $ids);
		
		$arr = array('status' => 1,'id' => $ids,'audit'=>'已审核','op'=>'back');
		$this->addItem($arr);
		$this->output();

	}
	/**
	 * 批量打回期刊
	 * 1为未审核状态  2为审核状态
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
		
		
		/*$sql = "SELECT state FROM " . DB_PREFIX ."issue WHERE id IN(" . $ids . ")";
		$q = $this->db->query($sql);
		$count = 0;
		while ($g = $this->db->fetch_array($q))
		{
			if($g['state'] != 2)
			{
				$count = $count + 1;
			}
		}*/
		
		$sql = 'UPDATE '.DB_PREFIX.'issue SET state = 2 WHERE id IN ('. $ids .')';
		$this->db->query($sql);
		
		//发布
		$sql = "SELECT * FROM " . DB_PREFIX ."issue WHERE id IN(" . $ids .")";
		$ret = $this->db->query($sql);
		while($info = $this->db->fetch_array($ret))
		{
			if(!empty($info['expand_id']))
			{
				$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
			}		
			else 
			{
				$op = "";
			}
			$issue_info[] = $info;
			$this->obj->publish_insert_query($info['id'], $op);
		}
		
		$magazine_id = $issue_info[0]['magazine_id'];
		
		//更新杂志计数
		$this->update_magazine($magazine_id);
		
		//添加日志
		$op = '打回期刊';
		$this->addLogs($op,'','',$op . '+' . $ids);
		
		$arr = array('status' => 2,'id' => $ids,'audit'=>'已打回','op'=>'audit');
		$this->addItem($arr);
		$this->output();
	}
	
	//更新杂志最新期刊和计数
	private function update_magazine($magazine_id)
	{
		//更新杂志最新期刊
		$sql = 'SELECT id,issue FROM '.DB_PREFIX.'issue WHERE state=1 AND magazine_id = '.$magazine_id.' ORDER BY id DESC LIMIT 0,1';
		$res = $this->db->query_first($sql);
		$issue_id = $res['id'];
		if($issue_id)
		{
			//更新杂志表
			$sql = 'UPDATE '.DB_PREFIX.'magazine SET issue_id="'.$issue_id.'"'; 
			
			//计数和审核打回没关系
			//$sql .= ' current_nper = current_nper' . $type . $count . ', mana_nper = mana_nper'.$type.$count.',volume = volume'.$type.$count;
			
			$sql .= ' WHERE id='.$magazine_id;
			$this->db->query($sql);
		}
		else 
		{
			//更新杂志表
			$sql = 'UPDATE '.DB_PREFIX.'magazine SET issue_id=0 WHERE id='.$magazine_id;
			$this->db->query($sql);
		}
	}
	/**
	 * 即时发布
	 * @param id  int   期刊id
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
	
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new IssueUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();