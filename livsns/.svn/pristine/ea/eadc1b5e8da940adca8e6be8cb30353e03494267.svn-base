<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID','vod');
class  vod_video_drag extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*参数:video_id(视频的id可以多个),order_id(视频的排序id),
	 *功能:对视频列表进行排序操作
	 *返回值:将视频id以逗号隔开，字符串的形式返回
	 * */
	public function drag_order()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}
	
		$ids       = explode(',',urldecode($this->input['video_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));

		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " .DB_PREFIX."vodinfo  SET video_order_id = '".$order_ids[$k]."'  WHERE id = '".$v."'";
			$this->db->query($sql);
		}
		$ids = implode(',',$ids);
		$sql = "SELECT * FROM " . DB_PREFIX ."vodinfo WHERE id IN(" . $ids . ")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			if(!empty($row['column_id']) && !empty($row['expand_id']))
			{
				$this->publish_video($row['id'], 'update');
			}
		}		
		$ids = explode(',',$ids);
		$this->addItem(array('id' =>$ids));
		$this->output();
	}
	
	//插入发布队列
	private function publish_video($id,$op,$column_id = array())
	{
		$id = intval($id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
			
		$sql = "select * from " . DB_PREFIX ."vodinfo where id = " . $id;
		$info = $this->db->query_first($sql);
		if(empty($column_id))
		{
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}
		}
		else
		{
			$column_id = implode(',',$column_id);
		}

		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 		=> PUBLISH_SET_ID,
			'from_id' 		=> $info['id'],
			'class_id' 		=> 0,
			'column_id' 	=>  $column_id,
			'title' 		=> $info['title'],
			'action_type'	=> $op,
			'publish_time'	=> $info['pub_time'],
			'publish_people'=> $this->user['user_name'],
			'ip'=> hg_getip(),
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	
}

$out = new vod_video_drag();
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