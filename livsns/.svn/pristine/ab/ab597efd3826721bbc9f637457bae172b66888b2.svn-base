<?php
define('MOD_UNIQUEID','cdn_account');//模块标识
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class CdnAccountApi extends adminReadBase
{
	public function __construct()
	{
	
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/CdnAccount.class.php');
		$this->obj = new CdnAccount();
		$this->core = new Core();
		include(CUR_CONF_PATH . 'lib/UpYun.class.php');
		$this->upyun = new UpYun();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  show()
	{	
		
		$upyun = $this->upyun->get_upyun_access_token();
	
       	$data = $dat = array();
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/accounts/profile/', 'GET', $data);
		if($info['error'])
        {
        	$info = array();
        }
        else
        {
        	$sql = 'SELECT * from '.DB_PREFIX.'cdn_account WHERE 1 ';
			$account = $this->db->query_first($sql);
        	if(defined('UpYun_Username') && UpYun_Username && defined('UpYun_Password') && UpYun_Password)
        	{
        		$info['username'] = UpYun_Username;
        		$info['password'] = UpYun_Password;
        	}
        	else
        	{
        		$info['username'] = $account['username'];
        		$info['password'] = $account['password'];
        	}
        	
			if(!$account)
			{
				$dat = array(
				  	'username'		 => $info['username'],
				  	'real_name' 	 => $info['realname'],
				  	'password' 		 => $info['password'],
				  	'account_type'	 => $info['account_type'],
				  	'company_name' 	 => $info['company_name'],
				  	'email' 		 => $info['email'],
				  	'mobile'		 => $info['mobile'],
				  	'im' 			 => $info['im'],
				  	'website' 		 => $info['website'],
				);
					
				$account_id = $this->obj->create('cdn_account',$dat);
			}
			
        }
        
		$this->addItem($info);
		$this->output();	
	}

	public function detail()
	{	
		/*$sql = 'SELECT *
				FROM '.DB_PREFIX.'cdn_account WHERE id = '.$this->input['id'];
		$ret = $this->db->query_first($sql);
		
		$this->addItem($ret);
		$this->output();*/
	}
	
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		echo json_encode(array('total' => 0));	
		exit;
		/*$sql = 'SELECT count(*) as total from '.DB_PREFIX.'cdn_account WHERE 1 '.$this->get_condition();
		$templates_total = $this->db->query_first($sql);
		echo json_encode($templates_total);	*/
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function get_condition()
	{		
		$condition = '';
		//查询应用分组
		return $condition;
	}
	
	
	public function index()
	{	
	}
}

$out = new CdnAccountApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
