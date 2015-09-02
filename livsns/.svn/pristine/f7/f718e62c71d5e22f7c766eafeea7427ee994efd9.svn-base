<?php
define('MOD_UNIQUEID','lottery');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/lottery_mode.php');
require_once(CUR_CONF_PATH . 'lib/template.class.php');
class lottery_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new lottery_mode();
		$this->template = new template_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$title = trim($this->input['title']);
		if(!$title)
		{
			$this->errorOutput('请填写标题');
		}
		
		$type = intval($this->input['type_id']);
		if(!$type)
		{
			$this->errorOutput('请选择抽奖活动类型');
		}
		
		$img_id = $this->input['img_id'];
			
		$indexpic_id = '';
		if($this->input['indexpic_id'])
		{
			$indexpic_id = intval($this->input['indexpic_id']);
			$img_id[] = $indexpic_id;
		}
		else if($img_id)
		{
			$indexpic_id = $img_id[0];
		}
		else 
		{
			$indexpic_id = '';
		}
			
		$sort_id = intval($this->input['sort_id']);
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $sort_id)
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN('.$sort_id.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		
		//抽奖开始结束时间
		if($this->input['time_limit'] && (!$this->input['start_time'] || !$this->input['end_time']))
		{
			$this->errorOutput('请输入开始结束日期');
		}
		
		
		if($this->input['start_time'] && $this->input['end_time'])
		{
		
			$this->input['start_hour'] = $this->input['start_hour'] ? $this->input['start_hour'] : '00:00:00';
	        $this->input['end_hour'] = $this->input['end_hour'] ? $this->input['end_hour'] : '23:59';
	        
	        
			$start_time = strtotime($this->input['start_time'] . ' ' . $this->input['start_hour']);
			$end_time	= strtotime($this->input['end_time'] . ' ' . $this->input['end_hour']);
			
			if($end_time <= $start_time)
			{
				$this->errorOutput('结束时间必须大于开始时间');
			}
			
			$this->input['start_hour'] = strtotime($this->input['start_hour']);
	        $this->input['end_hour'] = strtotime($this->input['end_hour']);
	        
	        
	        if ($this->input['start_hour'] >= $this->input['end_hour']) 
	        {
	        	$this->errorOutput('开始时间不能大于等于结束时间');
	        }
	        
	        $this->input['start_hour'] = date('His', $this->input['start_hour']);
	        $this->input['end_hour'] = date('His', $this->input['end_hour']);
		}
        
        
		//未中奖反馈
		if($this->input['no_lottery_feedback'])
		{
			$feedback = serialize($this->input['no_lottery_feedback']);
		}
		
		if($this->input['cycle_type'] == 'week')
		{
			$cycle_type = 1;
		}
		elseif ($this->input['cycle_type'] == 'month')
		{
			$cycle_type = 2;
		}
		
		$data = array(
			'title'				=> trim($this->input['title']),
			'brief'				=> trim($this->input['brief']),
			'sort_id'			=> $sort_id,
			'type'				=> $type,
			'rule'				=> trim($this->input['rule']),
			'indexpic_id'		=> $indexpic_id,
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> hg_getip(),
			'ip_limit'			=> intval($this->input['ip_limit']),
			'ip_limit_time'		=> intval($this->input['ip_limit_time']),
			'ip_limit_num'		=> intval($this->input['ip_limit_num']),
		
			'time_limit'		=> intval($this->input['time_limit']),
			'start_time'		=> $start_time,
			'end_time'			=> $end_time,
		
			'register_time'		=> strtotime($this->input['register_time']),
			'score_limit'		=> intval($this->input['score_limit']),
			'need_score'		=> intval($this->input['need_score']),
		
			'num_limit'			=> intval($this->input['num_limit']),
			'version_limit'		=> trim($this->input['version_limit']),
			'account_limit'		=> intval($this->input['account_limit']),
			'device_limit'		=> intval($this->input['device_limit']),
			'device_limit_time'	=> intval($this->input['device_limit_time']),
			'device_num_limit'	=> intval($this->input['device_num_limit']),
			
			'area_limit'		=> intval($this->input['area_limit']),
			'baidu_longitude'	=> trim($this->input['baidu_longitude']),
			'baidu_latitude'	=> trim($this->input['baidu_latitude']),
			'address'			=> trim($this->input['address']),
			'distance'			=> intval($this->input['distance']),
			'feedback'			=> $feedback,
			//创建数据状态
			'status'    		=> $this->get_status_setting('create'),
			'template_id'		=> $this->input['template_id'] ? intval($this->input['template_id']) : $this->settings['sign'][$this->input['sign']],		//选择的模板id
			//周期数据
			'start_hour'		=> $this->input['start_hour'],
			'end_hour'			=> $this->input['end_hour'],
			'cycle_type'		=> $cycle_type,
			'cycle_value'		=> trim($this->input['cycle_value']),
		
			'notstartdesc'		=> trim($this->input['notstartdesc']),
			'finish_desc'		=> trim($this->input['finish_desc']),
		
			'lottery_limit'		=> intval($this->input['lottery_limit']),
			'exchange_switch'	=> intval($this->input['exchange_switch']),
		
			'win_limit'			=> intval($this->input['win_limit']),
			'win_num_limit'		=> intval($this->input['win_num_limit']),
		
			'lottery_bg'		=> intval($this->input['lottery_bg']),
		);
		
		if($data['baidu_longitude'] && $data['baidu_latitude'])
		{
			$gps = $this->mode->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		
		$id = $this->mode->create($data);
		if($id)
		{
			//更新素材表中素材内容id
			if(is_array($img_id) && count($img_id))
			{
				$img_id = implode(',', $img_id);
				
				$sql = "UPDATE ".DB_PREFIX."materials SET cid = {$id} WHERE id IN ({$img_id})";
				$this->db->query($sql);
			}
			
			//奖项处理
			$award_name = $this->input['award_name'];
			if($award_name)
			{
				$add_arr = array();
				foreach ($award_name as $k => $v)
				{
					$add_arr[$k]['name'] 		= $v;
					$add_arr[$k]['type'] 		= $this->input['award_type'][$k+1];
					$add_arr[$k]['prize'] 		= $this->input['award'][$k];
					$add_arr[$k]['prize_num'] 	= $this->input['award_num'][$k];
					$add_arr[$k]['chance'] 		= $this->input['award_probability'][$k];
					$add_arr[$k]['tip'] 		= $this->input['award_feedback'][$k];
					$add_arr[$k]['indexpic_id']	= $this->input['award_indexpic'][$k];
					$add_arr[$k]['seller_id']	= $this->input['seller_id'][$k];
				}
			}
			
			//新增票
			if($add_arr)
			{
				$this->add_award($add_arr, $id);
			}
			
			$data['id'] = $id;
			
			$this->addLogs('创建',$data,'','创建' . $id);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$type = intval($this->input['type_id']);
		if(!$type)
		{
			$this->errorOutput('请选择抽奖活动类型');
		}
		
		$sort_id = intval($this->input['sort_id']);
		//抽奖开始结束时间
		$start_time = strtotime($this->input['start_time']);
		$end_time	= strtotime($this->input['end_time']);
		
		
		$img_id = $this->input['img_id'];
		//更新索引图id
		if($this->input['indexpic_id'])
		{
			$indexpic_id = intval($this->input['indexpic_id']);
			$img_id[] = $indexpic_id;
		}
		else if($img_id)
		{
			$indexpic_id = $img_id[0];
		}
		else 
		{
			$indexpic_id = '';
		}
		
		if($this->input['no_lottery_feedback'])
		{
			$feedback = serialize($this->input['no_lottery_feedback']);
		}
		
		
		//抽奖开始结束时间
		if($this->input['time_limit'] && (!$this->input['start_time'] || !$this->input['end_time']))
		{
			$this->errorOutput('请输入开始结束日期');
		}
		
		
		if($this->input['start_time'] && $this->input['end_time'])
		{
		
			$this->input['start_hour'] = $this->input['start_hour'] ? $this->input['start_hour'] : '00:00:00';
	        $this->input['end_hour'] = $this->input['end_hour'] ? $this->input['end_hour'] : '23:59';
	        
	       
			$start_time = strtotime($this->input['start_time'] . ' ' . $this->input['start_hour']);
			$end_time	= strtotime($this->input['end_time'] . ' ' . $this->input['end_hour']);
			if($end_time <= $start_time)
			{
				$this->errorOutput('结束时间必须大于开始时间');
			}
			
			$this->input['start_hour'] = strtotime($this->input['start_hour']);
	        $this->input['end_hour'] = strtotime($this->input['end_hour']);
	        
	        
	        if ($this->input['start_hour'] >= $this->input['end_hour']) 
	        {
	        	$this->errorOutput('开始时间不能大于等于结束时间');
	        }
	        
	        $this->input['start_hour'] = date('His', $this->input['start_hour']);
	        $this->input['end_hour'] = date('His', $this->input['end_hour']);
		}
		
		
		if($this->input['cycle_type'] == 'week')
		{
			$cycle_type = 1;
		}
		elseif ($this->input['cycle_type'] == 'month')
		{
			$cycle_type = 2;
		}
		
		$data = array(
			'title'				=> trim($this->input['title']),
			'brief'				=> trim($this->input['brief']),
			'sort_id'			=> $sort_id,
			'type'				=> $type,
			'rule'				=> trim($this->input['rule']),
			'indexpic_id'		=> $indexpic_id,
			//'update_time'		=> TIMENOW,
			//'org_id'			=> $this->user['org_id'],
			//'user_id'			=> $this->user['user_id'],
			//'user_name'		=> $this->user['user_name'],
			//'ip'				=> hg_getip(),
			'ip_limit'			=> intval($this->input['ip_limit']),
			'ip_limit_time'		=> intval($this->input['ip_limit_time']),
			'ip_limit_num'		=> intval($this->input['ip_limit_num']),
		
			'time_limit'		=> intval($this->input['time_limit']),
			'start_time'		=> $start_time,
			'end_time'			=> $end_time,
		
			'register_time'		=> strtotime($this->input['register_time']),
			'score_limit'		=> intval($this->input['score_limit']),
			'need_score'		=> intval($this->input['need_score']),
		
			'num_limit'			=> intval($this->input['num_limit']),
			'version_limit'		=> trim($this->input['version_limit']),
			'account_limit'		=> intval($this->input['account_limit']),
		
			'device_limit'		=> intval($this->input['device_limit']),
			'device_limit_time'	=> intval($this->input['device_limit_time']),
			'device_num_limit'	=> intval($this->input['device_num_limit']),
			
			'area_limit'		=> intval($this->input['area_limit']),
			'baidu_longitude'	=> trim($this->input['baidu_longitude']),
			'baidu_latitude'	=> trim($this->input['baidu_latitude']),
			'address'			=> trim($this->input['address']),
			'distance'			=> intval($this->input['distance']),
			'feedback'			=> $feedback,
		
			//周期数据
			'start_hour'		=> $this->input['start_hour'],
			'end_hour'			=> $this->input['end_hour'],
			'cycle_type'		=> $cycle_type,
			'cycle_value'		=> trim($this->input['cycle_value']),
		
			'notstartdesc'		=> trim($this->input['notstartdesc']),
			'finish_desc'		=> trim($this->input['finish_desc']),
		
			'lottery_limit'		=> intval($this->input['lottery_limit']),
			'exchange_switch'	=> intval($this->input['exchange_switch']),
		
			'win_limit'			=> intval($this->input['win_limit']),
			'win_num_limit'		=> intval($this->input['win_num_limit']),
		);
		
		if(intval($this->input['lottery_bg']))
		{
			$data['lottery_bg'] = intval($this->input['lottery_bg']);
		}
		
		if($data['baidu_longitude'] && $data['baidu_latitude'])
		{
			$gps = $this->mode->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		
		
		/**************权限控制开始**************/
		//源数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'lottery WHERE id = '.$id;
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
		
		//验证节点权限
		$this->verify_content_prms($node);	
		
		//能否修改他人数据
		$arr = array(
				'id'	  => $id,
				'user_id' => $preData['user_id'],
				'org_id'  => $preData['org_id'],
		);
		$this->verify_content_prms($arr);
		/**************权限控制结束**************/
		
		
		
		$ret = $this->mode->update($id,$data);
		
		if($ret)
		{
			$update_tag = false;
			if ($this->db->affected_rows($query))
			{
				$update_tag = true;
			}
			
			//删除需要删除的图片
			$del_ids = $this->input['del_id'];
			if($del_ids)
			{
				$sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE id IN ('.$del_ids.')';
				$this->db->query($sql);
				$update_tag = true;
			}
				
			//更新素材表中素材内容id
			if(is_array($img_id) && count($img_id))
			{
				$img_id = implode(',', $img_id);
				
				$sql = "UPDATE ".DB_PREFIX."materials SET cid = {$id} WHERE id IN ({$img_id})";
				$this->db->query($sql);
				if ($this->db->affected_rows($query))
				{
					$update_tag = true;
				}
			}
		
			$award_id = array();
			$award_id = $this->input['award_id'];
			if($award_id)
			{
				$award_info = array();
				foreach ($award_id as $k => $v)
				{
					if($v == 'add')
					{
						$add_arr[$k]['name'] 		= $this->input['award_name'][$k];
						$add_arr[$k]['type'] 		= $this->input['award_type'][$k+1];
						$add_arr[$k]['prize'] 		= $this->input['award'][$k];
						$add_arr[$k]['prize_num'] 	= $this->input['award_num'][$k];
						$add_arr[$k]['chance'] 		= $this->input['award_probability'][$k];
						$add_arr[$k]['tip'] 		= $this->input['award_feedback'][$k];
						$add_arr[$k]['indexpic_id']	= $this->input['award_indexpic'][$k];
						$add_arr[$k]['seller_id']	= $this->input['seller_id'][$k];
						continue;
					}
					$award_info[$v]['name'] 		= $this->input['award_name'][$k];
					$award_info[$v]['type'] 		= $this->input['award_type'][$k+1];
					$award_info[$v]['prize'] 		= $this->input['award'][$k];
					$award_info[$v]['prize_num'] 	= $this->input['award_num'][$k];
					$award_info[$v]['chance'] 		= $this->input['award_probability'][$k];
					$award_info[$v]['tip'] 			= $this->input['award_feedback'][$k];
					$award_info[$v]['indexpic_id']	= $this->input['award_indexpic'][$k];
					$award_info[$v]['seller_id']	= $this->input['seller_id'][$k];
				}
			}
			
			//查询场次下的票
			$sql = "SELECT id FROM " . DB_PREFIX . "prize WHERE lottery_id = " . $id;
			$q = $this->db->query($sql);
			
			$prize_id_old = array();
			while ($r = $this->db->fetch_array($q))
			{
				$prize_id_old[] = $r['id'];
			}
			
			$award_id = $award_id ? $award_id : array();
			$del_arr = array_diff($prize_id_old, $award_id);
			//$upd_arr = array_intersect($award_id,$prize_id_old);
			
			//更新票信息
			if($award_info)
			{
				foreach ($award_info as $key => $data)
				{
					//更新数据
					$sql = " UPDATE " . DB_PREFIX . "prize SET ";
					foreach ($data AS $k => $v)
					{
						$sql .= " {$k} = '{$v}',";
					}
					$sql  = trim($sql,',');
					$sql .= " WHERE id = '"  .$key. "'";
					$this->db->query($sql);
					
					if ($this->db->affected_rows($query))
					{
						$update_tag = true;
					}
				}
			}
			//删除票信息
			if($del_arr)
			{
				$del_ids = implode(',',$del_arr);
				$sql = 'DELETE FROM '.DB_PREFIX.'prize WHERE id IN ('.$del_ids.')';
				$this->db->query($sql);
				if ($this->db->affected_rows($query))
				{
					$update_tag = true;
				}
			}
			
			//新增票
			if($add_arr)
			{
				$this->add_award($add_arr, $id);
				$update_tag = true;
			}
			
			
			if($update_tag)
			{
				$additionalData = array(
					'update_time'		=> TIMENOW,
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
				
				$this->mode->update($id,$additionalData);
				
				$new_data = array_merge($data,$additionalData);
				$res = array_merge($preData, $new_data);
				
				$this->addLogs('更新', $preData, $res, $preData['name'], $preData['id'], $preData['sort_id']);
				
				//开启缓存后,更新抽奖活动,触发活动更新
				if($this->settings['lottery_filter'])
				{
					include_once CUR_CONF_PATH . 'cron/lottery_filter_plan.php';
					$run_obj = new LotteryFilter();
					$run_obj->run();
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
		
		
		/**************权限控制开始**************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'lottery WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		
		$before 	= array();
		$sorts 		= array();
		$recycle 	= array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			//回收站
			$recycle[$row['id']] = array(
									'delete_people' 	=> trim($this->user['user_name']),
									'title' 			=> $row['title'],
									'cid' 				=> $row['id'],
									'catid' 			=> $row['sort_id'],
									'user_id'			=> $row['user_id'],
									'org_id'			=> $row['org_id'],
								);
			$recycle[$row['id']]['content']['lottery'] = $row;
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
			$nodes['_action'] = 'delete';
			$this->verify_content_prms($nodes);
		}
		//能否修改他人数据
		if (!empty($before) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach ($before as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'delete'));
			}
		}
		/**************权限控制结束**************/
		
		//放入回收站
		if ($this->settings['App_recycle'] && !empty($recycle))
		{			
			include_once(ROOT_PATH . 'lib/class/recycle.class.php');
			$this->recycle = new recycle();
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
		}
			
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除',$ret,'','删除' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	
	public function delete_comp()
	{
		$id = $this->input['cid'];
		if (!$id)
		{
			return false;
		}
		//删除中奖信息
		$sql = " DELETE FROM " .DB_PREFIX. "win_info WHERE lottery_id IN (" . $id . ")";
		$this->db->query($sql);
		
		//删除抽奖下的奖品
		$sql = " DELETE FROM " .DB_PREFIX. "prize WHERE lottery_id IN (" . $id . ")";
		$this->db->query($sql);
		
		//删除抽奖下的素材
		$sql = " DELETE FROM " .DB_PREFIX. "materials WHERE cid IN (" . $id . ")";
		$this->db->query($sql);
		
		return true;
	}
		
	public function audit()
	{
		$ids = $this->input['id'];
		if(!$ids)
		{
			$this->errorOutput(NOID);
		}
		
		
		//节点权限验证
		$sql = 'SELECT sort_id FROM '.DB_PREFIX.'lottery WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$sorts = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
		}
		if (!empty($sorts))
		{
			$nodes = array();
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		//节点权限验证
		
		$audit = intval($this->input['audit']);
		
		$ret = $this->mode->audit($this->input['id'],$audit);
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
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

		$sql = "UPDATE ".DB_PREFIX."lottery SET indexpic_id = ".$indexpic_id." WHERE id = ".$id;
		$this->addItem('sucess');
		$this->output();
	}
	//删除照片
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
	
	//ajax上传图片
	public function upload_pic()
	{
		if($_FILES['Filedata'])
		{
			$cid = intval($this->input['id']);
			
			include_once(ROOT_PATH . 'lib/class/material.class.php');
			$this->material = new material();
			
			$PhotoInfor = $this->material->addMaterial($_FILES); //插入图片服务器
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
				'imgwidth'		=> $PhotoInfor['imgwidth'],
				'imgheight'		=> $PhotoInfor['imgheight'],
			);
			//插入素材表
			$PhotoId = $this->mode->insert_material($temp);
			
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
	
	
	//奖项入库
	public function add_award($add_arr,$lottery_id)
	{
		if(!$add_arr)
		{
			return FALSE;
		}
		
		$add_sql = "INSERT INTO ".DB_PREFIX."prize (name, type, lottery_id, indexpic_id, prize, prize_num,chance,tip,seller_id) VALUES";
		foreach ($add_arr as $v)
		{
			if(empty($v))
			{
				continue;
			}
			$vals.= "('".$v['name']."','" . $v['type'] . "', ".$lottery_id.",'".$v['indexpic_id']."','".$v['prize']."','".$v['prize_num']."','".$v['chance']."','".$v['tip']."','".$v['seller_id']."'),";
		}
		if($vals)
		{
			$vals = rtrim($vals,',');
			$add_sql .= $vals;
			$this->db->query($add_sql);
		}
	}
	
	public function create_form()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$lottery = $this->mode->detail($id);
		
		if(!$lottery)
		{
			$this->errorOutput(NO_DATA);
		}
		
		if(!defined('LOTTERY_DOMAIN') || !LOTTERY_DOMAIN)
		{
			$this->errorOutput(ERROR_URL);
		}
		
		$url = LOTTERY_DOMAIN.'lottery.php';
		//我的中奖记录地址
		$winlist_url = $this->settings['winlist_url'];
		
		if($this->settings['lottery_win_info'] && $winlist_url)
		{
			$winlist_url .= '?lottery_id='.$id;
		}
		$type = $lottery['type'];
		$prize = array();
		
		if(!empty($lottery['prize']) && count($lottery['prize']))
		{
			foreach ($lottery['prize'] as $key => $val)
			{
				unset($val['chance'],$val['prize_win'],$val['prize_num'],$val['tip']);
				
				$prize[$key] = $val;
			}
		}
		
		
		$form = '';
		$form .= '<!DOCTYPE html>';
		$form .= '<html>';
		$form .= '<head>';
		$form .= '<meta charset="utf-8" />';
		$form .= '<meta name="description" content="">';
		$form .= '<meta name="HandheldFriendly" content="True">';
		$form .= '<meta name="MobileOptimized" content="320">';
		$form .= '<meta name="apple-mobile-web-app-capable" content="yes">';
		$form .= '<meta name="apple-mobile-web-app-status-bar-style" content="black">';
		$form .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
		
		$form .= '<link rel="stylesheet" type="text/css" href="../css/lottery_preview.css" />';
		if( $lottery['lottery_bg'] )
		{
			$lottery_bg = $lottery['lottery_bg'];
			
			$form .= '<style>';
			$form .= 'body{background-image:url("'. $lottery_bg['host'] . $lottery_bg['dir'] . $lottery_bg['filepath'] . $lottery_bg['filename']. '")!important;}';
			$form .= '</style>';
		}
		//hg_pre($lottery_bg,0);
		$form .= '<script type="text/javascript">';
		$form .= 'var RESOURCE_URL = "' . $url .'";';
		$form .= 'var globalprize = ' .json_encode( $prize ) .';';
		$form .= '</script>';
		
		
		$form .= '<script type="text/javascript" src="../js/common/device.min.js"></script>';
		$form .= '<script type="text/javascript" src="../js/common/zepto.js"></script>';
		$form .= '<script type="text/javascript" src="../js/common/spin.min.js"></script>';
		$form .= '<script type="text/javascript" src="../js/common/viewPC.js"></script>';
		$form .= '<script type="text/javascript" src="../js/common/print.js"></script>';
		$form .= '<script type="text/javascript" src="../js/common/api.js"></script>';
		$form .= '<script type="text/javascript" src="../js/common/template.js"></script>';
		$form .= '<script type="text/javascript" src="../js/common/lottery.js"></script>';
		if( $type == '1' ){
			$form .= '<script type="text/javascript" src="../js/ggk/hg_scratch.js"></script>';
			$form .= '<script type="text/javascript" src="../js/ggk/myggk.js"></script>';
		}
		if( $type == '2' ){
			$form .= '<script type="text/javascript" src="../js/zhuanpan/hg_spin.js"></script>';
			$form .= '<script type="text/javascript" src="../js/zhuanpan/myspin.js"></script>';
		}
		$form .= '<title>'.$lottery['title'].'</title>';
		$form .= '</head>';
		if( $type == '1' ){
			$form .= '<body class="ggk-body">';
			$form .= '<div class="body-mask"></div>';
			$form .= '<div class="ggk-game-box lottery-game-box" _id="' . $lottery['id'] . '">';
		}
		if( $type == '2' ){
			$form .= '<body class="zhuanpan-body">';
			$form .= '<div class="body-mask"></div>';
			$form .= '<div class="zhuanpan-game-box lottery-game-box" _id="' . $lottery['id'] . '">';
		}
		$form .= '<div>';
		if( $type == '1' ){
				$form .= '<div class="ggk-time-box"><div class="ggk-time"><span>' . $lottery['effective_time'] .  '</span></div></div>';
				$form .= '<div class="ggk-prizenumber">' .$lottery['title'] .'</div>';
				$form .= '<div class="ggk-wrap lottery-canvas-wrap">';
				$form .= '<div class="ggk-inner">';
				$form .= '<div class="ggk-canvas-area" id="ggk-canvas"></div>';
				$form .= '<div class="ggk-mask lottery-mask"></div>';
				$form .= '</div>';
				$form .= '<a class="ggk-game-btn">再刮一次</a>';
				if($this->settings['winlist'])
				{
					$form .= '<a class="my-lottery-btn" href=' . $winlist_url . '>我的中奖列表</a>';
				}
				$form .= '</div>';
		}
		if( $type == '2' ){
			$form .='<div class="zhuanpan-head"></div>';
			$form .='<div class="zhuanpan-wrap lottery-canvas-wrap">';
			$form .='<canvas id="zhuanpan-canvas" class="zhuanpan-canvas-area" width="260" height="300"></canvas>';
			$form .='<div class="zhuanpan-arrow"></div>';
			$form .='<a class="zhuanpan-start-btn lottery-start-btn">再玩一次</a>';
			$form .= '<div class="ggk-mask lottery-mask"></div>';
			$form .='</div>';
		}
		
		$form .= '<div class="ggk-awards-pop lottery-awards-pop">';
		$form .= '<div class="ggk-awards-pop-inner">';
		$form .= '<div class="close"></div>';
		$form .= '<div class="awards-tip-box lottery-result-box">';
		$form .= '<div class="awards-tip">';
		$form .= '</div>';
		$form .= '<div class="other-lottery-info"></div>';
		$form .= '</div>';
		$form .= '<div class="lottery-personinfo-box form-lottery-info">';
		$form .= '<div><span>联系电话：</span><input type="tel" class="tel-txt" /></div>';
		$form .= '<div><span>联系地址：</span><textarea class="address-txtarea"></textarea></div>';
		$form .= '<div class="submit lottery-submit">提交</div>';
		$form .= '</div>';
		$form .= '</div>';
		$form .= '</div>';
		$form .= '<div class="lottery-nologin-area">';
		$form .= '<a class="lottery-refresh">刷新</a><a class="lottery-gologin">登录</a>';
		$form .= '</div>';
		$form .= '</div>';
		$form .= '<div class="lottery-limit-tip">';
		$form .= '<div class="lottery-limit-tip-area">';
		$form .= '<div class="msg"></div>';
		$form .= '<div class="controll">';
		$form .= '<span class="sure sure-mask">确定</span>';
		$form .= '<span class="cancle cancle-mask">取消</span>';
		$form .= '</div>';
		$form .= '</div>';
		$form .= '</div>';
		if( $type == '2' ){
			if($this->settings['winlist'])
			{
				$form .= '<a class="my-lottery-btn my-lottery-zhuanpanbtn" href=' . $winlist_url . '>我的中奖列表</a>';
			}
		}
		$form .= '<div class="lottery-shengming">本活动与苹果公司无关</div>';
		$form .= '</div>';
		
		$form .= '<script id="people-lottery-info" type="text/html">';
		$form .= '{{each list as value i}}';
		$form .= '<div class="item">';
		$form .= '<span class="member-name">{{value.member_name}}</span><span class="member-prize">{{value.prize}}</span><span class="member-time">{{value.create_time}}</span>';
		$form .= '</div>';
		$form .= '{{/each}}';
		$form .= '</script>';
		
		$form .= '<script id="lottery-tip-info" type="text/html">';
		$form .= '{{if !+id}}<div class="cry-icon"></div>{{/if}}';
		$form .= '<span class="awards-name {{if !+id}}no-awards-name{{/if}}">{{tip}}</span>';
		$form .= '{{if +id}}<div>恭喜你!</div>{{/if}}';
		$form .= '{{if +id}}<div class="awards-duijiang lottery-reward-btn"><a href="' . $winlist_url . '">去兑奖</a></div>{{/if}}';
		$form .= '{{if pic}}<div class="awards-pic"><img src="{{pic}}" /></div>{{/if}}';
		$form .= '</script>';
		
		
		$form .= '</body>';
		$form .= '</html>';
		
		
		$souce_dir =  CUR_CONF_PATH . 'core/';
		$dir = CUR_CONF_PATH . 'data/';
		
		
		if(!is_writeable($dir))
		{
			$this->errorOutput(NOWRITE);
		}
		
		/**生成入口文件**/
		//if(!file_exists($dir.'lottery.php'))
		{
			copy($souce_dir.'lottery.php',$dir.'lottery.php');
		}
		
		file_copy($souce_dir.'css/', $dir.'css/');
		file_copy($souce_dir.'js/', $dir.'js/');
		file_copy($souce_dir.'images/', $dir.'images/');
		
		/**生成入口文件结束**/
		$html_dir = $dir.$lottery['create_time'].$lottery['id'].'/';
		hg_mkdir($html_dir);
		
		hg_file_write($html_dir.'/' . $lottery['id'].'.html', $form,'wb+');
		
		$this->addItem($form);
		$this->output();
	}
	
	/**
	 * 云表单使用需要选择模板
	 */
	public function generate()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$lottery = $this->mode->detail($id);
		if(!$lottery)
		{
			$this->errorOutput(NO_DATA);
		}
		
		if(!defined('LOTTERY_DOMAIN') || !LOTTERY_DOMAIN)
		{
			$this->errorOutput(ERROR_URL);
		}

		$dir = $this->template->get_template($lottery['type']);
		
		//文件路径加密
		include_once(CUR_CONF_PATH . 'lib/XDeode.php');
		$this->script = new XDeode();
		$dir_file = $this->script->encode($lottery['user_id']);
    	$filename = $this->script->encode($lottery['id']);
    	
 		$lottery['winlist_url'] = $this->settings['winlist_url'];
		$lottery['prize'] = json_encode($lottery['prize']);
    	$lottery['assist_url'] = LOTTERY_DOMAIN .$dir['sign'].'/'.$dir['theme'].'/'.$dir['id'];
		$lottery['url'] = LOTTERY_DOMAIN.'lottery.php';
    	
		//生成内容静态页
    	$content = $this->template->generation($lottery,$dir['template_file']);
		if(!$content)
		{
			$this->errorOutput('生成模板失败');
		}
   		$html_dir = DATA_DIR.$dir_file.'/';
		if(!is_dir($html_dir))
		{
			hg_mkdir($html_dir);
		}
		hg_file_write($html_dir.'/' . $filename.'.html', $content,'wb+');
		
		//生成辅助文件
		if(!$this->template->create_file(array('lottery.php')))
		{
			$this->errorOutput('生成辅助文件失败');
		}
		//生成js/css/images辅助文件
		if(!$this->template->generate_assist($dir['style_dir'],$dir['sign'],$dir['theme'],$dir['id']))
		{
			$this->errorOutput('生成辅助文件失败');
		}
		
   		if(file_exists($html_dir.'/'.$filename.'.html'))
		{
			$ret['state'] = 1;
			$ret['url'] = LOTTERY_DOMAIN.$dir_file.'/'.$filename.'.html';
		}
		else 
		{
			$ret['state'] = 0;
		}
		$this->addItem($ret);
		$this->output();
	}
		
	public function sort()
	{
		$table_name = 'lottery';
		$order_name = 'order_id';
		$content_ids = explode(',', $this->input['content_id']);
        $order_ids   = explode(',', $this->input['order_id']);
        if(!$content_ids)
        {
        	$this->errorOutput('内容id不存在');
        }
        foreach ($content_ids as $k => $v)
        {
            $sql = "UPDATE " . DB_PREFIX . $table_name . "  SET " . $order_name . " = '" . $order_ids[$k] . "'  WHERE id = '" . $v . "'";
            $this->db->query($sql);
        }
        
		//开启缓存后,更新抽奖活动,触发活动更新
		if($this->settings['lottery_filter'])
		{
			include_once CUR_CONF_PATH . 'cron/lottery_filter_plan.php';
			$run_obj = new LotteryFilter();
			$run_obj->run();
		}
				
        $this->addItem('success');
        $this->output();
	}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new lottery_update();
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