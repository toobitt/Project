<?php
class sign extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members=new members();
		$this->membersql = new membersql();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition,$offset,$count)
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT member_id as id,time,lasted,mdays,credit1,credit2,qdxq,todaysay FROM " . DB_PREFIX . "sign ";
		$sql.= " WHERE 1 " . $condition;
		$sql .= " ORDER BY time DESC".$limit;
		$q = $this->db->query($sql);
		$return = array();
		$member_id=array();
		$today = strtotime(date('Y-m-d',TIMENOW));//今天起始时间
		$tomorrow = strtotime(date('Y-m-d',strtotime("+1 day")));//明天起始时间
		$qdxq_info=$this->Members->get_qdxq();
		$data_qd=array();
		$data_noqd=array();
		while ($row = $this->db->fetch_array($q))
		{
			$member_id[]=$row['id'];
			if($row['qdxq'])
			{
				$row['qdxq']=$qdxq_info[$row['qdxq']]?$qdxq_info[$row['qdxq']]:$qdxq_info['no_mood'];
			}
			if($row['time'] > $today&&$tomorrow>$row['time'])
			{
				// $row['rank']='第'.$i++.'名';
				$row['time'] 	= date('Y-m-d H:i:s', $row['time']);
				$row['is_todaysign']=1;
				$data_qd[]=$row;
			}
			else {
				$row['rank']='今日未签到';
				$row['is_todaysign']=0;
				$row['time'] 	= date('Y-m-d H:i:s', $row['time']);
				$data_noqd[]=$row;
			}
		}
		if($offset>=20)
		{
			$i=$offset+1;
		}
		else {
			$i=1;
		}
		if($data_qd&&is_array($data_noqd))
		{
			$data_qd = array_reverse($data_qd);
			foreach ($data_qd as $k => $v)
			{
				if(empty($condition))
				{
					$data_qd[$k]['rank']='No.'.$i++;//无检索条件才支持排名
				}
				else {
					$data_qd[$k]['rank']='今日已签到';
				}
			}
		}
		if ($data_noqd&&is_array($data_noqd))//拼接数组
		{
			foreach ($data_noqd as $val)
			{
				$data_qd[]=$val;
			}
		}
		$member_name=$this->Members->get_member_name($member_id);
		if($data_qd&&is_array($data_qd))
		{
			foreach ($data_qd as $k=>$v)
			{
				$v['member_name']=$member_name[$v['id']]?$member_name[$v['id']]:'此会员不存在或已被删除';
				$data_qd[$k]=$v;
			}
			return $data_qd;
		}
		return false;
	}

	public function detail($id)//admin参数为了区分前后台调用不同的方法使用
	{
		$condition = " AND member_id = ".intval($id);
		$field='m.member_id,m.member_name,m.avatar,m.gid,m.groupexpiry,m.signature,g.name as groupname,g.usernamecolor,g.icon as groupicon';
		$leftjoin='LEFT JOIN '.DB_PREFIX.'group as g ON m.gid=g.id';
		$member_info=$this->Members->get_member_info($condition,$field,$leftjoin);
		if(empty($member_info))
		{
			$this->membersql->delete('sign', array('member_id'=>$id));
			return false;
		}
		$sql = "SELECT member_id as id,time,days,lasted,mdays,reward,reward_credit1,reward_credit2,credit1,credit2,qdxq,todaysay FROM " . DB_PREFIX . "sign WHERE 1 " . $condition;
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			$qdxq_info=$this->Members->get_qdxq();
			$row['qdxq']=$qdxq_info[$row['qdxq']]?$qdxq_info[$row['qdxq']]:$qdxq_info['no_mood'];
			$today = strtotime(date('Y-m-d',TIMENOW));//今天起始时间
			$tomorrow = strtotime(date('Y-m-d',strtotime("+1 day")));//明天起始时间
			if($row['time'] > $today&&$tomorrow>$row['time'])
			{
				$row['is_todaysign']=1;
				$row['time'] 	= date('Y-m-d H:i:s', $row['time']);
			}
			else {
				$row['is_todaysign']=0;
				$row['time'] 	= date('Y-m-d H:i:s', $row['time']);
			}
			$row['member_info']=$member_info;
			return $row;
		}
		return false;
	}

	/**
	 *
	 * 获取应用配置值
	 */
	function get_setting($field='*')
	{
		$sql='SELECT '.$field.' FROM '.DB_PREFIX.'sign_set WHERE id=1';
		$setting=$this->db->query_first($sql);
		if($setting['credits'])
		{
			$setting['credits']=maybe_unserialize($setting['credits']);
		}
		return $setting;
	}

	/**
	 * 签到功能核心入口函数
	 */
	public function sign($member_id,$todaysay,$qdxq)
	{
		if (empty($member_id))
		{
			return array('status'=>0,'msg'=>array('ErrorCode'=>'0x0005','ErrorText'=>'会员id不能为空'));//会员id不正确
		}
		$member_sign_set=$this->get_setting();

		if(empty($member_sign_set['is_on']))
		{
			return array('status'=>-1,'msg'=>array('ErrorCode'=>'0x0089','ErrorText'=>'签到功能未开启'));//签到功能未开启
		}

		if(empty($member_sign_set['is_qdxq']))//判断是否开启签到心情
		{
			if(empty($qdxq))//签到心情未选择
			{
				return array('status'=>-5,'msg'=>array('ErrorCode'=>'0x0093','ErrorText'=>'你选择的心情不正确，请重新选择签到心情!'));
			}
		}
		else{
			$qdxq='no_mood';//如果未开启心情功能则重置心情为默认无心情选项
		}
		if ($member_sign_set['is_timeopen'])
		{
			$htime=hg_get_format_date(TIMENOW,14);//本次时间
			if($htime<$member_sign_set['limit_time']['start'])////签到时间还未开始
			{
				return array('status'=>-7,'msg'=>array('ErrorCode'=>'0x0095','ErrorText'=>'签到时间还未开始!'));
			}
			elseif ($htime>$member_sign_set['limit_time']['end'])//已经过了签到时间
			{
				return array('status'=>-8,'msg'=>array('ErrorCode'=>'0x0096','ErrorText'=>'已经过了签到时间!')); 
			}
		}
		if(!$member_sign_set['is_todaysay'])
		{
			if($member_sign_set['is_todaysayxt'])
			{
				$todaysay=$todaysay?$todaysay:'该会员没有填写最想说内容';
			}
			elseif(empty($member_sign_set['is_todaysayxt'])&&empty($todaysay)) {//今日最想说内容未填写
				return array('status'=>-6,'msg'=>array('ErrorCode'=>'0x0092','ErrorText'=>'今日最想说内容未填写!')); 
			}
		}
		else {
			$todaysay='该会员没有填写最想说内容';
		}
		$checkdata=$this->Members->checkuser($member_id);


		if(!$checkdata)//会员不存在
		{
			return array('status'=>-4,'msg'=>array('ErrorCode'=>'0x0006','ErrorText'=>'会员不存在')); 
		}
		$today = strtotime(date('Y-m-d',TIMENOW));//今天起始时间
		$sign_info=$this->db->query_first('SELECT * FROM '.DB_PREFIX.'sign WHERE member_id='.intval($member_id));
		$num = $this->db->query_first("SELECT COUNT(*) as count FROM ".DB_PREFIX."sign WHERE time >= {$today} ");
		$signnum=$num['count'];
		$stats = $this->db->query_first("SELECT * FROM ".DB_PREFIX."sign_count WHERE id='1'");
		$credit_type=array();
		$credit_type_info=$this->Members->get_credit_type();//获取已启用的积分类型
		$credit_type=array_keys($credit_type_info);
		if(empty($credit_type))//未设置可用奖励积分.
		{
			return array('status'=>-2,'msg'=>array('ErrorCode'=>'0x0090','ErrorText'=>'未设置可用奖励积分.')); 
		}
		$credits=array();
		$credits=$member_sign_set['credits'];
		$new_credits=array();
		$updatecredit=false;
		if(empty($sign_info))
		{
			$logarr=array(
				'member_id'=>$member_id,
				'time'=>TIMENOW,
				'days'=>1,
				'lasted'=>1,
				'mdays'=>1,
				'qdxq'=>$qdxq,
				'todaysay'=>$todaysay,
			);
			$lastreward=0;//最后增加积分值数量之和
			if($credits&&is_array($credits))
			{
				foreach ($credits as $k => $v)
				{
					if($k=='base'&&is_array($v)&&$v)
					{
						foreach ($v as $kk=>$vv)
						{
							if(in_array($kk, $credit_type))
							{
								$credit_num=intval($vv);
								$lastreward += $credit_num;
								$new_credits[$kk] =$credit_num;
								$logarr[$kk] = $credit_num;
								$logarr['reward_'.$kk] = $credit_num;
							}
						}
					}
					if($k=='lastedop'&&is_array($v)&&$v&&$member_sign_set['is_lastedop'])
					{
						if($member_sign_set['lastedop'])
						{
							$credit_d=$v[1];
							foreach ($credit_d as $kk=>$vv)
							{
								if(in_array($kk, $credit_type))
								{
									$credit_num=intval($vv);
									$lastreward += $credit_num;
									$new_credits[$kk] +=$credit_num;
									$logarr[$kk] += $credit_num;
									$logarr['reward_'.$kk] += $credit_num;
								}
							}
						}
					}
				}
			}
			$logarr['reward'] = $lastreward;
			$ret_log=$this->membersql->create('sign', $logarr);
			if($ret_log)//如果成功则把控制更新积分变量变为true
			{
				$updatecredit=true;
			}
		}
		else
		{
			if($sign_info['time'] > $today)//今天已经签过到!
			{
				return array('status'=>-3,'msg'=>array('ErrorCode'=>'0x0091','ErrorText'=>'今天已经签过到!')); 
			}
				$lasted=1;
				if(($today - $sign_info['time']) < 86400)//判断是否为连续签到@
				{
					$lasted=$sign_info['lasted']+1;
				}
				$lastmonth=hg_get_format_date($sign_info['time'],12);//上次签到月
				$nowmonth=hg_get_format_date(TIMENOW,11);//本次时间
				$mdays=1;
				if($nowmonth==$lastmonth){//是否在同一个月,在同一个月+1;
					$mdays=$sign_info['mdays']+1;
				}
				$logarr=array(
				'member_id'=>$member_id,
				'time'=>TIMENOW,
				'days'=>$sign_info['days']+1,
				'lasted'=>$lasted,//连续天数.
				'mdays'=>$mdays,
				'qdxq'=>$qdxq,
				'todaysay'=>$todaysay,
				);
				$lastreward=0;//最后增加积分值数量之和
				if($credits&&is_array($credits))
				{
					$credit_d=array();
					foreach ($credits as $k => $v)
					{
						if($k=='base'&&is_array($v)&&$v)
						{
							foreach ($v as $kk=>$vv)
							{
								if(in_array($kk, $credit_type))
								{
									$credit_num=intval($vv);
									$lastreward += $credit_num;
									$new_credits[$kk] =$credit_num;
									$logarr[$kk] = $credit_num;
									$logarr['reward_'.$kk] = $sign_info['reward_'.$kk]+$credit_num;
								}
							}
						}
						if($k=='lastedop'&&is_array($v)&&$v&&$member_sign_set['is_lastedop'])
						{
							if($member_sign_set['lastedop']>=$lasted)//如果连续奖励天数在最大可奖励之内则
							{
								$credit_d=$v[$lasted];
								foreach ($credit_d as $kk=>$vv)
								{
									if(in_array($kk, $credit_type))
									{
										$credit_num=intval($vv);
										$lastreward += $credit_num;
										$new_credits[$kk] +=$credit_num;
										$logarr[$kk] += $credit_num;
										$logarr['reward_'.$kk] += $credit_num;
									}
								}
							}
						}
						if($k=='final'&&is_array($v)&&$v&&$member_sign_set['is_lastedop'])
						{
							if($member_sign_set['lastedop']<$lasted&&$member_sign_set['lastedop'])
							{
								$credit_d=$v;
								foreach ($credit_d as $kk=>$vv)
								{
									if(in_array($kk, $credit_type))
									{
										$credit_num = mt_rand(intval($vv['min']),intval($vv['max']));
										$lastreward += $credit_num;
										$new_credits[$kk] +=$credit_num;
										$logarr[$kk] += $credit_num;
										$logarr['reward_'.$kk] += $credit_num;
									}
								}
							}
						}
					}
				}
				$logarr['reward'] = $sign_info['reward']+$lastreward;
				$ret_log=$this->membersql->update('sign', $logarr, array('member_id'=>$member_id));
				if($ret_log)//如果成功则把控制更新积分变量变为true
				{
					$updatecredit=true;
				}
			
		}
		if ($updatecredit)
		{
			if($signnum ==0)
			{
				if($stats['todayq'] > $stats['highestq'])
				{
					$this->db->query("UPDATE ".DB_PREFIX."sign_count SET highestq='$stats[todayq]' WHERE id='1'");
				}
				$this->db->query("UPDATE ".DB_PREFIX."sign_count SET yesterdayq='$stats[todayq]',todayq=1 WHERE id='1'");
				$this->db->query("UPDATE ".DB_PREFIX."sign_emot SET count=0");
			} else
			{
				$this->db->query("UPDATE ".DB_PREFIX."sign_count SET todayq=todayq+1 WHERE id='1'");
			}
			if($qdxq)
			{
				$this->db->query("UPDATE ".DB_PREFIX."sign_emot"." SET count=count+1 WHERE qdxq='".trim($qdxq)."'");
			}
			$this->db->query("UPDATE ".DB_PREFIX."sign_count SET total=total+1 WHERE id='1'");
			$credit_log=array(
		'app_uniqueid'=>APP_UNIQUEID,
		'mod_uniqueid'=>MOD_UNIQUEID,
		'action'=>'sign',
		'method'=>'sign',
		'relatedid'=>$member_id,
		'title'=>'签到',
		'remark'=>'签到成功',
			);
			$members_info=$this->Members->credits($new_credits,$member_id,$coef=1,true,true,true,$credit_type,array(),$credit_log);
			$logarr['member_info']=$members_info;
		}
		$logarr['updatecredit'] = $updatecredit;
		$logarr['status'] = 1;
		$logarr['credit_type']=$credit_type_info;
		$logarr['copywriting_credit']=copywriting_credit(array($logarr));
		unset($logarr['credit_type']);
		$logarr['copywriting']='签到成功';
		return $logarr;
	}
	function ban($member_id)
	{
		if(!$member_id)
		{
			return false;
		}
		if(is_string($member_id)&&!is_numeric($member_id)&&(stripos($member_id, ',')!==false))
		{
			$member_id=explode(',', $member_id);
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			if($member_id&&is_array($member_id))
			{
				$where=' AND member_id IN( '.implode(',', $member_id).')';
			}
			else return false;
		}
		elseif ($member_id&&is_array($member_id))
		{
			$member_id=array_filter($member_id,"clean_array_null");
			$member_id=array_filter($member_id,"clean_array_num");
			$where=' AND member_id IN( '.implode(',', $member_id).')';
		}
		else {
			$member_id=intval($member_id);
			$where=' AND member_id ='.$member_id;
		}
		$this->db->query("UPDATE ".DB_PREFIX."sign"." SET todaysay='该今日最想说内容已被管理员屏蔽!' WHERE 1".$where);
		return array('todaysay'=>'该今日最想说内容已被管理员屏蔽!');
	}
	/**
	 *
	 * 获取签到统计
	 */
	function get_sign_count()
	{
		$sql='SELECT todayq,yesterdayq,highestq,total FROM '.DB_PREFIX.'sign_count WHERE id=\'1\'';
		return  $this->db->query_first($sql);
	}
	
	public function getIsSign($member_id)
	{
		$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'sign WHERE member_id='.intval($member_id).' AND time >'.strtotime(date('Y-m-d',TIMENOW));
		$count = $this->db->query_first($sql);
		return (int)$count['total'];
	}

}

?>