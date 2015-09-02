<?php
require('global.php');
define('MOD_UNIQUEID','publishsys_content_detail');//模块标识
class content_detailApi extends adminBase
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/content.class.php');
		$this->obj = new content();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		 $cid = intval($this->input['cid']);
		 $fieldid = intval($this->input['fieldid']);
		 $expandid = intval($this->input['expandid']);
		 $offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		 $count = $this->input['count']?intval(urldecode($this->input['count'])):20;
		 if($fieldid)
		 {
		 	$content = $this->obj->get_field_by_id($fieldid);
		 	if(empty($content))
		 	{
		 		$this->errorOutput("没有相关内容");
		 	}
		 	$field = $content;
		 	$tablename = get_tablename($content['bundle_id'],$content['module_id'],$content['struct_id'],$content['struct_ast_id']);
			
			//查询出创建的主扩展表内容
		 	$expand = $this->obj->get_expand_by_expand_id($tablename,$expandid,$offset,$count);
		 }
		 else
		 {
		 	$content = $this->obj->get_content_by_id(' * ',$cid);
		 	$tablename = get_tablename($content['bundle_id'],$content['module_id'],$content['struct_id']);
			
			if(!$tablename)
		 	{
			 	$this->errorOutput("没有相关表");
		 	}
			
		 	$field = $this->obj->get_field($content['bundle_id'],$content['module_id'],$content['struct_id']);
		 	
		 	//查询出创建的主扩展表内容
		 	$expand = $this->obj->get_expand($tablename,$content['expand_id'],$offset,$count);
		 	
		 }
		 
		 //查询出对应表中需要显示的字段
		 $show_field = unserialize($field['show_field']);
		 
		 //查询出此扩展表的字表的table_title
		 $child_tablearr = explode(',',$field['child_table']);
		 if(empty($child_tablearr))
		 {
			 $child_data = array();
		 }
		 else
		 {
			 foreach($child_tablearr as $k=>$v)
			 {
				 if(!empty($v))
				 {
					 $child_field = $this->obj->get_field($content['bundle_id'],$content['module_id'],$content['struct_id'],$v);
					 $child_data[] = array('id'=>$child_field['id'],'title'=>$child_field['table_title']);
				 }
			 }
		 }
		  
		 
		 $alldata['show_field'] = $show_field;
		 $alldata['expand'] = $expand;
		 $alldata['child_data'] = $child_data;
		 $this->addItem($alldata);
		 $this->output();
		 
//		 print_r($show_field[0]->field);
		
	}
	
	public function count()
	{
		 $cid = intval($this->input['cid']);
		 $fieldid = intval($this->input['fieldid']);
		 $expandid = intval($this->input['expandid']);
		 $offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		 $count = $this->input['count']?intval(urldecode($this->input['count'])):20;
		 if($fieldid)
		 {
		 	$content = $this->obj->get_field_by_id($fieldid);
		 	if(empty($content))
		 	{
		 		$this->errorOutput("没有相关内容");
		 	}
		 	$field = $content;
		 	$tablename = get_tablename($content['bundle_id'],$content['module_id'],$content['struct_id'],$content['struct_ast_id']);
			
			//查询出创建的主扩展表内容
		 	$expand = $this->obj->get_expand_by_expand_id($tablename,$expandid,$offset,$count);
		 	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX .$tablename." WHERE expand_id in (".$expandid.")";
		 }
		 else
		 {
		 	$content = $this->obj->get_content_by_id(' * ',$cid);
		 	$tablename = get_tablename($content['bundle_id'],$content['module_id'],$content['struct_id']);
			
			if(!$tablename)
		 	{
			 	$this->errorOutput("没有相关表");
		 	}
			
		 	$field = $this->obj->get_field($content['bundle_id'],$content['module_id'],$content['struct_id']);
		 	
		 	//查询出创建的主扩展表内容
		 	$expand = $this->obj->get_expand($tablename,$content['expand_id'],$offset,$count);
		 	$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX .$tablename." WHERE id=".$content['expand_id'];
		 }
		
		echo json_encode($this->db->query_first($sql));
	}
	
	private function get_condition()
	{
		$condition = ' ';
		return $condition;		
	}
	

	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright 	ho	gesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new content_detailApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
