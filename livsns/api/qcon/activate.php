<?php
//嘉宾激活接口
define('MOD_UNIQUEID','activate');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
require_once(CUR_CONF_PATH . 'lib/activate_code_mode.php');
require_once(CUR_CONF_PATH . 'lib/qrcode.class.php');
class activate extends outerUpdateBase
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
	
	public function activate()
	{
		$name 			= $this->input['name'];//用户名
		$company 		= $this->input['company'];//单位
		$job 			= $this->input['job'];//职务
		$telephone 		= $this->input['telephone'];//电话号码
		$email 			= $this->input['email'];//邮箱
		$device_token 	= $this->input['device_token'];//设备号
		
		//判断有没有登陆
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_LOGIN);
		}
		else if($this->member_mode->detail(''," AND member_id = '" .$this->user['user_id']. "' "))
		{
			$this->errorOutput(YOU_HAVE_ACTIVATED);
		}
		
		//判断有没有用户名
		if(!$name)
		{
			$this->errorOutput(NO_USERNAME);
		}
		
		//判断有没有单位
		if(!$company)
		{
			$this->errorOutput(NO_COMPANY);
		}
		
		//判断有没有职务
		if(!$job)
		{
			$this->errorOutput(NO_JOB);
		}
		
		//判断有没有手机号以及手机号的格式对不对
		if(!$telephone)
		{
			$this->errorOutput(NO_TELEPHONE);
		}
		elseif (!preg_match('/^1[3-8]\d{9}$/',$telephone))
		{
			$this->errorOutput(ERROR_FORMAT_TEL);
		}
		
		//判断有没有邮箱以及邮箱格式对不对
		if(!$email)
		{
			$this->errorOutput(NO_EMAIL);
		}
		elseif (!preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/i',$email))
		{
			$this->errorOutput(ERROR_FORMAT_EMAIL);
		}
		
		//判断有没有传递设备号
		if(!$device_token)
		{
			$this->errorOutput(NO_DEVICE_TOKEN);
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
				
		//生成嘉宾vCard数据
		$_vcard_data = array(
			'name' 				=> $name,
			'company'	 		=> $company,
			'job' 				=> $job,
			'telephone' 		=> $telephone,
			'email' 			=> $email,
			'avatar' 			=> $avatar,
		);
		
		$data = array(
			'member_id' 		=> $this->user['user_id'],
			'avatar' 			=> ($avatar && is_array($avatar))?addslashes(serialize($avatar)):'',
			'user_id' 			=> $this->user['user_id'],
			'user_name' 		=> $this->user['user_name'],
			'org_id' 			=> $this->user['org_id'],
			'ip' 				=> hg_getip(),
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
		);
		
		//需要入库的数据
		$data = array_merge($_vcard_data,$data);
		
		/*********开启一个事务***************/       
        $this->db->commit_begin();
        $commit_judge = 1;
		
        //创建嘉宾信息
		$vid = $this->member_mode->create($data);
		if(!$vid)
		{
			$commit_judge = 0;
		}

		//为用户绑定设备号
		$_deviceInfo = array(
			'device_token' 	=> str_replace(' ','',$device_token),
			'user_id' 		=> $vid,
			'source'       	=> (defined('ISIOS') && ISIOS) ? 1 : ((defined('ISANDROID') && ISANDROID) ? 2 : 2),
		);
		if(!$this->member_mode->bindDevice($_deviceInfo))
		{
			$commit_judge = 0;
		}

		/************判断事务是否执行成功*************/
		if (!$commit_judge) 
		{   
            $this->db->rollback();
            $this->errorOutput(UNKNOW);
        }
    	$this->db->commit_end();
    	/************判断事务是否执行成功*************/
		
		//生成二维码,放在data目录
		$_vcard_data['id'] = $vid;
		if($_pic_name = $this->create_vcard_pic($_vcard_data))
		{
			//生成之后更新到库里面
			$this->member_mode->update($vid,array('vcard_pic_name' => $_pic_name));
		}
		
		$data['id'] = $vid;
		$data['vcard_url'] = 'http://' . $this->settings['App_qcon']['host'] . '/' . $this->settings['App_qcon']['dir'] .'data/vcard/' . $_pic_name;  
		$data['avatar'] = $avatar;
		$this->addItem($data);
		$this->output();
	}
	
	//生成vcard二维码图片
	private  function create_vcard_pic($_vcard_data = array())
	{
		if(!$_vcard_data || empty($_vcard_data))
		{
			return false;
		}
		
		$_pic_name = md5(TIMENOW . hg_rand_num(6)) . '.png';//随机产生图片文件名
		if (!hg_mkdir(VCARD_DIR) || !is_writeable(VCARD_DIR))
		{
			$this->errorOutput(NO_WRITE);
		}
		QRcode::png (json_encode($_vcard_data), VCARD_DIR . $_pic_name);
		return $_pic_name;
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

$out = new activate();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'activate';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 