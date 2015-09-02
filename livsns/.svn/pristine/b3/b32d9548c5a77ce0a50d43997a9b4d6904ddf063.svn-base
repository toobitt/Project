<?php
/***************************************************************************
* $Id: verifycode.php  2013-12-02
***************************************************************************/
define('MOD_UNIQUEID', 'verify_code');
//define('ROOT_DIR', '../../');
//define('CUR_CONF_PATH', './');
require("./global.php");
//require(CUR_CONF_PATH."lib/functions.php");
class verifyCodeApi extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$sql  = "SELECT id,name,is_dipartite FROM ".DB_PREFIX."verify WHERE status=1";
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$this->addItem($r);
		}
		$this->output();
	}
	
	public function detail(){}
	
	public function count(){}
	
	/**
	 * 输出验证码图像资源
	 * 将验证码存入库
	 */
	
	public function set_verify_code()
	{
		$verify_id = $this->input['type'];
		$session_id = $this->input['session_id'];
		if(!$verify_id)
		{
			$sql = "SELECT id FROM " .DB_PREFIX. "verify WHERE is_default = 1";
			$tmp = $this->db->query_first($sql);
			if($tmp['id'])
			{
				$verify_id = $tmp['id'];
			}
			else 
			{
				$this->errorOutput('缺少验证码id');
			}
		}
		
		//取参数
		$sql = "SELECT v.*,f.name AS fontface,p.name AS bg_pic,p.type AS pic_type FROM " . DB_PREFIX . "verify v 
		 	LEFT JOIN " . DB_PREFIX . "font f ON v.fontface_id=f.id 
		 	LEFT JOIN " . DB_PREFIX . "bgpicture p ON v.bgpicture_id=p.id 
		 	WHERE v.id = ". $verify_id;
		$parameter = $this->db->query_first($sql);
		require_once('lib/captche.class.php');
		$captche = new Captche($parameter);
		//向浏览器输出图像
		$captche->showImage();
		$code = $captche->getCheckCode();
		
		//验证码入库
		if($session_id)
		{
			$sql = "INSERT INTO " .DB_PREFIX. "verify_code SET 
										session_id='" .$session_id. "',
										code='" .$code. "',
										is_dipartite='" .$parameter['is_dipartite']. "',
										create_time='" .TIMENOW. "'";
			$this->db->query($sql);
		}
		
		$this->addItem('success');
		$this->output();
	}
	
	/**
	 * 核对验证码
	 * is_dipartite 是否区分大小写
	 */
	public function check_verify_code()
	{
		$code = trim($this->input['code']);
		$session_id = trim($this->input['session_id']);
		$result = '';
		if(!$code)
		{
			$result = "验证码不能为空";
		}
		
		if(!$session_id && !$result)
		{
			$result = "缺少参数";
		}
		
		$sql = "SELECT code,is_dipartite FROM " .DB_PREFIX. "verify_code WHERE session_id='" .$session_id. "' ORDER BY id DESC LIMIT 1";
		$re = $this->db->query_first($sql);
		$check_code = $re['code'];
		$is_dipartite = $re['is_dipartite'];
		
		if(!$is_dipartite)
		{
			$code = strtolower($code);
			$check_code = strtolower($check_code);
		}
		
		if($code != $check_code && !$result)
		{
			$result = "验证码错误";
		}
		
		if(!$result)
		{
			$result = "SUCCESS";
		}
		if(true)
		{
			//删除已验证过的和过期的验证码
			$sql = "DELETE FROM " .DB_PREFIX. "verify_code WHERE session_id='" .$session_id. "' OR create_time < " . (TIMENOW - intval($this->settings['verify_code_valid']));
			$this->db->query($sql);
		}
		$this->addItem($result);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
}

$out = new verifyCodeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'set_verify_code';
}
$out->$action();
?>