<?php 
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','lottery');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR.'lib/class/curl.class.php');
class lottery extends BaseFrm
{
	var $curl;
	function __construct()
	{
		parent::__construct();
		$this->appid = intval($this->input['appid']);
		$this->appkey = trim($this->input['appkey']);
		$this->access_token = trim($this->input['access_token']);
		$this->device_token = trim($this->input['device_token']);
		
		$this->curl = new curl($this->settings['App_lottery']['host'],$this->settings['App_lottery']['dir']);
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	public function lottery()
	{
		$id = intval($this->input['id']);
		
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('appid', $this->appid);
		$this->curl->addRequestData('appkey', $this->appkey);
		$this->curl->addRequestData('access_token', $this->access_token);
		$this->curl->addRequestData('device_token', $this->device_token);
		$this->curl->addRequestData('version', trim($this->input['version']));
		
		$this->curl->addRequestData('GPS_latitude', trim($this->input['GPS_latitude']));
		$this->curl->addRequestData('GPS_longitude', trim($this->input['GPS_longitude']));
		$this->curl->addRequestData('baidu_longitude', trim($this->input['baidu_longitude']));
		$this->curl->addRequestData('baidu_latitude', trim($this->input['baidu_latitude']));
		
		$this->curl->addRequestData('a', 'detail');
		
		$data = $this->curl->request('lottery_api.php');
		echo $data;
		exit();
	}
	
	public function update_win_info()
	{
		$sendno = $this->input['sendno'];
		//$this->curl = new curl($this->settings['App_lottery']['host'],$this->settings['App_lottery']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->addRequestData('sendno', $sendno);
		
		$this->curl->addRequestData('access_token', $this->access_token);
		$this->curl->addRequestData('device_token', $this->device_token);
		
		$this->curl->addRequestData('a', 'update_win_info');
		$data = $this->curl->request('lottery_api.php');
		echo $data;
		exit();
	}
	
	public function update_address()
	{
		$sendno = $this->input['sendno'];
		//$this->curl = new curl($this->settings['App_lottery']['host'],$this->settings['App_lottery']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		
		$this->curl->initPostData();
		
		$this->curl->addRequestData('access_token', $this->access_token);
		$this->curl->addRequestData('address', $this->input['address']);
		$this->curl->addRequestData('phone_num', $this->input['phone_num']);
		
		$this->curl->addRequestData('sendno', $sendno);
		$this->curl->addRequestData('a', 'update_address');
		
		$data = $this->curl->request('lottery_api.php');
		echo $data;
		exit();
	}
	
	public function get_member_win_info()
	{
		$count = intval($this->input['count']);
		$offset = intval($this->input['offset']);
		
		$offset = $offset ? $offset : 0;
		$count = $count ? $count : 10;
		
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		
		$this->curl->initPostData();
		
		$this->curl->addRequestData('access_token', $this->access_token);
		$this->curl->addRequestData('lottery_id', intval($this->input['lottery_id']));
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		
		$this->curl->addRequestData('a', 'get_member_win_info');
		
		$data = $this->curl->request('lottery_api.php');
		echo $data;
		exit();
	}
	
	
	//获取订单信息
	public function get_order_info()
	{
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		
		$this->curl->initPostData();
		
		$this->curl->addRequestData('seller_id', $this->input['seller_id']);
		$this->curl->addRequestData('send_no', $this->input['send_no']);
		
		$this->curl->addRequestData('a', 'get_order_info');
		
		$data = $this->curl->request('lottery_api.php');
		echo $data;
		exit();
	}
	

	//确认兑奖
	public function confirm_prize()
	{
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		
		$this->curl->initPostData();
		
		$this->curl->addRequestData('seller_id', $this->input['seller_id']);
		$this->curl->addRequestData('send_no', $this->input['send_no']);
		
		$this->curl->addRequestData('a', 'confirm_prize');
		
		$data = $this->curl->request('lottery_api.php');
		echo $data;
		exit();
	}
	
	
	//获取商家兑换信息
	public function seller_exchange_info()
	{
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		
		$this->curl->initPostData();
		
		$this->curl->addRequestData('seller_id', $this->input['seller_id']);
		
		$this->curl->addRequestData('a', 'seller_exchange_info');
		
		$data = $this->curl->request('lottery_api.php');
		echo $data;
		exit();
	}
	
	
	public function get_address_info()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		
		$this->curl->initPostData();
		
		$this->curl->addRequestData('id', $id);
		
		$this->curl->addRequestData('a', 'get_address_info');
		
		$this->curl->addRequestData('access_token', $this->access_token);
		
		$data = $this->curl->request('lottery_api.php');
		echo $data;
		exit();
	}
	
	
	public function exchange_prize()
	{
		$sendno = $this->input['send_no'];
		$exchange_code = $this->input['exchange_code'];
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		
		$this->curl->initPostData();
		
		$this->curl->addRequestData('send_no', $sendno);
		$this->curl->addRequestData('exchange_code', $exchange_code);
		$this->curl->addRequestData('access_token', $this->access_token);
		$this->curl->addRequestData('a', 'exchange_prize');
		
		$data = $this->curl->request('lottery_api.php');
		echo $data;
		exit();
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
			echo $data;
			exit();
		}
	}
	
}

$out = new lottery();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'lottery';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>