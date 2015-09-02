<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: visit.php 3386 2011-04-07 01:54:52Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class qqApi extends appCommonFrm
{
	private $mCredit;
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		require(ROOT_PATH . 'lib/class/credit.class.php');
		$this->mUser = new user();
		$this->mCredit = new credit();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 用QQ账户登录（同步数据）
	 * @param $nickname QQ昵称
	 * @param $openid 返回的唯一ID
	 * retrun $info
	 */
	function verfiy(){
		
		$info = array(
			'nickname' => $this->input['nickname']?urldecode($this->input['nickname'])."@qq.com":hexdec(substr(urldecode($this->input['openid']), 0,6)),
			'openid' => urldecode($this->input['openid']),
			'avatar' => urldecode($this->input['avatar']),
			'name_other' =>hexdec(substr(urldecode($this->input['openid']), 0,6)),
		);
		
		/*测试数据 
		$info = array(
			'nickname' => 'Yang@qq.com',
			'openid' => 'A2D8AE5184F0453A7F5502463CE6DD0C',
			'name_other' =>hexdec(substr('A2D8AE5184F0453A7F5502463CE6DD0C', 0,6)),
		);
		*/
		
		$sql = "SELECT * FROM ". DB_PREFIX ."member WHERE qq_login='".$info['openid']."'";
		$f1 = $this->db->query_first($sql);
		if($f1 && is_array($f1))
		{
			$this->setXmlNode('visit_info' , 'visit');
			$this->addItem($f1);
			$this->output();
		}
		else 
		{
			$sql = "select * from ". DB_PREFIX ."member where username = '".$info['nickname']."'";
			$f4 = $this->db->query_first($sql);
			if($f4 && is_array($f4))
			{
				$info['nickname'] = $info['name_other'];
				$sql = "select * from ". DB_PREFIX ."member where username = '".$info['nickname']."'";
				$f5 = $this->db->query_first($sql);
				if($f5 && is_array($f5))
				{
					$this->errorOutput(OBJECT_NULL);
					
					echo "这样都有重复？！我无语";
					exit;
				}
				else 
				{
					$salt = hg_generate_salt();
					$userinfo = array(
						'username' => $info['nickname'],
						'password' => md5(md5($info['openid']).$salt),
						'salt' => $salt,
						'avatar' => $info['avatar'],
						'qq_login' => $info['openid'],
						'join_time' => TIMENOW,
						'last_login' => TIMENOW,
						'privacy' => 0,
					);
					$sql = "
						INSERT ".DB_PREFIX."member
						(
							username,password,salt,avatar,qq_login,join_time,last_login
						) 
						VALUES
						(
							'".$userinfo['username']."','".$userinfo['password']."','".$userinfo['salt']."',
							'".$userinfo['avatar']."','".$userinfo['qq_login']."',".$userinfo['join_time'].",".$userinfo['last_login']."
						)";
					$this->db->query($sql);
					$userinfo['id'] = $this->db->insert_id();
					
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
					$this->setXmlNode('visit_info' , 'visit');
					$this->addItem($userinfo);
					$this->output();
				}
			}
			else 
			{
				$salt = hg_generate_salt();
				$userinfo = array(
					'username' => $info['nickname'],
					'password' => md5(md5($info['openid']).$salt),
					'salt' => $salt,
					'avatar' => $info['avatar'],
					'qq_login' => $info['openid'],
					'join_time' => TIMENOW,
					'last_login' => TIMENOW,
					'privacy' => 0,
				);
				$sql = "
					INSERT ".DB_PREFIX."member
					(
						username,password,salt,avatar,qq_login,join_time,last_login
					) 
					VALUES
					(
						'".$userinfo['username']."','".$userinfo['password']."','".$userinfo['salt']."',
						'".$userinfo['avatar']."','".$userinfo['qq_login']."',".$userinfo['join_time'].",".$userinfo['last_login']."
					)";
				$this->db->query($sql);
				$userinfo['id'] = $this->db->insert_id();
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
				$this->setXmlNode('visit_info' , 'visit');
				$this->addItem($userinfo);
				$this->output();
			}
		}
	}
	
	function create(){
		$mInfo = $this->mUser->verify_credentials();

		if(!$user_id&&!$cid)
		{
		  $this->errorOutput(OBJECT_NULL);
		}


		
		$this->setXmlNode('albums','info');
		$this->addItem($info);
		$this->output();
	}
}

$out = new qqApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>