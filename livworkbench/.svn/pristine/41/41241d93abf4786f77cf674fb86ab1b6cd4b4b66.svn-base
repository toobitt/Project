<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('ROOT_DIR', './');
define('WITH_DB', false);
define('SCRIPT_NAME', 'login');
require('./global.php');
if (!class_exists('curl'))
{
	include(ROOT_PATH . 'lib/class/curl.class.php');
}
class login extends uiBaseFrm
{
	private $curl;
	private $mLive;
	function __construct()
	{
		parent::__construct();
		unset($this->nav);
		$this->tpl->addVar('_nav', $this->nav);
		$this->input['code'] = $_SERVER['CHANNEL_CODE'];
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show($message = '')
	{
		if ($this->user['id'])
		{
			$this->Redirect('', 'index.php', '', 1);
		}
		$this->tpl->addVar('message', $message);
		if ($this->input['ajax'])
		{
			$this->tpl->addVar('_ajax', $this->input['ajax']);
			$callback = 'hg_show_template';
			$hg_ajax_submit = ' onsubmit="return hg_ajax_submit(\'loginform\');"';
			$this->tpl->addVar('hg_ajax_submit', $hg_ajax_submit);
		}

		//调出auth系统的密保卡开关
		if($this->settings['App_auth'])
		{
			$this->curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
			$this->curl->initPostData();
			$this->curl->addRequestData('a','get_mibao_status');
			$mibao_card = $this->curl->request('get_access_token.php');
			$mibao_card = $mibao_card[0];
			$this->tpl->addVar('isopencard',$mibao_card['open']);
		}

		if ($this->channel)	//调用直播互动登陆模板
		{
			//获取频道信息
			$this->mLive = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir']);
			$this->mLive->initPostData();
			$this->mLive->addRequestData('a', 'show');
			$this->mLive->addRequestData('channel_code', $this->input['code']);
			$ret_channel = $this->mLive->request('channel.php');
			
			if (empty($ret_channel[0]))
			{
				$this->ReportError('该频道信息不存在或已被删除');
			}
			$ret_channel = $this->channel['channel'];
			$channel_info = array(
				'id' 		 => $ret_channel['id'],
				'code'		 => $ret_channel['code'],
				'name'		 => $ret_channel['name'],
				'audio_only' => $ret_channel['audio_only'],
				'logo_info'	 => $ret_channel['logo']['rectangle'],
				'_mid'	 	 => $this->input['_mid'],
				'menuid'	 => $this->input['menuid'],
			);
			$this->tpl->addVar('channel_info', $channel_info);
			$this->tpl->outTemplate('interactive_login', $callback);
		}
		else
		{
			$this->tpl->outTemplate('login', $callback);
		}
	}

	public function dologin()
	{	
		$username = $this->input['username'];
		$password = $this->input['password'];
		$secret_value = $this->input['secret_value'];/*用户输入密保卡的值*/
		$security_zuo = $this->input['security_zuo'];/*密保卡坐标*/
		if ($this->settings['App_auth'])
		{
			//$this->show('授权服务器通信失败（配置文件缺失）！');
			$this->curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
			$this->curl->initPostData();
			$this->curl->addRequestData('appid', APPID);
			$this->curl->addRequestData('appkey', APPKEY);
			$this->curl->addRequestData('username', $username);
			$this->curl->addRequestData('password', $password);
			$this->curl->addRequestData('ip', hg_getip());
			//密保卡相关验证参数
			$this->curl->addRequestData('security_zuo',$security_zuo);
			$this->curl->addRequestData('secret_value',$secret_value);
			$ret = $this->curl->request('get_access_token.php');
			if($ret['ErrorCode'])
			{
				$this->show($ret['ErrorCode'].$ret['ErrorText']);
			}
			$ret = $ret[0];
			if($ret['forced_change_pwd'])
			{
				//如果开启了强制修改密码,第一次登陆的时候必须修改密码
				$this->tpl->addVar('admin_id', $ret['id']);
				$this->tpl->addVar('user_name', $ret['user_name']);
				$this->tpl->outTemplate('change_pwd');
			}
			if($ret['domain'] && $ret['domain']!= $_SERVER['HTTP_HOST'])
			{
				$this->show('用户名或密码错误');
			}
			if(!$ret['token'])
			{
				$_SESSION['livmcp_userinfo'] = array();
				$this->show('获取令牌错误！');
			}
			//客户端过期检测
			if($ret['app_expire_time'] && $ret['app_expire_time'] < TIMENOW)
			{
				$_SESSION['livmcp_userinfo'] = array();
				$this->show('客户端授权到期');
			}
			if ($ret['default_page'])
			{
				if (substr($ret['default_page'], 0,7) != 'http://')
				{
					$ret['default_page'] = 'http://'.$ret['default_page'];
				}
			}
			if (is_file(CACHE_DIR . 'expire.m2o'))
			{
				if (!is_writeable(CACHE_DIR . 'expire.m2o'))
				{
					$this->ReportError('请将' . CACHE_DIR . 'expire.m2o文件权限设置为可写');
				}
				$filemtime = filemtime(CACHE_DIR . 'expire.m2o');
				if ((time() - $filemtime) > 86400)
				{
					$updateauth = true;
				}
				else
				{
					$content = file_get_contents(CACHE_DIR . 'expire.m2o');
					$license = hoge_de($content);
					$updateauth = false;
					if (!$license)
					{
						$updateauth = true;
					}
				}
			}
			else
			{
				$updateauth = true;
			}
			
			if ($updateauth)
			{
				$curl = new curl($this->settings['verify_custom_api']['host'], $this->settings['verify_custom_api']['dir']);
				$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
				$curl->setToken('');
				$curl->setErrorReturn('');
				$curl->setCurlTimeOut(10);
				$curl->mAutoInput = false;
				$curl->initPostData();
				$postdata = array(
					'useappkey'				=>	1,
				);
				foreach ($postdata as $k=>$v)
				{
					$curl->addRequestData($k, $v);
				}
				$auth = $curl->request('Authorization.php');
				if (is_array($auth))
				{
					if ($auth['ErrorCode'] == 'NO_APP_INFO')
					{
						$this->ReportError('授权非法，请联系软件提供商');
					}
					if ($auth['ErrorCode'] == 'APP_AUTH_EXPIRED')
					{
						$this->ReportError('授权已到期，请联系软件提供商');
					}
				}
				$license = hoge_de($auth);
				if ($license)
				{
					if ($license['appid'])
					{
						file_put_contents(CACHE_DIR . 'expire.m2o', $auth);
					}
				}
			}
			$timedelay = 0;
			if (intval($license['expire_time']))
			{
				$license['expire'] = date('Y-m-d', $license['expire_time']);
				$license['leftday'] = intval(($license['expire_time'] - TIMENOW) / 86400);
				if($license['expire_time'] < time())
				{
					$this->ReportError('授权已到期，请联系软件提供商' );
				}
				if ($license['leftday'] <= 30)
				{
					$license_alert = '<div style="font-size:14px;">授权将在 <strong style="color:red;">' . $license['leftday'] . '</strong> 天后到期, 为了保证您正常使用系统，请提前联系软件提供商。</div>';
					$timedelay = 5;
				}
			}
			$_SESSION['livmcp_userinfo'] = $ret;
			$user = $ret;
			/*
			if($ret['id'])
			{
				$this->curl->initPostData();
				$this->curl->addRequestData('user_id', $user['id']);
				$this->curl->addRequestData('a', 'get_dynamic_token');
				$ret = $this->curl->request('admin/set_dynamic_token.php');
				$ret = $ret[0];
				if($ret['dynamic_token'] && $this->input['dynamic_token']!=$ret['dynamic_token']);
				{
					//$_SESSION['livmcp_userinfo'] = array();
					//$this->show('该用户已经绑定手机密保，请安装手机客户端密保软件！');
				}
			}*/
		}
		else
		{
			$username = $this->input['username'];
			$password = $this->input['password'];
			if ($this->settings['admin_user'])
			{
				$users = $this->settings['admin_user'];
				if (!$users[$username] || md5($password) != $users[$username])
				{
					$this->show('用户名或密码错误');
				}
				$_SESSION['livmcp_userinfo'] = array(
					'id' => -1,
					'user_name' => 	$username,
					'group_type' => 1,
				);
			}
			else
			{
				//创建临时用户
				$this->show('用户名或密码错误');
			}
		}
		
		if ($this->input['ajax'])
		{
			$func = 'hg_dialog_close();';
		}

		if ($this->input['referto'] && strpos($this->input['referto'], '.php') && !strpos($this->input['referto'], 'login.php'))
		{
			$reffer = '?referto=' . urlencode($this->input['referto']);
		}
		
		global $gUser;
		$gUser = $user;

		include_once(ROOT_PATH . 'lib/class/log.class.php');
		$log = new hglog();
		$log->add_log('登录平台');
		$this->input['goon'] = 1;
		if ($this->input['code'] && $this->channel['id'] && $this->settings['App_interactive']['mid'][$gUser['group_type']])	//直播互动登陆
		{
			//$this->input['code'].$this->settings['App_interactive']['host'].
			$reffer = 'run.php?mid=' . $this->settings['App_interactive']['mid'][$gUser['group_type']];

			$this->Redirect('成功登录系统.' . $license_alert, $reffer, $timedelay, 0, $func);
		}
		elseif ($user['default_page'] && $user['open_way']==1)
		{
			header('Location:' . $user['default_page']);		
		}
		else
		{
			$this->Redirect('成功登录系统.' . $license_alert,  'index.php' . $reffer, $timedelay, 0, $func);
		}
	}

	public function logout()
	{
		session_start();
		include_once(ROOT_PATH . 'lib/class/log.class.php');
		$log = new hglog();
		$log->add_log('退出平台');
		if ($this->settings['App_auth'])
		{
			$this->curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
			$this->curl->initPostData();
			if($_SESSION['livmcp_userinfo'])
			{
				foreach($_SESSION['livmcp_userinfo'] as $k=>$v)
				{
					$this->curl->addRequestData($k,$v);
				}
			}
			$this->curl->addRequestData('a','logout');
			$this->curl->request('get_access_token.php');
		}
		$_SESSION['livmcp_userinfo'] = array();
		$this->Redirect('成功退出系统.');
	}
	
	public function change_pwd()
	{
		$username = $this->input['user_name'];
		$password1 = $this->input['password'];
		$password = $this->input['password_again'];
		$old_password = $this->input['old_password'];
		$admin_id = $this->input['admin_id'];
		$test = array($username,$password1,$password,$old_password,$admin_id);
		if(!trim($password1) || !trim($password) || !trim($old_password))
		{
			echo json_encode(array('error'=>1,'msg'=>'密码不能为空'));exit;
		}
		if($password1 != $password)
		{
			echo json_encode(array('error'=>1,'msg'=>'两次输入密码不一致'));exit;
		}
		
		if ($this->settings['App_auth'])
		{
			$this->curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
			$this->curl->initPostData();
			$this->curl->addRequestData('appid', APPID);
			$this->curl->addRequestData('appkey', APPKEY);
			$this->curl->addRequestData('admin_id', $admin_id);
			$this->curl->addRequestData('username', $username);
			$this->curl->addRequestData('password', $password);
			$this->curl->addRequestData('old_password', $old_password);
			$this->curl->addRequestData('a', 'change_pwd');
			$ret = $this->curl->request('get_access_token.php');
			$ret = $ret[0];
			echo json_encode($ret);exit;
		}
		
	}
	
	protected function check_api()
	{
	}
}

include (ROOT_PATH . 'lib/exec.php');
?>