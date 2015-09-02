<?php
require_once './global.php';
include_once ROOT_PATH . 'lib/class/auth.class.php';
include_once ROOT_PATH . 'lib/class/curl.class.php';
include(CUR_CONF_PATH . 'lib/UpYunApi.class.php');
require_once CUR_CONF_PATH . 'lib/upyun.class.php';
define('MOD_UNIQUEID', 'register');  //模块标识

require_once(ROOT_PATH . 'lib/class/material.class.php');
class user extends appCommonFrm
{
	private $auth;
	private $upyun;

	public function __construct()
	{
		parent::__construct();
		$this->auth = new Auth();
		$this->upyun = new UpYunApi();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->auth);
	}

	/**
	 * 注册接口
	 */
	public function register()
	{
		$data = $this->filter_data();
		if (empty($data['password'])) $this->errorOutput(PARAM_WRONG);
		//默认角色
		$data['admin_role_id'] = $this->input['role_id'] ? $this->input['role_id'] : implode(',', $this->settings['default_role']);
		//$data['admin_role_id'] = implode(',', $this->settings['default_role']);
		//创建组织机构
		if (defined('DEFAULT_ORG') && DEFAULT_ORG)
		{
			$data['father_org_id'] = DEFAULT_ORG;
		}
		else
		{
			$orgData = array(
				'name' => $data['user_name']
			);
			$org_info = $this->auth->create_org($orgData);//创建组织
			if (!$org_info) $this->errorOutput('组织注册失败');
			$data['father_org_id'] = $org_info['id'];
		}
		//创建用户
		$user_info = $this->auth->create_user($data);
		log2file(array(), 'debug', '注册接口输入输出', $data, $user_info);
		$this->addLogs('用户注册', array(), $user_info, $data['user_name']);
		if (!$user_info) $this->errorOutput('注册失败');
		$func = SPACETYPE.'spaceApply';
		$cdnReg = $this->$func($user_info);//空间注册
		if($this->updateExtend($user_info['id'],$cdnReg))//更新本地空间信息
		{
			$user_info['extend'] = $cdnReg;
		}
		$userlogin = array(
		'username'=>$user_info['user_name'],
		'password'=>$data['password'],
		'isextend'=>1,
		);
		$reUserLogin = $this->login($userlogin,true);
		$reUserLogin['balance'] = DEFAULT_BALANCE;
		//插入统计队列 注册试用2小时之后统计费用
		$this->insert_user_queue($reUserLogin);
		
		$this->addItem($reUserLogin);
		$this->output();
	}
	/**
	 * 
	 * 登陆接口 ...
	 * @param unknown_type $userlogin
	 * @param unknown_type $isRe
	 */
	public function login($userlogin = array(),$isRe = false)
	{
		$userlogin = $userlogin?$userlogin:array(
		'username'=>$this->input['username'],
		'password'=>$this->input['password'],
		'isextend'=>1
		);
		$UserLogin = $this->auth->login($userlogin);
		unset($userlogin['password']);
		log2file(array(), 'debug', '用户登陆', $userlogin, $UserLogin);
		$this->addLogs('用户登陆', array(), $UserLogin, $userlogin['username']);
	 	unset($UserLogin['password']);
	 	
	 	if($UserLogin)
	 	{
		 	//读取用户余额
		 	$sql = 'SELECT balance FROM  ' . DB_PREFIX . 'user_queue WHERE user_id='.$UserLogin['id'];
		 	$balance = $this->db->query_first($sql);
		 	$UserLogin['balance'] = $balance['balance'];
		 	if($this->input['_app_id'])
		 	{
			 	if($bindinfo = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'user_bind_app  WHERE user_id='.$UserLogin['id'] . ' AND app_id='.$this->input['_app_id']))
			 	{
			 		$sql = 'UPDATE ' .DB_PREFIX . 'user_bind_app SET update_time = '.TIMENOW . ' WHERE bind_id='.$bindinfo['bind_id'];
			 	}
			 	//如果传递了应用_app_id则为来自oauth的应用绑定
			 	else 
			 	{
			 		$sql = 'INSERT INTO ' . DB_PREFIX . 'user_bind_app VALUES(null,'.$UserLogin['id'].', '.$this->input['_app_id'].', '.TIMENOW.',1,0)';
			 	}
			 	$this->db->query($sql);
		 	}
	 	}
	 	if($isRe)
	 	{
	 		return $UserLogin;
	 	}
	 	
	 	//$this->insert_user_queue($UserLogin);
		
	 	$this->addItem($UserLogin);
	 	$this->output();
	}
	protected function insert_user_queue($userinfo = array())
	{
		if(!$userinfo)
		{
			return;
		}
		$data = array(
			'user_id'=>$userinfo['id'],
			'bucket_name'=>$userinfo['extend']['bucket_name']['value'],
			'domain'=>$userinfo['extend']['domain']['value'],
			'update_time'=>TIMENOW+CHARGE_STA,
			'balance'=>DEFAULT_BALANCE,
			'charge_time'=>strtotime(date('Y-m-d')),
			'status'=>1,
		);
		$sql = 'REPLACE INTO ' . DB_PREFIX . 'user_queue SET ';
		foreach($data as $k=>$v)
		{
			$sql .= "`{$k}`=\"".addslashes($v)."\",";
		}
		//插入第一条消费纪录
		//$sql = 'INSERT INTO ' . DB_PREFIX . 'pay value('.$userinfo['id'].', '.TIMENOW.', '.TIMENOW.', 0,0,'.TIMENOW.')';
		//file_put_contents(CACHE_DIR.'debug.txt', trim($sql,','));
		$this->db->query(trim($sql,','));
		
		//统计表初始化数据
		$this->db->query('INSERT INTO ' . DB_PREFIX . 'analytic_statistics SET user_id='.$userinfo['id']);
	}
	public function logout()
	{
		$user = array('access_token'=>$this->user['token']);
		$responce = $this->auth->logout($user);
		$this->addLogs('用户退出', array(), $responce, $this->user['user_name']);
		$this->addItem($responce);
		$this->output();
	}
/**
 * 
 * 用户名检测 ...
 * @param unknown_type $username
 * @param unknown_type $userid
 */
	public function checkUserName($username = '',$userid = 0,$isRe = 0)
	{
		$params = array(
		'user_name' => $username?$username:trim($this->input['username']),
		'id' => $userid?intval($userid):intval($this->input['userid']),
		);
		$ret = $this->auth->CheckUserName($params);
		if($isRe)
		{
			return $ret['status']>0?true:false;
		}
		$this->addItem($ret);
		$this->output();
	}
	/**
	 * 
	 * 空间申请 ...
	 * @param unknown_type $config
	 */
	private function spaceApply($config)
	{
		$spaceApply = $this->upyun->spaceApply($config);//创建空间
		if($spaceApply['error_code'])
		{
			$this->errorOutput($spaceApply['message']);
		}
		if($spaceApply['result'])
		{
			return $config['bucket_name'];
		}
		return $spaceApply;
	}
	/**
	 * 
	 * 文件空间类型处理函数 ...
	 * @param unknown_type $config
	 */
	private function fileSpaceApply($user_info)
	{
		$bucket_name = spaceNameGenerate(array($user_info['id']));
		$config = array(
			'bucket_name'=> $bucket_name,
			'type'		=> SPACETYPE,
			'quota'		=> SPACEQUOTA,
		);
		$spaceApply = $this->spaceApply($config);
		$reSpace = array();
		if($spaceApply)
		{
			$reSpace = array('bucket_name'=>$spaceApply, 'dev_app'=>0);
			if($spaceOperators = $this->spaceAuth($config))
			{
				//上传crossdomain.xml
				$this->upload_crossdomian_xml($bucket_name);
				$reSpace['spaceOperators'] = $spaceOperators;
				$reSpace['spaceOperatorsPw'] = hg_authcode(SPACEOPERATORSPASSWORD, $operation);
			}
			if($domain = $this->spaceDomain($config,$user_info)){
				$reSpace['domain'] = $domain;
			}
		}
		log2file($user_info, 'debug', '用户空间申请', $config, $reSpace);
		return $reSpace;
	}
	/**
	 * 
	 * 操作员授权函数 ...
	 * @param unknown_type $config
	 */
	private function spaceAuth($config)
	{
		$spaceOperatorsAuth = $this->upyun->spaceOperatorsAuth(array('bucket_name'=>$config['bucket_name'],'operator_name'=>SPACEOPERATORS));//绑定操作员
		if($spaceOperatorsAuth['error_code'])
		{
			$this->errorOutput($spaceOperatorsAuth['message']);
		}
		if($spaceOperatorsAuth['result']){
			return SPACEOPERATORS;
		}
		return;
	}
	private function updateExtend($user_id,$cdnReg)
	{
		return $this->auth->updateExtend($user_id, array('extendInfo'=>$cdnReg));
	}
	/**
	 *
	 * 域名绑定方法...
	 * @param unknown_type $config
	 */
	private function spaceDomain($spaceinfo,$user_info,$diydomain = array())
	{
		$domainprefix = SPACEDOMAINPREFIX.$user_info['id'];
		$domain = SPACEDOMAIN;
		$_domain = $diydomain?$diydomain:array($domainprefix,$domain);
		$BindDomain = array('bucket_name'=>$spaceinfo['bucket_name'],'domain'=>implode('.', $_domain));
		$spaceBindDomain = $this->upyun->spaceBindDomain($BindDomain);//绑定域名
		if($spaceBindDomain['error_code'])
		{
			$this->errorOutput($spaceBindDomain['message']);
		}
		if($spaceBindDomain['result']){
			return $BindDomain['domain'];
		}
		return;
	}

	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$username = trim(urldecode($this->input['username']));
		$password = trim(urldecode($this->input['password']));
		$passwordRepeat = trim(urldecode($this->input['passwordRepeat']));
		if (empty($username)) $this->errorOutput(NOUSERNAME);
		if($this->checkUserName($username,0,1))$this->errorOutput(USERNAMEEXISTS);
		if (empty($password)) $this->errorOutput(NOPASSWORD);
		if($password!=$passwordRepeat)
		{
			$this->errorOutput('密码和确认密码不相同');
		}
		return array(
			'user_name' => $username,
			'password' => $password
		);
	}
	/**
	 * 表单api状态处理
	 */
	public function get_form_api()
	{
		$user_extend = $this->auth->getUserExtendInfo();
		if(!$user_extend || !is_array($user_extend))
		{
			$this->errorOutput("获取绑定信息失败");
		}
		$bucket_name = $user_extend['extend']['bucket_name']['value'];
		$update_key = intval($this->input['update_key']) ? 'true' : 'false';
		$status = !isset($this->input['status']) || intval($this->input['status']) ? 'on' : 'off';
		
		$param = array(
		'bucket_name'=>$bucket_name,
		'update_key'=>$update_key,
		'status'=>$status,
		);
		$result = $this->upyun->UpFormApi($param);
		log2file($this->user, 'debug', '获取表单key', $param, $result);
		//print_r($result);exit;
		if($result['error_code'])
		{
			log2file($this->user, 'error', '获取表单key失败', $param, $result);
			$this->errorOutput($result['message']);
		}
		
		$output = array(
		'form_api_secret'=>'', 
		'bucket_name'=>$bucket_name,
		'access_token'=>$this->input['access_token'],
		'allow_file_type'=>$this->settings['form_api_param']['allow_file_type'],
		'allow_max_size'=>$this->settings['form_api_param']['allow_max_size'],
		);
		$form_api_allow_file_type = '';
		$allow_file_type = explode(';',$this->settings['form_api_param']['allow_file_type']);
		foreach($allow_file_type as $t)
		{
			$form_api_allow_file_type .= trim($t, '*.') . ',';
		}		
		$form_api_allow_file_type = trim($form_api_allow_file_type,',');
		
		if($result['result'])
		{
			$key = $this->upyun->getSpaceInfo($bucket_name);
			//print_r($key);exit;
			if(!$key['form_api_secret'])
			{
				$this->errorOutput("获取表单api失败");
			}
			$output['form_api_secret'] = $key['form_api_secret'];
		}
		$options = array();
		
		//回调地址
		if($this->input['client_id'] && $this->input['return_type']=='sync')
		{
			//同步
			$data_format = '&return_url='.urlencode($this->input['return_url']);
			if($this->input['data_format']=='jsonp')
			{
				$data_format .= '&data_format=jsonp&func_name=' . ($this->input['func_name'] ? $this->input['func_name'] : 'callback_func');
			}
			else
			{
				$data_format .= '&data_format=json';
			}
			$options['return-url'] = $this->settings['form_api_param']['notify-url'] . 'callback_type=upload&access_token='.$user_extend['token'].'&return_type=sync' . $data_format;
		}
		else
		{
			//异步
			$options['notify-url'] = $this->settings['form_api_param']['notify-url'] . 'callback_type=upload&access_token='.$user_extend['token'].'&return_type=asyn';
		}
		//file_put_contents(CACHE_DIR . 'debug.txt', $options['return-url']);
		$options['bucket'] = $bucket_name;
		
		// 授权过期时间：以页面加载完毕开始计时，10分钟内有效
		$options['expiration'] = TIMENOW+$this->settings['form_api_param']['expiration'];
		
		// 保存路径：最终将以"/年/月/日/upload_待上传文件名"的形式进行保存
		$save_as = str_replace(array('0.',' '), array('','_'), microtime());
		$options['save-key'] = $this->settings['form_api_param']['filepath'] . $save_as . $this->settings['form_api_param']['suffix'];
		
		// 文件类型限制：仅允许上传扩展名为 jpg,gif,png 三种类型的文件
		$options['allow-file-type'] =  $form_api_allow_file_type;
		
		//扩展参数
		$options['ext-param'] = rawurlencode('upload_type='.$this->input['upload_type'].'&title=' . rawurldecode(trim($this->input['title'])) . '&client_id='.$this->input['client_id']);
		
		// 计算 policy 内容，具体说明请参阅"Policy 内容详解"
		$policy = base64_encode(json_encode($options));
		//file_put_contents(CACHE_DIR . 'option.txt', var_export($this->input,1), FILE_APPEND);
		// 计算签名值，具体说明请参阅"Signature 签名"
		$signature = md5($policy.'&'.$output['form_api_secret']);
		
		$output['signature'] = $signature;
		
		$output['policy'] = $policy;
		$output['action'] = $this->settings['form_api_param']['action'] . $bucket_name;
		log2file($this->user, 'debug', '获取表单API参数', $this->input, $output);
		$this->addLogs('获取表单api', $this->user, $output, $this->user['user_name']);
		$this->addItem($output);
		$this->output();
	}
	private function upload_crossdomian_xml($bucket_name)
	{
		$upyun = new UpYun($bucket_name, SPACEOPERATORS, SPACEOPERATORSPASSWORD);
		try {
			$file = CUR_CONF_PATH . 'data/crossdomain.xml';
		    $fh = fopen($file, 'rb');
		    $rsp = $upyun->writeFile('/crossdomain.xml', $fh, True);   // 上传图片，自动创建目录
		    fclose($fh);
		}
		catch(Exception $e) {
		    //
		}
	}
	public function get_bucket_status()
	{
		$user_extend = $this->auth->getUserExtendInfo();
		if(!$user_extend || !is_array($user_extend))
		{
			$this->errorOutput("获取绑定信息失败");
		}
		$bucket_name = $user_extend['extend']['bucket_name']['value'];
		$domain = $user_extend['extend']['domain']['value'];
		
		$start_day = urldecode($this->input['start_day']) ?  urldecode($this->input['start_day']): '';
		$period = intval($this->input['period']);
		$param = array(
		'bucket_name'=>$bucket_name,
		'domain'=>$domain,
		'start_day'=>$start_day ? $start_day : '',
		'period'=>$period > 1 ? $period : 1,
		);
		$status = $this->upyun->BucketStatus($param);
		
		log2file($this->user, 'debug', '空间状态查询统计', $param, $status);
		if($status['discharge'])
		{
			foreach ($status['discharge'] as $key=>$val)
			{
				$time = $period > 1 ? date('m月d日', strtotime($key)) : date('H:i',strtotime($key . ':00:00'));
				unset($status['discharge'][$key]);
				$status['discharge'][$time] = round($val/1024/1024,2);
			}
		}
		
		if($status['bandwidth'])
		{
			foreach ($status['bandwidth'] as $key=>$val)
			{
				$time = $period > 1 ? date('m月d日H时', strtotime($key . ':00:00')) : date('H:i', strtotime($key));
				unset($status['bandwidth'][$key]);
				$status['bandwidth'][$time] = round($val/1024,2);
			}
		}
		
		if($status['reqs'])
		{
			foreach ($status['reqs'] as $key=>$val)
			{
				$time = $period > 1 ? date('m月d日H时', strtotime($key . ':00:00')) : date('H:i', strtotime($key));
				unset($status['reqs'][$key]);
				$status['reqs'][$time] = $val;
			}
		}
		
		if($status['maxs'])
		{
			foreach ($status['maxs'] as $key=>$val)
			{
				$time = date('Y年m月d日', strtotime($key));
				$val['bytes'] = round($val['bytes']/1024,2);
				$val['sbytes'] = round($val['sbytes']/1024/1024,2);
				unset($status['maxs'][$key]);
				$status['maxs'][$time] = $val;
			}
		}
		
		if($status['day_count'])
		{
			foreach ($status['day_count'] as $key=>$val)
			{
				$time = date('m月d日', strtotime($key));
				unset($status['day_count'][$key]);
				$status['day_count'][$time] = $val;
			}
		}
		
		$used = $this->upyun->BucketInfo(array('bucket_name'=>$param['bucket_name']));
		if($used['storage'])
		{
			$used['storage']['total'] = round($used['storage']['total']/1024/1024,2);
			$used['storage']['used'] = round($used['storage']['used']/1024/1024,2);
		}
		$total = 'SELECT * FROM ' . DB_PREFIX . 'analytic_statistics WHERE user_id='.$user_extend['user_id'];
		$total = $this->db->query_first($total);
		$return = array(
		'bucket_name'=>$used['bucket_name'],
		'status'=>$used['status'],
		'storage'=>$used['storage'],
		'stats'=>$status,
		'total'=>$total,
		);
		$this->addItem($return);
		$this->output();
	}
	function update_userinfo()
	{
		$data = array(
			'id'=>intval($this->user['user_id']),
			'password'=>trim($this->input['password']),
			'password_again'=>trim($this->input['password_again']),
			'old_password'=>trim($this->input['old_password']),
		);
		if(!$data['old_password'])
		{
			//$this->errorOutput("请输入原始密码");
			unset($data['old_password']);
		}
		if(!$data['password'])
		{
			//$this->errorOutput("新密码不可以为空");
			unset($data['password']);
			unset($data['password_again']);
		}
		if($data['old_password'] && !$data['password'])
		{
			$this->errorOutput("新密码不可以为空");
		}
		if ($data['password'] && ($data['password'] != $data['password_again']))
		{
			$this->errorOutput('两次输入的密码不一样');
		}
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','update_password');
		foreach ( $data as $key=>$val)
		{
			$curl->addRequestData($key,$val);
		}
		if ($_FILES['Filedata'])
		{
			$curl->addFile($_FILES);
		}
		$return = $curl->request('member.php');		
		if($return && $return[0])
		{
			if ($return[0]['error'] == -1)
			{
				$this->errorOutput('原始密码错误');
			}
		}
		$this->addLogs('更新用户资料', null, null, $this->user['user_name']);
		$this->addItem($return[0]);
		$this->output();
	}
	//扩容空间接口
	public function enlarge_space()
	{
		$user_extend = $this->auth->getUserExtendInfo();
		$param = array(
		'bucket_name'=>$user_extend['extend']['bucket_name']['value'],
		'quota'=> intval($this->input['quota']) ? intval($this->input['quota']) : SPACEQUOTA,
		);
		$result = $this->upyun->BucketQuota($param);
		$this->addItem($result);
		$this->output();
	}
	//根据access_token获取用户信息
	public function getUserInfoByToken()
	{
		$user_extend = $this->auth->getUserExtendInfo();
		$sql = 'SELECT balance FROM  ' . DB_PREFIX . 'user_queue WHERE user_id='.$user_extend['user_id'];
	 	$balance = $this->db->query_first($sql);
	 	$user_extend['balance'] = $balance['balance'];
	 	$user_extend['org_name'] = $this->settings['user_type_name'][$user_extend['org_id']];
		$this->addItem($user_extend);
		$this->output();
	}
	//初始化已经注册用户
	public function initCloudUser()
	{
		$user_info = $this->user;
		$user_info['id'] = $this->user['user_id'];
		if (!$user_info) $this->errorOutput('用户未注册');
		$func = SPACETYPE.'spaceApply';
		$cdnReg = $this->$func($user_info);//空间注册		
		if($this->updateExtend($user_info['id'],$cdnReg))//更新本地空间信息
		{
			$user_info['extend']['bucket_name']['value'] = $cdnReg['bucket_name'];
			$user_info['extend']['domain']['value'] = $cdnReg['domain'];
		}
		$this->insert_user_queue($user_info);
		$this->addItem('success');
		$this->output();
	}
	public function get_user_bind_app()
	{
		$sql = 'SELECT * FROM '  . DB_PREFIX  . 'user_bind_app WHERE user_id = '.$this->user['user_id'];
		$query = $this->db->query($sql);
		$app_id = array();
		while($row = $this->db->fetch_array($query))
		{
			$app_id[] = $row['app_id'];
			$status[$row['app_id']] = $row['status'];
		}
		$appinfo = array();
		if($app_id)
		{
			$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/');
			$curl->initPostData();
			$curl->setSubmitType('post');
			$curl->addRequestData('id', implode(',', $app_id));
			$appinfo =  $curl->request('preferences.php');
			if(!empty($appinfo) && is_array($appinfo))
			{
				foreach ($appinfo as $val)
				{
					$val['bind_status'] = $status[$val['id']];
					$this->addItem($val);
				}
			}
		}
		$this->output();
	}
	public function set_bind_app_status()
	{
		if(!in_array($this->input['status'], array(0,1,2)))
		{
			$this->errorOutput('无效状态');
		}
		$where = ' WHERE user_id='.$this->user['user_id'] . ' AND app_id = '.$this->input['application_id'];
		if(!$this->input['status'])
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . 'user_bind_app' .$where;
		}
		else 
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'user_bind_app SET status='.intval($this->input['status']) . $where;
		}
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
        public function uploadIndexPic()
        {
            if($_FILES['Filedata'])
            {
                $material = new material();
                $fileinfo = $material->addMaterial($_FILES); //插入图片服务器
                $this->addItem($fileinfo);
		$this->output();
            }
            $this->errorOutput('上传失败');
        }
        
		public function localMaterial()
        {
        	if(!trim($this->input['url']))
        	{
        		$this->errorOutput('没有图片url');
        	}
            $material = new material();
            $fileinfo = $material->localMaterial(trim($this->input['url']), 0, 0, -1); //插入图片服务器
            if(!$fileinfo)
            {
            	$this->errorOutput('图片本地化失败');
            }
            $this->addItem($fileinfo);
			$this->output();
        }
}

$out = new user();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'register';
}
$out->$action();
?>