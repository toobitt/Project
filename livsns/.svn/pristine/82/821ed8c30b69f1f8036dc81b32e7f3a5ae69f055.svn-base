<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID','adv');//模块标识
class statistics extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//广告输出和点击总数的统计
	function increase($pubid=0)
	{
		$pubid = intval($pubid);
		if(!$pubid)
		{
			return false;
		}
		$sql = 'SELECT pubid FROM '.DB_PREFIX.'statistics WHERE pubid = '.$pubid;
		$ret = $this->db->query_first($sql);
		if(!empty($ret))
		{
			$sql = 'UPDATE '.DB_PREFIX.'statistics SET output = output+1 WHERE pubid='.$pubid;
		}
		else
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'statistics values('.$pubid.',1,0)';
		}
		$this->db->query($sql);
		return true;
	}
}
?>