<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class recolumn extends LivcmsFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	protected function getSiteinfo()
	{
	}
	public function show()
	{
		$sql = 'SELECT columnid,colname FROM '.DB_PREFIX.'column' . $this->get_condition();
		$qu = $this->db->query($sql);
		$tmp = array();
		while($row = $this->db->fetch_array($qu))
		{
			$tmp[$row['columnid']] = $row['colname'];
		}
		$this->addItem($tmp);
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		if($this->input['siteid'])
		{
			$condition .= ' AND siteid = '.intval($this->input['siteid']);
		}
		if($this->input['columnid'])
		{
			$condition .= ' AND columnid IN('.urldecode($this->input['columnid']).')';
		}
		return $condition;
	}
}
/**
 *  程序入口
 */
$out = new recolumn();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();