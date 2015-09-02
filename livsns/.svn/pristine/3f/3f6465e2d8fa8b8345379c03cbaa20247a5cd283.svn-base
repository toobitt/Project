<?php
class leanCloud
{
	private $host;
	private $client_id;
	private $email;
	private $username;
	private $timestamp;
	private $scope;
	private $client_secret;
	private $apiHost;
	
	public function __construct()
	{
		$this->apiHost = "https://leancloud.cn/1.1/open";
		$this->host = "https://leancloud.cn";
		$this->timestamp = (time()+8*60*60)*1000;
		$this->client_id = LEANCLOUD_CLIENT_ID;
		$this->client_secret = LEANCLOUD_CLIENT_SECRET;
	}
	
	/**
	 * 创建用户
	 */
	public function createUser($email = "" , $username = "")
	{
		$this->email = $email;		
		$this->username = $username;
		$this->scope = "app:create app:info app:settings app:key";
		$string = "/1.1/connect?client_id=".$this->client_id;
		$string = $string."&email=".$this->email;
		$string = $string."&scope=".$this->scope;
		$string = $string."&timestamp=".$this->timestamp;
		$string = $string."&username=".$this->username;
		$sign = hash_hmac("sha256",$string,$this->client_secret);
		$url = $this->host.$string."&sign=".$sign;
		$url = str_replace(' ', '%20', $url);
		$ret = json_decode($this->curl($url,"post"),1);
		return $ret;
	}
	
	/**
	 * 创建应用
	 */
	public function createApp($access_token = "" , $uid = 0 , $name = "" , $description = "")
	{
		$createUrl = $this->apiHost."/clients/".$uid."/apps?access_token=".$access_token;
		$data = array(
			'name' => $name,
			'description' => $description,
		);
		$ret = json_decode($this->curl($createUrl,'post',$data),1);
		return $ret;
	}
	
	/**
	 * 获取单个应用信息
	 */
	public function getSingleAppInfo($uid = 0 , $accsss_token = '')
	{
		$getUrl = $this->apiHost."/clients/".$uid."/apps?access_token=".$accsss_token;
		$type = "get";
		$ret =$this->curl($getUrl,$type);
		return json_decode($ret,1);	
	}
	
	/**
	 * 获取应用列表
	 */
	public function getAppList($uid = 0 , $accsss_token = '')
	{
		$type = "get";
		$url = $this->apiHost."/clients/".$uid."/apps?access_token=".$accsss_token;
		$ret = $this->curl($url,$type);
		return json_decode($ret,1);
	}
	
	/**
	 * 获取应用key
	 */
	public function getAppKey($uid = "",$app_id = "",$access_token = "")
	{
		$type = "get";
		$url = $this->apiHost."/clients/".$uid."/apps/".$app_id."/key?access_token=".$access_token;
		$ret = $this->curl($url,$type);
		return json_decode($ret,1);
	}
	
	/**
	 * 获取应用key 只有用户信息请看下
	 * @param string $uid
	 * @param string $access_token
	 * @param string $name
	 * @return mixed
	 */
	public function getAppInfoBy($uid = "" , $access_token = "", $name = '')
	{
		$type = "get";
		$url = $this->apiHost."/clients/".$uid."/apps?access_token=".$access_token;
		$ret = $this->curl($url,$type);
		$app_list = json_decode($ret,1);
		foreach ($app_list as $k => $v)
		{
			$info = $v['app_name'] = $name ? $v : '';
		}
		$app_id = $info['app_id'];
		$app_info = $this->getAppKey($uid,$app_id,$access_token);
		$app_info['client_id'] = $info['client_id'];
		$app_info['created'] = $info['created'];
		return $app_info;
	}
	
	/**
	 * 获取用户信息
	 */
	public function getUserInfo()
	{
		$type = "get";
	}
	
	/**
	 * 获取用户详细信息
	 */
	public function getUserDetailInfo()
	{
		$type = "get";
	}
	
	public function curl($url = '' , $type = '' , $data = array())
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($type == 'post')
		{
			curl_setopt($ch, CURLOPT_POST, 1);
		}	
		if($data)
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		$ret = curl_exec($ch);
		return $ret;
	}
}