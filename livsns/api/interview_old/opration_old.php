<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/interview_old.class.php';
define('MOD_UNIQUEID','opration_old');//模块标识
class opration_old extends outerUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->int = new interviewInfo_old();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function pre_ask()
	{
		//参数接收
		$data =array(
			'interview_id'=>intval($this->input['interview_id']),		
			'question'=>addslashes(htmlspecialchars($this->input['question'])),
			'user_id'=>intval($this->user['user_id']),
			'user_name'=>trim($this->user['user_name']),
			'ip'=>trim($this->user['ip']),
			'create_time'=>TIMENOW,
		);
		$data['user_id'] = $data['user_id'] ?$data['user_id'] : 0;
		$data['user_name'] = $data['user_name']?$data['user_name'] :'网友';
		if (!$data['interview_id'])
		{
			$this->errorOutput('访谈ID为空');
		}
		if (!$data['question'])
		{
			$this->errorOutput('提问内容为空');
		}
		$q = 'SELECT is_pre_ask FROM '.DB_PREFIX.'interview WHERE id='.$data['interview_id'];
		$r = $this->db->query_first($q);
		if (!$r['is_pre_ask']){
			$this->errorOutput('预提问已被关闭');
		}else {
			$this->int->pre_ask($data);
			$this->addItem(true);
		}
		$this->output();
	}
	public function speech()
	{
		
		$data = array(
	    	'interview_id'=>$this->input['interview_id'],
			'content'=>addslashes(strip_tags(trim(urldecode($this->input['content'])))),
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'create_time'=>TIMENOW,
			'ip'=>$this->user['ip'],
		);
		$data['content'] = str_replace(array("\r\n","\r","\n"," "),'',$data['content']);
		if (!$data['interview_id'])
		{
			$this->errorOutput(NOID);
		}
		if (!$data['user_id'])
		{
			$data['user_id'] = 0;
			
		}
		if (!$data['user_name'])
		{
			$data['user_name'] = $this->input['nickname'] ? addslashes(trim(urldecode($this->input['nickname']))) : '网友';
		}
		//访谈状态
		$res = $this->int->checkInterview($data['interview_id']);
		
		//权限验证
		$role = $this->int->role($data['user_id'],$data['interview_id']);
		$prms = $this->int->prms($role,$data['interview_id']);
		$prm = $prms[0];
		$return = array();
		if (empty($data['content']))
		{
			$return = array(
				'error'=>2,
				'msg'=>'发言不能为空！',
			);
		}elseif ($this->int->strlen_utf8($data['content']) < 4 || $this->int->strlen_utf8($data['content']) > 200){
			$return = array(
				'error'=>3,
				'msg'=>'发言不能少于4个字符或大于200字符！',
			);
		}elseif ($prm==-1){
			$return = array(
				'error'=>1,
				'msg'=>'对不起，您没有发言的权限',
			);
		}else {
			if (!$res)
			{
				$return = array(
					'error'=>0,
					'msg'=>'访谈还未开始或者已经结束，不能发言',
				);	
			}else {
						
				$data['audit_time'] = TIMENOW;
				$data['reply_time'] = TIMENOW;
				
				//指定嘉宾
				if (isset($this->input['guest_id']))
				{
					$data['guest_id'] = intval(urldecode($this->input['guest_id']));
				}			
				//插入数据库		
				$return = $this->int->speech($data['interview_id'], $data['content'],
				 $data['user_id'], $data['user_name'], $data['create_time'], $data['ip'],
				  $prm,$data['audit_time'],$data['guest_id'],$data['reply_time']);
			}		
			
		}
		$this->addItem($return);
		$this->output();
	}
	/**
	 * 引用
	 * 
	 */
	public function quote()
	{
		//参数接收
		$data = array(
			'id'=>intval($this->input['id']),
		);
		if (!$data['id'])
		{
			$this->errorOutput(NOID);
		}	
		$interviewId = $this->int->getId($data['id']);
		$ret = $this->int->check_int_prms($interviewId);
		$user_id = $this->user['user_id']?$this->user['user_id']:0;
		$role = $this->int->role($user_id, $interviewId);
		if ($ret['prms'][$role][0] ==-1)
		{
			$data = '';	
		}else 
		{
			
			$question = $this->int->getQuestion($data['id']);
			$string = $question['question'];
			$string = str_ireplace('[QUOTE]','',$string);
			$string = str_ireplace('[/QUOTE]','',$string);
			$string = preg_replace("/\[IMG=\S+\]\[\/IMG\]/",'',$string);
			$user_name = $question['user_name'];
			if(in_array($user_name,$ret['moderator']))
			{
				$user_name = '主持人';
			}
			else if(in_array($user_name,$ret['honor_guests']))
			{
				$user_name = '嘉宾' . $question['user_name'];
			}				
			$data =  $user_name . ':' . $string;;
		}
		$this->addItem($data);
		$this->output();
	}
	
	public function create()
	{
		
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		
	}
}
$out = new opration_old();
$action = $_INPUT['a'];
if(!$_INPUT['a'])
{
	$action = 'pre_ask';
}
$out->$action();
?>