<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: output_stream_update.php 5093 2011-11-16 09:50:56Z repheal $
***************************************************************************/
require('global.php');
class outputStreamUpdateApi extends adminUpdateBase
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

	function create()
	{
		$info = array(
			'encode_id' => trim($this->input['encode_id']),
			'name' => trim(urldecode($this->input['name'])),
			'stream' => trim(urldecode($this->input['stream'])),
			'port' => trim(urldecode($this->input['port'])),
			'is_used' => 1,
		);
		if(!$info['encode_id'] && !$info['name'] && !$info['stream'] && !$info['port'])
		{
			$this->errorOutput("参数不完整！");
		}
		
		$name_tips = $this->outputStream->verify(array('name' => trim(urldecode($this->input['name']))));
		if($name_tips)
		{
			$this->errorOutput("该编码器中流名称已存在！");
		}

		$stream_tips = $this->outputStream->verify(array('stream' => trim(urldecode($this->input['stream']))));
		if($stream_tips)
		{
			$this->errorOutput("该编码器中码流已存在！");
		}

		$port_tips = $this->outputStream->verify(array('port' => trim(urldecode($this->input['port']))));
		if($port_tips)
		{
			$this->errorOutput("该编码器中端口号已存在！");
		}

		$info = $this->outputStream->create();
		if(!$info)
		{
			$this->errorOutput("创建失败！");
		}

		$this->addItem($info);
		$this->output();
	}

	function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("未传入ID");
		}
		$info = $this->outputStream->update();
		if(!$info)
		{
			$this->errorOutput("更新失败！");
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function delete()
	{
		
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

	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new outputStreamUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			