<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: encode.php 5093 2011-11-16 09:50:56Z repheal $
***************************************************************************/
require('global.php');
class encodeApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/encode.class.php');
		$this->encode = new encode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		$condition = $this->get_condition();
		$info = $this->encode->show($condition);
		if($info)
		{
			foreach($info as $key => $value)
			{
				$value['is_used_show'] = $value['is_used'] ? '开启' : '关闭';
				$this->addItem($value);
			}
			$this->output();
		}
		else
		{
			$this->errorOutput("暂无数据");
		}
	}

	public function detail()
	{
		$info = $this->encode->detail($this->input['id']);
		$this->addItem($info);
		$this->output();
	}

	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->encode->count($condition);
		//暂时这样处理
		echo json_encode($info);
	}

	function verify()
	{
		$id = $this->input['id']? $this->input['id']: 0;
		if(trim(urldecode($this->input['name'])))
		{
			$name_tips = $this->encode->verify(array('name' => trim(urldecode($this->input['name']))));
			if($name_tips && $name_tips['id'] != $id)
			{
				$this->addItem(array('call' => 'name','error' => '编码器名称已存在！'));
				$this->output();
			}
			$this->addItem(array('call' => 'name'));
			$this->output();
		}
		
		if(trim(urldecode($this->input['ip'])))
		{
			$ip_tips = $this->encode->verify(array('ip' => trim(urldecode($this->input['ip']))));
			if($ip_tips && $ip_tips['id'] != $id)
			{				
				$this->addItem(array('call' => 'ip','error' => '编码器IP已存在！'));
				$this->output();
			}
			$this->addItem(array('call' => 'ip'));
			$this->output();
		}
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';
		if($this->input['channel_id']>0)
		{
			$condition .= ' AND channel_id=' . $this->input['channel_id'];
		}

		return $condition;
	}
	
	function index()
	{
		
	}

	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new encodeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			