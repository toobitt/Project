<?php
require_once('global.php');
define('MOD_UNIQUEID', 'consumption');
define('SCRIPT_NAME', 'consumption');
require_once(CUR_CONF_PATH . 'lib/curd.class.php');
require_once(CUR_CONF_PATH . 'lib/template.class.php');
class consumption extends adminBase
{
	private $curd = null;
	private $template = null;
	public function __construct()
	{
		parent::__construct();
		$this->curd = new curd('member_order');
		$this->template = new template();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function show(){
		$template_id = $this->input['template_id'];
		if(!$template_id)
		{
			$this->errorOutput("未知的模板");
		}
		$data = $this->curd->show('*',$this->get_conditions());
		$output = array('order'=>array(),'template_info'=>array());
		
		if($data)
		{
			$output['order'] = $data;
		}
		$template_info = $this->template->get_template_info_by_id($template_id);
		if($template_info)
		{
			$output['template_info'] = $template_info;
		}
		$this->addItem($output);
		$this->output();
		
	}
	public function detail()
	{
		$id = $this->input['id'];
		$data = array();
		if($id)
		{
			$data = $this->curd->detail($id);
		}
		$this->addItem($data);
		$this->output();
	}
	public function count()
	{
		$total = $this->curd->count($this->get_conditions());
		exit(json_encode($total));
	}
	public function get_conditions()
	{
		$conditions = '';
		if($this->input['template_id'])
		{
			$conditions .= ' AND template_id = "'.urldecode($this->input['template_id']).'"';
		}
		return $conditions;
	}
	
}
include ROOT_PATH . 'excute.php';
?>