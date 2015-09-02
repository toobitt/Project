<?php
define('MOD_UNIQUEID','cdn_log');//模块标识
require('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class CdnLogApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->obj = new Core();
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
		);
		if($this->input['type'] && $this->input['type'] !='-1')
		{
			$data['type'] = $this->input['type'];
		}
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
		$info = $oauth->request('/logs/', 'GET',$data);
		
		$return = $info['result']['data'];
		if($return && is_array($return))
		{
			foreach($return as $k=>$v)
			{
				if($v['type'])
				{
					switch($v['type'])
					{
						case 'auth':
							$type = '登陆';
						break;
						case 'account':
							$type = '帐号';
						break;
						case 'bucket':
							$type = '空间';
						break;
						case 'operator':
							$type = '操作员';
						break;
						case 'file':
							$type = '文件';
						break;
						default:
							break;
					}
				}
				$row['name'] = $v['log'];
				$row['type'] = $type;
				$row['ip'] = $v['ip'];
				$row['create_time'] = date("Y-m-d H:i:s",$v['created_at']);
				$re[] = $row;
			}
		}
		$this->addItem($re);	
		$this->output();		
	}

	public function detail()
	{	
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
	public function count($count ='')
	{	
		echo json_encode(array('total' => 0));	
		exit;
		$re = array();
		$re['total'] = $count;
		//echo json_encode($re);	
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

$out = new CdnLogApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
