<?php
require_once './global.php';
define('MOD_UNIQUEID','lottery_api');//模块标识
class LotteryApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_shengwen']['host'], $gGlobalConfig['App_shengwen']['dir']);
	}
	public function __destruct()
	{
		parent::__destruct();
		unset($this->curl);
	}
	function count()
	{
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		
		$week_now = date('w',TIMENOW);
		$hour_now = date('His',TIMENOW);
		$day_now = date('d',TIMENOW);
		
		
		$limit = " limit {$offset}, {$count}";
		$orderby = ' ORDER BY l.id  DESC ';

		//查找最近的正在进行的活动
		$sql = "SELECT l.id,l.title,l.type,l.time_limit,l.start_time,l.end_time,l.create_time,m.host,m.dir,m.filepath,m.filename FROM " . DB_PREFIX . "lottery l
				LEFT JOIN ".DB_PREFIX."materials m 
					ON l.indexpic_id = m.id 
				WHERE l.status = 1 AND start_time <=" . TIMENOW . " AND end_time > " .TIMENOW . " OR l.time_limit = 0 AND l.status = 1 " . $orderby . $limit;
		
		$q = $this->db->query($sql);
		
		
		while ($r = $this->db->fetch_array($q))
		{
			if($r['time_limit'] && ($hour_now > $r['end_hour'] || $hour_now < $r['start_hour']))
			{
				continue;
			}
			
			if($r['cycle_type'] && $r['cycle_value'])
			{
				$r['cycle_value'] = explode(',', $r['cycle_value']);
				
				
				if($r['cycle_type'] == 1 && !in_array($week_now, $r['cycle_value']))
				{
					continue;
				}
				else if($r['cycle_type'] == 2 && !in_array($day_now, $r['cycle_value']))
				{
					continue;
				}
			}
			
			
			if($this->settings['lottery_type'][$r['type']])
			{
				$r['type_name'] = $this->settings['lottery_type'][$r['type']];
			}
			$r['start_time'] = date('Y.m.d',$r['start_time']);
			$r['end_time'] = date('Y.m.d',$r['end_time']);
			
			if($r['time_limit'])
			{
				$r['effective_time'] = $r['start_time'] . '-' . $r['end_time'];
			}
			else 
			{
				$r['effective_time'] = '永久有效';
			}
			
			$r['indexpic'] = array(
				'host'			=> $r['host'],
				'dir'			=> $r['dir'],
				'filepath'		=> $r['filepath'],
				'filename'		=> $r['filename'],
			);
			
			unset($r['host'],$r['dir'],$r['filepath'],$r['filename']);
			$r['url'] = LOTTERY_DOMAIN . $r['create_time'] . $r['id'].'/'.$r['id'].'.html';
			
			
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$this->addItem($r);
		}
		
		$this->output();
	}
	

	private function get_condition()
	{
		//状态为已审核
		$condition = ' AND status=1 ';
		
		return $condition;
	}
	
	public function detail()
	{
		//echo generateExchangeCode();exit();
		$user_id = intval($this->user['user_id']);
		//$user_id = 496;
		if(!$user_id)
		{
			$this->errorOutput('登录才能参加抽奖哦！');
		}
		
		$id = intval($this->input['id']);
		$sort_id = intval($this->input['sort_id']);
		
		if($this->input['shenwen'])
		{
			if (!$this->curl)
			{
				return array();
			}
			if($_FILES)
			{
				$file = $_FILES;
			}
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addFile($file);
    		$this->curl->addRequestData('shenwen', $this->input['shenwen']);
			$this->curl->addRequestData('a','show');
			$ret = $this->curl->request('shenwen.php');
			
			if($ret['channel_name'])
			{
				$sql = "SELECT id FROM " . DB_PREFIX . "sort WHERE name LIME '" . $ret['channel_name'] . "%'";
				$res = $this->db->query_first($sql);
				
				if($res['id'])
				{
					$sort_id = $res['id'];
				}
			}
		}
		
		$data = array();
		
		if($this->settings['lottery_filter'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "lottery_filter WHERE 1 ";
			
			if($sort_id)
			{
				$sql .= "AND sort_id = " . $sort_id . " ORDER BY order_id DESC LIMIT 0,1";
			}
			else if($id)
			{
				$sql .= "AND id = " . $id;
			}
			else 
			{
				$sql .= "ORDER BY order_id DESC LIMIT 0,1";
			}
			
			$res = $this->db->query_first($sql);
			if($res['content'])
			{
				$data = unserialize($res['content']);
				
				$data['end_hours'] = $data['end_hour'];
				$data['start_hours'] = $data['start_hour'];
				$id = $data['id'];
				if($res['win_info'])
				{
					$data['win_info'] = unserialize($res['win_info']);
				}
			}
		}
		
		//hg_pre($data,0);
		if(empty($data))
		{
			if($sort_id)
			{
				$sql = "SELECT id FROM " . DB_PREFIX . "lottery WHERE status = 1 AND sort_id = " . $sort_id . " 
						ORDER BY order_id DESC LIMIT 0,1";
				$res = $this->db->query_first($sql);
				$id = $res['id'] ? $res['id'] : 0;
			}
		}
		
		$id = $id ? $id : intval($this->input['id']);
		
		if(!$id)
		{
			$this->errorOutput('不存在此活动');
		}
		
		if(empty($data))
		{
			include_once CUR_CONF_PATH . 'lib/lottery_mode.php';
			$obj = new lottery_mode();
			$data = $obj->detail($id);
		}
		
		if(!$data['id'] || $data['status'] != 1)
		{
			$this->errorOutput('不存在此活动');
		}
		//hg_pre($data,0);
		
		//时间和周期判断
		if($data['time_limit'])
		{
			$week_now = date('w',TIMENOW);
			$hour_now = date('His',TIMENOW);
			$day_now = date('d',TIMENOW);
			
			$notstartdesc = $data['notstartdesc'];
			if(!$notstartdesc)
			{
				$notstartdesc = $this->settings['notstartdesc'] ? $this->settings['notstartdesc'] : '活动尚未开始， 敬请期待';
			}
				
			if($data['start_times'] > TIMENOW || $data['start_hours'] > $hour_now)
			{
				$this->errorOutput($notstartdesc);
			}
			
			if($data['end_times'] < TIMENOW || $hour_now > $data['end_hours'])
			{
				$message = $data['finish_desc'];
				if(!$message)
				{
					$message = $this->settings['finish_desc'] ? $this->settings['finish_desc'] : '活动已结束， 敬请期待下次活动1.'; 
				}
				$this->errorOutput($message);
			}
			
			if($data['cycle_type'] && $data['cycle_value'])
			{
				$data['cycle_value'] = explode(',', $data['cycle_value']);
				
				if($week_now == 0)
				{
					$week_now = 1;
				}
				if($data['cycle_type'] == 1 && !in_array($week_now, $data['cycle_value']))
				{
					$this->errorOutput($notstartdesc);
				}
				else if($data['cycle_type'] == 2 && !in_array($day_now, $data['cycle_value']))
				{
					$this->errorOutput($notstartdesc);
				}
			}
		}
		
		//中奖限制
		if($data['lottery_limit'])
		{
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "win_info WHERE prize_id != 0 AND confirm = 1 AND member_id = " . $user_id . " AND lottery_id = " . $data['id'];
			$res = $this->db->query_first($sql);
			
			if($res['total'])
			{
				$message = $this->settings['lottery_limit_tip'] ? $this->settings['lottery_limit_tip'] : '您已中奖,谢谢参与！';
				$this->errorOutput($message);
			}
		}
		
		//积分限制
		if($data['score_limit'] && $data['need_score'] > 0)
		{
			include_once ROOT_PATH . 'lib/class/members.class.php';
			$mem_obj = new members();
			
			$credit = $mem_obj->get_member_credits($user_id);
			
			if($credit[$user_id]['credits'] < $data['need_score'])
			{
				$this->errorOutput('对不起，您的积分不够了！');
			}
	
		}
		
		//限制ip
		if($data['ip_limit'])
		{
			$ip_limit_hour = $data['ip_limit_time'] ? $data['ip_limit_time'] : 1;
			$ip_limit_num = $data['ip_limit_num'] ? $data['ip_limit_num'] : 1;
			
			
			$ip_limit_time = TIMENOW - ($ip_limit_hour * 3600);
			$ip = hg_getip();
			//查询用户参加此活动次数
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "win_info WHERE confirm = 1 AND ip = '" . $ip . "' AND lottery_id = " . $data['id'] . " AND create_time > " . $ip_limit_time;
			$res = $this->db->query_first($sql);
			
			//达到ip限制次数
			if($res['total'] >= $data['ip_limit_num'])
			{
				$this->errorOutput('您已参加活动，下一时段活动再来吧！');
			}
		}
		
		//限制设备标识
		$device_token = $this->input['device_token'];
		if($data['device_limit'] && $device_token)
		{
			$device_limit_hour = $data['device_limit_time'] ? $data['device_limit_time'] : 1;
			$device_num_limit = $data['device_num_limit'] ? $data['device_num_limit'] : 1;
			
			
			$device_limit_time = TIMENOW - ($device_limit_hour * 3600);
			//查询用户参加此活动次数
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "win_info WHERE confirm = 1 AND device_token = '" . $device_token . "' AND lottery_id = " . $data['id'] . " AND create_time > " . $device_limit_time;
			$res = $this->db->query_first($sql);
			
			//达到ip限制次数
			if($res['total'] >= $data['device_num_limit'])
			{
				$this->errorOutput('您已参加活动，下一时段活动再来吧！');
			}
		}
		
		//限制用户参加活动次数
		if($data['num_limit'] && $data['account_limit'])
		{
			
			//查询用户参加此活动次数
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "win_info WHERE confirm = 1 AND member_id = " . $user_id . " AND lottery_id = " . $data['id'];
			$res = $this->db->query_first($sql);
			
			
			//查询额外奖励抽奖次数
			$sql = "SELECT reward_num FROM " . DB_PREFIX . "reward WHERE member_id = {$user_id} AND lottery_id = {$id}";
			$reward = $this->db->query_first($sql);
			
			$reward_num = $reward['reward_num'];
			
			if($reward_num)
			{
				$data['account_limit'] += $reward_num;
			}
			//达到活动限制次数
			if($res['total'] >= $data['account_limit'])
			{
				$this->errorOutput('您已参加活动，下一时段活动再来吧！');
			}
		}
		
		//区域限制
		if($data['area_limit'])
		{
			$distance = '';
			if($this->input['GPS_longitude'] || $this->input['GPS_latitude'])
			{
				//计算距离
				if($data['GPS_latitude'] && $data['GPS_longitude'])
				{
					$distance = GetDistance($data['GPS_latitude'],$data['GPS_longitude'],$this->input['GPS_latitude'], $this->input['GPS_longitude']);
				}
			}
			elseif ($this->input['baidu_longitude'] || $this->input['baidu_latitude'])
			{
				if($data['baidu_latitude'] && $data['baidu_longitude'])
				{
					$distance = GetDistance($data['baidu_latitude'], $data['baidu_longitude'], $this->input['baidu_latitude'], $this->input['baidu_longitude']);
				}
			}
			
			if($distance > $data['distance'])
			{
				$this->errorOutput('您不在抽奖范围内，请到' . $data['address'] . '附近。');
			}
		}
		
		//版本限制
		if($data['version_limit'] && $data['version_limit'] > $this->input['version'])
		{
			$this->errorOutput('您的应用版本过低，升级后再来吧！');
		}
		
		
		//未中奖反馈
		$feedback 	= $data['feedback'];
		
		$arr = $prize = $award = array();
		
		//查询活动奖品
		$prize_arr = array();
		$prize_arr = $data['prize'];
		
		//中奖次数限制
		$win_limit_flag = false;
		if($data['win_limit'])
		{
			if(!$data['win_num_limit'])
			{
				$win_limit_flag = true;
			}
			else 
			{
				$today_start = strtotime(date('Y-m-d',TIMENOW));	
				$today_end = $today_start + 86400;
				$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "win_info WHERE confirm = 1 AND prize_id != '' AND create_time >= '" . $today_start . "' AND create_time < '" . $today_end . "' AND lottery_id = " . $data['id'];
				$res = $this->db->query_first($sql);
				if($res['total'] >= $data['win_num_limit'])
				{
					$win_limit_flag = true;
				}
			}
		}
		
		if(empty($prize_arr) && !$win_limit_flag)
		{
			//查询奖项
			$sql = 'SELECT p.*,m.host,m.dir,m.filepath,m.filename FROM '.DB_PREFIX."prize p  
					LEFT JOIN " . DB_PREFIX . "materials m 
						ON p.indexpic_id = m.id 
					WHERE p.lottery_id = {$data['id']} ORDER BY id ASC";
			$q = $this->db->query($sql);
			
			while($row = $this->db->fetch_array($q))
			{			
				$prize_arr[$row['id']] = $row;
			}
		}

		$sum = array();
		$arr = array();
		if(is_array($prize_arr) && count($prize_arr) && !$win_limit_flag)
		{
			foreach ($prize_arr as $key => $val) 
			{ 
				if($val['prize_win'] >= $val['prize_num'])
				{
					continue;
				}
				$chance = array();
				$chance = explode('/', $val['chance']);
				
				if(!$chance[1])
				{
					continue;
				}
			    $arr[$val['id']] = $chance[0]; 
			    $sum[$val['id']] = $chance[1];
			} 
		}
		
		$prize_id = '';
		if($sum && $arr)
		{
			$prize_id = get_rand($arr,$sum); //根据概率获取奖项id 
		}
		$prize_id = $prize_id ? $prize_id : 0;
		
		$award['lottery_id']	= $data['id'];//抽奖活动id
		$award['score_limit'] 	= $data['score_limit']; //积分限制
		$award['need_score'] 	= $data['need_score']; //需要积分
		$award['id']			= $prize_id;//奖品id
		$award['win_info']		= $data['win_info'] ? $data['win_info'] : array();
		$award['indexpic']		= $data['indexpic'] ? $data['indexpic'] : array();
		
		
		if($prize_id)
		{
			$prize = $prize_arr[$prize_id];
			$prize_indexpic = array(
				'host' 		=> $prize['host'],
				'dir'		=> $prize['dir'],
				'filepath'	=> $prize['filepath'],
				'filename'	=> $prize['filename'],
			);
			
			$award['prize'] = $prize['prize']; //奖品名称
			$award['name'] 	= $prize['name']; //奖项名称
			$award['tip'] 	= $prize['tip']; //奖品名称
			$award['prize_indexpic'] = $prize_indexpic; //奖品索引图
			
		}
		else 
		{
			$feedback_count = count($feedback);
			$rand_num = mt_rand(0, $feedback_count-1);
			$award['tip'] 	= $feedback[$rand_num]; //奖品名称
			$award['name'] 	= '谢谢参与'; //奖项名称
		}
	
		//记录获奖记录
		$lottery_data = array(
			'lottery_id'   		=> $data['id'],
			'prize_id'			=> $prize_id,
			'create_time'		=> TIMENOW,
			//'device_token'	=> $this->input['device_token'],
			//'member_id'		=> $user_id,
			//'ip'				=> $ip,
		);
		
		$send_no = md5(uniqid(rand(), true));
		
		$lottery_data['sendno'] = $award['sendno'] = $send_no;//中奖随机串
		if($data['score_limit'] && $data['need_score'] != 0)
		{
			$award['scores'] = $data['need_score']  < 0 ? abs($data['need_score']) : '-' . $data['need_score'];
		}
		$sql = " INSERT INTO " . DB_PREFIX . "win_info SET ";
		foreach ($lottery_data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		
		$this->db->query($sql);
		
		$wininfo_id = $this->db->insert_id();
		
		
		//中奖信息记录成功
		if($wininfo_id && $this->settings['lock_stock'])
		{
			//更新库存
			$sql = "UPDATE " . DB_PREFIX . "prize SET prize_win = prize_win + 1 WHERE id = " . $prize_id;
			$this->db->query($sql);
			
			//记录锁止库存表
			$lock_data = array(
				'send_no'		=> $send_no,
				'prize_id'		=> $prize_id,
				'member_id'		=> $user_id,
				'lottery_id'	=> $data['id'],
				'create_time'	=> TIMENOW,
			);
			
			$sql = " INSERT INTO " . DB_PREFIX . "stock_lock SET ";
			foreach ($lock_data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql = trim($sql,',');
			
			$this->db->query($sql);
		}
		
		if($this->input['tv_interact'])
		{
			$this->input['sendno'] = $lottery_data['sendno'];
			$this->update_win_info();
		}
		
		$this->addItem($award);
		$this->output();
	}
	
	
	//更新中奖者地址和电话
	public function update_win_info()
	{
		$id = $this->input['sendno'];
		if(!$id)
		{
			$this->errorOutput('请刷新后,再试！');
		}
		
		$user_id = $this->user['user_id'];
		if(!$user_id)
		{
			$this->errorOutput('请先登陆！');
		}
		$sql = "SELECT id,prize_id,lottery_id FROM " . DB_PREFIX . "win_info WHERE sendno = '{$id}' AND confirm = 0";
		$res = $this->db->query_first($sql);
		
		$wininfo_id = $res['id'];
		
		if(!$res || !$res['lottery_id'])
		{
			$this->errorOutput('网络超时,请刷新后,再试哦！');
		}
		
		$data = array();
		$sql = "SELECT score_limit,need_score,exchange_switch FROM " . DB_PREFIX . "lottery WHERE id = " . $res['lottery_id'];
		$data = $this->db->query_first($sql);
	
		if(empty($data))
		{
			$this->errorOutput('活动异常,请稍后再试！');
		}
		
		if($data['score_limit'] && $data['need_score'])
		{
			include_once ROOT_PATH . 'lib/class/members.class.php';
			$mem_obj = new members();
			//同步会员积分
			$credit_type = $mem_obj->get_trans_credits_type();
			if(!empty($credit_type))
			{
				$res1 = '';
				$ac = 'sub_' . $credit_type['db_field'];
				$res1 = $mem_obj->$ac($user_id,$data['need_score'],$data['id'],APP_UNIQUEID,MOD_UNIQUEID,'update_win_info','抽奖扣除');
				
				if(!$res1)
				{
					$this->errorOutput('您的积分不够啦,快去赚取积分吧！');
				}
			}
		}
		
		
		$address = $this->input['address'];
		$phone_num = $this->input['phone_num'];
		$ip = hg_getip();
		
		$up_data = array(
			'device_token'		=> $this->input['device_token'],
			'member_name'		=> $this->user['user_name'],//冗余中奖人名称
			'member_id'			=> $user_id,
			'confirm'			=> 1,
			'status'			=> 1,
			'ip'				=> $ip,
		);
		
		if($res['prize_id'])
		{
			$sql = "SELECT id,type,prize,seller_id,name FROM " . DB_PREFIX . "prize WHERE id = {$res['prize_id']}";
			$prize_res = $this->db->query_first($sql);
			
			if(!$prize_res['id'])
			{
				$this->errorOutput('请刷新后,再试~');
			}
			
			//锁止开关
			if(!$this->settings['lock_stock'])
			{
				$sql = "UPDATE " . DB_PREFIX . "prize SET prize_win = prize_win + 1 WHERE id = " . $res['prize_id'] . " AND prize_win < prize_num";
				$this->db->query($sql);
				
				//更新失败提示报错
	        	if(!$this->db->affected_rows())
	        	{
	        		$this->errorOutput('请刷新后,再试~');
	        	}
			}
			
        	//兑换码
        	$up_data['exchange_code'] = generateExchangeCode();
			
        	if($this->settings['App_qrcode'])
        	{
				include_once(ROOT_PATH . 'lib/class/qrcode.class.php');
				$qrcode_server = new qrcode();
				
				$exchange_url = $this->settings['exchange_url'] . '?send_no=' . $id;
				$data_qrcode = array('content'=>$exchange_url);
				$qrcode = $qrcode_server->create($data_qrcode,-1);
				
				$up_data['exchange_qrcode'] = is_array($qrcode) ? hg_fetchimgurl($qrcode) : '';
        	}
        	
        	$sync_fail_tag = false;
			if($prize_res['type'] == 1)
			{
				$up_data['prize_type'] = 1;
				
				include_once ROOT_PATH . 'lib/class/members.class.php';
				$mem_obj = new members();
				
				//同步会员积分
				$credit_type = $mem_obj->get_trans_credits_type();
				if(!$credit_type['db_field'])
				{
					$ac = 'add_' . $credit_type['db_field'];
					$res1 = $mem_obj->$ac($user_id,intval($prize_res['prize']),$res['lottery_id'],APP_UNIQUEID,MOD_UNIQUEID,'update_win_info','抽奖加积分');
					
					if(!$res1)
					{
						$sync_fail_tag = true;
					}
					else 
					{
						$up_data['provide_status'] = 1;
					}
				}
				else 
				{
					$sync_fail_tag = true;
				}
			}
			else if($prize_res['type'] == 0)
			{
				$up_data['prize_type'] = 2;
			}
			
			if($prize_res['seller_id'])
			{
				$up_data['seller_id'] = $prize_res['seller_id'];
			}
			
			//冗余奖品名称
			if($prize_res['prize'])
			{
				$up_data['prize_name'] = $prize_res['prize'];
			}
			elseif ($prize_res['name'])
			{
				$up_data['prize_name'] = $prize_res['name'];
			}
		}

		if($address)
		{
			$up_data['address'] = $address;
		}
		if($phone_num)
		{
			$up_data['phone_num'] = $phone_num;
		}
		
		$sql = "UPDATE " . DB_PREFIX . "win_info SET ";
		
		foreach ($up_data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		
		$sql .= " WHERE sendno = '"  .$id. "'";
		$this->db->query($sql);
		
		$affect = $this->db->affected_rows();
		
		if(!$affect)
		{
			$this->errorOutput('网络超时,请稍后再试~');
		}
		
		//锁止开关
		if($this->settings['lock_stock'])
		{
			//删除库存锁止记录
			$sql = "DELETE FROM " . DB_PREFIX . "stock_lock WHERE send_no = '" . $id . "'";
			$this->db->query($sql);
		}
		
		//如果奖品是积分，同步会员积分
		if($prize_res['type'] == 1 && intval($prize_res['prize']) && $sync_fail_tag)
		{
			//同步失败,记录失败记录
			$sync_fail = array(
				'user_id'		=> $user_id,
				'credits'		=> $prize_res['prize'],
				'lottery_id'	=> $res['lottery_id'],
				'wininfo_id'	=> $wininfo_id,
				'create_time'	=> TIMENOW,
				'update_time'	=> TIMENOW,
			);
			
			$sql = " INSERT INTO " . DB_PREFIX . "sync_fail SET ";
			foreach ($sync_fail AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql = trim($sql,',');
			
			$this->db->query($sql);
						
			$this->errorOutput('网络繁忙,积分暂未同步,请稍后到会员中心查看!');
		}
		
		if($this->input['tv_interact'])
		{
			return true;
		}
		else 
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	
	public function update_address()
	{
		$user_id = intval($this->user['user_id']);
		if(!$user_id)
		{
			$this->errorOutput('请先登录！');
		}
		
		$id = $this->input['sendno'];
		if(!$id)
		{
			$this->errorOutput('sendno不存在');
		}
		
		$address = $this->input['address'];
		$phone_num = $this->input['phone_num'];
		
		if(!$phone_num)
		{
			$this->errorOutput('请填写电话号码');
		}
		if($address)
		{
			$up_data['address'] = $address;
		}
		if($phone_num)
		{
			$up_data['phone_num'] = $phone_num;
		}
		
		$sql = "UPDATE " . DB_PREFIX . "win_info SET ";
		
		foreach ($up_data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		
		$sql .= " WHERE sendno = '"  .$id. "' AND member_id = " . $user_id;
		
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
	
	public function get_member_win_info()
	{
		$user_id = intval($this->user['user_id']);
		if(!$user_id)
		{
			$this->errorOutput('请先登录！');
		}
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		
		$limit = " limit {$offset}, {$count}";
		
		$sql = "SELECT w.*,p.name,p.type,p.prize,m.host,m.dir,m.filepath,m.filename,l.title,l.exchange_switch FROM " . DB_PREFIX . "win_info w
				LEFT JOIN " . DB_PREFIX . "prize p 
					ON w.prize_id = p.id 
				LEFT JOIN " . DB_PREFIX . "materials m 
					ON p.indexpic_id = m.id 
				LEFT JOIN " . DB_PREFIX . "lottery l
					ON w.lottery_id = l.id
				WHERE 1 AND w.member_id = {$user_id} AND w.confirm = 1 AND w.status = 1 AND w.prize_id != 0 ";
		
		
		$lottery_id = intval($this->input['lottery_id']);
		if($lottery_id)
		{
			$sql .= " AND w.lottery_id = " . $lottery_id;
		}
		
		$sql .= " ORDER BY w.create_time DESC " . $limit;
		
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			
			if(!$r['phone_num'])
			{
				$r['member_info_flag'] = false;
			}
			else 
			{
				$r['member_info_flag'] = true;
			}
			$r['create_time'] = date('Y-m-d H:i', $r['create_time']);
			$this->addItem($r);
		}
		
		$this->output();
	}
	
	//查询获奖信息
	public function get_win_info()
	{
		$lottery_id = intval($this->input['lottery_id']);
		
		if(!$lottery_id)
		{
			$this->errorOutput(NOID);
		}
		
		
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 10;
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$sql = "SELECT w.*,p.name,p.type,p.prize,p.tip,m.host,m.dir,m.filepath,m.filename FROM " . DB_PREFIX . "win_info w
					LEFT JOIN " . DB_PREFIX . "prize p 
						ON w.prize_id = p.id 
					LEFT JOIN " . DB_PREFIX . "materials m 
						ON p.indexpic_id = m.id 
					WHERE w.lottery_id = " . $lottery_id . " 
						AND w.prize_id != '' 
					ORDER BY p.id ASC,w.create_time DESC ".$limit;
			
		$q = $this->db->query($sql);
		$info = array();
		$member_id = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['create_time']	= date('Y-m-d H:i:s',$r['create_time']);
			$r['prize_pic'] = $r['host'].$r['dir'].$r['filepath'].$r['filename'];
			unset($r['host'],$r['dir'],$r['filepath'],$r['filename']);
			$info[] 			= $r;
			$member_id[] 		= $r['member_id'];
		}
		if(!empty($member_id))
		{
			include_once CUR_CONF_PATH . 'lib/win_info_mode.php';
			$obj = new win_info_mode();
			$member_info = $obj->get_memberInfo($member_id);
		}
		
		
		$arr = $prize = $award = array();
		
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
	
	//奖励次数
	public function lottery_reward()
	{
		$user_id = $this->input['member_id'];
		//$user_id = 496;
		if(!$user_id)
		{
			$this->errorOutput('user_id不存在');
		}
		$lottery_id = $this->input['lottery_id'];
		if(!$lottery_id)
		{
			$this->errorOutput('抽奖id不存在');
		}
		
		$reward_num = intval($this->input['reward_num']);
		
		$reward_num = $reward_num ? $reward_num : 1;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "reward WHERE member_id = {$user_id} AND lottery_id = {$lottery_id}";
		$res = $this->db->query_first($sql);
		
		$data = array(
			'reward_num'	=> $reward_num,
		);
		
		if($res['member_id'] && $res['lottery_id'])
		{
			$data['reward_num'] += $res['reward_num'];
			//更新数据
			$sql = " UPDATE " . DB_PREFIX . "reward SET ";
			foreach ($data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql  = trim($sql,',');
			$sql .= " WHERE member_id = {$user_id} AND lottery_id = {$lottery_id}";
			$this->db->query($sql);
		}
		else 
		{
			$data['member_id'] = $user_id;
			$data['lottery_id']	= $lottery_id;
			$sql = " INSERT INTO " . DB_PREFIX . "reward SET ";
			foreach ($data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql = trim($sql,',');
			$this->db->query($sql);
		}
		
		$this->addItem($data);
		$this->output();
	}
	
	public function get_sort()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 2;
		
		$limit = " limit {$offset}, {$count}";
		$orderby = ' ORDER BY order_id  ASC';
		
		$sql = "SELECT id,name,is_last FROM " . DB_PREFIX . "sort WHERE fid = 0 " . $orderby . $limit;
		
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
			$sql = "SELECT id,name,fid FROM " . DB_PREFIX . "sort WHERE fid IN (" . implode(',', $fid) . ") ORDER BY order_id ASC";
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
	
	public function get_address_info()
	{
		$id = intval($this->input['id']);
		
		if(!$id)
		{
			$this->errorOutput('信息不存在');
		}
		
		$res = array();
		$sql = "SELECT id,address,phone_num,sendno,exchange_code FROM " . DB_PREFIX . "win_info WHERE id = " . $id;
		$res = $this->db->query_first($sql);
		
		if(empty($res))
		{
			$this->errorOutput('信息不存在');
		}
		$this->addItem($res);
		$this->output();
	}
	
	
	//扫描二维码兑奖,二维码生成的是send_no,获取中奖信息
	public function get_order_info()
	{
		$seller_id = $this->input['seller_id'];
		
		if(!$seller_id)
		{
			$this->errorOutput('请输入商家号');
		}
		
		$sql = "SELECT seller_id FROM " . DB_PREFIX . "prize WHERE seller_id = '" . $seller_id . "' LIMIT 0,1";
		$res = $this->db->query_first($sql);
		
		if(!$res['seller_id'])
		{
			$this->errorOutput('商家id不存在');
		}
		
		
		$send_no = $this->input['send_no'];
		if(!$send_no)
		{
			$this->errorOutput('参数异常');
		}
		
		$sql = "SELECT w.*,l.title as lottery_title FROM " . DB_PREFIX . "win_info w 
				LEFT JOIN " . DB_PREFIX . "lottery l 
					ON w.lottery_id = l.id
				WHERE w.status=1 AND w.confirm =1 AND w.sendno = '" . $send_no . "'";
		$data = $this->db->query_first($sql);
		
		if($data['seller_id'] && ($data['seller_id'] != $seller_id))
		{
			$this->errorOutput('商家id错误');
		}
		
		if(!$data['id'])
		{
			$this->errorOutput('中奖信息异常,请联系工作人员');
		}
		
		//中奖时间
		$data['create_time'] = date('Y-m-d H:i',$data['create_time']);
		
		$prize_info = array();
		if($data['prize_id'])
		{
			$sql = "SELECT p.name,p.prize,p.type,m.host,m.dir,m.filepath,m.filename FROM " . DB_PREFIX . "prize p 
					LEFT JOIN " . DB_PREFIX . "materials m 
						ON p.indexpic_id = m.id 
					WHERE p.id = {$data['prize_id']}";
			
			$prize_info = $this->db->query_first($sql);
		}
		
		if(empty($prize_info))
		{
			$this->errorOutput('奖品不存在');
		}
		
		$data['prize_info'] = $prize_info;
		$this->addItem($data);
		$this->output();
	}
	
	//确认领取奖品
	public function confirm_prize()
	{
		$seller_id = $this->input['seller_id'];
		
		if(!$seller_id)
		{
			$this->errorOutput('请输入商家号');
		}
		
		$send_no = $this->input['send_no'];
		if(!$send_no)
		{
			$this->errorOutput('参数异常');
		}
		
		
		//查询中奖信息
		$sql = "SELECT * FROM " . DB_PREFIX . "win_info WHERE sendno = '" . $send_no . "' AND seller_id = '" . $seller_id . "' LIMIT 0,1";
		
		
		$res = $this->db->query_first($sql);
		

		if(!$res)
		{
			$this->errorOutput('中奖信息不存在');
		}
		
		if($res['provide_status'] == 1)
		{
			$this->errorOutput('奖品已兑换');
		}
		
		$sql = "UPDATE " . DB_PREFIX . "win_info SET provide_status = 1,exchange_time = '" . TIMENOW . "' WHERE sendno = '" . $send_no . "' AND seller_id = '" . $seller_id . "'";
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
	
	
	//商家获取兑换记录
	public function seller_exchange_info()
	{
		$seller_id = $this->input['seller_id'];
		if(!$seller_id)
		{
			$this->errorOutput('商家id不存在');
		}
		$lottery_id = $this->input['lottery_id'];
		
		
		$sql = "SELECT w.*,l.title as lottery_title,p.name as prize_name,p.prize,m.host,m.dir,m.filepath,m.filename FROM " . DB_PREFIX . "win_info w 
				LEFT JOIN " . DB_PREFIX . "lottery l 
					ON w.lottery_id = l.id
				LEFT JOIN " . DB_PREFIX . "prize p
					ON w.prize_id = p.id
				LEFT JOIN " . DB_PREFIX . "materials m
					ON p.indexpic_id = m.id
				WHERE w.status=1 AND w.confirm =1 AND w.prize_id !='' AND w.provide_status=1 AND w.seller_id = '" . $seller_id . "'";
	
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			
			if($r['exchange_time'])
			{
				$r['exchange_time'] = date('Y-m-d H:i',$r['exchange_time']);
			}
			$this->addItem($r);
		}
		$this->output();
	}
	//确认中奖
	public function exchange_prize()
	{
		$user_id = $this->user['user_id'];
		
		if(!$user_id)
		{
			$this->errorOutput('请先登录');
		}
		
		$send_no = $this->input['send_no'];
		if(!$send_no)
		{
			$this->errorOutput('参数异常');
		}
		
		
		$sql = "SELECT w.id,w.exchange_code,l.exchange_switch FROM " . DB_PREFIX . "win_info w 
				LEFT JOIN " . DB_PREFIX . "lottery l 
					ON w.lottery_id = l.id
				WHERE w.status=1 AND confirm =1 AND w.sendno = '" . $send_no . "' AND w.member_id = '" . $user_id . "'";
		$res = $this->db->query_first($sql);
		
		
		if(!$res['id'])
		{
			$this->errorOutput('中奖信息异常,请联系工作人员');
		}
		
		
		$exchange_code = $this->input['exchange_code'];
		
		if($res['exchange_switch'])
		{
			if(!$exchange_code)
			{
				$this->errorOutput('请输出兑换码');
			}
			
			if($res['exchange_code'] != $exchange_code)
			{
				$this->errorOutput('兑换码错误');
			}
		}
		
		$sql = "UPDATE " . DB_PREFIX . "win_info SET provide_status = 1 WHERE id = " . $res['id'];
		$this->db->query($sql);
		
		$this->addItem('success');
		
		$this->output();
	}
	
}
$out = new LotteryApi();
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