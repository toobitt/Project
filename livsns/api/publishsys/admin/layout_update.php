<?php
require('global.php');
define('MOD_UNIQUEID','layout');
class layoutUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();	
		include(CUR_CONF_PATH . 'lib/common.php');
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('layout',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		if (!$this->input['title']) {
			$this->errorOutput('标题不能为空');
		}
		$data = array(
			'node_id'	    => $this->input['node_id'],	
			'title'  		=> $this->input['title'],
			'content'   	=> $this->input['content'],
			'css'    		=> $this->input['css'],
			'sign'			=> uniqid(),
			'create_time'	=> TIMENOW,
			'update_time'   => TIMENOW,
			'user_id'       => $this->user['user_id'],
			'user_name'     => $this->user['user_name'],
			'indexpic'		=> $this->input['indexpic'] ? addslashes(serialize(json_decode(urldecode($this->input['indexpic']),1))) : '',
		);
		#####处理单元#######	
		$cell_id = $this->cell_process($data['content']);	
		#####处理单元#######		
		$layout_id = $this->db->insert_data($data, 'layout');
		$sql = "UPDATE ".DB_PREFIX."layout_cell SET layout_id = " . $layout_id . " WHERE id IN(" . $cell_id . ")";
		$this->db->query($sql);
		
		$data['id'] = $layout_id;
		$this->addLogs('新增布局' , '' , $data , $data['title']);
		
		$this->addItem($data);
		$this->output();	
	}
	
	function update()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('layout',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$id = intval($this->input['id']);
		if (!$id) {
			$this->errorOutput('Id is empty!');
		}
		if (!$this->input['title']) {
			$this->errorOutput('标题不能为空');
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "layout WHERE id = " . $id;
		$info = $this->db->query_first($sql);
		$data = array(
			'node_id'	    	=> $this->input['node_id'],	
			'title'				=> $this->input['title'],
			'content' 			=> $this->input['content'],
			'css'           	=> $this->input['css'],
			'indexpic'			=> $this->input['indexpic'] ? addslashes(serialize(json_decode(urldecode($this->input['indexpic']),1))) : '',
		);
		
		#####处理单元#######	
		$this->cell_process($data['content'], $id);	
		#####处理单元#######
		
		####修改布局####
		$condition = " id = " . $id;
		$this->db->update_data($data, 'layout', $condition);		
		if ($this->db->affected_rows()) {
			$arr = array(
				'update_time'		=> TIMENOW,
			);
			$this->db->update_data($arr, 'layout', $condition);
			
			$sq =  "SELECT * FROM " . DB_PREFIX . "layout WHERE id = " . $id;
			$up_data = $this->db->query_first($sq);
			
			$this->addLogs('更新布局' , $info , $up_data , $info['title']);
		
		}			
		$this->addItem($data);
		$this->output();
	}
	
	function cell_process($content, $layout_id = '')
	{
		#####解析单元开始#######
		$cells_info = common::parse_cell(html_entity_decode($content));
		$cells = $cells_info[1];
		if (is_array($cells) && count($cells) > 0) {
			foreach ($cells as $k => $v) {
				if ($k > 0) {
					if ($cells[0] == $v) {
						$this->errorOutput('单元名称不能重复');
					}
				}
			}	
		}
		$exist_cells = $layout_id ? $this->get_exist_cell($layout_id) : array();
		$celladding = array_diff($cells,$exist_cells); //将要增加的单元
		$celldeling = array_diff($exist_cells,$cells); //将要删除的单元
		$insert_id = array();
		if (is_array($celladding) && count($celladding) > 0) {
			foreach ($celladding as $k=>$v) {
				$add_cell = array(
					'layout_id'       => $layout_id,
					'cell_name'		  => $v,
					'cell_code'       => $cells_info[0][$k],
					'create_time'	  => TIMENOW,
					'update_time'	  => TIMENOW,
					'sign'			  => uniqid(),
					'user_id'		  => $this->user['user_id'],
					'user_name'		  => $this->user['user_name'],
					'appid'			  => $this->user['appid'],
					'appname'		  => $this->user['display_name'],	
				) ;
				$insert_id[] = $this->db->insert_data($add_cell,'layout_cell');
			}
		}
		$celldeling = implode("','", $celldeling);
		if ($celldeling) {
			$sql = "DELETE FROM ".DB_PREFIX."layout_cell WHERE cell_name IN('".$celldeling."') AND layout_id = " . $layout_id;
			$this->db->query($sql);
		}	
		return implode(',', $insert_id);
		#####解析单元开始#######		
	}
	
	function delete()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('layout',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$ids = urldecode($this->input['id']);
		if(!$ids){
			$this->errorOutput('请选择要删除的内容');
		}
		
		$sqll =  "SELECT * FROM " . DB_PREFIX . "layout WHERE id IN (" . $ids . ")";
		$sll = $this->db->query($sqll);
		$ret = array();
		while($rowl = $this->db->fetch_array($sll))
		{
			$pre_data[] = $rowl;
		}
		
		$sql = "DELETE FROM ".DB_PREFIX."layout WHERE id IN(" . $ids . ")";
		$this->db->query($sql);
		$sql = "DELETE FROM ".DB_PREFIX."layout_cell WHERE layout_id IN(" . $ids . ")";
		$this->db->query($sql);
		
		$this->addLogs('删除布局' , $pre_data , '', '删除布局'.$ids);
		
		$this->addItem($ids);
		$this->output();
	}
	
	function audit()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput('未传入ID');
		}
		$idArr = explode(',', $ids);
		if ( intval($this->input['audit']) == 1 ) 	//审核
		{
			$sql = "UPDATE ".DB_PREFIX."layout SET status = 1 WHERE id IN(" . $ids . ")";
			$this->db->query($sql);
			$return = array('status' => 1, 'id' => $idArr);
		}
		else if ( intval($this->input['audit']) == 0 )	//打回
		{
			$sql = "UPDATE ".DB_PREFIX."layout SET status = 2 WHERE id IN(" . $ids . ")";
			$this->db->query($sql);
			$return = array('status'  => 2, 'id' => $idArr);
		} 
		$this->addItem($return);
		$this->output();		
	}
	
	/**
	 * 获取布局中已经存在的单元
	 */
	function get_exist_cell($layout_id)
	{
		if (!$layout_id) {
			return array();
		}
		$sql = "SELECT DISTINCT cell_name FROM ".DB_PREFIX."layout_cell WHERE layout_id =" . intval($layout_id);
		$q = $this->db->query($sql);
		$ret = array();
		while ($row = $this->db->fetch_array($q)) {
			$ret[] = $row['cell_name'];
		}
		return $ret;		
	}
	
	function upload()
	{
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();		
		$material = $this->mater->addMaterial($_FILES);
		$ret = array();
		if (!$material) {
			$ret = array('errno' => '', 'errmsg' => '文件上传失败');
		}
		else {
			$ret = array(
				'id'		=> $material['id'],
				'host'		=> $material['host'],
				'dir'		=> $material['dir'],
				'filepath'	=> $material['filepath'],
				'filename'	=> $material['filename'],
			);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	function sort(){}
	function publish(){}
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new layoutUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>