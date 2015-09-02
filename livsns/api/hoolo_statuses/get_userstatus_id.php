<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: get_userstatus_id.php 2774 2011-03-15 06:58:54Z wang $
***************************************************************************/
define('ROOT_DIR', '../../');
require_once (ROOT_DIR . 'global.php');
class get_userstatus_id extends BaseFrm
{
	var $trans = array();
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	/**
	 *  获取当前登录用户发布的点滴消息列表
	 */
	public function show()
	{	
		$this->setXmlNode('statuses','status');
		$u_id = intval($this->input['user_id']);
		if (!$u_id)
		{
			$this->errorOutput(PARA_ERROR, 400);
		}
		$this->input['count'] = 300;
		$count = intval($this->input['count']);
		
		//取得本人用户的点滴信息
		$this->end = "limit 0 , $count";	
		$sql = "SELECT id FROM " . DB_PREFIX . "status where member_id=$u_id AND status=0 ORDER BY id DESC  ".$this->end;
		$result = $this->db->query($sql);
		if (!$this->db->num_rows($result))
		{
			$this->errorOutput(NO_INFO, 200);
		}
		while($row = $this->db->fetch_array($result))
		{
			$this->addItem($row);
		}
		$this->output();
	}	
}
$out = new get_userstatus_id();
$out->show();
?>