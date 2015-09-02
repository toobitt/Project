<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function create|update|delete|ping|unknow
* 
* $Id: vote.php 6440 2012-04-17 09:29:53Z lijiaying $
***************************************************************************/
require('global.php');
class appUpdateApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		require(CUR_CONF_PATH . 'lib/app.class.php');
		$this->mApp = new app();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		if (!$this->input['name'])
		{
			$this->errorOutput('应用名称不能为空');
		}

		if (!$this->input['url'])
		{
			$this->errorOutput('应用的主 URL 不能为空');
		}

		if (!$this->input['authkey'])
		{
			$this->errorOutput('通信密钥不能为空');
		}

		$info = $this->mApp->create();

		if ($info['appid'])
		{
			$info['conf'] = $this->mApp->getConfig();
			$info['conf']['UC_CONNECT'] = 'mysql';
			$info['conf']['UC_KEY'] = $this->input['authkey'];
			$info['conf']['UC_APPID'] = $info['appid'];
			$this->addItem($info);
		}
		else
		{
			switch ($info)
			{
				case 'app_add_url_invalid':
					$this->errorOutput('应用的主 URL 不合法！');
					break;
				case 'app_add_ip_invalid':
					$this->errorOutput('应用 IP 不合法！');
					break;
				case 'app_add_name_invalid':
					$this->errorOutput('应用名称已存在，请修改！');
					break;
				default:
					$this->errorOutput($info . '未知错误');
					break;
			}
		}
		
		$this->output();
	}

	public function update()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput('未传入应用ID');
		}

		if (!$this->input['name'])
		{
			$this->errorOutput('应用名称不能为空');
		}

		if (!$this->input['url'])
		{
			$this->errorOutput('应用的主 URL 不能为空');
		}

		if (!$this->input['authkey'])
		{
			$this->errorOutput('通信密钥不能为空');
		}

		$info = $this->mApp->update();
		
		if ($info['appid'])
		{
			$info['conf'] = $this->mApp->getConfig();
			$info['conf']['UC_CONNECT'] = 'mysql';
			$info['conf']['UC_KEY'] = $this->input['authkey'];
			$info['conf']['UC_APPID'] = $this->input['id'];
			$this->addItem($info);
		}
		else
		{
			$this->errorOutput('更新失败');
		}

		$this->output();
	}

	public function delete()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput('应用不存在或已被删除');
		}
		$info = $this->mApp->delete();
		$this->addItem($info);
		$this->output();
		
	}

	public function ping()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput('应用不存在或已被删除');
		}
		$info = $this->mApp->ping();

		$ret = '';
		if ($info == '1')
		{
			$ret = '通信成功';
		}
		else
		{
			$ret = '通信失败';
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('空方法');
	}

}
$out = new appUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>