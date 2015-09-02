<?php
define('MOD_UNIQUEID','cdn_space');//模块标识
require('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class CdnSpaceApi extends adminReadBase
{
	private $btype = array(
		'file' => array('domain' => '.b0.aicdn.com', 'name' =>'文件空间'),
		'image' => array('domain' => '.b0.aicdn.com', 'name' =>'图片空间'),
		'cdn' => array('domain' => '.b0.aicdn.com', 'name' =>'静态空间'),
		'ucdn' => array('domain' => '.c1.aicdn.com', 'name' =>'动态空间'),
	);
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
		$cache_file = CACHE_DIR . 'buckets.info';
		if (!$this->input['forceflush'] && is_file($cache_file) && filemtime($cache_file) >= (time() - 300))
		{
			$bucketinfo = json_decode(file_get_contents($cache_file), 1);
			$this->addItem($bucketinfo);	
			$this->output();	
		}
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/buckets/', 'GET');
		
		if($info['error'])
        {
        	$this->errorOutput($info['message']);
        }
        else
        {
        	if($info['buckets'] && is_array($info['buckets']))
        	{
        		foreach($info['buckets'] as $key=>$val)
        		{
        			$data = array(
						'bucket_name'		=> $val['bucket_name'],
					);
					
					$domain = $oauth->request('/buckets/info/', 'GET',$data);
					if($domain['approval_domains'] && is_array($domain['approval_domains']))
					{
						$return = array();
						foreach($domain['approval_domains'] as $k=>$v)
						{
							$return[] = $v;
						}
					}
					if($return)
					{
						$info['buckets'][$key]['domain'] = implode(',',$return);
					}
					$info['buckets'][$key]['cname'] = $val['bucket_name'] . $this->btype[$val['type']]['domain'];
					$info['buckets'][$key]['type'] = $this->btype[$val['type']]['name'];
        		}
        		
        	}
        }
        file_put_contents($cache_file, json_encode($info['buckets']));
		$this->addItem($info['buckets']);	
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

$out = new CdnSpaceApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
