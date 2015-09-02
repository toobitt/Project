<?php
define('MOD_UNIQUEID','tv_interact');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/tv_interact_mode.php');
require_once(ROOT_PATH.'lib/class/recycle.class.php');
class tv_interact_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new tv_interact_mode();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
		$this->recycle = new recycle();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$name = trim($this->input['name']);
		if(!$name)
		{
			$this->errorOutput('请输入活动名称');
		}
		
		if(!$this->input['start_time'] || !$this->input['end_time'])
		{
			$this->errorOutput('请输入开始结束日期');
		}
		
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
		
        
		$link_switch = $this->input['link_switch'] ? 1 : 0;
		//赠送积分上限
		$score_limit = abs(intval($this->input['score_limit']));
		if(!$score_limit && !$link_switch)
		{
			$this->errorOutput('请填写赠送积分上限');
			
		}
		
		$sort_id = intval($this->input['sort_id']);
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $sort_id)
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'tv_interact_node WHERE id IN('.$sort_id.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		
		if($link_switch && !$this->input['link_address'])
		{
			$this->errorOutput('请填写跳转地址');
		}
		
		
		$data = array(
			'name'				=> $name,
			'sort_id'			=> $sort_id,
			'brief'				=> trim($this->input['brief']),
			'start_time'		=> $start_time,
			'end_time'			=> $end_time,
			'delay_time'		=> intval($this->input['delay_time']),
			'score_limit'		=> $score_limit,
			'score_min'			=> intval($this->input['score_min']),
			'score_max'			=> intval($this->input['score_max']),
		
			'un_start_tip'		=> trim($this->input['un_start_tip']),
			'un_start_desc'		=> trim($this->input['un_start_desc']),
		
			'sense_tip'			=> trim($this->input['sense_tip']),
			'sense_desc'		=> trim($this->input['sense_desc']),
		
			'next_predict'		=> trim($this->input['next_predict']),
		
			'activity_rule'		=> trim($this->input['activity_rule']),
			'activity_desc'		=> trim($this->input['activity_desc']),
			
			'is_user_limit'		=> intval($this->input['is_user_limit']),
			'user_limit_num'	=> intval($this->input['user_limit_num']),
		
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> hg_getip(),
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			//创建数据状态
			'status'    		=> $this->get_status_setting('create'),
		
			'sense_num'			=> intval($this->input['sense_num']),
		
			'un_win_tip'		=> trim($this->input['un_win_tip']),
			'un_win_desc'		=> trim($this->input['un_win_desc']),
		
			'points_tip'		=> trim($this->input['points_tip']),
			'points_desc'		=> trim($this->input['points_desc']),
		
			'link_switch'		=> $link_switch,
			'link_address'		=> trim($this->input['link_address']),
		
			'start_hour'		=> $this->input['start_hour'],
			'end_hour'			=> $this->input['end_hour'],
		);
		
		if ( $this->input['week_day'] && is_array($this->input['week_day']) && count($this->input['week_day']) > 0 ) 
		{
            $this->input['week_day'] = implode(', ', $this->input['week_day']);
        }
        $data['week_day'] = $this->input['week_day'];
        
		//索引图
		if($_FILES['index_file'])
		{
			$file['Filedata'] = $_FILES['index_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$indexpic = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['indexpic'] = serialize($indexpic);
			}
		}
		//未开始图标
		if($_FILES['un_start_file'])
		{
			$file['Filedata'] = $_FILES['un_start_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$un_start = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['un_start_icon'] = serialize($un_start);
			}
		}
		//感应提示图标
		if($_FILES['sense_file'])
		{
			$file['Filedata'] = $_FILES['sense_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$sense_icon = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['sense_icon'] = serialize($sense_icon);
			}
		}
			
		//未中奖提示图标
		if($_FILES['un_win_file'])
		{
			$file['Filedata'] = $_FILES['un_win_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$sense_icon = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['un_win_icon'] = serialize($sense_icon);
			}
		}
		
		//扣分提示图标
		if($_FILES['points_file'])
		{
			$file['Filedata'] = $_FILES['points_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$sense_icon = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['points_icon'] = serialize($sense_icon);
			}
		}
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建',$data,'','创建' . $vid);
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
		
		$name = trim($this->input['name']);
		if(!$name)
		{
			$this->errorOutput('请输入活动名称');
		}
		
		if(!$this->input['start_time'] || !$this->input['end_time'])
		{
			$this->errorOutput('请输入开始结束日期');
		}
		
		$this->input['start_hour'] = $this->input['start_hour'] ? $this->input['start_hour'] : '00:00:00';
        $this->input['end_hour'] = $this->input['end_hour'] ? $this->input['end_hour'] : '23:59';
		
		$start_time = strtotime($this->input['start_time'] . ' ' . $this->input['start_hour']);
		$end_time	= strtotime($this->input['end_time'] . ' ' . $this->input['end_hour']);
		
		if($end_time <= $start_time)
		{
			$this->errorOutput('结束时间须大于开始时间');
		}
		
		$this->input['start_hour'] = strtotime($this->input['start_hour']);
        $this->input['end_hour'] = strtotime($this->input['end_hour']);
        
        
        if ($this->input['start_hour'] >= $this->input['end_hour']) 
        {
        	$this->errorOutput('开始时间不能大于等于结束时间');
        }
        
        $this->input['start_hour'] = date('His', $this->input['start_hour']);
        $this->input['end_hour'] = date('His', $this->input['end_hour']);
        
		
		$link_switch = $this->input['link_switch'] ? 1 : 0;
		//赠送积分上限
		$score_limit = intval($this->input['score_limit']);
		if(!$score_limit && !$link_switch)
		{
			$this->errorOutput('请填写赠送积分上限');
			
		}
		
		$sort_id = intval($this->input['sort_id']);
		
		
		if($link_switch && !$this->input['link_address'])
		{
			$this->errorOutput('请填写跳转地址');
		}
		$data = array(
			'name'				=> $name,
			'sort_id'			=> $sort_id,
			'brief'				=> trim($this->input['brief']),
			'start_time'		=> $start_time,
			'end_time'			=> $end_time,
			'delay_time'		=> intval($this->input['delay_time']),
			'score_limit'		=> intval($this->input['score_limit']),
			'score_min'			=> intval($this->input['score_min']),
			'score_max'			=> intval($this->input['score_max']),
		
			'un_start_tip'		=> trim($this->input['un_start_tip']),
			'un_start_desc'		=> trim($this->input['un_start_desc']),
		
			'sense_tip'			=> trim($this->input['sense_tip']),
			'sense_desc'		=> trim($this->input['sense_desc']),
		
			'next_predict'		=> trim($this->input['next_predict']),
		
			'activity_rule'		=> trim($this->input['activity_rule']),
			'activity_desc'		=> trim($this->input['activity_desc']),
			
			'is_user_limit'		=> intval($this->input['is_user_limit']),
			'user_limit_num'	=> intval($this->input['user_limit_num']),
		
			'sense_num'			=> intval($this->input['sense_num']),
			
			'un_win_tip'		=> trim($this->input['un_win_tip']),
			'un_win_desc'		=> trim($this->input['un_win_desc']),
		
			'points_tip'		=> trim($this->input['points_tip']),
			'points_desc'		=> trim($this->input['points_desc']),
		
			'link_switch'		=> $link_switch,
			'link_address'		=> trim($this->input['link_address']),
		
			'start_hour'		=> $this->input['start_hour'],
			'end_hour'			=> $this->input['end_hour'],
		);
		
		
		if ( $this->input['week_day'] && is_array($this->input['week_day']) && count($this->input['week_day']) > 0 ) 
		{
            $this->input['week_day'] = implode(', ', $this->input['week_day']);
        }
        $data['week_day'] = $this->input['week_day'];
        
		//索引图
		if($_FILES['index_file'])
		{
			$file['Filedata'] = $_FILES['index_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$indexpic = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['indexpic'] = serialize($indexpic);
			}
		}
		
		//未开始图标
		if($_FILES['un_start_file'])
		{
			$file['Filedata'] = $_FILES['un_start_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$un_start = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['un_start_icon'] = serialize($un_start);
			}
		}
		//感应提示图标
		if($_FILES['sense_file'])
		{
			$file['Filedata'] = $_FILES['sense_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$sense_icon = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['sense_icon'] = serialize($sense_icon);
			}
		}
		
		//未中奖提示图标
		if($_FILES['un_win_file'])
		{
			$file['Filedata'] = $_FILES['un_win_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$sense_icon = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['un_win_icon'] = serialize($sense_icon);
			}
		}
		
		//扣分提示图标
		if($_FILES['points_file'])
		{
			$file['Filedata'] = $_FILES['points_file'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$sense_icon = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['points_icon'] = serialize($sense_icon);
			}
		}
		
		/**************权限控制开始**************/
		//源数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'tv_interact WHERE id = '.$id;
		$preData = $this->db->query_first($sql);
		
		//节点权限
		$sql = 'SELECT id, parents FROM '.DB_PREFIX.'tv_interact_node WHERE id IN (' . $preData['sort_id']. ',' . $data['sort_id'] . ')';
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
		
		
		//更新
		$ret = $this->mode->update($id,$data);
		if($ret)
		{
			if ($this->db->affected_rows($query))
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
			}
			
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		$ids = trim($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput(NOID);
		}
		
		/**************权限控制开始**************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'tv_interact WHERE id IN ('.$ids.')';
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
									'title' 			=> $row['name'],
									'cid' 				=> $row['id'],
									'catid' 			=> $row['sort_id'],
									'user_id'			=> $row['user_id'],
									'org_id'			=> $row['org_id'],
								);
			$recycle[$row['id']]['content']['tv_interact'] = $row;
			$before[] = $row; 
		}
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'tv_interact_node WHERE id IN('.implode(',',$sorts).')';
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
				$sql = " DELETE FROM " .DB_PREFIX. "tv_interact WHERE id IN (" . $ids . ")";
				$this->db->query($sql);
				$data = $ids;
			}
			else
			{
				$data = $this->mode->delete($ids);
			} 
		}
		else
		{
			$data = $this->mode->delete($ids);
		}
		
		if($data)
		{
			$this->addLogs('删除',$data,'','删除' . $ids);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete_comp()
	{
		return true;
	}
	
	public function audit()
	{
		$ids = trim($this->input['id']);
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//节点权限验证
		$sql = 'SELECT sort_id FROM '.DB_PREFIX.'tv_interact WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$sorts = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
		}
		if (!empty($sorts))
		{
			$nodes = array();
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'tv_interact_node WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		//节点权限验证
		
		
		$audit = intval($this->input['audit']);
		$ret = $this->mode->audit($ids,$audit);
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核' . $ids);
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort()
	{
		$table_name = 'tv_interact';
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
        $this->addItem('success');
        $this->output();
	}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new tv_interact_update();
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