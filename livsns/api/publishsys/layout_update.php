<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','layout');
class layoutApiUpdate extends adminBase
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
	
	public function unknow()
	{
		$this->errorOutput('方法不存在!');
	}
	
	public function update_layout_title()
	{
		$layout_id = intval($this->input['layout_id']);
		if (!$layout_id) {
			$this->errorOutput('NO ID');
		}
		$condition = " id = " . $layout_id;
		$sql = "SELECT id, is_header, header_text, is_more, more_href 
				FROM ".DB_PREFIX."layout WHERE " . $condition;
		$layout_info = $this->db->query_first($sql);
		if (!$layout_info) {
			$this->errorOutput('LAYOUT IS NOT EXISTS');
		}
		$data = array(
			'is_header' 	=> $this->input['is_header'],
			'header_text'	=> $this->input['header_text'],
			'is_more'		=> $this->input['is_more'],
			'more_href'		=> $this->input['more_href'],
		);		
		$this->db->update_data($data, 'layout', $condition);
		$layout_info = array_merge($layout_info, $data);
		$layout_info = $this->layout->layout_namespace_and_header_process($layout_info);
		$header = $layout_info['header'];
		$this->addItem($header);
		$this->output();
	}
	
}	
$out = new layoutApiUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
