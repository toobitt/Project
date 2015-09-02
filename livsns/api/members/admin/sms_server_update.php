<?php
/***************************************************************************
* $Id: sms_server_update.php 41757 2014-11-19 02:02:05Z youzhenghuan $
***************************************************************************/
define('MOD_UNIQUEID','sms_server');//模块标识
require('./global.php');
class smsServerUpdateApi extends adminUpdateBase
{
	private $mSmsServer;
	public function __construct()
	{
		parent::__construct();
		$this->verify_setting_prms();
		require_once CUR_CONF_PATH . 'lib/sms_server.class.php';
		$this->mSmsServer = new smsServer();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 
		id 主键id
		name 名称
		brief 描述
		logo logo 
		order_id 排序id
		status 状态 
		verifycode_length 验证码长度 默认6
		verifycode_content 验证码类型值 默认数字
		account 账号
		password 密码
		company_name 公司名
		admin_mobile 管理员手机号
		over 余额 
		over_remind 余额提醒
		send_url 发送信息接口 http://sms.bechtech.cn/Api/send/data/json?accesskey={$accesskey}&secretkey={$secretkey}&mobile=您的手机号码&content=abc
		recvtime - 接收时间
		//mobile - 发送的手机号
		content - 发送的内容
		over_url 获取短信余额接口 http://sms.bechtech.cn/Api/getLeft/data/json?accesskey=xxx&secretkey=yyy
		over_mobile 发送余额手机号
		over_content 发送余额内容
		accesskey 用户接入KEY
		secretkey 用户接入密钥
		bind_ip 绑定IP地址 注意：多个IP地址，请使用半角逗号 , 隔开；如果动态IP地址，请填写*号，即不绑定您的Ip地址
		http_address 上行转发地址 地址必须以http://或https://开头
		appid 应用id
		appname 应用名
		create_time 创建时间
		update_time 更新时间
		ip 创建者ip
		短信发送测试，验证码：'{$c}'；南京厚建软件有限责任公司
		短信余额提醒，剩余：'{$over}'；南京厚建软件有限责任公司
	 */
	public function create()
	{
		$name				= trim($this->input['name']);
		$brief				= trim($this->input['brief']);
		$verifycode_length	= intval($this->input['verifycode_length']);
		$verifycode_content	= trim($this->input['verifycode_content']);
		$account			= trim($this->input['account']);
		$password			= trim($this->input['password']);
		$company_name		= trim($this->input['company_name']);
		$admin_mobile		= trim($this->input['admin_mobile']);
		$over				= intval($this->input['over']);
		$over_remind		= intval($this->input['over_remind']);
		$send_url			= trim($this->input['send_url']);
		$recvtime			= trim($this->input['recvtime']);
		$content			= trim($this->input['content']);
		$over_url			= trim($this->input['over_url']);
		$over_mobile		= trim($this->input['over_mobile']);
		$over_content		= trim($this->input['over_content']);
		$accesskey			= trim($this->input['accesskey']);
		$secretkey			= trim($this->input['secretkey']);
		$bind_ip			= trim($this->input['bind_ip']);
		$http_address		= trim($this->input['http_address']);
		
		if (!$name)
		{
			$this->errorOutput(NO_NAME);
		}
		
		if (!$account)
		{
			$this->errorOutput(NO_ACCOUNT);
		}
		
		if (!$password)
		{
			$this->errorOutput(NO_PASSWORD);
		}
		
		if (!$company_name)
		{
			$this->errorOutput(NO_COMPANY_NAME);
		}
		
		if (!$admin_mobile)
		{
			$this->errorOutput(NO_ADMIN_MOBILE);
		}
		
		if (!$over_remind)
		{
			$this->errorOutput(NO_OVER_REMIND);
		}
		
		if (!$send_url)
		{
			$this->errorOutput(NO_SEND_URL);
		}
		
		if (!$content)
		{
			$this->errorOutput(NO_SMS_CONTENT);
		}
		
		if (!$over_url)
		{
			$this->errorOutput(NO_OVER_URL);
		}
		
		if (!$over_mobile)
		{
			$this->errorOutput(NO_OVER_MOBILE);
		}
		
		if (!$over_content)
		{
			$this->errorOutput(NO_OVER_CONTENT);
		}
		
		if (!$accesskey)
		{
			$this->errorOutput(NO_ACCESSKEY);
		}
		
		if (!$secretkey)
		{
			$this->errorOutput(NO_SECRETKEY);
		}
		
		$length = mb_strlen($content);
		if ($length > 58)
		{
			//$this->errorOutput(SMS_CONTENT_MAX);
		}
		
		$brief = $brief == '这里输入描述' ? '' : $brief;
		
		$data = array(
			'name'					=> $name,
			'brief'					=> $brief,
			'verifycode_length' 	=> $verifycode_length ? $verifycode_length : 6,
			'verifycode_content'	=> $verifycode_content ? $verifycode_content : '0123456789',
			'account'				=> $account,
			'password'				=> $password,
			'company_name'			=> $company_name,
			'admin_mobile'			=> $admin_mobile,
			'over'					=> $over,
			'over_remind'			=> $over_remind,
			'send_url'				=> $send_url,
			'recvtime'				=> $recvtime,
			'content'				=> $content,
			'over_url'				=> $over_url,
			'over_mobile'			=> $over_mobile,
			'over_content'			=> $over_content,
			'accesskey'				=> $accesskey,
			'secretkey'				=> $secretkey,
			'bind_ip'				=> $bind_ip,
			'http_address'			=> $http_address,
			'org_id'				=> $this->user['org_id'],
			'user_id'				=> $this->user['user_id'],
			'user_name'				=> $this->user['user_name'],
			'appid'					=> $this->user['appid'],
			'appname'				=> $this->user['display_name'],
			'create_time'			=> TIMENOW,
			'update_time'			=> TIMENOW,
			'ip'					=> hg_getip(),
		);
		
		$ret = $this->mSmsServer->create($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput(ADD_FAILED);
		}
		
		$id = $ret['id'];
		
		//logo
		if ($_FILES['logo']['tmp_name'])
		{
			$logo = $this->mSmsServer->add_material($_FILES['logo'], $id);
		}
		
		//更新 排序id、logo
		$update_data = array(
			'id'		=> $id,
			'order_id'	=> $id,
			'logo' 		=> $logo ? serialize($logo) : '',
		);
		//更新数据
		$ret = $this->mSmsServer->update($update_data);
		
		$this->addItem($id);
		$this->output();
	}
	
	public function update()
	{
		$id 				= intval($this->input['id']);
		$name				= trim($this->input['name']);
		$brief				= trim($this->input['brief']);
		$verifycode_length	= intval($this->input['verifycode_length']);
		$verifycode_content	= trim($this->input['verifycode_content']);
		$account			= trim($this->input['account']);
		$password			= trim($this->input['password']);
		$company_name		= trim($this->input['company_name']);
		$admin_mobile		= trim($this->input['admin_mobile']);
		$over				= intval($this->input['over']);
		$over_remind		= intval($this->input['over_remind']);
		$send_url			= trim($this->input['send_url']);
		$recvtime			= trim($this->input['recvtime']);
		$content			= trim($this->input['content']);
		$over_url			= trim($this->input['over_url']);
		$over_mobile		= trim($this->input['over_mobile']);
		$over_content		= trim($this->input['over_content']);
		$accesskey			= trim($this->input['accesskey']);
		$secretkey			= trim($this->input['secretkey']);
		$bind_ip			= trim($this->input['bind_ip']);
		$http_address		= trim($this->input['http_address']);
		
		if (!$id)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		
		if (!$name)
		{
			$this->errorOutput(NO_NAME);
		}
		
		if (!$account)
		{
			$this->errorOutput(NO_ACCOUNT);
		}
		
		if (!$password)
		{
			$this->errorOutput(NO_PASSWORD);
		}
		
		if (!$company_name)
		{
			$this->errorOutput(NO_COMPANY_NAME);
		}
		
		if (!$admin_mobile)
		{
			$this->errorOutput(NO_ADMIN_MOBILE);
		}
		
		if (!$over_remind)
		{
			$this->errorOutput(NO_OVER_REMIND);
		}
		
		if (!$send_url)
		{
			$this->errorOutput(NO_SEND_URL);
		}
		
		if (!$content)
		{
			$this->errorOutput(NO_SMS_CONTENT);
		}
		
		if (!$over_url)
		{
			$this->errorOutput(NO_OVER_URL);
		}
		
		if (!$over_mobile)
		{
			$this->errorOutput(NO_OVER_MOBILE);
		}
		
		if (!$over_content)
		{
			$this->errorOutput(NO_OVER_CONTENT);
		}
		
		if (!$accesskey)
		{
			$this->errorOutput(NO_ACCESSKEY);
		}
		
		if (!$secretkey)
		{
			$this->errorOutput(NO_SECRETKEY);
		}
		
		$length = mb_strlen($content);
		if ($length > 58)
		{
			//$this->errorOutput(SMS_CONTENT_MAX);
		}
		
		$condition  = " AND id = " . $id;
		$sms_server = $this->mSmsServer->get_sms_server_info($condition);
		$sms_server = $sms_server[0];
		
		if (empty($sms_server))
		{
			$this->errorOutput(NO_RECORD);
		}
		
		$brief = $brief == '这里输入描述' ? '' : $brief;
		
		$data = array(
			'id'					=> $id,
			'name'					=> $name,
			'brief'					=> $brief,
			'verifycode_length' 	=> $verifycode_length ? $verifycode_length : 6,
			'verifycode_content'	=> $verifycode_content ? $verifycode_content : '0123456789',
			'account'				=> $account,
			'password'				=> $password,
			'company_name'			=> $company_name,
			'admin_mobile'			=> $admin_mobile,
			'over'					=> $over,
			'over_remind'			=> $over_remind,
			'send_url'				=> $send_url,
			'recvtime'				=> $recvtime,
			'content'				=> $content,
			'over_url'				=> $over_url,
			'over_mobile'			=> $over_mobile,
			'over_content'			=> $over_content,
			'accesskey'				=> $accesskey,
			'secretkey'				=> $secretkey,
			'bind_ip'				=> $bind_ip,
			'http_address'			=> $http_address,
			'update_org_id'			=> $this->user['org_id'],
			'update_user_id'		=> $this->user['user_id'],
			'update_user_name'		=> $this->user['user_name'],
			'update_time'			=> TIMENOW,
		);
		
		$ret = $this->mSmsServer->update($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput(UPDATE_FAILED);
		}
		
		//logo
		if ($_FILES['logo']['tmp_name'])
		{
			$logo = $this->mSmsServer->add_material($_FILES['logo'], $id);
			
			//更新logo
			$update_data = array(
				'id'	=> $id,
				'logo'  => $logo ? serialize($logo) : '',
			);
			//更新数据
			$ret = $this->mSmsServer->update($update_data);
		}
		
		$this->addItem($id);
		$this->output();
	}
	
	public function delete()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		
		$ret = $this->mSmsServer->delete($id);
		
		if (!$ret)
		{
			$this->errorOutput(DELETE_FAILED);
		}
		
		$this->addItem($id);
		$this->output();
	}
	
	public function audit()
	{
		$id = trim($this->input['id']);
		
		if (!$id)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		
		$field = 'id, status';
		$condition  = " AND id = " . $id;
		$sms_server = $this->mSmsServer->get_sms_server_info($condition, $field);
		$sms_server = $sms_server[0];
		
		if (empty($sms_server))
		{
			$this->errorOutput(NO_RECORD);
		}
		
		$status = $sms_server['status'];
		
		$ret = 0;
		
		$update_data = array(
			'id'	=> $id,
		);
		
		if (!$status) //启动
		{
			$update_data['status'] = 1;
			$ret = 1;
		}
		else	//停止
		{
			$update_data['status'] = 0;
			$ret = 2;
		}
		
		$this->mSmsServer->update($update_data);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		
		$ret = $this->drag_order('sms_server', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new smsServerUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>