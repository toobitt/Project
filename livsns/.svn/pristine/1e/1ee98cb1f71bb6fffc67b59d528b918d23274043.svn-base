<?php
define('MOD_UNIQUEID','cheapbuy');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/product_mode.php');
require_once(ROOT_PATH.'lib/class/recycle.class.php');
class product_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new product_mode();
		$this->recycle = new recycle();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$count_type 	= intval($this->input['count_type']);
		$amount 		= intval($this->input['amount']);
		if(!$amount)
		{
			$this->errorOutput('请填写商品数量');
		}
		$group_num 		= intval($this->input['group_num']);
		$max_num		= intval($this->input['max_num']);
		$id_limit		= intval($this->input['id_limit']);
		$front_money	= $this->input['front_money'];//定金
		$list_price		= $this->input['list_price'];
		$youhui_price	= $this->input['youhui_price'];
		if(!$youhui_price)
		{
			$this->errorOutput('请填写优惠后价格');
		}
		$fare			= $this->input['fare'];//运费
		$start_time 	= strtotime($this->input['start_time']);
		$end_time		= strtotime($this->input['end_time']);
		if(!$start_time || !$end_time)
		{
			$this->errorOutput('请填写截止时间');
		}
		$sort_id = intval($this->input['sort_id']);
		if(!$sort_id)
		{
			$this->errorOutput('请选择分类');
		}
		$company_id = intval($this->input['company_id']);
		if(!$company_id)
		{
			$this->errorOutput('请选择机构');
		}
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN('.$this->input['sort_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		//直播开始结束时间
		$live_start_time 	= strtotime($this->input['live_start_time']);
		$live_end_time		= strtotime($this->input['live_end_time']);
		
		$data = array(
			'title'				=> trim($this->input['title']),
			'brief'				=> trim($this->input['brief']),
			'company_id'		=> $company_id,
			'sort_id'			=> $sort_id,
			'type_id'			=> $this->input['type_id'],
			'cheap_policy'		=> trim($this->input['cheap_policy']),
			'indexpic_id'		=> $this->input['indexpic_id'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> hg_getip(),
			'need_address'		=> $this->input['need_address'],
			'need_email'		=> $this->input['need_email'],
		
			'count_type'		=> $count_type,
			'amount'			=> $amount,
			'group_num'			=> $group_num,
			'max_num'			=> $max_num,
			'id_limit'			=> $id_limit,
			'front_money'		=> $front_money,
			'list_price'		=> $list_price,
			'youhui_price'		=> $youhui_price,
			'fare'				=> $fare,
			'start_time'		=> $start_time,
			'end_time'			=> $end_time,
			//创建数据状态
			'status'    		=> $this->get_status_setting('create'),
			'prod_url'			=> trim($this->input['prod_url']),
			'channel_id'		=> intval($this->input['channel_id']),
			'live_start_time'	=> $live_start_time,	
			'live_end_time'		=> $live_end_time,
			'sale_base'			=> intval($this->input['sale_base']),
		);
		
		
		$ret = $this->mode->create($data);
		if($ret)
		{
			$id = $ret['id'];
			
			//更新素材表中素材内容id
			$img_id = $this->input['img_id'];
			if($img_id)
			{
				$img_id = implode(',', $img_id);
				
				$sql = "UPDATE ".DB_PREFIX."materials SET cid = {$id} WHERE id IN ({$img_id})";
				$this->db->query($sql);
				
			}
			
			//更新索引图id
			if($this->input['indexpic_id'])
			{
				$indexpic_id = intval($this->input['indexpic_id']);
			}
			else
			{
				$indexpic_id = $img_id[0];
			}
			
			//收录
			if($this->input['need_program_record'] && $live_start_time && $live_end_time)
			{
				$program_record_info = array(
					'prod_id'			=> $id,
					'channel_id'		=> intval($this->input['channel_id']),
					'live_start_time'	=> $live_start_time,
					'live_end_time'		=> $live_end_time,
					'app' 				=> APP_UNIQUEID,
					'filename'			=> 'product_update.php',
					'action'			=> 'update_program_record',
				);
				
				$pro_id = $this->mode->create_program_record($program_record_info);
				$pro_id = $pro_id['id'];
			}
			$sql = "UPDATE ".DB_PREFIX."product SET indexpic_id = ".$indexpic_id;
			if($pro_id)
			{
				$sql .= ",program_record_id = ".$pro_id;
			}
			$sql .= " WHERE id = ".$id;
			//file_put_contents('1.txt', $sql);
			$this->db->query($sql);
			
			//更新视频内容id
			$video_id = $this->input['video_id'];
			if($video_id)
			{
				$video_ids = implode(',', $video_id);
				$sql = "UPDATE ".DB_PREFIX."materials SET cid = {$id} WHERE id IN ({$video_ids})";
				$this->db->query($sql);
			}
			$this->addLogs('创建',$ret,'','创建' . $ret['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = intval($this->input['id']);
		
		$count_type 	= intval($this->input['count_type']);
		$amount 		= intval($this->input['amount']);
		if(!$amount)
		{
			$this->errorOutput('请填写商品数量');
		}
		$group_num 		= intval($this->input['group_num']);
		$max_num		= intval($this->input['max_num']);
		$id_limit		= intval($this->input['id_limit']);
		$front_money	= $this->input['front_money'];//定金
		$list_price		= $this->input['list_price'];
		$youhui_price	= $this->input['youhui_price'];
		if(!$youhui_price)
		{
			$this->errorOutput('请填写优惠后价格');
		}
		$fare			= $this->input['fare'];//运费
		$start_time 	= strtotime($this->input['start_time']);
		$end_time		= strtotime($this->input['end_time']);
		if(!$start_time ||!$end_time)
		{
			$this->errorOutput('请填写截止时间');
		}
				
		$sort_id = intval($this->input['sort_id']);
		if(!$sort_id)
		{
			$this->errorOutput('请选择分类');
		}
		
		$company_id = intval($this->input['company_id']);
		if(!$company_id)
		{
			$this->errorOutput('请选择机构');
		}
		
		//直播开始结束时间
		$live_start_time	= strtotime($this->input['live_start_time']);
		$live_end_time		= strtotime($this->input['live_end_time']);
		
		$data = array(
			'title'				=> trim($this->input['title']),
			'brief'				=> trim($this->input['brief']),
			'company_id'		=> $company_id,
			'sort_id'			=> $sort_id,
			'type_id'			=> $this->input['type_id'],
			'cheap_policy'		=> trim($this->input['cheap_policy']),
			'indexpic_id'		=> $this->input['indexpic_id'],
			
			'need_address'		=> $this->input['need_address'],
			'need_email'		=> $this->input['need_email'],
		
			'count_type'		=> $count_type,
			'amount'			=> $amount,
			'group_num'			=> $group_num,
			'max_num'			=> $max_num,
			'id_limit'			=> $id_limit,
			'front_money'		=> $front_money,
			'list_price'		=> $list_price,
			'youhui_price'		=> $youhui_price,
			'fare'				=> $fare,
			'start_time'		=> $start_time,
			'end_time'			=> $end_time,
			'prod_url'			=> trim($this->input['prod_url']),
			'channel_id'		=> intval($this->input['channel_id']),
			'live_start_time'	=> $live_start_time,	
			'live_end_time'		=> $live_end_time,
			'sale_base'			=> intval($this->input['sale_base']),
		);
		
		/**************权限控制开始**************/
		//源数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'product WHERE id = '.$id;
		$preData = $this->db->query_first($sql);
		//节点权限
							
		$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN (' . $preData['sort_id']. ',' . $data['sort_id'] . ')';
		$query = $this->db->query($sql);
		$sortInfo = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sortInfo[$row['id']] = $row['parents'];
		}
		//修改前
		if($preData['sort_id'])
		{
			$node['nodes'][$pre_data['sort_id']] = $sortInfo[$preData['sort_id']];
		}
		
		
		//修改后
		if($data['sort_id'])
		{
			$node['nodes'][$data['sort_id']] = $sortInfo[$data['sort_id']];
		}
		
		$this->verify_content_prms($node);	
		
		//能否修改他人数据
		$arr = array(
				'id'	  => $id,
				'user_id' => $preData['user_id'],
				'org_id'  => $preData['org_id'],
		);
		$this->verify_content_prms($arr);
		/**************权限控制结束**************/


		$ret = $this->mode->update($this->input['id'],$data);
		
		if($ret)
		{
			if ($this->db->affected_rows($query))
			{
				$additionalData = array(
					'update_time'		=> TIMENOW,
					'update_org_id'		=> $this->user['org_id'],
					'update_user_id'	=> $this->user['user_id'],
					'update_user_name'	=> $this->user['user_name'],
					'update_ip'			=> $this->user['ip'],
				);
				/**************权限控制开始**************/
				//修改审核数据后的状态
				if ($preData['status']==1 && $this->user['group_type'] > MAX_ADMIN_TYPE)
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
				
				$this->mode->update($this->input['id'],$additionalData);				
				/**************权限控制结束**************/
				
				$new_data = array_merge($data,$additionalData);
				$res = array_merge($preData, $new_data);
				
				$this->addLogs('更新商品', $preData, $res, $preData['title'], $preData['id'], $preData['sort_id']);
				
				//直播开始结束时间格式调整
				if($this->input['live_start_time'])
				{
					$dates_arr 		= explode(' ', $this->input['live_start_time']);
					$dates 			= $dates_arr[0];
					$rec_start_time = $dates_arr[1];
				}
				
				if($this->input['live_end_time'])
				{
					$dates_arr_end 		= explode(' ', $this->input['live_end_time']);
					$rec_end_time		= $dates_arr_end[1];
				}
				//收录
				if($this->input['need_program_record'] && $this->input['channel_id'] && $dates && $rec_start_time && $rec_end_time && $live_end_time > TIMENOW)
				{
					
					$program_record_info = array(
							'prod_id'			=> $id,
							'channel_id'		=> intval($this->input['channel_id']),
							//'live_start_time'	=> $live_start_time,
							//'live_end_time'	=> $live_end_time,
							'start_time'		=> $rec_start_time,
							'end_time'			=> $rec_end_time,
							'dates'				=> $dates,
							'filename'			=> 'product_update.php',
							'action'			=> 'update_program_record',
							'app' 				=> APP_UNIQUEID,
					);
					if(!$preData['program_record_id'])
					{
						$pro_id = $this->mode->create_program_record($program_record_info);
						if($pro_id)
						{
							$sql = "UPDATE " . DB_PREFIX . "product SET program_record_id = '" . $pro_id['id'] . "' WHERE id = '" . $id . "'";
							$this->db->query($sql);
						}
					}
					elseif($data['channel_id'] != $preData['channel_id'] || $live_start_time != $preData['live_start_time'] || $live_end_time != $preData['live_end_time'])
					{
						$program_record_info['id'] = $preData['program_record_id'];
						$pro_id = $this->mode->update_program_record($program_record_info);
					}
				}
			}
			
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		$ids = $this->input['id'];
		
		if(!$ids)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT count(*) as total FROM ".DB_PREFIX."order WHERE product_id IN (".$ids.")";
		$total = $this->db->query_first($sql);
		$num = $total['total'];
		if($num)
		{
			$this->errorOutput('请先删除商品下订单');
		}
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'product WHERE id IN ('.$ids.')';		
		$query = $this->db->query($sql);
		$sorts = array();
		$prod = array();
		$recycle = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$prod[$row['id']]  = $row;
			$recycle[$row['id']] = array(
				'cid'=>$row['id'],
				'title'=>$row['title'],
				'delete_people'=>$this->user['user_name'],
			);
			$recycle[$row['id']]['content']['product'] = $row;
		}
		//节点权限验证
		if($sorts && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$sorts = array_filter($sorts);
			if (!empty($sorts))
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN ('.implode(',',$sorts).')';
				$query = $this->db->query($sql);
				$nodes = array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
				if (!empty($nodes))
				{
					$this->verify_content_prms($nodes);
				}
			}
		}
		//能否修改他人数据
		if (!empty($prod) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach ($seekhelps as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
		
		
		//放入回收站
		if ($this->settings['App_recycle'] && !empty($recycle))
		{			
			foreach ($recycle as $infor)
			{
				$ret = $this->recycle->add_recycle($infor['title'], $infor['delete_people'], $infor['cid'], $infor['content']);
				$result = $ret['sucess'];
				$is_open = $ret['is_open'];
			}
			if (!$result)
			{
				$this->errorOutput('删除失败，数据不完整');
			}
			if ($is_open)
			{
				$sql = " DELETE FROM " .DB_PREFIX. "product WHERE id IN (" . $ids . ")";
				$this->db->query($sql);
				$data = $ids;
			}
			else
			{
				$data = $this->mode->delete($this->input['id']);
			} 
		}
		else
		{
			$data = $this->mode->delete($this->input['id']);
		}

		
		if($ret)
		{
			$this->addLogs('删除',$prod,'','删除' . $this->input['id']);
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function delete_comp()
	{
		$ids = $this->input['cid'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->mode->delete($ids);
		$this->addItem($data);
		$this->output();
	}


	public function audit()
	{
		$ids = $this->input['id'];
		if(!$ids)
		{
			$this->errorOutput(NOID);
		}
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			
			//节点权限验证
			$sql = 'SELECT * FROM '.DB_PREFIX.'product WHERE id IN ('.$ids.')';
			$query = $this->db->query($sql);
			$sorts = array();
			$nodes = array();
			while ($row = $this->db->fetch_array($query))
			{
				$sorts[] = $row['sort_id'];
			}
			if (!empty($sorts))
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
			}
			$this->verify_content_prms($nodes);
			//节点权限验证
		}
		$audit = intval($this->input['audit']);
		$ret = $this->mode->audit($ids,$audit);
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核' . $ids);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	
	//删除照片或者视频
	public function del_mater()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			return false;
		}
		
		$sql = "DELETE FROM ".DB_PREFIX."materials WHERE id = ".$id;
		$this->db->query($sql);
		
		$this->addItem('sucess');
		$this->output();
	}
	
	//设置索引图
	public function set_indexpic()
	{
		$id = $this->input['id'];
		$indexpic_id = $this->input['indexpic_id'];
		if(!$id || !$indexpic_id)
		{
			return false;
		}

		$sql = "UPDATE ".DB_PREFIX."product SET indexpic_id = ".$indexpic_id." WHERE id = ".$id;
		$this->addItem('sucess');
		$this->output();
	}
	
	
	//ajax上传图片
	public function upload_pic()
	{
		$_FILES['Filedata'] = $_FILES['videofile'];
		if($_FILES['Filedata'])
		{
		
			$cid = intval($this->input['id']);
			if($this->input['indexpic'] && $cid)
			{
				$cid = 0;//索引图不出现在图片列表中
			}	
			$PhotoInfor = $this->mode->uploadToPicServer($_FILES);
			if (empty($PhotoInfor))
			{
				return false;
			}
			$temp = array(
				'cid'			=> $cid,
				'type'			=> $PhotoInfor['type'],						
				'material_id'	=> $PhotoInfor['id'],
				'host'			=> $PhotoInfor['host'],
				'dir'			=> $PhotoInfor['dir'],
				'filepath' 		=> $PhotoInfor['filepath'],
				'filename'		=> $PhotoInfor['filename'],
			);
			//插入素材表
			$PhotoId = $this->mode->insert_material($temp);
			
			if($this->input['indexpic'] && $cid)
			{
				$sql = "UPDATE ".DB_PREFIX."product SET indexpic_id = ".$PhotoId." WHERE id = ".$cid;
				$this->db->query($sql);
			}	
			$pic_info = array(
				'host'		=> $PhotoInfor['host'],
				'dir'		=> $PhotoInfor['dir'],
				'filepath' 	=> $PhotoInfor['filepath'],
				'filename'	=> $PhotoInfor['filename'],
				'id'		=> $PhotoId,		
			);
			$this->addItem($pic_info);
			$this->output();
		}
	}
	
	//ajax上传视频
	public function upload_video()
	{
		//视频上传
		if ($_FILES['videofile'])
		{
			$video = array();
			//检测视频服务器
			if (!$this->settings['App_mediaserver'])
			{
				$this->errorOutput('视频服务器未安装!');
			}
			if ($_FILES['videofile']['error']>0)
			{
				$this->errorOutput('视频上传错误！');
			}
			//获取视频服务器上传配置
			$videoConfig = $this->mode->getVideoConfig();
			if (!$videoConfig)
			{
				$this->errorOutput('获取允许上传的视频类型失败！');
			}
			
			$filetype = strtolower(strrchr($_FILES['videofile']['name'], '.'));			
			if (!in_array($filetype, $videoConfig['type']))
			{
				$this->errorOutput('只允许上传'.$videoConfig['hit'].'格式的视频');
			}
			
			//上传视频服务器
			$videodata = $this->mode->uploadToVideoServer($_FILES);
			if (!$videodata)
			{
				$this->errorOutput('视频服务器错误!');
			}
			
			$cid = intval($this->input['id']);
			//视频入库
			$arr = array(
						'cid' 		 => $cid,
						'type'		 => $videodata['type'],
						'host'		 => $videodata['protocol'].$videodata['host'],
						'dir'		 => $videodata['dir'],
						'vodid'		 => $videodata['id'],
						'filename'	 => $videodata['file_name'],
					);
					
			$vid = $this->mode->insert_material($arr);
			
			$videodata['material_id'] = $vid;
			$videodata['video_url'] = $videodata['protocol'].$videodata['host'].'/'.$videodata['dir'].MANIFEST;
			$this->addItem($videodata);
			$this->output();
		}
	}
	
	public function change_live()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$status = intval($this->input['status']);
		
		$sql = "UPDATE ".DB_PREFIX."product SET use_live = {$status} WHERE id = ".$id;
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
	
	public function del_order()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$this->verify_content_prms(array('_action'=>'delete'));
		
		$id = $this->input['id'];
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "order WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
			$product_id = $r['product_id'];
		}
		if(!$pre_data)
		{
			return false;
		}
		
		$order_num = count($pre_data);
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "order WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		if($product_id && $order_num)
		{
			$sql = "UPDATE ".DB_PREFIX."product SET order_num = order_num - {$order_num} WHERE id = ".$product_id;
			$this->db->query($sql);
		}
		
		$this->addLogs('删除订单',$pre_data,'','删除' . $id);
		$this->addItem('success');
		$this->output();
		
	}
	
	
	public function audit_order()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$this->verify_content_prms(array('_action'=>'audit'));
		$id = $this->input['id'];
		$audit = intval($this->input['audit']);
		
		switch ($audit)
		{
			case 0:$status = 2;break;//打回
			case 1:$status = 1;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "order SET status = '" .$status. "' WHERE id IN ('" .$id. "')";
		$this->db->query($sql);
		
		$ret =  array('status' => $status,'id' => $id);
		
		
		if($ret)
		{
			$this->addLogs('审核订单','',$ret,'审核' . $id);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	
	/**
	  * 更新商品评论数目
	  * @name 		update_comment_count
	  */
	 function update_comment_count()
	 {
	 	if(empty($this->input['id']))
	 	{
	 		$this->errorOutput(NO_ID);
	 	}
	 	$id = intval($this->input['id']);
	 	//评论数
	 	if($this->input['comment_count'])
	 	{
	 		$comment_count = $this->input['comment_count'];
	 	}
	 	else
	 	{
	 		$comment_count = 1;
	 	}
	 	//审核增加评论数、打回减少评论数
	 	if($this->input['type'] == 'audit')
	 	{
	 		$type = '+';
	 	}
	 	else if($this->input['type'] == 'back')
	 	{
	 		$type = '-';
	 	}
	 	if($type)
	 	{
	 		$sql = "UPDATE " . DB_PREFIX . "product SET comment_num=comment_num" . $type . $comment_count . " WHERE id =" . $id ;
	 		$this->db->query($sql);
	 	}
	 	$return = array('status' => 1,'id'=> $id);
	 	$this->addItem($return);
	 	$this->output();
	 }

	public function sort()
	{
		$tableName = 'product';
				
		$ids = explode(',',urldecode($this->input['content_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . $tableName . " SET order_id = '" . $order_ids[$k] . "' WHERE id = '" . $v . "'";
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();

	}
	
	/**
	 * 回调更新收录的视频到商品下面
	 * Enter description here ...
	 */
	public function update_program_record()
	{
		$vodid = intval($this->input['vodid']);
		if(!$vodid)
		{
			return false;
		}
		
		$product_id = intval($this->input['prod_id']);
		if(!$product_id)
		{
			return false;
		}
		
		$sql = "REPLACR INTO ".DB_PREFIX."materials SET vodid = ".$vodid . ",cid=".$product_id;
		$this->db->query($sql);
	}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new product_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>