<?php 
/***************************************************************************

* $Id: setting.class.php 6862 2012-05-29 07:07:58Z lijiaying $

***************************************************************************/
class setting extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['uc_api']['host'], $this->settings['uc_api']['dir']);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 获取基本设置信息
	 */
	public function getSettingInfo()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_setting');
		$this->curl->addRequestData('a', 'ls');
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);
		$ret = $this->curl->request('admin.php');
		return $ret;
	}
	/**
	 * 基本设置更新
	 */
	public function settingBasic()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_setting');
		$this->curl->addRequestData('a', 'ls');
		$this->curl->addRequestData('submitcheck', $this->settings['submitcheck']['open']);
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);

		$this->curl->addRequestData('dateformat', urldecode($this->input['dateformat']));
		$this->curl->addRequestData('timeformat', urldecode($this->input['timeformat']));
		$this->curl->addRequestData('timeoffset', urldecode($this->input['timeoffset']));
		$this->curl->addRequestData('pmsendregdays', urldecode($this->input['pmsendregdays']));
		$this->curl->addRequestData('privatepmthreadlimit', urldecode($this->input['privatepmthreadlimit']));
		$this->curl->addRequestData('chatpmthreadlimit', urldecode($this->input['chatpmthreadlimit']));
		$this->curl->addRequestData('chatpmmemberlimit', urldecode($this->input['chatpmmemberlimit']));
		$this->curl->addRequestData('pmfloodctrl', urldecode($this->input['pmfloodctrl']));
		$this->curl->addRequestData('pmcenter', urldecode($this->input['pmcenter']));
		$this->curl->addRequestData('sendpmseccode', urldecode($this->input['sendpmseccode']));
		
		$ret = $this->curl->request('admin.php');
		return $ret;
	}
	/**
	 * 注册设置更新
	 */
	public function settingRegister()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_setting');
		$this->curl->addRequestData('a', 'register');
		$this->curl->addRequestData('submitcheck', $this->settings['submitcheck']['open']);
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);

		$this->curl->addRequestData('doublee', urldecode($this->input['doublee']));
		$this->curl->addRequestData('accessemail', urldecode($this->input['accessemail']));
		$this->curl->addRequestData('censoremail', urldecode($this->input['censoremail']));
		$this->curl->addRequestData('censorusername', urldecode($this->input['censorusername']));

		$ret = $this->curl->request('admin.php');
		return $ret;
	}
	/**
	 * 邮件设置更新
	 */
	public function settingMail()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_setting');
		$this->curl->addRequestData('a', 'mail');
		$this->curl->addRequestData('submitcheck', $this->settings['submitcheck']['open']);
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);

		$this->curl->addRequestData('maildefault', urldecode($this->input['maildefault']));
		$this->curl->addRequestData('mailsend', urldecode($this->input['mailsend']));

			$this->curl->addRequestData('mailserver', urldecode($this->input['mailserver']));
			$this->curl->addRequestData('mailport', urldecode($this->input['mailport']));
			$this->curl->addRequestData('mailauth', urldecode($this->input['mailauth']));
			$this->curl->addRequestData('mailfrom', urldecode($this->input['mailfrom']));
			$this->curl->addRequestData('mailauth_username', urldecode($this->input['mailauth_username']));
			$this->curl->addRequestData('mailauth_password', urldecode($this->input['mailauth_password']));

		$this->curl->addRequestData('maildelimiter', urldecode($this->input['maildelimiter']));
		$this->curl->addRequestData('mailusername', urldecode($this->input['mailusername']));
		$this->curl->addRequestData('mailsilent', urldecode($this->input['mailsilent']));

		$ret = $this->curl->request('admin.php');
		return $ret;
	}
	/**
	 * 获取单条信息
	 */
	public function detail()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('m', 'hg_setting');
		$this->curl->addRequestData('a', 'ls');
		$this->curl->addRequestData('open', $this->settings['ucenter']['open']);
		$ret = $this->curl->request('admin.php');
		return $ret;
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= urldecode($this->input['k']);
		}
	
		return $condition;
	}
	
}
?>