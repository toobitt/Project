<?php
require('global.php');
define('MOD_UNIQUEID','cdn_account');//模块标识
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class CdnAccountUpdateApi extends adminUpdateBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */

	public function __construct()
	{
		parent::__construct();
		$this->obj = new Core();
		include(CUR_CONF_PATH . 'lib/CdnAccount.class.php');
		$this->obj = new CdnAccount();
		include(CUR_CONF_PATH . 'lib/UpYun.class.php');
		$this->upyun = new UpYun();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{	
		$data = array(
			'username'			=> $this->input['username'],
            'password'			=> $this->input['password'],
            'email'				=> $this->input['email'],
            'account_type'		=> $this->input['account_type'],
            'company_name'		=> $this->input['company_name'],
		 	'real_name'			=> $this->input['realname'],
		 	'mobile'			=> $this->input['mobile'],
		 	'im'				=> $this->input['im'],
		 	'website'			=> $this->input['website'],
		 	
		 	'client_id'			=> OAUTH_CLIENT_ID,
		  	'client_secret'		=> OAUTH_CLIENT_SECRET,
		);
		
		if(!defined('OAUTH_CLIENT_ID') || !OAUTH_CLIENT_ID || !defined('OAUTH_CLIENT_SECRET') || !OAUTH_CLIENT_SECRET)
		{
			$this->errorOutput('请配置 OAUTH_CLIENT_ID 和 OAUTH_CLIENT_SECRET');
		}
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
		$info = $oauth->request('/accounts/', 'PUT', $data);

		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        else
        {
        	unset($data['client_id']);
        	unset($data['client_secret']);
       		$account_id = $this->obj->create('cdn_account',$data);
        }
		
		$this->addItem(1);
		$this->output();
	}
	
	public function update()
	{	
		if(!defined('OAUTH_CLIENT_ID') || !OAUTH_CLIENT_ID || !defined('OAUTH_CLIENT_SECRET') || !OAUTH_CLIENT_SECRET)
		{
			$this->errorOutput('请配置 OAUTH_CLIENT_ID 和 OAUTH_CLIENT_SECRET');
		}
	
		$upyun = $this->upyun->get_upyun_access_token();
		$data = array(
			'accountname'		=> $this->input['accountname'],
            'password'			=> $this->input['password'],
            'email'				=> $this->input['email'],
            'account_type'		=> $this->input['account_type'],
            'company_name'		=> $this->input['company_name'],
		 	'realname'			=> $this->input['realname'],
		 	'mobile'			=> $this->input['mobile'],
		 	'im'				=> $this->input['im'],
		 	'website'			=> $this->input['website'],
			'access_token'		=> $upyun['access_token'],
		);
		$re   = $this->update_upyun_real_name($data);
		$ret  = $this->update_upyun_mobile($data);
		$retu = $this->update_upyun_email($data);
		if($re['error'])
        {
        	$this->errorOutput($re['error']);
        }
        if($ret['error'])
        {
        	$this->errorOutput($ret['error']);
        }
        if($retu['error'])
        {
        	$this->errorOutput($retu['error']);
        }
		
		unset($data['accountname']);
        unset($data['access_token']);
        $data['real_name'] = $data['realname'];
        unset($data['realname']);
		$this->obj->update('cdn_account',$data);
		
		$this->addItem('ture');
		$this->output();
	}
	
	
	/*function delete()
	{	
		
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的帐号");
		}
		
		$sqll =  "SELECT * FROM " . DB_PREFIX . "cdn_account WHERE id IN (" . $ids . ")";
		$sll = $this->db->query($sqll);
		$ret = array();
		while($rowl = $this->db->fetch_array($sll))
		{
			$pre_data[] = $rowl;
		}
		
		$ret = $this->obj->delete('cdn_account'," where id IN (" . $ids . ")");
		if($ret)
		{
			$this->addLogs('删除帐号' , $pre_data , '', '删除帐号'.$ids);
		}
		
		$this->addItem($ret);
		$this->output();
		
	}*/
	
	public function update_upyun_real_name($data)
	{
        $data_ = array(
			'realname ' 		=>	$data['realname'],
			'company_name' 		=>	$data['company_name'],
			'im' 				=>	$data['im'],
			'website' 			=>	$data['website'],
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($data['access_token']);
		$info = $oauth->request('/accounts/', 'POST', $data_);
		
		return $info;
	}
	
	public function update_upyun_mobile($data)
	{
        $data_ = array(
			'password' 			=>	$data['password'],
			'mobile' 			=>	$data['mobile'],
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($data['access_token']);
		$info = $oauth->request('/accounts/mobile/', 'POST', $data_);
		
		return $info;
	}
	
	public function update_upyun_email($data)
	{
		$upyun = $this->upyun->get_upyun_access_token();
	
        $data_ = array(
			'password' 			=>	$data['password'],
			'email' 			=>	$data['email'],
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($data['access_token']);
		$info = $oauth->request('/accounts/email/', 'POST', $data_);
		
		return $info;
	}
	
	public function audit()
	{
	}
	public function sort()
	{
	}
	public function publish()
	{
	}
	
	public function delete()
	{
	}
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new CdnAccountUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>