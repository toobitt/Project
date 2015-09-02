<?php
require('global.php');
define('MOD_UNIQUEID','layout_node');//模块标识
class layoutNodeUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{	
		//获取类型
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填写布局分类名称");
		}
		$sql = "SELECT id FROM " . DB_PREFIX . "layout_node WHERE title = '".$title ."'";
		$q= $this->db->query_first($sql);
		if($q)
		{
			$this->errorOutput("布局分类已存在");
		}
		$data = array(
			'title'			=> trim(urldecode($this->input['title'])),
            'user_id'		=> $this->user['user_id'],
            'user_name'		=> $this->user['user_name'],
            'create_time'	=> TIMENOW,
            'update_time'	=> TIMENOW,
		);
		$ret = $this->db->insert_data($data,'layout_node');
		$this->addItem($ret);
		$this->output();
	}
	
	function update()
	{	
		$id = intval($this->input['id']);
		if ( !$id ) 
		{
			$this->errorOutput('ID不能为空');
		}
		$data = array(
			'title'			=> urldecode($this->input['title']),
		);
		$condition = " id = " . $id;
		$ret = $this->db->update_data($data,'layout_node', $condition);
		$this->addItem($ret);
		$this->output();
	}
	
	function delete()
	{			
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput("选择要删除的分类");
		}
		$sql = "DELETE FROM " . DB_PREFIX . "layout_node WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		$this->addItem($id);
		$this->output();
	}
	
	function audit() {}
	function sort() {}
	function publish() {}
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
	
}

$out = new layoutNodeUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>