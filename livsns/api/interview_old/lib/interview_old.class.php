<?php 
class interviewInfo_old extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show($condition='', $orderby = ' i.id DESC ', $offset = 0, $count = 10)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT i.*,f.id as fid,f.file_name,f.file_path,f.is_ban 
				FROM '.DB_PREFIX.'interview i 
				LEFT  JOIN '.DB_PREFIX.'files f ON i.cover_pic = f.id 
				WHERE i.isclose=0 '.$condition.$orderby.$limit;
		$res = $this->db->query($sql);
		$k =array();
		while (!false ==($r = $this->db->fetch_array($res)))
		{
			$arr['id'] = $r['id'];
			$arr['title'] = $r['title'];
			$arr['start_time'] = $r['start_time'];
			$arr['end_time'] = $r['end_time'];
			
			//$a = unserialize($r['moderator']);
			//$r['moderator'] = $a[key($a)];
			$r['moderator'] = unserialize($r['moderator']);
			$arr['moderator'] = empty($r['moderator']) ? '' : array_values($r['moderator']);
			$b = unserialize($r['honor_guests']);
			$arr['honor_guests'] = empty($b)? '' : array_values($b);
			$arr['object_type'] = $this->settings['object_type'][$r['object_type']];
			$arr['description'] =$r['description'];
			$arr['is_pre_ask'] = $r['is_pre_ask'];
			$arr['need_login'] = $r['need_login'];
			$arr['cover_pic'] = '';
			if (!$r['is_ban'])
			{
				if ($r['file_path']&&$r['file_name'])
				{
					$arr['cover_pic'] = array(
						'host'=>HOST,
						'dir'=>UPLOAD,
						'filepath'=>$r['file_path'],
						'filename'=>$r['file_name'],
					);
					$arr['pic_size'] = array(0=>'300_',1=>'200_',2=>'75_',3=>'thumb_'); 
				}
			}
			
			$k[] = $arr;	
		}
		return $k;
		
	}
	public function detail($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'interview WHERE id ='.$id;
		$res = $this->db->query_first($sql);
		$msg = '';
		if (!$res)
		{
			$msg  = array(
				'error'=>0,
				'msg'=>'无效的id',	
			);
			return $msg;
		}
		/*
		if (TIMENOW<$res['start_time'])
		{
			$msg = array(
				'error'=>1,
				'msg'=>'访谈还为开始，不能进入',
			);
			return $msg;
		}
		if (TIMENOW>$res['end_time'])
		{
			$msg = array(
				'error'=>2,
				'msg'=>'访谈已经结束',
			);
			return $msg;
		}
		if ($res['isclose'])
		{
			$msg = array(
				'error'=>3,
				'msg'=>'访谈已经关闭',
			);
			return $msg;
		}
		
		if (TIMENOW>$res['start_time'] && TIMENOW<$res['end_time'] && !$res['isclose'])
		{
		*/	
			$a = unserialize($res['moderator']);
			$res['moderator'] = empty($a) ? '' : array_values($a);
			$b = unserialize($res['honor_guests']);
			$res['honor_guests'] = empty($b)? '' : array_values($b);
			$c = unserialize($res['live_source']);
			$res['live_source']= empty($c)? '' : $c;
			unset($res['prms']);
			//图片处理
			$sql  = 'SELECT * FROM '.DB_PREFIX.'files WHERE interview_id = '.$id.' AND is_ban=0';
			$query = $this->db->query($sql);
			$files = array();
			$res['topbg'] = '';
			while ($row = $this->db->fetch_array($query))
			{
				if ($row['show_pos']==0)
				{
					//$res['topbg'] = UPLOAD.$row['file_path'].$row['file_name'];
					$res['topbg'] = array(
					'host'=>HOST,
						'dir'=>UPLOAD,
						'filepath'=>$row['file_path'],
						'filename'=>$$row['file_name'],
					);		
				}else {
					//$files[$row['id']] = UPLOAD.$row['file_path'].$row['file_name'];
					$files[] = array(
						'host'=>HOST,
						'dir'=>UPLOAD,
						'filepath'=>$row['file_path'],
						'filename'=>$row['file_name'],
					);
				}
			
				
			}
			$res['cover_pic'] = $files[$res['cover_pic']]?$files[$res['cover_pic']] :'';
			$res['pic'] = $files;
			$res['pic_size'] =  array(0=>'300_',1=>'200_',2=>'75_',3=>'thumb_');
			$res['online'] = $this->online($id);
			return $res;
		/*
		}
		*/
	}
	//在线人数
	public function online($id=0)
	{
		$sql = 'SELECT count(name) AS total FROM '.DB_PREFIX.'online WHERE 1 ';
		if ($id)
		{
			$sql .= ' AND interviewid = '.$id;
		}
		$res = $this->db->query_first($sql);
		return $res['total'];
	}
	public function content_info($condition, $orderby='', $offset = 0, $count = 20)
	{	
		$condition = $condition ?  $condition : '';
		$limit = " limit {$offset}, {$count}";
		$k =array();
		$moderator = array();
		$honor_guests = array();
		$ids = array();
		//获取主持人和嘉宾
		if ($this->input['interview_id'])
		{
			$int_info =  $this->detail($this->input['interview_id']);
			if (!empty($int_info['moderator']))
			{
				$moderator = $int_info['moderator'];
			}
			if (!empty($int_info['honor_guests']))
			{
				$honor_guests = $int_info['honor_guests'];
			}	
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'records WHERE is_pub=1 and reply_record_id=0 '.$condition.$orderby.$limit;
		$res = $this->db->query($sql);
		while (!false ==($r = $this->db->fetch_array($res)))
		{
				if (in_array($r['user_name'], $moderator))
				{
					$r['user_name'] = '主持人 '.$r['user_name'];
				}
				if (in_array($r['user_name'], $honor_guests))
				{
				$r['user_name'] = '嘉宾 '.$r['user_name'];
				}
				$arr=array();
				$arr['id'] = $r['id'];
				$arr['user_name'] = $r['user_name'];
				preg_match_all('/\[QUOTE\](.*?)\[\/QUOTE\]/', $r['question'], $match_mat);
				$arr['question'] = str_ireplace($match_mat[0][0],'',$r['question']);
				preg_match_all('/\[IMG=(.*?)\]\[\/IMG\]/', $arr['question'], $match_img);
				if (!empty($match_img[0]))
				{
					foreach ($match_img[1] as $val)
					{
						$arr['question_img'][] = $val;
					}
					foreach ($match_img[0] as $val)
					{
						$arr['question'] = str_ireplace($val,'',$arr['question']);
					}
				}
				preg_match_all('/\[:(\d+)\]/', $arr['question'], $match_face);
				
				if (!empty($match_face[0]))
				{
					foreach ($match_face[1] as $val)
					{
						$arr['question_face'][$val] = HOST.$this->settings['qqbiaoqing']['dir'].$val.$this->settings['qqbiaoqing']['type'];
					}
					foreach ($match_face[0] as $val)
					{
						//$arr['question'] = str_ireplace($val,'',$arr['question']);
					}
				}
				
				if ($match_mat[1][0])
				{
					$arr['quote'] = $match_mat[1][0];
					preg_match_all('/\[IMG=(.*?)\]\[\/IMG\]/', $arr['quote'], $match_img);
					if (!empty($match_img[0]))
					{
						foreach ($match_img[1] as $val)
						{
							$arr['quote_img'][] = $val;
						}
						foreach ($match_img[0] as $val)
						{
							$arr['quote'] = str_ireplace($val,'',$arr['quote']);
						}
					}
					preg_match_all('/\[:(\d+)\]/', $arr['quote'], $match_face);
					
					if (!empty($match_face[0]))
					{
						foreach ($match_face[1] as $val)
						{
							$arr['quote_face'][$val] = HOST.$this->settings['qqbiaoqing']['dir'].$val.$this->settings['qqbiaoqing']['type'];
						}
						foreach ($match_face[0] as $val)
						{
							//$arr['quote'] = str_ireplace($val,'',$arr['quote']);
						}
					}
				}			
				$arr['audit_time'] = $r['audit_time'];
				$arr['reply_time'] = $r['reply_time'];
				$arr['interview_id'] = $r['interview_id'];
				$arr['state'] = $r['state']; 
				$arr['is_guests_reply'] = $r['is_guests_reply'];
				if ($r['is_guests_reply'])
				{
					$ids[] = $r['id'];
				}
				
				$k[] = $arr;		
		}
		//获取答案
		$answer = array();
		if (!empty($ids))
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'records WHERE reply_record_id IN ('.implode(',', $ids).')'.$condition;
			//hg_pre($sql);exit();
			$query = $this->db->query($sql);
			while ($row = $this->db->fetch_array($query))
			{
				if (in_array($row['user_name'], $moderator))
				{
					$row['user_name'] = '主持人 '.$row['user_name'];
				}
				if (in_array($row['user_name'], $honor_guests))
				{
					$row['user_name'] = '嘉宾 '.$row['user_name'];
				}
				$a=array();
				$a['id'] = $row['id'];
				$a['reply_record_id'] = $row['reply_record_id'];
				$a['user_name'] = $row['user_name'];
				preg_match_all('/\[QUOTE\](.*?)\[\/QUOTE\]/', $row['question'], $match_mat);
				$a['question'] = str_ireplace($match_mat[0][0],'',$row['question']);
				preg_match_all('/\[IMG=(.*?)\]\[\/IMG\]/', $a['question'], $match_img);
				if (!empty($match_img[0]))
				{
					foreach ($match_img[1] as $val)
					{
						$a['question_img'][] = $val;
					}
					foreach ($match_img[0] as $val)
					{
						$a['question'] = str_ireplace($val,'',$a['question']);
					}
				}
				preg_match_all('/\[:(\d+)\]/', $a['question'], $match_face);
					
				if (!empty($match_face[0]))
				{
					foreach ($match_face[1] as $val)
					{
						$a['question_face'][$val] = $this->settings['qqbiaoqing']['dir'].$val.$this->settings['qqbiaoqing']['type'];
					}
					foreach ($match_face[0] as $val)
					{
						//$arr['question'] = str_ireplace($val,'',$arr['question']);
					}
				}	
				if ($match_mat[1][0])
				{
					$a['quote'] = $match_mat[1][0];
					preg_match_all('/\[IMG=(.*?)\]\[\/IMG\]/', $a['quote'], $match_img);
					if (!empty($match_img[0]))
					{
						foreach ($match_img[1] as $val)
						{
							$a['quote_img'][] = $val;
						}
						foreach ($match_img[0] as $val)
						{
							$a['quote'] = str_ireplace($val,'',$a['quote']);
						}
					}
					preg_match_all('/\[:(\d+)\]/', $a['quote'], $match_face);
						
					if (!empty($match_face[0]))
					{
						foreach ($match_face[1] as $val)
						{
							$a['quote_face'][$val] = $this->settings['qqbiaoqing']['dir'].$val.$this->settings['qqbiaoqing']['type'];
						}
						foreach ($match_face[0] as $val)
						{
								//$arr['quote'] = str_ireplace($val,'',$arr['quote']);
						}
					}
				}			
				$a['audit_time'] = $row['audit_time'];
				$a['reply_time'] = $row['reply_time'];
				$a['interview_id'] = $row['interview_id'];
				$a['state'] = $row['state']; 
				$a['is_guests_reply'] = $row['is_guests_reply'];
				$answer[] = $a;
				
			}
		}
		return array('question'=>$k,'answer'=>$answer);
	}
	public function pre_ask($data)
	{
		// 数据库插入
		$sql = 'INSERT INTO '.DB_PREFIX.'records SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",'; 
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		//更新order_id，默认与ID值相同
		$u_sql = 'UPDATE '.DB_PREFIX.'records SET order_id = '.$data['id'].' WHERE id ='.$data['id'];
		$this->db->query($u_sql);
		return $data['id'];
	}
	/**
	 * 
	 * 访谈角色信息
	 * @param int $user_id
	 * @param int $interview_id
	 * @return str $r 		  角色
	 */
	
	public function role($user_id,$interview_id)
	{
		//参数接收
		$data = array(
			'user_id'=>$user_id,
			'interview_id'=>$interview_id,
		);
		if (!isset($data['user_id']) || !$data['interview_id']){
			return ;
		}
		//返回结果
		$sql = 'SELECT role FROM '.DB_PREFIX.'interview_user WHERE 1 
				AND interview_id = '.$interview_id.' AND user_id ='.$user_id;
		$res = $this->db->query_first($sql);
		$role = $res['role']?$res['role']:0;
		return $role;
	}
	
	/**
	 * 
	 * 访谈权限
	 * @param int $role   角色
	 * @param int $interview_id  访谈ID
	 * @return Array $r 		权限组，0是发言，1是回复，2是修改，3是背景颜色，4是字体颜色
	 */
	public function prms($role,$interview_id)
	{
		//参数接收
		$data = array(
			'role'=>$role,
			'interview_id'=>$interview_id,
		);
		
		if (!isset($data['role']) || !$data['interview_id']){
			return ;
		}
		//返回结果
		$sql = 'SELECT prms FROM ' .DB_PREFIX .'interview  where id ='.$data['interview_id'];
		$res = $this->db->query_first($sql);
		$prms = $res['prms'];
		$arr = unserialize($prms);
		$r = $arr[$data['role']];
		return $r;
	}
	public function speech($interview_id, $content, $user_id, $user_name, $time, $ip,
					$state, $audit_time, $guest_id = 0,$reply_time)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.'records SET interview_id = '.$interview_id.
		',question = "'.$content.
		'",user_id = '.$user_id.
		',user_name = "'.$user_name.
		'",create_time = "'.$time.
		'",ip = "'.$ip.
		'",state = '.$state.
		',reply_time = '.$reply_time.
		',audit_time = '.$audit_time.
		',guests_id = "'.$guest_id.'"';
		$this->db->query($sql);
		$id = $this->db->insert_id();
		//更新order_id，默认与ID值相同
		$ord_sql = 'UPDATE '.DB_PREFIX.'records SET order_id = '.$id.' WHERE id ='.$id;
		$this->db->query($ord_sql);	
		//插入临时表
		$temp_sql = "replace into ". DB_PREFIX . "change(records_id,before_status,after_status,update_time) values(".$id.",".$state.",".$state.",".TIMENOW.")";
		$this->db->query($temp_sql);
		return $id;
	
	}
	public function checkInterview($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'interview WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		
		if ($ret['start_time']<TIMENOW && $ret['end_time']>TIMENOW && !$ret['isclose'])
		{
			return true;
		}else {
			return false;
		}
	}
	/**
	 * 
	 * 通过内容ID获取访谈ID
	 * @param unknown_type $id
	 */
	public function getId($id)
	{
		$sql = 'SELECT interview_id FROM '.DB_PREFIX.'records WHERE id='.$id;
		$res = $this->db->query_first($sql);
		return $res['interview_id'];
	}
	/**
	 * 
	 * 获取引用内容
	 * @param int $id
	 * @return string $question 返回内容
	 */
	public function getQuestion($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'records WHERE id='.$id;
		$res = $this->db->query_first($sql);
		return $res;
	}
	/**
	 * 获取权限
	 */
	public function check_int_prms($id)
	{
		if (!intval($id))
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'interview WHERE id = '.$id;
		$res = $this->db->query_first($sql);
		$res['moderator'] =  unserialize($res['moderator'])?unserialize($res['moderator']):array();
		$res['honor_guests'] =  unserialize($res['honor_guests'])?unserialize($res['honor_guests']):array();
		$res['live_source'] =  unserialize($res['live_source'])?unserialize($res['live_source']):'';
		$res['prms'] =  unserialize($res['prms'])?unserialize($res['prms']):'';
		return $res;
	}
	/**
	 * 计算utf-8的字符个数
	 * @param $str
	 * @return unknown_type
	 */
	public function strlen_utf8($str)
	{
		$i = 0;
		$count = 0;
		$len = strlen ($str);
		while ($i < $len)
		{
			$chr = ord ($str[$i]);
			$count++;
			$i++;
			if($i >= $len) break;
			if($chr & 0x80)
			{
				$chr <<= 1;
				while ($chr & 0x80)
				{
					$i++;
					$chr <<= 1;
				}
			}
		}
		return $count;
	}
	/**
	 * 获取相关状态数据
	 * @param $state
	 * @return unknown_type
	 */
	public function get_data($state = '',$interview_id)
	{
		$where_cond = '';
		if($state == 0)
		{
			$where_cond .= ' AND state=0 ORDER BY id ASC';
		}
		else if($state == 2)
		{
			if($this->user['role'] == 3)
			{
				$where_cond .= ' AND state != 0 AND state != 3 AND guests_id=' . $this->user['id'] . ' ORDER BY audit_time ASC';
			}
			else
			{
				$where_cond .= ' AND state != 0 AND state != 3 ORDER BY audit_time ASC';
			}
		}
		else if($state == 3)
		{
			$where_cond .= ' AND state=3 ORDER BY reply_time ASC';
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'records WHERE interview_id=' .$interview_id  . $where_cond;
		$result = $this->db->query($sql);
		while($row = $this->db->fetch_array($result))
		{
			$records_lists[] = $row;
		}
		return $records_lists;
	} 
	
}




