<?php 
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','submit');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH.'lib/functions.php');
require(ROOT_DIR.'lib/class/curl.class.php');
require(ROOT_DIR.'lib/class/members.class.php');
$_INPUT['appid'] = APPID;
$_INPUT['appkey'] = APPKEY;
class submit extends outerUpdateBase
{
	private $device_token;
	function __construct()
	{
		parent::__construct();
		$this->device_token = trim($this->input['device_token']);
		$this->members = new members();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		session_start();
		$id = intval($this->input['id']);
		$form = $this->input['form'];
		$this->curl = new curl($this->settings['App_feedback']['host'],$this->settings['App_feedback']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('device_token', $this->device_token);
		$this->curl->addRequestData('column_id', intval($this->input['column_id']));
		$this->curl->addRequestData('verify_code', trim($this->input['verify_code']));
		$this->curl->addRequestData('session_id', $_SESSION['id']);
		$this->curl->addRequestData('pid', intval($this->input['pid']));
		$this->curl->addRequestData('a', trim($this->input['func']));
		if (is_array($form))
		{
			$this->array_to_add('form',$form);
		}
		else
		{
			$this->curl->addRequestData('form', $form);
		}
		
		if($_FILES)
		{
			$this->curl->addFile($_FILES);	
		}
		$data = $this->curl->request('feedback_update.php');
		if($this->input['return'])
		{
			echo $data;
		}
		else 
		{
			echo "<script>
					var msg = ".$data." ;
					if( msg.ErrorCode || msg.ErrorText){
						if(msg.ErrorCode == 'NO_ACCESS_TOKEN')
						{
							parent.hgClient.goLogin();
						}
						msg = msg.ErrorText ? msg.ErrorText : msg.ErrorCode;
					}else{
						msg = '提交成功！';
					}
					parent.showTip(msg);
					
				</script>";
		}
		exit();
	}
	
	public function update(){}
	
	public function delete(){}
	
	public function check_feedback()
	{
		$id = intval($this->input['id']);
		$form = $this->input['form'];
		$this->curl = new curl($this->settings['App_feedback']['host'],$this->settings['App_feedback']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('device_token', $this->device_token);
		$this->curl->addRequestData('a', 'check_feedback');
		$this->curl->addRequestData('is_edit', intval($this->input['is_edit']));
        $this->curl->addRequestData('is_result_page', intval($this->input['is_result_page']));
        $this->curl->addRequestData('person_id', intval($this->input['pid']));
		$data = $this->curl->request('feedback_update.php');
		echo $data;
		exit();
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
		$this->curl = new curl($this->settings['App_feedback']['host'],$this->settings['App_feedback']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('message', trim($this->input['message']));
		$this->curl->addRequestData('a', 'send_message');
		$data = $this->curl->request('feedback_update.php');
		echo $data;
		exit();
	}
	
	public function array_to_add($str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
	}	
	
	public function get_verifycode()
	{
		if($this->settings['App_verifycode'])
		{
			$type = intval($this->input['verifycode_type']);
			$this->curl = new curl($this->settings['App_verifycode']['host'],$this->settings['App_verifycode']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->mReturnType = 'str';
			$this->curl->initPostData();
			$this->curl->addRequestData('type', $type);
			$this->curl->addRequestData('a', 'detail');
			$data = $this->curl->request('verify.php');
			$ret = json_decode($data,1);
			session_start();
            $_SESSION['id'] = $ret[0]['session_id'];
			echo $data;
			exit();
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
            $data = $this->curl->request('verifycode.php');
            header("Content-Type:image/png");
            echo $data;
            exit();
        }

    }
	
	public function create_greet()
	{
		$id = intval($this->input['id']);
		$form = $this->input['form'];
		$this->curl = new curl($this->settings['App_feedback']['host'],$this->settings['App_feedback']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('device_token', $this->device_token);
		$this->curl->addRequestData('repeat_feed', intval($this->input['repeat_feed']));
		$this->curl->addRequestData('a', 'create_greeting_cards');
		if (is_array($form))
		{
			$this->array_to_add('form',$form);
		}
		else
		{
			$this->curl->addRequestData('form', $form);
		}
		
		if($_FILES)
		{
			$this->curl->addFile($_FILES);	
		}
		$data = $this->curl->request('feedback_update.php');
		echo $data;
		exit();
	}	
	
	
	public function filtinfo()
	{
		$id = intval($this->input['id']);
		$this->curl = new curl($this->settings['App_feedback']['host'],$this->settings['App_feedback']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('device_token', $this->device_token);
		$this->curl->addRequestData('a', 'filtinfo');
		$data = $this->curl->request('feedback.php');
		echo $data;
		exit();
	}
	
	public function check_members()
	{
		$identifier = trim($this->input['system']);
		$user_name = trim($this->input['user_name']);
		$user_id = intval($this->input['user_id']);
		if(!$identifier || !$user_id || !$user_name)
		{
			$this->errorOutput('登陆失败，请重新登陆');
		}
		$this->connect_mb();
		$sql = 'SELECT * FROM '.DB_PREFIX.'member_bind WHERE identifier = "'.$identifier.'" AND member_id = '.$user_id;
		$query = $this->newdb->query_first($sql);
		if($query['new_member_id'])  //该用户已注册
		{
			$ret = $this->do_login();
			$ret = json_decode($ret,1);
			if($ret['ErrorCode'])
			{
				$error = $ret['ErrorText'] ? $ret['ErrorText'] : $ret['ErrorCode'];
				$this->errorOutput('绑定失败-'.$error);
			}
		}else{
			$ret = $this->regist_members();
			$ret = json_decode($ret,1);
			if($ret['ErrorCode'])
			{
				$error = $ret['ErrorText'] ? $ret['ErrorText'] : $ret['ErrorCode'];
				$this->errorOutput('绑定失败-'.$error);
			}
			if($ret[0]['member_id'])
			{
				$sql = 'INSERT INTO '.DB_PREFIX.'member_bind SET
				 identifier= "'.$identifier.'",
				 new_member_id = '.$ret[0]['member_id'].',
				 name = "'.$ret[0]['member_name'].'",
				 member_id = '.$user_id .',
				 create_time = '.TIMENOW;
				$this->newdb->query($sql);
			}
		}
		if($ret[0]['access_token'])
		{
			setcookie('token',$ret[0]['access_token'],TIMENOW+3600*24*30);
		}
		$this->newdb->close();
		$this->addItem($ret[0]);
		$this->output();
	}

	private  function regist_members()
	{
		$identifier_id = $this->get_identifier_id();
		$name = trim($this->input['user_name']);
		$tel = trim($this->input['tel']);
		$name = ($name != $tel) ? $name : 'yun'.$name;
		$this->curl = new curl($this->settings['App_members']['host'],$this->settings['App_members']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->addRequestData('member_name',$name);
		$this->curl->addRequestData('password', PASSWORD);
		$this->curl->addRequestData('identifier', $identifier_id);
		$this->curl->addRequestData('type', 'm2o');
		$this->curl->addRequestData('email',  $this->input['system'].$this->input['user_id'].'@cloud.com');
		$data = $this->curl->request('register.php');
		return $data;
	}
	
	private function do_login()
	{
		$identifier_id = $this->get_identifier_id();
		$name = trim($this->input['user_name']);
		$tel = trim($this->input['tel']);
		$name = ($name != $tel) ? $name : 'yun'.$name;
		$this->curl = new curl($this->settings['App_members']['host'],$this->settings['App_members']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->addRequestData('member_name', $name);
		$this->curl->addRequestData('password', PASSWORD);
		$this->curl->addRequestData('identifier', $identifier_id);
		$this->curl->addRequestData('type','m2o');
		$this->curl->addRequestData('a', 'login');
		$data = $this->curl->request('login.php');
		return $data;
	}
	
	private function get_identifier_id()
	{
		$identifier = $this->input['system'];
		$sql = 'SELECT id FROM '.DB_PREFIX.'identifier WHERE identifier = "'.$identifier.'"';
		$query = $this->newdb->query_first($sql);
		if($query['id']) 
		{
			$identifier_id = $query['id'];
		}else{
			$sql = 'INSERT INTO '.DB_PREFIX.'identifier SET identifier = "'.$identifier.'" , create_time = '.TIMENOW;
			$this->newdb->query($sql);
			$identifier_id = $this->newdb->insert_id();
		}
		return $identifier_id;
	}
	
	public function check_token()
	{
		if(!intval($this->input['user_id']))
		{
			$this->errorOutput('请刷新页面后重新尝试');
		}
		$ret = array('success'=>0);
		if($this->user['user_id'] )
		{
			$this->connect_mb();
			$sql = 'SELECT new_member_id FROM '.DB_PREFIX.'member_bind WHERE member_id = '.intval($this->input['user_id']);
			$query = $this->newdb->query_first($sql);
			if($this->user['user_id'] == $query['new_member_id'])
			{
				$ret = array('success'=>1);
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	private function connect_mb()
	{
		$newdbconfig = $this->settings['db_bind'];
		$this->newdb = new db();
		$this->newdb->connect($newdbconfig['host'], $newdbconfig['user'],$newdbconfig['pass'],$newdbconfig['database']);
	}
}

$out = new submit();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'create';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>