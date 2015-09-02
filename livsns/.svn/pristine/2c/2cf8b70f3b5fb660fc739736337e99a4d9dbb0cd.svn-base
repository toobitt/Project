<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/feedback_mode.php';
define('MOD_UNIQUEID','feedback_update');//模块标识
class feedbackUpdateApi extends outerUpdateBase
{
	private $record;
	private $record_img;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new feedback_mode();
		require ROOT_PATH . 'lib/class/members.class.php';
		require_once ROOT_PATH . 'lib/class/curl.class.php';
		$this->members = new members();		
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$device_token = trim($this->input['device_token']);
		$ip = hg_getip();
		$feedback = $this->mode->get_feedback_list('id = '.$id);
		$feedback = $feedback[0];
		if(!$feedback)
		{
			$this->errorOutput(NOT_EXISTS_CONTENT);
		}
		if(!$feedback['status'] || $feedback['status'] == 2)
		{
			$this->errorOutput(NO_AUDIT);
		}
		if($feedback['start_time'] > TIMENOW)
		{
			$this->errorOutput(FB_NO_START);
		}
		if($feedback['end_time'] && $feedback['end_time'] < TIMENOW)
		{
			$this->errorOutput(FB_END);
		}
		if($feedback['is_login'] && !$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
				
		//同一用户投票时间限制
		if ($feedback['is_login'] && $feedback['userid_limit_num'])
		{
			$this->check_user_num($id,$feedback['userid_limit_time'],$feedback['userid_limit_num']);
		}
		else if($device_token && $feedback['is_device'] && $feedback['device_limit_num'])
		{
			$this->check_device_num($id,$feedback['device_limit_time'],$feedback['device_limit_num'],$device_token,$uuid);
			
		}
		elseif(!$device_token && $feedback['is_device'])
		{
			$error = defined('NO_DEVICE_TIPS') && NO_DEVICE_TIPS  ? NO_DEVICE_TIPS : '您的客户端版本太低，请先升级';
			$this->errorOutput($error);
		}
		
		//ip投票时间限制
		if ($feedback['is_ip'] && (!$device_token || !$feedback['is_device']) && !$feedback['is_login'])
		{
			$this->check_ip_num($id,$feedback['ip_limit_time'],$feedback['ip_limit_num']);
		}
				
		if($this->settings['App_verifycode'] && $feedback['is_verifycode'])
		{
			$this->check_verify_code(trim($this->input['verify_code']),trim($this->input['session_id']));
		}
		$forms = array();
		$forms = $this->input['form'];
		$_forms = $this->mode->get_forms($id);
		if($_forms)
		{
			foreach ($_forms as $k=>$v)
			{
				$_oldforms[$v['type']][$v['id']] = $v;
			}
		}
		if(!$forms && !$_FILES)
		{
			$this->errorOutput('未提交数据');
		}
		foreach ($_forms as $key=>$value) //表单验证
		{
			$this->check_limit($forms,$value,$id);
			if($value['mode_type'] != 'file' && $value['mode_type'] != 'split')
			{				
				if(is_array($forms[$value['type'].'_'.$value['id']]))
				{
					$forms[$value['type'].'_'.$value['id']] = array_filter($forms[$value['type'].'_'.$value['id']]);
					$form_value = implode(',',$forms[$value['type'].'_'.$value['id']]);
					if($value['mode_type'] == 'time')
					{
						if(!$forms[$value['type'].'_'.$value['id']][1]) unset($forms[$value['type'].'_'.$value['id']][1]);
						if(!$forms[$value['type'].'_'.$value['id']][2]) unset($forms[$value['type'].'_'.$value['id']][2]);
						if(!$forms[$value['type'].'_'.$value['id']][3]) unset($forms[$value['type'].'_'.$value['id']][3]);
						$form_value = implode(':',$forms[$value['type'].'_'.$value['id']]);
					}
				}
				else 
				{
					$form_value = $forms[$value['type'].'_'.$value['id']];
				}
				$this->record[] = array(
				    'feedback_id' => $id,
				    'type'      => $value['type'],
				    'form_id'   => $value['id'],
				    'form_name' => $value['name'],
				    'value'     => addslashes($form_value),
				    'order_id'  => $value['order_id'],
				);
			}
		}
		if($_FILES)
		{
			$this->upload_material($_FILES,$_oldforms,$id);
		}
		$person = array(
		    'user_id'    => $this->user['user_id'],
		    'user_name'  => $this->user['user_name'],
		    'ip'         => hg_getip(),
		    'app_id'     => $this->user['appid'],
		    'app_name'   => $this->user['display_name'],
		    'create_time'=> TIMENOW,
		    'feedback_id'=> $id,
		    'column_id'  => intval($this->input['column_id']),
			'device_token'  => md5($device_token),
		    'process'    => 0,
			'source_id'    => intval($this->input['source_id']),
			'source_app'    => trim($this->input['source_app']),
		);
		$person_id = $this->mode->create($person,'record_person');
		if($this->record && is_array($this->record))
		{
			foreach ($this->record as $k=>$v)
			{
				$this->record[$k]['person_id'] = $person_id;
			}
		}
		$record = $this->mode->insert_datas('record', $this->record);
		
		//增加积分
		if($this->user['user_id'] && $feedback['is_credit'] && !AUDIT_ADD_CRIDET)
		{
			$this->add_credits($id,$feedback['title'],$feedback['credit1'],$feedback['credit2']);
		}
		//同步会员信息
		if(trim($this->user['token']) && $arr && is_array($arr))
		{
			if($arr['nick_name']) unset($arr['nick_name']);
			$ret = $this->members->update_member_info(trim($this->user['token']),$arr);
		}	
		
		$sql = 'UPDATE '.DB_PREFIX.'feedback SET counts = counts+1 WHERE id ='.$id ;
		$this->db->query($sql);
		if(intval($this->input['need_upload_img']) && $this->record_img)
		{
			$keysvalue = array();
			foreach ($this->record as $k=>$v)
			{
				if($v['type'] == 'file' && $v['value'])
				{
					$this->record[$k]['value'] = array();
					$img_value = explode(',',$v['value']);
					$mas = explode('_',$img_value[0]);
					$mas[0] ? $this->record[$k]['value']['indexpic'] = $this->record_img[$mas[0]]['indexpic'] : false;
					$mas[1] ? $this->record[$k]['value']['mediaurl'] = $this->record_img[$mas[1]]['mediaurl'] : false;
				}
				$keysvalue[$k] = $v['order_id'];
			}
			//更新输出结果的排序
			$new_record = $this->record;
			asort($keysvalue);
			if(is_array($keysvalue))
			{
				foreach ($keysvalue as $k=>$v){
					$record[] = $new_record[$k];
				}
			}
		}
		$this->addItem($record);
		$this->output();
	}

	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$device_token = trim($this->input['device_token']);
		$ip = hg_getip();
		$feedback = $this->mode->get_feedback_list('id = '.$id);
		$fb = $feedback[0];
		if(!$fb)
		{
			$this->errorOutput(NOT_EXISTS_CONTENT);
		}
		if(!$fb['status'] || $fb['status'] == 2)
		{
			$this->errorOutput(NO_AUDIT);
		}
		if($fb['start_time'] > TIMENOW)
		{
			$this->errorOutput(FB_NO_START);
		}
		if($fb['end_time'] && $fb['end_time'] < TIMENOW)
		{
			$this->errorOutput(FB_END);
		}
		if($fb['is_login'] && !$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
				
		//同一用户投票时间限制
		if ($fb['is_login'] && $fb['userid_limit_num'])
		{
			$this->check_user_num($id,$fb['userid_limit_time'],$fb['userid_limit_num']);
		}
		else if($device_token && $fb['is_device'] && $fb['device_limit_num'])
		{
			$this->check_device_num($id,$fb['device_limit_time'],$fb['device_limit_num'],$device_token,$uuid);
			
		}
		elseif(!$device_token && $fb['is_device'])
		{
			$error = defined('NO_DEVICE_TIPS') && NO_DEVICE_TIPS  ? NO_DEVICE_TIPS : '您的客户端版本太低，请先升级';
			$this->errorOutput($error);
		}
		//ip投票时间限制
		if ($fb['is_ip'] && (!$device_token || !$fb['is_device']) && !$fb['is_login'])
		{
			$this->check_ip_num($id,$fb['ip_limit_time'],$fb['ip_limit_num']);
		}
				
		if($this->settings['App_verifycode'] && $fb['is_verifycode'])
		{
			$this->check_verify_code(trim($this->input['verify_code']),trim($this->input['session_id']));
		}
		$forms = array();
		$forms = $this->input['form'];
		$_forms = $this->mode->get_forms($id);
		if(!$forms && !$_FILES)
		{
			$this->errorOutput('未提交数据');
		}
		if(!$pid = intval($this->input['pid']))
		{
			$this->errorOutput('提交失败');
		}		
		$sql = 'SELECT * FROM '.DB_PREFIX.'record WHERE person_id = '.$pid;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$old_id[] = $r['id'];
			$check[$r['type'].'_'.$r['form_id']] = $r;
		}
		if($_forms)
		{
			foreach ($_forms as $k=>$v)
			{
				$_formname[$v['type'].'_'.$v['id']]['name'] = $v['name'];
				$_formname[$v['type'].'_'.$v['id']]['order_id'] = $v['name'];
				$_formname[$v['type'].'_'.$v['id']]['mode_type'] = $v['mode_type'];
				$_oldforms[$v['type'][$v['id']]] = $v;
				$this->check_limit($forms,$v,$id);
			}
		}
		foreach ($forms as $k=>$v)
		{
			if(is_array($v))
			{
				$form_value = implode(',',$v);
				if($_formname[$k]['mode_type'] == 'time')
				{
					$v = array_filter($v);
					$form_value = $v ? implode(':',$v) : '';
				}
			}else 
			{
				$form_value = $v;
			}
			if($check[$k] && $check[$k]['id'] && $check[$k]['value'] != $form_value)
			{
				$sql = 'UPDATE '.DB_PREFIX .'record SET value = "'.$form_value.'" WHERE id = '.$check[$k]['id'];
				$this->db->query($sql);
			}elseif((!$check[$k] || !$check[$k]['id']) && $_formname[$k]['mode_type'] != 'file') 
			{
				$new_type = explode('_',$k);
				$insertdata[] = array(
					'feedback_id' => $id,
					'person_id'	=> $pid,
					'type'      => $new_type[0],
					'form_id'   => $new_type[1],
			        'form_name' => $_formname[$k]['name'],
					'value'     => $form_value,
			        'order_id'  => $_formname[$k]['order_id'],
				);
			}
		}
		if($insertdata)
		{
			$record = $this->mode->insert_datas('record', $insertdata);
		}
		if($_FILES)
		{
			$this->upload_material($_FILES,$_oldforms,$id,$check);
		}
		if($this->mater)
		{
			foreach ($this->mater as $ks=>$vs)
			{
				$sql = 'UPDATE '.DB_PREFIX .'record SET value = "'.$vs.'" WHERE id = '.$ks;
				$this->db->query($sql);
			}
		}
		if($this->new_record)
		{
			$record = $this->mode->insert_datas('record', $this->new_record);
		}
		
		//增加积分
		if($this->user['user_id'] && $fb['is_credit'] && !AUDIT_ADD_CRIDET)
		{
			$this->add_credits($id,$fb['title'],$fb['credit1'],$fb['credit2']);
		}
		
		if(intval($this->input['need_upload_img']) && $this->record_img)
		{
			$keysvalue = array();
			foreach ($this->record as $k=>$v)
			{
				if($v['type'] == 'file' && $v['value'])
				{
					$this->record[$k]['value'] = array();
					$img_value = explode(',',$v['value']);
					$mas = explode('_',$img_value[0]);
					$mas[0] ? $this->record[$k]['value']['indexpic'] = $this->record_img[$mas[0]]['indexpic'] : false;
					$mas[1] ? $this->record[$k]['value']['mediaurl'] = $this->record_img[$mas[1]]['mediaurl'] : false;
				}
				$keysvalue[$k] = $v['order_id'];
			}
			//更新输出结果的排序
			$new_record = $this->record;
			asort($keysvalue);
			if(is_array($keysvalue))
			{
				foreach ($keysvalue as $k=>$v){
					$record[] = $new_record[$k];
				}
			}
		}
		$this->addItem($record);
		$this->output();
	
	}
	
	public function delete()
	{}

	/**
	 * 登录用户提交次数限制
	 * @param unknown_type $id 表单id
	 * @param unknown_type $userid_limit_time 限制时间
	 * @param unknown_type $userid_limit_num 限制次数
	 */
	private function check_user_num($id,$userid_limit_time , $userid_limit_num)
	{
		$user_time = TIMENOW - $userid_limit_time * 3600;
		$sql = "SELECT  count(id) as total  FROM " . DB_PREFIX . "record_person ";
		$sql.= " WHERE feedback_id = " . $id . " AND user_id = " . $this->user['user_id'];
		if($userid_limit_time)
		{
			$sql .= " AND create_time > " . $user_time ;
		}
		$user_feed = $this->db->query_first($sql);
		if($user_feed['total'] >= $userid_limit_num)
		{
			$error1 =  '同一用户在' .$userid_limit_time . '小时内最多提交'.$userid_limit_num.'次！';
			$error2 =  '同一用户最多提交'.$userid_limit_num.'次！';
			$error = $userid_limit_time ? $error1 : $error2;				
			$this->errorOutput($error);
		}
	}
	
	/**
	 * 同一ip提交次数限制
	 * @param unknown_type $id 表单id
	 * @param unknown_type $userid_limit_time 限制时间
	 * @param unknown_type $userid_limit_num 限制次数
	 */
	private function  check_ip_num($id ,$ip_limit_time ,$ip_limit_num)
	{
		$ip_time = TIMENOW - $ip_limit_time * 3600;
		$sql = "SELECT count(id) as iptotal FROM " . DB_PREFIX . "record_person WHERE 1 AND feedback_id = " . $id . " AND ip = '" . $ip . "'";
		if($ip_limit_time * 3600)
		{
			$sql .= " AND create_time > " . $ip_time ;
		}
		$ip_question_preson = $this->db->query_first($sql);
		if ($ip_question_preson['iptotal'] >= $ip_limit_num)
		{
			$error1 =  '同一IP在' . $ip_limit_time . '小时内最多提交'.$ip_limit_num.'次！';
			$error2 =  '同一IP多提交'.$ip_limit_num.'次！';
			$error = $ip_limit_time ? $error1 : $error2;
			$this->errorOutput($error);
		}
	}
	
	/**
	 * 同一设备提交次数限制
	 * @param unknown_type $id 表单id
	 * @param unknown_type $userid_limit_time 限制时间
	 * @param unknown_type $userid_limit_num 限制次数
	 */
	private function check_device_num($id,$device_limit_time,$device_limit_num,$device_token,$uuid = '')
	{
		if($this->settings['App_mobile'] && NEED_CHECK_DEVICE)
		{
			$this->curl = new curl($this->settings['App_mobile']['host'],$this->settings['App_mobile']['dir']);
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('device_token',$device_token);
			$this->curl->addRequestData('uuid',$uuid);
			$ret = $this->curl->request('mobile_device.php');
			if(!$ret[0])
			{
				$this->errorOutput(ERROR_DEVICE_TOKEN);
			}
		}
		$device_user_time = TIMENOW - $device_limit_time * 3600;
		$sql = "SELECT count(id) as total FROM " . DB_PREFIX . "record_person WHERE 1 ";
		$sql.= " AND  feedback_id = " . $id . " AND device_token = '" . md5($device_token)."'";
		if($feedback['device_limit_time'])
		{
			$sql .= " AND create_time > " . $device_user_time ;
		}
		$device_feed = $this->db->query_first($sql);
		
		if($device_feed['total'] >= $device_limit_num)
		{
			$error1 =  '同一设备在' .$device_limit_time . '小时内最多提交'.$device_limit_num.'次！';
			$error2 =  '同一设备最多提交'.$device_limit_num.'次！';
			$error = $device_limit_time ? $error1 : $error2;				
			$this->errorOutput($error);
		}
	}
	
	/**
	 * 
	 * 验证码
	 * @param unknown_type $verifycode 验证码
	 * @param unknown_type $session_id 验证码标识
	 */
	private function check_verify_code($verifycode, $session_id)
	{
		require_once ROOT_PATH . 'lib/class/verifycode.class.php';
		$mVerifyCode = new verifyCode();
		$check_result = $mVerifyCode->check_verify_code($verifycode, $session_id);  //验证验证码
	    if( $check_result != 'SUCCESS')
		{
			$this->errorOutput($check_result);
		}
	}
	
	/**
	 * 检查必填等限制项
	 * @param unknown_type $forms 提交的表单
	 * @param unknown_type $value
	 */
	private function check_limit($forms,$value,$id)
	{
		$nums = 0;
		if($value['is_required']  && !$forms[$value['type'].'_'.$value['id']])
		{
			if(($value['type'] == 'standard' && $value['form_type'] == 5 && $_FILES['file_'.$value['id']]) || ($value['type'] == 'standard' && $value['form_type'] == 6 ))
			{}
			else
			{
				$this->errorOutput($value['name'].'不能为空！');
			}
		}
		if($value['is_unique'] && $forms[$value['type'].'_'.$value['id']])
		{
			if($this->check_exist($forms[$value['type'].'_'.$value['id']],$id,$value['id']))
			{
				$this->errorOutput($value['name'].' '.$forms[$value['type'].'_'.$value['id']].' 已经存在！');
			}
		}
		if(is_array($forms[$value['type'].'_'.$value['id']]) && !array_filter($forms[$value['type'].'_'.$value['id']]) && $value['is_required'])
		{
			$this->errorOutput($value['name'].'不能为空！');
		}
		if($value['char_num'])
		{
			 $num = $this->get_char_num(trim($forms[$value['type'].'_'.$value['id']]));
			 if($num >$value['char_num'])
			 {
			 	$this->errorOutput($value['name'].'字符数不能超过'.$value['char_num'].'个！');
			 }
		}
		if($forms[$value['type'].'_'.$value['id']] && ($value['fixed_id'] == 3 && $value['type'] == 'fixed') || ($value['member_field'] == 'mobile'))
		{
			$check_mobile = hg_verify_mobile_fb($forms[$value['type'].'_'.$value['id']]);
			if(!$check_mobile && $forms[$value['type'].'_'.$value['id']])
			{
				$this->errorOutput(MOBILE_ERROR);
			}
		}
		if($forms[$value['type'].'_'.$value['id']] &&  ($value['fixed_id'] == 2 && $value['type'] == 'fixed') || ($value['member_field'] == 'email'))
		{
			$check_email = hg_check_email_format($forms[$value['type'].'_'.$value['id']]);
			if(!$check_email)
			{
				$this->errorOutput(EMAIL_ERROR);
			}
		}
		if($value['form_type'] == 3 && $value['type'] == 'standard') //多选的选项数
		{
			if(is_array($forms[$value['type'].'_'.$value['id']]))
			{
				$nums = count(array_filter($forms[$value['type'].'_'.$value['id']]));
			}
			else
			{
				$nums = count(array_filter(explode(',',$forms[$value['type'].'_'.$value['id']])));
			}
			if($nums)
			{
				if($value['limit_type'] == 1 && $nums < $value['op_num'] &&  $nums >0 )
				{
					$this->errorOutput($value['name'].'至少要选'.$value['op_num'].'项！');
				}
				if($value['limit_type'] == 2 && $nums > $value['op_num'])
				{
					$this->errorOutput($value['name'].'至多只能选'.$value['op_num'].'项！');
				}
				if($value['limit_type'] == 3 && $nums != $value['op_num'])
				{
					$this->errorOutput($value['name'].'只能且必须选'.$value['op_num'].'项！');
				}
				if($value['min'] && $nums < $value['min'] && $nums >0)
				{
					$this->errorOutput($value['name'].'至少要选'.$value['min'].'项！');
				}
				if($value['max'] && $nums > $value['max'])
				{
					$this->errorOutput($value['name'].'至多只能选'.$value['max'].'项！');
				}
			}
		}
		if($value['fixed_id'] == 5 && $forms[$value['type'].'_'.$value['id']])//日期范围
		{
			if(!$input_time = strtotime($forms[$value['type'].'_'.$value['id']]))
			{
				$this->errorOutput( $value['name'] .'不是合法的日期格式');
			}
			$_start_time = strtotime($value['start_time'].'-01-01 00:00:00');
			$_end_time = strtotime($value['end_time'].'-12-31 23:59:59');
			if(($_start_time && $input_time < $_start_time) or ($_end_time && $input_time > $_end_time))
			{
				$this->errorOutput( $value['name'] .'超出时间范围！');
			}
		}
	}
	
	/**
	 * 
	 * 上传附件
	 * @param unknown_type $sub_file
	 * @param unknown_type $_oldforms
	 * @param unknown_type $id
	 */
	private function upload_material($sub_file,$_oldforms,$id ,$check  = array())
	{
		$video_allow_type = $this->settings['video_type']['allow_type'] ? explode(',',$this->settings['video_type']['allow_type']) : array();
		$materials_allow_type = $this->mode->get_allow_type();
		foreach ($sub_file as $key=>$val)
		{	
			$form_id = '';
			$files = array();
			$form_id = str_replace('file_','',$key);
			if(is_array($val['tmp_name']))//多个文件上传
			{
				foreach ($val as $key_name=>$values)
				{
					foreach($values AS $k =>$v)
					{
						if($key_name == 'name')
						{
							$v = urldecode($val['name'][$k]);
						}
						$files[$k][$key_name] = $v;
					}
				}
				foreach ($files as $index=>$one_file)
				{
					if($tp = $this->check_material($one_file,$video_allow_type,$materials_allow_type))
					{
						$files[$index] = array($tp=>$one_file);
					}
				}
				foreach ($files as $mate)
				{
					$material_info[$form_id][] = $this->upload($mate, $id);
				}
			}
			else //单个文件上传
			{
				if($tp = $this->check_material($val,$video_allow_type,$materials_allow_type))
				{
					$files[$tp] = $val;
					$material_info[$form_id][] = $this->upload($files, $id);
				}
			}
			$arr = array(
				'feedback_id' => $id,
				'type'      => 'file',
				'form_id'   => $form_id,
		        'form_name' => $_oldforms['standard'][$form_id]['name'],
				'value'     => $material_info[$form_id] ? implode(',',$material_info[$form_id]): '',
		        'order_id'  => $_oldforms['standard'][$form_id]['order_id'],
			);
			$this->record[] = $arr;
			if(!$check[$key]['id'])
			{
				$arr['person_id'] = intval($this->input['pid']);
				$this->new_record[] = $arr;
			}else 
			{
				$this->mater[$check[$key]['id']] = $material_info[$form_id] ? implode(',',$material_info[$form_id]): '';
			}
		}
		
	}
	
	private function upload($mate,$id)
	{
		if($mate['Filedata'])
		{
			if($pic = $this->mode->uploadToPicServer($mate, $id))
			{
				$material_id = $this->uploade_to_material($pic,$id);
			}
			else 
			{
				$this->errorOutput('图片服务器错误!');
			}
		}
		elseif($mate['videofile'])
		{
			if($video = $this->mode->uploadToVideoServer($mate))
			{
				//有视频没有图片时，将视频截图上传作为索引图
				if(!$material_id && $video['img'])
				{
					$url = $video['img']['host'].$video['img']['dir'].$video['img']['filepath'].$video['img']['filename'];
					$material = $this->mode->localMaterial($url, $id);
					//此处可能是音频,视频取截图作为索引图
					if ($material)
					{
						$material_id = $this->uploade_to_material($material,$id,1);
					}
					$video['pic_id'] = $material_id ? $material_id : 0 ;
					$videoid = $this->uploade_to_material($video,$id,1);
					$material_id = $material_id ? $material_id.'_'.$videoid : $videoid;
				}
			}
			else 
			{
				$this->errorOutput('视频服务器错误!');
			}
		}
		return $material_id;
	}
	
	private function uploade_to_material($m,$id,$is_vod = 0)
	{
		$temp = array(
			'content_id'	=> $id,
			'mtype'			=> $m['type'],						
			'original_id'	=> $is_vod ? $m['pic_id'] : $m['id'],
			'host'			=> $is_vod ? $m['protocol'].$m['host'] : $m['host'],
			'dir'			=> $m['dir'],
			'material_path' => $m['filepath'],
			'pic_name'		=> addslashes($m['filename']),
			'imgwidth'		=> $m['imgwidth'],
			'imgheight'		=> $m['imgheight'],
			'is_vod_pic'	=> $is_vod,
			'vodid'		 	=> $is_vod ? $m['id'] : 0,
			'filename'	 	=> $m['file_name'],
		);
		$mid = $this->mode->upload($temp);
		if($temp['pic_name'])
		{
			$this->record_img[$mid]['indexpic'] = $temp;
		}
		else if($temp['filename'])
		{
			$this->record_img[$mid]['mediaurl'] = $temp;
		}
		return $mid;
	}
	
	/**
	 * 新增会员积分
	 * @param unknown_type $id
	 * @param unknown_type $title
	 * @param unknown_type $credit1
	 * @param unknown_type $credit2
	 */
	private function add_credits($id,$title,$credit1,$credit2)
	{
		$sql = 'SELECT COUNT(id) as total FROM '.DB_PREFIX.'record_person WHERE user_id = '.$this->user['user_id'].' AND feedback_id ='.$id ;
		$credits = $this->db->query_first($sql);
		if($credits['total'] <= ADD_CREDIT_NUM)
		{
			$re = $this->members->add_credit(
				$this->user['user_id'],
	            array('credit1'=>$credit1,'credit2'=>$credit2),
            	$id,
            	APP_UNIQUEID,
            	MOD_UNIQUEID,
            	'create',
            	'参与:'.$title,
            	'反馈表单'
	            );
        }
		
	}	
	function get_char_num($str)
	{
		if(function_exists("mb_strlen"))
		{
			return mb_strlen($str,'utf8'); 
		}
		else
		{
			preg_match_all("/./us", $str, $match);
			// 返回单元个数
			return count($match[0]);
		}  	 
	}
		
	private function check_exist($value,$id,$form_id)
	{
		if(trim($value) && $id && $form_id)
		{
			$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'record WHERE feedback_id = '.$id.' and form_id = '.$form_id.' and value = "'.trim($value).'"';
			$re = $this->db->query_first($sql);
			return $re['total'] > 0 ? true : false;
		}
		else
		{
			$this->errorOutput(PARA_ERROR);
		}
	}
	
	private function check_material($file,$v_type = array(),$m_type = array())
	{
		if (!$file['name'])
		{
			$this->errorOutput('尚未上传文件!');
		}
		$file_type = strtolower(strrchr($file['name'], '.'));
		if(in_array($file_type, $m_type))
		{
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装!');
			}
			if ($file['error'] > 0)
			{
				$this->errorOutput('图片上传异常!');
			}
			if ($file['size'] > UPLOAD_MATERIAL_SIZE*1024*1024)
			{
				$this->errorOutput('只允许上传'.UPLOAD_MATERIAL_SIZE.'M以下的图片!');
			}
			return 'Filedata';
	    }
	    elseif (in_array($file_type,$v_type))
	    {	    	
	    	if (!$this->settings['App_livmedia'])
	    	{
	    		$this->errorOutput('视频服务器未安装!');
	    	}
	    	if ($file['error'] > 0)
	    	{
	    		$this->errorOutput('视频上传错误！');
	    	}
			if ($file['size'] > UPLOAD_MEDIA_SIZE*1024*1024)
			{
				$this->errorOutput('只允许上传'.UPLOAD_MEDIA_SIZE.'M以下的视频!');
			}
			return 'videofile';
		}
		elseif ($file_type)
		{
			$this->errorOutput('附件上传类型错误，仅支持图片和视频上传！');
		}
	}

	public function check_feedback()
	{
		$id = intval($this->input['id']);
		$device_token = trim($this->input['device_token']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$feedback = $this->mode->get_feedback('id = '.$id,'id,status,start_time,end_time,brief,create_time,is_login,is_device');
		if($feedback['is_login'] && !$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
		if(!$feedback['status'] or $feedback['status'] == 2)
		{
			$this->errorOutput(NO_AUDIT);
		}
        if(intval($this->input['is_result_page']))
        {
            $data['back'] = 0;
            $this->addItem($data);
            $this->output();
        }
        if($feedback['is_device'] &&  !$device_token)
        {
            $this->errorOutput(NO_DEVICE_TOKEN);
        }
        if($pid = $this->input['person_id'])
        {
            $sql = 'SELECT id,process,message_id FROM '.DB_PREFIX.'record_person WHERE id = '.$pid ;
            $backinfo = $this->db->query_first($sql);
            if($backinfo['id'])
            {
                $data['back'] = 1;
                if($this->settings['App_im'])
                {
                    $data['message'] = $this->fetch_message($backinfo['message_id'],$id);
                }
            }
            else
            {
                $data['back'] = 0;
            }
        }elseif($device_token && !$this->user['user_id']) //用户未登录，但是有设备号
		{			
			$sql = 'SELECT id,process,message_id FROM '.DB_PREFIX.'record_person WHERE feedback_id = '.$id .' and device_token = "'.md5($device_token) .'"';
			$backinfo = $this->db->query_first($sql);
			if($backinfo['id'])
			{
				$data['back'] = 1;
				if($this->settings['App_im'])
				{
					$data['message'] = $this->fetch_message($backinfo['message_id'],$id);
				}
			}
			else 
			{
				$data['back'] = 0;
			}
		}
		elseif($this->user['user_id']) //登录用户
		{
			$sql = 'SELECT id,process,message_id FROM '.DB_PREFIX.'record_person WHERE feedback_id = '.$id .' and user_id = "'.$this->user['user_id'] .'"';
			$backinfo = $this->db->query_first($sql);
			if($backinfo['id'])
			{
				$data['back'] = 1;
				if($this->settings['App_im'])
				{
					$data['message'] = $this->fetch_message($backinfo['message_id'],$id);
				}
			}
			else 
			{
				$data['back'] = 0;
				if($this->user['user_id'])
				{
					$ret = $this->fetch_member_info();
					$data['filed'] = $ret['filed'];
					$data['address'] = $ret['address'];
				}
			}
		}
		else
		{
			$data['back'] = 0;
		}
		if($backinfo && $backinfo['process'] )
		{
			//$this->errorOutput(FB_PROCESS);
		}
		if($data['back'] == 0 && $feedback['star_time'] && $feedback['star_time'] > TIMENOW)
		{
			$this->errorOutput(FB_NO_START);
		}
		if($data['back'] == 0 && $feedback['end_time'] && $feedback['end_time'] < TIMENOW)
		{
			$this->errorOutput(FB_END);
		}
		if($data['back'] == 1)
		{
			$data['pid'] = $backinfo['id'];
			$data['html'] = $this->fetch_result($backinfo, $feedback);
		}
		$this->addItem($data);
		$this->output();
	}
	private function fetch_result($info,$feedback)
	{
		$body_html = '';
		$is_edit = intval($this->input['is_edit']);
		$feedback['process'] = $info['process'];
		if($feedback['id'] && !$is_edit)
		{
			$sql = 'SELECT id FROM '.DB_PREFIX.'fixed WHERE fid = '.$feedback['id'] .' and fixed_id = 4';//查找地址组件
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$addid[] = $r['id'];
			}
		}
		$sql = 'SELECT id,form_id,type,form_name ,value,order_id FROM '.DB_PREFIX.'record WHERE person_id = '.$info['id'];
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($is_edit)
			{
				$ret[$r['type'].'_'.$r['form_id']] = array(
					'id'		=> $r['id'],
					'value'		=> $r['value'],
				);
			}else 
			{
				if($r['type'] == 'file' && $r['value'])
				{
					$values = $r['value'];
					$material_id[] = $r['value'];
					$r['value'] = array();
					$r['value'] = explode(',',$values);
					$file[$r['form_id']] = $r;
				}
				else
				{
					/*******去除地址中的逗号*******/
					if($addid && in_array($r['form_id'],$addid) && $r['type'] == 'fixed')
					{
						$r['value'] = str_replace(',','',$r['value']);
					}
					$data[$r['id']] = $r;
				}
			}
		}
		if($material_id && !$is_edit)
		{
			$allmaterial = implode(',',$material_id);
			$matids = str_replace('_',',',$allmaterial);
			$matids = trim($matids,',');
			$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE id IN ( '.$matids.' ) ';
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$material[$r['id']] = $r;
			}
			foreach ($file as $k=>$v)
			{
				$value = array();
				foreach ($v['value'] as $mat_id)
				{
					$mat = explode('_',$mat_id);
					if($material[$mat[1]])
					{
						$material[$mat[1]]['indexpic'] = $material[$mat[0]];
						$value[] = $material[$mat[1]];
					}else 
					{
						$value[] = $material[$mat[0]];
					}
				}
				$v['value'] = $value;
				$data[$v['id']] = $v;
			}
		}
		if(!$is_edit)
		{
			return $this->fetch_resultpage($feedback, $data);
		}
		else 
		{
			return $this->fetch_editpage($feedback, $ret);
		}
	}
	
	private function fetch_resultpage($feedback,$data)
	{
		$body_html .= '<p class="title">'.$feedback['title'].'</p>';
		$body_html .= '<p class="info">'.$feedback['brief'].'</p>';
		$body_html .= '<span class="line"></span>';
		if($feedback['process'] == 2)
		{
			$body_html .='<div class="button failed">审核未通过</div>';
		}
		elseif($feedback['process'] == 1)
		{
			$body_html .= '<div class="button success-audit">审核已通过</div>';
		}
		else 
		{
			$body_html .= '<div class="button success-audit">审核中，请耐心等待</div>';
		}
		$body_html .= '<div class="my-info">';
		$body_html .= '<span class="info-title">我的报名信息</span>';
		$body_html .= '<div class="info-list">';
		$body_html .= '<ul>';
		foreach ($data as $k=>$v)
		{
			$order[$v['id']] = $v['order_id'];
		}
		asort($order);
		foreach ($order as $k=>$v)
		{
			$ret[] = $data[$k];
			$body_html .= '<li>';
			$body_html .= '<span class="title color">'.$data[$k]['form_name'].'</span>';
			if($data[$k]['type'] !== 'file')
			{
				$data[$k]['value'] = $data[$k]['value'] ? $data[$k]['value'] : '未填写';
				$body_html .= '<span class="title">'.$data[$k]['value'].'</span>';
			}
			else
			{
				foreach ($data[$k]['value'] as $img)
				{
					$body_html .= '<img src="'.hg_material_link($img['host'], $img['dir'], $img['material_path'], $img['pic_name'],'480x0/').'"/>';
				}
			}
			$body_html .= '</li>';
		}
		$body_html .= '</ul>';
		$body_html .= '</div>';
		$body_html .= '</div>';
		return $body_html;
	}
	
	private function fetch_editpage($fb,$ret)
	{
		$html = '';
		$forms = $this->mode->get_forms($fb['id'],SORT_ASC);
		foreach ($forms as $k=>$v)
		{
			if($v['mode_type'] == 'file')
			{
				$v['default'] = $ret['file_'.$v['id']]['value'];
			}else 
			{
				$v['default'] = $ret[$v['type'].'_'.$v['id']]['value'];
			}
			if($v['type'] == 'standard')
			{
				$html .= $this->mode->formtypes($v);	
				$html .= "\n";
			}
			elseif($v['type'] == 'fixed')
			{
				switch ($v['form_type'])
				{
					case 1:case 2:case 3:case 5:
						$v['form_type'] = $v['standard_type'];
						$html .= $this->mode->formtypes($v);
						$html .= "\r\n";
						break;
					case 4:case 6:
						$html .= '<p class="title">'.$v['name'].'</p>'."\r\n";
						$html .= '<div class="item file-item">';
						$split  = $v['form_type'] == 4 ? ',' : ':';
						$default = explode($split,$v['default']);
        				if(is_array($v['element']))
						{
							foreach ($v['element'] as $kle=>$ele)
        					{
        						$ele['ele_id'] = $ele['id'];
        						$ele['id'] = $v['id'];
        						$ele['options'] = $ele['value'] ? $ele['value'] : array();
        						$ele['name'] = '';
      							$ele['tips'] = '请填写详细地址';
      							$ele['type'] = $v['type'];
      							$ele['default'] = $default[$kle];
          						$html .= $this->mode->formtypes($ele);
        					}
						}
						$html .= '</div>'."\r\n";
        				break;
					default:break;
				}
			}	
		}
		return $html;
	}
	
	/*************取对话消息************/
	private function fetch_message($msg_id ,$feedback_id, $no_btn = 0 )
	{
		if($msg_id)
		{
			$this->curl = new curl($this->settings['App_im']['host'],$this->settings['App_im']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('session_id', $msg_id);
			$this->curl->addRequestData('sort_type', 'ASC');
			$this->curl->addRequestData('a', 'session_detail');
			$message = $this->curl->request('message.php');
			$message = $message[0];
			$msg = $message['messages'][$msg_id];
			$userinfo = $message['users'];
			if($feedback_id)
			{
				$sql = 'SELECT admin_reply_count FROM '.DB_PREFIX.'record_person WHERE feedback_id = '.$feedback_id .' AND user_id = '.$this->user['user_id'] ;
				$back = $this->db->query_first($sql);
				$reply = $back['admin_reply_count'];
				$sql = 'UPDATE '.DB_PREFIX.'record_person SET admin_reply_count = 0 WHERE feedback_id = '.$feedback_id .' AND user_id = '.$this->user['user_id'] ;
				$this->db->query($sql);
				if($message['session_info']['id'] && $reply)
				{
					/*************会员查看过消息之后，管理员的回复数量加新消息数************/
					if($this->settings['App_members'])
					{
						require_once ROOT_PATH . 'lib/class/members.class.php';
						$members = new members();
						$data = array(
							'member_id'	=> $this->user['user_id'],
							'mark'		=> 'apply',
							'math'		=> 2,
							'total'		=> $reply,
						);
						$ret = $members->updateMyData($data);
					}
					/*************会员查看过消息之后，管理员的回复数量加新消息数************/
				}
			}
			if($userinfo && is_array($userinfo))
			{
				foreach ($userinfo as $k=>$v)
				{
					$user[$v['uid']] = $v['utype'];
				}
			}
			if($msg && is_array($msg))
			{
				$body_html = '';
				$body_html .= '<ul class="talk-list">';
				foreach ($msg as $k=>$v)
				{
					if($user[$v['send_uid']] == 'admin')
					{
						$body_html .= '<li class="feedback-flex">';
						$body_html .= '<img class="service-provider-avatar" src="'.hg_material_link($v['send_uavatar']['host'], $v['send_uavatar']['dir'], $v['send_uavatar']['filepath'], $v['send_uavatar']['filename'],'48x48/').'" />';
						$body_html .= '<div class="feedback-flex-one">';
						$body_html .= '<span class="msg service-provider">'.$v['message'].'</span>';
						$body_html .= '</div>';
						$body_html .= '</li>';
					}
					else 
					{
						$body_html .= '<li>';
						$body_html .= '<span class="msg user">'.$v['message'].'</span>';
						$body_html .= '</li>';
					}
				}
				$body_html .= '</ul>';
			}
		}
		if($msg_id && !$no_btn)
		{
			$body_html .= '<div class="replay feedback-flex">';
			$body_html .= '<input class="feedback-flex-one" type="text" name="replay" placeholder="在此输入回复内容">';
			$body_html .= '<span class="btn-default replay-btn">发送</span>';
			$body_html .= '</div>';
		}
		return $body_html;
	}
	
	public function send_message()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
		$sql = 'SELECT id,message_id FROM '.DB_PREFIX.'record_person WHERE feedback_id = '.$id .' and user_id = "'.$this->user['user_id'] .'" ORDER BY create_time DESC';
		$backinfo = $this->db->query_first($sql);
		$msg_id = $backinfo['message_id'];
		$rp_id = $backinfo['id'];
		if($this->settings['App_im'] && $msg_id)
		{
			$this->curl = new curl($this->settings['App_im']['host'],$this->settings['App_im']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('session_id', $msg_id);
			$this->curl->addRequestData('message', trim($this->input['message']));
			$this->curl->addRequestData('user_device_token', trim($this->input['device_token']));
			$this->curl->addRequestData('user_appid',$this->user['appid']);
			$this->curl->addRequestData('a', 'reply_session');
			$message = $this->curl->request('message.php');
			$message = $message[0];
			if($message)
			{
				$sql = 'UPDATE '.DB_PREFIX.'record_person SET new_reply = 1 WHERE id = '.$rp_id ;
				$this->db->query($sql);
			}
		}
		if(!$message)
		{
			$this->errorOutput(ERROR_SEND);
		}
		$this->addItem($message);
		$this->output();
	}
	
	private function fetch_member_info()
	{
		
		/***从会员接口取会员相关信息和拓展信息****/
		if($this->settings['App_members'] && $this->user['user_id'])
		{
			$members = new members();
			$info = $members->get_members(0,'detail',$this->user['token']);
			$info = $info[0];
			if($info['extension'] && is_array($info['extension']))
			{
				foreach ($info['extension'] as $v)
				{
					$extension[$v['field']] = $v['value'];
				}
			}
			if($info)
			{
				$forms  = $this->mode->get_forms($id);
				if($forms && is_array($forms))
				{
					foreach ($forms as $k=>$v)
					{
						if($v['fixed_id'] == 4 && $v['member_field_addr'] && is_array($v['member_field_addr']))
						{
							foreach ($v['member_field_addr'] as $ele => $elev)
							{
								if($extension[$elev])
								{
									if($ele == -1) 
									{
										$address = $extension[$elev];
										$address_info = explode(',',$address);
										$address_info[0] ? $prov_name = $data['field']['form['.$v['type'].'_'.$v['id'].'][8]'] = $address_info[0] :false;
										$address_info[1] ? $city_name = $data['field']['form['.$v['type'].'_'.$v['id'].'][9]'] = $address_info[1] :false;
										$address_info[2] ? $area_name = $data['field']['form['.$v['type'].'_'.$v['id'].'][10]'] = $address_info[2] :false;
										$address_info[3] ? $data['field']['form['.$v['type'].'_'.$v['id'].'][11]'] = $address_info[3] :false;
									}
									if($ele == 8) $prov_name = $extension[$elev];
									if($ele == 9) $city_name = $extension[$elev];
									if($ele == 10) $area_name = $extension[$elev];
									$data['field']['form['.$v['type'].'_'.$v['id'].']['.$ele.']'] = $extension[$elev];
								}
							}
							if($prov_name)
							{
								$sql = 'SELECT c.id as city_id,c.city FROM '.DB_PREFIX.'province p LEFT JOIN '.DB_PREFIX.'city c ON c.province_id = p.id WHERE p.name = "'.$prov_name.'"';
								$q = $this->db->query($sql);
								while ($r = $this->db->fetch_array($q))
								{
									$data['address']['city'][$r['city_id']] = $r['city'];
								}
							}
							if($city_name)
							{
								$sql = 'SELECT a.id as area_id,a.area FROM '.DB_PREFIX.'city c LEFT JOIN '.DB_PREFIX.'area a ON c.id = a.city_id  WHERE c.city = "'.$city_name.'"';
								$q = $this->db->query($sql);
								while ($r = $this->db->fetch_array($q))
								{
									$data['address']['area'][$r['area_id']] = $r['area'];
								}
							}
						}
						if($v['member_field'] == 'mobile' || $v['member_field'] == 'email' )
						{
							if($info[$v['member_field']])
							{
								$data['field']['form['.$v['type'].'_'.$v['id'].']'] = $info[$v['member_field']];
							}
						}
						elseif($v['member_field'] && $extension[$v['member_field']])
						{
								$data['field']['form['.$v['type'].'_'.$v['id'].']'] = $extension[$v['member_field']];
						}
					}
				}
			}
		}
		/***从会员接口取会员相关信息和拓展信息****/		
		return $data;		
	}
	

	/**
	 * 记录用户的浏览记录
	 * @param param uuid,sid,start_time,user_agent,refer,ip,device_token,year,month,day		
	 */
	public function setclick()
	{
		$data = $this->input['param'];
		$ret = $this->mode->create($data,'clicks',0);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 更新浏览用户的离开时间，以及停留时间
	 * @param param all_time,end_time
	 */
	public function updateclick()
	{
		$data = $this->input['param'];
		$ret = $this->mode->update($this->input['uuid'],'clicks',$data,'uuid');
		$this->addItem($ret);
		$this->output();
	}
}
$ouput= new feedbackUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>