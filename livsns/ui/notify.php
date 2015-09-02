<?php
/***************************************************************************

*
* $Id: notify.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');


class notifyApi extends uiBaseFrm
{
	function __construct()
	{
		parent::__construct();
		$this->check_login();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->load_lang('notify');
		$this->curl = new curl();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$unreadNum = $this->action($this->user['id'],'count');
		$notifyInfo = $this->action($this->user['id'],'get');
		$this->page_title = $this->lang['pageTitle'];
		//include hg_load_template('notify');

		$this->tpl->addVar('unreadNum', $total_nums);
		$this->tpl->addVar('notifyInfo', $notifyInfo);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('notify');

	}
	function action($id,$a='count',$content='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('a', $a);
		if(!empty($content))
			$this->curl->addRequestData('content', $content);
		return $this->curl->request('users/notify.php');	
	}
	
}
$out = new notifyApi();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>