<?php
	/*
	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0)
	{
		
		header("HTTP/1.1 500 File Upload Error");
		if (isset($_FILES["Filedata"]))
		{
			echo $_FILES["Filedata"]["error"];
		}
		exit(0);
	}
	//move_uploaded_file($_FILES["Filedata"]["tmp_name"],$_FILES["Filedata"]["name"]);
	 */
	define('WITH_DB',true);
	define('ROOT_DIR', './');
	define('SCRIPT_NAME','upload');
	define('WITHOUT_LOGIN',true);
	require('./global.php');
	include_once('./lib/class/curl.class.php');
	class upload extends uiBaseFrm
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
			if(!$this->input['mid'])
			{
				echo "0x0000";
				exit(0);
			}	
		}
		function uploadApi()
		{
			$sql = "SELECT host,dir FROM ".DB_PREFIX."applications  WHERE  id = (SELECT application_id  FROM ".DB_PREFIX."modules  WHERE  id = ".intval($this->input['mid']).")";
			$url = $this->db->query_first($sql);
			
			$sql = "SELECT file_name,file_type FROM ".DB_PREFIX."module_op  WHERE  func_name = 'upload'  AND  module_id = ".intval($this->input['mid']);
		    $file = $this->db->query_first($sql);
		    
			$this->curl = new curl($url['host'],$url['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->initPostData();
			$this->curl->addRequestData('mid', intval($this->input['mid']));
			$this->curl->addRequestData('id', intval($this->input['id']));
			$this->curl->addRequestData('a', 'upload');
			$retrun = $this->curl->request($file['file_name'].$file['file_type']);
			echo json_encode($retrun);
		}
	}
	//include('lib/exec.php');
	$obj = new upload();
	$obj->uploadApi();
?>