<?php
define('MOD_UNIQUEID','news');//模块标识
require_once('global.php');
class  new_drag_order extends appCommonFrm
{
    function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:video_id(新闻的id可以多个),order_id(新闻的排序id),table_name(需要排序的表名)
	 *功能:对新闻列表进行排序操作
	 *返回值:将新闻id以逗号隔开，字符串的形式返回
	 * */
	public function drag_order()
	{
		if(!$this->input['content_id'])
		{
			$this->errorOutput(NOID);
		}	
		$ids       = explode(',',urldecode($this->input['content_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " .DB_PREFIX . "article  SET order_id = '".$order_ids[$k]."'  WHERE id = '".$v."'";
			$this->db->query($sql);
		}
		$ids = implode(',',$ids);
		$sql = "SELECT * FROM " . DB_PREFIX ."article WHERE id IN(" . $ids . ")";
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

$out = new new_drag_order();
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