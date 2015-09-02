<?php
define('MOD_UNIQUEID','survey');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require_once(CUR_CONF_PATH . 'lib/survey_mode.php');
/*
$_INPUT['appid'] = 55;
$_INPUT['appkey'] = 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7';
$_INPUT['device_token'] = '44297956-6bce-4e61-9cfc-1b5c2037d92e';
*/
class survey_update extends outerUpdateBase
{
	private $mode;
	private $is_redis;
    public function __construct()
	{
		parent::__construct();
		$device_token = $this->input['device_token'];
		$salt = $this->input['salt'];
		if($device_token && IS_ENDEVICE) //设备号加密解密
		{
			if(!$salt || !is_numeric($salt)  || strlen($salt) != 13)
			{
				$this->errorOutput(SALT_ERROR);
			}
			$dt = substr($device_token,0,strlen($device_token) - 8);
			$this->input['device_token'] = substr($dt,0,10).substr($dt,15);
		}
		$this->mode = new survey_mode();
		$this->is_redis = $this->settings['redis']  ? 1 : 0;
		if($this->is_redis)
		{
			$this->redis = new Redis();
			$this->redis->connect($this->settings['redis']['redis2']['host'], $this->settings['redis']['redis2']['port']);
			$this->redis->auth(REDIS_KEY);
		}
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){
		/*
		$checkcode = '76e6fd3100cf834c2e43c5c687976d0a';
		if(!$checkcode)
		{
			$this->errorOutput(SALT_ERROR);
		}
		$code1 = strrev(substr($checkcode,0,16));
		$code2 = strrev(substr($checkcode,16));
		$this->input['checkcode'] = $code2.$code1;
		*/
		session_start();
		$this->input['need_cache'] = 1;
		$this->input['session_id'] = $_SESSION['id'];
		$this->update();
	}
	
	public function update()
	{
		$id = $this->input['id'];//问卷id
		$answer = $this->input['answer'];
		$other = $this->input['other_answer']; //其他选项id及答案
		if(!$answer)
		{
			$this->errorOutput('请填写题目！');
		}
		if(!$id)
		{
			$this->errorOutput('没有问卷ID');
		}
		$ip = hg_getip();
		$device_token = trim($this->input['device_token']);
		$uuid = $this->input['uuid'];
		if($this->is_redis)
		{
			if(!$_COOKIE['vote_sid'])
			{
				$this->errorOutput(SALT_ERROR);
			}
			$correct_code = $this->redis->get('vs_'.md5($device_token).'_'.$id);
			if(!$correct_code || $correct_code != $_COOKIE['vote_sid'])
			{
				$this->errorOutput(SALT_ERROR);
			}
		}
		$survey = $this->mode->get_survey('id='.$id);
		if($survey['status'] == 0 || $survey['status'] == 2 )
		{
			$this->errorOutput('该问卷未通过审核!');
		}
		if ($survey['start_time'] && $survey['start_time'] > TIMENOW)
		{
			$this->errorOutput('该问卷将于'.date('Y-m-d H:i') .'开始');
		}
		if ($survey['end_time'] && $survey['end_time'] < TIMENOW)
		{
			$this->errorOutput('该问卷已过期');
		}
		if ($survey['is_login'] && !$this->user['user_id'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
		if(!$survey['is_auto_submit'] && $this->input['use_time'] > $survey['question_time'])
		{
			$this->errorOutput('超时不能提交！');
		}
		if($survey['is_device'])
		{
			$this->check_device($id, $device_token, $survey['device_limit_time'], $survey['device_limit_num'],$uuid,$survey['device_num_error'],$survey['device_time_error']);
		}
		//ip投票时间限制
		if ($survey['is_ip'] && (!$device_token || !$survey['is_device']))
		{
			$this->check_ip($id, $ip, $survey['ip_limit_time'], $survey['ip_limit_num']);
		}	
		if($this->settings['App_verifycode'] && $survey['is_verifycode'])
		{
			$this->check_verifycode();
		}
		if($this->settings['App_mobile'] && CHECK_DEVICE && $device_token && $survey['is_verifycode'])
		{
			$this->verify_device($device_token,$uuid);
		}
		$_problem = $this->mode->problems($id);//得到问题id跟类型关联的数组
		if(!$_problem)
		{
			$this->errorOutput('该问卷没有问题!');
		}
		foreach ($_problem as $key=>$value)
		{
			$num = 0;
			$input_answer = $other_answer = '';
			$input_answer = is_array($answer[$value['id']]) ? array_unique($answer[$value['id']]) : $answer[$value['id']];
			if($value['is_required'] && !$input_answer)
			{
				$this->errorOutput($value['title'].'是必答题!');
			}
			if($other[$value['id']])
			{
				$other_answer = trim($other[$value['id']]);
			}
			switch ($value['type'])
			{
				case 2://多选
					$option_id = $this->check_checkbox($input_answer,$value['min_option'],$value['max_option'],$value['title']);
					break;
				case 3://填空
					if(is_array($input_answer))
					{
						$other_answer = $this->check_input($input_answer,$value['char_limit']);
					}else 
					{
						$other_answer = $this->check_textarea($input_answer,$value['min_option'],$value['max_option']);
					}
					break;
				case 4://问答
					$other_answer = $this->check_textarea($input_answer,$value['min_option'],$value['max_option'],$value['title']);
					break;
				case 1://单选
					$option_id = $this->check_radio($input_answer);
					break;
				default:break;	
			}
			
			if($input_answer || $other_answer)
			{
				$data[] = array(
					'survey_id'    => $id,
				    'problem_id'   => $value['id'],
				    'option_id'    => $option_id,
				    'answer'       => $other_answer,
				);
				if(!$this->is_redis)
				{
					if($value['id'])
					{
						$problems[] = $value['id'];
					}
					if($option_id)
					{
						$options[] = $option_id;
					}
				}else 
				{
					if($value['type'] < 3)
					{
						if($value['type'] == 2)
						{
							$op = explode(',',$option_id);
							foreach ($op as $v)
							{
								if($v)
								{
									$this->redis->hIncrBy('s_'.$id,'p_'.$value['id'].'_'.$v,1);
								}
							}
						}else 
						{
							$this->redis->hIncrBy('s_'.$id,'p_'.$value['id'].'_'.$option_id,1);
						}
					}
				}
			}
		}
		if($this->is_redis)
		{
			$this->redis->incr('g_'.$id); //投票人数新增
		}
		else 
		{
			if($options)
			{
				$option_ids = implode(',',$options);
			}
			//得到提交的问卷中有效的题目id
			if($problems)
			{
				$problem_ids = implode(',',$problems);
			}
		}
		$user = array(
			'survey_id' 	=> $id,
			'user_id' 		=> $this->user['user_id'],
		    'user_name' 	=> $this->user['user_name'],
			'create_time' 	=> TIMENOW,
			'use_time' 		=> intval($this->input['use_time']),
			'ip'	 		=> $ip,
			'app_id' 		=> $this->user['appid'],
		    'app_name' 		=> $this->user['display_name'],
			'device_token' 		=> md5($device_token),
			'uuid'				=> $uuid,
			'agent'				=> $_SERVER['HTTP_USER_AGENT'],
			'referer'			=> $_SERVER['HTTP_REFERER']
		);
		$dUser = $this->mode->create('record_person',$user,false);
		$person_id = $dUser['id'];
		if($data)
		{
			foreach ($data as $k=>$v)
			{
				$data[$k]['person_id'] = $person_id;
			}
		}
		$this->mode->insert_datas('result', $data);//插入结果表
		if(!$this->is_redis)
		{
			if($option_ids)
			{
				//更新某选项被选中总数
				$o_sql = "UPDATE " .DB_PREFIX. "options SET total = total+1 WHERE id IN (".$option_ids.")";
				$this->db->query($o_sql);
			}
			if($problem_ids)
			{
				//更新某问题被回答总数
				$p_sql = "UPDATE " .DB_PREFIX. "problem SET counts = counts+1 WHERE id IN (".$problem_ids.")";
				$this->db->query($p_sql);
			}
			//更新收到问卷总数
			$s_sql = "UPDATE " .DB_PREFIX. "survey SET submit_num = submit_num+1 ,used_survey_id = used_survey_id+1 WHERE id = ".$id;
			$this->db->query($s_sql);
		}else 
		{
			if($device_token)
			{
				$this->redis->hIncrBy(md5($device_token).'_'.$id,'vote_num',1);
				$this->redis->hset(md5($device_token).'_'.$id,'last_time',TIMENOW);
			}
			if($survey['is_ip'])
			{
				$this->redis->incr($ip.'_'.$id);
			}
		}
		if($this->input['need_cache'])
		{
			if($this->is_redis)
			{
				$ret = $this->getredis($id);
				$this->redis->del('vs_'.md5($device_token).'_'.$id);
			}else 
			{
				$ret = $this->mode->getResult($id);
				@file_put_contents(CACHE_DIR.'r'.$id.'.json', json_encode($ret));
			}
			setcookie('vote_sid','',TIMENOW -1);
			$this->addItem($ret);
			$this->output();
		}
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 记录用户的浏览记录
	 * @param param uuid,sid,start_time,user_agent,refer,ip,device_token,year,month,day		
	 */
	public function setclick()
	{
		$data = $this->input['param'];
		$ret = $this->mode->create('clicks',$data,0);
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
	
	private function check_device($id,$device_token,$device_limit_time,$device_limit_num,$uuid = '',$num_error = '',$time_error = '')
	{
		if($this->is_redis)
		{
			if(!$device_token)
			{
				$error = defined('NO_DEVICE_TIPS') && NO_DEVICE_TIPS  ? NO_DEVICE_TIPS : '您的客户端版本太低，请先升级';
				$this->errorOutput($error);
			}
			$dVote = $this->redis->hgetall(md5($device_token).'_'.$id);
			if(TIMENOW - $dVote['last_time'] <= $device_limit_time * 3600)
	        {
	        	$ret = $this->getredis($id);
	        	$ret['back'] = 2;
	        	$this->addItem($ret);
	        	$this->output();
	        //	$error = $time_error ? $time_error : '您已在'.$device_limit_time.'小时内投过票';
	        //	$this->errorOutput($error);
	        }
			if($dVote['vote_num'] >= $device_limit_num)
			{
				$ret = $this->getredis($id);
	        	$ret['back'] = 1;
	        	$this->addItem($ret);
	        	$this->output();
				//$error = $num_error ? $num_error : '同一设备最多投'.$device_limit_num.'票！';
				//$this->errorOutput($error);
			}
			
		}else 
		{
			if(!$device_token)
			{
				$error = defined('NO_DEVICE_TIPS') && NO_DEVICE_TIPS  ? NO_DEVICE_TIPS : '您的客户端版本太低，请先升级';
				$this->errorOutput($error);
			}
			$device_user_toff = $device_limit_time * 3600;
			$device_user_time = TIMENOW - $device_user_toff;
			$sql = "SELECT count(*) as dtotal FROM " . DB_PREFIX . "record_person WHERE 1 ";
			$sql.= " AND  survey_id = " . $id . " AND device_token = '" . md5($device_token)."'";
			if ($uuid)
			{
				$sql.= " AND uuid = '" . $uuid."'";
			}
			if($device_limit_time)
			{
				$sql .= " AND create_time > " . $device_user_time ;
			}
			$device_vote = $this->db->query_first($sql);
			if($device_vote['dtotal'] >= $device_limit_num)
			{
				$error1 =  '同一设备在' .$device_limit_time . '小时内最多投'.$device_limit_num.'票！';
				$error2 =  '同一设备最多投'.$device_limit_num.'票！';
				$error = $device_limit_time ? $error1 : $error2;				
				$this->errorOutput($error);
			}
		}
	}
	
	private function check_ip($id,$ip,$ip_limit_time,$ip_limit_num)
	{
		if($this->is_redis)
		{
			$ip_vote_num = $this->redis->get($ip.'_'.$id);
			if($ip_vote_num >= $ip_limit_num)
			{
				$this->errorOutput('同一IP最多投'.$ip_limit_num.'票！');
			}
		}
		else
		{
			$ip_toff = $ip_limit_time * 3600;
			$ip_time = TIMENOW - $ip_toff;
			$sql = "SELECT count(*) as iptotal FROM " . DB_PREFIX . "record_person ";
			$sql.= " WHERE survey_id = " . $id . " AND ip = '" . $ip . "'";
			if($ip_limit_time)
			{
				$sql .= " AND create_time > " . $ip_time ;
			}
			$ipc = $this->db->query_first($sql);
			if ($ipc && $ipc['iptotal'] >= $ip_limit_num)
			{
				$error3 =  '同一IP在' . $ip_limit_time . '小时内最多投'.$ip_limit_num.'票！';
				$error4 =  '同一IP最多投'.$ip_limit_num.'票！';
				$error = $ip_limit_time ? $error3 : $error4;
				if($this->input['need_cache'])
				{
					$ret = $this->mode->getResult($id);
					$this->addItem($ret);
					$this->output();
				}
			}
		}
	}
	private function check_verifycode()
	{
		$verifycode = trim($this->input['verify_code']);
		$session_id = trim($this->input['session_id']);
		require_once ROOT_PATH . 'lib/class/verifycode.class.php';
	    $this->mVerifyCode = new verifyCode();
		$check_result = $this->mVerifyCode->check_verify_code($verifycode, $session_id);  //验证验证码
		if( $check_result != 'SUCCESS')
		{
			$this->errorOutput($check_result);
		}
		
	}
	
	private function check_radio($input_answer)
	{
		return $input_answer;
	}
	
	private function check_checkbox($input_answer,$min = '',$max = '',$tite = '')
	{
		if(is_array($input_answer))
		{
			$num = count($input_answer);
			$option_id = implode(',',$input_answer);
		}
		else
		{
			$num = count(explode(',',$input_answer));
			$option_id = $input_answer;
		}
		if($min && $num < $min)
		{
			$this->errorOutput($tite.'最少选'.$min.'项!');
		}
		if($max && $num > $max)
		{
			$this->errorOutput($tite.'最多选'.$max.'项!');
		}
		return $option_id;
	}
	
	private function check_input($input_answer,$char_limit)
	{
		if($char_limit)
		{
			foreach ($char_limit as $key=>$li)
			{
				$num = $this->get_char_num($input_answer[$key]);
				if($li['char_num'] && $num > $li['char_num'])
				{
					$this->errorOutput($li['name'].'最多可填'.$li['char_num'].'个字!');
				}
			}
		}
		return serialize($input_answer);
	}
	
	private function check_textarea($input_answer,$min = '',$max = '',$tite = '')
	{
		$num = $this->get_char_num($input_answer);
		if($min && $num < $min)
		{
			$this->errorOutput($tite.'最少要填'.$min.'个字!');
		}
		if($max && $num > $max)
		{
			$this->errorOutput($tite.'最多可填'.$max.'个字!');
		}
		return addslashes($input_answer);
	}
	
	private function verify_device($device_token = '',$uuid = '')
	{
		require_once ROOT_DIR . 'lib/class/curl.class.php';
		$this->curl = new curl($this->settings['App_mobile']['host'],$this->settings['App_mobile']['dir']);
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('device_token',$device_token);
		$this->curl->addRequestData('uuid', $uuid);
		$ret = $this->curl->request('mobile_device.php');
		if(!$ret[0])
		{
			$this->errorOutput(ERROR_DEVICE_TOKEN);
		}
	}
	
	public function delete(){}
	
	public function audit(){}
	
	public function result_cache()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->getResult($id);
		@file_put_contents(CACHE_DIR.'r'.$id.'.json', json_encode($ret));
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 得到提交上来的问题id,返回数组元素不为空的数组元素键值所组成的数组
	 * Enter description here ...
	 * @param unknown_type $arr
	 */
	public function get_problem_ids($arr = array())
	{
		if(count($arr)<1 || !$arr)
		{
			return false;
		}
		foreach($arr as $k => $v)
		{
			if(!is_array($v))
			{
				$v = trim($v);
				if($v)
				{
					$p[] = $k;
				}
			}
			else
			{
				foreach($v as $key => $val)
				{
					if(trim($val))
					{
						$p[] = $k;
					}
				}
				if(is_array($p))
				{
					$p = array_unique($p);
				}
			}
		}
		return $p;
	}
	
	public function sort(){}
	public function publish(){}
	
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
	
	public function captcha()
	{
		if($this->settings['App_verifycode'])
		{
			session_start();
            $_SESSION['id'] = md5(time().rand(1000000000,9999999999));
            $type = intval($this->input['type']);
            $this->curl = new curl($this->settings['App_verifycode']['host'],$this->settings['App_verifycode']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->mReturnType = 'str';
            $this->curl->initPostData();
            $this->curl->addRequestData('type', $type);
            $this->curl->addRequestData('a', 'set_verify_code');
            $this->curl->addRequestData('session_id', $_SESSION['id']);
            $data = $this->curl->request('verifycode.php');
            header("Content-Type:image/png");
            echo $data;
            exit();
		}
		
	}
	
	public function get_result_cache()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if($this->is_redis)
		{
			$ret = $this->getredis($id);
		}else 
		{
			if(file_exists($file))
			{
				$ret = json_decode(@file_get_contents($file),1);
			}else 
			{
				$ret = $this->mode->getResult($id);
				@file_put_contents($file, json_encode($ret));
			}
		}
		$this->addItem($ret);
		$this->output();
		
	}
	
	private function getredis($id)
	{
		$init = $this->redis->hgetall('inis_'.$id);
		$more = $this->redis->hgetall('s_'.$id);
		if($init)
		{
			foreach ($init as $kk=>$vv)
			{
				$data[$kk] = $vv + intval($more[$kk]);
			}
		}
		$total = $this->redis->get('g_'.$id) + $this->redis->get('inig_'.$id); 
		$ret['data'] = $data;
		$ret['total'] = $total;
		return $ret;
	}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new survey_update();
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