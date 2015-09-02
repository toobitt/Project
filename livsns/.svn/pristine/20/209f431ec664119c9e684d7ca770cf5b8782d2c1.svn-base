<?php
/*******************************************************************
 * filename :CDN.php
 * Created  :2013年8月9日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'UpYun'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class UpYunAPi extends  adminReadBase
{
	private $obj=null;
	public function __construct()
	{
		parent::__construct();
		$this->obj = new Core();
		include(CUR_CONF_PATH . 'lib/UpYun.class.php');
		$this->upyun = new UpYun();
		
	}
	public function detail()
	{
	}
	public function show()
	{
	}
	public function count()
	{
	}
	public function index()
	{

	}
	private function get_condition()
	{
		
		//$cond = " where 1 and state=0";
		$cond = " where 1 ";
		return $cond;
	}
	
	/*public function register_upyun_user()
	{
		if($this->input['id'])
		{
			$sq = "select * from " . DB_PREFIX . "cdn_account  where id = ".$this->input['id'];
			$account = $this->db->query_first($sq);
			$data = array(
				'username' 			=>	$account['accountname'],
				'password' 			=>	$account['password'],
				'email' 			=>	$account['email'],
				'account_type' 		=>	$account['account_type'],
				'real_name' 		=>	$account['realname'],
				'company_name' 		=>	$account['company_name'],
				'mobile' 			=>	$account['mobile'],
				'client_id' 		=>	OAUTH_CLIENT_ID,
				'client_secret' 	=>	OAUTH_CLIENT_SECRET,
			);
		}
		else
		{
			$data = array(
				'username' 			=>	UpYun_Username,
				'password' 			=>	UpYun_Password,
				'email' 			=>	UpYun_Email,
				'account_type' 		=>	UpYun_AccountType,
				'real_name' 		=>	UpYun_RealName,
				'company_name' 		=>	UpYun_CompanyName,
				'mobile' 			=>	UpYun_Mobile,
				'client_id' 		=>	OAUTH_CLIENT_ID,
				'client_secret' 	=>	OAUTH_CLIENT_SECRET,
			);
		}
       	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
		$info = $oauth->request('/accounts/', 'PUT', $data);
		
		if($this->input['id'])
        {
        	if(!$info['error'] || $info['message'] == 'Username has exists.')
        	{
        		$sql = "UPDATE " . DB_PREFIX ."cdn_account SET register =1 WHERE id = ".$this->input['id'];
				$this->db->query($sql);
        	}
        }
        
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
     
		$this->addItem('ture');
		$this->output();	
	}
	
	public function make_upyun_access_toke()
	{
		$return  = $this->upyun->make_upyun_access_token();
		
		$this->addItem($return);
		$this->output();
	}
	
	
	public function get_upyun_access_token()
	{
		$return  = $this->upyun->get_upyun_access_token();
		
		$this->addItem($return);
		$this->output();
	}
	
	public function get_upyun_user_info()
	{
		$upyun = $this->upyun->get_upyun_access_token();
	
       	$data = array(
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/accounts/profile/', 'GET', $data);
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        
		$this->addItem($info);
		$this->output();	
	}
	
	public function update_upyun_real_name()
	{
		$upyun = $this->upyun->get_upyun_access_token();
	
        $data = array(
			'realname ' 		=>	UpYun_RealName,
			'company_name' 		=>	UpYun_CompanyName,
			'im' 				=>	UpYun_Im,
			'website' 			=>	UpYun_Website,
		);
		//file_put_contents('012',var_export($data,1));
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/accounts/', 'POST', $data);
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        
		$this->addItem($info);
		$this->output();	
	}
	
	public function update_upyun_mobile()
	{
		$upyun = $this->upyun->get_upyun_access_token();
	
        $data = array(
			'password' 			=>	UpYun_Password,
			'mobile' 			=>	UpYun_Mobile,
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/accounts/mobile/', 'POST', $data);
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        
		$this->addItem($info);
		$this->output();	
	}
	
	public function update_upyun_email()
	{
		$upyun = $this->upyun->get_upyun_access_token();
	
        $data = array(
			'password' 			=>	UpYun_Password,
			'email' 			=>	UpYun_Email,
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/accounts/email/', 'POST', $data);
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        
		$this->addItem($info);
		$this->output();	
	}
	
	public function get_upyun_buckets()
	{
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/', 'GET');
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        
		$this->addItem($info);
		$this->output();	
	}
	
	public function create_bucket()
	{
		if($this->input['id'])
		{
			$sq = "select * from " . DB_PREFIX . "cdn_space  where id = ".$this->input['id'];
			$account = $this->db->query_first($sq);
			$data = array(
				'bucket_name' 		=>	$account['bucket_name'],
				'type' 				=>	$account['type'],
				'quota' 			=>	$account['quota'],
			);
		}
		else
		{
			$this->errorOutput("请输入需要创建cdn空间id");
		}
		
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/', 'PUT',$data);
		
		if($this->input['id'] || $info['message'] == 'Bucket already exists.')
        {
        	if(!$info['error'])
        	{
        		$sql = "UPDATE " . DB_PREFIX ."cdn_space SET is_create =1 WHERE id = ".$this->input['id'];
				$this->db->query($sql);
        	}
        }
        
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        
		$this->addItem($info);
		$this->output();	
	}
	
	
	public function get_upyun_buckets_info()
	{
		$id = urldecode($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput("请选择需要删除的cdn空间id");
		}
		
		$sqll =  "SELECT * FROM " . DB_PREFIX . "cdn_space WHERE id  =" .$id;
		$sll = $this->db->query_first($sqll);
		
		$upyun = $this->upyun->get_upyun_access_token();
		$data  = array(
			'bucket_name'	=>	$sll['bucket_name'],
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/info/', 'GET',$data);
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        
		$this->addItem($info);
		$this->output();	
	}
	
	public function delete_upyun_bucket()
	{	
		
		$id = urldecode($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput("请选择需要删除的cdn空间id");
		}
		
		$sqll =  "SELECT * FROM " . DB_PREFIX . "cdn_space WHERE id  =" .$id;
		$sll = $this->db->query_first($sqll);
		$upyun = $this->upyun->get_upyun_access_token();

		$data  = array(
			'bucket_name'  =>	$sll['bucket_name'],
		);
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/', 'DELETE', $data);
		if($info['error_code'])
		{
			$this->errorOutput($info['message']);
		}
			
		$ret = $this->obj->delete('cdn_space'," where id =  " .$id);
		
		
		$this->addItem($ret);
		$this->output();
		
	}*/
	
}

$out = new UpYunAPi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'get_upyun_access_token';
}
$out-> $action ();


?>