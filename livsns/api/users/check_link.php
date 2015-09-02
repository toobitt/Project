<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: create.php 2885 2011-03-17 01:43:03Z wang $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class checkLinkApi extends appCommonFrm
{

	function __construct()
	{
		parent::__construct();

	}

	function __destruct()
	{
		parent::__destruct();
	}
	/*
	 * 邮箱验证
	 */
	public function check()
	{
		$verify_code = urldecode($this->input['verify_code']);
		$sql = "select * from ".DB_PREFIX."verify_code where type=0 and user_id=".$this->input['id'];
		$rt = $this->db->query_first($sql);
		$this->setXmlNode('check','value');
		$result['done'] =0;
		if(strcmp($verify_code,$rt['verify_code']) == 0 )
		{
			$sql = "update ".DB_PREFIX."member set email_check=1 where id=".$this->input['id'];
			$this->db->query($sql);
			$sql = "delete from ".DB_PREFIX."verify_code where type =0 and user_id=".$this->input['id'];
			$this->db->query($sql);
			$result['done'] =1;
		}
		$this->addItem($result);
		$this->output();
	}
	/*
	 * 找回密码验证
	 */
	public function check_pwd()
	{
		$verify_code = urldecode($this->input['verify_code']);
		$sql = "select * from ".DB_PREFIX."verify_code where type=1 and verify_code='".$verify_code."'";
		$rt = $this->db->query_first($sql);
		$this->setXmlNode('check','value');
		if($rt)
		{
			//$sql = "delete from ".DB_PREFIX."verify_code where type =1 and user_id=".$this->input['id'];
			//$this->db->query($sql);
			$result['done'] =1;
		}
		$this->addItem($result);
		$this->output();
	}
	public function update_email()
	{
		$this->setXmlNode('update','email');
		$email = urldecode($this->input['email']);
		if($this->input['id']&&$email)
		{
			$sql = "update ".DB_PREFIX."member set email='".$email."' where id=".$this->input['id'];
			try {
				$rt = $this->db->query($sql);
				$this->addItem(1);
			}catch (Exception $e)
			{
				$this->addItem(0);
			}
		}
		else
		{
			$this->addItem(0);
		}
		$this->output();
	}
	
	public function update_pwd()
	{
		$verify_code = urldecode($this->input['verify_code']);
		$sql = "select * from ".DB_PREFIX."verify_code where type=1 and verify_code='".$verify_code."'";
		$rt = $this->db->query_first($sql);
		$this->setXmlNode('check','value');
		$result['done'] =0;
		if($rt)
		{
			$salt = hg_generate_salt();
			$password = md5(md5(trim($this->input['password'])).$salt);
			$id = $rt['user_id'];
			$sql = "update ".DB_PREFIX."member set password='".$password."',salt='".$salt."' where id=".$id;
			$this->db->query($sql);
			$sql = "delete from ".DB_PREFIX."verify_code where type =1 and user_id=".$id;
			$this->db->query($sql);
			$result['done'] =1;
			$result['name'] = $rt['user_name'];
		}
		$this->addItem($result);
		$this->output();
	}
}
$out = new checkLinkApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'check';
}
$out->$action();
?>