<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: encode_update.php 5093 2011-11-16 09:50:56Z repheal $
***************************************************************************/
require('global.php');
class encodeUpdateApi extends adminUpdateBase
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

	function create()
	{
		$info = array(
			'name' => trim(urldecode($this->input['name'])),	
			'ip' => trim(urldecode($this->input['ip'])),
			'is_used' => trim($this->input['is_used'])? 1 : 0,
			'stream' => '',
		);

		$num = count($this->settings['stream_port']);
		for($i = 1; $i <= $num; $i++)
		{
			if($this->input['num'][$i])
			{
				$info['stream']['id'][$i] = $this->input['out_id'][$i]?$this->input['out_id'][$i]:0;
				$info['stream']['num'][$i] = $this->input['num'][$i];
				$info['stream']['name'][$i] = urldecode($this->input['out_name'][$i]);
				$info['stream']['stream'][$i] = urldecode($this->input['stream'][$i]);
				$info['stream']['port'][$i] = $this->input['port'][$i];
			}
		}

		if(count($info['stream']['name']) != count(array_unique($info['stream']['name'])))
		{
			$this->errorOutput("同一个编码器下流名称不相同！");
		}

		if(count($info['stream']['stream']) != count(array_unique($info['stream']['stream'])))
		{
			$this->errorOutput("同一个编码器下码流不相同！");
		}

		if(count($info['stream']['port']) != count(array_unique($info['stream']['port'])))
		{
			$this->errorOutput("同一个编码器下端口号不相同！");
		}

		if(!$info['name'])
		{
			$this->errorOutput("编码器名称不为空！");
		}

		if(!$info['ip'])
		{
			$this->errorOutput("编码器IP不为空！");
		}

		$name_tips = $this->encode->verify(array('name' => $info['name']));
		if($name_tips)
		{
			$this->errorOutput("编码器名称已存在！");
		}

		$ip_tips = $this->encode->verify(array('ip' => $info['ip']));
		if($ip_tips)
		{
			$this->errorOutput("编码器IP已存在！");
		}

		$info = $this->encode->create($info);
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
		$info = array(
			'name' => trim(urldecode($this->input['name']))?trim(urldecode($this->input['name'])):'',	
			'ip' => trim(urldecode($this->input['ip']))? trim(urldecode($this->input['ip'])):'',
			'is_used' => trim($this->input['is_used'])?1:0,	
			'stream' => '',
		);	
		
		$num = count($this->settings['stream_port']);
		for($i = 1; $i <= $num; $i++)
		{
			if($this->input['num'][$i])
			{
				$info['stream']['id'][$i] = $this->input['out_id'][$i]?$this->input['out_id'][$i]:0;
				$info['stream']['num'][$i] = $this->input['num'][$i];
				$info['stream']['name'][$i] = urldecode($this->input['out_name'][$i]);
				$info['stream']['stream'][$i] = urldecode($this->input['stream'][$i]);
				$info['stream']['port'][$i] = $this->input['port'][$i];
			}
			else
			{
				if( $this->input['out_id'][$i])
				{
					$info['stream']['id'][$i] = $this->input['out_id'][$i];
				}
			}
		}

		if(count($info['stream']['name']) && count($info['stream']['name']) != count(array_unique($info['stream']['name'])))
		{
			$this->errorOutput("同一个编码器下流名称不相同！");
		}

		if(count($info['stream']['stream']) && count($info['stream']['stream']) != count(array_unique($info['stream']['stream'])))
		{
			$this->errorOutput("同一个编码器下码流不相同！");
		}

		if(count($info['stream']['port']) && count($info['stream']['port']) != count(array_unique($info['stream']['port'])))
		{
			$this->errorOutput("同一个编码器下端口号不相同！");
		}

		if(!$info['name'])
		{
			$this->errorOutput("编码器名称不为空！");
		}

		if(!$info['ip'])
		{
			$this->errorOutput("编码器IP不为空！");
		}

		$name_tips = $this->encode->verify(array('name' => $info['name']));
		if($name_tips && $name_tips['id'] != $this->input['id'])
		{
			$this->errorOutput("编码器名称已存在！");
		}

		$ip_tips = $this->encode->verify(array('ip' => $info['ip']));
		if($ip_tips && $ip_tips['id'] != $this->input['id'])
		{
			$this->errorOutput("编码器IP已存在！");
		}

		$info = $this->encode->update($info,$this->input['id']);
		
		if(!$info)
		{
			$this->errorOutput("更新失败！");
		}
		$this->addItem($info);
		$this->output();
	}

	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("未传入ID");
		}

		$info = $this->encode->delete($this->input['id']);

		if(!$info)
		{
			$this->errorOutput("删除失败！");
		}
		$this->addItem('删除成功！');
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

	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new encodeUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			