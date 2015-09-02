<?php
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');
require(CUR_CONF_PATH . 'lib/MibaoCard.class.php');
class configuare extends configuareFrm
{	
	private $mibaoInfo;
	private $isOpenMibao = false;
	function __construct()
	{
		parent::__construct();
		$this->verifyToken();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	protected function register_client($db, $link)
	{
		$app = parent::register_client();
		$sql  = " INSERT INTO ".$db['dbprefix']."appinfo SET ";
		$sql .= " custom_name = 'LivMCP网页版',".
		 		" custom_desc = '网页版后台授权',".
		 		" bundle_id = 'livmcp',".
		 		" display_name = 'LivMCP网页版',".
		 		" expire_time = '0',".
		 		" appkey = '".$app['appkey']."',".
		 		" create_time = '".TIMENOW."',".
		 		" update_time = '".TIMENOW."',".
		 		" status = 1, " . 
		 		" order_id = 1 ";
		$q = mysql_query($sql, $link);
		$appid = mysql_insert_id($link);
		$app['appid'] = $appid;
		return $app;
	}
	
	//此处重写父类方法为了获取当前用户的信息
	protected function verifyToken()
	{
		$gAuthServerConfig = $this->settings['App_auth'];
		if(!$gAuthServerConfig) //未配置授权
		{
			$this->user = array(
				'user_id'		=>$this->input['user_id'],
				'user_name'		=> $this->input['user_name'],
				'group_type'	=>1,//超级用户
				'appid'			=>$this->input['appid'],
				'display_name'	=>$this->input['user_name'],
				'visit_client'	=>0,
			);
			return;
		}
		if(!class_exists('curl'))
		{
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
		}
		$curl = new curl($gAuthServerConfig['host'], $gAuthServerConfig['dir']);
		$curl->initPostData();
		$postdata = array(
			'appid'			=>	$this->input['appid'],
			'appkey'		=>	$this->input['appkey'],
			'access_token'	=>	$this->input['access_token'],
			'mod_uniqueid'	=> 	MOD_UNIQUEID,
			'app_uniqueid'	=>	APP_UNIQUEID,
			'a'				=>	'get_user_info',
		);
		foreach ($postdata as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		$ret = $curl->request('get_access_token.php');
		//判定终端是否需要登录授权
		if($ret['ErrorCode'])
		{
			$this->errorOutput($ret['ErrorCode']);
		}
		$this->user = $ret[0];
		
		if ($this->input['m2o_ckey'] == CUSTOM_APPKEY)
		{
			$this->user['group_type'] = 1;
		}
	}
	function settings_process()
	{
		if($this->input['define']['TOKEN_EXPIRED'] <= 0)
		{
			$this->input['define']['TOKEN_EXPIRED'] = 7200;
		}
	}
	//更改配置预处理（密保卡）
	protected function settings_process_with_mibao()
	{
		$open = $this->input['base']['mibao']['open'];
		//如果开启了密保就去下载当前用户的密保卡
		if($open)
		{
			if(!$this->user)
			{
				$this->errorOutput('非法用户,禁止更改配置');
			}
			$mibao = new MibaoCard();
			$mibaoInfo = $mibao->get_mibao_info($this->user['user_id']);
			//如果已经绑定了密保就根据密保信息生成密保图片
			if($mibaoInfo)
			{
				$this->mibaoInfo = $mibaoInfo;
			}
			else 
			{
				//没有绑定就绑定
				$this->mibaoInfo = $mibao->bind_card($this->user['user_id']);
			}
			$this->isOpenMibao = true;
		}
		else 
		{
			$this->input['base']['mibao']['open'] = 0;
		}
	}
	
	//重写修改配置操作
	public function doset()
	{
		$this->settings_process_with_mibao();
		$this->addItem_withkey('mibaoInfo', $this->mibaoInfo);
		$this->addItem_withkey('isOpenMibao', $this->isOpenMibao);
		parent::doset();
	}
}
$module = 'configuare';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>