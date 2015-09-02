<?php
//获取个人名片信息
define('MOD_UNIQUEID','card_info');
define('SCRIPT_NAME', 'card_info');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
require_once(CUR_CONF_PATH . 'lib/activate_code_mode.php');
class card_info extends outerReadBase
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
	
	public function count(){}
	public function detail(){}
	
	public function show()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_LOGIN);
		}
		
		//获取嘉宾激活信息
		$_memberInfo = $this->member_mode->detail(''," AND member_id = '" .$this->user['user_id']. "' ");
		if(!$_memberInfo)
		{
			$this->errorOutput(YOU_HAVE_NOT_ACTIVATED);
		}
	
		//判断名片二维码存不存在，不存在就要生成一张
		if(!file_exists(VCARD_DIR . $_memberInfo['vcard_pic_name']) || !is_file(VCARD_DIR . $_memberInfo['vcard_pic_name']))
		{
			//构建名片数据
			$_vcard_data = array(
				'id'				=> $_memberInfo['id'],
				'name' 				=> $_memberInfo['name'],
				'company'	 		=> $_memberInfo['company'],
				'job' 				=> $_memberInfo['job'],
				'telephone' 		=> $_memberInfo['telephone'],
				'email' 			=> $_memberInfo['email'],
				'avatar'			=> $_memberInfo['avatar']?@unserialize($_memberInfo['avatar']):array(),
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
		
		//获取自己已经交换名片的个数
		$_memberInfo['exchange_nums'] = $this->member_mode->get_exchanged_nums($_memberInfo['id']);
		$_memberInfo['vcard_url'] = $data['vcard_url'] = 'http://' . $this->settings['App_qcon']['host'] . '/' . $this->settings['App_qcon']['dir'] .'data/vcard/' . $_memberInfo['vcard_pic_name'];
		$_memberInfo['avatar'] = $_memberInfo['avatar']?unserialize($_memberInfo['avatar']):array();
		$this->addItem($_memberInfo);
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');