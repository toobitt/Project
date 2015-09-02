<?php 
define('MOD_UNIQUEID','auth');
require('./global.php');
define('SCRIPT_NAME', 'set_dynamic_token');
class set_dynamic_token extends coreFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		if(!$this->input['user_id'])
		{
			$this->errorOutput(NO_USER_ID);
		}
		$n ='';
		for($i = 0;$i<DYNAMIC_TOKEN_LEN;$i++)
		{
			$n .= rand(0,9);
		}
		$sql = 'REPLACE INTO '.DB_PREFIX.'dynamic_sec value('.intval($this->input['user_id']).', "'.$n.'", "'.TIMENOW.'")';
		$this->db->query($sql);
		$this->addItem(array('token'=>$n));
		$this->output();
	}

	public function verifyToken()
	{

	}
	public function get_dynamic_token()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'dynamic_sec WHERE user_id = '.intval($this->input['user_id']);
		$row = $this->db->query_first($sql);
		$this->addItem(array('dynamic_token'=>$row['dynamic_token']));
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>