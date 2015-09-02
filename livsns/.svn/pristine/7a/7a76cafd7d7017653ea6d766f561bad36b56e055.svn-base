<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/invitationCode.class.php';
define('MOD_UNIQUEID','dingdone_user');//模块标识


class invitationCode extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new invitationCodeClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	/**
	 * 验证邀请码是否有效
	 */
	public function validate()
	{
	    $code = trim(urldecode($this->input['code']));
	    //验证邀请码是否有效
	    $queryData = array(
	        'code' => $code,
	        'user_id' => 0
	    );
	    $code_info = $this->api->detail('invitation_code', $queryData);
	    $return = $code_info ? true : false;
	    $this->addItem($return);
	    $this->output();
	}
	
	/**
	 * 更新邀请码使用状态
	 */
	public function update()
	{
	    $code = trim(urldecode($this->input['code']));
	    $user_id = intval($this->input['uid']);
	    //验证用户是否有效
	    include_once ROOT_PATH . 'lib/class/auth.class.php';
	    $auth = new Auth();
	    $user_info = $auth->getMemberById($user_id);
	    if (!$user_info) $this->errorOutput(USER_NOT_EXISTS);
	    $user_info = $user_info[0];
	    $where = array(
	        'code' => $code,
	        'user_id' => 0
	    );
	    $data = array(
	        'user_id' => $user_id,
	        'user_name' => $user_info['user_name']
	    );
	    $result = $this->api->update('invitation_code', $data, $where);
	    $this->addItem($result);
	    $this->output();
	}
}

$ouput = new invitationCode();

if (!method_exists($ouput, $_INPUT['a']))
{
	$action = 'validate';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>