<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: topic.php 2843 2011-03-16 09:21:25Z chengqing $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class topicApi extends adminBase
{

	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}


	public function getTopic()
	{
		
		$sql = "SELECT * FROM ".DB_PREFIX."topic order by relate_count desc limit 0,8";
		$query = $this->db->query($sql);		
		
		while($array = $this->db->fetch_array($query))
		{
			$topic[] = $array;
		}
		$this->addItem($topic);
		$this->output();
	}
	
	public function delTopic()
	{
		$id = urldecode($this->input['id']);
		$sql = "DELETE FROM ".DB_PREFIX."topic WHERE id IN({$id})";//删除操作
		$result =  $this->db->query($sql);
		if($result)
		{
			echo 1;//操作成功
		}else {
			echo 2;//操作失败
		}
	}
	
	public function verifyTopic()
	{
		$id = urldecode($this->input['id']);
		$type = $this->input['type'];
		$sql = "UPDATE " . DB_PREFIX . "topic SET status=".$type." WHERE id IN(".$id.")";//更新审核状态
		$result =  $this->db->query($sql);
		if($result)
		{
			echo 1;//操作成功
		}else {
			echo 2;//操作失败
		}
	}
	
	/**
	 *后台搜索话题
	 */
	public function searchTopic()
	{
		$search_condition = unserialize(urldecode($this->input['search_condition']));
		
		$start_time = @mktime($search_condition['start_time']);
		$end_time = @mktime($search_condition['end_time']);
		$keywords = urldecode($search_condition['keywords']);
		$status = $search_condition['status'];
		
		$perpage = $this->input['perpage'];
		$curpage = intval($this->input['curpage']);
		
		$AND = ""; 
		if(!empty($keywords) )
		{
			$AND .= "AND t.title LIKE '%{$keywords}%' ";
		}
		if( !empty($start_time))
		{
			$AND .= " AND tm.create_time > $start_time ";
		}
		if(!empty($end_time))
		{
			$AND .= " AND tm.create_time < $end_time ";
		}
		
		if($status != 4)
		{
			$AND .= "AND  status=$status";
		}
		
		$sql_total = "SELECT count(*) as total FROM " .DB_PREFIX. "topic AS t LEFT JOIN " .DB_PREFIX. "topic_member AS tm ON t.id=tm.topic_id  WHERE 1 ".$AND;
		$total_query = $this->db->query($sql_total);
		$total = $this->db->fetch_array($total_query);
		$this->addItem($total);
		
		$LIMIT = " LIMIT $curpage,$perpage";
		$sql = "SELECT t.*,tm.create_time as total FROM " .DB_PREFIX. "topic AS t LEFT JOIN " .DB_PREFIX. "topic_member AS tm ON t.id=tm.topic_id  WHERE 1 " .$AND . $LIMIT;
		$q = $this->db->query($sql);
		
		$search_info = array();
		while($rows = $this->db->fetch_array($q))
		{
			$search_info[] = $rows;
		}
		
		foreach ($search_info as $key=>$value) {
			$search_info[$key]['create_time'] = date("Y-n-d",$value['create_time']);
		}
		
		$this->addItem($search_info);
		$this->output();
	}
	
	/**
	 * 编辑话题
	 */
	public function editTopic()
	{
		$title = urldecode($this->input['title']);
		$id = $this->input['topicId'];
		$sql = "UPDATE " .DB_PREFIX. "topic SET title='$title' WHERE id='$id'";
		$result = $this->db->query($sql);
		if($result)
		{
			$flog = 1;//编辑成功
		}
		else
		{
			$flog = 2;//编辑失败
		}
		
		echo $flog;
	}
}
$out = new topicApi();
$action = $_POST['a'];
if(!method_exists($out, $action))
{
	$action = 'getTopic';
}
$out->$action();
?>