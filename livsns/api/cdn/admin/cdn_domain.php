<?php
define('MOD_UNIQUEID','cdn_domain');//模块标识
require('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class CdnDomainApi extends adminReadBase
{
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
	
	public function  show() 
	{	
		$data = array(
			'bucket_name'		=> $this->input['bucket_name'],
		);
		
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/info/', 'GET',$data);
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
		if($info['approval_domains'] && is_array($info['approval_domains']))
		{
			foreach($info['approval_domains'] as $k=>$v)
			{
				$return[] = array(
					'domain'    => $v,
					'status'	=> '已审核'
				);
			}
		}
		if($info['approvaling_domains'] && is_array($info['approvaling_domains']))
		{
			foreach($info['approvaling_domains'] as $k=>$v)
			{
				$return[] = array(
					'domain'    => $v,
					'status'	=> '等待备案审核中'
				);
			}
		}
		
		$return['bucket_name']	 = $this->input['bucket_name'];
		$this->addItem($return);	
		$this->output();		
	}

	public function detail()
	{	
		$data = array(
			'bucket_name'		=> $this->input['id'],
		);
		
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/info/', 'GET',$data);
		
		$re = array();
		$re = array(
			'bucket_name'		=> $info['bucket_name'],
			'type'				=> $info['type'],
		);
		$typeinfo = $info[$info['type']];
		
		$re['domain'] = $typeinfo['domain'];
		$re['ip_tel'] = $typeinfo['ip_tel'];
		$re['ip_cnc'] = $typeinfo['ip_cnc'];
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
		
		$this->addItem($re);
		$this->output();
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'cdn_space WHERE 1 '.$this->get_condition();
		$templates_total = $this->db->query_first($sql);
		echo json_encode($templates_total);	
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

$out = new CdnDomainApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
