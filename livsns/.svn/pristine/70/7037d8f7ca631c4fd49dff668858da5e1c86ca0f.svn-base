<?php
//更新嘉宾头像（讲用户会员中心的头像同步到会议里面）
define('MOD_UNIQUEID','update_avatar');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
require_once(CUR_CONF_PATH . 'lib/activate_code_mode.php');
class update_avatar extends outerUpdateBase
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
	
	public function create(){}
	public function update(){}
	public function delete(){}
	
	public function run()
	{
		//判断有没有登陆
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_LOGIN);
		}
		
		//获取嘉宾信息
		$_memberInfo = $this->member_mode->detail(''," AND member_id = '" .$this->user['user_id']. "' ");
		if(!$_memberInfo)
		{
			$this->errorOutput(YOU_HAVE_NOT_ACTIVATED);
		}

		//获取该嘉宾在用户中心的头像
		$avatar = array();
		if($member_info  = $this->getMemberInfoFromMemberCenter($this->user['user_id']))
		{
			if($member_info[0])
			{
				$member_info = $member_info[0];
				if($member_info['avatar'] && is_array($member_info['avatar']))
				{
					$avatar = $member_info['avatar'];
				}
			}
		}
		
		//更新库里面的头像
		$_avatar = ($avatar && is_array($avatar))?addslashes(serialize($avatar)):'';
		if($this->member_mode->update($_memberInfo['id'],array('avatar' => $_avatar)))
		{
			//构建名片数据
			$_vcard_data = array(
				'id'				=> $_memberInfo['id'],
				'name' 				=> $_memberInfo['name'],
				'company'	 		=> $_memberInfo['company'],
				'job' 				=> $_memberInfo['job'],
				'telephone' 		=> $_memberInfo['telephone'],
				'email' 			=> $_memberInfo['email'],
				'avatar'			=> $avatar,
			);
			
			//引入二维码类
			if (!class_exists('QRcode')) 
	        {
	            include_once(CUR_CONF_PATH . 'lib/qrcode.class.php');
		    }
		    
		    $_pic_name = $_memberInfo['vcard_pic_name'];
		    $_isupdate = 0;
		    //如果库里面二维码图片的名称都没有就产生图片名
		    if(!$_pic_name)
		    {
		    	$_pic_name = md5(TIMENOW . hg_rand_num(6)) . '.png';//随机产生图片文件名
		    	$_isupdate = 1;
		    }
		    
		    if (!hg_mkdir(VCARD_DIR) || !is_writeable(VCARD_DIR))
			{
				$this->errorOutput(NO_WRITE);
			}
			QRcode::png (json_encode($_vcard_data), VCARD_DIR . $_pic_name);
			
			//更新库里面的图片名称
			if($_isupdate)
			{
				$this->member_mode->update($_memberInfo['id'],array('vcard_pic_name' => $_pic_name));
				$_memberInfo['vcard_pic_name'] = $_pic_name;
			}
		}
		
		$this->addItem(array('return' => 1));
		$this->output();
	}
	
	//从会员中心获取会员信息
	public function getMemberInfoFromMemberCenter($member_id = '')
	{
		if(!$this->settings['App_members'] || !$member_id)
		{
			return false;
		}
		
		$curl = new curl($this->settings['App_members']['host'],$this->settings['App_members']['dir']);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','detail');
		$curl->addRequestData('member_id',$member_id);
		$memberInfo = $curl->request('member.php');
		return $memberInfo;
	}
}

$out = new update_avatar();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 