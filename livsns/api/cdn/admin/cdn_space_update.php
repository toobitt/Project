<?php
require('global.php');
define('MOD_UNIQUEID','CdnSpace');//模块标识
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class CdnSpaceUpdateApi extends adminUpdateBase
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
		include(CUR_CONF_PATH . 'lib/CdnSpace.class.php');
		$this->obj = new CdnSpace();
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
			'bucket_name'		=> $this->input['bucket_name'],
            'type'				=> $this->input['type'],
            'quota'				=> $this->input['quota'],
		);
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/', 'PUT',$data);
		
		$data_ = array(
			'bucket_name'		=> $this->input['bucket_name'],
            'domain'			=> $this->input['domain'],
		);
		if($this->input['ip_tel'])
		{
			$data_['ip_tel'] = $this->input['ip_tel'];
		}
		if($this->input['ip_cnc'])
		{
			$data_['ip_cnc'] = $this->input['ip_cnc'];
		}
		
		$re = $oauth->request('/buckets/cdn/', 'POST',$data_);
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        if($re['error'])
        {
        	$this->errorOutput($re['message']);
        }
		$this->addItem('ture');
		$this->output();
	}
	
	public function update()
	{	
		if(!$bucket_name = $this->input['bucket_name'])
		{
			$this->errorOutput('请选择空间');
		}
		$data = array(
			'bucket_name'		=> $bucket_name,
            'quota'			=> $this->input['quota'],
		);
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/quota/', 'POST',$data);
		
		$data_ = array(
			'bucket_name'		=> $this->input['bucket_name'],
            'domain'			=> $this->input['domain'],
		);
		if($this->input['ip_tel'])
		{
			$data_['ip_tel'] = $this->input['ip_tel'];
		}
		if($this->input['ip_cnc'])
		{
			$data_['ip_cnc'] = $this->input['ip_cnc'];
		}
		
		$re = $oauth->request('/buckets/cdn/', 'POST',$data_);
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        
		$this->addItem('ture');
		$this->output();
	}
	
	public function add_domain()
	{
		if(!$domain_name = $this->input['id'])
		{
			$this->errorOutput('请选择空间');
		}
		$data = array(
			'bucket_name'		=> $domain_name,
            'domain'			=> $this->input['domain'],
		);
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/domains/', 'PUT',$data);
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        
		$this->addItem($info);
		$this->output();	
	}
	
	
	public function delete()
	{	
		$sql = 'SELECT * from '.DB_PREFIX.'cdn_account WHERE 1 ';
		$account = $this->db->query_first($sql);
			
		$data  = array(
			'bucket_name'  =>	$this->input['id'],
			'password'	   =>	$account['password'],
		);
		$upyun = $this->upyun->get_upyun_access_token();
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/', 'DELETE',$data);
		if($info['error_code'])
		{
			$this->errorOutput($info['message']);
		}
		
		$this->addItem('ture');
		$this->output();
		
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

$out = new CdnSpaceUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>