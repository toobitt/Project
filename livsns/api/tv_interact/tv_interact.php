<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','tv_interact');//模块标识
class tvInteractApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function count()
	{
	}
	
	public function show()
	{
		$link_jump = intval($this->input['link_jump']);
		$user_id = intval($this->user['user_id']);
		if(!$user_id && !$link_jump)
		{
			$this->errorOutput('登录才能参加活动哦！');
		}
		
		$week_now = date('w',TIMENOW);
		$hour_now = date('His',TIMENOW);
		
		if($week_now === '0')
		{
			$week_now = 7;
		}
		if($this->input['sort_id'])
		{
			$con = ' AND sort_id = ' . $this->input['sort_id'];
		}
		
		//获取链接
		if($link_jump)
		{
			$con .= ' AND link_switch = 1';
			$filed = 'link_switch,link_address,week_day,start_hour,end_hour,name,start_time,brief';
		}
		else 
		{
			$filed = '*';
		}
		
		$orderby = ' ORDER BY start_time  DESC,order_id DESC';

		//查找最近的正在进行的活动
		$sql = "SELECT " . $filed . " FROM " . DB_PREFIX . "tv_interact WHERE status = 1 AND start_time <=" . TIMENOW . " AND (end_time+delay_time) > " .TIMENOW . $con . $orderby;
		$q = $this->db->query($sql);
		
		
		while ($r = $this->db->fetch_array($q))
		{
			//$info[] = $r;
			if($r['start_hour'] && $r['end_hour'] && ($hour_now > $r['end_hour'] || $hour_now < $r['start_hour']))
			{
				continue;
			}
			
			if($r['week_day'])
			{
				$r['week_day'] = explode(',', $r['week_day']);
				if(!empty($r['week_day']) && !in_array($week_now, $r['week_day']))
				{
					continue;
				}
			}
			
			$data = $r;
			break;
		}
		
		//hg_pre($info);
		//hg_pre($data,0);
		if($link_jump)
		{
			$data_link['link_address'] = $data['link_address'] ? $data['link_address'] : '';
			$data_link['tip'] = $data['brief'] ? $data['brief'] : '';
			$data_link['link_switch'] = $data['link_switch'] ? $data['link_switch'] : '';
			
			
			$this->addItem($data_link);
			
			$this->output();
		}
		
		//开始的活动
		$start_flag = true;
		
		//没有在进行的活动，查询即将要开始的最近的活动
		if(!$data)
		{
			$orderby = ' ORDER BY start_time  ASC LIMIT 0,1';
			$sql = "SELECT * FROM " . DB_PREFIX . "tv_interact WHERE status = 1 AND start_time > " . TIMENOW . " AND (end_time+delay_time) > " .TIMENOW . $con . $orderby;
			$data = $this->db->query_first($sql);
			
			//未开始的活动
			$start_flag = false;
		}
		//hg_pre($res);
		
		if(!$data)
		{
			$data = array();
		}
		else 
		{
			//正在进行的活动，验证参加活动限制
			if($start_flag && $data['id'])
			{
				//限制用户参加活动次数
				if($data['is_user_limit'] && $data['user_limit_num'])
				{
					
					//查询用户参加此活动次数
					$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "win_info WHERE member_id = " . $user_id . " AND tv_interact_id = " . $data['id'];
					$res = $this->db->query_first($sql);
					
					//达到活动限制次数
					if($res['total'] >= $data['user_limit_num'])
					{
						$this->errorOutput('您已达到活动参加最大次数！');
					}
				}
				
				
				//有积分限制
				if($data['score_limit'])
				{
					$error_mes = '活动积分已经送完，下一时段活动再来吧！';
					if($data['score_min'] <= 0)
					{
						if($data['current_score'] >= $data['score_limit'])
						{
							$this->errorOutput($error_mes);
						}
					}
					else if($data['current_score'] > ($data['score_limit'] - $data['score_min']))
					{
						$this->errorOutput($error_mes);
					}
				}
				
				
						
				//固定分值
				if($data['score_min'] == $data['score_max'])
				{
					$data['score'] = $data['score_min'];
				}
				else //区间分值
				{
					$score = mt_rand($data['score_min'], $data['score_max']);
					
					
					
					if($data['score_limit'])
					{
						
						$last_score = $data['score_limit']-$data['current_score'];
						
						if($last_score <= 0)
						{
							$this->errorOutput('活动积分已经送完，下一时段活动再来吧！');
						}
						
						if( $data['score_min'] > 0)
						{
							//如果随即分值大于  限定分值,返回最小值
							if($last_score < $data['score_min'])
							{
								$this->errorOutput('活动积分已经送完，下一时段活动再来吧！');
							}
						}
					}
					
					
					$data['score'] = $score;
				}
				
				if($data['score'] > 0)
				{
					$sql = "UPDATE " . DB_PREFIX . "tv_interact SET ";
					$sql .= "current_score = current_score + ". $data['score'];
					
					$sql .= " WHERE id = " . $data['id'];
					$this->db->query($sql);
				}
			
				//记录获奖记录
				$score_data = array(
					'tv_interact_id'   	=> $data['id'],
					'member_id'			=> $user_id,
					'red_bag'			=> $data['score'],
					'create_time'		=> TIMENOW,
				);
				
				$sql = " INSERT INTO " . DB_PREFIX . "win_info SET ";
				foreach ($score_data AS $k => $v)
				{
					$sql .= " {$k} = '{$v}',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
				
				
				//同步会员积分
				include_once ROOT_PATH . 'lib/class/members.class.php';
				$obj = new members();
				
				$credit_type = $obj->get_trans_credits_type();
				
				if(!empty($credit_type) && $data['score'] > 0)
				{
					$ac = 'add_' . $credit_type['db_field'];
					$res = $obj->$ac($user_id,$data['score'],$data['id'],APP_UNIQUEID,MOD_UNIQUEID,'show','电视互动奖励');
				}
				
			}
			if(!$data['score'])
			{
				$data['score'] = 0;
			}
			
			//开始的活动和未开始的活动都要更新感应人数
			$sql = "UPDATE " . DB_PREFIX . "tv_interact SET sense_num = sense_num + 1";
			
			if($data['score'] > 0)
			{
				//$sql .= ",current_score = current_score + ". $data['score'];
			}
			$sql .= " WHERE id = " . $data['id'];
			$this->db->query($sql);
			
			if($data['score'] < 0)
			{
				$sub_score = intval($data['score']);
				//同步会员积分
				if(!empty($credit_type))
				{
					$ac = 'sub_' . $credit_type['db_field'];
					$res1 = $obj->$ac($user_id,$sub_score,$data['id'],APP_UNIQUEID,MOD_UNIQUEID,'show','电视互动扣除');
				}
			}
			
			//查询活动中奖记录
			
			if($this->settings['need_win_info'])
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "win_info WHERE tv_interact_id = " . $data['id'] . " AND red_bag > 0 ORDER BY create_time DESC LIMIT 0,4";
				
				$q = $this->db->query($sql);
				$info = array();
				$member_id = array();
				while ($r = $this->db->fetch_array($q))
				{
					$r['create_time']	= hg_tran_time_tv($r['create_time']);
					if($r['red_bag'])
					{
						$r['red_bag'] = $r['red_bag'] . TV_SCORE_TYPE;
					}
					$info[] 			= $r;
					$member_id[] 		= $r['member_id'];
				}
				if(!empty($member_id))
				{
					$member_id 			= implode(',', $member_id);
					$member_info	 	= array();
					$member_info_tmp 	= array();
					$member_info_tmp 	= $obj->get_member_info($member_id);
					
					if(!empty($member_info_tmp))
					{
						foreach ($member_info_tmp as $val)
						{
							$member_info[$val['member_id']]['avatar']			= $val['avatar'];
							$member_info[$val['member_id']]['member_name'] 		= $val['member_name'];
							$member_info[$val['member_id']]['phone_num']		= $val['mobile'];
						}
					}
				}
			
				if(!empty($info))
				{
					$win_info = array();
					foreach ($info as $val)
					{
						foreach ($val as $k => $v)
						{
							if($k == 'member_id' && $member_info[$v])
							{
								$val['member_name'] 	= $member_info[$v]['member_name'];
								$val['phone_num']	 	= $member_info[$v]['phone_num'];
								$val['avatar']	 		= $member_info[$v]['avatar'];
							}
						}
						$win_info[] = $val;
					}
					
					$data['win_info'] = $win_info;
				}
			}
			else 
			{
				$data['win_info'] = array();
			}
			
			//感应数+1
			$data['sense_num'] += 1; 
			
			//标识活动开始还是未开始
			$data['start_flag'] = $start_flag;
			
			if($data['indexpic'])
			{
				$data['indexpic'] = unserialize($data['indexpic']);
			}
			if($data['un_start_icon'])
			{
				$data['un_start_icon'] = unserialize($data['un_start_icon']);
			}
			if($data['sense_icon'])
			{
				$data['sense_icon'] = unserialize($data['sense_icon']);
			}
			if($data['un_win_icon'])
			{
				$data['un_win_icon'] = unserialize($data['un_win_icon']);
			}
			if($data['points_icon'])
			{
				$data['points_icon'] = unserialize($data['points_icon']);
			}
			
			
			if(!$data['brief'])
			{
				$data['brief'] = '当电视或者广播出现提示时，马上摇动您的手机获取积分换奖品吧！';
			}
			if(!$data['un_start_tip'])
			{
				$data['un_start_tip'] = '当前游戏未开始';
			}
			if(!$data['un_start_desc'])
			{
				$data['un_start_desc'] = '请在节目播发时按提示摇一摇';
			}
			if(!$data['sense_tip'])
			{
				$data['sense_tip'] = '恭喜你！';
			}
			
			if(!$data['sense_desc'])
			{
				$data['sense_desc'] = '摇到了';
			}
			$data['scores'] = $data['score'];
			
			$data['score_type'] = TV_SCORE_TYPE;
			//在获得奖励加上奖励类型
			$data['score'] = $data['score'] . TV_SCORE_TYPE;
			
			if($data['scores'] == 0)
			{
				$data['sense_tip'] = $data['un_win_tip'] ? $data['un_win_tip'] : '很遗憾！';
				$data['sense_desc'] = $data['un_win_desc'] ? $data['un_win_desc'] : '下次再来吧！';
				$data['sense_icon'] = $data['un_win_icon'] ? $data['un_win_icon'] : '';
			}
			elseif ($data['scores'] < 0)
			{
				$data['sense_tip'] = $data['points_tip'] ? $data['points_tip'] : '您被扣了' . $data['score'];
				$data['sense_desc'] = $data['points_desc'] ? $data['points_desc'] : '您被扣了' . $data['score'];
				$data['sense_icon'] = $data['points_icon'] ? $data['points_icon'] : '';
			}
		}
		$this->addItem($data);
		$this->output();
	}
	
	
	public function get_score($data)
	{
		if(empty($data))
		{
			return false;
		}
		//固定分值
		if($data['score_min'] == $data['score_max'])
		{
			$data['score'] = $data['score_min'];
		}
		else //区间分值
		{
			$score = mt_rand($data['min'], $data['max']);
			
			$last_score = $data['score_limit']-$data['current_score'];
			//如果随即分值大于  限定分值,返回最小值
			if($score > $last_score)
			{
				$score = $data['score_min'];
			}
			
			$data['score'] = $score;
		}
		
		
		//记录获奖记录
		$score_data = array(
			'tv_interact_id'   	=> $data['id'],
			'member_id'			=> $user_id,
			'red_bag'			=> $data['score'],
			'create_time'		=> TIMENOW,
		);
		
		$sql = " INSERT INTO " . DB_PREFIX . "win_info SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		
		return $data['score'];
	}
	private function get_condition()
	{
		//状态为已审核
		$condition = ' AND status=1 ';
		
		return $condition;
	}
	
	
	public function get_sort()
	{
		
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 2;
		
		$limit = " limit {$offset}, {$count}";
		$orderby = ' ORDER BY order_id  ASC';
		
		$sql = "SELECT id,name,is_last FROM " . DB_PREFIX . "tv_interact_node WHERE fid = 0 " . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		while ($val = $this->db->fetch_array($q))
		{
			if(!$val['is_last'])
			{
				$fid[] = $val['id'];
			}
			$data[$val['id']] = $val;
		}
		
		if($fid)
		{
			$sql = "SELECT id,name,fid FROM " . DB_PREFIX . "tv_interact_node WHERE fid IN (" . implode(',', $fid) . ") ORDER BY order_id ASC";
			$q = $this->db->query($sql);
			while ($row = $this->db->fetch_array($q))
			{
				if($data[$row['fid']])
				{
					$data[$row['fid']]['subset'][] = $row;
				}
			}
		}
		
		if($data)
		{
			foreach ($data as $v)
			{
				$this->addItem($v);
			}
		}
		
		$this->output();
	}
	
	
	
	public function get_win_info()
	{
		$tv_interact_id = intval($this->input['tv_interact_id']);
		
		if(!$tv_interact_id)
		{
			$this->errorOutput(NOID);
		}
		
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 10;
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "win_info WHERE tv_interact_id = " . $tv_interact_id . " ORDER BY create_time DESC LIMIT 0,".$count;
			
		$q = $this->db->query($sql);
		$info = array();
		$member_id = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['create_time']	= date('Y-m-d H:i:s',$r['create_time']);
			
			$member_id[] 		= $r['member_id'];
			$info[] 			= $r;
			
		}
		if(!empty($member_id))
		{
			include_once ROOT_PATH . 'lib/class/members.class.php';
			
			$obj = new members();
			$member_id 			= implode(',', $member_id);
			$member_info	 	= array();
			$member_info_tmp 	= array();
			$member_info_tmp 	= $obj->get_member_info($member_id);
			
			
			if(!empty($member_info_tmp))
			{
				$size = '30x30/';
				foreach ($member_info_tmp as $val)
				{
					$member_info[$val['member_id']]['member_name'] 	= $val['member_name'];
					if(!empty($val['avatar']))
					{
						$member_info[$val['member_id']]['avatar']	= hg_material_link($val['avatar']['host'], $val['avatar']['dir'], $val['avatar']['filepath'], $val['avatar']['filename'],$size);
					}
					else 
					{
						$member_info[$val['member_id']]['avatar']	= array();
					}
					$member_info[$val['member_id']]['phone_num']	= $val['mobile'];
				}
			}
		}
		
		if(!empty($info) && $member_info)
		{
			$win_info = array();
			foreach ($info as $val)
			{
				foreach ($val as $k => $v)
				{
					if($k == 'member_id' && $member_info[$v])
					{
						$val['member_name'] 	= $member_info[$v]['member_name'];
						$val['phone_num']	 	= $member_info[$v]['phone_num'];
						$val['avatar']	 		= $member_info[$v]['avatar'];
					}
				}
				$this->addItem($val);
			}
		}
		$this->output();
	}
	public function detail()
	{
	}
}
$out = new tvInteractApi();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>