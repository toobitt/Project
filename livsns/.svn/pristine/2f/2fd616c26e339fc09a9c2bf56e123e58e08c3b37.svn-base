<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once '../lib/magazine.class.php';
define('MOD_UNIQUEID','magazine');//模块标识
class MagazineUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->magazine = new MagazineClass();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
	}
	function __destruct()
	{
		parent::__destruct();
	}

	function sort(){}
	function audit()
	{
		$id = urldecode($this->input['id']);	//杂志id们
		$audit = $this->input['audit']; //操作标识,'审核'或'打回'
		
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if(!$audit)
		{
			$this->errorOutput('无操作');
		}
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'audit')); //权限判断
			/**************节点权限*************/
			$prms_magazine_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			$magazine_id = explode(',',$id);
			foreach ($magazine_id as $key => $val)
			{
				if($prms_magazine_ids && !in_array($val,$prms_magazine_ids))
				{
					$this->errorOutput('没有权限');
				}
			}
			/*********************************/
			
			/**************审核他人数据权限判断***************/
			$sql = 'SELECT * FROM '.DB_PREFIX.'magazine WHERE id IN ('. $id .')';
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$conInfor[] = $row;
			}
			if (!empty($conInfor))
			{
				foreach ($conInfor as $val)
				{
					$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'audit'));
				}
			}
			/*********************************************/
		}
		
		if($audit == 1)	//'审核'操作
		{
			$status = 1;
			$audit_status = '已审核';
		}
		elseif($audit == 2)	//'打回'操作
		{
			$status = 2;
			$audit_status = '已打回';
		}
		
		$sql = " UPDATE " .DB_PREFIX. "magazine SET state = " .$status. " WHERE id in (" . $id . ")";
		$this->db->query($sql);
		$ret = array('status' => $status,'id' => $id,'audit'=>$audit_status);
	
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核杂志' . $id);	//此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = urldecode($this->input['id']);
		
		
		//查询期刊id
		$sql = 'SELECT id FROM '.DB_PREFIX.'issue WHERE magazine_id IN ('.$ids.')';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$issue_ids[] = $row['id'];
		}
		if(count($issue_ids))
		{
			echo -1;exit();
		}
		
		//判断删除的杂志是否属于查看的杂志
		$node = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
				
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $node)
		{
			//被删除杂志ids
			$arr_ids = explode(',', $ids);
			
			foreach ($arr_ids as $k => $v)
			{
				if(!in_array($v, $node))
				{
					$this->errorOutput('没权限删除杂志');
					break;
				}
			}
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'magazine WHERE id IN ('.$ids.')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
			if($row['expand_id'])
			{
				$op = "delete";
				$this->magazine->publish_insert_query($row['id'],$op);
			}
			$result[] = $row['id'];
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['magazine'] = $row;
		}
		
		//能否修改他人数据
		/*if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage_maga'));
			}
		}*/
		if(is_array($data2) && count($data2) && $this->settings['App_recycle'])
		{
			include_once(ROOT_PATH . 'lib/class/recycle.class.php');
			$this->recycle = new recycle();
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
			//放入回收站结束
		}
		
		$sql = 'DELETE FROM '.DB_PREFIX.'magazine WHERE id IN ('.urldecode($this->input['id']).')';
		$this->db->query($sql);
		
		/***********添加日志***********/
		$this->addLogs('删除杂志', $data2, '','删除杂志'.$ids);
		
		$this->addItem('success');

		$this->output();
	}
	//彻底删除
	public function delete_comp()
	{
		if(empty($this->input['cid']))
		{
			return false;
		}
		//杂志id
		$id = urldecode($this->input['cid']);
		//查询期刊id
		$sql = 'SELECT id FROM '.DB_PREFIX.'issue WHERE magazine_id IN ('.$id.')';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$ids[] = $row['id'];
		}
		//期刊id
		$issue_id = implode(',', $ids);
		//删除期刊
		$issue_sql = 'DELETE FROM '.DB_PREFIX.'issue WHERE magazine_id IN ('.$ids.')';
		$this->db->query($issue_sql);
		
		//查询文章id
		$sql = 'SELECT id FROM '.DB_PREFIX.'article WHERE issue_id IN ('.$issue_id.')';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$ids_art[] = $row['id'];
		}
		//文章id
		$article_id = implode(',', $ids_art);
		//删除期刊下文章
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
			$this->errorOutput('杂志id不存在');
		}
		if(empty($this->input['title']))
		{
			$this->errorOutput("杂志名称不能为空");
		}
		//判断更新的杂志是否属于有权限查看的杂志
		$node = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
				
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $node)
		{
			if(!in_array($id, $node))
			{
				$this->errorOutput('没权限更新此杂志');
			}
		}
		
		//查询修改杂志之前已经发布到的栏目
		$sql = "select * from " . DB_PREFIX ."magazine where id = " . $id;
		$q = $this->db->query_first($sql);
		
		/**************修改他人数据权限判断***************/
		$info['id'] = $id;
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['column_id'] = $this->input['column_id'];
		$info['_action'] = 'manage_maga';
		
		//$this->verify_content_prms($info);
		/**************结束***************/
		
		//只有管理员可以修改杂志名称
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->input['title'] != $q['name'])
			{
				$this->errorOutput('杂志名称不能修改');
			}
		}
		//确定杂志名称唯一
		if($this->input['title'] != $q['name'])
		{
			if($this->sole_magazine_name($this->input['title']))
			{
				$this->errorOutput("杂志名称已存在");
			}
		}
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		if($this->input['contract_name'] || $this->input['contract_value'])
		{
			if(is_array($this->input['contract_name']))
			{
				foreach($this->input['contract_name'] as $k=>$v)
				{
					$contract['contract_name'][$k] = urldecode($this->input['contract_name'][$k]);
				}
			}
			if(is_array($this->input['contract_value']))
			{
				foreach($this->input['contract_value'] as $k=>$v)
				{
					$contract['contract_value'][$k] = urldecode($this->input['contract_value'][$k]);
				}
			}
		}
		$contract_way = serialize($contract);
		$data = array(
			'id'=>intval($this->input['id']),
			'name'=>trim(urldecode($this->input['title'])),
			'sort_id'=>intval($this->input['group_id']),
			'brief'=>trim(urldecode($this->input['brief'])),
			'volume'=>intval($this->input['volume']),
			//'user_id'   => intval($this->user['user_id']),
			//'org_id' => $this->user['org_id'],
 			//'user_name'=>urldecode($this->user['user_name']),
 			
			//'update_time'=>TIMENOW,
			//'ip'=>hg_getip(),
			'sponsor' => trim(urldecode($this->input['sponsor'])),
			'editor' => trim(urldecode($this->input['editor'])),
			'contract_way' => $contract_way,
			'release_cycle' => intval($this->input['release_cycle']),
			'cssn' => trim(urldecode($this->input['cssn'])),
			'issn' => trim(urldecode($this->input['issn'])),
			'price'=> $this->input['price'],
			'page_num'=>intval($this->input['page_num']),
			'language' => trim(urldecode($this->input['language'])),
			'current_nper' =>intval($this->input['current_nper']),
			'column_id' => $this->input['column_id'],
		);
		//更改后的栏目id
		$data['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$data['column_id']);
		$data['column_id'] = addslashes(serialize($data['column_id']));
		
      	$sql = "UPDATE ".DB_PREFIX."magazine SET ";
		foreach($data as $k=>$v)
		{
			$sql .= "`".$k . "`='" . $v . "',";
		}
        $sql = rtrim($sql,',');
		$sql = $sql." WHERE id = ".$id;
		$this->db->query($sql);
		$update_tag = false;
		if($this->db->affected_rows())
		{
			$update_tag = true;
			$sql = "UPDATE " . DB_PREFIX . "magazine SET 
				org_id_update ='" . $this->user['org_id'] . "',
				user_id_update = '".$this->user['user_id']."',
				user_name_update = '".$this->user['user_name']."',
				ip_update = '" . $this->user['ip'] . "', 
				update_time = '". TIMENOW . "' WHERE id=" . $id;
			$this->db->query($sql);
		}
		
		//发布系统
		$sql = "select * from " . DB_PREFIX ."magazine where id = " . $id;
		$ret = $this->db->query_first($sql);
		//更改杂志后发布的栏目
		$ret['column_id'] = unserialize($ret['column_id']);
		$new_column_id = array();
		if(is_array($ret['column_id']))
		{
			$new_column_id = array_keys($ret['column_id']);
		}
		
		if(!empty($ret['expand_id']))   //已经发布过，对比修改先后栏目
		{
			$del_column = array_diff($ori_column_id,$new_column_id);
			if(!empty($del_column))
			{
				$this->magazine->publish_insert_query($id, 'delete',$del_column);
			}
			$add_column = array_diff($new_column_id,$ori_column_id);
			if(!empty($add_column))
			{
				$this->magazine->publish_insert_query($id, 'insert',$add_column);
			}
			$same_column = array_intersect($ori_column_id,$new_column_id);
			if(!empty($same_column))
			{
				$this->magazine->publish_insert_query($id, 'update',$same_column);
				//有新插入素材时需插入子队列
				//$this->magazine->publish_insert_query($id, 'insert',$same_column,1);
			}
		}
		else //未发布，直接插入
		{
			$op = "insert";
			$this->magazine->publish_insert_query($id,$op);
		}
		if($update_tag)
		{
			//添加日志
			$this->addLogs('更新杂志',$q,$ret,$data['name']);
		}
		$this->addItem($data);
		$this->output();

	}
	/**
	 * 创建杂志
	 */
	function create()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->errorOutput('没有权限创建杂志');
		}
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_maga'));
		
		if(empty($this->input['title']))
		{
			$this->errorOutput("杂志名称不能为空");
		}
		
		if($this->sole_magazine_name($this->input['title']))
		{
			$this->errorOutput("杂志名称已存在");
		}
		
		/********创建数据上限判断**********/
		$create_data_limit = $this->user['prms']['default_setting']['create_data_limit'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $create_data_limit)
		{
			$sql = "SELECT count(*) FROM ".DB_PREFIX."magazine WHERE user_id = ".$this->user['user_id'];
			$count = $this->db->query_first($sql);
			if($count['count']>$create_data_limit)
			{
				$this->errorOutput('您只能添加'.$create_data_limit.'条数据');
			}
		}
		/********创建数据上限判断**********/
		
		/*
		if(empty($this->input['group_id']))
		{
			$this->errorOutput("请选择分类");
		}
		*/
		
		//联系方式
		if($this->input['contract_name'] || $this->input['contract_value'])
		{
			if(is_array($this->input['contract_name']))
			{
				foreach($this->input['contract_name'] as $k=>$v)
				{
					$contract['contract_name'][$k] = urldecode($this->input['contract_name'][$k]);
				}
			}
		
			if(is_array($this->input['contract_value']))
			{
				foreach($this->input['contract_value'] as $k=>$v)
				{
					$contract['contract_value'][$k] = urldecode($this->input['contract_value'][$k]);
				}
			}
		}
		$contract_way = serialize($contract);
		//接收参数
		$data1 =array(
			'name'=>trim(urldecode($this->input['title'])),
			'sort_id'=>intval($this->input['group_id']),
			'brief'=>trim(urldecode($this->input['brief'])),
			'volume'=>intval($this->input['volume']),
			'user_id' => intval($this->user['user_id']),
			'org_id' => $this->user['org_id'],
 			'user_name'=>$this->user['user_name'],
 			'create_time'=>TIMENOW,
			'update_time'=>TIMENOW,
			'sponsor' => trim(urldecode($this->input['sponsor'])),
			'editor' => trim(urldecode($this->input['editor'])),
			'contract_way' => $contract_way,
			'release_cycle' => intval($this->input['release_cycle']),
			'cssn' => trim(urldecode($this->input['cssn'])),
			'issn' => trim(urldecode($this->input['issn'])),
			'price'=> $this->input['price'],
			'page_num'=>intval($this->input['page_num']),
			'language' => trim(urldecode($this->input['language'])),
			'current_nper' =>intval($this->input['current_nper']),
			'column_id' => $this->input['column_id'],
		);
		$column = $this->publish_column->get_columnname_by_ids('id,name',$data1['column_id']);
		$data1['column_id'] = addslashes(serialize($column));
		if($data1)
		{
			//插入杂志表
			$sql = 'INSERT INTO '.DB_PREFIX.'magazine SET ';
			foreach($data1 as $k=>$v)
			{
				$sql .= "{$k} = '".$v."',";
			}
			$sql = rtrim($sql,',');
			$this->db->query($sql);
			$maga_id = $this->db->insert_id();
			$data1['id'] = $maga_id;
		}
		//更新排序
		$update_sql = 'UPDATE '.DB_PREFIX.'magazine set order_id = '.$maga_id.' WHERE id = '.$maga_id;
		$this->db->query($update_sql);
		
		//放入发布队列
		$sql = "SELECT * FROM " . DB_PREFIX ."magazine WHERE id = " . $maga_id;
		$r = $this->db->query_first($sql);
		if(!empty($r['column_id']))
		{
			$op = 'insert';
			$this->magazine->publish_insert_query($maga_id,$op);
		}
		
		//添加日志
		$this->addLogs('创建杂志','',$data1,$data1['name']);
		
		if($data1['sort_id'])
		{
			$sql = "SELECT name FROM ".DB_PREFIX."magazine_node WHERE id = ".$data1['sort_id'];
			$res = $this->db->query_first($sql);
			$data1['sort_name'] = $res['name'];
		}
		else 
		{
			$data1['sort_name'] = '未分类';
		}
		
		if($data1['release_cycle'])
		{
			$release = $this->settings['release_cycle'][$data1['release_cycle']];
			
			if($release)
			{
				$data1['release_cycle'] = $release;
			}
		}
		
		if($data1['create_time'])
		{
			$data1['create_time'] = date('Y-m-d H:i',$data1['create_time']);
			$data1['year'] = substr($data1['create_time'],0,4);
		}
		$this->addItem($data1);
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
		$ret = $this->magazine->publish();
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
	
	/**
	 * 
	 * 筛选杂志名称，防止重名
	 * @param string $name 杂志名称
	 */
	function sole_magazine_name($name)
	{
		if(!$name)
		{
			return false;
		}
		$sql = "SELECT id FROM ".DB_PREFIX."magazine WHERE name = '".$name."'";
		$res = $this->db->query_first($sql);
		if($res['id'])
		{
			return true;
		}
	}
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new MagazineUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();