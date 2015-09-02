<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: drag_order.php 6224 2012-03-28 09:17:18Z lijiaying $
***************************************************************************/
require_once('global.php');
class dragOrderApi extends adminBase
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
	 *功能:对列表进行排序操作
	 *返回值:将视频id以逗号隔开，字符串的形式返回
	 * */
	public function drag_order()
	{
		$sort = json_decode(html_entity_decode($this->input['sort']),true);
		if(!empty($sort))
		{
			foreach($sort as $key=>$val)
			{
				$data = array(
					'order_id' => $val,
				);
				if(intval($key) && intval($val))
				{
					$sql ="UPDATE " . DB_PREFIX . "vote_node SET";
		
					$sql_extra=$space=' ';
					foreach($data as $k => $v)
					{
						$sql_extra .=$space . $k . "='" . $v . "'";
						$space=',';
					}
					$sql .=$sql_extra.' WHERE id='.$key;
					$this->db->query($sql);
				}
			}
		}
		$this->addItem('success');
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