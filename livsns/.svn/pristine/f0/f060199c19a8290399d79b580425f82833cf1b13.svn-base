<?php 
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','greet_result');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH.'lib/functions.php');
require(ROOT_DIR.'lib/class/curl.class.php');
class greeting extends coreFrm
{
	public function show()
	{
		$id = intval($this->input['person_id']);
		$this->curl = new curl($this->settings['App_feedback']['host'],$this->settings['App_feedback']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->mReturnType = 'str';
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'get_greeting_result');
		$data = $this->curl->request('feedback.php');
		echo $data;
		exit();
	}
	
}
$greeting = new greeting();
$greeting->show();
?>
