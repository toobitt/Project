<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: drag_order.php 17934 2013-02-26 01:52:15Z repheal $
***************************************************************************/
require_once('global.php');
class dragOrderApi extends appCommonFrm
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:video_id(视频的id可以多个),order_id(视频的排序id),table_name(需要排序的表名)
	 *功能:对视频列表进行排序操作
	 *返回值:将视频id以逗号隔开，字符串的形式返回
	 * */
	public function drag_order()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$table_name = urldecode($this->input['table_name']);
		$order_name = urldecode($this->input['order_name']);
	
		$ids       = explode(',',urldecode($this->input['video_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));

		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . $table_name . " SET " . $order_name . " = '" . $order_ids[$k] . "' WHERE id = '" . $v . "'";
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();
	}
}

$out = new dragOrderApi();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'drag_order';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>