<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_update.php 4728 2011-10-12 10:38:02Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','payment');//模块标识
class paymentUpdateApi extends adminUpdateBase
{
	private $pay_type;
	private $obj;
	function __construct()
	{
		parent::__construct();
		$this->pay_type = $this->input['pay_type'] ? trim($this->input['pay_type']) : 'alipay';
		include_once(CUR_CONF_PATH . 'lib/' . $this->pay_type . '.class.php');
		include_once(CUR_CONF_PATH . 'lib/' . $this->pay_type . '.func.php');
		$this->obj = new $this->pay_type();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/*
	* 支付
	*/
	public function create()
	{
		
	}
	
	public function update()
	{
		/*$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$this->setXmlNode('program_record', 'info');
		$this->addLogs('update' , $fo , $info);
		$this->addItem($ret);
		$this->output();*/
	}

	public function delete()
	{
		$this->setXmlNode('program_record','info');
		$this->addItem($re);
		$this->output();
	}	
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
}

$out = new paymentUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>