<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: userprivacy.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'userprivacy');
require('./global.php');

class setPrivacy extends uiBaseFrm
{
	private $curl;
	
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->check_login();
		$this->curl = new curl();
		$this->load_lang('authority');
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$authority = $this->get();	
		$this->page_title =  $this->lang['pageTitle'];	
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'userprivacy.js');
		$gScriptName = SCRIPTNAME;

		$this->tpl->addVar('authority', $authority);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('userprivacy');
	}
	
	public function get()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$authority = $this->curl->request('users/get_authority.php');
		return $authority[0];	
	}
		
	public function set()
	{
		$visit_user_info = $this->input['visit_user_info'];					
		$comment = $this->input['comment'];
		$search_true_name = $this->input['search_true_name'];
		$follow = $this->input['follow'];

		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('visit_user_info', $visit_user_info);
		$this->curl->addRequestData('comment', $comment);
		$this->curl->addRequestData('search_true_name', $search_true_name);
		$this->curl->addRequestData('follow', $follow);
		$this->curl->request('users/authority.php');
	}	
}

$out = new setPrivacy();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>