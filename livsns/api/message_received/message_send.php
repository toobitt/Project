<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* $Id: message_send.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/messagesend.class.php';
define('MOD_UNIQUEID', 'message_send'); //模块标识

class messagesendApi extends outerReadBase
{
	private $messagesend;
	
	public function __construct()
	{
		parent::__construct();
		$this->messagesend = new messagesendClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->messagesend);
	}
		
	
	public function show()
	{
		$messagesend_info = array();
		$messagesend_info = $this->messagesend->show_send();
		$this->addItem($messagesend_info);
		$this->output();
	}
	
	public function count()
	{
		
	}
	
	public function detail()
	{
		
	}
	
	//通过号码检查是否存在，如存在返回该条数据
	public function exists_send()
	{
		if (empty($this->input['coul'])){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			$coul = $this->input['coul'];
			$coulvalue = $this->input['coulvalue'];
			$info = $this->messagesend->exists_send($coul,$coulvalue);
			$this->addItem($info);
			$this->output();
		}
	}


	private function get_condition()
	{	
		return array(
			'key' => trim(urldecode($this->input['key'])),
		);
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
	
}



$out = new messagesendApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>