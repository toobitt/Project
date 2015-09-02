<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 343 2011-11-26 06:12:05Z develop_tong $
***************************************************************************/
//define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'status');
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
class status extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}


	public function show()
	{
		$this->ctrl_status();
		$this->trans_status();
	}

	public function ctrl_status()
	{
		$configs = $this->settings['App_mediaserver'];;
		$this->curl = new curl($configs['host'], $configs['dir'], $configs['token']);
		
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$ctrl_status = $this->curl->request('ctrl_status.php');
		$this->tpl->addVar('ctrl_status', $ctrl_status);
		$this->tpl->addVar('media_api', $configs);
		$this->tpl->outTemplate('_ctrl_status', 'hg_set_dom_html,ctrl_state');
	}
	
	public function stream_status()
	{
		$configs = $this->settings['App_live'];;
		$this->curl = new curl($configs['host'], $configs['dir'] . 'admin/', $configs['token']);
		
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');
		$total = $this->curl->request('stream.php');
		$this->curl->addRequestData('s_status', '0');
		$norun = $this->curl->request('stream.php');
		$stream_status = array();
		$stream_status['total'] = intval($total['total']);
		$stream_status['norun'] = intval($norun['total']);
		$this->tpl->addVar('stream_status', $stream_status);
		$this->tpl->outTemplate('_stream_status', 'hg_set_dom_html,stream_state');
	}
	public function channel_status()
	{
		$configs = $this->settings['App_live'];;
		$this->curl = new curl($configs['host'], $configs['dir'] . 'admin/', $configs['token']);
		
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');
		$total = $this->curl->request('channel.php');
		$this->curl->addRequestData('stream_state', '0');
		$norun = $this->curl->request('channel.php');
		$channel_status = array();
		$channel_status['total'] = intval($total['total']);
		$channel_status['norun'] = intval($norun['total']);
		$this->tpl->addVar('channel_status', $channel_status);
		$this->tpl->outTemplate('_channel_status', 'hg_set_dom_html,channel_state');
	}

	public function cron_status()
	{
		include(ROOT_DIR . 'lib/class/cron.class.php');
		$crond = new crond();
		if ($this->settings['croncmd'])
		{
			$crond->setCronCmd($this->settings['croncmd']);
		}
		$cron_status = $crond->isRun();
		$this->tpl->addVar('cron_status', $cron_status);
		$this->tpl->outTemplate('_cron_status', 'hg_set_dom_html,crontab_state');
	}


	public function trans_status()
	{
		$configs = $this->settings['App_upserver'];
		$this->curl = new curl($configs['host'], $configs['dir'], $configs['token']);
		
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'view');
		$trans_status = $this->curl->request('control.php');
		$trans_status['runtime'] = hg_sec2str(TIMENOW - $trans_status['starttime']);
		$this->tpl->addVar('trans_status', $trans_status);
		$this->tpl->addVar('_user', $this->user);
		$this->tpl->outTemplate('_trans_status', 'hg_set_dom_html,trans_state');
	}

	public function trans_start()
	{
		$configs = $this->settings['App_upserver'];
		$this->curl = new curl($configs['host'], $configs['dir'], $configs['token']);
		
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'start');
		$trans_status = $this->curl->request('control.php');
		$this->input['goon'] = 1;
		$this->redirect($trans_status['msg'], 'default.php');
	}
	
	public function trans_stop()
	{
		$configs = $this->settings['App_upserver'];
		$this->curl = new curl($configs['host'], $configs['dir'], $configs['token']);
		
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'stop');
		$trans_status = $this->curl->request('control.php');
		$this->input['goon'] = 1;
		$this->redirect($trans_status['msg'], 'default.php');
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>