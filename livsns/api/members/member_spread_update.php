<?php
/*******************************************************************
 * filename :member_spread_update.php
 * 推广接口
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','memberSpread');//模块标识
require('./global.php');
class memberSpreadUpdateApi extends appCommonFrm
{
	private $member_id = 0;//会员id
	private $spreadCode = '';//推广标识
	private $deviceToken = '';//设备标识

	public function __construct()
	{
		parent::__construct();
		$this->memberSpread = new memberSpread();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$this->get_condition();//获取必要参数处理
		$this->dataProcess();//数据处理
		$this->outProcess();//输出更新状态
	}

	private function dataProcess()
	{
		$data = array(
			'spreadCode'   => $this->spreadCode,
			'fuid'		   => $this->member_id,
			'device_token' => $this->deviceToken,
			'create_time'  => TIMENOW,
		);
		$this->retData = $this->memberSpread->create($data);
	}
	private function outProcess()
	{
		if($this->retData)
		{
			$this->addItem_withkey('status', 1);
			$this->addItem_withkey('copywriting', '恭喜,您已经成功提交推广码！');
			$this->output();
		}
		else
		{
			$this->errorOutput(SPREAD_ERROR);
		}
	}
	/**
	 *
	 * 获取需要的条件
	 */
	private function get_condition()
	{
		$Members = new members();
		$this->deviceToken = $Members->check_device_token(trim($this->input['device_token']),1);
		if(! $this->deviceToken)
		{
			$this->errorOutput(ERROR_DEVICE_TOKEN);
		}
		else if (SPREADDTONLY && $this->memberSpread->verify(array('device_token'=>$this->deviceToken)))
		{
			$this->errorOutput(DEVICE_TOKEN_ALREADY_SPREAD);
		}
		
		if($this->user['user_id'])
		{
			$this->member_id = $this->user['user_id']?$this->user['user_id']:0;
			if(!$Members->checkuser($this->member_id))
			{
				$this->errorOutput(NO_MEMBER);
			}
			if($this->memberSpread->verify(array('fuid'=>$this->member_id)))
			{
				$this->errorOutput(USER_ALREADY_SPREAD);
			}
		}
		else
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		if($this->input['spreadcode'])
		{
			$this->spreadCode = trim($this->input['spreadcode']);
			if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $this->spreadCode))
			{
				$this->errorOutput(PROHIBIT_CN);
			}
			elseif(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$this->spreadCode))
			{
				$this->errorOutput(NO_LEGAL_CHARACTER);
			}
		}
		else {
			$this->errorOutput(NO_SPREADCODE_ERROR);
		}
	}

	/**
	 * 空方法,如果用户调取的方法不存在.则执行
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在");
	}


}

$out = new memberSpreadUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>