<?php
define('ROOT_DIR', '../../');
define('SCRIPT_NAME', 'control');
define('MOD_UNIQUEID','control');
require(ROOT_DIR.'global.php');
class control extends coreFrm
{
	protected $user;
	public function __construct()
	{
		parent::__construct();
		$this->user = $this->verifyToken();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$clock = 1;
		$identifier = $this->input['identifier'];
		if(!$identifier)
		{
			$this->errorOutput(INVALID_IDENTIFIER);
		}
		$sql = 'SELECT status FROM '.DB_PREFIX.'device_token WHERE identifier = "'.$identifier.'"';
		while(True)
		{
			if($clock >= 15)
			{
				$status = array('status'=>0);
				break;
			}
			$status = $this->db->query_first($sql);
			if($status['status'])
			{
				$sql = 'UPDATE '.DB_PREFIX.'device_token SET status=0 WHERE identifier = "'.$identifier.'"';
				$this->db->query($sql);
				$status['status'] = intval($status['status']);
				break;
			}
			$clock++;
			sleep(SLEEP_TIME);
		}
		$this->addItem($status);
		$this->output();
		
	}
	public function update_status()
	{
		$identifier = $this->input['identifier'];
		if(!$identifier)
		{
			$this->errorOutput(INVALID_IDENTIFIER);
		}
		$sql = "UPDATE ".DB_PREFIX."device_token SET status = ".intval($this->input['status']).' WHERE identifier="'.$identifier.'"';
		$this->db->query($sql);
		$this->addItem(array('status'=>intval($this->input['status'])));
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';
?>