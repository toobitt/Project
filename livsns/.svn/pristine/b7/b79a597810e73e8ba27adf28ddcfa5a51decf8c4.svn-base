<?php
define(ROOT_DIR, '../../');
define('MOD_UNIQUEID', 'gather_login');
require(ROOT_DIR . 'global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class loginApi extends appCommonFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 登陆
	 * @param string $user_name  	用户名
	 * @param string $password  	密码	 
	 */
	public function login()
	{
		$data = array(
			'username'	=> trim($this->input['username']),
			'password'	=> trim($this->input['password']),
			'appid'		=> intval($this->input['appid']),
			'appkey'	=> trim($this->input['appkey']),
			'ip'		=> hg_getip(),
		);
		if (!$data['username'])
		{
			$this->errorOutput('用户名为空');
		}
		if (!$data['password'])
		{
			$this->errorOutput('密码为空');
		}
		//验证登陆
		if ($this->settings['App_auth'])
		{
			require_once(ROOT_PATH.'lib/class/curl.class.php');
			$this->curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', 'show');
			$this->curl->addRequestData('appid', $data['appid']);
			$this->curl->addRequestData('appkey', $data['appkey']);
			$this->curl->addRequestData('username', $data['username']);
			$this->curl->addRequestData('password', $data['password']);
			$this->curl->addRequestData('ip', $data['ip']);
			$ret = $this->curl->request('get_access_token.php');
			
			if (!$ret || $ret['ErrorCode'])
			{
				$this->errorOutput('用户验证失败');
			}
			$ret = $ret[0];
			if (!$ret['token'])
			{
				$this->errorOutput('用户验证失败');
			}
			$this->input['access_token'] = $ret['token'];
			//通过token获取用户权限
			$this->curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
			$this->curl->setSubmitType('get');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', 'get_user_info');
			$this->curl->addRequestData('access_token', $ret['token']);
			$this->curl->addRequestData('app_uniqueid', APP_UNIQUEID);
			$userInfo = $this->curl->request('get_access_token.php');
			if (!$userInfo || $userInfo['ErrorCode'] )
			{
				$this->errorOutput('获取用户权限失败');
			}
			$userInfo = $userInfo[0];
			$sort = array();
			if ($userInfo['group_type'] > MAX_ADMIN_TYPE)
			{
				if ($userInfo['prms'])
				{
					$nodes = $userInfo['prms']['app_prms'][APP_UNIQUEID]['nodes'];
					if ($nodes && !empty($nodes) && is_array($nodes))
					{
						$sortIds = implode(',', $nodes);
						//查询分类
						$sql = 'SELECT id,name FROM '.DB_PREFIX.'sort WHERE id IN ('.$sortIds.')';
						$query = $this->db->query($sql);
						while ($row = $this->db->fetch_array($query))
						{
							$sort[] = $row;
						}
					}
				}
			}
			else 
			{
				$sql = 'SELECT id,name FROM '.DB_PREFIX.'sort';
				$query = $this->db->query($sql);
				while ($row = $this->db->fetch_array($query))
				{
					$sort[] = $row;
				}	
			}
			$ret['sort'] = $sort;			
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new loginApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>