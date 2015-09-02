<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* $Id: weight.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
header("Content-type:text/html;charset=utf-8");
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/weight.class.php';
define('MOD_UNIQUEID', 'weightset'); //模块标识

class weightApi extends outerReadBase
{
	private $weight;
	
	public function __construct()
	{
		parent::__construct();
		$this->weight = new weightClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->weight);
	}
		
	
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$weight_info = array();
		$weight_info = $this->weight->show($offset, $count, $condition);
		$this->setXmlNode('weight_info', 'weight');
		
		if ($weight_info)
		{
			foreach ($weight_info as $value)
			{
				$this->addItem($value);
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



$out = new weightApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>