<?php
/*
 * Created on 2012-12-10
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 define('ROOT_DIR', './');
 define('SCRIPT_NAME', 'redirect');
 define('WITH_DB', true);
 require_once('./global.php');
 require_once(ROOT_PATH . 'lib/class/curl.class.php');
 class redirect extends uiBaseFrm
 {
 	function __construct()
 	{
 		parent::__construct();
 		$this->curl = new curl($this->settings['App_publishcontent']['host'], $this->settings['App_publishcontent']['dir']);
 	}
 	
 	function __destruct()
 	{
 		parent::__destruct();
 	}
 	
 	function show()
 	{
 		$content_id = $this->input['id'];
 		if(!$content_id)
 		{
 			$this->ReportError('NOID');
 		}
 		$this->curl->initPostData();
		$this->curl->setSubmitType('post');
		$this->curl->addRequestData('a','get_content');
		$this->curl->addRequestData('content_id', $content_id);
		$ret = $this->curl->request('content.php');
		if(!$ret[0]['id'])
		{
			$this->ReportError('发布内容已经被撤回!');
		}
		$url = $ret[0]['content_url'];
		if (substr($url, 0, 7) != 'http://')
		{
			$link = explode('#', $url);
			if ($link[1])
			{
				$this->curl->initPostData();
				$this->curl->setSubmitType('post');
				$this->curl->addRequestData('a','get_content');
				$this->curl->addRequestData('count','1');
				$this->curl->addRequestData('bundle_id', $link[0]);
				$this->curl->addRequestData('cid', $link[1]);
				$ret = $this->curl->request('content.php');
				$url = $ret[0]['content_url'];
				header('Location:' . $url);
				exit();
			}                        
			else
			{
					exit('未设置网站页面，无法访问');
			}
			if ($this->settings['apppage'])
			{
				$url = $this->settings['apppage'];
			}
			else
			{
				exit('客户端跳转规则，不能直接访问');
			}
		}
		header('Location:' . $url);
		exit();
 	}
 }
 include (ROOT_PATH . 'lib/exec.php');
?>
