<?php
/* $Id: userprofile.php 4194 2011-07-26 05:26:45Z lijiaying $ */
define('ROOT_DIR','../');
define('SCRIPTNAME', 'userprofile');
require('./global.php');
class userProfile extends uiBaseFrm
{

	function __construct()
	{
		parent::__construct();
		
		$this->check_login();
		$this->load_lang('userprofile');
		include_once (ROOT_PATH . 'lib/class/curl.class.php');		
		include_once(ROOT_PATH . 'lib/user/user.class.php');	
		$this->info = new user();	
		$this->curl = new curl();

	}
	function __destruct()
	{
		parent::__destruct();
	}
	//加载页面
	function show()
	{
		$info = $this->info->getUserById($this->user['id'],"all");
		$this->userinfo = $info[0];
		$this->location = split('-',$this->userinfo['location']);
		$this->birth = split('-',$this->userinfo['birthday']);	
		$this->getOption();
		$this->getPrivacyOption();
		hg_add_head_element('js-c',"
		var userInfo ={
				province:" . json_encode($this->location[0]). ",
				city:" . json_encode($this->location[1]). ",
				country:" . json_encode($this->location[2]). ",
		}
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'zone.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'userprofile.js');
		$gScriptName = SCRIPTNAME;
		$this->page_title = '个人资料';

		$this->tpl->addVar('_userinfo', $this->userinfo);
		$this->tpl->addVar('_location', $this->location);
		$this->tpl->addVar('_birth', $this->birth);
		$this->tpl->addVar('_birthday', $this->birthday);
		$this->tpl->addVar('_privacy', $this->privacy);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('userprofile');
	}
	//获得日期选项
	function getOption()
	{
		//get year
		$dateLimit = date('Y');
		$str ="";
		for($i=$dateLimit;$i>=1900;$i--)
		{
			if($i==intval($this->birth[0]))
				$str .= "<option value='".$i."' selected='selected'>".$i."</option>";
			else 
				$str .= "<option value='".$i."'>".$i."</option>";
		}
		$this->birthday[0] = $str;
		// get month
		$str ="";
		for($i=1;$i<13;$i++)
		{
			if($i<10)
				$i = "0".$i;
			if(intval($i)==intval($this->birth[1]))
				$str .= "<option value='".$i."' selected='selected'>".$i."</option>";
			else 
				$str .= "<option value='".$i."'>".$i."</option>";
		}
		$this->birthday[1] = $str;
		//get date
		$str ="";
		for($i=1;$i<32;$i++)
		{
			if($i<10)
				$i = "0".$i;
			if(intval($i)==intval($this->birth[2]))
				$str .= "<option value='".$i."' selected>".$i."</option>";
			else 
				$str .= "<option value='".$i."'>".$i."</option>";
		}
		$this->birthday[2] = $str;
	}
	//更新表单
	function submitForm()
	{
		$userinfo = array(
			'id' => $this->user['id'],
			'truename' => urldecode($this->input['truename']),
			'sex' => intval($this->input['sex']),
			'email' => urldecode($this->input['email']),
			'username' => urldecode($this->input['username']),
			'location' => urldecode($this->input['location']),
			'location_code' => urldecode($this->input['location_code']),
			'birthday' => $this->input['birthday'],
			'qq' => $this->input['qq'],
			'mobile' => $this->input['mobile'],
			'msn' => urldecode($this->input['msn']),
			'privacy' =>$this->input['privacy']
		);
		$returnVal = $this->info->update_profile($userinfo);
echo json_encode($returnVal);
		if($returnVal[0] == 1)
		{
			echo "保存成功！";
		}
		else
		{
			echo "保存失败！";
		} 			
	}
	//检查用户名是否存在
	function checkUsername()
	{
		$info = $this->info->getUserById($this->user['id'],"all");
		$this->userinfo = $info[0];
		$username = $this->input['username'];
		$rt = $this->info->verifyUsername($username);
		if(strcmp($username,$this->userinfo['username']) == 0 || intval($rt['0']) != 1)
		{
			echo "ok";
		}
		else
		{
			echo "username already exists";
		}
	}
	
	//检查邮箱是否存在
	function checkEmail()
	{
		$info = $this->info->getUserById($this->user['id'],"all");
		$this->userinfo = $info[0];
		$email = $this->input['email'];
		$rt = $this->info->verifyEmail($email);
		if(strcmp($email,$this->userinfo['email']) == 0 || intval($rt['0']) != 1)
		{
			echo "ok";
		}
		else
		{
			echo "email already exists";
		}
	}
	
	//获得隐私选项
	function getPrivacyOption()
	{
		$gPublicBirth = $this->settings['userprofile']['birth'];
		$gPublicOther = $this->settings['userprofile']['other'];

		$privacy = $this->userinfo['privacy'];
		$privacy = substr($privacy,0,6);
		$privacyCount = 20;
		for($i=0;$i < $privacyCount;$i++)
		{
			$str = "";
			if($i == 1)
			{
				for($j=0;$j<count($gPublicBirth);$j++)
				{
					if(intval($privacy[$i]) == $j)
						$str .= "<option value='$j' selected='true'>".$gPublicBirth[$j]."</option>";
					else 
						$str .= "<option value='$j' >".$gPublicBirth[$j]."</option>";
				}
				
			}
			else
			{
				for($j=0;$j<count($gPublicOther);$j++)
				{
					if(intval($privacy[$i]) == $j)
						$str .= "<option value='$j' selected='true'>".$gPublicOther[$j]."</option>";
					else 
						$str .= "<option value='$j' >".$gPublicOther[$j]."</option>";
				}
			}
			$this->privacy[$i] = $str;
		}
	}
}
$out = new userProfile();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>