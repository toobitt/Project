<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','layout');
class layoutApi extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/common.php');
		include(CUR_CONF_PATH . 'lib/layout.class.php');
		$this->layout = new layout();		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']  ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . ", " . $count;
		$ret = $this->layout->show($condition . $data_limit);
		$this->addItem($ret);
		$this->output();
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if($id){
			$data_limit = " AND id = " . $id;
		}
		else{
			$data_limit = " LIMIT 1";
		}
		$info = $this->layout->detail($data_limit);
		if ($info) {
			$info['indexpic'] = $info['indexpic'] ? unserialize($info['indexpic']) : array();
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$total = $this->layout->count($condition);
		echo json_encode($total);
	}	
	
	public function layout_node()
	{
		$sql = "SELECT l.*, ln.title  node_title 
				FROM " .DB_PREFIX. "layout l 
				LEFT JOIN ".DB_PREFIX."layout_node ln ON l.node_id = ln.id
				WHERE 1 AND status = 1 AND l.original_id = 0 ";
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['content'] = html_entity_decode($row['content']);
			$row['css'] = html_entity_decode($row['css']);
			$row['create_time_show'] = date('Y-m-d H:i', $row['create_time']);
			$row['update_time_show'] = date('Y-m-d H:i', $row['update_time']);
			$row['state'] = $this->settings['status_show'][$row['status']];	
			$row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : array();	
			!$row['node_title'] && $row['node_title'] = '无分类';	
			$ret[$row['node_title']][] = $row;
		}
		$this->addItem($ret);
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		$condition .= ' AND status = 1 ';
		return $condition;	
	}
	
	
	/**
	*  copy一份布局并返回布局的信息
	*/
	public function get_layout_preview()
	{
		if (!$this->input['layout_id']) {
			$this->errorOutput('LAYOUT OR TEMPLATE ID IS EMPTY');
		}
		$layout_id = intval($this->input['layout_id']);
		$template_id = intval($this->input['template_id']);
		
		//copy一份此布局里的单元
		$sql = "SELECT * FROM " .DB_PREFIX. "layout_cell WHERE layout_id = " . $layout_id;
		$q = $this->db->query($sql);
		$new_cell_ids = array();
		while ($row = $this->db->fetch_array($q)) {
			$new_cell_name = date('YmdHis') . hg_generate_user_salt(6);
			$find[] =  $row['cell_name'];
			$replace[] =  $new_cell_name;
			$row['cell_name'] = $new_cell_name;
			$row['cell_code'] = str_replace($row['cell_name'], $new_cell_name, $row['cell_code']);
			$row['original_id'] = $row['id'];
			$row['param_asso'] = addslashes($row['param_asso']);
			unset($row['id'], $row['layout_id']);   //unset掉layout_id防止更新操作不成功对原始布局产生有影响
			$new_cell_ids[] = $row['id'] = $this->db->insert_data($row, 'layout_cell');
            $row['param_asso'] = unserialize(stripslashes($row['param_asso']));
            $layout_cell[] = $row;
		}
		//布局详细信息
		$condition = ' AND id = ' . $layout_id;
		$layout_info = $this->layout->detail($condition);	
		$layout_info['content']  = 	html_entity_decode($layout_info['content']);
		$layout_info['css'] = html_entity_decode($layout_info['css']);
		//重新生成一份布局
		$layout_info['content']	 =  str_replace($find, $replace, $layout_info['content']);
		$layout_info['original_id'] = $layout_info['id'];
		unset($layout_info['id']);
		$layout_info['id'] = $this->db->insert_data($layout_info, 'layout');
		if ($new_cell_ids) {
			$this->db->update_data('layout_id=' . $layout_info['id'], 'layout_cell', ' id IN(' . implode(',', $new_cell_ids) . ')');
		}
		$layout_info['content'] = str_replace($find, $replace, $layout_info['content']);
		//布局命名空间和头部处理
		$layout_info = $this->layout->layout_namespace_and_header_process($layout_info);	
		$ret = array();
		//处理布局中单元的信息、生成html
		$return_cell = isset($this->input['return_cell']) ? $this->input['return_cell'] : true;
		if ($return_cell) {
            if (!class_exists('Magic')) {
                include (CUR_CONF_PATH . 'lib/magic.class.php');
            }
            $objMagic = new Magic();
            foreach($layout_cell as  $k => $v) {
                $layout_cell[$k] = $objMagic->cellProcess($v);
            }
            $ret['cells'] = $layout_cell;
            $ret = array_merge($ret, $layout_info);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	
	//在快速专题中 新增、删除、排序布局
	public function update_special_layout()
	{
		$special_id = intval($this->input['special_id']);
		$layout_ids = $this->input['layout_ids'];
		$new_layout_ids = explode(',', $layout_ids);
		if (!$special_id) {
			$this->errorOutput('SPECIAL_ID IS EMPTY');
		}
		$sql = "SELECT * FROM ".DB_PREFIX."template_layout WHERE special_id = " . $special_id;
		$info = $this->db->query_first($sql);
		$original_layout_ids = explode(',', $info['layout_ids']);
		//删除已经移除的布局和单元
		$del_layout_ids = array_diff($original_layout_ids, $new_layout_ids);
		$del_layout_ids = implode(',', $del_layout_ids);
		if ($del_layout_ids) {
			$sql = 'DELETE FROM ' . DB_PREFIX . 'layout WHERE id IN('.$del_layout_ids.')';
			$this->db->query($sql);
			$sql = 'DELETE FROM ' . DB_PREFIX . 'layout_cell WHERE layout_id IN('.$del_layout_ids.')';
			$this->db->query($sql);
		}
		if ($info['id']) {		
			$this->db->update_data("layout_ids='".$layout_ids."'", 'template_layout', ' special_id = ' . $special_id);
		}
		else {
			$tmp_array = array(
				'special_id' => $special_id,
				'layout_ids' => $layout_ids,
			);
			$this->db->insert_data($tmp_array,'template_layout');
		}
		$this->addItem(true);
		$this->output();
	}

	public function test()
	{
		$sql = 'select layout_ids from '.DB_PREFIX.'template_layout';
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q)){
			$ret[] =$row['layout_ids'];
		}
		$ret = implode(',',$ret);
		//echo $ret;exit;
		$sql = "delete from " .DB_PREFIX."layout where id not in(".$ret.") and original_id != 0 ";
		//echo $sql;exit;
		$sql = "delete from ".DB_PREFIX."layout_cell where layout_id not in(".$ret.") and original_id != 0";
		echo $sql;exit;
	}
}
$out = new layoutApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
