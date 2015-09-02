<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: drag_order.php 26623 2013-07-29 14:43:20Z wangleyuan $
***************************************************************************/

require_once('global.php');
define('MOD_UNIQUEID','tuji');
class dragOrderApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	/**
	 *排序
	 */
	public function drag_order()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$table_name = 'tuji';
		$order_name = urldecode($this->input['order_name']);
	
		$ids       = explode(',',urldecode($this->input['video_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));

		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . $table_name . " SET " . $order_name . " = '" . $order_ids[$k] . "' WHERE id = '" . $v . "'";
			$this->db->query($sql);
		}
		$ids = implode(',',$ids);
		$sql = "SELECT * FROM " . DB_PREFIX . $table_name ." WHERE id IN(" . $ids . ")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			if(!empty($row['column_id']) && !empty($row['expand_id']))
			{
				publish_insert_query($row, 'update');
			}
		}		
		$ids = explode(',',$ids);
		$this->addItem($ids);
		$this->output();
	}
}
$out = new dragOrderApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'drag_order';
}
$out->$action();
?>