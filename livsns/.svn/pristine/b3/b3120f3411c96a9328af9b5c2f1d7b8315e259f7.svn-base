<?php
define('MOD_UNIQUEID','hogecloud');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/hogecloud_mode.php');
class hogecloud extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mPrmsMethods = array(
			'manage' => '使用',
		);
//		$this->mode = new hogecloud_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		//权限验证
		$this->privilege();
		
		$data = array(
			'username'		=> $this->user['user_name'],
			'custom_id'	=> CUSTOM_APPID,
			'custom_key' => CUSTOM_APPKEY,
		);
		$return = $this->curl($this->settings['hogecloud_index'],$data);
//		$ret = json_decode($return,1);
//		if($ret['errorCode'] == 100)
//		{
//			setcookie('token',$ret['token'],date('Y-m-d H:i:s',$ret['expired_time']),'/',$ret['domain']);
//		}
		$return = json_decode($return,1);
		$this->addItem($return);
		$this->output();
	}
	
	/*
	 * 绑定
	 */
	public function bound()
	{
		//权限验证
		$this->privilege();
		
		//参数验证
		$password = $this->check();
		
		//请求绑定
		$data = array(
			'username'  => $this->user['user_name'],
			'password'  => $password,
			'custom_id' => CUSTOM_APPID,
			'custom_key'=> CUSTOM_APPKEY,
		);
		$return = $this->curl($this->settings['hogecloud_bound'],$data);
//		$ret = json_decode($return,1);
//		if($ret['errorCode'] == 100)
//		{
//			setcookie('token',$ret['token'],time()+3600,'/',$ret['domain']);
//		}
		$return = json_decode($return,1);
		$this->addItem($return);
		$this->output();
	}
	
	private function check()
	{
		$password = trim($this->input['password']);
		$password_again = trim($this->input['password_again']);
		if(!$password || !$password_again)
		{
			echo json_encode(array(array('errorCode'=>'00','errorText'=>'密码不能为空')));exit;
		}
		if($password != $password_again)
		{
			echo json_encode(array(array('errorCode'=>'01','errorText'=>'两次输入的密码不一致')));exit;
		}
		return $password;
	}
	
	
	
	private function curl($url = '', $data = array())
	{
	    if (!$url)
	    {
	        return false;
	    }
	    $data_str = '';
	    foreach ($data AS $k => $v)
	    {
	        if (!$v)
	            continue;
	        $data_str .= $k . '=' . $v . '&';
	    }
	    $data_str = rtrim($data_str, '&');
	    $ch       = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, '20');
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
	    $response = curl_exec($ch);
	    curl_close($ch);
	    return $response;
	}
	
	/*
	 * 权限验证
	 */
	private function privilege()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['app_prms']['hogecloud'])
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
	}
	
	public function count(){}
	
	public function get_condition(){}
	
	public function detail(){}
}

$out = new hogecloud();
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