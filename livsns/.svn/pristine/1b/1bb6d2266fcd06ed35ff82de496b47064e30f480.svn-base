<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: output_stream.php 5093 2011-11-16 09:50:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID', 'vod');
class outputStreamApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/output_stream.class.php');
		$this->outputStream = new outputStream();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}

	function show()
	{
		$info = $this->outputStream->show();
		if($info)
		{
			foreach($row as $key => $value)
			{
				$this->addItem($value);
			}
			$this->output();
		}
		else
		{
			$this->errorOutput("暂无数据");
		}
	}

	/**	根据编码器ID，获取输出流，若无，根据配置文件输出相应的空记录
	*
	*
	*/
	function showByEncode()
	{
		$info = $this->outputStream->show();
		$count = count($this->settings['stream_port']);
		if(count($info) != $count)
		{
			foreach($this->settings['stream_port'] as $key => $value)
			{
				if(!$info[$key])
				{
					$info[$key] = '';
				}
				$this->addItem($info[$key]);
			}
		}
		$this->output();
	}
	
	public function count()
	{
		$info = $this->outputStream->count();
		//暂时这样处理
		echo json_encode($info);
	}
	

	public function detail()
	{
		$info = $this->outputStream->detail();
		$this->addItem($info);
		$this->output();
	}

	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new outputStreamApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			