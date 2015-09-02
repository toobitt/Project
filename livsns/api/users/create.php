<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: create.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('../lib/user.class.php');
require(ROOT_DIR . 'lib/class/credit.class.php');
require(ROOT_DIR . 'lib/class/uset.class.php');
class createApi extends appCommonFrm
{
	private $mUser;
	private $mCredit;
	function __construct()
	{
		parent::__construct();
		$this->mUser = new user();
		$this->mCredit = new credit();
		$this->mUset = new uset();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	
	/**
	* 插入1000邀请码
	*/
	public function create_code()
	{
		$num = 1000;
		$sql = "INSERT INTO";
		for($num = 1000;$num>0;$num--)
		{
			$arr[] = hg_generate_user_salt(8);
		}
		
		$sql = "INSERT INTO ".DB_PREFIX."invite_code(code) VALUES";
		$con = "";
		$space = "";
		foreach($arr as $key=>$value)
		{
			
			$con .= $space."('".$value."')";
			$space = ", ";
		}
		$sql = $sql.$con;
		$this->db->query($sql);
	}
	
	public function record_email()
	{
		$email = urldecode($this->input['email']?$this->input['email']:"");
		if(!$email)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else
		{
			$sql = "INSERT IGNORE INTO ".DB_PREFIX."email(email) VALUES('".$email."')";
			$this->db->query($sql);
			$this->setXmlNode('register','result');
			$this->addItem($email);
			$this->output();
		}
	}
	
	/**
	* 验证邀请码
	*@param $invite_code;
	*/
	public function verify_invite_codes()
	{
		$invite_code = urldecode($this->input['invite_code']?$this->input['invite_code']:"");
		
		if(!$invite_code)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "SELECT * FROM ".DB_PREFIX."invite_code WHERE code ='".$invite_code."'";
		$q = $this->db->query_first($sql);
		
		if(!is_array($q)&&!$q)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else
		{
			if($q['member_id'])
			{
				$this->errorOutput(OBJECT_NULL);
			}
			else 
			{
				$this->setXmlNode('register','result');
				$this->addItem(1);
				$this->output();
			}
		}
	}
	
	/**
	* 验证邀请码
	*@param $invite_code;
	*/
	private function verify_invite_code($invite_code)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."invite_code WHERE code ='".$invite_code."'";
		$q = $this->db->query_first($sql);
		if(!is_array($q)&&!$q)
		{
			return false;
		}
		else
		{
			if($q['member_id'])
			{
				return false;
			}
			else 
			{
				return true;
			}
		}
	}
	
	/**
	* 更新邀请码
	*@param $member_id;
	*@param $code;
	*/
	private function update_invite_code($member_id,$code)
	{
		$sql = "UPDATE ".DB_PREFIX."invite_code SET member_id =".$member_id." WHERE code='".$code."'";
		$q = $this->db->query($sql);
	}
	
	/**
	* 增加用户
	*@return array 用户信息
	*/
	public function create()
	{
		//判断是否允许注册
		$rt = $this->mUset->get_desig_uset(array('register','noregister','emailAction','isopeninvite'));
		if($rt['result'] == 1)
		{
			$rt0 = $rt[0];//register
			$rt1 = $rt[1];//noregister
			$rt2 = $rt[2];//emailAction
			$rt3 = $rt[3];//isopeninvite
			$rt3['descripion'] = "请通过邀请进行注册！";
			if(!$rt0['status'])//close
			{
				if(!$rt3['status'])//close invite_code
				{
					$this->setXmlNode('register','result');
					$ret['register'] =1;
					$ret['reason'] =$rt1['status'];
					$this->addItem($ret);
					$this->output();
				}
				else 
				{
					if(!urldecode($this->input['invite_code']))
					{
						$this->setXmlNode('register','result');
						$ret['register'] =1;
						$ret['reason'] =$rt3['descripion'];
						$this->addItem($ret);
						$this->output();
					}
					else 
					{
						$is_invite = $this->verify_invite_code(urldecode($this->input['invite_code']));
						if(!$is_invite)
						{
							$this->setXmlNode('register','result');
							$ret['register'] =1;
							$ret['reason'] =$rt3['descripion'];
							$this->addItem($ret);
							$this->output();
						}
					}
				}
			}
			else //open
			{
				if(urldecode($this->input['invite_code']))
				{
					$is_invite = $this->verify_invite_code(urldecode($this->input['invite_code']));
				}
			}		
		}
		if(!$this->input['username'])
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码
		}	
		$username = urldecode(trim($this->input['username']));
		
		$patten = "/[!@#$%&()><\\/:;|,，。？！}{‘’“”\'\"]+/u";
		if(preg_match($patten,$username))
		{
			$this -> errorOutput(NON_SPECIAL_CHAR);
		}
		//判断是否有禁止词
		include_once(ROOT_PATH . 'lib/class/banword.class.php');	
		$banword = new banword();
		$rt = $banword->banword($username);
		if($rt && $rt != 'null')
		{
			$this->setXmlNode('userinfo','repeat_user');
			$rt['banword'] =1;
			$this->addItem($rt);
			$this->output();
			exit;
		}
		$result = $this->mUser->checkUsername($username);
		if($result)
		{
			$this->setXmlNode('userinfo','repeat_user');
			$rt['user_exist'] =1;
			$rt['message'] = '用户名已被占用';
			$this->addItem($rt);
			$this->output();
			exit;
		}
		$email = trim(urldecode(($this->input['email'])));
		if(!hg_clean_email($email))
		{
			$this -> errorOutput(EMAIL_ERROR);//返回0x2000代码
		}
		$result = $this->mUser->checkEmail($email);
		if($result)
		{
			$this -> errorOutput(EMAIL_REPEAT);//返回0x2100代码
		}
		$salt = hg_generate_salt();
		$password = md5(md5(trim($this->input['password'])).$salt);
		$location = trim(urldecode($this->input['location']));
		$location_code = trim(urldecode($this->input['location_code']));
		$avatar = trim(urldecode($this->input['avatar']))?trim(urldecode($this->input['avatar'])): AVATAR_DEFAULT;//调用头像接口
		$userinfo = array(
			'email' => $email,
			'username' => $username,
			'password' => $password,
			'salt' => $salt,
			'location' => $location,
			'location_code' => $location_code,
			'avatar' => $avatar,
			'birthday' => urldecode($this->input['birthday']),
			'qq' => urldecode($this->input['qq']),
			'mobile' => urldecode($this->input['mobile']),
			'msn' => urldecode($this->input['msn']),
			'source' => intval($this->input['source']),
			'digital_tv' => urldecode($this->input['digital_tv']),
			'join_time' => TIMENOW,
			'last_login' => TIMENOW,
			'privacy' => 0,
		);
		$sql = "
			INSERT ".DB_PREFIX."member
			(
				email,username,password,salt,location,location_code,
				birthday,avatar,qq,mobile,msn,join_time,
				last_login,digital_tv,source
			) 
			VALUES
			(
				'".$userinfo['email']."','".$userinfo['username']."','".$userinfo['password']."','".$userinfo['salt']."',
				'".$userinfo['location']."','".$userinfo['location_code']."','".$userinfo['birthday']."',
				'".$userinfo['avatar']."','".$userinfo['qq']."',
				'".$userinfo['mobile']."',
				'".$userinfo['msn']."',".$userinfo['join_time'].",".$userinfo['last_login'].",'" . $userinfo['digital_tv'] . "','" . $userinfo['source'] . "'
			)";
		$this->db->query($sql);
		$userinfo['id'] = $this->db->insert_id();
		if($is_invite)
		{
			$this->update_invite_code($userinfo['id'],urldecode($this->input['invite_code']));
		}
		$credit_info = $this->mCredit->get_single_credit_rule(REGISTER); //获取注册积分
		$credit = floatval($credit_info['credit']);
		
		$userextra = array(
		 	'member_id' => $userinfo['id'],
		 	'last_activity' => TIMENOW,
		 	'followers_count' => 0,
		 	'attention_count' => 0,
		 	'ip' => hg_getip(),		 	
		 );
		$sql = "INSERT ".DB_PREFIX."member_extra
		(
			member_id,
			last_activity,
			followers_count,
			attention_count,
			reffer_user,
			ip,
			credit 
		) 
		VALUES
		(
			".$userextra['member_id'].",
			".$userextra['last_activity'].",
			".$userextra['followers_count'].",
			".$userextra['attention_count'].",
			".intval($this->input['reffer_user']).",
			'".$userextra['ip']."' , 
			" . $credit . "
		)";	
		$this->db->query($sql);
		if($rt2['status'] == 1)//需要邮箱激活
		{
			include_once(ROOT_PATH . 'lib/user/email.class.php');
			$emailclass = new email();
			$data = array(
				'id' =>$userinfo['id'],
				'username' =>$userinfo['username'],
				'email' => $userinfo['email']
			);
			$rt = $emailclass->send_link($data);
			if($rt['done'] == 1)
			{
				$userinfo['send_email'] = 1;
			}
			else
			{
				$userinfo['send_email'] = 0;
			}
			$userinfo['email_action'] = 1;
		}		
		$this->setXmlNode('userinfo','user');
		$this->addItem($userinfo);
		return $this->output();
	}
	
}

$out = new createApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>