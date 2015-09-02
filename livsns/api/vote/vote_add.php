<?php
/***************************************************************************
* $Id: vote_add.php 44560 2015-03-11 06:45:19Z jiyuting $
***************************************************************************/
define('MOD_UNIQUEID', 'vote');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class voteAddApi extends appCommonFrm
{
	private $mVote;
	private $mVerifyCode;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/vote.class.php';
		$this->mVote = new vote();
		
		require_once ROOT_PATH . 'lib/class/verifycode.class.php';
		$this->mVerifyCode = new verifycode();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 投票接口
	 * @param $id int 投票ID
	 * @param $option_id string 选项id (1,2,3)
	 * @param $verify_code string 验证码
	 * @param $other_title string 用户提交过来的其他选项
	 * 
	 */
	public function vote_add()
	{
		$id 		 = intval($this->input['id']);
		$option_id 	 = trim($this->input['option_id']);
		$verify_code = trim($this->input['verify_code']);
		$other_title = trim($this->input['other_title']);
		
		$ip		 = hg_getip();
		$appid 	 = intval($this->user['appid']);
		$appname = $this->user['display_name'];
		$user_id = intval($this->user['user_id']);
		$verifycode = trim($this->input['verify_code']);
		$session_id = trim($this->input['session_id']);
		$device_token = trim($this->input['device_token']);
		$uuid = $this->input['uuid'];
		
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		if (!$option_id)
		{
			$this->errorOutput('NO_OPTION_ID');
		}
		
		//取投票数据
		$vote = $this->mVote->get_vote_by_id($id);
		$vote = $vote[0];
		
		if (empty($vote))
		{
			$this->errorOutput('该投票不存在或删除');
		}
		
		//审核状态
		if (!$vote['status'])
		{
			$this->errorOutput('该投票未审核');
		}
		
		//开启状态
		if (!$vote['is_open'])
		{
			$this->errorOutput('该投票已关闭');
		}
		
		//有效期验证
		if ($vote['end_time'] &&  strtotime($vote['end_time']) < TIMENOW)
		{
			$this->errorOutput('该投票已过期');
		}
		//有效期验证
		if ($vote['start_time'] &&  strtotime($vote['start_time']) > TIMENOW)
		{
			$this->errorOutput('该投票未开始');
		}
		//选项数目
		$option_ids = explode(',', $option_id);
		$option_count = count(array_filter($option_ids));
		
		//单选
		if ($vote['option_type'] == 1 && $option_count != 1)
		{
			$this->errorOutput('只能选择一个选项');
		}
		
		//多选
		if ($vote['option_type'] == 2)
		{
			if ($option_count > $vote['max_option'] && $vote['max_option'])
			{
				$this->errorOutput('投票选项已超过' . $vote['max_option'] . '个');
			}
			
			if ($option_count < $vote['min_option'])
			{
				$this->errorOutput('投票选项不能少于' . $vote['min_option'] . '个');
			}
		}
		
		//验证码
		if ($this->settings['App_verifycode'] && $vote['is_verify_code'])
		{
			$is_dipartite = $vote['is_verify_dipartite'] ? $vote['is_verify_dipartite'] : 0 ; //验证码是否区分大小写
			$check_result = $this->mVerifyCode->check_verify_code($verifycode, $session_id, $is_dipartite);  //验证验证码
			if( $check_result != 'SUCCESS')
			{
				$this->errorOutput($check_result);
			}
		}
		
		//用户登陆
		if ($vote['is_user_login'] && $user_id <= 0)
		{
			$this->errorOutput('会员未登录');
		}
		
		if(defined("RESERVED_IP_LIMIT") && RESERVED_IP_LIMIT)
		{
			if(is_reserverd_ip($ip))
			{
				$this->errorOutput(RESERVED_IP_NOT_ALLOWED);
			}
		}
		
		//同一用户投票时间限制
		if ($vote['is_userid'])
		{
			$user_toff = $vote['userid_limit_time'] * 3600;
			$user_time = TIMENOW - $user_toff;
			
			$sql = "SELECT  count(vote_question_id) as total  FROM " . DB_PREFIX . "question_person ";
			$sql.= " WHERE vote_question_id = " . $id . " AND user_id = " . $user_id;
			if($vote['userid_limit_time'])
			{
				$sql .= " AND create_time > " . $user_time ;
			}
			$user_vote = $this->db->query_first($sql);
			if($user_vote['total'] >= $vote['userid_limit_num'])
			{
				$error5 =  '同一用户在' .$vote['userid_limit_time'] . '小时内最多投'.$vote['userid_limit_num'].'票！';
				$error6 =  '同一用户最多投'.$vote['userid_limit_num'].'票！';
				$data['error'] = $vote['userid_limit_time'] ? $error5 : $error6;				
				$this->errorOutput($data['error']);
			}
		}
		
		if(!$device_token && $vote['is_device'] && defined('NO_DEVICE_VOTE') && NO_DEVICE_VOTE)
		{
			$error = defined('NO_DEVICE_TIPS') && NO_DEVICE_TIPS  ? NO_DEVICE_TIPS : '您的客户端版本太低，请先升级';
			$this->errorOutput($error);
		}
		
		if($device_token && $vote['is_device'])
		{
			if($this->settings['App_mobile'])
			{
				require_once ROOT_DIR . 'lib/class/curl.class.php';
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
			$device_user_toff = $vote['device_limit_time'] * 3600;
			$device_user_time = TIMENOW - $device_user_toff;
				
			$sql = "SELECT count(vote_question_id) as total FROM " . DB_PREFIX . "question_person WHERE 1 ";
			$sql.= " AND  vote_question_id = " . $id . " AND device_token = '" . md5($device_token)."'";
			if($vote['device_limit_time'])
			{
				$sql .= " AND create_time > " . $device_user_time ;
			}
			$device_vote = $this->db->query_first($sql);
			if($device_vote['total'] >= $vote['device_limit_num'])
			{
				$error1 =  '同一设备在' .$vote['device_limit_time'] . '小时内最多投'.$vote['device_limit_num'].'票！';
				$error2 =  '同一设备最多投'.$vote['device_limit_num'].'票！';
				$data['error'] = $vote['device_limit_time'] ? $error1 : $error2;				
				$this->errorOutput($data['error']);
			}
		}
		
		//ip投票时间限制
		if ($vote['is_ip'] && (!$device_token || !$vote['is_device']))
		{
			$ip_toff = $vote['ip_limit_time'] * 3600;
			$ip_time = TIMENOW - $ip_toff;
				
			$sql = "SELECT count(vote_question_id) as iptotal FROM " . DB_PREFIX . "question_person WHERE 1 ";
			$sql.= " AND vote_question_id = " . $id . " AND ip = '" . $ip . "'";
			if($vote['ip_limit_time'])
			{
				$sql .= " AND create_time > " . $ip_time ;
			}
			$ip_question_preson = $this->db->query_first($sql);
			if ($ip_question_preson['iptotal'] >= $vote['ip_limit_num'])
			{
				$error3 =  '同一IP在' . $vote['ip_limit_time'] . '小时内最多投'.$vote['ip_limit_num'].'票！';
				$error4 =  '同一IP多投'.$vote['ip_limit_num'].'票！';
				$data['error'] = $vote['ip_limit_time'] ? $error3 : $error4;
				$this->errorOutput($data['error']);
			}
		}
		
		//记录数据
		foreach ($option_ids AS $k => $_option_id)
		{
			//更新选项 投票数
			$sql = "UPDATE " . DB_PREFIX . "question_option SET single_total=(single_total+1) WHERE id = " . $_option_id;
			$this->db->query($sql);
			
			//记录选项 投票
			$question_record_data = array(
				'question_option_id' => $_option_id,
				'vote_question_id' 	 => $id,
				'ip' 	 			 => $ip,
				'num' 	 			 => 1,
				'start_time' 	 	 => TIMENOW,
				'appid' 	 	 	 => $appid,
				'appname' 	 	 	 => $appname,
			);
			
			$this->mVote->create_data('question_record', $question_record_data);
			
		}
			//更新主表 total 字段
			$sql = "UPDATE " . DB_PREFIX . "vote_question SET total = (total+" . $option_count . ") WHERE id = " . $id;
			$this->db->query($sql);
	
		//记录其他 投票
		if (isset($this->input['other_title']) && $other_title)
		{
			$question_option_data = array(
				'vote_question_id'	=> $id,
				'other_option'		=> $other_title,
				'user_id'			=> $user_id,
				'create_time'		=> TIMENOW,
			);
			$ret_other_data  = $this->mVote->create_data('question_other_option', $question_option_data, true);
			$other_option_id = $ret_other_data['id'];
			
			if ($other_option_id)
			{
				//记录选项 投票
				$question_record_data = array(
					'question_option_id' => $other_option_id,
					'vote_question_id' 	 => $id,
					'ip' 	 			 => $ip,
					'num' 	 			 => 1,
					'start_time' 	 	 => TIMENOW,
					'appid' 	 	 	 => $appid,
					'appname' 	 	 	 => $appname,
				);
				
				$this->mVote->create_data('question_record', $question_record_data);
				
				//更新主表 total 字段
				$sql = "UPDATE " . DB_PREFIX . "vote_question SET total = (total+1) WHERE id = " . $id;
				$this->db->query($sql);
			}
		}
		//记录参与人数 所投选项
		$question_person_info_data = array(
			'vote_question_id'	=> $id,
			'user_id'			=> $user_id,
			'option_ids'		=> $option_id,
		);
		
		$question_person_info = $this->mVote->create_data('question_person_info', $question_person_info_data,true);
		$pid = $question_person_info['id'];
		$user_name = $this->user['user_name'];
		//记录参与人数
		$question_person_data = array(
			'vote_question_id'	=> $id,
			'user_id'			=> $user_id,
		    'user_name'         => $user_name,
			'app_id'			=> $appid,
			'app_name'          => $appname,
			'create_time'		=> TIMENOW,
			'ip'				=> $ip,
			'pid'               => $pid,
		    'device_token'      => md5($device_token),
			'uuid'      		=> $uuid,
			'agent'				=> $_SERVER['HTTP_USER_AGENT'],
			'referer'			=> $_SERVER['HTTP_REFERER']
		);
		
		$this->mVote->create_data('question_person', $question_person_data);
		
		
		//统计参与人数
		$sql = "SELECT vote_question_id FROM " . DB_PREFIX . "question_count WHERE vote_question_id=" . $id . " AND app_id = " . $appid;
		$question_count = $this->db->query_first($sql);
		if (empty($question_count))
		{
			$question_count_data = array(
				'vote_question_id'	=> $id,
				'app_id'			=> $appid,
				'app_name'			=> $appname,
				'counts'			=> 1,
			);
			
			$this->mVote->create_data('question_count', $question_count_data);
		}
		else
		{
			$sql = "UPDATE " . DB_PREFIX . "question_count SET counts=(counts+1) WHERE vote_question_id=" . $id . " AND app_id = " . $appid;
			$this->db->query($sql);
		}
		/***********************调用积分规则,给已审核评论增加积分START*****************/
		$data = $vote;
		if($this->settings['App_members'] && $this->user['user_id']&&$this->input['iscreditsrule'])
		{
			$sql = "SELECT count(vote_question_id) as vote_num FROM " . DB_PREFIX . "question_person WHERE 1 ";
			$sql.= " AND vote_question_id = " . $id . " AND user_id = '" . $this->user['user_id'] . "'";
			$vote_num = $this->db->query_first($sql);
			if($vote_num['vote_num'] < CREDIT_NUM+1)
			{
				include (ROOT_PATH.'lib/class/members.class.php');
				$Members = new members();
				$Members->Setoperation(APP_UNIQUEID,MOD_UNIQUEID,'vote_add');
				/***增加积分**/
				if((IS_CREDITS)&&$this->user['user_id'])
				{
					$credit_rules = $Members->get_credit_rules($this->user['user_id'],APP_UNIQUEID,MOD_UNIQUEID,0,$id);
				}
				/**积分文案处理**/
				$credit_copy=array();
				if($credit_rules['updatecredit'])
				{
					$credit_copy[]=$credit_rules;
				}		
				$data['copywriting_credit'] = $Members->copywriting_credit($credit_copy);
			}
		/**积分文案处理结束**/
		}
		/***********************调用积分规则,给已审核评论增加积分END*****************/
		if(!trim($this->input['app_version']))
		{
			$this->addItem('success');
		}
		else 
		{
			$data['vote_status']  = 'success';
			$this->addItem($data);
		}
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
}

$out = new voteAddApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>