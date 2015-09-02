<?php
	define('WITH_DB',true);
	define('ROOT_DIR', './');
	define('SCRIPT_NAME','vote');
	require('./global.php');
	include_once('./lib/class/curl.class.php');
	class vote_add extends uiBaseFrm
	{
		private $curl;
		function __construct()
		{
			parent::__construct();
			$this->init();
			
		}
		function __destruct()
		{
			parent::__destruct();
		} 
		
		function init()
		{
		}
		function add_vote()
		{
			$this->curl = new curl($this->settings['App_vote']['host'],$this->settings['App_vote']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->initPostData();
			$this->curl->addRequestData('id', intval($this->input['id']));
			$this->curl->addRequestData('option_id', intval($this->input['option_id']));
			$this->curl->addRequestData('verify_code', trim($this->input['verify_code']));
			$this->curl->addRequestData('other_title', trim($this->input['other_title']));
			$this->curl->addRequestData('session_id', $_COOKIE['PHPSESSID']);
			$this->curl->addRequestData('a', 'vote_add');
			$retrun = $this->curl->request('vote_update');
			echo json_encode($retrun);
		}
	}
	//include('lib/exec.php');
	$obj = new vote_add();
	$obj->add_vote();
?>