<?php
abstract class videoCloud
{	
	protected $input = array();	
	protected $settings = array();
	protected $mFiles = array();	
	protected $mVideoInfo = array(
        		'content_id' => 0,
        		'extend_data' => '',
        		'notranscode' => 1,
    );
	protected $db;
	
	public function __construct()
	{
	}

	public function __destruct()
	{
	}
	
	public function setSettings($settings)
	{
		$this->settings = $settings;
	}
	
	public function setInput($input)
	{
		$this->input = $input;
	}
	
	public function setFiles($file)
	{
		$this->mFiles = $file;
	}
	public function getVideoInfo()
	{
		return $this->mVideoInfo;
	}
	public function setDB($db)
	{
		$this->db = $db;
	}
	protected function setVideoInfo($videoinfo)
	{
		$this->mVideoInfo = $videoinfo;
        	file_put_contents(CACHE_DIR . 'le.txt',var_export($videoinfo, 1), FILE_APPEND);
	}
}
?>