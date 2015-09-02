<?php
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','auth');
define(WITH_LOGIN, False);
define(WITHOUT_DB, true);
define('SCRIPT_NAME', 'get_access_token');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH . 'lib/functions.php');
require(CUR_CONF_PATH . 'lib/extendInfo.class.php');
class get_access_token extends outerReadBase
{

	private $iscp = false;
	private $dbname = '';
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
    private function post($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
		$head_info = curl_getinfo($ch);
        curl_close($ch);
        return json_decode($ret, true);
    }
	private function mk_access_token($servindex = 0)
	{
		$pos = 8;
		$len = strlen($servindex);
		if ($len == 10)
		{
			$len = 'A';
		}
		$accesstoken = md5($userid . '_' . $servindex . microtime() . mt_rand());
		$accesstoken = substr($accesstoken, 0, $pos) . $len . $servindex . substr($accesstoken, $pos + $len + 1);
		return $accesstoken;
	}

	private function get_serv_index($accesstoken)
	{
		$pos = 8;
		$len = substr($accesstoken, $pos, 1);
		if ($len == 'A')
		{
			$len = 10;
		}
		elseif (! ($len >= '0' && $len < '10'))
		{
			$this->erroroutput(TOKEN_ILLEGAL);
		}
		$servindex = substr($accesstoken, $pos + 1, $len);
		return $servindex;
	}

	private function getDB($token)
	{
		if (!$token)
		{
			return hg_ConnectDB();
		}
		$servindex = $this->get_serv_index($token);
		$servindex = $servindex - 1;
		if ($servindex == -1)
		{
			$ServDB = hg_ConnectDB();
			$this->iscp = true;
		}
		else
		{
			$this->iscp = false;
			$servers = hg_load_login_serv();
			if (!$servers)
			{
				$ServDB = hg_ConnectDB();
			}
			else
			{
				$server = $servers[$servindex];
				if ($server)
				{
					include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
					$ServDB = new db();
					$server['pass'] = hg_encript_str($server['pass'], false);
					$ServDB->connect($server['host'], $server['user'], $server['pass'], $server['database'], $server['charset'], $server['pconnect']);
					$this->dbname = $server['database'] . '.';
				}
				else
				{
					$ServDB = hg_ConnectDB();
				}
			}
		}
		return $ServDB;
	}

	public function show()
	{
		$callback = urldecode(trim($this->input['verify_user_cb']));
		$time_expired = defined('TOKEN_EXPIRED') ? intval(TOKEN_EXPIRED + TIMENOW) : intval(3600+TIMENOW);
		if (!$callback)
		{
			$this->db = hg_ConnectDB();
			$userinfo = $this->mcp_dologin();
			if(!$userinfo)
			{
				$userinfo = $this->member_dologin();
				if(!$userinfo)
				{
					$this->errorOutput(USER_LOGIN_ERROR);
				}
				$group_type = 9999999999;
			}
			else
			{
				if($userinfo['forced_change_pwd'])
				{
					$this->addItem($userinfo);
					$this->output();
				}
				//5游客
				$group_type = intval(min(explode(',', $userinfo['admin_role_id'])));
				if(!$group_type)
				{
					$group_type = 9999999999;
				}
			}
			
			$appinfo = $this->verify_appkey($this->input['appid'], $this->input['appkey']);

			$accesstoken = $this->mk_access_token(0);
			//入user_login表数据
			$data = array(
				'ip'			=>hg_getip(),
				'user_name'		=>urldecode($userinfo['user_name']),
				'user_id'		=>intval($userinfo['id']),
				'login_time'	=>TIMENOW,
				'token'			=> $accesstoken,
				'appid'			=>intval($appinfo['appid']),
				'group_type'	=>$group_type,
				'display_name'	=>$appinfo['display_name'] ? $appinfo['display_name'] : $appinfo['custom_name'],
				'visit_client'	=>$appinfo['mobile'],
				'org_id'		=>$userinfo['org_id'],
				'slave_group'	=>$userinfo['admin_role_id'],
				'slave_org'		=>$userinfo['childs'],
                'is_member'     =>$userinfo['is_member'],  //区分前台会员用户和后台用户
			); 
			$sql = 'INSERT INTO '.DB_PREFIX.'user_login SET ';
			foreach($data as $field=>$value)
			{
				$sql .= "{$field} = '{$value}',";
			}
			$this->db->query(trim($sql, ','));
			//获取用户头像
			$avatar = unserialize($userinfo['avatar']) ? unserialize($userinfo['avatar'])  : '';
			//登陆返回的数据 纪录session
			$reUserInfo = array(
				'token'				=>$data['token'],
				'appid'				=>$data['appid'],
				'display_name'		=>$data['display_name'],
				'app_expire_time'	=>$appinfo['expire_time'],
				'user_name'			=>$userinfo['user_name'],
				'group_type'		=>$group_type,
				'password'			=>$userinfo['password'],
				'id'				=>$userinfo['id'],
				'verify_code'		=>$userinfo['verify_code'],
				//'menu'			=>$userinfo['menu'],
				'visit_client'		=>$appinfo['mobile'],
				'group_name'		=>$userinfo['role_name'],
				'org_name'			=>$userinfo['org_name'],
				'org_id'			=>$userinfo['org_id'],
				'slave_org'			=>$userinfo['childs'],
				'avatar'			=>$avatar,
				'default_page'		=>$userinfo['index_page'],
				'open_way'			=>$userinfo['open_way'],
				'domain'			=>$userinfo['domain'],
				'app_custom_menus' 	=>$userinfo['app_unique'],
				'cardid' 			=>$userinfo['cardid'],
				'prms_menus'		=>$userinfo['prms_menus'],
				'expired_time'		=>$time_expired,
                'is_member'         => $data['is_member'],
			);
			if($reUserInfo['id']>0&&($this->input['isextend']||defined('IS_EXTEND')&&IS_EXTEND))
			{
				$reUserInfo['extend'] = $this->getUserExtendInfo($reUserInfo['id']);
			}
			$this->addItem($reUserInfo);
			//$token_expired = defined('TOKEN_EXPIRED') ? TOKEN_EXPIRED : 3600;
			$this->output();
		}
		else
		{
			$extend = urldecode($this->input['extend']);
			$user = array(
				'user_name' => $this->input['user_name'],
				'password' => $this->input['password']
			);
			$extend = explode('&', $extend);
			foreach ($extend AS $v)
			{
				$v = explode('=', $v);
				if ($v[0])
				{
					$user[$v[0]] = $v[1];
				}
			}
			$userinfo = $this->post(urldecode($callback),$user);
			$userinfo = $userinfo[0];
			if(!$userinfo['user_id'])
			{
				$this->erroroutput(USER_VERIFY_FAIL);
			}
			$servers = hg_load_login_serv();
			if (!$servers)
			{				
				$server_index = -1;
				$this->db = hg_ConnectDB();
			}
			else
			{
				include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
				$server_index = $userinfo['user_id'] % count($servers);
				$server = $servers[$server_index];
				$server['pass'] = hg_encript_str($server['pass'], false);
				$this->db = new db();
				$conn = $this->db->connect($server['host'], $server['user'], $server['pass'], $server['database'], $server['charset'], $server['pconnect']);
				if (!$conn)
				{
					$this->erroroutput(LOGIN_SERVER_ERROR);
				}
				$server['database'] = $server['database'] . '.';
			}			
			$appinfo = $this->verify_appkey($this->input['appid'], $this->input['appkey']);			
			
			$group_type = 999999999;
			$accesstoken = $this->mk_access_token($server_index + 1);			
			$data = array(
				'ip'			=>hg_getip(),
				'user_name'		=>urldecode($userinfo['user_name']),
				'user_id'		=>intval($userinfo['user_id']),
				'login_time'	=>TIMENOW,
				'token'			=> $accesstoken,
				'appid'			=>intval($appinfo['appid']),
				'group_type'	=>$group_type,
				'display_name'	=>$appinfo['display_name'] ? $appinfo['display_name'] : $appinfo['custom_name'],
				'visit_client'	=>$appinfo['mobile'],
				'org_id'		=>$userinfo['org_id'],
				'slave_group'	=>$userinfo['admin_role_id'],
				'slave_org'		=>$userinfo['childs'],
                'is_member'     =>1,  //区分前台会员用户和后台用户
			); 
			$sql = 'INSERT INTO ' . $server['database'] . DB_PREFIX . 'user_login SET ';
			foreach($data as $field=>$value)
			{
				$sql .= "{$field} = '{$value}',";
			}
			$this->db->query(trim($sql, ','));
			$userinfo['token'] = $accesstoken;
			$userinfo['appid'] = $data['appid'];
			$userinfo['display_name'] = $data['display_name'];
			$userinfo['visit_client'] = $data['visit_client'];
			$userinfo['login_time'] = $data['login_time'];
			$userinfo['expired_time'] = $time_expired;
            $userinfo['is_member'] = $data['is_member'];
			if($userinfo['user_id']>0&&($this->input['isextend']||defined('IS_EXTEND')&&IS_EXTEND))
			{
				$userinfo['extend'] = $this->getUserExtendInfo($userinfo['user_id']);
			}
			$this->addItem($userinfo);
			$this->output();
		}

	}
	//mcp用户登陆
	private function mcp_dologin()
	{
		if(!$this->input['username'])
		{
			return false;
		}
		$discuz = $this->input['discuz'];
		if(!$this->input['password'] && !$discuz)
		{
			return false;
		}
		$sql = 'SELECT a.*,org.name org_name,org.id org_id,org.childs,role.name role_name,role.index_page index_page,role.open_way open_way,role.domain as rdomain,role.publish_prms,role.site_prms FROM ' . DB_PREFIX . 'admin a LEFT JOIN '.DB_PREFIX.'admin_role role ON a.admin_role_id=role.id LEFT JOIN '.DB_PREFIX.'admin_org org ON org.id=a.father_org_id
				WHERE a.user_name="' . $this->input['username'] . '"';
		$user = $this->db->query_first($sql);
		if (!$user)
		{
			return false;
		}
		/**********取已授权的应用标志和模块标志用于控制菜单显示************/
		if($user['id'] && $user['admin_role_id'] > MAX_ADMIN_TYPE)
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'role_prms  WHERE admin_role_id IN('.$user['admin_role_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				if($row['func_prms'])
				{
					if($row['setting_prms']>=$user['prms_menus'][$row['app_uniqueid']])
					{
						$user['prms_menus'][$row['app_uniqueid']] = $row['setting_prms'];
					}
				}
			}
		}
		/**********取已授权的应用标志和模块标志用于控制菜单显示************/
		$user['domain'] = $user['domain'] ? $user['domain'] : $user['rdomain'];
		unset($user['rdomain']);
		if(!$discuz)
		{
			$password = $this->input['password'];
			$encrypt_num  = intval($this->input['encrypt_num']);
			if ($encrypt_num == 1)
			{
				$password = md5($password . $user['salt']);
			}
			else
			{
				$password = md5(md5($password) . $user['salt']);
			}
			if ($password != $user['password'])
			{
				return false;
			}	
		}
		//验证密保卡(在密保打开的情况下验证，如果用户没有绑定密保也报错)
		if($this->settings['mibao']['open'])
		{
			if(!$user['cardid'])
			{
				return false;
			}
			
			if(!$this->verify_mibao($user['cardid'],$this->input['security_zuo'],$this->input['secret_value']))
			{
				return false;
			}
		}
		//用户是否开启了"强制修改密码"
		if($user['forced_change_pwd'])
		{
			$user['forced_change_pwd'] = 1;
		}
		return $user;
	}
	private function member_dologin()
	{
		$userinfo = array(
		'id'=>$this->input['id'],
		'user_name'=>urldecode($this->input['user_name']),
		//会员角色默认4
		'admin_role_id'=> 4,
        'is_member'    => 1,   //会员用户 is_member = 1
		);
		if(!$userinfo['id'] || !$userinfo['user_name'])
		{
			$this->errorOutput(USER_LOGIN_ERROR);
		}
		return $userinfo;
	}

	private function verify_appkey($appid, $appkey)
	{
		if ($this->settings['auth_setting']['open_encript'])
		{
			//解密获取appkey
			$appid = $appid;
			$appkey = $appkey;
		}
		$sql = 'SELECT * FROM ' . $this->dbname . DB_PREFIX.'authinfo WHERE appid = '.intval($appid).' AND appkey="'.$appkey.'"';
		$appinfo = $this->db->query_first($sql);
		if(!$appinfo)
		{
			$this->erroroutput(NO_APP_INFO);
		}
		if ($this->settings['auth_setting']['open_audit'] && !$appinfo['status'])
		{
			$this->erroroutput(APP_NOT_AUDIT);
		}
		if($appinfo['expire_time'] && $appinfo['expire_time']<TIMENOW)
		{
			$this->erroroutput(APP_AUTH_EXPIRED);
		}
		if ($appinfo['is_auth'])
		{
			$this->erroroutput(USER_NOT_LOGIN);
		}
		return $appinfo;
	}

	public function logout()
	{
		$this->initUserInfo();
		$data = $this->user;
		$data['logout'] = 0;
		$ServDB = $this->getDB($this->user['token']);
		$sql = 'DELETE FROM ' . $this->dbname . DB_PREFIX . 'user_login WHERE token = "'.$this->user['token'].'"';
		if($ServDB->query($sql))
		{
			$data['logout'] = 1;
		}
		$this->addItem($data);
		$this->output();
	}
	public function get_user_info()
	{
		$this->db = $this->getDB($this->input['access_token']);
		if($this->input['access_token'])
		{
			$sql = 'SELECT * FROM ' . $this->dbname . DB_PREFIX.'user_login WHERE token = "' . $this->input['access_token'] . '"';
			$user = $this->db->query_first($sql);
			if(!$user)
			{
				$this->erroroutput(NO_ACCESS_TOKEN);
			}
			$token_expired = defined('TOKEN_EXPIRED') ? TOKEN_EXPIRED : 3600;
			if($user['login_time'] < TIMENOW - $token_expired)
			{
				$sql = 'UPDATE ' . $this->dbname . DB_PREFIX.'user_login SET login_time = '.TIMENOW.' WHERE token="'.$this->input['access_token'].'"';
				$this->db->query($sql);
				//删除过期会话
				$sql = 'DELETE FROM ' . $this->dbname . DB_PREFIX.'user_login WHERE login_time < '.(TIMENOW-$token_expired);
				$this->db->query($sql);
			}
			
			if($user['user_id'] && $this->iscp)
			{
				//$user['slave_org'] = explode(',', $user['slave_org']);
				//非管理型用户加在权限
				if($user['group_type'] > MAX_ADMIN_TYPE && $user['slave_group'])
				{
					$app_uniqueid = $this->input['app_uniqueid'];
					$complex = hg_check_prms($user);
					//合并多角色用户权限
					$user['prms'] = merge_user_prms($complex);
				}
			}
		}
		if(!$user)
		{
			if($this->input['appid'] && $this->input['appkey'])
			{
				$appinfo = $this->verify_appkey($this->input['appid'], $this->input['appkey']);
				$user_name= $appinfo['display_name'] ? $appinfo['display_name'] : $appinfo['custom_name'];
				$user = array(
					'user_id'		=>0,
					'user_name'		=>$user_name,
					'group_type'	=>9999999999,
					'appid'			=>$appinfo['appid'],
					'display_name'	=>$user_name,
					'visit_client'	=>$appinfo['mobile'],
					'expire_time'	=>$appinfo['expire_time'],
				);
			}
			else
			{
				if (!DEBUG_MODE)
				{
					$this->erroroutput(NO_APP_INFO);
				}
			}
		}
		
		//判断当期那用户表里面的字段(是否是第一次登陆,如果是就提示必须修改密码
		/********************************
		if($user['is_first_login'])
		{
			$this->errorOutput(YOU_MUST_MODIFY_PASSWORD_FIRST);
		}
		********************************/	
			

		
		
		
		if($user['user_id']>0&&($this->input['isextend']||defined('IS_EXTEND')&&IS_EXTEND))
		{
			$user['extend'] = $this->getUserExtendInfo($user['user_id']);
			$sql = 'SELECT avatar FROM '  . DB_PREFIX . 'admin WHERE id = '.$user['user_id'];
			$avatar = $this->db->query_first($sql);
			if($avatar = unserialize($avatar['avatar']))
			{
				$user['avatar'] = $avatar;
			}
		}
		$this->addItem($user);
		$this->output();
	}
	//验证密保卡
	public function verify_mibao($cardid,$security_zuo,$secret_value)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."security_card WHERE id = '".$cardid."'";
		$cards = $this->db->query_first($sql);
		$cardsinfo = unserialize($cards['zuobiao']);
		$cards_value = $cardsinfo[$security_zuo[0]].$cardsinfo[$security_zuo[1]].$cardsinfo[$security_zuo[2]];
		if($cards_value != trim($secret_value))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	//调出密保开关
	public function get_mibao_status()
	{
		$this->addItem($this->settings['mibao']);
		$this->output();
	}
	
	public function count()
	{
		
	}
	public function detail()
	{
		
	}
	public function update_token_expired_time()
	{
		//用户token time_expired自定义过期时间戳
		//这个戳实际是记录的登录时间
		if(!$this->input['access_token'])
		{
			$this->errorOutput(NO_ACCESS_TOKEN);
		}
		$this->db = $this->getDB($this->input['access_token']);
		$sql = 'SELECT * FROM ' . $this->dbname . DB_PREFIX.'user_login WHERE token = "' . $this->input['access_token'] . '"';
		//exit;
		$user = $this->db->query_first($sql);
		if(!$user)
		{
			$this->erroroutput(USER_NOT_LOGIN);
		}
		$diff = defined('TOKEN_EXPIRED') ? TOKEN_EXPIRED : 3600;
		$time_expired = intval($this->input['time_expired']) ? $this->input['time_expired'] - $diff : TIMENOW;
		$sql = 'UPDATE ' . $this->dbname . DB_PREFIX.'user_login SET login_time = '.$time_expired.' WHERE token="'.$user['token'].'"';
		$this->db->query($sql);
		$output = array(
		'access_token'=>$user['token'],
		'expired_time'=>$time_expired,
		);
		$this->addItem($output);
		$this->output();
	}
	//扩展信息输出
	public function getUserExtendInfo($user_id = 0)
	{
		$user_id = $user_id?$user_id:$this->user['user_id'];
		$UserExtend     = array();//extendinfo表数据
		$reUserExtend   = array();//返回数据
		if(empty($user_id))
		{
			return $Userextend;
		}
		$extendInfo	 = new extendInfo();
		$UserExtend = $extendInfo->show(" AND user_id = " . $user_id);
		$reUserExtend =  $extendInfo->extendDataProcess($UserExtend,0);
		return $reUserExtend;
	}
	/*
	 * 检测用户名是否存在
	 */
	public function check_username_existed()
	{
		$errortype  = $this->input['errortype']?intval($this->input['errortype']):0;//报错方式
		$this->db = hg_ConnectDB();
		if(!trim($this->input['user_name']))
		{
			!$errortype&&$this->errorOutput('用户名不能为空');
			 if($errortype){
			 	$this->addItem_withkey('status', '-1');
			 	$this->output();
			 }
			
		}
		$this->input['id'] = $this->input['id'] ? $this->input['id'] : 0;
		$sql = "SELECT count(*) AS total FROM " .DB_PREFIX. "admin WHERE user_name = '" .$this->input['user_name']. "' and id != " .$this->input['id'];
		$row = $this->db->query_first($sql);
		if ($row['total'])
		{
			!$errortype&&$this->errorOutput('用户名已存在');
			if($errortype){
			 	$this->addItem_withkey('status', $row['total']);
			 	$this->output();
			 }
		}
			!$errortype&&$this->addItem('success');
			if($errortype){
			 	$this->addItem_withkey('status', $row['total']);
			 }
			$this->output();
	}
	
	/*
	 * 强制修改密码
	 */
	public function change_pwd()
	{
		$db = hg_ConnectDB();
		$username = trim($this->input['username']);
		$old_password = trim($this->input['old_password']);
		$password = trim($this->input['password']);
		$admin_id = intval($this->input['admin_id']);
		if(!$old_password || !$password || !$admin_id)
		{
			$this->addItem(array('error'=>1,'msg'=>'参数缺失'));
			$this->output();
		}
		//验证旧密码
		$sql = "SELECT password,salt FROM " .DB_PREFIX. "admin WHERE id = " .$admin_id. " AND user_name = '" .$username. "'";
		$q = $db->query_first($sql);
		
		$salt = hg_generate_salt();
		if ($this->input['md5once'])
		{
			$password = md5($password.$salt);
			$old_password = md5($old_password.$q['salt']);
		}
		else
		{
			$password = md5(md5($password).$salt);
			$old_password = md5(md5($old_password).$q['salt']);
		}
		if($old_password != $q['password'])
		{
			$this->addItem(array('error'=>1,'msg'=>'原始密码有误'));
			$this->output();
		}
		
		$data = array(
			'password'	 		=> $password,
			'salt'		 		=> $salt,
			'update_time'		=> TIMENOW,
			'forced_change_pwd' => 0,
		);
		$re = $db->update_data($data, 'admin','id='.$admin_id);
		if($re)
		{
			$ret = array('error'=>0,'msg'=>'success');
		}
		else
		{
			$ret = array('error'=>1,'msg'=>'修改失败');
		}
		$this->addItem($ret);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>
