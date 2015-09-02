<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* $Id: message_received.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
header("Content-type:text/html;charset=utf-8");
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/messagereceived.class.php';
define('MOD_UNIQUEID', 'message_received'); //模块标识

class messagereceivedApi extends outerReadBase
{
	private $messagereceived;
	
	public function __construct()
	{
		parent::__construct();
		$this->messagereceived = new messagereceivedClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->messagereceived);
	}
		
	
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$cateid = isset($this->input['cateid']) ? intval($this->input['cateid']) : "";
		$condition = $this->get_condition();
		$messagereceived_info = array();
		$messagereceived_info = $this->messagereceived->show($offset, $count, $condition ,$cateid);
		$this->setXmlNode('messagereceived_info', 'messagereceived');
		if ($messagereceived_info)
		{
			foreach ($messagereceived_info as $messagereceived)
			{
				$this->addItem($messagereceived);
			}
		}
		
		$this->output();
	}
	
	public function count()
	{
		
	}
	
	public function detail()
	{
		
	}
	
	//通过号码检查是否存在，如存在返回该条数据
	public function exists()
	{
		if (empty($this->input['coul'])){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			$coul = $this->input['coul'];
			$coulvalue = $this->input['coulvalue'];
			$info = $this->messagereceived->exists($coul,$coulvalue);
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



$out = new messagereceivedApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>