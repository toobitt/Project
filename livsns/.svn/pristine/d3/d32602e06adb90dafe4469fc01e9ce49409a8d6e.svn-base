<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:  $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class getOneMsgApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_one_msg()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NEED_LOGIN);
		}
		$this->setXmlNode("Sessions","Message"); 
		$sid = intval($this->input['sid']); 
		
		$pm = array();  
		 
		$sql = 'select sessionId from ' . DB_PREFIX . 's_pm where sid=' . $sid;
		$qq = $this->db->query_first($sql);
		$qq = $qq['sessionId'];
		if(!$qq)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else
		{
			$sql_ = 'SELECT * 
					FROM `' . DB_PREFIX . 'pm` 
					WHERE sid =' . $sid . '
					ORDER BY stime ASC ';
			$qid = $this->db->query($sql_);
			while( false != ($row = $this->db->fetch_array($qid)))
			{
				$pm[$qq][$row['pid']] = $row;	
			}
		}
		if(!empty($pm))
		{
			$this->addItem($pm);
			$this->output();
		}
	}
}

$getOneMsgApi = new getOneMsgApi();
$getOneMsgApi->get_one_msg();
?>