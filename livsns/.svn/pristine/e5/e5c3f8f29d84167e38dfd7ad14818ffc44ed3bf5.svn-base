<?php
require_once('./global.php');
define('MOD_UNIQUEID','ticket');//模块标识
require_once CUR_CONF_PATH.'lib/ticket.class.php';
include_once(ROOT_PATH . 'lib/class/recycle.class.php');
class ticketUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->ticket = new ticket();
		$this->recycle = new recycle();
		/*
		$this->mPrmsMethods['sale_state'] = array(
			'name' => '售票状态',
			'node' => true,
		);
		*/
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	
	function publish()
	{
		$column_id = rtrim($this->input['column_id'],',');
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('id不存在');
		}
		
		if($column_id)
		{
			$sql = "SELECT id,column_id,title FROM " . DB_PREFIX . "column WHERE column_id IN (" . $column_id . ")";
			$q = $this->db->query($sql);
			
			$column = array();
			while ($r = $this->db->fetch_array($q))
			{
				$column[$r['column_id']] 		= $r['title'];
				$id_columnid[$r['column_id']]	= $r['id'];
			}
		
			if(!empty($column))
			{
				$column = serialize($column);
				$sql = "UPDATE " . DB_PREFIX . "show SET publish_time = " . TIMENOW . ",column_id = '" . $column . "' WHERE id = " . $id;
				$this->db->query($sql);
			}
		}
		else 
		{
			$sql = "UPDATE " . DB_PREFIX . "show SET publish_time = " . TIMENOW . ",column_id = '' WHERE id = " . $id;
			$this->db->query($sql);
		}
		//删除发布记录
		$sql = "DELETE FROM " . DB_PREFIX . "publish_record WHERE show_id = " . $id;
		$this->db->query($sql);
		
		if($column_id)
		{
			$column_id_arr = array();
			$column_id_arr = explode(',', $column_id);
			if(!empty($column_id_arr))
			{
				$sql = "INSERT INTO " . DB_PREFIX . "publish_record VALUES";
				foreach ($column_id_arr as $k => $v)
				{
					$vals .= "(" . $id_columnid[$v]  . "," . $id . "," . $v . "),";	
				}
				
				if($vals)
				{
					$vals = rtrim($vals,',');
					$sql .= $vals;
					$this->db->query($sql);
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	/**
	 * 
	 * @Description 删除票务，支持多个
	 * @author Kin
	 * @date 2013-5-6 上午11:51:01 
	 * @see adminUpdateBase::delete()
	 */
	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		/**************权限控制开始**************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'show WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$before = array();
		$sorts = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			//回收站
			$data2[$row['id']] = array(
									'delete_people' => trim($this->user['user_name']),
									'title' 		=> $row['title'],
									'cid' 			=> $row['id'],
								);
			$data2[$row['id']]['content']['show'] = $row;
			$before[] = $row; 
		}
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			$nodes = array();
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
			$nodes['_action'] = 'manage';
			$this->verify_content_prms($nodes);
		}
			//能否修改他人数据
		if (!empty($before) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach ($before as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		/**************权限控制结束**************/
		//内容表
		$sql = "SELECT * FROM " . DB_PREFIX . "content WHERE id IN (" . $ids .")";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$data2[$row['id']]['content']['content'] = $row;
		}
		
		//素材表
		$sql = 'SELECT * FROM '.DB_PREFIX.'material WHERE show_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$data2[$row['show_id']]['content']['material'][$row['id']] = $row; 
		}
		
		if (!$this->settings['App_recycle'])
		{
			$this->errorOutput('回收站系统未安装!');
		}
		if(is_array($data2) && count($data2))
		{
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		$res = $this->ticket->delete($ids);
		
		//删除明星行程记录
		$sql = "DELETE FROM " . DB_PREFIX . "star_trip WHERE show_id IN (" . $ids . ')';
		$this->db->query($sql);
		
		//添加日志
		$this->addLogs('删除票务', $before, '', '删除票务'.$ids);
		$this->addItem($res);
		$this->output();
	}
	
	
	/**
	 * 
	 * @Description  回收站彻底删除
	 * @author Kin
	 * @date 2013-5-4 下午05:46:19
	 */
	public function delete_comp()
	{
		return true;
	}
	
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = intval($this->input['id']);
		$data = array(
			'title'=>addslashes(trim($this->input['title'])),
			'brief'=>addslashes(trim($this->input['brief'])),
			'sort_id'=>intval($this->input['sort_id']),
			'venue'=>addslashes(trim($this->input['venue'])),
			'address'=>addslashes(trim($this->input['address'])),
			'start_time'=>strtotime(trim($this->input['start_time'])),
			'end_time'=>strtotime(trim($this->input['end_time'])),
			'show_time'=>addslashes(trim($this->input['show_time'])),
			'price_notes'=>addslashes(trim($this->input['price_notes'])),
			'goods_total'=>intval($this->input['goods_total']),
			'goods_total_left'=>intval($this->input['goods_total_left']),	
			'star_ids'=> urldecode(trim($this->input['star_ids'])),
			'venue_id'=> intval($this->input['venue_id']),
			//'column_id' => intval($this->input['column_id']),
			'ticket_address' => addslashes(trim($this->input['ticket_address'])),
			'outlink'=> trim($this->input['outlink']),
		);
		if (!$data['title'])
		{
			$this->errorOutput('请填写标题');
		}
		if (!$this->input['start_time'])
		{
			$this->errorOutput('请填写开始时间');
		}
		if (!$this->input['end_time'])
		{
			$this->errorOutput('请填写结束时间');
		}
		if ($data['start_time'] > $data['end_time'])
		{
			$this->errorOutput('开始时间不能大于结束时间');
		}
		//添加座位图
		if($_FILES['seat_map'])
		{
			$file['Filedata'] = $_FILES['seat_map'];
			$res = $this->ticket->uploadToPicServer($file);
			if($res)
			{
				$seat_map = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['seat_map'] = serialize($seat_map);
			}
		}
		$data['tel'] = '';
		if ($this->input['connect_tel'] && is_array($this->input['connect_tel']))
		{
			foreach ($this->input['connect_tel'] as $key=>$val)
			{
				$data['tel'][] = array(
					'start_time'=>$this->input['connect_start_time'][$key],
					'end_time'=>$this->input['connect_end_time'][$key],
					'tel'=>$val,
				);
			}
		}
		if (!empty($data['tel']))
		{
			$data['tel'] = addslashes(serialize($data['tel']));
		}
		/**************权限控制开始**************/
		//节点权限
		$sql = 'SELECT * FROM '.DB_PREFIX.'show WHERE id = '.$id;
		$infor = $this->db->query_first($sql);
		//修改前
		if($infor['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id ='.$infor['sort_id'];
			$sortInfo = $this->db->query_first($sql);
			$nodes['nodes'][$sortInfo['id']] = $sortInfo['parents'];
		}
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		//修改后
		if($data['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id ='.$data['sort_id'];
			$sortInfo = $this->db->query_first($sql);
			$nodes['nodes'][$sortInfo['id']] = $sortInfo['parents'];
		}
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		//是否能修改他人数据
		$arr = array(
			'_action'=>'manage',
			'id'=>$id,
			'user_id'=>$infor['user_id'],
			'org_id'=>$infor['org_id'],
		);
		$this->verify_content_prms($arr);
		
		/**************权限控制结束**************/
		//验证是否有数据更新
		//主表
		$affected_rows = false;
		$sql = 'UPDATE '.DB_PREFIX.'show SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'=\''.$val.'\',';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id; 
		$query = $this->db->query($sql);
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
		}
		//内容表
		$content = addslashes($this->input['content']);
		$content_link = trim($this->input['content_link']);
		$sql = 'UPDATE '.DB_PREFIX.'content SET content = "'.$content.'",content_link = "' . $content_link . '" WHERE id = '.$id;
		$query = $this->db->query($sql);
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
		}
		if ($_FILES['Filedata'])
		{
			//插入图片服务器
			$index_id = $this->ticket->update_indexPic($_FILES, $id,intval($this->input['index_id']));
			$affected_rows = true;
			$infor['index_id'] = $index_id;
		}
		if ($affected_rows)
		{
			
			//删除明星行程记录
			$sql = "DELETE FROM " . DB_PREFIX . "star_trip WHERE show_id = " . $id;
			$this->db->query($sql);
		
			//存在明星id，插入明星行程表
			if($data['star_ids'])
			{
				$star_ids_arr = array();
				$star_ids_arr = explode(',', $data['star_ids']);
				if(!empty($star_ids_arr))
				{
					$sql = "INSERT INTO " . DB_PREFIX . "star_trip VALUES";
					foreach ($star_ids_arr as $k => $v)
					{
						$vals .= "(" . $v  . "," . $id . "," . $data['end_time'] . "),";	
					}
					
					if($vals)
					{
						$vals = rtrim($vals,',');
						$sql .= $vals;
						$this->db->query($sql);
					}
				}
			}
		
			$additionalData = array(
				'update_time'		=> TIMENOW,
				'update_org_id'		=> $this->user['org_id'],
				'update_user_id'	=> $this->user['user_id'],
				'update_user_name'	=> addslashes($this->user['user_name']),
				'update_ip'			=> $this->user['ip'],
			);
			/**************权限控制开始**************/
			//修改审核数据后的审核状态
			if ($infor['status']==1 && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if ($this->user['prms']['default_setting']['update_audit_content']==1)
				{
					$additionalData['status'] = 0;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
				{
					$additionalData['status'] = 1;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
				{
					$additionalData['status'] = 2;
				}
			}
			$this->ticket->update_show($additionalData, $id);
			$res = array_merge($infor, $data, $additionalData);
			//添加日志
			$this->addLogs('更新票务', $infor, $res, $infor['title'], $infor['id'], $infor['sort_id']);
		}
		/*
		//更新主表
		$this->ticket->update_show($data, $id);
		//更新内容表
		$content = addslashes($this->input['content']);
		$this->ticket->update_content($content, $id);
		//更新索引图
		if ($_FILES['Filedata'])
		{
			//插入图片服务器
			$ret = $this->ticket->update_indexPic($_FILES, $id,intval($this->input['index_id']));
		}
		*/
		$this->addItem('success');
		$this->output();
	}
	
	
	public function create()
	{
		$data = array(
			'title'=>addslashes(trim($this->input['title'])),
			'brief'=>addslashes(trim($this->input['brief'])),
			'sort_id'=>intval($this->input['sort_id']),
			'venue'=>addslashes(trim($this->input['venue'])),
			'address'=>addslashes(trim($this->input['address'])),
			'start_time'=>strtotime(trim($this->input['start_time'])),
			'end_time'=>strtotime(trim($this->input['end_time'])),
			'show_time'=>addslashes(trim($this->input['show_time'])),
			'price_notes'=>addslashes(trim($this->input['price_notes'])),
			'goods_total'=>intval($this->input['goods_total']),
			'goods_total_left'=>intval($this->input['goods_total_left']),
			'create_time'=>TIMENOW,
			'update_time'=>TIMENOW,
			'org_id'=>$this->user['org_id'],
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'appid'=>$this->user['appid'],
			'client'=>$this->user['display_name'],
			'ip'=>$this->user['ip'],	
			'star_ids'=> urldecode(trim($this->input['star_ids'])),
			'venue_id'=> intval($this->input['venue_id']),
			//'column_id' => intval($this->input['column_id']),
			'ticket_address' => addslashes(trim($this->input['ticket_address'])),
			'outlink'=> trim($this->input['outlink']),
		);
		if (!$data['title'])
		{
			$this->errorOutput('请填写标题');
		}
		if (!$data['start_time'])
		{
			$this->errorOutput('请填写开始时间');
		}
		if (!$data['end_time'])
		{
			$this->errorOutput('请填写结束时间');
		}
		if ($data['start_time'] > $data['end_time'])
		{
			$this->errorOutput('开始时间不能大于结束时间');
		}
		
		if(!$data['venue_id'])
		{
			$this->errorOutput('请选择场馆');
		}
		
		//添加座位图
		if($_FILES['seat_map'])
		{
			$file['Filedata'] = $_FILES['seat_map'];
			$res = $this->ticket->uploadToPicServer($file);
			if($res)
			{
				$seat_map = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['seat_map'] = serialize($seat_map);
			}
		}
		
		$content = $this->input['content'];
		$data['tel'] = '';
		if ($this->input['connect_tel'] && is_array($this->input['connect_tel']))
		{
			foreach ($this->input['connect_tel'] as $key=>$val)
			{
				$data['tel'][] = array(
					'start_time'=>$this->input['connect_start_time'][$key],
					'end_time'=>$this->input['connect_end_time'][$key],
					'tel'=>$val,
				);
			}
		}
		if (!empty($data['tel']))
		{
			$data['tel'] = addslashes(serialize($data['tel']));
		}
		
		/**************权限控制开始**************/
		if($data['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN('.$data['sort_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['create_content_status']==1)
			{
				$data['status'] = 0 ;
			}elseif ($this->user['prms']['default_setting']['create_content_status']==2)
			{
				$data['status'] = 1;
			}elseif ($this->user['prms']['default_setting']['create_content_status']==3)
			{
				$data['status'] = 2;
			}
		}
		
		/**************权限控制结束**************/
		$show_id = $this->ticket->add_show($data);
		
		if(!$show_id)
		{
			$this->errorOutput('创建演出失败');
		}
		//添加内容表		
		$show_content = array(
			'id'				=> $show_id,
			'content'			=> $content,
			'content_link'		=> trim($this->input['content_link']),
		);	
		$this->ticket->add_content($show_content);
		
		//存在明星id，插入明星行程表
		if($data['star_ids'])
		{
			$star_ids_arr = array();
			$star_ids_arr = explode(',', $data['star_ids']);
			if(!empty($star_ids_arr))
			{
				$sql = "INSERT INTO " . DB_PREFIX . "star_trip VALUES";
				foreach ($star_ids_arr as $k => $v)
				{
					$vals .= "(" . $v  . "," . $show_id . "," . $data['end_time'] . "),";	
				}
				
				if($vals)
				{
					$vals = rtrim($vals,',');
					$sql .= $vals;
					$this->db->query($sql);
				}
			}
		}
		//添加索引图
		if ($_FILES['Filedata'])
		{
			$mid = $this->ticket->add_indexPic($_FILES,$show_id);
		}
		
		//添加日志
		$sql = 'SELECT * FROM '.DB_PREFIX.'show WHERE id = '.$show_id;
		$res = $this->db->query_first($sql);
		$this->addLogs('添加票务', '', $res, $res['title'], $res['id'], $res['sort_id']);
		$this->addItem($show_id);
		$this->output();		
	}
	
	/**
	 * 
	 * @Description  审核，支持多条
	 * @author Kin
	 * @date 2013-5-6 上午11:29:56 
	 * @see adminUpdateBase::audit()
	 */
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = $this->input['id'];
		$audit = intval($this->input['audit']);
		/**************权限控制开始**************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'show WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$before = array();
		$sorts = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$before[] = $row;
		}
		if (empty($before))
		{
			return ;
		}
		$nodes = array();
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		
		if (!empty($before))
		{
			foreach ($before as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		/**************权限控制结束**************/
		$ret = $this->ticket->audit($ids,$audit);
		$after = array();
		foreach ($before as $key=>$val)
		{
			if ($val['status'] != $audit)
			{
				foreach ($val as $kk=>$vv)
				{
					if ($kk == 'status')
					{
						$after[$key]['status'] = $audit;
					}else {
						$after[$key][$kk] = $vv;
					}
				}
			}
		}
		//添加日志,数据状态未改变不添加日志
		if (!empty($after))
		{
			if ($audit == 1)
			{
				$this->addLogs('审核票务', $before, $after, '审核票务'.$ids);
			}
			if ($audit == 2) 
			{
				$this->addLogs('打回票务', $before, $after, '打回票务'.$ids);
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 
	 * @Description 售票状态控制
	 * @author Kin
	 * @date 2013-5-6 下午03:03:36
	 */
	public function sale_state()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = $this->input['id'];
		$state = intval($this->input['state']);
		
		/**************权限控制开始**************/
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'show WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$before = array();
		$sorts = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$before[] = $row;
		}
		
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			$nodes = array();
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
			$nodes['_action'] = 'manage';
			$this->verify_content_prms($nodes);
			
		}
		if (!empty($before))
		{
			foreach ($before as $val)
			{
				//$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
		/**************权限控制结束**************/
		$ret = $this->ticket->sale_state($ids, $state);
		$after = array();
		foreach ($before as $key=>$val)
		{
			if ($val['sale_state'] != $state)
			{
				foreach ($val as $kk=>$vv)
				{
					if ($kk=='sale_state')
					{
						$after[$key]['sale_state'] = $state;
					}
					else 
					{
						$after[$key][$kk] = $vv;
					}
				}
			}
			
		}
		//添加日志,票务状态未改变不添加日志
		if (!empty($after))
		{
			if ($state == 1)
			{
				$this->addLogs('改变票务状态', $before, $after, '改变票务状态为设计中'.$ids);
			}
			if ($state == 2)
			{
				$this->addLogs('改变票务状态', $before, $after, '改变票务状态为售票'.$ids);
			}
			if ($state == 3)
			{
				$this->addLogs('改变票务状态', $before, $after, '改变票务状态为结束'.$ids);
			}
		}
	
		$this->addItem($ret);
		$this->output();	
	}
	
	public function sort()
	{
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->addLogs('更改票务排序', '', '', '更改票务排序');
		$ret = $this->drag_order('show', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	
	//验证操作者权重权限是否足够
	function check_weight_prms($input_weight =  0, $org_weight = 0)
	{
		if($this->user['group_type'] < MAX_ADMIN_TYPE)
		{
			return;
		}
		$set_weight_limit = $this->user['prms']['default_setting']['set_weight_limit'];
		if(!$set_weight_limit)
		{
			return;
		}
		if($org_weight > $set_weight_limit)
		{
			$this->errorOutput(MAX_WEIGHT_LIMITED);
		}
		if($input_weight > $set_weight_limit)
		{
			$this->errorOutput(MAX_WEIGHT_LIMITED);
		}
	}
	
	//更新权重
	function update_weight()
	{
		//检测
		if(empty($this->input['data']))
		{
			$this->errorOutput(NO_DATA);
		}
		$data = $this->input['data'];
		$data = htmlspecialchars_decode($data);
		$data = json_decode($data,1);
		$id = @array_keys($data);
		if(!$id)
		{
			$this->errorOutput('id不存在');
		}
		$sql = 'SELECT id,weight FROM '.DB_PREFIX.'show WHERE id IN('.implode(',', $id).')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$org_weight[$row['id']] = $row['weight'];
		}
		$sql = "CREATE TEMPORARY TABLE tmp (id int primary key, weight int)";
		$this->db->query($sql);
		$sql = "INSERT INTO tmp VALUES ";
		$space = '';

		foreach ($data as $k => $v)
		{
			$sql .= $space . "(" . $k . ", ". $v .")";
			$this->check_weight_prms($v, $org_weight[$k]);
			$space = ',';
		}
		$this->db->query($sql);
		$sql = "UPDATE " . DB_PREFIX . "show a,tmp SET a.weight = tmp.weight WHERE a.id = tmp.id";
		$this->db->query($sql);
		$id = implode(',',$id);
		$this->addLogs('修改权重','','', '修改权重+' . $id);
		$this->addItem('true');
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new ticketUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();