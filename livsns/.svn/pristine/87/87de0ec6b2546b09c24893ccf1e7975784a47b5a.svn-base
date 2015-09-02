<?php
require('global.php');
define('MOD_UNIQUEID','cdn_domain');//模块标识
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class CdnDomainUpdateApi extends adminUpdateBase
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
		if(!$bucket_name = $this->input['bucket_name'])
		{
			$this->errorOutput('请选择空间');
		}
		$data = array(
			'bucket_name'		=> $bucket_name,
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
        
		$this->addItem('ture');
		$this->output();
	}
	
	public function update()
	{	
		/*$sql = "SELECT * FROM " . DB_PREFIX . "cdn_space WHERE id =" .$this->input['id'];
		$pre_data = $this->db->query_first($sql);
		
		$data = array(
			'id'				=> $this->input['id'],
			'bucket_name'		=> $this->input['bucket_name'],
            'type'				=> $this->input['type'],
            'quota'				=> $this->input['quota'],
			'update_time'		=> TIMENOW,
		);
		
		$re = $this->obj->update('cdn_space',$data);
		
		$sq =  "SELECT * FROM " . DB_PREFIX . "cdn_space WHERE id = " . $this->input['id'];
		$up_data = $this->db->query_first($sq);
		
		$this->addLogs('更新CDN空间' , $pre_data , $up_data , $pre_data['accountname']);
		
		$this->addItem('ture');
		$this->output();*/
	}
	
	
	
	function delete()
	{	
		
		$upyun = $this->upyun->get_upyun_access_token();

		$data  = array(
			'bucket_name'  =>	$this->input['bucket_name'],
			'domain'  		=>	$this->input['id'],
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/domains/', 'DELETE',$data);
		
		if($info['error'])
		{
			$this->errorOutput($info['error']);
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

$out = new CdnDomainUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>