<?php
/***************************************************************************
* $Id: sys_update.php 22758 2013-05-24 07:36:11Z lijiaying $
***************************************************************************/
//define('DEBUG_MODE','schedule');
define('MOD_UNIQUEID','logs');
require('global.php');
class logs extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function create()
	{
		$mark = $this->input['mark'];
		$title = $this->input['title'];
		$type = intval($this->input['type']);
		if (!$mark)
		{
			$this->errorOutput('NO_MARK');
		}
		if (!$title)
		{
			$this->errorOutput('NO_TITLE');
		}
		$content = $this->input['content'];
		$sql = 'REPLACE INTO ' . DB_PREFIX . "logs (mark, title, content, type, create_time) VALUES ('$mark', '$title', '$content', $type, " . time() . ")";

		$this->db->query($sql);
		$data = array(
			'id' => $this->db->insert_id()	
		);
		$this->addItem($data);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
	
}
$out = new logs();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>