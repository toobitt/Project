<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/qrcodeLogin.class.php';
define('MOD_UNIQUEID', 'dingdone_app');

class qrcodeLogin extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new app();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	public function show() {}
	
	public function detail()
	{
	    $sessid = trim($this->input['id']);
		if (empty($sessid)) $this->errorOutput(PARAM_WRONG);
		$queryData = array('sessid' => $sessid);
		$login_info = $this->api->detail('qrcode_login', $queryData);
		$this->addItem($login_info);
		$this->output();
	}
	
	public function create()
	{
	    $sessid = trim($this->input['id']);
	    $token = trim($this->input['token']);
	    if (empty($sessid) || empty($token))
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $insertData = array(
	        'sessid' => $sessid,
	        'token' => $token
	    );
	    $result = $this->api->create('qrcode_login', $insertData);
	    $this->addItem($result);
	    $this->output();
	}
	
	public function delete()
	{
	    $sessid = trim($this->input['id']);
	    $token = trim($this->input['token']);
	    if (empty($sessid) && empty($token))
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $deleteData = array();
	    if ($sessid) $deleteData['sessid'] = $sessid;
	    if ($token) $deleteData['token'] = $token;
	    $result = $this->api->delete('qrcode_login', $deleteData);
	    $this->addItem($result);
	    $this->output();
	}
}

$out = new qrcodeLogin();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>