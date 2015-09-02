<?php 
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','submit');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH.'lib/functions.php');
require(ROOT_DIR.'lib/class/curl.class.php');
$_INPUT['appid'] = $_INPUT['appid'] ? $_INPUT['appid'] : APPID;
$_INPUT['appkey'] = $_INPUT['appkey'] ? $_INPUT['appid'] : APPKEY;
class submit extends outerReadBase
{
	private $access_token;
	private $device_token;
	function __construct()
	{
		parent::__construct();
		$this->device_token = 'c84279cb08b981a0eb96246e8b81e677';//trim($this->input['device_token']);
		$this->appid = $this->input['appid'];
		$this->appkey = $this->input['appkey'];
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		session_start();
		$id = intval($this->input['id']);
		$answer = $this->input['answer'];
		$other_answer = $this->input['other_answer'];
		$this->curl = new curl($this->settings['App_survey']['host'],$this->settings['App_survey']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->setCurlTimeOut(100);
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('device_token', $this->device_token);
		$this->curl->addRequestData('column_id', intval($this->input['column_id']));
		$this->curl->addRequestData('verify_code', trim($this->input['verifycode']));
		$this->curl->addRequestData('session_id', $_SESSION['id']);
		$this->curl->addRequestData('appid', $this->appid);
		$this->curl->addRequestData('appkey', $this->appkey);
		$this->curl->addRequestData('need_cache', 1);
		$this->curl->addRequestData('a', 'update');
		if (is_array($answer))
		{
			$this->array_to_add('answer',$answer);
		}
		else
		{
			$this->curl->addRequestData('answer', $answer);
		}
		if (is_array($other_answer))
		{
			$this->array_to_add('other_answer',$other_answer);
		}
		else
		{
			$this->curl->addRequestData('other_answer', $other_answer);
		}
		
		if($_FILES)
		{
			$this->curl->addFile($_FILES);	
		}
		$data = $this->curl->request('survey_update.php');
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
		$this->curl = new curl($this->settings['App_survey']['host'],$this->settings['App_survey']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->setCurlTimeOut(100);
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'get_result_cache');
		$this->curl->addRequestData('appid', $this->appid);
		$this->curl->addRequestData('appkey', $this->appkey);
		$data = $this->curl->request('survey_update.php');
		echo $data;
		exit();
	}

	public function check_voted()
	{
		$id = intval($this->input['id']);
		$this->curl = new curl($this->settings['App_survey']['host'],$this->settings['App_survey']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->setCurlTimeOut(100);
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('device_token', $this->device_token);
		$this->curl->addRequestData('appid', $this->appid);
		$this->curl->addRequestData('appkey', $this->appkey);
		$this->curl->addRequestData('a', 'check_voted');
		$data = $this->curl->request('survey.php');
		echo $data;
		exit();
	}
	public function show(){}
	public function detail(){}
	public function count(){}
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