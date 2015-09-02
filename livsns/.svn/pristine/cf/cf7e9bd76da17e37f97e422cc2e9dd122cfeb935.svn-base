<?php
//验证用户是否可以签到
define('MOD_UNIQUEID','verify_sign_in');
define('SCRIPT_NAME', 'verify_sign_in');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
require_once(CUR_CONF_PATH . 'lib/activate_code_mode.php');
class verify_sign_in extends outerReadBase
{
	private $member_mode;
	private $activate;
	public function __construct()
	{
		parent::__construct();
		$this->member_mode = new member_mode();
		$this->activate = new activate_code_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}
	
	public function show()
	{
		//判断有没有登陆
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_LOGIN);
		}
		
		//判断到没到签到时间
		if(TIMENOW < strtotime(SIGN_STIME))
		{
			$this->errorOutput(SIGN_NOT_START);
		}
		
		//判断是否过了签到时间
		if(TIMENOW > strtotime(SIGN_ETIME))
		{
			$this->errorOutput(SIGN_HAVE_OVER);
		}

		//判断用户在不在会场附近
		if(defined('IS_VERIFY_GPS') && IS_VERIFY_GPS)
		{
			if(!$this->input['long_x'] || !$this->input['lat_y'])
			{
				$this->errorOutput(NO_X_Y);
			}
			else
			{
				$long_x = $this->input['long_x'];//经度
				$lat_y 	= $this->input['lat_y'];//纬度
				
				//如果是安卓设备传过来的话，传过来的是百度坐标，需要转换成GPS坐标
				if((defined('ISANDROID') && ISANDROID) || $this->input['is_android'])
				{
					$_GPS = FromBaiduToGpsXY($long_x,$lat_y);
					if($_GPS)
					{
						$long_x = $_GPS['GPS_x'];
						$lat_y 	= $_GPS['GPS_y'];
					}
				}
	
				$_distance = GetDistance($lat_y,$long_x,$this->settings['meeting_pos']['y'],$this->settings['meeting_pos']['x']);
				if($_distance > MEETING_DISTANCE)
				{
					$this->errorOutput(YOU_NOT_NEARBY_MEETING);
				}
			}
		}

		//判断当前用户有没有激活
		$_memberInfo = $this->member_mode->detail(''," AND member_id = '" .$this->user['user_id']. "' ");
		if(!$_memberInfo)
		{
			$this->errorOutput(YOU_HAVE_NOT_ACTIVATED);
		}
		
		$ret = array();
		//根据激活码id取出激活码类型
		$code = $this->activate->detail($_memberInfo['activate_code_id']);
		if($code)
		{
			$ret['guest_type'] = $code['guest_type'];
		}
		else
		{
			$ret['guest_type'] = '1';//默认场外嘉宾
		}
		
		//判断当前用户是否已经签过到
		if($_memberInfo['is_sign'])
		{
			//已经签到的场外嘉宾提示
			if($ret['guest_type'] == '1')
			{
				$this->errorOutput(NOT_KNOW_IDENTY);
			}
			else 
			{
				$this->errorOutput(YOU_HAVE_SIGNED);
			}
		}

		$this->addItem($ret);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');