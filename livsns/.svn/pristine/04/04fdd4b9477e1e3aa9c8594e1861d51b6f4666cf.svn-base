<?php
require('./global.php');
define('SCRIPT_NAME', 'violation');
define('MOD_UNIQUEID','violation');//应用标识
require(ROOT_PATH . 'lib/class/curl.class.php');
class violation extends adminBase
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	function detail(){}
	function count(){}
	function newshow()
	{
		$type = $this->input['type'];     		//汽车类别
		$code = $this->input['code'];			//汽车牌照 苏D 不用
		$drv  = $this->input['drv'];			//发动机号   后六位
		
		$url = "www.zhong5.cn/";
		$this->curl = new curl($url);
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('xml');
		$this->curl->initPostData();
		$this->curl->addRequestData('mod','record');
		$this->curl->addRequestData('action','xml');
		$this->curl->addRequestData('type',$type);
		$this->curl->addRequestData('code',$code);
		$this->curl->addRequestData('drv',$drv);
		$ret = $this->curl->request('tmplugin.php');

		$ret = $this->xml2Array($ret);
		$this->addItem_withkey('lastupdate', $ret['lastupdate']);
		
		if ($ret['record']['datetime'])
		{
			$this->addItem_withkey('record', array($ret['record']));
		}
		else
		{
			$this->addItem_withkey('record', $ret['record']);
		}
		$this->output();
	}
	function show()
	{
		$type = $this->input['type'];     		//汽车类别
		$code = $this->input['code'];			//汽车牌照 苏D 不用
		$drv  = $this->input['drv'];			//发动机号   后六位
		
		$url = "www.zhong5.cn/";
		$this->curl = new curl($url);
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('xml');
		$this->curl->initPostData();
		$this->curl->addRequestData('mod','record');
		$this->curl->addRequestData('action','xml');
		$this->curl->addRequestData('type',$type);
		$this->curl->addRequestData('code',$code);
		$this->curl->addRequestData('drv',$drv);
		$ret = $this->curl->request('tmplugin.php');

		$ret = $this->xml2Array($ret);

		if (is_array($ret['record']))
		{
			if ($ret['record']['datetime'])
			{
				$this->addItem($ret['record']);
			}
			else
			{
				foreach($ret['record'] AS $v)
				{
					$this->addItem($v);
				}
			}
		}
		$this->output();
	}
	
	private function xml2Array($xml) 
	{
		$this->normalizeSimpleXML(simplexml_load_string($xml,null,LIBXML_NOCDATA), $result);
		return $result;
	}
	
	private function normalizeSimpleXML($obj, &$result) 
	{
		$data = $obj;
		if (is_object($data)) 
		{
			$data = get_object_vars($data);
		}
		if (is_array($data)) 
		{
			foreach ($data as $key => $value) 
			{
				$res = null;
				$this->normalizeSimpleXML($value, $res);
				if (($key == '@attributes') && ($key)) 
				{
					$result = $res;
				}
				else 
				{
					$result[$key] = $res;
				}
			}
		}
		else
		{
			$result = $data;
		}
	}	
	
}
include(ROOT_PATH . 'excute.php');
?>
