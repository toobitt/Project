<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 343 2011-11-26 06:12:05Z develop_tong $
***************************************************************************/ 
define('ROOT_DIR', './');
define('SCRIPT_NAME', '_default');
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
class _default extends uiBaseFrm
{	
	private $curl;
	function __construct()
	{
		parent::__construct();		
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->ReportError('对不起，您没有权限进行升级');
		}
		$this->curl = new curl('host.cloud.hogesoft.com', '');
		$this->curl->setSubmitType('post');
		$this->curl->setCurlTimeOut(15);
		$this->curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
		$this->curl->setToken('');
		$this->curl->setReturnFormat('json');
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{	
		$hosts = $this->curl->request('host.php');
		$rdbs = $this->curl->request('rdb.php');
		$this->tpl->addVar('hosts', $hosts);
		$this->tpl->addVar('rdbs', $rdbs);
		$this->tpl->outTemplate('hosts');
	}	
	public function syscslowlog()
	{
		$rdb_id = $this->input['rdb_id'];
		if (!$this->canop())
		{
			$title = '操作进行，请稍侯....';
			$callback = "hg_hostop_callback('{$rdb_id}', 'sync')";
			$this->redirect($title, '?show', 0, '', $callback);
		}
		file_put_contents(CACHE_DIR . 'host_op_lock', time());
		$this->curl->initPostData(); 
		$this->curl->addRequestData('a', 'syscslowlog');
		$this->curl->addRequestData('zone', $this->input['zone']);
		$this->curl->addRequestData('rdb_id', $rdb_id);
		$hosts = $this->curl->request('rdb.php');
		if (!$hosts['ret_code'])
		{
			$title = '同步成功';
			$callback = "hg_hostop_callback('{$rdb_id}', 'sync')";
		}
		else
		{
			$title = '同步失败，' . $hosts['message'];
			$callback = "hg_hostop_callback('{$rdb_id}', 'sync', '{$title}')";
		}
		$this->redirect($title, '?show', 0, '', $callback);
	}	
	
	public function boot()
	{
		$instance_id = $this->input['instance_id'];
		if (!$this->canop())
		{
			$title = '操作进行，请稍侯....';
			$callback = "hg_hostop_callback('{$instance_id}', 'boot')";
			$this->redirect($title, '?show', 0, '', $callback);
		}
		file_put_contents(CACHE_DIR . 'host_op_lock', time());
		$instance_id = $this->input['instance_id'];
		$this->curl->initPostData(); 
		$this->curl->addRequestData('a', 'boot');
		$this->curl->addRequestData('zone', $this->input['zone']);
		$this->curl->addRequestData('instance_id', $instance_id);
		$hosts = $this->curl->request('host.php');
		if (!$hosts['ret_code'])
		{
			$title = '启动中....';
			$callback = "hg_hostop_callback('{$instance_id}', 'boot')";
		}
		else
		{
			$title = '启动失败，' . $hosts['message'];
			$callback = "hg_hostop_callback('{$instance_id}', 'fail', '{$title}')";
		}
		$this->redirect($title, '?show', 0, '', $callback);
	}	
	public function reboot()
	{
		$instance_id = $this->input['instance_id'];
		if (!$this->canop())
		{
			$title = '操作进行，请稍侯....';
			$callback = "hg_hostop_callback('{$instance_id}', 'reboot')";
			$this->redirect($title, '?show', 0, '', $callback);
		}
		file_put_contents(CACHE_DIR . 'host_op_lock', time());
		$instance_id = $this->input['instance_id'];
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'reboot');
		$this->curl->addRequestData('zone', $this->input['zone']);
		$this->curl->addRequestData('instance_id', $instance_id);
		$hosts = $this->curl->request('host.php');

		if (!$hosts['ret_code'])
		{
			$title = '重启中....';
			$callback = "hg_hostop_callback('{$instance_id}', 'reboot')";
		}
		else
		{
			$title = '重启失败，' . $hosts['message'];
			$callback = "hg_hostop_callback('{$instance_id}', 'fail', '{$title}')";
		}
		$this->redirect($title, '?show', 0, '', $callback);
	}	
	public function shutdown()
	{
		$instance_id = $this->input['instance_id'];
		if (!$this->canop())
		{
			$title = '操作进行，请稍侯....';
			$callback = "hg_hostop_callback('{$instance_id}', 'shutdown')";
			$this->redirect($title, '?show', 0, '', $callback);
		}
		file_put_contents(CACHE_DIR . 'host_op_lock', time());
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'shutdown');
		$this->curl->addRequestData('zone', $this->input['zone']);
		$this->curl->addRequestData('instance_id', $instance_id);
		$this->curl->addRequestData('force', 1);
		$hosts = $this->curl->request('host.php');

		if (!$hosts['ret_code'])
		{
			$title = '关机中....';
			$callback = "hg_hostop_callback('{$instance_id}', 'shutdown')";
		}
		else
		{
			$title = '关机失败，' . $hosts['message'];
			$callback = "hg_hostop_callback('{$instance_id}', 'fail', '{$title}')";
		}
		$this->redirect($title, '?show', 0, '', $callback);
	}
	
	private function canop()
	{
		$optime = @file_get_contents(CACHE_DIR . 'host_op_lock');
		if ((time() - $optime) < 20)
		{
			return false;
		}
		return true;
	}	
} 
include (ROOT_PATH . 'lib/exec.php');
?>